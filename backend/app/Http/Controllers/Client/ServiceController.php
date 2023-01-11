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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->service->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($param)
    {
        try {
            $this->service->create($param);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'something went wrong creating record']);
        }
        return response()->json(['success' => 'service created with successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = $this->service::whereId($id)
            ->orWhere(function ($query) use ($id) {
                $query->where('order_id', $id);
            })->get();

        return isset($service[0]) ?
            response()->json($service) : 
            response()->json(['error' => 'no record found']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        try {
            if($this->service::whereId($id)->get()[0]){
                foreach ($this->request->all() as $key => $value) {
                    if(!is_null($value))
                        $this->service::whereId($id)->update([$key => $value]);
                }
            }    
            return response()->json(['success' => 'item updated successfully']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'no order found with these parameters']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->service::whereId($id)->delete() ? 
            response()->json(['success' => 'record was been deleted successfull']) : 
            response()->json(['error' => 'error deleting item']);
    }
}