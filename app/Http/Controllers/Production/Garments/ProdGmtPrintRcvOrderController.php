<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtPrintRcvRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtPrintRcvOrderRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtPrintRcvQtyRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;


use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtPrintRcvOrderRequest;


class ProdGmtPrintRcvOrderController extends Controller
{

  private $prodgmtprintrcvorder;
  private $prodgmtprintreceive;
  private $location;
  private $gmtprintreceiveqty;

  public function __construct(ProdGmtPrintRcvOrderRepository $prodgmtprintrcvorder, ProdGmtPrintRcvRepository $prodgmtprintreceive, LocationRepository $location, SalesOrderCountryRepository $salesordercountry, WstudyLineSetupRepository $wstudylinesetup, ProdGmtPrintRcvQtyRepository $gmtprintreceiveqty, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize)
  {
    $this->prodgmtprintrcvorder = $prodgmtprintrcvorder;
    $this->prodgmtprintreceive = $prodgmtprintreceive;
    $this->gmtprintreceiveqty = $gmtprintreceiveqty;
    $this->salesordercountry = $salesordercountry;
    $this->location = $location;
    $this->wstudylinesetup = $wstudylinesetup;
    $this->salesordergmtcolorsize = $salesordergmtcolorsize;

    $this->middleware('auth');
    /*$this->middleware('permission:view.prodgmtprodgmtprintrcvorders',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtprodgmtprintrcvorders', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtprodgmtprintrcvorders',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtprodgmtprintrcvorders', ['only' => ['destroy']]);*/
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');

    $prodgmtprintrcvorders = array();
    $rows = $this->prodgmtprintrcvorder
      ->selectRaw('
            prod_gmt_print_rcv_orders.id,
            sales_order_countries.id as sales_order_country_id,
            sales_orders.sale_order_no,
            sales_orders.ship_date,
            styles.style_ref,
            styles.id as style_id,
            jobs.job_no,
            jobs.company_id,
            buyers.code as buyer_name,
            countries.name as country_id,
            companies.name as company_id
        ')
      ->leftJoin('prod_gmt_print_rcvs', function ($join) {
        $join->on('prod_gmt_print_rcvs.id', '=', 'prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id');
      })
      ->leftJoin('sales_order_countries', function ($join) {
        $join->on('sales_order_countries.id', '=', 'prod_gmt_print_rcv_orders.sales_order_country_id');
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
      ->where([['prod_gmt_print_rcv_id', '=', request('prod_gmt_print_rcv_id', 0)]])
      ->orderBy('prod_gmt_print_rcv_orders.id', 'desc')
      ->get([
        'prod_gmt_print_rcv_orders.*',
        'sales_orders.sale_order_no'
      ]);
    foreach ($rows as $row) {
      $prodgmtprintrcvorder['id'] = $row->id;
      $prodgmtprintrcvorder['sales_order_country_id'] = $row->sales_order_country_id;
      $prodgmtprintrcvorder['sale_order_no'] = $row->sale_order_no;
      $prodgmtprintrcvorder['country_id'] = $row->country_id;
      $prodgmtprintrcvorder['buyer_name'] = $row->buyer_name;
      $prodgmtprintrcvorder['style_ref'] = $row->style_ref;
      $prodgmtprintrcvorder['job_no'] = $row->job_no;
      $prodgmtprintrcvorder['ship_date'] = $row->ship_date;
      $prodgmtprintrcvorder['fabric_look_id'] = $fabriclooks[$row->fabric_look_id];
      array_push($prodgmtprintrcvorders, $prodgmtprintrcvorder);
    }
    echo json_encode($prodgmtprintrcvorders);
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
  public function store(ProdGmtPrintRcvOrderRequest $request)
  {
    $prodgmtprintrcvorder = $this->prodgmtprintrcvorder->create([
      'prod_gmt_print_rcv_id' => $request->prod_gmt_print_rcv_id,
      'sales_order_country_id' => $request->sales_order_country_id,
      'fabric_look_id' => $request->fabric_look_id,
      'supplier_id' => $request->supplier_id,
      'location_id' => $request->location_id,
      'asset_quantity_cost_id' => $request->asset_quantity_cost_id,
      'receive_hour' => $request->receive_hour,
      'prod_source_id' => $request->prod_source_id,
    ]);

    if ($prodgmtprintrcvorder) {
      return response()->json(array('success' => true, 'id' =>  $prodgmtprintrcvorder->id, 'message' => 'Save Successfully'), 200);
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

    $prodgmtprintrcvorder = $this->prodgmtprintrcvorder
      ->leftJoin('prod_gmt_print_rcvs', function ($join) {
        $join->on('prod_gmt_print_rcvs.id', '=', 'prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id');
      })
      /* ->leftJoin('asset_quantity_costs', function($join)  {
            $join->on('asset_quantity_costs.id', '=', 'prod_gmt_print_rcv_orders.asset_quantity_cost_id');
        }) */
      ->leftJoin('sales_order_countries', function ($join) {
        $join->on('sales_order_countries.id', '=', 'prod_gmt_print_rcv_orders.sales_order_country_id');
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
        $join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
      })
      ->leftJoin('style_gmts', function ($join) {
        $join->on('style_gmts.id', '=', 'sales_order_gmt_color_sizes.style_gmt_id');
      })
      ->leftJoin('buyers', function ($join) {
        $join->on('buyers.id', '=', 'styles.buyer_id');
      })
      ->where([['prod_gmt_print_rcv_orders.id', '=', $id]])
      ->get([
        'prod_gmt_print_rcv_orders.*',
        'sales_order_countries.id as sales_order_country_id',
        'sales_orders.sale_order_no',
        'sales_orders.ship_date',
        'sales_orders.job_id',
        'jobs.job_no',
        'jobs.company_id',
        'styles.buyer_id',
        'styles.style_ref',
        'companies.name as company_id',
        'countries.name as country_id',
        'buyers.name as buyer_name'
      ])
      ->first();
    $gmtprintreceiveqty = $this->prodgmtprintrcvorder
      ->join('prod_gmt_print_rcvs', function ($join) {
        $join->on('prod_gmt_print_rcvs.id', '=', 'prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id');
      })
      ->join('sales_order_countries', function ($join) {
        $join->on('sales_order_countries.id', '=', 'prod_gmt_print_rcv_orders.sales_order_country_id');
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
      ->join('budget_Embs', function ($join) {
        $join->on('budgets.id', '=', 'budget_Embs.budget_id');
      })
      ->join('companies', function ($join) {
        $join->on('companies.id', '=', 'jobs.company_id');
      })
      ->join('styles', function ($join) {
        $join->on('styles.id', '=', 'jobs.style_id');
      })
      ->join('sales_order_gmt_color_sizes', function ($join) {
        $join->on('sales_order_gmt_color_sizes.sale_order_country_id', '=', 'sales_order_countries.id');
        //->whereNull('sales_order_gmt_color_sizes.deleted_at'); 
      })
      ->join('style_gmt_color_sizes', function ($join) {
        $join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
      })
      ->join('style_gmts', function ($join) {
        $join->on('style_gmts.id', '=', 'style_gmt_color_sizes.style_gmt_id');
      })
      ->join('item_accounts', function ($join) {
        $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
      })
      ->join('style_colors', function ($join) {
        $join->on('style_colors.id', '=', 'style_gmt_color_sizes.style_color_id');
      })
      ->join('colors', function ($join) {
        $join->on('colors.id', '=', 'style_colors.color_id');
      })
      ->join('style_sizes', function ($join) {
        $join->on('style_sizes.id', '=', 'style_gmt_color_sizes.style_size_id');
      })
      ->join('sizes', function ($join) {
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
      ->leftJoin('prod_gmt_print_rcv_qties', function ($join) {
        $join->on('prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id', '=', 'prod_gmt_print_rcv_orders.id');
        $join->on('prod_gmt_print_rcv_qties.sales_order_gmt_color_size_id', '=', 'sales_order_gmt_color_sizes.id');
      })

      ->leftJoin(\DB::raw("(select sales_order_gmt_color_sizes.id as        sales_order_gmt_color_size_id,
            sum(prod_gmt_print_rcv_qties.qty) as qty 
            from prod_gmt_print_rcvs 
            join prod_gmt_print_rcv_orders on prod_gmt_print_rcv_orders.prod_gmt_print_rcv_id =prod_gmt_print_rcvs.id
            join sales_order_countries on sales_order_countries.id = prod_gmt_print_rcv_orders.sales_order_country_id
            join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id 
            join prod_gmt_print_rcv_qties on prod_gmt_print_rcv_qties.prod_gmt_print_rcv_order_id = prod_gmt_print_rcv_orders.id 
            and prod_gmt_print_rcv_qties.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
            where prod_gmt_print_rcv_qties.deleted_at is null  
            group by sales_order_gmt_color_sizes.id) cumulatives"), "cumulatives.sales_order_gmt_color_size_id", "=", "sales_order_gmt_color_sizes.id")
      ->orderBy('style_colors.sort_id')
      ->orderBy('style_sizes.sort_id')
      ->where([['prod_gmt_print_rcv_orders.id', '=', $id]])
      ->where([['style_embelishments.embelishment_id', '=', 1]])
      ->get([
        'sizes.name as size_name',
        'sizes.code as size_code',
        'colors.name as color_name',
        'colors.code as color_code',
        'style_sizes.sort_id as size_sort_id',
        'style_colors.sort_id as color_sort_id',
        'sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id',
        'item_accounts.item_description',
        'prod_gmt_print_rcv_qties.id as prod_gmt_print_rcv_qty_id',
        'prod_gmt_print_rcv_qties.qty',
        'prod_gmt_print_rcv_qties.reject_qty',
        'budget_emb_cons.req_cons as req_qty',
        'cumulatives.qty as cumulative_qty'
      ])
      ->map(function ($gmtprintreceiveqty) {
        $gmtprintreceiveqty->balance_qty = $gmtprintreceiveqty->req_qty - $gmtprintreceiveqty->cumulative_qty;

        $gmtprintreceiveqty->cumulative_qty_saved = $gmtprintreceiveqty->cumulative_qty - $gmtprintreceiveqty->qty;

        $gmtprintreceiveqty->balance_qty_saved = $gmtprintreceiveqty->req_qty - $gmtprintreceiveqty->cumulative_qty_saved;

        return $gmtprintreceiveqty;
      });
    $saved = $gmtprintreceiveqty->filter(function ($value) {
      if ($value->prod_gmt_print_rcv_qty_id) {
        return $value;
      }
    });
    $new = $gmtprintreceiveqty->filter(function ($value) {
      if (!$value->prod_gmt_print_rcv_qty_id) {
        return $value;
      }
    });
    $row['fromData'] = $prodgmtprintrcvorder;
    $dropdown['printreceivegmtcosi'] = "'" . Template::loadView('Production.Garments.ProdGmtPrintRcvQtyMatrix', ['colorsizes' => $new, 'saved' => $saved]) . "'";
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
  public function update(ProdGmtPrintRcvOrderRequest $request, $id)
  {
    $prodgmtprintrcvorder = $this->prodgmtprintrcvorder->update(
      $id,
      [
        'prod_gmt_print_rcv_id' => $request->prod_gmt_print_rcv_id,
        'sales_order_country_id' => $request->sales_order_country_id,
        'fabric_look_id' => $request->fabric_look_id,
        'supplier_id' => $request->supplier_id,
        'location_id' => $request->location_id,
        'asset_quantity_cost_id' => $request->asset_quantity_cost_id,
        'receive_hour' => $request->receive_hour,
        'prod_source_id' => $request->prod_source_id,
      ]
    );
    if ($prodgmtprintrcvorder) {
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
    if ($this->prodgmtprintrcvorder->delete($id)) {
      return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
    }
  }

  public function getPrintRcvOrder()
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
        'countries.name',

      ])
      ->get()
      ->map(function ($salesordercountry) {
        return $salesordercountry;
      });
    echo json_encode($salesordercountry);
  }
}
