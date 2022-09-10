<?php

namespace App\Http\Controllers\Util\Renewal;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\Renewal\RenewalItemRepository;
use App\Library\Template;
use App\Http\Requests\Util\Renewal\RenewalItemRequest;

class RenewalItemController extends Controller {

    private $renewalitem;
    
    public function __construct(RenewalItemRepository $renewalitem) {
        $this->renewalitem = $renewalitem;
		
        
        $this->middleware('auth');
        /* $this->middleware('permission:view.renewalitems',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.renewalitems', ['only' => ['store']]);
        $this->middleware('permission:edit.renewalitems',   ['only' => ['update']]);
        $this->middleware('permission:delete.renewalitems', ['only' => ['destroy']]); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $month=config('bprs.months');

        $renewalitems=array();
        $rows=$this->renewalitem
        ->orderBy('renewal_items.id','desc')
        ->get();
        foreach ($rows as $row){
            $renewalitem['id']=$row->id;
            $renewalitem['renewal_item']=$row->renewal_item;
            $renewalitem['code']=$row->code;
            $renewalitem['tenure_start']=$month[$row->tenure_start];
            $renewalitem['tenure_end']=$month[$row->tenure_end];
            $renewalitem['days']=$row->days;
            $renewalitem['sort_id']=$row->sort_id;
            array_push($renewalitems,$renewalitem);
        }
        echo json_encode($renewalitems);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $month=array_prepend(config('bprs.months'),'-Select-','');
		return Template::loadView('Util.Renewal.RenewalItem', ['month'=>$month]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RenewalItemRequest $request) {
		$renewalitem=$this->renewalitem->create($request->except(['id']));
        if($renewalitem){
            return response()->json(array('success'=>true,'id'=>$renewalitem->id,'message'=>'Save Successfully'),200);
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
        $renewalitem = $this->renewalitem->find($id);
        $row ['fromData'] = $renewalitem;
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
    public function update(RenewalItemRequest $request, $id) {
        $renewalitem=$this->renewalitem->update($id,$request->except(['id']));
        if($renewalitem){
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
        if($this->renewalitem->delete($id)){
            return response()->json(array('success'=>true,'message' => 'Delete Successfully'),200);
        }
    }

}
