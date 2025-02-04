<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use ReflectionMethod;

class ListModelRelations extends Command
{
    protected $signature = 'list:relations';
    protected $description = 'List all models with their relationships';

    public function handle()
    {
        $modelsPath = app_path('Models');
        $models = collect(File::files($modelsPath))
            ->map(fn ($file) => pathinfo($file, PATHINFO_FILENAME))
            ->all();

        foreach ($models as $model) {
            $fullModel = "App\\Models\\$model";
            if (!class_exists($fullModel) || !is_subclass_of($fullModel, Model::class)) {
                continue;
            }

            $this->info("Model: $model");
            $this->line(str_repeat("-", 30));

            $reflection = new ReflectionClass($fullModel);
            foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->class === $fullModel && $method->getNumberOfParameters() === 0) {
                    try {
                        $returnType = $method->invoke(new $fullModel());
                        if ($returnType instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                            $this->line(" - {$method->getName()}() => " . get_class($returnType));
                        }
                    } catch (\Throwable $th) {
                        continue;
                    }
                }
            }

            $this->line("");
        }
    }
}
