<?php

namespace App\Http\Controllers\Client;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Client\VehiclesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehiclesController extends Controller
{
    protected $auto;
    protected $request;
    protected $tenant;

    public function __construct(VehiclesModel $automobile, Request $request, Helpers $helpers)
    {
        return [
            $this->auto = $automobile, 
            $this->request = $request,
            $this->tenant = $helpers,
        ];
    }

    public function index()
    {
        return response()->json(
            $this->tenant->setTenant($this->auto)->get()
        );
    }

    public function store()
    {
        try {
            if(json_decode($this->show($this->request->plate)->content())->{'id'})
                return response()->json([
                    'error' => 'record already exists'
                ]);
        } catch (\Throwable $th) {
            $th = $th; 
        }

        return $this->auto::create($this->request->all()) ? 
            response()->json(['success' => $this->request->model.' registered with success']) : 
            response()->json(['error' => 'something went wrong creating record']);
    }

    public function show($param)
    {


        $auto = DB::table('vehicles')
            ->wherePlate($param)
                ->first();
        return $auto ? response()->json($auto) : response()->json(['error' => 'no record found']);
    }

    public function update($id)
    {
        try {
            if($this->auto::whereId($id)->get()[0])
                foreach ($this->request->all() as $key => $value) {
                    if(!is_null($value))
                        $this->auto::whereId($id)->update([$key => $value]);
                }
            return response()->json(['success' => 'items updated successfully']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'no automobile found with these parameters']);
        }
    }

    public function destroy($id)
    {
        // return $this->tenant->setTable($this->auto->table)->whereId($id)->delete() ? 
        //     response()->json(['success' => 'record was been deleted successfully'], 200) : 
        //         response()->json(['error' => 'error deleting item'], 409);

        return $this->auto::whereId($id)->delete() ? 
            response()->json(['success' => 'record was been deleted successfully']) : 
            response()->json(['error' => 'error deleting item']);
    }
}
