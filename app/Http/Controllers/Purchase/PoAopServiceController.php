<?php

namespace App\Http\Controllers\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoAopServiceRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\TermsConditionRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Library\Template;
use App\Library\Numbertowords;
use App\Http\Requests\Purchase\PoAopServiceRequest;

class PoAopServiceController extends Controller
{
    private $poaopservice;
    private $company;
    private $supplier;
    private $currency;
    private $termscondition;
    private $purchasetermscondition;
    

	public function __construct(
        PoAopServiceRepository $poaopservice,
        CompanyRepository $company,
        SupplierRepository $supplier,
        CurrencyRepository $currency,
        TermsConditionRepository $termscondition,
        PurchaseTermsConditionRepository $purchasetermscondition,
        EmbelishmentTypeRepository $embelishmenttype

        )
	{
        $this->poaopservice = $poaopservice;
        $this->company = $company;
        $this->supplier = $supplier;
        $this->currency = $currency;
        $this->termscondition = $termscondition;
        $this->purchasetermscondition = $purchasetermscondition;
        $this->embelishmenttype = $embelishmenttype;
        $this->middleware('auth');
        
      $this->middleware('permission:view.poaopservices',   ['only' => ['create', 'index','show']]);
      $this->middleware('permission:create.poaopservices', ['only' => ['store']]);
      $this->middleware('permission:edit.poaopservices',   ['only' => ['update']]);
      $this->middleware('permission:delete.poaopservices', ['only' => ['destroy']]);
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	/*$results = \DB::select("
		select budget_fabric_prod_cons.id,
		budget_fabric_prod_cons.sales_order_id,
		budget_fabric_prod_cons.fabric_color_id,
		min(budget_fabric_cons.dia) as dia,
		min(budget_fabric_cons.measurment) as measurment

		FROM budget_fabric_prod_cons 
		inner join budget_fabric_prods  on budget_fabric_prod_cons.budget_fabric_prod_id=budget_fabric_prods.id
		inner join budget_fabrics  on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
		inner join budget_fabric_cons  on budget_fabric_cons.budget_fabric_id=budget_fabrics.id
		and budget_fabric_cons.fabric_color=budget_fabric_prod_cons.fabric_color_id
		inner join sales_order_gmt_color_sizes  on sales_order_gmt_color_sizes.id=budget_fabric_cons.sales_order_gmt_color_size_id
		inner join sales_order_countries  on sales_order_countries.id=sales_order_gmt_color_sizes.sale_order_country_id
		inner join sales_orders  on sales_orders.id=sales_order_countries.sale_order_id
		and sales_orders.id=budget_fabric_prod_cons.sales_order_id

		group by 
		budget_fabric_prod_cons.id,
		budget_fabric_prod_cons.sales_order_id,
		budget_fabric_prod_cons.fabric_color_id
		order by budget_fabric_prod_cons.id");
		$data=collect($results);
		foreach ($data as $row){
			\DB::table('po_aop_service_item_qties')
                ->where('budget_fabric_prod_con_id', $row->id)
                ->update(['dia' => $row->dia,'measurment' => $row->measurment]);
		}*/


      $paymode=config('bprs.paymode');
      $rows=$this->poaopservice
      ->join('companies',function($join){
        $join->on('companies.id','=','po_aop_services.company_id');
      })
      ->join('suppliers',function($join){
        $join->on('suppliers.id','=','po_aop_services.supplier_id');
      })
      ->join('currencies',function($join){
        $join->on('currencies.id','=','po_aop_services.currency_id');
      })
      ->where([['po_type_id', 1]])
      ->orderBy('po_aop_services.id','desc')
      ->get([
          'po_aop_services.*',
          'companies.code as company_code',
          'suppliers.name as supplier_code',
          'currencies.code as currency_code'
        ])
      ->map(function ($rows) use($paymode)  {
          $rows->po_date = date("d-M-Y",strtotime($rows->po_date));
          $rows->paymode = $paymode[$rows->pay_mode];
          $rows->amount = number_format($rows->amount,2);
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
        $supplier=array_prepend(array_pluck($this->supplier->AopSubcontractor(),'name','id'),'','');
        $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
        $basis = array_only(config('bprs.pur_order_basis'), [1]);
        $paymode = array_prepend(array_only(config('bprs.paymode'), [3,4,6]),'-Select-','');
        $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');

		return Template::loadView("Purchase.PoAopService", ['company'=>$company,'basis'=>$basis,'supplier'=>$supplier,'currency'=>$currency,'paymode'=>$paymode,'aoptype'=>$aoptype]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoAopServiceRequest $request)
    {
      $supplierId=$this->supplier->find($request->supplier_id);
      if($supplierId->status_id==0){
          return response()->json(array('success' => false,  'message' => 'Purchase Order not allowed to inactive Supplier'), 200);
      }
      elseif($supplierId->status_id==1 || $supplierId->status_id=='') {
        $max = $this->poaopservice->where([['company_id', $request->company_id]])->max('po_no');
        $po_no=$max+1;

        $poaopservice = $this->poaopservice->create(['po_no'=>$po_no,'po_type_id'=>1,'company_id'=>$request->company_id,'po_date'=>$request->po_date,'basis_id'=>$request->basis_id,'supplier_id'=>$request->supplier_id,'pay_mode'=>$request->pay_mode,'currency_id'=>$request->currency_id,'exch_rate'=>$request->exch_rate,'delv_start_date'=>$request->delv_start_date,'delv_end_date'=>$request->delv_end_date,'pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'remarks'=>$request->remarks]);
        $termscondition=$this->termscondition->where([['menu_id','=',5]])->orderBy('sort_id')->get();
        foreach($termscondition as $row){
          $purchasetermscondition = $this->purchasetermscondition->create(['purchase_order_id'=>$poaopservice->id,'term'=>$row->term,'sort_id'=>$row->sort_id,'menu_id'=>5]);
        }
        if ($poaopservice) {
          return response()->json(array('success' => true, 'id' => $poaopservice->id, 'message' => 'Save Successfully'), 200);
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
		  $poaopservice = $this->poaopservice->find($id);
        $poaopservice->po_date=date('Y-m-d',strtotime($poaopservice->po_date));

        if($poaopservice->delv_start_date){
            $poaopservice->delv_start_date=date('Y-m-d',strtotime($poaopservice->delv_start_date));
        }
        
        if($poaopservice->delv_end_date){
           $poaopservice->delv_end_date=date('Y-m-d',strtotime($poaopservice->delv_end_date));
        }
    		$row ['fromData'] = $poaopservice;
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
    public function update(PoAopServiceRequest $request, $id)
    {
      $approved=$this->poaopservice->find($id);
    	if($approved->approved_at){
    	$this->poaopservice->update($id, ['pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'remarks'=>$request->remarks]);
    	  return response()->json(array('success' => false,  'message' => 'Approved, Update not Possible Except PI No , PI Date & Remarks,'), 200);
    	}
      $poaopservice = $this->poaopservice->update($id, $request->except(['id','company_id','po_no','supplier_id','basis_id']));
      if ($poaopservice) {
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
      if($this->poaopservice->delete($id)){
			 return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}else{
			 return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
		}
    }

    public function getPdf()
    {

      $id=request('id',0);
      $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
      $rows=$this->poaopservice
      ->join('companies',function($join){
      $join->on('companies.id','=','po_aop_services.company_id');
      })
      ->join('currencies',function($join){
      $join->on('currencies.id','=','po_aop_services.currency_id');
      })
      ->join('suppliers',function($join){
      $join->on('suppliers.id','=','po_aop_services.supplier_id');
      })
      ->join('users',function($join){
      $join->on('users.id','=','po_aop_services.created_by');
      })
      ->leftJoin('users as approve_users',function($join){
      $join->on('approve_users.id','=','po_aop_services.approved_by');
      })
      ->leftJoin('employee_h_rs as approve_employee',function($join){
      $join->on('approve_employee.user_id','=','approve_users.id');
      })
      ->leftJoin('designations',function($join){
      $join->on('approve_employee.designation_id','=','designations.id');
      })
      ->where([['po_aop_services.id','=',$id]])
      ->get([
        'po_aop_services.*',
        'po_aop_services.id as po_aop_service_id',
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

        $materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
        $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');
        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');


        $fabricDescription=$this->poaopservice
        ->join('po_aop_service_items',function($join){
          $join->on('po_aop_service_items.po_aop_service_id','=','po_aop_services.id');
        })
        ->join('budget_fabric_prods',function($join){
          $join->on('budget_fabric_prods.id','=','po_aop_service_items.budget_fabric_prod_id');
        })
        ->join('budget_fabrics',function($join){
          $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
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
        ->join('autoyarnratios',function($join){
          $join->on('autoyarns.id','=','autoyarnratios.autoyarn_id');
        })
        ->join('compositions',function($join){
            $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->join('constructions',function($join){
            $join->on('constructions.id','=','autoyarns.construction_id');
        })
        ->where([['po_aop_services.id','=',$id]])
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


      $data=$this->poaopservice
      ->selectRaw(
      '
        budget_fabrics.style_fabrication_id,
        budget_fabrics.gsm_weight,
        style_fabrications.fabric_look_id,
        style_fabrications.fabric_shape_id,
        style_fabrications.dyeing_type_id,
        
        gmtsparts.name as gmtspart_name,
        sales_orders.sale_order_no,
        colors.name as fabric_color,
        po_aop_service_item_qties.coverage,
        po_aop_service_item_qties.impression,
        po_aop_service_item_qties.embelishment_type_id,
        po_aop_service_item_qties.qty,
        po_aop_service_item_qties.rate,
        po_aop_service_item_qties.amount
      '
      )
      ->join('po_aop_service_items',function($join){
        $join->on('po_aop_service_items.po_aop_service_id','=','po_aop_services.id');
      })
      ->join('po_aop_service_item_qties',function($join){
        $join->on('po_aop_service_item_qties.po_aop_service_item_id','=','po_aop_service_items.id');
        $join->whereNull('po_aop_service_item_qties.deleted_at');
      })
      ->join('sales_orders',function($join){
        $join->on('sales_orders.id','=','po_aop_service_item_qties.sales_order_id');
      })
      ->join('budget_fabric_prods',function($join){
        $join->on('budget_fabric_prods.id','=','po_aop_service_items.budget_fabric_prod_id');
      })
      ->join('budget_fabrics',function($join){
        $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
      })
      ->join('colors',function($join){
        $join->on('colors.id','=','po_aop_service_item_qties.fabric_color_id');
      })
      ->join('style_fabrications',function($join){
        $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
      })
      ->join('gmtsparts',function($join){
        $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
      })
      ->join('autoyarns',function($join){
        $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
      })
      ->where([['po_aop_services.id','=',$id]])
      ->get()
      ->map(function($data) use($desDropdown,$fabriclooks,$fabricshape,$aoptype,$dyetype){
        $data->embelishment_type_id = isset($aoptype[$data->embelishment_type_id])?$aoptype[$data->embelishment_type_id]:'';
        $data->dyeing_type_id = $dyetype[$data->dyeing_type_id];
        $data->fabric_description=$desDropdown[$data->style_fabrication_id];
        $data->fabriclooks=$fabriclooks[$data->fabric_look_id];
        $data->fabricshape=$fabricshape[$data->fabric_shape_id];
        return $data;
      });
      $amount=$data->sum('amount');
      $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents');
      $rows->inword=$inword;
      $purOrder['master']=$rows;
      $purOrder['details']=$data;
      $purOrder['created_at']=date('d-M-Y',strtotime($rows->created_at)) ;
      $purchasetermscondition=$this->purchasetermscondition->where([['purchase_order_id','=',$id]])->where([['menu_id','=',5]])->orderBy('sort_id')->get();
      $purOrder['purchasetermscondition']=$purchasetermscondition;
     

      $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      
      $pdf->SetPrintHeader(false);
      $pdf->SetPrintFooter(true);
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
      $pdf->SetX(200);
      $challan=str_pad($purOrder['master']->po_aop_service_id,10,0,STR_PAD_LEFT ) ;
      $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.3, $barcodestyle, 'N');
      $pdf->SetY(10);
      
      //$txt = "Trim Purchase Order";
      //$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
      $image_file ='images/logo/'.$rows->logo;
      $pdf->Image($image_file, 90, 5, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
      $pdf->SetY(13);
      $pdf->SetFont('helvetica', 'N', 9);
      $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
      //$pdf->Text(111, 16, $rows->company_address);
      $pdf->SetY(35);
      $pdf->SetFont('helvetica', 'B', 16);
      $pdf->Write(0, 'AOP Service Work Order ', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      $pdf->SetTitle('AOP Service Work Order');
      $view= \View::make('Defult.Purchase.PoAopServicePdf',['purOrder'=>$purOrder]);
      $html_content=$view->render();
      $pdf->SetY(35);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/PoAopServicePdf.pdf';
      $pdf->output($filename);
    }
}
