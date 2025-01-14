<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ListTables extends Command
{
    protected $signature = 'db:tables';
    protected $description = 'List all tables in the database';

    public function handle()
    {
        $tables = DB::select('SHOW TABLES');
        $this->info("Tables in the database:");
        foreach ($tables as $table) {
            $this->line(array_values((array)$table)[0]);
        }
    }
}
