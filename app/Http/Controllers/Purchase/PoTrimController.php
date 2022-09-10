<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;


use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoTrimRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\TermsConditionRepository;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Library\Template;
use App\Library\Numbertowords;
use App\Http\Requests\Purchase\PoTrimRequest;


class PoTrimController extends Controller
{
   private $potrim;
   private $company;
   private $supplier;
   private $buyer;
   private $currency;
   private $itemcategory;
   private $termscondition;
   private $purchasetermscondition;

	public function __construct(
    PoTrimRepository $potrim,
    CompanyRepository $company,
    SupplierRepository $supplier,
    BuyerRepository $buyer,
    CurrencyRepository $currency,
    ItemcategoryRepository $itemcategory,
    TermsConditionRepository $termscondition,
    PurchaseTermsConditionRepository $purchasetermscondition,
    ImpLcPoRepository $implcpo
  )
	{
    $this->potrim = $potrim;
    $this->company = $company;
    $this->supplier = $supplier;
    $this->buyer = $buyer;
    $this->currency = $currency;
    $this->itemcategory = $itemcategory;
    $this->termscondition = $termscondition;
    $this->purchasetermscondition = $purchasetermscondition;
    $this->implcpo = $implcpo;
    
		$this->middleware('auth');
		$this->middleware('permission:view.potrims',   ['only' => ['create', 'index','show']]);
		$this->middleware('permission:create.potrims', ['only' => ['store']]);
		$this->middleware('permission:edit.potrims',   ['only' => ['update']]);
		$this->middleware('permission:delete.potrims', ['only' => ['destroy']]);
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
      $rows=$this->potrim
      ->join('companies',function($join){
        $join->on('companies.id','=','po_trims.company_id');
      })
      ->join('suppliers',function($join){
        $join->on('suppliers.id','=','po_trims.supplier_id');
      })
      ->join('currencies',function($join){
        $join->on('currencies.id','=','po_trims.currency_id');
      })
      ->where([['po_type_id', 1]])
      ->orderBy('po_trims.id','desc')
      ->get([
        'po_trims.*',
        'companies.code as company_code',
        'suppliers.name as supplier_code',
        'currencies.code as currency_code',
      ])
      ->take(500)
      ->map(function($rows) use($source,$paymode){
        $rows->source=$source[$rows->source_id];
        $rows->paymode=$paymode[$rows->pay_mode];
        $rows->amount=number_format($rows->amount);
        $rows->delv_start_date=date('d-M-Y',strtotime($rows->delv_start_date));
        $rows->po_date=date('d-M-Y',strtotime($rows->po_date));
        $rows->delv_end_date=date('d-M-Y',strtotime($rows->delv_end_date));
        if($rows->approved_by){
          $rows->approved="Approved";
        }
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
    $source = array_prepend(config('bprs.purchasesource'), '-Select-','');
    $basis = array_prepend(array_only(config('bprs.pur_order_basis'), [1, 20]),'-Select-','');
    $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
    $supplier=array_prepend(array_pluck($this->supplier->trimsSupplier(),'name','id'),'','');
    $buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'-Select-','');
    $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
    $indentor=array_prepend(array_pluck($this->supplier->indentor(),'name','id'),'','');

    return Template::loadView("Purchase.PoTrim", [
      'company'=>$company,
      'source'=>$source,
      'supplier'=>$supplier,
      'buyer'=>$buyer,
      'currency'=>$currency,
      'paymode'=>$paymode,
      'order_type_id'=>1,
      'basis'=>$basis,
      'indentor'=>$indentor
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(PoTrimRequest $request)
  {
    $supplierId=$this->supplier->find($request->supplier_id);
    if($supplierId->status_id==0){
        return response()->json(array('success' => false,  'message' => 'Purchase Order not allowed to inactive Supplier'), 200);
    }
    elseif($supplierId->status_id==1 || $supplierId->status_id=='') {
      $max = $this->potrim->where([['company_id', $request->company_id]])->max('po_no');
      $po_no=$max+1;

      $potrim = $this->potrim->create([
        'po_no'=>$po_no,
        'po_type_id'=>1,
        'po_date'=>$request->po_date,
        'company_id'=>$request->company_id,
        'source_id'=>$request->source_id,
        'basis_id'=>$request->basis_id,
        'supplier_id'=>$request->supplier_id,
        'currency_id'=>$request->currency_id,
        'exch_rate'=>$request->exch_rate,
        'delv_start_date'=>$request->delv_start_date,
        'delv_end_date'=>$request->delv_end_date,
        'pay_mode'=>$request->pay_mode,
        'pi_no'=>$request->pi_no,
        'pi_date'=>$request->pi_date,
        'remarks'=>$request->remarks,
        'indentor_id'=>$request->indentor_id,
        'buyer_id'=>$request->buyer_id,
      ]);

      $termscondition=$this->termscondition->where([['menu_id','=',2]])->orderBy('sort_id')->get();
      foreach($termscondition as $row)
      {
      $purchasetermscondition = $this->purchasetermscondition->create(['purchase_order_id'=>$potrim->id,'term'=>$row->term,'sort_id'=>$row->sort_id,'menu_id'=>2]);
      }

      if ($potrim) {
      return response()->json(array('success' => true, 'id' => $potrim->id, 'message' => 'Save Successfully'), 200);
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
    $potrim = $this->potrim->find($id);
    $row ['fromData'] = $potrim;
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
  public function update(PoTrimRequest $request, $id)
  {
  	$potrimapproved=$this->potrim->find($id);
  	if($potrimapproved->approved_at){
  	$this->potrim->update($id, ['pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'remarks'=>$request->remarks]);
  	  return response()->json(array('success' => false,  'message' => 'Trim Purchase Order is Approved, Update not Possible Except PI No , PI Date & Remarks,'), 200);
  	}

    $implcpo=$this->implcpo
    ->join('imp_lcs',function($join){
      $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
    })
    ->where([['imp_lcs.menu_id','=',2]])
    ->where([['imp_lc_pos.purchase_order_id','=',$id]])
    ->get(['imp_lc_pos.purchase_order_id'])
    ->first();
    if ($implcpo) {
      $potrim = $this->potrim->update($id, $request->except(['id','company_id','supplier_id','buyer_id']));
    }
    else{
      $potrim = $this->potrim->update($id, $request->except(['id','company_id','buyer_id']));
    }

    /*$termscondition=$this->termscondition->where([['menu_id','=',2]])->orderBy('sort_id')->get();
      foreach($termscondition as $row){
      	$purchasetermscondition = $this->purchasetermscondition->create(['purchase_order_id'=>$id,'term'=>$row->term,'sort_id'=>$row->sort_id]);
    }*/
      if ($potrim) {
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
    if($this->potrim->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
  }

  public function searchPoTrim()
  {
    $source = array_prepend(config('bprs.purchasesource'), '-Select-', '');
    $paymode = array_prepend(config('bprs.paymode'), '-Select-', '');
    $rows = $this->potrim
      ->join('companies', function ($join) {
        $join->on('companies.id', '=', 'po_trims.company_id');
      })
      ->join('suppliers', function ($join) {
        $join->on('suppliers.id', '=', 'po_trims.supplier_id');
      })
      ->join('currencies', function ($join) {
        $join->on('currencies.id', '=', 'po_trims.currency_id');
      })
      ->when(request('po_no'), function ($q) {
        return $q->where('po_trims.po_no', 'like', '%' . request('po_no') . '%');
      })
      ->when(request('supplier_search_id'), function ($q) {
        return $q->where('suppliers.id', '=', request('supplier_search_id'));
      })
      ->when(request('buyer_search_id'), function ($q) {
        return $q->where('buyers.id', '=', request('buyer_search_id'));
      })
      ->when(request('from_date'), function ($q) {
        return $q->where('po_trims.po_date', '>=', request('from_date'));
      })
      ->when(request('to_date'), function ($q) {
        return $q->where('po_trims.po_date', '<=', request('to_date'));
      })
      ->where([['po_type_id', 1]])
      ->orderBy('po_trims.id', 'desc')
      ->get([
        'po_trims.*',
        'companies.code as company_code',
        'suppliers.name as supplier_code',
        'currencies.code as currency_code',
      ])->map(function ($rows) use ($source, $paymode) {
        $rows->source = $source[$rows->source_id];
        $rows->paymode = $paymode[$rows->pay_mode];
        $rows->amount = number_format($rows->amount);
        $rows->delv_start_date = date('d-M-Y', strtotime($rows->delv_start_date));
        $rows->po_date = date('d-M-Y', strtotime($rows->po_date));
        $rows->delv_end_date = date('d-M-Y', strtotime($rows->delv_end_date));
        if ($rows->approved_by) {
          $rows->approved = "Approved";
        }
        return $rows;
      });
    echo json_encode($rows);
  }

  public function getPdf()
  {

    $id=request('id',0);
    $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
    $rows=$this->potrim
    ->join('companies',function($join){
    $join->on('companies.id','=','po_trims.company_id');
    })
    ->join('currencies',function($join){
    $join->on('currencies.id','=','po_trims.currency_id');
    })
    ->join('suppliers',function($join){
    $join->on('suppliers.id','=','po_trims.supplier_id');
    })
    ->join('users',function($join){
    $join->on('users.id','=','po_trims.created_by');
    })
    ->leftJoin('users as approve_users',function($join){
    $join->on('approve_users.id','=','po_trims.approved_by');
    })
    ->leftJoin('employee_h_rs as approve_employee',function($join){
    $join->on('approve_employee.user_id','=','approve_users.id');
    })
    ->leftJoin('designations',function($join){
    $join->on('approve_employee.designation_id','=','designations.id');
    })
    ->where([['po_trims.id','=',$id]])
    ->get([
    'po_trims.*',
    'po_trims.id as po_trim_id',
    'companies.name as company_name',
    'companies.logo as logo',
    'companies.address as company_address',
    'currencies.code as currency_name',
    'suppliers.name as supplier_name',
    'suppliers.address as supplier_address',
    'users.name as user_name',
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
    $rows->approved_user_signature=$rows->signature_file?'images/signature/'.$rows->signature_file:null;
    $rows->approved_at=$rows->approved_at?date('d-M-Y',strtotime($rows->approved_at)):null;

    $data=$this->potrim
    ->selectRaw(
    '
    styles.style_ref,
    buyers.code as buyer_name,
    companies.code as company_name,
    sales_orders.sale_order_no,
    itemclasses.name as itemclass_name,
    colors.name as gmt_color,
    sizes.name as gmt_size,
    trimcolors.name as trim_color,
    budget_trim_cons.measurment,
    po_trim_item_qties.description,
    uoms.code as uom_code,
    po_trim_item_qties.description,
    sum(po_trim_item_qties.qty) as qty,
    avg(po_trim_item_qties.rate) as rate,
    sum(po_trim_item_qties.amount) as amount
    '
    )
    ->join('po_trim_items',function($join){
    $join->on('po_trim_items.po_trim_id','=','po_trims.id');
    })
    ->join('budget_trims',function($join){
    $join->on('budget_trims.id','=','po_trim_items.budget_trim_id');
    })
    ->join('itemclasses',function($join){
    $join->on('itemclasses.id','=','budget_trims.itemclass_id');
    })
    ->join('uoms',function($join){
    $join->on('uoms.id','=','budget_trims.uom_id');
    })
    ->join('po_trim_item_qties',function($join){
    $join->on('po_trim_item_qties.po_trim_item_id','=','po_trim_items.id');
    })
    ->join('budget_trim_cons',function($join){
    $join->on('budget_trim_cons.id','=','po_trim_item_qties.budget_trim_con_id');
    })
    ->join('sales_order_gmt_color_sizes',function($join){
    $join->on('sales_order_gmt_color_sizes.id','=','budget_trim_cons.sales_order_gmt_color_size_id');
    })

    ->join('sales_order_countries',function($join){
    $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
    })
    ->join('sales_orders',function($join){
    $join->on('sales_orders.id','=','sales_order_countries.sale_order_id');
    })
    ->join('jobs',function($join){
    $join->on('jobs.id','=','sales_orders.job_id');
    })
    ->join('companies',function($join){
    $join->on('companies.id','=','jobs.company_id');
    })
    ->join('styles',function($join){
    $join->on('styles.id','=','jobs.style_id');
    })
    ->join('buyers',function($join){
    $join->on('buyers.id','=','styles.buyer_id');
    })
    ->join('style_gmt_color_sizes',function($join){
    $join->on('styles.id','=','style_gmt_color_sizes.style_id');
    $join->on('style_gmt_color_sizes.id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id');
    })
    ->join('style_gmts',function($join){
    $join->on('style_gmts.id','=','style_gmt_color_sizes.style_gmt_id');
    })
    ->join('style_colors',function($join){
    $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
    })
    ->join('style_sizes',function($join){
    $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
    })
    ->join('colors',function($join){
    $join->on('colors.id','=','style_colors.color_id');
    })
    ->join('sizes',function($join){
    $join->on('sizes.id','=','style_sizes.size_id');
    })
    ->join('item_accounts', function($join) {
    $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
    })
    ->join('colors as trimcolors',function($join){
    $join->on('trimcolors.id','=','budget_trim_cons.trim_color');
    })

    ->where([['po_trims.id','=',$id]])

    ->groupBy([
    'styles.style_ref',
    'buyers.code',
    'companies.code',
    'sales_orders.sale_order_no',
    'itemclasses.name',
    'colors.name',
    'sizes.name',
    'trimcolors.name',
    'budget_trim_cons.measurment',
    'po_trim_item_qties.description',
    'uoms.code'
    ])
    ->get();
    $amount=$data->sum('amount');
    $details=$data->groupBy('itemclass_name');

    $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents');
    $rows->inword=$inword;
    $purOrder['master']=$rows;
    $purOrder['details']=$details;
    $purchasetermscondition=$this->purchasetermscondition->where([['purchase_order_id','=',$id]])->where([['menu_id','=',2]])->orderBy('sort_id')->get();
    $purOrder['purchasetermscondition']=$purchasetermscondition;
    $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(3);
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
    $pdf->SetX(170);
    $challan=str_pad($purOrder['master']->po_trim_id,10,0,STR_PAD_LEFT ) ;
    $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
    $pdf->SetY(10);
    $image_file ='images/logo/'.$rows->logo;
    $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
    $pdf->SetY(12);
    $pdf->SetFont('helvetica', 'N', 8);
    $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
    //$pdf->Text(70, 12, $rows->company_address);
    $pdf->SetY(35);
    $pdf->SetFont('helvetica', 'N', 10);
    $pdf->Write(0, 'Trim Purchase Order', '', 0, 'C', true, 0, false, false, 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetTitle('Trim Purchase Order');
    $view= \View::make('Defult.Purchase.PoTrimPdf',['purOrder'=>$purOrder]);
    $html_content=$view->render();
    $pdf->SetY(40);
    $pdf->WriteHtml($html_content, true, false,true,false,'');
    $filename = storage_path() . '/PurOrderTrimPdf.pdf';
    $pdf->output($filename);

    
  }

  public function getPdfShort()
  {

    $id=request('id',0);
    $paper_type=request('paper_type',0);
    if(!$paper_type){
      $paper_type='A4';
    }
    $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
    $rows=$this->potrim
    ->join('companies',function($join){
    $join->on('companies.id','=','po_trims.company_id');
    })
    ->join('currencies',function($join){
    $join->on('currencies.id','=','po_trims.currency_id');
    })
    ->join('suppliers',function($join){
    $join->on('suppliers.id','=','po_trims.supplier_id');
    })
    ->join('users',function($join){
    $join->on('users.id','=','po_trims.created_by');
    })
    ->leftJoin('users as approve_users',function($join){
    $join->on('approve_users.id','=','po_trims.approved_by');
    })
    ->leftJoin('employee_h_rs as approve_employee',function($join){
    $join->on('approve_employee.user_id','=','approve_users.id');
    })
    ->leftJoin('designations',function($join){
    $join->on('approve_employee.designation_id','=','designations.id');
    })
    ->where([['po_trims.id','=',$id]])
    ->get([
    'po_trims.*',
    'po_trims.id as po_trim_id',
    'po_trims.amount',
    'po_trims.exch_rate',
    'companies.name as company_name',
    'companies.logo as logo',
    'companies.address as company_address',
    'currencies.code as currency_name',
    'currencies.hundreds_name',
    'suppliers.name as supplier_name',
    'suppliers.address as supplier_address',
    'users.name as user_name',
    'approve_users.signature_file',
    'approve_employee.name as approval_emp_name',
    'approve_employee.contact as approval_emp_contact',
    'designations.name as approval_emp_designation'
    ])
    ->map(function($rows){
      //$rows->amount=$rows->amount*$rows->exch_rate;
      $rows->amount=$rows->amount;
      return $rows;

    })->first()
    ;
    $rows->pay_mode=$paymode[$rows->pay_mode];
    $rows->po_date=date('d-M-Y',strtotime($rows->po_date));
    $rows->delv_start_date=date('d-M-Y',strtotime($rows->delv_start_date));
    $rows->delv_end_date=date('d-M-Y',strtotime($rows->delv_end_date));
    $rows->approved_user_signature=$rows->signature_file?'images/signature/'.$rows->signature_file:null;
    $rows->approved_at=$rows->approved_at?date('d-M-Y',strtotime($rows->approved_at)):null;

    $data=$this->potrim
    ->selectRaw(
    '
    itemclasses.name as itemclass_name,
    styles.style_ref,
    buyers.code as buyer_name,
    sales_orders.id as sales_order_id,
    sales_orders.sale_order_no,
    companies.code as company_name,
    po_trim_item_reports.description,
    colors.name as gmt_color,
    sizes.name as gmt_size,
    trimcolors.name as trim_color,
    po_trim_item_reports.measurment,
    uoms.code as uom_code,
    po_trim_item_reports.sensivity_id,
    --po_trim_item_reports.rate*po_trims.exch_rate as rate,
    po_trim_item_reports.rate,
    sum(po_trim_item_reports.qty) as qty,
    --sum(po_trim_item_reports.amount*po_trims.exch_rate) as amount,
    sum(po_trim_item_reports.amount) as amount
    '
    )
    ->join('po_trim_items',function($join){
    $join->on('po_trim_items.po_trim_id','=','po_trims.id');
    })

    ->join('po_trim_item_reports',function($join){
    $join->on('po_trim_item_reports.po_trim_item_id','=','po_trim_items.id');
    })
    ->join('budget_trims',function($join){
    $join->on('budget_trims.id','=','po_trim_items.budget_trim_id');
    })
    ->join('itemclasses',function($join){
    $join->on('itemclasses.id','=','budget_trims.itemclass_id');
    })
    ->join('uoms',function($join){
    $join->on('uoms.id','=','budget_trims.uom_id');
    })
    ->join('sales_orders',function($join){
    $join->on('sales_orders.id','=','po_trim_item_reports.sales_order_id');
    })
    ->join('jobs',function($join){
    $join->on('jobs.id','=','sales_orders.job_id');
    })
    ->join('companies',function($join){
    $join->on('companies.id','=','jobs.company_id');
    })
    ->join('styles',function($join){
    $join->on('styles.id','=','jobs.style_id');
    })
    ->join('buyers',function($join){
    $join->on('buyers.id','=','styles.buyer_id');
    })
    ->leftJoin('style_colors',function($join){
    $join->on('style_colors.id','=','po_trim_item_reports.style_color_id');
    })
    ->leftJoin('style_sizes',function($join){
    $join->on('style_sizes.id','=','po_trim_item_reports.style_size_id');
    })
    ->leftJoin('colors',function($join){
    $join->on('colors.id','=','style_colors.color_id');
    })
    ->leftJoin('sizes',function($join){
    $join->on('sizes.id','=','style_sizes.size_id');
    })
    
    ->leftJoin('colors as trimcolors',function($join){
    $join->on('trimcolors.id','=','po_trim_item_reports.trim_color');
    })
    ->where([['po_trims.id','=',$id]])
    ->groupBy([
    'itemclasses.name',
    'styles.style_ref',
    'buyers.code',
    'sales_orders.id',
    'sales_orders.sale_order_no',
    'companies.code',
    'po_trim_item_reports.description',
    'colors.id',
    'colors.name',

    'sizes.id',
    'sizes.name',
    'trimcolors.id',
    'trimcolors.name',
    'po_trim_item_reports.measurment',
    'uoms.code',
    'po_trim_item_reports.sensivity_id',
    'po_trim_item_reports.rate',
    'po_trims.exch_rate'
    ])
    ->get();

    $amount=$data->sum('amount');

    $colorsizesensivits = $data->filter(
        function ($value) 
        {
          if($value->sensivity_id==15)
          {
            return $value;
          }
        }
      )
     ->values();

    

     $colorsizesensivitarray=array();
     $colorsizearray=array();
     $colorsizetotalarray=array();
     foreach($colorsizesensivits as $colorsizesensivit){
      $index="'".$colorsizesensivit->sales_order_id."-".$colorsizesensivit->description."-".$colorsizesensivit->trim_color."-".$colorsizesensivit->measurment."-".$colorsizesensivit->gmt_color."-".$colorsizesensivit->uom_code."-".$colorsizesensivit->rate."'";

      $colorsizesensivitarray[$colorsizesensivit->itemclass_name][$index]['style_ref']=$colorsizesensivit->style_ref;
      $colorsizesensivitarray[$colorsizesensivit->itemclass_name][$index]['buyer_name']=$colorsizesensivit->buyer_name;
      $colorsizesensivitarray[$colorsizesensivit->itemclass_name][$index]['sale_order_no']=$colorsizesensivit->sale_order_no;
      $colorsizesensivitarray[$colorsizesensivit->itemclass_name][$index]['company_name']=$colorsizesensivit->company_name;
      $colorsizesensivitarray[$colorsizesensivit->itemclass_name][$index]['description']=$colorsizesensivit->description;
      $colorsizesensivitarray[$colorsizesensivit->itemclass_name][$index]['trim_color']=$colorsizesensivit->trim_color;
      $colorsizesensivitarray[$colorsizesensivit->itemclass_name][$index]['measurment']=$colorsizesensivit->measurment;
      $colorsizesensivitarray[$colorsizesensivit->itemclass_name][$index]['gmt_color']=$colorsizesensivit->gmt_color;
      $colorsizesensivitarray[$colorsizesensivit->itemclass_name][$index]['uom_code']=$colorsizesensivit->uom_code;
      $colorsizesensivitarray[$colorsizesensivit->itemclass_name][$index]['rate']=$colorsizesensivit->rate;
      $colorsizesensivitarray[$colorsizesensivit->itemclass_name][$index]['qty'][$colorsizesensivit->gmt_size]=$colorsizesensivit->qty;

      $colorsizesensivitarray[$colorsizesensivit->itemclass_name][$index]['amount'][$colorsizesensivit->gmt_size]=$colorsizesensivit->amount;
      $colorsizearray[$colorsizesensivit->itemclass_name][$colorsizesensivit->gmt_size]=$colorsizesensivit->gmt_size;
      $colorsizetotalarray[$colorsizesensivit->itemclass_name][$colorsizesensivit->gmt_size][$index]=$colorsizesensivit->qty;

     }
     

    $sizesensivits = $data->filter(
        function ($value) 
        {
          if($value->sensivity_id==10)
          {
            return $value;
          }
        }
      )
     ->values();
     $sizesensivitarray=array();
     $sizearray=array();
     $sizetotalarray=array();
     foreach($sizesensivits as $sizesensivit){
      $index="'".$sizesensivit->sales_order_id."-".$sizesensivit->description."-".$sizesensivit->trim_color."-".$sizesensivit->measurment."-".$sizesensivit->uom_code."-".$sizesensivit->rate."'";
      $sizesensivitarray[$sizesensivit->itemclass_name][$index]['style_ref']=$sizesensivit->style_ref;
      $sizesensivitarray[$sizesensivit->itemclass_name][$index]['buyer_name']=$sizesensivit->buyer_name;
      $sizesensivitarray[$sizesensivit->itemclass_name][$index]['sale_order_no']=$sizesensivit->sale_order_no;
      $sizesensivitarray[$sizesensivit->itemclass_name][$index]['company_name']=$sizesensivit->company_name;
      $sizesensivitarray[$sizesensivit->itemclass_name][$index]['description']=$sizesensivit->description;
      $sizesensivitarray[$sizesensivit->itemclass_name][$index]['trim_color']=$sizesensivit->trim_color;
      $sizesensivitarray[$sizesensivit->itemclass_name][$index]['measurment']=$sizesensivit->measurment;
      $sizesensivitarray[$sizesensivit->itemclass_name][$index]['uom_code']=$sizesensivit->uom_code;
      $sizesensivitarray[$sizesensivit->itemclass_name][$index]['rate']=$sizesensivit->rate;
      $sizesensivitarray[$sizesensivit->itemclass_name][$index]['qty'][$sizesensivit->gmt_size]=$sizesensivit->qty;
      $sizesensivitarray[$sizesensivit->itemclass_name][$index]['amount'][$sizesensivit->gmt_size]=$sizesensivit->amount;
      $sizearray[$sizesensivit->itemclass_name][$sizesensivit->gmt_size]=$sizesensivit->gmt_size;
      $sizetotalarray[$sizesensivit->itemclass_name][$sizesensivit->gmt_size][$index]=$sizesensivit->qty;

     }
     

    
    $colorsensivits = $data->filter(
        function ($value) 
        {
          if($value->sensivity_id==1)
          {
            return $value;
          }
        }
      )->values();

     $colorsensivitarray=array();
     $colorarray=array();
     $colortotalarray=array();
     foreach($colorsensivits as $colorsensivit){

      $index="'".$colorsensivit->sales_order_id."-".$colorsensivit->description."-".$colorsensivit->trim_color."-".$colorsensivit->measurment."-".$colorsensivit->uom_code."-".$colorsensivit->rate."'";

      $colorsensivitarray[$colorsensivit->itemclass_name][$index]['style_ref']=$colorsensivit->style_ref;
      $colorsensivitarray[$colorsensivit->itemclass_name][$index]['buyer_name']=$colorsensivit->buyer_name;
      $colorsensivitarray[$colorsensivit->itemclass_name][$index]['sale_order_no']=$colorsensivit->sale_order_no;
      $colorsensivitarray[$colorsensivit->itemclass_name][$index]['company_name']=$colorsensivit->company_name;
      $colorsensivitarray[$colorsensivit->itemclass_name][$index]['description']=$colorsensivit->description;
      $colorsensivitarray[$colorsensivit->itemclass_name][$index]['trim_color']=$colorsensivit->trim_color;
      $colorsensivitarray[$colorsensivit->itemclass_name][$index]['measurment']=$colorsensivit->measurment;
      $colorsensivitarray[$colorsensivit->itemclass_name][$index]['uom_code']=$colorsensivit->uom_code;
      $colorsensivitarray[$colorsensivit->itemclass_name][$index]['rate']=$colorsensivit->rate;
      $colorsensivitarray[$colorsensivit->itemclass_name][$index]['qty'][$colorsensivit->gmt_color]=$colorsensivit->qty;
      $colorsensivitarray[$colorsensivit->itemclass_name][$index]['amount'][$colorsensivit->gmt_color]=$colorsensivit->amount;
      $colorarray[$colorsensivit->itemclass_name][$colorsensivit->gmt_color]=$colorsensivit->gmt_color;
      $colortotalarray[$colorsensivit->itemclass_name][$colorsensivit->gmt_color][$index]=$colorsensivit->qty;

     }
    $nosensivits = $data->filter(
        function ($value) 
        {
          if(!$value->sensivity_id)
          {
            return $value;
          }
        }
      )->values()->groupBy('itemclass_name');
    

    $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,$rows->hundreds_name);
    $rows->inword=$inword;
    $purOrder['master']=$rows;
    $purOrder['sizesensivitarray']=$sizesensivitarray;
    $purOrder['colorsensivitarray']=$colorsensivitarray;
    $purOrder['colorsizesensivitarray']=$colorsizesensivitarray;
    $purOrder['nosensivits']=$nosensivits;
    $purchasetermscondition=$this->purchasetermscondition->where([['purchase_order_id','=',$id]])->where([['menu_id','=',2]])->orderBy('sort_id')->get();
    $purOrder['purchasetermscondition']=$purchasetermscondition;
    $pdf = new \TCPDF('L', PDF_UNIT, $paper_type, true, 'UTF-8', false);
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(3);
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
    //$pdf->SetX(185);
    if($paper_type=='A4'){
      
      $pdf->SetX(185);
    }
    else if($paper_type=='LEGAL'){
      $pdf->SetX(230);

    }
    else{
      $pdf->SetX(185);
    }
    $challan=str_pad($purOrder['master']->po_trim_id,10,0,STR_PAD_LEFT ) ;
    $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
    $pdf->SetY(10);
    $image_file ='images/logo/'.$rows->logo;
    $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
    $pdf->SetY(12);
    $pdf->SetFont('helvetica', 'N', 9);
    $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
    //$pdf->Text(115, 12, $rows->company_address);
    $pdf->SetY(35);
    $pdf->SetFont('helvetica', 'N', 10);
    $pdf->Write(0, 'Trim Purchase Order', '', 0, 'C', true, 0, false, false, 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetTitle('Trim Purchase Order');
    $view= \View::make('Defult.Purchase.PoTrimPdfShort',['purOrder'=>$purOrder,'sizearray'=>$sizearray,'sizetotalarray'=>$sizetotalarray,'colorarray'=>$colorarray,'colortotalarray'=>$colortotalarray,'colorsizearray'=>$colorsizearray,'colorsizetotalarray'=>$colorsizetotalarray]);
    $html_content=$view->render();
    $pdf->SetY(40);
    $pdf->WriteHtml($html_content, true, false,true,false,'');
    $filename = storage_path() . '/PurOrderTrimPdf.pdf';
    $pdf->output($filename);
  }

}