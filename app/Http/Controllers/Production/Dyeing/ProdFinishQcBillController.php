<?php

namespace App\Http\Controllers\Production\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishDlvRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishQcBillItemRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Library\Numbertowords;
//use Illuminate\Support\Carbon;
use App\Http\Requests\Production\Dyeing\ProdFinishDlvRequest;

class ProdFinishQcBillController extends Controller {

    private $prodfinishdlv;
    private $prodfinishqcbillitem;
    private $store;
    private $location;
    private $company;
    private $buyer;
    private $gmtspart;
    private $itemaccount;

    public function __construct(
        ProdFinishDlvRepository $prodfinishdlv,
        ProdFinishQcBillItemRepository $prodfinishqcbillitem,
        StoreRepository $store,
        LocationRepository $location,
        CompanyRepository $company,
        BuyerRepository $buyer,
        GmtspartRepository $gmtspart,
        ItemAccountRepository $itemaccount
       ) {

        $this->prodfinishdlv = $prodfinishdlv;
        $this->prodfinishqcbillitem = $prodfinishqcbillitem;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->store = $store;
        $this->location = $location;
        $this->gmtspart = $gmtspart;
        $this->itemaccount = $itemaccount;
        

        $this->middleware('auth');
        // $this->middleware('permission:view.prodfinishqcbills',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.prodfinishqcbills', ['only' => ['store']]);
        // $this->middleware('permission:edit.prodfinishqcbills',   ['only' => ['update']]);
        // $this->middleware('permission:delete.prodfinishqcbills', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $rows=$this->prodfinishdlv
        ->leftJoin('companies', function($join)  {
            $join->on('prod_finish_dlvs.company_id', '=', 'companies.id');
        })
         ->leftJoin('locations', function($join)  {
           $join->on('prod_finish_dlvs.location_id', '=', 'locations.id');
        })
        ->leftJoin('buyers', function($join)  {
            $join->on('prod_finish_dlvs.buyer_id', '=', 'buyers.id');
        })
        ->leftJoin('stores', function($join)  {
            $join->on('prod_finish_dlvs.store_id', '=', 'stores.id');
        })
        ->where([['prod_finish_dlvs.dlv_to_finish_store','=',0]])
        ->where([['prod_finish_dlvs.menu_id','=',288]])
        ->orderBy('prod_finish_dlvs.id','desc')
        ->get([
            'prod_finish_dlvs.*',
            'companies.name as company_name',
            'locations.name as location_name',
            'buyers.name as buyer_name',
            'stores.name as store_name',
        ])->map(function($rows){
            $rows->dlv_date=date('d-M-Y',strtotime($rows->dlv_date));
            return $rows;
        });
        return response()->json($rows);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer/* ->whereNotNull('buyers.company_id') */->get(),'name','id'),'','');
        $store=array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        return Template::loadView('Production.Dyeing.ProdFinishQcBill',
        ['company'=>$company,'buyer'=>$buyer,'batchfor'=>$batchfor,'location'=> $location,'store'=>$store]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(ProdFinishDlvRequest $request) {
        $max = $this->prodfinishdlv->max('dlv_no');
        $dlv_no=$max+1;
		$prodfinishdlv=$this->prodfinishdlv->create([
            'dlv_no'=>$dlv_no,
            'dlv_date'=>$request->dlv_date,
            'company_id'=>$request->company_id,
            'buyer_id'=>$request->buyer_id,
            'store_id'=>$request->store_id,
            'remarks'=>$request->remarks,
            // 'location_id'=>$request->location_id,
            // 'driver_name'=>$request->driver_name,
            // 'driver_contact_no'=>$request->driver_contact_no,
            // 'driver_license_no'=>$request->driver_license_no,
            // 'lock_no'=>$request->lock_no,
            // 'truck_no'=>$request->truck_no,
            'dlv_to_finish_store'=>0,
            'menu_id'=>288,
        ]);

        if($prodfinishdlv){
            return response()->json(array('success' => true,'id' =>  $prodfinishdlv->id,'message' => 'Save Successfully'),200);
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
        $prodfinishdlv=$this->prodfinishdlv->find($id);
        $row ['fromData'] = $prodfinishdlv;
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

    public function update(ProdFinishDlvRequest $request, $id) {
        $prodfinishdlv=$this->prodfinishdlv->update($id,$request->except(['id','dlv_no','company_id','buyer_id']));
        if($prodfinishdlv){
            return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->prodfinishdlv->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
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
        ->join('users',function($join){
            $join->on('users.id','=','prod_finish_dlvs.created_by');
        })
        ->leftJoin('employee_h_rs',function($join){
            $join->on('users.id','=','employee_h_rs.user_id');
        })
        ->leftJoin('prod_finish_dlv_rolls',function($join){
            $join->on('prod_finish_dlv_rolls.prod_finish_dlv_id', '=', 'prod_finish_dlvs.id');
        })
        ->leftJoin('prod_batch_finish_qc_rolls',function($join){
            $join->on('prod_batch_finish_qc_rolls.id', '=', 'prod_finish_dlv_rolls.prod_batch_finish_qc_roll_id');
        })
        ->leftJoin('prod_batch_finish_qcs',function($join){
            $join->on('prod_batch_finish_qc_rolls.prod_batch_finish_qc_id', '=', 'prod_batch_finish_qcs.id');
        })
        ->leftJoin('prod_batches',function($join){
            $join->on('prod_batch_finish_qcs.prod_batch_id', '=', 'prod_batches.id');
        })
        ->leftJoin('prod_batch_rolls',function($join){
            $join->on('prod_batch_rolls.id', '=', 'prod_batch_finish_qc_rolls.prod_batch_roll_id');
        })
        ->leftJoin('so_dyeing_fabric_rcv_rols',function($join){
            $join->on('so_dyeing_fabric_rcv_rols.id', '=', 'prod_batch_rolls.so_dyeing_fabric_rcv_rol_id');
        })
        ->leftJoin('so_dyeing_fabric_rcv_items',function($join){
            $join->on('so_dyeing_fabric_rcv_rols.so_dyeing_fabric_rcv_item_id', '=', 'so_dyeing_fabric_rcv_items.id');
        })
        ->leftJoin('so_dyeing_refs',function($join){
            $join->on('so_dyeing_refs.id', '=', 'so_dyeing_fabric_rcv_items.so_dyeing_ref_id');
        })
        ->leftJoin('so_dyeings',function($join){
            $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
        })
        ->leftJoin('buyer_branches', function($join)  {
            $join->on('buyer_branches.buyer_id', '=', 'buyers.id');
        })
        ->leftJoin('currencies', function($join)  {
            $join->on('currencies.id', '=', 'so_dyeings.currency_id');
        })
        ->where([['prod_finish_dlvs.id','=',$id]])
        ->orderBy('prod_finish_dlvs.id','desc')
        ->get([
            'prod_finish_dlvs.*',
            'companies.name as company_name',
            'companies.logo as logo',
            'companies.address as company_address',
            'buyers.name as buyer_name',
            'buyer_branches.address as buyer_address',
            'currencies.code as currency_code',
            'currencies.name as currency_name',
            'currencies.hundreds_name',
            'stores.name as store_name',
            'stores.address as store_address',
            'users.name as user_name',
            'users.signature_file',
            'employee_h_rs.contact'
        ])
        ->first();
        $rows->dlv_date=date('d-M-Y',strtotime($rows->dlv_date));

        $rolldtls = collect(
        \DB::select("
        select
        m.style_ref,
        m.sale_order_no,
        m.autoyarn_id,
        m.gmtspart_id,
        m.fabric_look_id,
        m.fabric_shape_id,
        m.gsm_weight,
        m.dia,
        m.c_autoyarn_id,
        m.c_gmtspart_id,
        m.c_fabric_color_id,
        m.c_fabric_shape_id,
        m.c_fabric_look_id,
        m.c_gsm_weight,
        m.c_dia,
        m.gmt_sale_order_no,
        m.gmt_style_ref,
        m.dye_sale_order_no,
        m.batch_no,
        m.batch_color_name,
        m.fabric_color_name,
        m.c_fabric_color_name,
        sum(m.no_of_roll) as number_of_roll,
        sum(m.grey_qty) as qty,
        sum(m.amount) as amount,
        avg(m.rate) as rate
        from(
            select
            styles.style_ref,
            sales_orders.sale_order_no,
            style_fabrications.autoyarn_id,
            style_fabrications.gmtspart_id,
            style_fabrications.fabric_look_id,
            style_fabrications.fabric_shape_id,
            budget_fabrics.gsm_weight,
            po_dyeing_service_item_qties.dia,
            so_dyeing_items.autoyarn_id as c_autoyarn_id,
            so_dyeing_items.gmtspart_id as c_gmtspart_id,
            so_dyeing_items.fabric_color_id as c_fabric_color_id,
            so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
            so_dyeing_items.fabric_shape_id as c_fabric_look_id,
            so_dyeing_items.gmt_sale_order_no,
            so_dyeing_items.gmt_style_ref,
            so_dyeing_items.gsm_weight as c_gsm_weight,
            so_dyeing_items.dia as c_dia,
            so_dyeings.sales_order_no as dye_sale_order_no,
            prod_batches.batch_no,
            batch_color.name as batch_color_name,
            pocolors.name as fabric_color_name,
            socolors.name as c_fabric_color_name,
            prod_finish_qc_bill_items.no_of_roll,
            prod_finish_qc_bill_items.qty as grey_qty,
            prod_finish_qc_bill_items.amount,
            prod_finish_qc_bill_items.rate
            from
            prod_finish_dlvs
            inner join prod_finish_qc_bill_items on prod_finish_qc_bill_items.prod_finish_dlv_id=prod_finish_dlvs.id
            inner join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcv_items.id=prod_finish_qc_bill_items.so_dyeing_fabric_rcv_item_id
            inner join so_dyeing_refs on so_dyeing_refs.id = so_dyeing_fabric_rcv_items.so_dyeing_ref_id
            left join so_dyeings on so_dyeings.id = so_dyeing_refs.so_dyeing_id
            left join so_dyeing_pos on so_dyeing_pos.so_dyeing_id = so_dyeings.id
            left join so_dyeing_po_items on so_dyeing_po_items.so_dyeing_ref_id = so_dyeing_refs.id
            left join po_dyeing_service_item_qties on po_dyeing_service_item_qties.id = so_dyeing_po_items.po_dyeing_service_item_qty_id
            left join po_dyeing_service_items on po_dyeing_service_items.id = po_dyeing_service_item_qties.po_dyeing_service_item_id
            left join sales_orders on sales_orders.id = po_dyeing_service_item_qties.sales_order_id
            left join jobs on jobs.id = sales_orders.job_id
            left join styles on styles.id = jobs.style_id
            left join budget_fabric_prods on budget_fabric_prods.id = po_dyeing_service_items.budget_fabric_prod_id
            left join budget_fabrics on budget_fabrics.id = budget_fabric_prods.budget_fabric_id
            left join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
            left join autoyarns on autoyarns.id = style_fabrications.autoyarn_id
            left join constructions on constructions.id = autoyarns.construction_id
            left join so_dyeing_items on so_dyeing_items.so_dyeing_ref_id = so_dyeing_refs.id
            left join colors pocolors on  pocolors.id=po_dyeing_service_item_qties.fabric_color_id
            left join colors socolors on  socolors.id=so_dyeing_items.fabric_color_id
            inner join prod_batch_finish_qcs on prod_batch_finish_qcs.id=prod_finish_qc_bill_items.prod_batch_finish_qc_id
            inner join prod_batches on prod_batches.id=prod_batch_finish_qcs.prod_batch_id
            inner join colors batch_color on batch_color.id=prod_batches.fabric_color_id
            where (prod_finish_dlvs.id = ?) and prod_finish_dlvs.deleted_at is null
        )m
        group by
        m.style_ref,
        m.sale_order_no,
        m.autoyarn_id,
        m.gmtspart_id,
        m.fabric_look_id,
        m.fabric_shape_id,
        m.gsm_weight,
        m.dia,
        m.c_autoyarn_id,
        m.c_gmtspart_id,
        m.c_fabric_color_id,
        m.c_fabric_shape_id,
        m.c_fabric_look_id,
        m.c_gsm_weight,
        m.c_dia,
        m.gmt_sale_order_no,
        m.gmt_style_ref,
        m.dye_sale_order_no,
        m.batch_no,
        m.batch_color_name,
        m.fabric_color_name,
        m.c_fabric_color_name

        ",[$id])
        )
        ->map(function($prodknitqc) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart){
        $prodknitqc->body_part=$prodknitqc->gmtspart_id?$gmtspart[$prodknitqc->gmtspart_id]:$gmtspart[$prodknitqc->c_gmtspart_id];
        $prodknitqc->fabrication=$prodknitqc->autoyarn_id?$desDropdown[$prodknitqc->autoyarn_id]:$desDropdown[$prodknitqc->c_autoyarn_id];
        $prodknitqc->fabric_look=$prodknitqc->fabric_look_id?$fabriclooks[$prodknitqc->fabric_look_id]:$fabriclooks[$prodknitqc->c_fabric_look_id];
        $prodknitqc->fabric_shape=$prodknitqc->fabric_shape_id?$fabricshape[$prodknitqc->fabric_shape_id]:$fabricshape[$prodknitqc->c_fabric_shape_id];
        $prodknitqc->fabric_color_name=$prodknitqc->fabric_color_name?$prodknitqc->fabric_color_name:$prodknitqc->c_fabric_color_name;
        $prodknitqc->gsm_weight=$prodknitqc->gsm_weight?$prodknitqc->gsm_weight:$prodknitqc->c_gsm_weight;
        $prodknitqc->dia=$prodknitqc->dia?$prodknitqc->dia:$prodknitqc->c_dia;
        $prodknitqc->style_ref=$prodknitqc->style_ref?$prodknitqc->style_ref:$prodknitqc->gmt_style_ref;
        $prodknitqc->sale_order_no=$prodknitqc->sale_order_no?$prodknitqc->sale_order_no:$prodknitqc->gmt_sale_order_no;

        return $prodknitqc;
        });

        $amount=$rolldtls->sum('amount');
        $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name, $rows->hundreds_name);
        $rows->inword=$inword;

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
        $pdf->SetMargins(PDF_MARGIN_LEFT, '42', PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $header['logo']=$rows->logo;
        $header['address']=$rows->company_address;
        $header['title']='Additional Bill';
        $header['barcodestyle']= $barcodestyle;
        $header['barcodeno']= $challan;
        $pdf->setCustomHeader($header);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->AddPage();

        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTitle('Bill');
        $view= \View::make('Defult.Production.Dyeing.ProdFinishQcBillPdf',['data'=>$data]);
        $html_content=$view->render();
        $pdf->SetY(42);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/ProdFinishQcBillPdf.pdf';
        $pdf->output($filename);
        exit();
    }

}