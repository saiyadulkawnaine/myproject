<?php

namespace App\Http\Controllers\Subcontract\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitPoItemRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRtnRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Purchase\PoKnitServiceRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitPoRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRefRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Kniting\SoKnitYarnRtnRequest;

class SoKnitYarnRtnController extends Controller {

    private $soknit;
    private $soknitpoitem;
    private $soknityarnrtn;
    private $company;
    private $buyer;
    private $uom;
    private $gmtspart;
    private $poknitservice;
    private $poknitpo;
    private $poknitref;
    private $currency;
    private $itemaccount;

    public function __construct(
        SoKnitRepository $soknit,
        SoKnitPoItemRepository $soknitpoitem,
        BuyerRepository $buyer,
        CompanyRepository $company, 
        UomRepository $uom, 
        SoKnitYarnRtnRepository $soknityarnrtn, 
        GmtspartRepository $gmtspart,
        PoKnitServiceRepository $poknitservice,
        SoKnitPoRepository $poknitpo,
        SoKnitRefRepository $poknitref,
        CurrencyRepository $currency,
        ItemAccountRepository $itemaccount
        ) {
        $this->soknit = $soknit;
        $this->soknitpoitem = $soknitpoitem;
        $this->soknityarnrtn = $soknityarnrtn;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->uom = $uom;
        $this->gmtspart = $gmtspart;
        $this->poknitservice = $poknitservice;
        $this->poknitpo = $poknitpo;
        $this->poknitref = $poknitref;
        $this->currency = $currency;
        $this->itemaccount = $itemaccount;
         
        $this->middleware('auth');
        // $this->middleware('permission:view.soknityarnrtns',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.soknityarnrtns', ['only' => ['store']]);
        // $this->middleware('permission:edit.soknityarnrtns',   ['only' => ['update']]);
        // $this->middleware('permission:delete.soknityarnrtns', ['only' => ['destroy']]);
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       return response()->json(
          $this->soknityarnrtn        
          ->leftJoin('buyers', function($join)  {
            $join->on('so_knit_yarn_rtns.buyer_id', '=', 'buyers.id');
          })
          ->leftJoin('companies', function($join)  {
            $join->on('so_knit_yarn_rtns.company_id', '=', 'companies.id');
          })
          ->orderBy('so_knit_yarn_rtns.id','desc')
          ->get([
            'so_knit_yarn_rtns.*',
            'buyers.name as buyer_id',
            'companies.name as company_id'
          ])
          ->map(function($soknityarnrtn){
            $soknityarnrtn->return_date=date('d-M-Y',strtotime($soknityarnrtn->return_date));
            return $soknityarnrtn;
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
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        return Template::LoadView('Subcontract.Kniting.SoKnitYarnRtn',['company'=>$company,'buyer'=>$buyer,'uom'=>$uom,'fabriclooks'=>$fabriclooks,'fabricshape'=>$fabricshape,'gmtspart'=>$gmtspart,'currency'=>$currency]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoKnitYarnRtnRequest $request) {
        $soknityarnrtn=$this->soknityarnrtn->create($request->except(['id']));
        
        if($soknityarnrtn){
          return response()->json(array('success' => true,'id' =>  $soknityarnrtn->id,'message' => 'Save Successfully'),200);
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
        $soknityarnrtn = $this->soknityarnrtn
        ->where([['so_knit_yarn_rtns.id','=',$id]])
        ->get([
          'so_knit_yarn_rtns.*',
          'so_knit_yarn_rtns.company_id',
          'so_knit_yarn_rtns.buyer_id',
          
        ])
        ->first();
        $soknityarnrtn->return_date=date('Y-m-d',strtotime($soknityarnrtn->return_date));
        $row ['fromData'] = $soknityarnrtn;
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
    public function update(SoKnitYarnRtnRequest $request, $id) {
        $soknityarnrtn=$this->soknityarnrtn->update($id,$request->except(['id','sales_order_no']));
        if($soknityarnrtn){
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
        if($this->soknityarnrtn->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    

    public function getSo()
    {
        return response()->json(
          $soknit=$this->soknit
          ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'so_knits.company_id');
          })
          ->leftJoin('buyers', function($join)  {
            $join->on('so_knits.buyer_id', '=', 'buyers.id');
          })
          ->when(request('so_no'), function ($q) {
            return $q->where('sales_order_no', 'LIKE', "%".request('so_no', 0)."%");
          })
          //->where([['sales_order_no','=',request('so_no',0)]])
          ->get([
            'so_knits.*',
            'buyers.name as buyer_name',
            'companies.name as company_name'
          ])
        );

    }

    public function getPdf()
    {
      $id=request('id',0);

      $menu=array_prepend(config('bprs.menu'),'-Select-','');  

      $rows=$this->soknityarnrtn        
      ->leftJoin('buyers', function($join)  {
        $join->on('so_knit_yarn_rtns.buyer_id', '=', 'buyers.id');
      })
      ->leftJoin('companies', function($join)  {
        $join->on('so_knit_yarn_rtns.company_id', '=', 'companies.id');
      })
      ->join('users',function($join){
        $join->on('users.id','=','so_knit_yarn_rtns.created_by');
      })
      ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->where([['so_knit_yarn_rtns.id','=',$id]])
      ->get([
        'so_knit_yarn_rtns.*',
        'so_knit_yarn_rtns.remarks as master_remark',
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


        $yarnDescription=$this->itemaccount
        ->join('item_account_ratios',function($join){
        $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
        })
        ->join('yarncounts',function($join){
        $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->join('yarntypes',function($join){
        $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->join('itemclasses',function($join){
        $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->join('compositions',function($join){
        $join->on('compositions.id','=','item_account_ratios.composition_id');
        })
        ->join('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        ->where([['itemcategories.identity','=',1]])
        ->when(request('count_name'), function ($q) {
            return $q->where('yarncounts.count', 'LIKE', "%".request('count_name', 0)."%");
        })
        ->when(request('type_name'), function ($q) {
            return $q->where('yarntypes.name', 'LIKE', "%".request('type_name', 0)."%");
        })
        ->get([
          'item_accounts.id',
          'yarncounts.count',
          'yarncounts.symbol',
          'yarntypes.name as yarn_type',
          'itemclasses.name as itemclass_name',
          'compositions.name as composition_name',
          'item_account_ratios.ratio'
        ]);
        
        $itemaccountArr=array();
        $yarnCompositionArr=array();

        foreach($yarnDescription as $row){
          $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
          $itemaccountArr[$row->id]['yarn_type']=$row->yarn_type;
          $itemaccountArr[$row->id]['itemclass_name']=$row->itemclass_name;
          $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        
        $yarnDropdown=array();

        foreach($itemaccountArr as $key=>$value){
          $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
        }


        $soknityarnrtnitem=$this->soknityarnrtn
        ->leftJoin('so_knit_yarn_rtn_items', function($join)  {
            $join->on('so_knit_yarn_rtns.id', '=', 'so_knit_yarn_rtn_items.so_knit_yarn_rtn_id');
          })
        ->leftJoin('so_knits', function($join)  {
            $join->on('so_knits.id', '=', 'so_knit_yarn_rtn_items.so_knit_id');
          })
        ->leftJoin('so_knit_yarn_rcv_items', function($join)  {
           $join->on('so_knit_yarn_rtn_items.so_knit_yarn_rcv_item_id', '=', 'so_knit_yarn_rcv_items.id');
          })
        ->leftJoin('item_accounts', function($join)  {
            $join->on('item_accounts.id', '=', 'so_knit_yarn_rcv_items.item_account_id');
          })
        ->join('yarncounts',function($join){
           $join->on('yarncounts.id','=','item_accounts.yarncount_id');
          })
        ->join('yarntypes',function($join){
           $join->on('yarntypes.id','=','item_accounts.yarntype_id');
          })
        ->leftJoin('itemclasses',function($join){
           $join->on('itemclasses.id','=','item_accounts.itemclass_id');
          })
          ->leftJoin('uoms', function($join)  {
            $join->on('uoms.id', '=', 'so_knit_yarn_rcv_items.uom_id');
          })
          ->leftJoin('colors', function($join)  {
            $join->on('colors.id', '=', 'so_knit_yarn_rcv_items.color_id');
          })
          ->where([['so_knit_yarn_rtns.id', '=', $id]])
          ->orderBy('so_knit_yarn_rtn_items.id','desc')
          ->get([
            'so_knit_yarn_rtn_items.*',
            'yarncounts.count as yarn_count',
            'yarntypes.name as yarn_type',
            'itemclasses.name as itemclass_name',
            'yarncounts.symbol',
            'colors.name as color_name',
            'uoms.code as uom_code',
            'so_knit_yarn_rcv_items.item_account_id',
            'so_knit_yarn_rcv_items.lot',
            'so_knit_yarn_rcv_items.supplier_name',
            'so_knit_yarn_rcv_items.rate',
            'so_knit_yarn_rcv_items.uom_id',
            'so_knits.sales_order_no as sale_order_no',
          ])
          ->map(function($soknityarnrtnitem) use($yarnDropdown){
            $soknityarnrtnitem->composition=$yarnDropdown[$soknityarnrtnitem->item_account_id];
            $soknityarnrtnitem->count=$soknityarnrtnitem->count."/".$soknityarnrtnitem->symbol;
            return $soknityarnrtnitem;
          });
      
      $data['master']    =$rows;
      $data['details']   =$soknityarnrtnitem;

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
      $pdf->SetX(200);
      $challan=str_pad($data['master']->id,10,0,STR_PAD_LEFT ) ;
      $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');

      $pdf->SetY(35);
      $pdf->SetFont('helvetica', 'N', 14);
      $pdf->Write(0, 'Subcontract Yarn Return Challan/Gate Pass ', '', 0, 'C', true, 0, false, false, 0);
      $pdf->SetFont('helvetica', '', 10);
      $pdf->SetTitle('Subcontract Yarn Return Challan/Gate Pass');
      $view= \View::make('Defult.Subcontract.Kniting.SoKnitYarnRtnPdf',['data'=>$data]);
      $html_content=$view->render();
      $pdf->SetY(42);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/SoKnitYarnRtnPdf.pdf';
      $pdf->output($filename);
    }
}