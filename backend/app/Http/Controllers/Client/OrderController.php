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
        try {
            $this->order::create($this->request->all());
        } catch (\Throwable $th) {
            return response()->json(['error' => 'something went wrong creating record']);
        }
        return response()->json(['success' => 'order created']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = $this->order::whereId($id)->first();

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
            return response()->json(['error' => 'no order found with these parameters']);
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
