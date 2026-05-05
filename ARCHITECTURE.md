# ARCHITECTURE.md

## 🧠 Visión General

El sistema se implementa como un **monolito modular en Laravel**, separando dos dominios principales:

* **AccessControl**: registro de check-ins (operación crítica, síncrona)
* **Engagement**: asignación de frases motivacionales (proceso asíncrono)

Se aplican patrones de arquitectura como:

* Domain-Driven Design (DDD) (adaptada a Laravel)
* Event-Driven Architecture
* CQRS (separación de lectura y escritura)
* Outbox Pattern
* Anti-Corruption Layer (ACL)

---

## 🧩 Bounded Contexts

### 🔹 AccessControl

Responsabilidades:

* Registrar check-ins
* Emitir evento de dominio `UserCheckedIn`

Persistencia:

* Tabla `check_ins`

---

### 🔹 Engagement

Responsabilidades:

* Obtener frases motivacionales desde un servicio externo
* Persistir frases asociadas a check-ins
* Proyectar datos hacia el modelo de lectura

Elementos:

* `Quote` (Value Object)
* Evento `QuoteAssigned`

Persistencia:

* Tabla `engagement_quotes`
* Tabla `dashboard_view` (modelo de lectura)

---

### 🔹 Shared

Responsabilidades:

* Infraestructura compartida

Componentes:

* Tabla `outbox_events`
* Job `GenericEventJob`
* Uso de colas (Redis)

---

## 🔄 Comunicación entre módulos

Los módulos se comunican exclusivamente mediante eventos:

```text
UserCheckedIn → AssignMotivationalQuote
UserCheckedIn → CreateDashboardProjection
QuoteAssigned → UpdateDashboardProjection
```

No existe acceso directo entre repositorios de distintos dominios.

---

## 📦 Patrón Outbox

El registro de check-in y la persistencia del evento se realizan dentro de una misma transacción.

Posteriormente, un proceso asíncrono publica los eventos almacenados en la tabla `outbox_events` hacia la cola.

Esto evita inconsistencias como:

* Check-in guardado sin evento emitido

---

## 🔌 Anti-Corruption Layer (ACL)

Se define un puerto en dominio:

```php
QuoteServicePort
```

Y una implementación en infraestructura:

```php
HttpQuoteService
```

Responsabilidades del adapter:

* Consumir la API externa
* Manejar errores (timeouts, fallos)
* Mapear la respuesta a un Value Object (`Quote`)

El dominio no depende directamente de HTTP ni de estructuras JSON externas.

---

## ⚡ Resiliencia

El sistema está diseñado para que el proceso crítico (check-in) no dependa de servicios externos.

Estrategias implementadas:

* Ejecución asíncrona de la lógica de Engagement
* Uso de timeouts en llamadas HTTP
* Manejo de excepciones en el adapter
* Uso de valores fallback en caso de fallo

Escenarios considerados:

* API externa caída
* Latencia alta
* Respuestas inesperadas

En todos los casos, el check-in se registra correctamente y el sistema continúa operando.

---

## 🔁 Manejo de colas y reintentos

Los jobs utilizan la infraestructura de colas de Laravel.

Se configuraron:

* Número de intentos (`tries`)
* Tiempo entre reintentos (`backoff`)

Se utiliza la tabla:

* `failed_jobs`

Esto permite:

* Reintentos automáticos
* Reprocesamiento manual de jobs fallidos

No se implementaron estrategias avanzadas como DLQ explícita o políticas de reintento dinámicas.

---

## 🔁 Idempotencia

Las proyecciones utilizan:

```php
updateOrInsert
```

Con clave única:

```text
check_in_id
```

Esto permite:

* Evitar duplicados en caso de reintentos
* Soportar reprocesamiento de eventos

Las proyecciones son tolerantes a ejecuciones múltiples del mismo evento.

---

## 📊 CQRS

Se separan los modelos de escritura y lectura:

### Write Model

* `check_ins`
* `engagement_quotes`

### Read Model

* `dashboard_view`

El endpoint del dashboard consulta directamente el read model, evitando joins en tiempo de ejecución.

---

## 🔄 Proyecciones

Las proyecciones se ejecutan mediante listeners de eventos:

* `CreateDashboardProjection`
* `AssignMotivationalQuote`
* `UpdateDashboardProjection`

Se diseñaron para ser:

* Idempotentes
* Tolerantes a reintentos
* Parcialmente tolerantes al desorden de eventos (mediante `updateOrInsert`)

No se implementa versionado de eventos ni control estricto de orden.

---

## 🧠 Eventos

Los eventos transportan los datos necesarios para procesar cada caso de uso.

Ejemplo:

```text
QuoteAssigned:
- checkInId
- userId
- quote
- author
- occurredAt
```

Esto permite que las proyecciones no dependan estrictamente de otros procesos previos, aunque comparten la misma base de datos.

---

## 🌐 Endpoint de lectura

El endpoint del dashboard sigue una estructura tipo CQRS:

```text
Controller → Query → Handler → DB
```

El controller actúa como adaptador HTTP, delegando la lógica al handler.

---

## 🧱 Estructura del proyecto

```text
app/
 ├── AccessControl/
 ├── Engagement/
 ├── Shared/
 ├── Http/
```

Se organiza principalmente por dominio, manteniendo elementos propios de Laravel para la capa de entrega (controllers, rutas).

---

## 🎯 Decisiones clave

| Decisión       | Motivo                  |
| -------------- | ----------------------- |
| Uso de eventos | desacoplar módulos      |
| Outbox         | garantizar consistencia |
| CQRS           | optimizar lecturas      |
| ACL            | aislar API externa      |
| Idempotencia   | tolerar reintentos      |
