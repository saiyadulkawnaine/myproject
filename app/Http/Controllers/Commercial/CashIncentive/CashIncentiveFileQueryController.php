<?php
namespace App\Http\Controllers\Commercial\CashIncentive;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRefRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveFileRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveFileQueryRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\CashIncentive\CashIncentiveFileQueryRequest;



class CashIncentiveFileQueryController extends Controller {

    private $incentivefilequery;
    private $cashincentivefile;
    private $cashincentiveref;


    public function __construct(CashIncentiveFileQueryRepository $incentivefilequery,CashIncentiveFileRepository $cashincentivefile, CashIncentiveRefRepository $cashincentiveref) {
        $this->incentivefilequery = $incentivefilequery;
        $this->cashincentivefile = $cashincentivefile;
        $this->cashincentiveref = $cashincentiveref;


        $this->middleware('auth');

        $this->middleware('permission:view.cashincentivefilequeries',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.cashincentivefilequeries', ['only' => ['store']]);
        $this->middleware('permission:edit.cashincentivefilequeries',   ['only' => ['update']]);
        $this->middleware('permission:delete.cashincentivefilequeries', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //$filequery = array_prepend(config('bprs.filequery'), '-Select-','');

        $incentivefilequerys=array();
        $rows=$this->incentivefilequery
            ->leftJoin('cash_incentive_files',function($join){
                $join->on('cash_incentive_file_queries.cash_incentive_file_id','=','cash_incentive_files.id');
            })
            ->where([['cash_incentive_file_queries.cash_incentive_file_id','=',request('cash_incentive_file_id',0)]])
            ->orderBy('cash_incentive_file_queries.id','desc')
            ->get([
                'cash_incentive_file_queries.*'
            ]);
        
        foreach($rows as $row){
            $incentivefilequery['id']=$row->id;
            $incentivefilequery['query_date']=date('d-M-Y',strtotime($row->query_date));
            $incentivefilequery['query_remarks']=$row->query_remarks;

            array_push($incentivefilequerys,$incentivefilequery);
        }
        echo json_encode($incentivefilequerys);
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
    public function store(CashIncentiveFileQueryRequest $request) {
        $incentivefilequery=$this->incentivefilequery->create([
            'cash_incentive_file_id'=>$request->cash_incentive_file_id,
            'query_date'=>$request->query_date,
            'query_remarks'=>$request->query_remarks
            //'query_detail_id'=>$request->query_detail_id
        ]);
        if($incentivefilequery){
            return response()->json(array('success' => true,'id' =>  $incentivefilequery->id,'message' => 'Save Successfully'),200);
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
       $incentivefilequery = $this->incentivefilequery->find($id);
       $row ['fromData'] = $incentivefilequery;
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
    public function update(CashIncentiveFileQueryRequest $request, $id) {
        $incentivefilequery=$this->incentivefilequery->update($id,[
            'cash_incentive_file_id'=>$request->cash_incentive_file_id,
            'query_date'=>$request->query_date,
            'query_remarks'=>$request->query_remarks
           // 'query_detail_id'=>$request->query_detail_id
        ]);
        if($incentivefilequery){
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
        if($this->incentivefilequery->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}
