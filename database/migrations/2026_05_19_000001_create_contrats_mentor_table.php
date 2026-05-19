<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contrats_mentor', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('mentor_id');
            $table->string('chemin_pdf')->nullable();
            $table->string('ip_signature', 45)->nullable();
            $table->timestamp('signe_le')->nullable();
            $table->enum('statut', ['en_attente', 'signé'])->default('en_attente');
            $table->timestamps();

            $table->foreign('mentor_id')->references('id')->on('utilisateurs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrats_mentor');
    }
};
