<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
//use App\Http\Requests\UserRequest;

class SignatureUserController extends Controller {

    private $user;

    public function __construct(UserRepository $user) {

        $this->user = $user;

        $this->middleware('auth');
        // $this->middleware('permission:view.signatureusers',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.signatureusers', ['only' => ['store']]);
        // $this->middleware('permission:edit.signatureusers',   ['only' => ['update']]);
        // $this->middleware('permission:delete.signatureusers', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
        $signatureusers=array();
        $rows=$this->user
        ->where([['id','=',request('id', 0)]])
        ->get();
        foreach ($rows as $row) {
          $signatureuser['signature_file']=$row->signature_file;
          $signatureuser['id']=$row->id;
          $signatureuser['name']=$row->name;
          array_push($signatureusers,$signatureuser);
        }
        echo json_encode($signatureusers);
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
    public function store(Request $request) {
       // dd($request->id);die;
        //$user = $this->user->find($request->id);

        $name =time().'.'.$request->signature_file->getClientOriginalExtension();
        //dd($name);die;
        $request->signature_file->move(public_path('images/signature'), $name);

        $signatureuser=$this->user->update($request->id,[
            'signature_file'=> $name,
        ]);
        
        if($signatureuser){
            return response()->json(array('success'=>true,'id'=>$request->id,'message'=>'Update Successfully'),200);
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
        $signatureuser = $this->user->find($id);
        $row ['fromData'] = $signatureuser;
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
    public function update(Request $request, $id) {
        // dd($id);die;
         $name =time().'.'.$request->signature->getClientOriginalExtension();
         $request->signature->move(public_path('images/signature'), $name);
         $signatureuser=$this->user->update($id,[
             'signature_file'=> $name,
         ]);
         if($signatureuser){
         return response()->json(array('success'=>true,'id'=>$id,'message'=>'Save Successfully'),200);
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if ($this->user->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
