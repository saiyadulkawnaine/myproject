<?php

namespace App\Http\Controllers\Subcontract\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingPoItemRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbMarketingRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Purchase\PoDyeingServiceRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingPoRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRefRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;

use App\Library\Template;
use App\Http\Requests\Subcontract\Dyeing\SoDyeingRequest;

class SoDyeingController extends Controller
{

 private $sodyeing;
 private $sodyeingpoitem;
 private $subinbmarketing;
 private $company;
 private $buyer;
 private $uom;
 private $gmtspart;
 private $podyeingservice;
 private $podyeingpo;
 private $podyeingref;
 private $currency;
 private $colorrange;
 private $color;
 private $teammember;

 public function __construct(
  SoDyeingRepository $sodyeing,
  SoDyeingPoItemRepository $sodyeingpoitem,
  BuyerRepository $buyer,
  CompanyRepository $company,
  UomRepository $uom,
  SubInbMarketingRepository $subinbmarketing,
  GmtspartRepository $gmtspart,
  PoDyeingServiceRepository $podyeingservice,
  SoDyeingPoRepository $podyeingpo,
  SoDyeingRefRepository $podyeingref,
  CurrencyRepository $currency,
  ColorrangeRepository $colorrange,
  ColorRepository $color,
  TeammemberRepository $teammember
 ) {
  $this->sodyeing = $sodyeing;
  $this->sodyeingpoitem = $sodyeingpoitem;
  $this->subinbmarketing = $subinbmarketing;
  $this->company = $company;
  $this->buyer = $buyer;
  $this->uom = $uom;
  $this->gmtspart = $gmtspart;
  $this->podyeingservice = $podyeingservice;
  $this->podyeingpo = $podyeingpo;
  $this->podyeingref = $podyeingref;
  $this->currency = $currency;
  $this->colorrange = $colorrange;
  $this->color = $color;
  $this->teammember = $teammember;

  $this->middleware('auth');
  $this->middleware('permission:view.sodyeings',   ['only' => ['create', 'index', 'show']]);
  $this->middleware('permission:create.sodyeings', ['only' => ['store']]);
  $this->middleware('permission:edit.sodyeings',   ['only' => ['update']]);
  $this->middleware('permission:delete.sodyeings', ['only' => ['destroy']]);
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
  return response()->json(
   $this->sodyeing
    ->leftJoin('buyers', function ($join) {
     $join->on('so_dyeings.buyer_id', '=', 'buyers.id');
    })
    ->leftJoin('companies', function ($join) {
     $join->on('so_dyeings.company_id', '=', 'companies.id');
    })
    ->leftJoin('currencies', function ($join) {
     $join->on('currencies.id', '=', 'so_dyeings.currency_id');
    })
    ->leftJoin('sub_inb_marketings', function ($join) {
     $join->on('so_dyeings.sub_inb_marketing_id', '=', 'sub_inb_marketings.id');
    })
    ->leftJoin('teammembers', function ($join) {
     $join->on('teammembers.id', '=', 'so_dyeings.teammember_id');
    })
    ->leftJoin('users', function ($join) {
     $join->on('teammembers.user_id', '=', 'users.id');
    })
    ->orderBy('so_dyeings.id', 'desc')
    ->take(500)
    ->get([
     'so_dyeings.*',
     'buyers.name as buyer_name',
     'companies.name as company_name',
     'currencies.name as currency_name',
     'users.name as teammember_name'
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
  $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '', '');
  $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
  $fabricshape = array_prepend(config('bprs.fabricshape'), '-Select-', '');
  $gmtspart = array_prepend(array_pluck($this->gmtspart->get(), 'name', 'id'), '-Select-', '');
  $currency = array_prepend(array_pluck($this->currency->get(), 'name', 'id'), '-Select-', '');
  $colorrange = array_prepend(array_pluck($this->colorrange->get(), 'name', 'id'), '-Select-', '');
  $color = array_prepend(array_pluck($this->color->get(), 'name', 'id'), '-Select-', '');
  $dyetype = array_prepend(config('bprs.dyetype'), '-Select-', '');
  $team = $this->teammember
   ->leftJoin('teams', function ($join) {
    $join->on('teammembers.team_id', '=', 'teams.id');
   })
   ->leftJoin('users', function ($join) {
    $join->on('teammembers.user_id', '=', 'users.id');
   })
   ->get([
    'teammembers.id',
    'users.name',
    'teams.name as team_name',
   ])
   ->map(function ($team) {
    $team->name = $team->name . " (" . $team->team_name . " )";
    return $team;
   });

  $teammember = array_prepend(array_pluck($team, 'name', 'id'), '-Select-', 0);

  return Template::LoadView('Subcontract.Dyeing.SoDyeing', ['company' => $company, 'buyer' => $buyer, 'uom' => $uom, 'fabriclooks' => $fabriclooks, 'fabricshape' => $fabricshape, 'gmtspart' => $gmtspart, 'currency' => $currency, 'colorrange' => $colorrange, 'color' => $color, 'dyetype' => $dyetype, 'teammember' => $teammember]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(SoDyeingRequest $request)
 {
  $sodyeing = $this->sodyeing->create($request->except(['id', 'po_dyeing_service_id']));
  if ($request->po_dyeing_service_id) {
   $podyeingservice = $this->podyeingservice->find($request->po_dyeing_service_id);
   $this->sodyeing->update($sodyeing->id, ['currency_id' => $podyeingservice->currency_id, 'exch_rate' => $podyeingservice->exch_rate]);

   $this->podyeingpo->create([
    'so_dyeing_id' => $sodyeing->id, 'po_dyeing_service_id' => $request->po_dyeing_service_id
   ]);
   $podyeingserviceitems = $this->podyeingservice
    ->join('po_dyeing_service_items', function ($join) {
     $join->on('po_dyeing_service_items.po_dyeing_service_id', '=', 'po_dyeing_services.id')
      ->whereNull('po_dyeing_service_items.deleted_at');
    })
    ->join('po_dyeing_service_item_qties', function ($join) {
     $join->on('po_dyeing_service_item_qties.po_dyeing_service_item_id', '=', 'po_dyeing_service_items.id');
    })
    ->where([['po_dyeing_services.id', '=', $request->po_dyeing_service_id]])
    ->get(['po_dyeing_service_item_qties.id as po_dyeing_service_item_qty_id']);

   foreach ($podyeingserviceitems as $podyeingserviceitem) {
    $podyeingref = $this->podyeingref->create(['so_dyeing_id' => $sodyeing->id]);

    $sodyeingpoitem = $this->sodyeingpoitem->create(['so_dyeing_ref_id' => $podyeingref->id, 'po_dyeing_service_item_qty_id' => $podyeingserviceitem->po_dyeing_service_item_qty_id]);
   }
  }
  if ($sodyeing) {
   return response()->json(array('success' => true, 'id' =>  $sodyeing->id, 'message' => 'Save Successfully'), 200);
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
  $sodyeing = $this->sodyeing
   ->leftJoin('so_dyeing_pos', function ($join) {
    $join->on('so_dyeing_pos.so_dyeing_id', '=', 'so_dyeings.id');
   })
   ->where([['so_dyeings.id', '=', $id]])
   ->get([
    'so_dyeings.*',
    'so_dyeing_pos.po_dyeing_service_id',
   ])
   ->first();

  $row['fromData'] = $sodyeing;
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
 public function update(SoDyeingRequest $request, $id)
 {
  if ($request->po_dyeing_service_id) {
   $sodyeing = $this->sodyeing->update($id, $request->except(['id', 'po_dyeing_service_id', 'currency_id', 'exch_rate']));
  } else {
   $sodyeing = $this->sodyeing->update($id, $request->except(['id', 'po_dyeing_service_id']));
  }
  if ($sodyeing) {
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
  if ($this->sodyeing->delete($id)) {
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
  $podyeingservice = $this->podyeingservice
   ->leftJoin('companies', function ($join) {
    $join->on('companies.id', '=', 'po_dyeing_services.company_id');
   })
   ->leftJoin('currencies', function ($join) {
    $join->on('currencies.id', '=', 'po_dyeing_services.currency_id');
   })
   ->leftJoin('so_dyeing_pos', function ($join) {
    $join->on('so_dyeing_pos.po_dyeing_service_id', '=', 'po_dyeing_services.id');
   })
   ->when(request('po_no'), function ($q) {
    return $q->where('po_dyeing_services.po_no', 'LIKE', "%" . request('po_no', 0) . "%");
   })
   ->whereNotNull('po_dyeing_services.approved_at')
   ->get([
    'po_dyeing_services.*',
    'companies.name as company_name',
    'currencies.code as currency_code',
    'so_dyeing_pos.po_dyeing_service_id'
   ]);
  $data = $podyeingservice->filter(function ($podyeingservice) {
   if (!$podyeingservice->po_dyeing_service_id) {
    return $podyeingservice;
   }
  })->values();

  return response()->json($data);
 }

 public function getTeammember()
 {
  $buyer_id = request('buyer_id', 0);
  $results = collect(
   \DB::select("
        select 
        teammembers.id,
        users.name
        from buyers
        left join teams on teams.id=buyers.team_id
        left join teammembers on teammembers.team_id=teams.id
        left join users on users.id=teammembers.user_id
        where 
        buyers.id = ?
        ", [$buyer_id])
  );
  echo json_encode($results);
 }

 public function getSoDyeingList()
 {
  $sodyeing = $this->sodyeing
   ->leftJoin('buyers', function ($join) {
    $join->on('so_dyeings.buyer_id', '=', 'buyers.id');
   })
   ->leftJoin('companies', function ($join) {
    $join->on('so_dyeings.company_id', '=', 'companies.id');
   })
   ->leftJoin('currencies', function ($join) {
    $join->on('currencies.id', '=', 'so_dyeings.currency_id');
   })
   ->leftJoin('sub_inb_marketings', function ($join) {
    $join->on('so_dyeings.sub_inb_marketing_id', '=', 'sub_inb_marketings.id');
   })
   ->leftJoin('teammembers', function ($join) {
    $join->on('teammembers.id', '=', 'so_dyeings.teammember_id');
   })
   ->leftJoin('users', function ($join) {
    $join->on('teammembers.user_id', '=', 'users.id');
   })
   ->orderBy('so_dyeings.id', 'desc')
   ->when(request('customer_id'), function ($q) {
    return $q->where('so_dyeings.buyer_id', '=', request('customer_id', 0));
   })
   ->when(request('from_receive_date'), function ($q) {
    return $q->where('so_dyeings.receive_date', '>=', request('from_receive_date', 0));
   })
   ->when(request('to_receive_date'), function ($q) {
    return $q->where('so_dyeings.receive_date', '<=', request('to_receive_date', 0));
   })
   ->orderBy('so_dyeings.id', 'desc')
   ->get([
    'so_dyeings.*',
    'buyers.name as buyer_name',
    'companies.name as company_name',
    'currencies.name as currency_name',
    'users.name as teammember_name'
   ])
   ->map(function ($sodyeing) {
    $sodyeing->receive_date = date('Y-m-d', strtotime($sodyeing->receive_date));
    return $sodyeing;
   });
  echo json_encode($sodyeing);
 }
}
