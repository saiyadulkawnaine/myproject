<?php

namespace App\Http\Controllers\Subcontract\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRtnRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\AOP\SoAopFabricRtnRequest;

class SoAopFabricRtnController extends Controller {

    private $soaopfabricrtn;
    private $company;
    private $buyer;
    private $uom;
    private $color;
    private $autoyarn;
    private $gmtspart;
    private $colorrange;

    public function __construct(
        SoAopFabricRtnRepository $soaopfabricrtn,
         CompanyRepository $company,
        BuyerRepository $buyer,
        UomRepository $uom,
        ColorRepository $color,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        ColorrangeRepository $colorrange
         
        ) {
        $this->soaopfabricrtn = $soaopfabricrtn;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->uom = $uom;
        $this->color = $color;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->colorrange = $colorrange;
         
        $this->middleware('auth');
        // $this->middleware('permission:view.soaopfabricrtns',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.soaopfabricrtns', ['only' => ['store']]);
        // $this->middleware('permission:edit.soaopfabricrtns',   ['only' => ['update']]);
        // $this->middleware('permission:delete.soaopfabricrtns', ['only' => ['destroy']]);
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       return response()->json(
          $this->soaopfabricrtn        
          ->leftJoin('buyers', function($join)  {
            $join->on('so_aop_fabric_rtns.buyer_id', '=', 'buyers.id');
          })
          ->leftJoin('companies', function($join)  {
            $join->on('so_aop_fabric_rtns.company_id', '=', 'companies.id');
          })
          ->orderBy('so_aop_fabric_rtns.id','desc')
          ->get([
            'so_aop_fabric_rtns.*',
            'buyers.name as buyer_id',
            'companies.name as company_id'
          ])
          ->map(function($soaopfabricrtn){
            $soaopfabricrtn->return_date=date('d-M-Y',strtotime($soaopfabricrtn->return_date));
            return $soaopfabricrtn;
          })
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'','');
        return Template::LoadView('Subcontract.AOP.SoAopFabricRtn',['company'=>$company,'buyer'=>$buyer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoAopFabricRtnRequest $request) {
        $soaopfabricrtn=$this->soaopfabricrtn->create($request->except(['id']));

        if($soaopfabricrtn){
        return response()->json(array('success' => true,'id' =>  $soaopfabricrtn->id,'message' => 'Save Successfully'),200);
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
        $soaopfabricrtn = $this->soaopfabricrtn
        ->where([['so_aop_fabric_rtns.id','=',$id]])
        ->get([
          'so_aop_fabric_rtns.*',
          'so_aop_fabric_rtns.company_id',
          'so_aop_fabric_rtns.buyer_id',
          
        ])
        ->first();
        $soaopfabricrtn->return_date=date('Y-m-d',strtotime($soaopfabricrtn->return_date));
        $row ['fromData'] = $soaopfabricrtn;
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
    public function update(SoAopFabricRtnRequest $request, $id) {
        $soaopfabricrtn=$this->soaopfabricrtn->update($id,$request->except(['id']));
        if($soaopfabricrtn){
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
        if($this->soaopfabricrtn->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getPdf()
    {
      $id=request('id',0);

      $menu=array_prepend(config('bprs.menu'),'-Select-','');  

      $rows=$this->soaopfabricrtn        
      ->leftJoin('buyers', function($join)  {
        $join->on('so_aop_fabric_rtns.buyer_id', '=', 'buyers.id');
      })
      ->leftJoin('companies', function($join)  {
        $join->on('so_aop_fabric_rtns.company_id', '=', 'companies.id');
      })
      ->join('users',function($join){
        $join->on('users.id','=','so_aop_fabric_rtns.created_by');
      })
      ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->where([['so_aop_fabric_rtns.id','=',$id]])
      ->get([
        'so_aop_fabric_rtns.*',
        'so_aop_fabric_rtns.remarks as master_remark',
        'buyers.name as customer_name',
        'buyers.address as customer_address',
        'companies.name as company_name',
        'companies.logo as logo',
        'companies.address as company_address',
        'users.name as user_name',
        'employee_h_rs.contact'
      ])
      ->first();
        
      $rows->return_date=date('d-M-Y',strtotime($rows->return_date));
      $rows->contact_detail=$rows->contact_person.','.$rows->designation.','.$rows->email;

      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
      $color=array_prepend(array_pluck($this->color->get(),'name','id'),'-Select-','');
      
      $autoyarn=$this->autoyarn
      ->join('autoyarnratios', function($join)  {
        $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
      })
      ->join('constructions', function($join)  {
        $join->on('autoyarns.construction_id', '=', 'constructions.id');
      })
      ->join('compositions',function($join){
        $join->on('compositions.id','=','autoyarnratios.composition_id');
      })
      ->when(request('construction_name'), function ($q) {
        return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
      })
      ->when(request('composition_name'), function ($q) {
        return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
      })
      ->orderBy('autoyarns.id','desc')
      ->get([
        'autoyarns.*',
        'constructions.name',
        'compositions.name as composition_name',
        'autoyarnratios.ratio'
      ]);

      $fabricDescriptionArr=array();
      $fabricCompositionArr=array();
      foreach($autoyarn as $row){
        $fabricDescriptionArr[$row->id]=$row->name;
        $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
      }
      $desDropdown=array();
      foreach($fabricDescriptionArr as $key=>$val){
        $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
      }
      $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
      $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
      $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

      $soaopfabricrtnitem=$this->soaopfabricrtn
      ->join('so_aop_fabric_rtn_items',function($join){
        $join->on('so_aop_fabric_rtn_items.so_aop_fabric_rtn_id','=','so_aop_fabric_rtns.id');
      })
      ->join('so_aop_refs',function($join){
        $join->on('so_aop_refs.id','=','so_aop_fabric_rtn_items.so_aop_ref_id');
      })
      ->join('so_aops',function($join){
        $join->on('so_aops.id','=','so_aop_refs.so_aop_id');
      })
      ->join('so_aop_items',function($join){
          $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
      })
      ->leftJoin('uoms',function($join){
        $join->on('uoms.id','=','so_aop_items.uom_id');
      })
      ->join(\DB::raw("(
        SELECT 
        so_aop_fabric_rcv_items.so_aop_ref_id,
        sum(so_aop_fabric_rcv_items.qty) as qty,
        avg(so_aop_fabric_rcv_items.rate) as rate,
        sum(so_aop_fabric_rcv_items.amount) as amount 
        FROM so_aop_fabric_rcv_items 
        group by so_aop_fabric_rcv_items.so_aop_ref_id) soaopfabricrcv"), "soaopfabricrcv.so_aop_ref_id", "=", "so_aop_refs.id")
      ->where([['so_aop_fabric_rtns.id','=', $id]])
      ->selectRaw('
          so_aops.sales_order_no,
          so_aop_refs.so_aop_id,
          so_aop_items.autoyarn_id,
          so_aop_items.fabric_look_id,
          so_aop_items.fabric_shape_id,
          so_aop_items.gmtspart_id,
          so_aop_items.gsm_weight,
          so_aop_items.fabric_color_id,
          so_aop_items.colorrange_id,          
          so_aop_items.gmt_style_ref,
          so_aop_items.gmt_sale_order_no,
          so_aop_items.uom_id,
          uoms.code as uom_code,
          so_aop_fabric_rtn_items.*,
          soaopfabricrcv.rate,
          so_aop_fabric_rtn_items.qty*soaopfabricrcv.rate as amount
          '
        )
      ->orderBy('so_aop_items.id','desc')
      ->get()
      ->map(function($soaopfabricrtnitem) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$colorrange,$color){
        $soaopfabricrtnitem->fabrication=$soaopfabricrtnitem->autoyarn_id?$desDropdown[$soaopfabricrtnitem->autoyarn_id]:'';
        $soaopfabricrtnitem->gmtspart=$soaopfabricrtnitem->gmtspart_id?$gmtspart[$soaopfabricrtnitem->gmtspart_id]:'';
        $soaopfabricrtnitem->fabriclooks=$soaopfabricrtnitem->fabric_look_id?$fabriclooks[$soaopfabricrtnitem->fabric_look_id]:'';
        $soaopfabricrtnitem->fabricshape=$soaopfabricrtnitem->fabric_shape_id?$fabricshape[$soaopfabricrtnitem->fabric_shape_id]:'';

        $soaopfabricrtnitem->fabric_color=$soaopfabricrtnitem->fabric_color_id?$color[$soaopfabricrtnitem->fabric_color_id]:'';
        $soaopfabricrtnitem->colorrange_id=$soaopfabricrtnitem->colorrange_id?$colorrange[$soaopfabricrtnitem->colorrange_id]:'';
        $soaopfabricrtnitem->sale_order_no=$soaopfabricrtnitem->gmt_sale_order_no?$soaopfabricrtnitem->sales_order_no:'';
        //$soaopfabricrtnitem->qty=number_format($soaopfabricrtnitem->qty,2,'.',',');
        //$soaopfabricrtnitem->amount=number_format($soaopfabricrtnitem->amount,2,'.',','); 
        return $soaopfabricrtnitem;
      });

      $data['master']    =$rows;
      $data['details']   =$soaopfabricrtnitem;

      $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetPrintHeader(false);
      $pdf->SetPrintFooter(false);
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->SetMargins(PDF_MARGIN_LEFT, '45', PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->AddPage();
      $pdf->SetY(10);
      //$txt = "Trim Purchase Order";
      //$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
      $image_file ='images/logo/'.$rows->logo;
      $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
      $pdf->SetY(13);
      $pdf->SetFont('helvetica', 'N', 8);
      //$pdf->Text(115, 12, $rows->company_address);
      $pdf->Cell(0, 40, $rows->company_address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
      //$pdf->Write(0, $rows->company_address, '', 0, 'C', true, 0, false, false, 0);

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
      $pdf->SetX(220);
      $challan=str_pad($data['master']->id,10,0,STR_PAD_LEFT ) ;
      $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');

      $pdf->SetY(36);
      $pdf->SetFont('helvetica', 'N', 14);
      $pdf->Write(0, 'Subcontract AOP Fabrics Return Challan/Gate Pass ', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 10);
      $pdf->SetTitle('Subcontract AOP Fabrics Return Challan/Gate Pass');
      $view= \View::make('Defult.Subcontract.AOP.SoAopFabricRtnPdf',['data'=>$data]);
      $html_content=$view->render();
      $pdf->SetY(45);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/SoAopFabricRtnPdf.pdf';
      $pdf->output($filename);
    }
}