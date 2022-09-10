<?php

namespace App\Http\Controllers\Util\Renewal;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\Renewal\RenewalItemRepository;
use App\Repositories\Contracts\Util\Renewal\RenewalItemDocRepository;

use App\Library\Template;
use App\Http\Requests\Util\Renewal\RenewalItemDocRequest;

class RenewalItemDocController extends Controller {

    private $renewalitemdoc;
    private $renewalitem;
    
    

    public function __construct(RenewalItemDocRepository $renewalitemdoc,RenewalItemRepository $renewalitem) {
        $this->renewalitemdoc = $renewalitemdoc;
		$this->renewalitem = $renewalitem;
        
        $this->middleware('auth');
        /* $this->middleware('permission:view.renewalitemdocs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.renewalitemdocs', ['only' => ['store']]);
        $this->middleware('permission:edit.renewalitemdocs',   ['only' => ['update']]);
        $this->middleware('permission:delete.renewalitemdocs', ['only' => ['destroy']]); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $renewalitemdocs=array();
        
            $rows=$this->renewalitemdoc
            ->where([['renewal_item_id','=',request('renewal_item_id',0)]])
            ->orderBy('renewal_item_docs.id','desc')
            ->get();
            foreach ($rows as $row){
                $renewalitemdoc['id']=$row->id;       
                $renewalitemdoc['name']=$row->name;    
                $renewalitemdoc['sort_id']=$row->sort_id;
                array_push($renewalitemdocs,$renewalitemdoc);
            }
        echo json_encode($renewalitemdocs);
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
    public function store(RenewalItemDocRequest $request) {
		$renewalitemdoc=$this->renewalitemdoc->create($request->except(['id']));
        if($renewalitemdoc){
            return response()->json(array('success'=>true,'id'=>$renewalitemdoc->id,'message'=>'Save Successfully'),200);
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
        $renewalitemdoc = $this->renewalitemdoc->find($id);
        $row ['fromData'] = $renewalitemdoc;
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
    public function update(RenewalItemDocRequest $request, $id) {
        $renewalitemdoc=$this->renewalitemdoc->update($id,$request->except(['id']));
        if($renewalitemdoc){
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
        if($this->renewalitemdoc->delete($id)){
            return response()->json(array('success'=>true,'message' => 'Delete Successfully'),200);
        }
    }

}
