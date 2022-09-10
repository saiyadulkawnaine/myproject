<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeMovementDtlRepository;
use App\Repositories\Contracts\HRM\EmployeeMovementRepository;
use App\Library\Template;
use App\Http\Requests\HRM\EmployeeMovementDtlRequest;

class EmployeeMovementDtlController extends Controller {

    private $employeemovement;
    private $employeemovementdtl;

    public function __construct(EmployeeMovementDtlRepository $employeemovementdtl, EmployeeMovementRepository $employeemovement) {
        $this->employeemovementdtl = $employeemovementdtl;
        $this->employeemovement = $employeemovement;

        $this->middleware('auth');
        // $this->middleware('permission:view.employeemovementdtls',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.employeemovementdtls', ['only' => ['store']]);
        // $this->middleware('permission:edit.employeemovementdtls',   ['only' => ['update']]);
        // $this->middleware('permission:delete.employeemovementdtls', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $purpose = array_prepend(config('bprs.purpose'),'-Select-','');
        $transportmode = array_prepend(config('bprs.transportmode'),'-Select-','');
        $employeemovementdtls=array();
        $rows=$this->employeemovementdtl
        ->where([['employee_movement_id','=',request('employee_movement_id',0)]])
        ->orderBy('employee_movement_dtls.id','desc')
        ->get();
        foreach($rows as $row){
            $employeemovementdtl['id']=$row->id; 
            $employeemovementdtl['work_detail']=$row->work_detail; 
            $employeemovementdtl['purpose_id']=isset($purpose[$row->purpose_id])?$purpose[$row->purpose_id]:'';
            $employeemovementdtl['out_date']=date('Y-m-d',strtotime($row->out_date_time));
            $employeemovementdtl['out_time']=date('h:i:s A',strtotime($row->out_date_time));
            $employeemovementdtl['return_date']=($row->return_date_time!==null)?date('Y-m-d',strtotime($row->return_date_time)):null;
            $employeemovementdtl['return_time']=($row->return_date_time!==null)?date('h:i:s A',strtotime($row->return_date_time)):null;
            $employeemovementdtl['destination']=$row->destination;
            $employeemovementdtl['amount']=$row->amount;
            $employeemovementdtl['transport_mode_id']=($transportmode[$row->transport_mode_id])?$transportmode[$row->transport_mode_id]:''; 
            
           array_push($employeemovementdtls,$employeemovementdtl);
        }
        echo json_encode($employeemovementdtls);
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
    public function store(EmployeeMovementDtlRequest $request) {
        $req=$this->employeemovement->find($request->employee_movement_id);
        if($req->approved_by){
            return response()->json(array('success' => false,'message' => 'This Entry is approved so insert new item is not allowed'),200);
        }

        $out_date_time=date('Y-m-d H:i:s',strtotime($request->out_date." ".$request->out_time));
        if (!$request->return_date && !$request->return_time) {
            $return_date_time='';
        }else {
            $return_date_time=date('Y-m-d H:i:s',strtotime($request->return_date." ".$request->return_time));
        }
        
		$employeemovementdtl=$this->employeemovementdtl->create([
            'employee_movement_id'=>$request->employee_movement_id,
            'out_date_time'=>$out_date_time,
            'return_date_time'=>$return_date_time,
            'purpose_id'=>$request->purpose_id,
            'work_detail'=>$request->work_detail,
            'destination'=>$request->destination,
            'amount'=>$request->amount,
            'transport_mode_id'=>$request->transport_mode_id,
            'ta_da_amount'=>$request->ta_da_amount,
            'out_date'=>$request->out_date,
            'return_date'=>$request->return_date,
        ]);
		if($employeemovementdtl){
			return response()->json(array('success' => true,'id' =>  $employeemovementdtl->id,'message' => 'Save Successfully'),200);
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
       $employeemovementdtl = $this->employeemovementdtl->find($id);
       $employeemovementdtl->out_date=date('Y-m-d',strtotime($employeemovementdtl->out_date_time));
       $employeemovementdtl->out_time=date('H:i:s A',strtotime($employeemovementdtl->out_date_time));
       $employeemovementdtl->return_date=($employeemovementdtl->return_date_time!==null)?date('Y-m-d',strtotime($employeemovementdtl->return_date_time)):null;
       $employeemovementdtl->return_time=($employeemovementdtl->return_date_time!==null)?date('H:i:s A',strtotime($employeemovementdtl->return_date_time)):null;
	   $row ['fromData'] = $employeemovementdtl;
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
    public function update(EmployeeMovementDtlRequest $request, $id) {
        $req=$this->employeemovement->find($request->employee_movement_id);
        if($req->approved_by){
            return response()->json(array('success' => false,'message' => 'This Entry is approved so update not allowed'),200);

        }else{
            $out_date_time=date('Y-m-d H:i:s',strtotime($request->out_date." ".$request->out_time));
            if (!$request->return_date && !$request->return_time) {
                $return_date_time='';
            }else{
                $return_date_time=date('Y-m-d H:i:s',strtotime($request->return_date." ".$request->return_time));
            }
            $employeemovementdtl=$this->employeemovementdtl->update($id,
            [
                'employee_movement_id'=>$request->employee_movement_id,
                'out_date_time'=>$out_date_time,
                'return_date_time'=>$return_date_time,
                'purpose_id'=>$request->purpose_id,
                'work_detail'=>$request->work_detail,
                'destination'=>$request->destination,
                'amount'=>$request->amount,
                'transport_mode_id'=>$request->transport_mode_id,
                'ta_da_amount'=>$request->ta_da_amount,
                'out_date'=>$request->out_date,
                'return_date'=>$request->return_date,
                ]
            );
            if($employeemovementdtl){
                return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
            } 
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->employeemovementdtl->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}