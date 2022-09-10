<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Library\Template;
use App\Http\Requests\CommercialHeadRequest;

class CommercialHeadController extends Controller {

    private $commercialhead;
    
    public function __construct(CommercialHeadRepository $commercialhead) {
        $this->commercialhead = $commercialhead;
        
        $this->middleware('auth');
        $this->middleware('permission:view.commercialheads',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.commercialheads', ['only' => ['store']]);
        $this->middleware('permission:edit.commercialheads',   ['only' => ['update']]);
        $this->middleware('permission:delete.commercialheads', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $commercialheads=array();
        $commercialheadtype=array_prepend(config('bprs.commercialheadtype'),'-Select-',0);
            $rows=$this->commercialhead
            ->orderBy('commercial_heads.id','desc')
            ->get();
            foreach ($rows as $row){
                $commercialhead['id']=$row->id;
                $commercialhead['name']=$row->name;
                $commercialhead['commercialhead_type_id']=$row->commercialhead_type_id?$commercialheadtype[$row->commercialhead_type_id]:'--';
                $commercialhead['sort_id']=$row->sort_id;
                array_push($commercialheads,$commercialhead);
            }
        echo json_encode($commercialheads);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $commercialheadtype=array_prepend(config('bprs.commercialheadtype'),'-Select-',0);
		return Template::loadView('Util.CommercialHead', ['commercialheadtype'=>$commercialheadtype]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommercialHeadRequest $request) {
		$commercialhead=$this->commercialhead->create($request->except(['id']));
        if($commercialhead){
            return response()->json(array('success'=>true,'id'=>$commercialhead->id,'message'=>'Save Successfully'),200);
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
        $commercialhead = $this->commercialhead->find($id);
        $row ['fromData'] = $commercialhead;
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
    public function update(CommercialHeadRequest $request, $id) {
        $commercialhead=$this->commercialhead->update($id,$request->except(['id']));
        if($commercialhead){
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
        if($this->commercialhead->delete($id)){
            return response()->json(array('success'=>true,'message' => 'Delete Successfully'),200);
        }
    }

}
