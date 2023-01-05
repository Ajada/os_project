<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client\OrderModel;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $order;
    
    public function __construct(OrderModel $order)
    {
        return $this->order = $order;
    }

    public function index()
    {
        dd('index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)  // recebe os dados dos clientes que estão entrnaod para manutenções
    {
        dd($request);
        $auto_description = DB::table('automobiles')
            ->wherePlate($request->plate)
                ->get('car_model');
        
        $data = $request->all();
        $auto_description ? $data['car_model'] = $auto_description[0]->car_model : '';

        return $this->order::create($data) ?
            response()->json(['success' => 'order created']) :
            response()->json(['error' => 'something went wrong creating record']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd('show');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
