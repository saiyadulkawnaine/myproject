<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\RenewalEntryRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\Renewal\RenewalItemRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\CgroupRepository;
use App\Repositories\Contracts\Util\SectionRepository;
use App\Library\Template;
use App\Http\Requests\HRM\RenewalEntryRequest;



class RenewalEntryController extends Controller {

    private $renewalentry;
    private $renewalitem;
    private $user;
    private $company;
    private $cgroup;
    private $section;

    
    public function __construct(
        RenewalEntryRepository $renewalentry,
        RenewalItemRepository $renewalitem, 
        UserRepository $user,
        CompanyRepository $company,
        CgroupRepository $cgroup,
        SectionRepository $section
    ) {
        $this->renewalentry = $renewalentry;
        $this->renewalitem = $renewalitem;
        $this->user = $user;
        $this->company = $company;
        $this->cgroup = $cgroup;
        $this->section = $section;
        
        $this->middleware('auth');
        /* $this->middleware('permission:view.renewalentries',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.renewalentries', ['only' => ['store']]);
        $this->middleware('permission:edit.renewalentries',   ['only' => ['update']]);
        $this->middleware('permission:delete.renewalentries', ['only' => ['destroy']]); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

      //==============================
      /*$path= public_path('images')."/ship_date_change.csv";
      $row = 1;
      \DB::beginTransaction();
      if (($handle = fopen($path, "r")) !== FALSE) {
        while (($data = fgetcsv($handle)) !== FALSE) {
          if($row<=226){
            if($row==1){
            }
            else{
              try
              {
                

                \DB::table('SALES_ORDERS')
                ->where('id', $data[2])
                ->update([
                  'ship_date' => date('Y-m-d',strtotime($data[3]))
                ]);
              }
              catch(EXCEPTION $e)
              {
                \DB::rollback();
                throw $e;
              }
            }
          }
          $row++;
        }
        fclose($handle);
      }
      \DB::commit();
      echo $row;

      die; */


     /*$path= public_path('images')."/EMPAPP-21.csv";
      $row = 1;
      \DB::beginTransaction();
      if (($handle = fopen($path, "r")) !== FALSE) {
        while (($data = fgetcsv($handle)) !== FALSE) {
          if($row<=1684){
            if($row==1){
              //print_r($data); die;
            }
            else{
            //$department = $this->department->firstOrCreate(['name' => $data[1]]);
            //$designation = $this->designation->firstOrCreate(['name' => $data[3]]);
            $sectioncode = substr($data[9], 0, 3);
            //$section = $this->section->firstOrCreate(['name' => $data[9],['code' => $sectioncode]]);
            $section = $this->section->firstOrCreate(['name' => $data[9]],['code' => $sectioncode]);
              try
              {
                \DB::table('employee_h_rs')
                ->where('id', $data[0])
                ->update([
                    
                    'company_id'=>2,
                    'NAME' => $data[1],
                    'CODE' => $data[2],
                    'DESIGNATION_ID' => $data[3],
                    'LOCATION_ID' => 1,
                    'DIVISION_ID' => 1,
                    'DEPARTMENT_ID' => $data[7],
                    'SECTION_ID' => $section->id,
                    'SUBSECTION_ID' => $data[10],
                    'GRADE' => $data[11],
                    'DATE_OF_JOIN' => date('Y-m-d',strtotime($data[12])),
                    'DATE_OF_BIRTH' => date('Y-m-d',strtotime($data[13])),
                    'CONTACT' => $data[14],
                    'EMAIL' => $data[15],
                    'NATIONAL_ID' => $data[17],
                    'ADDRESS' => $data[18],
                    'SALARY' => $data[19],
                    'TIN' => $data[20],
                    'IS_ADVANCED_APPLICABLE' => $data[21],
                    'LAST_EDUCATION' => $data[22],
                    'EXPERIENCE' => $data[23],
                    'STATUS_ID' =>1,
                    'UPDATED_AT' => date('Y-m-d h:i:s'),
                ]);
              }
              catch(EXCEPTION $e)
              {
                \DB::rollback();
                throw $e;
              }
            }
          }
          $row++;
        }
        fclose($handle);
      }
      \DB::commit();
      echo $row;

      die;*/

        



        //==============================
      /*$path= public_path('images')."\Inc21FFL.csv";
      $row = 1;
      \DB::beginTransaction();
      if (($handle = fopen($path, "r")) !== FALSE) {
        while (($data = fgetcsv($handle)) !== FALSE) {
          if($row<=10){
            if($row==1){
            }
            else{
              try
              {
                \DB::table('employee_increments')->insert([
                  'employee_h_r_id'=>$data[0],
                  'prev_gross'=>$data[1],
                  'increment_per'=>$data[5],
                  'increment_amount'=>$data[6],
                  'new_gross'=>$data[7],
                  'effective_date'=>'2020-12-01',
                ]
                );

                \DB::table('employee_h_rs')
                ->where('id', $data[0])
                ->update(['SALARY' => $data[7]]);
              }
              catch(EXCEPTION $e)
              {
                \DB::rollback();
                throw $e;
              }
            }
          }
          $row++;
        }
        fclose($handle);
      }
      \DB::commit();
      echo $row;

      die;*/ 

      //===============================
        $renewalitem=array_prepend(array_pluck($this->renewalitem->get(),'renewal_item','id'),'-Select-','');
        $user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $renewalentries=array();
       
        $rows=$this->renewalentry->get();
        foreach ($rows as $row){
            $renewalentry['id']=$row->id;
            $renewalentry['renewal_no']=$row->renewal_no;
            $renewalentry['renewal_item_id']=$renewalitem[$row->renewal_item_id];
            $renewalentry['company_id']=$company[$row->company_id];
            $renewalentry['document_no']=$row->document_no;
            $renewalentry['no_of_sewing_machine']=$row->no_of_sewing_machine;
            $renewalentry['fees']=$row->fees;
            $renewalentry['processing_expense']=$row->processing_expense;
            $renewalentry['fees_deposit_to']=$row->fees_deposit_to;
            $renewalentry['validity_start']=date('d-M-Y',strtotime($row->validity_start));
            $renewalentry['validity_end']=date('d-M-Y',strtotime($row->validity_end));
            $renewalentry['applied_date']=date('d-M-Y',strtotime($row->applied_date));
            $renewalentry['renewed_date']=date('d-M-Y',strtotime($row->renewed_date));  
            $renewalentry['user_id']=$user[$row->user_id]; 
            
            array_push($renewalentries,$renewalentry);
        }
    echo json_encode($renewalentries);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
       
        $cgroup=$this->cgroup->get(['address as company_address']);
        $renewalitem=array_prepend(array_pluck($this->renewalitem->get(),'renewal_item','id'),'-Select-','');
        $user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        return Template::loadView('HRM.RenewalEntry',['renewalitem'=>$renewalitem,'user'=>$user,'company'=>$company,'cgroup'=>$cgroup]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RenewalEntryRequest $request) 

    {
        $max=$this->renewalentry->where([['company_id' , $request->company_id]])
        ->max('renewal_no');
        $renewal_no=$max+1;
        $renewalentry = $this->renewalentry->create([
            'renewal_item_id'=>$request->renewal_item_id,
            'renewal_no'=>$renewal_no,
            'company_id'=>$request->company_id,
            'validity_start'=>$request->validity_start,
            'fees'=>$request->fees,
            'validity_end'=>$request->validity_end,
            'document_no'=>$request->document_no,

            'no_of_sewing_machine'=>$request->no_of_sewing_machine,
            'processing_expense'=>$request->processing_expense,
            'fees_deposit_to'=>$request->fees_deposit_to,
            'user_id'=>$request->user_id, 
            'applied_date'=>$request->applied_date,
            'renewed_date'=>$request->renewed_date,
            'remarks'=>$request->remarks,
        ]);
        if($renewalentry){
            return response()->json(array('success' => true,'id' =>  $renewalentry->id,'renewal_no' => $renewal_no ,'message' => 'Save Successfully'),200);
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
        $renewalentry = $this->renewalentry->find($id);
        $row ['fromData'] = $renewalentry;
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
    public function update(RenewalEntryRequest $request, $id) {
        $renewalentry=$this->renewalentry->update($id,$request->except(['id','company_id']));
        if($renewalentry){
            return response()->json(array('success'=>true,'id'=>$id,'message'=>'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->renewalentry->delete($id)){
            return response()->json(array('success'=>true,'message' => 'Delete Successfully'),200);
        }
    }


}
