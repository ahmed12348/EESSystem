<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ListModels extends Command
{
    protected $signature = 'list:models';
    protected $description = 'List all models in the application';

    public function handle()
    {
        $modelsPath = app_path('Models');
        $models = collect(File::files($modelsPath))
            ->map(fn ($file) => pathinfo($file, PATHINFO_FILENAME))
            ->all();

        $this->info("Models Found:");
        foreach ($models as $model) {
            $this->line("- " . $model);
        }
    }
}
