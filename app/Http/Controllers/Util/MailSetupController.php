<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\MailSetupRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Http\Requests\MailSetupRequest;
use App\Library\Template;


class MailSetupController extends Controller
{
    private $mailsetup;
     private $company;
     private $employeehr;




    public function __construct(MailSetupRepository $mailsetup,CompanyRepository $company,EmployeeHRRepository $employeehr) {
        $this->mailsetup=$mailsetup;
        $this->company=$company;
        $this->employeehr=$employeehr;

        $this->middleware('auth');
        // $this->middleware('permission:view.mailsetups',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.mailsetups', ['only' => ['store']]);
        // $this->middleware('permission:edit.mailsetups',   ['only' => ['update']]);
        // $this->middleware('permission:delete.mailsetups', ['only' => ['destroy']]);
    }

    public function index()
    {

        $reportname=array_prepend(config('bprs.reportname'),'-Select-','');

        $mailsetups=array();
        $rows=$this->mailsetup
        ->get();

        foreach ($rows as $row) {
            $mailsetup['id']=$row->id;
            $mailsetup['report_name']=$reportname[$row->report_name_id];
            array_push($mailsetups,$mailsetup);
        }
        echo json_encode($mailsetups);
    }

    public function create()
    {
        $reportname=array_prepend(config('bprs.reportname'),'-Select-','');
        $status=array_prepend(array_only(config('bprs.status'), [1,0]),'-Select-','');
        return Template::loadView('Util.MailSetup',['status'=>$status,'reportname'=>$reportname]);
    }

    public function store(MailSetupRequest $request) {
        $mailsetup = $this->mailsetup->create($request->except(['id']));
         if ($mailsetup) {
          return response()->json(array('success' => true, 'id' => $mailsetup->id, 'message' => 'Save Successfully'), 200);
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
        $mailsetup = $this->mailsetup->find($id);
        $row ['fromData'] = $mailsetup;
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
    public function update(MailSetupRequest $request, $id){
        $mailsetup = $this->mailsetup->update($id,$request->except(['id']));
         if ($mailsetup) {
          return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
       }

}


    public function destroy($id)
    {
        if ($this->mailsetup->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
