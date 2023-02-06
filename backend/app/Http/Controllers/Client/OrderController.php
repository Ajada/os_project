<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client\OrderModel;
use App\Http\Controllers\Client\ServiceController;
use App\Http\Controllers\Client\PartsController;
use App\Helpers\Helpers;
use App\Models\Client\PartsModel;
use App\Models\ServiceModel;

class OrderController extends Controller
{
    protected $order;
    protected $request;
    protected $service;
    protected $parts;
    protected $tenant;

    public function __construct(OrderModel $order, Request $request, ServiceController $service, PartsController $parts, Helpers $helpers)
    {
        return [
            $this->order = $order,
            $this->request = $request,
            $this->service = $service,
            $this->parts = $parts,
            $this->tenant = $helpers,
        ];
    }

    public function tes()
    {
        dd($this->tenant->setTenant($this->order));
        $tes = auth()->attempt();
    }

    public function index()
    {   
        $order = [];

        $collection = $this->tenant->setTenant($this->order);

        foreach ($collection->get() as $key => $value) {
            $order[$key] = [
                'order' => $value,
                'services' => $this->service->index($value['id']),
                'parts' => $this->parts->index($value['id']),
            ];
        }

        return !empty($order) ? response()->json($order) : response()->json($order, 204);
    }

    public function store()
    {
        try {
            $order = $this->tenant->setTenant($this->order)->create($this->request->all());

            $service = $this->service->store([
                'order_id' => $order->id, 
                'service' => $this->request->service
            ]);
        
            $parts = $this->parts->store([
                'order_id' => $order->id, 
                'parts' => $this->request->parts
            ]);
            
            if(isset(json_decode($service->content())->{'error'}) || isset(json_decode($parts->content())->{'error'}))
                return response()->json([
                    'success' => 'order created', 
                    'warning' => 'something went wrong adding parts or service'
                ], 206);  
        } catch (\Throwable $th) {
            return response()->json(['error' => 'vehicle not reported or not found'], 406);
        }

        return response()->json(['success' => 'order created'], 201);
    }

    public function addItemToOrder($id)
    {
        $order = $this->tenant->setTenant($this->order)->whereId($id)->first();

        if(is_null($order))
            return response()->json(['error' => 'order does not exists']);

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
                response()->json(['success' => 'items added to order '.$id], 201);
    }

    public function show($id)
    {
        $order = [
            'order' => $this->tenant->setTenant($this->order)->whereId($id)->first(),
            'services' => $this->service->show($id)->original,
            'parts' => $this->parts->show($id)->original,
        ];

        return $order['order'] ? response()->json($order, 200) : response()->json(['error' => 'no record found'], 404);
    }

    public function update($id) 
    {
        try {
            json_decode($this->show($id)->content())->{'order'}->{'id'};

            return response()->json([
                'order' => $this->updateOrder(
                    $this->request->all($this->order->fillable)),

                'service' => $this->service->update(
                    $this->request->service),

                'parts' => $this->parts->update(
                    $this->request->parts)
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'no order found with these parameters',
                'msg' => $th
            ], 404);
        }
    }
     
    public function updateOrder($order)
    {
        $exeption = [];
        foreach ($order as $key => $value) {
            if($value == null)
                continue;
            else{
                $this->order->update([
                    $key => $value
                ]);
                $exeption[$key] = [
                    'status' => 200,
                    'message' => 'order updated successfully'
                ];
            }
        }
        return !empty($exeption) ? $exeption : 'no reported content';
    }

    public function destroy($id)
    {
        $order = $this->tenant->setTenant($this->order)->whereId($id);

        if(!$order->first())
            return response()->json(['error' => 'record not found'], 404);
        
        $this->service->destroy($id);
        $this->parts->destroy($id);
        $order->delete();

        return response()->json(['success' => 'record was been deleted successfully'], 200);
    }

    public function deleteServiceAndParts()
    {
        return response()->json([
            'service' => $this->service->destroyOneService($this->request->service),
            'parts' => $this->parts->destroyOnePart($this->request->parts),
        ]);
    }

}
