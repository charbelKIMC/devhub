<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contrats_nda', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('candidature_id');
            $table->integer('utilisateur_id');
            $table->integer('publication_id');
            $table->string('token', 64)->unique();
            $table->string('chemin_pdf')->nullable();
            $table->string('ip_signature', 45)->nullable();
            $table->timestamp('signe_le')->nullable();
            $table->enum('statut', ['en_attente', 'signé'])->default('en_attente');
            $table->timestamps();

            $table->foreign('candidature_id')->references('id')->on('candidatures')->onDelete('cascade');
            $table->foreign('utilisateur_id')->references('id')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('publication_id')->references('id')->on('publications')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrats_nda');
    }
};
