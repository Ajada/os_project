<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client\OrderModel;
use App\Http\Controllers\Client\ServiceController;
use App\Http\Controllers\Client\PartsController;
use Symfony\Component\Mailer\Transport\Dsn;

class OrderController extends Controller
{
    protected $order;
    protected $request;
    protected $service;
    protected $parts;

    public function __construct(OrderModel $order, Request $request, ServiceController $service, PartsController $parts)
    {
        return [
            $this->order = $order, 
            $this->request = $request,
            $this->service = $service,
            $this->parts = $parts,
        ];
    }

    public function index()
    {   
        return $this->order->all();
    }

    public function store()  // recebe os dados dos clientes que estão entrnaod para manutenções
    {
        $order = $this->order::create($this->request->all());
    
        $service = $this->service->store([
            'order_id' => $order->id,
            'description' => $this->request->description,
            'status' => '1'
        ]);

        $parts = $this->parts->store([
            'order_id' => $order->id,
            'description_parts' => $this->request->description_parts,
        ]);
        
        if(isset(json_decode($service->content())->{'error'}) || isset(json_decode($parts->content())->{'error'}))
            return response()->json(['error' => 'something went wrong adding parts or service']);

        return response()->json(['success' => 'order created']);
    }

    public function show($id)
    {
        $order = $this->order::whereId($id)->first();

        return $order ?
            response()->json($order) : 
            response()->json(['error' => 'no record found']);
    }

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

    public function destroy($id)
    {
        return $this->order::whereId($id)->delete() ? 
            response()->json(['success' => 'record was been deleted successfull']) : 
            response()->json(['error' => 'error deleting item']);
    }
}
