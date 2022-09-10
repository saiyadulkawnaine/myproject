<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPoItemRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbMarketingRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Purchase\PoEmbServiceRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPoRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRefRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\EmbelishmentRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Embelishment\SoEmbRequest;

class SoEmbController extends Controller
{

 private $soemb;
 private $soembpoitem;
 private $subinbmarketing;
 private $company;
 private $buyer;
 private $uom;
 private $gmtspart;
 private $poembservice;
 private $poembpo;
 private $poembref;
 private $currency;
 private $embelishment;
 private $embelishmenttype;
 private $itemaccount;

 public function __construct(
  SoEmbRepository $soemb,
  SoEmbPoItemRepository $soembpoitem,
  BuyerRepository $buyer,
  CompanyRepository $company,
  UomRepository $uom,
  SubInbMarketingRepository $subinbmarketing,
  GmtspartRepository $gmtspart,
  PoEmbServiceRepository $poembservice,
  SoEmbPoRepository $poembpo,
  SoEmbRefRepository $poembref,
  CurrencyRepository $currency,
  EmbelishmentRepository $embelishment,
  EmbelishmentTypeRepository $embelishmenttype,
  ItemAccountRepository $itemaccount

 ) {
  $this->soemb = $soemb;
  $this->soembpoitem = $soembpoitem;
  $this->subinbmarketing = $subinbmarketing;
  $this->company = $company;
  $this->buyer = $buyer;
  $this->uom = $uom;
  $this->gmtspart = $gmtspart;
  $this->poembservice = $poembservice;
  $this->poembpo  = $poembpo;
  $this->poembref = $poembref;
  $this->currency = $currency;
  $this->embelishment = $embelishment;
  $this->embelishmenttype = $embelishmenttype;
  $this->itemaccount = $itemaccount;

  $this->middleware('auth');
  $this->middleware('permission:view.soembs',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.soembs', ['only' => ['store']]);
  $this->middleware('permission:edit.soembs',   ['only' => ['update']]);
  $this->middleware('permission:delete.soembs', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  $productionarea = array_prepend(array_only(config('bprs.productionarea'), [45, 50]), '-Select-', '');
  return response()->json(
   $this->soemb
    ->leftJoin('buyers', function ($join) {
     $join->on('so_embs.buyer_id', '=', 'buyers.id');
    })
    ->leftJoin('companies', function ($join) {
     $join->on('so_embs.company_id', '=', 'companies.id');
    })
    ->leftJoin('sub_inb_marketings', function ($join) {
     $join->on('so_embs.sub_inb_marketing_id', '=', 'sub_inb_marketings.id');
    })
    ->orderBy('so_embs.id', 'desc')
    ->get([
     'so_embs.*',
     'sub_inb_marketings.id as sub_inb_marketing_id',
     'buyers.name as buyer_id',
     'companies.name as company_id'
    ])
    ->map(function ($rows) use ($productionarea) {
     $rows->receive_date = date('d-M-Y', strtotime($rows->receive_date));
     $rows->production_area = $productionarea[$rows->production_area_id];
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
  $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '', '');
  $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
  $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
  $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');
  $currency = array_prepend(array_pluck($this->currency->get(), 'name', 'id'), '-Select-', '');
  $embelishment = array_prepend(array_pluck(
   $this->embelishment
    ->join('production_processes', function ($join) use ($request) {
     $join->on('production_processes.id', '=', 'embelishments.production_process_id');
    })
    ->whereIn('production_area_id', [45, 50])
    ->get([
     'embelishments.id',
     'production_processes.process_name as name',
    ]),
   'name',
   'id'
  ), '-Select-', '');
  $embelishmenttype = array_prepend(array_pluck($this->embelishmenttype->getEmbelishmentTypes(), 'name', 'id'), '', '');
  $embelishmentsize = array_prepend(config('bprs.embelishmentsize'), '-Select-', '');
  $itemaccount = array_prepend(array_pluck($this->itemaccount->where([['item_accounts.itemcategory_id', '=', 21]])->get(), 'item_description', 'id'), '-Select-', '');
  $productionarea = array_prepend(array_only(config('bprs.productionarea'), [45, 50]), '-Select-', '');

  return Template::LoadView('Subcontract.Embelishment.SoEmb', ['company' => $company, 'buyer' => $buyer, 'uom' => $uom, 'embelishment' => $embelishment, 'embelishmenttype' => $embelishmenttype, 'embelishmentsize' => $embelishmentsize, 'gmtspart' => $gmtspart, 'currency' => $currency, 'itemaccount' => $itemaccount, 'productionarea' => $productionarea]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(SoEmbRequest $request)
 {
  $soemb = $this->soemb->create($request->except(['id', 'po_emb_service_id']));
  if ($request->po_emb_service_id) {
   $poembservice = $this->poembservice->find($request->po_emb_service_id);
   $this->soemb->update($soemb->id, ['currency_id' => $poembservice->currency_id, 'exch_rate' => $poembservice->exch_rate]);

   $this->poembpo->create(['so_emb_id' => $soemb->id, 'po_emb_service_id' => $request->po_emb_service_id]);
   $poembserviceitems = $this->poembservice
    ->join('po_emb_service_items', function ($join) {
     $join->on('po_emb_service_items.po_emb_service_id', '=', 'po_emb_services.id')
      ->whereNull('po_emb_service_items.deleted_at');
    })
    ->join('po_emb_service_item_qties', function ($join) {
     $join->on('po_emb_service_item_qties.po_emb_service_item_id', '=', 'po_emb_service_items.id');
    })
    ->where([['po_emb_services.id', '=', $request->po_emb_service_id]])
    ->get(['po_emb_service_item_qties.id as po_emb_service_item_qty_id']);

   foreach ($poembserviceitems as $poembserviceitem) {
    $poembref = $this->poembref->create(['so_emb_id' => $soemb->id]);

    $soembpoitem = $this->soembpoitem->create(['so_emb_ref_id' => $poembref->id, 'po_emb_service_item_qty_id' => $poembserviceitem->po_emb_service_item_qty_id]);
   }
  }
  if ($soemb) {
   return response()->json(array('success' => true, 'id' =>  $soemb->id, 'message' => 'Save Successfully'), 200);
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
  $soemb = $this->soemb
   ->leftJoin('so_emb_pos', function ($join) {
    $join->on('so_emb_pos.so_emb_id', '=', 'so_embs.id');
   })
   ->where([['so_embs.id', '=', $id]])
   ->get([
    'so_embs.*',
    'so_emb_pos.po_emb_service_id',
   ])
   ->first();

  $row['fromData'] = $soemb;
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
 public function update(SoEmbRequest $request, $id)
 {
  if ($request->po_emb_service_id) {
   $soemb = $this->soemb->update($id, $request->except(['id', 'po_emb_service_id', 'currency_id', 'exch_rate', 'production_area_id']));
  } else {
   $soemb = $this->soemb->update($id, $request->except(['id', 'po_emb_service_id', 'production_area_id']));
  }
  if ($soemb) {
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
  if ($this->soemb->delete($id)) {
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
  $poembservice = $this->poembservice
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'po_emb_services.company_id');
   })
   ->leftJoin('so_emb_pos', function ($join) {
    $join->on('so_emb_pos.po_emb_service_id', '=', 'po_emb_services.id');
   })
   //->where([['po_no','=',request('po_no',0)]])
   ->where([['currency_id', '=', request('currency_id', 0)]])
   ->where([['po_emb_services.production_area_id', '=', request('production_area_id', 0)]])
   ->whereNotNull('po_emb_services.approved_by')
   ->get([
    'po_emb_services.*',
    'companies.name as company_name',
    'so_emb_pos.po_emb_service_id',
   ]);

  $data = $poembservice->filter(function ($poembservice) {
   if (!$poembservice->po_emb_service_id) {
    return $poembservice;
   }
  })->values();

  return response()->json($data);
 }
}
