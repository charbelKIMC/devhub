<?php

namespace App\Console\Commands;

use App\Models\Publication;
use App\Models\Like;
use App\Models\Commentaire;
use Illuminate\Console\Command;

class RecalculerCompteurs extends Command
{
    protected $signature   = 'devhub:recalculer';
    protected $description = 'Recalcule les compteurs likes et commentaires';

    public function handle()
    {
        $publications = Publication::all();
        $bar = $this->output->createProgressBar($publications->count());

        foreach ($publications as $pub) {
            $pub->update([
                'nb_likes'       => Like::where('publication_id', $pub->id)->count(),
                'nb_commentaires'=> Commentaire::where('publication_id', $pub->id)->count(),
            ]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Compteurs recalculés avec succès.');
    }
}