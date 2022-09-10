<?php

namespace App\Http\Controllers\Subcontract\Inbound;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbMarketingRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbImageRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Inbound\SubInbImageRequest;

class SubInbImageController extends Controller {

    private $subinbimage;
    private $subinbmarketing;
    


    public function __construct(SubInbImageRepository $subinbimage,SubInbMarketingRepository $subinbmarketing) {
        $this->subinbimage = $subinbimage;
        $this->subinbmarketing = $subinbmarketing;


        $this->middleware('auth');
            $this->middleware('permission:view.subinbimages',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.subinbimages', ['only' => ['store']]);
            $this->middleware('permission:edit.subinbimages',   ['only' => ['update']]);
            $this->middleware('permission:delete.subinbimages', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $subinbimages=array();
        $rows=$this->subinbimage
        ->where([['sub_inb_marketing_id','=',request('sub_inb_marketing_id',0)]])->get();
        foreach($rows as $row){
            $subinbimage['id']=$row->id;
            $subinbimage['sub_inb_marketing_id']=$row->sub_inb_marketing_id;
            $subinbimage['file_src']=$row->file_src;
            $subinbimage['doc_file_src']=$row->doc_file_src;

            array_push($subinbimages, $subinbimage);
        }
        echo json_encode($subinbimages);

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
    public function store(SubInbImageRequest $request) {

        if($request->id)
        {
            $this->update($request, $request->id);
                return response()->json(array('success'=>true,'id'=>$request->id,'message'=>'Update Successfully'),200);

        }
        else{
            // $name =time().'.'.$request->file_src->getClientOriginalExtension();
            // $fileUpload =time().'.'.$request->doc_file_src->getClientOriginalExtension();
            // $request->file_src->move(public_path('images'), $name);
            // $request->doc_file_src->move(public_path('files'), $fileUpload);
            // $subinbimage=$this->subinbimage->create([
            // 'sub_inb_marketing_id'=>$request->sub_inb_marketing_id,
            // 'file_src'=> $name,
            // 'doc_file_src'=> $fileUpload,
            // ]);
            $name='';
            $fileUpload='';
            if($request->file_src !='undefined')
		    {
                $name =time().'.'.$request->file_src->getClientOriginalExtension();
                $request->file_src->move(public_path('images'), $name);
            }
            if($request->doc_file_src !='undefined')
		    {
                $fileUpload =time().'.'.$request->doc_file_src->getClientOriginalExtension();
                $request->doc_file_src->move(public_path('files'), $fileUpload);
            }
            
           
            $subinbimage=$this->subinbimage->create([
            'sub_inb_marketing_id'=>$request->sub_inb_marketing_id,
            'file_src'=> $name,
            'doc_file_src'=> $fileUpload,
            ]);
            
            if($subinbimage){
            return response()->json(array('success'=>true,'id'=>$subinbimage->id,'message'=>'Save Successfully'),200);
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
       $subinbimage=$this->subinbimage->find($id);
       $row['fromData']=$subinbimage;
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
    public function update(SubInbImageRequest $request, $id) {
        $name =time().'.'.$request->file_src->getClientOriginalExtension();
        $fileUpload =time().'.'.$request->doc_file_src->getClientOriginalExtension();
        $request->file_src->move(public_path('images'), $name);
        $request->doc_file_src->move(public_path('files'), $name);
        $subinbimage=$this->subinbimage->update($request->id,[
        'sub_inb_marketing_id'=>$request->sub_inb_marketing_id,
        'file_src'=> $name,
        'doc_file_src'=> $fileUpload,
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->subinbimage->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}

        
    }

}
