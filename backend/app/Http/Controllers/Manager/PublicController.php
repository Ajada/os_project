<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Manager\PublicModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class PublicController extends Controller
{
    protected $public;

    public function __construct()
    {
        return [
            $this->public = new PublicModel,
        ];
    }

    public function index()
    {
        //
    }

    /**
     * @param Array
     */
    public function store($tenant)
    {
        if(is_null($tenant['tenant_id']) || is_null($tenant['host']))
            return [
                'error' => [
                    'status' => 204,
                    'message' => 'no reported content'
                ]
            ];

        $show = $this->show($tenant['host']);

        if(isset($show['error']))
            return [
                'error' => [
                    'status' => 409,
                    'message' => $tenant['host'] . $show['error']
                ]
            ];

        if($this->public->create($tenant)){
            Artisan::call('schema', [
                'create' => $tenant['host']
            ]);
            return ['success' => ' created succeffully'];
        }

        return ['error' => 
            [
                'status' => 500, 
                'message' => 'something went wrong creating host: '
            ]
        ];
    }

    public function show($host)
    {
        $public = $this->public->whereHost($host)->first();

        return is_null($public) ? null : ['error' => ' already exists'];
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($host)
    {
        if(is_null($host['host']))
            return [
                'error' => [
                    'status' => 201,
                    'message' => 'no reported content'
                ]
            ];

        if(isset($this->show($host['host'])['error'])){
            Artisan::call('schema', [
                'delete' => $host['host']
            ]);
            return $this->public->whereHost($host['host'])->delete() ? 
                ['success' => ' deleted with success'] : 
                ['error' => [
                    'status' => 409,
                    'message' => 'something went wrong deleting host: ' . $host['host']
                    ]];
        }
            
        return [
            'error' => [
                'status' => 404,
                'message' => 'host: '.$host['host'].' not found',
            ]
        ];
        
    }
}