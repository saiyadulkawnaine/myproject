<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvPrintRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvPrintOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvPrintQtyRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Purchase\PoEmbServiceRepository;

use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtDlvPrintOrderRequest;


class ProdGmtDlvPrintOrderController extends Controller
{

  private $prodgmtdlvprintorder;
  private $prodgmtdlvprint;
  private $location;
  private $gmtdlvprintqty;
  private $poembservice;

  public function __construct(
    ProdGmtDlvPrintOrderRepository $prodgmtdlvprintorder,
    ProdGmtDlvPrintRepository $prodgmtdlvprint,
    LocationRepository $location,
    SalesOrderCountryRepository $salesordercountry,
    WstudyLineSetupRepository $wstudylinesetup,
    ProdGmtDlvPrintQtyRepository $gmtdlvprintqty,
    SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,
    PoEmbServiceRepository $poembservice
  ) {
    $this->prodgmtdlvprintorder = $prodgmtdlvprintorder;
    $this->prodgmtdlvprint = $prodgmtdlvprint;
    $this->gmtdlvprintqty = $gmtdlvprintqty;
    $this->salesordercountry = $salesordercountry;
    $this->location = $location;
    $this->wstudylinesetup = $wstudylinesetup;
    $this->salesordergmtcolorsize = $salesordergmtcolorsize;
    $this->poembservice = $poembservice;
    $this->middleware('auth');
    /*$this->middleware('permission:view.prodgmtdlvprintorders',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtdlvprintorders', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtdlvprintorders',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtdlvprintorders', ['only' => ['destroy']]);*/
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
    $prodgmtdlvprintorders = array();
    $rows = $this->prodgmtdlvprintorder
      ->selectRaw('
            prod_gmt_dlv_print_orders.id,
            sales_order_countries.id as sales_order_country_id,
            sales_orders.sale_order_no,
            sales_orders.ship_date,
            sales_orders.produced_company_id,
            styles.style_ref,
            styles.id as style_id,
            jobs.job_no,
            jobs.company_id,
            buyers.code as buyer_name,
            countries.name as country_id,
            companies.name as company_id
        ')
      ->leftJoin('prod_gmt_dlv_prints', function ($join) {
        $join->on('prod_gmt_dlv_prints.id', '=', 'prod_gmt_dlv_print_orders.prod_gmt_dlv_print_id');
      })
      ->leftJoin('sales_order_countries', function ($join) {
        $join->on('sales_order_countries.id', '=', 'prod_gmt_dlv_print_orders.sales_order_country_id');
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
      ->where([['prod_gmt_dlv_print_id', '=', request('prod_gmt_dlv_print_id', 0)]])
      ->orderBy('prod_gmt_dlv_print_orders.id', 'desc')
      ->get([
        'prod_gmt_dlv_print_orders.*',
        'sales_orders.sale_order_no',

      ]);
    foreach ($rows as $row) {
      $prodgmtdlvprintorder['id'] = $row->id;
      $prodgmtdlvprintorder['sales_order_country_id'] = $row->sales_order_country_id;
      $prodgmtdlvprintorder['sale_order_no'] = $row->sale_order_no;
      $prodgmtdlvprintorder['country_id'] = $row->country_id;
      $prodgmtdlvprintorder['buyer_name'] = $row->buyer_name;
      $prodgmtdlvprintorder['style_ref'] = $row->style_ref;
      $prodgmtdlvprintorder['job_no'] = $row->job_no;
      $prodgmtdlvprintorder['ship_date'] = $row->ship_date;
      $prodgmtdlvprintorder['fabric_look_id'] = $fabriclooks[$row->fabric_look_id];
      /*   $styleembelishment['embelishment']=	$row->embelishment_name;
			$styleembelishment['embelishment_id']=	$row->embelishment_id;
			$styleembelishment['embelishmenttype']=	$row->embelishment_type_name; */
      array_push($prodgmtdlvprintorders, $prodgmtdlvprintorder);
    }
    echo json_encode($prodgmtdlvprintorders);
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
  public function store(ProdGmtDlvPrintOrderRequest $request)
  {
    $prodgmtdlvprintorder = $this->prodgmtdlvprintorder->create([
      'prod_gmt_dlv_print_id' => $request->prod_gmt_dlv_print_id,
      'sales_order_country_id' => $request->sales_order_country_id,
      'fabric_look_id' => $request->fabric_look_id
    ]);
    if ($prodgmtdlvprintorder) {
      return response()->json(array('success' => true, 'id' =>  $prodgmtdlvprintorder->id, 'message' => 'Save Successfully'), 200);
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

    $prodgmtdlvprintorder = $this->prodgmtdlvprintorder
      ->leftJoin('prod_gmt_dlv_prints', function ($join) {
        $join->on('prod_gmt_dlv_prints.id', '=', 'prod_gmt_dlv_print_orders.prod_gmt_dlv_print_id');
      })
      ->leftJoin('sales_order_countries', function ($join) {
        $join->on('sales_order_countries.id', '=', 'prod_gmt_dlv_print_orders.sales_order_country_id');
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
      ->where([['prod_gmt_dlv_print_orders.id', '=', $id]])
      ->get([
        'prod_gmt_dlv_print_orders.*',
        'sales_orders.sale_order_no',
        'sales_orders.produced_company_id',
        'sales_orders.ship_date',
        'sales_orders.job_id',
        'jobs.job_no',
        'jobs.company_id',
        'styles.buyer_id',
        'styles.style_ref',
        'companies.name as company_id',
        'countries.name as country_id',
        'buyers.name as buyer_name',
        'produced_company.name as produced_company_name'

      ])
      ->first();

    $gmtdlvprintqty = $this->prodgmtdlvprintorder
      ->join('prod_gmt_dlv_prints', function ($join) {
        $join->on('prod_gmt_dlv_prints.id', '=', 'prod_gmt_dlv_print_orders.prod_gmt_dlv_print_id');
      })


      ->join('sales_order_countries', function ($join) {
        $join->on('sales_order_countries.id', '=', 'prod_gmt_dlv_print_orders.sales_order_country_id');
      })
      ->join('countries', function ($join) {
        $join->on('countries.id', '=', 'sales_order_countries.country_id');
      })
      ->join('sales_orders', function ($join) {
        $join->on('sales_orders.id', '=', 'sales_order_countries.sale_order_id');
      })
      ->join('jobs', function ($join) {
        $join->on('jobs.id', '=', 'sales_orders.job_id');
      })
      ->join('budgets', function ($join) {
        $join->on('jobs.id', '=', 'budgets.job_id');
      })
      ->join('budget_embs', function ($join) {
        $join->on('budgets.id', '=', 'budget_embs.budget_id');
      })
      ->join('companies', function ($join) {
        $join->on('companies.id', '=', 'jobs.company_id');
      })
      ->join('styles', function ($join) {
        $join->on('styles.id', '=', 'jobs.style_id');
      })


      ->join('prod_gmt_cutting_orders', function ($join) {
        $join->on('prod_gmt_cutting_orders.sales_order_country_id', '=', 'prod_gmt_dlv_print_orders.sales_order_country_id');
      })
      ->join('prod_gmt_cutting_qties', function ($join) {
        $join->on('prod_gmt_cutting_qties.prod_gmt_cutting_order_id', '=', 'prod_gmt_cutting_orders.id');
      })
      ->join('sales_order_gmt_color_sizes', function ($join) {
        $join->on('sales_order_gmt_color_sizes.id', '=', 'prod_gmt_cutting_qties.sales_order_gmt_color_size_id');
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
      ->join('style_embelishments', function ($join) {
        $join->on('style_embelishments.id', '=', 'budget_embs.style_embelishment_id');
        $join->on('style_embelishments.style_gmt_id', '=', 'sales_order_gmt_color_sizes.style_gmt_id');
      })
      ->join('budget_emb_cons', function ($join) {
        $join->on('budget_embs.id', '=', 'budget_emb_cons.budget_emb_id')
          ->on('sales_order_gmt_color_sizes.id', '=', 'budget_emb_cons.sales_order_gmt_color_size_id')
          ->whereNull('budget_emb_cons.deleted_at');
      })
      ->leftJoin('prod_gmt_dlv_print_qties', function ($join) {
        $join->on('prod_gmt_dlv_print_qties.prod_gmt_dlv_print_order_id', '=', 'prod_gmt_dlv_print_orders.id');
        $join->on('prod_gmt_dlv_print_qties.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
      })

      ->join(\DB::raw("(SELECT sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,sum(prod_gmt_cutting_qties.qty) as cut_qty FROM prod_gmt_cutting_qties join prod_gmt_cutting_orders on prod_gmt_cutting_orders.id =prod_gmt_cutting_qties.prod_gmt_cutting_order_id join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_cutting_qties.sales_order_gmt_color_size_id where prod_gmt_cutting_qties.deleted_at is null  group by sales_order_gmt_color_sizes.id) prodgmtcutqty"), "prodgmtcutqty.sales_order_gmt_color_size_id", "=", "sales_order_gmt_color_sizes.id")

      ->leftJoin(\DB::raw("(SELECT sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,sum(prod_gmt_dlv_print_qties.qty) as qty FROM prod_gmt_dlv_print_qties join prod_gmt_dlv_print_orders on prod_gmt_dlv_print_orders.id =prod_gmt_dlv_print_qties.prod_gmt_dlv_print_order_id join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_dlv_print_qties.sales_order_gmt_color_size_id where prod_gmt_dlv_print_qties.deleted_at is null  group by sales_order_gmt_color_sizes.id) cumulatives"), "cumulatives.sales_order_gmt_color_size_id", "=", "sales_order_gmt_color_sizes.id")
      ->orderBy('style_colors.sort_id')
      ->orderBy('style_sizes.sort_id')
      ->where([['prod_gmt_dlv_print_orders.id', '=', $id]])
      ->selectRaw('
            sizes.name as size_name,
            sizes.code as size_code,
            colors.name as color_name,
            colors.code as color_code,
            style_sizes.sort_id as size_sort_id,
            style_colors.sort_id as color_sort_id,
            sales_order_gmt_color_sizes.plan_cut_qty,
            sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,
            item_accounts.item_description,
            prod_gmt_dlv_print_qties.id as prod_gmt_dlv_print_qty_id,
            prod_gmt_dlv_print_qties.qty,
            prodgmtcutqty.cut_qty,
            cumulatives.qty as cumulative_qty,
            budget_emb_cons.req_cons as req_qty
            ')
      ->groupBy([
        'sizes.name',
        'sizes.code',
        'colors.name',
        'colors.code',
        'style_sizes.sort_id',
        'style_colors.sort_id',
        'sales_order_gmt_color_sizes.plan_cut_qty',
        'sales_order_gmt_color_sizes.id',
        'item_accounts.item_description',
        'prod_gmt_dlv_print_qties.id',
        'prod_gmt_dlv_print_qties.qty',
        'prodgmtcutqty.cut_qty',
        'cumulatives.qty',
        'budget_emb_cons.req_cons'
      ])
      ->get()
      ->map(function ($gmtdlvprintqty) {
        $gmtdlvprintqty->balance_qty = $gmtdlvprintqty->plan_cut_qty - $gmtdlvprintqty->cumulative_qty;
        $gmtdlvprintqty->cumulative_qty_saved = $gmtdlvprintqty->cumulative_qty - $gmtdlvprintqty->qty;
        $gmtdlvprintqty->balance_qty_saved = $gmtdlvprintqty->plan_cut_qty - $gmtdlvprintqty->cumulative_qty_saved;
        return $gmtdlvprintqty;
      });
    $saved = $gmtdlvprintqty->filter(function ($value) {
      if ($value->prod_gmt_dlv_print_qty_id) {
        return $value;
      }
    });
    $new = $gmtdlvprintqty->filter(function ($value) {
      if (!$value->prod_gmt_dlv_print_qty_id) {
        return $value;
      }
    });

    $row['fromData'] = $prodgmtdlvprintorder;
    $dropdown['dlvprintgmtcosi'] = "'" . Template::loadView('Production.Garments.ProdGmtDlvPrintQtyMatrix', ['colorsizes' => $new, 'saved' => $saved]) . "'";
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
  public function update(ProdGmtDlvPrintOrderRequest $request, $id)
  {
    $prodgmtdlvprintorder = $this->prodgmtdlvprintorder->update(
      $id,
      [
        'prod_gmt_dlv_print_id' => $request->prod_gmt_dlv_print_id,
        'sales_order_country_id' => $request->sales_order_country_id,
        'fabric_look_id' => $request->fabric_look_id

      ]
    );
    if ($prodgmtdlvprintorder) {
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
    if ($this->prodgmtdlvprintorder->delete($id)) {
      return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
    }
  }

  public function getdlvprintOrder()
  {
    $prodgmtdlvprint = $this->prodgmtdlvprint->find(request('prodgmtdlvprintid', 0));
    $salesordercountry = $this->poembservice
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
      ->join('po_emb_service_items', function ($join) {
        $join->on('po_emb_service_items.po_emb_service_id', '=', 'po_emb_services.id');
      })
      ->join('po_emb_service_item_qties', function ($join) {
        $join->on('po_emb_service_item_qties.po_emb_service_item_id', '=', 'po_emb_services.id');
      })
      ->join('budget_emb_cons', function ($join) {
        $join->on('budget_emb_cons.id', '=', 'po_emb_service_item_qties.budget_emb_con_id');
      })
      ->join('sales_order_gmt_color_sizes', function ($join) {
        $join->on('sales_order_gmt_color_sizes.id', '=', 'budget_emb_cons.sales_order_gmt_color_size_id');
      })
      ->join('sales_order_countries', function ($join) {
        $join->on('sales_order_countries.id', '=', 'sales_order_gmt_color_sizes.sale_order_country_id');
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
      ->join('buyers', function ($join) {
        $join->on('buyers.id', '=', 'styles.buyer_id');
      })
      ->join('countries', function ($join) {
        $join->on('countries.id', '=', 'sales_order_countries.country_id');
      })
      ->where([['sales_orders.produced_company_id', '=', $prodgmtdlvprint->produced_company_id]])
      ->where([['po_emb_services.id', '=', request('po_emb_service_id', 0)]])
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
      ->get();
    echo json_encode($salesordercountry);
  }
}
