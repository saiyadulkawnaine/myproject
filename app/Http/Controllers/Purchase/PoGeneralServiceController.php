<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;


use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoGeneralServiceRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\TermsConditionRepository;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Library\Template;
use App\Library\Numbertowords;
use App\Http\Requests\Purchase\PoGeneralServiceRequest;


class PoGeneralServiceController extends Controller
{
   private $pogeneralservice;
   private $company;
   private $supplier;
   private $currency;
   private $uom;
   private $termscondition;
   private $purchasetermscondition;
   private $user;
   private $department;
   private $implcpo;

	public function __construct(
    PoGeneralServiceRepository $pogeneralservice,
    CompanyRepository $company,
    SupplierRepository $supplier,
    BuyerRepository $buyer,
    CurrencyRepository $currency,
    UomRepository $uom,
    DepartmentRepository $department,
    TermsConditionRepository $termscondition,
    PurchaseTermsConditionRepository $purchasetermscondition,
    UserRepository $user,
    ImpLcPoRepository $implcpo

  )
	{
    $this->pogeneralservice = $pogeneralservice;
    $this->company = $company;
    $this->supplier = $supplier;
    $this->buyer = $buyer;
    $this->currency = $currency;
    $this->uom = $uom;
    $this->termscondition = $termscondition;
    $this->purchasetermscondition = $purchasetermscondition;
    $this->user= $user;
    $this->department = $department;
    $this->implcpo = $implcpo;

		$this->middleware('auth');
		// $this->middleware('permission:view.pogeneralservices',   ['only' => ['create', 'index','show']]);
		// $this->middleware('permission:create.pogeneralservices', ['only' => ['store']]);
		// $this->middleware('permission:edit.pogeneralservices',   ['only' => ['update']]);
		// $this->middleware('permission:delete.pogeneralservices', ['only' => ['destroy']]);
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
        $rows=$this->pogeneralservice
        ->selectRaw('
          po_general_services.id,
          po_general_services.po_no,
          po_general_services.po_date,
          po_general_services.company_id,
          po_general_services.supplier_id,
          po_general_services.source_id,
          po_general_services.pay_mode,
          po_general_services.delv_start_date,
          po_general_services.delv_end_date,
          po_general_services.exch_rate,
          po_general_services.pi_no,
          po_general_services.remarks,
          po_general_services.approved_at,
          companies.code as company_code,
          suppliers.name as supplier_code,
          currencies.code as currency_code,
          sum(po_general_service_items.qty) as item_qty,
          po_general_services.amount
        ')
        ->leftJoin('po_general_service_items', function($join){
          $join->on('po_general_service_items.po_general_service_id', '=', 'po_general_services.id');
        })
        ->join('companies',function($join){
          $join->on('companies.id','=','po_general_services.company_id');
        })
        ->join('suppliers',function($join){
          $join->on('suppliers.id','=','po_general_services.supplier_id');
        })
        ->join('currencies',function($join){
          $join->on('currencies.id','=','po_general_services.currency_id');
        })
        ->where([['po_type_id', 1]])
	      ->orderBy('po_general_services.id','desc')
        ->groupBy([
          'po_general_services.id',
          'po_general_services.po_no',
          'po_general_services.po_date',
          'po_general_services.company_id',
          'po_general_services.supplier_id',
          'po_general_services.source_id',
          'po_general_services.pay_mode',
          'po_general_services.delv_start_date',
          'po_general_services.delv_end_date',
          'po_general_services.exch_rate',
          'po_general_services.pi_no',
          'po_general_services.remarks',
          'po_general_services.approved_at',
          'companies.code',
          'suppliers.name',
          'currencies.code',
          'po_general_services.amount'
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
      $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->GeneralItemSupplier(),'name','id'),'','');
      $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'name','id'),'-Select-','');
      $user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
      $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
      $indentor=array_prepend(array_pluck($this->supplier->indentor(),'name','id'),'','');


      return Template::loadView("Purchase.PoGeneralService", ['company'=>$company,'source'=>$source,'supplier'=>$supplier,'buyer'=>$buyer,'currency'=>$currency,'paymode'=>$paymode,'uom'=>$uom,'user'=>$user,'indentor'=>$indentor,'department'=>$department]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoGeneralServiceRequest $request)
    {
      $supplierId=$this->supplier->find($request->supplier_id);
      if($supplierId->status_id==0){
          return response()->json(array('success' => false,  'message' => 'Work Order not allowed to inactive Supplier'), 200);
      }
      elseif($supplierId->status_id==1 || $supplierId->status_id=='') {
        $max = $this->pogeneralservice->where([['company_id', $request->company_id]])->max('po_no');
        $po_no=$max+1;

        $pogeneralservice = $this->pogeneralservice->create(['po_no'=>$po_no,'po_type_id'=>1,'po_date'=>$request->po_date,'company_id'=>$request->company_id,'source_id'=>$request->source_id,'supplier_id'=>$request->supplier_id,'currency_id'=>$request->currency_id,'exch_rate'=>$request->exch_rate,'delv_start_date'=>$request->delv_start_date,'delv_end_date'=>$request->delv_end_date,'pay_mode'=>$request->pay_mode,'pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'remarks'=>$request->remarks,'indentor_id'=>$request->indentor_id,'price_verified_by_id'=>$request->price_verified_by_id]);

        $termscondition=$this->termscondition->where([['menu_id','=',11]])->orderBy('sort_id')->get(['terms_conditions.*']);
        foreach($termscondition as $row)
        {
        $purchasetermscondition = $this->purchasetermscondition->create(['purchase_order_id'=>$pogeneralservice->id,'term'=>$row->term,'sort_id'=>$row->sort_id,'menu_id'=>11]);
        }

        if ($pogeneralservice) {
        return response()->json(array('success' => true, 'id' => $pogeneralservice->id, 'message' => 'Save Successfully'), 200);
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
      $pogeneralservice = $this->pogeneralservice->find($id);
      $row ['fromData'] = $pogeneralservice;
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
    public function update(PoGeneralServiceRequest $request, $id)
    {
      $approved=$this->pogeneralservice->find($id);
      if($approved->approved_at){
        $this->pogeneralservice->update($id, ['pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'remarks'=>$request->remarks]);
        return response()->json(array('success' => false,  'message' => 'General Service Work Order is Approved, Update not Possible Except PI No , PI Date & Remarks'), 200);
      }
      
      $implcpo=$this->implcpo
      ->join('imp_lcs',function($join){
        $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
      })
      ->where([['imp_lcs.menu_id','=',11]])
      ->where([['imp_lc_pos.purchase_order_id','=',$id]])
      ->get(['imp_lc_pos.purchase_order_id'])
      ->first();

      $pogeneralservice = $this->pogeneralservice->update($id, $request->except(['id','company_id','supplier_id']));
      if ($pogeneralservice) {
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
      if($this->pogeneralservice->delete($id)){
  			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
  		}
    }

    public function getPdf()
    {

      $id=request('id',0);
      $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
      $rows=$this->pogeneralservice
      ->join('companies',function($join){
      $join->on('companies.id','=','po_general_services.company_id');
      })
      ->join('currencies',function($join){
      $join->on('currencies.id','=','po_general_services.currency_id');
      })
      ->join('suppliers',function($join){
      $join->on('suppliers.id','=','po_general_services.supplier_id');
      })
      ->leftJoin('users',function($join){
      $join->on('users.id','=','po_general_services.created_by');
      })
      ->leftJoin('users as price_verified',function($join){
      $join->on('price_verified.id','=','po_general_services.price_verified_by_id');
      })
      ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->leftJoin('users as approve_users',function($join){
      $join->on('approve_users.id','=','po_general_services.approved_by');
      })
      ->leftJoin('employee_h_rs as approve_employee',function($join){
      $join->on('approve_employee.user_id','=','approve_users.id');
      })
      ->leftJoin('designations',function($join){
      $join->on('approve_employee.designation_id','=','designations.id');
      })
      ->where([['po_general_services.id','=',$id]])
      ->get([
        'po_general_services.*',
        'po_general_services.id as po_general_service_id',
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
        'price_verified.name as price_verified_by',
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

      $data=$this->pogeneralservice
      ->selectRaw('
        po_general_service_items.id,
        po_general_service_items.service_description,
        po_general_service_items.remarks as item_remarks,
        asset_acquisitions.name as asset_name,
        asset_acquisitions.origin,
        asset_acquisitions.brand,
        asset_acquisitions.asset_group,
        departments.name as department_name,
        users.name as demand_by,
        uoms.code as uom_code,
        sum(po_general_service_items.qty) as qty,
        avg(po_general_service_items.rate) as rate,
        sum(po_general_service_items.amount) as amount
      '
      )
      ->join('po_general_service_items', function($join){
        $join->on('po_general_service_items.po_general_service_id', '=', 'po_general_services.id');
      })
      ->leftJoin('departments', function($join){
        $join->on('departments.id', '=', 'po_general_service_items.department_id');
      })
      ->leftJoin('users', function($join){
        $join->on('users.id', '=', 'po_general_service_items.demand_by_id');
      })
      ->leftJoin('asset_quantity_costs', function($join){
        $join->on('asset_quantity_costs.id', '=', 'po_general_service_items.asset_quantity_cost_id');
      })
      ->leftJoin('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
      ->leftJoin('uoms', function($join){
        $join->on('uoms.id', '=', 'po_general_service_items.uom_id');
      })
      ->where([['po_general_services.id','=',$id]])
      ->orderBy('po_general_service_items.id','asc')
      ->groupBy([
        'po_general_service_items.id',
        'po_general_service_items.service_description',
        'po_general_service_items.remarks',
        'asset_acquisitions.name',
			  'asset_acquisitions.origin',
			  'asset_acquisitions.brand',
			  'asset_acquisitions.asset_group',
			  'departments.name',
        'users.name',
        'uoms.code',
      ])
      ->get();
      $amount=$data->sum('amount');
      //$details=$data->groupBy('requisition_no');

      $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents');
      $rows->inword=$inword;
      $purOrder['master']=$rows;
      $purOrder['created_at']=date('d-M-Y',strtotime($rows->created_at)) ;
      //$purOrder['details']=$details;
      $purchasetermscondition=$this->purchasetermscondition->where([['purchase_order_id','=',$id]])->where([['menu_id','=',11]])->orderBy('sort_id')->get();
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
      $challan=str_pad($purOrder['master']->po_general_service_id,10,0,STR_PAD_LEFT ) ;
      $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
      $pdf->SetY(10);
      $image_file ='images/logo/'.$rows->logo;
      $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
      $pdf->SetY(12);
      $pdf->SetFont('helvetica', 'N', 8);
      $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
      //$pdf->Text(70, 12, $rows->company_address);
      $pdf->SetY(16);
      $pdf->SetFont('helvetica', 'N', 10);
      $pdf->Write(0, '', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      //$pdf->SetTitle('General Item Purchase Order');

      $view= \View::make('Defult.Purchase.PoGeneralServicePdf',['purOrder'=>$purOrder,'data'=>$data]);
      $html_content=$view->render();
      $pdf->SetY(36);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/PoGeneralServicePdf.pdf';
      $pdf->output($filename);
    }

  
}
