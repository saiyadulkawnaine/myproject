<?php

namespace App\Http\Controllers\Production\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\AOP\ProdAopMcParameterRepository;
use App\Repositories\Contracts\Production\AOP\ProdAopMcSetupRepository;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\DivisionRepository;
use App\Repositories\Contracts\Util\SectionRepository;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Production\AOP\ProdAopBatchRepository;
use App\Library\Template;

use App\Http\Requests\Production\AOP\ProdAopMcParameterRequest;

class ProdAopMcParameterController extends Controller {

    private $prodaopmcparameter;
    private $prodaopmcsetup;
    private $employeehr;
    private $designation;
    private $department;
    private $user;
    private $division;
    private $section;
    private $subsection;
    private $location;
    private $company;
    private $prodaopbatch;

    public function __construct(
        ProdAopMcParameterRepository $prodaopmcparameter,
        ProdAopMcSetupRepository $prodaopmcsetup,
        EmployeeHRRepository $employeehr,
        DesignationRepository $designation,
        DepartmentRepository $department,
        DivisionRepository $division,
        SectionRepository $section,
        SubsectionRepository $subsection,
        CompanyRepository $company,
        UserRepository $user,
        LocationRepository $location,
        ProdAopBatchRepository $prodaopbatch
    ) {
        $this->prodaopmcparameter = $prodaopmcparameter;
        $this->prodaopmcsetup = $prodaopmcsetup;
        $this->employeehr = $employeehr;
        $this->designation = $designation;
        $this->department = $department;
        $this->division = $division;
        $this->section = $section;
        $this->subsection = $subsection;
        $this->company = $company;
        $this->location = $location;
        $this->user = $user;
        $this->prodaopbatch = $prodaopbatch;

        $this->middleware('auth');
        // $this->middleware('permission:view.prodaopmcparameters',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.prodaopmcparameters', ['only' => ['store']]);
        // $this->middleware('permission:edit.prodaopmcparameters',   ['only' => ['update']]);
        // $this->middleware('permission:delete.prodaopmcparameters', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $shiftname=array_prepend(config('bprs.shiftname'),'','');
       $prodaopmcparameters = array();
       $rows=$this->prodaopmcparameter
       ->leftJoin('prod_aop_batches',function($join){
          $join->on('prod_aop_batches.id','=','prod_aop_mc_parameters.prod_aop_batch_id');
        })
       ->leftJoin('employee_h_rs',function($join){
          $join->on('employee_h_rs.id','=','prod_aop_mc_parameters.employee_h_r_id');
        })
       ->where([['prod_aop_mc_date_id','=',request('prod_aop_mc_date_id',0)]])
       ->get([
        'prod_aop_mc_parameters.*',
         'prod_aop_batches.batch_no',
         'employee_h_rs.name as employee_name'
       ]);
       foreach($rows as $row){
           $prodaopmcparameter['id']=$row->id;
           $prodaopmcparameter['batch_no']=$row->batch_no;
           $prodaopmcparameter['rpm']=$row->rpm;
           $prodaopmcparameter['gsm_weight']=$row->gsm_weight;
           $prodaopmcparameter['dia']=$row->dia;
           $prodaopmcparameter['repeat_size']=$row->repeat_size;
           $prodaopmcparameter['production_per_hr']=number_format($row->production_per_hr,2);
           $prodaopmcparameter['tgt_qty']=number_format($row->tgt_qty,2);
           $prodaopmcparameter['shiftname_id']=isset($shiftname[$row->shiftname_id])?$shiftname[$row->shiftname_id]:'';
           $prodaopmcparameter['employee_name']=$row->employee_name;
           $prodaopmcparameter['remarks']=$row->remarks;

           array_push($prodaopmcparameters,$prodaopmcparameter);
       }
       echo json_encode($prodaopmcparameters);
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
    public function store(ProdAopMcParameterRequest $request) {
        $prodaopmcparameter=$this->prodaopmcparameter->create([
            'prod_aop_mc_date_id'=>$request->prod_aop_mc_date_id,
            'prod_aop_batch_id'=>$request->prod_aop_batch_id,
            'employee_h_r_id'=>$request->employee_h_r_id,
            'rpm'=>$request->rpm,
            'gsm_weight'=>$request->gsm_weight,
            'repeat_size'=>$request->repeat_size,
            'dia'=>$request->dia,
            'production_per_hr'=>$request->production_per_hr,
            'tgt_qty'=>$request->tgt_qty,
            'shiftname_id'=>$request->shiftname_id,
            'remarks'=>$request->remarks,

        ]);

        if($prodaopmcparameter){
            return response()->json(array('success' => true,'id' =>  $prodaopmcparameter->id,'message' => 'Save Successfully'),200);
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
    $prodaopmcparameter=$this->prodaopmcparameter
        ->leftJoin('prod_aop_batches',function($join){
          $join->on('prod_aop_batches.id','=','prod_aop_mc_parameters.prod_aop_batch_id');
        })
        ->leftJoin('employee_h_rs',function($join){
            $join->on('employee_h_rs.id','=','prod_aop_mc_parameters.employee_h_r_id');
        })->where([['prod_aop_mc_parameters.id','=',$id]])
        ->get([
           'prod_aop_mc_parameters.*',
           'prod_aop_batches.batch_no',
           'employee_h_rs.name',
       ])
       ->first();

        $row ['fromData'] = $prodaopmcparameter;
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
    public function update(ProdAopMcParameterRequest $request, $id) {
        $prodaopmcparameter=$this->prodaopmcparameter->update($id,[
            'prod_aop_mc_date_id'=>$request->prod_aop_mc_date_id,
            'prod_aop_batch_id'=>$request->prod_aop_batch_id,
            'employee_h_r_id'=>$request->employee_h_r_id,
            'rpm'=>$request->rpm,
            'gsm_weight'=>$request->gsm_weight,
            'repeat_size'=>$request->repeat_size,
            'dia'=>$request->dia,
            'production_per_hr'=>$request->production_per_hr,
            'tgt_qty'=>$request->tgt_qty,
            'shiftname_id'=>$request->shiftname_id,
            'remarks'=>$request->remarks
        ]);
        if($prodaopmcparameter){
            return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->prodaopmcparameter->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getBatch(){
        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $rows=$this->prodaopbatch
        ->join('so_aops',function($join){
            $join->on('so_aops.id','=','prod_aop_batches.so_aop_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','so_aops.company_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','so_aops.buyer_id');
        })
        ->join('colors as batch_colors',function($join){
            $join->on('batch_colors.id','=','prod_aop_batches.batch_color_id');
        })
        ->when(request('batch_no'), function ($q) {
        return $q->where('prod_aop_batches.batch_no', '=',request('batch_no', 0));
        })
        ->when(request('company_id'), function ($q) {
        return $q->where('so_aops.company_id', '=',request('company_id', 0));
        })
        ->when(request('batch_for'), function ($q) {
        return $q->where('prod_aop_batches.batch_for', '=',request('batch_for', 0));
        })
        ->orderBy('prod_aop_batches.id','desc')
        ->get([
            'prod_aop_batches.*',
            'so_aops.company_id',
            'companies.code as company_code',
            'buyers.name as customer_name',
            'batch_colors.name as batch_color_name',
        ])
        ->map(function($rows) use($batchfor){
            $rows->batchfor=$rows->batch_for?$batchfor[$rows->batch_for]:'';
            $rows->batch_date=date('Y-m-d',strtotime($rows->batch_date));
            return $rows;
        });
        echo json_encode($rows);

    }

    public function getEmployee(){
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $prod_aop_mc_setup_id=request('prodfinishmcsetupId',0);
        $prodaopmcsetup=$this->prodaopmcsetup->find($prod_aop_mc_setup_id);

        $company=$this->company
        ->join('asset_acquisitions',function($join){
            $join->on('companies.id','=','asset_acquisitions.company_id');
        })
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->where([['asset_quantity_costs.id','=',$prodaopmcsetup->machine_id]])
        ->get(['companies.id','companies.name'])
        ->first();

        $yesno = config('bprs.yesno');
        

        $employeehrs=array();
        $rows=$this->employeehr
        ->where([['company_id','=',$company->id]])
        ->orderBy('employee_h_rs.id','desc')
        ->when(request('designation_id'), function ($q) {
            return $q->where('employee_h_rs.designation_id', '=', request('designation_id', 0));
        })
        ->when(request('department_id'), function ($q) {
            return $q->where('employee_h_rs.department_id', '=', request('department_id', 0));
        })
        ->get([
         'employee_h_rs.*'
        ]);

        foreach($rows as $row){
            $employeehr['id']=$row->id;
            $employeehr['name']=$row->name;
            $employeehr['code']=$row->code;
            //$employeehr['company_id']=$company[$row->company_id];
            $employeehr['designation_id']=isset($designation[$row->designation_id])?$designation[$row->designation_id]:'';
            $employeehr['department_id']=isset($department[$row->department_id])?$department[$row->department_id]:'';
            $employeehr['date_of_join']=($row->date_of_join !== null)?date("Y-m-d",strtotime($row->date_of_join)):null;
            $employeehr['national_id']=$row->national_id;
            $employeehr['last_education']=$row->last_education;
            $employeehr['experience']=$row->experience;
            $employeehr['email']=$row->email;
            $employeehr['contact']=$row->contact;
            array_push($employeehrs,$employeehr);
        }
        echo json_encode($employeehrs);

    }


}

