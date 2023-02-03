<?php

namespace App\Http\Controllers\Client;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceModel;
use Exception;

class ServiceController extends Controller
{
    protected $service;
    protected $request;
    protected $tenant;

    public function __construct(ServiceModel $service, Request $request, Helpers $helpers)
    {
        return [
            $this->service = $service, 
            $this->request = $request,
            $this->tenant = $helpers,
        ];
    }

    public function index($id)
    {
        return response()->json($this->service->whereOrderId($id)->get());
    }

    public function store($order)
    {
        try {
            $service = $this->tenant->setTenant($this->service);
            foreach ($order['service'] as $key => $value) {
                $service->create([
                    'order_id' => $order['order_id'] ,
                    'description' => $value['description'],
                    'status' => $value['status']
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => 'something went wrong creating record']);
        }
        return response()->json(['success' => 'service created with successfully']);
    }

    public function show($id)
    {
        $service = $this->tenant->setTenant($this->service)->whereOrderId($id)->get();
        
        return isset($service[0]) ?
            response()->json($service) : 
                response()->json(['There are no registered services for this vehicle.']);
    }

    public function update($service) // refatorar
    {
        $client = $this->tenant->setTenant($this->service);
        $exeption = [];
        foreach ($service as $key => $value) {
            $reg = $client->whereId($service[$key]['id']);
            if(isset($reg->first()->id)){
                $reg->update([
                    'description' => is_null($value['description']) ? $reg->first()->description : $value['description'],
                    'status' => is_null($value['status']) ? $reg->first()->status : $value['status'],
                ]);
                $exeption[$key] = [
                    'status' => 200,
                    'message' => 'service with id: '.$service[$key]['id'].' updadted successfully'
                ];
            }
            else
                $exeption[$key] = [
                    'status' => 404,
                    'message' => 'service with id: '.$service[$key]['id'].' not found'
                ];
        }
        return !empty($exeption) ? $exeption : 'not informed';
    }

    public function destroy($id)
    {
        return $this->service::whereOrderId($id)->delete() ? 
            response()->json(['success' => 'record was been deleted successfull']) : 
            response()->json(['error' => 'error deleting item']);
    }

    public function destroyOneService($serviceId)
    {
        foreach ($serviceId as $key => $value) {
            $this->service::whereId($value['id'])->delete();
        }
    }

}