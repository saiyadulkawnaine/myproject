<?php

namespace App\Http\Controllers\Production\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Production\AOP\ProdAopBatchRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishQcRepository;
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
use App\Http\Requests\Production\AOP\ProdAopBatchFinishQcRequest;

class ProdAopBatchFinishQcController extends Controller {

    private $prodaopbatch;
    private $prodaopbatchfinishqc;
    private $company;
    private $location;
    private $buyer;
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
        ProdAopBatchRepository $prodaopbatch,  
        ProdBatchFinishQcRepository $prodaopbatchfinishqc,  
        CompanyRepository $company,
        LocationRepository $location, 
        BuyerRepository $buyer, 
        ColorRepository $color,
        ColorrangeRepository $colorrange,
        AssetQuantityCostRepository $assetquantitycost,
        UomRepository $uom,
        ProductionProcessRepository $productionprocess ,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        ItemAccountRepository $itemaccount,
        EmployeeHRRepository $employeehr,
        DesignationRepository $designation,
        DepartmentRepository $department
    ) {
        $this->prodaopbatch = $prodaopbatch;
        $this->prodaopbatchfinishqc = $prodaopbatchfinishqc;
        $this->company = $company;
        $this->location = $location;
        $this->buyer = $buyer;
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

        /*$this->middleware('permission:view.prodaopbatchfinishqcs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.prodaopbatchfinishqcs', ['only' => ['store']]);
        $this->middleware('permission:edit.prodaopbatchfinishqcs',   ['only' => ['update']]);
        $this->middleware('permission:delete.prodaopbatchfinishqcs', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');

        $rows=$this->prodaopbatchfinishqc
        ->join('prod_aop_batches',function($join){
            $join->on('prod_aop_batches.id','=','prod_batch_finish_qcs.prod_aop_batch_id');
        })
        
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
        ->leftJoin('employee_h_rs',function($join){
            $join->on('employee_h_rs.id','=','prod_batch_finish_qcs.qc_by_id');
        })
        ->orderBy('prod_batch_finish_qcs.id','desc')
        ->get([
            'prod_batch_finish_qcs.*',
            'prod_aop_batches.batch_no',
            'prod_aop_batches.batch_date',
            'prod_aop_batches.batch_for',
            'prod_aop_batches.paste_wgt',
            'prod_aop_batches.fabric_wgt',
            'companies.code as company_code',
            'batch_colors.name as batch_color_name',
            'employee_h_rs.name as qc_by_name',
        ])
        ->take(100)
        ->map(function($rows) use($batchfor,$shiftname){
            $rows->batch_for=$rows->batch_for?$batchfor[$rows->batch_for]:'';
            $rows->shiftname=$rows->shift_id?$shiftname[$rows->shift_id]:'';
            $rows->batch_date=date('Y-m-d',strtotime($rows->batch_date));
            $rows->posting_date=date('Y-m-d',strtotime($rows->posting_date));
            return $rows;
        });
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'','');
        $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'','');
        $process_name=array_prepend(array_pluck($this->productionprocess->whereIn('production_area_id',[20,30])->get(),'process_name','id'),'','');
        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $location = array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $rollqcresult=array_prepend(config('bprs.rollqcresult'),'-Select-','');





        return Template::loadView('Production.AOP.ProdAopBatchFinishQc', [ 
            'company'=> $company,
            'color'=>$color,
            'colorrange'=>$colorrange,
            'batchfor'=>$batchfor,
            'uom'=>$uom,
            'process_name'=>$process_name,
            'location'=>$location,
            'shiftname'=>$shiftname,
            'designation'=>$designation,
            'department'=>$department,
            'buyer'=>$buyer,
            'rollqcresult'=>$rollqcresult,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdAopBatchFinishQcRequest $request) {
        //$posting_date=date('Y-m-d');
        //$request->request->add(['posting_date' =>$posting_date]);
        $prodaopbatchfinishqc = $this->prodaopbatchfinishqc->create($request->except(['id','batch_no','qc_by_name']));
        if($prodaopbatchfinishqc){
            return response()->json(array('success' => true,'id' =>  $prodaopbatchfinishqc->id,'message' => 'Save Successfully'),200);
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
        $rows=$this->prodaopbatchfinishqc
        ->join('prod_aop_batches',function($join){
            $join->on('prod_aop_batches.id','=','prod_batch_finish_qcs.prod_aop_batch_id');
        })
        
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
        
        
        ->leftJoin('employee_h_rs',function($join){
            $join->on('employee_h_rs.id','=','prod_batch_finish_qcs.qc_by_id');
        })
        ->where([['prod_batch_finish_qcs.id','=',$id]])
        ->get([
            'prod_batch_finish_qcs.*',
            'prod_aop_batches.batch_no',
            'so_aops.company_id',
            'so_aops.buyer_id as customer_id',
            'prod_aop_batches.batch_color_id',
            'prod_aop_batches.design_no',
            'prod_aop_batches.batch_date',
            'prod_aop_batches.batch_for',
            'prod_aop_batches.paste_wgt',
            'prod_aop_batches.fabric_wgt',
            'companies.code as company_code',
            'batch_colors.name as batch_color_name',
            'employee_h_rs.name as qc_by_name',
        ])
        ->map(function($rows){
            $rows->batch_date=date('Y-m-d',strtotime($rows->batch_date));
            //$rows->posting_date=date('Y-m-d',strtotime($rows->posting_date));
            return $rows;
        })
        ->first();
        $row ['fromData'] = $rows;
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
    public function update(ProdAopBatchFinishQcRequest $request, $id) {
        //$posting_date=date('Y-m-d');

        //$request->request->add(['posting_date' =>$posting_date]);
        $prodaopbatchfinishqc = $this->prodaopbatchfinishqc->update($id,$request->except(['id','prod_aop_batch_id','batch_no','qc_by_name']));

        if($prodaopbatchfinishqc){
            return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->prodaopbatchfinishqc->delete($id)){
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
        ->when(request('batch_for'), function ($q)   {
           return $q->whereDate('prod_aop_batches.batch_for', '=', request('batch_for',0));
        })
        ->when(request('batch_no'), function ($q)   {
           return $q->whereDate('prod_aop_batches.batch_no', '=', request('batch_no',0));
        })
        ->when(request('company_id'), function ($q)   {
           return $q->whereDate('so_aops.company_id', '=', request('company_id',0) );
        })
        ->orderBy('prod_aop_batches.id','desc')
        ->get([
            'prod_aop_batches.*',
            'so_aops.buyer_id as customer_id',
            'so_aops.company_id',
            'companies.code as company_code',
            'buyers.name as customer_name',
            'batch_colors.name as batch_color_name',
        ])
        ->map(function($rows) use($batchfor){
            $rows->batch_for_name=$rows->batch_for?$batchfor[$rows->batch_for]:'';
            $rows->batch_date=date('Y-m-d',strtotime($rows->batch_date));
            return $rows;
        });
        echo json_encode($rows);

    }

    public function getEmployeeHr(){
      $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
      $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');

      $employeehr=$this->employeehr
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
        'employee_h_rs.*'
      ])
      ->map(function($employeehr) use($company,$designation,$department){
        $employeehr->employee_name=$employeehr->name;
        $employeehr->company_id=$company[$employeehr->company_id];
        $employeehr->designation_id=isset($designation[$employeehr->designation_id])?$designation[$employeehr->designation_id]:'';
        $employeehr->department_id=isset($department[$employeehr->department_id])?$department[$employeehr->department_id]:'';
        return $employeehr;
      });

      echo json_encode($employeehr);
    }
   
    public function getList(){
        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');

        $rows=$this->prodaopbatchfinishqc
        ->join('prod_aop_batches',function($join){
            $join->on('prod_aop_batches.id','=','prod_batch_finish_qcs.prod_aop_batch_id');
        })
        
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
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_batch_finish_qcs.machine_id');
        })
        
        ->leftJoin('employee_h_rs',function($join){
            $join->on('employee_h_rs.id','=','prod_batch_finish_qcs.qc_by_id');
        })
        ->orderBy('prod_batch_finish_qcs.id','desc')
        ->when(request('from_batch_date'), function ($q) {
        return $q->where('prod_aop_batches.batch_date', '>=', request('from_batch_date', 0));
        })
        ->when(request('to_batch_date'), function ($q) {
        return $q->where('prod_aop_batches.batch_date', '<=', request('to_batch_date', 0));
        })
        ->when(request('from_load_posting_date'), function ($q) {
        return $q->where('prod_batch_finish_qcs.posting_date', '>=', request('from_load_posting_date', 0));
        })
        ->when(request('to_load_posting_date'), function ($q) {
        return $q->where('prod_batch_finish_qcs.posting_date', '<=', request('to_load_posting_date', 0));
        })
        
        ->get([
            'prod_batch_finish_qcs.*',
            'prod_aop_batches.batch_no',
            'prod_aop_batches.batch_date',
            'prod_aop_batches.batch_for',
            'prod_aop_batches.paste_wgt',
            'prod_aop_batches.fabric_wgt',
            'companies.code as company_code',
            'batch_colors.name as batch_color_name',
            'asset_quantity_costs.custom_no as machine_no',
            'employee_h_rs.name as qc_by_name',
        ])
        ->map(function($rows) use($batchfor,$shiftname){
            $rows->batch_for=$rows->batch_for?$batchfor[$rows->batch_for]:'';
            $rows->shiftname=$rows->shift_id?$shiftname[$rows->shift_id]:'';
            $rows->batch_date=date('Y-m-d',strtotime($rows->batch_date));
            $rows->posting_date=date('Y-m-d',strtotime($rows->posting_date));
            return $rows;
        });
        echo json_encode($rows);
    }

    public function exportCsvdd(){
            $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=theincircle_csv.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
            );

             $uoms = $this->uom
            ->where([['uoms.row_status','=',1]])
            ->get(['uoms.name','uoms.code']);

            $columns = array(
            'Name', 
            'Code', 
            );
            $skill=NULL;
            $callback = function() use ($uoms, $columns, $skill)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach($uoms as $uom) {
                    fputcsv($file, array(
                    $uom->name,
                    $uom->code,
                    ));
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
    }

    public function exportCsv(){
        $headers = array(
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=rollcsv.csv",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
        );

        $columns = array(
        'Roll No', 
        'Batch Color', 
        'Batch Qty', 
        'QC Pass Qty', 
        'Reject Qty', 
        'Grade', 
        'GSM', 
        'Dia',
        'prod_aop_batch_roll_id',
        );
        $skill=NULL;

        $prodaopbatchfinishqc=$this->prodaopbatchfinishqc->find(request('id',0));
        $prodaopbatch=$this->prodaopbatch->find($prodaopbatchfinishqc->prod_aop_batch_id);

        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'--','');


        $autoyarn=$this->autoyarn
        ->join('autoyarnratios', function($join)  {
        $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
        })
        ->join('constructions', function($join)  {
        $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->join('compositions',function($join){
        $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->when(request('construction_name'), function ($q) {
        return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
        })
        ->when(request('composition_name'), function ($q) {
        return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
        })
        ->orderBy('autoyarns.id','desc')
        ->get([
        'autoyarns.*',
        'constructions.name',
        'compositions.name as composition_name',
        'autoyarnratios.ratio'
        ]);

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($autoyarn as $row){
        $fabricDescriptionArr[$row->id]=$row->name;
        $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
        $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }


        if($prodaopbatch->batch_for==1){
            $prodknitqc=$this->prodaopbatch
            ->selectRaw('
            prod_aop_batch_rolls.id,
            so_aop_fabric_isus.issue_no,
            so_aop_fabric_isu_items.id as so_aop_fabric_isu_item_id,
            so_aop_fabric_rcv_rols.id as so_aop_fabric_rcv_rol_id,
            prod_knit_item_rolls.id as prod_knit_item_roll_id,
            prod_knit_item_rolls.custom_no,
            so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id,
            so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id,
            prod_batch_finish_qc_rolls.qty as rcv_qty,
            prod_batch_finish_qc_rolls.gsm_weight as dyeing_gsm_weight,
            prod_batch_finish_qc_rolls.dia_width as dyeing_dia_width,
            fabriccolors.name as fabric_color,
            inv_grey_fab_items.autoyarn_id,
            inv_grey_fab_items.gmtspart_id,
            inv_grey_fab_items.fabric_look_id,
            inv_grey_fab_items.fabric_shape_id,
            inv_grey_fab_items.gsm_weight as knited_gsm_weight,
            inv_grey_fab_items.dia as knited_dia_width,
            inv_grey_fab_items.measurment as measurement,
            inv_grey_fab_items.roll_length,
            inv_grey_fab_items.stitch_length,
            inv_grey_fab_items.shrink_per,
            inv_grey_fab_items.colorrange_id,
            colorranges.name as colorrange_name,
            inv_grey_fab_items.color_id,
            colors.name as knit_fabric_color,
            inv_grey_fab_items.supplier_id,

            inv_grey_fab_rcv_items.inv_grey_fab_item_id,
            inv_grey_fab_rcv_items.store_id,
            prod_knit_dlv_rolls.id as prod_knit_dlv_roll_id, 
            prod_knits.prod_no,
            prod_knit_items.id as prod_knit_item_id,
            suppliers.name as supplier_name,
            asset_quantity_costs.custom_no as machine_no,
            asset_technical_features.dia_width as machine_dia,
            asset_technical_features.gauge as machine_gg,
            sales_orders.sale_order_no,
            styles.style_ref,
            buyers. name as buyer_name,
            inv_isus.issue_no as kint_issue_no,
            prodaopbatchfinishqcrolls.id as prod_batch_finish_qc_roll_id
            
            ')
            ->join('prod_aop_batch_rolls',function($join){
            $join->on('prod_aop_batches.id', '=', 'prod_aop_batch_rolls.prod_aop_batch_id');
            })
            ->join('so_aop_fabric_isu_items',function($join){
            $join->on('so_aop_fabric_isu_items.id', '=', 'prod_aop_batch_rolls.so_aop_fabric_isu_item_id');
            })
            ->join('so_aop_fabric_isus',function($join){
            $join->on('so_aop_fabric_isus.id', '=', 'so_aop_fabric_isu_items.so_aop_fabric_isu_id');
            })
            ->join('so_aop_fabric_rcv_rols',function($join){
            $join->on('so_aop_fabric_rcv_rols.id', '=', 'so_aop_fabric_isu_items.so_aop_fabric_rcv_rol_id');
            })
            ->join('so_aop_fabric_rcv_items',function($join){
            $join->on('so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id', '=', 'so_aop_fabric_rcv_items.id');
            })
            ->join('prod_finish_dlv_rolls',function($join){
            $join->on('so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id', '=', 'prod_finish_dlv_rolls.id');
            })
            ->join('prod_batch_finish_qc_rolls',function($join){
            $join->on('prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id', '=', 'prod_batch_finish_qc_rolls.id');
            })
            ->join('prod_batch_rolls',function($join){
            $join->on('prod_batch_finish_qc_rolls.prod_batch_roll_id', '=', 'prod_batch_rolls.id');
            })
            ->join('prod_batches',function($join){
            $join->on('prod_batch_rolls.prod_batch_id', '=', 'prod_batches.id');
            })
            ->join('colors as fabriccolors',function($join){
            $join->on('fabriccolors.id','=','prod_batches.batch_color_id');
            })
            ->join('so_aop_refs',function($join){
            $join->on('so_aop_refs.id', '=', 'so_aop_fabric_rcv_items.so_aop_ref_id');
            })
            ->join('so_aops',function($join){
            $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
            })
            ->leftJoin('so_aop_pos',function($join){
            $join->on('so_aop_pos.so_aop_id','=','so_aops.id');
            })
            ->join('so_aop_po_items',function($join){
            $join->on('so_aop_po_items.so_aop_ref_id', '=', 'so_aop_refs.id');
            })
            ->join('po_aop_service_item_qties',function($join){
            $join->on('po_aop_service_item_qties.id','=','so_aop_po_items.po_aop_service_item_qty_id');
            })
            ->join('po_aop_service_items',function($join){
            $join->on('po_aop_service_items.id','=','po_aop_service_item_qties.po_aop_service_item_id')
            ->whereNull('po_aop_service_items.deleted_at');
            })
            // ->join('budget_fabric_prod_cons',function($join){
            // $join->on('budget_fabric_prod_cons.id','=','po_aop_service_item_qties.budget_fabric_prod_con_id');
            // })
            ->join('sales_orders',function($join){
            // $join->on('sales_orders.id','=','budget_fabric_prod_cons.sales_order_id');
            $join->on('sales_orders.id','=','po_aop_service_item_qties.sales_order_id');
            })
            ->join('jobs',function($join){
            $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->join('styles',function($join){
            $join->on('styles.id','=','jobs.style_id');
            })
            ->join('budget_fabric_prods',function($join){
            $join->on('budget_fabric_prods.id','=','po_aop_service_items.budget_fabric_prod_id');
            })
            ->join('budget_fabrics',function($join){
            $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->join('style_fabrications',function($join){
            $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->join('autoyarns',function($join){
            $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->join('constructions', function($join)  {
            $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->join('buyers',function($join){
            $join->on('buyers.id','=','styles.buyer_id');
            })
            ->join('so_dyeing_fabric_rcv_rols',function($join){
            $join->on('prod_batch_rolls.so_dyeing_fabric_rcv_rol_id', '=', 'so_dyeing_fabric_rcv_rols.id');
            })
            ->join('inv_grey_fab_isu_items',function($join){
            $join->on('inv_grey_fab_isu_items.id', '=', 'so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id');
            })
            ->join('inv_isus',function($join){
            $join->on('inv_isus.id', '=', 'inv_grey_fab_isu_items.inv_isu_id');
            })
            ->join('inv_grey_fab_items',function($join){
            $join->on('inv_grey_fab_items.id', '=', 'inv_grey_fab_isu_items.inv_grey_fab_item_id');
            })
            ->join('inv_grey_fab_rcv_items',function($join){
            $join->on('inv_grey_fab_rcv_items.id', '=', 'inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id');
            })
            ->join('inv_grey_fab_rcvs',function($join){
            $join->on('inv_grey_fab_rcvs.id', '=', 'inv_grey_fab_rcv_items.inv_grey_fab_rcv_id');
            })
            ->join('inv_rcvs',function($join){
            $join->on('inv_rcvs.id', '=', 'inv_grey_fab_rcvs.inv_rcv_id');
            })
            ->join('prod_knit_dlvs',function($join){
            $join->on('prod_knit_dlvs.id', '=', 'inv_grey_fab_rcvs.prod_knit_dlv_id');
            })
            ->join('prod_knit_dlv_rolls',function($join){
            $join->on('prod_knit_dlvs.id', '=', 'prod_knit_dlv_rolls.prod_knit_dlv_id');
            $join->on('inv_grey_fab_rcv_items.prod_knit_dlv_roll_id', '=', 'prod_knit_dlv_rolls.id');
            })
            ->join('prod_knit_qcs',function($join){
            $join->on('prod_knit_qcs.id', '=', 'prod_knit_dlv_rolls.prod_knit_qc_id');
            })
            ->join('prod_knit_rcv_by_qcs',function($join){
            $join->on('prod_knit_rcv_by_qcs.id', '=', 'prod_knit_qcs.prod_knit_rcv_by_qc_id');
            })
            ->join('prod_knit_item_rolls',function($join){
            $join->on('prod_knit_item_rolls.id', '=', 'prod_knit_rcv_by_qcs.prod_knit_item_roll_id');
            })
            ->join('prod_knit_items',function($join){
            $join->on('prod_knit_items.id', '=', 'prod_knit_item_rolls.prod_knit_item_id');
            })
            ->join ('prod_knits',function($join){
            $join->on('prod_knits.id', '=', 'prod_knit_items.prod_knit_id');
            })
            ->join ('suppliers',function($join){
            $join->on('suppliers.id', '=', 'inv_grey_fab_items.supplier_id');
            })
            ->leftJoin ('colorranges',function($join){
            $join->on('colorranges.id', '=', 'inv_grey_fab_items.colorrange_id');
            })
            ->leftJoin('colors',function($join){
            $join->on('colors.id','=','prod_knit_item_rolls.fabric_color');
            })
            ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_knit_items.asset_quantity_cost_id');
            })
            ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_quantity_costs.asset_acquisition_id','=','asset_technical_features.asset_acquisition_id');
            })
            ->leftJoin(\DB::raw("(
            select
            prod_batch_finish_qc_rolls.id,
            prod_batch_finish_qc_rolls.prod_aop_batch_roll_id
            from prod_batch_finish_qc_rolls
            join prod_batch_finish_qcs on prod_batch_finish_qcs.id=prod_batch_finish_qc_rolls.prod_batch_finish_qc_id
            where 
            prod_batch_finish_qc_rolls.deleted_at is null 
            and prod_batch_finish_qcs.prod_aop_batch_id is not null
            and prod_batch_finish_qcs.prod_aop_batch_id='".$prodaopbatch->id."'
            ) prodaopbatchfinishqcrolls"),"prodaopbatchfinishqcrolls.prod_aop_batch_roll_id","=","prod_aop_batch_rolls.id")
            ->where([['prod_aop_batches.id','=',$prodaopbatch->id]])
            ->orderBy('prod_aop_batch_rolls.id','desc')
            //->toSql();
            ///dd($prodknitqc); 
            ->get()
            ->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype){
                $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
                $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
                $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
                $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
                $prodknitqc->rcv_qty=number_format($prodknitqc->rcv_qty,4);
                return $prodknitqc;
            })
            ->filter(function($prodknitqc){
                if(!$prodknitqc->prod_batch_finish_qc_roll_id){
                return   $prodknitqc;
                }
            })
            ->values();
            $callback = function() use ($prodknitqc, $columns, $skill)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach($prodknitqc as $roll) {
                fputcsv($file, array(
                $roll->prod_knit_item_roll_id,
                $roll->fabric_color,
                $roll->rcv_qty,
                '',
                '',
                '',
                '',
                '',
                $roll->id,
                ));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if($prodaopbatch->batch_for==2){
            $prodknitqc=$this->prodaopbatch
            ->selectRaw('
            prod_aop_batch_rolls.id,
            so_aop_fabric_isus.issue_no,
            so_aop_fabric_isu_items.id as so_aop_fabric_isu_item_id,
            so_aop_fabric_rcv_rols.id as so_aop_fabric_rcv_rol_id,
            so_aop_fabric_rcv_rols.id as prod_knit_item_roll_id,
            so_aop_fabric_rcv_rols.custom_no,
            so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id,
            so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id,
            so_aop_fabric_rcv_rols.qty as rcv_qty,

            so_aop_items.autoyarn_id,
            so_aop_items.gmtspart_id,
            so_aop_items.fabric_look_id,
            so_aop_items.fabric_shape_id,
            so_aop_items.gsm_weight as dyeing_gsm_weight,
            so_aop_items.colorrange_id,
            so_aop_items.fabric_color_id,
            so_aop_items.gmt_sale_order_no as sale_order_no,
            so_aop_items.gmt_style_ref as style_ref,
            buyers.name as buyer_name,
            fabriccolors.name as fabric_color,
            prodaopbatchfinishqcrolls.id as prod_batch_finish_qc_roll_id
            ')
            ->join('prod_aop_batch_rolls',function($join){
            $join->on('prod_aop_batches.id', '=', 'prod_aop_batch_rolls.prod_aop_batch_id');
            })
            ->join('so_aop_fabric_isu_items',function($join){
            $join->on('so_aop_fabric_isu_items.id', '=', 'prod_aop_batch_rolls.so_aop_fabric_isu_item_id');
            })
            ->join('so_aop_fabric_isus',function($join){
            $join->on('so_aop_fabric_isus.id', '=', 'so_aop_fabric_isu_items.so_aop_fabric_isu_id');
            })
            ->join('so_aop_fabric_rcv_rols',function($join){
            $join->on('so_aop_fabric_rcv_rols.id', '=', 'so_aop_fabric_isu_items.so_aop_fabric_rcv_rol_id');
            })
            ->join('so_aop_fabric_rcv_items',function($join){
            $join->on('so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id', '=', 'so_aop_fabric_rcv_items.id');
            })
            ->join('so_aop_refs',function($join){
            $join->on('so_aop_refs.id', '=', 'so_aop_fabric_rcv_items.so_aop_ref_id');
            })
            ->join('so_aop_items',function($join){
            $join->on('so_aop_refs.id', '=', 'so_aop_items.so_aop_ref_id');
            })
            ->join('so_aops',function($join){
            $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
            })
            ->join('autoyarns',function($join){
            $join->on('autoyarns.id','=','so_aop_items.autoyarn_id');
            })
            ->join('constructions', function($join)  {
            $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin ('buyers',function($join){
            $join->on('buyers.id', '=', 'so_aop_items.gmt_buyer');
            })
            ->leftJoin('colors as fabriccolors',function($join){
            $join->on('fabriccolors.id','=','so_aop_items.fabric_color_id');
            })
            ->leftJoin(\DB::raw("(
            select
            prod_batch_finish_qc_rolls.id,
            prod_batch_finish_qc_rolls.prod_aop_batch_roll_id
            from prod_batch_finish_qc_rolls
            join prod_batch_finish_qcs on prod_batch_finish_qcs.id=prod_batch_finish_qc_rolls.prod_batch_finish_qc_id
            where 
            prod_batch_finish_qc_rolls.deleted_at is null 
            and prod_batch_finish_qcs.prod_aop_batch_id is not null
            and prod_batch_finish_qcs.prod_aop_batch_id='".$prodaopbatch->id."'
            ) prodaopbatchfinishqcrolls"),"prodaopbatchfinishqcrolls.prod_aop_batch_roll_id","=","prod_aop_batch_rolls.id")
            ->where([['prod_aop_batches.id','=',$prodaopbatch->id]])
            ->orderBy('prod_aop_batch_rolls.id','desc')
            ->get()
            ->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$dyetype){
                $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
                $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
                $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
                $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
                $prodknitqc->rcv_qty=number_format($prodknitqc->rcv_qty,4);
                return $prodknitqc;
            })
            ->filter(function($prodknitqc){
                if(!$prodknitqc->prod_batch_finish_qc_roll_id){
                return   $prodknitqc;
                }
            })
            ->values();

            $callback = function() use ($prodknitqc, $columns, $skill)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach($prodknitqc as $roll) {
                fputcsv($file, array(
                $roll->prod_knit_item_roll_id,
                $roll->fabric_color,
                $roll->rcv_qty,
                '',
                '',
                '',
                '',
                '',
                $roll->id,
                ));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
    }
}