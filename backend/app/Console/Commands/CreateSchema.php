<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

<<<<<<< HEAD
use function PHPUnit\Framework\returnValue;

=======
>>>>>>> feature_recreate_methods_to_controllers
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
<<<<<<< HEAD
    
=======

>>>>>>> feature_recreate_methods_to_controllers
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $create = $this->argument('create');
        $delete = $this->argument('delete');
<<<<<<< HEAD
        
        $create ? $this->create($create) : '';

        $delete ? $this->delete($delete) : '';
    }

    /**
     * 
     */
=======

        $create ? $this->create($create) : ''; //trocar validação para this->argument
        $delete ? $this->delete($delete) : ''; 
    }

>>>>>>> feature_recreate_methods_to_controllers
    public function create ($create) 
    {
        try {
            DB::statement('CREATE SCHEMA '. $create);
            Artisan::call('migrate:schema');
            die(json_encode([
                'success' => 'schema created with success'
            ]));
        } catch (\Throwable $th) {
            die(json_encode([
                'error'  => 'error creating schema',
                'mesasge' => $th
            ]));
        }
    }

    public function delete($delete) 
    {
        try {
            DB::statement('DROP SCHEMA '. $delete .' CASCADE');
            die(json_encode([
                'success' => 'schema deleted with success'
            ]));
        } catch (\Throwable $th) {
            die(json_encode([
                'error'  => 'error deleting schema',
                'mesasge' => $th
            ]));
        }
    }
<<<<<<< HEAD
}
=======

}

>>>>>>> feature_recreate_methods_to_controllers
