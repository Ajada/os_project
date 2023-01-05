<?php

namespace App\Http\Controllers\Register;

use App\Http\Controllers\Controller;
use App\Models\Automobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutomobilesController extends Controller
{
    protected $auto;

    public function __construct(Automobile $automobile)
    {
        return $this->auto = $automobile;
    }

    public function index()
    {
        return response()->json($this->auto->all());
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($this->show($request->plate))
            return response()->json(['error' => 'record already exists']);

        $register = $this->auto::create($request->all());

        return $register ? response()->json(['success' => 'automobile registered with success']) : response()->json(['error' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($param)
    {
        $auto = DB::table('automobiles')
            ->wherePlate($param)
                ->first();
        return $auto ? response()->json($auto) : false;
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
