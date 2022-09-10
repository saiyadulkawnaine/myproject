<?php

namespace App\Http\Controllers\Subcontract\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostQpriceRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\KeycontrolRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Dyeing\SoDyeingMktCostQpriceRequest;

class SoDyeingMktCostQpriceController extends Controller {

   
    private $sodyeingmktcostqprice;
    private $sodyeingmktcost;
    private $autoyarn;
    private $gmtspart;
    private $uom;
    private $colorrange;
    private $color;
    private $keycontrol;
    private $company;
    private $purchasetermscondition;


    public function __construct(
        SoDyeingMktCostQpriceRepository $sodyeingmktcostqprice,
        SoDyeingMktCostRepository $sodyeingmktcost,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        UomRepository $uom,
        ColorrangeRepository $colorrange,
        ColorRepository $color,
        KeycontrolRepository $keycontrol,
        CompanyRepository $company,
        PurchaseTermsConditionRepository $purchasetermscondition
    ) {
        $this->sodyeingmktcostqprice = $sodyeingmktcostqprice;
        $this->sodyeingmktcost = $sodyeingmktcost;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->keycontrol = $keycontrol;
        $this->company = $company;
        $this->purchasetermscondition = $purchasetermscondition;

        $this->middleware('auth');
      
        //$this->middleware('permission:view.sodyeingmktcostqprices',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.sodyeingmktcostqprices', ['only' => ['store']]);
        //$this->middleware('permission:edit.sodyeingmktcostqprices',   ['only' => ['update']]);
        //$this->middleware('permission:delete.sodyeingmktcostqprices', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
        $sodyeingmktcostqprices=array();
        $rows=$this->sodyeingmktcostqprice
        ->where([['so_dyeing_mkt_cost_id','=',request('so_dyeing_mkt_cost_id',0)]])
        ->orderBy('so_dyeing_mkt_cost_qprices.id','desc')
        ->get();

        foreach($rows as $row){
            $sodyeingmktcostqprice['id']=$row->id;
            $sodyeingmktcostqprice['qprice_date']=date('Y-m-d',strtotime($row->qprice_date));
            $sodyeingmktcostqprice['qprice_no']=$row->qprice_no;
            $sodyeingmktcostqprice['ready_to_approve']=$yesno[$row->ready_to_approve_id];
            $sodyeingmktcostqprice['remarks']=$row->remarks;
            array_push($sodyeingmktcostqprices,$sodyeingmktcostqprice);
        }

        echo json_encode($sodyeingmktcostqprices);
      
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoDyeingMktCostQpriceRequest $request) {
        $max=$this->sodyeingmktcostqprice
        ->max('qprice_no');
        $qprice_no=$max+1;
        $request->request->add(['qprice_no' => $qprice_no]);
        $sodyeingmktcostqprice=$this->sodyeingmktcostqprice->create($request->except(['id']));

        if($sodyeingmktcostqprice){
        return response()->json(array('success' => true,'id' =>  $sodyeingmktcostqprice->id,'so_dyeing_mkt_cost_id' =>  $request->so_dyeing_mkt_cost_id,'message' => 'Save Successfully'),200);
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
        $rows=$this->sodyeingmktcostqprice->find($id);

        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $autoyarn=$this->autoyarn
        ->join('autoyarnratios', function($join)  {
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
            $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }
        $sodyeingmktcostqpricedtl=$this->sodyeingmktcostqprice
        ->selectRaw('
            so_dyeing_mkt_costs.exch_rate,
            so_dyeing_mkt_cost_fabs.id as so_dyeing_mkt_cost_fab_id,
            so_dyeing_mkt_cost_fabs.autoyarn_id,
            so_dyeing_mkt_cost_fabs.liqure_ratio,
            so_dyeing_mkt_cost_fabs.liqure_wgt,
            so_dyeing_mkt_cost_fabs.fabric_wgt,
            so_dyeing_mkt_cost_fabs.dyeing_type_id,
            so_dyeing_mkt_cost_fabs.offer_qty,
            so_dyeing_mkt_cost_fabs.color_ratio_from,
            so_dyeing_mkt_cost_fabs.color_ratio_to,
            so_dyeing_mkt_cost_fabs.colorrange_id,
            so_dyeing_mkt_cost_fabs.gsm_weight,
            so_dyeing_mkt_cost_fabs.overhead_amount,
            colorranges.name as colorrange_name,
            so_dyeing_mkt_cost_qprices.id as so_dyeing_mkt_cost_qprice_id,
            fabricItemDyesCost.dyes_cost,
            fabricItemChemicalCost.chem_cost,
            fabricSpecialFinishCost.special_chem_cost,
            so_dyeing_mkt_cost_qpricedtls.id as so_dyeing_mktcost_qpricedtl_id,
            so_dyeing_mkt_cost_qpricedtls.cost_per_kg,
            so_dyeing_mkt_cost_qpricedtls.quoted_price_bdt,
            so_dyeing_mkt_cost_qpricedtls.quoted_price,
            so_dyeing_mkt_cost_qpricedtls.profit_amount_bdt,
            so_dyeing_mkt_cost_qpricedtls.profit_amount,
            so_dyeing_mkt_cost_qpricedtls.profit_per,
            so_dyeing_mkt_cost_qpricedtls.remarks
        ')
        ->join('so_dyeing_mkt_costs',function($join){
            $join->on('so_dyeing_mkt_cost_qprices.so_dyeing_mkt_cost_id','=','so_dyeing_mkt_costs.id');
        })
        ->join('so_dyeing_mkt_cost_fabs',function($join){
            $join->on('so_dyeing_mkt_cost_fabs.so_dyeing_mkt_cost_id','=','so_dyeing_mkt_costs.id');
        })
        ->leftJoin('colorranges',function($join){
            $join->on('so_dyeing_mkt_cost_fabs.colorrange_id','=','colorranges.id');
        })
        ->leftJoin(\DB::raw("(
            SELECT 
            so_dyeing_mkt_cost_fab_items.so_dyeing_mkt_cost_fab_id,
            sum(so_dyeing_mkt_cost_fab_items.amount) as dyes_cost 
            FROM so_dyeing_mkt_cost_fab_items 
            join item_accounts on item_accounts.id=so_dyeing_mkt_cost_fab_items.item_account_id
            join itemcategories on itemcategories.id=item_accounts.itemcategory_id
            where  so_dyeing_mkt_cost_fab_items.deleted_at is null
            and itemcategories.identity=7
            group by 
            so_dyeing_mkt_cost_fab_items.so_dyeing_mkt_cost_fab_id
        ) fabricItemDyesCost"), "fabricItemDyesCost.so_dyeing_mkt_cost_fab_id", "=", "so_dyeing_mkt_cost_fabs.id")
        ->leftJoin(\DB::raw("(
            SELECT 
            so_dyeing_mkt_cost_fab_items.so_dyeing_mkt_cost_fab_id,
            sum(so_dyeing_mkt_cost_fab_items.amount) as chem_cost 
            FROM so_dyeing_mkt_cost_fab_items 
            join item_accounts on item_accounts.id=so_dyeing_mkt_cost_fab_items.item_account_id
            join itemcategories on itemcategories.id=item_accounts.itemcategory_id
            where  so_dyeing_mkt_cost_fab_items.deleted_at is null
            and itemcategories.identity=8
            group by 
            so_dyeing_mkt_cost_fab_items.so_dyeing_mkt_cost_fab_id
        ) fabricItemChemicalCost"), "fabricItemChemicalCost.so_dyeing_mkt_cost_fab_id", "=", "so_dyeing_mkt_cost_fabs.id")
        ->leftJoin(\DB::raw("(
            SELECT 
            so_dyeing_mkt_cost_fab_fins.so_dyeing_mkt_cost_fab_id,
            sum(so_dyeing_mkt_cost_fab_fins.amount) as special_chem_cost 
            FROM so_dyeing_mkt_cost_fab_fins 
            where  so_dyeing_mkt_cost_fab_fins.deleted_at is null
            group by 
            so_dyeing_mkt_cost_fab_fins.so_dyeing_mkt_cost_fab_id
        ) fabricSpecialFinishCost"), "fabricSpecialFinishCost.so_dyeing_mkt_cost_fab_id", "=", "so_dyeing_mkt_cost_fabs.id")
        ->leftJoin('so_dyeing_mkt_cost_qpricedtls',function($join){
            $join->on('so_dyeing_mkt_cost_qpricedtls.so_dyeing_mkt_cost_fab_id','=','so_dyeing_mkt_cost_fabs.id')->whereNull('so_dyeing_mkt_cost_qpricedtls.deleted_at');
            $join->on('so_dyeing_mkt_cost_qpricedtls.so_dyeing_mkt_cost_qprice_id','=','so_dyeing_mkt_cost_qprices.id');
         })
        ->where([['so_dyeing_mkt_cost_qprices.id','=',$id]])
        ->get()
        ->map(function($sodyeingmktcostqpricedtl) use($desDropdown) {
            $sodyeingmktcostqpricedtl->total_cost=$sodyeingmktcostqpricedtl->dyes_cost+$sodyeingmktcostqpricedtl->chem_cost+$sodyeingmktcostqpricedtl->overhead_amount+$sodyeingmktcostqpricedtl->special_chem_cost;
            $sodyeingmktcostqpricedtl->cost_per_kg_bdt=$sodyeingmktcostqpricedtl->total_cost/$sodyeingmktcostqpricedtl->fabric_wgt;
            $sodyeingmktcostqpricedtl->cost_per_kg=$sodyeingmktcostqpricedtl->cost_per_kg_bdt/$sodyeingmktcostqpricedtl->exch_rate;
            $sodyeingmktcostqpricedtl->fabrication=$sodyeingmktcostqpricedtl->autoyarn_id?$desDropdown[$sodyeingmktcostqpricedtl->autoyarn_id].",".$sodyeingmktcostqpricedtl->gsm_weight:'';
            $sodyeingmktcostqpricedtl->total_cost=number_format($sodyeingmktcostqpricedtl->total_cost, 2, '.', '');
            $sodyeingmktcostqpricedtl->cost_per_kg_bdt=number_format($sodyeingmktcostqpricedtl->cost_per_kg_bdt, 2, '.', '');
            $sodyeingmktcostqpricedtl->cost_per_kg=number_format($sodyeingmktcostqpricedtl->cost_per_kg, 4, '.', '');
            return $sodyeingmktcostqpricedtl;
        });

        $saved = $sodyeingmktcostqpricedtl->filter(function ($value) {
            if($value->so_dyeing_mktcost_qpricedtl_id){
                return $value;
            }
        });
        $new = $sodyeingmktcostqpricedtl->filter(function ($value) {
            if(!$value->so_dyeing_mktcost_qpricedtl_id){
                return $value;
            }
        });
         
        $row ['fromData'] = $rows;
        $dropdown['sodyeingmktcostqpricedtlcosi'] = "'".Template::loadView('Subcontract.Dyeing.SoDyeingMktCostQpricedtlMatrix',['rows'=>$new,'saved'=>$saved])."'";
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

    public function update(SoDyeingMktCostQpriceRequest $request, $id) {
        $approved=$this->sodyeingmktcostqprice->find($id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost is Approved, So Save/Update/Delete not possible '), 200);
        }

        $quotedPrice=$this->sodyeingmktcostqprice
        ->where([['so_dyeing_mkt_cost_id','=',$request->so_dyeing_mkt_cost_id]])
        ->where([['ready_to_approve_id','=',1]])
        ->get()->first();
        if ($quotedPrice && $request->ready_to_approve_id==1) {
            $ready_to_approve_id=0;
            return response()->json(array('success' => false,'message' => 'Already Approved. Quotation ID:'.$quotedPrice->id),200);
        }else{
            $ready_to_approve_id=$request->ready_to_approve_id;
        }

        $sodyeingmktcostqprice=$this->sodyeingmktcostqprice->update($id,[
            'qprice_date'=>$request->qprice_date,
            'remarks'=>$request->remarks,
            'ready_to_approve_id'=>$ready_to_approve_id,
        ]);
        if($sodyeingmktcostqprice){
            return response()->json(array('success' => true,'id' => $id,'so_dyeing_mkt_cost_id' => $request->so_dyeing_mkt_cost_id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id) {
        $approved=$this->sodyeingmktcostqprice->find($id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
      if($this->sodyeingmktcostqprice->delete($id)){
        return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
      }
    }

    private function getData($id){
        $rows=$this->sodyeingmktcostqprice
        ->join('so_dyeing_mkt_costs',function($join){
            $join->on('so_dyeing_mkt_cost_qprices.so_dyeing_mkt_cost_id','=','so_dyeing_mkt_costs.id');
        })
        ->join('sub_inb_services', function($join)  {
            $join->on('sub_inb_services.id', '=', 'so_dyeing_mkt_costs.sub_inb_service_id');
        })
        ->join('sub_inb_marketings', function($join)  {
            $join->on('sub_inb_marketings.id', '=', 'sub_inb_services.sub_inb_marketing_id');
        })
        ->join('buyers', function($join)  {
            $join->on('sub_inb_marketings.buyer_id', '=', 'buyers.id');
        })
        ->leftJoin('buyer_branches', function($join)  {
            $join->on('sub_inb_marketings.buyer_branch_id', '=', 'buyer_branches.id');
        })
        ->leftJoin('currencies', function($join)  {
            $join->on('sub_inb_marketings.currency_id', '=', 'currencies.id');
        })
        ->join('users',function($join){
            $join->on('users.id','=','so_dyeing_mkt_costs.created_by');
        })
        ->leftJoin('users as first_approval',function($join){
            $join->on('first_approval.id','=','so_dyeing_mkt_cost_qprices.first_approved_by');
        })
        ->leftJoin('employee_h_rs as first_approval_emp',function($join){
            $join->on('first_approval.id','=','first_approval_emp.user_id');
        })
        ->leftJoin('users as second_approval',function($join){
            $join->on('second_approval.id','=','so_dyeing_mkt_cost_qprices.second_approved_by');
        })
        ->leftJoin('employee_h_rs as second_approval_emp',function($join){
            $join->on('second_approval.id','=','second_approval_emp.user_id');
        })
        ->leftJoin('users as third_approval',function($join){
            $join->on('third_approval.id','=','so_dyeing_mkt_cost_qprices.third_approved_by');
        })
        ->leftJoin('employee_h_rs as third_approval_emp',function($join){
            $join->on('third_approval.id','=','third_approval_emp.user_id');
        })
        ->leftJoin('users as final_approval',function($join){
            $join->on('final_approval.id','=','so_dyeing_mkt_cost_qprices.final_approved_by');
        })
        ->leftJoin('employee_h_rs as final_approval_emp',function($join){
            $join->on('final_approval.id','=','final_approval_emp.user_id');
        })
        ->where([['so_dyeing_mkt_cost_qprices.id','=',$id]])
        ->get([
            'so_dyeing_mkt_cost_qprices.id',
            'so_dyeing_mkt_costs.id as so_dyeing_mkt_cost_id',
            'so_dyeing_mkt_costs.sub_inb_service_id',
            'so_dyeing_mkt_costs.created_at',
            'buyers.name as buyer_name',
            'buyer_branches.contact_person',
            'buyer_branches.email',
            'buyer_branches.designation',
            'buyer_branches.address as buyer_address',
            'users.name as user_name',
            'sub_inb_marketings.company_id',
            'currencies.code as currency_code',
            'first_approval.name as first_approval_name',
            'first_approval.signature_file as first_approval_signature',
            'first_approval_emp.name as first_approval_emp_name',
            'first_approval_emp.contact as first_approval_emp_contact',
            'first_approval_emp.designation_id as first_approval_emp_designation',
            'second_approval_emp.name as second_approval_name',
            'second_approval.signature_file as second_approval_signature',
            'third_approval_emp.name as third_approval_name',
            'third_approval.signature_file as third_approval_signature',
            'final_approval_emp.name as final_approval_name',
            'final_approval.signature_file as final_approval_signature',
        ])
        ->map(function($rows){
            $rows->first_approval_signature=$rows->first_approval_signature?'images/signature/'.$rows->first_approval_signature:null;
            $rows->second_approval_signature=$rows->second_approval_signature?'images/signature/'.$rows->second_approval_signature:null;
            $rows->third_approval_signature=$rows->third_approval_signature?'images/signature/'.$rows->third_approval_signature:null;
            $rows->final_approval_signature=$rows->final_approval_signature?'images/signature/'.$rows->final_approval_signature:null;
            return $rows;
        })
        ->first();

        
        $company=$this->company->where([['id','=',$rows->company_id]])->get()->first();

        $today=date('Y-m-d');
        $askingProfit=$this->keycontrol
        ->join('keycontrol_parameters', function($join){
            $join->on('keycontrol_parameters.keycontrol_id','=','keycontrols.id');
        })
        ->whereRaw('? between keycontrol_parameters.from_date and keycontrol_parameters.to_date', [$today])
        ->where([['keycontrols.company_id','=',5]])
        ->where([['keycontrol_parameters.parameter_id','=',1]])
        ->get(['keycontrol_parameters.value'])
        ->first();

        $rows->asking_profit=$askingProfit->value;

        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');

        $autoyarn=$this->autoyarn
        ->join('autoyarnratios', function($join)  {
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
            $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }

        $sodyeingmktcostqprice=$this->sodyeingmktcostqprice
        ->selectRaw('
            so_dyeing_mkt_costs.exch_rate,
            so_dyeing_mkt_cost_fabs.id as so_dyeing_mkt_cost_fab_id,
            so_dyeing_mkt_cost_fabs.autoyarn_id,
            so_dyeing_mkt_cost_fabs.liqure_ratio,
            so_dyeing_mkt_cost_fabs.liqure_wgt,
            so_dyeing_mkt_cost_fabs.fabric_wgt,
            so_dyeing_mkt_cost_fabs.dyeing_type_id,
            so_dyeing_mkt_cost_fabs.offer_qty,
            so_dyeing_mkt_cost_fabs.color_ratio_from,
            so_dyeing_mkt_cost_fabs.color_ratio_to,
            so_dyeing_mkt_cost_fabs.colorrange_id,
            so_dyeing_mkt_cost_fabs.gsm_weight,
            so_dyeing_mkt_cost_fabs.overhead_amount,
            colorranges.name as colorrange_name,
            so_dyeing_mkt_cost_qprices.id as so_dyeing_mkt_cost_qprice_id,
            fabricItemDyesCost.dyes_cost,
            fabricItemChemicalCost.chem_cost,
            fabricSpecialFinishCost.special_chem_cost,
            so_dyeing_mkt_cost_qpricedtls.cost_per_kg,
            so_dyeing_mkt_cost_qpricedtls.quoted_price_bdt,
            so_dyeing_mkt_cost_qpricedtls.quoted_price,
            so_dyeing_mkt_cost_qpricedtls.profit_amount_bdt,
            so_dyeing_mkt_cost_qpricedtls.profit_amount,
            so_dyeing_mkt_cost_qpricedtls.profit_per,
            so_dyeing_mkt_cost_qpricedtls.remarks
        ')
        ->join('so_dyeing_mkt_costs',function($join){
            $join->on('so_dyeing_mkt_cost_qprices.so_dyeing_mkt_cost_id','=','so_dyeing_mkt_costs.id');
        })
        ->join('so_dyeing_mkt_cost_fabs',function($join){
            $join->on('so_dyeing_mkt_cost_fabs.so_dyeing_mkt_cost_id','=','so_dyeing_mkt_costs.id');
        })
        ->leftJoin('colorranges',function($join){
            $join->on('so_dyeing_mkt_cost_fabs.colorrange_id','=','colorranges.id');
        })
        ->leftJoin(\DB::raw("(
            SELECT 
            so_dyeing_mkt_cost_fab_items.so_dyeing_mkt_cost_fab_id,
            sum(so_dyeing_mkt_cost_fab_items.amount) as dyes_cost 
            FROM so_dyeing_mkt_cost_fab_items 
            join item_accounts on item_accounts.id=so_dyeing_mkt_cost_fab_items.item_account_id
            join itemcategories on itemcategories.id=item_accounts.itemcategory_id
            where  so_dyeing_mkt_cost_fab_items.deleted_at is null
            and itemcategories.identity=7
            group by 
            so_dyeing_mkt_cost_fab_items.so_dyeing_mkt_cost_fab_id
        ) fabricItemDyesCost"), "fabricItemDyesCost.so_dyeing_mkt_cost_fab_id", "=", "so_dyeing_mkt_cost_fabs.id")
        ->leftJoin(\DB::raw("(
            SELECT 
            so_dyeing_mkt_cost_fab_items.so_dyeing_mkt_cost_fab_id,
            sum(so_dyeing_mkt_cost_fab_items.amount) as chem_cost 
            FROM so_dyeing_mkt_cost_fab_items 
            join item_accounts on item_accounts.id=so_dyeing_mkt_cost_fab_items.item_account_id
            join itemcategories on itemcategories.id=item_accounts.itemcategory_id
            where  so_dyeing_mkt_cost_fab_items.deleted_at is null
            and itemcategories.identity=8
            group by 
            so_dyeing_mkt_cost_fab_items.so_dyeing_mkt_cost_fab_id
        ) fabricItemChemicalCost"), "fabricItemChemicalCost.so_dyeing_mkt_cost_fab_id", "=", "so_dyeing_mkt_cost_fabs.id")
        ->leftJoin(\DB::raw("(
            SELECT 
            so_dyeing_mkt_cost_fab_fins.so_dyeing_mkt_cost_fab_id,
            sum(so_dyeing_mkt_cost_fab_fins.amount) as special_chem_cost 
            FROM so_dyeing_mkt_cost_fab_fins 
            where  so_dyeing_mkt_cost_fab_fins.deleted_at is null
            group by 
            so_dyeing_mkt_cost_fab_fins.so_dyeing_mkt_cost_fab_id
        ) fabricSpecialFinishCost"), "fabricSpecialFinishCost.so_dyeing_mkt_cost_fab_id", "=", "so_dyeing_mkt_cost_fabs.id")
        ->join('so_dyeing_mkt_cost_qpricedtls',function($join){
            $join->on('so_dyeing_mkt_cost_qpricedtls.so_dyeing_mkt_cost_fab_id','=','so_dyeing_mkt_cost_fabs.id')->whereNull('so_dyeing_mkt_cost_qpricedtls.deleted_at');
            $join->on('so_dyeing_mkt_cost_qpricedtls.so_dyeing_mkt_cost_qprice_id','=','so_dyeing_mkt_cost_qprices.id');
        })
        ->where([['so_dyeing_mkt_cost_qprices.id','=',$id]])
        ->get()
        ->map(function($sodyeingmktcostqpricedtl) use($desDropdown) {
            $sodyeingmktcostqpricedtl->total_cost=$sodyeingmktcostqpricedtl->dyes_cost+$sodyeingmktcostqpricedtl->chem_cost+$sodyeingmktcostqpricedtl->overhead_amount+$sodyeingmktcostqpricedtl->special_chem_cost;
            $sodyeingmktcostqpricedtl->cost_per_kg_bdt=$sodyeingmktcostqpricedtl->total_cost/$sodyeingmktcostqpricedtl->fabric_wgt;
            $sodyeingmktcostqpricedtl->cost_per_kg=$sodyeingmktcostqpricedtl->cost_per_kg_bdt/$sodyeingmktcostqpricedtl->exch_rate;
            $sodyeingmktcostqpricedtl->fabrication=$sodyeingmktcostqpricedtl->autoyarn_id?$desDropdown[$sodyeingmktcostqpricedtl->autoyarn_id].",".$sodyeingmktcostqpricedtl->gsm_weight:'';
            return $sodyeingmktcostqpricedtl;
        });

        $purchasetermscondition=$this->purchasetermscondition->where([['purchase_order_id','=',$rows->so_dyeing_mkt_cost_id]])->where([['menu_id','=',351]])->orderBy('sort_id')->get();

        $comment_histories=$this->sodyeingmktcostqprice
        ->join('approval_comment_histories',function($join){
        $join->on('so_dyeing_mkt_cost_qprices.id','=','approval_comment_histories.model_id');
        })
        ->join('users',function($join){
        $join->on('users.id','=','approval_comment_histories.comments_by');
        })
        ->where([['approval_comment_histories.model_type','=','so_dyeing_mkt_cost_qprices']])
        ->where([['so_dyeing_mkt_cost_qprices.id','=',$id]])
        ->orderBy('approval_comment_histories.id')
        ->get(['approval_comment_histories.*','users.name as user_name']);
        $data['comment_histories']=$comment_histories;

        $data['master']=$rows;
        $data['company']=$company;
        $data['sodyeingmktcosquotaionpricedetails']=$sodyeingmktcostqprice;
        $data['termscondition']=$purchasetermscondition;

        return $data;
    }

    public function getPdf(){
        $id=request('id', 0);
        $datas=$this->getData($id);
        $rows=$datas['company'];
        

        $pdf = new \Pdf('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(true);
        $pdf->SetPrintFooter(true);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(10, 45, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $header['logo']=$rows->logo;
        $header['address']=$rows->company_address;
        $header['title']='Forecasting ID : '.$datas['master']->sub_inb_service_id;
        //$header['barcodestyle']= $barcodestyle;
        //$header['barcodeno']= $challan;
        $pdf->setCustomHeader($header);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->AddPage();

        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTitle('Textile Price Quotation');
        $view= \View::make('Defult.Subcontract.Dyeing.SoDyeingMktCostQuotationPdf',[
            'datas'=>$datas,
            'is_html'=>0,
        ]);

        $html_content=$view->render();
        $pdf->SetY(45);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/SoDyeingMktCostQuotationPdf.pdf';
        $pdf->output($filename);
    }

    public function getHtml () {
        $id=request('id',0);
        $approval_type=request('approval_type',0);
        $datas=$this->getData($id);
        $company=$datas['company'];
        return Template::loadView('Subcontract.Dyeing.SoDyeingMktCostQuotationPdf', [
            'datas'=>$datas,
            'company'=>$company,
            'is_html'=>1,
            'approval_type'=>$approval_type,
        ]);
      }

}