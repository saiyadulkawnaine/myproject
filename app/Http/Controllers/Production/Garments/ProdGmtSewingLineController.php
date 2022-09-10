<?php
namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingLineRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingLineOrderRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;

use App\Library\Template;
use App\Http\Requests\Production\Garments\ProdGmtSewingLineRequest;

class ProdGmtSewingLineController extends Controller {

    private $prodgmtsewingline;
    private $prodgmtsewinglineorder;
    private $wstudylinesetup;
    private $location;
    private $company;
    private $supplier;

    public function __construct(ProdGmtSewingLineRepository $prodgmtsewingline, LocationRepository $location, CompanyRepository $company, SupplierRepository $supplier,ProdGmtSewingLineOrderRepository $prodgmtsewinglineorder,WstudyLineSetupRepository $wstudylinesetup) {
        $this->prodgmtsewingline = $prodgmtsewingline;
        $this->prodgmtsewinglineorder = $prodgmtsewinglineorder;
        $this->wstudylinesetup = $wstudylinesetup;
        $this->location = $location;
        $this->company = $company;
        $this->supplier = $supplier;
        $this->middleware('auth');
            /*$this->middleware('permission:view.prodgmtsewinglines',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtsewinglines', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtsewinglines',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtsewinglines', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
         
        $prodgmtsewinglines=array();
        $rows=$this->prodgmtsewingline
        ->orderBy('prod_gmt_sewing_lines.id','desc')
        ->get();
        foreach($rows as $row){
            $prodgmtsewingline['id']=$row->id;
            $prodgmtsewingline['challan_no']=$row->challan_no;
            $prodgmtsewingline['input_date']=date('d-M-Y',strtotime($row->input_date));
            $prodgmtsewingline['supplier_id']=isset($supplier[$row->supplier_id])?$supplier[$row->supplier_id]:'';
            $prodgmtsewingline['produced_company_id']=isset($company[$row->produced_company_id])?$company[$row->produced_company_id]:'';
            $prodgmtsewingline['location_id']=$location[$row->location_id];
            $prodgmtsewingline['shiftname_id']=$shiftname[$row->shiftname_id];
            array_push($prodgmtsewinglines,$prodgmtsewingline);
        }
        echo json_encode($prodgmtsewinglines);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->garmentSubcontractors(),'name','id'),'','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
		$productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
		$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');

        return Template::loadView('Production.Garments.ProdGmtSewingLine', ['location'=> $location,'productionsource'=> $productionsource,'shiftname'=> $shiftname, 'company'=> $company, 'fabriclooks'=>$fabriclooks,'supplier'=>$supplier]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdGmtSewingLineRequest $request) {
        $max=$this->prodgmtsewingline->where([['produced_company_id','=',$request->produced_company_id]])->max('challan_no');
        $challan_no=$max+1;
        $prodgmtsewingline=$this->prodgmtsewingline->create([
            'challan_no'=>$challan_no,
            'produced_company_id'=>$request->produced_company_id,
            'supplier_id'=>$request->supplier_id,
            'location_id'=>$request->location_id,
            'shiftname_id'=>$request->shiftname_id,'input_date'=>$request->input_date, 
        ]);
        if($prodgmtsewingline){
            return response()->json(array('success' => true,'id' =>  $prodgmtsewingline->id, 'challan_no'=>$challan_no ,'message' => 'Save Successfully'),200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $prodgmtsewingline = $this->prodgmtsewingline->find($id);
        $row ['fromData'] = $prodgmtsewingline;
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
    public function update(ProdGmtSewingLineRequest $request, $id) {
        $prodgmtsewingline=$this->prodgmtsewingline->update($id,$request->except(['id','location_id','produced_company_id']));
        if($prodgmtsewingline){
            return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->prodgmtsewingline->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function SewingLinePdf(){
        $id=request('id',0);    
		$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $prodgmtsewinglines=array();
        $rows=$this->prodgmtsewingline
        ->leftJoin('suppliers',function($join){
            $join->on('prod_gmt_sewing_lines.supplier_id','=','suppliers.id');
        })
        ->leftJoin('locations',function($join){
            $join->on('prod_gmt_sewing_lines.location_id','=','locations.id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'prod_gmt_sewing_lines.produced_company_id');
        })
        ->where([['prod_gmt_sewing_lines.id','=', $id]])
        ->get([
	        'prod_gmt_sewing_lines.*',
	        'suppliers.name as supplier_name',
	        'suppliers.address as supplier_address',
	        'suppliers.id as supplier_id',
	        'companies.id as produced_company_id',
	        'locations.name as location_id'
        ]);

        foreach($rows as $row){
            $prodgmtsewingline['id']=$row->id;
            $prodgmtsewingline['challan_no']=$row->challan_no;
            //$prodgmtsewingline['supplier_id']=$supplier[$row->supplier_id];
            $prodgmtsewingline['supplier_name']=$row->supplier_name;
            $prodgmtsewingline['supplier_address']=$row->supplier_address;
            $prodgmtsewingline['input_date']=date('Y-m-d',strtotime($row->input_date));
            $prodgmtsewingline['location_id']=$row->location_id;
            $prodgmtsewingline['produced_company_id']=$row->produced_company_id;
            $prodgmtsewingline['shiftname_id']=$shiftname[$row->shiftname_id];
        }
       
        $company=$this->company
        ->where([['id','=',$prodgmtsewingline['produced_company_id']]])
        ->get()->first();

        $subsections=$this->wstudylinesetup
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
        })
        ->join('wstudy_line_setup_lines', function($join)  {
            $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })
        ->join('subsections', function($join)  {
            $join->on('subsections.id', '=', 'wstudy_line_setup_lines.subsection_id');
        })
        ->join('floors', function($join)  {
            $join->on('floors.id', '=', 'subsections.floor_id');
        })
        //->where([['prod_gmt_sewing_line_orders.prod_gmt_sewing_line_id','=',$id]])
        ->get([
            'wstudy_line_setups.id',
            'subsections.name',
            'subsections.code',
            'floors.name as floor_name'
        ]);
        $lineNames=Array();
        $lineFloor=Array();
        foreach($subsections as $subsection){
            $lineNames[$subsection->id][]=$subsection->code;
            $lineFloor[$subsection->id][]=$subsection->floor_name;
        }

        $gmtsewinglineqty = $this->prodgmtsewingline
        ->join('prod_gmt_sewing_line_orders', function($join)  {
            $join->on('prod_gmt_sewing_lines.id', '=', 'prod_gmt_sewing_line_orders.prod_gmt_sewing_line_id');
        })
        ->join('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_sewing_line_orders.sales_order_country_id');
        })
        ->join('countries', function($join) {
            $join->on('countries.id', '=', 'sales_order_countries.country_id');
        })
        ->join('sales_orders',function($join){
            $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
        })
        ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'jobs.company_id');
        })
        ->join('companies as produced_company', function($join)  {
            $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
        })
        ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('buyers', function($join)  {
            $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->join('uoms',function($join){
			$join->on('uoms.id','=','styles.uom_id');
		})
        ->join('prod_gmt_cutting_orders',function($join){
            $join->on('prod_gmt_cutting_orders.sales_order_country_id','=','prod_gmt_sewing_line_orders.sales_order_country_id');
         })
        ->join('prod_gmt_cutting_qties',function($join){
            $join->on('prod_gmt_cutting_qties.prod_gmt_cutting_order_id','=','prod_gmt_cutting_orders.id');
         })
        ->join('sales_order_gmt_color_sizes', function($join)  {
            $join->on('sales_order_gmt_color_sizes.id', '=', 'prod_gmt_cutting_qties.sales_order_gmt_color_size_id');
        })
        ->join('style_gmt_color_sizes', function($join)  {
            $join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
        })
        ->join('style_gmts',function($join){
            $join->on('style_gmts.id','=','style_gmt_color_sizes.style_gmt_id');
        })
        ->join('item_accounts',function($join){
            $join->on('item_accounts.id','=','style_gmts.item_account_id');
        })
        ->join('style_colors',function($join){
            $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
        })
        ->join('colors',function($join){
            $join->on('colors.id','=','style_colors.color_id');
        })
        ->join('style_sizes',function($join){
            $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
        })
        ->join('sizes',function($join){
            $join->on('sizes.id','=','style_sizes.size_id');
        })
        ->join('prod_gmt_sewing_line_qties',function($join){
            $join->on('prod_gmt_sewing_line_qties.prod_gmt_sewing_line_order_id','=','prod_gmt_sewing_line_orders.id');
            $join->on('prod_gmt_sewing_line_qties.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
        })
        ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id') 
        ->where([['prod_gmt_sewing_lines.id','=', $id]])   
        ->selectRaw('
            sales_orders.id as sale_order_id,
            sales_orders.sale_order_no,
            sales_orders.produced_company_id,
            sales_orders.ship_date,
            styles.buyer_id,
            styles.style_ref,
            uoms.code as uom_name,
            buyers.name as buyer_name,
            sizes.name as size_name,
            colors.id as color_id,
            colors.name as color_name,
            style_sizes.sort_id as size_sort_id,
            style_colors.sort_id as color_sort_id,
            item_accounts.item_description,
            prod_gmt_sewing_line_orders.wstudy_line_setup_id,
            prod_gmt_sewing_line_qties.qty
            ')
            
        ->groupBy([
            'sales_orders.id',
            'sales_orders.sale_order_no',
            'sales_orders.produced_company_id',
            'sales_orders.ship_date',
            'styles.buyer_id',
            'styles.style_ref',
            'uoms.code',
            'buyers.name',
            'sizes.name',
            'colors.id',
            'colors.name',
            'style_sizes.sort_id',
            'style_colors.sort_id',
            'item_accounts.item_description',
            'prod_gmt_sewing_line_orders.wstudy_line_setup_id',
            'prod_gmt_sewing_line_qties.qty',
        ])
        ->get()
        ->map(function ($gmtsewinglineqty) use($lineNames,$lineFloor) {
            $gmtsewinglineqty->ship_date=date('d-m-Y',strtotime($gmtsewinglineqty->ship_date));
            $gmtsewinglineqty->line_name=isset($lineNames[$gmtsewinglineqty->wstudy_line_setup_id])?implode(',',$lineNames[$gmtsewinglineqty->wstudy_line_setup_id]):'--';
            $gmtsewinglineqty->line_floor=isset($lineFloor[$gmtsewinglineqty->wstudy_line_setup_id])?implode(',',$lineFloor[$gmtsewinglineqty->wstudy_line_setup_id]):'--';
            return $gmtsewinglineqty;
        });
        $podata=array();
        $colordata=array();
        //$coloritem = array();
        
        foreach($gmtsewinglineqty as $row){
            $podata[$row->sale_order_id]['sale_order_no']=$row->sale_order_no;
            $podata[$row->sale_order_id]['line_name']=$row->line_name;
            $podata[$row->sale_order_id]['line_floor']=$row->line_floor;
            $podata[$row->sale_order_id]['ship_date']=$row->ship_date;
            $podata[$row->sale_order_id]['style_ref']=$row->style_ref;
            $podata[$row->sale_order_id]['buyer_name']=$row->buyer_name;
            $colordata[$row->color_id]=$row->color_name;
        } 

        $saved=$gmtsewinglineqty->groupBy(['sale_order_id','color_id',]);
        
        $pdf = new \Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $header=['logo'=>$company->logo,'address'=>$company->address,'title'=>'Challan'];
        $pdf->setCustomHeader($header);
        $pdf->SetPrintHeader(true);
        //$pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(true);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, 42, PDF_MARGIN_RIGHT);
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
        $challan=str_pad($prodgmtsewingline['id'],10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
        $pdf->SetFont('helvetica', '', 8);
        $view= \View::make('Defult.Production.Garments.SewingLinePdf',['prodgmtsewingline'=>$prodgmtsewingline,'saved'=>$saved,'podata'=>$podata,'colordata'=>$colordata]);
        $html_content=$view->render();
        $pdf->SetY(42);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/SewingLineShortPdf.pdf';
        $pdf->output($filename);
        exit();
    }

    public function SewingLineShortPdf(){
        $id=request('id',0);    
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $prodgmtsewinglines=array();
        $rows=$this->prodgmtsewingline
        ->leftJoin('suppliers',function($join){
            $join->on('prod_gmt_sewing_lines.supplier_id','=','suppliers.id');
        })
        ->leftJoin('locations',function($join){
            $join->on('prod_gmt_sewing_lines.location_id','=','locations.id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'prod_gmt_sewing_lines.produced_company_id');
        })
        ->where([['prod_gmt_sewing_lines.id','=', $id]])
        ->get([
            'prod_gmt_sewing_lines.*',
            'suppliers.name as supplier_name',
            'suppliers.address as supplier_address',
            'suppliers.id as supplier_id',
            'companies.id as produced_company_id',
            'locations.name as location_id'
        ]);

        foreach($rows as $row){
            $prodgmtsewingline['id']=$row->id;
            $prodgmtsewingline['challan_no']=$row->challan_no;
            //$prodgmtsewingline['supplier_id']=$supplier[$row->supplier_id];
            $prodgmtsewingline['supplier_name']=$row->supplier_name;
            $prodgmtsewingline['supplier_address']=$row->supplier_address;
            $prodgmtsewingline['input_date']=date('Y-m-d',strtotime($row->input_date));
            $prodgmtsewingline['location_id']=$row->location_id;
            $prodgmtsewingline['produced_company_id']=$row->produced_company_id;
            $prodgmtsewingline['shiftname_id']=$shiftname[$row->shiftname_id];
        }
       
        $company=$this->company
        ->where([['id','=',$prodgmtsewingline['produced_company_id']]])
        ->get()->first();

        $subsections=$this->wstudylinesetup
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'wstudy_line_setups.company_id');
        })
        ->join('wstudy_line_setup_lines', function($join)  {
            $join->on('wstudy_line_setup_lines.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        })
        ->join('subsections', function($join)  {
            $join->on('subsections.id', '=', 'wstudy_line_setup_lines.subsection_id');
        })
        ->join('floors', function($join)  {
            $join->on('floors.id', '=', 'subsections.floor_id');
        })
        //->where([['prod_gmt_sewing_line_orders.prod_gmt_sewing_line_id','=',$id]])
        ->get([
            'wstudy_line_setups.id',
            'subsections.name',
            'subsections.code',
            'floors.name as floor_name'
        ]);
        $lineNames=Array();
        $lineFloor=Array();
        foreach($subsections as $subsection){
            $lineNames[$subsection->id][]=$subsection->code;
            $lineFloor[$subsection->id][]=$subsection->floor_name;
        }

        $gmtsewinglineqty = $this->prodgmtsewingline
        ->join('prod_gmt_sewing_line_orders', function($join)  {
            $join->on('prod_gmt_sewing_lines.id', '=', 'prod_gmt_sewing_line_orders.prod_gmt_sewing_line_id');
        })
        ->join('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_sewing_line_orders.sales_order_country_id');
        })
        ->join('countries', function($join) {
            $join->on('countries.id', '=', 'sales_order_countries.country_id');
        })
        ->join('sales_orders',function($join){
            $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
        })
        ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'jobs.company_id');
        })
        ->join('companies as produced_company', function($join)  {
            $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
        })
        ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('buyers', function($join)  {
            $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->join('uoms',function($join){
            $join->on('uoms.id','=','styles.uom_id');
        })
        ->join('prod_gmt_cutting_orders',function($join){
            $join->on('prod_gmt_cutting_orders.sales_order_country_id','=','prod_gmt_sewing_line_orders.sales_order_country_id');
         })
        ->join('prod_gmt_cutting_qties',function($join){
            $join->on('prod_gmt_cutting_qties.prod_gmt_cutting_order_id','=','prod_gmt_cutting_orders.id');
         })
        ->join('sales_order_gmt_color_sizes', function($join)  {
            $join->on('sales_order_gmt_color_sizes.id', '=', 'prod_gmt_cutting_qties.sales_order_gmt_color_size_id');
        })
        ->join('style_gmt_color_sizes', function($join)  {
            $join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
        })
        ->join('style_gmts',function($join){
            $join->on('style_gmts.id','=','style_gmt_color_sizes.style_gmt_id');
        })
        ->join('item_accounts',function($join){
            $join->on('item_accounts.id','=','style_gmts.item_account_id');
        })
        ->join('style_colors',function($join){
            $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
        })
        ->join('colors',function($join){
            $join->on('colors.id','=','style_colors.color_id');
        })
        ->join('style_sizes',function($join){
            $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
        })
        ->join('sizes',function($join){
            $join->on('sizes.id','=','style_sizes.size_id');
        })
        ->join('prod_gmt_sewing_line_qties',function($join){
            $join->on('prod_gmt_sewing_line_qties.prod_gmt_sewing_line_order_id','=','prod_gmt_sewing_line_orders.id');
            $join->on('prod_gmt_sewing_line_qties.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
        })
        ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id') 
        ->where([['prod_gmt_sewing_lines.id','=', $id]])   
        ->selectRaw('
            sales_orders.id as sale_order_id,
            sales_orders.sale_order_no,
            sales_orders.produced_company_id,
            sales_orders.ship_date,
            styles.buyer_id,
            styles.style_ref,
            uoms.code as uom_name,
            buyers.name as buyer_name,
            sizes.name as size_name,
            colors.id as color_id,
            colors.name as color_name,
            style_sizes.sort_id as size_sort_id,
            style_colors.sort_id as color_sort_id,
            item_accounts.item_description,
            prod_gmt_sewing_line_orders.wstudy_line_setup_id,
            prod_gmt_sewing_line_qties.qty
            ')
            
        ->groupBy([
            'sales_orders.id',
            'sales_orders.sale_order_no',
            'sales_orders.produced_company_id',
            'sales_orders.ship_date',
            'styles.buyer_id',
            'styles.style_ref',
            'uoms.code',
            'buyers.name',
            'sizes.name',
            'colors.id',
            'colors.name',
            'style_sizes.sort_id',
            'style_colors.sort_id',
            'item_accounts.item_description',
            'prod_gmt_sewing_line_orders.wstudy_line_setup_id',
            'prod_gmt_sewing_line_qties.qty',
        ])
        ->get()
        ->map(function ($gmtsewinglineqty) use($lineNames,$lineFloor) {
            $gmtsewinglineqty->ship_date=date('d-m-Y',strtotime($gmtsewinglineqty->ship_date));
            $gmtsewinglineqty->line_name=isset($lineNames[$gmtsewinglineqty->wstudy_line_setup_id])?implode(',',$lineNames[$gmtsewinglineqty->wstudy_line_setup_id]):'--';
            $gmtsewinglineqty->line_floor=isset($lineFloor[$gmtsewinglineqty->wstudy_line_setup_id])?implode(',',$lineFloor[$gmtsewinglineqty->wstudy_line_setup_id]):'--';
            return $gmtsewinglineqty;
        });
        $podata=array();
        $colordata=array();
        //$coloritem = array();
        
        foreach($gmtsewinglineqty as $row){
            $podata[$row->sale_order_id]['sale_order_no']=$row->sale_order_no;
            $podata[$row->sale_order_id]['line_name']=$row->line_name;
            $podata[$row->sale_order_id]['line_floor']=$row->line_floor;
            $podata[$row->sale_order_id]['ship_date']=$row->ship_date;
            $podata[$row->sale_order_id]['style_ref']=$row->style_ref;
            $podata[$row->sale_order_id]['buyer_name']=$row->buyer_name;
            $colordata[$row->color_id]=$row->color_name;
        } 

        $saved=$gmtsewinglineqty->groupBy(['sale_order_id','color_id',]);
        
        $pdf = new \Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $header=['logo'=>$company->logo,'address'=>$company->address,'title'=>'Challan'];
        $pdf->setCustomHeader($header);
        $pdf->SetPrintHeader(true);
        //$pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(true);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, 42, PDF_MARGIN_RIGHT);
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
        $challan=str_pad($prodgmtsewingline['id'],10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
        $pdf->SetFont('helvetica', '', 8);
        $view= \View::make('Defult.Production.Garments.SewingLineShortPdf',['prodgmtsewingline'=>$prodgmtsewingline,'saved'=>$saved,'podata'=>$podata,'colordata'=>$colordata]);
        $html_content=$view->render();
        $pdf->SetY(42);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/SewingLinePdf.pdf';
        $pdf->output($filename);
        exit();
    }
    

}