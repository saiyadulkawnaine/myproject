<?php

namespace App\Http\Controllers\Subcontract\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\AOP\SoAopMktCostQpriceRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopMktCostRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\KeycontrolRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\AOP\SoAopMktCostQpriceRequest;

class SoAopMktCostQpriceController extends Controller {

   
    private $soaopmktcostqprice;
    private $soaopmktcost;
    private $uom;
    private $colorrange;
    private $color;
    private $keycontrol;
    private $company;
    private $embelishmenttype;
    private $purchasetermscondition;


    public function __construct(
        SoAopMktCostQpriceRepository $soaopmktcostqprice,
        SoAopMktCostRepository $soaopmktcost,
        UomRepository $uom,
        ColorrangeRepository $colorrange,
        ColorRepository $color,
        KeycontrolRepository $keycontrol,
        CompanyRepository $company,
        EmbelishmentTypeRepository $embelishmenttype,
        PurchaseTermsConditionRepository $purchasetermscondition
    ) {
        $this->soaopmktcostqprice = $soaopmktcostqprice;
        $this->soaopmktcost = $soaopmktcost;
        $this->uom = $uom;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->keycontrol = $keycontrol;
        $this->company = $company;
        $this->embelishmenttype = $embelishmenttype;
        $this->purchasetermscondition = $purchasetermscondition;

        $this->middleware('auth');
      
        //$this->middleware('permission:view.soaopmktcostqprices',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.soaopmktcostqprices', ['only' => ['store']]);
        //$this->middleware('permission:edit.soaopmktcostqprices',   ['only' => ['update']]);
        //$this->middleware('permission:delete.soaopmktcostqprices', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
        $soaopmktcostqprices=array();
        $rows=$this->soaopmktcostqprice
        ->where([['so_aop_mkt_cost_id','=',request('so_aop_mkt_cost_id',0)]])
        ->orderBy('so_aop_mkt_cost_qprices.id','desc')
        ->get();

        foreach($rows as $row){
            $soaopmktcostqprice['id']=$row->id;
            $soaopmktcostqprice['qprice_date']=date('Y-m-d',strtotime($row->qprice_date));
            $soaopmktcostqprice['qprice_no']=$row->qprice_no;
            $soaopmktcostqprice['ready_to_approve']=$yesno[$row->ready_to_approve_id];
            $soaopmktcostqprice['remarks']=$row->remarks;
            array_push($soaopmktcostqprices,$soaopmktcostqprice);
        }

        echo json_encode($soaopmktcostqprices);
      
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
    public function store(SoAopMktCostQpriceRequest $request) {
        $max=$this->soaopmktcostqprice
        ->max('qprice_no');
        $qprice_no=$max+1;
        $request->request->add(['qprice_no' => $qprice_no]);
        $soaopmktcostqprice=$this->soaopmktcostqprice->create($request->except(['id']));

        if($soaopmktcostqprice){
        return response()->json(array('success' => true,'id' =>  $soaopmktcostqprice->id,'so_aop_mkt_cost_id' =>  $request->so_aop_mkt_cost_id,'message' => 'Save Successfully'),200);
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
        $rows=$this->soaopmktcostqprice->find($id);
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        
        $soaopmktcostqpricedtl=$this->soaopmktcostqprice
        ->selectRaw('
            so_aop_mkt_costs.exch_rate,
            so_aop_mkt_cost_params.id as so_aop_mkt_cost_param_id,
            so_aop_mkt_cost_params.paste_wgt,
            so_aop_mkt_cost_params.fabric_wgt,
            so_aop_mkt_cost_params.print_type_id,
            so_aop_mkt_cost_params.offer_qty,
            so_aop_mkt_cost_params.color_ratio_from,
            so_aop_mkt_cost_params.color_ratio_to,
            so_aop_mkt_cost_params.colorrange_id,
            so_aop_mkt_cost_params.gsm_weight,
            so_aop_mkt_cost_params.overhead_amount,
            colorranges.name as colorrange_name,
            so_aop_mkt_cost_qprices.id as so_aop_mkt_cost_qprice_id,
            fabricItemDyesCost.dyes_cost,
            fabricItemChemicalCost.chem_cost,
            fabricSpecialFinishCost.special_chem_cost,
            so_aop_mkt_cost_qpricedtls.id as so_aop_mktcost_qpricedtl_id,
            so_aop_mkt_cost_qpricedtls.cost_per_kg,
            so_aop_mkt_cost_qpricedtls.quoted_price_bdt,
            so_aop_mkt_cost_qpricedtls.quoted_price,
            so_aop_mkt_cost_qpricedtls.profit_amount_bdt,
            so_aop_mkt_cost_qpricedtls.profit_amount,
            so_aop_mkt_cost_qpricedtls.profit_per,
            so_aop_mkt_cost_qpricedtls.remarks
        ')
        ->join('so_aop_mkt_costs',function($join){
            $join->on('so_aop_mkt_cost_qprices.so_aop_mkt_cost_id','=','so_aop_mkt_costs.id');
        })
        ->join('so_aop_mkt_cost_params',function($join){
            $join->on('so_aop_mkt_cost_params.so_aop_mkt_cost_id','=','so_aop_mkt_costs.id');
        })
        ->leftJoin('colorranges',function($join){
            $join->on('so_aop_mkt_cost_params.colorrange_id','=','colorranges.id');
        })
        ->leftJoin(\DB::raw("(
            SELECT 
            so_aop_mkt_cost_param_items.so_aop_mkt_cost_param_id,
            sum(so_aop_mkt_cost_param_items.amount) as dyes_cost 
            FROM so_aop_mkt_cost_param_items 
            join item_accounts on item_accounts.id=so_aop_mkt_cost_param_items.item_account_id
            join itemcategories on itemcategories.id=item_accounts.itemcategory_id
            where  so_aop_mkt_cost_param_items.deleted_at is null
            and itemcategories.identity=7
            group by 
            so_aop_mkt_cost_param_items.so_aop_mkt_cost_param_id
        ) fabricItemDyesCost"), "fabricItemDyesCost.so_aop_mkt_cost_param_id", "=", "so_aop_mkt_cost_params.id")
        ->leftJoin(\DB::raw("(
            SELECT 
            so_aop_mkt_cost_param_items.so_aop_mkt_cost_param_id,
            sum(so_aop_mkt_cost_param_items.amount) as chem_cost 
            FROM so_aop_mkt_cost_param_items 
            join item_accounts on item_accounts.id=so_aop_mkt_cost_param_items.item_account_id
            join itemcategories on itemcategories.id=item_accounts.itemcategory_id
            where  so_aop_mkt_cost_param_items.deleted_at is null
            and itemcategories.identity=8
            group by 
            so_aop_mkt_cost_param_items.so_aop_mkt_cost_param_id
        ) fabricItemChemicalCost"), "fabricItemChemicalCost.so_aop_mkt_cost_param_id", "=", "so_aop_mkt_cost_params.id")
        ->leftJoin(\DB::raw("(
            SELECT 
            so_aop_mkt_cost_param_fins.so_aop_mkt_cost_param_id,
            sum(so_aop_mkt_cost_param_fins.amount) as special_chem_cost 
            FROM so_aop_mkt_cost_param_fins 
            where  so_aop_mkt_cost_param_fins.deleted_at is null
            group by 
            so_aop_mkt_cost_param_fins.so_aop_mkt_cost_param_id
        ) fabricSpecialFinishCost"), "fabricSpecialFinishCost.so_aop_mkt_cost_param_id", "=", "so_aop_mkt_cost_params.id")
        ->leftJoin('so_aop_mkt_cost_qpricedtls',function($join){
            $join->on('so_aop_mkt_cost_qpricedtls.so_aop_mkt_cost_param_id','=','so_aop_mkt_cost_params.id')->whereNull('so_aop_mkt_cost_qpricedtls.deleted_at');
            $join->on('so_aop_mkt_cost_qpricedtls.so_aop_mkt_cost_qprice_id','=','so_aop_mkt_cost_qprices.id');
         })
        ->where([['so_aop_mkt_cost_qprices.id','=',$id]])
        //->toSql();dd($soaopmktcostqpricedtl);die;
        ->get()
        ->map(function($soaopmktcostqpricedtl) {
            $soaopmktcostqpricedtl->total_cost=$soaopmktcostqpricedtl->dyes_cost+$soaopmktcostqpricedtl->chem_cost+$soaopmktcostqpricedtl->overhead_amount+$soaopmktcostqpricedtl->special_chem_cost;
            $soaopmktcostqpricedtl->cost_per_kg_bdt=$soaopmktcostqpricedtl->total_cost/$soaopmktcostqpricedtl->fabric_wgt;
            $soaopmktcostqpricedtl->cost_per_kg=$soaopmktcostqpricedtl->cost_per_kg_bdt/$soaopmktcostqpricedtl->exch_rate;
            $soaopmktcostqpricedtl->fabrication=$soaopmktcostqpricedtl->autoyarn_id?$desDropdown[$soaopmktcostqpricedtl->autoyarn_id].",".$soaopmktcostqpricedtl->gsm_weight:'';
            $soaopmktcostqpricedtl->total_cost=number_format($soaopmktcostqpricedtl->total_cost, 2, '.', '');
            $soaopmktcostqpricedtl->cost_per_kg_bdt=number_format($soaopmktcostqpricedtl->cost_per_kg_bdt, 2, '.', '');
            $soaopmktcostqpricedtl->cost_per_kg=number_format($soaopmktcostqpricedtl->cost_per_kg, 4, '.', '');
            return $soaopmktcostqpricedtl;
        });

        $saved = $soaopmktcostqpricedtl->filter(function ($value) {
            if($value->so_aop_mktcost_qpricedtl_id){
                return $value;
            }
        });
        $new = $soaopmktcostqpricedtl->filter(function ($value) {
            if(!$value->so_aop_mktcost_qpricedtl_id){
                return $value;
            }
        });
         
        $row ['fromData'] = $rows;
        $dropdown['soaopmktcostqpricedtlcosi'] = "'".Template::loadView('Subcontract.AOP.SoAopMktCostQpricedtlMatrix',['rows'=>$new,'saved'=>$saved])."'";
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

    public function update(SoAopMktCostQpriceRequest $request, $id) {
        $approved=$this->soaopmktcostqprice->find($id);
        if($approved->final_approved_by){
            return response()->json(array('success' => false,  'message' => 'This Cost is Approved, So Save/Update/Delete not possible '), 200);
        }

        $quotedPrice=$this->soaopmktcostqprice
        ->where([['so_aop_mkt_cost_id','=',$request->so_aop_mkt_cost_id]])
        ->where([['ready_to_approve_id','=',1]])
        ->get()->first();
        if ($quotedPrice && $request->ready_to_approve_id==1) {
            $ready_to_approve_id=0;
            return response()->json(array('success' => false,'message' => 'Already Approved. Quotation ID:'.$quotedPrice->id),200);
        }else{
            $ready_to_approve_id=$request->ready_to_approve_id;
        }

        $soaopmktcostqprice=$this->soaopmktcostqprice->update($id,[
            'qprice_date'=>$request->qprice_date,
            'remarks'=>$request->remarks,
            'ready_to_approve_id'=>$ready_to_approve_id,
        ]);
        if($soaopmktcostqprice){
            return response()->json(array('success' => true,'id' => $id,'so_aop_mkt_cost_id' => $request->so_aop_mkt_cost_id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id) {
        $approved=$this->soaopmktcostqprice->find($id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
      if($this->soaopmktcostqprice->delete($id)){
        return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
      }
    }

    private function getData($id){
        $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');
        
        $rows=$this->soaopmktcostqprice
        ->join('so_aop_mkt_costs',function($join){
            $join->on('so_aop_mkt_cost_qprices.so_aop_mkt_cost_id','=','so_aop_mkt_costs.id');
        })
        ->join('sub_inb_services', function($join)  {
            $join->on('sub_inb_services.id', '=', 'so_aop_mkt_costs.sub_inb_service_id');
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
            $join->on('users.id','=','so_aop_mkt_costs.created_by');
        })
        ->leftJoin('users as first_approval',function($join){
            $join->on('first_approval.id','=','so_aop_mkt_cost_qprices.first_approved_by');
        })
        ->leftJoin('employee_h_rs as first_approval_emp',function($join){
            $join->on('first_approval.id','=','first_approval_emp.user_id');
        })
        ->leftJoin('users as second_approval',function($join){
            $join->on('second_approval.id','=','so_aop_mkt_cost_qprices.second_approved_by');
        })
        ->leftJoin('employee_h_rs as second_approval_emp',function($join){
            $join->on('second_approval.id','=','second_approval_emp.user_id');
        })
        ->leftJoin('users as third_approval',function($join){
            $join->on('third_approval.id','=','so_aop_mkt_cost_qprices.third_approved_by');
        })
        ->leftJoin('employee_h_rs as third_approval_emp',function($join){
            $join->on('third_approval.id','=','third_approval_emp.user_id');
        })
        ->leftJoin('users as final_approval',function($join){
            $join->on('final_approval.id','=','so_aop_mkt_cost_qprices.final_approved_by');
        })
        ->leftJoin('employee_h_rs as final_approval_emp',function($join){
            $join->on('final_approval.id','=','final_approval_emp.user_id');
        })
        ->where([['so_aop_mkt_cost_qprices.id','=',$id]])
        ->get([
            'so_aop_mkt_cost_qprices.id',
            'so_aop_mkt_costs.id as so_aop_mkt_cost_id',
            'so_aop_mkt_costs.sub_inb_service_id',
            'so_aop_mkt_costs.created_at',
            'buyers.name as buyer_name',
            'buyer_branches.contact_person',
            'buyer_branches.email',
            'buyer_branches.designation',
            'buyer_branches.address as buyer_address',
            'users.name as user_name',
            'sub_inb_marketings.company_id',
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
        $soaopmktcostqprice=$this->soaopmktcostqprice
        ->selectRaw('
            so_aop_mkt_costs.exch_rate,
            so_aop_mkt_cost_params.id as so_aop_mkt_cost_param_id,
            so_aop_mkt_cost_params.paste_wgt,
            so_aop_mkt_cost_params.fabric_wgt,
            so_aop_mkt_cost_params.print_type_id,
            so_aop_mkt_cost_params.offer_qty,
            so_aop_mkt_cost_params.color_ratio_from,
            so_aop_mkt_cost_params.color_ratio_to,
            so_aop_mkt_cost_params.no_of_color_from,
            so_aop_mkt_cost_params.no_of_color_to,
            so_aop_mkt_cost_params.colorrange_id,
            so_aop_mkt_cost_params.gsm_weight,
            so_aop_mkt_cost_params.overhead_amount,
            colorranges.name as colorrange_name,
            so_aop_mkt_cost_qprices.id as so_aop_mkt_cost_qprice_id,
            fabricItemDyesCost.dyes_cost,
            fabricItemChemicalCost.chem_cost,
            fabricSpecialFinishCost.special_chem_cost,
            so_aop_mkt_cost_qpricedtls.cost_per_kg,
            so_aop_mkt_cost_qpricedtls.quoted_price_bdt,
            so_aop_mkt_cost_qpricedtls.quoted_price,
            so_aop_mkt_cost_qpricedtls.profit_amount_bdt,
            so_aop_mkt_cost_qpricedtls.profit_amount,
            so_aop_mkt_cost_qpricedtls.profit_per,
            so_aop_mkt_cost_qpricedtls.remarks
        ')
        ->join('so_aop_mkt_costs',function($join){
            $join->on('so_aop_mkt_cost_qprices.so_aop_mkt_cost_id','=','so_aop_mkt_costs.id');
        })
        ->join('so_aop_mkt_cost_params',function($join){
            $join->on('so_aop_mkt_cost_params.so_aop_mkt_cost_id','=','so_aop_mkt_costs.id');
        })
        ->leftJoin('colorranges',function($join){
            $join->on('so_aop_mkt_cost_params.colorrange_id','=','colorranges.id');
        })
        ->leftJoin(\DB::raw("(
            SELECT 
            so_aop_mkt_cost_param_items.so_aop_mkt_cost_param_id,
            sum(so_aop_mkt_cost_param_items.amount) as dyes_cost 
            FROM so_aop_mkt_cost_param_items 
            join item_accounts on item_accounts.id=so_aop_mkt_cost_param_items.item_account_id
            join itemcategories on itemcategories.id=item_accounts.itemcategory_id
            where  so_aop_mkt_cost_param_items.deleted_at is null
            and itemcategories.identity=7
            group by 
            so_aop_mkt_cost_param_items.so_aop_mkt_cost_param_id
        ) fabricItemDyesCost"), "fabricItemDyesCost.so_aop_mkt_cost_param_id", "=", "so_aop_mkt_cost_params.id")
        ->leftJoin(\DB::raw("(
            SELECT 
            so_aop_mkt_cost_param_items.so_aop_mkt_cost_param_id,
            sum(so_aop_mkt_cost_param_items.amount) as chem_cost 
            FROM so_aop_mkt_cost_param_items 
            join item_accounts on item_accounts.id=so_aop_mkt_cost_param_items.item_account_id
            join itemcategories on itemcategories.id=item_accounts.itemcategory_id
            where  so_aop_mkt_cost_param_items.deleted_at is null
            and itemcategories.identity=8
            group by 
            so_aop_mkt_cost_param_items.so_aop_mkt_cost_param_id
        ) fabricItemChemicalCost"), "fabricItemChemicalCost.so_aop_mkt_cost_param_id", "=", "so_aop_mkt_cost_params.id")
        ->leftJoin(\DB::raw("(
            SELECT 
            so_aop_mkt_cost_param_fins.so_aop_mkt_cost_param_id,
            sum(so_aop_mkt_cost_param_fins.amount) as special_chem_cost 
            FROM so_aop_mkt_cost_param_fins 
            where  so_aop_mkt_cost_param_fins.deleted_at is null
            group by 
            so_aop_mkt_cost_param_fins.so_aop_mkt_cost_param_id
        ) fabricSpecialFinishCost"), "fabricSpecialFinishCost.so_aop_mkt_cost_param_id", "=", "so_aop_mkt_cost_params.id")
        ->join('so_aop_mkt_cost_qpricedtls',function($join){
            $join->on('so_aop_mkt_cost_qpricedtls.so_aop_mkt_cost_param_id','=','so_aop_mkt_cost_params.id')->whereNull('so_aop_mkt_cost_qpricedtls.deleted_at');
            $join->on('so_aop_mkt_cost_qpricedtls.so_aop_mkt_cost_qprice_id','=','so_aop_mkt_cost_qprices.id');
        })
        ->where([['so_aop_mkt_cost_qprices.id','=',$id]])
        ->get()
        ->map(function($soaopmktcostqpricedtl) use($embelishmenttype) {
            $soaopmktcostqpricedtl->total_cost=$soaopmktcostqpricedtl->dyes_cost+$soaopmktcostqpricedtl->chem_cost+$soaopmktcostqpricedtl->overhead_amount+$soaopmktcostqpricedtl->special_chem_cost;
            $soaopmktcostqpricedtl->cost_per_kg_bdt=$soaopmktcostqpricedtl->total_cost/$soaopmktcostqpricedtl->fabric_wgt;
            $soaopmktcostqpricedtl->cost_per_kg=$soaopmktcostqpricedtl->cost_per_kg_bdt/$soaopmktcostqpricedtl->exch_rate;
            $soaopmktcostqpricedtl->print_type=$soaopmktcostqpricedtl->print_type_id?$embelishmenttype[$soaopmktcostqpricedtl->print_type_id]:'';
            return $soaopmktcostqpricedtl;
        });

        $purchasetermscondition=$this->purchasetermscondition->where([['purchase_order_id','=',$rows->so_aop_mkt_cost_id]])->where([['menu_id','=',352]])->orderBy('sort_id')->get();

        $comment_histories=$this->soaopmktcostqprice
        ->join('approval_comment_histories',function($join){
        $join->on('so_aop_mkt_cost_qprices.id','=','approval_comment_histories.model_id');
        })
        ->join('users',function($join){
        $join->on('users.id','=','approval_comment_histories.comments_by');
        })
        ->where([['approval_comment_histories.model_type','=','so_aop_mkt_cost_qprices']])
        ->where([['so_aop_mkt_cost_qprices.id','=',$id]])
        ->orderBy('approval_comment_histories.id')
        ->get(['approval_comment_histories.*','users.name as user_name']);
        $data['comment_histories']=$comment_histories;

        $data['master']=$rows;
        $data['company']=$company;
        $data['soaopmktcosquotaionpricedetails']=$soaopmktcostqprice;
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
        $view= \View::make('Defult.Subcontract.AOP.SoAopMktCostQuotationPdf',[
            'datas'=>$datas,
            'is_html'=>0,
        ]);

        $html_content=$view->render();
        $pdf->SetY(45);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/SoAopMktCostQuotationPdf.pdf';
        $pdf->output($filename);
    }

    public function getHtml () {
        $id=request('id',0);
        $approval_type=request('approval_type',0);
        $datas=$this->getData($id);
        $company=$datas['company'];
        return Template::loadView('Subcontract.AOP.SoAopMktCostQuotationPdf', [
            'datas'=>$datas,
            'company'=>$company,
            'is_html'=>1,
            'approval_type'=>$approval_type,
        ]);
      }

}