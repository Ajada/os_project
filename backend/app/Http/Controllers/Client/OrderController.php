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

    public function tes()
    {
        $tes = auth()->attempt();
    }

    public function index()
    {   
        $order = [];

        foreach ($this->order->all() as $key => $value) {
            $order[$key] = [
                'order' => $value,
                'services' => $this->service->index($value['id'])->original,
                'parts' => $this->parts->index($value['id'])->original,
            ];
        }

        return response()->json($order);
    }

    public function store()
    {
        try {
            $order = $this->order::create($this->request->all());

            $service = $this->service->store(['order_id' => $order->id, 'service' => $this->request->service]);

            $parts = $this->parts->store(['order_id' => $order->id, 'parts' => $this->request->parts]);
            
            if(isset(json_decode($service->content())->{'error'}) || isset(json_decode($parts->content())->{'error'}))
                return response()->json(['error' => 'something went wrong adding parts or service']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'vehicle not reported or not found']);
        }

        return response()->json(['success' => 'order created']);
    }

    public function addItemToOrder($id)
    {
        $service = $this->service->store([
            'order_id' => $id, 
            'service' => $this->request->service
        ]);
    
        $parts = $this->parts->store([
            'order_id' => $id, 
            'parts' => $this->request->parts
        ]);

        return !$service || !$parts ? 
            response()->json(['error' => 'something went wrong creating record']) : 
            response()->json(['success' => 'items added to order']);
    }

    public function show($id)
    {
        $order = [
            'order' => $this->order::whereId($id)->first(),
            'services' => $this->service->show($id)->original,
            'parts' => $this->parts->show($id)->original,
        ];

        return $order ? response()->json($order) : response()->json(['error' => 'no record found']);
    }

    public function update($id)
    {
        try {
            if($this->order::whereId($id)->get()[0])
                foreach ($this->request->all() as $key => $value) {
                    $key == 'service' ? $this->service->update($value) : '';
                    $key == 'parts' ? $this->parts->update($value) : '';
                    $key == 'service' || $key == 'parts' ? '' : 
                        $this->order::whereId($id)->update([
                            $key => $value ? $value : $this->order::whereId($id)->get()[0]->$key]);
                }
            return response()->json(['success' => 'items updated successfully']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'no order found with these parameters']);
        }
    }

    public function destroy($id)
    {
        if(!$this->order->whereId($id)->first())
            return response()->json(['error' => 'record not found']);
        
        $this->service->destroy($id);
        $this->parts->destroy($id);
        $this->order::whereId($id)->delete();

        return response()->json(['success' => 'record was been deleted successfully']);
    }

    public function deleteServiceAndParts()
    {
        return $this->service->destroyOneService($this->request->all()['service']) &&
            $this->service->destroyOnePart($this->request->all()['parts']) ?
                response()->json(['success' => 'items deleted with successfully']) : 
                response()->json(['error' => 'something went wrong while removing items']);
    }

}
