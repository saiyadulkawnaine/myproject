<?php

namespace App\Http\Controllers\System\Configuration;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\System\Configuration\ExpDocPrepStdDayRepository;
use App\Library\Template;
use App\Http\Requests\System\Configuration\ExpDocPrepStdDayRequest;


class ExpDocPrepStdDayController extends Controller {

    private $expdocprepstdday;
    private $company;

    public function __construct(ExpDocPrepStdDayRepository $expdocprepstdday,
    CompanyRepository $company) {
        $this->expdocprepstdday = $expdocprepstdday;
        $this->company = $company;
		
        
        $this->middleware('auth');
        // $this->middleware('permission:view.expdocprepstddays',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.expdocprepstddays', ['only' => ['store']]);
        // $this->middleware('permission:edit.expdocprepstddays',   ['only' => ['update']]);
        // $this->middleware('permission:delete.expdocprepstddays', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $expdocprogressevent=array_prepend(config('bprs.expdocprogressevent'), '-Select-','');
        $status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-',''); 
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $expdocprepstddays=array();
            $rows=$this->expdocprepstdday
            ->orderBy('id','desc')
            ->get();
            foreach ($rows as $row){
                $expdocprepstdday['id']=$row->id;
                $expdocprepstdday['company_id']=$company[$row->company_id];
                $expdocprepstdday['status_id']=$status[$row->status_id];
                $expdocprepstdday['standard_days']=$row->standard_days;
                $expdocprepstdday['exp_doc_progress_event_id']=$expdocprogressevent[$row->exp_doc_progress_event_id];
                array_push($expdocprepstddays,$expdocprepstdday);
            }
        echo json_encode($expdocprepstddays);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$expdocprogressevent=array_prepend(config('bprs.expdocprogressevent'), '-Select-','');
        $status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-',''); 
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		return Template::loadView('System.Configuration.ExpDocPrepStdDay', ['company'=>$company,'expdocprogressevent'=>$expdocprogressevent,'status'=>$status]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpDocPrepStdDayRequest $request) {
		$expdocprepstdday=$this->expdocprepstdday->create($request->except(['id']));
        if($expdocprepstdday){
            return response()->json(array('success'=>true,'id'=>$expdocprepstdday->id,'message'=>'Save Successfully'),200);
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
        $expdocprepstdday = $this->expdocprepstdday->find($id);
        $row ['fromData'] = $expdocprepstdday;
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
    public function update(ExpDocPrepStdDayRequest $request, $id) {
        $expdocprepstdday=$this->expdocprepstdday->update($id,$request->except(['id']));
        if($expdocprepstdday){
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
        if($this->expdocprepstdday->delete($id)){
            return response()->json(array('success'=>true,'message' => 'Delete Successfully'),200);
        }
    }

}
