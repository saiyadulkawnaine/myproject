<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbFileRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbFileRequest;

class SoEmbFileController extends Controller {

    private $soembfile;
    private $soemb;
    


    public function __construct(SoEmbFileRepository $soembfile,SoEmbRepository $soemb) {
        $this->soembfile = $soembfile;
        $this->soemb = $soemb;


        $this->middleware('auth');
        // $this->middleware('permission:view.soembfiles',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.soembfiles', ['only' => ['store']]);
        // $this->middleware('permission:edit.soembfiles',   ['only' => ['update']]);
        // $this->middleware('permission:delete.soembfiles', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $soembfiles=array();
        $rows=$this->soembfile
        ->where([['so_emb_id','=',request('so_emb_id',0)]])->get();
        foreach($rows as $row){
            $soembfile['id']=$row->id;
            $soembfile['so_emb_id']=$row->so_emb_id;
            $soembfile['file_src']=$row->file_src;

            array_push($soembfiles, $soembfile);
        }
        echo json_encode($soembfiles);

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
    public function store(SoEmbFileRequest $request) {

        if($request->id)
        {
            $this->update($request, $request->id);
                return response()->json(array('success'=>true,'id'=>$request->id,'message'=>'Update Successfully'),200);

        }
        else{
            $name =time().'.'.$request->file_src->getClientOriginalExtension();
            $request->file_src->move(public_path('images'), $name);
            $soembfile=$this->soembfile->create([
            'so_emb_id'=>$request->so_emb_id,
            'file_src'=> $name,
            ]);
            if($soembfile){
            return response()->json(array('success'=>true,'id'=>$soembfile->id,'message'=>'Save Successfully'),200);
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
       $soembfile=$this->soembfile->find($id);
       $row['fromData']=$soembfile;
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
    public function update(SoEmbFileRequest $request, $id) {
        $name =time().'.'.$request->file_src->getClientOriginalExtension();
        $request->file_src->move(public_path('images'), $name);
        $soembfile=$this->soembfile->update($request->id,[
        'so_emb_id'=>$request->so_emb_id,
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
        if($this->soembfile->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }

}
