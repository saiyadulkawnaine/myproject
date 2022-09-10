<?php

namespace App\Http\Controllers\Production\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\AOP\ProdAopBatchRepository;
use App\Repositories\Contracts\Production\AOP\ProdAopBatchProcessRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;



use App\Library\Template;
use App\Http\Requests\Production\AOP\ProdAopBatchProcessRequest;

class ProdAopBatchProcessController extends Controller {

    private $prodaopbatch;
    private $prodaopbatchprocess;
    private $assetquantitycost;
    private $employeehr;

    public function __construct(
        ProdAopBatchRepository $prodaopbatch,  
        ProdAopBatchProcessRepository $prodaopbatchprocess,
        AssetQuantityCostRepository $assetquantitycost,
        EmployeeHRRepository $employeehr
    ) {
        $this->prodaopbatch = $prodaopbatch;
        $this->prodaopbatchprocess = $prodaopbatchprocess;
        $this->assetquantitycost = $assetquantitycost;
        $this->employeehr = $employeehr;
        $this->middleware('auth');
        $this->middleware('permission:view.prodaopbatchprocesses',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodaopbatchprocesses', ['only' => ['store']]);
        $this->middleware('permission:edit.prodaopbatchprocesses',   ['only' => ['update']]);
        $this->middleware('permission:delete.prodaopbatchprocesses', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
            $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
            $prodknitqc=$this->prodaopbatch
            ->join('prod_aop_batch_processes',function($join){
            $join->on('prod_aop_batch_processes.prod_aop_batch_id','=','prod_aop_batches.id');
            })
            ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_aop_batch_processes.asset_quantity_cost_id');
            })
            ->join('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
            })
            ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_quantity_costs.asset_acquisition_id','=','asset_technical_features.asset_acquisition_id');
            })
            ->join('employee_h_rs',function($join){
            $join->on('employee_h_rs.id','=','prod_aop_batch_processes.supervisor_id');
            })
            ->join('production_processes', function($join)  {
            $join->on('production_processes.id', '=', 'prod_aop_batch_processes.production_process_id');
            })
            ->where([['prod_aop_batches.id','=',request('prod_aop_batch_id',0)]])
            ->get([
            'prod_aop_batch_processes.*',
            'asset_quantity_costs.custom_no as machine_no',
            'asset_acquisitions.prod_capacity',
            'asset_acquisitions.origin',
            'asset_acquisitions.brand',
            'production_processes.process_name',
            'employee_h_rs.name as supervisor_name',
            ])
            ->map(function($prodknitqc) use($shiftname){
                $prodknitqc->shift_name=$shiftname[$prodknitqc->shift_id];
                return $prodknitqc;

            });
            echo json_encode($prodknitqc);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdAopBatchProcessRequest $request) {

        $batch=$this->prodaopbatch->find($request->prod_aop_batch_id);
        if($batch->approved_at){
        return response()->json(array('success' => false,'id' => $request->prod_aop_batch_id,'message' => 'This Batch is Approved. Roll Adding Not Allowed'),200);
        }
            
        $prodaopbatchprocess = $this->prodaopbatchprocess->create($request->except(['id','supervisor_name','machine_no']));
        if($prodaopbatchprocess){
            return response()->json(array('success' => true,'id' =>  $prodaopbatchprocess->id,'prod_aop_batch_id'=>$request->prod_aop_batch_id,'message' => 'Save Successfully'),200);
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
        $prodbatchprocess=$this->prodaopbatchprocess
        ->join('asset_quantity_costs',function($join){
        $join->on('asset_quantity_costs.id','=','prod_aop_batch_processes.asset_quantity_cost_id');
        })
        ->join('asset_acquisitions',function($join){
        $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->leftJoin('asset_technical_features',function($join){
        $join->on('asset_quantity_costs.asset_acquisition_id','=','asset_technical_features.asset_acquisition_id');
        })
        ->join('employee_h_rs',function($join){
        $join->on('employee_h_rs.id','=','prod_aop_batch_processes.supervisor_id');
        })
        ->join('production_processes', function($join)  {
        $join->on('production_processes.id', '=', 'prod_aop_batch_processes.production_process_id');
        })
        ->where([['prod_aop_batch_processes.id','=',$id]])
        ->get([
        'prod_aop_batch_processes.*',
        'asset_quantity_costs.custom_no as machine_no',
        'asset_acquisitions.prod_capacity',
        'asset_acquisitions.origin',
        'asset_acquisitions.brand',
        'production_processes.process_name',
        'employee_h_rs.name as supervisor_name',
        ])
        ->first();
        $row ['fromData'] = $prodbatchprocess;
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
    public function update(ProdAopBatchProcessRequest $request, $id) {
        $batch=$this->prodaopbatch->find($request->prod_aop_batch_id);
        if($batch->approved_at){
        return response()->json(array('success' => false,'id' => $request->prod_aop_batch_id,'message' => 'This Batch is Approved. Roll Adding Not Allowed'),200);
        }
            
        $prodaopbatchprocess = $this->prodaopbatchprocess->update($id,$request->except(['id','supervisor_name','machine_no']));
        if($prodaopbatchprocess){
            return response()->json(array('success' => true,'id' =>  $id,'prod_aop_batch_id'=>$request->prod_aop_batch_id,'message' => 'Save Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $prodaopbatchprocess=$this->prodaopbatchprocess->find($id);
        $prodaopbatch=$this->prodaopbatch->find($prodaopbatchprocess->prod_aop_batch_id);
        if($prodaopbatch->approved_at){
        return response()->json(array('success' => false,'id' => $id,'message' => 'This Batch is Approved. Delete Not Allowed'),200);
        }

        if($this->prodaopbatchprocess->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getMachine()
    {
        $machine=$this->assetquantitycost
        ->join('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_acquisitions.id','=','asset_technical_features.asset_acquisition_id');
        })
        ->when(request('brand'), function ($q) {
        return $q->where('asset_acquisitions.brand', 'like','%'.request('brand', 0).'%');
        })
        ->when(request('machine_no'), function ($q) {
        return $q->where('asset_quantity_costs.custom_no', '=',request('machine_no', 0));
        })
        ->where([['asset_acquisitions.production_area_id','=',25]])
        ->orderBy('asset_acquisitions.id','asc')
        ->orderBy('asset_quantity_costs.id','asc')
        ->get([
            'asset_quantity_costs.*',
            'asset_acquisitions.prod_capacity',
            'asset_acquisitions.name as asset_name',
            'asset_acquisitions.origin',
            'asset_acquisitions.brand',
            'asset_technical_features.dia_width',
            'asset_technical_features.gauge',
            'asset_technical_features.extra_cylinder',
            'asset_technical_features.no_of_feeder'
        ]);
        echo json_encode($machine);
    }
    public function getEmployeeHr(){
      

      $employeehr=$this->employeehr
        ->join('designations',function($join){
        $join->on('designations.id','=','employee_h_rs.designation_id');
        })
        ->join('departments',function($join){
        $join->on('departments.id','=','employee_h_rs.department_id');
        })
        ->join('companies',function($join){
        $join->on('companies.id','=','employee_h_rs.company_id');
        })
        ->when(request('company_id'), function ($q) {
        return $q->where('employee_h_rs.company_id','=',request('company_id', 0));
        })
        ->when(request('designation_id'), function ($q) {
        return $q->where('employee_h_rs.designation_id','=',request('designation_id', 0));
        })   
        ->when(request('department_id'), function ($q) {
        return $q->where('employee_h_rs.department_id','=',request('department_id', 0));
        }) 
      ->get([
        'employee_h_rs.*',
        'companies.name as company_name',
        'designations.name as designation_name',
        'departments.name as department_name',
      ]);
      echo json_encode($employeehr);
    }
}