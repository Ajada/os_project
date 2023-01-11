<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\PartsModel;
use Illuminate\Http\Request;

class PartsController extends Controller
{
    protected $parts;
    protected $request;

    public function __construct(PartsModel $parts, Request $request)
    {
        return [
            $this->parts = $parts,
            $this->request = $request
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->parts->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($param)
    {
        try {
            foreach ($param['description_parts'] as $key => $value) {
                $this->parts->create([
                    'order_id' => $param['order_id'],
                    'description' => $value['part'],
                    'amount' => $value['amount']
                ]);
            }
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['error' => $th]);
        }
        return response()->json(['success' => 'part successfully added']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $parts = $this->parts->whereOrderId($id)
                ->orWhere(function ($query) use ($id) { 
                    $query->whereId($id); 
                })->get();
            return isset($parts[0]) ? $parts : response()->json(['error' => 'used service parts not found']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Throwable error']);
        }
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
            if($this->parts::whereId($id)->get()[0]){
                foreach ($this->request->all() as $key => $value) {
                    if(!is_null($value))
                        $this->parts::whereId($id)->update([$key => $value]);
                }
            }    
            return response()->json(['success' => 'parts updated successfully']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'no parts found with these parameters']);
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
        return $this->parts::whereId($id)->delete() ? 
            response()->json(['success' => 'part was been deleted successfully']) : 
            response()->json(['error' => 'error deleting part']);
    }
}
