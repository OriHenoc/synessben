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
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('nom');
            $table->string('prenoms');
            $table->date('datenais');
            $table->string('niveau');
            $table->string('numCarteEtud')->unique();
            $table->string('email')->unique();
            $table->string('telephone')->unique();
            $table->boolean('access')->default(false);
            $table->string('username')->unique()->nullable();
            $table->string('passwd')->nullable();
            $table->foreignId('roleID')->constrained('roles');
            $table->boolean('active')->default(true);
            $table->timestamps();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};
