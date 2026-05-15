<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidatures_mentor_externe', function (Blueprint $table) {
            $table->text('references')->nullable()->after('motivation');
        });
    }

    public function down(): void
    {
        Schema::table('candidatures_mentor_externe', function (Blueprint $table) {
            $table->dropColumn('references');
        });
    }
};
