<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\StyleFileUploadRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Library\Template;
use App\Http\Requests\StyleFileUploadRequest;

class StyleFileUploadController extends Controller {

  private $stylefileupload;
  private $style;

    public function __construct(StyleFileUploadRepository $stylefileupload,StyleRepository $style) {
      $this->stylefileupload = $stylefileupload;
      $this->style = $style;
      $this->middleware('auth');
      /* $this->middleware('permission:view.stylefileuploads',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.stylefileuploads', ['only' => ['store']]);
      $this->middleware('permission:edit.stylefileuploads',   ['only' => ['update']]);
      $this->middleware('permission:delete.stylefileuploads', ['only' => ['destroy']]); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $stylefileuploads = array();

        $rows = $this->stylefileupload
        ->where([['style_id','=',request('style_id',0)]])
        ->get();
		
		foreach($rows as $row){
            $stylefileupload['id']=	$row->id;
            $stylefileupload['style']=	$row->style_ref;
            $stylefileupload['style_id']=	$row->style_id;
            $stylefileupload['original_name']=	$row->original_name;
            $stylefileupload['file_src']=	$row->file_src;
		array_push($stylefileuploads,$stylefileupload);
		}
		echo json_encode($stylefileuploads);
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
    public function store(StyleFileUploadRequest $request) {
        if($request->id){
            $this->update($request, $request->id);
            return response()->json(array('success'=>true,'id'=>$request->id,'message'=>'Update Successfully'),200);
         }
         else{

            $name =time().'.'.$request->file_src->getClientOriginalExtension();
            //$name =$request->file_src->getClientOriginalName().'.'.$request->file_src->extension();
             $request->file_src->move(public_path('images'), $name);
             $stylefileupload=$this->stylefileupload->create([
             'style_id'=>$request->style_id,
             'original_name'=>$request->original_name,
             'file_src'=> $name,
             ]);
             if ($stylefileupload) {
                return response()->json(array('success' => true, 'id' => $stylefileupload->id, 'message' => 'Save Successfully'), 200);
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
        $stylefileupload = $this->stylefileupload->find($id);
        $row ['fromData'] = $stylefileupload;
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
    public function update(StyleFileUploadRequest $request, $id) {

        $name =time().'.'.$request->file_src->getClientOriginalExtension();
        $request->file_src->move(public_path('images'), $name);   
        $stylefileupload=$this->stylefileupload->update($request->id,[
            'style_id'=>$request->style_id,
            'original_name'=>$request->original_name,
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
        if ($this->stylefileupload->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
