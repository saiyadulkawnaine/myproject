<?php

namespace App\Http\Controllers\Subcontract\Inbound;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbOrderRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbOrderFileRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Inbound\SubInbOrderFileRequest;

class SubInbOrderFileController extends Controller {

    private $subinborderfile;
    private $subinborder;
    


    public function __construct(SubInbOrderFileRepository $subinborderfile,SubInbOrderRepository $subinborder) {
        $this->subinborderfile = $subinborderfile;
        $this->subinborder = $subinborder;


        $this->middleware('auth');
            $this->middleware('permission:view.subinborderfiles',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.subinborderfiles', ['only' => ['store']]);
            $this->middleware('permission:edit.subinborderfiles',   ['only' => ['update']]);
            $this->middleware('permission:delete.subinborderfiles', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $subinborderfiles=array();
        $rows=$this->subinborderfile
        ->where([['sub_inb_order_id','=',request('sub_inb_order_id',0)]])->get();
        foreach($rows as $row){
            $subinborderfile['id']=$row->id;
            $subinborderfile['sub_inb_order_id']=$row->sub_inb_order_id;
            $subinborderfile['file_src']=$row->file_src;

            array_push($subinborderfiles, $subinborderfile);
        }
        echo json_encode($subinborderfiles);

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
    public function store(SubInbOrderFileRequest $request) {

        if($request->id)
        {
            $this->update($request, $request->id);
                return response()->json(array('success'=>true,'id'=>$request->id,'message'=>'Update Successfully'),200);

        }
        else{
            $name =time().'.'.$request->file_src->getClientOriginalExtension();
            $request->file_src->move(public_path('images'), $name);
            $subinborderfile=$this->subinborderfile->create([
            'sub_inb_order_id'=>$request->sub_inb_order_id,
            'file_src'=> $name,
            ]);
            if($subinborderfile){
            return response()->json(array('success'=>true,'id'=>$subinborderfile->id,'message'=>'Save Successfully'),200);
            }
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
       $subinborderfile=$this->subinborderfile->find($id);
       $row['fromData']=$subinborderfile;
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
    public function update(SubInbOrderFileRequest $request, $id) {
        $name =time().'.'.$request->file_src->getClientOriginalExtension();
        $request->file_src->move(public_path('images'), $name);
        $subinborderfile=$this->subinborderfile->update($request->id,[
        'sub_inb_order_id'=>$request->sub_inb_order_id,
        'file_src'=> $name,
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->subinborderfile->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }

}
