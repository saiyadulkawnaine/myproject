<?php

namespace App\Http\Controllers\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoDyeingServiceRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\TermsConditionRepository;
use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Library\Template;
use App\Library\Numbertowords;
use App\Http\Requests\Purchase\PoDyeingServiceRequest;

class PoDyeingServiceController extends Controller
{
    private $podyeingservice;
    private $company;
    private $supplier;
    private $currency;
    private $termscondition;
    private $purchasetermscondition;
    private $colorrange;
    

	public function __construct(
        PoDyeingServiceRepository $podyeingservice,
        CompanyRepository $company,
        SupplierRepository $supplier,
        CurrencyRepository $currency,
        TermsConditionRepository $termscondition,
        PurchaseTermsConditionRepository $purchasetermscondition,
        ColorrangeRepository $colorrange
        )
	{
        $this->podyeingservice = $podyeingservice;
		    $this->company = $company;
		    $this->supplier = $supplier;
		    $this->currency = $currency;
        $this->termscondition = $termscondition;
        $this->purchasetermscondition = $purchasetermscondition;
        $this->colorrange = $colorrange;
        $this->middleware('auth');
        
		$this->middleware('permission:view.podyeingservices',   ['only' => ['create', 'index','show']]);
		$this->middleware('permission:create.podyeingservices', ['only' => ['store']]);
		$this->middleware('permission:edit.podyeingservices',   ['only' => ['update']]);
		$this->middleware('permission:delete.podyeingservices', ['only' => ['destroy']]);
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $paymode=config('bprs.paymode');
      $rows=$this->podyeingservice
      ->join('companies',function($join){
      $join->on('companies.id','=','po_dyeing_services.company_id');
      })
      ->join('suppliers',function($join){
      $join->on('suppliers.id','=','po_dyeing_services.supplier_id');
      })
      ->join('currencies',function($join){
      $join->on('currencies.id','=','po_dyeing_services.currency_id');
      })
      ->where([['po_type_id', 1]])
      ->orderBy('po_dyeing_services.id','desc')
      ->get([
        'po_dyeing_services.*',
        'companies.code as company_code',
        'suppliers.name as supplier_code',
        'currencies.code as currency_code',
      ])
      ->map(function ($rows) use($paymode)  {
          $rows->paymode = $paymode[$rows->pay_mode];
          $rows->amount = number_format($rows->amount,2);
          $rows->po_date=date('d-M-Y',strtotime($rows->po_date));
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
      $supplier=array_prepend(array_pluck($this->supplier->DyeFinSubcontractor(),'name','id'),'','');
      $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
      $basis = array_only(config('bprs.pur_order_basis'), [1]);
      $paymode = array_prepend(array_only(config('bprs.paymode'), [3,4,6]),'-Select-','');

      return Template::loadView("Purchase.PoDyeingService", ['company'=>$company,'basis'=>$basis,'supplier'=>$supplier,'currency'=>$currency,'paymode'=>$paymode]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoDyeingServiceRequest $request)
    {
        $max = $this->podyeingservice->where([['company_id', $request->company_id]])->max('po_no');
        $po_no=$max+1;

        $podyeingservice = $this->podyeingservice->create(['po_no'=>$po_no,'po_type_id'=>1,'company_id'=>$request->company_id,'po_date'=>$request->po_date,'basis_id'=>$request->basis_id,'supplier_id'=>$request->supplier_id,'pay_mode'=>$request->pay_mode,'currency_id'=>$request->currency_id,'exch_rate'=>$request->exch_rate,'delv_start_date'=>$request->delv_start_date,'delv_end_date'=>$request->delv_end_date,'pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'remarks'=>$request->remarks]);
        $termscondition=$this->termscondition->where([['menu_id','=',6]])->orderBy('sort_id')->get();
        foreach($termscondition as $row)
        {
        $purchasetermscondition = $this->purchasetermscondition->create(['purchase_order_id'=>$podyeingservice->id,'term'=>$row->term,'sort_id'=>$row->sort_id,'menu_id'=>6]);
        }
        if ($podyeingservice) {
        return response()->json(array('success' => true, 'id' => $podyeingservice->id, 'message' => 'Save Successfully'), 200);
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
		  $podyeingservice = $this->podyeingservice->find($id);
        $podyeingservice->po_date=date('Y-m-d',strtotime($podyeingservice->po_date));

        if($podyeingservice->delv_start_date){
            $podyeingservice->delv_start_date=date('Y-m-d',strtotime($podyeingservice->delv_start_date));
        }
        
        if($podyeingservice->delv_end_date){
           $podyeingservice->delv_end_date=date('Y-m-d',strtotime($podyeingservice->delv_end_date));
        }
		  $row ['fromData'] = $podyeingservice;
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
    public function update(PoDyeingServiceRequest $request, $id)
    {
      $approved=$this->podyeingservice->find($id);
    	if($approved->approved_at){
    	$this->podyeingservice->update($id, ['pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'remarks'=>$request->remarks]);
    	  return response()->json(array('success' => false,  'message' => 'Approved, Update not Possible Except PI No , PI Date & Remarks,'), 200);
    	}
		  $podyeingservice = $this->podyeingservice->update($id, $request->except(['id','company_id','po_no',/* 'supplier_id', */'basis_id']));
		  if ($podyeingservice) {
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
        if($this->podyeingservice->delete($id)){
			 return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}else{
			 return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
		}
    }

    public function getPdf()
    {

      $id=request('id',0);
      $paymode=array_prepend(config('bprs.paymode'),'-Select-','');
      $rows=$this->podyeingservice
      ->join('companies',function($join){
      $join->on('companies.id','=','po_dyeing_services.company_id');
      })
      ->join('currencies',function($join){
      $join->on('currencies.id','=','po_dyeing_services.currency_id');
      })
      ->join('suppliers',function($join){
      $join->on('suppliers.id','=','po_dyeing_services.supplier_id');
      })
      ->join('users',function($join){
      $join->on('users.id','=','po_dyeing_services.created_by');
      })
      ->leftJoin('users as approve_users',function($join){
      $join->on('approve_users.id','=','po_dyeing_services.approved_by');
      })
      ->leftJoin('employee_h_rs as approve_employee',function($join){
      $join->on('approve_employee.user_id','=','approve_users.id');
      })
      ->leftJoin('designations',function($join){
      $join->on('approve_employee.designation_id','=','designations.id');
      })
      ->where([['po_dyeing_services.id','=',$id]])
      ->get([
      'po_dyeing_services.*',
      'po_dyeing_services.id as po_dyeing_service_id',
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


        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

        $fabricDescription=$this->podyeingservice
        ->join('po_dyeing_service_items',function($join){
        $join->on('po_dyeing_service_items.po_dyeing_service_id','=','po_dyeing_services.id');
        })
        ->join('budget_fabric_prods',function($join){
        $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
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
        ->where([['po_dyeing_services.id','=',$id]])
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


      $data=$this->podyeingservice
      ->selectRaw(
      '
      budget_fabrics.style_fabrication_id,
      budget_fabrics.gsm_weight,
      style_fabrications.fabric_look_id,
      style_fabrications.fabric_shape_id,
      style_fabrications.dyeing_type_id,
      gmtsparts.name as gmtspart_name,
      jobs.job_no,
      sales_orders.sale_order_no,
      styles.style_ref,
      buyers.name as buyer_name,
      colors.name as fabric_color,
      po_dyeing_service_item_qties.fabric_color_id,
      po_dyeing_service_item_qties.colorrange_id,
      po_dyeing_service_item_qties.qty,
      po_dyeing_service_item_qties.pcs_qty,
      po_dyeing_service_item_qties.rate,
      po_dyeing_service_item_qties.amount,
      processloss.process_loss
      '
      )
      ->join('po_dyeing_service_items',function($join){
        $join->on('po_dyeing_service_items.po_dyeing_service_id','=','po_dyeing_services.id');
      })
      ->join('po_dyeing_service_item_qties',function($join){
        $join->on('po_dyeing_service_item_qties.po_dyeing_service_item_id','=','po_dyeing_service_items.id');
        $join->whereNull('po_dyeing_service_item_qties.deleted_at');
      })
      // ->join('budget_fabric_prod_cons',function($join){
      //   $join->on('po_dyeing_service_item_qties.budget_fabric_prod_con_id','=','budget_fabric_prod_cons.id');
      // })
      ->join('sales_orders',function($join){
        $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
      })
      ->join('colors',function($join){
        $join->on('colors.id','=','po_dyeing_service_item_qties.fabric_color_id');
      })

      ->join('budget_fabric_prods',function($join){
        $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
        //$join->on('budget_fabric_prods.id','=','budget_fabric_prod_cons.budget_fabric_prod_id');
      })
      ->join('budget_fabrics',function($join){
        $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
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
      ->join('budgets',function($join){
        $join->on('budgets.id','=','budget_fabrics.budget_id');
      })
      ->join('jobs',function($join){
          $join->on('jobs.id','=','budgets.job_id');
      })
      ->join('styles', function($join) {
          $join->on('styles.id', '=', 'jobs.style_id');
      })
      ->join('buyers',function($join){
          $join->on('buyers.id','=','styles.buyer_id');
      })
      ->leftJoin(\DB::raw("(
        SELECT 
        po_dyeing_service_items.budget_fabric_prod_id,
        po_dyeing_service_item_qties.sales_order_id,
        po_dyeing_service_item_qties.dia,
        po_dyeing_service_item_qties.measurment,
        po_dyeing_service_item_qties.fabric_color_id,
        avg(budget_fabric_cons.process_loss) as process_loss 
        FROM po_dyeing_service_item_qties 
        join po_dyeing_service_items on  po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 
        and po_dyeing_service_items.deleted_at is null
        join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
        join budget_fabric_cons on  budget_fabric_cons.budget_fabric_id=budget_fabric_prods.budget_fabric_id
        and budget_fabric_cons.fabric_color=po_dyeing_service_item_qties.fabric_color_id
        where po_dyeing_service_item_qties.deleted_at is null 
        group by
        po_dyeing_service_item_qties.sales_order_id,
        po_dyeing_service_items.budget_fabric_prod_id, 
        po_dyeing_service_item_qties.dia,
        po_dyeing_service_item_qties.measurment,
        po_dyeing_service_item_qties.fabric_color_id
      ) processloss"), [
        ["processloss.dia", "=", "po_dyeing_service_item_qties.dia"],
        ["processloss.measurment", "=", "po_dyeing_service_item_qties.measurment"],
        ["processloss.sales_order_id", "=", "po_dyeing_service_item_qties.sales_order_id"],
        ["processloss.budget_fabric_prod_id", "=", "budget_fabric_prods.id"],
        ["processloss.fabric_color_id", "=", "po_dyeing_service_item_qties.fabric_color_id"]
      ])
      ->where([['po_dyeing_services.id','=',$id]])
      ->orderBy('sales_orders.id')
      //->orderBy('budget_fabrics.style_fabrication_id')
      //->orderBy('po_dyeing_service_item_qties.colorrange_id')
      ->orderBy('po_dyeing_service_item_qties.fabric_color_id')
      ->get()
      ->map(function($data) use($desDropdown,$fabriclooks,$fabricshape,$dyetype,$colorrange){
        $data->fabric_description=$desDropdown[$data->style_fabrication_id];
        $data->fabriclooks=$fabriclooks[$data->fabric_look_id];
        $data->fabricshape=$fabricshape[$data->fabric_shape_id];
        $data->dyeingtype=$dyetype[$data->dyeing_type_id];
        $data->colorrange=$colorrange[$data->colorrange_id];
        $process_lossqty=($data->qty*$data->process_loss)/100;
        $data->process_lossqty=$process_lossqty;
        $data->fin_qty=$data->qty-$data->process_lossqty;
        return $data;
      });

		$yarncolors = collect(\DB::select("select 
		budget_fabrics.style_fabrication_id,
		po_dyeing_service_items.budget_fabric_prod_id,
		po_dyeing_service_item_qties.sales_order_id,
		po_dyeing_service_item_qties.fabric_color_id,
		style_fabrication_stripes.style_color_id,
		style_fabrication_stripes.color_id,
		budget_fabrics.gsm_weight,
		gmtsparts.name as gmt_part_name,
		gmt_color.name as gmt_color_name,
		fabric_color.name as fabric_color_name,
		yarn_color.name as yarn_color_name,
		style_fabrications.fabric_look_id,
		style_fabrications.fabric_shape_id,
		jobs.job_no,
		sales_orders.sale_order_no,
		styles.style_ref,
		buyers.name as buyer_name


		FROM po_dyeing_services 
		join po_dyeing_service_items on  po_dyeing_services.id=po_dyeing_service_items.po_dyeing_service_id 
		join po_dyeing_service_item_qties on  po_dyeing_service_items.id=po_dyeing_service_item_qties.po_dyeing_service_item_id 

		and po_dyeing_service_items.deleted_at is null
		join budget_fabric_prods on  budget_fabric_prods.id=po_dyeing_service_items.budget_fabric_prod_id
		join budget_fabric_cons on  budget_fabric_cons.budget_fabric_id=budget_fabric_prods.budget_fabric_id
		and budget_fabric_cons.fabric_color=po_dyeing_service_item_qties.fabric_color_id
		join budget_fabrics on  budget_fabrics.id=budget_fabric_prods.budget_fabric_id
		join budget_yarn_dyeings on  budget_yarn_dyeings.budget_fabric_id=budget_fabrics.id
		join budget_yarn_dyeing_cons on  budget_yarn_dyeing_cons.budget_yarn_dyeing_id=budget_yarn_dyeings.id
		join style_fabrication_stripes on  style_fabrication_stripes.id=budget_yarn_dyeing_cons.style_fabrication_stripe_id
		join style_fabrications on  style_fabrications.id=style_fabrication_stripes.style_fabrication_id
		join style_colors on  style_colors.id=style_fabrication_stripes.style_color_id
		join colors gmt_color on  gmt_color.id=style_colors.color_id
		join colors fabric_color on  fabric_color.id=po_dyeing_service_item_qties.fabric_color_id
		join colors yarn_color on  yarn_color.id=style_fabrication_stripes.color_id
		join gmtsparts  on  gmtsparts.id=style_fabrications.gmtspart_id
		join autoyarns  on  autoyarns.id=style_fabrications.autoyarn_id
		join budgets  on  budgets.id=budget_fabrics.budget_id
		join sales_orders on sales_orders.id=po_dyeing_service_item_qties.sales_order_id
		join jobs  on  jobs.id=budgets.job_id
		join styles  on  styles.id=jobs.style_id
		join buyers  on  buyers.id=styles.buyer_id
		where po_dyeing_service_item_qties.deleted_at is null 
		and po_dyeing_services.id=".$id."
		group by
		po_dyeing_service_items.budget_fabric_prod_id, 
		po_dyeing_service_item_qties.sales_order_id,
		budget_fabrics.style_fabrication_id,
		po_dyeing_service_item_qties.fabric_color_id,
		fabric_color.name,
		style_fabrication_stripes.style_color_id,
		gmt_color.name,
		style_fabrication_stripes.color_id,
		yarn_color.name,
		budget_fabrics.gsm_weight,
		gmtsparts.name,
		style_fabrications.fabric_look_id,
		style_fabrications.fabric_shape_id,
		jobs.job_no,
		sales_orders.sale_order_no,
		styles.style_ref,
		buyers.name
		")
		)
		->map(function($yarncolors) use($desDropdown,$fabriclooks,$fabricshape,$dyetype,$colorrange){
			$yarncolors->fabric_description=$desDropdown[$yarncolors->style_fabrication_id];
			$yarncolors->fabriclooks=$fabriclooks[$yarncolors->fabric_look_id];
			$yarncolors->fabricshape=$fabricshape[$yarncolors->fabric_shape_id];
			return $yarncolors;
		});

      $amount=$data->sum('amount');
      $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_name,'cents');
      $rows->inword=$inword;
      $purOrder['master']=$rows;
      $purOrder['details']=$data;
      $purOrder['yarncolors']=$yarncolors;

      $purchasetermscondition=$this->purchasetermscondition->where([['purchase_order_id','=',$id]])->where([['menu_id','=',6]])->orderBy('sort_id')->get();
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
      $pdf->SetX(210);
      $challan=str_pad($purOrder['master']->po_dyeing_service_id,10,0,STR_PAD_LEFT ) ;
      $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.3, $barcodestyle, 'N');
      $pdf->SetY(10);
      $image_file ='images/logo/'.$rows->logo;
      $pdf->Image($image_file, 90, 5, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
      $pdf->SetY(13);
      $pdf->SetFont('helvetica', 'N', 9);
      //$pdf->Text(111, 16, $rows->company_address);
      $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
      // $pdf->SetY(26);
      // $pdf->SetFont('helvetica', 'B', 16);
      // $pdf->Write(0, 'Dyeing/Finishing Work Order ', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 8);
      $pdf->SetTitle('Dyeing Service Order');
      $view= \View::make('Defult.Purchase.PoDyeingServicePdf',['purOrder'=>$purOrder]);
      $html_content=$view->render();
      $pdf->SetY(35);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/PoDyeingServicePdf.pdf';
      $pdf->output($filename);
    }

    public function getSearchPoDyeing()
    {
     $paymode = config('bprs.paymode');
     $rows = $this->podyeingservice
      ->join('companies', function ($join) {
       $join->on('companies.id', '=', 'po_dyeing_services.company_id');
      })
      ->join('suppliers', function ($join) {
       $join->on('suppliers.id', '=', 'po_dyeing_services.supplier_id');
      })
      ->join('currencies', function ($join) {
       $join->on('currencies.id', '=', 'po_dyeing_services.currency_id');
      })
      ->when(request('wo_no'), function ($q) {
       return $q->where('po_dyeing_services.po_no', 'like', '%' . request('wo_no') . '%');
      })
      ->when(request('supplier_search_id'), function ($q) {
       return $q->where('suppliers.id', '=', request('supplier_search_id'));
      })
      ->when(request('beneficiary_search_id'), function ($q) {
       return $q->where('companies.id', '=', request('beneficiary_search_id'));
      })
      ->when(request('from_date'), function ($q) {
       return $q->where('po_dyeing_services.po_date', '>=', request('from_date'));
      })
      ->when(request('to_date'), function ($q) {
       return $q->where('po_dyeing_services.po_date', '=', request('to_date'));
      })
      ->where([['po_type_id', 1]])
      ->orderBy('po_dyeing_services.id', 'desc')
      ->get([
       'po_dyeing_services.*',
       'companies.code as company_code',
       'suppliers.name as supplier_code',
       'currencies.code as currency_code',
      ])
      ->map(function ($rows) use ($paymode) {
       $rows->paymode = $paymode[$rows->pay_mode];
       $rows->amount = number_format($rows->amount, 2);
       $rows->po_date = date('d-M-Y', strtotime($rows->po_date));
       $rows->approve_status = $rows->approved_at ? "Approved" : "--";
       return $rows;
      });
     echo json_encode($rows);
    }
}
