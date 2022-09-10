<?php

namespace App\Http\Controllers\Subcontract\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitFileRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Kniting\SoKnitFileRequest;

class SoKnitFileController extends Controller {

    private $soknitfile;
    private $soknit;
    


    public function __construct(SoKnitFileRepository $soknitfile,SoKnitRepository $soknit) {
        $this->soknitfile = $soknitfile;
        $this->soknit = $soknit;


       /*  $this->middleware('auth');
            $this->middleware('permission:view.soknitfiles',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.soknitfiles', ['only' => ['store']]);
            $this->middleware('permission:edit.soknitfiles',   ['only' => ['update']]);
            $this->middleware('permission:delete.soknitfiles', ['only' => ['destroy']]); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $soknitfiles=array();
        $rows=$this->soknitfile
        ->where([['so_knit_id','=',request('so_knit_id',0)]])->get();
        foreach($rows as $row){
            $soknitfile['id']=$row->id;
            $soknitfile['so_knit_id']=$row->so_knit_id;
            $soknitfile['file_src']=$row->file_src;

            array_push($soknitfiles, $soknitfile);
        }
        echo json_encode($soknitfiles);

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
    public function store(SoKnitFileRequest $request) {

        if($request->id)
        {
            $this->update($request, $request->id);
                return response()->json(array('success'=>true,'id'=>$request->id,'message'=>'Update Successfully'),200);

        }
        else{
            $name =time().'.'.$request->file_src->getClientOriginalExtension();
            $request->file_src->move(public_path('images'), $name);
            $soknitfile=$this->soknitfile->create([
            'so_knit_id'=>$request->so_knit_id,
            'file_src'=> $name,
            ]);
            if($soknitfile){
            return response()->json(array('success'=>true,'id'=>$soknitfile->id,'message'=>'Save Successfully'),200);
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
       $soknitfile=$this->soknitfile->find($id);
       $row['fromData']=$soknitfile;
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
    public function update(SoKnitFileRequest $request, $id) {
        $name =time().'.'.$request->file_src->getClientOriginalExtension();
        $request->file_src->move(public_path('images'), $name);
        $soknitfile=$this->soknitfile->update($request->id,[
        'so_knit_id'=>$request->so_knit_id,
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
        if($this->soknitfile->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }

}
