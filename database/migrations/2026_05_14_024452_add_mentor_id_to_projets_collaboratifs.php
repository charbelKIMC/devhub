<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projets_collaboratifs', function (Blueprint $table) {
            $table->integer('mentor_id')->nullable()->after('phase');
            $table->foreign('mentor_id')->references('id')->on('utilisateurs')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('projets_collaboratifs', function (Blueprint $table) {
            $table->dropForeign('projets_collaboratifs_mentor_id_foreign');
            $table->dropColumn('mentor_id');
        });
    }
};
