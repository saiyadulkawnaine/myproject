<?php

namespace App\Http\Controllers\Subcontract\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFileRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Dyeing\SoDyeingFileRequest;

class SoDyeingFileController extends Controller {

    private $sodyeingfile;
    private $sodyeing;
    


    public function __construct(SoDyeingFileRepository $sodyeingfile,SoDyeingRepository $sodyeing) {
        $this->sodyeingfile = $sodyeingfile;
        $this->sodyeing = $sodyeing;


        $this->middleware('auth');
        $this->middleware('permission:view.sodyeingfiles',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.sodyeingfiles', ['only' => ['store']]);
        $this->middleware('permission:edit.sodyeingfiles',   ['only' => ['update']]);
        $this->middleware('permission:delete.sodyeingfiles', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $sodyeingfiles=array();
        $rows=$this->sodyeingfile
        ->where([['so_dyeing_id','=',request('so_dyeing_id',0)]])->get();
        foreach($rows as $row){
            $sodyeingfile['id']=$row->id;
            $sodyeingfile['so_dyeing_id']=$row->so_dyeing_id;
            $sodyeingfile['file_src']=$row->file_src;

            array_push($sodyeingfiles, $sodyeingfile);
        }
        echo json_encode($sodyeingfiles);

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
    public function store(SoDyeingFileRequest $request) {

        if($request->id)
        {
            $this->update($request, $request->id);
                return response()->json(array('success'=>true,'id'=>$request->id,'message'=>'Update Successfully'),200);

        }
        else{
            $name =time().'.'.$request->file_src->getClientOriginalExtension();
            $request->file_src->move(public_path('images'), $name);
            $sodyeingfile=$this->sodyeingfile->create([
            'so_dyeing_id'=>$request->so_dyeing_id,
            'file_src'=> $name,
            ]);
            if($sodyeingfile){
            return response()->json(array('success'=>true,'id'=>$sodyeingfile->id,'message'=>'Save Successfully'),200);
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
       $sodyeingfile=$this->sodyeingfile->find($id);
       $row['fromData']=$sodyeingfile;
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
    public function update(SoDyeingFileRequest $request, $id) {
        $name =time().'.'.$request->file_src->getClientOriginalExtension();
        $request->file_src->move(public_path('images'), $name);
        $sodyeingfile=$this->sodyeingfile->update($request->id,[
        'so_dyeing_id'=>$request->so_dyeing_id,
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
        if($this->sodyeingfile->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }

}
