<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dashboard_view', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->index();
            $table->uuid('check_in_id')->unique();
            $table->timestamp('checked_in_at');
            $table->text('quote')->nullable();
            $table->string('author')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_view');
    }
};
