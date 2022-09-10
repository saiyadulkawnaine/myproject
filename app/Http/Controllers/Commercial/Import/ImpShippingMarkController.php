<?php

namespace App\Http\Controllers\Commercial\Import;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Import\ImpShippingMarkRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Import\ImpShippingMarkRequest;

class ImpShippingMarkController extends Controller {

    private $impshipmark;
    private $implc;

    public function __construct(ImpShippingMarkRepository $impshipmark,ImpLcRepository $implc) {
        $this->impshipmark = $impshipmark;
        $this->implc = $implc;

        $this->middleware('auth');
        $this->middleware('permission:view.impshippingmarks',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.impshippingmarks', ['only' => ['store']]);
        $this->middleware('permission:edit.impshippingmarks',   ['only' => ['update']]);
        $this->middleware('permission:delete.impshippingmarks', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       $impshipmarks = array();
       $rows = $this->impshipmark->where([['imp_lc_id','=',request('imp_lc_id',0)]])->get();
       foreach($rows as $row){
         $impshipmark['id']=$row->id;
         $impshipmark['shipping_mark']=$row->shipping_mark;
         array_push($impshipmarks,$impshipmark);
       }
       echo json_encode($impshipmarks);
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
    public function store(ImpShippingMarkRequest $request) {
      $impshipmark=$this->impshipmark->create($request->except(['id']));
        if($impshipmark){
            return response()->json(array('success' => true,'id' =>  $impshipmark->id,'message' => 'Save Successfully'),200);
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
        $impshipmark=$this->impshipmark->find($id);
        $row['fromData']=$impshipmark;
        $dropdown['att']='';
        $row['dropDown']=$dropdown;
        echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ImpShippingMarkRequest $request, $id) {
        $impshipmark=$this->impshipmark->update($id,$request->except(['id']));
        if($impshipmark){
           return response()->json(array('success'=>true,'id'=>$id,'message'=>'Updated Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->impshipmark->delete($id)){
           return response()->json(array('success'=>true,'message'=>'Delete Successfully'),200);
        }
    }

}
