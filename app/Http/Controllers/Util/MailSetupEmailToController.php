<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\MailSetupEmailToRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Library\Template;
use App\Http\Requests\MailSetupEmailToRequest;

class MailSetupEmailToController extends Controller
{
    private $mailsetupemailto;
    private $company;
    private $employeehr;

    public function __construct(
        MailSetupEmailToRepository $mailsetupemailto,
        CompanyRepository  $company,
        EmployeeHRRepository  $employeehr
    )

    {
        $this->mailsetupemailto = $mailsetupemailto;
        $this->company = $company;
        $this->employeehr = $employeehr;

        $this->middleware('auth');

        // $this->middleware('permission:view.mailsetupemailtos',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.mailsetupemailtos', ['only' => ['store']]);
        // $this->middleware('permission:edit.mailsetupemailtos',   ['only' => ['update']]);
        // $this->middleware('permission:delete.mailsetupemailtos', ['only' => ['destroy']]);
    }


    public function index()
    {
        $status=array_prepend(array_only(config('bprs.status'), [1,0]),'-All-','');
        $mailsetupemailtos = array();
        $rows=$this->mailsetupemailto
        ->where([['mail_setup_id','=',request('mail_setup_id',0)]])
        ->orderBy('mail_setup_email_tos.id','desc')
        ->get();
        foreach($rows as $row){
            $mailsetupemailto['id']=$row->id;
            $mailsetupemailto['email']=$row->customer_email;
            $mailsetupemailto['status_id']=isset($status[$row->status_id])?$status[$row->status_id]:'';
            array_push($mailsetupemailtos,$mailsetupemailto);
        }

        echo json_encode($mailsetupemailtos);
    }

    public function create()
    {
        //
    }

    public function store(MailSetupEmailToRequest $request) {
        $mailsetupemailto = $this->mailsetupemailto->create([
            'mail_setup_id'=>$request->mail_setup_id,
            'customer_email'=>$request->customer_email,
            'status_id'=>$request->status_id,
        ]);
        if ($mailsetupemailto) {
          return response()->json(array('success' => true, 'id' => $mailsetupemailto->id, 'message' => 'Save Successfully'), 200);
       }
  }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $mailsetupemailto = $this->mailsetupemailto->find($id);
        $row ['fromData'] = $mailsetupemailto;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        echo json_encode($row);
    }

    public function update(MailSetupEmailToRequest $request, $id){
     $mailsetupemailto = $this->mailsetupemailto->update($id,[
        'mail_setup_id'=>$request->mail_setup_id,
        'customer_email'=>$request->customer_email,
        'status_id'=>$request->status_id
        ]);
         if ($mailsetupemailto) {
          return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
       }

    }

    public function destroy($id)
    {
        if ($this->mailsetupemailto->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }


}