<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtDlvToEmbRepository;

use App\Library\Template;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Http\Requests\Production\Garments\ProdGmtDlvToEmbRequest;

class ProdGmtDlvToEmbController extends Controller {

    private $prodgmtdlvtoemb;
    private $location;
    private $company;
    private $supplier;

    public function __construct(ProdGmtDlvToEmbRepository $prodgmtdlvtoemb, LocationRepository $location, CompanyRepository $company, SupplierRepository $supplier) {
        $this->prodgmtdlvtoemb = $prodgmtdlvtoemb;
        $this->location = $location;
        $this->company = $company;
        $this->supplier = $supplier;
        $this->middleware('auth');
            /*$this->middleware('permission:view.prodgmtdlvtoembs',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtdlvtoembs', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtdlvtoembs',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtdlvtoembs', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
       $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
	   $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
         
        $prodgmtdlvtoembs=array();
        $rows=$this->prodgmtdlvtoemb
        ->orderBy('prod_gmt_dlv_to_embs.id','desc')
        ->get();
        foreach($rows as $row){
            $prodgmtdlvtoemb['id']=$row->id;
            $prodgmtdlvtoemb['challan_no']=$row->challan_no;
            $prodgmtdlvtoemb['supplier_id']=$supplier[$row->supplier_id];
            $prodgmtdlvtoemb['delivery_date']=date('Y-m-d',strtotime($row->delivery_date));
            $prodgmtdlvtoemb['location_id']=$location[$row->location_id];
            $prodgmtdlvtoemb['shiftname_id']=$shiftname[$row->shiftname_id];
            array_push($prodgmtdlvtoembs,$prodgmtdlvtoemb);
        }
        echo json_encode($prodgmtdlvtoembs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->embellishmentSubcontractor(),'name','id'),'','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
		$productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
		$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');

        return Template::loadView('Production.Garments.ProdGmtDlvToEmb', ['location'=> $location,'productionsource'=> $productionsource,'shiftname'=> $shiftname, 'company'=> $company, 'fabriclooks'=>$fabriclooks,'supplier'=>$supplier]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdGmtDlvToEmbRequest $request) {
        $max=$this->prodgmtdlvtoemb->where([['produced_company_id' , $request->produced_company_id ]])->max('challan_no');
        $challan_no=$max+1;
        $prodgmtdlvtoemb = $this->prodgmtdlvtoemb->create([
            'challan_no'=>$challan_no,
            'supplier_id'=>$request->supplier_id,
            'produced_company_id'=>$request->produced_company_id,
            'delivery_date'=>$request->delivery_date,
            'location_id'=>$request->location_id,
            'shiftname_id'=>$request->shiftname_id
        ]);
        if($prodgmtdlvtoemb){
            return response()->json(array('success' => true,'id' =>  $prodgmtdlvtoemb->id,'challan_no' => $challan_no ,'message' => 'Save Successfully'),200);
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
        $prodgmtdlvtoemb = $this->prodgmtdlvtoemb->find($id);
        $row ['fromData'] = $prodgmtdlvtoemb;
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
    public function update(ProdGmtDlvToEmbRequest $request, $id) {
        $prodgmtdlvtoemb=$this->prodgmtdlvtoemb->update($id,$request->except(['id',/*  'supplier_id', */ 'challan_no','produced_company_id']));
        if($prodgmtdlvtoemb){
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
        if($this->prodgmtdlvtoemb->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function EmbPdf(){
        $id=request('id',0);

        
		$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $prodgmtdlvtoembs=array();
        $rows=$this->prodgmtdlvtoemb
        ->leftJoin('suppliers',function($join){
            $join->on('prod_gmt_dlv_to_embs.supplier_id','=','suppliers.id');
        })
        ->leftJoin('locations',function($join){
            $join->on('prod_gmt_dlv_to_embs.location_id','=','locations.id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'prod_gmt_dlv_to_embs.produced_company_id');
        })
        ->where([['prod_gmt_dlv_to_embs.id','=', $id]])
        ->get([
        'prod_gmt_dlv_to_embs.*',  
        'suppliers.name as supplier_name',
        'suppliers.address as supplier_address',
        'suppliers.id as supplier_id',
        'companies.id as produced_company_id',
        'locations.name as location_id'
        ]);
        foreach($rows as $row){
            $prodgmtdlvtoemb['id']=$row->id;
            $prodgmtdlvtoemb['challan_no']=$row->challan_no;
            //$prodgmtdlvtoemb['supplier_id']=$supplier[$row->supplier_id];
            $prodgmtdlvtoemb['supplier_name']=$row->supplier_name;
            $prodgmtdlvtoemb['supplier_address']=$row->supplier_address;
            $prodgmtdlvtoemb['delivery_date']=date('Y-m-d',strtotime($row->delivery_date));
            $prodgmtdlvtoemb['location_id']=$row->location_id;
            $prodgmtdlvtoemb['produced_company_id']=$row->produced_company_id;
            $prodgmtdlvtoemb['shiftname_id']=$shiftname[$row->shiftname_id]; 
        }
       
        $company=$this->company
        ->where([['id','=',$prodgmtdlvtoemb['produced_company_id']]])
        ->get()->first();

        $gmtdlvtoembqty = $this->prodgmtdlvtoemb
        ->join('prod_gmt_dlv_to_emb_orders', function($join)  {
            $join->on('prod_gmt_dlv_to_embs.id', '=', 'prod_gmt_dlv_to_emb_orders.prod_gmt_dlv_to_emb_id');
        })
        ->join('sales_order_countries', function($join)  {
            $join->on('sales_order_countries.id', '=', 'prod_gmt_dlv_to_emb_orders.sales_order_country_id');
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
        ->join('budgets',function($join){
            $join->on('jobs.id','=','budgets.job_id');
        })
        ->join('budget_embs',function($join){
            $join->on('budgets.id','=','budget_embs.budget_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'jobs.company_id');
        })
        ->leftJoin('companies as produced_company', function($join)  {
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
            $join->on('prod_gmt_cutting_orders.sales_order_country_id','=','prod_gmt_dlv_to_emb_orders.sales_order_country_id');
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
        ->join('style_embelishments',function($join){
            $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
            $join->on('style_embelishments.style_gmt_id','=','sales_order_gmt_color_sizes.style_gmt_id');
            })
        ->join('budget_emb_cons',function($join){
            $join->on('budget_embs.id','=','budget_emb_cons.budget_emb_id')
            ->on('sales_order_gmt_color_sizes.id','=','budget_emb_cons.sales_order_gmt_color_size_id')
            ->whereNull('budget_emb_cons.deleted_at');
        })
        ->join('prod_gmt_dlv_to_emb_qties',function($join){
            $join->on('prod_gmt_dlv_to_emb_qties.prod_gmt_dlv_to_emb_order_id','=','prod_gmt_dlv_to_emb_orders.id');
            $join->on('prod_gmt_dlv_to_emb_qties.sales_order_gmt_color_size_id','=','sales_order_gmt_color_sizes.id');
        })
        /* ->join(\DB::raw("(SELECT sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,sum(prod_gmt_dlv_to_emb_qties.qty) as qty FROM prod_gmt_dlv_to_emb_qties join prod_gmt_dlv_to_emb_orders on prod_gmt_dlv_to_emb_orders.id =prod_gmt_dlv_to_emb_qties.prod_gmt_dlv_to_emb_order_id join sales_order_gmt_color_sizes on  sales_order_gmt_color_sizes.id=prod_gmt_dlv_to_emb_qties.sales_order_gmt_color_size_id where prod_gmt_dlv_to_emb_qties.deleted_at is null  group by sales_order_gmt_color_sizes.id) cumulatives"), "cumulatives.sales_order_gmt_color_size_id", "=", "sales_order_gmt_color_sizes.id") */

        ->orderBy('style_colors.sort_id')
        ->orderBy('style_sizes.sort_id') 
        ->where([['prod_gmt_dlv_to_embs.id','=', $id]])   
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
            prod_gmt_dlv_to_emb_qties.qty       
            ')/*sales_order_gmt_color_sizes.id as sales_order_gmt_color_size_id,sum(prod_gmt_dlv_to_emb_qties.qty) as qty ,
            cumulatives.qty*/
            
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
            //'sales_order_gmt_color_sizes.id',
            'prod_gmt_dlv_to_emb_qties.qty'
            //'cumulatives.qty'
        ])
        ->get()
        ->map(function ($gmtdlvtoembqty){
            $gmtdlvtoembqty->ship_date=date('d-m-Y',strtotime($gmtdlvtoembqty->ship_date));
            return $gmtdlvtoembqty;
        });
        $podata=array();
        $colordata=array();
        
        foreach($gmtdlvtoembqty as $row){
            $podata[$row->sale_order_id]['sale_order_no']=$row->sale_order_no;
            $podata[$row->sale_order_id]['ship_date']=$row->ship_date;
            $podata[$row->sale_order_id]['style_ref']=$row->style_ref;
            $podata[$row->sale_order_id]['buyer_name']=$row->buyer_name;
            $colordata[$row->color_id]=$row->color_name;
        } 

        $saved=$gmtdlvtoembqty->groupBy(['sale_order_id','color_id',]);
        
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
        $challan=str_pad($prodgmtdlvtoemb['id'],10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
        /* $pdf->SetY(10);
        $txt = $prodgmtdlvtoemb['screenPrint']->supplier_name;
        $pdf->Write(0, 'Challan', '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetY(5);
        $pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTitle($txt); */
        $pdf->SetFont('helvetica', '', 8);
        $view= \View::make('Defult.Production.Garments.DeliveryToEmbPdf',['prodgmtdlvtoemb'=>$prodgmtdlvtoemb,'saved'=>$saved,'podata'=>$podata,'colordata'=>$colordata]);
        $html_content=$view->render();
        $pdf->SetY(42);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/DeliveryToEmbPdf.pdf';
        $pdf->output($filename);
        exit();
    }

}