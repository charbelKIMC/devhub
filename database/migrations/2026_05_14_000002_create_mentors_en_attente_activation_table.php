<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mentors_en_attente_activation', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->string('email')->unique();
            $table->text('donnees');   // JSON chiffré avec les infos du mentor
            $table->timestamp('expire_a');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mentors_en_attente_activation');
    }
};
