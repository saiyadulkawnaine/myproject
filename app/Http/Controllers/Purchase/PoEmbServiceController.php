<?php

namespace App\Http\Controllers\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoEmbServiceRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\TermsConditionRepository;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Library\Numbertowords;
use App\Http\Requests\Purchase\PoEmbServiceRequest;

class PoEmbServiceController extends Controller
{
    private $poembservice;
    private $company;
    private $supplier;
    private $currency;
    private $termscondition;
    private $purchasetermscondition;
    private $itemaccount;
    

	public function __construct(
        PoEmbServiceRepository $poembservice,
        CompanyRepository $company,
        SupplierRepository $supplier,
        CurrencyRepository $currency,
        TermsConditionRepository $termscondition,
        PurchaseTermsConditionRepository $purchasetermscondition,
        ItemAccountRepository $itemaccount
        )
	{
        $this->poembservice = $poembservice;
        $this->company = $company;
        $this->supplier = $supplier;
        $this->currency = $currency;
        $this->termscondition = $termscondition;
        $this->purchasetermscondition = $purchasetermscondition;
        $this->itemaccount = $itemaccount;
        $this->middleware('auth');
        $this->middleware('permission:view.poembservices',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.poembservices', ['only' => ['store']]);
        $this->middleware('permission:edit.poembservices',   ['only' => ['update']]);
        $this->middleware('permission:delete.poembservices', ['only' => ['destroy']]);
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productionarea=array_prepend(array_only(config('bprs.productionarea'),[45,50,58,60,51]),'-Select-','');
		$paymode=config('bprs.paymode');
		$rows=$this->poembservice
		->join('companies',function($join){
		  $join->on('companies.id','=','po_emb_services.company_id');
		})
		->join('suppliers',function($join){
		  $join->on('suppliers.id','=','po_emb_services.supplier_id');
		})
		->join('currencies',function($join){
		  $join->on('currencies.id','=','po_emb_services.currency_id');
		})
		->where([['po_type_id', 1]])
		->orderBy('po_emb_services.id','desc')
		->get([
		  'po_emb_services.*',
		  'companies.code as company_code',
		  'suppliers.code as supplier_code',
		  'currencies.code as currency_code',
		])
		->map(function ($rows) use($paymode,$productionarea)  {
            $rows->paymode = $paymode[$rows->pay_mode];
            $rows->production_area = $productionarea[$rows->production_area_id];
            $rows->amount = number_format($rows->amount,2);
            $rows->delv_start_date=date('d-M-Y',strtotime($rows->delv_start_date));
            $rows->po_date=date('d-M-Y',strtotime($rows->po_date));
            $rows->delv_end_date=date('d-M-Y',strtotime($rows->delv_end_date));
            $rows->approve_status=($rows->approved_at)?'Approved':'--';
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
        $basis = array_prepend(array_only(config('bprs.pur_order_basis'), [1]),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->embellishmentSubcontractor(),'name','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
        $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
        $productionarea=array_prepend(array_only(config('bprs.productionarea'),[45,50,58,60,51]),'-Select-','');
		return Template::loadView("Purchase.PoEmbService", ['company'=>$company,'basis'=>$basis,'supplier'=>$supplier,'currency'=>$currency,'paymode'=>$paymode,'productionarea'=>$productionarea]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoEmbServiceRequest $request)
    {
        $max = $this->poembservice->where([['company_id', $request->company_id]])->max('po_no');
        $po_no=$max+1;

        $poembservice = $this->poembservice->create(['po_no'=>$po_no,'po_type_id'=>1,'company_id'=>$request->company_id,'po_date'=>$request->po_date,'basis_id'=>$request->basis_id,'supplier_id'=>$request->supplier_id,'pay_mode'=>$request->pay_mode,'currency_id'=>$request->currency_id,'exch_rate'=>$request->exch_rate,'delv_start_date'=>$request->delv_start_date,'delv_end_date'=>$request->delv_end_date,'pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'production_area_id'=>$request->production_area_id,'remarks'=>$request->remarks]);
        $termscondition=$this->termscondition->where([['menu_id','=',10]])->orderBy('sort_id')->get();
        foreach($termscondition as $row)
        {
        $purchasetermscondition = $this->purchasetermscondition->create(['purchase_order_id'=>$poembservice->id,'term'=>$row->term,'sort_id'=>$row->sort_id,'menu_id'=>10]);
        }
        if ($poembservice) {
        return response()->json(array('success' => true, 'id' => $poembservice->id, 'message' => 'Save Successfully'), 200);
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
		$poembservice = $this->poembservice->find($id);
        $poembservice->po_date=date('Y-m-d',strtotime($poembservice->po_date));

        if($poembservice->delv_start_date){
            $poembservice->delv_start_date=date('Y-m-d',strtotime($poembservice->delv_start_date));
        }
        
        if($poembservice->delv_end_date){
           $poembservice->delv_end_date=date('Y-m-d',strtotime($poembservice->delv_end_date));
        }
		$row ['fromData'] = $poembservice;
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
    public function update(PoEmbServiceRequest $request, $id)
    {
        $approved=$this->poembservice->find($id);
        if($approved->approved_at){
            $this->poembservice->update($id, ['pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'remarks'=>$request->remarks]);
            return response()->json(array('success' => false,  'message' => 'Embelishment Work Order is Approved, Update not Possible Except PI No , PI Date & Remarks'), 200);
        }
		$poembservice = $this->poembservice->update($id, $request->except(['id','company_id','po_no','supplier_id','basis_id','production_area_id']));
		if ($poembservice) {
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
        if($this->poembservice->delete($id)){
			 return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}else{
			 return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
		}
    }

    public function getPdf()
    {

        $id=request('id',0);
        $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
        $rows=$this->poembservice
        ->join('companies',function($join){
        $join->on('companies.id','=','po_emb_services.company_id');
        })
        ->join('currencies',function($join){
        $join->on('currencies.id','=','po_emb_services.currency_id');
        })
        ->join('suppliers',function($join){
        $join->on('suppliers.id','=','po_emb_services.supplier_id');
        })
        ->join('users',function($join){
        $join->on('users.id','=','po_emb_services.created_by');
        })
        ->leftJoin('users as approve_users',function($join){
        $join->on('approve_users.id','=','po_emb_services.approved_by');
        })
        ->leftJoin('employee_h_rs as approve_employee',function($join){
        $join->on('approve_employee.user_id','=','approve_users.id');
        })
        ->leftJoin('designations',function($join){
        $join->on('approve_employee.designation_id','=','designations.id');
        })
        ->where([['po_emb_services.id','=',$id]])
        ->get([
        'po_emb_services.*',
        'po_emb_services.id as po_emb_service_id',
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

        
      


        $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');
        $fabrics=$this->poembservice
        ->selectRaw('
        budget_embs.id as budget_emb_id,
        sales_orders.id as sales_order_id,
        sales_orders.sale_order_no,
        style_embelishments.embelishment_size_id,
        embelishments.name as embelishment_name,
        embelishment_types.name as embelishment_type,
        gmtsparts.name as gmtspart_name,
        colors.name as color_name,
        sizes.name as size_name,
        item_accounts.item_description,
        po_emb_service_item_qties.remarks,
        sum(po_emb_service_item_qties.qty) as qty,
        sum(po_emb_service_item_qties.rate) as rate,
        sum(po_emb_service_item_qties.amount) as amount
        '
        )
        ->join('po_emb_service_items',function($join){
        $join->on('po_emb_services.id','=','po_emb_service_items.po_emb_service_id');
        })
        ->join('budget_embs',function($join){
        $join->on('budget_embs.id','=','po_emb_service_items.budget_emb_id');
        })
        ->join('style_embelishments',function($join){
        $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
        })
        ->join('style_gmts',function($join){
        $join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
        $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->join('gmtsparts',function($join){
        $join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
        })
        ->join('embelishments',function($join){
        $join->on('embelishments.id','=','style_embelishments.embelishment_id');
        })
        ->join('embelishment_types',function($join){
        $join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
        })

        ->join('budget_emb_cons',function($join){
        $join->on('budget_emb_cons.budget_emb_id','=','budget_embs.id')
        ->whereNull('budget_emb_cons.deleted_at');
        })
        ->join('budgets',function($join){
        $join->on('budgets.id','=','budget_embs.budget_id');
        })
        ->join('sales_order_gmt_color_sizes',function($join){
        $join->on('sales_order_gmt_color_sizes.id','=','budget_emb_cons.sales_order_gmt_color_size_id');
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
        ->join('style_gmt_color_sizes',function($join){
        $join->on('style_gmt_color_sizes.id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id');
        })
        ->join('style_sizes',function($join){
        $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
        })
        ->join('sizes',function($join){
        $join->on('sizes.id','=','style_sizes.size_id');
        })
        ->join('style_colors',function($join){
        $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
        })
        ->join('colors',function($join){
        $join->on('colors.id','=','style_colors.color_id');
        })

        ->join('countries',function($join){
        $join->on('countries.id','=','sales_order_countries.country_id');
        })

        ->join('po_emb_service_item_qties',function($join){
        $join->on('po_emb_service_item_qties.po_emb_service_item_id','=','po_emb_service_items.id');
        $join->on('po_emb_service_item_qties.budget_emb_con_id','=','budget_emb_cons.id');
        $join->whereNull('po_emb_service_item_qties.deleted_at');
        })
        ->where([['po_emb_services.id','=',$id]])
        ->groupBy([
        'budget_embs.id',
        'sales_orders.id',
        'sales_orders.sale_order_no',
        'style_embelishments.embelishment_size_id',
        'embelishments.name',
        'embelishment_types.name',
        'gmtsparts.name',
        'colors.name',
        'sizes.name',
        'item_accounts.item_description',
        'style_colors.sort_id',
        'style_sizes.sort_id',
        'po_emb_service_item_qties.remarks',
        ])
        ->orderBy('sales_orders.id')
        ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id')
        ->get()
        ->map(function ($fabrics) use($embelishmentsize)
        {
        $fabrics->embelishment_size = $embelishmentsize[$fabrics->embelishment_size_id];
        $fabrics->rate = number_format($fabrics->amount/$fabrics->qty,4);
        return $fabrics;
        });
        $amount=$fabrics->sum('amount');
        $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents');
        $rows->inword          =$inword;
        $purOrder['master']    =$rows;
        $purOrder['details']   =$fabrics;

        $purchasetermscondition=$this->purchasetermscondition->where([['purchase_order_id','=',$id]])->where([['menu_id','=',10]])->orderBy('sort_id')->get();
        $purOrder['purchasetermscondition']=$purchasetermscondition;


        $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(true);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(5);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->AddPage();
        $pdf->SetY(10);
        $image_file ='images/logo/'.$rows->logo;
        $pdf->Image($image_file, 90, 5, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $pdf->SetY(12);
        $pdf->SetFont('helvetica', 'N', 8);
        //$pdf->Text(115, 16, $rows->company_address);
        $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
        $pdf->SetY(20);
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
        $pdf->SetX(215);
        $challan=str_pad($purOrder['master']->po_emb_service_id,10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.3, $barcodestyle, 'N');
        // $pdf->SetFont('helvetica', 'N', 10);
        // $pdf->Write(0, 'Emb Service Order ', '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', '', 8);
        //$pdf->SetTitle('Emb Service Order');
        $view= \View::make('Defult.Purchase.PoEmbServicePdf',['purOrder'=>$purOrder]);
        $html_content=$view->render();
        $pdf->SetY(35);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/PoEmbServicePdf.pdf';
        $pdf->output($filename);
    }
}
