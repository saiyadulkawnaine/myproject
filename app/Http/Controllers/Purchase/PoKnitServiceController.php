<?php

namespace App\Http\Controllers\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoKnitServiceRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\TermsConditionRepository;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Library\Numbertowords;
use App\Http\Requests\Purchase\PoKnitServiceRequest;

class PoKnitServiceController extends Controller
{
    private $poknitservice;
    private $company;
    private $supplier;
    private $currency;
    private $termscondition;
    private $purchasetermscondition;
    private $itemaccount;
    

	public function __construct(
        PoKnitServiceRepository $poknitservice,
        CompanyRepository $company,
        SupplierRepository $supplier,
        CurrencyRepository $currency,
        TermsConditionRepository $termscondition,
        PurchaseTermsConditionRepository $purchasetermscondition,
        ItemAccountRepository $itemaccount
        )
	{
        $this->poknitservice = $poknitservice;
		    $this->company = $company;
		    $this->supplier = $supplier;
		    $this->currency = $currency;
        $this->termscondition = $termscondition;
        $this->purchasetermscondition = $purchasetermscondition;
        $this->itemaccount = $itemaccount;
		$this->middleware('auth');
		$this->middleware('permission:view.poknitservices',   ['only' => ['create', 'index','show']]);
		$this->middleware('permission:create.poknitservices', ['only' => ['store']]);
		$this->middleware('permission:edit.poknitservices',   ['only' => ['update']]);
		$this->middleware('permission:delete.poknitservices', ['only' => ['destroy']]);
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $paymode=config('bprs.paymode');
      $rows=$this->poknitservice
      ->join('companies',function($join){
      $join->on('companies.id','=','po_knit_services.company_id');
      })
      ->join('suppliers',function($join){
      $join->on('suppliers.id','=','po_knit_services.supplier_id');
      })
      ->join('currencies',function($join){
      $join->on('currencies.id','=','po_knit_services.currency_id');
      })
      ->where([['po_type_id', 1]])
      ->orderBy('po_knit_services.id','desc')
      ->get([
      'po_knit_services.*',
      'companies.code as company_code',
      'suppliers.code as supplier_code',
      'currencies.code as currency_code',
      ])
      ->map(function ($rows) use($paymode)  {
          $rows->paymode = $rows->pay_mode?$paymode[$rows->pay_mode]:'';
          $rows->amount = number_format($rows->amount,2);
          $rows->delv_start_date=date('d-M-Y',strtotime($rows->delv_start_date));
          $rows->po_date=date('d-M-Y',strtotime($rows->po_date));
          $rows->delv_end_date=date('d-M-Y',strtotime($rows->delv_end_date));
          $rows->approve_status=$rows->approved_at?"Approved":"--";
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
      $supplier=array_prepend(array_pluck($this->supplier->knitSubcontractor(),'name','id'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
      $paymode=array_prepend(config('bprs.paymode'),'-Select-','');

      return Template::loadView("Purchase.PoKnitService", ['company'=>$company,'basis'=>$basis,'supplier'=>$supplier,'currency'=>$currency,'paymode'=>$paymode]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoKnitServiceRequest $request)
    {
        $max = $this->poknitservice->where([['company_id', $request->company_id]])->max('po_no');
        $po_no=$max+1;

        $poknitservice = $this->poknitservice->create(['po_no'=>$po_no,'po_type_id'=>1,'company_id'=>$request->company_id,'po_date'=>$request->po_date,'basis_id'=>$request->basis_id,'supplier_id'=>$request->supplier_id,'pay_mode'=>$request->pay_mode,'currency_id'=>$request->currency_id,'exch_rate'=>$request->exch_rate,'delv_start_date'=>$request->delv_start_date,'delv_end_date'=>$request->delv_end_date,'pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'remarks'=>$request->remarks]);
        $termscondition=$this->termscondition->where([['menu_id','=',4]])->orderBy('sort_id')->get();
        foreach($termscondition as $row)
        {
        $purchasetermscondition = $this->purchasetermscondition->create(['purchase_order_id'=>$poknitservice->id,'term'=>$row->term,'sort_id'=>$row->sort_id,'menu_id'=>4]);
        }
        if ($poknitservice) {
        return response()->json(array('success' => true, 'id' => $poknitservice->id, 'message' => 'Save Successfully'), 200);
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
      $poknitservice = $this->poknitservice->find($id);
          $poknitservice->po_date=date('Y-m-d',strtotime($poknitservice->po_date));

          if($poknitservice->delv_start_date){
              $poknitservice->delv_start_date=date('Y-m-d',strtotime($poknitservice->delv_start_date));
          }
          
          if($poknitservice->delv_end_date){
            $poknitservice->delv_end_date=date('Y-m-d',strtotime($poknitservice->delv_end_date));
          }
      $row ['fromData'] = $poknitservice;
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
    public function update(PoKnitServiceRequest $request, $id)
    {
      $approved=$this->poknitservice->find($id);
    	if($approved->approved_at){
    	$this->poknitservice->update($id, ['pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'remarks'=>$request->remarks]);
    	  return response()->json(array('success' => false,  'message' => 'Approved, Update not Possible Except PI No , PI Date & Remarks,'), 200);
    	}
      $poknitservice = $this->poknitservice->update($id, $request->except(['id','company_id','po_no','supplier_id','basis_id']));
      if ($poknitservice) {
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
      if($this->poknitservice->delete($id)){
  			 return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
  		}else{
  			 return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
  		}
    }

    public function getPdf()
    {

      $id=request('id',0);
      $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
      $rows=$this->poknitservice
      ->join('companies',function($join){
        $join->on('companies.id','=','po_knit_services.company_id');
      })
      ->join('currencies',function($join){
        $join->on('currencies.id','=','po_knit_services.currency_id');
      })
      ->join('suppliers',function($join){
        $join->on('suppliers.id','=','po_knit_services.supplier_id');
      })
      ->join('users',function($join){
        $join->on('users.id','=','po_knit_services.created_by');
      })
      ->leftJoin('users as approve_users',function($join){
        $join->on('approve_users.id','=','po_knit_services.approved_by');
      })
      ->leftJoin('employee_h_rs as approve_employee',function($join){
        $join->on('approve_employee.user_id','=','approve_users.id');
      })
      ->leftJoin('designations',function($join){
        $join->on('approve_employee.designation_id','=','designations.id');
      })
      ->where([['po_knit_services.id','=',$id]])
      ->get([
        'po_knit_services.*',
        'po_knit_services.id as po_knit_service_id',
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


        $materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
        $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

        $fabricDescription=$this->poknitservice
        ->leftJoin('po_knit_service_items',function($join){
        $join->on('po_knit_service_items.po_knit_service_id','=','po_knit_services.id');
        })
        ->leftJoin('budget_fabric_prods',function($join){
        $join->on('budget_fabric_prods.id','=','po_knit_service_items.budget_fabric_prod_id');
        })
        ->leftJoin('budget_fabrics',function($join){
        $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->leftJoin('style_fabrications',function($join){
        $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->leftJoin('style_gmts',function($join){
        $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
        })
        ->leftJoin('item_accounts', function($join) {
        $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->leftJoin('budgets',function($join){
        $join->on('budgets.id','=','budget_fabrics.budget_id');
        })
        ->leftJoin('jobs',function($join){
        $join->on('jobs.id','=','budgets.job_id');
        })
        ->leftJoin('styles', function($join) {
        $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->leftJoin('gmtsparts',function($join){
        $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
        })
        ->leftJoin('autoyarns',function($join){
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
        ->where([['po_knit_services.id','=',$id]])
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
            $desDropdown[$key]=$val.", ".implode(",",$fabricCompositionArr[$key]);
        }

      $yarnDescription=$this->itemaccount
      ->join('item_account_ratios',function($join){
        $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
      })
      ->join('compositions',function($join){
        $join->on('compositions.id','=','item_account_ratios.composition_id');
      })
      ->join('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
      })
      ->where([['itemcategories.identity','=',1]])
      ->get([
        'item_accounts.id',
        'compositions.name as composition_name',
        'item_account_ratios.ratio'
      ]);
      $yarnCompositionArr=array();
      foreach($yarnDescription as $row){
      $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
      }

      
      $yarn=$this->poknitservice
      ->selectRaw(
      'po_knit_service_item_qties.id,
      item_accounts.id as item_account_id,
      yarncounts.count,
      yarncounts.symbol,
      yarntypes.name as type_name
      '
      )
      ->leftJoin('po_knit_service_items',function($join){
      $join->on('po_knit_service_items.po_knit_service_id','=','po_knit_services.id');
      })
      ->leftJoin('po_knit_service_item_qties',function($join){
      $join->on('po_knit_service_item_qties.po_knit_service_item_id','=','po_knit_service_items.id');
      })
      ->leftJoin('budget_fabric_prods',function($join){
        $join->on('budget_fabric_prods.id','=','po_knit_service_items.budget_fabric_prod_id');
      })
      ->leftJoin('budget_fabrics',function($join){
        $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
      })
      ->leftJoin('budget_yarns',function($join){
        $join->on('budget_fabrics.id','=','budget_yarns.budget_fabric_id');
      })
      ->leftJoin('item_accounts',function($join){
        $join->on('item_accounts.id','=','budget_yarns.item_account_id');
      })
      ->leftJoin('yarncounts',function($join){
        $join->on('yarncounts.id','=','item_accounts.yarncount_id');
      })
      ->leftJoin('yarntypes',function($join){
        $join->on('yarntypes.id','=','item_accounts.yarntype_id');
      })
      ->where([['po_knit_services.id','=',$id]])
      ->get();
      $yarn_array=array();
      foreach( $yarn as  $row)
      {
        //$yarn_array[$row->id]['count'][]=$row->count."/".$row->symbol;
        //$yarn_array[$row->id]['type'][]=$row->type_name;
        //$yarn_array[$row->id]['composition'][]=implode(",",$yarnCompositionArr[$row->item_account_id]);
        $yarn_array[$row->id]['yarn'][]=$row->count."/".$row->symbol .", ". implode(",",$yarnCompositionArr[$row->item_account_id]).", ".$row->type_name;
      }
      


      $data=$this->poknitservice
      ->selectRaw(
      '
      budget_fabrics.style_fabrication_id,
      budget_fabrics.gsm_weight,
      style_fabrications.fabric_look_id,
      style_fabrications.fabric_shape_id,
      gmtsparts.name as gmtspart_name,
      sales_orders.sale_order_no,
      po_knit_service_item_qties.id,
      po_knit_service_item_qties.dia,
      po_knit_service_item_qties.measurment,
      po_knit_service_item_qties.qty,
      po_knit_service_item_qties.pcs_qty,
      po_knit_service_item_qties.rate,
      po_knit_service_item_qties.amount,
      po_knit_service_item_qties.colorrange_id,
      po_knit_service_item_qties.pl_dia,
      po_knit_service_item_qties.pl_gsm_weight,
      po_knit_service_item_qties.pl_stitch_length,
      po_knit_service_item_qties.pl_spandex_stitch_length,
      po_knit_service_item_qties.pl_draft_ratio,
      po_knit_service_item_qties.pl_machine_gg,
      colorranges.name as colorrange_name,
      buyers.name as buyer_name,
      colors.name as color_name
      '
      )
      ->leftJoin('po_knit_service_items',function($join){
      $join->on('po_knit_service_items.po_knit_service_id','=','po_knit_services.id');
      })
      ->leftJoin('po_knit_service_item_qties',function($join){
      $join->on('po_knit_service_item_qties.po_knit_service_item_id','=','po_knit_service_items.id');
      })
      
      ->leftJoin('sales_orders',function($join){
      $join->on('sales_orders.id','=','po_knit_service_item_qties.sales_order_id');
      })
      ->leftJoin('budget_fabric_prods',function($join){
      $join->on('budget_fabric_prods.id','=','po_knit_service_items.budget_fabric_prod_id');
      })
      ->leftJoin('budget_fabrics',function($join){
      $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
      })
      ->leftJoin('style_fabrications',function($join){
        $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
      })
      ->leftJoin('gmtsparts',function($join){
        $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
      })
      ->leftJoin('autoyarns',function($join){
        $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
      })
      ->leftJoin('colorranges',function($join){
        $join->on('colorranges.id','=','po_knit_service_item_qties.colorrange_id');
      })
      ->leftJoin('jobs',function($join){
        $join->on('jobs.id','=','sales_orders.job_id');
      })
      ->leftJoin('styles',function($join){
        $join->on('styles.id','=','jobs.style_id');
      })
      ->leftJoin('buyers',function($join){
        $join->on('buyers.id','=','styles.buyer_id');
      })
      ->leftJoin('colors',function($join){
        $join->on('colors.id','=','po_knit_service_item_qties.fabric_color_id');
      })
      ->where([['po_knit_services.id','=',$id]])
      ->get()
      ->map(function($data) use($desDropdown,$fabriclooks,$fabricshape){
        $data->fabric_description=$desDropdown[$data->style_fabrication_id];
        $data->fabriclooks=$fabriclooks[$data->fabric_look_id];
        $data->fabricshape=$fabricshape[$data->fabric_shape_id];
        return $data;
      });
      $amount=$data->sum('amount');
      $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents');
      $rows->inword          =$inword;
      $purOrder['master']    =$rows;
      $purOrder['details']   =$data;
      $purOrder['yarn_array']=$yarn_array;

      $purchasetermscondition=$this->purchasetermscondition->where([['purchase_order_id','=',$id]])->where([['menu_id','=',4]])->orderBy('sort_id')->get();
      $purOrder['purchasetermscondition']=$purchasetermscondition;
     

      $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetPrintHeader(false);
      $pdf->SetPrintFooter(true);
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->SetMargins(10, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(5);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->AddPage();
      $pdf->SetY(10);
      $image_file ='images/logo/'.$rows->logo;
      $pdf->Image($image_file, 90, 5, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
      $pdf->SetY(13);
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
      $challan=str_pad($purOrder['master']->po_knit_service_id,10,0,STR_PAD_LEFT ) ;
      $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.3, $barcodestyle, 'N');
      // $pdf->SetFont('helvetica', 'N', 10);
      // $pdf->Write(0, 'Knit Service Order ', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      //$pdf->SetTitle('Knit Service Order');
      $view= \View::make('Defult.Purchase.PoKnitServicePdf',['purOrder'=>$purOrder]);
      $html_content=$view->render();
      $pdf->SetY(36);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/PoKnitServicePdf.pdf';
      $pdf->output($filename);
    }

  public function getSearchPoKint()
  {
    $paymode = config('bprs.paymode');
    $rows = $this->poknitservice
      ->join('companies', function ($join) {
        $join->on('companies.id', '=', 'po_knit_services.company_id');
      })
      ->join('suppliers', function ($join) {
        $join->on('suppliers.id', '=', 'po_knit_services.supplier_id');
      })
      ->join('currencies', function ($join) {
        $join->on('currencies.id', '=', 'po_knit_services.currency_id');
      })
      ->when(request('po_no'), function ($q) {
        return $q->where('po_knit_services.po_no', '=', request('po_no',0));
      })
      ->when(request('supplier_search_id'), function ($q) {
        return $q->where('suppliers.id', '=', request('supplier_search_id',0));
      })
      ->when(request('company_search_id'), function ($q) {
        return $q->where('companies.id', '=', request('company_search_id',0));
      })
      ->when(request('from_date'), function ($q) {
        return $q->where('po_knit_services.po_date', '>=', request('from_date',0));
      })
      ->when(request('to_date'), function ($q) {
        return $q->where('po_knit_services.po_date', '<=', request('to_date',0));
      })
      ->where([['po_type_id', 1]])
      ->orderBy('po_knit_services.id', 'desc')
      ->get([
        'po_knit_services.*',
        'companies.code as company_code',
        'suppliers.code as supplier_code',
        'currencies.code as currency_code',
      ])
      ->map(function ($rows) use ($paymode) {
        $rows->paymode = $rows->pay_mode ? $paymode[$rows->pay_mode] : '';
        $rows->amount = number_format($rows->amount, 2);
        $rows->delv_start_date = date('d-M-Y', strtotime($rows->delv_start_date));
        $rows->po_date = date('d-M-Y', strtotime($rows->po_date));
        $rows->delv_end_date = date('d-M-Y', strtotime($rows->delv_end_date));
        $rows->approve_status = $rows->approved_at ? "Approved" : "--";
        return $rows;
      });
    echo json_encode($rows);
  }
}
