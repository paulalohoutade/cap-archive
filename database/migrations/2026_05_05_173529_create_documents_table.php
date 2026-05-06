<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration : Table documents enrichie
 * 
 * On garde la structure de base et on ajoute des champs
 * utiles pour l'affichage dans le dashboard.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            // Nom original du fichier (affiché dans l'UI)
            $table->string('nom_fichier');

            // Chemin relatif dans le storage Laravel (storage/app/public/...)
            $table->string('chemin_stockage');

            // Source : 'upload-manuel', 'pipeline', 'import-batch', etc.
            $table->string('source')->default('upload-manuel');

            $table->timestamps(); // created_at + updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
