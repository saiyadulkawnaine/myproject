<?php

namespace App\Http\Controllers\Production\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishMcParameterRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishMcSetupRepository;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\DivisionRepository;
use App\Repositories\Contracts\Util\SectionRepository;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchRepository;
use App\Library\Template;


use App\Http\Requests\Production\Dyeing\ProdFinishMcParameterRequest;

class ProdFinishMcParameterController extends Controller {

    private $prodfinishmcparameter;
    private $prodfinishmcsetup;
    private $employeehr;
    private $designation;
    private $department;
    private $user;
    private $division;
    private $section;
    private $subsection;
    private $location;
    private $company;
    private $prodbatch;

    public function __construct(
        ProdFinishMcParameterRepository $prodfinishmcparameter,
        ProdFinishMcSetupRepository $prodfinishmcsetup,
        EmployeeHRRepository $employeehr,
        DesignationRepository $designation,
        DepartmentRepository $department,
        DivisionRepository $division,
        SectionRepository $section,
        SubsectionRepository $subsection,
        CompanyRepository $company,
        UserRepository $user,
        LocationRepository $location,
        ProdBatchRepository $prodbatch
    ) {
        $this->prodfinishmcparameter = $prodfinishmcparameter;
        $this->prodfinishmcsetup = $prodfinishmcsetup;
        $this->employeehr = $employeehr;
        $this->designation = $designation;
        $this->department = $department;
        $this->division = $division;
        $this->section = $section;
        $this->subsection = $subsection;
        $this->company = $company;
        $this->location = $location;
        $this->user = $user;
        $this->prodbatch = $prodbatch;

        $this->middleware('auth');
        // $this->middleware('permission:view.prodfinishmcparameters',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.prodfinishmcparameters', ['only' => ['store']]);
        // $this->middleware('permission:edit.prodfinishmcparameters',   ['only' => ['update']]);
        // $this->middleware('permission:delete.prodfinishmcparameters', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $shiftname=array_prepend(config('bprs.shiftname'),'','');
       $prodfinishmcparameters = array();
       $rows=$this->prodfinishmcparameter
       ->join('prod_batches',function($join){
          $join->on('prod_batches.id','=','prod_finish_mc_parameters.prod_batch_id');
         })
        ->leftJoin('employee_h_rs',function($join){
            $join->on('employee_h_rs.id','=','prod_finish_mc_parameters.employee_h_r_id');
        })
        ->where([['prod_finish_mc_date_id','=',request('prod_finish_mc_date_id',0)]])
        ->get([
            'prod_finish_mc_parameters.*',
             'prod_batches.batch_no',
             'prod_batches.batch_wgt',
             'employee_h_rs.name as employee_name'
       ]);
       foreach($rows as $row){
           $prodfinishmcparameter['id']=$row->id;
           $prodfinishmcparameter['rmp']=$row->rmp;
           $prodfinishmcparameter['batch_no']=$row->batch_no;
           $prodfinishmcparameter['gsm_weight']=$row->gsm_weight;
           $prodfinishmcparameter['batch_wgt']=$row->batch_wgt;
           $prodfinishmcparameter['dia']=$row->dia;
           $prodfinishmcparameter['working_minute']=number_format($row->working_minute,2);
           $prodfinishmcparameter['shift_id']=isset($shiftname[$row->shift_id])?$shiftname[$row->shift_id]:'';
           $prodfinishmcparameter['employee_name']=$row->employee_name;
           $prodfinishmcparameter['remarks']=$row->remarks;

           array_push($prodfinishmcparameters,$prodfinishmcparameter);
       }
       echo json_encode($prodfinishmcparameters);
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
    public function store(ProdFinishMcParameterRequest $request) {
        $prodfinishmcparameter=$this->prodfinishmcparameter->create([
            'prod_finish_mc_date_id'=>$request->prod_finish_mc_date_id,
            'prod_batch_id'=>$request->prod_batch_id,
            'employee_h_r_id'=>$request->employee_h_r_id,
            'rmp'=>$request->rmp,
            'gsm_weight'=>$request->gsm_weight,
            'dia'=>$request->dia,
            'working_minute'=>$request->working_minute,
            'shift_id'=>$request->shift_id,
            'remarks'=>$request->remarks,
        ]);
        
        if($prodfinishmcparameter){
            return response()->json(array('success' => true,'id' =>  $prodfinishmcparameter->id,'message' => 'Save Successfully'),200);
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
     $prodfinishmcparameter=$this->prodfinishmcparameter
     ->leftJoin('prod_batches',function($join){
      $join->on('prod_batches.id','=','prod_finish_mc_parameters.prod_batch_id');
     })
      ->leftJoin('employee_h_rs',function($join){
            $join->on('employee_h_rs.id','=','prod_finish_mc_parameters.employee_h_r_id');
        })->where([['prod_finish_mc_parameters.id','=',$id]])
        ->get([
           'prod_finish_mc_parameters.*',
           'prod_batches.batch_no',
           'prod_batches.batch_wgt',
           'employee_h_rs.name as employee_name',
           
       ])
       ->first();
        $row ['fromData'] = $prodfinishmcparameter;
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
    public function update(ProdFinishMcParameterRequest $request, $id) {
        $prodfinishmcparameter=$this->prodfinishmcparameter->update($id,[
            'prod_finish_mc_date_id'=>$request->prod_finish_mc_date_id,
            'prod_batch_id'=>$request->prod_batch_id,
            'employee_h_r_id'=>$request->employee_h_r_id,
            'rmp'=>$request->rmp,
            'gsm_weight'=>$request->gsm_weight,
            'dia'=>$request->dia,
            'working_minute'=>$request->working_minute,
            'shift_id'=>$request->shift_id,
            'remarks'=>$request->remarks
        ]);
        if($prodfinishmcparameter){
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
        if($this->prodfinishmcparameter->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getFinishMcParameterBatch(){
        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');

        $rows=$this->prodbatch
        ->join('companies',function($join){
            $join->on('companies.id','=','prod_batches.company_id');
        })
        ->leftJoin('colors',function($join){
            $join->on('colors.id','=','prod_batches.fabric_color_id');
        })
        ->leftJoin('colors as batch_colors',function($join){
            $join->on('batch_colors.id','=','prod_batches.batch_color_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_batches.machine_id');
        })
        ->leftJoin('colorranges',function($join){
            $join->on('colorranges.id','=','prod_batches.colorrange_id');
        })
        ->leftJoin('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        //->whereNull('prod_batches.loaded_at')
        ->whereNotNull('prod_batches.unloaded_at')
        ->whereNotNull('prod_batches.batch_wgt')
        //->whereNotNull('prod_batches.approved_at')
        ->when(request('batch_no'), function ($q) {
        return $q->where('prod_batches.batch_no', '=',request('batch_no', 0));
        })
        ->when(request('company_id'), function ($q) {
        return $q->where('prod_batches.company_id', '=',request('company_id', 0));
        })
        ->orderBy('prod_batches.id','desc')
        ->get([
             'prod_batches.*',
             'companies.code as company_code',
             'colors.name as color_name',
             'batch_colors.name as batch_color_name',
             'asset_quantity_costs.custom_no as machine_no',
             'asset_acquisitions.brand',
             'asset_acquisitions.prod_capacity',
             'colorranges.name as color_range_name',
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
        $prod_finish_mc_setup_id=request('prodfinishmcsetupId',0);
        $prodfinishmcsetup=$this->prodfinishmcsetup->find($prod_finish_mc_setup_id);

        $company=$this->company
        ->join('asset_acquisitions',function($join){
            $join->on('companies.id','=','asset_acquisitions.company_id');
        })
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->where([['asset_quantity_costs.id','=',$prodfinishmcsetup->machine_id]])
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
        ->when(request('company_id'), function ($q) {
            return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
        })
        ->get([
         'employee_h_rs.*'
        ]);
        foreach($rows as $row){
           $employeehr['id']=$row->id; 
           $employeehr['name']=$row->name;
           $employeehr['code']=$row->code;
           $employeehr['company_id']=$company[$row->company_id];
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
