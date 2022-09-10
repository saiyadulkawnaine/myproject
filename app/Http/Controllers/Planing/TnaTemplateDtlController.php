<?php

namespace App\Http\Controllers\Planing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\TnataskRepository;
use App\Repositories\Contracts\Planing\TnaProgressDelayRepository;
use App\Repositories\Contracts\Planing\TnaTemplateDtlRepository;
use App\Library\Template;
use App\Http\Requests\Planing\TnaTemplateDtlRequest;

class TnaTemplateDtlController extends Controller {

    private $tnaprogressdelay;
    private $tnatemplatedtl;
    private $tnatask;

    public function __construct(
        TnaProgressDelayRepository $tnaprogressdelay,
        TnaTemplateDtlRepository $tnatemplatedtl,
        TnataskRepository $tnatask
    ) {
        $this->tnaprogressdelay = $tnaprogressdelay;
        $this->tnatemplatedtl = $tnatemplatedtl;
        $this->tnatask = $tnatask;

        $this->middleware('auth');
        /* $this->middleware('permission:view.tnatemplatedtls',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.tnatemplatedtls', ['only' => ['store']]);
        $this->middleware('permission:edit.tnatemplatedtls',   ['only' => ['update']]);
        $this->middleware('permission:delete.tnatemplatedtls', ['only' => ['destroy']]); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $tnatask=array_prepend(array_pluck($this->tnatask->get(),'task_name','id'),'','');
        $startendbasis=array_prepend(config('bprs.startendbasis'), '-Select-','');
        $tnatemplatedtls=array();
        $rows=$this->tnatemplatedtl
        ->where([['tna_template_id','=',request('tna_template_id',0)]])
        ->orderBy('id','desc')
        ->get();
        foreach($rows as $row){
            $tnatemplatedtl['id']=$row->id;
            $tnatemplatedtl['task_name']=$tnatask[$row->tnatask_id];
            $tnatemplatedtl['depending_task_id']=$tnatask[$row->depending_task_id];
            $tnatemplatedtl['lead_days']=$row->lead_days;
            $tnatemplatedtl['lag_days']=$row->lag_days;
            $tnatemplatedtl['start_end_basis_id']=$startendbasis[$row->start_end_basis_id];
            $tnatemplatedtl['start_end_basis_days']=$row->start_end_basis_days;
            $tnatemplatedtl['start_reminder_days']=$row->start_reminder_days;
            $tnatemplatedtl['end_reminder_days']=$row->end_reminder_days;

            array_push($tnatemplatedtls, $tnatemplatedtl);
        }
        echo json_encode($tnatemplatedtls);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TnaTemplateDtlRequest $request) {
        $tnatemplatedtl=$this->tnatemplatedtl->create($request->except(['id']));
        if($tnatemplatedtl){
            return response()->json(array('success'=>true,'id'=>$tnatemplatedtl->id,'message'=>'Save Successfully'),200);
        }
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
       $tnatemplatedtl=$this->tnatemplatedtl->find($id);
       $row['fromData']=$tnatemplatedtl;
       $dropdown['att'] = '';
       $row ['dropDown'] = $dropdown;
       echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    

    public function update(TnaTemplateDtlRequest $request, $id) {
        $tnatemplatedtl=$this->tnatemplatedtl->update($id,$request->except(['id']));
        if($tnatemplatedtl){
            return response()->json(array('success'=>true,'id'=>$id,'message'=>'Update Successfully'),200);
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->tnatemplatedtl->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}       
    }

}