<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client\OrderModel;
use App\Http\Controllers\Client\ServiceController;
use App\Http\Controllers\Client\PartsController;

class OrderController extends Controller
{
    protected $order;
    protected $request;
    protected $service;
    protected $parts;
    protected $public;

    public function __construct(OrderModel $order, Request $request, ServiceController $service, PartsController $parts)
    {
        return [
            $this->order = $order,
            $this->request = $request,
            $this->service = $service,
            $this->parts = $parts,
        ];
    }
    
    protected function setTable()
    {
        return $this->order->table = $this->request->tenant_id.'.'.$this->order->table;
    }
    
    public function authTeste() 
    {
        dd($this->setTable());
    }

    public function index()
    {   
        $this->setTable();
        $order = [];

        foreach ($this->order->all() as $key => $value) {
            $order[$key] = [
                'order' => $value,
                'services' => $this->service->index($value['id'])->original,
                'parts' => $this->parts->index($value['id'])->original,
            ];
        }

        return !empty($order) ? response()->json($order) : response()->json($order, 204);
    }

    public function store()
    {
        $this->setTable();
        try {
            $order = $this->order::create($this->request->all());

            $service = $this->service->store([
                'order_id' => $order->id, 
                'service' => $this->request->service
            ]);

            $parts = $this->parts->store([
                'order_id' => $order->id, 
                'parts' => $this->request->parts
            ]);
            
            if(isset(json_decode($service->content())->{'error'}) || isset(json_decode($parts->content())->{'error'}))
                return response()->json(['success' => 'order created', 'warning' => 'something went wrong adding parts or service'], 206);  
        } catch (\Throwable $th) {
            return response()->json(['error' => 'vehicle not reported or not found'], 406);
        }

        return response()->json(['success' => 'order created'], 201);
    }

    public function addItemToOrder($id)
    {
        $this->setTable();
        $service = $this->service->store([
            'order_id' => $id, 
            'service' => $this->request->service
        ]);
    
        $parts = $this->parts->store([
            'order_id' => $id, 
            'parts' => $this->request->parts
        ]);

        return !$service || !$parts ? 
            response()->json(['error' => 'something went wrong creating record'], 409) : 
            response()->json(['success' => 'items added to order'], 201);
    }

    public function show($id)
    {
        $this->setTable();
        $order = [
            'order' => $this->order::whereId($id)->first(),
            'services' => $this->service->show($id)->original,
            'parts' => $this->parts->show($id)->original,
        ];

        return $order['order'] ? response()->json($order, 200) : response()->json(['error' => 'no record found'], 404);
    }

    public function update($id)
    {
        $this->setTable();
        try {
            if($this->order::whereId($id)->get()[0])
                foreach ($this->request->all() as $key => $value) {
                    $key == 'service' ? $this->service->update($value) : '';
                    $key == 'parts' ? $this->parts->update($value) : '';
                    $key == 'service' || $key == 'parts' ? '' : 
                        $this->order::whereId($id)->update([
                            $key => $value ? $value : $this->order::whereId($id)->get()[0]->$key]);
                }
            return response()->json(['success' => 'items updated successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'no order found with these parameters'], 404);
        }
    }

    public function destroy($id)
    {
        $this->setTable();
        if(!$this->order->whereId($id)->first())
            return response()->json(['error' => 'record not found'], 404);
        
        $this->service->destroy($id);
        $this->parts->destroy($id);
        $this->order::whereId($id)->delete();

        return response()->json(['success' => 'record was been deleted successfully'], 200);
    }

    public function deleteServiceAndParts()
    {
        $this->setTable();
        return $this->service->destroyOneService($this->request->all()['service']) &&
            $this->service->destroyOnePart($this->request->all()['parts']) ?
                response()->json(['success' => 'items deleted with successfully'], 200) : 
                response()->json(['error' => 'something went wrong while removing items'], 409);
    }

}
