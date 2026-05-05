# Fitmewise — Prueba Técnica (Monolito Modular)

Este proyecto implementa un sistema de gestión basado en:

- Monolito modular
- Domain-Driven Design (DDD) (parcial)
- Event-Driven Architecture
- CQRS
- Outbox Pattern

---

## 🚀 Requisitos

- Docker
- Docker Compose

---

## ⚙️ Instalación

### 1. Clonar repositorio

```bash
git clone <repo-url>
cd <repo>
```

### 2. Crear archivo de entorno

```bash
cp .env.example .env
```

### 3. Levantar contenedores

```bash
docker-compose up -d --build
```

### 4. Instalar dependencias

```bash
docker exec -it fitmewise_app composer install
```

### 5. Generar APP_KEY

```bash
docker exec -it fitmewise_app php artisan key:generate
```

### 6. Ejecutar migraciones

```bash
docker exec -it fitmewise_app php artisan migrate
```

---

## 🧪 Ejecución

### Iniciar worker de colas

```bash
docker exec -it fitmewise_app php artisan queue:work
```

### Publicar eventos (Outbox)

```bash
docker exec -it fitmewise_app php artisan outbox:publish
```

---

## 🔥 Flujo principal

### 1. Registrar check-in

```bash
curl -X POST http://localhost:8080/api/check-in \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": "user_1"
  }'
```

### 2. Publicar eventos

```bash
docker exec -it fitmewise_app php artisan outbox:publish
```

### 3. Procesar colas

```bash
docker exec -it fitmewise_app php artisan queue:work
```

### 4. Consultar dashboard

```bash
curl http://localhost:8080/api/dashboard/user_1
```

---

## 🧠 Flujo del sistema

Check-in (sync)
   ↓
Outbox
   ↓
Queue
   ↓
AssignMotivationalQuote
   ↓
QuoteAssigned
   ↓
UpdateDashboardProjection

---

## 📦 Estructura del proyecto

app/
 ├── AccessControl/
 ├── Engagement/
 ├── Shared/
 ├── Http/

---

## ⚠️ Notas

- El archivo `.env` no se incluye en el repositorio
- Debe generarse a partir de `.env.example`
- Redis se usa como broker de colas
- La API externa de frases puede fallar, pero el sistema utiliza fallback

---

## 🚀 Consideraciones

El sistema está diseñado para:

- No bloquear el check-in ante fallos externos
- Soportar reintentos de jobs
- Mantener consistencia eventual
- Evitar duplicados mediante idempotencia

---

## 📄 Documentación

Ver archivo:

ARCHITECTURE.md
