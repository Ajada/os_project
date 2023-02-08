<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SchemasController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        return [
            $this->request = $request,
        ];
    }

    public function index()
    {
        //
    }

    public function createHost ()
    {
        Artisan::call('schema:host', [
            'create' => $this->request->create
        ]);
    }

    /**
     * make new schema in DB
     */
    public function createSchema ()
    {
        Artisan::call('schema', [
            'create' => $this->request->create, 
        ]);
    }

    public function show($id)
    {
        //
    }

    public function update($id)
    {
        //
    }
    
    public function destroyHost ()
    {
        Artisan::call('schema:host', [
            'delete' => $this->request->delete,
        ]); 
    }

    /**
     * delete schema of the DB
     */
    public function destroySchema ()
    {
        Artisan::call('schema', [
            'delete' => $this->request->delete,
        ]);
    }
    
}