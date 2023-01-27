<?php

namespace App\Console\Commands;

use App\Models\Manager\MapAllSchemas;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RunMigrateInSchemas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:schema';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'run migrate to all schemas';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $schemas = MapAllSchemas::where('schema_name', '<>', 'information_schema')
        ->where('schema_name', '<>', 'pg_catalog')
        ->where('schema_name', '<>', 'public')
        ->where('schema_name', '<>', 'pg_toast')->get('schema_name');

        foreach ($schemas as $key => $value) {
            DB::purge('pgsql');

            config([
                'database.connections.pgsql.schema' => $value['schema_name'],
                'database.connections.pgsql.search_path' => $value['schema_name']
            ]);

            DB::connection('pgsql');

            $this->call('migrate', ['--path' => 'database/tenants']);
        }
    }
}
