<?php

namespace App\Http\Controllers\Register;

use App\Http\Controllers\Controller;
use App\Models\Automobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutomobilesController extends Controller
{
    protected $auto;
    protected $request;

    public function __construct(Automobile $automobile, Request $request)
    {
        return [
            $this->auto = $automobile, 
            $this->request = $request
        ];
    }

    public function index()
    {
        return response()->json($this->auto->all());
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($param)
    {
        $auto = DB::table('vehicles')
            ->wherePlate($param)
                ->first();
        return $auto ? response()->json($auto) : response()->json(['error' => 'no record found']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->auto::whereId($id)->delete() ? 
            response()->json(['success' => 'record was been deleted successfully']) : 
            response()->json(['error' => 'error deleting item']);
    }
}
