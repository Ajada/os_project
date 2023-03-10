<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class CreateSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schema {create?} {delete?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create or delete schema';
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if($this->argument('create') != null)
            try {
                DB::statement('CREATE SCHEMA '. $this->argument('create'));
                Artisan::call('migrate:schema');
            } catch (\Throwable $th) {
                die(json_encode([
                    'error' => [ 
                        'status' => 'error creating schema',
                        'message' => $th
                    ]
                ]));
            }
        
        if($this->argument('delete') != null)
            try {
                DB::statement('DROP SCHEMA '. $this->argument('delete') .' CASCADE');
            } catch (\Throwable $th) {
                die(json_encode([
                    'error'  => 'error deleting schema',
                    'message' => $th
                ]));
            }
    }
}

