<?php

namespace App\Http\Controllers\Report\FAM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\FAMS\AssetTechnicalFeatureRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
class FamListController extends Controller
{
	private $assetacquisition;
	private $assetquantitycost;
    private $company;
    private $location;
    private $uom;
    private $supplier;
    private $assettechfeature;
    private $itemaccount;
    private $itemclass;
    private $itemcategory;
    private $store;

	public function __construct(AssetAcquisitionRepository $assetacquisition, CompanyRepository $company,LocationRepository $location, UomRepository $uom, SupplierRepository $supplier, AssetTechnicalFeatureRepository $assettechfeature, ItemAccountRepository $itemaccount, ItemclassRepository $itemclass, ItemcategoryRepository $itemcategory,StoreRepository $store, AssetQuantityCostRepository $assetquantitycost)
    {
		$this->assetacquisition = $assetacquisition;
		$this->assetquantitycost = $assetquantitycost;
        $this->company = $company;
        $this->store = $store;
        $this->location = $location;
        $this->uom = $uom;
        $this->supplier = $supplier;
        $this->assettechfeature = $assettechfeature;
        $this->itemaccount = $itemaccount;
        $this->itemclass = $itemclass;
        $this->itemcategory = $itemcategory;

		$this->middleware('auth');
		$this->middleware('permission:view.famlists',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
        $productionarea=array_prepend(config('bprs.productionarea'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'','');
        $assetType = array_prepend(config('bprs.assetType'),'','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'','');
      return Template::loadView('Report.FAM.FamList',['productionarea'=>$productionarea,'location'=>$location,'assetType'=>$assetType,'supplier'=>$supplier,'company'=>$company]);
	 }
	 
	public function reportData() {
	  $today=date('Y-m-d');
      $assetType = config('bprs.assetType');
      $productionarea=config('bprs.productionarea');
		
      $employeehrs =array();
      $rows=$this->assetacquisition
		   ->leftJoin('companies',function($join){
			   $join->on('asset_acquisitions.company_id','=','companies.id');
		   })
		   ->leftJoin('locations',function($join){
			   $join->on('asset_acquisitions.location_id','=','locations.id');
		   })
		    ->leftJoin('suppliers',function($join){
			   $join->on('asset_acquisitions.supplier_id','=','suppliers.id');
			})
			->leftJoin('asset_quantity_costs',function($join){
			   $join->on('asset_quantity_costs.asset_acquisition_id','=','asset_acquisitions.id');
			})
			->leftJoin('asset_technical_features',function($join){
			   $join->on('asset_technical_features.asset_acquisition_id','=','asset_acquisitions.id');
			})

			->leftJoin(\DB::raw("(
				SELECT 
				asset_manpowers.asset_quantity_cost_id,
				employee_h_rs.name  as emp_name
				FROM asset_manpowers 
			join employee_h_rs on employee_h_rs.id = asset_manpowers.employee_h_r_id
			where 
			'".$today."' between  asset_manpowers.tenure_start and asset_manpowers.tenure_end
			group by 
			asset_manpowers.asset_quantity_cost_id,
			employee_h_rs.name
		    ) manpowers"), "manpowers.asset_quantity_cost_id", "=", "asset_quantity_costs.id")
		   ->when(request('company_id'), function ($q) {
			return $q->where('asset_acquisitions.company_id', '=', request('company_id', 0));
		   })
		   ->when(request('location_id'), function ($q) {
			return $q->where('asset_acquisitions.location_id', '=', request('location_id', 0));
		   })
		    ->when(request('type_id'), function ($q) {
			return $q->where('asset_acquisitions.type_id', '=', request('type_id', 0));
		   })
		    ->when(request('production_area_id'), function ($q) {
			return $q->where('asset_acquisitions.production_area_id', '=', request('production_area_id', 0));
		   })
		   ->when(request('name'), function ($q) {
			return $q->where('asset_acquisitions.name', 'LIKE', '%'.request('production_area_id', 0).'%');
		   })
		   	->when(request('date_from'), function ($q) {
				return $q->whereDate('asset_acquisitions.purchase_date', '>=', request('date_from',0));
			})
			->when(request('date_to'), function ($q) {
			return $q->whereDate('asset_acquisitions.purchase_date', '<=', request('date_to',0));
			})
		   ->orderBy('asset_acquisitions.id','asc')
			->orderBy('asset_quantity_costs.id','asc')
			 ->get([
				'asset_acquisitions.*',
				'asset_acquisitions.id as aquisition_id',
				'asset_quantity_costs.id as asset_quantity_cost_id',
				'asset_quantity_costs.asset_no',
				'asset_quantity_costs.serial_no',
				'asset_quantity_costs.custom_no',
				'asset_quantity_costs.vendor_price',
				'asset_quantity_costs.landed_price',
				'asset_quantity_costs.machanical_cost',
				'asset_quantity_costs.civil_cost',
				'asset_quantity_costs.electrical_cost',
				'asset_quantity_costs.total_cost',
				'asset_quantity_costs.warrantee_close',
				'asset_technical_features.dia_width',
				'asset_technical_features.gauge',
				'asset_technical_features.extra_cylinder',
				'asset_technical_features.no_of_feeder',
				'companies.code as company_name',
				'locations.code as location_name',
				'suppliers.code as supplier_name',
				'manpowers.emp_name'
			])
			->map(function ($rows) use($assetType,$productionarea)  {
				$rows->total_cost=number_format($rows->total_cost,2);
				$rows->asset_type_name = isset($assetType[$rows->type_id])?$assetType[$rows->type_id]:'--';
				$rows->production_area = isset($productionarea[$rows->production_area_id])?$productionarea[$rows->production_area_id]:'--';
				$rows->brand_origin = $rows->brand." ".$rows->origin;
				$rows->asset_no = str_pad($rows->asset_quantity_cost_id,6,0,STR_PAD_LEFT );
				return $rows;
			});

			$total_cost=$rows->sum('total_cost');
			

		return $rows;
	 }
	 

   	public function html(){
      $employeelist = $this->reportData();
      echo json_encode($employeelist);

   	}
	

   	public function assetTicket(){
   		$id=request('asset_quantity_cost_id',0);
        $idarr=explode(',',$id);
		$rows=$this->assetquantitycost
		->join('asset_acquisitions',function($join){
			$join->on('asset_quantity_costs.asset_acquisition_id','=','asset_acquisitions.id');
		})
		->join('companies',function($join){
			$join->on('asset_acquisitions.company_id','=','companies.id');
		})
		->leftJoin('locations',function($join){
			$join->on('asset_acquisitions.location_id','=','locations.id');
		})
		->leftJoin(\DB::raw("(
			select 
			asset_quantity_costs.id as asset_quantity_cost_id,
			asset_manpowers.employee_h_r_id,
			employee_h_rs.name as employee_name
			from asset_manpowers
			join asset_quantity_costs on asset_quantity_costs.id=asset_manpowers.asset_quantity_cost_id
			join employee_h_rs on employee_h_rs.id=asset_manpowers.employee_h_r_id
			where asset_manpowers.id=(select max(asset_manpowers.id) from asset_manpowers where asset_quantity_costs.id=asset_manpowers.asset_quantity_cost_id)
			group by 
			asset_quantity_costs.id,
			asset_manpowers.employee_h_r_id,
			employee_h_rs.name
			) custody"), "custody.asset_quantity_cost_id", "=", "asset_quantity_costs.id")
		->whereIn('asset_quantity_costs.id',$idarr)
		->orderBy('asset_quantity_costs.id','desc')
		->get([
			'asset_quantity_costs.id as asset_quantity_cost_id',
			//'asset_quantity_costs.',
			'asset_quantity_costs.serial_no',
			'asset_quantity_costs.warrantee_close',
			'asset_quantity_costs.custom_no',
			'asset_acquisitions.name as asset_name',
			'asset_acquisitions.purchase_date',
			'asset_acquisitions.prod_capacity',
			'asset_acquisitions.brand',
			'asset_acquisitions.origin',
			'asset_acquisitions.location_id',
			'companies.name as company_name',
			'locations.name as location',
			'custody.employee_name',
		])
		->map(function($rows){
			$rows->purchase_date=($rows->purchase_date!==null)?date('d-M-Y',strtotime($rows->purchase_date)):null;
			$rows->company_purchase_date=$rows->company_name.";Purchase Date:".$rows->purchase_date;
			$rows->warrantee_close=($rows->warrantee_close!==null)?date('d-M-Y',strtotime($rows->warrantee_close)):null;
			$rows->brand_origin=$rows->brand." ; ".$rows->origin;
			return $rows;
		});
		$pdf = new \Pdf(PDF_PAGE_ORIENTATION, 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->setTopMargin(13.0);
		$pdf->SetRightMargin(0);
		$pdf->setHeaderMargin(0);
		$pdf->SetFooterMargin(0);
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, 2);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->SetFont('helvetica', '', 8);
		$pdf->AddPage('P', '');
		$barcodestyle = array(
			'position' => '',
			'align' => 'L',
			'stretch' => false,
			'fitwidth' => false,
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

		$counter = 1;
		foreach ($rows as $row) {
			$challan=str_pad($row['asset_quantity_cost_id'],10,0,STR_PAD_LEFT ) ;
			$x = $pdf->GetX();
			$y = $pdf->GetY();
			$pdf->setCellMargins(0,0,2.5,0);
			$pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39',  $x-2.5, $y-6.5, 63.5, 18, 0.4, $barcodestyle, 'L');
			$pdf->SetXY($x,$y);
			$pdf->Cell(63.5, 26, $row['custom_no'].";".$row['asset_name'], 0, 0, 'L', FALSE, '', 0, FALSE, 'C', 'B');
			$pdf->SetXY($x,$y);
			$pdf->Cell(63.5, 34, $row['company_purchase_date'], 0, 0, 'L', FALSE, '', 0, FALSE, 'C', 'B');
			$pdf->SetXY($x,$y);
			$pdf->Cell(63.5, 42, $row['brand_origin'], 0, 0, 'L', FALSE, '', 0, FALSE, 'C', 'B');
			$pdf->SetXY($x,$y);
			$pdf->Cell(63.5, 50, "Serial No: ".$row['serial_no'], 0, 0, 'L', FALSE, '', 0, FALSE, 'C', 'B');
			$pdf->SetXY($x,$y);
			$pdf->Cell(63.5, 58, $row['employee_name'], 0, 0, 'L', FALSE, '', 0, FALSE, 'C', 'B');
			$pdf->SetXY($x,$y);
			$pdf->Cell(63.5, 66, "Warrentee:".$row['warrantee_close'], 0, 0, 'L', FALSE, '', 0, FALSE, 'C', 'B');
			$pdf->SetXY($x,$y);
			$pdf->Cell(63.5, 74, "Locaton:".$row['location'], 0, 0, 'L', FALSE, '', 0, FALSE, 'C', 'B');
			$pdf->SetXY($x,$y);
			$pdf->Cell(63.5, 82, "Capacity:".$row['prod_capacity'], 0, 0, 'L', FALSE, '', 0, FALSE, 'C', 'B');
		if($counter == 3)
		{
			$counter = 1;
			$pdf->Ln(50);
		}else{
			$counter++;
		}

		}

		$view= \View::make('Defult.Report.FAM.AssetTicket',['rows'=>$rows]);
		$html_content=$view->render();
		$pdf->SetY(10);
		$pdf->WriteHtml($html_content, true, false,true,false,'');
		$filename = storage_path() . '/AssetTicket.pdf';
		$pdf->output($filename,'I');
		exit();
        
		// $id=request('asset_quantity_cost_id',0);
		// $rows=$this->assetacquisition
		// ->join('asset_quantity_costs',function($join){
		// 	$join->on('asset_quantity_costs.asset_acquisition_id','=','asset_acquisitions.id');
		// })
		// ->join('companies',function($join){
		// 	$join->on('asset_acquisitions.company_id','=','companies.id');
		// })
		// ->leftJoin('locations',function($join){
		// 	$join->on('asset_acquisitions.location_id','=','locations.id');
		// })
		// ->leftJoin(\DB::raw("(
		// 	select 
		// 	asset_quantity_costs.id as asset_quantity_cost_id,
		// 	asset_manpowers.employee_h_r_id,
		// 	employee_h_rs.name as employee_name
		// 	from asset_manpowers
		// 	join asset_quantity_costs on asset_quantity_costs.id=asset_manpowers.asset_quantity_cost_id
		// 	join employee_h_rs on employee_h_rs.id=asset_manpowers.employee_h_r_id
		// 	where asset_manpowers.id=(select max(asset_manpowers.id) from asset_manpowers where asset_quantity_costs.id=asset_manpowers.asset_quantity_cost_id)
		// 	group by 
		// 	asset_quantity_costs.id,
		// 	asset_manpowers.employee_h_r_id,
		// 	employee_h_rs.name
		// 	) custody"), "custody.asset_quantity_cost_id", "=", "asset_quantity_costs.id")
		// ->where([['asset_quantity_costs.id','=',$id]])
		// ->orderBy('asset_quantity_costs.id','desc')
		// ->get([
		// 	'asset_quantity_costs.id as asset_quantity_cost_id',
		// 	'asset_quantity_costs.serial_no',
		// 	'asset_quantity_costs.warrantee_close',
		// 	'asset_quantity_costs.custom_no',
		// 	'asset_acquisitions.name as asset_name',
		// 	'asset_acquisitions.purchase_date',
		// 	'asset_acquisitions.prod_capacity',
		// 	'asset_acquisitions.brand',
		// 	'asset_acquisitions.origin',
		// 	'asset_acquisitions.location_id',
		// 	'companies.name as company_name',
		// 	'locations.name as location',
		// 	'custody.employee_name',
		// ])
		// ->map(function($rows){
		// 	$rows->company_purchase_date=$rows->company_name."; Purchase Date:".date('d-M-Y',strtotime($rows->purchase_date));
		// 	$rows->brand_origin=$rows->brand." & ".$rows->origin;
		// 	$rows->employee_serial=$rows->employee_name."; Serial No: ".$rows->serial_no;
		// 	return $rows;
		// })
		// ->first();
		// // dd($rows);
		// // die;
		// //$pdf = new \Pdf('P', 'mm', 'A7 PORTRAIT', true, 'UTF-8', false);
		// $pdf = new \Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		// $pdf->SetPrintHeader(false);
		// $pdf->SetPrintFooter(false);
		// $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		// $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		// //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		// //$pdf->SetMargins('.5', PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		// //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		// //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		// $pdf->setTopMargin(0);
		// $pdf->SetRightMargin(0);
		// $pdf->setHeaderMargin(0);
		// $pdf->SetFooterMargin(0);
		// // set auto page breaks
		// $pdf->SetAutoPageBreak(TRUE, 0);
		// //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		// $pdf->SetFont('helvetica', '', 6);
		// //$pdf->AddPage('P', 'G9');
		// //$pdf->AddPage('L', array(57,32));
		// $pdf->AddPage('P', '');
		// $barcodestyle = array(
		// 	'position' => '',
		// 	'align' => 'L',
		// 	'stretch' => false,
		// 	'fitwidth' => false,
		// 	'cellfitalign' => '',
		// 	'border' => false,
		// 	'hpadding' => 'auto',
		// 	'vpadding' => 'auto',
		// 	'fgcolor' => array(0,0,0),
		// 	'bgcolor' => false, //array(255,255,255),
		// 	'text' => true,
		// 	'font' => 'helvetica',
		// 	'fontsize' => 6,
		// 	'stretchtext' => 4
		// );
		
		// $pdf->SetY(4);
		// $pdf->SetX(3);
		// $challan=str_pad($rows['asset_quantity_cost_id'],10,0,STR_PAD_LEFT ) ;
		// //$x = $pdf->GetX();
		// //$y = $pdf->GetY();
		// $pdf->setCellMargins(0,0,2.0,0);
		// //$pdf->Cell(0, 0,'Product Code', 0, 1);
		// $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', /* $x-2.5 */'', /* $y-6.5 */'', 63.5, 12, 0.3, $barcodestyle, 'N');
		// $pdf->SetFont('helvetica', 'N',5);
		// $pdf->Text(5, 16, $rows['custom_no']."; ".$rows['asset_name']);
		// $pdf->Text(5, 18, $rows['company_purchase_date']);
		// $pdf->Text(5, 20, $rows['brand_origin']);
		// $pdf->Text(5, 22, $rows['employee_serial']);
		// $pdf->Text(5, 24, "Warrentee:".$rows['warrantee_close']."; Locaton:".$rows['location']);
		// $pdf->Text(5, 26, "Capacity:".$rows['prod_capacity']);
		
		// $view= \View::make('Defult.Report.FAM.AssetTicket',['rows'=>$rows]);
		// $html_content=$view->render();
		// $pdf->SetY(10);
		// $pdf->WriteHtml($html_content, true, false,true,false,'');
		// $filename = storage_path() . '/BarcodePdf.pdf';
		// $pdf->output($filename);
		// exit();
	}
}
