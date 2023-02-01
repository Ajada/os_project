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

    /**
     * make new schema in DB
     */
    public function store()
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

    /**
     * delete schema of the DB
     */
    public function destroy()
    {
        Artisan::call('schema', [
            'delete' => $this->request->delete,
        ]);
    }
}
