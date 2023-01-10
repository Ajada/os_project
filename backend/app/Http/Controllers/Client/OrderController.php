<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client\OrderModel;

class OrderController extends Controller
{
    protected $order;
    protected $request;

    public function __construct(OrderModel $order, Request $request)
    {
        return [
            $this->order = $order, 
            $this->request = $request
        ];
    }

    public function index()
    {   
        return $this->order->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
        */
    public function store()  // recebe os dados dos clientes que estão entrnaod para manutenções
    {
        // $this->order::find($this->request->vehicle_id)->automobile[0]->id

        return $this->order::create($this->request->all()) ?
            response()->json(['success' => 'order created']) : 
            response()->json(['error' => 'something went wrong creating record']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = $this->order::whereId($id)
            ->where('user_id', $this->request->user_id)
                ->first();

        return $order ?
            response()->json($order) : 
            response()->json(['error' => 'no record found']);
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
            if($this->order::whereId($id)->get()[0])
                foreach ($this->request->all() as $key => $value) {
                    if(!is_null($value))
                        $this->order::whereId($id)->update([$key => $value]);
                }
            return response()->json(['success' => 'items updated successfully']);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'description' => 'no order found with these parameters']);
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
        return $this->order::whereId($id)->delete() ? 
            response()->json(['success' => 'record was been deleted successfull']) : 
            response()->json(['error' => 'error deleting item']);
    }
}
