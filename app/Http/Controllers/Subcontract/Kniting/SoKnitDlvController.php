<?php

namespace App\Http\Controllers\Subcontract\Kniting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitDlvRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitPoItemRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRcvRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Purchase\PoKnitServiceRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitPoRepository;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRefRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Numbertowords;
use App\Library\Template;
use App\Http\Requests\Subcontract\Kniting\SoKnitDlvRequest;

class SoKnitDlvController extends Controller {

    private $soknitdlv;
    private $soknit;
    private $soknitpoitem;
    private $soknityarnrcv;
    private $company;
    private $buyer;
    private $uom;
    private $gmtspart;
    private $poknitservice;
    private $poknitpo;
    private $poknitref;
    private $currency;
    private $autoyarn;
    private $itemaccount;

    public function __construct(
        SoKnitDlvRepository $soknitdlv,
        SoKnitRepository $soknit,
        SoKnitPoItemRepository $soknitpoitem,
        BuyerRepository $buyer,
        CompanyRepository $company, 
        UomRepository $uom, 
        SoKnitYarnRcvRepository $soknityarnrcv, 
        GmtspartRepository $gmtspart,
        PoKnitServiceRepository $poknitservice,
        SoKnitPoRepository $poknitpo,
        SoKnitRefRepository $poknitref,
        CurrencyRepository $currency,
        AutoyarnRepository $autoyarn,
        ItemAccountRepository $itemaccount
        ) {
        $this->soknitdlv = $soknitdlv;
        $this->soknit = $soknit;
        $this->soknitpoitem = $soknitpoitem;
        $this->soknityarnrcv = $soknityarnrcv;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->uom = $uom;
        $this->gmtspart = $gmtspart;
        $this->poknitservice = $poknitservice;
        $this->poknitpo = $poknitpo;
        $this->poknitref = $poknitref;
        $this->currency = $currency;
        $this->autoyarn = $autoyarn;
        $this->itemaccount = $itemaccount;
         
        $this->middleware('auth');
        $this->middleware('permission:view.soknitdlvs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.soknitdlvs', ['only' => ['store']]);
        $this->middleware('permission:edit.soknitdlvs',   ['only' => ['update']]);
        $this->middleware('permission:delete.soknitdlvs', ['only' => ['destroy']]);
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       return response()->json(
          $this->soknitdlv
          
          ->leftJoin('buyers', function($join)  {
            $join->on('so_knit_dlvs.buyer_id', '=', 'buyers.id');
          })
          ->leftJoin('companies', function($join)  {
            $join->on('so_knit_dlvs.company_id', '=', 'companies.id');
          })
          ->orderBy('so_knit_dlvs.id','desc')
          ->get([
            'so_knit_dlvs.*',
            'buyers.name as buyer_id',
            'companies.name as company_id'
          ])
          ->map(function($rows){
            $rows->issue_date=date('d-M-Y',strtotime($rows->issue_date));
            return $rows;
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
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        return Template::LoadView('Subcontract.Kniting.SoKnitDlv',['company'=>$company,'buyer'=>$buyer,'uom'=>$uom,'fabriclooks'=>$fabriclooks,'fabricshape'=>$fabricshape,'gmtspart'=>$gmtspart,'currency'=>$currency]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoKnitDlvRequest $request) {
        $max=$this->soknitdlv
        ->where([['company_id','=',$request->company_id]])
        ->max('issue_no');
        $issue_no=$max+1;
        $request->request->add(['issue_no' => $issue_no]);
                                 
        $soknitdlv=$this->soknitdlv->create($request->except(['id']));
        if($soknitdlv){
          return response()->json(array('success' => true,'id' =>  $soknitdlv->id,'message' => 'Save Successfully'),200);
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
        $soknitdlv = $this->soknitdlv
        
        ->where([['so_knit_dlvs.id','=',$id]])
        ->get([
          'so_knit_dlvs.*',
        ])
        ->first();
        $soknitdlv->issue_date=date('Y-m-d',strtotime($soknitdlv->issue_date));
        $row ['fromData'] = $soknitdlv;
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
    public function update(SoKnitDlvRequest $request, $id) {
        $master=$this->soknitdlv->find($id);
        if($master->approved_by && $master->approved_at && \Auth::user()->level() < 5){
        return response()->json(array('success' => false,'id' => $id,'message' => 'It is Approved,So Update Not Possible'),200);
        }
        $soknitdlv=$this->soknitdlv->update($id,$request->except(['id','issue_no','currency_id','company_id','buyer_id']));
        if($soknitdlv){
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
        if($this->soknitdlv->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getDlvChalan()
    {
      $id=request('id',0);
      $master=$this->soknitdlv
      ->join('companies',function($join){
      $join->on('companies.id','=','so_knit_dlvs.company_id');
      })
      ->join('buyers',function($join){
      $join->on('buyers.id','=','so_knit_dlvs.buyer_id');
      })
      ->join('users',function($join){
      $join->on('users.id','=','so_knit_dlvs.created_by');
      })
      ->leftJoin('employee_h_rs',function($join){
      $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->leftJoin('users as approveby',function($join){
      $join->on('approveby.id','=','so_knit_dlvs.approved_by');
      })
      ->where([['so_knit_dlvs.id','=',$id]])
      ->get([
        'so_knit_dlvs.*',
        'so_knit_dlvs.remarks as master_remarks',
        'companies.name as company_name',
        'companies.logo as logo',
        'companies.address as company_address',
        'buyers.name as buyer_name',
        'users.name as user_name',
        'employee_h_rs.contact',
        'approveby.name as approved_by_name'
      ])
      ->first();
      $master->issue_date=date('d-M-Y',strtotime($master->issue_date));
      if($master->approved_by<=0 && $master->approved_at=='' && \Auth::user()->level() < 5){
        echo "It is not Approved, Please approve before Print";
        die;
      }


        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
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
        $desDropdown[$key]=$val.",".implode(",",$fabricCompositionArr[$key]);
        }
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');

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
        /*->leftJoin('smp_cost_yarns',function($join){
        $join->on('smp_cost_yarns.item_account_id','=','item_accounts.id');
        })*/
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

        $yarn=$this->soknitdlv
        ->join('so_knit_dlv_items',function($join){
        $join->on('so_knit_dlv_items.so_knit_dlv_id','=','so_knit_dlvs.id');
        })
        ->join('so_knit_dlv_item_yarns',function($join){
        $join->on('so_knit_dlv_item_yarns.so_knit_dlv_item_id','=','so_knit_dlv_items.id');
        })
        ->join('so_knit_yarn_rcv_items',function($join){
        $join->on('so_knit_yarn_rcv_items.id','=','so_knit_dlv_item_yarns.so_knit_yarn_rcv_item_id');
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
        ->leftJoin('colors', function($join)  {
        $join->on('colors.id', '=', 'so_knit_yarn_rcv_items.color_id');
        })
        ->where([['so_knit_dlvs.id','=',$id]])
        ->orderBy('so_knit_yarn_rcv_items.id')
        ->get([
        'so_knit_yarn_rcv_items.*',
        'yarncounts.count',
        'yarntypes.name as yarn_type',
        'yarncounts.symbol',
        'colors.name as color_name',
        'so_knit_dlv_items.id as so_knit_dlv_item_id',
        'so_knit_dlv_item_yarns.qty'
        ])
        ->map(function($yarn) use($yarnDropdown){
        $yarn->yarn_desc=$yarn->count."/".$yarn->symbol.", ". $yarnDropdown[$yarn->item_account_id].", ".$yarn->yarn_type.", ".$yarn->lot.", ".$yarn->color_name.", ".$yarn->supplier_name.", Qty:".$yarn->qty;
        return $yarn;
        });
        $yarn_arr=[];
        foreach($yarn as $yarn_r)
        {
           $yarn_arr[$yarn_r->so_knit_dlv_item_id][]=$yarn_r->yarn_desc;
        }


        $rows=$this->soknitdlv
        ->join('so_knit_dlv_items',function($join){
        $join->on('so_knit_dlv_items.so_knit_dlv_id','=','so_knit_dlvs.id');
        })
        ->join('so_knit_refs',function($join){
        $join->on('so_knit_refs.id','=','so_knit_dlv_items.so_knit_ref_id');
        })
         ->join('so_knits',function($join){
        $join->on('so_knit_refs.so_knit_id','=','so_knits.id');
        })
        ->leftJoin('so_knit_items',function($join){
        $join->on('so_knit_items.so_knit_ref_id','=','so_knit_refs.id');
        })
        ->leftJoin('buyers',function($join){
        $join->on('buyers.id','=','so_knit_items.gmt_buyer');
        })
        ->leftJoin('uoms',function($join){
        $join->on('uoms.id','=','so_knit_items.uom_id');
        })
        ->leftJoin('colors',function($join){
        $join->on('colors.id','=','so_knit_items.fabric_color_id');
        })
        ->where([['so_knit_dlvs.id','=',$id]])
        ->selectRaw('
         so_knits.sales_order_no,
        so_knit_refs.id as so_knit_ref_id,
        so_knit_refs.so_knit_id,
        so_knit_items.autoyarn_id,
        so_knit_items.fabric_look_id,
        so_knit_items.fabric_shape_id,
        so_knit_items.gmtspart_id,
        so_knit_items.gsm_weight,
        so_knit_items.dia,
        so_knit_items.measurment,
        so_knit_dlv_items.id,
        so_knit_dlv_items.qty,
        so_knit_dlv_items.rate,
        so_knit_dlv_items.amount,
        so_knit_dlv_items.no_of_roll,
        so_knit_dlv_items.remarks,
        so_knit_items.gmt_style_ref,
        so_knit_items.gmt_sale_order_no,
        buyers.name as buyer_name,
        uoms.code as uom_name,
        colors.name as fabric_color
        '
        )
        ->orderBy('so_knit_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$fabricDescriptionArr,$gmtspart,$fabriclooks,$fabricshape,$uom,$yarn_arr){
        $rows->fabrication=$desDropdown[$rows->autoyarn_id];
        $rows->fabricshape=$fabricshape[$rows->fabric_shape_id];
        $rows->fabriclooks=$fabriclooks[$rows->fabric_look_id];
        $rows->gmtspart=$gmtspart[$rows->gmtspart_id];
        $rows->constructions_name=$fabricDescriptionArr[$rows->autoyarn_id];
        $rows->yarn_used=isset($yarn_arr[$rows->id])? implode(',',$yarn_arr[$rows->id]):'';

        $rows->qty=$rows->qty;
        $rows->amount=$rows->amount;
        return $rows;
        });

      
      $data['master']    =$master;
      $data['details']    =$rows;

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
        $challan=str_pad($data['master']->id,10,0,STR_PAD_LEFT ) ;


          $pdf = new \Pdf('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
          $pdf->SetPrintHeader(true);
          $pdf->SetPrintFooter(true);
          $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
          $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
          $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
          $pdf->SetMargins(PDF_MARGIN_LEFT, '42', PDF_MARGIN_RIGHT);
          $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
          $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
          $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
          $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
          $header['logo']=$master->logo;
          $header['address']=$master->company_address;
          $header['title']='Subcontract Knit Fabric Delivery Challan  / Gate Pass';
          $header['barcodestyle']= $barcodestyle;
          $header['barcodeno']= $challan;
          $pdf->setCustomHeader($header);
          $pdf->SetFont('helvetica', 'B', 12);
          $pdf->AddPage();
        //$pdf->SetY(10);
        //$image_file ='images/logo/'.$rows->logo;
        //$pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        //$pdf->SetY(13);
        //$pdf->SetFont('helvetica', 'N', 8);
        //$pdf->Text(115, 12, $rows->company_address);

        /*$pdf->SetY(3);
        $pdf->SetX(190);
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');*/
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTitle('Subcontract Knit Fabric Delivery Challan  / Gate Pass');
        $view= \View::make('Defult.Subcontract.Kniting.SoKnitDlvDcPdf',['data'=>$data]);

        $html_content=$view->render();
        $pdf->SetY(42);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/SoKnitDlvDcPdf.pdf';
        $pdf->output($filename);
    }

    public function getBill()
    {
      $id=request('id',0);
      $master=$this->soknitdlv
      ->join('companies',function($join){
      $join->on('companies.id','=','so_knit_dlvs.company_id');
      })
      ->join('buyers',function($join){
      $join->on('buyers.id','=','so_knit_dlvs.buyer_id');
      })
      ->join('users',function($join){
      $join->on('users.id','=','so_knit_dlvs.created_by');
      })
      ->join('currencies',function($join){
      $join->on('currencies.id','=','so_knit_dlvs.currency_id');
      })
      ->leftJoin('employee_h_rs',function($join){
      $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->where([['so_knit_dlvs.id','=',$id]])
      ->get([
        'so_knit_dlvs.*',
        'so_knit_dlvs.remarks as master_remarks',
        'companies.name as company_name',
        'companies.logo as logo',
        'companies.address as company_address',
        'buyers.name as buyer_name',
        'users.name as user_name',
        'employee_h_rs.contact',
        'currencies.code as currency_name',
        'currencies.hundreds_name',
      ])
      ->first();
      $master->issue_date=date('d-M-Y',strtotime($master->issue_date));

      if($master->approved_by<=0 && $master->approved_at=='' && \Auth::user()->level() < 5){
        echo "It is not Approved, Please approve before Print";
        die;
      }

      $accounts =collect(\DB::select('
        select 
        acc_trans_sales.buyer_id,
        sum(acc_trans_sales.amount) as amount 
        from acc_trans_prnts
        join acc_trans_sales on acc_trans_sales.acc_trans_prnt_id=acc_trans_prnts.id
        join acc_chart_ctrl_heads on acc_chart_ctrl_heads.id=acc_trans_sales.acc_chart_ctrl_head_id
        where acc_trans_sales.buyer_id=? and 
        acc_trans_prnts.company_id=? and
        acc_trans_sales.deleted_at is null and 
        acc_trans_prnts.deleted_at is null and 
        acc_chart_ctrl_heads.control_name_id=30
        group by acc_trans_sales.buyer_id', [$master->buyer_id,$master->company_id]))
        ->first();

        $fabrcvbal =collect(\DB::select('
        select sum(m.amount) as amount from (select 
        so_knit_yarn_rcv_items.id,
        so_knit_yarn_rcv_items.qty as qty ,
        so_knit_yarn_rcv_items.rate as rate,
        used.qty as used_qty,
        returned.qty as returned_qty,
        (so_knit_yarn_rcv_items.qty-used.qty) as bal_qty,
        CASE WHEN used.qty is not null
        then
        (sum(so_knit_yarn_rcv_items.qty) - used.qty ) * so_knit_yarn_rcv_items.rate 
        ELSE sum(so_knit_yarn_rcv_items.qty) * so_knit_yarn_rcv_items.rate 
        END as bal_amount,

        CASE 
        WHEN used.qty is not null and returned.qty is not null THEN
        ((sum(so_knit_yarn_rcv_items.qty) - (used.qty+returned.qty) )*avg(so_knit_yarn_rcv_items.rate)) 
        WHEN used.qty is not null and returned.qty is null THEN
        ((sum(so_knit_yarn_rcv_items.qty) - used.qty )*avg(so_knit_yarn_rcv_items.rate))
        WHEN used.qty is null and returned.qty is not null THEN
        ((sum(so_knit_yarn_rcv_items.qty) - returned.qty )*avg(so_knit_yarn_rcv_items.rate))
        ELSE sum(so_knit_yarn_rcv_items.qty)*avg(so_knit_yarn_rcv_items.rate) 
        END as amount

        from so_knits
        join so_knit_yarn_rcvs on so_knit_yarn_rcvs.so_knit_id=so_knits.id
        join so_knit_yarn_rcv_items on so_knit_yarn_rcv_items.so_knit_yarn_rcv_id=so_knit_yarn_rcvs.id
        left join (
        select
        so_knit_dlv_item_yarns.so_knit_yarn_rcv_item_id,
        sum(so_knit_dlv_item_yarns.qty) as qty 
        from so_knit_dlvs
        join so_knit_dlv_items on so_knit_dlv_items.so_knit_dlv_id=so_knit_dlvs.id
        join so_knit_dlv_item_yarns on so_knit_dlv_item_yarns.so_knit_dlv_item_id=so_knit_dlv_items.id
        where so_knit_dlvs.buyer_id=? and 
        so_knit_dlvs.company_id=? and
        so_knit_dlvs.deleted_at is null and 
        so_knit_dlv_items.deleted_at is null and
        so_knit_dlv_item_yarns.deleted_at is null 
        group by 
        so_knit_dlv_item_yarns.so_knit_yarn_rcv_item_id
        ) used on used.so_knit_yarn_rcv_item_id=so_knit_yarn_rcv_items.id

        left join (
          select
          so_knit_yarn_rtn_items.so_knit_yarn_rcv_item_id,
          sum(so_knit_yarn_rtn_items.qty) as qty,
          sum(so_knit_yarn_rtn_items.amount) as amount
          from 
          so_knit_yarn_rtns
          join so_knit_yarn_rtn_items on so_knit_yarn_rtns.id=so_knit_yarn_rtn_items.so_knit_yarn_rtn_id
          where 
          so_knit_yarn_rtns.buyer_id=? and 
          so_knit_yarn_rtns.company_id=?
          and so_knit_yarn_rtns.deleted_at is null
          and so_knit_yarn_rtn_items.deleted_at is null
          group by 
          so_knit_yarn_rtn_items.so_knit_yarn_rcv_item_id
          ) returned on returned.so_knit_yarn_rcv_item_id=so_knit_yarn_rcv_items.id


        where so_knits.buyer_id=? and 
        so_knits.company_id=? and
        so_knits.deleted_at is null and 
        so_knit_yarn_rcvs.deleted_at is null and 
        so_knit_yarn_rcv_items.deleted_at is null and
        so_knit_yarn_rcv_items.qty >0 and
        so_knit_yarn_rcv_items.rate >0 
        group by 
        so_knit_yarn_rcv_items.id,
        so_knit_yarn_rcv_items.qty ,
        so_knit_yarn_rcv_items.rate,
        used.qty,returned.qty) m', [$master->buyer_id,$master->company_id,$master->buyer_id,$master->company_id,$master->buyer_id,$master->company_id]))
        ->first();

        $currentBill =collect(\DB::select('
        select so_knit_dlvs.id,so_knit_dlvs.currency_id,sum(so_knit_dlv_items.amount) as amount 
        from so_knit_dlvs
        join so_knit_dlv_items on so_knit_dlv_items.so_knit_dlv_id=so_knit_dlvs.id
        where 
        so_knit_dlvs.id=? and
        so_knit_dlvs.deleted_at is null and 
        so_knit_dlv_items.deleted_at is null 
        group by so_knit_dlvs.id,so_knit_dlvs.currency_id', [$id]))
        ->first();
        
        if(!$currentBill)
        {
	        echo "Item Not Found";
	        die;
        }

        $currentbillamount=0;

        if($currentBill->currency_id==1){
             $currentbillamount=$currentBill->amount*82;
        }else{
            $currentbillamount=$currentBill->amount;
        }

        //$receivable=$accounts->amount+$currentbillamount;
        $receivable=0;
        if($accounts){
            $receivable=$accounts->amount+$currentbillamount;
        }
        else{
            $receivable=0;
        }

        $bal=$fabrcvbal->amount-$receivable;
        $master->receivable=$receivable;
        $master->fabrcvbal=$fabrcvbal->amount;
        $master->bal=$bal;


        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
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
        $desDropdown[$key]=$val.",".implode(",",$fabricCompositionArr[$key]);
        }
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');


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
        /*->leftJoin('smp_cost_yarns',function($join){
        $join->on('smp_cost_yarns.item_account_id','=','item_accounts.id');
        })*/
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

        $yarn=$this->soknitdlv
        ->join('so_knit_dlv_items',function($join){
        $join->on('so_knit_dlv_items.so_knit_dlv_id','=','so_knit_dlvs.id');
        })
        ->join('so_knit_dlv_item_yarns',function($join){
        $join->on('so_knit_dlv_item_yarns.so_knit_dlv_item_id','=','so_knit_dlv_items.id');
        })
        ->join('so_knit_yarn_rcv_items',function($join){
        $join->on('so_knit_yarn_rcv_items.id','=','so_knit_dlv_item_yarns.so_knit_yarn_rcv_item_id');
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
        ->leftJoin('colors', function($join)  {
        $join->on('colors.id', '=', 'so_knit_yarn_rcv_items.color_id');
        })
        ->where([['so_knit_dlvs.id','=',$id]])
        ->orderBy('so_knit_yarn_rcv_items.id')
        ->get([
        'so_knit_yarn_rcv_items.*',
        'yarncounts.count',
        'yarntypes.name as yarn_type',
        'yarncounts.symbol',
        'colors.name as color_name',
        'so_knit_dlv_items.id as so_knit_dlv_item_id',
        'so_knit_dlv_item_yarns.qty'
        ])
        ->map(function($yarn) use($yarnDropdown){
        $yarn->yarn_desc=$yarn->count."/".$yarn->symbol.", ". $yarnDropdown[$yarn->item_account_id].", ".$yarn->yarn_type.", ".$yarn->lot.", ".$yarn->color_name.", ".$yarn->supplier_name.", Qty:".$yarn->qty;
        return $yarn;
        });
        $yarn_arr=[];
        foreach($yarn as $yarn_r)
        {
           $yarn_arr[$yarn_r->so_knit_dlv_item_id][]=$yarn_r->yarn_desc;
        }



        $rows=$this->soknitdlv
        ->join('so_knit_dlv_items',function($join){
        $join->on('so_knit_dlv_items.so_knit_dlv_id','=','so_knit_dlvs.id');
        })
        ->join('so_knit_refs',function($join){
        $join->on('so_knit_refs.id','=','so_knit_dlv_items.so_knit_ref_id');
        })
         ->join('so_knits',function($join){
        $join->on('so_knit_refs.so_knit_id','=','so_knits.id');
        })
        ->leftJoin('so_knit_items',function($join){
        $join->on('so_knit_items.so_knit_ref_id','=','so_knit_refs.id');
        })
        ->leftJoin('buyers',function($join){
        $join->on('buyers.id','=','so_knit_items.gmt_buyer');
        })
        ->leftJoin('uoms',function($join){
        $join->on('uoms.id','=','so_knit_items.uom_id');
        })
        ->leftJoin('colors',function($join){
        $join->on('colors.id','=','so_knit_items.fabric_color_id');
        })
        ->where([['so_knit_dlvs.id','=',$id]])
        ->selectRaw('
         so_knits.sales_order_no,
        so_knit_refs.id as so_knit_ref_id,
        so_knit_refs.so_knit_id,
        so_knit_items.autoyarn_id,
        so_knit_items.fabric_look_id,
        so_knit_items.fabric_shape_id,
        so_knit_items.gmtspart_id,
        so_knit_items.gsm_weight,
        so_knit_items.dia,
        so_knit_items.measurment,
        so_knit_dlv_items.id,
        so_knit_dlv_items.qty,
        so_knit_dlv_items.rate,
        so_knit_dlv_items.amount,
        so_knit_dlv_items.no_of_roll,
        so_knit_dlv_items.remarks,
        so_knit_items.gmt_style_ref,
        so_knit_items.gmt_sale_order_no,
        buyers.name as buyer_name,
        uoms.code as uom_name,
        colors.name as fabric_color
        '
        )
        ->orderBy('so_knit_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$fabricDescriptionArr,$gmtspart,$fabriclooks,$fabricshape,$uom,$yarn_arr){
        $rows->fabrication=$desDropdown[$rows->autoyarn_id];
        $rows->fabricshape=$fabricshape[$rows->fabric_shape_id];
        $rows->fabriclooks=$fabriclooks[$rows->fabric_look_id];
        $rows->gmtspart=$gmtspart[$rows->gmtspart_id];
        $rows->constructions_name=$fabricDescriptionArr[$rows->autoyarn_id];
        $rows->yarn_used=isset($yarn_arr[$rows->id])? implode(',',$yarn_arr[$rows->id]):'';

        $rows->qty=$rows->qty;
        $rows->amount=$rows->amount;
        return $rows;
        });
        $amount=$rows->sum('amount');
        $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$master->currency_name,$master->hundreds_name);

        $master->inword=$inword;

      
      $data['master']    =$master;
      $data['details']    =$rows;

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
        $challan=str_pad($data['master']->id,10,0,STR_PAD_LEFT ) ;


          $pdf = new \Pdf('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
          $pdf->SetPrintHeader(true);
          $pdf->SetPrintFooter(true);
          $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
          $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
          $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
          $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
          $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
          $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
          $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
          $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
          $header['logo']=$master->logo;
          $header['address']=$master->company_address;
          $header['title']='Subcontract Knitting Bill';
          $header['barcodestyle']= $barcodestyle;
          $header['barcodeno']= $challan;
          $pdf->setCustomHeader($header);
          $pdf->SetFont('helvetica', 'B', 12);
          $pdf->AddPage();
        //$pdf->SetY(10);
        //$image_file ='images/logo/'.$rows->logo;
        //$pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        //$pdf->SetY(13);
        //$pdf->SetFont('helvetica', 'N', 8);
        //$pdf->Text(115, 12, $rows->company_address);

        /*$pdf->SetY(3);
        $pdf->SetX(190);
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');*/
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTitle('Subcontract Knitting Bill');
        $view= \View::make('Defult.Subcontract.Kniting.SoKnitBillPdf',['data'=>$data]);

        $html_content=$view->render();
        $pdf->SetY(42);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/SoKnitBillPdf.pdf';
        $pdf->output($filename);
    }
}