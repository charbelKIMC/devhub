<?php

namespace App\Console\Commands;

use App\Jobs\AttribuerMentorJob;
use Illuminate\Console\Command;

class AttribuerMentorsManquants extends Command
{
    protected $signature   = 'mentors:attribuer-manquants';
    protected $description = 'Attribue automatiquement un mentor aux projets collaboratifs qui n\'en ont pas encore.';

    public function handle(): void
    {
        $this->info('Attribution des mentors manquants…');
        AttribuerMentorJob::tenterAttributionAutomatique();
        $this->info('Terminé.');
    }
}
