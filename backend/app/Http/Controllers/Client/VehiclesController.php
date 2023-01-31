<?php

namespace App\Http\Controllers\Client;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Client\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class VehiclesController extends Controller
{
    protected $auto;
    protected $tenant;
    protected $request;

    public function __construct(Request $request, VehicleModel $auto, Helpers $helper)
    {
        return [
            $this->request = $request,
            $this->auto = $auto,
            $this->tenant = $helper,
        ];
    }

    public function index()
    {
        $vehicles = $this->tenant
            ->setTable($this->auto->table)
                ->get($this->auto->fillable);

        return !empty($vehicles) ? 
                response()->json($vehicles) : 
                    response()->json($vehicles, 204);
    }

    public function store()
    {
        $tes = $this->tenant
            ->setTable($this->auto->table)
                ->wherePlate($this->request->plate)
                    ->first(); 

        dd($tes);

        try {
            return $this->checkVehicleExists($this->request->plate) ? response()->json([
                'error' => 'record already exists'
            ], 409) : '';
        } catch (\Throwable $th) {
            //throw $th;
            dd('erro');
        }

        $this->checkVehicleExists($this->request->plate) ? dd('ja existe') : '';
        
        $create = $this->tenant->setTable($this->auto->table)->insert($this->request->all());

        return $create ? response()->json(['success']) : response()->json(['error']);

        // dd($this->checkVehicleExists($this->request->plate));
        return $this->checkVehicleExists($this->request->plate) ? dd('in') : dd('out');

        if(!$this->checkVehicleExists($this->request->plate)->id)
            return response()->json(['error']);
        
        dd('pass');

        dd($this->checkVehicleExists($this->request->plate)->id);
        
        if(json_decode($this->show($this->request->plate)->data())->{'id'}){
            return response()->json([
                'error' => 'record already exists'
            ], 409);
        }
        
        $this->tenant->setTable($this->auto->table)
            ->insert($this->request->all());
        return response()->json(['success' => $this->request->model.' registered with success'], 201);

        try {
            if(!json_decode($this->show($this->request->plate)->content())->{'id'})
                $this->tenant->setTable($this->auto->table)
                    ->insert($this->request->all());
            return response()->json(['success' => $this->request->model.' registered with success'], 201);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json([
                'error' => 'record already exists'
            ], 409);
        }
    }

    public function show($param)
    {
        // $auto = $this->tenant
        //     ->setTenant($this->auto)
        //         ::wherePlate($param)
        //             ->get($this->auto->fillable)
        //                 ->first();

        // dd($this->tenant->setTenant($this->auto));

        $tes = $this->auto::wherePlate($param)->first();

        // $tes = DB::table('client_2.vehicles')->wherePlate($param)->get()->first();

        // $tes = $this->auto::collection();

        // dd($tes);

        return $tes ? 
            response()->json($tes) : 
                response()->json(['error' => 'no record found'], 409);

        return $auto ? $auto : 'er';

        return $auto ? 
            response()->json($auto) : 
                response()->json(['error' => 'no record found'], 409);
    }

    public function checkVehicleExists($param)
    {
        $auto = $this->tenant
        ->setTable($this->auto->table)
            ->wherePlate($param)
                ->first();

        return $auto->id;
    }

    public function update($id)
    {
        try {
            if($this->tenant->setTable($this->auto->table)->whereId($id)->get()[0])
                foreach ($this->request->all() as $key => $value) {
                    if(!is_null($value))
                        $this->tenant->setTable('.vehicles')
                            ->whereId($id)
                                ->update([$key => $value]);
                }
            return response()->json([
                'success' => 'items updated successfully'
            ], 205);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'no automobile found with these parameters'
            ], 409);
        }
    }

    public function destroy($id)
    {
        return $this->tenant->setTable($this->auto->table)->whereId($id)->delete() ? 
            response()->json(['success' => 'record was been deleted successfully'], 200) : 
                response()->json(['error' => 'error deleting item'], 409);
    }
}
