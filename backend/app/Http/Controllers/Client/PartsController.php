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
        return response()->json($this->tenant->setTenant($this->parts)->whereOrderId($id)->get());
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
        $client = $this->tenant->setTenant($this->parts);
        $exeption = [];
        foreach ($parts as $key => $value) {
            $reg = $client->whereId($parts[$key]['id']);
            if(isset($reg->first()->id)){
                $reg->update([
                    'description' => !$value['description'] ? $reg->first()->description : $value['description'],
                    'amount' => !$value['amount'] ? $reg->first()->amount : $value['amount'],
                ]);
                $exeption[$key] = [
                    'status' => 200,
                    'message' => 'service with id: '.$parts[$key]['id'].' updadted successfully'
                ];
            }
            else
                $exeption[$key] = [
                    'status' => 404,
                    'message' => 'service with id: '.$parts[$key]['id'].' not found'
                ];
        }
        return !empty($exeption) ? $exeption : 'not informed';
    }

    public function destroy($id)
    {
        return $this->parts::whereOrderId($id)->delete() ? 
            response()->json(['success' => 'part was been deleted successfully']) : 
            response()->json(['error' => 'error deleting part']);
    }

    public function destroyOnePart($partId)
    {
        foreach ($partId as $key => $value) {
            $this->parts::whereId($value['id'])->delete();
        }
    }
}
