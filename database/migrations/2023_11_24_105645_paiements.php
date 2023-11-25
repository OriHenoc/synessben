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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->integer('montantApayer');
            $table->integer('montantPaye');
            $table->date('datePaiement');
            $table->text('commentaire')->nullable();
            $table->integer('montantRestant');
            $table->string('recu')->nullable();
            $table->string('qrCode')->nullable();
            $table->foreignId('etudiantID')->constrained('etudiants');
            $table->foreignId('createdBy')->constrained('etudiants');
            $table->foreignId('updatedBy')->constrained('etudiants');
            $table->boolean('active')->default(true);
            $table->timestamps();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
