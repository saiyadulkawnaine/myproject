<?php

namespace App\Http\Controllers\Inventory\FinishFabric;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabRcvRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\FinishFabric\InvFinishFabIsuRtnRequest;

class InvFinishFabIsuRtnController extends Controller {

    private $invrcv;
    private $invfinishfabrcv;
    private $company;
    private $location;
    private $currency;
    private $supplier;
    private $store;
    private $itemaccount;
    private $gmtspart;

    public function __construct(
        InvRcvRepository $invrcv,
        InvFinishFabRcvRepository $invfinishfabrcv, 
        CompanyRepository $company, 
        LocationRepository $location,
        CurrencyRepository $currency,
        SupplierRepository $supplier,
        StoreRepository $store,
        ItemAccountRepository $itemaccount,
        GmtspartRepository $gmtspart
    ) {
        $this->invrcv = $invrcv;
        $this->invfinishfabrcv = $invfinishfabrcv;
        $this->company = $company;
        $this->location = $location;
        $this->currency = $currency;
        $this->supplier = $supplier;
        $this->store = $store;
        $this->itemaccount = $itemaccount;
        $this->gmtspart = $gmtspart;
        $this->middleware('auth');
        $this->middleware('permission:view.invfinishfabisurtns',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.invfinishfabisurtns', ['only' => ['store']]);
        $this->middleware('permission:edit.invfinishfabisurtns',   ['only' => ['update']]);
        $this->middleware('permission:delete.invfinishfabisurtns', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       $invreceivebasis=array_prepend(config('bprs.invreceivebasis'), '-Select-','');
       $rows = $this->invrcv
       ->join('inv_finish_fab_rcvs',function($join){
        $join->on('inv_finish_fab_rcvs.inv_rcv_id','=','inv_rcvs.id');
       })
       ->join('companies',function($join){
        $join->on('companies.id','=','inv_rcvs.company_id');
       })
       ->join('suppliers',function($join){
        $join->on('suppliers.id','=','inv_rcvs.supplier_id');
       })
       ->where([['inv_rcvs.menu_id','=',227]])
       ->orderBy('inv_rcvs.id','desc')
       ->get([
        'inv_rcvs.*',
        'inv_finish_fab_rcvs.id as inv_finish_fab_rcv_id',
        'companies.code as company_id',
        'suppliers.name as supplier_id',
       ])
       ->map(function($rows) use($invreceivebasis){
        $rows->receive_basis_id=$invreceivebasis[$rows->receive_basis_id];
        return $rows;
       });
      echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $invreceivebasis=array_prepend(array_only(config('bprs.invreceivebasis'),[1,2,3,9,10]),'-Select-','');
      $menu=array_prepend(array_only(config('bprs.menu'),[0,8,109]),'-Select-','');
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
      $store = array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
      return Template::loadView('Inventory.FinishFabric.InvFinishFabIsuRtn',['company'=>$company,'currency'=>$currency, 'invreceivebasis'=>$invreceivebasis,'supplier'=>$supplier,'store'=>$store,'menu'=>$menu]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvFinishFabIsuRtnRequest $request) {
      $max=$this->invrcv
      ->where([['company_id','=',$request->company_id]])
      ->whereIn('menu_id',[224,225,226,227])
      ->max('receive_no');
      $receive_no=$max+1;

      

      $invrcv=$this->invrcv->create([
        'menu_id'=>227,
        'receive_no'=>$receive_no,
        'company_id'=>$request->company_id,
        'receive_basis_id'=>4,
        'receive_against_id'=>0,
        'supplier_id'=>$request->supplier_id,
        'receive_date'=>$request->receive_date,
        'remarks'=>$request->remarks,
      ]);

      $invfinishfabrcv=$this->invfinishfabrcv->create([
        'inv_rcv_id'=>$invrcv->id,
        'menu_id'=>227,
        'challan_no'=>0,
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
        ->where([['inv_rcvs.id','=',$id]])
        ->get([
            'inv_rcvs.*',
            'inv_finish_fab_rcvs.id  as inv_finish_fab_rcv_id'
        ])
        ->first();
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
    public function update(InvFinishFabIsuRtnRequest $request, $id) {
        $invfinishfabrcv=$this->invrcv->update($id,$request->except(['id','inv_finish_fab_rcv_id','company_id','receive_basis_id','receive_against_id','supplier_id','from_company_id']));
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
      return response()->json(array('success' => false,'message' => 'Delete Not Successfully'),200);
        if($this->invrcv->delete($id)){
            return response()->json(array('success'=>true,'message'=>'Deleted Successfully'),200);
        }
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
        ->leftJoin('companies fromcompany', function($join)  {
        $join->on('fromcompany.id', '=', 'inv_rcvs.from_company_id');
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
        'fromcompany.name as store_name',
        'fromcompany.address as from_company_address',
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
        count(id) as number_of_roll 
        from (
        select 
        prod_knit_item_rolls.id as prod_knit_item_roll_id,
        prod_knit_item_rolls.custom_no,

        prod_knit_item_rolls.roll_weight,
        prod_knit_item_rolls.width,
        prod_knit_item_rolls.qty_pcs,
        prod_knit_items.id as prod_knit_item_id,
        prod_knit_items.prod_knit_id,
        prod_knit_items.stitch_length,
        prod_knits.prod_no,
        prod_knit_qcs.measurement,   
        prod_knit_qcs.roll_length,   
        prod_knit_qcs.shrink_per, 
        inv_finish_fab_items.autoyarn_id,
        inv_finish_fab_items.gmtspart_id,
        inv_finish_fab_items.fabric_look_id,
        inv_finish_fab_items.fabric_shape_id,
        prod_batch_finish_qc_rolls.gsm_weight,   
        prod_batch_finish_qc_rolls.dia_width,
        prod_batch_finish_qc_rolls.grade_id,

        styles.style_ref,
        buyers.name as buyer_name,
        sales_orders.sale_order_no,
        CASE 
        WHEN  dyeingbatch.batch_color_name IS NULL THEN aopbatch.batch_color_name 
        ELSE dyeingbatch.batch_color_name
        END as batch_color_name,

        CASE 
        WHEN  dyeingbatch.customer_name IS NULL THEN aopbatch.customer_name 
        ELSE dyeingbatch.customer_name
        END as customer_name,

        CASE 
        WHEN  dyeingbatch.dyeing_batch_no IS NULL THEN aopbatch.dyeing_batch_no 
        ELSE dyeingbatch.dyeing_batch_no
        END as dyeing_batch_no,
        aopbatch.aop_batch_no, 

        inv_finish_fab_rcv_items.id, 
        inv_finish_fab_rcv_items.store_qty as qty,
        inv_finish_fab_rcv_items.inv_finish_fab_item_id,
        inv_finish_fab_rcv_items.store_id,
        stores.name as store_name



        from 
        inv_rcvs

        inner join inv_finish_fab_rcvs on inv_finish_fab_rcvs.inv_rcv_id=inv_rcvs.id
        inner join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.inv_finish_fab_rcv_id=inv_finish_fab_rcvs.id
        inner join inv_finish_fab_items on inv_finish_fab_rcv_items.inv_finish_fab_item_id=inv_finish_fab_items.id
        inner join prod_finish_dlv_rolls on inv_finish_fab_rcv_items.prod_finish_dlv_roll_id = prod_finish_dlv_rolls.id 
        inner join prod_finish_dlvs on prod_finish_dlv_rolls.prod_finish_dlv_id=prod_finish_dlvs.id

        and inv_finish_fab_rcv_items.prod_finish_dlv_roll_id=prod_finish_dlv_rolls.id
        inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
        inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_batch_finish_qc_rolls.prod_batch_finish_qc_id 
        left join asset_quantity_costs on asset_quantity_costs.id = prod_batch_finish_qcs.machine_id 
        left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 
        left join stores on stores.id=inv_finish_fab_rcv_items.store_id

        left join (
        select 
        prod_batches.id,
        prod_batches.batch_no as dyeing_batch_no,
        prod_batch_rolls.id as prod_batch_roll_id,
        batch_colors.name as batch_color_name,
        customers.name as customer_name,
        po_dyeing_service_item_qties.sales_order_id,
        budget_fabric_prods.budget_fabric_id,
        so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id

        from 
        prod_batches
        inner join prod_batch_rolls on prod_batch_rolls.prod_batch_id = prod_batches.id
        inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
        inner join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id = so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
        inner join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_fabric_rcv_items.so_dyeing_ref_id
        inner join so_dyeings on so_dyeings.id = so_dyeing_refs.so_dyeing_id
        inner join so_dyeing_pos on so_dyeing_pos.so_dyeing_id = so_dyeings.id
        inner join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id = so_dyeing_refs.id
        inner join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id = so_dyeing_po_items.po_dyeing_service_item_qty_id
        inner join po_dyeing_service_items on po_dyeing_service_items.id = po_dyeing_service_item_qties.po_dyeing_service_item_id
        left join colors dyeingcolors on  dyeingcolors.id=po_dyeing_service_item_qties.fabric_color_id
        left join colors batch_colors on  batch_colors.id=prod_batches.batch_color_id
        inner join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id
        inner join buyers  customers on customers.id = so_dyeings.buyer_id 
        ) dyeingbatch on dyeingbatch.prod_batch_roll_id=prod_batch_finish_qc_rolls.prod_batch_roll_id

        left join (
        select 
        prod_aop_batches.id,
        prod_aop_batches.batch_no as aop_batch_no,
        prod_aop_batch_rolls.id as prod_aop_batch_roll_id,
        batch_colors.name as batch_color_name,
        customers.name as customer_name,
        po_aop_service_item_qties.sales_order_id,
        budget_fabric_prods.budget_fabric_id,
        so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id,
        prod_batches.batch_no as dyeing_batch_no



        from 
        prod_aop_batches
        inner join prod_aop_batch_rolls on prod_aop_batch_rolls.prod_aop_batch_id = prod_aop_batches.id
        inner join so_aop_fabric_isu_items on so_aop_fabric_isu_items.id = prod_aop_batch_rolls.so_aop_fabric_isu_item_id
        inner join so_aop_fabric_isus on so_aop_fabric_isus.id = so_aop_fabric_isu_items.so_aop_fabric_isu_id
        inner join so_aop_fabric_rcv_rols on so_aop_fabric_rcv_rols.id = so_aop_fabric_isu_items.so_aop_fabric_rcv_rol_id
        inner join so_aop_fabric_rcv_items on so_aop_fabric_rcv_items.id = so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id
        inner join prod_finish_dlv_rolls on prod_finish_dlv_rolls.id = so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id
        inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id

        inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
        inner join prod_batches on prod_batches.id = prod_batch_rolls.prod_batch_id
        inner join colors batch_colors on batch_colors.id = prod_batches.batch_color_id
        inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id

        inner join so_aop_refs on so_aop_refs.id = so_aop_fabric_rcv_items.so_aop_ref_id
        inner join so_aops on so_aops.id = so_aop_refs.so_aop_id
        inner join so_aop_pos on so_aop_pos.so_aop_id = so_aops.id
        inner join so_aop_po_items on so_aop_po_items.so_aop_ref_id = so_aop_refs.id
        inner join po_aop_service_item_qties on po_aop_service_item_qties.id = so_aop_po_items.po_aop_service_item_qty_id
        inner join po_aop_service_items on po_aop_service_items.id = po_aop_service_item_qties.po_aop_service_item_id and po_aop_service_items.deleted_at is null
        inner join budget_fabric_prods on budget_fabric_prods.id = po_aop_service_items.budget_fabric_prod_id
        inner join buyers  customers on customers.id = so_aops.buyer_id 

        ) aopbatch on aopbatch.prod_aop_batch_roll_id=prod_batch_finish_qc_rolls.prod_aop_batch_roll_id

        inner join sales_orders on  (sales_orders.id = dyeingbatch.sales_order_id or sales_orders.id = aopbatch.sales_order_id) 
        inner join jobs on jobs.id = sales_orders.job_id
        inner join styles on styles.id = jobs.style_id
        inner join buyers on buyers.id = styles.buyer_id

        inner join budget_fabrics on (budget_fabrics.id = dyeingbatch.budget_fabric_id or budget_fabrics.id = aopbatch.budget_fabric_id) 
        inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
        inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
        inner join constructions on constructions.id = autoyarns.construction_id

        inner join inv_grey_fab_isu_items on (inv_grey_fab_isu_items.id = dyeingbatch.inv_grey_fab_isu_item_id or inv_grey_fab_isu_items.id = aopbatch.inv_grey_fab_isu_item_id)
        inner join inv_isus grey_fab_isus on grey_fab_isus.id = inv_grey_fab_isu_items.inv_isu_id
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
        where inv_rcvs.id = ?  and inv_rcvs.deleted_at is null
        order by inv_finish_fab_rcv_items.id desc
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
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $header['logo']=$rows->logo;
        $header['address']=$rows->company_address;
        $header['title']='Finish Fabric Transfer In Report';
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
        $pdf->SetTitle('Finish Fabric Transfer In Report');
        $view= \View::make('Defult.Inventory.FinishFabric.InvFinishFabTransInPdf',['data'=>$data]);
        $html_content=$view->render();
        $pdf->SetY(25);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/InvFinishFabTransInPdf.pdf';
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
        ->leftJoin('companies fromcompany', function($join)  {
        $join->on('fromcompany.id', '=', 'inv_rcvs.from_company_id');
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
        'fromcompany.name as store_name',
        'fromcompany.address as from_company_address',
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

        inner join prod_finish_dlv_rolls on  inv_finish_fab_rcv_items.prod_finish_dlv_roll_id=prod_finish_dlv_rolls.id
        inner join prod_finish_dlvs on prod_finish_dlv_rolls.prod_finish_dlv_id=prod_finish_dlvs.id

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
        
        m.batch_color_name,
        m.stitch_length,
        m.sale_order_no,
        m.style_ref,
        m.buyer_name,
        
        sum(m.qty) as qty,
        sum(m.qty_pcs) as qty_pcs,
        count(id) as number_of_roll 
        from (
        select 
        prod_knit_item_rolls.id as prod_knit_item_roll_id,
        prod_knit_item_rolls.custom_no,

        prod_knit_item_rolls.roll_weight,
        prod_knit_item_rolls.width,
        prod_knit_item_rolls.qty_pcs,
        prod_knit_items.id as prod_knit_item_id,
        prod_knit_items.prod_knit_id,
        prod_knit_items.stitch_length,
        prod_knits.prod_no,
        prod_knit_qcs.measurement,   
        prod_knit_qcs.roll_length,   
        prod_knit_qcs.shrink_per, 
        inv_finish_fab_items.autoyarn_id,
        inv_finish_fab_items.gmtspart_id,
        inv_finish_fab_items.fabric_look_id,
        inv_finish_fab_items.fabric_shape_id,
        prod_batch_finish_qc_rolls.gsm_weight,   
        prod_batch_finish_qc_rolls.dia_width,
        prod_batch_finish_qc_rolls.grade_id,

        styles.style_ref,
        buyers.name as buyer_name,
        sales_orders.sale_order_no,
        CASE 
        WHEN  dyeingbatch.batch_color_name IS NULL THEN aopbatch.batch_color_name 
        ELSE dyeingbatch.batch_color_name
        END as batch_color_name,

        CASE 
        WHEN  dyeingbatch.customer_name IS NULL THEN aopbatch.customer_name 
        ELSE dyeingbatch.customer_name
        END as customer_name,

        CASE 
        WHEN  dyeingbatch.dyeing_batch_no IS NULL THEN aopbatch.dyeing_batch_no 
        ELSE dyeingbatch.dyeing_batch_no
        END as dyeing_batch_no,
        aopbatch.aop_batch_no, 

        inv_finish_fab_rcv_items.id, 
        inv_finish_fab_rcv_items.store_qty as qty,
        inv_finish_fab_rcv_items.inv_finish_fab_item_id,
        inv_finish_fab_rcv_items.store_id,
        stores.name as store_name



        from 
        inv_rcvs

        inner join inv_finish_fab_rcvs on inv_finish_fab_rcvs.inv_rcv_id=inv_rcvs.id
        inner join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.inv_finish_fab_rcv_id=inv_finish_fab_rcvs.id
        inner join inv_finish_fab_items on inv_finish_fab_rcv_items.inv_finish_fab_item_id=inv_finish_fab_items.id
        inner join prod_finish_dlv_rolls on inv_finish_fab_rcv_items.prod_finish_dlv_roll_id = prod_finish_dlv_rolls.id 
        inner join prod_finish_dlvs on prod_finish_dlv_rolls.prod_finish_dlv_id=prod_finish_dlvs.id

        and inv_finish_fab_rcv_items.prod_finish_dlv_roll_id=prod_finish_dlv_rolls.id
        inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id 
        inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id = prod_batch_finish_qc_rolls.prod_batch_finish_qc_id 
        left join asset_quantity_costs on asset_quantity_costs.id = prod_batch_finish_qcs.machine_id 
        left join asset_technical_features on asset_quantity_costs.asset_acquisition_id = asset_technical_features.asset_acquisition_id 
        left join stores on stores.id=inv_finish_fab_rcv_items.store_id

        left join (
        select 
        prod_batches.id,
        prod_batches.batch_no as dyeing_batch_no,
        prod_batch_rolls.id as prod_batch_roll_id,
        batch_colors.name as batch_color_name,
        customers.name as customer_name,
        po_dyeing_service_item_qties.sales_order_id,
        budget_fabric_prods.budget_fabric_id,
        so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id

        from 
        prod_batches
        inner join prod_batch_rolls on prod_batch_rolls.prod_batch_id = prod_batches.id
        inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id
        inner join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id = so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id
        inner join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_fabric_rcv_items.so_dyeing_ref_id
        inner join so_dyeings on so_dyeings.id = so_dyeing_refs.so_dyeing_id
        inner join so_dyeing_pos on so_dyeing_pos.so_dyeing_id = so_dyeings.id
        inner join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id = so_dyeing_refs.id
        inner join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id = so_dyeing_po_items.po_dyeing_service_item_qty_id
        inner join po_dyeing_service_items on po_dyeing_service_items.id = po_dyeing_service_item_qties.po_dyeing_service_item_id
        left join colors dyeingcolors on  dyeingcolors.id=po_dyeing_service_item_qties.fabric_color_id
        left join colors batch_colors on  batch_colors.id=prod_batches.batch_color_id
        inner join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id
        inner join buyers  customers on customers.id = so_dyeings.buyer_id 
        ) dyeingbatch on dyeingbatch.prod_batch_roll_id=prod_batch_finish_qc_rolls.prod_batch_roll_id

        left join (
        select 
        prod_aop_batches.id,
        prod_aop_batches.batch_no as aop_batch_no,
        prod_aop_batch_rolls.id as prod_aop_batch_roll_id,
        batch_colors.name as batch_color_name,
        customers.name as customer_name,
        po_aop_service_item_qties.sales_order_id,
        budget_fabric_prods.budget_fabric_id,
        so_dyeing_fabric_rcv_rols.inv_grey_fab_isu_item_id,
        prod_batches.batch_no as dyeing_batch_no



        from 
        prod_aop_batches
        inner join prod_aop_batch_rolls on prod_aop_batch_rolls.prod_aop_batch_id = prod_aop_batches.id
        inner join so_aop_fabric_isu_items on so_aop_fabric_isu_items.id = prod_aop_batch_rolls.so_aop_fabric_isu_item_id
        inner join so_aop_fabric_isus on so_aop_fabric_isus.id = so_aop_fabric_isu_items.so_aop_fabric_isu_id
        inner join so_aop_fabric_rcv_rols on so_aop_fabric_rcv_rols.id = so_aop_fabric_isu_items.so_aop_fabric_rcv_rol_id
        inner join so_aop_fabric_rcv_items on so_aop_fabric_rcv_items.id = so_aop_fabric_rcv_rols.so_aop_fabric_rcv_item_id
        inner join prod_finish_dlv_rolls on prod_finish_dlv_rolls.id = so_aop_fabric_rcv_rols.prod_finish_dlv_roll_id
        inner join prod_batch_finish_qc_rolls on prod_batch_finish_qc_rolls.id = prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id

        inner join prod_batch_rolls on prod_batch_rolls.id = prod_batch_finish_qc_rolls.prod_batch_roll_id
        inner join prod_batches on prod_batches.id = prod_batch_rolls.prod_batch_id
        inner join colors batch_colors on batch_colors.id = prod_batches.batch_color_id
        inner join so_dyeing_fabric_rcv_rols on so_dyeing_fabric_rcv_rols.id = prod_batch_rolls.so_dyeing_fabric_rcv_rol_id

        inner join so_aop_refs on so_aop_refs.id = so_aop_fabric_rcv_items.so_aop_ref_id
        inner join so_aops on so_aops.id = so_aop_refs.so_aop_id
        inner join so_aop_pos on so_aop_pos.so_aop_id = so_aops.id
        inner join so_aop_po_items on so_aop_po_items.so_aop_ref_id = so_aop_refs.id
        inner join po_aop_service_item_qties on po_aop_service_item_qties.id = so_aop_po_items.po_aop_service_item_qty_id
        inner join po_aop_service_items on po_aop_service_items.id = po_aop_service_item_qties.po_aop_service_item_id and po_aop_service_items.deleted_at is null
        inner join budget_fabric_prods on budget_fabric_prods.id = po_aop_service_items.budget_fabric_prod_id
        inner join buyers  customers on customers.id = so_aops.buyer_id 

        ) aopbatch on aopbatch.prod_aop_batch_roll_id=prod_batch_finish_qc_rolls.prod_aop_batch_roll_id

        inner join sales_orders on  (sales_orders.id = dyeingbatch.sales_order_id or sales_orders.id = aopbatch.sales_order_id) 
        inner join jobs on jobs.id = sales_orders.job_id
        inner join styles on styles.id = jobs.style_id
        inner join buyers on buyers.id = styles.buyer_id

        inner join budget_fabrics on (budget_fabrics.id = dyeingbatch.budget_fabric_id or budget_fabrics.id = aopbatch.budget_fabric_id) 
        inner join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
        inner join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
        inner join constructions on constructions.id = autoyarns.construction_id

        inner join inv_grey_fab_isu_items on (inv_grey_fab_isu_items.id = dyeingbatch.inv_grey_fab_isu_item_id or inv_grey_fab_isu_items.id = aopbatch.inv_grey_fab_isu_item_id)
        inner join inv_isus grey_fab_isus on grey_fab_isus.id = inv_grey_fab_isu_items.inv_isu_id
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
        where inv_rcvs.id = ?  and inv_rcvs.deleted_at is null
        order by inv_finish_fab_rcv_items.id desc
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
        ->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart,$yarnDtls){
        $index=$prodknitqc->gmtspart_id."-".$prodknitqc->autoyarn_id."-".$prodknitqc->fabric_look_id."-".$prodknitqc->fabric_shape_id."-".$prodknitqc->gsm_weight."-".$prodknitqc->dia_width."-".$prodknitqc->batch_color_name."-".$prodknitqc->sale_order_no."-".$prodknitqc->stitch_length."-".$prodknitqc->style_ref."-".$prodknitqc->buyer_name;



        $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:'';
        $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:'';
        $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:'';
        $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:'';
        $prodknitqc->yarn=isset($yarnDtls[$index])?implode(' + ',$yarnDtls[$index]):'';
        $prodknitqc->fab_color_name=$prodknitqc->batch_color_name;
        $prodknitqc->machine_no='';
        $prodknitqc->machine_gg='';
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
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $header['logo']=$rows->logo;
        $header['address']=$rows->company_address;
        $header['title']='Finish Fabric Transfer In Report';
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
        $pdf->SetTitle('Finish Fabric Transfer In Report');
        $view= \View::make('Defult.Inventory.FinishFabric.InvFinishFabTransInPdfTwo',['data'=>$data]);
        $html_content=$view->render();
        $pdf->SetY(25);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/InvFinishFabTransInPdfTwo.pdf';
        $pdf->output($filename);
    }
    
}