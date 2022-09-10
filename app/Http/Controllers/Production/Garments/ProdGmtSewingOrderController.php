<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingQtyRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Util\SupplierRepository;

use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtSewingOrderRequest;


class ProdGmtSewingOrderController extends Controller
{

 private $prodgmtsewingorder;
 private $prodgmtsewing;
 private $location;
 private $gmtsewingqty;

 public function __construct(ProdGmtSewingOrderRepository $prodgmtsewingorder, ProdGmtSewingRepository $prodgmtsewing, LocationRepository $location, SalesOrderCountryRepository $salesordercountry, WstudyLineSetupRepository $wstudylinesetup, ProdGmtSewingQtyRepository $gmtsewingqty, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize, SupplierRepository $supplier)
 {
  $this->prodgmtsewingorder = $prodgmtsewingorder;
  $this->prodgmtsewing = $prodgmtsewing;
  $this->gmtsewingqty = $gmtsewingqty;
  $this->salesordercountry = $salesordercountry;
  $this->location = $location;
  $this->wstudylinesetup = $wstudylinesetup;
  $this->salesordergmtcolorsize = $salesordergmtcolorsize;
  $this->supplier = $supplier;
  $this->middleware('auth');
  $this->middleware('permission:view.prodgmtsewingorders',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.prodgmtsewingorders', ['only' => ['store']]);
  $this->middleware('permission:edit.prodgmtsewingorders',   ['only' => ['update']]);
  $this->middleware('permission:delete.prodgmtsewingorders', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $supplier = array_prepend(array_pluck($this->supplier->get(), 'name', 'id'), '', '');
  $productionsource = array_prepend(config('bprs.productionsource'), '-Select-', '');
  $subsections = $this->wstudylinesetup
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
   })
   ->join('wstudy_line_setup_lines', function ($join) {
    $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
   })
   ->join('subsections', function ($join) {
    $join->on('subsections.id', '=', 'wstudy_line_setup_lines.subsection_id');
   })
   ->join('floors', function ($join) {
    $join->on('floors.id', '=', 'subsections.floor_id');
   })
   ->when(request('location_id'), function ($q) {
    return $q->where('wstudy_line_setups.location_id', '=', request('location_id', 0));
   })
   ->get([
    'wstudy_line_setups.id',
    'subsections.name',
    'subsections.code',
    'floors.name as floor_name'
   ]);
  $lineNames = array();
  $lineCode = array();
  $lineFloor = array();
  foreach ($subsections as $subsection) {
   $lineNames[$subsection->id][] = $subsection->name;
   $lineCode[$subsection->id][] = $subsection->code;
   $lineFloor[$subsection->id][] = $subsection->floor_name;
  }

  $prodgmtsewingorders = array();
  $rows = $this->prodgmtsewingorder
   // ->selectRaw('
   // prod_gmt_sewing_orders.id,
   // sales_order_countries.id as sales_order_country_id,
   // sales_orders.sale_order_no,
   // sales_orders.ship_date,
   // sales_orders.produced_company_id,
   // styles.style_ref,
   // styles.id as style_id,
   // jobs.job_no,
   // jobs.company_id,
   // buyers.code as buyer_name,
   // countries.name as country_id,

   // wstudy_line_setups.id as wstudy_line_setup_id,
   // wstudy_line_setups.location_id,
   // locations.name as location_id,
   // companies.name as company_id
   // ')
   ->leftJoin('prod_gmt_sewings', function ($join) {
    $join->on('prod_gmt_sewings.id', '=', 'prod_gmt_sewing_orders.prod_gmt_sewing_id');
   })
   ->leftJoin('sales_order_countries', function ($join) {
    $join->on('sales_order_countries.id', '=', 'prod_gmt_sewing_orders.sales_order_country_id');
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
   ->leftJoin('wstudy_line_setups', function ($join) {
    $join->on('prod_gmt_sewing_orders.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
   })
   ->join('locations', function ($join) {
    $join->on('locations.id', '=', 'wstudy_line_setups.location_id');
   })
   /*->join('wstudy_line_setup_lines', function($join)  {
            $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })*/
   ->where([['prod_gmt_sewing_id', '=', request('prod_gmt_sewing_id', 0)]])
   ->orderBy('prod_gmt_sewing_orders.id', 'desc')
   ->get([
    'prod_gmt_sewing_orders.*',
    'sales_orders.sale_order_no',
    'wstudy_line_setups.location_id',
    'locations.name as location_id'

   ]);
  foreach ($rows as $row) {
   $prodgmtsewingorder['id'] = $row->id;
   $prodgmtsewingorder['sales_order_country_id'] = $row->sales_order_country_id;
   $prodgmtsewingorder['sale_order_no'] = $row->sale_order_no;
   $prodgmtsewingorder['prod_source_id'] = $productionsource[$row->prod_source_id];
   $prodgmtsewingorder['location_id'] = $row->location_id;
   $prodgmtsewingorder['wstudy_line_setup_id'] = implode(',', $lineCode[$row->wstudy_line_setup_id]);
   $prodgmtsewingorder['supplier_id'] = $supplier[$row->supplier_id];
   array_push($prodgmtsewingorders, $prodgmtsewingorder);
  }
  echo json_encode($prodgmtsewingorders);
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
 public function store(ProdGmtSewingOrderRequest $request)
 {

  $prodgmtsewing = $this->prodgmtsewing->find($request->prod_gmt_sewing_id);
  $time = substr_replace($request->prod_hour, ":00", -2) . " " . substr($request->prod_hour, -2);
  $prod_at = date('Y-m-d H:i:s', strtotime($request->sew_qc_date . " " . $time));

  $prodgmtsewingorder = $this->prodgmtsewingorder->create([
   'prod_gmt_sewing_id' => $request->prod_gmt_sewing_id,
   'sales_order_country_id' => $request->sales_order_country_id,
   'prod_source_id' => $request->prod_source_id,
   'wstudy_line_setup_id' => $request->wstudy_line_setup_id,
   'supplier_id' => $request->supplier_id,
   'prod_hour' => $request->prod_hour,
   'prod_at' => $prod_at
  ]);
  if ($prodgmtsewingorder) {
   return response()->json(array('success' => true, 'id' =>  $prodgmtsewingorder->id, 'message' => 'Save Successfully'), 200);
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

  $subsections = $this->prodgmtsewingorder
   ->leftJoin('wstudy_line_setups', function ($join) {
    $join->on('prod_gmt_sewing_orders.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
   })
   ->join('wstudy_line_setup_lines', function ($join) {
    $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
   })
   ->join('subsections', function ($join) {
    $join->on('subsections.id', '=', 'wstudy_line_setup_lines.subsection_id');
   })
   ->when(request('line_merged_id'), function ($q) {
    return $q->where('wstudy_line_setups.line_merged_id', 'LIKE', "%" . request('line_merged_id', 0) . "%");
   })
   ->where([['prod_gmt_sewing_orders.id', '=', $id]])
   ->get([
    'wstudy_line_setups.id',
    'subsections.code'
   ]);
  $lineNames = array();
  foreach ($subsections as $subsection) {
   $lineNames[$subsection->id][] = $subsection->code;
  }


  $prodgmtsewingorder = $this->prodgmtsewingorder
   ->leftJoin('prod_gmt_sewings', function ($join) {
    $join->on('prod_gmt_sewings.id', '=', 'prod_gmt_sewing_orders.prod_gmt_sewing_id');
   })
   ->leftJoin('sales_order_countries', function ($join) {
    $join->on('sales_order_countries.id', '=', 'prod_gmt_sewing_orders.sales_order_country_id');
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
   ->leftJoin('wstudy_line_setups', function ($join) {
    $join->on('prod_gmt_sewing_orders.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
   })
   ->join('locations', function ($join) {
    $join->on('locations.id', '=', 'wstudy_line_setups.location_id');
   })
   /*->join('wstudy_line_setup_lines', function($join)  {
            $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })*/
   ->where([['prod_gmt_sewing_orders.id', '=', $id]])
   ->get([
    'prod_gmt_sewing_orders.*',
    'sales_orders.sale_order_no',
    'wstudy_line_setups.id as wstudy_line_setup_id',
    'sales_orders.produced_company_id',
    'sales_orders.ship_date',
    'sales_orders.job_id',
    'jobs.job_no',
    'jobs.company_id',
    'styles.buyer_id',
    'companies.name as company_id',
    'countries.name as country_id',
    'locations.name as location_id',
    'buyers.name as buyer_name',
    'produced_company.name as produced_company_name'

   ])->map(function ($prodgmtsewingorder) use ($lineNames) {
    $prodgmtsewingorder->line_name = implode(',', $lineNames[$prodgmtsewingorder->wstudy_line_setup_id]);
    return $prodgmtsewingorder;
   })->first();




  /*$prodgmtsewingorder=$prodgmtsewingorder->map(function($prodgmtsewingorder) use($lineNames){
            $prodgmtsewingorder->line_name=implode(',',$lineNames[$prodgmtsewingorder->wstudy_line_setup_id]);
            return $prodgmtsewingorder;
        })->first();*/


  $gmtsewingqty = $this->prodgmtsewingorder
   ->leftJoin('prod_gmt_sewings', function ($join) {
    $join->on('prod_gmt_sewings.id', '=', 'prod_gmt_sewing_orders.prod_gmt_sewing_id');
   })
   ->leftJoin('sales_order_countries', function ($join) {
    $join->on('sales_order_countries.id', '=', 'prod_gmt_sewing_orders.sales_order_country_id');
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
   ->leftJoin('prod_gmt_sewing_qties', function ($join) {
    $join->on('prod_gmt_sewing_qties.prod_gmt_sewing_order_id', '=', 'prod_gmt_sewing_orders.id');
    $join->on('prod_gmt_sewing_qties.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
   })

   ->leftJoin(\DB::raw("(SELECT sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,sum(prod_gmt_sewing_qties.qty) as qty FROM prod_gmt_sewing_qties join prod_gmt_sewing_orders on prod_gmt_sewing_orders.id =prod_gmt_sewing_qties.prod_gmt_sewing_order_id join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_sewing_qties.sales_order_gmt_color_size_id where prod_gmt_sewing_qties.deleted_at is null  group by sales_order_gmt_color_sizes.id) cumulatives"), "cumulatives.sales_order_gmt_color_size_id", "=", "sales_order_gmt_color_sizes.id")

   ->orderBy('style_colors.sort_id')
   ->orderBy('style_sizes.sort_id')
   ->where([['prod_gmt_sewing_orders.id', '=', $id]])
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
    'prod_gmt_sewing_qties.id as prod_gmt_sewing_qty_id',
    'prod_gmt_sewing_qties.qty',
    'prod_gmt_sewing_qties.alter_qty',
    'prod_gmt_sewing_qties.spot_qty',
    'prod_gmt_sewing_qties.reject_qty',
    'prod_gmt_sewing_qties.replace_qty',
    'cumulatives.qty as cumulative_qty'
   ])
   ->map(function ($gmtsewingqty) {
    $gmtsewingqty->balance_qty = $gmtsewingqty->plan_cut_qty - $gmtsewingqty->cumulative_qty;
    $gmtsewingqty->cumulative_qty_saved = $gmtsewingqty->cumulative_qty - $gmtsewingqty->qty;
    $gmtsewingqty->balance_qty_saved = $gmtsewingqty->plan_cut_qty - $gmtsewingqty->cumulative_qty_saved;
    return $gmtsewingqty;
   });
  $saved = $gmtsewingqty->filter(function ($value) {
   if ($value->prod_gmt_sewing_qty_id) {
    return $value;
   }
  });
  $new = $gmtsewingqty->filter(function ($value) {
   if (!$value->prod_gmt_sewing_qty_id) {
    return $value;
   }
  });

  $row['fromData'] = $prodgmtsewingorder;
  $dropdown['sewinggmtcosi'] = "'" . Template::loadView('Production.Garments.ProdGmtSewingQtyMatrix', ['colorsizes' => $new, 'saved' => $saved]) . "'";
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
 public function update(ProdGmtSewingOrderRequest $request, $id)
 {
  $prodgmtsewing = $this->prodgmtsewing->find($request->prod_gmt_sewing_id);
  $time = substr_replace($request->prod_hour, ":00", -2) . " " . substr($request->prod_hour, -2);
  $prod_at = date('Y-m-d H:i:s', strtotime($request->sew_qc_date . " " . $time));

  $prodgmtsewingQty = $this->gmtsewingqty
   ->where([['prod_gmt_sewing_order_id', '=', $id]])
   ->first();
  if ($prodgmtsewingQty) {
   $prodgmtsewingorder = $this->prodgmtsewingorder->update(
    $id,
    [
     'prod_source_id' => $request->prod_source_id,
     'supplier_id' => $request->supplier_id,
     'wstudy_line_setup_id' => $request->wstudy_line_setup_id,
     'prod_hour' => $request->prod_hour,
     'prod_at' => $prod_at
    ]
   );
  } else {
   $prodgmtsewingorder = $this->prodgmtsewingorder->update(
    $id,
    [
     'prod_gmt_sewing_id' => $request->prod_gmt_sewing_id,
     'sales_order_country_id' => $request->sales_order_country_id,
     'prod_source_id' => $request->prod_source_id,
     'supplier_id' => $request->supplier_id,
     'wstudy_line_setup_id' => $request->wstudy_line_setup_id,
     'prod_hour' => $request->prod_hour,
     'prod_at' => $prod_at
    ]
   );
  }

  if ($prodgmtsewingorder) {
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
  if ($this->prodgmtsewingorder->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 public function getSewingOrder()
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

 public function getLine()
 {
  $prodgmtsewing = $this->prodgmtsewing->find(request('prod_gmt_sewing_id', 0));
  $sew_qc_date = $prodgmtsewing->sew_qc_date;
  $yesno = array_prepend(config('bprs.yesno'), '-Select-', '');
  $subsections = $this->wstudylinesetup
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
   })
   ->join('wstudy_line_setup_lines', function ($join) {
    $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
   })
   ->join('subsections', function ($join) {
    $join->on('subsections.id', '=', 'wstudy_line_setup_lines.subsection_id');
   })
   ->join('floors', function ($join) {
    $join->on('floors.id', '=', 'subsections.floor_id');
   })
   ->when(request('location_id'), function ($q) {
    return $q->where('wstudy_line_setups.location_id', '=', request('location_id', 0));
   })
   //->where([['wstudy_line_setups.company_id','=',request('produced_company_id',0)]]) N:B: From 23/04/2022 10.23 AM
   ->get([
    'wstudy_line_setups.id',
    'subsections.name',
    'subsections.code',
    'floors.name as floor_name'
   ]);
  $lineNames = array();
  $lineCode = array();
  $lineFloor = array();
  foreach ($subsections as $subsection) {
   $lineNames[$subsection->id][] = $subsection->name;
   $lineCode[$subsection->id][] = $subsection->code;
   $lineFloor[$subsection->id][] = $subsection->floor_name;
  }


  $wstudylinesetup = $this->wstudylinesetup
   // ->join('wstudy_line_setup_lines', function($join)  {
   //     $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
   // })
   ->join('wstudy_line_setup_dtls', function ($join) {
    $join->on('wstudy_line_setup_dtls.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
   })
   ->join('locations', function ($join) {
    $join->on('locations.id', '=', 'wstudy_line_setups.location_id');
   })
   ->when(request('line_merged_id'), function ($q) {
    return $q->where('wstudy_line_setups.line_merged_id', 'LIKE', "%" . request('line_merged_id', 0) . "%");
   })
   ->whereRAW(" ? between wstudy_line_setup_dtls.from_date and wstudy_line_setup_dtls.to_date ", [$sew_qc_date])

   /*->when($sew_qc_date, function ($q) use($sew_qc_date){
        return $q->where('wstudy_line_setup_dtls.from_date', '>=',$sew_qc_date);
        })
        ->when($sew_qc_date, function ($q) use($sew_qc_date){
        return $q->where('wstudy_line_setup_dtls.to_date', '<=',$sew_qc_date);
        })*/
   //->where([['wstudy_line_setups.company_id','=',request('produced_company_id',0)]]) N:B: From 23/04/2022 10.23 AM
   ->get([
    'wstudy_line_setups.*',
    'companies.name as company_name',
    'locations.name as location_name'
   ])
   ->map(function ($wstudylinesetup) use ($yesno, $lineNames, $lineCode, $lineFloor) {
    $wstudylinesetup->line_merged_id = $yesno[$wstudylinesetup->line_merged_id];
    $wstudylinesetup->line_name = implode(',', $lineNames[$wstudylinesetup->id]);
    $wstudylinesetup->line_code = implode(',', $lineCode[$wstudylinesetup->id]);
    $wstudylinesetup->line_floor = implode(',', $lineFloor[$wstudylinesetup->id]);

    return $wstudylinesetup;
   });

  echo json_encode($wstudylinesetup);
 }
}
