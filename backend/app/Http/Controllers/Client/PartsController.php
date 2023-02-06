<?php

namespace App\Http\Controllers\Client;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Client\PartsModel;
use Illuminate\Http\Request;

use function GuzzleHttp\Promise\each;

class PartsController extends Controller
{
    protected $parts;
    protected $request;
    protected $tenant;

    public function __construct(PartsModel $parts, Request $request, Helpers $helpers)
    {
        return [
            $this->parts = $parts,
            $this->request = $request,
            $this->tenant = $helpers,
        ];
    }

    public function index($id)
    {
        $this->parts->setTable('.parts');

        $parts = $this->tenant->setTenant($this->parts)->whereOrderId($id)->get();

        return $parts;
    }

    public function store($order)
    {
        $parts = $this->tenant->setTenant($this->parts);
        try {
            foreach ($order['parts'] as $key => $value) {
                $parts->create([
                    'order_id' => $order['order_id'],
                    'description' => $value['description'],
                    'amount' => $value['amount']
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => 'something went wrong creating record']);
        }
        return response()->json(['success' => 'part successfully added']);
    }

    public function show($id)
    {
        $parts = $this->tenant->setTenant($this->parts)->whereOrderId($id)->get();

        return isset($parts[0]) ?
            response()->json($parts) : 
            response()->json(['there are no registered parts for this vehicle.']);
    }

    public function update($parts)
    {
        $exeption = [];
        foreach ($parts as $key => $value) {
            if(isset($this->parts->whereId($value['id'])->first()->id)){
                if($value['description'] != null){
                    $this->parts->whereId($value['id'])->update([
                        'description' => $value['description']
                    ]);
                    $exeption[$value['id']] = [
                        'status' => 200,
                        'message' => 'part with id: '.$value['id'].' updadted successfully'
                    ];    
                }
                if($value['amount'] != null){
                    $this->parts->whereId($value['id'])->update([
                        'status' => $value['status']
                    ]);
                    $exeption[$value['id']] = [
                        'status' => 200,
                        'message' => 'part with id: '.$value['id'].' updadted successfully'
                    ];
                }
            }
            else
                $exeption[$key] = [
                    'status' => 404,
                    'message' => $value['id'] != null ? 'part with id: '.$value['id'].' not found' : 'id not informed'
                ];
        }
        return !empty($exeption) ? $exeption : 'no reported content';
    }

    public function destroy($id)
    {
        return $this->tenant->setTenant($this->parts)->whereOrderId($id)->delete() ? 
            response()->json(['success' => 'part was been deleted successfully']) : 
            response()->json(['error' => 'error deleting part']);
    }

    public function destroyOnePart($partId)
    {
        $part = $this->tenant->setTenant($this->parts);
        $exception = [];

        foreach ($partId as $key => $value) {
            if($part->whereId($value['id'])->first())
                $part->whereId($value['id'])->delete() ? 
                $exception[$key] = [
                    'status' => 200,
                    'message' => 'id: '.$value['id'].' deleted successfully'
                ] : 
                $exception[$key] = [
                    'status' => 404,
                    'message' => $value['id'].' not found'
                ];
            else
                !is_null($value['id']) ?
                    $exception[$key] = [
                        'status' => 404,
                        'message' => 'id: '.$value['id'].' not exists'
                    ] : 
                    $exception[$key] = [
                        'status' => 400,
                        'message' => 'id not informed' 
                    ];
        }

        return !empty($exception) ? $exception : 'no reported content';
    }
}