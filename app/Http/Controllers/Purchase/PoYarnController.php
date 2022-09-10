<?php

namespace App\Http\Controllers\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoYarnRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\TermsConditionRepository;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Library\Template;
use App\Library\Numbertowords;
use App\Http\Requests\Purchase\PoYarnRequest;


class PoYarnController extends Controller
{
   private $poyarn;
   private $company;
   private $supplier;
   private $currency;
   private $uom;
   private $itemcategory;
   private $termscondition;
   private $purchasetermscondition;
   private $implcpo;

	public function __construct(
    PoYarnRepository $poyarn,
    CompanyRepository $company,
    SupplierRepository $supplier,
    CurrencyRepository $currency,
    UomRepository $uom,
    ItemcategoryRepository $itemcategory,
    TermsConditionRepository $termscondition,
    PurchaseTermsConditionRepository $purchasetermscondition,
    ItemAccountRepository $itemaccount,
    ImpLcPoRepository $implcpo
  )
	{
    $this->poyarn = $poyarn;
		$this->company = $company;
		$this->supplier = $supplier;
    $this->currency = $currency;
		$this->uom = $uom;
		$this->itemcategory = $itemcategory;
    $this->termscondition = $termscondition;
    $this->purchasetermscondition = $purchasetermscondition;
    $this->itemaccount = $itemaccount;
    $this->implcpo = $implcpo;


		$this->middleware('auth');
		$this->middleware('permission:view.poyarns',   ['only' => ['create', 'index','show']]);
		$this->middleware('permission:create.poyarns', ['only' => ['store']]);
		$this->middleware('permission:edit.poyarns',   ['only' => ['update']]);
		$this->middleware('permission:delete.poyarns', ['only' => ['destroy']]);
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
        /* $rows=$this->poyarn
        ->join('companies',function($join){
        $join->on('companies.id','=','po_yarns.company_id');
        })
        ->join('suppliers',function($join){
        $join->on('suppliers.id','=','po_yarns.supplier_id');
        })
        ->join('currencies',function($join){
        $join->on('currencies.id','=','po_yarns.currency_id');
        })
        ->where([['po_type_id', 1]])
        ->orderBy('po_yarns.id','desc')
        ->get([
          'po_yarns.*',
          'companies.code as company_code',
          'suppliers.code as supplier_code',
          'currencies.code as currency_code',
        ])
        ->map(function($rows) use($source,$paymode){
          $rows->source=$source[$rows->source_id];
          $rows->paymode=$paymode[$rows->pay_mode];
          return $rows;
        });
        echo json_encode($rows); */

        $rows=$this->poyarn
        ->selectRaw('
          po_yarns.id,
          po_yarns.po_no,
          po_yarns.po_date,
          po_yarns.company_id,
          po_yarns.supplier_id,
          po_yarns.source_id,
          po_yarns.pay_mode,
          po_yarns.delv_start_date,
          po_yarns.delv_end_date,
          po_yarns.pi_no,
          po_yarns.exch_rate,
          po_yarns.remarks,
          po_yarns.approved_at,
          companies.code as company_code,
          suppliers.name as supplier_code,
          currencies.code as currency_code,
          po_yarns.amount,
          sum(po_yarn_items.qty) as item_qty
        ')
        ->join('companies',function($join){
          $join->on('companies.id','=','po_yarns.company_id');
        })
        ->join('suppliers',function($join){
          $join->on('suppliers.id','=','po_yarns.supplier_id');
        })
        ->join('currencies',function($join){
          $join->on('currencies.id','=','po_yarns.currency_id');
        })
        ->leftJoin('po_yarn_items', function($join){
          $join->on('po_yarn_items.po_yarn_id', '=', 'po_yarns.id');
        })
        ->where([['po_type_id', 1]])
        ->orderBy('po_yarns.id','desc')
        ->groupBy([
          'po_yarns.id',
          'po_yarns.po_no',
          'po_yarns.po_date',
          'po_yarns.company_id',
          'po_yarns.supplier_id',
          'po_yarns.source_id',
          'po_yarns.pay_mode',
          'po_yarns.delv_start_date',
          'po_yarns.delv_end_date',
          'po_yarns.pi_no',
          'po_yarns.exch_rate',
          'po_yarns.remarks',
          'po_yarns.approved_at',
          'companies.code',
          'suppliers.name',
          'currencies.code',
          'po_yarns.amount'
        ])
        ->take(500)
        ->get()
        ->map(function($rows) use($source,$paymode){
          $rows->source=isset($source[$rows->source_id])?$source[$rows->source_id]:'';
          $rows->paymode=isset($paymode[$rows->pay_mode])?$paymode[$rows->pay_mode]:'';
          $rows->item_qty = number_format($rows->item_qty,2);
          $rows->amount = number_format($rows->amount,2);
          $rows->delv_start_date=date('d-M-Y',strtotime($rows->delv_start_date));
          $rows->po_date=date('d-M-Y',strtotime($rows->po_date));
          $rows->delv_end_date=date('d-M-Y',strtotime($rows->delv_end_date));
          $rows->pi_no=($rows->pi_no)?$rows->pi_no:'--';
          if ($rows->approved_at) {
            $rows->approve_status="Approved";
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
      $basis = array_only(config('bprs.pur_order_basis'), [20]);
      $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->yarnSupplier(),'name','id'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
      $indentor=array_prepend(array_pluck($this->supplier->indentor(),'name','id'),'','');

      return Template::loadView("Purchase.PoYarn", ['company'=>$company,'source'=>$source,'supplier'=>$supplier,'currency'=>$currency,'paymode'=>$paymode,'order_type_id'=>1,'basis'=>$basis,'uom'=>$uom,'indentor'=>$indentor]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoYarnRequest $request)
    {
      $supplierId=$this->supplier->find($request->supplier_id);
      if($supplierId->status_id==0){
          return response()->json(array('success' => false,  'message' => 'Purchase Order not allowed to inactive Supplier'), 200);
      }elseif($supplierId->status_id==1 || $supplierId->status_id=='') {
        $max = $this->poyarn->where([['company_id', $request->company_id]])->max('po_no');
        $po_no=$max+1;
        $poyarn = $this->poyarn->create(['po_no'=>$po_no,'po_type_id'=>1,'po_date'=>$request->po_date,'company_id'=>$request->company_id,'source_id'=>$request->source_id,'basis_id'=>20,'supplier_id'=>$request->supplier_id,'currency_id'=>$request->currency_id,'exch_rate'=>$request->exch_rate,'delv_start_date'=>$request->delv_start_date,'delv_end_date'=>$request->delv_end_date,'pay_mode'=>$request->pay_mode,'pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'remarks'=>$request->remarks,'indentor_id'=>$request->indentor_id]);

        $termscondition=$this->termscondition->where([['menu_id','=',3]])->orderBy('sort_id')->get();
        foreach($termscondition as $row)
        {
        $purchasetermscondition = $this->purchasetermscondition->create(['purchase_order_id'=>$poyarn->id,'term'=>$row->term,'sort_id'=>$row->sort_id,'menu_id'=>3]);
        }

        if ($poyarn) {
        return response()->json(array('success' => true, 'id' => $poyarn->id, 'message' => 'Save Successfully'), 200);
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
      $poyarn = $this->poyarn->find($id);
      $row ['fromData'] = $poyarn;
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
    public function update(PoYarnRequest $request, $id)
    {
      $poyarnapproved=$this->poyarn->find($id);
      if($poyarnapproved->approved_at){
        $this->poyarn->update($id, ['pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'remarks'=>$request->remarks]);
        return response()->json(array('success' => false,  'message' => 'Yarn Purchase Order is Approved, Update not Possible Except PI No , PI Date & Remarks'), 200);
      }

      $implcpo=$this->implcpo
      ->join('imp_lcs',function($join){
        $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
      })
      ->where([['imp_lcs.menu_id','=',3]])
      ->where([['imp_lc_pos.purchase_order_id','=',$id]])
      ->get(['imp_lc_pos.purchase_order_id'])
      ->first();

      if ($implcpo) {
        $poyarn = $this->poyarn->update($id, $request->except(['id','company_id','supplier_id']));
      }else {
        $poyarn = $this->poyarn->update($id, $request->except(['id','company_id']));
      }
      if ($poyarn) {
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
      if($this->poyarn->delete($id)){
  			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
  		}
    }

  public function searchPoYearn()
  {
    $source = array_prepend(config('bprs.purchasesource'), '-Select-', '');
    $paymode = array_prepend(config('bprs.paymode'), '-Select-', '');

    $rows = $this->poyarn
     ->selectRaw('
        po_yarns.id,
        po_yarns.po_no,
        po_yarns.po_date,
        po_yarns.company_id,
        po_yarns.supplier_id,
        po_yarns.source_id,
        po_yarns.pay_mode,
        po_yarns.delv_start_date,
        po_yarns.delv_end_date,
        po_yarns.pi_no,
        po_yarns.exch_rate,
        po_yarns.remarks,
        po_yarns.approved_at,
        companies.code as company_code,
        suppliers.name as supplier_code,
        currencies.code as currency_code,
        po_yarns.amount,
        sum(po_yarn_items.qty) as item_qty
      ')
     ->join('companies', function ($join) {
      $join->on('companies.id', '=', 'po_yarns.company_id');
     })
     ->join('suppliers', function ($join) {
      $join->on('suppliers.id', '=', 'po_yarns.supplier_id');
     })
     ->join('currencies', function ($join) {
      $join->on('currencies.id', '=', 'po_yarns.currency_id');
     })
     ->leftJoin('po_yarn_items', function ($join) {
      $join->on('po_yarn_items.po_yarn_id', '=', 'po_yarns.id');
     })
     ->when(request('po_no'), function ($q) {
      return $q->where('po_yarns.po_no', '=', request('po_no',0));
     })
     ->when(request('supplier_search_id'), function ($q) {
      return $q->where('po_yarns.supplier_id', '=', request('supplier_search_id',0));
     })
     ->when(request('from_date'), function ($q) {
      return $q->where('po_yarns.po_date', '>=', request('from_date',0));
     })
     ->when(request('to_date'), function ($q) {
      return $q->where('po_yarns.po_date', '<=', request('to_date',0));
     })
     ->where([['po_type_id', 1]])
     ->orderBy('po_yarns.id', 'desc')
     ->groupBy([
      'po_yarns.id',
      'po_yarns.po_no',
      'po_yarns.po_date',
      'po_yarns.company_id',
      'po_yarns.supplier_id',
      'po_yarns.source_id',
      'po_yarns.pay_mode',
      'po_yarns.delv_start_date',
      'po_yarns.delv_end_date',
      'po_yarns.pi_no',
      'po_yarns.exch_rate',
      'po_yarns.remarks',
      'po_yarns.approved_at',
      'companies.code',
      'suppliers.name',
      'currencies.code',
      'po_yarns.amount'
     ])
     ->get()
     ->map(function ($rows) use ($source, $paymode) {
      $rows->source = isset($source[$rows->source_id]) ? $source[$rows->source_id] : '';
      $rows->paymode = isset($paymode[$rows->pay_mode]) ? $paymode[$rows->pay_mode] : '';
      $rows->item_qty = number_format($rows->item_qty, 2);
      $rows->amount = number_format($rows->amount, 2);
      $rows->delv_start_date = date('d-M-Y', strtotime($rows->delv_start_date));
      $rows->po_date = date('d-M-Y', strtotime($rows->po_date));
      $rows->delv_end_date = date('d-M-Y', strtotime($rows->delv_end_date));
      $rows->pi_no = ($rows->pi_no) ? $rows->pi_no : '--';
      if ($rows->approved_at) {
       $rows->approve_status = "Approved";
      }
      return $rows;
     });
    echo json_encode($rows);
  }

  /* public function getPdf()
  {

      $id=request('id',0);
      $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
      $rows=$this->poyarn
      ->join('companies',function($join){
      $join->on('companies.id','=','po_yarns.company_id');
      })
      ->join('currencies',function($join){
      $join->on('currencies.id','=','po_yarns.currency_id');
      })
      ->join('suppliers',function($join){
      $join->on('suppliers.id','=','po_yarns.supplier_id');
      })
      ->join('users',function($join){
      $join->on('users.id','=','po_yarns.created_by');
      })
      ->where([['po_yarns.id','=',$id]])
      ->get([
      'po_yarns.*',
      'companies.name as company_name',
      'companies.logo as logo',
      'companies.address as company_address',
      'currencies.code as currency_name',
      'suppliers.name as supplier_name',
      'suppliers.address as supplier_address',
      'users.name as user_name'
      ])
      ->first();
      $rows->pay_mode=$paymode[$rows->pay_mode];
      $rows->po_date=date('d-M-Y',strtotime($rows->po_date));
      $rows->delv_start_date=date('d-M-Y',strtotime($rows->delv_start_date));
      $rows->delv_end_date=date('d-M-Y',strtotime($rows->delv_end_date));

      $data=$this->poyarn
      ->selectRaw(
      '
      styles.style_ref,
      sales_orders.sale_order_no,
      itemclasses.name as itemclass_name,
      colors.name as gmt_color,
      sizes.name as gmt_size,
      yarncolors.name as yarn_color,
      budget_yarn_cons.measurment,
      po_yarn_item_qties.description,
      uoms.code as uom_code,
      po_yarn_item_qties.description,
      sum(po_yarn_item_qties.qty) as qty,
      avg(po_yarn_item_qties.rate) as rate,
      sum(po_yarn_item_qties.amount) as amount
      '
      )
      ->join('po_yarn_items',function($join){
      $join->on('po_yarn_items.po_yarn_id','=','po_yarns.id');
      })
      ->join('budget_yarns',function($join){
      $join->on('budget_yarns.id','=','po_yarn_items.budget_yarn_id');
      })
      ->join('itemclasses',function($join){
      $join->on('itemclasses.id','=','budget_yarns.itemclass_id');
      })
      ->join('uoms',function($join){
      $join->on('uoms.id','=','budget_yarns.uom_id');
      })
      ->join('po_yarn_item_qties',function($join){
      $join->on('po_yarn_item_qties.po_yarn_item_id','=','po_yarn_items.id');
      })
      ->join('budget_yarn_cons',function($join){
      $join->on('budget_yarn_cons.id','=','po_yarn_item_qties.budget_yarn_con_id');
      })
      ->join('sales_order_gmt_color_sizes',function($join){
        $join->on('sales_order_gmt_color_sizes.id','=','budget_yarn_cons.sales_order_gmt_color_size_id');
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
      ->join('styles',function($join){
        $join->on('styles.id','=','jobs.style_id');
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
      ->join('colors as yarncolors',function($join){
      $join->on('yarncolors.id','=','budget_yarn_cons.yarn_color');
      })

      ->where([['po_yarns.id','=',$id]])
    
     ->groupBy([
      'styles.style_ref',
      'sales_orders.sale_order_no',
      'itemclasses.name',
      'colors.name',
      'sizes.name',
      'yarncolors.name',
      'budget_yarn_cons.measurment',
      'po_yarn_item_qties.description',
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
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->AddPage();
    $pdf->SetY(10);
    //$txt = "Yarn Purchase Order";
    //$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
    $image_file ='images/logo/'.$rows->logo;
    $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
    $pdf->SetY(12);
    $pdf->SetFont('helvetica', 'N', 8);
    $pdf->Text(70, 12, $rows->company_address);
    $pdf->SetY(16);
    $pdf->SetFont('helvetica', 'N', 10);
    $pdf->Write(0, 'Yarn Purchase Order', '', 0, 'C', true, 0, false, false, 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetTitle('Yarn Purchase Order');
    $view= \View::make('Defult.Purchase.PoYarnPdf',['purOrder'=>$purOrder]);
    $html_content=$view->render();
    $pdf->SetY(20);
    $pdf->WriteHtml($html_content, true, false,true,false,'');
    $filename = storage_path() . '/PurOrderYarnPdf.pdf';
    $pdf->output($filename);
  } */

  public function getPdf()
  {

    $id=request('id',0);
    $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
    $rows=$this->poyarn
    ->join('companies',function($join){
      $join->on('companies.id','=','po_yarns.company_id');
    })
    ->join('currencies',function($join){
      $join->on('currencies.id','=','po_yarns.currency_id');
    })
    ->join('suppliers',function($join){
      $join->on('suppliers.id','=','po_yarns.supplier_id');
    })
    ->join('users',function($join){
      $join->on('users.id','=','po_yarns.created_by');
    })
    ->leftJoin('employee_h_rs',function($join){
      $join->on('users.id','=','employee_h_rs.user_id');
    })
    ->leftJoin('users as approve_users',function($join){
      $join->on('approve_users.id','=','po_yarns.approved_by');
    })
    ->leftJoin('employee_h_rs as approve_employee',function($join){
      $join->on('approve_employee.user_id','=','approve_users.id');
    })
    ->leftJoin('designations',function($join){
      $join->on('approve_employee.designation_id','=','designations.id');
    })
    ->where([['po_yarns.id','=',$id]])
    ->get([
      'po_yarns.*',
      'po_yarns.id as po_yarn_id',
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

    $yarnDescription=$this->itemaccount
    ->join('item_account_ratios',function($join){
    $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
    })
    ->join('yarncounts',function($join){
    $join->on('yarncounts.id','=','item_accounts.yarncount_id');
    })
    ->join('yarntypes',function($join){
    $join->on('yarntypes.id','=','item_accounts.yarntype_id');
    })
    ->join('itemclasses',function($join){
    $join->on('itemclasses.id','=','item_accounts.itemclass_id');
    })
    ->join('compositions',function($join){
    $join->on('compositions.id','=','item_account_ratios.composition_id');
    })
    ->join('itemcategories',function($join){
    $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
    })
    //->where([['itemcategories.identity','=',1]])
    ->get([
    'item_accounts.id',
    'yarncounts.count',
    'yarncounts.symbol',
    'yarntypes.name as yarn_type',
    'itemclasses.name as itemclass_name',
    'compositions.name as composition_name',
    'item_account_ratios.ratio',
    ]);
    $itemaccountArr=array();
    $yarnCompositionArr=array();
    foreach($yarnDescription as $row){
        $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
        $itemaccountArr[$row->id]['yarn_type']=$row->yarn_type;
        //$itemaccountArr[$row->id]['itemclass_name']=$row->itemclass_name;
        $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
    }
    $yarnDropdown=array();
    foreach($itemaccountArr as $key=>$value){
        $yarnDropdown[$key]=/* $value['itemclass_name']." ". */$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
    }

    $data=$this->poyarn
      ->selectRaw(
      '
        uoms.code as uom_code,
        po_yarn_items.po_yarn_id,
        po_yarn_items.item_account_id,
        po_yarn_items.remarks as item_remarks,
        po_yarn_items.qty,
        po_yarn_items.rate,
        po_yarn_items.amount
      '
      )
      ->join('po_yarn_items', function($join){
        $join->on('po_yarn_items.po_yarn_id', '=', 'po_yarns.id');
        $join->whereNull('po_yarn_items.deleted_at');
      })
      ->join('item_accounts', function($join){
        $join->on('item_accounts.id', '=', 'po_yarn_items.item_account_id');
      })
      ->join('itemclasses', function($join){
        $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
      })
      ->join('itemcategories', function($join){
        $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
      })
      ->leftJoin('uoms', function($join){
        $join->on('uoms.id', '=', 'item_accounts.uom_id');
      })
      ->where([['po_yarns.id','=',$id]])
      ->groupBy([
        'uoms.code',
        'po_yarn_items.po_yarn_id',
        'po_yarn_items.item_account_id',
        'po_yarn_items.remarks',
        'po_yarn_items.qty',
        'po_yarn_items.rate',
        'po_yarn_items.amount'
      ])
      ->get()
      ->map(function ($data) use($yarnDropdown) {
          $data->item_description = $yarnDropdown[$data->item_account_id];
          return $data;
      });
    $amount=$data->sum('amount');
   

    $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents');
    $rows->inword=$inword;
    $purOrder['master']=$rows;
    $purOrder['created_at']=date('d-M-Y',strtotime($rows->created_at)) ;
    $details=$data->groupBy('po_yarn_id');
    $purOrder['details']=$details;
    $purchasetermscondition=$this->purchasetermscondition->where([['purchase_order_id','=',$id]])->where([['menu_id','=',3]])->orderBy('sort_id')->get();
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
    $pdf->SetY(10);
    $image_file ='images/logo/'.$rows->logo;
    $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
    $pdf->SetY(12);
    $pdf->SetFont('helvetica', 'N', 8);
    $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
    //$pdf->Text(70, 12, $rows->company_address);
    $pdf->SetY(16);
    //$pdf->AddPage();
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
      $challan=str_pad($purOrder['master']->po_yarn_id,10,0,STR_PAD_LEFT ) ;
      $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
      $pdf->SetFont('helvetica', 'N', 10);
      //$pdf->Write(0, 'Dyes/Chemical Purchase Order', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      //$pdf->SetTitle('General Item Purchase Order');
      $view= \View::make('Defult.Purchase.PoYarnPdf',['purOrder'=>$purOrder,'data'=>$data]);
      $html_content=$view->render();
      $pdf->SetY(35);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path().'/PoYarnPdf.pdf';
      $pdf->output($filename);
      exit();
  }

  public function getSummeryPdf()
  {
    $id=request('id',0);

    $data=collect(
      \DB::select("
      select
      n.style_id,
      n.style_ref,
      n.buyer_name,
      sum(n.yarn_qty) as req_qty,
      sum(n.yarn_amount) as yarn_cost,
      sum(n.amount) as selling_value,
      avg(n.rate) as unit_price,
      sum(n.po_qty) as current_po_qty,
      sum(n.total_po_qty) as total_po_qty
      from(
      select 
          styles.id as style_id,
          styles.style_ref,
          buyers.name as buyer_name,
          sales_orders.id as sale_order_id,
          orders.rate,
          orders.amount,
          budgetyarn.yarn_qty,
          budgetyarn.yarn_amount,
          totalPoQty.total_po_qty,
          sum(po_yarn_item_bom_qties.qty) as po_qty
          from
          sales_orders
          join po_yarn_item_bom_qties on po_yarn_item_bom_qties.sale_order_id=sales_orders.id
          join po_yarn_items on po_yarn_item_bom_qties.po_yarn_item_id=po_yarn_items.id
          join po_yarns on po_yarn_items.po_yarn_id=po_yarns.id
          join jobs on jobs.id=sales_orders.job_id
          join styles on styles.id=jobs.style_id
          join buyers on buyers.id=styles.buyer_id
          left join(
          select
            sales_orders.id as sale_order_id,
            avg(sales_order_gmt_color_sizes.rate) as rate,
            sum(sales_order_gmt_color_sizes.amount) as amount
            from sales_orders
            join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
            where sales_order_gmt_color_sizes.deleted_at is null
           group by sales_orders.id
          )orders on orders.sale_order_id=sales_orders.id
          
          left join(
            select 
            m.sales_order_id,
            sum(m.yarn) as yarn_qty,
            sum(m.yarn_amount) as yarn_amount  
            from 
            (
                select budget_yarns.id as budget_yarn_id,
                budget_yarns.item_account_id,
                budget_yarns.ratio,
                budget_yarns.cons,
                budget_yarns.rate,
                budget_yarns.amount,
                sales_orders.id as sales_order_id,
                sum(budget_fabric_cons.grey_fab) as grey_fab,
                sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100)) as yarn,
                (sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100))*budget_yarns.rate) as yarn_amount
                from budget_yarns
                join budget_fabrics on budget_fabrics.id=budget_yarns.budget_fabric_id 
                join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id 
                join style_gmts on style_gmts.id=style_fabrications.style_gmt_id 
                join item_accounts on item_accounts.id=style_gmts.item_account_id
                left join gmtsparts on gmtsparts.id=style_fabrications.gmtspart_id 
                join budget_fabric_cons on budget_yarns.budget_fabric_id=budget_fabric_cons.budget_fabric_id 
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id
                join sales_orders on sales_orders.id=sales_order_gmt_color_sizes.sale_order_id 
                join jobs on jobs.id=sales_orders.job_id
                join styles on styles.id=jobs.style_id
                group by 
                budget_yarns.id,
                budget_yarns.item_account_id,
                budget_yarns.ratio,
                budget_yarns.cons,
                budget_yarns.rate,
                budget_yarns.amount,
                sales_orders.id
            ) m group by 
            m.sales_order_id
          ) budgetyarn on budgetyarn.sales_order_id = sales_orders.id
          
          left join(
            select
                po_yarn_item_bom_qties.sale_order_id,
                sum(po_yarn_item_bom_qties.qty) as total_po_qty
                from po_yarns
                join po_yarn_items on po_yarn_items.po_yarn_id=po_yarns.id
                join po_yarn_item_bom_qties on po_yarn_item_bom_qties.po_yarn_item_id=po_yarn_items.id
                where po_yarn_items.deleted_at is null
              group by 
                po_yarn_item_bom_qties.sale_order_id
          )totalPoQty on totalPoQty.sale_order_id=sales_orders.id
          
        where po_yarns.id=?
         group by
          styles.id,
          styles.style_ref,
          buyers.name,
          sales_orders.id,
          orders.rate,
          orders.amount,
          budgetyarn.yarn_qty,
          budgetyarn.yarn_amount,
          totalPoQty.total_po_qty
      )n 
      group by
      n.style_id,
      n.style_ref,
      n.buyer_name
      order by n.style_id",[$id]))
      ->map(function ($data) {
        if ($data->req_qty) {
          $data->yarn_budget_per_kg=$data->yarn_cost/$data->req_qty;
        }
        return $data;
      });

   // $amount=$data->sum('amount');
   

    //$inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents');
   // $rows->inword=$inword;
   // $purOrder['master']=$rows;
   // $purOrder['created_at']=date('d-M-Y',strtotime($rows->created_at)) ;
    
    $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(20, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->AddPage();
    $pdf->SetY(10);
    // $image_file ='images/logo/'.$rows->logo;
    // $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
    // $pdf->SetY(12);
    // $pdf->SetFont('helvetica', 'N', 8);
    // $pdf->Text(70, 12, $rows->company_address);
    // $pdf->SetY(16);
    //$pdf->AddPage();
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
    $pdf->SetX(187);
    $challan=str_pad($id,10,0,STR_PAD_LEFT ) ;
    $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 20, 0.4, $barcodestyle, 'N');
    $pdf->SetFont('helvetica', 'N', 10);
    //$pdf->Write(0, 'Dyes/Chemical Purchase Order', '', 0, 'C', true, 0, false, false, 0);
    $pdf->SetY(30);
    $pdf->SetTitle('Yarn PO Stylewise Summery');
    $view= \View::make('Defult.Purchase.PoYarnSummeryPdf',['data'=>$data]);
    $html_content=$view->render();
    $pdf->SetY(40);
    $pdf->WriteHtml($html_content, true, false,true,false,'');
    $filename = storage_path() . '/PoYarnPdf.pdf';
    $pdf->output($filename);
    exit();
  }

}