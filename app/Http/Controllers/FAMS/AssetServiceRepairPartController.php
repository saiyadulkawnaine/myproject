<?php

namespace App\Http\Controllers\FAMS;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\FAMS\AssetServiceRepairRepository;
use App\Repositories\Contracts\FAMS\AssetServiceRepairPartRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\FAMS\AssetTechnicalFeatureRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\FAMS\AssetBreakdownRepository;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DivisionRepository;
use App\Repositories\Contracts\Util\SectionRepository;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Template;
use App\Http\Requests\FAMS\AssetServiceRepairPartRequest;

class AssetServiceRepairPartController extends Controller {

    private $assetservicerepair;
    private $assetservicerepairpart;
    private $assetbreakdown;
    private $employeehr;
    private $assetquantitycost;
    private $company;
    private $location;
    private $uom;
    private $supplier;
    private $assettechfeature;
    private $itemaccount;
    private $itemclass;
    private $itemcategory;
    private $store;
    private $division;
    private $section;
    private $subsection;
    private $buyer;

    public function __construct(AssetServiceRepairRepository $assetservicerepair,AssetServiceRepairPartRepository $assetservicerepairpart, CompanyRepository $company, LocationRepository $location, UomRepository $uom, SupplierRepository $supplier, AssetTechnicalFeatureRepository $assettechfeature, ItemAccountRepository $itemaccount, ItemclassRepository $itemclass, ItemcategoryRepository $itemcategory, StoreRepository $store,AssetQuantityCostRepository $assetquantitycost,EmployeeHRRepository $employeehr,DesignationRepository $designation,DepartmentRepository $department,DivisionRepository $division,
    SectionRepository $section, SubsectionRepository $subsection,
    BuyerRepository $buyer, AssetBreakdownRepository $assetbreakdown) {

        $this->assetquantitycost = $assetquantitycost;
        $this->assetbreakdown = $assetbreakdown;
        $this->employeehr = $employeehr;
        $this->assetservicerepair = $assetservicerepair;
        $this->assetservicerepairpart = $assetservicerepairpart;
        $this->company = $company;
        $this->location = $location;
        $this->uom = $uom;
        $this->supplier = $supplier;
        $this->assettechfeature = $assettechfeature;
        $this->itemaccount = $itemaccount;
        $this->itemclass = $itemclass;
        $this->itemcategory = $itemcategory;
        $this->store = $store;
        $this->department = $department;
        $this->division = $division;
        $this->section = $section;
        $this->subsection = $subsection;
        $this->designation = $designation;
        $this->buyer= $buyer;


        $this->middleware('auth');
        /* $this->middleware('permission:view.assetservicerepairparts',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.assetservicerepairparts', ['only' => ['store']]);
        $this->middleware('permission:edit.assetservicerepairparts',   ['only' => ['update']]);
        $this->middleware('permission:delete.assetservicerepairparts', ['only' => ['destroy']]); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
     $rows=$this->assetservicerepairpart
     ->join("asset_service_repairs",function($join){
      $join->on("asset_service_repairs.id","=","asset_service_repair_parts.asset_service_repair_id");
     })
     ->join('item_accounts',function($join){
      $join->on('item_accounts.id','=','asset_service_repair_parts.item_account_id');
     })
     ->join("itemcategories",function($join){
      $join->on("itemcategories.id","=","item_accounts.itemcategory_id");
     })
     ->where([['asset_service_repair_parts.asset_service_repair_id','=',request('asset_service_repair_id',0)]])
     ->orderBy("asset_service_repair_parts.id","DESC")
     ->get([
      "asset_service_repair_parts.*",
      'item_accounts.item_description',
      'item_accounts.specification',
     ])
     ->map(function($rows){
         $rows->itemcategory=$rows->item_description.",".$rows->specification;
         return $rows;
     });

     echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AssetServiceRepairPartRequest $request) {
        $assetservicerepairpart=$this->assetservicerepairpart->create([
         "asset_service_repair_id"=>$request->asset_service_repair_id,
         "item_account_id"=>$request->item_account_id,
         "qty"=>$request->qty,
         "remarks"=>$request->remarks,
        ]);
        if($assetservicerepairpart){
            return response()->json(array('success'=>true,'id'=>$assetservicerepairpart->id,'message'=>'Save Successfully'),200);
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
        $assetservicerepairpart=$this->assetservicerepairpart
        ->join("asset_service_repairs",function($join){
            $join->on("asset_service_repairs.id","=","asset_service_repair_parts.asset_service_repair_id");
        })
        ->join('item_accounts',function($join){
            $join->on('item_accounts.id','=','asset_service_repair_parts.item_account_id');
        })
        ->join("itemcategories",function($join){
            $join->on("itemcategories.id","=","item_accounts.itemcategory_id");
        })
        ->where("asset_service_repair_parts.id","=",$id)
        ->get([
            'asset_service_repair_parts.*',
    		'item_accounts.item_description',
            'item_accounts.specification',
        ])
    	->map(function($assetservicerepairpart){
            $assetservicerepairpart->itemcategories_name=$assetservicerepairpart->item_description.",".$assetservicerepairpart->specification;
            return $assetservicerepairpart;
        })
       ->first();
       $row['fromData']=$assetservicerepairpart;
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
    public function update(AssetServiceRepairPartRequest $request, $id) {
        $assetservicerepairpart=$this->assetservicerepairpart->update($id,[
         "asset_service_repair_id"=>$request->asset_service_repair_id,
         "item_account_id"=>$request->item_account_id,
         "qty"=>$request->qty,
         "remarks"=>$request->remarks,
        ]);
        if($assetservicerepairpart){
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
        if($this->assetservicerepairpart->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
	   	}
    }

    public function getAssetServicePart(){
           $rows = $this->itemaccount
          ->join('itemcategories', function ($join) {
                $join->on('itemcategories.id', '=', 'item_accounts.itemcategory_id');
            })
            ->join('itemclasses', function ($join) {
                $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
            })
            ->leftJoin('yarncounts', function ($join) {
                $join->on('yarncounts.id', '=', 'item_accounts.yarncount_id');
            })
            ->leftJoin('yarntypes', function ($join) {
                $join->on('yarntypes.id', '=', 'item_accounts.yarntype_id');
            })
            ->leftJoin('compositions', function ($join) {
                $join->on('compositions.id', '=', 'item_accounts.composition_id');
            })
            ->leftJoin('colors', function ($join) {
                $join->on('colors.id', '=', 'item_accounts.color_id');
            })
            ->leftJoin('sizes', function ($join) {
                $join->on('sizes.id', '=', 'item_accounts.size_id');
            })
            ->leftJoin('uoms', function ($join) {
                $join->on('uoms.id', '=', 'item_accounts.uom_id');
            })
           ->when(request('itemcategory_id'), function ($q) {
            return $q->where('itemcategories.id', '=',request('itemcategory_id', 0));
           })
           ->when(request('itemclass_id'), function ($q) {
            return $q->where('itemclasses.id', '=',request('itemclass_id', 0));
           })
            ->where([['item_accounts.status_id', '=', 1]])
            ->where([['itemcategories.identity','=',9]])
            ->orderBy('item_accounts.id', 'desc')
            ->get([
                'item_accounts.*',
                'itemcategories.name as itemcategories_name',
                'itemclasses.name as class_name',
                'yarncounts.count',
                'yarncounts.symbol',
                'yarntypes.name as yarn_type',
                'compositions.name as composition',
                'colors.name as color',
                'sizes.name as size',
                'uoms.code as uom_code'
            ]);
        echo json_encode($rows);
    }

}
