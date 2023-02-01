<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

<<<<<<< HEAD
// use Illuminate\Support\Facades\Artisan;

=======
>>>>>>> feature_recreate_methods_to_controllers
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

<<<<<<< HEAD
=======
    /**
     * make new schema in DB
     */
>>>>>>> feature_recreate_methods_to_controllers
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

<<<<<<< HEAD
=======
    /**
     * delete schema of the DB
     */
>>>>>>> feature_recreate_methods_to_controllers
    public function destroy()
    {
        Artisan::call('schema', [
            'delete' => $this->request->delete,
        ]);
    }
}
