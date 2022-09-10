<?php

namespace App\Http\Controllers\Inventory\FinishFabric;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabRcvRepository;
use App\Repositories\Contracts\Purchase\PoFabricRepository;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishDlvRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Library\Template;
use App\Http\Requests\Inventory\FinishFabric\InvFinishFabRcvPurRequest;

class InvFinishFabRcvPurController extends Controller {

    private $invrcv;
    private $invfinishfabrcv;
    private $pofabric;
    private $prodfinishdlv;
    private $gmtspart;
    private $company;
    private $location;
    private $currency;
    private $supplier;
    private $store;
    private $itemaccount;
    private $colorrange;

    public function __construct(
        InvRcvRepository $invrcv,
        InvFinishFabRcvRepository $invfinishfabrcv,
        PoFabricRepository $pofabric,
        ProdFinishDlvRepository $prodfinishdlv,
        GmtspartRepository $gmtspart,  
        CompanyRepository $company, 
        LocationRepository $location,
        CurrencyRepository $currency,
        SupplierRepository $supplier,
        StoreRepository $store,
        ItemAccountRepository $itemaccount,
        ColorrangeRepository $colorrange
    ) {
        $this->invrcv = $invrcv;
        $this->invfinishfabrcv = $invfinishfabrcv;
        $this->pofabric = $pofabric;
        $this->prodfinishdlv = $prodfinishdlv;
        $this->gmtspart = $gmtspart;
        $this->company = $company;
        $this->location = $location;
        $this->currency = $currency;
        $this->supplier = $supplier;
        $this->store = $store;
        $this->itemaccount = $itemaccount;
        $this->colorrange = $colorrange;
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
        ->join('po_fabrics',function($join){
        $join->on('po_fabrics.id','=','inv_finish_fab_rcvs.po_fabric_id');
        })
        ->where([['inv_rcvs.menu_id','=',224]])
        ->orderBy('inv_rcvs.id','desc')
        ->get([
        'inv_rcvs.*',
        'inv_finish_fab_rcvs.id as inv_finish_fab_rcv_id',
        'inv_finish_fab_rcvs.po_fabric_id',
        'po_fabrics.po_no',
        ]);
        foreach ($rows as $row) {
        $invyarnrcv['id']=$row->id;
        $invyarnrcv['inv_finish_fab_rcv_id']=$row->inv_finish_fab_rcv_id;
        $invyarnrcv['po_fabric_id']=$row->po_fabric_id;
        $invyarnrcv['receive_basis_id']=$invreceivebasis[$row->receive_basis_id];
        $invyarnrcv['company_id']=isset($company[$row->company_id])?$company[$row->company_id]:'';
        $invyarnrcv['supplier_id']=isset($supplier[$row->supplier_id])?$supplier[$row->supplier_id]:'';
        $invyarnrcv['receive_no']=$row->receive_no;
        $invyarnrcv['challan_no']=$row->challan_no;
        $invyarnrcv['po_no']=$row->po_no;
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
      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
      return Template::loadView('Inventory.FinishFabric.InvFinishFabRcvPur',[
            'company'=>$company,
            'currency'=>$currency, 
            'invreceivebasis'=>$invreceivebasis,
            'supplier'=>$supplier,
            'store'=>$store,
            'menu'=>$menu,
            'colorrange'=>$colorrange,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvFinishFabRcvPurRequest $request) {
        $pofabric=$this->pofabric->find($request->po_fabric_id);
        $max=$this->invrcv
        ->where([['company_id','=',$pofabric->company_id]])
        ->whereIn('menu_id',[224,225,226,227])
        ->max('receive_no');
        $receive_no=$max+1;
        $invrcv=$this->invrcv->create([
        'menu_id'=>224,
        'receive_no'=>$receive_no,
        'company_id'=>$pofabric->company_id,
        'receive_basis_id'=>1,
        'receive_against_id'=>1,
        'supplier_id'=>$pofabric->supplier_id,
        'challan_no'=>$request->challan_no,
        'receive_date'=>$request->receive_date,
        'remarks'=>$request->remarks,
        ]);

        $invfinishfabrcv=$this->invfinishfabrcv->create([
        'menu_id'=>224,
        'inv_rcv_id'=>$invrcv->id,
        'po_fabric_id'=>$request->po_fabric_id,
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
        ->join('po_fabrics',function($join){
            $join->on('po_fabrics.id','=','inv_finish_fab_rcvs.po_fabric_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','inv_rcvs.company_id');
        })
        ->join('suppliers',function($join){
            $join->on('suppliers.id','=','inv_rcvs.supplier_id');
        })
        
        ->where([['inv_rcvs.id','=',$id]])
        ->get([
            'inv_rcvs.*',
            'inv_finish_fab_rcvs.id  as inv_finish_fab_rcv_id',
            'po_fabrics.po_no',
            'inv_finish_fab_rcvs.po_fabric_id',
            'companies.name as company_name',
            'suppliers.name as supplier_name'
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
    public function update(InvFinishFabRcvPurRequest $request, $id) {
        //$prodfinishdlv=$this->prodfinishdlv->find($request->prod_finish_dlv_id);
        $invfinishfabrcv=$this->invrcv->update($id,$request->except(['id','po_fabric_id','po_no','inv_finish_fab_rcv_id','company_id','receive_basis_id','receive_against_id','supplier_id']));
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

    public function getPo()
    {
        $source = array_prepend(config('bprs.purchasesource'),'-Select-','');
        $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
        $rows=$this->pofabric
        ->selectRaw('
          po_fabrics.id,
          po_fabrics.po_no,
          po_fabrics.po_date,
          po_fabrics.company_id,
          po_fabrics.supplier_id,
          po_fabrics.source_id,
          po_fabrics.pay_mode,
          po_fabrics.delv_start_date,
          po_fabrics.delv_end_date,
          po_fabrics.pi_no,
          po_fabrics.pi_date,
          po_fabrics.exch_rate,
          po_fabrics.remarks,
          companies.name as company_name,
          suppliers.name as supplier_name,
          currencies.code as currency_code,
          po_fabrics.amount
        ')
        ->join('companies',function($join){
          $join->on('companies.id','=','po_fabrics.company_id');
        })
        ->join('suppliers',function($join){
          $join->on('suppliers.id','=','po_fabrics.supplier_id');
        })
        ->join('currencies',function($join){
          $join->on('currencies.id','=','po_fabrics.currency_id');
        })
        ->where([['po_type_id', 1]])
        ->when(request('company_id'), function ($q) {
            return $q->where('po_fabrics.company_id', '=', request('company_id', 0));
        })
        ->when(request('supplier_id'), function ($q) {
            return $q->where('po_fabrics.supplier_id', '=', request('supplier_id', 0));
        })
        ->when(request('po_no'), function ($q) {
            return $q->where('po_fabrics.po_no', '=', request('po_no', 0));
        })
        ->orderBy('po_fabrics.id','desc')
        
        ->get()
        ->map(function($rows) use($source,$paymode){
          $rows->source=isset($source[$rows->source_id])?$source[$rows->source_id]:'';
          $rows->paymode=isset($paymode[$rows->pay_mode])?$paymode[$rows->pay_mode]:'';
          $rows->amount = number_format($rows->amount,2);
          $rows->delv_start_date=date('d-M-Y',strtotime($rows->delv_start_date));
          $rows->po_date=$rows->po_date?date('d-M-Y',strtotime($rows->po_date)):'--';
          $rows->pi_date=$rows->pi_date?date('d-M-Y',strtotime($rows->pi_date)):'--';
          $rows->delv_end_date=date('d-M-Y',strtotime($rows->delv_end_date));
          return $rows;
        });

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
        ->leftJoin('companies', function($join)  {
            $join->on('inv_rcvs.company_id', '=', 'companies.id');
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
            'companies.name as company_name',
            'companies.logo as logo',
            'companies.address as company_address',
            'users.name as user_name',
            'employee_h_rs.contact',
            'inv_rcvs.menu_id'
        ])
        ->first();
        $rows->receive_date=date('d-M-Y',strtotime($rows->receive_date));

        $rolldtls = collect(
        \DB::select("
            select 
            m.gmtspart_id,
            m.autoyarn_id,
            m.fabric_look_id,
            m.fabric_shape_id,
            m.gsm_weight,
            m.dia,
            m.measurment,
            m.roll_length,
            m.stitch_length,
            m.shrink_per,
            m.fab_color_name,
            sum(m.qc_pass_qty) as qty,
            sum(id) as number_of_roll 
            from (
            select 
            inv_finish_fab_rcv_items.id,   
            inv_finish_fab_items.gsm_weight,   
            inv_finish_fab_items.dia,
            inv_finish_fab_items.stitch_length,
            inv_finish_fab_items.measurment,   
            inv_finish_fab_items.roll_length,   
            inv_finish_fab_items.shrink_per,      
            inv_finish_fab_rcv_items.qty as qc_pass_qty,   
            
            buyers.name as buyer_name,
            styles.style_ref,
            sales_orders.sale_order_no,
            style_fabrications.autoyarn_id,
            style_fabrications.gmtspart_id,
            style_fabrications.fabric_look_id,
            style_fabrications.fabric_shape_id,
            colors.name as fab_color_name
            
            from 
            inv_rcvs
            inner join inv_finish_fab_rcvs on inv_finish_fab_rcvs.inv_rcv_id=inv_rcvs.id
            inner join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.inv_finish_fab_rcv_id=inv_finish_fab_rcvs.id
            inner join inv_finish_fab_items on inv_finish_fab_rcv_items.inv_finish_fab_item_id=inv_finish_fab_items.id
            inner join inv_finish_fab_rcv_fabrics on inv_finish_fab_rcv_fabrics.id=inv_finish_fab_rcv_items.inv_finish_fab_rcv_fabric_id
            inner join po_fabric_items on inv_finish_fab_rcv_fabrics.po_fabric_item_id=po_fabric_items.id
            inner join po_fabrics on po_fabrics.id=po_fabric_items.po_fabric_id
            inner join budget_fabrics on budget_fabrics.id=po_fabric_items.budget_fabric_id
            inner join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
            left join style_gmts on style_gmts.id=style_fabrications.style_gmt_id
            left join item_accounts on item_accounts.id=style_gmts.item_account_id
            inner join sales_orders on inv_finish_fab_rcv_fabrics.sales_order_id=sales_orders.id
            inner join jobs on jobs.id = sales_orders.job_id
            inner join styles on styles.id = jobs.style_id
            left join colors on  colors.id=inv_finish_fab_rcv_fabrics.fabric_color_id
            left join gmtsparts on gmtsparts.id=style_fabrications.gmtspart_id
            left join buyers on styles.buyer_id=styles.id
            where (inv_rcvs.id = ?) and inv_rcvs.deleted_at is null
            ) m  
            group by 
            m.gmtspart_id,
            m.autoyarn_id,
            m.fabric_look_id,
            m.fabric_shape_id,
            m.gsm_weight,
            m.dia,
            m.measurment,
            m.roll_length,
            m.stitch_length,
            m.shrink_per,
            m.fab_color_name",[$id])
        )
        ->map(function($rolldtls) use($desDropdown,$fabriclooks,$fabricshape,$gmtspart){
            $rolldtls->body_part=$rolldtls->gmtspart_id?$gmtspart[$rolldtls->gmtspart_id]:'';
            $rolldtls->fabrication=$rolldtls->autoyarn_id?$desDropdown[$rolldtls->autoyarn_id]:'';
            $rolldtls->fabric_look=$rolldtls->fabric_look_id?$fabriclooks[$rolldtls->fabric_look_id]:'';
            $rolldtls->fabric_shape=$rolldtls->fabric_shape_id?$fabricshape[$rolldtls->fabric_shape_id]:'';
            return $rolldtls;
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
        $view= \View::make('Defult.Inventory.FinishFabric.InvFinishFabRcvPurPdf',['data'=>$data]);
        $html_content=$view->render();
        $pdf->SetY(42);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/InvFinishFabRcvPurPdf.pdf';
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
        ->leftJoin('companies', function($join)  {
            $join->on('inv_rcvs.company_id', '=', 'companies.id');
        })
        ->leftJoin('users',function($join){
            $join->on('users.id','=','inv_rcvs.created_by');
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
            'users.name as user_name',
            'employee_h_rs.contact',
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


        $rolldtls = collect(
        \DB::select("
            select 
            m.gmtspart_id,
            m.autoyarn_id,
            m.fabric_look_id,
            m.fabric_shape_id,
            m.gsm_weight,
            m.dia,
            m.measurment,
            m.roll_length,
            m.stitch_length,
            m.shrink_per,
            m.fab_color_name,
            m.buyer_name,
            m.style_ref,
            m.sale_order_no,
            sum(m.qc_pass_qty) as qty,
            sum(id) as number_of_roll 
            from (
            select 
            inv_finish_fab_rcv_items.id,   
            inv_finish_fab_items.gsm_weight,   
            inv_finish_fab_items.dia,
            inv_finish_fab_items.stitch_length,
            inv_finish_fab_items.measurment,   
            inv_finish_fab_items.roll_length,   
            inv_finish_fab_items.shrink_per,      
            inv_finish_fab_rcv_items.qty as qc_pass_qty,   
            
            buyers.name as buyer_name,
            styles.style_ref,
            sales_orders.sale_order_no,
            style_fabrications.autoyarn_id,
            style_fabrications.gmtspart_id,
            style_fabrications.fabric_look_id,
            style_fabrications.fabric_shape_id,
            colors.name as fab_color_name
            
            from 
            inv_rcvs
            inner join inv_finish_fab_rcvs on inv_finish_fab_rcvs.inv_rcv_id=inv_rcvs.id
            inner join inv_finish_fab_rcv_items on inv_finish_fab_rcv_items.inv_finish_fab_rcv_id=inv_finish_fab_rcvs.id
            inner join inv_finish_fab_items on inv_finish_fab_rcv_items.inv_finish_fab_item_id=inv_finish_fab_items.id
            inner join inv_finish_fab_rcv_fabrics on inv_finish_fab_rcv_fabrics.id=inv_finish_fab_rcv_items.inv_finish_fab_rcv_fabric_id
            inner join po_fabric_items on inv_finish_fab_rcv_fabrics.po_fabric_item_id=po_fabric_items.id
            inner join po_fabrics on po_fabrics.id=po_fabric_items.po_fabric_id
            inner join budget_fabrics on budget_fabrics.id=po_fabric_items.budget_fabric_id
            inner join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
            left join style_gmts on style_gmts.id=style_fabrications.style_gmt_id
            left join item_accounts on item_accounts.id=style_gmts.item_account_id
            inner join sales_orders on inv_finish_fab_rcv_fabrics.sales_order_id=sales_orders.id
            inner join jobs on jobs.id = sales_orders.job_id
            inner join styles on styles.id = jobs.style_id
            left join colors on  colors.id=inv_finish_fab_rcv_fabrics.fabric_color_id
            left join gmtsparts on gmtsparts.id=style_fabrications.gmtspart_id
            left join buyers on styles.buyer_id=styles.id
            where (inv_rcvs.id = ?) and inv_rcvs.deleted_at is null
            ) m  
            group by 
            m.gmtspart_id,
            m.autoyarn_id,
            m.fabric_look_id,
            m.fabric_shape_id,
            m.gsm_weight,
            m.dia,
            m.measurment,
            m.roll_length,
            m.stitch_length,
            m.shrink_per,
            m.fab_color_name,
            m.buyer_name,
            m.style_ref,
            m.sale_order_no
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
        $view= \View::make('Defult.Inventory.FinishFabric.InvFinishFabRcvPurPdfTwo',['data'=>$data]);
        $html_content=$view->render();
        $pdf->SetY(42);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/InvFinishFabRcvPurPdfTwo.pdf';
        $pdf->output($filename);
    }
}