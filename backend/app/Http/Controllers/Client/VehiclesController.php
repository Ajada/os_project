<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehiclesController extends Controller
{
    protected $auto;
    protected $request;

    public function __construct(VehicleModel $automobile, Request $request)
    {
        return [
            $this->auto = $automobile, 
            $this->request = $request
        ];
    }

    public function defineTable()
    {
        return $this->auto->setTable('client_1.vehicles');
    }

    public function index()
    {
        return !empty($this->auto->all()) ? response()->json($this->auto->all()) : response()->json($this->auto->all(), 204);
    }

    public function store()
    {
        $this->setTable();
        try {
            if(json_decode($this->show($this->request->plate)->content())->{'id'})
                return response()->json([
                    'error' => 'record already exists'
                ], 409);
        } catch (\Throwable $th) {
            $th = $th; 
        }

        return $this->auto::create($this->request->all()) ? 
            response()->json(['success' => $this->request->model.' registered with success'], 201) : 
            response()->json(['error' => 'something went wrong creating record'], 406);
    }

    public function show($param)
    {
        $this->setTable();
        $auto = DB::table('vehicles')
            ->wherePlate($param)
                ->first();
        return $auto ? response()->json($auto) : response()->json(['error' => 'no record found'], 409);
    }

    public function update($id)
    {
        $this->setTable();
        try {
            if($this->auto::whereId($id)->get()[0])
                foreach ($this->request->all() as $key => $value) {
                    if(!is_null($value))
                        $this->auto::whereId($id)->update([$key => $value]);
                }
            return response()->json(['success' => 'items updated successfully'], 205);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'no automobile found with these parameters'], 409);
        }
    }

    public function destroy($id)
    {
        $this->setTable();
        return $this->auto::whereId($id)->delete() ? 
            response()->json(['success' => 'record was been deleted successfully'], 200) : 
            response()->json(['error' => 'error deleting item'], 409);
    }
}
