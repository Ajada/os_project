<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client\OrderModel;
use App\Http\Controllers\Client\ServiceController;
use App\Http\Controllers\Client\PartsController;
use App\Helpers\Helpers;

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
                'services' => $this->service->index($value['id'])->original,
                'parts' => $this->parts->index($value['id'])->original,
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
            $order = json_decode($this->show($id)->content())->{'order'}->{'id'};
            $order = $this->updateOrder($this->request->all($this->order->fillable));
            $service = $this->service->update($this->request->service);
            $parts = $this->parts->update($this->request->parts);

            return response()->json([
                'order' => $order,
                'service' => $service,
                'parts' => $parts
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'no order found with these parameters',
                'msg' => $th
            ], 404);
        }

        dd($this->request->all($this->order->fillable));

    }

    public function updateOrder($order)
    {
        $client = $this->tenant->setTenant($this->order);
        $exeption = [];
        foreach ($order as $key => $value) {
            if($value == null)
                continue;
            $client->update([
                    $key => $value
                ]);
            $exeption[$key] = [
                'status' => 200,
                'message' => 'service with id: '.$order[$key]['id'].' updadted successfully'
            ];
        }
        return !empty($exeption) ? $exeption : 'not informed';
            // }
            // else
            //     $exeption[$key] = [
            //         'status' => 404,
            //         'message' => 'service with id: '.$order[$key]['id'].' not found'
            //     ];
        // return !empty($exeption) ? $exeption : 'not informed';
        // try {
        //     $order = $this->tenant->setTenant($this->order)->whereId($id);
        //     if($order->get()[0]){
        //         $orderCollection = '';
        //         $service = '';
        //         $parts = '';
        //         foreach ($this->request->all() as $key => $value) {
        //             if($key != 'service' && $key != 'parts'){
        //                 $order->update([
        //                     $key => $value ? $value : $order->get()[0]->$key
        //                 ]);
        //                 continue;
        //             }
        //             if($key == 'service'){
        //                 $service = $this->service->update($value);
        //                 continue;
        //             }
        //             if($key == 'parts'){
        //                 $parts = $this->parts->update($value);
        //                 continue;
        //             }
        //         }
        //         return response()->json([
        //             'order' => 'items updated successfully',
        //             'service' => $service,
        //             'parts' => $parts
        //         ], 200);
        //     }
        // } catch (\Throwable $th) {  
        //     return response()->json(['error' => 'no order found with these parameters'], 404);
        // }
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
