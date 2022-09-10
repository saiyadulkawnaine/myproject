<?php

namespace App\Http\Controllers\Commercial\Import;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Import\ImpLcFileRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Import\ImpLcFileRequest;

class ImpLcFileController extends Controller {

    private $implcfile;
    private $implc;


    public function __construct(ImpLcFileRepository $implcfile, ImpLcRepository $implc) {
        $this->implcfile = $implcfile;
        $this->implc = $implc; 


        $this->middleware('auth');
        // $this->middleware('permission:view.implcfiles',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.implcfiles', ['only' => ['store']]);
        // $this->middleware('permission:edit.implcfiles',   ['only' => ['update']]);
        // $this->middleware('permission:delete.implcfiles', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $implc=array_prepend(array_pluck($this->implc->get(),'name','id'),'-Select-','');

        $implcfiles=array();
        $rows=$this->implcfile->where([['imp_lc_id','=',request('imp_lc_id',0)]])->get();
        foreach($rows as $row){
            $implcfile['id']=$row->id;
            $implcfile['imp_lc_id']=$implc[$row->imp_lc_id];
            $implcfile['file_src']=$row->file_src;
            $implcfile['original_name']=$row->original_name;

            array_push($implcfiles, $implcfile);
        }
        echo json_encode($implcfiles);

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
    public function store(ImpLcFileRequest $request) {
        if($request->id){
           $this->update($request, $request->id);
           return response()->json(array('success'=>true,'id'=>$request->id,'message'=>'Update Successfully'),200);
        }
        else{
            $name =time().'.'.$request->file_src->getClientOriginalExtension();
            $request->file_src->move(public_path('images'), $name);
            $implcfile=$this->implcfile->create([
            'imp_lc_id'=>$request->imp_lc_id,
            'file_src'=> $name,
            'original_name'=> $request->original_name,
            ]);
            if($implcfile){
            return response()->json(array('success'=>true,'id'=>$implcfile->id,'message'=>'Save Successfully'),200);
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
       $implcfile=$this->implcfile->find($id);
       $row['fromData']=$implcfile;
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
    public function update(ImpLcFileRequest $request, $id) {    
        $name =time().'.'.$request->file_src->getClientOriginalExtension();
        $request->file_src->move(public_path('images'), $name);   
        $implcfile=$this->implcfile->update($request->id,[
            'imp_lc_id'=>$request->imp_lc_id,
            'original_name'=> $request->original_name,
            'file_src'=>$name
            ]);
               
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->implcfile->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
        // else{
        //      return response()->json(array('success' => false, 'message' => 'Delete Not Successfull'), 200);
        // }
        
    }

}
