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
        $exeption = [];

        foreach ($service as $key => $value) {
            if(isset($this->service->whereId($value['id'])->first()->id)){
                if($value['description'] != null){
                    $this->service->whereId($value['id'])->update([
                        'description' => $value['description']
                    ]);
                    $exeption[$value['id']] = [
                        'status' => 200,
                        'message' => 'service with id: '.$value['id'].' updadted successfully'
                    ];
                }
                if($value['status'] != null){
                    $this->service->whereId($value['id'])->update([
                        'status' => $value['status']
                    ]);
                    $exeption[$value['id']] = [
                        'status' => 200,
                        'message' => 'service with id: '.$value['id'].' updadted successfully'
                    ];    
                }
            }
            else
                $exeption[$value['id']] = [
                    'status' => 404,
                    'message' => $value['id'] != null ? 'service with id: '.$value['id'].' not found' : 'id not informed'
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