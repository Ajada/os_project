<?php

namespace App\Console\Commands\Manager;

use App\Http\Controllers\Manager\PublicController;
use App\Models\Manager\PublicModel;
use Illuminate\Console\Command;

class RegisterNewTenantHost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schema:host {create?} {delete?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'register hosts of the customer';
    protected $response = [];

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        $this->argument('create') ?
            self::registerHost($this->argument('create')) : '';
        
        $this->argument('delete') ?
            self::deleteHost($this->argument('delete')) : '';
    }

    private function registerHost($create)
    {
        $public = new PublicController();

        foreach ($create as $key => $value) {
            $create = $public->store($value);

            if(isset($create['error'])){
                $this->response[$key] = $create['error'];
                continue;
            }

            $this->response[$key] = [
                'status' => 200,
                'message' => 'host: '.$value['host'].$create['success']
            ];
        }
        die(json_encode($this->response));
    }

    private function deleteHost($delete)
    {
        $public = new PublicController();

        foreach ($delete as $key => $value) {
            $delete = $public->destroy($value);

            if(isset($delete['error'])){
                $this->response[$key] = $delete['error'];
                continue;
            }

            $this->response[$key] = [
                'status' => 200,
                'message' => 'host: ' . $value['host'] . $delete['success']
            ];
        }

        die(json_encode($this->response));
    }

}
