<?php

namespace App\Http\Controllers\Production\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishProgRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;



use App\Library\Template;
use App\Http\Requests\Production\Dyeing\ProdBatchFinishProgRequest;

class ProdBatchFinishProgController extends Controller
{

 private $prodbatch;
 private $prodbatchfinishprog;
 private $company;
 private $location;
 private $color;
 private $colorrange;
 private $assetquantitycost;
 private $uom;
 private $productionprocess;
 private $autoyarn;
 private $gmtspart;
 private $itemaccount;
 private $designation;
 private $department;
 private $employeehr;


 public function __construct(
  ProdBatchRepository $prodbatch,
  ProdBatchFinishProgRepository $prodbatchfinishprog,
  CompanyRepository $company,
  LocationRepository $location,
  ColorRepository $color,
  ColorrangeRepository $colorrange,
  AssetQuantityCostRepository $assetquantitycost,
  UomRepository $uom,
  ProductionProcessRepository $productionprocess,
  AutoyarnRepository $autoyarn,
  GmtspartRepository $gmtspart,
  ItemAccountRepository $itemaccount,
  EmployeeHRRepository $employeehr,
  DesignationRepository $designation,
  DepartmentRepository $department
 ) {
  $this->prodbatch = $prodbatch;
  $this->prodbatchfinishprog = $prodbatchfinishprog;
  $this->company = $company;
  $this->location = $location;
  $this->color = $color;
  $this->colorrange = $colorrange;
  $this->assetquantitycost = $assetquantitycost;
  $this->uom = $uom;
  $this->productionprocess = $productionprocess;
  $this->autoyarn = $autoyarn;
  $this->gmtspart = $gmtspart;
  $this->itemaccount = $itemaccount;
  $this->employeehr = $employeehr;
  $this->designation = $designation;
  $this->department = $department;

  $this->middleware('auth');
  /*$this->middleware('permission:view.prodbatchfinishprogs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodbatchfinishprogs', ['only' => ['store']]);
        $this->middleware('permission:edit.prodbatchfinishprogs',   ['only' => ['update']]);
        $this->middleware('permission:delete.prodbatchfinishprogs', ['only' => ['destroy']]);*/
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $batchfor = array_prepend(config('bprs.batchfor'), '-Select-', '');
  $shiftname = array_prepend(config('bprs.shiftname'), '-Select-', '');

  $rows = $this->prodbatchfinishprog
   ->join('prod_batches', function ($join) {
    $join->on('prod_batches.id', '=', 'prod_batch_finish_progs.prod_batch_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'prod_batches.company_id');
   })
   ->leftJoin('colors', function ($join) {
    $join->on('colors.id', '=', 'prod_batches.fabric_color_id');
   })
   ->join('colors as batch_colors', function ($join) {
    $join->on('batch_colors.id', '=', 'prod_batches.batch_color_id');
   })
   ->leftJoin('asset_quantity_costs', function ($join) {
    $join->on('asset_quantity_costs.id', '=', 'prod_batch_finish_progs.machine_id');
   })
   ->leftJoin('colorranges', function ($join) {
    $join->on('colorranges.id', '=', 'prod_batches.colorrange_id');
   })
   ->join('production_processes', function ($join) {
    $join->on('production_processes.id', '=', 'prod_batch_finish_progs.production_process_id');
   })
   ->leftJoin('employee_h_rs as operator', function ($join) {
    $join->on('operator.id', '=', 'prod_batch_finish_progs.operator_id');
   })
   ->leftJoin('employee_h_rs as incharge', function ($join) {
    $join->on('incharge.id', '=', 'prod_batch_finish_progs.incharge_id');
   })
   ->orderBy('prod_batch_finish_progs.id', 'desc')

   ->get([
    'prod_batch_finish_progs.*',
    'prod_batches.batch_no',
    'prod_batches.batch_date',
    'prod_batches.batch_for',
    'prod_batches.batch_wgt',
    'prod_batches.fabric_wgt',
    'companies.code as company_code',
    'colors.name as color_name',
    'batch_colors.name as batch_color_name',
    'asset_quantity_costs.custom_no as machine_no',
    'colorranges.name as color_range_name',
    'production_processes.process_name',
    'incharge.name as incharge_name',
    'operator.name as operator_name',
   ])
   ->take(100)
   ->map(function ($rows) use ($batchfor, $shiftname) {
    $rows->batch_for = $rows->batch_for ? $batchfor[$rows->batch_for] : '';
    $rows->shiftname = $rows->shift_id ? $shiftname[$rows->shift_id] : '';
    $rows->batch_date = date('Y-m-d', strtotime($rows->batch_date));
    $rows->posting_date = date('Y-m-d', strtotime($rows->posting_date));
    $rows->load_date = date('Y-m-d', strtotime($rows->loaded_at));
    $rows->load_time = date('h:i:s A', strtotime($rows->loaded_at));
    $rows->unload_date = date('Y-m-d', strtotime($rows->unloaded_at));
    $rows->unload_time = date('h:i:s A', strtotime($rows->unloaded_at));
    return $rows;
   });
  echo json_encode($rows);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $color = array_prepend(array_pluck($this->color->get(), 'name', 'id'), '', '');
  $colorrange = array_prepend(array_pluck($this->colorrange->get(), 'name', 'id'), '', '');
  $uom = array_prepend(array_pluck($this->uom->get(), 'name', 'id'), '', '');
  $process_name = array_prepend(array_pluck($this->productionprocess->whereIn('production_area_id', [20, 30])->get(), 'process_name', 'id'), '', '');
  $batchfor = array_prepend(config('bprs.batchfor'), '-Select-', '');
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
  $shiftname = array_prepend(config('bprs.shiftname'), '-Select-', '');
  $designation = array_prepend(array_pluck($this->designation->get(), 'name', 'id'), '-Select-', '');
  $department = array_prepend(array_pluck($this->department->get(), 'name', 'id'), '-Select-', '');




  return Template::loadView('Production.Dyeing.ProdBatchFinishProg', [
   'company' => $company,
   'color' => $color,
   'colorrange' => $colorrange,
   'batchfor' => $batchfor,
   'uom' => $uom,
   'process_name' => $process_name,
   'location' => $location,
   'shiftname' => $shiftname,
   'designation' => $designation,
   'department' => $department,
  ]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(ProdBatchFinishProgRequest $request)
 {
  $loaded_at = date('Y-m-d H:i:s', strtotime($request->load_date . " " . $request->load_time));
  $unloaded_at = date('Y-m-d H:i:s', strtotime($request->unload_date . " " . $request->unload_time));
  //$posting_date=date('Y-m-d');

  $request->request->add(['loaded_at' => $loaded_at]);
  $request->request->add(['unloaded_at' => $unloaded_at]);
  $request->request->add(['start_date' => $request->load_date]);
  $request->request->add(['end_date' => $request->unload_date]);
  // $request->request->add(['posting_date' =>$posting_date]);
  $prodbatchfinishprog = $this->prodbatchfinishprog->create($request->except(['id', 'batch_no', 'operator_name', 'incharge_name', 'load_date', 'load_time', 'unload_date', 'unload_time', 'machine_no']));

  if ($prodbatchfinishprog) {
   return response()->json(array('success' => true, 'id' =>  $prodbatchfinishprog->id, 'message' => 'Save Successfully'), 200);
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
  $rows = $this->prodbatchfinishprog
   ->join('prod_batches', function ($join) {
    $join->on('prod_batches.id', '=', 'prod_batch_finish_progs.prod_batch_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'prod_batches.company_id');
   })
   ->join('colors', function ($join) {
    $join->on('colors.id', '=', 'prod_batches.fabric_color_id');
   })
   ->join('colors as batch_colors', function ($join) {
    $join->on('batch_colors.id', '=', 'prod_batches.batch_color_id');
   })
   ->leftJoin('asset_quantity_costs', function ($join) {
    $join->on('asset_quantity_costs.id', '=', 'prod_batch_finish_progs.machine_id');
   })
   ->leftJoin('asset_acquisitions', function ($join) {
    $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
   })
   ->leftJoin('colorranges', function ($join) {
    $join->on('colorranges.id', '=', 'prod_batches.colorrange_id');
   })
   ->join('production_processes', function ($join) {
    $join->on('production_processes.id', '=', 'prod_batch_finish_progs.production_process_id');
   })
   ->leftJoin('employee_h_rs as operator', function ($join) {
    $join->on('operator.id', '=', 'prod_batch_finish_progs.operator_id');
   })
   ->leftJoin('employee_h_rs as incharge', function ($join) {
    $join->on('incharge.id', '=', 'prod_batch_finish_progs.incharge_id');
   })
   ->where([['prod_batch_finish_progs.id', '=', $id]])
   ->orderBy('prod_batches.id', 'desc')
   ->get([
    'prod_batch_finish_progs.*',
    'prod_batches.batch_no',
    'prod_batches.company_id',
    'prod_batches.location_id',
    'prod_batches.fabric_color_id',
    'prod_batches.batch_color_id',
    'prod_batches.colorrange_id',
    'prod_batches.lap_dip_no',
    'prod_batches.batch_date',
    'prod_batches.batch_for',
    'prod_batches.batch_wgt',
    'prod_batches.fabric_wgt',
    'companies.code as company_code',
    'colors.name as color_name',
    'batch_colors.name as batch_color_name',
    'asset_quantity_costs.custom_no as machine_no',
    'colorranges.name as color_range_name',
    'production_processes.process_name',
    'incharge.name as incharge_name',
    'operator.name as operator_name',
    'asset_acquisitions.brand',
    'asset_acquisitions.prod_capacity'
   ])
   ->map(function ($rows) {
    $rows->batch_date = date('Y-m-d', strtotime($rows->batch_date));
    $rows->posting_date = date('Y-m-d', strtotime($rows->posting_date));
    $rows->load_date = date('Y-m-d', strtotime($rows->loaded_at));
    $rows->load_time = date('h:i:s A', strtotime($rows->loaded_at));
    $rows->unload_date = date('Y-m-d', strtotime($rows->unloaded_at));
    $rows->unload_time = date('h:i:s A', strtotime($rows->unloaded_at));
    return $rows;
   })
   ->first();
  $row['fromData'] = $rows;
  $dropdown['att'] = '';
  $row['dropDown'] = $dropdown;
  echo json_encode($row);
 }

 /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function update(ProdBatchFinishProgRequest $request, $id)
 {
  $loaded_at = date('Y-m-d H:i:s', strtotime($request->load_date . " " . $request->load_time));
  $unloaded_at = date('Y-m-d H:i:s', strtotime($request->unload_date . " " . $request->unload_time));
  //$posting_date=date('Y-m-d');

  $request->request->add(['loaded_at' => $loaded_at]);
  $request->request->add(['unloaded_at' => $unloaded_at]);
  $request->request->add(['start_date' => $request->load_date]);
  $request->request->add(['end_date' => $request->unload_date]);
  //$request->request->add(['posting_date' =>$posting_date]);
  $prodbatchfinishprog = $this->prodbatchfinishprog->update($id, $request->except(['id', 'prod_batch_id', 'batch_no', 'operator_name', 'incharge_name', 'load_date', 'load_time', 'unload_date', 'unload_time', 'machine_no', 'start_date', 'end_date']));

  if ($prodbatchfinishprog) {
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
  if ($this->prodbatchfinishprog->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getBatch()
 {

  $batchfor = array_prepend(config('bprs.batchfor'), '-Select-', '');

  $rows = $this->prodbatch
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'prod_batches.company_id');
   })
   ->join('colors', function ($join) {
    $join->on('colors.id', '=', 'prod_batches.fabric_color_id');
   })
   ->join('colors as batch_colors', function ($join) {
    $join->on('batch_colors.id', '=', 'prod_batches.batch_color_id');
   })
   ->leftJoin('asset_quantity_costs', function ($join) {
    $join->on('asset_quantity_costs.id', '=', 'prod_batches.machine_id');
   })
   ->leftJoin('colorranges', function ($join) {
    $join->on('colorranges.id', '=', 'prod_batches.colorrange_id');
   })
   ->leftJoin('asset_acquisitions', function ($join) {
    $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
   })
   ->whereNotNull('prod_batches.unloaded_at')
   ->when(request('batch_no'), function ($q) {
    return $q->where('prod_batches.batch_no', '=', request('batch_no', 0));
   })
   ->when(request('company_id'), function ($q) {
    return $q->where('prod_batches.company_id', '=', request('company_id', 0));
   })
   ->when(request('batch_for'), function ($q) {
    return $q->where('prod_batches.batch_for', '=', request('batch_for', 0));
   })
   ->orderBy('prod_batches.id', 'desc')
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
   ->map(function ($rows) use ($batchfor) {
    $rows->batchfor = $rows->batch_for ? $batchfor[$rows->batch_for] : '';
    $rows->batch_date = date('Y-m-d', strtotime($rows->batch_date));
    return $rows;
   });
  echo json_encode($rows);
 }

 public function getMachine()
 {
  $machine = $this->assetquantitycost
   ->join('asset_acquisitions', function ($join) {
    $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
   })
   ->leftJoin('asset_technical_features', function ($join) {
    $join->on('asset_acquisitions.id', '=', 'asset_technical_features.asset_acquisition_id');
   })
   ->when(request('brand'), function ($q) {
    return $q->where('asset_acquisitions.brand', 'like', '%' . request('brand', 0) . '%');
   })
   ->when(request('machine_no'), function ($q) {
    return $q->where('asset_quantity_costs.custom_no', '=', request('machine_no', 0));
   })
   ->where([['asset_acquisitions.production_area_id', '=', 30]])
   ->orderBy('asset_acquisitions.id', 'asc')
   ->orderBy('asset_quantity_costs.id', 'asc')
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

 public function getEmployeeHr()
 {
  $designation = array_prepend(array_pluck($this->designation->get(), 'name', 'id'), '-Select-', '');
  $department = array_prepend(array_pluck($this->department->get(), 'name', 'id'), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');

  $employeehr = $this->employeehr
   ->when(request('company_id'), function ($q) {
    return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
   })
   ->when(request('designation_id'), function ($q) {
    return $q->where('employee_h_rs.designation_id', '=', request('designation_id', 0));
   })
   ->when(request('department_id'), function ($q) {
    return $q->where('employee_h_rs.department_id', '=', request('department_id', 0));
   })
   ->get([
    'employee_h_rs.*'
   ])
   ->map(function ($employeehr) use ($company, $designation, $department) {
    $employeehr->employee_name = $employeehr->name;
    $employeehr->company_id = $company[$employeehr->company_id];
    $employeehr->designation_id = isset($designation[$employeehr->designation_id]) ? $designation[$employeehr->designation_id] : '';
    $employeehr->department_id = isset($department[$employeehr->department_id]) ? $department[$employeehr->department_id] : '';
    return $employeehr;
   });

  echo json_encode($employeehr);
 }



 public function getList()
 {
  /*$batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $rows=$this->prodbatch
        ->join('companies',function($join){
            $join->on('companies.id','=','prod_batches.company_id');
        })
        ->join('colors',function($join){
            $join->on('colors.id','=','prod_batches.fabric_color_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_batches.machine_id');
        })
        ->leftJoin('colorranges',function($join){
            $join->on('colorranges.id','=','prod_batches.colorrange_id');
        })
        ->whereNotNull('prod_batches.loaded_at')
        ->when(request('from_batch_date'), function ($q) {
        return $q->where('prod_batches.batch_date', '>=', request('from_batch_date', 0));
        })
        ->when(request('to_batch_date'), function ($q) {
        return $q->where('prod_batches.batch_date', '<=', request('to_batch_date', 0));
        })
        ->when(request('from_load_posting_date'), function ($q) {
        return $q->where('prod_batches.load_posting_date', '>=', request('from_load_posting_date', 0));
        })
        ->when(request('to_load_posting_date'), function ($q) {
        return $q->where('prod_batches.load_posting_date', '<=', request('to_load_posting_date', 0));
        })
        ->orderBy('prod_batches.id','desc')
        ->get([
            'prod_batches.*',
            'companies.code as company_code',
            'colors.name as color_name',
            'asset_quantity_costs.custom_no as machine_no',
            'colorranges.name as color_range_name',
            'rootbatches.batch_no as root_batch_no',
        ])
        ->map(function($rows) use($batchfor){
            $rows->batch_for=$rows->batch_for?$batchfor[$rows->batch_for]:'';
            $rows->batch_date=date('Y-m-d',strtotime($rows->batch_date));
            $rows->load_posting_date=date('Y-m-d',strtotime($rows->load_posting_date));
            $rows->load_date=date('Y-m-d',strtotime($rows->loaded_at));
            $rows->load_time=date('h:i:s A',strtotime($rows->loaded_at));
            return $rows;
        });
        echo json_encode($rows);*/
  $batchfor = array_prepend(config('bprs.batchfor'), '-Select-', '');
  $shiftname = array_prepend(config('bprs.shiftname'), '-Select-', '');

  $rows = $this->prodbatchfinishprog
   ->join('prod_batches', function ($join) {
    $join->on('prod_batches.id', '=', 'prod_batch_finish_progs.prod_batch_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'prod_batches.company_id');
   })
   ->join('colors', function ($join) {
    $join->on('colors.id', '=', 'prod_batches.fabric_color_id');
   })
   ->join('colors as batch_colors', function ($join) {
    $join->on('batch_colors.id', '=', 'prod_batches.batch_color_id');
   })
   ->leftJoin('asset_quantity_costs', function ($join) {
    $join->on('asset_quantity_costs.id', '=', 'prod_batch_finish_progs.machine_id');
   })
   ->leftJoin('colorranges', function ($join) {
    $join->on('colorranges.id', '=', 'prod_batches.colorrange_id');
   })
   ->join('production_processes', function ($join) {
    $join->on('production_processes.id', '=', 'prod_batch_finish_progs.production_process_id');
   })
   ->leftJoin('employee_h_rs as operator', function ($join) {
    $join->on('operator.id', '=', 'prod_batch_finish_progs.operator_id');
   })
   ->leftJoin('employee_h_rs as incharge', function ($join) {
    $join->on('incharge.id', '=', 'prod_batch_finish_progs.incharge_id');
   })
   ->orderBy('prod_batch_finish_progs.id', 'desc')
   ->when(request('from_batch_date'), function ($q) {
    return $q->where('prod_batches.batch_date', '>=', request('from_batch_date', 0));
   })
   ->when(request('to_batch_date'), function ($q) {
    return $q->where('prod_batches.batch_date', '<=', request('to_batch_date', 0));
   })
   ->when(request('from_load_posting_date'), function ($q) {
    return $q->where('prod_batch_finish_progs.posting_date', '>=', request('from_load_posting_date', 0));
   })
   ->when(request('to_load_posting_date'), function ($q) {
    return $q->where('prod_batch_finish_progs.posting_date', '<=', request('to_load_posting_date', 0));
   })

   ->get([
    'prod_batch_finish_progs.*',
    'prod_batches.batch_no',
    'prod_batches.batch_date',
    'prod_batches.batch_for',
    'prod_batches.batch_wgt',
    'prod_batches.fabric_wgt',
    'companies.code as company_code',
    'colors.name as color_name',
    'batch_colors.name as batch_color_name',
    'asset_quantity_costs.custom_no as machine_no',
    'colorranges.name as color_range_name',
    'production_processes.process_name',
    'incharge.name as incharge_name',
    'operator.name as operator_name',
   ])
   ->map(function ($rows) use ($batchfor, $shiftname) {
    $rows->batch_for = $rows->batch_for ? $batchfor[$rows->batch_for] : '';
    $rows->shiftname = $rows->shift_id ? $shiftname[$rows->shift_id] : '';
    $rows->batch_date = date('Y-m-d', strtotime($rows->batch_date));
    $rows->posting_date = date('Y-m-d', strtotime($rows->posting_date));
    $rows->load_date = date('Y-m-d', strtotime($rows->loaded_at));
    $rows->load_time = date('h:i:s A', strtotime($rows->loaded_at));
    $rows->unload_date = date('Y-m-d', strtotime($rows->unloaded_at));
    $rows->unload_time = date('h:i:s A', strtotime($rows->unloaded_at));
    return $rows;
   });
  echo json_encode($rows);
 }
}
