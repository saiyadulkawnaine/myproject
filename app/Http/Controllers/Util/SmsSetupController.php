<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\SmsSetupRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;

use App\Library\Template;
use App\Http\Requests\SmsSetupRequest;

class SmsSetupController extends Controller
{
    private $smssetup;
    private $designation;
    private $department;
    private $company;


    public function __construct(SmsSetupRepository $smssetup,CompanyRepository $company,DepartmentRepository $department,DesignationRepository $designation) {
        $this->smssetup=$smssetup;
        $this->company=$company;
        $this->department=$department;
        $this->designation=$designation;

        $this->middleware('auth');
        
        // $this->middleware('permission:view.smssetups',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.smssetups', ['only' => ['store']]);
        // $this->middleware('permission:edit.smssetups',   ['only' => ['update']]);
        // $this->middleware('permission:delete.smssetups', ['only' => ['destroy']]);
    }
    /**
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $menu=array_prepend(config('bprs.menu'),'-Select-','');

        $smssetups=array();
        $rows=$this->smssetup
        ->get();
        foreach ($rows as $row) {
            $smssetup['id']=$row->id;
            $smssetup['menu_name']=$menu[$row->menu_id];
            array_push($smssetups,$smssetup);
        }
        echo json_encode($smssetups);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $menu=array_prepend(config('bprs.menu'),'-Select-','');
        return Template::loadView('Util.SmsSetup',['menu'=>$menu,'company'=>$company,'department'=>$department,'designation'=>$designation]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SmsSetupRequest $request) {
        $smssetup = $this->smssetup->create($request->except(['id']));
         if ($smssetup) {
          return response()->json(array('success' => true, 'id' => $smssetup->id, 'message' => 'Save Successfully'), 200);
       }
  }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        $smssetup = $this->smssetup->find($id);
        $row ['fromData'] = $smssetup;
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

    public function update(SmsSetupRequest $request,$id){
        $smssetup= $this->smssetup->update($id,$request->except(['id']));

        if ($smssetup) {
          return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
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
        if ($this->smssetup->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
