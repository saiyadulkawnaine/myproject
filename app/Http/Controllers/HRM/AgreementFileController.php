<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\AgreementFileRepository;
use App\Repositories\Contracts\HRM\AgreementRepository;
use App\Library\Template;
use App\Http\Requests\HRM\AgreementFileRequest;

class AgreementFileController extends Controller {

  private $agreementfile;
  private $agreement;

    public function __construct(AgreementFileRepository $agreementfile,AgreementRepository $agreement) {
      $this->agreementfile = $agreementfile;
      $this->agreement = $agreement;
      $this->middleware('auth');
      /* $this->middleware('permission:view.agreementfiles',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.agreementfiles', ['only' => ['store']]);
      $this->middleware('permission:edit.agreementfiles',   ['only' => ['update']]);
      $this->middleware('permission:delete.agreementfiles', ['only' => ['destroy']]); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $agreementfiles = array();

        $rows = $this->agreementfile
        ->where([['agreement_id','=',request('agreement_id',0)]])
        ->get();
		
		foreach($rows as $row){
            $agreementfile['id']=	$row->id;
            $agreementfile['agreement']=	$row->agreement_ref;
            $agreementfile['agreement_id']=	$row->agreement_id;
            $agreementfile['original_name']=	$row->original_name;
            $agreementfile['file_src']=	$row->file_src;
		array_push($agreementfiles,$agreementfile);
		}
		echo json_encode($agreementfiles);
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
    public function store(AgreementFileRequest $request) {
        if($request->id){
            $this->update($request, $request->id);
            return response()->json(array('success'=>true,'id'=>$request->id,'message'=>'Update Successfully'),200);
         }
         else{

            $name ='AgreementFile_'.time().'.'.$request->file_src->getClientOriginalExtension();
            //$name =$request->file_src->getClientOriginalName().'.'.$request->file_src->extension();
             $request->file_src->move(public_path('images'), $name);
             $agreementfile=$this->agreementfile->create([
             'agreement_id'=>$request->agreement_id,
             'original_name'=>$request->original_name,
             'file_src'=> $name,
             ]);
             if ($agreementfile) {
                return response()->json(array('success' => true, 'id' => $agreementfile->id, 'message' => 'Save Successfully'), 200);
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
        $agreementfile = $this->agreementfile->find($id);
        $row ['fromData'] = $agreementfile;
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
    public function update(AgreementFileRequest $request, $id) {

        $name ='AgreementFile_'.time().'.'.$request->file_src->getClientOriginalExtension();
        $request->file_src->move(public_path('images'), $name);   
        $agreementfile=$this->agreementfile->update($request->id,[
            'agreement_id'=>$request->agreement_id,
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
    	$agreementfile = $this->agreementfile->findOrFail($id);
        unlink(public_path() .  '/images/' . $agreementfile->file_src );
        if($agreementfile->forceDelete()){
        	return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
        // if ($this->agreementfile->delete($id)) {
        //     return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        // }
    }

}