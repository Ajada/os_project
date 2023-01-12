<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceModel;

class ServiceController extends Controller
{
    protected $service;
    protected $request;

    public function __construct(ServiceModel $service, Request $request)
    {
        return [
            $this->service = $service, 
            $this->request = $request
        ];
    }

    public function index($id)
    {
        return response()->json($this->service->whereOrderId($id)->get());
    }

    public function store($order)
    {
        try {
            foreach ($order['service'] as $key => $value) {
                $this->service->create([
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
        $service = $this->service::whereOrderId($id)->get();

        return isset($service[0]) ?
            response()->json($service) : 
            response()->json(['There are no registered services for this vehicle.']);
    }

    public function update($service)
    {
        try {
            foreach ($service as $key => $value) {
                $reg = $this->service->whereId($service[$key]['id']);
                if(isset($reg->first()->id))
                    return response()->json('teste'); // return 500 
                    // $reg->update([
                    //     'description' => is_null($value['description']) ? $reg->first()->description : $value['description'],
                    //     'status' => is_null($value['status']) ? $reg->first()->status : $value['status'],
                    // ]);
            }
        } catch (\Throwable $th) {
            dd($th);
        }
        
        return response()->json(['success' => 'services updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->service::whereOrderId($id)->delete() ? 
            response()->json(['success' => 'record was been deleted successfull']) : 
            response()->json(['error' => 'error deleting item']);
    }

    public function destroyOneService($id)
    {
        return $this->service::whereId($id)->delete() ? 
            response()->json(['success' => 'service has been deleted successfull']) : 
            response()->json(['error' => 'error deleting item']);
    }

}