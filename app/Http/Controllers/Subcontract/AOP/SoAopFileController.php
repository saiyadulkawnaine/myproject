<?php

namespace App\Http\Controllers\Subcontract\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFileRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\AOP\SoAopFileRequest;

class SoAopFileController extends Controller {

    private $soaopfile;
    private $soaop;
    


    public function __construct(SoAopFileRepository $soaopfile,SoAopRepository $soaop) {
        $this->soaopfile = $soaopfile;
        $this->soaop = $soaop;
        $this->middleware('auth');

        
        $this->middleware('permission:view.soaopfiles',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.soaopfiles', ['only' => ['store']]);
        $this->middleware('permission:edit.soaopfiles',   ['only' => ['update']]);
        $this->middleware('permission:delete.soaopfiles', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $soaopfiles=array();
        $rows=$this->soaopfile
        ->where([['so_aop_id','=',request('so_aop_id',0)]])->get();
        foreach($rows as $row){
            $soaopfile['id']=$row->id;
            $soaopfile['so_aop_id']=$row->so_aop_id;
            $soaopfile['file_src']=$row->file_src;

            array_push($soaopfiles, $soaopfile);
        }
        echo json_encode($soaopfiles);

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
    public function store(SoAopFileRequest $request) {

        if($request->id)
        {
            $this->update($request, $request->id);
            return response()->json(array('success'=>true,'id'=>$request->id,'message'=>'Update Successfully'),200);
        }
        else{
            $name =time().'.'.$request->file_src->getClientOriginalExtension();
            $request->file_src->move(public_path('images'), $name);
            $soaopfile=$this->soaopfile->create([
            'so_aop_id'=>$request->so_aop_id,
            'file_src'=> $name,
            ]);
            if($soaopfile){
            return response()->json(array('success'=>true,'id'=>$soaopfile->id,'message'=>'Save Successfully'),200);
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
       $soaopfile=$this->soaopfile->find($id);
       $row['fromData']=$soaopfile;
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
    public function update(SoAopFileRequest $request, $id) {
        $name =time().'.'.$request->file_src->getClientOriginalExtension();
        $request->file_src->move(public_path('images'), $name);
        $soaopfile=$this->soaopfile->update($request->id,[
        'so_aop_id'=>$request->so_aop_id,
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
        if($this->soaopfile->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }

}
