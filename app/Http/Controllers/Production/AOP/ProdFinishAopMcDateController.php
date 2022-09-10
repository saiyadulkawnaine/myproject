<?php

namespace App\Http\Controllers\Production\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\AOP\ProdFinishAopMcSetupRepository;
use App\Repositories\Contracts\Production\AOP\ProdFinishAopMcDateRepository;
use App\Library\Template;
use Illuminate\Support\Carbon;
use App\Http\Requests\Production\AOP\ProdFinishAopMcDateRequest;

class ProdFinishAopMcDateController extends Controller {

    private $prodfinishaopmcdate;
    private $prodfinishaopmcsetup;

    public function __construct(ProdFinishAopMcDateRepository $prodfinishaopmcdate,ProdFinishAopMcSetupRepository $prodfinishaopmcsetup) {
        $this->prodfinishaopmcdate = $prodfinishaopmcdate;
        $this->prodfinishaopmcsetup = $prodfinishaopmcsetup;

        $this->middleware('auth');
        // $this->middleware('permission:view.prodaopmcdates',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.prodaopmcdates', ['only' => ['store']]);
        // $this->middleware('permission:edit.prodaopmcdates',   ['only' => ['update']]);
        // $this->middleware('permission:delete.prodaopmcdates', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $prodfinishaopmcdates = array();
        $rows = $this->prodfinishaopmcdate
        ->where([['prod_finish_aop_mc_setup_id','=',request('prod_finish_aop_mc_setup_id',0)]])
        ->orderBy('prod_finish_aop_mc_dates.id', 'desc')
        ->get();
        foreach ($rows as $row) {
            $prodfinishaopmcdate['id'] = $row->id;
            $prodfinishaopmcdate['target_date'] = date('Y-m-d',strtotime($row->target_date));
            $prodfinishaopmcdate['remarks'] = $row->remarks;
            $prodfinishaopmcdate['adjusted_minute'] = $row->adjusted_minute;
            array_push($prodfinishaopmcdates, $prodfinishaopmcdate);
        }
        echo json_encode($prodfinishaopmcdates);
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
    public function store(ProdFinishAopMcDateRequest $request) {
         $prodfinishaopmcdate = $this->prodfinishaopmcdate->create($request->except(['id']));
        if($prodfinishaopmcdate){
            return response()->json(array('success' => true,'id' =>  $prodfinishaopmcdate->id,'message' => 'Save Successfully'),200);
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
        $prodfinishaopmcdate=$this->prodfinishaopmcdate->find($id);
        $row ['fromData'] = $prodfinishaopmcdate;
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
    public function update(ProdFinishAopMcDateRequest $request, $id) {
        $prodfinishaopmcdate = $this->prodfinishaopmcdate->update($id,$request->except(['id']));
        if($prodfinishaopmcdate){
            return response()->json(array('success' => true,'id'=>$id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->prodfinishaopmcdate->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}
