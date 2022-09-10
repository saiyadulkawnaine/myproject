<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;


use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoDyeChemRepository;
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
use App\Http\Requests\Purchase\PoDyeChemRequest;


class PoDyeChemController extends Controller
{
   private $podyechem;
   private $company;
   private $supplier;
   private $currency;
   private $itemcategory;
   private $termscondition;
   private $purchasetermscondition;
   private $itemclass;
   private $implcpo;

	public function __construct(
    PoDyeChemRepository $podyechem,
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
    $this->podyechem = $podyechem;
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
		$this->middleware('permission:view.podyechems',   ['only' => ['create', 'index','show']]);
		$this->middleware('permission:create.podyechems', ['only' => ['store']]);
		$this->middleware('permission:edit.podyechems',   ['only' => ['update']]);
		$this->middleware('permission:delete.podyechems', ['only' => ['destroy']]);
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
      $rows=$this->podyechem
      ->join('companies',function($join){
        $join->on('companies.id','=','po_dye_chems.company_id');
      })
      ->join('suppliers',function($join){
        $join->on('suppliers.id','=','po_dye_chems.supplier_id');
      })
      ->join('currencies',function($join){
        $join->on('currencies.id','=','po_dye_chems.currency_id');
      })
      ->leftJoin('po_dye_chem_items', function($join){
        $join->on('po_dye_chem_items.po_dye_chem_id', '=', 'po_dye_chems.id');
      }) 
      ->where([['po_type_id', 1]])
      ->orderBy('po_dye_chems.id','desc')
      ->selectRaw('
        po_dye_chems.id,
        po_dye_chems.po_no,
        po_dye_chems.po_date,
        po_dye_chems.pi_no,
        po_dye_chems.company_id,
        po_dye_chems.supplier_id,
        po_dye_chems.source_id,
        po_dye_chems.pay_mode,
        po_dye_chems.delv_start_date,
        po_dye_chems.delv_end_date,
        po_dye_chems.exch_rate,
        po_dye_chems.approved_at,
        companies.code as company_code,
        suppliers.name as supplier_code,
        currencies.code as currency_code,
        sum(po_dye_chem_items.qty) as item_qty,
        po_dye_chems.amount
      ')
      ->groupBy([
        'po_dye_chems.id',
        'po_dye_chems.po_no',
        'po_dye_chems.po_date',
        'po_dye_chems.pi_no',
        'po_dye_chems.company_id',
        'po_dye_chems.supplier_id',
        'po_dye_chems.source_id',
        'po_dye_chems.pay_mode',
        'po_dye_chems.delv_start_date',
        'po_dye_chems.delv_end_date',
        'po_dye_chems.exch_rate',
        'po_dye_chems.approved_at',
        'companies.code',
        'suppliers.name',
        'currencies.code',
        'po_dye_chems.amount'
      ])
      ->get()
      ->map(function($rows) use($source,$paymode){
        $rows->source=isset($source[$rows->source_id])?$source[$rows->source_id]:'';
        $rows->paymode=isset($paymode[$rows->pay_mode])?$paymode[$rows->pay_mode]:'';
        $rows->item_qty = number_format($rows->item_qty,2);
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
      $source = array_prepend(config('bprs.purchasesource'),'-Select-','');
      $basis = array_only(config('bprs.pur_order_basis'), [2]);
      $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->DyesAndChemSupplier(),'name','id'),'-Select-','');
      $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');

      $itemcategory=array_prepend(array_pluck($this->itemcategory->get(),'name','id'),'-Select-','');
      $itemclass=array_prepend(array_pluck($this->itemclass->get(),'name','id'),'-Select-','');
      $indentor=array_prepend(array_pluck($this->supplier->indentor(),'name','id'),'','');
      
      return Template::loadView("Purchase.PoDyeChem", ['company'=>$company,'source'=>$source,'supplier'=>$supplier,'buyer'=>$buyer,'currency'=>$currency,'paymode'=>$paymode,'order_type_id'=>1,'basis'=>$basis,'itemcategory'=>$itemcategory,'itemclass'=>$itemclass,'indentor'=>$indentor]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoDyeChemRequest $request)
    {
      $supplierId=$this->supplier->find($request->supplier_id);
      if($supplierId->status_id==0){
          return response()->json(array('success' => false,  'message' => 'Purchase Order not allowed to inactive Supplier'), 200);
      }
      elseif($supplierId->status_id==1 || $supplierId->status_id=='') {
        $max = $this->podyechem->where([['company_id', $request->company_id]])->max('po_no');
        $po_no=$max+1;

        $podyechem = $this->podyechem->create(['po_no'=>$po_no,'po_type_id'=>1,'po_date'=>$request->po_date,'company_id'=>$request->company_id,'source_id'=>$request->source_id,'basis_id'=>$request->basis_id,'supplier_id'=>$request->supplier_id,'currency_id'=>$request->currency_id,'exch_rate'=>$request->exch_rate,'delv_start_date'=>$request->delv_start_date,'delv_end_date'=>$request->delv_end_date,'pay_mode'=>$request->pay_mode,'pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'remarks'=>$request->remarks,'indentor_id'=>$request->indentor_id]);

        $termscondition=$this->termscondition->where([['menu_id','=',7]])->orderBy('sort_id')->get();
        foreach($termscondition as $row)
        {
        $purchasetermscondition = $this->purchasetermscondition->create(['purchase_order_id'=>$podyechem->id,'term'=>$row->term,'sort_id'=>$row->sort_id,'menu_id'=>7]);
        }

        if ($podyechem) {
        return response()->json(array('success' => true, 'id' => $podyechem->id, 'message' => 'Save Successfully'), 200);
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
      $podyechem = $this->podyechem->find($id);
      $row ['fromData'] = $podyechem;
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
    public function update(PoDyeChemRequest $request, $id)
    {
      $podyechemapproved=$this->podyechem->find($id);
      if($podyechemapproved->approved_at){
        $this->podyechem->update($id, ['pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'remarks'=>$request->remarks]);
        return response()->json(array('success' => false,  'message' => 'Dyes & Chemical Purchase Order is Approved, Update not Possible Except PI No , PI Date & Remarks'), 200);
      }
      $implcpo=$this->implcpo
      ->join('imp_lcs',function($join){
        $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
      })
      ->where([['imp_lcs.menu_id','=',7]])
      ->where([['imp_lc_pos.purchase_order_id','=',$id]])
      ->get(['imp_lc_pos.purchase_order_id'])
      ->first();
      
      if ($implcpo) {
        $podyechem = $this->podyechem->update($id, $request->except(['id','company_id','supplier_id']));
      }
      else{
        $podyechem = $this->podyechem->update($id, $request->except(['id','company_id']));
      }
      

      /*$termscondition=$this->termscondition->where([['menu_id','=',2]])->orderBy('sort_id')->get();
      foreach($termscondition as $row)
      {
      $purchasetermscondition = $this->purchasetermscondition->create(['purchase_order_id'=>$id,'term'=>$row->term,'sort_id'=>$row->sort_id]);
      }*/
      if ($podyechem) {
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
      if($this->podyechem->delete($id)){
  			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
  		}
    }

    public function getPdf()
    {

      $id=request('id',0);
      $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
      $rows=$this->podyechem
      ->join('companies',function($join){
        $join->on('companies.id','=','po_dye_chems.company_id');
      })
      ->join('currencies',function($join){
        $join->on('currencies.id','=','po_dye_chems.currency_id');
      })
      ->join('suppliers',function($join){
        $join->on('suppliers.id','=','po_dye_chems.supplier_id');
      })
      ->join('users',function($join){
        $join->on('users.id','=','po_dye_chems.created_by');
      })
      ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->leftjoin('users as approve_users',function($join){
        $join->on('approve_users.id','=','po_dye_chems.approved_by');
      })
      ->leftjoin('employee_h_rs as approve_employee',function($join){
        $join->on('approve_employee.user_id', '=','approve_users.id');
      })
      ->leftjoin('designations',function($join){
        $join->on('approve_employee.designation_id','=','designations.id');
      })
      ->where([['po_dye_chems.id','=',$id]])
      ->get([
        'po_dye_chems.*',
        'po_dye_chems.id as po_dye_chems_id',
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
      
      $data=$this->podyechem
        ->selectRaw(
        '
          inv_pur_reqs.requisition_no,
          itemcategories.name as category_name,
          itemclasses.name as class_name,
          item_accounts.sub_class_name,
          item_accounts.item_description,
          item_accounts.specification,
          uoms.code as uom_code,
          po_dye_chem_items.id,
          po_dye_chem_items.remarks as item_remarks,
          sum(po_dye_chem_items.qty) as qty,
          avg(po_dye_chem_items.rate) as rate,
          sum(po_dye_chem_items.amount) as amount
        '
        )
        ->join('po_dye_chem_items', function($join){
          $join->on('po_dye_chem_items.po_dye_chem_id', '=', 'po_dye_chems.id');
        })
        ->join('inv_pur_req_items', function($join){
          $join->on('inv_pur_req_items.id', '=', 'po_dye_chem_items.inv_pur_req_item_id');
        })
        ->join('item_accounts', function($join){
          $join->on('item_accounts.id', '=', 'inv_pur_req_items.item_account_id');
        })
        /*->join('item_account_suppliers', function($join){
          $join->on('item_account_suppliers.item_account_id', '=', 'item_accounts.id');
          $join->on('item_account_suppliers.supplier_id', '=', 'po_dye_chems.supplier_id');
        })*/
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
        ->where([['po_dye_chems.id','=',$id]])
        ->groupBy([
          'inv_pur_reqs.requisition_no',
          'itemcategories.name',
          'itemclasses.name',
          'item_accounts.sub_class_name',
          'item_accounts.item_description',
          'item_accounts.specification',
          'uoms.code',
          //'item_account_suppliers.custom_name',
          'po_dye_chem_items.id',
          'po_dye_chem_items.remarks'
        ])
        ->orderBy('po_dye_chem_items.id','asc')
        ->get();
      $amount=$data->sum('amount');
      //$details=$data->groupBy('requisition_no');

      $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents');
      $rows->inword=$inword;
      $purOrder['master']=$rows;
      $purOrder['created_at']=date('d-M-Y',strtotime($rows->created_at)) ;
      //$purOrder['details']=$details;
      $purchasetermscondition=$this->purchasetermscondition->where([['purchase_order_id','=',$id]])->where([['menu_id','=',7]])->orderBy('sort_id')->get();
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
        $challan=str_pad($purOrder['master']->po_dye_chems_id,10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
      $pdf->SetFont('helvetica', 'N', 10);
      //$pdf->Write(0, 'Dyes/Chemical Purchase Order', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      //$pdf->SetTitle('General Item Purchase Order');
      $view= \View::make('Defult.Purchase.PoDyeChemPdf',['purOrder'=>$purOrder,'data'=>$data]);
      $html_content=$view->render();
      $pdf->SetY(35);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/PoDyeChemPdf.pdf';
      $pdf->output($filename);
    }

    public function getPdfTopSheet()
    {

      $id=request('id',0);

      $rqrows=$this->podyechem
      ->join('po_dye_chem_items', function($join){
          $join->on('po_dye_chem_items.po_dye_chem_id', '=', 'po_dye_chems.id');
      })
      ->join('inv_pur_req_items', function($join){
          $join->on('inv_pur_req_items.id', '=', 'po_dye_chem_items.inv_pur_req_item_id');
      })
      ->join('inv_pur_reqs', function($join){
          $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
      })
      
      
      ->whereIn('po_dye_chems.id',explode(',',$id))
      ->get([
        'po_dye_chems.*',
        'inv_pur_reqs.id as rq_id',
        'inv_pur_reqs.requisition_no as rq_no',
      ]);
      $rqnos=[];
      foreach($rqrows as $row)
      {
        $rqnos[$row->id]['rq_no'][$row->rq_id]=$row->rq_no;

      }

      $rows=$this->podyechem
      
      ->join('companies',function($join){
        $join->on('companies.id','=','po_dye_chems.company_id');
      })
      ->join('currencies',function($join){
        $join->on('currencies.id','=','po_dye_chems.currency_id');
      })
      ->join('suppliers',function($join){
        $join->on('suppliers.id','=','po_dye_chems.supplier_id');
      })
      ->join('users',function($join){
        $join->on('users.id','=','po_dye_chems.created_by');
      })
      
      ->whereIn('po_dye_chems.id',explode(',',$id))
      ->orderBy('po_dye_chems.id')
      ->get([
        'po_dye_chems.*',
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
      $view= \View::make('Defult.Purchase.PoDyeChemTopSheetPdf',['purOrder'=>$rows]);
      $html_content=$view->render();
      $pdf->SetY(5);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/PoDyeChemTopSheetPdf.pdf';
      $pdf->output($filename);

    }

    public function getSearchPoDyeChem()
    {
     $source = array_prepend(config('bprs.purchasesource'), '-Select-', '');
     $paymode = array_prepend(config('bprs.paymode'), '-Select-', '');
     $rows = $this->podyechem
      ->join('companies', function ($join) {
       $join->on('companies.id', '=', 'po_dye_chems.company_id');
      })
      ->join('suppliers', function ($join) {
       $join->on('suppliers.id', '=', 'po_dye_chems.supplier_id');
      })
      ->join('currencies', function ($join) {
       $join->on('currencies.id', '=', 'po_dye_chems.currency_id');
      })
      ->leftJoin('po_dye_chem_items', function ($join) {
       $join->on('po_dye_chem_items.po_dye_chem_id', '=', 'po_dye_chems.id');
      })
      ->when(request('po_no'), function ($q) {
       return $q->where('po_dye_chems.po_no', 'like', '%' . request('po_no') . '%');
      })
      ->when(request('supplier_search_id'), function ($q) {
       return $q->where('suppliers.id', '=', request('supplier_search_id'));
      })
      ->when(request('company_search_id'), function ($q) {
       return $q->where('companies.id', '=', request('company_search_id'));
      })
      ->when(request('from_date'), function ($q) {
       return $q->where('po_dye_chems.po_date', '>=', request('from_date'));
      })
      ->when(request('to_date'), function ($q) {
       return $q->where('po_dye_chems.po_date', '<=', request('to_date'));
      })
      ->where([['po_type_id', 1]])
      ->orderBy('po_dye_chems.id', 'desc')
      ->selectRaw('
           po_dye_chems.id,
           po_dye_chems.po_no,
           po_dye_chems.po_date,
           po_dye_chems.pi_no,
           po_dye_chems.company_id,
           po_dye_chems.supplier_id,
           po_dye_chems.source_id,
           po_dye_chems.pay_mode,
           po_dye_chems.delv_start_date,
           po_dye_chems.delv_end_date,
           po_dye_chems.exch_rate,
           po_dye_chems.approved_at,
           companies.code as company_code,
           suppliers.name as supplier_code,
           currencies.code as currency_code,
           sum(po_dye_chem_items.qty) as item_qty,
           po_dye_chems.amount
         ')
      ->groupBy([
       'po_dye_chems.id',
       'po_dye_chems.po_no',
       'po_dye_chems.po_date',
       'po_dye_chems.pi_no',
       'po_dye_chems.company_id',
       'po_dye_chems.supplier_id',
       'po_dye_chems.source_id',
       'po_dye_chems.pay_mode',
       'po_dye_chems.delv_start_date',
       'po_dye_chems.delv_end_date',
       'po_dye_chems.exch_rate',
       'po_dye_chems.approved_at',
       'companies.code',
       'suppliers.name',
       'currencies.code',
       'po_dye_chems.amount'
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
       $rows->approve_status = ($rows->approved_at) ? 'Approved' : '--';
       return $rows;
      });
     echo json_encode($rows);
    }
   
   /* public function getPdfShort()
    {

      $id=request('id',0);
      $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
      $rows=$this->podyechem
      ->join('companies',function($join){
      $join->on('companies.id','=','po_dye_chems.company_id');
      })
      ->join('currencies',function($join){
      $join->on('currencies.id','=','po_dye_chems.currency_id');
      })
      ->join('suppliers',function($join){
      $join->on('suppliers.id','=','po_dye_chems.supplier_id');
      })
      ->join('users',function($join){
      $join->on('users.id','=','po_dye_chems.created_by');
      })
      ->where([['po_dye_chems.id','=',$id]])
      ->get([
      'po_dye_chems.*',
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

      $data=$this->podyechem
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
        po_trim_item_reports.rate,
        sum(po_trim_item_reports.qty) as qty,
        sum(po_trim_item_reports.amount) as amount
        '
      )
      ->join('po_trim_items',function($join){
        $join->on('po_trim_items.po_trim_id','=','po_dye_chems.id');
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
      ->where([['po_dye_chems.id','=',$id]])
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
      

      $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents');
      $rows->inword=$inword;
      $purOrder['master']=$rows;
      $purOrder['sizesensivitarray']=$sizesensivitarray;
      $purOrder['colorsensivitarray']=$colorsensivitarray;
      $purOrder['colorsizesensivitarray']=$colorsizesensivitarray;
      $purOrder['nosensivits']=$nosensivits;
      $purchasetermscondition=$this->purchasetermscondition->where([['purchase_order_id','=',$id]])->where([['menu_id','=',2]])->orderBy('sort_id')->get();
      $purOrder['purchasetermscondition']=$purchasetermscondition;
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
      $image_file ='images/logo/'.$rows->logo;
      $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
      $pdf->SetY(12);
      $pdf->SetFont('helvetica', 'N', 8);
      $pdf->Text(115, 12, $rows->company_address);
      $pdf->SetY(16);
      $pdf->SetFont('helvetica', 'N', 10);
      $pdf->Write(0, 'Trim Purchase Order', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      $pdf->SetTitle('Trim Purchase Order');
      $view= \View::make('Defult.Purchase.PoTrimPdfShort',['purOrder'=>$purOrder,'sizearray'=>$sizearray,'sizetotalarray'=>$sizetotalarray,'colorarray'=>$colorarray,'colortotalarray'=>$colortotalarray,'colorsizearray'=>$colorsizearray,'colorsizetotalarray'=>$colorsizetotalarray]);
      $html_content=$view->render();
      $pdf->SetY(20);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/PurOrderTrimPdf.pdf';
      $pdf->output($filename);
    }*/
}
