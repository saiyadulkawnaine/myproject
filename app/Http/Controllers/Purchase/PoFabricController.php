<?php

namespace App\Http\Controllers\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoFabricRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\TermsConditionRepository;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Library\Template;
use App\Library\Numbertowords;
use App\Http\Requests\Purchase\PoFabricRequest;


class PoFabricController extends Controller
{
   private $pofabric;
   private $company;
   private $supplier;
   private $currency;
   private $uom;
   private $itemcategory;
   private $termscondition;
   private $purchasetermscondition;
   private $implcpo;

	public function __construct(
    PoFabricRepository $pofabric,
    CompanyRepository $company,
    SupplierRepository $supplier,
    CurrencyRepository $currency,
    UomRepository $uom,
    ItemcategoryRepository $itemcategory,
    TermsConditionRepository $termscondition,
    PurchaseTermsConditionRepository $purchasetermscondition,
    ItemAccountRepository $itemaccount,
    BudgetFabricRepository $budgetfabric,
    ImpLcPoRepository $implcpo
  )
	{
    $this->pofabric = $pofabric;
		$this->company = $company;
		$this->supplier = $supplier;
    $this->currency = $currency;
		$this->uom = $uom;
		$this->itemcategory = $itemcategory;
    $this->termscondition = $termscondition;
    $this->purchasetermscondition = $purchasetermscondition;
    $this->itemaccount = $itemaccount;
    $this->budgetfabric = $budgetfabric;
    $this->implcpo = $implcpo;

		$this->middleware('auth');
		//$this->middleware('permission:view.pofabrics',   ['only' => ['create', 'index','show']]);
		//$this->middleware('permission:create.pofabrics', ['only' => ['store']]);
		//$this->middleware('permission:edit.pofabrics',   ['only' => ['update']]);
		//$this->middleware('permission:delete.pofabrics', ['only' => ['destroy']]);
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
        /* $rows=$this->pofabric
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
        ->orderBy('po_fabrics.id','desc')
        ->get([
          'po_fabrics.*',
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
          po_fabrics.approved_by,
          companies.code as company_code,
          suppliers.name as supplier_code,
          currencies.code as currency_code,
          po_fabrics.amount,
          sum(po_fabric_items.qty) as item_qty
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
        ->leftJoin('po_fabric_items', function($join){
          $join->on('po_fabric_items.po_fabric_id', '=', 'po_fabrics.id');
        })
        ->where([['po_type_id', 1]])
        ->orderBy('po_fabrics.id','desc')
        ->groupBy([
          'po_fabrics.id',
          'po_fabrics.po_no',
          'po_fabrics.po_date',
          'po_fabrics.company_id',
          'po_fabrics.supplier_id',
          'po_fabrics.source_id',
          'po_fabrics.pay_mode',
          'po_fabrics.delv_start_date',
          'po_fabrics.delv_end_date',
          'po_fabrics.pi_no',
          'po_fabrics.pi_date',
          'po_fabrics.exch_rate',
          'po_fabrics.remarks',
          'po_fabrics.approved_by',
          'companies.code',
          'suppliers.name',
          'currencies.code',
          'po_fabrics.amount'
          ])
        ->get()
        ->map(function($rows) use($source,$paymode){
          $rows->source=isset($source[$rows->source_id])?$source[$rows->source_id]:'';
          $rows->paymode=isset($paymode[$rows->pay_mode])?$paymode[$rows->pay_mode]:'';
          $rows->item_qty = number_format($rows->item_qty,2);
          $rows->amount = number_format($rows->amount,2);
          $rows->delv_start_date=date('d-M-Y',strtotime($rows->delv_start_date));
          $rows->po_date=$rows->po_date?date('d-M-Y',strtotime($rows->po_date)):'--';
          $rows->pi_date=$rows->pi_date?date('d-M-Y',strtotime($rows->pi_date)):'--';
          $rows->delv_end_date=date('d-M-Y',strtotime($rows->delv_end_date));
          $rows->approve_status=$rows->approved_by?'Approved':'--';
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
      $supplier=array_prepend(array_pluck($this->supplier->fabricSupplier(),'name','id'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
      $indentor=array_prepend(array_pluck($this->supplier->indentor(),'name','id'),'','');

      return Template::loadView("Purchase.PoFabric", ['company'=>$company,'source'=>$source,'supplier'=>$supplier,'currency'=>$currency,'paymode'=>$paymode,'order_type_id'=>1,'basis'=>$basis,'uom'=>$uom,'indentor'=>$indentor]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoFabricRequest $request)
    {
      $max = $this->pofabric->where([['company_id', $request->company_id]])->max('po_no');
      $po_no=$max+1;
      $pofabric = $this->pofabric->create(['po_no'=>$po_no,'po_type_id'=>1,'po_date'=>$request->po_date,'company_id'=>$request->company_id,'source_id'=>$request->source_id,'basis_id'=>20,'supplier_id'=>$request->supplier_id,'currency_id'=>$request->currency_id,'exch_rate'=>$request->exch_rate,'delv_start_date'=>$request->delv_start_date,'delv_end_date'=>$request->delv_end_date,'pay_mode'=>$request->pay_mode,'pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'remarks'=>$request->remarks,'indentor_id'=>$request->indentor_id]);

      $termscondition=$this->termscondition->where([['menu_id','=',1]])->orderBy('sort_id')->get();
      foreach($termscondition as $row)
      {
      $purchasetermscondition = $this->purchasetermscondition->create(['purchase_order_id'=>$pofabric->id,'term'=>$row->term,'sort_id'=>$row->sort_id,'menu_id'=>1]);
      }

      if ($pofabric) {
      return response()->json(array('success' => true, 'id' => $pofabric->id, 'message' => 'Save Successfully'), 200);
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
      $pofabric = $this->pofabric->find($id);
      $row ['fromData'] = $pofabric;
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
    public function update(PoFabricRequest $request, $id)
    {
      $approved=$this->pofabric->find($id);
      if($approved->approved_at){
      $this->pofabric->update($id, ['pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'remarks'=>$request->remarks]);
        return response()->json(array('success' => false,  'message' => 'Approved, Update not Possible Except PI No , PI Date & Remarks,'), 200);
      }

      $implcpo=$this->implcpo
      ->join('imp_lcs',function($join){
        $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
      })
      ->where([['imp_lcs.menu_id','=',1]])
      ->where([['imp_lc_pos.purchase_order_id','=',$id]])
      ->get(['imp_lc_pos.purchase_order_id'])
      ->first();

      if ($implcpo) {
        $pofabric = $this->pofabric->update($id, $request->except(['id','company_id','supplier_id']));
      }else {
        $pofabric = $this->pofabric->update($id, $request->except(['id','company_id']));
      }
      if ($pofabric) {
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
      if($this->pofabric->delete($id)){
  			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
  		}
    }

    /* public function getPdf()
      {

        $id=request('id',0);
        $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
        $rows=$this->pofabric
        ->join('companies',function($join){
        $join->on('companies.id','=','po_fabrics.company_id');
        })
        ->join('currencies',function($join){
        $join->on('currencies.id','=','po_fabrics.currency_id');
        })
        ->join('suppliers',function($join){
        $join->on('suppliers.id','=','po_fabrics.supplier_id');
        })
        ->join('users',function($join){
        $join->on('users.id','=','po_fabrics.created_by');
        })
        ->where([['po_fabrics.id','=',$id]])
        ->get([
        'po_fabrics.*',
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

        $data=$this->pofabric
        ->selectRaw(
        '
        styles.style_ref,
        sales_orders.sale_order_no,
        itemclasses.name as itemclass_name,
        colors.name as gmt_color,
        sizes.name as gmt_size,
        yarncolors.name as yarn_color,
        budget_yarn_cons.measurment,
        po_fabric_item_qties.description,
        uoms.code as uom_code,
        po_fabric_item_qties.description,
        sum(po_fabric_item_qties.qty) as qty,
        avg(po_fabric_item_qties.rate) as rate,
        sum(po_fabric_item_qties.amount) as amount
        '
      )
        ->join('po_fabric_items',function($join){
        $join->on('po_fabric_items.po_fabric_id','=','po_fabrics.id');
        })
        ->join('budget_yarns',function($join){
        $join->on('budget_yarns.id','=','po_fabric_items.budget_yarn_id');
        })
        ->join('itemclasses',function($join){
        $join->on('itemclasses.id','=','budget_yarns.itemclass_id');
        })
        ->join('uoms',function($join){
        $join->on('uoms.id','=','budget_yarns.uom_id');
        })
        ->join('po_fabric_item_qties',function($join){
        $join->on('po_fabric_item_qties.po_fabric_item_id','=','po_fabric_items.id');
        })
        ->join('budget_yarn_cons',function($join){
        $join->on('budget_yarn_cons.id','=','po_fabric_item_qties.budget_yarn_con_id');
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

        ->where([['po_fabrics.id','=',$id]])
        
       ->groupBy([
        'styles.style_ref',
        'sales_orders.sale_order_no',
        'itemclasses.name',
        'colors.name',
        'sizes.name',
        'yarncolors.name',
        'budget_yarn_cons.measurment',
        'po_fabric_item_qties.description',
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

  // public function getPdf(){

    //   $id=request('id',0);
    //   $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
    //   $rows=$this->pofabric
    //   ->join('companies',function($join){
    //     $join->on('companies.id','=','po_fabrics.company_id');
    //   })
    //   ->join('currencies',function($join){
    //     $join->on('currencies.id','=','po_fabrics.currency_id');
    //   })
    //   ->join('suppliers',function($join){
    //     $join->on('suppliers.id','=','po_fabrics.supplier_id');
    //   })
    //   ->join('users',function($join){
    //     $join->on('users.id','=','po_fabrics.created_by');
    //   })
    //   ->leftJoin('employee_h_rs',function($join){
    //     $join->on('users.id','=','employee_h_rs.user_id');
    //   })
    //   ->where([['po_fabrics.id','=',$id]])
    //   ->get([
    //     'po_fabrics.*',
    //     'po_fabrics.id as po_fabric_id',
    //     'companies.name as company_name',
    //     'companies.logo as logo',
    //     'companies.address as company_address',
    //     'currencies.code as currency_name',
    //     'suppliers.name as supplier_name',
    //     'suppliers.address as supplier_address',
    //     'suppliers.contact_person',
    //     'suppliers.designation',
    //     'suppliers.email',
    //     'users.name as user_name',
    //     'employee_h_rs.contact'
    //   ])
    //   ->first();

    //   $rows->pay_mode=$paymode[$rows->pay_mode];
    //   $rows->po_date=date('d-M-Y',strtotime($rows->po_date));
    //   $rows->delv_start_date=date('d-M-Y',strtotime($rows->delv_start_date));
    //   $rows->delv_end_date=date('d-M-Y',strtotime($rows->delv_end_date));
    //   $rows->contact_detail=$rows->contact_person.','.$rows->designation.','.$rows->email;

    //   $yarnDescription=$this->itemaccount
    //   ->join('item_account_ratios',function($join){
    //   $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
    //   })
    //   ->join('yarncounts',function($join){
    //   $join->on('yarncounts.id','=','item_accounts.yarncount_id');
    //   })
    //   ->join('yarntypes',function($join){
    //   $join->on('yarntypes.id','=','item_accounts.yarntype_id');
    //   })
    //   ->join('itemclasses',function($join){
    //   $join->on('itemclasses.id','=','item_accounts.itemclass_id');
    //   })
    //   ->join('compositions',function($join){
    //   $join->on('compositions.id','=','item_account_ratios.composition_id');
    //   })
    //   ->join('itemcategories',function($join){
    //   $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
    //   })
    //   //->where([['itemcategories.identity','=',1]])
    //   ->get([
    //   'item_accounts.id',
    //   'yarncounts.count',
    //   'yarncounts.symbol',
    //   'yarntypes.name as yarn_type',
    //   'itemclasses.name as itemclass_name',
    //   'compositions.name as composition_name',
    //   'item_account_ratios.ratio',
    //   ]);
    //   $itemaccountArr=array();
    //   $yarnCompositionArr=array();
    //   foreach($yarnDescription as $row){
    //       $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
    //       $itemaccountArr[$row->id]['yarn_type']=$row->yarn_type;
    //       //$itemaccountArr[$row->id]['itemclass_name']=$row->itemclass_name;
    //       $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
    //   }
    //   $yarnDropdown=array();
    //   foreach($itemaccountArr as $key=>$value){
    //       $yarnDropdown[$key]=/* $value['itemclass_name']." ". */$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
    //   }

    //   $data=$this->pofabric
    //     ->selectRaw(
    //     '
    //       uoms.code as uom_code,
    //       po_fabric_items.po_fabric_id,
    //       po_fabric_items.item_account_id,
    //       po_fabric_items.remarks as item_remarks,
    //       po_fabric_items.qty,
    //       po_fabric_items.rate,
    //       po_fabric_items.amount
    //     '
    //     )/* 
        
    //     sum(po_fabric_items.qty) as qty,
    //       avg(po_fabric_items.rate) as rate,
    //       sum(po_fabric_items.amount) as amount
    //     */
    //     ->join('po_fabric_items', function($join){
    //       $join->on('po_fabric_items.po_fabric_id', '=', 'po_fabrics.id');
    //     })
    //     ->join('item_accounts', function($join){
    //       $join->on('item_accounts.id', '=', 'po_fabric_items.item_account_id');
    //     })
    //     ->join('itemclasses', function($join){
    //       $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
    //     })
    //     ->join('itemcategories', function($join){
    //       $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
    //     })
    //     ->leftJoin('uoms', function($join){
    //       $join->on('uoms.id', '=', 'item_accounts.uom_id');
    //     })
    //     ->where([['po_fabrics.id','=',$id]])
    //     ->groupBy([
    //       'uoms.code',
    //       'po_fabric_items.po_fabric_id',
    //       'po_fabric_items.item_account_id',
    //       'po_fabric_items.remarks',
    //       'po_fabric_items.qty',
    //       'po_fabric_items.rate',
    //       'po_fabric_items.amount'
    //     ])
    //     ->get()
    //     ->map(function ($data) use($yarnDropdown) {
    //         $data->item_description = $yarnDropdown[$data->item_account_id];
    //         return $data;
    //     });
    //   $amount=$data->sum('amount');
     

    //   $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents');
    //   $rows->inword=$inword;
    //   $purOrder['master']=$rows;
    //   $purOrder['created_at']=date('d-M-Y',strtotime($rows->created_at)) ;
    //   $details=$data->groupBy('po_fabric_id');
    //   $purOrder['details']=$details;
    //   $purchasetermscondition=$this->purchasetermscondition->where([['purchase_order_id','=',$id]])->where([['menu_id','=',3]])->orderBy('sort_id')->get();
    //   $purOrder['purchasetermscondition']=$purchasetermscondition;
    //   $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    //   $pdf->SetPrintHeader(false);
    //   $pdf->SetPrintFooter(false);
    //   $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    //   $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    //   $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    //   $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    //   $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    //   $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    //   $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    //   $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    //   $pdf->SetFont('helvetica', 'B', 12);
    //   $pdf->AddPage();
    //   $pdf->SetY(10);
    //   $image_file ='images/logo/'.$rows->logo;
    //   $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
    //   $pdf->SetY(12);
    //   $pdf->SetFont('helvetica', 'N', 8);
    //   $pdf->Text(70, 12, $rows->company_address);
    //   $pdf->SetY(16);
    //   //$pdf->AddPage();
    //     $barcodestyle = array(
    //         'position' => '',
    //         'align' => 'C',
    //         'stretch' => false,
    //         'fitwidth' => true,
    //         'cellfitalign' => '',
    //         'border' => false,
    //         'hpadding' => 'auto',
    //         'vpadding' => 'auto',
    //         'fgcolor' => array(0,0,0),
    //         'bgcolor' => false, //array(255,255,255),
    //         'text' => true,
    //         'font' => 'helvetica',
    //         'fontsize' => 8,
    //         'stretchtext' => 4
    //     );
    //     $pdf->SetY(5);
    //     $pdf->SetX(150);
    //     $challan=str_pad($purOrder['master']->po_fabric_id,10,0,STR_PAD_LEFT ) ;
    //     $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
    //     $pdf->SetFont('helvetica', 'N', 10);
    //     //$pdf->Write(0, 'Dyes/Chemical Purchase Order', '', 0, 'C', true, 0, false, false, 0);
    //     $pdf->SetFont('helvetica', '', 8);
    //     //$pdf->SetTitle('General Item Purchase Order');
    //     $view= \View::make('Defult.Purchase.PoFabricPdf',['purOrder'=>$purOrder,'data'=>$data]);
    //     $html_content=$view->render();
    //     $pdf->SetY(20);
    //     $pdf->WriteHtml($html_content, true, false,true,false,'');
    //     $filename = storage_path() . '/PoFabricPdf.pdf';
    //     $pdf->output($filename);
  // }
  public function getPosPdf(){
      $id=request('id',0);
      $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
      $rows=$this->pofabric
      ->join('companies',function($join){
        $join->on('companies.id','=','po_fabrics.company_id');
      })
      ->join('currencies',function($join){
        $join->on('currencies.id','=','po_fabrics.currency_id');
      })
      ->join('suppliers',function($join){
        $join->on('suppliers.id','=','po_fabrics.supplier_id');
      })
      ->join('users',function($join){
        $join->on('users.id','=','po_fabrics.created_by');
      })
      ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->leftJoin('users as approve_users',function($join){
      $join->on('approve_users.id','=','po_fabrics.approved_by');
      })
      ->leftJoin('employee_h_rs as approve_employee',function($join){
      $join->on('approve_employee.user_id','=','approve_users.id');
      })
      ->leftJoin('designations',function($join){
      $join->on('approve_employee.designation_id','=','designations.id');
      })
      ->where([['po_fabrics.id','=',$id]])
      ->get([
        'po_fabrics.*',
        'po_fabrics.id as po_fabric_id',
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
      $rows->delv_start_date=($rows->delv_start_date!==null)?date('d-M-Y',strtotime($rows->delv_start_date)):null;
      $rows->delv_end_date=($rows->delv_end_date!==null)?date('d-M-Y',strtotime($rows->delv_end_date)):null;
      $rows->contact_detail=$rows->contact_person.','.$rows->designation.','.$rows->email;
      $rows->approved_user_signature=$rows->signature_file?'images/signature/'.$rows->signature_file:null;
      $rows->approved_at=$rows->approved_at?date('d-M-Y',strtotime($rows->approved_at)):null;

      $materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
      $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
      $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
      $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
      $fabricDescription=$this->budgetfabric
      ->join('style_fabrications',function($join){
      $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
      })
      ->join('style_gmts',function($join){
      $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
      })
      ->join('item_accounts', function($join) {
      $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
      })
      ->join('budgets',function($join){
      $join->on('budgets.id','=','budget_fabrics.budget_id');
      })
      ->join('jobs',function($join){
      $join->on('jobs.id','=','budgets.job_id');
      })
      ->join('styles', function($join) {
      $join->on('styles.id', '=', 'jobs.style_id');
      })
      ->join('gmtsparts',function($join){
      $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
      })
      ->join('autoyarns',function($join){
      $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
      })
      
      ->join('autoyarnratios',function($join){
        $join->on('autoyarns.id','=','autoyarnratios.autoyarn_id');
      })
      ->join('compositions',function($join){
        $join->on('compositions.id','=','autoyarnratios.composition_id');
      })
      ->join('constructions',function($join){
        $join->on('constructions.id','=','autoyarns.construction_id');
      })
      ->join('po_fabric_items',function($join){
      $join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id')
      ->whereNull('po_fabric_items.deleted_at');
      })
      ->join('po_fabrics',function($join){
      $join->on('po_fabrics.id','=','po_fabric_items.po_fabric_id');
      })
      ->where([['po_fabrics.id','=',$id]])
      ->get([
      'style_fabrications.id',
      'constructions.name as construction',
      'autoyarnratios.composition_id',
      'compositions.name',
      'autoyarnratios.ratio',
      ]);
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
    

      $fabrics=$this->pofabric
        ->selectRaw('
          jobs.job_no,
          styles.style_ref,
 
          
          sum(po_fabric_item_qties.qty) as qty,
          avg(po_fabric_item_qties.rate) as rate,
          sum(po_fabric_item_qties.amount) as amount,
          budget_fabrics.id as budget_fabric_id,
          budget_fabrics.budget_id,
          budget_fabrics.style_fabrication_id,
          budget_fabrics.gsm_weight,

          fabric_colors.name as fabric_color_name,
          budget_fabric_cons.dia,
          budget_fabric_cons.fabric_color,

          style_fabrications.fabric_nature_id,
          style_fabrications.gmtspart_id,
          style_fabrications.autoyarn_id,
          style_fabrications.fabric_look_id,
          style_fabrications.material_source_id,
          style_fabrications.is_stripe,
          style_fabrications.fabric_shape_id,
          style_fabrications.uom_id,
          style_fabrications.is_narrow,
          gmtsparts.name as gmtspart_name,
          item_accounts.item_description,
          uoms.code as uom_code
        ')/* */
        ->join('po_fabric_items',function($join){
          $join->on('po_fabrics.id','=','po_fabric_items.po_fabric_id');
        })
        ->join('budget_fabrics',function($join){
          $join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id')
          ->whereNull('po_fabric_items.deleted_at');
        })
        ->join('style_fabrications',function($join){
          $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->join('style_gmts',function($join){
          $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
          $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->join('budgets',function($join){
          $join->on('budgets.id','=','budget_fabrics.budget_id');
        })
        ->join('jobs',function($join){
          $join->on('jobs.id','=','budgets.job_id');
        })
        ->join('styles', function($join) {
          $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('gmtsparts',function($join){
          $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
        })
        ->join('autoyarns',function($join){
          $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
        })
        ->join('uoms',function($join){
          $join->on('uoms.id','=','style_fabrications.uom_id');
        })
        ->join('budget_fabric_cons',function($join){
          $join->on('budget_fabric_cons.budget_fabric_id','=','po_fabric_items.budget_fabric_id')
          //->on('budget_fabric_cons.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id')
          ->whereNull('budget_fabric_cons.deleted_at');
        })
        ->join('colors as fabric_colors',function($join){
          $join->on('fabric_colors.id','=','budget_fabric_cons.fabric_color');
        })  
        ->join('po_fabric_item_qties',function($join){
          $join->on('po_fabric_item_qties.po_fabric_item_id','=','po_fabric_items.id');
          $join->on('po_fabric_item_qties.budget_fabric_con_id','=','budget_fabric_cons.id');
        })
        ->where([['po_fabrics.id','=',$id]])
        //->orderBy('po_fabric_item_qties.id','desc')
        ->groupBy([
          'jobs.job_no',
          'styles.style_ref',
        
        
          'budget_fabrics.id',
          'budget_fabrics.budget_id',
          'budget_fabrics.style_fabrication_id',
          'budget_fabrics.gsm_weight',

          'fabric_colors.name',
          'budget_fabric_cons.dia',
          'budget_fabric_cons.fabric_color',

          'style_fabrications.fabric_nature_id',
          'style_fabrications.gmtspart_id',
          'style_fabrications.autoyarn_id',
          'style_fabrications.fabric_look_id',
          'style_fabrications.material_source_id',
          'style_fabrications.is_stripe',
          'style_fabrications.fabric_shape_id',
          'style_fabrications.uom_id',
          'style_fabrications.is_narrow',
          'gmtsparts.name',
          'item_accounts.item_description',
          'uoms.code'
        ])
        ->get()
        ->map(function($fabrics) use($desDropdown,$materialsourcing,$fabricnature,$fabriclooks,$fabricshape){
          $fabrics->style_fabrication_id =  $fabrics->style_fabrication_id;
          $fabrics->style_gmt = $fabrics->item_description;
          $fabrics->gmtspart =  $fabrics->gmtspart_name;
          $fabrics->fabric_description =  $desDropdown[$fabrics->style_fabrication_id];
          $fabrics->uom_name =  $fabrics->uom_name;
          $fabrics->materialsourcing =  $materialsourcing[$fabrics->material_source_id];
          $fabrics->fabricnature =  $fabricnature[$fabrics->fabric_nature_id];
          $fabrics->fabriclooks = $fabriclooks[$fabrics->fabric_look_id];
          $fabrics->fabricshape = $fabricshape[$fabrics->fabric_shape_id];
          return $fabrics;
        });
        $data=$fabrics;
      //$amount=$data->sum('amount');
     

      //$inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents');
      //$rows->inword=$inword;
      $purOrder['master']=$rows;
      $purOrder['created_at']=date('d-M-Y',strtotime($rows->created_at)) ;
      $details=$data->groupBy('po_fabric_id');
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
      //$pdf->Text(70, 12, $rows->company_address);
      $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
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
        $challan=str_pad( $id,10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
        $pdf->SetFont('helvetica', 'N', 10);
        //$pdf->Write(0, 'Dyes/Chemical Purchase Order', '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', '', 8);
        //$pdf->SetTitle('General Item Purchase Order');
        $view= \View::make('Defult.Purchase.PoFabricPosPdf',['purOrder'=>$purOrder,'data'=>$data]);
        $html_content=$view->render();
        $pdf->SetY(35);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/PoFabricPdf.pdf';
        $pdf->output($filename);
  }

  public function getPodPdf(){
      $id=request('id',0);
      $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
      $rows=$this->pofabric
      ->join('companies',function($join){
        $join->on('companies.id','=','po_fabrics.company_id');
      })
      ->join('currencies',function($join){
        $join->on('currencies.id','=','po_fabrics.currency_id');
      })
      ->join('suppliers',function($join){
        $join->on('suppliers.id','=','po_fabrics.supplier_id');
      })
      ->join('users',function($join){
        $join->on('users.id','=','po_fabrics.created_by');
      })
      ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->leftJoin('users as approve_users',function($join){
        $join->on('approve_users.id','=','po_fabrics.approved_by');
      })
      ->leftJoin('employee_h_rs as approve_employee',function($join){
        $join->on('approve_employee.user_id','=','approve_users.id');
      })
      ->leftJoin('designations',function($join){
        $join->on('approve_employee.designation_id','=','designations.id');
      })
      ->where([['po_fabrics.id','=',$id]])
      ->get([
        'po_fabrics.*',
        'po_fabrics.id as po_fabric_id',
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
      $rows->delv_start_date=($rows->delv_start_date!==null)?date('d-M-Y',strtotime($rows->delv_start_date)):null;
      $rows->delv_end_date=($rows->delv_end_date!==null)?date('d-M-Y',strtotime($rows->delv_end_date)):null;
      $rows->contact_detail=$rows->contact_person.','.$rows->designation.','.$rows->email;
      $rows->approved_user_signature=$rows->signature_file?'images/signature/'.$rows->signature_file:null;
      $rows->approved_at=$rows->approved_at?date('d-M-Y',strtotime($rows->approved_at)):null;

      $materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
      $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
      $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
      $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
      $fabricDescription=$this->budgetfabric
      ->join('style_fabrications',function($join){
      $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
      })
      ->join('style_gmts',function($join){
      $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
      })
      ->join('item_accounts', function($join) {
      $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
      })
      ->join('budgets',function($join){
      $join->on('budgets.id','=','budget_fabrics.budget_id');
      })
      ->join('jobs',function($join){
      $join->on('jobs.id','=','budgets.job_id');
      })
      ->join('styles', function($join) {
      $join->on('styles.id', '=', 'jobs.style_id');
      })
      ->join('gmtsparts',function($join){
      $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
      })
      ->join('autoyarns',function($join){
      $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
      })
      ->join('autoyarnratios',function($join){
        $join->on('autoyarns.id','=','autoyarnratios.autoyarn_id');
      })
      ->join('compositions',function($join){
        $join->on('compositions.id','=','autoyarnratios.composition_id');
      })
      ->join('constructions',function($join){
        $join->on('constructions.id','=','autoyarns.construction_id');
      })
      ->join('po_fabric_items',function($join){
      $join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id')
      ->whereNull('po_fabric_items.deleted_at');
      })
      ->join('po_fabrics',function($join){
      $join->on('po_fabrics.id','=','po_fabric_items.po_fabric_id');
      })
      ->where([['po_fabrics.id','=',$id]])
      ->get([
      'style_fabrications.id',
      'constructions.name as construction',
      'autoyarnratios.composition_id',
      'compositions.name',
      'autoyarnratios.ratio',
      ]);
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
    

      $fabrics=$this->pofabric
        ->selectRaw(
          'jobs.job_no,
          styles.id as style_id,
          styles.style_ref,
          styles.buyer_id,
          gmt_colors.name as color_name,
          gmt_colors.code as color_code,
          fabric_colors.name as fabric_color_name,
          fabric_colors.code as fabric_color_code,
          po_fabric_items.id as po_fabric_item_id,
          budget_fabrics.id as budget_fabric_id,
          budget_fabrics.budget_id,
          budget_fabrics.style_fabrication_id,
          budget_fabrics.gsm_weight,

          budget_fabric_cons.dia,
          budget_fabric_cons.cons,
          budget_fabric_cons.fabric_color,
          budget_fabric_cons.measurment,
          sales_orders.sale_order_no, 

          style_fabrications.fabric_nature_id,
          style_fabrications.gmtspart_id,
          style_fabrications.autoyarn_id,
          style_fabrications.fabric_look_id,
          style_fabrications.material_source_id,
          style_fabrications.is_stripe,
          style_fabrications.fabric_shape_id,
          style_fabrications.uom_id,
          style_fabrications.is_narrow,
          gmtsparts.name as gmtspart_name,
          item_accounts.item_description,
          uoms.code as uom_code,
          buyers.code as buyer_code,
          
          sum(po_fabric_item_qties.qty) as qty,
          avg(po_fabric_item_qties.rate) as rate,
          sum(po_fabric_item_qties.amount) as amount'
        )
        /*
        po_fabric_item_qties.id as po_fabric_item_qty_id,
            fabric_colors.name as fabric_color_name,
          avg(budget_fabric_cons.dia) as dia,
          budget_fabric_cons.fabric_color,

        */
        ->join('po_fabric_items',function($join){
          $join->on('po_fabrics.id','=','po_fabric_items.po_fabric_id');
        })
        ->join('po_fabric_item_qties',function($join){
          $join->on('po_fabric_item_qties.po_fabric_item_id','=','po_fabric_items.id');
        })
        ->join('budget_fabric_cons',function($join){
          $join->on('po_fabric_item_qties.budget_fabric_con_id','=','budget_fabric_cons.id'); 
        })
        ->join('budget_fabrics',function($join){
          $join->on('budget_fabric_cons.budget_fabric_id','=','budget_fabrics.id');  
          $join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id');
        })
        ->join('sales_order_gmt_color_sizes',function($join){
          $join->on('budget_fabric_cons.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
        })
        ->join('sales_order_countries',function($join){
          $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
        })
        ->join('sales_orders',function($join){
          $join->on('sales_order_countries.sale_order_id','=','sales_orders.id');
        })
        ->join('jobs',function($join){
          $join->on('sales_orders.job_id','=','jobs.id');
        })
        ->leftJoin('style_sizes',function($join){
          $join->on('style_sizes.id','=','sales_order_gmt_color_sizes.style_size_id');
        })
        ->leftJoin('sizes',function($join){
          $join->on('sizes.id','=','style_sizes.size_id');
        })
        ->join('style_colors',function($join){
          $join->on('style_colors.id','=','sales_order_gmt_color_sizes.style_color_id');
        })
        ->join('colors as gmt_colors',function($join){
          $join->on('gmt_colors.id','=','style_colors.color_id');
        })
        ->join('colors as fabric_colors',function($join){
          $join->on('fabric_colors.id','=','budget_fabric_cons.fabric_color');
        })
        ->join('countries',function($join){
          $join->on('countries.id','=','sales_order_countries.country_id');
        })
        ->join('style_fabrications',function($join){
          $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->join('style_gmts',function($join){
          $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
          $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->join('budgets',function($join){
          $join->on('budgets.id','=','budget_fabrics.budget_id');
          $join->on('jobs.id','=','budgets.job_id');
        })
        ->join('styles', function($join) {
          $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->leftJoin('buyers',function($join){
          $join->on('buyers.id','=','styles.buyer_id');
        })
        ->join('gmtsparts',function($join){
          $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
        })
        ->join('autoyarns',function($join){
          $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
        })
        ->join('uoms',function($join){
          $join->on('uoms.id','=','style_fabrications.uom_id');
        })
        ->where([['po_fabrics.id','=',$id]])
        //->orderBy('style_colors.sort_id')
        //->orderBy('style_sizes.sort_id')
        ->groupBy([
          'jobs.job_no',
          'styles.id',
          'styles.style_ref',
          'styles.buyer_id',
          'gmt_colors.name',
          'gmt_colors.code',
          'fabric_colors.name',
          'fabric_colors.code',
          'po_fabric_items.id',
          'budget_fabrics.id',
          'budget_fabrics.budget_id',
          'budget_fabrics.style_fabrication_id',
          'budget_fabrics.gsm_weight',

          'budget_fabric_cons.dia',
          'budget_fabric_cons.cons',
          'budget_fabric_cons.fabric_color',
          'budget_fabric_cons.measurment',
          'sales_orders.sale_order_no', 

          'style_fabrications.fabric_nature_id',
          'style_fabrications.gmtspart_id',
          'style_fabrications.autoyarn_id',
          'style_fabrications.fabric_look_id',
          'style_fabrications.material_source_id',
          'style_fabrications.is_stripe',
          'style_fabrications.fabric_shape_id',
          'style_fabrications.uom_id',
          'style_fabrications.is_narrow',
          'gmtsparts.name',
          'item_accounts.item_description',
          'uoms.code',
          'buyers.code',
    
        ])
        ->get(['po_fabric_item_qties.*'])
        ->map(function($fabrics) use($desDropdown,$materialsourcing,$fabricnature,$fabriclooks,$fabricshape){
          $fabrics->style_fabrication_id =  $fabrics->style_fabrication_id;
          $fabrics->style_gmt = $fabrics->item_description;
          $fabrics->gmtspart =  $fabrics->gmtspart_name;
          $fabrics->fabric_description =  $desDropdown[$fabrics->style_fabrication_id];
          $fabrics->uom_name =  $fabrics->uom_name;
          $fabrics->materialsourcing =  $materialsourcing[$fabrics->material_source_id];
          $fabrics->fabricnature =  $fabricnature[$fabrics->fabric_nature_id];
          $fabrics->fabriclooks = $fabriclooks[$fabrics->fabric_look_id];
          $fabrics->fabricshape = $fabricshape[$fabrics->fabric_shape_id];
          return $fabrics;
        });
        $data=$fabrics;

      $amount=$data->sum('amount');
      $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents');
      $rows->inword=$inword;
      $purOrder['master']=$rows;
      $purOrder['created_at']=date('d-M-Y',strtotime($rows->created_at)) ;
      $details=$data->groupBy('style_id','buyer_id');
      $purOrder['details']=$details;
      $purchasetermscondition=$this->purchasetermscondition->where([['purchase_order_id','=',$id]])->where([['menu_id','=',3]])->orderBy('sort_id')->get();
      $purOrder['purchasetermscondition']=$purchasetermscondition;
      $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetPrintHeader(false);
      $pdf->SetPrintFooter(true);
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->SetMargins('6.5', PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
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
      //$pdf->Text(117, 12, $rows->company_address);
      $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
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
        $pdf->SetX(200);
        $challan=str_pad($purOrder['master']->po_fabric_id,10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
        $pdf->SetFont('helvetica', 'N', 10);
        //$pdf->Write(0, 'Dyes/Chemical Purchase Order', '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', '', 8);
        //$pdf->SetTitle('General Item Purchase Order');
        $view= \View::make('Defult.Purchase.PoFabricPodPdf',['purOrder'=>$purOrder,'data'=>$data]);
        $html_content=$view->render();
        $pdf->SetY(35);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/PoFabricPodPdf.pdf';
        $pdf->output($filename);
    }
    

}
