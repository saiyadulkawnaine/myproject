<?php

namespace App\Http\Controllers\Inventory\DyeChem;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuRqRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\DyeChem\InvDyeChemIsuRqRequest;

class InvDyeChemIsuRqController extends Controller {

    private $invdyechemisurq;
    private $company;
    private $buyer;
    private $location;
    private $itemaccount;
    private $autoyarn;
    private $colorrange;
    private $prodbatch;
    private $color;
    private $designation;
    private $department;
    private $employeehr;

    public function __construct(
        InvDyeChemIsuRqRepository $invdyechemisurq, 
        CompanyRepository $company, 
        BuyerRepository $buyer, 
        LocationRepository $location,
        ItemAccountRepository $itemaccount,
        AutoyarnRepository $autoyarn,
        ColorrangeRepository $colorrange,
        ProdBatchRepository $prodbatch,
        ColorRepository $color,
        EmployeeHRRepository $employeehr,
        DesignationRepository $designation,
        DepartmentRepository $department
    ) {
        $this->invdyechemisurq = $invdyechemisurq;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->location = $location;
        $this->itemaccount = $itemaccount;
        $this->autoyarn = $autoyarn;
        $this->colorrange = $colorrange;
        $this->prodbatch = $prodbatch;
        $this->color = $color;
        $this->employeehr = $employeehr;
        $this->designation = $designation;
        $this->department = $department;
        $this->middleware('auth');
        $this->middleware('permission:view.invdyechemisurqs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invdyechemisurqs', ['only' => ['store']]);
        $this->middleware('permission:edit.invdyechemisurqs',   ['only' => ['update']]);
        $this->middleware('permission:delete.invdyechemisurqs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       $rows = $this->invdyechemisurq
       ->join('prod_batches',function($join){
        $join->on('prod_batches.id','=','inv_dye_chem_isu_rqs.prod_batch_id');
       })
       ->join('companies',function($join){
        $join->on('companies.id','=','prod_batches.company_id');
       })
       ->leftJoin('locations',function($join){
        $join->on('locations.id','=','prod_batches.location_id');
       })
       ->join('colors',function($join){
        $join->on('colors.id','=','prod_batches.fabric_color_id');
       })
       ->join('colors batch_colors',function($join){
        $join->on('batch_colors.id','=','prod_batches.batch_color_id');
        })
       ->join('colorranges',function($join){
        $join->on('colorranges.id','=','prod_batches.colorrange_id');
       })
       ->where([['inv_dye_chem_isu_rqs.menu_id','=',208]])
       ->orderBy('inv_dye_chem_isu_rqs.id','desc')
       
       ->get([
        'inv_dye_chem_isu_rqs.*',
        'colors.name as fabric_color',
        'batch_colors.name as batch_color',
        'colorranges.name as colorrange_name',
        'prod_batches.colorrange_id',
        'prod_batches.batch_no',
        'prod_batches.lap_dip_no',
        'prod_batches.batch_wgt',
        'companies.code as company_id',
        'locations.name as location_id',
       ])
       ->take(100)
       ->map(function($rows){
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
      $location = array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
      $buyer = array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
      $dyeingsubprocess=array_prepend(config('bprs.dyeingsubprocess'), '-Select-','');
      $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
      $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
      return Template::loadView('Inventory.DyeChem.InvDyeChemIsuRq',[
        'company'=>$company,
        'location'=>$location, 
        'buyer'=>$buyer, 
        'colorrange'=>$colorrange,
        'dyeingsubprocess'=>$dyeingsubprocess,
        'designation'=>$designation,
        'department'=>$department,
      ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvDyeChemIsuRqRequest $request) {
      $batch=$this->prodbatch->find($request->prod_batch_id);
      $max=$this->invdyechemisurq
      ->join('prod_batches',function($join){
      $join->on('prod_batches.id','=','inv_dye_chem_isu_rqs.prod_batch_id');
      })
      ->where([['prod_batches.company_id','=',$batch->company_id]])
      ->max('inv_dye_chem_isu_rqs.rq_no');
      $rq_no=$max+1;
      /*$color_name=strtoupper($request->fabric_color);
      $color = $this->color->firstOrCreate(['name' =>$color_name],['code' => '']);*/
     /* \DB::beginTransaction();
      try
      {*/
      /*$prodbatch=$this->prodbatch->create([
        'fabric_color_id'=>$color->id,
        'colorrange_id'=>$request->colorrange_id,
        'company_id'=>$request->company_id,
        'location_id'=>$request->location_id,
        'batch_date'=>$request->rq_date,
        'batch_no'=>$request->batch_no,
        'lap_dip_no'=>$request->lap_dip_no,
        'batch_wgt'=>$request->batch_wgt,
        'remarks'=>$request->remarks,
      ]);*/

      $invdyechemisurq=$this->invdyechemisurq->create([
        'rq_no'=>$rq_no,
        'menu_id'=>208,
        
        'rq_basis_id'=>1,
        'prod_batch_id'=>$request->prod_batch_id,
        /*'company_id'=>$request->company_id,
        'location_id'=>$request->location_id,

        'fabrication_id'=>$request->fabrication_id,
        'fabric_desc'=>$request->fabric_desc,
        'buyer_id'=>$request->buyer_id,*/
        'rq_date'=>$request->rq_date,
        'liqure_ratio'=>$request->liqure_ratio,
        'liqure_wgt'=>$request->liqure_wgt,
        'remarks'=>$request->remarks,
        'operator_id'=>$request->operator_id,
        'incharge_id'=>$request->incharge_id,
      ]);
      /*}
      catch(EXCEPTION $e)
      {
          \DB::rollback();
          throw $e;
      }
      \DB::commit();*/

     
      if($invdyechemisurq){
        return response()->json(array('success' =>true ,'id'=>$invdyechemisurq->id, 'rq_no'=>$rq_no,'message'=>'Saved Successfully'),200);
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
        $rows = $this->invdyechemisurq
       ->join('prod_batches',function($join){
        $join->on('prod_batches.id','=','inv_dye_chem_isu_rqs.prod_batch_id');
       })
       ->join('companies',function($join){
        $join->on('companies.id','=','prod_batches.company_id');
       })
       ->leftJoin('locations',function($join){
        $join->on('locations.id','=','prod_batches.location_id');
       })
       ->join('colors',function($join){
        $join->on('colors.id','=','prod_batches.fabric_color_id');
       })
       ->join('colors batch_colors',function($join){
        $join->on('batch_colors.id','=','prod_batches.batch_color_id');
        })
       ->join('colorranges',function($join){
        $join->on('colorranges.id','=','prod_batches.colorrange_id');
       })
       ->leftJoin('employee_h_rs',function($join){
         $join->on('inv_dye_chem_isu_rqs.operator_id','=','employee_h_rs.id');
       })
       ->leftJoin('employee_h_rs as employee_incharge',function($join){
         $join->on('inv_dye_chem_isu_rqs.incharge_id','=','employee_incharge.id');
       })
       ->where([['inv_dye_chem_isu_rqs.id','=',$id]])
       ->orderBy('inv_dye_chem_isu_rqs.id','desc')
       ->get([
        'inv_dye_chem_isu_rqs.*',
        'colors.name as fabric_color',
        'batch_colors.name as batch_color_name',
        'colorranges.name as colorrange_name',
        'prod_batches.colorrange_id',
        'prod_batches.batch_no',
        'prod_batches.company_id',
        'prod_batches.location_id',
        'prod_batches.lap_dip_no',
        'prod_batches.batch_wgt',
        'employee_h_rs.name as operator_name',
        'employee_incharge.name as incharge_name',
       ])
       ->map(function($rows){
        return $rows;
       })->first();
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
    public function update(InvDyeChemIsuRqRequest $request, $id) {
      $isurq=$this->invdyechemisurq->find($id);
      /*$color_name=strtoupper($request->fabric_color);
      $color = $this->color->firstOrCreate(['name' =>$color_name],['code' => '']);
      \DB::beginTransaction();
      try
      {
      $prodbatch=$this->prodbatch->update($isurq->prod_batch_id,[
        'fabric_color_id'=>$color->id,
        'colorrange_id'=>$request->colorrange_id,
        'location_id'=>$request->location_id,
        'batch_date'=>$request->rq_date,
        'batch_no'=>$request->batch_no,
        'lap_dip_no'=>$request->lap_dip_no,
        'batch_wgt'=>$request->batch_wgt,
        'remarks'=>$request->remarks,
      ]);*/
      $invdyechemisurq=$this->invdyechemisurq->update($id,[
        /*'location_id'=>$request->location_id,
        'fabrication_id'=>$request->fabrication_id,
        'fabric_desc'=>$request->fabric_desc,
        'buyer_id'=>$request->buyer_id,*/
        'rq_date'=>$request->rq_date,
        'liqure_ratio'=>$request->liqure_ratio,
        'liqure_wgt'=>$request->liqure_wgt,
        'remarks'=>$request->remarks,
        'operator_id'=>$request->operator_id,
        'incharge_id'=>$request->incharge_id,
      ]);
      /*}
      catch(EXCEPTION $e)
      {
          \DB::rollback();
          throw $e;
      }
      \DB::commit();*/

     
      if($invdyechemisurq){
        return response()->json(array('success' =>true ,'id'=>$id, 'message'=>'Saved Successfully'),200);
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
        return response()->json(array('success'=>false,'message'=>'Deleted not Successfully'),200);
        if($this->invdyechemisurq->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }

    public function getBatch (){
        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $rows=$this->prodbatch
        ->join('companies',function($join){
        $join->on('companies.id','=','prod_batches.company_id');
        })
        ->join('colors',function($join){
        $join->on('colors.id','=','prod_batches.fabric_color_id');
        })
        ->join('colors batch_colors',function($join){
        $join->on('batch_colors.id','=','prod_batches.batch_color_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
        $join->on('asset_quantity_costs.id','=','prod_batches.machine_id');
        })
        ->leftJoin('colorranges',function($join){
        $join->on('colorranges.id','=','prod_batches.colorrange_id');
        })
        ->when(request('batch_date_from'), function ($q) {
        return $q->where('prod_batches.batch_date', '>=', request('batch_date_from', 0));
        })
        ->when(request('batch_date_to'), function ($q) {
        return $q->where('prod_batches.batch_date', '<=', request('batch_date_to', 0));
        })
        ->orderBy('prod_batches.id','desc')
        ->get([
        'prod_batches.*',
        'companies.code as company_code',
        'colors.name as color_name',
        'batch_colors.name as batch_color_name',
        'asset_quantity_costs.custom_no as machine_no',
        'colorranges.name as color_range_name',
        ])
        ->map(function($rows) use($batchfor){
        $rows->batch_for=$rows->batch_for?$batchfor[$rows->batch_for]:'';
        $rows->batch_date=date('Y-m-d',strtotime($rows->batch_date));
        return $rows;
        });
        echo json_encode($rows);
    }

    public function getFabric()
    {

      $autoyarn=$this->autoyarn->join('autoyarnratios', function($join)  {
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
      $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
      }

      $fab=array();
      $fabs=array();
      foreach($autoyarn as $row){
      $fab[$row->id]['id']=$row->id;
      $fab[$row->id]['name']=$row->name;
      $fab[$row->id]['composition_name']=$desDropdown[$row->id];
      }
      foreach($fab as $row){
        array_push($fabs,$row);
      }
      echo json_encode($fabs);
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

    public function getRq() {
       $rows = $this->invdyechemisurq
       ->join('prod_batches',function($join){
        $join->on('prod_batches.id','=','inv_dye_chem_isu_rqs.prod_batch_id');
       })
       ->join('companies',function($join){
        $join->on('companies.id','=','prod_batches.company_id');
       })
       /*->join('buyers',function($join){
        $join->on('buyers.id','=','inv_dye_chem_isu_rqs.buyer_id');
       })*/
       ->leftJoin('locations',function($join){
        $join->on('locations.id','=','prod_batches.location_id');
       })
       ->join('colors',function($join){
        $join->on('colors.id','=','prod_batches.fabric_color_id');
       })
       ->join('colorranges',function($join){
        $join->on('colorranges.id','=','prod_batches.colorrange_id');
       })
       ->where([['inv_dye_chem_isu_rqs.menu_id','=',208]])
        ->when(request('from_batch_date'), function ($q) {
        return $q->where('prod_batches.batch_date', '>=', request('from_batch_date', 0));
        })
        ->when(request('to_batch_date'), function ($q) {
        return $q->where('prod_batches.batch_date', '<=', request('to_batch_date', 0));
        })
         ->when(request('from_rq_date'), function ($q) {
        return $q->where('inv_dye_chem_isu_rqs.rq_date', '>=', request('from_rq_date', 0));
        })
        ->when(request('to_rq_date'), function ($q) {
        return $q->where('inv_dye_chem_isu_rqs.rq_date', '<=', request('to_rq_date', 0));
        })
       ->orderBy('inv_dye_chem_isu_rqs.id','desc')
       ->get([
        'inv_dye_chem_isu_rqs.*',
        'colors.name as fabric_color',
        'colorranges.name as colorrange_name',
        'prod_batches.colorrange_id',
        'prod_batches.batch_no',
        'prod_batches.lap_dip_no',
        'prod_batches.batch_wgt',
        'companies.code as company_id',
        'locations.name as location_id',
       ])
       ->map(function($rows){
        return $rows;
       });
      echo json_encode($rows);
    }


    public function getPdf()
    {
      $id=request('id',0);
      $invdyechemisurq=$this->invdyechemisurq->find($id);
      $prodbatch=$this->prodbatch->find($invdyechemisurq->prod_batch_id);
      $dyeingsubprocess=array_prepend(config('bprs.dyeingsubprocess'),'-Select-','');
      $menu=array_prepend(config('bprs.menu'),'-Select-','');

      $rows=$this->invdyechemisurq
      
      ->join('prod_batches',function($join){
        $join->on('prod_batches.id','=','inv_dye_chem_isu_rqs.prod_batch_id');
       })
       ->join('companies',function($join){
        $join->on('companies.id','=','prod_batches.company_id');
       })
       ->leftJoin('asset_quantity_costs',function($join){
        $join->on('asset_quantity_costs.id','=','prod_batches.machine_id');
        })
       ->join('locations',function($join){
        $join->on('locations.id','=','prod_batches.location_id');
       })
       ->join('colors',function($join){
        $join->on('colors.id','=','prod_batches.fabric_color_id');
       })
       ->join('colorranges',function($join){
        $join->on('colorranges.id','=','prod_batches.colorrange_id');
       })
       ->join('colors batch_colors',function($join){
        $join->on('batch_colors.id','=','prod_batches.batch_color_id');
        })
       ->join('users',function($join){
      $join->on('users.id','=','inv_dye_chem_isu_rqs.created_by');
      })
      ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->where([['inv_dye_chem_isu_rqs.id','=',$id]])
      ->get([
        'inv_dye_chem_isu_rqs.*',
        'colors.name as fabric_color',
        'colorranges.name as colorrange_name',
        'prod_batches.colorrange_id',
        'prod_batches.batch_no',
        'prod_batches.lap_dip_no',
        'prod_batches.batch_wgt',
        'prod_batches.machine_id',
        'asset_quantity_costs.custom_no as machine_no',
        'companies.name as company_name',
        'companies.logo as logo',
        'companies.address as company_address',
        'locations.name as location_id',
        'users.name as user_name',
        'batch_colors.name as batch_color',
      'employee_h_rs.contact'
       ])
       ->first();
        $rows->rq_date=date('d-M-Y',strtotime($rows->rq_date));

        $invdyechemisurqitem=$this->invdyechemisurq
        ->join('inv_dye_chem_isu_rq_items',function($join){
          $join->on('inv_dye_chem_isu_rq_items.inv_dye_chem_isu_rq_id','=','inv_dye_chem_isu_rqs.id');
        })
        ->join('item_accounts',function($join){
          $join->on('inv_dye_chem_isu_rq_items.item_account_id','=','item_accounts.id');
        })
        ->join('itemclasses', function($join){
          $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
        })
        ->join('itemcategories', function($join){
          $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
        })
        ->leftJoin('uoms', function($join){
          $join->on('uoms.id', '=', 'item_accounts.uom_id');
        })
        ->leftJoin(\DB::raw("(SELECT 
          inv_dye_chem_transactions.item_account_id,
          sum(inv_dye_chem_transactions.store_qty) as qty 
          FROM inv_dye_chem_transactions 
          where  inv_dye_chem_transactions.deleted_at is null
          group by inv_dye_chem_transactions.item_account_id
          ) stock"), "stock.item_account_id", "=", "item_accounts.id")
        ->leftJoin(\DB::raw("(SELECT 
          inv_dye_chem_transactions.item_account_id,
          sum(inv_dye_chem_transactions.store_amount) as receive_amount 
          FROM inv_dye_chem_transactions 
          where trans_type_id=1 and 
          inv_dye_chem_transactions.deleted_at is null
          group by inv_dye_chem_transactions.item_account_id
          ) receives"), "receives.item_account_id", "=", "item_accounts.id")

        ->leftJoin(\DB::raw("(SELECT 
          inv_dye_chem_transactions.item_account_id,
          sum(inv_dye_chem_transactions.store_amount) as issue_amount 
          FROM inv_dye_chem_transactions 
          where trans_type_id=2 and 
          inv_dye_chem_transactions.deleted_at is null
          group by inv_dye_chem_transactions.item_account_id
          ) issues"), "issues.item_account_id", "=", "item_accounts.id")
        ->where([['inv_dye_chem_isu_rqs.id','=',$id]])
        ->orderBy('inv_dye_chem_isu_rq_items.id')
        ->orderBy('inv_dye_chem_isu_rq_items.sort_id')
        ->get([
        'inv_dye_chem_isu_rq_items.*',
        'itemcategories.name as category_name',
        'itemclasses.name as class_name',
        'item_accounts.sub_class_name',
        'item_accounts.item_description',
        'item_accounts.specification',
        'uoms.code as uom_name',
        'stock.qty as stock_qty',
        'receives.receive_amount',
        'issues.issue_amount',
        ]) 
        ->map(function($invdyechemisurqitem) use ($dyeingsubprocess){
          $invdyechemisurqitem->sub_process_name=$dyeingsubprocess[$invdyechemisurqitem->sub_process_id];
          $invdyechemisurqitem->ratio='';
          if($invdyechemisurqitem->per_on_batch_wgt){
          $invdyechemisurqitem->ratio=$invdyechemisurqitem->per_on_batch_wgt.' % on Batch Wgt';
          }
          else if ($invdyechemisurqitem->gram_per_ltr_liqure){
          $invdyechemisurqitem->ratio=$invdyechemisurqitem->gram_per_ltr_liqure.' Gram/L. Liqure';
          }
          $invdyechemisurqitem->stock_amount=$invdyechemisurqitem->receive_amount-$invdyechemisurqitem->issue_amount;
          $invdyechemisurqitem->stock_rate=0;
          if($invdyechemisurqitem->stock_qty){
          $invdyechemisurqitem->stock_rate=number_format($invdyechemisurqitem->stock_amount/$invdyechemisurqitem->stock_qty,4);
          }
          return $invdyechemisurqitem;
        })
        ->groupBy('sub_process_name');
        if($prodbatch->batch_for==1){
            $prodknitqc=$this->prodbatch
              ->selectRaw('
                  prod_batches.id as prod_batch_id,
                  CASE 
                  WHEN  inhouseprods.sale_order_no IS NULL THEN outhouseprods.sale_order_no 
                  ELSE inhouseprods.sale_order_no
                  END as sale_order_no,
                  CASE 
                  WHEN  inhouseprods.style_ref IS NULL THEN outhouseprods.style_ref 
                  ELSE inhouseprods.style_ref
                  END as style_ref,
  
                  CASE 
                  WHEN  inhouseprods.buyer_name IS NULL THEN outhouseprods.buyer_name 
                  ELSE inhouseprods.buyer_name
                  END as buyer_name,
  
                  CASE 
                  WHEN  inhouseprods.customer_name IS NULL THEN outhouseprods.customer_name 
                  ELSE inhouseprods.customer_name
                  END as customer_name
              ')
              ->join('prod_batch_rolls',function($join){
                  $join->on('prod_batch_rolls.prod_batch_id', '=', 'prod_batches.id');
              })
              ->join('so_dyeing_fabric_rcv_rols',function($join){
                  $join->on('so_dyeing_fabric_rcv_rols.id', '=', 'prod_batch_rolls.so_dyeing_fabric_rcv_rol_id');
              })
              ->join('so_dyeing_fabric_rcv_items',function($join){
                  $join->on('so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id', '=', 'so_dyeing_fabric_rcv_items.id');
              })
              ->join('so_dyeing_refs',function($join){
                  $join->on('so_dyeing_refs.id', '=', 'so_dyeing_fabric_rcv_items.so_dyeing_ref_id');
              })
              ->join('so_dyeings',function($join){
                  $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
              })
              ->join('buyers as customers',function($join){
                  $join->on('customers.id','=','so_dyeings.buyer_id');
              })
              ->leftJoin('so_dyeing_pos',function($join){
                  $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
              })
              ->join('so_dyeing_po_items',function($join){
                  $join->on('so_dyeing_po_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
              })
              ->join('po_dyeing_service_item_qties',function($join){
                  $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
              })
              ->join('po_dyeing_service_items',function($join){
                  $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
              ->whereNull('po_dyeing_service_items.deleted_at');
              })
              ->join('sales_orders',function($join){
                  $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
              })
              ->join('jobs',function($join){
                  $join->on('jobs.id','=','sales_orders.job_id');
              })
              ->join('styles',function($join){
                  $join->on('styles.id','=','jobs.style_id');
              })
              ->join('buyers',function($join){
                  $join->on('buyers.id','=','styles.buyer_id');
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
              ->leftJoin('asset_quantity_costs',function($join){
                  $join->on('asset_quantity_costs.id','=','prod_knit_items.asset_quantity_cost_id');
              })
              ->leftJoin('asset_technical_features',function($join){
                  $join->on('asset_quantity_costs.asset_acquisition_id','=','asset_technical_features.asset_acquisition_id');
              })
              ->leftJoin(\DB::raw("(
                  select 
                  pl_knit_items.id,
                  customer.name as customer_name,
                  companies.id as company_id,
                  
                  CASE 
                  WHEN sales_orders.sale_order_no IS NULL THEN so_knit_items.gmt_sale_order_no 
                  ELSE sales_orders.sale_order_no
                  END as sale_order_no,
                  CASE 
                  WHEN sales_orders.id IS NULL THEN 0
                  ELSE sales_orders.id
                  END as sale_order_id,
                  CASE 
                  WHEN styles.style_ref IS NULL THEN so_knit_items.gmt_style_ref 
                  ELSE styles.style_ref
                  END as style_ref,
                  CASE 
                  WHEN styles.id IS NULL THEN 0 
                  ELSE styles.id
                  END as style_id,
                  CASE 
                  WHEN buyers.name IS NULL THEN outbuyers.name 
                  ELSE buyers.name
                  END as buyer_name,
  
                  CASE 
                  WHEN buyers.id IS NULL THEN outbuyers.id 
                  ELSE buyers.id
                  END as buyer_id
  
                  from pl_knit_items
                  join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
                  join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
                  left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
                  left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
                  left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
                  and po_knit_service_items.deleted_at is null
                  left join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id 
                  left join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
                  left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
                  left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
                  left join so_knits on so_knits.id=so_knit_refs.so_knit_id
                  left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
                  left join jobs on jobs.id=sales_orders.job_id
                  left join styles on styles.id=jobs.style_id
                  left join buyers on buyers.id=styles.buyer_id
                  left join buyers outbuyers on outbuyers.id=so_knit_items.gmt_buyer
                  left join buyers customer on customer.id=so_knits.buyer_id
                  left join companies  on companies.id=customer.company_id
              ) inhouseprods"),"inhouseprods.id","=","prod_knit_items.pl_knit_item_id")
              ->leftJoin(\DB::raw("(
                  select 
                  po_knit_service_item_qties.id,
                  sales_orders.sale_order_no,
                  sales_orders.id as sale_order_id,
                  styles.style_ref,
                  styles.id as style_id,
                  buyers.name as buyer_name,
                  buyers.id as buyer_id,
                  companies.name as customer_name,
                  companies.id as company_id   
                  from 
                  po_knit_service_item_qties
                  join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
                  join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
                  left join colorranges on colorranges.id=po_knit_service_item_qties.colorrange_id
                  join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
                  join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
                  join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
                  
                  left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
                  left join jobs on jobs.id=sales_orders.job_id
                  left join styles on styles.id=jobs.style_id
                  left join buyers on buyers.id=styles.buyer_id
                  left join companies on companies.id=po_knit_services.company_id
                  order by po_knit_service_item_qties.id
              ) outhouseprods"),"outhouseprods.id","=","prod_knit_items.po_knit_service_item_qty_id")
              ->leftJoin('companies',function($join){
                  $join->on('companies.id','=','outhouseprods.company_id');
                  $join->Oron('companies.id','=','inhouseprods.company_id');
              })
              ->where([['prod_batches.id','=',$invdyechemisurq->prod_batch_id]])
              ->orderBy('sales_orders.ship_date','desc')
              ->get()
              ->map(function($prodknitqc) {
                  return $prodknitqc;
              });
        }
        if($prodbatch->batch_for==2){
              $prodknitqc=$this->prodbatch
              ->selectRaw('
                prod_batches.id as prod_batch_id,
                so_dyeing_fabric_rcv_rols.id as so_dyeing_fabric_rcv_rol_id,
                so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id,
                so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id,
                so_dyeing_items.gmt_sale_order_no as sale_order_no,
                so_dyeing_items.gmt_style_ref as style_ref,
                buyers.name as buyer_name,
                customers.name as customer_name
              ')
              ->join('prod_batch_rolls',function($join){
                $join->on('prod_batch_rolls.prod_batch_id', '=', 'prod_batches.id');
              })
              ->join('so_dyeing_fabric_rcv_rols',function($join){
                $join->on('so_dyeing_fabric_rcv_rols.id', '=', 'prod_batch_rolls.so_dyeing_fabric_rcv_rol_id');
              })
              ->join('so_dyeing_fabric_rcv_items',function($join){
                $join->on('so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id', '=', 'so_dyeing_fabric_rcv_items.id');
              })
              ->join('so_dyeing_refs',function($join){
                $join->on('so_dyeing_refs.id', '=', 'so_dyeing_fabric_rcv_items.so_dyeing_ref_id');
              })
              ->join('so_dyeing_items',function($join){
                $join->on('so_dyeing_refs.id', '=', 'so_dyeing_items.so_dyeing_ref_id');
              })
              ->join('so_dyeings',function($join){
                $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
              })
              ->join('buyers as customers',function($join){
                $join->on('customers.id','=','so_dyeings.buyer_id');
              })
              ->leftJoin ('buyers',function($join){
                $join->on('buyers.id', '=', 'so_dyeing_items.gmt_buyer');
              })
              ->where([['prod_batches.id','=',$invdyechemisurq->prod_batch_id]])
              ->orderBy('prod_batches.id','desc')
              ->get()
              ->map(function($prodknitqc){
                return $prodknitqc;
              });
          }
          $ordDtl=[];
          foreach($prodknitqc as $data){
              $ordDtl['sale_order_no'][$data->sale_order_no]=$data->sale_order_no;
              $ordDtl['style_ref'][$data->style_ref]=$data->style_ref;
              $ordDtl['buyer_name'][$data->buyer_name]=$data->buyer_name;
              //$ordDtl['ship_date'][$data->ship_date]=$data->ship_date?date("d-M-Y",strtotime($data->ship_date)):'';
              $ordDtl['customer_name'][$data->customer_name]=$data->customer_name;
        }
        $batch['ordDtl']=$ordDtl;
        $data['master']    =$rows;
        $data['details']   =$invdyechemisurqitem;

      $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetPrintHeader(false);
      $pdf->SetPrintFooter(false);
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->AddPage();
      $pdf->SetY(10);
      //$txt = "Trim Purchase Order";
      //$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
      $image_file ='images/logo/'.$rows->logo;
      $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
      $pdf->SetY(13);
      $pdf->SetFont('helvetica', 'N', 8);
      $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
      //$pdf->Text(115, 12, $rows->company_address);
      //$pdf->Write(0, $rows->company_address, '', 0, 'C', true, 0, false, false, 0);

      $barcodestyle = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );
        $pdf->SetY(5);
        $pdf->SetX(200);
        $challan=str_pad($data['master']->id,10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');

      $pdf->SetY(36);
      $pdf->SetFont('helvetica', 'N', 10);
      $pdf->Write(0, 'Dyes & Chemicals Issue Requisition ', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      $pdf->SetTitle('Dyes & Chemicals Issue Requisition');
      $view= \View::make('Defult.Inventory.DyeChem.DyeChemIsuRqPdf',['data'=>$data,'batch'=>$batch]);
      $html_content=$view->render();
      $pdf->SetY(46);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/DyeChemIsuRqPdf.pdf';
      $pdf->output($filename);
    }
}