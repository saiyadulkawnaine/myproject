<?php

namespace App\Http\Controllers\Production\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchProcessRepository;


use App\Library\Template;
use App\Http\Requests\Production\Dyeing\ProdBatchRdProcessRequest;

class ProdBatchRdProcessController extends Controller {

    private $prodbatch;
    private $prodbatchprocess;

    public function __construct(
        ProdBatchRepository $prodbatch,  
        ProdBatchProcessRepository $prodbatchprocess 

    ) {
        $this->prodbatch = $prodbatch;
        $this->prodbatchprocess = $prodbatchprocess;
        $this->middleware('auth');
        
        /*$this->middleware('permission:view.prodbatchrdprocesses',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodbatchrdprocesses', ['only' => ['store']]);
        $this->middleware('permission:edit.prodbatchrdprocesses',   ['only' => ['update']]);
        $this->middleware('permission:delete.prodbatchrdprocesses', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $prodbatchprocess=$this->prodbatch
        ->join('prod_batch_processes', function($join)  {
        $join->on('prod_batch_processes.prod_batch_id', '=', 'prod_batches.id');
        })
        ->join('production_processes', function($join)  {
        $join->on('production_processes.id', '=', 'prod_batch_processes.production_process_id');
        })
       
        ->where([['prod_batches.id','=',request('prod_batch_id')]])
        ->orderBy('prod_batch_processes.sort_id')
        ->get([
            'prod_batch_processes.*',
            'production_processes.process_name',
        ]);
        echo json_encode($prodbatchprocess);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdBatchRdProcessRequest $request) {
        $batch=$this->prodbatch->find($request->prod_batch_id);
        if($batch->approved_at){
        return response()->json(array('success' => false,'id' => $request->prod_batch_id,'message' => 'This Batch is Approved. Process Adding Not Allowed'),200);
        }
        $prodbatchprocess = $this->prodbatchprocess->create($request->except(['id']));

        if($prodbatchprocess){
            return response()->json(array('success' => true,'id' =>  $prodbatchprocess->id,'prod_batch_id'=>$request->prod_batch_id,'message' => 'Save Successfully'),200);
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
        $prodbatchprocess=$this->prodbatchprocess
        ->find($id);
        $row ['fromData'] = $prodbatchprocess;
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
    public function update(ProdBatchRdProcessRequest $request, $id) {
        $batch=$this->prodbatch->find($request->prod_batch_id);
        if($batch->approved_at){
        return response()->json(array('success' => false,'id' => $id,'message' => 'This Batch is Approved. Process Adding Not Allowed'),200);
        }
        
        $prodbatchprocess = $this->prodbatchprocess->update($id,$request->except(['id']));
        
        if($prodbatchprocess){
            return response()->json(array('success' => true,'id' => $id,'prod_batch_id'=>$request->prod_batch_id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $prodbatchprocess=$this->prodbatchprocess->find($id);
        $prodbatch=$this->prodbatch->find($prodbatchprocess->prod_batch_id);
        if($prodbatch->approved_at){
        return response()->json(array('success' => false,'id' => $id,'message' => 'This Batch is Approved. Delete Not Allowed'),200);
        }
        if($this->prodbatchprocess->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}