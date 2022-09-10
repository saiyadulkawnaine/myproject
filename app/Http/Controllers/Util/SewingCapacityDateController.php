<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\SewingCapacityDateRepository;
use App\Repositories\Contracts\Util\SewingCapacityRepository;
use App\Library\Template;
use App\Http\Requests\SewingCapacityDateRequest;

class SewingCapacityDateController extends Controller {

    private $sewingcapacitydate;
    private $sewingcapacity;

    public function __construct(
        SewingCapacityDateRepository $sewingcapacitydate, 
        SewingCapacityRepository $sewingcapacity
    ) {
        $this->sewingcapacitydate = $sewingcapacitydate;
        $this->sewingcapacity = $sewingcapacity;

        $this->middleware('auth');
        $this->middleware('permission:view.sewingcapacitydates',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.sewingcapacitydates', ['only' => ['store']]);
        $this->middleware('permission:edit.sewingcapacitydates',   ['only' => ['update']]);
        $this->middleware('permission:delete.sewingcapacitydates', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
     
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $daystatus=array_prepend(config('bprs.daystatus'),'-Select-','');
        $data=$this->sewingcapacitydate
        ->where([['sewing_capacity_id','=',request('sewing_capacity_id',0)]])
        ->orderBy('id')
        ->get()
        ->map(function($data){
            $data->capacity_date=date('Y-m-d',strtotime($data->capacity_date));
            return $data;
        });
        return Template::loadView('Util.SewingCapacityDate',[
            'data'=>$data,
            'daystatus'=>$daystatus,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SewingCapacityDateRequest $request) {
        $sewingcapacity=$this->sewingcapacity->find(request('sewing_capacity_id'));
        \DB::beginTransaction();
        try
        {
        foreach($request->id as $index=>$id){
            $mkt_cap_mint=$sewingcapacity->working_hour*60*$request->resource_qty[$index]*($sewingcapacity->mkt_eff_percent/100);
            $prod_cap_mint=$sewingcapacity->working_hour*60*$request->resource_qty[$index]*($sewingcapacity->prod_eff_percent/100);
            $mkt_cap_pcs=$mkt_cap_mint/$sewingcapacity->basic_smv;
            $prod_cap_pcs= $prod_cap_mint/$sewingcapacity->basic_smv;
            $sewingcapacitydate = $this->sewingcapacitydate->update($id,[
                'day_status'=>$request->day_status[$index],
                'resource_qty'=>$request->resource_qty[$index],
                'mkt_cap_mint'=> $mkt_cap_mint,
                'mkt_cap_pcs'=> $mkt_cap_pcs,
                'prod_cap_mint'=> $prod_cap_mint,
                'prod_cap_pcs'=> $prod_cap_pcs,
                ]);
        }
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
        return response()->json(array('success' => true,  'message' => 'Save Successfully'), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SewingCapacityDateRequest $request, $id) {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
    }

}
