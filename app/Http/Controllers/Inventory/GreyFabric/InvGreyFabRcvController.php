<?php

namespace App\Http\Controllers\Inventory\GreyFabric;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabRcvRepository;
use App\Repositories\Contracts\Production\Kniting\ProdKnitDlvRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\Yarn\InvYarnRcvRequest;

class InvGreyFabRcvController extends Controller {

    private $invrcv;
    private $invgreyfabrcv;
    private $prodknitdlv;
    private $gmtspart;
    private $company;
    private $location;
    private $currency;
    private $supplier;
    private $store;
    private $itemaccount;

    public function __construct(
        InvRcvRepository $invrcv,
        InvGreyFabRcvRepository $invgreyfabrcv,
        ProdKnitDlvRepository $prodknitdlv,
        GmtspartRepository $gmtspart,  
        CompanyRepository $company, 
        LocationRepository $location,
        CurrencyRepository $currency,
        SupplierRepository $supplier,
        StoreRepository $store,
        ItemAccountRepository $itemaccount
    ) {
        $this->invrcv = $invrcv;
        $this->invgreyfabrcv = $invgreyfabrcv;
        $this->prodknitdlv = $prodknitdlv;
        $this->gmtspart = $gmtspart;
        $this->company = $company;
        $this->location = $location;
        $this->currency = $currency;
        $this->supplier = $supplier;
        $this->store = $store;
        $this->itemaccount = $itemaccount;
        $this->middleware('auth');
        //$this->middleware('permission:view.invyarnrcv',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.invyarnrcv', ['only' => ['store']]);
        //$this->middleware('permission:edit.invyarnrcv',   ['only' => ['update']]);
        //$this->middleware('permission:delete.invyarnrcv', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       $company = array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
       $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
       $supplier = array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
       $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
       $invreceivebasis=array_prepend(config('bprs.invreceivebasis'), '-Select-','');
       $invgreyfabrcvs = array();
       $rows = $this->invrcv
       ->join('inv_grey_fab_rcvs',function($join){
        $join->on('inv_grey_fab_rcvs.inv_rcv_id','=','inv_rcvs.id');
       })
       ->where([['inv_rcvs.menu_id','=',217]])
       ->orderBy('inv_rcvs.id','desc')
       ->get(['inv_rcvs.*','inv_grey_fab_rcvs.id as inv_greyfab_rcv_id','inv_grey_fab_rcvs.prod_knit_dlv_id']);
       foreach ($rows as $row) {
            $invyarnrcv['id']=$row->id;
            $invyarnrcv['inv_greyfab_rcv_id']=$row->inv_greyfab_rcv_id;
            $invyarnrcv['prod_knit_dlv_id']=$row->prod_knit_dlv_id;
            $invyarnrcv['receive_basis_id']=$invreceivebasis[$row->receive_basis_id];
            $invyarnrcv['company_id']=isset($company[$row->company_id])?$company[$row->company_id]:'';
            $invyarnrcv['supplier_id']=isset($supplier[$row->supplier_id])?$supplier[$row->supplier_id]:'';
            $invyarnrcv['receive_no']=$row->receive_no;
            $invyarnrcv['challan_no']=$row->challan_no;
            $invyarnrcv['receive_date']=date('d-M-Y',strtotime($row->receive_date));
            $invyarnrcv['remarks']=$row->remarks;
            array_push($invgreyfabrcvs, $invyarnrcv);
        }
        echo json_encode($invgreyfabrcvs);
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
      return Template::loadView('Inventory.GreyFabric.InvGreyFabRcv',['company'=>$company,'currency'=>$currency, 'invreceivebasis'=>$invreceivebasis,'supplier'=>$supplier,'store'=>$store,'menu'=>$menu]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvYarnRcvRequest $request) {
     $prodknitdlv=$this->prodknitdlv->find($request->prod_knit_dlv_id);
      $max=$this->invrcv
      ->where([['company_id','=',$prodknitdlv->company_id]])
      ->whereIn('menu_id',[217,218,219])
      ->max('receive_no');
      $receive_no=$max+1;

      

      $invrcv=$this->invrcv->create([
        'menu_id'=>217,
        'receive_no'=>$receive_no,
        'company_id'=>$prodknitdlv->company_id,
        'receive_basis_id'=>1,
        'receive_against_id'=>0,
        'supplier_id'=>1141,
        'challan_no'=>$request->challan_no,
        'receive_date'=>$request->receive_date,
        'remarks'=>$request->remarks,
      ]);

      $invgreyfabrcv=$this->invgreyfabrcv->create([
        'menu_id'=>217,
        'inv_rcv_id'=>$invrcv->id,
        'prod_knit_dlv_id'=>$request->prod_knit_dlv_id,
        'challan_no'=>$request->challan_no,
      ]);
      if($invgreyfabrcv){
        return response()->json(array('success' =>true ,'id'=>$invgreyfabrcv->id, 'receive_no'=>$receive_no,'message'=>'Saved Successfully'),200);
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
        $invgreyfabrcv = $this->invrcv
        ->join('inv_grey_fab_rcvs',function($join){
            $join->on('inv_grey_fab_rcvs.inv_rcv_id','=','inv_rcvs.id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','inv_rcvs.company_id');
        })
        ->where([['inv_rcvs.id','=',$id]])
        ->get([
            'inv_rcvs.*',
            'inv_grey_fab_rcvs.id  as inv_greyfab_rcv_id',
            'inv_grey_fab_rcvs.prod_knit_dlv_id',
            'companies.name as company_name'
        ])
        ->first();
        $invgreyfabrcv->receive_date=date('Y-m-d',strtotime($invgreyfabrcv->receive_date));
        $row ['fromData'] = $invgreyfabrcv;
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
    public function update(InvYarnRcvRequest $request, $id) {
        //$prodknitdlv=$this->prodknitdlv->find($request->prod_knit_dlv_id);
        $invgreyfabrcv=$this->invrcv->update($id,$request->except(['id','prod_knit_dlv_id','inv_greyfab_rcv_id','company_id','receive_basis_id','receive_against_id','supplier_id','challan_no']));
        if($invgreyfabrcv){
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
        $rows=$this->prodknitdlv
        ->leftJoin('companies', function($join)  {
        	$join->on('prod_knit_dlvs.company_id', '=', 'companies.id');
        })
        ->leftJoin('buyers', function($join)  {
        	$join->on('prod_knit_dlvs.buyer_id', '=', 'buyers.id');
        })
        ->leftJoin('stores', function($join)  {
        	$join->on('prod_knit_dlvs.store_id', '=', 'stores.id');
        })
        ->leftJoin('inv_grey_fab_rcvs',function($join){
            $join->on('inv_grey_fab_rcvs.prod_knit_dlv_id','=','prod_knit_dlvs.id');
        })
        ->orderBy('prod_knit_dlvs.id','desc')
        ->get([
        	'prod_knit_dlvs.*',
        	'companies.name as company_name',
        	'buyers.name as buyer_name',
        	'stores.name as store_name',
        	'inv_grey_fab_rcvs.id as inv_grey_fab_rcv_id'
        ])
        ->map(function($rows){
        	$rows->dlv_date=date('Y-m-d',strtotime($rows->dlv_date));
        	return $rows;
        })
        ->filter(function ($rows) {
            if(!$rows->inv_grey_fab_rcv_id){
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
        $rows=$this->invrcv
        ->leftJoin('inv_grey_fab_rcvs', function($join)  {
        $join->on('inv_grey_fab_rcvs.inv_rcv_id', '=', 'inv_rcvs.id');
        })
        ->leftJoin('companies', function($join)  {
        $join->on('inv_rcvs.company_id', '=', 'companies.id');
        })
        ->leftJoin('prod_knit_dlvs', function($join)  {
        $join->on('prod_knit_dlvs.id', '=', 'inv_grey_fab_rcvs.prod_knit_dlv_id');
        })
        ->leftJoin('stores', function($join)  {
        $join->on('prod_knit_dlvs.store_id', '=', 'stores.id');
        })
        ->join('users',function($join){
        $join->on('users.id','=','inv_rcvs.created_by');
        })
        ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
        })
        ->where([['inv_rcvs.id','=',$id]])
        ->orderBy('inv_rcvs.id','desc')
        ->get([
        'inv_rcvs.*',
        'inv_grey_fab_rcvs.id as inv_greyfab_rcv_id',
        'companies.name as company_name',
        'companies.logo as logo',
        'companies.address as company_address',
        'stores.name as store_name',
        'prod_knit_dlvs.dlv_no',
        'users.name as user_name',
        'employee_h_rs.contact'
        ])
        ->first();
        $rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));

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
        sum(m.qty) as qty,
        sum(m.qty_pcs) as qty_pcs,
        count(id) as number_of_roll from (
        select 
        inv_grey_fab_rcv_items.qty,
        prod_knit_dlv_rolls.id, 
        prod_knit_qcs.id as prod_knit_qc_id,   
        prod_knit_qcs.gsm_weight,   
        prod_knit_qcs.dia_width,   
        prod_knit_qcs.measurement,   
        prod_knit_qcs.roll_length,   
        prod_knit_qcs.shrink_per,   
        prod_knit_qcs.reject_qty,   
        prod_knit_qcs.qc_pass_qty,   
        prod_knit_qcs.reject_qty_pcs,   
        prod_knit_qcs.qc_pass_qty_pcs,   
        prod_knit_qcs.qc_result,

        prod_knit_item_rolls.id as prod_knit_item_roll_id,
        prod_knit_item_rolls.custom_no,
        prod_knit_item_rolls.roll_weight,
        prod_knit_item_rolls.width,
        prod_knit_item_rolls.qty_pcs,
        prod_knit_item_rolls.fabric_color,
        prod_knit_item_rolls.gmt_sample,
        prod_knit_items.prod_knit_id,
        prod_knit_items.stitch_length,

        prod_knits.shift_id,
        prod_knits.prod_no,
        prod_knits.supplier_id,
        prod_knits.location_id,
        prod_knits.floor_id,

        suppliers.name as supplier_name,
        locations.name as location_name,
        floors.name as floor_name,
        asset_quantity_costs.custom_no as machine_no,
        asset_technical_features.dia_width as machine_dia,
        asset_technical_features.gauge as machine_gg,
        gmtssamples.name as gmt_sample,
        case 
        when  inhouseprods.autoyarn_id is null then outhouseprods.autoyarn_id 
        else inhouseprods.autoyarn_id
        end as autoyarn_id,
        case 
        when  inhouseprods.gmtspart_id is null then outhouseprods.gmtspart_id 
        else inhouseprods.gmtspart_id
        end as gmtspart_id,
        case 
        when  inhouseprods.fabric_look_id is null then outhouseprods.fabric_look_id 
        else inhouseprods.fabric_look_id
        end as fabric_look_id,

        case 
        when  inhouseprods.fabric_shape_id is null then outhouseprods.fabric_shape_id 
        else inhouseprods.fabric_shape_id
        end as fabric_shape_id,
        case 
        when  inhouseprods.colorrange_name is null then outhouseprods.colorrange_name 
        else inhouseprods.colorrange_name
        end as colorrange_name,


        case 
        when  inhouseprods.sale_order_no is null then outhouseprods.sale_order_no 
        else inhouseprods.sale_order_no
        end as sale_order_no,
        case 
        when  inhouseprods.style_ref is null then outhouseprods.style_ref 
        else inhouseprods.style_ref
        end as style_ref,

        case 
        when  inhouseprods.buyer_name is null then outhouseprods.buyer_name 
        else inhouseprods.buyer_name
        end as buyer_name,

        case 
        when  inhouseprods.customer_name is null then outhouseprods.customer_name 
        else inhouseprods.customer_name
        end as customer_name


        from 
        inv_rcvs
        inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.inv_rcv_id = inv_rcvs.id 
        inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.inv_grey_fab_rcv_id = inv_grey_fab_rcvs.id 
        inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id 
        inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
        and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
        inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
        inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
        inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
        inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
        inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
        inner join suppliers on suppliers.id = prod_knits.supplier_id 
        left join locations on locations.id = prod_knits.location_id 
        left join floors on floors.id = prod_knits.floor_id 
        left join asset_quantity_costs on asset_quantity_costs.id = prod_knit_items.asset_quantity_cost_id 
        left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 
        left join gmtssamples on gmtssamples.id = prod_knit_item_rolls.gmt_sample 
        left join (
        select 
        pl_knit_items.id,
        colorranges.name as colorrange_name,
        customer.name as customer_name,
        case 
        when  style_fabrications.autoyarn_id is null then so_knit_items.autoyarn_id 
        else style_fabrications.autoyarn_id
        end as autoyarn_id,

        case 
        when  style_fabrications.gmtspart_id is null then so_knit_items.gmtspart_id 
        else style_fabrications.gmtspart_id
        end as gmtspart_id,

        case 
        when  style_fabrications.fabric_look_id is null then so_knit_items.fabric_look_id 
        else style_fabrications.fabric_look_id
        end as fabric_look_id,

        case 
        when  style_fabrications.fabric_shape_id is null then so_knit_items.fabric_shape_id 
        else style_fabrications.fabric_shape_id
        end as fabric_shape_id,
        case 
        when sales_orders.sale_order_no is null then so_knit_items.gmt_sale_order_no 
        else sales_orders.sale_order_no
        end as sale_order_no,
        case 
        when styles.style_ref is null then so_knit_items.gmt_style_ref 
        else styles.style_ref
        end as style_ref,
        case 
        when buyers.name is null then outbuyers.name 
        else buyers.name
        end as buyer_name
        from pl_knit_items
        join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
        left join colorranges on colorranges.id=pl_knit_items.colorrange_id
        join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
        left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
        left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
        left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
        and po_knit_service_items.deleted_at is null
        left join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id 
        left join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
        left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
        left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
        left join so_knits on so_knits.id=so_knit_refs.so_knit_id
        left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
        left join jobs on jobs.id=sales_orders.job_id
        left join styles on styles.id=jobs.style_id
        left join buyers on buyers.id=styles.buyer_id
        left join buyers outbuyers on outbuyers.id=so_knit_items.gmt_buyer
        left join buyers customer on customer.id=so_knits.buyer_id
        ) inhouseprods on inhouseprods.id = prod_knit_items.pl_knit_item_id 
        left join (
        select 
        po_knit_service_item_qties.id,
        colorranges.name as colorrange_name,
        style_fabrications.autoyarn_id,
        style_fabrications.gmtspart_id,
        style_fabrications.fabric_look_id,
        style_fabrications.fabric_shape_id,
        sales_orders.sale_order_no,
        styles.style_ref,
        buyers.name as buyer_name,
        companies.name as customer_name  
        from 
        po_knit_service_item_qties
        join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
        join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
        left join colorranges on colorranges.id=po_knit_service_item_qties.colorrange_id
        join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
        join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
        join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id

        left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
        left join jobs on jobs.id=sales_orders.job_id
        left join styles on styles.id=jobs.style_id
        left join buyers on buyers.id=styles.buyer_id
        left join companies on companies.id=po_knit_services.company_id
        order by po_knit_service_item_qties.id
        ) outhouseprods on outhouseprods.id = prod_knit_items.po_knit_service_item_qty_id 
        where (inv_rcvs.id = ?) and prod_knit_dlvs.deleted_at is null
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
        $challan=str_pad($data['master']->inv_greyfab_rcv_id,10,0,STR_PAD_LEFT ) ;

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
        $header['title']='Grey Fabric Receive Report';
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
        $pdf->SetTitle('Grey Fabric Receive Report');
        $view= \View::make('Defult.Inventory.GreyFabric.InvGreyFabRcvPdf',['data'=>$data]);
        $html_content=$view->render();
        $pdf->SetY(42);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/InvGreyFabRcvPdf.pdf';
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
        $rows=$this->invrcv
        ->leftJoin('inv_grey_fab_rcvs', function($join)  {
        $join->on('inv_grey_fab_rcvs.inv_rcv_id', '=', 'inv_rcvs.id');
        })
        ->leftJoin('companies', function($join)  {
        $join->on('inv_rcvs.company_id', '=', 'companies.id');
        })
        ->leftJoin('prod_knit_dlvs', function($join)  {
        $join->on('prod_knit_dlvs.id', '=', 'inv_grey_fab_rcvs.prod_knit_dlv_id');
        })
        ->leftJoin('stores', function($join)  {
        $join->on('prod_knit_dlvs.store_id', '=', 'stores.id');
        })
        ->join('users',function($join){
        $join->on('users.id','=','inv_rcvs.created_by');
        })
        ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
        })
        ->where([['inv_rcvs.id','=',$id]])
        ->orderBy('inv_rcvs.id','desc')
        ->get([
        'inv_rcvs.*',
        'inv_grey_fab_rcvs.id as inv_greyfab_rcv_id',
        'companies.name as company_name',
        'companies.logo as logo',
        'companies.address as company_address',
        'stores.name as store_name',
        'prod_knit_dlvs.dlv_no',
        'users.name as user_name',
        'employee_h_rs.contact'
        ])
        ->first();
        $rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));


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
			prod_knit_dlv_rolls.id, 
			prod_knit_qcs.id as prod_knit_qc_id,   
			prod_knit_qcs.gsm_weight,   
			prod_knit_qcs.dia_width,   
			prod_knit_qcs.measurement,   
			prod_knit_qcs.roll_length,   
			prod_knit_qcs.shrink_per,   
			prod_knit_qcs.reject_qty,   
			prod_knit_qcs.qc_pass_qty,   
			prod_knit_qcs.reject_qty_pcs,   
			prod_knit_qcs.qc_pass_qty_pcs,   
			prod_knit_qcs.qc_result,

			prod_knit_item_rolls.id as prod_knit_item_roll_id,
			prod_knit_item_rolls.custom_no,
			prod_knit_item_rolls.roll_weight,
			prod_knit_item_rolls.width,
			prod_knit_item_rolls.qty_pcs,
			prod_knit_item_rolls.fabric_color,
			prod_knit_item_rolls.gmt_sample,
			prod_knit_items.prod_knit_id,
			prod_knit_items.stitch_length,

			prod_knits.shift_id,
			prod_knits.prod_no,
			prod_knits.supplier_id,
			prod_knits.location_id,
			prod_knits.floor_id,

			suppliers.name as supplier_name,
			locations.name as location_name,
			floors.name as floor_name,
			asset_quantity_costs.custom_no as machine_no,
			asset_technical_features.dia_width as machine_dia,
			asset_technical_features.gauge as machine_gg,
			gmtssamples.name as gmt_sample,
			case 
			when  inhouseprods.autoyarn_id is null then outhouseprods.autoyarn_id 
			else inhouseprods.autoyarn_id
			end as autoyarn_id,
			case 
			when  inhouseprods.gmtspart_id is null then outhouseprods.gmtspart_id 
			else inhouseprods.gmtspart_id
			end as gmtspart_id,
			case 
			when  inhouseprods.fabric_look_id is null then outhouseprods.fabric_look_id 
			else inhouseprods.fabric_look_id
			end as fabric_look_id,

			case 
			when  inhouseprods.fabric_shape_id is null then outhouseprods.fabric_shape_id 
			else inhouseprods.fabric_shape_id
			end as fabric_shape_id,
			case 
			when  inhouseprods.colorrange_name is null then outhouseprods.colorrange_name 
			else inhouseprods.colorrange_name
			end as colorrange_name,


			case 
			when  inhouseprods.sale_order_no is null then outhouseprods.sale_order_no 
			else inhouseprods.sale_order_no
			end as sale_order_no,
			case 
			when  inhouseprods.style_ref is null then outhouseprods.style_ref 
			else inhouseprods.style_ref
			end as style_ref,

			case 
			when  inhouseprods.buyer_name is null then outhouseprods.buyer_name 
			else inhouseprods.buyer_name
			end as buyer_name,

			case 
			when  inhouseprods.customer_name is null then outhouseprods.customer_name 
			else inhouseprods.customer_name
			end as customer_name,
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
			inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.inv_rcv_id = inv_rcvs.id 
			inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.inv_grey_fab_rcv_id = inv_grey_fab_rcvs.id

			inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id 
			 
			inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
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
			inner join colors on  colors.id=inv_yarn_items.color_id
			inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 

			inner join suppliers on suppliers.id = prod_knits.supplier_id 
			left join locations on locations.id = prod_knits.location_id 
			left join floors on floors.id = prod_knits.floor_id 
			left join asset_quantity_costs on asset_quantity_costs.id = prod_knit_items.asset_quantity_cost_id 
			left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 
			left join gmtssamples on gmtssamples.id = prod_knit_item_rolls.gmt_sample 
			left join (
			select 
			pl_knit_items.id,
			colorranges.name as colorrange_name,
			customer.name as customer_name,
			case 
			when  style_fabrications.autoyarn_id is null then so_knit_items.autoyarn_id 
			else style_fabrications.autoyarn_id
			end as autoyarn_id,

			case 
			when  style_fabrications.gmtspart_id is null then so_knit_items.gmtspart_id 
			else style_fabrications.gmtspart_id
			end as gmtspart_id,

			case 
			when  style_fabrications.fabric_look_id is null then so_knit_items.fabric_look_id 
			else style_fabrications.fabric_look_id
			end as fabric_look_id,

			case 
			when  style_fabrications.fabric_shape_id is null then so_knit_items.fabric_shape_id 
			else style_fabrications.fabric_shape_id
			end as fabric_shape_id,
			case 
			when sales_orders.sale_order_no is null then so_knit_items.gmt_sale_order_no 
			else sales_orders.sale_order_no
			end as sale_order_no,
			case 
			when styles.style_ref is null then so_knit_items.gmt_style_ref 
			else styles.style_ref
			end as style_ref,
			case 
			when buyers.name is null then outbuyers.name 
			else buyers.name
			end as buyer_name
			from pl_knit_items
			join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
			left join colorranges on colorranges.id=pl_knit_items.colorrange_id
			join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
			left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
			left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
			left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
			and po_knit_service_items.deleted_at is null
			left join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id 
			left join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
			left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
			left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
			left join so_knits on so_knits.id=so_knit_refs.so_knit_id
			left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
			left join jobs on jobs.id=sales_orders.job_id
			left join styles on styles.id=jobs.style_id
			left join buyers on buyers.id=styles.buyer_id
			left join buyers outbuyers on outbuyers.id=so_knit_items.gmt_buyer
			left join buyers customer on customer.id=so_knits.buyer_id
			) inhouseprods on inhouseprods.id = prod_knit_items.pl_knit_item_id 
			left join (
			select 
			po_knit_service_item_qties.id,
			colorranges.name as colorrange_name,
			style_fabrications.autoyarn_id,
			style_fabrications.gmtspart_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			sales_orders.sale_order_no,
			styles.style_ref,
			buyers.name as buyer_name,
			companies.name as customer_name  
			from 
			po_knit_service_item_qties
			join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
			join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
			left join colorranges on colorranges.id=po_knit_service_item_qties.colorrange_id
			join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
			join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
			join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id

			left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
			left join jobs on jobs.id=sales_orders.job_id
			left join styles on styles.id=jobs.style_id
			left join buyers on buyers.id=styles.buyer_id
			left join companies on companies.id=po_knit_services.company_id
			order by po_knit_service_item_qties.id
			) outhouseprods on outhouseprods.id = prod_knit_items.po_knit_service_item_qty_id 
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
        	$index=$yar->gmtspart_id."-".$yar->autoyarn_id."-".$yar->fabric_look_id."-".$yar->fabric_shape_id."-".$yar->gsm_weight."-".$yar->dia_width."-".$yar->fabric_color."-".$yar->sale_order_no."-".$yar->stitch_length."-".$yar->style_ref."-".$yar->buyer_name."-".$yar->machine_no."-".$yar->machine_gg;
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
        m.sale_order_no,
        m.stitch_length,
        m.style_ref,
        m.buyer_name,
        m.machine_no,
        m.machine_gg,
        sum(m.qty) as qty,
        sum(m.qty_pcs) as qty_pcs,
        count(id) as number_of_roll 
        from (
        select 
        inv_grey_fab_rcv_items.qty,
        prod_knit_dlv_rolls.id, 
        prod_knit_qcs.id as prod_knit_qc_id,   
        prod_knit_qcs.gsm_weight,   
        prod_knit_qcs.dia_width,   
        prod_knit_qcs.measurement,   
        prod_knit_qcs.roll_length,   
        prod_knit_qcs.shrink_per,   
        prod_knit_qcs.reject_qty,   
        prod_knit_qcs.qc_pass_qty,   
        prod_knit_qcs.reject_qty_pcs,   
        prod_knit_qcs.qc_pass_qty_pcs,   
        prod_knit_qcs.qc_result,

        prod_knit_item_rolls.id as prod_knit_item_roll_id,
        prod_knit_item_rolls.custom_no,
        prod_knit_item_rolls.roll_weight,
        prod_knit_item_rolls.width,
        prod_knit_item_rolls.qty_pcs,
        prod_knit_item_rolls.fabric_color,
        colors.name as fab_color_name,
        prod_knit_item_rolls.gmt_sample,
        prod_knit_items.prod_knit_id,
        prod_knit_items.stitch_length,

        prod_knits.shift_id,
        prod_knits.prod_no,
        prod_knits.supplier_id,
        prod_knits.location_id,
        prod_knits.floor_id,

        suppliers.name as supplier_name,
        locations.name as location_name,
        floors.name as floor_name,
        asset_quantity_costs.custom_no as machine_no,
        asset_technical_features.dia_width as machine_dia,
        asset_technical_features.gauge as machine_gg,
        gmtssamples.name as gmt_sample,
        case 
        when  inhouseprods.autoyarn_id is null then outhouseprods.autoyarn_id 
        else inhouseprods.autoyarn_id
        end as autoyarn_id,
        case 
        when  inhouseprods.gmtspart_id is null then outhouseprods.gmtspart_id 
        else inhouseprods.gmtspart_id
        end as gmtspart_id,
        case 
        when  inhouseprods.fabric_look_id is null then outhouseprods.fabric_look_id 
        else inhouseprods.fabric_look_id
        end as fabric_look_id,

        case 
        when  inhouseprods.fabric_shape_id is null then outhouseprods.fabric_shape_id 
        else inhouseprods.fabric_shape_id
        end as fabric_shape_id,
        case 
        when  inhouseprods.colorrange_name is null then outhouseprods.colorrange_name 
        else inhouseprods.colorrange_name
        end as colorrange_name,


        case 
        when  inhouseprods.sale_order_no is null then outhouseprods.sale_order_no 
        else inhouseprods.sale_order_no
        end as sale_order_no,
        case 
        when  inhouseprods.style_ref is null then outhouseprods.style_ref 
        else inhouseprods.style_ref
        end as style_ref,

        case 
        when  inhouseprods.buyer_name is null then outhouseprods.buyer_name 
        else inhouseprods.buyer_name
        end as buyer_name,

        case 
        when  inhouseprods.customer_name is null then outhouseprods.customer_name 
        else inhouseprods.customer_name
        end as customer_name


        from 
        inv_rcvs
        inner join inv_grey_fab_rcvs on inv_grey_fab_rcvs.inv_rcv_id = inv_rcvs.id 
        inner join inv_grey_fab_rcv_items on inv_grey_fab_rcv_items.inv_grey_fab_rcv_id = inv_grey_fab_rcvs.id 

        inner join prod_knit_dlvs on prod_knit_dlvs.id = inv_grey_fab_rcvs.prod_knit_dlv_id 
        inner join prod_knit_dlv_rolls on prod_knit_dlvs.id = prod_knit_dlv_rolls.prod_knit_dlv_id 
        and inv_grey_fab_rcv_items.prod_knit_dlv_roll_id=prod_knit_dlv_rolls.id
        inner join prod_knit_qcs on prod_knit_qcs.id = prod_knit_dlv_rolls.prod_knit_qc_id 
        inner join prod_knit_rcv_by_qcs on prod_knit_rcv_by_qcs.id = prod_knit_qcs.prod_knit_rcv_by_qc_id 
        inner join prod_knit_item_rolls on prod_knit_item_rolls.id = prod_knit_rcv_by_qcs.prod_knit_item_roll_id 
        inner join prod_knit_items on prod_knit_items.id = prod_knit_item_rolls.prod_knit_item_id 
        inner join prod_knits on prod_knits.id = prod_knit_items.prod_knit_id 
        inner join suppliers on suppliers.id = prod_knits.supplier_id 
        left join locations on locations.id = prod_knits.location_id 
        left join floors on floors.id = prod_knits.floor_id 
        left join asset_quantity_costs on asset_quantity_costs.id = prod_knit_items.asset_quantity_cost_id 
        left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 
        left join gmtssamples on gmtssamples.id = prod_knit_item_rolls.gmt_sample
        left join colors on  colors.id=prod_knit_item_rolls.fabric_color 
        left join (
        select 
        pl_knit_items.id,
        colorranges.name as colorrange_name,
        customer.name as customer_name,
        case 
        when  style_fabrications.autoyarn_id is null then so_knit_items.autoyarn_id 
        else style_fabrications.autoyarn_id
        end as autoyarn_id,

        case 
        when  style_fabrications.gmtspart_id is null then so_knit_items.gmtspart_id 
        else style_fabrications.gmtspart_id
        end as gmtspart_id,

        case 
        when  style_fabrications.fabric_look_id is null then so_knit_items.fabric_look_id 
        else style_fabrications.fabric_look_id
        end as fabric_look_id,

        case 
        when  style_fabrications.fabric_shape_id is null then so_knit_items.fabric_shape_id 
        else style_fabrications.fabric_shape_id
        end as fabric_shape_id,
        case 
        when sales_orders.sale_order_no is null then so_knit_items.gmt_sale_order_no 
        else sales_orders.sale_order_no
        end as sale_order_no,
        case 
        when styles.style_ref is null then so_knit_items.gmt_style_ref 
        else styles.style_ref
        end as style_ref,
        case 
        when buyers.name is null then outbuyers.name 
        else buyers.name
        end as buyer_name
        from pl_knit_items
        join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
        left join colorranges on colorranges.id=pl_knit_items.colorrange_id
        join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
        left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
        left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
        left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id 
        and po_knit_service_items.deleted_at is null
        left join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id 
        left join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
        left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
        left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
        left join so_knits on so_knits.id=so_knit_refs.so_knit_id
        left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
        left join jobs on jobs.id=sales_orders.job_id
        left join styles on styles.id=jobs.style_id
        left join buyers on buyers.id=styles.buyer_id
        left join buyers outbuyers on outbuyers.id=so_knit_items.gmt_buyer
        left join buyers customer on customer.id=so_knits.buyer_id
        ) inhouseprods on inhouseprods.id = prod_knit_items.pl_knit_item_id 
        left join (
        select 
        po_knit_service_item_qties.id,
        colorranges.name as colorrange_name,
        style_fabrications.autoyarn_id,
        style_fabrications.gmtspart_id,
        style_fabrications.fabric_look_id,
        style_fabrications.fabric_shape_id,
        sales_orders.sale_order_no,
        styles.style_ref,
        buyers.name as buyer_name,
        companies.name as customer_name  
        from 
        po_knit_service_item_qties
        join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
        join po_knit_services on po_knit_services.id=po_knit_service_items.po_knit_service_id
        left join colorranges on colorranges.id=po_knit_service_item_qties.colorrange_id
        join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
        join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
        join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id

        left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
        left join jobs on jobs.id=sales_orders.job_id
        left join styles on styles.id=jobs.style_id
        left join buyers on buyers.id=styles.buyer_id
        left join companies on companies.id=po_knit_services.company_id
        order by po_knit_service_item_qties.id
        ) outhouseprods on outhouseprods.id = prod_knit_items.po_knit_service_item_qty_id 
        where (inv_rcvs.id = ?) and prod_knit_dlvs.deleted_at is null
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
        m.sale_order_no,
        m.stitch_length,
        m.style_ref,
        m.buyer_name,
        m.machine_no,
        m.machine_gg
        ",[$id])
        )
        ->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$yarnDtls){
        $index=$prodknitqc->gmtspart_id."-".$prodknitqc->autoyarn_id."-".$prodknitqc->fabric_look_id."-".$prodknitqc->fabric_shape_id."-".$prodknitqc->gsm_weight."-".$prodknitqc->dia_width."-".$prodknitqc->fabric_color."-".$prodknitqc->sale_order_no."-".$prodknitqc->stitch_length."-".$prodknitqc->style_ref."-".$prodknitqc->buyer_name."-".$prodknitqc->machine_no."-".$prodknitqc->machine_gg;

        $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
        $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
        $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
        $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
        $prodknitqc->yarn=isset($yarnDtls[$index])?implode(' + ',$yarnDtls[$index]):'';

        return $prodknitqc;
        });

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
        $challan=str_pad($data['master']->inv_greyfab_rcv_id,10,0,STR_PAD_LEFT ) ;

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
        $header['title']='Grey Fabric Receive Report';
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
        $pdf->SetTitle('Grey Fabric Receive Report');
        $view= \View::make('Defult.Inventory.GreyFabric.InvGreyFabRcvPdfTwo',['data'=>$data]);
        $html_content=$view->render();
        $pdf->SetY(42);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/InvGreyFabRcvPdfTwo.pdf';
        $pdf->output($filename);
    }
}