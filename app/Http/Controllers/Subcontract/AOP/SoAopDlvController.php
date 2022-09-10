<?php

namespace App\Http\Controllers\Subcontract\AOP;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\AOP\SoAopDlvRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRepository;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRcvRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Library\Numbertowords;
use App\Library\Template;
use App\Http\Requests\Subcontract\AOP\SoAopDlvRequest;

class SoAopDlvController extends Controller {

    private $soaopdlv;
    private $soaop;
    private $soaopfabricrcv;
    private $company;
    private $buyer;
    private $uom;
    private $currency;
    private $autoyarn;
    private $gmtspart;
    private $embelishmenttype;

    public function __construct(
        SoAopDlvRepository $soaopdlv,
        SoAopRepository $soaop,
        SoAopFabricRcvRepository $soaopfabricrcv, 
        CompanyRepository $company, 
        BuyerRepository $buyer,
        UomRepository $uom, 
        CurrencyRepository $currency,
        AutoyarnRepository $autoyarn,
        GmtspartRepository $gmtspart,
        EmbelishmentTypeRepository $embelishmenttype

        ) {
        $this->soaopdlv = $soaopdlv;
        $this->soaop = $soaop;
        $this->soaopfabricrcv = $soaopfabricrcv;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->uom = $uom;
        $this->currency = $currency;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->embelishmenttype = $embelishmenttype;
         
        $this->middleware('auth');
        $this->middleware('permission:view.soaopdlvs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.soaopdlvs', ['only' => ['store']]);
        $this->middleware('permission:edit.soaopdlvs',   ['only' => ['update']]);
        $this->middleware('permission:delete.soaopdlvs', ['only' => ['destroy']]);
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       return response()->json(
          $this->soaopdlv 
          ->join('buyers', function($join)  {
            $join->on('so_aop_dlvs.buyer_id', '=', 'buyers.id');
          })
          ->join('companies', function($join)  {
            $join->on('so_aop_dlvs.company_id', '=', 'companies.id');
          })
          ->orderBy('so_aop_dlvs.id','desc')
          ->take(500)
          ->get([
            'so_aop_dlvs.*',
            'buyers.name as buyer_name',
            'companies.name as company_name'
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
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        $billfor=array_prepend(config('bprs.billfor'),'-Select-','');
        return Template::LoadView('Subcontract.AOP.SoAopDlv',['company'=>$company,'buyer'=>$buyer,'uom'=>$uom,'currency'=>$currency,'billfor'=>$billfor]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoAopDlvRequest $request) {
        $max=$this->soaopdlv
        ->where([['company_id','=',$request->company_id]])
        ->max('issue_no');
        $issue_no=$max+1;
        $request->request->add(['issue_no' => $issue_no]);
        $soaopdlv=$this->soaopdlv->create($request->except(['id']));
        if($soaopdlv){
          return response()->json(array('success' => true,'id' =>  $soaopdlv->id,'message' => 'Save Successfully'),200);
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
        $soaopdlv = $this->soaopdlv
        
        ->where([['so_aop_dlvs.id','=',$id]])
        ->get([
          'so_aop_dlvs.*',
        ])
        ->first();
        $soaopdlv->issue_date=date('Y-m-d',strtotime($soaopdlv->issue_date));
        $row ['fromData'] = $soaopdlv;
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
    public function update(SoAopDlvRequest $request, $id) {
        $master=$this->soaopdlv->find($id);
        if($master->approved_by && $master->approved_at && \Auth::user()->level() < 5){
        return response()->json(array('success' => false,'id' => $id,'message' => 'It is Approved,So Update Not Possible'),200);
        }
        $soaopdlv=$this->soaopdlv->update($id,$request->except(['id','issue_no']));
        if($soaopdlv){
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
        if($this->soaopdlv->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getSoAopDlvList(){
        $rows=$this->soaopdlv 
        ->join('buyers', function($join)  {
            $join->on('so_aop_dlvs.buyer_id', '=', 'buyers.id');
        })
        ->join('companies', function($join)  {
            $join->on('so_aop_dlvs.company_id', '=', 'companies.id');
        })
        ->when(request('customer_id'), function ($q) {
            return $q->where('so_aop_dlvs.buyer_id', '=', request('customer_id', 0));
        })
        ->when(request('from_date'),function($q){
            return $q->where('so_aop_dlvs.issue_date','>=',request('from_date',0));
        })
        ->when(request('to_date'),function($q){
            return $q->where('so_aop_dlvs.issue_date','<=',request('to_date',0));
        })
        ->orderBy('so_aop_dlvs.id','desc')
        ->get([
            'so_aop_dlvs.*',
            'buyers.name as buyer_name',
            'companies.name as company_name'
        ])
        ->map(function($rows){
            $rows->issue_date=date('d-M-Y',strtotime($rows->issue_date));
            return $rows;
        });
        echo json_encode($rows);
    }

    public function getDlvChalan()
    {
      $id=request('id',0);
      $master=$this->soaopdlv
      ->join('companies',function($join){
      $join->on('companies.id','=','so_aop_dlvs.company_id');
      })
      ->join('buyers',function($join){
      $join->on('buyers.id','=','so_aop_dlvs.buyer_id');
      })
      ->join('users',function($join){
      $join->on('users.id','=','so_aop_dlvs.created_by');
      })
      ->leftJoin('employee_h_rs',function($join){
      $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->leftJoin('users as approveby',function($join){
      $join->on('approveby.id','=','so_aop_dlvs.approved_by');
      })
      ->where([['so_aop_dlvs.id','=',$id]])
      ->get([
        'so_aop_dlvs.*',
        'so_aop_dlvs.remarks as master_remarks',
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
        $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');



        $rows=$this->soaopdlv
        ->join('so_aop_dlv_items',function($join){
        $join->on('so_aop_dlv_items.so_aop_dlv_id','=','so_aop_dlvs.id');
        })
        ->join('so_aop_refs',function($join){
        $join->on('so_aop_refs.id','=','so_aop_dlv_items.so_aop_ref_id');
        })
         ->join('so_aops',function($join){
        $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
        })
        ->leftJoin('so_aop_items',function($join){
        $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
        })
        ->leftJoin('buyers',function($join){
        $join->on('buyers.id','=','so_aop_items.gmt_buyer');
        })
        ->leftJoin('uoms',function($join){
        $join->on('uoms.id','=','so_aop_items.uom_id');
        })
        ->leftJoin('colors',function($join){
        $join->on('colors.id','=','so_aop_items.fabric_color_id');
        })
        ->where([['so_aop_dlvs.id','=',$id]])
        ->selectRaw('
        so_aops.sales_order_no,
        so_aop_refs.id as so_aop_ref_id,
        so_aop_refs.so_aop_id,
        so_aop_items.autoyarn_id,
        so_aop_items.fabric_look_id,
        so_aop_items.fabric_shape_id,
        so_aop_items.gmtspart_id,
        so_aop_items.gsm_weight,
        so_aop_dlv_items.id,
        so_aop_dlv_items.qty,
        so_aop_dlv_items.rate,
        so_aop_dlv_items.amount,
        so_aop_dlv_items.no_of_roll,
        so_aop_dlv_items.design_no,
        so_aop_dlv_items.design_name,
        so_aop_dlv_items.fin_dia,
        so_aop_dlv_items.fin_gsm,
        so_aop_dlv_items.grey_used,
        so_aop_dlv_items.remarks,
        so_aop_items.gmt_style_ref,
        so_aop_items.gmt_sale_order_no,
        so_aop_items.embelishment_type_id,
        so_aop_items.coverage,
        so_aop_items.impression,
        buyers.name as buyer_name,
        uoms.code as uom_name,
        colors.name as fabric_color
        '
        )
        ->orderBy('so_aop_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$fabricDescriptionArr,$gmtspart,$fabriclooks,$fabricshape,$uom,$aoptype){
        $rows->fabrication=$desDropdown[$rows->autoyarn_id];
        $rows->fabricshape=$fabricshape[$rows->fabric_shape_id];
        $rows->fabriclooks=$fabriclooks[$rows->fabric_look_id];
        $rows->gmtspart=$gmtspart[$rows->gmtspart_id];
        $rows->constructions_name=$fabricDescriptionArr[$rows->autoyarn_id];
        $rows->aoptype=$aoptype[$rows->embelishment_type_id];
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
        $pdf->SetMargins(PDF_MARGIN_LEFT, 42, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $header['logo']=$master->logo;
        $header['address']=$master->company_address;
        $header['title']='Subcontract Aop Fabric Delivery Challan  / Gate Pass';
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
        $pdf->SetTitle('Subcontract Aop Fabric Delivery Challan  / Gate Pass');
        $view= \View::make('Defult.Subcontract.AOP.SoAopDlvDcPdf',['data'=>$data]);

        $html_content=$view->render();
        $pdf->SetY(42);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/SoAopDlvDcPdf.pdf';
        $pdf->output($filename);
    }

    public function getBill()
    {
      $id=request('id',0);
      $master=$this->soaopdlv
      ->join('companies',function($join){
      $join->on('companies.id','=','so_aop_dlvs.company_id');
      })
      ->join('buyers',function($join){
      $join->on('buyers.id','=','so_aop_dlvs.buyer_id');
      })
      ->join('users',function($join){
      $join->on('users.id','=','so_aop_dlvs.created_by');
      })
      ->join('currencies',function($join){
      $join->on('currencies.id','=','so_aop_dlvs.currency_id');
      })
      ->leftJoin('employee_h_rs',function($join){
      $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->where([['so_aop_dlvs.id','=',$id]])
      ->get([
        'so_aop_dlvs.*',
        'so_aop_dlvs.remarks as master_remarks',
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
      select m.buyer_id, sum(m.amount) as amount from (select 
      so_aops.buyer_id,
      so_aop_fabric_rcv_items.so_aop_ref_id,
      sum(so_aop_fabric_rcv_items.qty) as qty ,
      avg(so_aop_fabric_rcv_items.rate) as rate,
      used.qty as used_qty,
      returned.qty as return_qty,
      CASE 
      WHEN used.qty is not null and returned.qty is not null THEN
      ((sum(so_aop_fabric_rcv_items.qty) - (used.qty+returned.qty) )*avg(so_aop_fabric_rcv_items.rate)) 
      WHEN used.qty is not null and returned.qty is null THEN
      ((sum(so_aop_fabric_rcv_items.qty) - used.qty )*avg(so_aop_fabric_rcv_items.rate))
      WHEN used.qty is null and returned.qty is not null THEN
      ((sum(so_aop_fabric_rcv_items.qty) - returned.qty )*avg(so_aop_fabric_rcv_items.rate))
      ELSE sum(so_aop_fabric_rcv_items.qty)*avg(so_aop_fabric_rcv_items.rate) 
      END as amount

      from so_aops
      join so_aop_fabric_rcvs on so_aop_fabric_rcvs.so_aop_id=so_aops.id
      join so_aop_fabric_rcv_items on so_aop_fabric_rcv_items.so_aop_fabric_rcv_id=so_aop_fabric_rcvs.id
      left join (
      select so_aop_dlvs.buyer_id,
      so_aop_dlv_items.so_aop_ref_id,
      sum(so_aop_dlv_items.grey_used) as qty 
      from so_aop_dlvs
      join so_aop_dlv_items on so_aop_dlv_items.so_aop_dlv_id=so_aop_dlvs.id
      where so_aop_dlvs.buyer_id=? and 
      so_aop_dlvs.company_id=? and
      so_aop_dlvs.deleted_at is null and 
      so_aop_dlv_items.deleted_at is null 
      group by 
      so_aop_dlvs.buyer_id,
      so_aop_dlv_items.so_aop_ref_id
      ) used on so_aop_fabric_rcv_items.so_aop_ref_id=used.so_aop_ref_id

      left join (
      select so_aop_fabric_rtns.buyer_id,
      so_aop_fabric_rtn_items.so_aop_ref_id,
      sum(so_aop_fabric_rtn_items.qty)  as qty
      from so_aop_fabric_rtns
      join so_aop_fabric_rtn_items on so_aop_fabric_rtn_items.so_aop_fabric_rtn_id=so_aop_fabric_rtns.id
      where so_aop_fabric_rtns.buyer_id=? and 
      so_aop_fabric_rtns.company_id=? and
      so_aop_fabric_rtns.deleted_at is null and 
      so_aop_fabric_rtn_items.deleted_at is null 
      group by 
      so_aop_fabric_rtns.buyer_id,
      so_aop_fabric_rtn_items.so_aop_ref_id
      ) returned on 
      so_aop_fabric_rcv_items.so_aop_ref_id=returned.so_aop_ref_id

      where so_aops.buyer_id=? and 
      so_aops.company_id=? and
      so_aops.deleted_at is null and 
      so_aop_fabric_rcvs.deleted_at is null and 
      so_aop_fabric_rcv_items.deleted_at is null and
      so_aop_fabric_rcv_items.qty >0 and
      so_aop_fabric_rcv_items.rate >0 
      group by 
      so_aops.buyer_id,
      so_aop_fabric_rcv_items.so_aop_ref_id,
      used.qty,returned.qty) m group by m.buyer_id', [$master->buyer_id,$master->company_id,$master->buyer_id,$master->company_id,$master->buyer_id,$master->company_id]))
      ->first();



      $currentBill =collect(\DB::select('
      select so_aop_dlvs.id,so_aop_dlvs.currency_id,sum(so_aop_dlv_items.amount) as amount 
      from so_aop_dlvs
      join so_aop_dlv_items on so_aop_dlv_items.so_aop_dlv_id=so_aop_dlvs.id
      where 
      so_aop_dlvs.id=? and
      so_aop_dlvs.deleted_at is null and 
      so_aop_dlv_items.deleted_at is null 
      group by so_aop_dlvs.id,so_aop_dlvs.currency_id', [$id]))
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
        $billfor=array_prepend(config('bprs.billfor'),'','');

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
        $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');



        $rows=$this->soaopdlv
        ->join('so_aop_dlv_items',function($join){
        $join->on('so_aop_dlv_items.so_aop_dlv_id','=','so_aop_dlvs.id');
        })
        ->join('so_aop_refs',function($join){
        $join->on('so_aop_refs.id','=','so_aop_dlv_items.so_aop_ref_id');
        })
         ->join('so_aops',function($join){
        $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
        })
        ->leftJoin('so_aop_items',function($join){
        $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
        })
        ->leftJoin('buyers',function($join){
        $join->on('buyers.id','=','so_aop_items.gmt_buyer');
        })
        ->leftJoin('uoms',function($join){
        $join->on('uoms.id','=','so_aop_items.uom_id');
        })
        ->leftJoin('colors',function($join){
        $join->on('colors.id','=','so_aop_items.fabric_color_id');
        })
        ->where([['so_aop_dlvs.id','=',$id]])
        ->selectRaw('
         so_aops.sales_order_no,
        so_aop_refs.id as so_aop_ref_id,
        so_aop_refs.so_aop_id,
        so_aop_items.autoyarn_id,
        so_aop_items.fabric_look_id,
        so_aop_items.fabric_shape_id,
        so_aop_items.gmtspart_id,
        so_aop_items.gsm_weight,
        so_aop_dlv_items.id,
        so_aop_dlv_items.qty,
        so_aop_dlv_items.rate,
        so_aop_dlv_items.amount,
        so_aop_dlv_items.no_of_roll,
        so_aop_dlv_items.design_no,
        so_aop_dlv_items.design_name,
        so_aop_dlv_items.fin_dia,
        so_aop_dlv_items.fin_gsm,
        so_aop_dlv_items.grey_used,
        so_aop_dlv_items.remarks,
        so_aop_items.gmt_style_ref,
        so_aop_items.gmt_sale_order_no,
        so_aop_items.embelishment_type_id,
        so_aop_items.coverage,
        so_aop_items.impression,
        so_aop_items.bill_for,
        buyers.name as buyer_name,
        uoms.code as uom_name,
        colors.name as fabric_color
        '
        )
        ->orderBy('so_aop_items.id','desc')
        ->get()
        ->map(function($rows) use($desDropdown,$fabricDescriptionArr,$gmtspart,$fabriclooks,$fabricshape,$uom,$aoptype,$billfor){
        $rows->fabrication=$desDropdown[$rows->autoyarn_id];
        $rows->fabricshape=$fabricshape[$rows->fabric_shape_id];
        $rows->fabriclooks=$fabriclooks[$rows->fabric_look_id];
        $rows->gmtspart=$gmtspart[$rows->gmtspart_id];
        $rows->constructions_name=$fabricDescriptionArr[$rows->autoyarn_id];
        $rows->aoptype=$aoptype[$rows->embelishment_type_id];
        $rows->bill_for=$billfor[$rows->bill_for];
        $rows->qty=$rows->qty;
        //$rows->amount=number_format($rows->amount,2,'.',',');
        //$rows->amount_raw=$rows->amount;
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
          $pdf->SetMargins(PDF_MARGIN_LEFT, '42', PDF_MARGIN_RIGHT);
          $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
          $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
          $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
          $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
          $header['logo']=$master->logo;
          $header['address']=$master->company_address;
          $header['title']='Subcontract Aop Bill';
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
        $pdf->SetTitle('Subcontract Aop Bill');
        $view= \View::make('Defult.Subcontract.AOP.SoAopBillPdf',['data'=>$data]);

        $html_content=$view->render();
        $pdf->SetY(42);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/SoAopBillPdf.pdf';
        $pdf->output($filename);
    }
}