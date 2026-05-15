<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            if (!$this->hasIndex('publications', 'publications_statut_date_publication_index')) {
                $table->index(['statut', 'date_publication'], 'publications_statut_date_publication_index');
            }
            if (!$this->hasIndex('publications', 'publications_auteur_id_index')) {
                $table->index('auteur_id', 'publications_auteur_id_index');
            }
            if (!$this->hasIndex('publications', 'publications_type_index')) {
                $table->index('type', 'publications_type_index');
            }
        });

        Schema::table('candidatures', function (Blueprint $table) {
            if (!$this->hasIndex('candidatures', 'candidatures_utilisateur_id_statut_index')) {
                $table->index(['utilisateur_id', 'statut'], 'candidatures_utilisateur_id_statut_index');
            }
            if (!$this->hasIndex('candidatures', 'candidatures_projet_collab_statut_index')) {
                $table->index(['projet_collaboratif_id', 'statut'], 'candidatures_projet_collab_statut_index');
            }
        });

        Schema::table('membres_projet', function (Blueprint $table) {
            if (!$this->hasIndex('membres_projet', 'membres_projet_utilisateur_id_index')) {
                $table->index('utilisateur_id', 'membres_projet_utilisateur_id_index');
            }
        });

        Schema::table('messages', function (Blueprint $table) {
            if (!$this->hasIndex('messages', 'messages_conversation_id_date_envoi_index')) {
                $table->index(['conversation_id', 'date_envoi'], 'messages_conversation_id_date_envoi_index');
            }
            if (!$this->hasIndex('messages', 'messages_expediteur_id_est_lu_index')) {
                $table->index(['expediteur_id', 'est_lu'], 'messages_expediteur_id_est_lu_index');
            }
        });

        Schema::table('conversations', function (Blueprint $table) {
            if (!$this->hasIndex('conversations', 'conversations_dernier_message_date_index')) {
                $table->index('dernier_message_date', 'conversations_dernier_message_date_index');
            }
        });

        Schema::table('projets_collaboratifs', function (Blueprint $table) {
            if (!$this->hasIndex('projets_collaboratifs', 'projets_collaboratifs_phase_index')) {
                $table->index('phase', 'projets_collaboratifs_phase_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->dropIndexIfExists('publications_statut_date_publication_index');
            $table->dropIndexIfExists('publications_auteur_id_index');
            $table->dropIndexIfExists('publications_type_index');
        });
        Schema::table('candidatures', function (Blueprint $table) {
            $table->dropIndexIfExists('candidatures_utilisateur_id_statut_index');
            $table->dropIndexIfExists('candidatures_projet_collab_statut_index');
        });
        Schema::table('membres_projet', function (Blueprint $table) {
            $table->dropIndexIfExists('membres_projet_utilisateur_id_index');
        });
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndexIfExists('messages_conversation_id_date_envoi_index');
            $table->dropIndexIfExists('messages_expediteur_id_est_lu_index');
        });
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropIndexIfExists('conversations_dernier_message_date_index');
        });
        Schema::table('projets_collaboratifs', function (Blueprint $table) {
            $table->dropIndexIfExists('projets_collaboratifs_phase_index');
        });
    }

    private function hasIndex(string $table, string $index): bool
    {
        return collect(DB::select(
            "SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index]
        ))->isNotEmpty();
    }
};
