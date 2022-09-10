<?php

namespace App\Http\Controllers\Production\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishMcSetupRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishMcDateRepository;
use App\Library\Template;
use Illuminate\Support\Carbon;
use App\Http\Requests\Production\Dyeing\ProdFinishMcDateRequest;

class ProdFinishMcDateController extends Controller {

    private $prodfinishmcdate;
    private $prodfinishmcsetup;

    public function __construct(
        ProdFinishMcDateRepository $prodfinishmcdate,
        ProdFinishMcSetupRepository $prodfinishmcsetup
    ) {
        $this->prodfinishmcdate = $prodfinishmcdate;
        $this->prodfinishmcsetup = $prodfinishmcsetup;

        $this->middleware('auth');
        // $this->middleware('permission:view.prodfinishmcdates',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.prodfinishmcdates', ['only' => ['store']]);
        // $this->middleware('permission:edit.prodfinishmcdates',   ['only' => ['update']]);
        // $this->middleware('permission:delete.prodfinishmcdates', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $prodfinishmcdates = array();
        $rows = $this->prodfinishmcdate
        ->where([['prod_finish_mc_setup_id','=',request('prod_finish_mc_setup_id',0)]])
            ->orderBy('prod_finish_mc_dates.id', 'desc')
            ->get();
        foreach ($rows as $row) {
            $prodfinishmcdate['id'] = $row->id;
            $prodfinishmcdate['target_date'] = date('Y-m-d',strtotime($row->target_date));
            $prodfinishmcdate['adjusted_minute'] = $row->adjusted_minute;
            $prodfinishmcdate['remarks'] = $row->remarks;
            array_push($prodfinishmcdates, $prodfinishmcdate);
        }
        echo json_encode($prodfinishmcdates);
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
    public function store(ProdFinishMcDateRequest $request) {
         $prodfinishmcdate = $this->prodfinishmcdate->create($request->except(['id']));
        if($prodfinishmcdate){
            return response()->json(array('success' => true,'id' =>  $prodfinishmcdate->id,'message' => 'Save Successfully'),200);
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
        $prodfinishmcdate=$this->prodfinishmcdate->find($id);
        $row ['fromData'] = $prodfinishmcdate;
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
    public function update(ProdFinishMcDateRequest $request, $id) {
        $prodfinishmcdate = $this->prodfinishmcdate->update($id,$request->except(['id']));
        if($prodfinishmcdate){
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
        if($this->prodfinishmcdate->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}
