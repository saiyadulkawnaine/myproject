<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtIronRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtIronOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtIronQtyRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;

use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtIronOrderRequest;


class ProdGmtIronOrderController extends Controller
{

 private $prodgmtironorder;
 private $prodgmtiron;
 private $location;
 private $gmtironqty;
 private $assetquantitycost;

 public function __construct(ProdGmtIronOrderRepository $prodgmtironorder, ProdGmtIronRepository $prodgmtiron, LocationRepository $location, SalesOrderCountryRepository $salesordercountry, WstudyLineSetupRepository $wstudylinesetup, ProdGmtIronQtyRepository $gmtironqty, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize, SupplierRepository $supplier, AssetQuantityCostRepository $assetquantitycost)
 {
  $this->prodgmtironorder = $prodgmtironorder;
  $this->prodgmtiron = $prodgmtiron;
  $this->gmtironqty = $gmtironqty;
  $this->salesordercountry = $salesordercountry;
  $this->location = $location;
  $this->wstudylinesetup = $wstudylinesetup;
  $this->salesordergmtcolorsize = $salesordergmtcolorsize;
  $this->supplier = $supplier;
  $this->assetquantitycost = $assetquantitycost;

  $this->middleware('auth');
  $this->middleware('permission:view.prodgmtironorders',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.prodgmtironorders', ['only' => ['store']]);
  $this->middleware('permission:edit.prodgmtironorders',   ['only' => ['update']]);
  $this->middleware('permission:delete.prodgmtironorders', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {

  $productionsource = array_prepend(config('bprs.productionsource'), '-Select-', '');
  $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '', '');
  // $subsections=$this->wstudylinesetup
  //     ->join('companies', function($join)  {
  //         $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
  //     })
  //     ->join('wstudy_line_setup_lines', function($join)  {
  //         $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
  //     })
  //     ->join('subsections', function($join)  {
  //         $join->on('subsections.id', '=', 'wstudy_line_setup_lines.subsection_id');
  //     })
  //     ->join('floors', function($join)  {
  //         $join->on('floors.id', '=', 'subsections.floor_id');
  //     })
  //     ->when(request('location_id'), function ($q) {
  //         return $q->where('wstudy_line_setups.location_id', '=',request('location_id', 0));
  //     })
  //     ->get([
  //         'wstudy_line_setups.id',
  //         'subsections.name',
  //         'subsections.code',
  //         'floors.name as floor_name'
  //     ]);
  //     $lineNames=Array();
  //     $lineCode=Array();
  //     $lineFloor=Array();
  //     foreach($subsections as $subsection)
  //     {
  //        $lineNames[$subsection->id][]=$subsection->name;
  //        $lineCode[$subsection->id][]=$subsection->code;
  //        $lineFloor[$subsection->id][]=$subsection->floor_name;
  //     }

  $prodgmtironorders = array();
  $rows = $this->prodgmtironorder
   ->leftJoin('prod_gmt_irons', function ($join) {
    $join->on('prod_gmt_irons.id', '=', 'prod_gmt_iron_orders.prod_gmt_iron_id');
   })
   ->leftJoin('sales_order_countries', function ($join) {
    $join->on('sales_order_countries.id', '=', 'prod_gmt_iron_orders.sales_order_country_id');
   })
   ->leftJoin('countries', function ($join) {
    $join->on('countries.id', '=', 'sales_order_countries.country_id');
   })
   ->leftJoin('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'sales_order_countries.sale_order_id');
   })
   ->leftJoin('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'jobs.company_id');
   })
   ->leftJoin('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'styles.buyer_id');
   })
   // ->leftJoin('wstudy_line_setups', function($join)  {
   //     $join->on('prod_gmt_iron_orders.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
   // })
   // ->leftJoin('locations', function($join)  {
   //     $join->on('locations.id', '=', 'wstudy_line_setups.location_id');
   // })
   ->leftJoin('asset_quantity_costs', function ($join) {
    $join->on('prod_gmt_iron_orders.asset_quantity_cost_id', '=', 'asset_quantity_costs.id');
   })
   ->leftJoin('asset_acquisitions', function ($join) {
    $join->on('asset_quantity_costs.asset_acquisition_id', '=', 'asset_acquisitions.id');
   })
   ->leftJoin('locations', function ($join) {
    $join->on('locations.id', '=', 'asset_acquisitions.location_id');
   })
   ->where([['prod_gmt_iron_id', '=', request('prod_gmt_iron_id', 0)]])
   ->orderBy('prod_gmt_iron_orders.id', 'desc')
   ->get([
    'prod_gmt_iron_orders.*',
    'sales_orders.sale_order_no',
    'asset_acquisitions.location_id',
    'locations.name as location_id',
    'asset_quantity_costs.custom_no as table_no'
   ]);
  foreach ($rows as $row) {
   $prodgmtironorder['id'] = $row->id;
   $prodgmtironorder['sales_order_country_id'] = $row->sales_order_country_id;
   $prodgmtironorder['sale_order_no'] = $row->sale_order_no;
   $prodgmtironorder['prod_source_id'] = $productionsource[$row->prod_source_id];
   $prodgmtironorder['supplier_id'] = $supplier[$row->supplier_id];
   $prodgmtironorder['location_id'] = $row->location_id;
   $prodgmtironorder['table_no'] = $row->table_no;
   //$prodgmtironorder['wstudy_line_setup_id']=isset($row->wstudy_line_setup_id)?implode(',',$lineCode[$row->wstudy_line_setup_id]):'';
   array_push($prodgmtironorders, $prodgmtironorder);
  }
  echo json_encode($prodgmtironorders);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {

  //
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(ProdGmtIronOrderRequest $request)
 {
  $prodgmtironorder = $this->prodgmtironorder->create([
   'prod_gmt_iron_id' => $request->prod_gmt_iron_id,
   'sales_order_country_id' => $request->sales_order_country_id,
   'prod_source_id' => $request->prod_source_id,
   'supplier_id' => $request->supplier_id,
   //'wstudy_line_setup_id'=>$request->wstudy_line_setup_id,
   'asset_quantity_cost_id' => $request->asset_quantity_cost_id,
   'prod_hour' => $request->prod_hour
  ]);
  if ($prodgmtironorder) {
   return response()->json(array('success' => true, 'id' =>  $prodgmtironorder->id, 'message' => 'Save Successfully'), 200);
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

  // $subsections=$this->prodgmtironorder
  // ->leftJoin('wstudy_line_setups', function($join)  {
  //     $join->on('prod_gmt_iron_orders.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
  // })    
  // ->join('wstudy_line_setup_lines', function($join)  {
  //     $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
  // })
  // ->join('subsections', function($join)  {
  //     $join->on('subsections.id', '=', 'wstudy_line_setup_lines.subsection_id');
  // })
  // ->when(request('line_merged_id'), function ($q) {
  //     return $q->where('wstudy_line_setups.line_merged_id', 'LIKE', "%".request('line_merged_id', 0)."%");
  // })
  // ->where([['prod_gmt_iron_orders.id','=',$id]])
  // ->get([
  //     'wstudy_line_setups.id',
  //     'subsections.code'
  // ]);
  // $lineNames=Array();
  // foreach($subsections as $subsection)
  // {
  //    $lineNames[$subsection->id][]=$subsection->code;
  // }


  $prodgmtironorder = $this->prodgmtironorder
   ->join('prod_gmt_irons', function ($join) {
    $join->on('prod_gmt_irons.id', '=', 'prod_gmt_iron_orders.prod_gmt_iron_id');
   })
   ->leftJoin('sales_order_countries', function ($join) {
    $join->on('sales_order_countries.id', '=', 'prod_gmt_iron_orders.sales_order_country_id');
   })
   ->leftJoin('countries', function ($join) {
    $join->on('countries.id', '=', 'sales_order_countries.country_id');
   })
   ->leftJoin('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'sales_order_countries.sale_order_id');
   })
   ->leftJoin('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'jobs.company_id');
   })
   ->leftJoin('companies as produced_company', function ($join) {
    $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
   })
   ->leftJoin('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->leftJoin('sales_order_gmt_color_sizes', function ($join) {
    $join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
   })
   ->leftJoin('style_gmts', function ($join) {
    $join->on('style_gmts.id', '=', 'sales_order_gmt_color_sizes.style_gmt_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'styles.buyer_id');
   })
   // ->leftJoin('wstudy_line_setups', function($join)  {
   //     $join->on('prod_gmt_iron_orders.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
   // })
   // ->leftJoin('locations', function($join)  {
   //     $join->on('locations.id', '=', 'wstudy_line_setups.location_id');
   // })
   ->leftJoin('asset_quantity_costs', function ($join) {
    $join->on('prod_gmt_iron_orders.asset_quantity_cost_id', '=', 'asset_quantity_costs.id');
   })
   ->leftJoin('asset_acquisitions', function ($join) {
    $join->on('asset_quantity_costs.asset_acquisition_id', '=', 'asset_acquisitions.id');
   })
   ->leftJoin('locations', function ($join) {
    $join->on('locations.id', '=', 'asset_acquisitions.location_id');
   })
   ->where([['prod_gmt_iron_orders.id', '=', $id]])
   ->get([
    'prod_gmt_iron_orders.*',
    'sales_orders.sale_order_no',
    //'wstudy_line_setups.id as wstudy_line_setup_id',
    'sales_orders.produced_company_id',
    'sales_orders.ship_date',
    'sales_orders.job_id',
    'jobs.job_no',
    'jobs.company_id',
    'styles.buyer_id',
    'companies.name as company_id',
    'countries.name as country_id',
    'locations.id as location_id',
    'locations.name as location_name',
    'buyers.name as buyer_name',
    'produced_company.name as produced_company_name',
    'asset_quantity_costs.custom_no as table_no'

   ])->map(function ($prodgmtironorder) /*use($lineNames)*/ {
    // $prodgmtironorder->line_name=isset($prodgmtironorder->wstudy_line_setup_id)?implode(',',$lineNames[$prodgmtironorder->wstudy_line_setup_id]):'';
    return $prodgmtironorder;
   })->first();

  $gmtironqty = $this->prodgmtironorder
   ->leftJoin('prod_gmt_irons', function ($join) {
    $join->on('prod_gmt_irons.id', '=', 'prod_gmt_iron_orders.prod_gmt_iron_id');
   })
   ->leftJoin('sales_order_countries', function ($join) {
    $join->on('sales_order_countries.id', '=', 'prod_gmt_iron_orders.sales_order_country_id');
   })
   ->leftJoin('countries', function ($join) {
    $join->on('countries.id', '=', 'sales_order_countries.country_id');
   })
   ->leftJoin('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'sales_order_countries.sale_order_id');
   })
   ->leftJoin('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'jobs.company_id');
   })
   ->leftJoin('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->leftJoin('sales_order_gmt_color_sizes', function ($join) {
    $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id');
   })
   ->leftJoin('style_gmt_color_sizes', function ($join) {
    $join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
   })
   ->leftJoin('style_gmts', function ($join) {
    $join->on('style_gmts.id', '=', 'style_gmt_color_sizes.style_gmt_id');
   })
   ->join('item_accounts', function ($join) {
    $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
   })
   ->leftJoin('style_colors', function ($join) {
    $join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
   })
   ->leftJoin('colors', function ($join) {
    $join->on('colors.id', '=', 'style_colors.color_id');
   })
   ->leftJoin('style_sizes', function ($join) {
    $join->on('style_sizes.id', '=', 'style_gmt_color_sizes.style_size_id');
   })
   ->leftJoin('sizes', function ($join) {
    $join->on('sizes.id', '=', 'style_sizes.size_id');
   })
   ->leftJoin('prod_gmt_iron_qties', function ($join) {
    $join->on('prod_gmt_iron_qties.prod_gmt_iron_order_id', '=', 'prod_gmt_iron_orders.id');
    $join->on('prod_gmt_iron_qties.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
   })

   ->leftJoin(\DB::raw("(SELECT sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,sum(prod_gmt_iron_qties.qty) as qty FROM prod_gmt_iron_qties join prod_gmt_iron_orders on prod_gmt_iron_orders.id =prod_gmt_iron_qties.prod_gmt_iron_order_id join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_iron_qties.sales_order_gmt_color_size_id where prod_gmt_iron_qties.deleted_at is null  group by sales_order_gmt_color_sizes.id) cumulatives"), "cumulatives.sales_order_gmt_color_size_id", "=", "sales_order_gmt_color_sizes.id")

   ->orderBy('style_colors.sort_id')
   ->orderBy('style_sizes.sort_id')
   ->where([['prod_gmt_iron_orders.id', '=', $id]])
   ->get([
    'sizes.name as size_name',
    'sizes.code as size_code',
    'colors.name as color_name',
    'colors.code as color_code',
    'style_sizes.sort_id as size_sort_id',
    'style_colors.sort_id as color_sort_id',
    'sales_order_gmt_color_sizes.plan_cut_qty',
    'sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id',
    'item_accounts.item_description',
    'prod_gmt_iron_qties.id as prod_gmt_iron_qty_id',
    'prod_gmt_iron_qties.qty',
    'prod_gmt_iron_qties.alter_qty',
    'prod_gmt_iron_qties.spot_qty',
    'prod_gmt_iron_qties.reject_qty',
    'prod_gmt_iron_qties.replace_qty',
    'cumulatives.qty as cumulative_qty'
   ])
   ->map(function ($gmtironqty) {
    $gmtironqty->balance_qty = $gmtironqty->plan_cut_qty - $gmtironqty->cumulative_qty;
    $gmtironqty->cumulative_qty_saved = $gmtironqty->cumulative_qty - $gmtironqty->qty;
    $gmtironqty->balance_qty_saved = $gmtironqty->plan_cut_qty - $gmtironqty->cumulative_qty_saved;
    return $gmtironqty;
   });
  $saved = $gmtironqty->filter(function ($value) {
   if ($value->prod_gmt_iron_qty_id) {
    return $value;
   }
  });
  $new = $gmtironqty->filter(function ($value) {
   if (!$value->prod_gmt_iron_qty_id) {
    return $value;
   }
  });

  $row['fromData'] = $prodgmtironorder;
  $dropdown['irongmtcosi'] = "'" . Template::loadView('Production.Garments.ProdGmtIronQtyMatrix', ['colorsizes' => $new, 'saved' => $saved]) . "'";
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
 public function update(ProdGmtIronOrderRequest $request, $id)
 {
  $prodgmtironorder = $this->prodgmtironorder->update(
   $id,
   [
    'prod_gmt_iron_id' => $request->prod_gmt_iron_id,
    'sales_order_country_id' => $request->sales_order_country_id,
    'prod_source_id' => $request->prod_source_id,
    'supplier_id' => $request->supplier_id,
    'asset_quantity_cost_id' => $request->asset_quantity_cost_id,
    // 'wstudy_line_setup_id'=>$request->wstudy_line_setup_id,
    'prod_hour' => $request->prod_hour
   ]
  );
  if ($prodgmtironorder) {
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
  if ($this->prodgmtironorder->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getIronOrder()
 {
  $salesordercountry = $this->salesordercountry
   ->selectRaw('
            sales_order_countries.id as sales_order_country_id,
            sales_orders.sale_order_no,
            sales_orders.ship_date,
            sales_orders.produced_company_id,
            styles.style_ref,
            styles.id as style_id,
            jobs.job_no,
            buyers.code as buyer_name,
            companies.name as company_id,
            produced_company.name as produced_company_name,
            countries.name as country_id,
            sum(sales_order_gmt_color_sizes.qty) as order_qty,
            avg(sales_order_gmt_color_sizes.rate) as order_rate,
            sum(sales_order_gmt_color_sizes.amount) as order_amount
         ')
   ->join('countries', function ($join) {
    $join->on('countries.id', '=', 'sales_order_countries.country_id');
   })
   ->join('sales_orders', function ($join) {
    $join->on('sales_orders.id', '=', 'sales_order_countries.sale_order_id');
   })
   ->join('jobs', function ($join) {
    $join->on('jobs.id', '=', 'sales_orders.job_id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'jobs.company_id');
   })
   ->leftJoin('companies as produced_company', function ($join) {
    $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
   })
   ->join('styles', function ($join) {
    $join->on('styles.id', '=', 'jobs.style_id');
   })
   ->join('sales_order_gmt_color_sizes', function ($join) {
    $join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
   })
   ->join('style_gmts', function ($join) {
    $join->on('style_gmts.id', '=', 'sales_order_gmt_color_sizes.style_gmt_id');
   })
   ->join('buyers', function ($join) {
    $join->on('buyers.id', '=', 'styles.buyer_id');
   })
   ->when(request('style_ref'), function ($q) {
    return $q->where('styles.style_ref', 'LIKE', "%" . request('style_ref', 0) . "%");
   })
   ->when(request('job_no'), function ($q) {
    return $q->where('jobs.job_no', 'LIKE', "%" . request('job_no', 0) . "%");
   })
   ->when(request('sale_order_no'), function ($q) {
    return $q->where('sales_orders.sale_order_no', 'LIKE', "%" . request('sale_order_no', 0) . "%");
   })
   ->groupBy([
    'sales_order_countries.id',
    'sales_orders.sale_order_no',
    'sales_orders.ship_date',
    'sales_orders.produced_company_id',
    'styles.style_ref',
    'styles.id',
    'jobs.job_no',
    'buyers.code',
    'companies.name',
    'produced_company.name',
    'countries.name'
   ])
   ->get()
   ->map(function ($salesordercountry) {
    return $salesordercountry;
   });
  echo json_encode($salesordercountry);
 }

 /*public function getLine(){
       $prodgmtiron=$this->prodgmtiron->find(request('prod_gmt_iron_id',0));
       $iron_qc_date=$prodgmtiron->iron_qc_date;
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
        $subsections=$this->wstudylinesetup
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
        })
        ->join('wstudy_line_setup_lines', function($join)  {
            $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })
        ->join('subsections', function($join)  {
            $join->on('subsections.id', '=', 'wstudy_line_setup_lines.subsection_id');
        })
        ->join('floors', function($join)  {
            $join->on('floors.id', '=', 'subsections.floor_id');
        })
        ->when(request('location_id'), function ($q) {
            return $q->where('wstudy_line_setups.location_id', '=',request('location_id', 0));
        })
        ->where([['wstudy_line_setups.company_id','=',request('produced_company_id',0)]])
        ->get([
            'wstudy_line_setups.id',
            'subsections.name',
            'subsections.code',
            'floors.name as floor_name'
        ]);
        $lineNames=Array();
        $lineCode=Array();
        $lineFloor=Array();
        foreach($subsections as $subsection)
        {
           $lineNames[$subsection->id][]=$subsection->name;
           $lineCode[$subsection->id][]=$subsection->code;
           $lineFloor[$subsection->id][]=$subsection->floor_name;
        }


        $wstudylinesetup=$this->wstudylinesetup
        ->join('wstudy_line_setup_dtls', function($join)  {
            $join->on('wstudy_line_setup_dtls.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
        })
        ->join('locations', function($join)  {
            $join->on('locations.id', '=', 'wstudy_line_setups.location_id');
        })
        ->when(request('line_merged_id'), function ($q) {
            return $q->where('wstudy_line_setups.line_merged_id', 'LIKE', "%".request('line_merged_id', 0)."%");
        })
        ->whereRAW(" ? between wstudy_line_setup_dtls.from_date and wstudy_line_setup_dtls.to_date ",[$iron_qc_date])
        ->where([['wstudy_line_setups.company_id','=',request('produced_company_id',0)]])
        ->get([
            'wstudy_line_setups.*',
            'companies.name as company_name',
            'locations.name as location_name'
        ])
        ->map(function($wstudylinesetup) use($yesno,$lineNames,$lineCode,$lineFloor){
            $wstudylinesetup->line_merged_id=$yesno[$wstudylinesetup->line_merged_id];
            $wstudylinesetup->line_name=implode(',',$lineNames[$wstudylinesetup->id]);
            $wstudylinesetup->line_code=implode(',',$lineCode[$wstudylinesetup->id]);
            $wstudylinesetup->line_floor=implode(',',$lineFloor[$wstudylinesetup->id]);

            return $wstudylinesetup;
        });

        echo json_encode($wstudylinesetup);
    }*/

 public function getTable()
 {
  $machine = $this->assetquantitycost
   ->join('asset_acquisitions', function ($join) {
    $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
   })
   ->leftJoin('locations', function ($join) {
    $join->on('locations.id', '=', 'asset_acquisitions.location_id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'asset_acquisitions.company_id');
   })
   ->when(request('brand'), function ($q) {
    return $q->where('asset_acquisitions.brand', 'like', '%' . request('brand', 0) . '%');
   })
   ->when(request('asset_no'), function ($q) {
    return $q->where('asset_quantity_costs.custom_no', '=', request('custom_no', 0));
   })
   //->where([['asset_acquisitions.company_id','=',request('produced_company_id',0)]])
   ->where([['asset_acquisitions.type_id', '=', 65]])
   ->where([['asset_acquisitions.production_area_id', '=', 65]])
   ->orderBy('asset_quantity_costs.id', 'asc')
   ->get([
    'asset_quantity_costs.*',
    'asset_acquisitions.id as asset_acquisition_id',
    'asset_acquisitions.prod_capacity',
    'asset_acquisitions.name as asset_name',
    'asset_acquisitions.origin',
    'asset_acquisitions.brand',
    'asset_acquisitions.location_id',
    'asset_acquisitions.asset_group',
    'locations.name as location_name',
    'companies.name as company_name',
   ]);
  echo json_encode($machine);
 }
}
