<?php

use App\Enums\MovieType;
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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hall_id')->constrained('halls')->cascadeOnDelete();
            $table->string('name');
            $table->string('description');
            $table->string('thumbnail');
            $table->float('standard_price');
            $table->float('vip_price');
            $table->enum('type', MovieType::values());
            $table->foreignId('schedule_id')->constrained('schedules');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
