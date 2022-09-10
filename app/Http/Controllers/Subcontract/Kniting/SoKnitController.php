<?php

namespace App\Http\Controllers\Subcontract\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitPoItemRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbMarketingRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Purchase\PoKnitServiceRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitPoRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRefRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Kniting\SoKnitRequest;

class SoKnitController extends Controller
{

  private $soknit;
  private $soknitpoitem;
  private $subinbmarketing;
  private $company;
  private $buyer;
  private $uom;
  private $gmtspart;
  private $poknitservice;
  private $poknitpo;
  private $poknitref;
  private $currency;

  public function __construct(
    SoKnitRepository $soknit,
    SoKnitPoItemRepository $soknitpoitem,
    BuyerRepository $buyer,
    CompanyRepository $company,
    UomRepository $uom,
    SubInbMarketingRepository $subinbmarketing,
    GmtspartRepository $gmtspart,
    PoKnitServiceRepository $poknitservice,
    SoKnitPoRepository $poknitpo,
    SoKnitRefRepository $poknitref,
    CurrencyRepository $currency
  ) {
    $this->soknit = $soknit;
    $this->soknitpoitem = $soknitpoitem;
    $this->subinbmarketing = $subinbmarketing;
    $this->company = $company;
    $this->buyer = $buyer;
    $this->uom = $uom;
    $this->gmtspart = $gmtspart;
    $this->poknitservice = $poknitservice;
    $this->poknitpo = $poknitpo;
    $this->poknitref = $poknitref;
    $this->currency = $currency;

    $this->middleware('auth');
    $this->middleware('permission:view.soknits',   ['only' => ['create', 'index', 'show']]);
    $this->middleware('permission:create.soknits', ['only' => ['store']]);
    $this->middleware('permission:edit.soknits',   ['only' => ['update']]);
    $this->middleware('permission:delete.soknits', ['only' => ['destroy']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return response()->json(
      $this->soknit
        ->leftJoin('buyers', function ($join) {
          $join->on('so_knits.buyer_id', '=', 'buyers.id');
        })
        ->leftJoin('companies', function ($join) {
          $join->on('so_knits.company_id', '=', 'companies.id');
        })
        ->leftJoin('sub_inb_marketings', function ($join) {
          $join->on('so_knits.sub_inb_marketing_id', '=', 'sub_inb_marketings.id');
        })
        ->leftJoin('currencies', function ($join) {
          $join->on('so_knits.currency_id', '=', 'currencies.id');
        })
        ->orderBy('so_knits.id', 'desc')
        ->take(500)
        ->get([
          'so_knits.*',
          'buyers.name as buyer_name',
          'companies.name as company_name',
          'currencies.code as currency_code'
        ])
        ->map(function ($rows) {
          $rows->receive_date = date('d-M-Y', strtotime($rows->receive_date));
          return $rows;
        })
    );
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request)
  {
    $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
    $uom = array_prepend(array_pluck($this->uom->get(), 'code', 'id'), '', '');
    $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '-Select-', '');
    $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
    $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
    $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');
    $currency = array_prepend(array_pluck($this->currency->get(), 'name', 'id'), '-Select-', '');
    return Template::LoadView('Subcontract.Kniting.SoKnit', ['company' => $company, 'buyer' => $buyer, 'uom' => $uom, 'fabriclooks' => $fabriclooks, 'fabricshape' => $fabricshape, 'gmtspart' => $gmtspart, 'currency' => $currency]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(SoKnitRequest $request)
  {
    $soknit = $this->soknit->create($request->except(['id', 'po_knit_service_id']));
    if ($request->po_knit_service_id) {
      $poknitservice = $this->poknitservice->find($request->po_knit_service_id);
      $this->soknit->update($soknit->id, ['currency_id' => $poknitservice->currency_id, 'exch_rate' => $poknitservice->exch_rate]);

      $this->poknitpo->create(['so_knit_id' => $soknit->id, 'po_knit_service_id' => $request->po_knit_service_id]);


      $poknitserviceitems = $this->poknitservice
        ->join('po_knit_service_items', function ($join) {
          $join->on('po_knit_service_items.po_knit_service_id', '=', 'po_knit_services.id')
            ->whereNull('po_knit_service_items.deleted_at');
        })
        ->join('po_knit_service_item_qties', function ($join) {
          $join->on('po_knit_service_item_qties.po_knit_service_item_id', '=', 'po_knit_service_items.id');
        })
        ->where([['po_knit_services.id', '=', $request->po_knit_service_id]])
        ->get(['po_knit_service_item_qties.id as po_knit_service_item_qty_id']);

      foreach ($poknitserviceitems as $poknitserviceitem) {
        $poknitref = $this->poknitref->create(['so_knit_id' => $soknit->id]);

        $soknitpoitem = $this->soknitpoitem->create(['so_knit_ref_id' => $poknitref->id, 'po_knit_service_item_qty_id' => $poknitserviceitem->po_knit_service_item_qty_id]);
      }
    }
    if ($soknit) {
      return response()->json(array('success' => true, 'id' =>  $soknit->id, 'message' => 'Save Successfully'), 200);
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
    $soknit = $this->soknit
      ->leftJoin('so_knit_pos', function ($join) {
        $join->on('so_knit_pos.so_knit_id', '=', 'so_knits.id');
      })
      ->where([['so_knits.id', '=', $id]])
      ->get([
        'so_knits.*',
        'so_knit_pos.po_knit_service_id',
      ])
      ->first();

    $row['fromData'] = $soknit;
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
  public function update(SoKnitRequest $request, $id)
  {
    if ($request->po_knit_service_id) {
      $soknit = $this->soknit->update($id, $request->except(['id', 'po_knit_service_id', 'currency_id', 'exch_rate']));
    } else {
      $soknit = $this->soknit->update($id, $request->except(['id', 'po_knit_service_id']));
    }
    if ($soknit) {
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
    if ($this->soknit->delete($id)) {
      return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
    }
  }

  public function getMktRef()
  {
    return response()->json(
      $this->subinbmarketing
        ->leftJoin('buyers', function ($join) {
          $join->on('sub_inb_marketings.buyer_id', '=', 'buyers.id');
        })
        ->leftJoin('companies', function ($join) {
          $join->on('sub_inb_marketings.company_id', '=', 'companies.id');
        })
        ->leftJoin('teams', function ($join) {
          $join->on('sub_inb_marketings.team_id', '=', 'teams.id');
        })
        ->leftJoin('teammembers', function ($join) {
          $join->on('teammembers.id', '=', 'sub_inb_marketings.teammember_id');
        })
        ->leftJoin('users', function ($join) {
          $join->on('users.id', '=', 'teammembers.user_id');
        })
        ->when(request('company_id'), function ($q) {
          return $q->where('sub_inb_marketings.company_id', '=', request('company_id', 0));
        })
        ->when(request('production_area_id'), function ($q) {
          return $q->where('sub_inb_marketings.production_area_id', '=', request('production_area_id', 0));
        })
        ->when(request('buyer_id'), function ($q) {
          return $q->where('sub_inb_marketings.buyer_id', '=', request('buyer_id', 0));
        })
        ->when(request('mkt_date'), function ($q) {
          return $q->where('sub_inb_marketings.mkt_date', '=', request('mkt_date', 0));
        })
        ->orderBy('sub_inb_marketings.id', 'desc')
        ->get([
          'sub_inb_marketings.*',
          'buyers.name as buyer_id',
          'companies.name as company_id',
          'teams.name as team_name',
          'users.name as team_member_name'
        ])
    );
  }

  public function getPo()
  {
    $poknitservice = $this->poknitservice
      ->join('companies', function ($join) {
        $join->on('companies.id', '=', 'po_knit_services.company_id');
      })
      ->join('currencies', function ($join) {
        $join->on('currencies.id', '=', 'po_knit_services.currency_id');
      })
      ->leftJoin('so_knit_pos', function ($join) {
        $join->on('so_knit_pos.po_knit_service_id', '=', 'po_knit_services.id');
      })
      ->whereNotNull('po_knit_services.approved_at')
      ->when(request('po_no'), function ($q) {
        return $q->where('po_knit_services.po_no', 'LIKE', "%" . request('po_no', 0) . "%");
      })
      ->get([
        'po_knit_services.*',
        'companies.name as company_name',
        'currencies.code as currency_code',
        'so_knit_pos.po_knit_service_id'
      ]);
    $data = $poknitservice->filter(function ($poknitservice) {
      if (!$poknitservice->po_knit_service_id) {
        return $poknitservice;
      }
    })->values();

    return response()->json($data);
  }

  public function getSoKnit()
  {
    return response()->json(
      $this->soknit
        ->leftJoin('buyers', function ($join) {
          $join->on('so_knits.buyer_id', '=', 'buyers.id');
        })
        ->leftJoin('companies', function ($join) {
          $join->on('so_knits.company_id', '=', 'companies.id');
        })
        ->leftJoin('sub_inb_marketings', function ($join) {
          $join->on('so_knits.sub_inb_marketing_id', '=', 'sub_inb_marketings.id');
        })
        ->leftJoin('currencies', function ($join) {
          $join->on('so_knits.currency_id', '=', 'currencies.id');
        })
        ->when(request('search_buyer_id'), function ($q) {
          return $q->where('so_knits.buyer_id', '=', request('search_buyer_id', 0));
        })
        ->when(request('date_from'), function ($q) {
          return $q->where('so_knits.receive_date', '>=', request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
          return $q->where('so_knits.receive_date', '<=', request('date_to', 0));
        })
        ->orderBy('so_knits.id', 'desc')
        ->get([
          'so_knits.*',
          // 'sub_inb_marketings.id as sub_inb_marketing_id',
          'buyers.name as buyer_name',
          'companies.name as company_name',
          'currencies.code as currency_code'
        ])
        ->map(function ($rows) {
          $rows->receive_date = date('d-M-Y', strtotime($rows->receive_date));
          return $rows;
        })
    );
  }
}
