<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\TargetProcessSetupRepository;
use App\Library\Template;
use App\Http\Requests\TargetProcessSetupRequest;

class TargetProcessSetupController extends Controller
{
   private $targetprocesssetup;

   public function __construct(TargetProcessSetupRepository $targetprocesssetup){
    $this->targetprocesssetup=$targetprocesssetup;
    $this->middleware('auth');
        // $this->middleware('permission:view.invfinishfabrcvitems',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.invfinishfabrcvitems', ['only' => ['store']]);
        // $this->middleware('permission:edit.invfinishfabrcvitems',   ['only' => ['update']]);
        // $this->middleware('permission:delete.invfinishfabrcvitems', ['only' => ['destroy']]);
   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tergetProcess=array_prepend(config('bprs.tergetProcess'),'-Select-','');
        $productionarea=array_prepend(config('bprs.productionarea'),'-Select-','');
        $targetprocesssetups=array();
        $rows=$this->targetprocesssetup
        ->orderBy('target_process_setups.id','desc')
        ->get();
        foreach ($rows as $row) {
          $targetprocesssetup['id']=$row->id;
          $targetprocesssetup['process_name']=$tergetProcess[$row->process_id];
          $targetprocesssetup['production_area_name']=$productionarea[$row->production_area_id];
          $targetprocesssetup['sort_id']=$row->sort_id;
          array_push($targetprocesssetups,$targetprocesssetup);
        }
        echo json_encode($targetprocesssetups);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tergetProcess=array_prepend(config('bprs.tergetProcess'),'-Select-','');
        $productionarea=array_prepend(config('bprs.productionarea'),'-Select-','');
        return Template::loadView("Util.TargetProcessSetup",['tergetProcess'=>$tergetProcess,'productionarea'=>$productionarea]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TargetProcessSetupRequest $request)
    {
        $targetprocesssetup=$this->targetprocesssetup->create($request->except(['id']));
        if ($targetprocesssetup) {
         return response()->json(array('success'=>true, 'id'=>$targetprocesssetup->id,'message'=>'Save Successfully'), 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
     $targetprocesssetup=$this->targetprocesssetup->find($id);
     $row['fromData']=$targetprocesssetup;
     $dropdown['att']='';
     $row['dropDown']=$dropdown;
     echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TargetProcessSetupRequest $request, $id)
    {
      $targetprocesssetup=$this->targetprocesssetup->update($id,$request->except(['id']));
      if ($targetprocesssetup) {
       return response()->json(array('success'=>true,'id'=>$id,'message'=>"Update Successfully"), 200);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       if ($this->targetprocesssetup->delete($id)) {
        return response()->json(array('success'=>true,'message'=>'Delete Successfully'));
       }
    }
}
