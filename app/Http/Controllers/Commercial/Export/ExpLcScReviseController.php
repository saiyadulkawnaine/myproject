<?php
namespace App\Http\Controllers\Commercial\Export;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScReviseRepository;

use App\Library\Template;
use App\Http\Requests\Commercial\Export\ExpLcScReviseRequest;

class ExpLcScReviseController extends Controller {

    private $explcsc;
    private $explcscrevise;

    public function __construct(ExpLcScReviseRepository $explcscrevise, ExpLcScRepository $explcsc) {
        $this->explcsc = $explcsc;
        $this->explcscrevise = $explcscrevise;

        $this->middleware('auth');
        // $this->middleware('permission:view.explcorders',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.explcorders', ['only' => ['store']]);
        // $this->middleware('permission:edit.explcorders',   ['only' => ['update']]);
        // $this->middleware('permission:delete.explcorders', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $explcscrevise=$this->explcscrevise
        ->where([['exp_lc_sc_id','=',request('exp_lc_sc_id', 0)]])
        ->get(['exp_lc_sc_revises.*']);

        echo json_encode($explcscrevise);
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
    public function store(ExpLcScReviseRequest $request) {
        $explcscrevise=$this->explcscrevise->updateOrCreate(
            [ 'exp_lc_sc_id'=>$request->exp_lc_sc_id ],
            [
                'last_amendment_date'=>$request->last_amendment_date,
                'amount'=>$request->amount,
                'remarks'=>$request->remarks
            ]);
            
           // $explcsc=$this->explcsc->find($request->exp_lc_sc_id);
           // $revised_lc_value=($explcsc->lc_sc_value+$request->amount)*1;
           // $revised_explcsc=$this->explcsc->where([['id','=',$request->exp_lc_sc_id]])->update(['lc_sc_value'=>$revised_explcsc]);

            return response()->json(array('success'=>true,'id'=>$explcscrevise->id,'message'=>'Save Successfully'),200);
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
        $explcscrevise=$this->explcscrevise
        ->where([['exp_lc_sc_revises.exp_lc_sc_id' ,'=',$id]])
        ->get()
        ->first();
        $row['fromData']=$explcscrevise;
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
    public function update(ExpLcScReviseRequest $request, $id) {
        $explcscrevise=$this->explcscrevise->update($id,$request->except(['id']));
       // $explcsc=$this->explcsc->find($request->exp_lc_sc_id);
       // $revised_lc_value=($explcsc->lc_sc_value+$request->amount)*1;
       // $revised_explcsc=$this->explcsc->where([['id','=',$request->exp_lc_sc_id]])->update(['lc_sc_value'=>$revised_lc_value]);
        if($explcscrevise){
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
        if($this->explcscrevise->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}