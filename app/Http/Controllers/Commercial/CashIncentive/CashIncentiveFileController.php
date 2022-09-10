<?php
namespace App\Http\Controllers\Commercial\CashIncentive;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRefRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveFileRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\CashIncentive\CashIncentiveFileRequest;



class CashIncentiveFileController extends Controller {

    private $cashincentivefile;
    private $cashincentiveref;


    public function __construct(CashIncentiveFileRepository $cashincentivefile, CashIncentiveRefRepository $cashincentiveref) {
        $this->cashincentivefile = $cashincentivefile;
        $this->cashincentiveref = $cashincentiveref;


        $this->middleware('auth');

        $this->middleware('permission:view.cashincentivefiles',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.cashincentivefiles', ['only' => ['store']]);
        $this->middleware('permission:edit.cashincentivefiles',   ['only' => ['update']]);
        $this->middleware('permission:delete.cashincentivefiles', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
         
        $cashincentivefiles=array();
        $rows=$this->cashincentivefile
        ->where([['cash_incentive_ref_id','=',request('cash_incentive_ref_id',0)]])
        ->orderBy('cash_incentive_files.id','desc')
        ->get();
        
        foreach($rows as $row){
            $cashincentivefile['id']=$row->id;
            $cashincentivefile['audit_submit_date']=$row->audit_submit_date?date('d-M-Y',strtotime($row->audit_submit_date)):null;
            $cashincentivefile['audit_report_date']=$row->audit_report_date?date('d-M-Y',strtotime($row->audit_report_date)):null;
            $cashincentivefile['bb_submit_date']=$row->bb_submit_date?date('d-M-Y',strtotime($row->bb_submit_date)):null;
            $cashincentivefile['bb_sanction_date']=$row->bb_sanction_date?date('d-M-Y',strtotime($row->bb_sanction_date)):null;
            $cashincentivefile['progress_bd_bank']=$row->progress_bd_bank;
            $cashincentivefile['amount']=number_format($row->amount,2);

            array_push($cashincentivefiles,$cashincentivefile);
        }
        echo json_encode($cashincentivefiles);
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
    public function store(CashIncentiveFileRequest $request) {
       $cashincentivefile=$this->cashincentivefile->updateOrCreate(
            [
                'cash_incentive_ref_id'=>$request->cash_incentive_ref_id
            ],
            [
            'audit_submit_date'=>$request->audit_submit_date,
            'audit_report_date'=>$request->audit_report_date,
            'bb_submit_date'=>$request->bb_submit_date,
            'bb_sanction_date'=>$request->bb_sanction_date,
            'amount'=>$request->amount,
            'progress_bd_bank'=>$request->progress_bd_bank
        ]);
        if($cashincentivefile){
            return response()->json(array('success' => true,'id' =>  $cashincentivefile->id,'message' => 'Save Successfully'),200);
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
       $cashincentivefile =  $this->cashincentivefile
       ->where([['cash_incentive_ref_id' ,'=',$id]])
       ->get()
       ->first();
       $row ['fromData'] = $cashincentivefile;
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
    public function update(CashIncentiveFileRequest $request, $id) {
        $cashincentivefile=$this->cashincentivefile->update($id,[
            'cash_incentive_ref_id'=>$request->cash_incentive_ref_id,
            'audit_submit_date'=>$request->audit_submit_date,
            'audit_report_date'=>$request->audit_report_date,
            'bb_submit_date'=>$request->bb_submit_date,
            'bb_sanction_date'=>$request->bb_sanction_date,
            'amount'=>$request->amount,
            'progress_bd_bank'=>$request->progress_bd_bank
        ]);
        if($cashincentivefile){
            return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->cashincentivefile->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}
