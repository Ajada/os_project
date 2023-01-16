<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\PartsModel;
use Illuminate\Http\Request;

use function GuzzleHttp\Promise\each;

class PartsController extends Controller
{
    protected $parts;
    protected $request;

    public function __construct(PartsModel $parts, Request $request)
    {
        return [
            $this->parts = $parts,
            $this->request = $request
        ];
    }

    public function index($id)
    {
        return response()->json($this->parts->whereOrderId($id)->get());
    }

    public function store($order)
    {
        try {
            foreach ($order['parts'] as $key => $value) {
                $this->parts->create([
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
        $parts = $this->parts->whereOrderId($id)->get();

        return isset($parts[0]) ?
            response()->json($parts) : 
            response()->json(['there are no registered parts for this vehicle.']);
    }

    public function update($parts)
    {
        foreach ($parts as $key => $value) {
            $par = $this->parts->whereId($parts[$key]['id']);
            if(isset($par->first()->id))
                $par->update([
                    'description' => !$value['description'] ? $par->first()->description : $value['description'],
                    'amount' => !$value['amount'] ? $par->first()->amount : $value['amount'],
                ]);
        }
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
