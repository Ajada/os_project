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
    public function index($id)
    {
        return response()->json($this->parts->whereOrderId($id)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($order)
    {
        try {
            foreach ($order['parts'] as $key => $value) {
                $this->parts->create([
                    'order_id' => $order['order_id'],
                    'description' => $value['description'],
                    'amount' => $value['amount']
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => 'something went wrong creating record']);
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
        $parts = $this->parts->whereOrderId($id)->get();

        return isset($parts[0]) ?
            response()->json($parts) : 
            response()->json(['there are no registered parts for this vehicle.']);
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
        return $this->parts::whereOrderId($id)->delete() ? 
            response()->json(['success' => 'part was been deleted successfully']) : 
            response()->json(['error' => 'error deleting part']);
    }
}
