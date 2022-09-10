<?php

namespace App\Http\Controllers\Inventory\FinishFabric;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabRcvRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishDlvRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\FinishFabric\InvFinishFabRcvRequest;

class InvFinishFabRcvController extends Controller {

    private $invrcv;
    private $invfinishfabrcv;
    private $prodfinishdlv;
    private $gmtspart;
    private $company;
    private $location;
    private $currency;
    private $supplier;
    private $buyer;
    private $store;
    private $itemaccount;

    public function __construct(
        InvRcvRepository $invrcv,
        InvFinishFabRcvRepository $invfinishfabrcv,
        ProdFinishDlvRepository $prodfinishdlv,
        GmtspartRepository $gmtspart,  
        CompanyRepository $company, 
        LocationRepository $location,
        CurrencyRepository $currency,
        SupplierRepository $supplier,
        BuyerRepository $buyer,
        StoreRepository $store,
        ItemAccountRepository $itemaccount
    ) {
        $this->invrcv = $invrcv;
        $this->invfinishfabrcv = $invfinishfabrcv;
        $this->prodfinishdlv = $prodfinishdlv;
        $this->gmtspart = $gmtspart;
        $this->company = $company;
        $this->location = $location;
        $this->currency = $currency;
        $this->supplier = $supplier;
        $this->buyer = $buyer;
        $this->store = $store;
        $this->itemaccount = $itemaccount;
        $this->middleware('auth');
        $this->middleware('permission:view.invfinishfabrcvs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invfinishfabrcvs', ['only' => ['store']]);
        $this->middleware('permission:edit.invfinishfabrcvs',   ['only' => ['update']]);
        $this->middleware('permission:delete.invfinishfabrcvs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        

        /*$knitings = collect(\DB::select("
        select 
        inv_rcvs.id,
        inv_rcvs.receive_no
        from
        inv_rcvs
        join  inv_finish_fab_rcvs on inv_finish_fab_rcvs.inv_rcv_id=inv_rcvs.id
        where inv_rcvs.company_id=1
        order by inv_rcvs.id
        "));
        $receive_no=77;
        foreach($knitings as $knit){
            $invfinishfabrcv=$this->invrcv->update($knit->id,['receive_no'=>$receive_no]);
            $receive_no++;
        }
        echo "Done"; Die;*/

        $company = array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
        $supplier = array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        $invreceivebasis=array_prepend(config('bprs.invreceivebasis'), '-Select-','');
        $invfinishfabrcvs = array();
        $rows = $this->invrcv
        ->join('inv_finish_fab_rcvs',function($join){
        $join->on('inv_finish_fab_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('prod_finish_dlvs',function($join){
        $join->on('prod_finish_dlvs.id','=','inv_finish_fab_rcvs.prod_finish_dlv_id');
        })
        ->where([['inv_rcvs.menu_id','=',225]])
        ->orderBy('inv_rcvs.id','desc')
        ->get([
        'inv_rcvs.*',
        'inv_finish_fab_rcvs.id as inv_finish_fab_rcv_id',
        'inv_finish_fab_rcvs.prod_finish_dlv_id',
        ]);
        foreach ($rows as $row) {
        $invyarnrcv['id']=$row->id;
        $invyarnrcv['inv_finish_fab_rcv_id']=$row->inv_finish_fab_rcv_id;
        $invyarnrcv['prod_finish_dlv_id']=$row->prod_finish_dlv_id;
        $invyarnrcv['receive_basis_id']=$invreceivebasis[$row->receive_basis_id];
        $invyarnrcv['company_id']=isset($company[$row->company_id])?$company[$row->company_id]:'';
        $invyarnrcv['supplier_id']=isset($supplier[$row->supplier_id])?$supplier[$row->supplier_id]:'';
        $invyarnrcv['receive_no']=$row->receive_no;
        $invyarnrcv['challan_no']=$row->challan_no;
        $invyarnrcv['receive_date']=date('d-M-Y',strtotime($row->receive_date));
        $invyarnrcv['remarks']=$row->remarks;
        array_push($invfinishfabrcvs, $invyarnrcv);
        }
        echo json_encode($invfinishfabrcvs);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

      $invreceivebasis=array_prepend(array_only(config('bprs.invreceivebasis'),[1,2,3]),'-Select-','');
      $menu=array_prepend(array_only(config('bprs.menu'),[0,3,9]),'-Select-','');
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->yarnSupplier(),'name','id'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
      $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
      return Template::loadView('Inventory.FinishFabric.InvFinishFabRcv',[
            'company'=>$company,
            'currency'=>$currency, 
            'invreceivebasis'=>$invreceivebasis,
            'supplier'=>$supplier,
            'store'=>$store,
            'menu'=>$menu,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvFinishFabRcvRequest $request) {
        $prodfinishdlv=$this->prodfinishdlv->find($request->prod_finish_dlv_id);
        $buyer=$this->buyer->find($prodfinishdlv->buyer_id);
        $company_id=$buyer->company_id;
        $max=$this->invrcv
        ->where([['company_id','=',$company_id]])
        ->whereIn('menu_id',[224,225,226,227])
        ->max('receive_no');
        $receive_no=$max+1;
        $invrcv=$this->invrcv->create([
        'menu_id'=>225,
        'receive_no'=>$receive_no,
        'company_id'=>$company_id,
        'receive_basis_id'=>1,
        'receive_against_id'=>$prodfinishdlv->menu_id,
        'supplier_id'=>1141,
        'challan_no'=>$request->challan_no,
        'receive_date'=>$request->receive_date,
        'remarks'=>$request->remarks,
        ]);

        $invfinishfabrcv=$this->invfinishfabrcv->create([
        'menu_id'=>225,
        'inv_rcv_id'=>$invrcv->id,
        'prod_finish_dlv_id'=>$request->prod_finish_dlv_id,
        'challan_no'=>$request->challan_no,
        ]);

        if($invfinishfabrcv){
            return response()->json(array('success' =>true ,'id'=>$invfinishfabrcv->id, 'receive_no'=>$receive_no,'message'=>'Saved Successfully'),200);
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
        $invfinishfabrcv = $this->invrcv
        ->join('inv_finish_fab_rcvs',function($join){
            $join->on('inv_finish_fab_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','inv_rcvs.company_id');
        })
        ->where([['inv_rcvs.id','=',$id]])
        ->get([
            'inv_rcvs.*',
            'inv_finish_fab_rcvs.id  as inv_finish_fab_rcv_id',
            'inv_finish_fab_rcvs.prod_finish_dlv_id',
            'companies.name as company_name'
        ])
        ->first();
        $invfinishfabrcv->receive_date=date('Y-m-d',strtotime($invfinishfabrcv->receive_date));
        $row ['fromData'] = $invfinishfabrcv;
        $dropdown['att'] = '';
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
    public function update(InvFinishFabRcvRequest $request, $id) {
        //$prodfinishdlv=$this->prodfinishdlv->find($request->prod_finish_dlv_id);
        $invfinishfabrcv=$this->invrcv->update($id,$request->except(['id','prod_finish_dlv_id','inv_finish_fab_rcv_id','company_id','receive_basis_id','receive_against_id','supplier_id','challan_no']));
        if($invfinishfabrcv){
            return response()->json(array('success'=> true, 'id' =>$id, 'message'=>'Updated Successfully'),200);
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
        return response()->json(array('success'=>false,'message'=>'Deleted Not Successfully'),200);

        if($this->invrcv->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
    }

    public function getChallan()
    {
        $rows=$this->prodfinishdlv
        ->leftJoin('companies', function($join)  {
        	$join->on('prod_finish_dlvs.company_id', '=', 'companies.id');
        })
        ->leftJoin('buyers', function($join)  {
        	$join->on('prod_finish_dlvs.buyer_id', '=', 'buyers.id');
        })
        ->leftJoin('stores', function($join)  {
        	$join->on('prod_finish_dlvs.store_id', '=', 'stores.id');
        })
        ->leftJoin('inv_finish_fab_rcvs',function($join){
            $join->on('inv_finish_fab_rcvs.prod_finish_dlv_id','=','prod_finish_dlvs.id');
        })
        ->leftJoin('companies as rcvcompanies',function($join){
            $join->on('rcvcompanies.id','=','buyers.company_id');
        })
        ->where([['prod_finish_dlvs.menu_id','!=',286]])
        ->orderBy('prod_finish_dlvs.id','desc')
        ->get([
        	'prod_finish_dlvs.*',
        	'companies.name as company_name',
        	'buyers.name as buyer_name',
        	'stores.name as store_name',
        	'inv_finish_fab_rcvs.id as inv_finish_fab_rcv_id',
            'rcvcompanies.id as rcv_company_id',
            'rcvcompanies.name as rcv_company_name',
        ])
        ->map(function($rows){
        	$rows->dlv_date=date('Y-m-d',strtotime($rows->dlv_date));
        	return $rows;
        })
        ->filter(function ($rows) {
            if(!$rows->inv_finish_fab_rcv_id){
                return $rows;
            }
        })
        ->values();
        return response()->json($rows);
    }



    public function getPdf()
    {
		$fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
		$fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
		$gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');


		$fabricDescription = collect(\DB::select("
		select
		autoyarns.id,
		constructions.name as construction,
		compositions.name,
		autoyarnratios.ratio
		FROM autoyarns
		join autoyarnratios on autoyarnratios.autoyarn_id = autoyarns.id
		join compositions on compositions.id = autoyarnratios.composition_id
		join constructions on constructions.id = autoyarns.construction_id
		"
		));

		$fabricDescriptionArr=array();
		$fabricCompositionArr=array();
		foreach($fabricDescription as $row){
		$fabricDescriptionArr[$row->id]=$row->construction;
		$fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
		}

		$desDropdown=array();
		foreach($fabricDescriptionArr as $key=>$val){
		$desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
		}

		$id=request('id',0);
		$rows = $this->invrcv
        ->join('inv_finish_fab_rcvs',function($join){
        $join->on('inv_finish_fab_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('prod_finish_dlvs',function($join){
        $join->on('prod_finish_dlvs.id','=','inv_finish_fab_rcvs.prod_finish_dlv_id');
        })
		->leftJoin('companies', function($join)  {
		$join->on('prod_finish_dlvs.company_id', '=', 'companies.id');
		})
		->leftJoin('buyers', function($join)  {
		$join->on('prod_finish_dlvs.buyer_id', '=', 'buyers.id');
		})
		->leftJoin('stores', function($join)  {
		$join->on('prod_finish_dlvs.store_id', '=', 'stores.id');
		})
		->join('users',function($join){
		$join->on('users.id','=','prod_finish_dlvs.created_by');
		})
		->leftJoin('employee_h_rs',function($join){
		$join->on('users.id','=','employee_h_rs.user_id');
		})
		->where([['inv_rcvs.id','=',$id]])
		->orderBy('inv_rcvs.id','desc')
		->get([
		'inv_rcvs.*',
		'companies.name as company_name',
		'companies.logo as logo',
		'companies.address as company_address',
		'buyers.name as buyer_name',
		'stores.name as store_name',
		'users.name as user_name',
		'employee_h_rs.contact',
		'prod_finish_dlvs.menu_id'
		])
		->first();
		$rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));

    	if($rows->receive_against_id==285){

    		$rolldtls = collect(
    		\DB::select("
    		select m.gmtspart_id,
    		m.autoyarn_id,
    		m.fabric_look_id,
    		m.fabric_shape_id,
    		m.gsm_weight,
    		m.dia_width,
    		m.measurement,
    		m.roll_length,
    		m.stitch_length,
    		m.shrink_per,
    		sum(m.qc_pass_qty) as qty,
    		sum(m.qty_pcs) as qty_pcs,
    		count(id) as number_of_roll 
    		from (
    		select 
    		inv_finish_fab_rcv_items.id, 
    		prod_batch_finish_qcs.id as prod_batch_finish_qc_id,   
    		prod_batch_finish_qc_rolls.gsm_weight,   
    		prod_batch_finish_qc_rolls.dia_width,

    		prod_knit_qcs.measurement,   
    		prod_knit_qcs.roll_length,   
    		prod_knit_qcs.shrink_per,   
    		prod_batch_finish_qc_rolls.reject_qty,   
    		inv_finish_fab_rcv_items.qty as qc_pass_qty,   
    		prod_batch_finish_qc_rolls.grade_id,

    		prod_knit_item_rolls.id as prod_knit_item_roll_id,
    		prod_knit_item_rolls.custom_no,
    		prod_knit_item_rolls.roll_weight,
    		prod_knit_item_rolls.width,
    		prod_knit_item_rolls.qty_pcs,
    		dyeingcolors.id as fabric_color,
    		dyeingcolors.name as fab_color_name,
    		prod_knit_item_rolls.gmt_sample,
    		prod_knit_items.prod_knit_id,
    		prod_knit_items.stitch_length,
    		prod_knits.prod_no,
    		asset_quantity_costs.custom_no as machine_no,
    		asset_technical_features.dia_width as machine_dia,
    		asset_technical_features.gauge as machine_gg,

    		buyers.name as buyer_name,
    		styles.style_ref,
    		sales_orders.sale_order_no,
    		style_fabrications.autoyarn_id,
    		style_fabrications.gmtspart_id,
    		style_fabrications.fabric_look_id,
    		style_fabrications.fabric_shape_id




    		from 
    		inv_rcvs
    		inner join inv_finish_fab_rcvs on inv_finish_fab_rcvs.inv_rcv_id=inv_rcvs.id
    		inner join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.inv_finish_fab_rcv_id=inv_finish_fab_rcvs.id
    		inner join inv_finish_fab_items on inv_finish_fab_rcv_items.inv_finish_fab_item_id=inv_finish_fab_items.id
    		inner join prod_finish_dlvs on inv_finish_fab_rcvs.prod_finish_dlv_id=prod_finish_dlvs.id

    		inner join prod_finish_dlv_rolls on prod_finish_dlvs.id = prod_finish_dlv_rolls.prod_finish_dlv_id 
    		      and inv_finish_fab_rcv_items.prod_finish_dlv_roll_id=prod_finish_dlv_rolls.id

    		inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
    		inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_batch_finish_qc_rolls.prod_batch_finish_qc_id 
    		inner join prod_batches on prod_batches.id = prod_batch_finish_qcs.prod_batch_id
    		inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
    		inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
    		inner join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id = so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
    		inner join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_fabric_rcv_items.so_dyeing_ref_id
    		inner join so_dyeings on so_dyeings.id = so_dyeing_refs.so_dyeing_id
    		inner join so_dyeing_pos on so_dyeing_pos.so_dyeing_id = so_dyeings.id
    		inner join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id = so_dyeing_refs.id
    		inner join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id = so_dyeing_po_items.po_dyeing_service_item_qty_id
    		inner join po_dyeing_service_items on po_dyeing_service_items.id = po_dyeing_service_item_qties.po_dyeing_service_item_id
    		inner join sales_orders on sales_orders.id = po_dyeing_service_item_qties.sales_order_id
    		inner join jobs on jobs.id = sales_orders.job_id
    		inner join styles on styles.id = jobs.style_id
    		inner join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id
    		inner join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id
    		inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
    		inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
    		inner join constructions on constructions.id = autoyarns.construction_id
    		inner join buyers on buyers.id = styles.buyer_id
    		left join colors dyeingcolors on  dyeingcolors.id=po_dyeing_service_item_qties.fabric_color_id
    		left join asset_quantity_costs on asset_quantity_costs.id = prod_batch_finish_qcs.machine_id 
    		left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 


    		inner join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id = so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id
    		inner join inv_isus on inv_isus.id = inv_grey_fab_isu_items.inv_isu_id
    		inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
    		inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
    		inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
    		inner join inv_rcvs  grey_fab_rcvs on grey_fab_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
    		inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
    		inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
    		and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
    		inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
    		inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
    		inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
    		inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
    		inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
    		left join colors on  colors.id=prod_knit_item_rolls.fabric_color
    		where (inv_rcvs.id = ?) and prod_finish_dlvs.deleted_at is null
    		) m  
    		group by 
    		m.gmtspart_id,
    		m.autoyarn_id,
    		m.fabric_look_id,
    		m.fabric_shape_id,
    		m.gsm_weight,
    		m.dia_width,
    		m.measurement,
    		m.roll_length,
    		m.stitch_length,
    		m.shrink_per
    		",[$id])
    		)
    		->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart){
    		$prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
    		$prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
    		$prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
    		$prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
    		return $prodknitqc;
    		});
    	}
    	if($rows->receive_against_id==287){
    		$rolldtls = collect(
    		\DB::select("
    		select m.gmtspart_id,
    		m.autoyarn_id,
    		m.fabric_look_id,
    		m.fabric_shape_id,
    		m.gsm_weight,
    		m.dia_width,
    		m.measurement,
    		m.roll_length,
    		m.stitch_length,
    		m.shrink_per,
    		sum(m.qc_pass_qty) as qty,
    		sum(m.qty_pcs) as qty_pcs,
    		count(id) as number_of_roll 
    		from (
    			select 
    			inv_finish_fab_rcv_items.id,
    			prod_finish_dlv_rolls.id as prod_finish_dlv_roll_id, 
    			prod_batch_finish_qcs.id as prod_batch_finish_qc_id,   
    			prod_aop_batch_finish_qc_rolls.gsm_weight,   
    			prod_aop_batch_finish_qc_rolls.dia_width,
    			prod_aop_batch_finish_qc_rolls.reject_qty,   
    			inv_finish_fab_rcv_items.qty as qc_pass_qty,   
    			prod_aop_batch_finish_qc_rolls.grade_id,
    			prod_aop_batches.batch_no,
    			prod_aop_batch_rolls.qty as batch_qty,

    			prod_knit_qcs.measurement,   
    			prod_knit_qcs.roll_length,   
    			prod_knit_qcs.shrink_per,
    			prod_knit_item_rolls.id as prod_knit_item_roll_id,
    			prod_knit_item_rolls.custom_no,
    			prod_knit_item_rolls.roll_weight,
    			prod_knit_item_rolls.width,
    			prod_knit_item_rolls.qty_pcs,
    			prod_knit_item_rolls.gmt_sample,
    			prod_knit_items.prod_knit_id,
    			prod_knit_items.stitch_length,
    			prod_knits.prod_no,

    			buyers.name as buyer_name,
    			styles.style_ref,
    			sales_orders.sale_order_no,
    			style_fabrications.autoyarn_id,
    			style_fabrications.gmtspart_id,
    			style_fabrications.fabric_look_id,
    			style_fabrications.fabric_shape_id,
    			po_aop_service_item_qties.rate,
    			so_aops.sales_order_no as aop_sale_order_no,
    			fabriccolors.name as batch_color_name

    			from 
    			inv_rcvs
    			inner join inv_finish_fab_rcvs on inv_finish_fab_rcvs.inv_rcv_id=inv_rcvs.id
    			inner join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.inv_finish_fab_rcv_id=inv_finish_fab_rcvs.id
    			inner join inv_finish_fab_items on inv_finish_fab_rcv_items.inv_finish_fab_item_id=inv_finish_fab_items.id
    			inner join prod_finish_dlvs on inv_finish_fab_rcvs.prod_finish_dlv_id=prod_finish_dlvs.id

    			inner join prod_finish_dlv_rolls prod_aop_finish_dlv_rolls on prod_finish_dlvs.id = prod_aop_finish_dlv_rolls.prod_finish_dlv_id 
    			and inv_finish_fab_rcv_items.prod_finish_dlv_roll_id=prod_aop_finish_dlv_rolls.id
    			inner join prod_batch_finish_qc_rolls prod_aop_batch_finish_qc_rolls on prod_aop_batch_finish_qc_rolls.id = prod_aop_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
    			inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_aop_batch_finish_qc_rolls.prod_batch_finish_qc_id 
    			inner join prod_aop_batches on prod_aop_batches.id = prod_batch_finish_qcs.prod_aop_batch_id
    			inner join prod_aop_batch_rolls on prod_aop_batch_rolls.id = prod_aop_batch_finish_qc_rolls.prod_aop_batch_roll_id
    			inner join so_aop_fabric_isu_items on so_aop_fabric_isu_items.id = prod_aop_batch_rolls.so_aop_fabric_isu_item_id
    			inner join so_aop_fabric_isus on so_aop_fabric_isus.id = so_aop_fabric_isu_items.so_aop_fabric_isu_id
    			inner join so_aop_fabric_rcv_rols on so_aop_fabric_rcv_rols.id = so_aop_fabric_isu_items.so_aop_fabric_rcv_rol_id
    			inner join so_aop_fabric_rcv_items on so_aop_fabric_rcv_items.id = so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id
    			inner join prod_finish_dlv_rolls on prod_finish_dlv_rolls.id = so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id

    			inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id
    			inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
    			inner join prod_batches on prod_batches.id = prod_batch_rolls.prod_batch_id
    			inner join colors fabriccolors on fabriccolors.id = prod_batches.batch_color_id

    			inner join so_aop_refs on so_aop_refs.id = so_aop_fabric_rcv_items.so_aop_ref_id
    			inner join so_aops on so_aops.id = so_aop_refs.so_aop_id
    			inner join so_aop_pos on so_aop_pos.so_aop_id = so_aops.id
    			inner join so_aop_po_items on so_aop_po_items.so_aop_ref_id = so_aop_refs.id
    			inner join po_aop_service_item_qties on po_aop_service_item_qties.id = so_aop_po_items.po_aop_service_item_qty_id
    			inner join po_aop_service_items on po_aop_service_items.id = po_aop_service_item_qties.po_aop_service_item_id and po_aop_service_items.deleted_at is null
    			inner join sales_orders on sales_orders.id = po_aop_service_item_qties.sales_order_id
    			inner join jobs on jobs.id = sales_orders.job_id
    			inner join styles on styles.id = jobs.style_id
    			inner join budget_fabric_prods on budget_fabric_prods.id = po_aop_service_items.budget_fabric_prod_id
    			inner join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id
    			inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
    			inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
    			inner join constructions on constructions.id = autoyarns.construction_id
    			inner join buyers on buyers.id = styles.buyer_id
    			inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id

    			inner join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id = so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id
    			inner join inv_isus on inv_isus.id = inv_grey_fab_isu_items.inv_isu_id
    			inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
    			inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
    			inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
    			inner join inv_rcvs knit_inv_rcvs on knit_inv_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
    			inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
    			inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
    			and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
    			inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
    			inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
    			inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
    			inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
    			inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 

    			where (inv_rcvs.id = ?) and prod_finish_dlvs.deleted_at is null
    		) m  
    		group by 
    		m.gmtspart_id,
    		m.autoyarn_id,
    		m.fabric_look_id,
    		m.fabric_shape_id,
    		m.gsm_weight,
    		m.dia_width,
    		m.measurement,
    		m.roll_length,
    		m.stitch_length,
    		m.shrink_per
    		",[$id])
    		)
    		->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart){
    		$prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
    		$prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
    		$prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
    		$prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
    		return $prodknitqc;
    		});
    	}

		$data['master']    =$rows;
		$data['details']   =$rolldtls;

		$barcodestyle = array(
		'position' => '',
		'align' => 'C',
		'stretch' => false,
		'fitwidth' => true,
		'cellfitalign' => '',
		'border' => false,
		'hpadding' => 'auto',
		'vpadding' => 'auto',
		'fgcolor' => array(0,0,0),
		'bgcolor' => false, //array(255,255,255),
		'text' => true,
		'font' => 'helvetica',
		'fontsize' => 8,
		'stretchtext' => 4
		);

	    $challan=str_pad($data['master']->id,10,0,STR_PAD_LEFT ) ;


		$pdf = new \Pdf('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetPrintHeader(true);
		$pdf->SetPrintFooter(true);
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(PDF_MARGIN_LEFT, 42, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$header['logo']=$rows->logo;
		$header['address']=$rows->company_address;
		$header['title']='Finish Fabric Roll Receive By Store';
		$header['barcodestyle']= $barcodestyle;
		$header['barcodeno']= $challan;
		$pdf->setCustomHeader($header);
		$pdf->SetFont('helvetica', 'B', 12);
		$pdf->AddPage();
	       
		$pdf->SetFont('helvetica', '', 8);
		$pdf->SetTitle('Finish Fabric Roll Receive By Store');
		$view= \View::make('Defult.Inventory.FinishFabric.InvFinishFabRcvPdf',['data'=>$data]);
		$html_content=$view->render();
		$pdf->SetY(42);
		$pdf->WriteHtml($html_content, true, false,true,false,'');
		$filename = storage_path() . '/InvFinishFabRcvPdf.pdf';
		$pdf->output($filename);
    }
    public function getPdfTwo()
    {
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');


        $fabricDescription = collect(\DB::select("
            select
            autoyarns.id,
            constructions.name as construction,
            compositions.name,
            autoyarnratios.ratio
            FROM autoyarns
            join autoyarnratios on autoyarnratios.autoyarn_id = autoyarns.id
            join compositions on compositions.id = autoyarnratios.composition_id
            join constructions on constructions.id = autoyarns.construction_id
            "
        ));

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($fabricDescription as $row){
            $fabricDescriptionArr[$row->id]=$row->construction;
            $fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
        }
        
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
            $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }
        $id=request('id',0);
        $rows = $this->invrcv
        ->join('inv_finish_fab_rcvs',function($join){
        $join->on('inv_finish_fab_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('prod_finish_dlvs',function($join){
        $join->on('prod_finish_dlvs.id','=','inv_finish_fab_rcvs.prod_finish_dlv_id');
        })
		->leftJoin('companies', function($join)  {
		$join->on('prod_finish_dlvs.company_id', '=', 'companies.id');
		})
		->leftJoin('buyers', function($join)  {
		$join->on('prod_finish_dlvs.buyer_id', '=', 'buyers.id');
		})
		->leftJoin('stores', function($join)  {
		$join->on('prod_finish_dlvs.store_id', '=', 'stores.id');
		})
		->join('users',function($join){
		$join->on('users.id','=','prod_finish_dlvs.created_by');
		})
		->leftJoin('employee_h_rs',function($join){
		$join->on('users.id','=','employee_h_rs.user_id');
		})
		->where([['inv_rcvs.id','=',$id]])
		->orderBy('inv_rcvs.id','desc')
		->get([
		'inv_rcvs.*',
		'companies.name as company_name',
		'companies.logo as logo',
		'companies.address as company_address',
		'buyers.name as buyer_name',
		'stores.name as store_name',
		'users.name as user_name',
		'employee_h_rs.contact',
		'prod_finish_dlvs.menu_id'
		])
		->first();
		$rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));

		if($rows->menu_id==285){
			$yarnDescription=$this->itemaccount
			->leftJoin('item_account_ratios',function($join){
			$join->on('item_account_ratios.item_account_id','=','item_accounts.id');
			})
			->leftJoin('compositions',function($join){
			$join->on('compositions.id','=','item_account_ratios.composition_id');
			})
			->leftJoin('itemclasses',function($join){
			$join->on('itemclasses.id','=','item_accounts.itemclass_id');
			})
			->leftJoin('itemcategories',function($join){
			$join->on('itemcategories.id','=','item_accounts.itemcategory_id');
			})

			->where([['itemcategories.identity','=',1]])
			->orderBy('item_account_ratios.ratio','desc')
			->get([
			'item_accounts.id',
			'compositions.name as composition_name',
			'item_account_ratios.ratio',
			]);

			$itemaccountArr=array();
			$yarnCompositionArr=array();
			foreach($yarnDescription as $row){
			$itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
			$yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
			}

			$yarnDropdown=array();
			foreach($itemaccountArr as $key=>$value){
			$yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
			}





			$yarn = collect(
			\DB::select("
			select 
			inv_finish_fab_rcv_items.id, 
			prod_batch_finish_qcs.id as prod_batch_finish_qc_id,   
			prod_batch_finish_qc_rolls.gsm_weight,   
			prod_batch_finish_qc_rolls.dia_width,

			prod_knit_qcs.measurement,   
			prod_knit_qcs.roll_length,   
			prod_knit_qcs.shrink_per,   
			prod_batch_finish_qc_rolls.reject_qty,   
			inv_finish_fab_rcv_items.qty as qc_pass_qty,   
			prod_batch_finish_qc_rolls.grade_id,

			prod_knit_item_rolls.id as prod_knit_item_roll_id,
			prod_knit_item_rolls.custom_no,
			prod_knit_item_rolls.roll_weight,
			prod_knit_item_rolls.width,
			prod_knit_item_rolls.qty_pcs,
			dyeingcolors.id as fabric_color,
			dyeingcolors.name as fab_color_name,
			batch_colors.id as batch_color_id,
			batch_colors.name as batch_color_name,
			prod_knit_item_rolls.gmt_sample,
			prod_knit_items.prod_knit_id,
			prod_knit_items.stitch_length,
			prod_knits.prod_no,
			asset_quantity_costs.custom_no as machine_no,
			asset_technical_features.dia_width as machine_dia,
			asset_technical_features.gauge as machine_gg,

			buyers.name as buyer_name,
			styles.style_ref,
			sales_orders.sale_order_no,
			style_fabrications.autoyarn_id,
			style_fabrications.gmtspart_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			prod_knit_item_yarns.id as prod_knit_item_yarn_id,
			inv_yarn_items.lot,
			inv_yarn_items.brand,
			colors.name as color_name,
			itemcategories.name as itemcategory_name,
			itemclasses.name as itemclass_name,
			item_accounts.id as item_account_id,
			yarncounts.count,
			yarncounts.symbol,
			yarntypes.name as yarn_type,
			uoms.code as uom_code




			from 
			inv_rcvs
			inner join inv_finish_fab_rcvs on inv_finish_fab_rcvs.inv_rcv_id=inv_rcvs.id
			inner join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.inv_finish_fab_rcv_id=inv_finish_fab_rcvs.id
			inner join inv_finish_fab_items on inv_finish_fab_rcv_items.inv_finish_fab_item_id=inv_finish_fab_items.id
			inner join prod_finish_dlvs on inv_finish_fab_rcvs.prod_finish_dlv_id=prod_finish_dlvs.id
			inner join prod_finish_dlv_rolls on prod_finish_dlvs.id = prod_finish_dlv_rolls.prod_finish_dlv_id 
			and inv_finish_fab_rcv_items.prod_finish_dlv_roll_id=prod_finish_dlv_rolls.id
			inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
			inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_batch_finish_qc_rolls.prod_batch_finish_qc_id 
			inner join prod_batches on prod_batches.id = prod_batch_finish_qcs.prod_batch_id
			inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
			inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
			inner join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id = so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
			inner join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_fabric_rcv_items.so_dyeing_ref_id
			inner join so_dyeings on so_dyeings.id = so_dyeing_refs.so_dyeing_id
			inner join so_dyeing_pos on so_dyeing_pos.so_dyeing_id = so_dyeings.id
			inner join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id = so_dyeing_refs.id
			inner join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id = so_dyeing_po_items.po_dyeing_service_item_qty_id
			inner join po_dyeing_service_items on po_dyeing_service_items.id = po_dyeing_service_item_qties.po_dyeing_service_item_id
			inner join sales_orders on sales_orders.id = po_dyeing_service_item_qties.sales_order_id
			inner join jobs on jobs.id = sales_orders.job_id
			inner join styles on styles.id = jobs.style_id
			inner join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id
			inner join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id
			inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
			inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
			inner join constructions on constructions.id = autoyarns.construction_id
			inner join buyers on buyers.id = styles.buyer_id
			left join colors dyeingcolors on  dyeingcolors.id=po_dyeing_service_item_qties.fabric_color_id
			left join colors batch_colors on  batch_colors.id=prod_batches.batch_color_id
			left join asset_quantity_costs on asset_quantity_costs.id = prod_batch_finish_qcs.machine_id 
			left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 


			inner join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id = so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id
			inner join inv_isus on inv_isus.id = inv_grey_fab_isu_items.inv_isu_id
			inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
			inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
			inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
			inner join inv_rcvs grey_fab_rcvs on grey_fab_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
			inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
			inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
			and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
			inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
			inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
			inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
			inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
			inner join prod_knit_item_yarns on prod_knit_items.id = prod_knit_item_yarns.prod_knit_item_id 
			inner join inv_yarn_isu_items on  inv_yarn_isu_items.id=prod_knit_item_yarns.inv_yarn_isu_item_id
			inner join inv_yarn_items on  inv_yarn_items.id=inv_yarn_isu_items.inv_yarn_item_id
			inner join item_accounts on  item_accounts.id=inv_yarn_items.item_account_id
			inner join yarncounts on  yarncounts.id=item_accounts.yarncount_id
			inner join yarntypes on  yarntypes.id=item_accounts.yarntype_id
			inner join itemclasses on  itemclasses.id=item_accounts.itemclass_id
			inner join itemcategories on  itemcategories.id=item_accounts.itemcategory_id
			inner join uoms on  uoms.id=item_accounts.uom_id
			inner join colors  on  colors.id=inv_yarn_items.color_id
			inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
			where (inv_rcvs.id = ?) and inv_rcvs.deleted_at is null
			",[$id])
			)
			->map(function($yarn) use($yarnDropdown){
			$yarn->yarn_count=$yarn->count."/".$yarn->symbol;
			$yarn->composition=$yarn->item_account_id?$yarnDropdown[$yarn->item_account_id]:'';
			return $yarn;
			});

			$yarnDtls=[];
			foreach($yarn as $yar){

			$index=$yar->gmtspart_id."-".$yar->autoyarn_id."-".$yar->fabric_look_id."-".$yar->fabric_shape_id."-".$yar->gsm_weight."-".$yar->dia_width."-".$yar->fabric_color."-".$yar->batch_color_id."-".$yar->sale_order_no."-".$yar->stitch_length."-".$yar->style_ref."-".$yar->buyer_name."-".$yar->machine_no."-".$yar->machine_gg;

			$yarn=$yar->lot." ".$yar->itemclass_name." ".$yar->yarn_count." ".$yar->composition." ".$yar->yarn_type." ".$yar->brand." ".$yar->color_name;

			$yarnDtls[$index][$yarn]=$yarn;
			}



			$rolldtls = collect(
			\DB::select("
			select 
			m.gmtspart_id,
			m.autoyarn_id,
			m.fabric_look_id,
			m.fabric_shape_id,
			m.gsm_weight,
			m.dia_width,
			m.fabric_color,
			m.fab_color_name,
			m.batch_color_id,
			m.batch_color_name,
			m.stitch_length,
			m.sale_order_no,
			m.style_ref,
			m.buyer_name,
			m.machine_no,
			m.machine_gg,
			sum(m.qc_pass_qty) as qty,
			sum(m.qty_pcs) as qty_pcs,
			count(id) as number_of_roll 
			from (
			select 
			inv_finish_fab_rcv_items.id, 
			prod_batch_finish_qcs.id as prod_batch_finish_qc_id,   
			prod_batch_finish_qc_rolls.gsm_weight,   
			prod_batch_finish_qc_rolls.dia_width,

			prod_knit_qcs.measurement,   
			prod_knit_qcs.roll_length,   
			prod_knit_qcs.shrink_per,   
			prod_batch_finish_qc_rolls.reject_qty,   
			inv_finish_fab_rcv_items.qty as qc_pass_qty,   
			prod_batch_finish_qc_rolls.grade_id,

			prod_knit_item_rolls.id as prod_knit_item_roll_id,
			prod_knit_item_rolls.custom_no,
			prod_knit_item_rolls.roll_weight,
			prod_knit_item_rolls.width,
			prod_knit_item_rolls.qty_pcs,
			dyeingcolors.id as fabric_color,
			dyeingcolors.name as fab_color_name,
			batch_colors.id as batch_color_id,
			batch_colors.name as batch_color_name,
			prod_knit_item_rolls.gmt_sample,
			prod_knit_items.prod_knit_id,
			prod_knit_items.stitch_length,
			prod_knits.prod_no,
			asset_quantity_costs.custom_no as machine_no,
			asset_technical_features.dia_width as machine_dia,
			asset_technical_features.gauge as machine_gg,

			buyers.name as buyer_name,
			styles.style_ref,
			sales_orders.sale_order_no,
			style_fabrications.autoyarn_id,
			style_fabrications.gmtspart_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id




			from 
			inv_rcvs
			inner join inv_finish_fab_rcvs on inv_finish_fab_rcvs.inv_rcv_id=inv_rcvs.id
			inner join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.inv_finish_fab_rcv_id=inv_finish_fab_rcvs.id
			inner join inv_finish_fab_items on inv_finish_fab_rcv_items.inv_finish_fab_item_id=inv_finish_fab_items.id
			inner join prod_finish_dlvs on inv_finish_fab_rcvs.prod_finish_dlv_id=prod_finish_dlvs.id

			inner join prod_finish_dlv_rolls on prod_finish_dlvs.id = prod_finish_dlv_rolls.prod_finish_dlv_id 
			and inv_finish_fab_rcv_items.prod_finish_dlv_roll_id=prod_finish_dlv_rolls.id
			inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
			inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_batch_finish_qc_rolls.prod_batch_finish_qc_id 
			inner join prod_batches on prod_batches.id = prod_batch_finish_qcs.prod_batch_id
			inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
			inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
			inner join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id = so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
			inner join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_fabric_rcv_items.so_dyeing_ref_id
			inner join so_dyeings on so_dyeings.id = so_dyeing_refs.so_dyeing_id
			inner join so_dyeing_pos on so_dyeing_pos.so_dyeing_id = so_dyeings.id
			inner join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id = so_dyeing_refs.id
			inner join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id = so_dyeing_po_items.po_dyeing_service_item_qty_id
			inner join po_dyeing_service_items on po_dyeing_service_items.id = po_dyeing_service_item_qties.po_dyeing_service_item_id
			inner join sales_orders on sales_orders.id = po_dyeing_service_item_qties.sales_order_id
			inner join jobs on jobs.id = sales_orders.job_id
			inner join styles on styles.id = jobs.style_id
			inner join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id
			inner join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id
			inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
			inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
			inner join constructions on constructions.id = autoyarns.construction_id
			inner join buyers on buyers.id = styles.buyer_id
			left join colors dyeingcolors on  dyeingcolors.id=po_dyeing_service_item_qties.fabric_color_id
			left join colors batch_colors on  batch_colors.id=prod_batches.batch_color_id

			left join asset_quantity_costs on asset_quantity_costs.id = prod_batch_finish_qcs.machine_id 
			left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 


			inner join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id = so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id
			inner join inv_isus on inv_isus.id = inv_grey_fab_isu_items.inv_isu_id
			inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
			inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
			inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
			inner join inv_rcvs grey_fab_rcvs  on grey_fab_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
			inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
			inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
			and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
			inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
			inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
			inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
			inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
			inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
			left join colors on  colors.id=prod_knit_item_rolls.fabric_color
			where (inv_rcvs.id = ?) and inv_rcvs.deleted_at is null
			) m  
			group by 
			m.gmtspart_id,
			m.autoyarn_id,
			m.fabric_look_id,
			m.fabric_shape_id,
			m.gsm_weight,
			m.dia_width,
			m.fabric_color,
			m.fab_color_name,
			m.batch_color_id,
			m.batch_color_name,
			m.sale_order_no,
			m.stitch_length,
			m.style_ref,
			m.buyer_name,
			m.machine_no,
			m.machine_gg
			",[$id])
			)
			->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$yarnDtls){
			$index=$prodknitqc->gmtspart_id."-".$prodknitqc->autoyarn_id."-".$prodknitqc->fabric_look_id."-".$prodknitqc->fabric_shape_id."-".$prodknitqc->gsm_weight."-".$prodknitqc->dia_width."-".$prodknitqc->fabric_color."-".$prodknitqc->batch_color_id."-".$prodknitqc->sale_order_no."-".$prodknitqc->stitch_length."-".$prodknitqc->style_ref."-".$prodknitqc->buyer_name."-".$prodknitqc->machine_no."-".$prodknitqc->machine_gg;



			$prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
			$prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
			$prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
			$prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
			$prodknitqc->yarn=isset($yarnDtls[$index])?implode(' + ',$yarnDtls[$index]):'';
			return $prodknitqc;
			});

		}
		if($rows->menu_id==287){
			$rolldtls = collect(
			\DB::select("
			select 
			m.gmtspart_id,
			m.autoyarn_id,
			m.fabric_look_id,
			m.fabric_shape_id,
			m.gsm_weight,
			m.dia_width,
			
			
			m.batch_color_name,
			m.stitch_length,
			m.sale_order_no,
			m.style_ref,
			m.buyer_name,
			
			sum(m.qc_pass_qty) as qty,
			sum(m.qty_pcs) as qty_pcs,
			count(id) as number_of_roll 
			from (
			select 
			inv_finish_fab_rcv_items.id,
			prod_finish_dlv_rolls.id as prod_finish_dlv_roll_id, 
			prod_batch_finish_qcs.id as prod_batch_finish_qc_id,   
			prod_aop_batch_finish_qc_rolls.gsm_weight,   
			prod_aop_batch_finish_qc_rolls.dia_width,
			prod_aop_batch_finish_qc_rolls.reject_qty,   
			inv_finish_fab_rcv_items.qty as qc_pass_qty,   
			prod_aop_batch_finish_qc_rolls.grade_id,
			prod_aop_batches.batch_no,
			prod_aop_batch_rolls.qty as batch_qty,

			prod_knit_qcs.measurement,   
			prod_knit_qcs.roll_length,   
			prod_knit_qcs.shrink_per,
			prod_knit_item_rolls.id as prod_knit_item_roll_id,
			prod_knit_item_rolls.custom_no,
			prod_knit_item_rolls.roll_weight,
			prod_knit_item_rolls.width,
			prod_knit_item_rolls.qty_pcs,
			prod_knit_item_rolls.gmt_sample,
			prod_knit_items.prod_knit_id,
			prod_knit_items.stitch_length,
			prod_knits.prod_no,

			buyers.name as buyer_name,
			styles.style_ref,
			sales_orders.sale_order_no,
			style_fabrications.autoyarn_id,
			style_fabrications.gmtspart_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			po_aop_service_item_qties.rate,
			so_aops.sales_order_no as aop_sale_order_no,
			fabriccolors.name as batch_color_name

			from 
			inv_rcvs
			inner join inv_finish_fab_rcvs on inv_finish_fab_rcvs.inv_rcv_id=inv_rcvs.id
			inner join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.inv_finish_fab_rcv_id=inv_finish_fab_rcvs.id
			inner join inv_finish_fab_items on inv_finish_fab_rcv_items.inv_finish_fab_item_id=inv_finish_fab_items.id
			inner join prod_finish_dlvs on inv_finish_fab_rcvs.prod_finish_dlv_id=prod_finish_dlvs.id

			inner join prod_finish_dlv_rolls prod_aop_finish_dlv_rolls on prod_finish_dlvs.id = prod_aop_finish_dlv_rolls.prod_finish_dlv_id 
			and inv_finish_fab_rcv_items.prod_finish_dlv_roll_id=prod_aop_finish_dlv_rolls.id
			inner join prod_batch_finish_qc_rolls prod_aop_batch_finish_qc_rolls on prod_aop_batch_finish_qc_rolls.id = prod_aop_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
			inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_aop_batch_finish_qc_rolls.prod_batch_finish_qc_id 
			inner join prod_aop_batches on prod_aop_batches.id = prod_batch_finish_qcs.prod_aop_batch_id
			inner join prod_aop_batch_rolls on prod_aop_batch_rolls.id = prod_aop_batch_finish_qc_rolls.prod_aop_batch_roll_id
			inner join so_aop_fabric_isu_items on so_aop_fabric_isu_items.id = prod_aop_batch_rolls.so_aop_fabric_isu_item_id
			inner join so_aop_fabric_isus on so_aop_fabric_isus.id = so_aop_fabric_isu_items.so_aop_fabric_isu_id
			inner join so_aop_fabric_rcv_rols on so_aop_fabric_rcv_rols.id = so_aop_fabric_isu_items.so_aop_fabric_rcv_rol_id
			inner join so_aop_fabric_rcv_items on so_aop_fabric_rcv_items.id = so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id
			inner join prod_finish_dlv_rolls on prod_finish_dlv_rolls.id = so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id

			inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id
			inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
			inner join prod_batches on prod_batches.id = prod_batch_rolls.prod_batch_id
			inner join colors fabriccolors on fabriccolors.id = prod_batches.batch_color_id

			inner join so_aop_refs on so_aop_refs.id = so_aop_fabric_rcv_items.so_aop_ref_id
			inner join so_aops on so_aops.id = so_aop_refs.so_aop_id
			inner join so_aop_pos on so_aop_pos.so_aop_id = so_aops.id
			inner join so_aop_po_items on so_aop_po_items.so_aop_ref_id = so_aop_refs.id
			inner join po_aop_service_item_qties on po_aop_service_item_qties.id = so_aop_po_items.po_aop_service_item_qty_id
			inner join po_aop_service_items on po_aop_service_items.id = po_aop_service_item_qties.po_aop_service_item_id and po_aop_service_items.deleted_at is null
			inner join sales_orders on sales_orders.id = po_aop_service_item_qties.sales_order_id
			inner join jobs on jobs.id = sales_orders.job_id
			inner join styles on styles.id = jobs.style_id
			inner join budget_fabric_prods on budget_fabric_prods.id = po_aop_service_items.budget_fabric_prod_id
			inner join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id
			inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
			inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
			inner join constructions on constructions.id = autoyarns.construction_id
			inner join buyers on buyers.id = styles.buyer_id
			inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id

			inner join inv_grey_fab_isu_items on inv_grey_fab_isu_items.id = so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id
			inner join inv_isus on inv_isus.id = inv_grey_fab_isu_items.inv_isu_id
			inner join inv_grey_fab_items on inv_grey_fab_items.id = inv_grey_fab_isu_items.inv_grey_fab_item_id
			inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.id = inv_grey_fab_isu_items.inv_grey_fab_rcv_item_id
			inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id = inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
			inner join inv_rcvs knit_inv_rcvs on knit_inv_rcvs.id = inv_grey_fab_rcvs.inv_rcv_id
			inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id
			inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
			and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
			inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
			inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
			inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
			inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
			inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 

			where (inv_rcvs.id = ?) and prod_finish_dlvs.deleted_at is null
			) m  
			group by 
			m.gmtspart_id,
			m.autoyarn_id,
			m.fabric_look_id,
			m.fabric_shape_id,
			m.gsm_weight,
			m.dia_width,
			
			
			
			m.batch_color_name,
			m.sale_order_no,
			m.stitch_length,
			m.style_ref,
			m.buyer_name
			",[$id])
			)
			->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart){
			$index=$prodknitqc->gmtspart_id."-".$prodknitqc->autoyarn_id."-".$prodknitqc->fabric_look_id."-".$prodknitqc->fabric_shape_id."-".$prodknitqc->gsm_weight."-".$prodknitqc->dia_width."-".$prodknitqc->batch_color_name."-".$prodknitqc->sale_order_no."-".$prodknitqc->stitch_length."-".$prodknitqc->style_ref."-".$prodknitqc->buyer_name;



			$prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
			$prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
			$prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
			$prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
			$prodknitqc->fab_color_name='';
			$prodknitqc->machine_no='';
			$prodknitqc->machine_gg='';
			$prodknitqc->yarn='';
			return $prodknitqc;
			});

		}

        

      $data['master']    =$rows;
      $data['details']   =$rolldtls;

      $barcodestyle = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );
        $challan=str_pad($data['master']->id,10,0,STR_PAD_LEFT ) ;


      $pdf = new \Pdf('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetPrintHeader(true);
      $pdf->SetPrintFooter(true);
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->SetMargins(PDF_MARGIN_LEFT, 42, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      $header['logo']=$rows->logo;
      $header['address']=$rows->company_address;
      $header['title']='Finish Fabric Roll Receive By Store Challan';
      $header['barcodestyle']= $barcodestyle;
      $header['barcodeno']= $challan;
      $pdf->setCustomHeader($header);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->AddPage();
      //$pdf->SetY(10);
      //$image_file ='images/logo/'.$rows->logo;
      //$pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
      //$pdf->SetY(13);
      //$pdf->SetFont('helvetica', 'N', 8);
      //$pdf->Text(115, 12, $rows->company_address);
      
        /*$pdf->SetY(3);
        $pdf->SetX(190);
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');*/
        

        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTitle('Finish Fabric Receive Report');
        $view= \View::make('Defult.Inventory.FinishFabric.InvFinishFabRcvPdfTwo',['data'=>$data]);
        $html_content=$view->render();
        $pdf->SetY(42);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/InvFinishFabRcvPdfTwo.pdf';
        $pdf->output($filename);
    }
}