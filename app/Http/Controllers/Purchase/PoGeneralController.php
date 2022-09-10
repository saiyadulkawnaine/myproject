<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;


use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoGeneralRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\TermsConditionRepository;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Library\Template;
use App\Library\Numbertowords;
use App\Http\Requests\Purchase\PoGeneralRequest;


class PoGeneralController extends Controller
{
   private $pogeneral;
   private $company;
   private $supplier;
   private $currency;
   private $itemcategory;
   private $termscondition;
   private $purchasetermscondition;
   private $itemclass;

	public function __construct(
    PoGeneralRepository $pogeneral,
    CompanyRepository $company,
    SupplierRepository $supplier,
    BuyerRepository $buyer,
    CurrencyRepository $currency,
    ItemcategoryRepository $itemcategory,
    TermsConditionRepository $termscondition,
    PurchaseTermsConditionRepository $purchasetermscondition,
    ItemclassRepository $itemclass,
    ImpLcPoRepository $implcpo

  )
	{
    $this->pogeneral = $pogeneral;
		$this->company = $company;
    $this->supplier = $supplier;
		$this->buyer = $buyer;
		$this->currency = $currency;
		$this->itemcategory = $itemcategory;
    $this->termscondition = $termscondition;
    $this->purchasetermscondition = $purchasetermscondition;
    $this->itemclass     = $itemclass;
    $this->implcpo = $implcpo;
		$this->middleware('auth');
		$this->middleware('permission:view.pogenerals',   ['only' => ['create', 'index','show']]);
		$this->middleware('permission:create.pogenerals', ['only' => ['store']]);
		$this->middleware('permission:edit.pogenerals',   ['only' => ['update']]);
		$this->middleware('permission:delete.pogenerals', ['only' => ['destroy']]);
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $source = array_prepend(config('bprs.purchasesource'),'-Select-','');
        $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
        $rows=$this->pogeneral
        ->selectRaw('
          po_generals.id,
          po_generals.po_no,
          po_generals.po_date,
          po_generals.company_id,
          po_generals.supplier_id,
          po_generals.source_id,
          po_generals.pay_mode,
          po_generals.delv_start_date,
          po_generals.delv_end_date,
          po_generals.exch_rate,
          po_generals.approved_at,
          companies.code as company_code,
          suppliers.name as supplier_code,
          currencies.code as currency_code,
          sum(po_general_items.qty) as item_qty,
          po_generals.amount
        ')
        ->leftJoin('po_general_items', function($join){
          $join->on('po_general_items.po_general_id', '=', 'po_generals.id');
        })
        ->join('companies',function($join){
        	$join->on('companies.id','=','po_generals.company_id');
        })
        ->join('suppliers',function($join){
        	$join->on('suppliers.id','=','po_generals.supplier_id');
        })
        ->join('currencies',function($join){
        	$join->on('currencies.id','=','po_generals.currency_id');
        })
        ->where([['po_type_id', 1]])
	    ->orderBy('po_generals.id','desc')
	    ->groupBy([
          'po_generals.id',
          'po_generals.po_no',
          'po_generals.po_date',
          'po_generals.company_id',
          'po_generals.supplier_id',
          'po_generals.source_id',
          'po_generals.pay_mode',
          'po_generals.delv_start_date',
          'po_generals.delv_end_date',
          'po_generals.exch_rate',
          'po_generals.approved_at',
          'companies.code',
          'suppliers.name',
          'currencies.code',
          'po_generals.amount'
          ])
        ->get()
        ->map(function($rows) use($source,$paymode){
          $rows->source=$source[$rows->source_id];
          $rows->paymode=$paymode[$rows->pay_mode];
          $rows->item_qty = number_format($rows->item_qty,2);
          $rows->amount = number_format($rows->amount,2);
          $rows->delv_start_date=date('d-M-Y',strtotime($rows->delv_start_date));
          $rows->po_date=date('d-M-Y',strtotime($rows->po_date));
          $rows->delv_end_date=date('d-M-Y',strtotime($rows->delv_end_date));
          $rows->approve_status=($rows->approved_at)?$rows->approve_status="Approved":'--';
          return $rows;
        });
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $company=array_prepend(array_pluck($this->company->where([['status_id','=',1]])->get(),'name','id'),'-Select-','');
      $source = array_prepend(config('bprs.purchasesource'),'-Select-','');
      $basis = array_only(config('bprs.pur_order_basis'), [2]);
      $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->GeneralItemSupplier(),'name','id'),'','');
      $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
      $itemcategory=array_prepend(array_pluck($this->itemcategory->get(),'name','id'),'-Select-','');
      $itemclass=array_prepend(array_pluck($this->itemclass->get(),'name','id'),'-Select-','');
      $indentor=array_prepend(array_pluck($this->supplier->indentor(),'name','id'),'','');


      return Template::loadView("Purchase.PoGeneral", ['company'=>$company,'source'=>$source,'supplier'=>$supplier,'buyer'=>$buyer,'currency'=>$currency,'paymode'=>$paymode,'order_type_id'=>1,'basis'=>$basis,'itemcategory'=>$itemcategory,'itemclass'=>$itemclass,'indentor'=>$indentor]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoGeneralRequest $request)
    {
      $supplierId=$this->supplier->find($request->supplier_id);
      if($supplierId->status_id==0){
          return response()->json(array('success' => false,  'message' => 'Purchase Order not allowed to inactive Supplier'), 200);
      }
      elseif($supplierId->status_id==1 || $supplierId->status_id=='') {
        $max = $this->pogeneral->where([['company_id', $request->company_id]])->max('po_no');
        $po_no=$max+1;

        $pogeneral = $this->pogeneral->create(['po_no'=>$po_no,'po_type_id'=>1,'po_date'=>$request->po_date,'company_id'=>$request->company_id,'source_id'=>$request->source_id,'basis_id'=>$request->basis_id,'supplier_id'=>$request->supplier_id,'currency_id'=>$request->currency_id,'exch_rate'=>$request->exch_rate,'delv_start_date'=>$request->delv_start_date,'delv_end_date'=>$request->delv_end_date,'pay_mode'=>$request->pay_mode,'pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'remarks'=>$request->remarks,'indentor_id'=>$request->indentor_id]);

        $termscondition=$this->termscondition->where([['menu_id','=',8]])->orderBy('sort_id')->get(['terms_conditions.*']);
        foreach($termscondition as $row)
        {
        $purchasetermscondition = $this->purchasetermscondition->create(['purchase_order_id'=>$pogeneral->id,'term'=>$row->term,'sort_id'=>$row->sort_id,'menu_id'=>8]);
        }

        if ($pogeneral) {
        return response()->json(array('success' => true, 'id' => $pogeneral->id, 'message' => 'Save Successfully'), 200);
        }
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
      $pogeneral = $this->pogeneral->find($id);
      $row ['fromData'] = $pogeneral;
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
    public function update(PoGeneralRequest $request, $id)
    {
      $approved=$this->pogeneral->find($id);
      if($approved->approved_at){
        $this->pogeneral->update($id, ['pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'remarks'=>$request->remarks]);
        return response()->json(array('success' => false,  'message' => 'General Item Purchase Order is Approved, Update not Possible Except PI No , PI Date & Remarks'), 200);
      }

      $implcpo=$this->implcpo
      ->join('imp_lcs',function($join){
        $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
      })
      ->where([['imp_lcs.menu_id','=',8]])
      ->where([['imp_lc_pos.purchase_order_id','=',$id]])
      ->get(['imp_lc_pos.purchase_order_id'])
      ->first();
      if ($implcpo) {
        $pogeneral = $this->pogeneral->update($id, $request->except(['id','company_id','supplier_id']));
      }
      else{
        $pogeneral = $this->pogeneral->update($id, $request->except(['id','company_id']));
      }
      /*$termscondition=$this->termscondition->where([['menu_id','=',8]])->orderBy('sort_id')->get();
      foreach($termscondition as $row)
      {
      $purchasetermscondition = $this->purchasetermscondition->create(['purchase_order_id'=>$id,'term'=>$row->term,'sort_id'=>$row->sort_id]);
      }*/
      if ($pogeneral) {
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
      if($this->pogeneral->delete($id)){
  			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
  		}
    }

    public function getPdf()
    {

      $id=request('id',0);
      $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
      $rows=$this->pogeneral
      ->join('companies',function($join){
      $join->on('companies.id','=','po_generals.company_id');
      })
      ->join('currencies',function($join){
      $join->on('currencies.id','=','po_generals.currency_id');
      })
      ->join('suppliers',function($join){
      $join->on('suppliers.id','=','po_generals.supplier_id');
      })
      ->join('users',function($join){
      $join->on('users.id','=','po_generals.created_by');
      })
      ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->leftJoin('users as approve_users',function($join){
        $join->on('approve_users.id','=','po_generals.approved_by');
      })
      ->leftJoin('employee_h_rs as approve_employee',function($join){
        $join->on('approve_employee.user_id','=','approve_users.id');
      })
      ->leftJoin('designations',function($join){
        $join->on('approve_employee.designation_id','=','designations.id');
      })
      ->where([['po_generals.id','=',$id]])
      ->get([
        'po_generals.*',
        'po_generals.id as po_general_id',
        'companies.name as company_name',
        'companies.logo as logo',
        'companies.address as company_address',
        'currencies.code as currency_name',
        'suppliers.name as supplier_name',
        'suppliers.address as supplier_address',
        'suppliers.contact_person',
        'suppliers.designation',
        'suppliers.email',
        'users.name as user_name',
        'employee_h_rs.contact',
        'approve_users.signature_file',
        'approve_employee.name as approval_emp_name',
        'approve_employee.contact as approval_emp_contact',
        'designations.name as approval_emp_designation'
      ])
      ->first();
      $rows->pay_mode=$paymode[$rows->pay_mode];
      $rows->po_date=date('d-M-Y',strtotime($rows->po_date));
      $rows->delv_start_date=date('d-M-Y',strtotime($rows->delv_start_date));
      $rows->delv_end_date=date('d-M-Y',strtotime($rows->delv_end_date));
      $rows->contact_detail=$rows->contact_person.','.$rows->designation.','.$rows->email;
      $rows->approved_user_signature=$rows->signature_file?'images/signature/'.$rows->signature_file:null;
      $rows->approved_at=$rows->approved_at?date('d-M-Y',strtotime($rows->approved_at)):null;

      $data=$this->pogeneral
      ->selectRaw(
      '
        inv_pur_reqs.requisition_no,
        itemcategories.name as category_name,
        itemclasses.name as class_name,
        item_accounts.sub_class_name,
        item_accounts.item_description,
        item_accounts.specification,
        uoms.code as uom_code,
        po_general_items.id,
        po_general_items.remarks as item_remarks,
        sum(po_general_items.qty) as qty,
        avg(po_general_items.rate) as rate,
        sum(po_general_items.amount) as amount
      '
      )
      ->join('po_general_items', function($join){
        $join->on('po_general_items.po_general_id', '=', 'po_generals.id');
      })
      ->join('inv_pur_req_items', function($join){
        $join->on('inv_pur_req_items.id', '=', 'po_general_items.inv_pur_req_item_id');
      })
      ->join('item_accounts', function($join){
        $join->on('item_accounts.id', '=', 'inv_pur_req_items.item_account_id');
      })
      ->join('itemclasses', function($join){
        $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
      })
      ->join('itemcategories', function($join){
        $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
      })
      ->join('inv_pur_reqs', function($join){
        $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
      })
      ->leftJoin('uoms', function($join){
        $join->on('uoms.id', '=', 'item_accounts.uom_id');
      })
      ->where([['po_generals.id','=',$id]])
      ->orderBy('po_general_items.id','asc')
      ->groupBy([
        'inv_pur_reqs.requisition_no',
        'itemcategories.name',
        'itemclasses.name',
        'item_accounts.sub_class_name',
        'item_accounts.item_description',
        'item_accounts.specification',
        'uoms.code',
        'po_general_items.id',
        'po_general_items.remarks'
      ])
      ->get();
      $amount=$data->sum('amount');
      //$details=$data->groupBy('requisition_no');

      $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents');
      $rows->inword=$inword;
      $purOrder['master']=$rows;
      $purOrder['created_at']=date('d-M-Y',strtotime($rows->created_at)) ;
      //$purOrder['details']=$details;
      $purchasetermscondition=$this->purchasetermscondition->where([['purchase_order_id','=',$id]])->where([['menu_id','=',8]])->orderBy('sort_id')->get();
      $purOrder['purchasetermscondition']=$purchasetermscondition;
      $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetPrintHeader(false);
      $pdf->SetPrintFooter(false);
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->AddPage();
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
    $pdf->SetY(5);
    $pdf->SetX(150);
    $challan=str_pad($purOrder['master']->po_general_id,10,0,STR_PAD_LEFT ) ;
    $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
      $pdf->SetY(10);
      $image_file ='images/logo/'.$rows->logo;
      $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
      $pdf->SetY(12);
      $pdf->SetFont('helvetica', 'N', 8);
      //$pdf->Text(70, 12, $rows->company_address);
      $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
      $pdf->SetY(16);
      $pdf->SetFont('helvetica', 'N', 10);
      $pdf->Write(0, '', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      //$pdf->SetTitle('General Item Purchase Order');

      $view= \View::make('Defult.Purchase.PoGeneralPdf',['purOrder'=>$purOrder,'data'=>$data]);
      $html_content=$view->render();
      $pdf->SetY(35);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/PoGeneralPdf.pdf';
      $pdf->output($filename);
    }

    public function getPdfTopSheet()
    {

      $id=request('id',0);

      $rqrows=$this->pogeneral
      ->join('po_general_items', function($join){
          $join->on('po_general_items.po_general_id', '=', 'po_generals.id');
      })
      ->join('inv_pur_req_items', function($join){
          $join->on('inv_pur_req_items.id', '=', 'po_general_items.inv_pur_req_item_id');
      })
      ->join('inv_pur_reqs', function($join){
          $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
      })
      
      
      ->whereIn('po_generals.id',explode(',',$id))
      ->get([
        'po_generals.*',
        'inv_pur_reqs.id as rq_id',
        'inv_pur_reqs.requisition_no as rq_no',
      ]);
      $rqnos=[];
      foreach($rqrows as $row)
      {
        $rqnos[$row->id]['rq_no'][$row->rq_id]=$row->rq_no;

      }

      $rows=$this->pogeneral
      
      ->join('companies',function($join){
        $join->on('companies.id','=','po_generals.company_id');
      })
      ->join('currencies',function($join){
        $join->on('currencies.id','=','po_generals.currency_id');
      })
      ->join('suppliers',function($join){
        $join->on('suppliers.id','=','po_generals.supplier_id');
      })
      ->join('users',function($join){
        $join->on('users.id','=','po_generals.created_by');
      })
      
      ->whereIn('po_generals.id',explode(',',$id))
      ->orderBy('po_generals.id')
      ->get([
        'po_generals.*',
        'companies.code as company_name',
        'companies.logo as logo',
        'companies.address as company_address',
        'currencies.code as currency_name',
        'suppliers.name as supplier_name',
        'suppliers.address as supplier_address',
        'suppliers.contact_person',
        'suppliers.designation',
        'suppliers.email',
        'users.name as user_name',
      ])
      ->map(function($rows) use($rqnos){
        $rows->rq_no=isset($rqnos[$rows->id]['rq_no'])?implode(',',$rqnos[$rows->id]['rq_no']):'';
        $rows->po_date=date('d-M-Y',strtotime($rows->po_date));
        $rows->delv_end_date=date('d-M-Y',strtotime($rows->delv_end_date));
        $rows->amount_taka=0;
        if($rows->currency_name=='BDT'){
          $rows->amount_taka=$rows->amount;
        }
        else{
          $rows->amount_taka=$rows->amount*$rows->exch_rate;
        }
        $rows->exch_rate_c=0;
        if($rows->currency_name=='BDT'){
          $rows->exch_rate_c=1;
        }
        else{
          $rows->exch_rate_c=$rows->exch_rate;
        }
        return $rows;

      });
      //echo json_encode($rows);
      //die;
      //$rows->po_date=date('d-M-Y',strtotime($rows->po_date));
      //$rows->delv_end_date=date('d-M-Y',strtotime($rows->delv_end_date));
      

      

      $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetPrintHeader(false);
      $pdf->SetPrintFooter(false);
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->AddPage();
      //$pdf->SetY(10);
      //$image_file ='images/logo/'.$rows->logo;
      //$pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
      //$pdf->SetY(12);
      //$pdf->SetFont('helvetica', 'N', 8);
      //$pdf->Text(70, 12, $rows->company_address);
      //$pdf->SetY(16);
      //$pdf->AddPage();
       
      //$pdf->SetY(5);
      //$pdf->SetX(150);
      //$pdf->SetFont('helvetica', 'N', 10);
      //$pdf->Write(0, 'Dyes/Chemical Purchase Order', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      //$pdf->SetTitle('General Item Purchase Order');
      $view= \View::make('Defult.Purchase.PoGeneralTopSheetPdf',['purOrder'=>$rows]);
      $html_content=$view->render();
      $pdf->SetY(5);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/PoGeneralTopSheetPdf.pdf';
      $pdf->output($filename);

    }

  public function getSearchGeneral()
  {
    $source = array_prepend(config('bprs.purchasesource'), '-Select-', '');
    $paymode = array_prepend(config('bprs.paymode'), '-Select-', '');
    $rows = $this->pogeneral
    ->selectRaw('
      po_generals.id,
      po_generals.po_no,
      po_generals.po_date,
      po_generals.company_id,
      po_generals.supplier_id,
      po_generals.source_id,
      po_generals.pay_mode,
      po_generals.delv_start_date,
      po_generals.delv_end_date,
      po_generals.exch_rate,
      po_generals.approved_at,
      companies.code as company_code,
      suppliers.name as supplier_code,
      currencies.code as currency_code,
      sum(po_general_items.qty) as item_qty,
      po_generals.amount
    ')
   ->leftJoin('po_general_items', function ($join) {
    $join->on('po_general_items.po_general_id', '=', 'po_generals.id');
   })
   ->join('companies', function ($join) {
    $join->on('companies.id', '=', 'po_generals.company_id');
   })
   ->join('suppliers', function ($join) {
    $join->on('suppliers.id', '=', 'po_generals.supplier_id');
   })
   ->join('currencies', function ($join) {
    $join->on('currencies.id', '=', 'po_generals.currency_id');
   })
   ->when(request('po_no'), function ($q) {
    return $q->where('po_generals.po_no', '=', request('po_no',0));
   })
   ->when(request('supplier_search_id'), function ($q) {
    return $q->where('suppliers.id', '=', request('supplier_search_id',0));
   })
   ->when(request('company_search_id'), function ($q) {
    return $q->where('companies.id', '=', request('company_search_id',0));
   })
   ->when(request('from_date'), function ($q) {
    return $q->where('po_generals.po_date', '>=', request('from_date',0));
   })
   ->when(request('to_date'), function ($q) {
    return $q->where('po_generals.po_date', '<=', request('to_date',0));
   })
   ->where([['po_type_id', 1]])
   ->orderBy('po_generals.id', 'desc')
   ->groupBy([
    'po_generals.id',
    'po_generals.po_no',
    'po_generals.po_date',
    'po_generals.company_id',
    'po_generals.supplier_id',
    'po_generals.source_id',
    'po_generals.pay_mode',
    'po_generals.delv_start_date',
    'po_generals.delv_end_date',
    'po_generals.exch_rate',
    'po_generals.approved_at',
    'companies.code',
    'suppliers.name',
    'currencies.code',
    'po_generals.amount'
   ])
   ->get()
   ->map(function ($rows) use ($source, $paymode) {
    $rows->source = $source[$rows->source_id];
    $rows->paymode = $paymode[$rows->pay_mode];
    $rows->item_qty = number_format($rows->item_qty, 2);
    $rows->amount = number_format($rows->amount, 2);
    $rows->delv_start_date = date('d-M-Y', strtotime($rows->delv_start_date));
    $rows->po_date = date('d-M-Y', strtotime($rows->po_date));
    $rows->delv_end_date = date('d-M-Y', strtotime($rows->delv_end_date));
    $rows->approve_status = ($rows->approved_at) ? $rows->approve_status = "Approved" : '--';
    return $rows;
   });
  echo json_encode($rows);
 }

}
