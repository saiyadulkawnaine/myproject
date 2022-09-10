<?php

namespace App\Http\Controllers\Subcontract\Embelishment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvOrderRepository;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvQtyRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvToEmbRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvPrintRepository;

use App\Library\Template;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Http\Requests\Subcontract\Embelishment\SoEmbCutpanelRcvInhRequest;

class SoEmbCutpanelRcvInhController extends Controller
{

 private $soembcutpanelrcvinh;
 private $soembcutpanelrcvinhorder;
 private $prodgmtdlvprint;
 private $prodgmtdlvtoemb;
 private $location;
 private $company;
 private $supplier;
 private $buyer;

 public function __construct(
  SoEmbCutpanelRcvRepository $soembcutpanelrcvinh,
  SoEmbCutpanelRcvOrderRepository $soembcutpanelrcvinhorder,
  LocationRepository $location,
  CompanyRepository $company,
  SupplierRepository $supplier,
  BuyerRepository $buyer,
  ProdGmtDlvPrintRepository $prodgmtdlvprint,
  ProdGmtDlvToEmbRepository $prodgmtdlvtoemb
 ) {

  $this->soembcutpanelrcvinh = $soembcutpanelrcvinh;
  $this->soembcutpanelrcvinhorder = $soembcutpanelrcvinhorder;
  $this->prodgmtdlvprint = $prodgmtdlvprint;
  $this->prodgmtdlvtoemb = $prodgmtdlvtoemb;
  $this->location = $location;
  $this->company = $company;
  $this->supplier = $supplier;
  $this->buyer = $buyer;

  $this->middleware('auth');

  /*$this->middleware('permission:view.soembcutpanelrcvinhs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.soembcutpanelrcvinhs', ['only' => ['store']]);
        $this->middleware('permission:edit.soembcutpanelrcvinhs',   ['only' => ['update']]);
        $this->middleware('permission:delete.soembcutpanelrcvinhs', ['only' => ['destroy']]);*/
 }

 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */

 public function index()
 {
  $shiftname = config('bprs.shiftname');
  $productionarea = config('bprs.productionarea');
  $soembcutpanelrcvinhs = array();
  $rows = $this->soembcutpanelrcvinh
   ->leftJoin('prod_gmt_dlv_prints', function ($join) {
    $join->on('prod_gmt_dlv_prints.id', '=', 'so_emb_cutpanel_rcvs.prod_gmt_party_challan_id');
   })
   ->leftJoin('prod_gmt_dlv_to_embs', function ($join) {
    $join->on('prod_gmt_dlv_to_embs.id', '=', 'so_emb_cutpanel_rcvs.prod_gmt_party_challan_id');
   })
   ->leftJoin('buyers', function ($join) {
    $join->on('buyers.id', '=', 'so_emb_cutpanel_rcvs.buyer_id');
   })
   ->where([['so_emb_cutpanel_rcvs.is_self', '=', 0]])
   ->orderBy('so_emb_cutpanel_rcvs.id', 'desc')
   ->get([
    'so_emb_cutpanel_rcvs.*',
    'prod_gmt_dlv_to_embs.challan_no as emb_challan_no',
    'prod_gmt_dlv_prints.challan_no as print_challan_no',
    'buyers.name as buyer_name'
   ]);
  foreach ($rows as $row) {
   $soembcutpanelrcvinh['id'] = $row->id;
   $soembcutpanelrcvinh['buyer_name'] = $row->buyer_name;
   $soembcutpanelrcvinh['party_challan_no'] = $row->print_challan_no ? $row->print_challan_no : $row->emb_challan_no;
   $soembcutpanelrcvinh['receive_date'] = date('Y-m-d', strtotime($row->receive_date));
   $soembcutpanelrcvinh['shift_id'] = isset($shiftname[$row->shift_id]) ? $shiftname[$row->shift_id] : '';
   $soembcutpanelrcvinh['remarks'] = $row->remarks;

   array_push($soembcutpanelrcvinhs, $soembcutpanelrcvinh);
  }
  echo json_encode($soembcutpanelrcvinhs);
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create(Request $request)
 {
  $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
  $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
  $buyer = array_prepend(array_pluck($this->buyer->get(), 'name', 'id'), '', '');
  $supplier = array_prepend(array_pluck($this->supplier->embellishmentSubcontractor(), 'name', 'id'), '', '');
  $fabriclooks = array_prepend(config('bprs.fabriclooks'), '-Select-', '');
  $years = array_prepend(config('bprs.years'), '-Select-', '');
  $productionsource = array_prepend(config('bprs.productionsource'), '-Select-', '');
  $shiftname = array_prepend(config('bprs.shiftname'), '-Select-', '');
  $productionarea = array_prepend(array_only(config('bprs.productionarea'), [45, 50]), '-Select-', '');

  return Template::loadView('Subcontract.Embelishment.SoEmbCutpanelRcvInh', ['location' => $location, 'productionsource' => $productionsource, 'shiftname' => $shiftname, 'company' => $company, 'fabriclooks' => $fabriclooks, 'supplier' => $supplier, 'years' => $years, 'buyer' => $buyer, 'productionarea' => $productionarea]);
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */

 public function store(SoEmbCutpanelRcvInhRequest $request)
 {
  $soembcutpanelrcvinh = $this->soembcutpanelrcvinh->create([
   'production_area_id' => $request->production_area_id,
   'prod_gmt_party_challan_id' => $request->prod_gmt_party_challan_id,
   'receive_date' => $request->receive_date,
   'shift_id' => $request->shift_id,
   'buyer_id' => $request->buyer_id,
   'is_self' => 0,
   'remarks' => $request->remarks
  ]);

  if ($soembcutpanelrcvinh) {
   return response()->json(array('success' => true, 'id' =>  $soembcutpanelrcvinh->id,/*  'receive_no' => $receive_no , */ 'message' => 'Save Successfully'), 200);
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
  $cutpanelrcv = $this->soembcutpanelrcvinh->find($id);
  if ($cutpanelrcv->production_area_id == 45) {
   $soembcutpanelrcvinh = $this->soembcutpanelrcvinh
    ->leftJoin('prod_gmt_dlv_prints', function ($join) {
     $join->on('prod_gmt_dlv_prints.id', '=', 'so_emb_cutpanel_rcvs.prod_gmt_party_challan_id');
    })
    ->leftJoin('companies', function ($join) {
     $join->on('prod_gmt_dlv_prints.produced_company_id', '=', 'companies.id');
    })
    ->leftJoin('suppliers', function ($join) {
     $join->on('prod_gmt_dlv_prints.supplier_id', '=', 'suppliers.id');
    })
    ->leftJoin('locations', function ($join) {
     $join->on('prod_gmt_dlv_prints.location_id', '=', 'locations.id');
    })
    ->where([['so_emb_cutpanel_rcvs.id', '=', $id]])
    ->get([
     'so_emb_cutpanel_rcvs.*',
     'prod_gmt_dlv_prints.challan_no as party_challan_no',
     'suppliers.name as supplier_name',
     'companies.name as company_name'
    ])
    ->first();
   $row['fromData'] = $soembcutpanelrcvinh;
   $dropdown['att'] = '';
   $row['dropDown'] = $dropdown;
   echo json_encode($row);
  }
  if ($cutpanelrcv->production_area_id == 50) {
   $soembcutpanelrcvinh = $this->soembcutpanelrcvinh
    ->leftJoin('prod_gmt_dlv_to_embs', function ($join) {
     $join->on('prod_gmt_dlv_to_embs.id', '=', 'so_emb_cutpanel_rcvs.prod_gmt_party_challan_id');
    })
    ->leftJoin('companies', function ($join) {
     $join->on('prod_gmt_dlv_to_embs.produced_company_id', '=', 'companies.id');
    })
    ->leftJoin('suppliers', function ($join) {
     $join->on('prod_gmt_dlv_to_embs.supplier_id', '=', 'suppliers.id');
    })
    ->leftJoin('locations', function ($join) {
     $join->on('prod_gmt_dlv_to_embs.location_id', '=', 'locations.id');
    })
    ->where([['so_emb_cutpanel_rcvs.id', '=', $id]])
    ->get([
     'so_emb_cutpanel_rcvs.*',
     'prod_gmt_dlv_to_embs.challan_no as party_challan_no',
     'suppliers.name as supplier_name',
     'companies.name as company_name'
    ])
    ->first();
   $row['fromData'] = $soembcutpanelrcvinh;
   $dropdown['att'] = '';
   $row['dropDown'] = $dropdown;
   echo json_encode($row);
  }
 }

 /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function update(SoEmbCutpanelRcvInhRequest $request, $id)
 {
  $soembcutpanelrcvinh = $this->soembcutpanelrcvinh->update($id, $request->except(['id', 'party_challan_no', 'prod_gmt_party_challan_id', 'production_area_id', 'supplier_name']));
  if ($soembcutpanelrcvinh) {
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
  if ($this->soembcutpanelrcvinh->delete($id)) {
   return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
  }
 }

 // from prodgmtdlvtoemb and prodgmtdlvprint
 public function getPartyChallan()
 {
  $shift = array_prepend(config('bprs.shiftname'), '-Select-', '');
  $production_area_id = request('production_area_id', 0);
  if ($production_area_id == 45) {
   $rows = $this->prodgmtdlvprint
    ->selectRaw('
                prod_gmt_dlv_prints.id,
                prod_gmt_dlv_prints.challan_no,
                prod_gmt_dlv_prints.shiftname_id,
                prod_gmt_dlv_prints.delivery_date,
                companies.name as company_name,
                suppliers.name as supplier_name,
                locations.name as location_name
            ')
    ->join('prod_gmt_dlv_print_orders', function ($join) {
     $join->on('prod_gmt_dlv_print_orders.prod_gmt_dlv_print_id', '=', 'prod_gmt_dlv_prints.id');
    })
    ->leftJoin('companies', function ($join) {
     $join->on('prod_gmt_dlv_prints.produced_company_id', '=', 'companies.id');
    })
    ->leftJoin('suppliers', function ($join) {
     $join->on('prod_gmt_dlv_prints.supplier_id', '=', 'suppliers.id');
    })
    ->leftJoin('locations', function ($join) {
     $join->on('prod_gmt_dlv_prints.location_id', '=', 'locations.id');
    })
    ->when(request('supplier_id'), function ($q) {
     return $q->where('prod_gmt_dlv_prints.supplier_id', '=', request('supplier_id', 0));
    })
    ->when(request('delivery_date'), function ($q) {
     return $q->where('prod_gmt_dlv_prints.delivery_date', '=', request('delivery_date', 0));
    })
    ->orderBy('prod_gmt_dlv_prints.id', 'desc')
    ->groupBy([
     'prod_gmt_dlv_prints.id',
     'prod_gmt_dlv_prints.challan_no',
     'prod_gmt_dlv_prints.shiftname_id',
     'prod_gmt_dlv_prints.delivery_date',
     'companies.name',
     'suppliers.name',
     'locations.name'
    ])
    ->get()
    ->map(function ($rows) use ($shift) {
     $rows->shift_name = $shift[$rows->shiftname_id];
     return $rows;
    });
   echo json_encode($rows);
  }
  if ($production_area_id == 50) {
   $rows = $this->prodgmtdlvtoemb
    ->selectRaw('
                prod_gmt_dlv_to_embs.id,
                prod_gmt_dlv_to_embs.challan_no,
                prod_gmt_dlv_to_embs.shiftname_id,
                prod_gmt_dlv_to_embs.delivery_date,
                companies.name as company_name,
                suppliers.name as supplier_name,
                locations.name as location_name
            ')
    ->join('prod_gmt_dlv_to_emb_orders', function ($join) {
     $join->on('prod_gmt_dlv_to_emb_orders.prod_gmt_dlv_to_emb_id', '=', 'prod_gmt_dlv_to_embs.id');
    })
    ->leftJoin('companies', function ($join) {
     $join->on('prod_gmt_dlv_to_embs.produced_company_id', '=', 'companies.id');
    })
    ->leftJoin('suppliers', function ($join) {
     $join->on('prod_gmt_dlv_to_embs.supplier_id', '=', 'suppliers.id');
    })
    ->leftJoin('locations', function ($join) {
     $join->on('prod_gmt_dlv_to_embs.location_id', '=', 'locations.id');
    })
    ->when(request('supplier_id'), function ($q) {
     return $q->where('prod_gmt_dlv_to_embs.supplier_id', '=', request('supplier_id', 0));
    })
    ->when(request('delivery_date'), function ($q) {
     return $q->where('prod_gmt_dlv_to_embs.delivery_date', '=', request('delivery_date', 0));
    })
    ->orderBy('prod_gmt_dlv_to_embs.id', 'desc')
    ->groupBy([
     'prod_gmt_dlv_to_embs.id',
     'prod_gmt_dlv_to_embs.challan_no',
     'prod_gmt_dlv_to_embs.shiftname_id',
     'prod_gmt_dlv_to_embs.delivery_date',
     'companies.name',
     'suppliers.name',
     'locations.name'
    ])
    ->get()
    ->map(function ($rows) use ($shift) {
     $rows->shift_name = $shift[$rows->shiftname_id];
     return $rows;
    });
   echo json_encode($rows);
  }
 }
}
