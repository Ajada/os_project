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

        $create = $this->auto->create($this->request->all());

        return $create ? 
            response()->json(['success' => $this->request->model.' registered with success']) : 
                response()->json(['error' => 'something went wrong creating record']);
    }

    public function show($param)
    {
        $auto = $this->tenant
            ->setTenant($this->auto)
                ->wherePlate($param)
                    ->first();
                    
        return $auto ? response()->json($auto) : response()->json(['error' => 'no record found']);
    }

    public function update($id)
    {
        $vehicle = $this->tenant
            ->setTenant($this->auto)
                ->whereId($id);
        try {
            if($vehicle->get()[0])
                foreach ($this->request->all() as $key => $value) {
                    !is_null($value) ? 
                        $vehicle->update([$key => $value]) : '';
                }
        } catch (\Throwable $th) {
            return response()->json(['error' => 'no automobile found with these parameters']);
        }

        return response()->json(['success' => 'items updated successfull']);
    }

    public function destroy($id)
    {
        return $this->tenant->setTenant($this->auto)->whereId($id)->delete() ? 
            response()->json(['success' => 'record was been deleted successfully'], 200) : 
                response()->json(['error' => 'error deleting item'], 409);
    }
}
