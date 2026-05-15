<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE `notifications`
            MODIFY COLUMN `type` ENUM(
                'bienvenue',
                'connexion',
                'nouveau_like',
                'nouveau_message',
                'nouvel_avis',
                'nouveau_commentaire',
                'nouvelle_candidature',
                'candidature_acceptee',
                'candidature_refusee',
                'demande_mentor',
                'mentor_valide',
                'mentor_refuse',
                'mentor_attribue',
                'profil_incomplet',
                'projet_termine'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE `notifications`
            MODIFY COLUMN `type` ENUM(
                'bienvenue',
                'connexion',
                'nouveau_like',
                'nouveau_message',
                'nouvel_avis',
                'nouveau_commentaire',
                'nouvelle_candidature',
                'candidature_acceptee',
                'candidature_refusee',
                'demande_mentor',
                'mentor_valide',
                'mentor_refuse'
            ) NOT NULL
        ");
    }
};
