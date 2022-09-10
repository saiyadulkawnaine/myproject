<?php

namespace App\Http\Controllers\Commercial\LocalExport;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpDocSubAcceptRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\SizeRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Util\EmbelishmentRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpLcRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpInvoiceRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpDocSubInvoiceRepository;
use App\Library\Numbertowords;
use App\Library\Template;
use App\Http\Requests\Commercial\LocalExport\LocalExpDocSubAcceptRequest;
use App\Repositories\Contracts\Util\BankBranchRepository;

class LocalExpDocSubAcceptController extends Controller {

    private $localexpdocaccept;
    private $company;
    private $buyer;
    private $currency;
    private $localexplc;
    private $localexpdocsubinvoice;
    private $localexpinvoice;
    private $itemaccount;
    private $embelishment;
    private $size;

    public function __construct(LocalExpDocSubAcceptRepository $localexpdocaccept,LocalExpLcRepository $localexplc,CompanyRepository $company, BuyerRepository $buyer,CurrencyRepository $currency, LocalExpDocSubInvoiceRepository $localexpdocsubinvoice,LocalExpInvoiceRepository $localexpinvoice,ItemAccountRepository $itemaccount, 
    GmtspartRepository $gmtspart,
            AutoyarnRepository $autoyarn,
            ColorrangeRepository $colorrange,
            EmbelishmentTypeRepository $embelishmenttype,
            EmbelishmentRepository $embelishment,
            ColorRepository $color,
            SizeRepository $size,BankBranchRepository $bankbranch) {

        $this->localexpdocaccept = $localexpdocaccept;
        $this->localexplc = $localexplc;
        $this->currency = $currency;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->localexpdocsubinvoice = $localexpdocsubinvoice;
        $this->localexpinvoice = $localexpinvoice;
        $this->itemaccount = $itemaccount;
        $this->autoyarn = $autoyarn;
        $this->gmtspart = $gmtspart;
        $this->colorrange = $colorrange;
        $this->color = $color;
        $this->size = $size;
        $this->embelishmenttype = $embelishmenttype;
        $this->embelishment = $embelishment;
        $this->bankbranch = $bankbranch;
        

    $this->middleware('auth');

    // $this->middleware('permission:view.localexpdocsubaccepts',   ['only' => ['create', 'index','show']]);
    // $this->middleware('permission:create.localexpdocsubaccepts', ['only' => ['store']]);
    // $this->middleware('permission:edit.localexpdocsubaccepts',   ['only' => ['update']]);
    // $this->middleware('permission:delete.localexpdocsubaccepts', ['only' => ['destroy']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {        
        $localexpdocaccepts=array();
        $rows=$this->localexpdocaccept
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'local_exp_lcs.beneficiary_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        ->orderBy('local_exp_doc_sub_accepts.id','desc')
        ->get([            
            'local_exp_doc_sub_accepts.*',
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.beneficiary_id',
            'local_exp_lcs.buyer_id', 
            'local_exp_lcs.buyers_bank', 
            'local_exp_lcs.currency_id', 
            'buyers.name as buyer_name',
            'companies.name as beneficiary',
            'currencies.code as currency_code',
            ]);
        //->get();
        foreach($rows as $row){
            $localexpdocaccept['id']=$row->id;
            $localexpdocaccept['local_exp_lc_id']=$row->local_exp_lc_id;
            $localexpdocaccept['local_lc_no']=$row->local_lc_no;
            $localexpdocaccept['submission_date']=date('d-M-Y',strtotime($row->submission_date));
            $localexpdocaccept['courier_recpt_no']=$row->courier_recpt_no;
            $localexpdocaccept['buyer_name']=$row->buyer_name;
            $localexpdocaccept['beneficiary']=$row->beneficiary;
            $localexpdocaccept['buyers_bank']=$row->buyers_bank;
            $localexpdocaccept['currency_code']=$row->currency_code;
            $localexpdocaccept['courier_company']=$row->courier_company;
            $localexpdocaccept['accept_receive_date']=($row->accept_receive_date!==null)?date('d-M-Y',strtotime($row->accept_receive_date)):null;
            $localexpdocaccept['remarks']=$row->remarks;
            array_push($localexpdocaccepts,$localexpdocaccept);
        }
        echo json_encode($localexpdocaccepts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create() {
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');

        return Template::LoadView('Commercial.LocalExport.LocalExpDocSubAccept',['currency'=>$currency,'company'=>$company]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(LocalExpDocSubAcceptRequest $request) {
        $localexpdocaccept=$this->localexpdocaccept->create($request->except(['id','beneficiary_id','buyer_id','currency_id','local_lc_no']));
        if($localexpdocaccept){
            return response()->json(array('success' => true,'id' =>  $localexpdocaccept->id,'message' => 'Save Successfully'),200);
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
       $localexpdocaccept = $this->localexpdocaccept
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'local_exp_lcs.beneficiary_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        ->leftJoin('local_exp_doc_sub_invoices',function($join){
            $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            $join->whereNull('local_exp_doc_sub_invoices.deleted_at');
        })
        ->leftJoin('local_exp_invoices',function($join){
            $join->on('local_exp_doc_sub_invoices.local_exp_invoice_id','=','local_exp_invoices.id');
        })
        ->where([['local_exp_doc_sub_accepts.id','=',$id]])
        ->get([
            'local_exp_doc_sub_accepts.*',
            'local_exp_lcs.id as local_exp_lc_id',
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.beneficiary_id',
            'local_exp_lcs.buyer_id', 
            'local_exp_lcs.buyers_bank', 
            'local_exp_lcs.currency_id', 
            'buyers.name as buyer_id',
            'companies.name as beneficiary_id',
            'currencies.name as currency_id',
            'local_exp_invoices.id as local_exp_invoice_id'
        ])
        ->first();
        $row ['fromData'] = $localexpdocaccept;
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
    public function update(LocalExpDocSubAcceptRequest $request, $id) {
        $localexpdocaccept=$this->localexpdocaccept->update($id,$request->except(['id','beneficiary_id','buyer_id','currency_id','local_lc_no']));
        if($localexpdocaccept){
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
        if($this->localexpdocaccept->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getLocalExportLc(){
        $rows=$this->localexplc
        ->join('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'local_exp_lcs.beneficiary_id');
        })
        ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        ->join('local_exp_invoices',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_invoices.local_exp_lc_id');
        })
        ->when(request('local_lc_no'), function ($q) {
            return $q->where('local_exp_lcs.local_lc_no', 'LIKE', "%".request('local_lc_no', 0)."%");
        })
        ->when(request('beneficiary_id'), function ($q) {
            return $q->where('local_exp_lcs.beneficiary_id', '=', request('beneficiary_id', 0));
        })
        ->orderBy('local_exp_lcs.id','asc')
        ->get([
            'local_exp_lcs.*',
            'local_exp_invoices.id as local_exp_invoice_id',
            'local_exp_invoices.local_invoice_no',
            'local_exp_invoices.local_invoice_value',
            'buyers.name as buyer_id',
            'companies.name as beneficiary_id',
            'currencies.name as currency_id'
        ]);
        echo json_encode($rows);
    }

    public function OpenCi(){
        $id=request('id', 0);
        $invoicedtl=$this->localexpdocaccept
        ->join('local_exp_doc_sub_invoices',function($join){
            $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            $join->whereNull('local_exp_doc_sub_invoices.deleted_at');
        })
        ->join('local_exp_invoices',function($join){
            $join->on('local_exp_doc_sub_invoices.local_exp_invoice_id','=','local_exp_invoices.id');
        })
        ->where([['local_exp_doc_sub_accepts.id','=',$id]])
        ->get([
            'local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id',
            'local_exp_doc_sub_invoices.id as local_exp_doc_sub_invoice_id',
           // 'local_exp_doc_sub_invoices.local_exp_invoice_id',
            'local_exp_invoices.id as local_exp_invoice_id',
            'local_exp_invoices.local_invoice_no',
            'local_exp_invoices.local_invoice_date',
            'local_exp_invoices.local_invoice_value'
        ]);
        echo json_encode($invoicedtl);
    }

    public function getCIPdf(){
        $id=request('local_exp_invoice_id', 0);

        $localpi=$this->localexpinvoice
        ->join('local_exp_invoice_orders',function($join){
             $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
             $join->whereNull('local_exp_invoice_orders.deleted_at');
         })
        ->join('local_exp_pi_orders',function($join){
             $join->on('local_exp_pi_orders.id','=','local_exp_invoice_orders.local_exp_pi_order_id');
         })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pis.id', '=', 'local_exp_pi_orders.local_exp_pi_id');
        })
        /*->join('local_exp_lcs',function($join){
             $join->on('local_exp_lcs.id','=','local_exp_invoices.local_exp_lc_id');
         })
        ->join('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_lc_tag_pis.local_exp_lc_id');
        })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
        })*/
        ->where([['local_exp_invoices.id','=',$id]])
        ->get([    
            'local_exp_invoices.id as local_exp_invoice_id', 
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
        ]);

        $arrPi=array();
	    foreach($localpi as $bar){
		    $arrPi[$bar->local_exp_invoice_id][$bar->pi_no]=$bar->pi_no.", Dated: ".date('d-M-Y',strtotime($bar->pi_date));
        }

        $localexpdocaccepts=$this->localexpinvoice
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_invoices.local_exp_lc_id');
         })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->leftJoin('buyer_branches',function($join){
            $join->on('buyers.id','=','buyer_branches.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'local_exp_lcs.beneficiary_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        ->join('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_lc_tag_pis.local_exp_lc_id');
        })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
        })
        ->where([['local_exp_invoices.id','=',$id]])
       // ->where([['local_exp_doc_sub_accepts.id','=',$localexpdocacceptId]])
        ->get([            
           // 'local_exp_doc_sub_accepts.id',
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.*',
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
            'buyers.name as buyer_name',
            'buyer_branches.address as buyer_address',
            'buyer_branches.email as buyer_email',
            'companies.name as beneficiary',
            'companies.address as company_address',
            'companies.logo',
            'currencies.name as currency_id',
            'local_exp_invoices.*',
            'local_exp_invoices.id as local_exp_invoice_id'
        ]);
        foreach ($localexpdocaccepts as $rows) {
            $localexpdocaccept['local_exp_invoice_id']=$rows->id;
            $localexpdocaccept['logo']=$rows->logo;
            $localexpdocaccept['beneficiary']=$rows->beneficiary;
            $localexpdocaccept['company_address']=$rows->company_address;
            $localexpdocaccept['local_invoice_no']=$rows->local_invoice_no;
            $localexpdocaccept['local_invoice_date']=date('d-M-Y',strtotime($rows->local_invoice_date));
            $localexpdocaccept['buyer_name']=$rows->buyer_name;
            $localexpdocaccept['buyer_address']=$rows->buyer_address;
            $localexpdocaccept['buyer_email']=$rows->buyer_email;
            $localexpdocaccept['buyers_bank']=$rows->buyers_bank;
            $localexpdocaccept['pi_no']=implode(" ; ",$arrPi[$rows->local_exp_invoice_id]);
           // $localexpdocaccept['pi_date']=date('d-M-Y',strtotime($rows->pi_date));
            $localexpdocaccept['local_lc_no']=$rows->local_lc_no;
            $localexpdocaccept['lc_date']=date('d-M-Y',strtotime($rows->lc_date));
            $localexpdocaccept['customer_lc_sc']=$rows->customer_lc_sc;
            $localexpdocaccept['delivery_place']=$rows->delivery_place;
            $localexpdocaccept['currency_id']=$rows->currency_id;
        }
        $localexpdocaccept['master']=$rows;


        $localexpinvoice=$this->localexpinvoice->find($id);
        $localexplc=$this->localexplc->find($localexpinvoice->local_exp_lc_id);
        $production_area_id=$localexplc->production_area_id;
         //Yarn Dyeing Sales Order
         if($production_area_id==5){
            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                sum(local_exp_pi_orders.qty) as pi_qty,
                sum(local_exp_pi_orders.amount) as pi_amount,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                local_exp_invoice_orders.qty as invoice_qty,
                local_exp_invoice_orders.rate as invoice_rate,
                local_exp_invoice_orders.amount as invoice_amount,
                cumulatives.cumulative_amount,
                cumulatives.cumulative_qty
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
            }) 
            ->join(\DB::raw("(SELECT
                    local_exp_pi_orders.id as local_exp_pi_order_id,
                    sum(local_exp_invoice_orders.qty) as cumulative_qty,
                    sum(local_exp_invoice_orders.amount) as cumulative_amount 
                FROM local_exp_invoice_orders
                    join local_exp_pi_orders on local_exp_pi_orders.id =local_exp_invoice_orders.local_exp_pi_order_id
                    join local_exp_invoices on  local_exp_invoices.id=local_exp_invoice_orders.local_exp_invoice_id
                where local_exp_invoice_orders.deleted_at is null
                group by local_exp_pi_orders.id) cumulatives"), "cumulatives.local_exp_pi_order_id", "=", "local_exp_pi_orders.id")
            ->leftJoin('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=',$id]])
            ->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoice_orders.id',
                'local_exp_invoice_orders.qty',
                'local_exp_invoice_orders.rate',
                'local_exp_invoice_orders.amount',
                'cumulatives.cumulative_amount',
                'cumulatives.cumulative_qty'
            ])
            ->get()
            ->map(function ($impdocaccept){
                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                $impdocaccept->pi_rate=$impdocaccept->pi_amount/$impdocaccept->pi_qty;
                $impdocaccept->balance_qty=$impdocaccept->qty-$impdocaccept->cumulative_qty;
                return $impdocaccept;
            });


        }
        //Knitting Sales Order
        elseif ($production_area_id==10) {
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
            $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }

            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                so_knit_refs.id as sales_order_ref_id,
                so_knit_refs.so_knit_id,
                constructions.name as constructions_name,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_knit_service_item_qties.dia,
                po_knit_service_item_qties.measurment,
                so_knit_items.gmt_sale_order_no,
                so_knit_items.autoyarn_id as c_autoyarn_id,
                so_knit_items.fabric_look_id as c_fabric_look_id,
                so_knit_items.fabric_shape_id as c_fabric_shape_id,
                so_knit_items.gmtspart_id as c_gmtspart_id,
                so_knit_items.gsm_weight as c_gsm_weight,
                so_knit_items.dia as c_dia,
                so_knit_items.measurment as c_measurment,
                so_color.name as c_fabric_color_name,
                po_color.name as fabric_color_name,
                sales_orders.sale_order_no,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                local_exp_invoice_orders.qty as invoice_qty,
                local_exp_invoice_orders.rate as invoice_rate,
                local_exp_invoice_orders.amount as invoice_amount
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_knit_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_knit_refs.id');
            })
            ->join('so_knits',function($join){
                $join->on('so_knit_refs.so_knit_id','=','so_knits.id');
            })
            ->leftJoin('so_knit_pos',function($join){
                $join->on('so_knit_pos.so_knit_id','=','so_knits.id');
            })
            ->leftJoin('so_knit_po_items',function($join){
                $join->on('so_knit_po_items.so_knit_ref_id','=','so_knit_refs.id');
            })
            ->leftJoin('po_knit_service_item_qties',function($join){
                $join->on('po_knit_service_item_qties.id','=','so_knit_po_items.po_knit_service_item_qty_id');
            })
            ->leftJoin('po_knit_service_items',function($join){
                $join->on('po_knit_service_items.id','=','po_knit_service_item_qties.po_knit_service_item_id')
                ->whereNull('po_knit_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_knit_service_item_qties.sales_order_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
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
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_knit_items',function($join){
                $join->on('so_knit_items.so_knit_ref_id','=','so_knit_refs.id');
            })
            // ->leftJoin('buyers',function($join){
            //     $join->on('buyers.id','=','styles.buyer_id');
            // })
            // ->leftJoin('buyers as gmt_buyer',function($join){
            //     $join->on('gmt_buyer.id','=','so_knit_items.gmt_buyer');
            // })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_knit_items.uom_id');
            })
            ->leftJoin('colors as so_color',function($join){
                $join->on('so_color.id','=','so_knit_items.fabric_color_id');
            })
            ->leftJoin('colors as po_color',function($join){
                $join->on('po_color.id','=','po_knit_service_item_qties.fabric_color_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=',$id]])
            ->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoice_orders.id',
                'so_knit_refs.id',
                'so_knit_refs.so_knit_id',
                'constructions.name',
                'style_fabrications.autoyarn_id',
                'style_fabrications.fabric_look_id',
                'style_fabrications.fabric_shape_id',
                'style_fabrications.gmtspart_id',
                'budget_fabrics.gsm_weight',
                'po_knit_service_item_qties.dia',
                'po_knit_service_item_qties.measurment',
                'so_knit_items.gmt_sale_order_no',
                'so_knit_items.autoyarn_id',
                'so_knit_items.fabric_look_id',
                'so_knit_items.fabric_shape_id',
                'so_knit_items.gmtspart_id',
                'so_knit_items.gsm_weight',
                'so_knit_items.dia',
                'so_knit_items.measurment',
                'so_color.name',
                'po_color.name',
                'uoms.code',
                'so_uoms.code',
                'sales_orders.sale_order_no',
                'local_exp_invoice_orders.qty',
                'local_exp_invoice_orders.rate',
                'local_exp_invoice_orders.amount'
            ])
            ->get()
            ->map(function ($impdocaccept) use($gmtspart,$fabriclooks,$fabricshape,$desDropdown){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->gmtspart=$impdocaccept->gmtspart_id?$gmtspart[$impdocaccept->gmtspart_id]:$gmtspart[$impdocaccept->c_gmtspart_id];
                $impdocaccept->fabriclooks=$impdocaccept->fabric_look_id?$fabriclooks[$impdocaccept->fabric_look_id]:$fabriclooks[$impdocaccept->c_fabric_look_id];
                $impdocaccept->fabricshape=$impdocaccept->fabric_shape_id?$fabricshape[$impdocaccept->fabric_shape_id]:$fabricshape[$impdocaccept->c_fabric_shape_id];
                $impdocaccept->gsm_weight=$impdocaccept->gsm_weight?$impdocaccept->gsm_weight:$impdocaccept->c_gsm_weight;
                $impdocaccept->dia=$impdocaccept->dia?$impdocaccept->dia:$impdocaccept->c_dia;
                $impdocaccept->measurment=$impdocaccept->measurment?$impdocaccept->measurment:$impdocaccept->c_measurment;
                $impdocaccept->fabric_color=$impdocaccept->fabric_color_name?$impdocaccept->fabric_color_name:$impdocaccept->c_fabric_color_name;

                $impdocaccept->item_description=$impdocaccept->fabrication.','.$impdocaccept->gmtspart.','.$impdocaccept->fabriclooks.','.$impdocaccept->fabricshape.','.$impdocaccept->gsm_weight.','.$impdocaccept->fabric_color.','.$impdocaccept->dia.','.$impdocaccept->measurment;

                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                $impdocaccept->uom_code=$impdocaccept->uom_name?$impdocaccept->uom_name:$impdocaccept->so_uom_name;

                return $impdocaccept;
            });
        }
        //Dyeing Sales Order
        elseif ($production_area_id==20) {
            $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
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
                $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');
            $dyetype=array_prepend(config('bprs.dyetype'),'','');

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                so_dyeing_refs.id as so_dyeing_ref_id,
                so_dyeing_refs.so_dyeing_id,
                constructions.name as constructions_name,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_dyeing_service_item_qties.fabric_color_id,
                po_dyeing_service_item_qties.colorrange_id,
                so_dyeing_items.autoyarn_id as c_autoyarn_id,
                so_dyeing_items.fabric_look_id as c_fabric_look_id,
                so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
                so_dyeing_items.gmtspart_id as c_gmtspart_id,
                so_dyeing_items.gsm_weight as c_gsm_weight,
                so_dyeing_items.fabric_color_id as c_fabric_color_id,
                so_dyeing_items.colorrange_id as c_colorrange_id,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                sales_orders.sale_order_no,
                so_dyeing_items.gmt_sale_order_no,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                local_exp_invoice_orders.qty as invoice_qty,
                local_exp_invoice_orders.rate as invoice_rate,
                local_exp_invoice_orders.amount as invoice_amount
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_dyeing_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_dyeing_refs.id');
            })
            ->join('so_dyeings',function($join){
                $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
            })
            ->leftJoin('so_dyeing_pos',function($join){
                $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
            })
            ->leftJoin('so_dyeing_po_items',function($join){
                $join->on('so_dyeing_po_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
            })
            ->leftJoin('po_dyeing_service_item_qties',function($join){
                $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
            })
            ->leftJoin('po_dyeing_service_items',function($join){
                $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
            ->whereNull('po_dyeing_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','po_dyeing_service_item_qties.fabric_color_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
                $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
            })
            ->leftJoin('budget_fabrics',function($join){
                $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->leftJoin('style_fabrications',function($join){
                $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_dyeing_items',function($join){
                $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
            })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_dyeing_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=',$id]])
            ->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoice_orders.id',
                'so_dyeing_refs.id',
                'so_dyeing_refs.so_dyeing_id',
                'constructions.name',
                'style_fabrications.autoyarn_id',
                'style_fabrications.fabric_look_id',
                'style_fabrications.fabric_shape_id',
                'style_fabrications.gmtspart_id',
                'budget_fabrics.gsm_weight',
                'po_dyeing_service_item_qties.fabric_color_id',
                'po_dyeing_service_item_qties.colorrange_id',
                'so_dyeing_items.autoyarn_id',
                'so_dyeing_items.fabric_look_id',
                'so_dyeing_items.fabric_shape_id',
                'so_dyeing_items.gmtspart_id',
                'so_dyeing_items.gsm_weight',
                'so_dyeing_items.fabric_color_id',
                'so_dyeing_items.colorrange_id',
                'uoms.code',
                'so_uoms.code',
                'sales_orders.sale_order_no',
                'so_dyeing_items.gmt_sale_order_no',
                'local_exp_invoice_orders.qty',
                'local_exp_invoice_orders.rate',
                'local_exp_invoice_orders.amount'
            ])
            ->get()
            ->map(function ($impdocaccept) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$colorrange,$color,$dyetype){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->gmtspart=$impdocaccept->gmtspart_id?$gmtspart[$impdocaccept->gmtspart_id]:$gmtspart[$impdocaccept->c_gmtspart_id];
                $impdocaccept->fabriclooks=$impdocaccept->fabric_look_id?$fabriclooks[$impdocaccept->fabric_look_id]:$fabriclooks[$impdocaccept->c_fabric_look_id];
                $impdocaccept->fabricshape=$impdocaccept->fabric_shape_id?$fabricshape[$impdocaccept->fabric_shape_id]:$fabricshape[$impdocaccept->c_fabric_shape_id];
                $impdocaccept->gsm_weight=$impdocaccept->gsm_weight?$impdocaccept->gsm_weight:$impdocaccept->c_gsm_weight;
                $impdocaccept->fabric_color_name=$impdocaccept->fabric_color_id?$color[$impdocaccept->fabric_color_id]:$color[$impdocaccept->c_fabric_color_id];
                $impdocaccept->colorrange_id=$impdocaccept->colorrange_id?$colorrange[$impdocaccept->colorrange_id]:$colorrange[$impdocaccept->c_colorrange_id];
                $impdocaccept->dyeing_type=$impdocaccept->dyeing_type_id?$dyetype[$impdocaccept->dyeing_type_id]:$dyetype[$impdocaccept->c_dyeing_type_id];
                $impdocaccept->item_description=$impdocaccept->fabrication.','.$impdocaccept->gmtspart.','.$impdocaccept->fabriclooks.','.$impdocaccept->fabricshape.','.$impdocaccept->gsm_weight.','.$impdocaccept->fabric_color_name.','.$impdocaccept->colorrange_id.','.$impdocaccept->dyeing_type;
                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                $impdocaccept->uom_code=$impdocaccept->uom_name?$impdocaccept->uom_name:$impdocaccept->so_uom_name;

                return $impdocaccept;
            });
        }
        //AOP Sales Order
        elseif ($production_area_id==25) {
            $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
            $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'','');
            $autoyarn=$this->autoyarn
            ->leftJoin('autoyarnratios', function($join)  {
                $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('compositions',function($join){
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
                $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                so_aop_refs.id as so_aop_ref_id,
                so_aop_refs.so_aop_id,
                constructions.name as constructions_name,
                po_aop_service_item_qties.fabric_color_id,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_aop_service_item_qties.colorrange_id,
                so_aop_items.autoyarn_id as c_autoyarn_id,
                so_aop_items.fabric_look_id as c_fabric_look_id,
                so_aop_items.fabric_shape_id as c_fabric_shape_id,
                so_aop_items.gmtspart_id as c_gmtspart_id,
                so_aop_items.gsm_weight as c_gsm_weight,
                so_aop_items.fabric_color_id as c_fabric_color_id,
                so_aop_items.colorrange_id as c_colorrange_id,
                po_aop_service_item_qties.embelishment_type_id,
                po_aop_service_item_qties.coverage,
                po_aop_service_item_qties.impression,
                so_aop_items.embelishment_type_id as c_embelishment_type_id,
                so_aop_items.coverage as c_coverage,
                so_aop_items.impression as c_impression,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                sales_orders.sale_order_no,
                so_aop_items.gmt_sale_order_no,
                local_exp_pi_orders.sales_order_ref_id,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                local_exp_invoice_orders.qty as invoice_qty,
                local_exp_invoice_orders.rate as invoice_rate,
                local_exp_invoice_orders.amount as invoice_amount
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
            })
            ->join('so_aop_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_aop_refs.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_aops',function($join){
                $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
            })
            ->leftJoin('so_aop_pos',function($join){
                $join->on('so_aop_pos.so_aop_id','=','so_aops.id');
            })
            ->leftJoin('so_aop_po_items',function($join){
                $join->on('so_aop_po_items.so_aop_ref_id','=','so_aop_refs.id');
            })
            ->leftJoin('po_aop_service_item_qties',function($join){
                $join->on('po_aop_service_item_qties.id','=','so_aop_po_items.po_aop_service_item_qty_id');
            })
            ->leftJoin('po_aop_service_items',function($join){
                $join->on('po_aop_service_items.id','=','po_aop_service_item_qties.po_aop_service_item_id')
            ->whereNull('po_aop_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_aop_service_item_qties.sales_order_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','po_aop_service_item_qties.fabric_color_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
                $join->on('budget_fabric_prods.id','=','po_aop_service_items.budget_fabric_prod_id');
            })
            ->leftJoin('budget_fabrics',function($join){
                $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->leftJoin('style_fabrications',function($join){
                $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_aop_items',function($join){
                $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
            })
            // ->leftJoin('buyers',function($join){
            //     $join->on('buyers.id','=','styles.buyer_id');
            // })
            // ->leftJoin('buyers as gmt_buyer',function($join){
            //     $join->on('gmt_buyer.id','=','so_aop_items.gmt_buyer');
            // })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_aop_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=', $id]])
            ->groupBy([
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoice_orders.id',
                'so_aop_refs.id',
                'so_aop_refs.so_aop_id',
                'constructions.name',
                'po_aop_service_item_qties.fabric_color_id',
                'style_fabrications.autoyarn_id',
                'style_fabrications.fabric_look_id',
                'style_fabrications.fabric_shape_id',
                'style_fabrications.gmtspart_id',
                'budget_fabrics.gsm_weight',
                'po_aop_service_item_qties.colorrange_id',
                'so_aop_items.autoyarn_id',
                'so_aop_items.fabric_look_id',
                'so_aop_items.fabric_shape_id',
                'so_aop_items.gmtspart_id',
                'so_aop_items.gsm_weight',
                'so_aop_items.fabric_color_id',
                'so_aop_items.colorrange_id',
                'po_aop_service_item_qties.embelishment_type_id',
                'po_aop_service_item_qties.coverage',
                'po_aop_service_item_qties.impression',
                'so_aop_items.embelishment_type_id',
                'so_aop_items.coverage',
                'so_aop_items.impression',
                'uoms.code',
                'so_uoms.code',
                'sales_orders.sale_order_no',
                'so_aop_items.gmt_sale_order_no',
                'local_exp_pi_orders.sales_order_ref_id',
                'local_exp_invoice_orders.qty',
                'local_exp_invoice_orders.rate',
                'local_exp_invoice_orders.amount',
            ])
            ->get()
            ->map(function ($impdocaccept) use($color,$gmtspart,$fabriclooks,$fabricshape,$colorrange,$color,$aoptype,$desDropdown){
                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->gmtspart=$impdocaccept->gmtspart_id?$gmtspart[$impdocaccept->gmtspart_id]:$gmtspart[$impdocaccept->c_gmtspart_id];
                $impdocaccept->fabriclooks=$impdocaccept->fabric_look_id?$fabriclooks[$impdocaccept->fabric_look_id]:$fabriclooks[$impdocaccept->c_fabric_look_id];
                $impdocaccept->fabricshape=$impdocaccept->fabric_shape_id?$fabricshape[$impdocaccept->fabric_shape_id]:$fabricshape[$impdocaccept->c_fabric_shape_id];
                $impdocaccept->gsm_weight=$impdocaccept->gsm_weight?$impdocaccept->gsm_weight:$impdocaccept->c_gsm_weight;
                $impdocaccept->fabric_color_name=$impdocaccept->fabric_color_id?$color[$impdocaccept->fabric_color_id]:$color[$impdocaccept->c_fabric_color_id];
                $impdocaccept->colorrange_id=$impdocaccept->colorrange_id?$colorrange[$impdocaccept->colorrange_id]:$colorrange[$impdocaccept->c_colorrange_id];
                $impdocaccept->embelishment_type_id=$impdocaccept->embelishment_type_id?$aoptype[$impdocaccept->embelishment_type_id]:$aoptype[$impdocaccept->c_embelishment_type_id];
                $impdocaccept->item_description=$impdocaccept->fabrication.','.$impdocaccept->gmtspart.','.$impdocaccept->fabriclooks.','.$impdocaccept->fabricshape.','.$impdocaccept->gsm_weight.','.$impdocaccept->fabric_color_name.','.$impdocaccept->colorrange_id.','.$impdocaccept->embelishment_type_id;
                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                $impdocaccept->uom_code=$impdocaccept->uom_name?$impdocaccept->uom_name:$impdocaccept->so_uom_name;

                return $impdocaccept;
            });
    
        }
        //Embelishment Work Order
        elseif ($production_area_id==45 ||$production_area_id==50 || $production_area_id==51) {
            $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');
            $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getEmbelishmentTypes(),'name','id'),'','');
            $embelishment=array_prepend(array_pluck($this->embelishment->get(),'name','id'),'','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
            $size=array_prepend(array_pluck($this->size->get(),'name','id'),'','');

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                so_emb_refs.id as so_emb_ref_id,
                so_emb_refs.so_emb_id,
                so_embs.sales_order_no,
                gmtsparts.id as gmtspart_id,
                so_emb_items.gmtspart_id as c_gmtspart_id,
                style_embelishments.embelishment_size_id,
                style_embelishments.embelishment_type_id,
                style_embelishments.embelishment_id,
                so_emb_items.embelishment_id as c_embelishment_id,
                so_emb_items.embelishment_type_id as c_embelishment_type_id,
                so_emb_items.embelishment_size_id as c_embelishment_size_id,
                so_emb_items.color_id as c_color_id,
                so_emb_items.size_id as c_size_id,
                so_uoms.code as so_uom_name,
                sales_orders.sale_order_no,
                so_emb_items.gmt_sale_order_no,
                item_accounts.item_description,
                colors.name as gmt_color,
                sizes.name as gmt_size,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                local_exp_invoice_orders.qty as invoice_qty,
                local_exp_invoice_orders.rate as invoice_rate,
                local_exp_invoice_orders.amount as invoice_amount
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->leftJoin('so_emb_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin('so_embs',function($join){
                $join->on('so_emb_refs.so_emb_id','=','so_embs.id');
            })
            ->leftJoin('so_emb_pos',function($join){
                $join->on('so_emb_pos.so_emb_id','=','so_embs.id');
            })
            ->leftJoin('so_emb_po_items',function($join){
                $join->on('so_emb_po_items.so_emb_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin('po_emb_service_item_qties',function($join){
                $join->on('po_emb_service_item_qties.id','=','so_emb_po_items.po_emb_service_item_qty_id');
            })
            ->leftJoin('po_emb_service_items',function($join){
                $join->on('po_emb_service_items.id','=','po_emb_service_item_qties.po_emb_service_item_id')
                ->whereNull('po_emb_service_items.deleted_at');
            })
            ->leftJoin('budget_embs',function($join){
                $join->on('budget_embs.id','=','po_emb_service_items.budget_emb_id');
            })
            ->leftJoin('style_embelishments',function($join){
                $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
            })
            ->leftJoin('style_gmts',function($join){
                $join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
            })
            ->leftJoin('item_accounts', function($join) {
                $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
            })
            ->leftJoin('gmtsparts',function($join){
                $join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
            })
            ->leftJoin('embelishments',function($join){
                $join->on('embelishments.id','=','style_embelishments.embelishment_id');
            })
            ->leftJoin('embelishment_types',function($join){
                $join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
            })
            ->leftJoin('budget_emb_cons',function($join){
                $join->on('budget_emb_cons.id','=','po_emb_service_item_qties.budget_emb_con_id')
                ->whereNull('budget_emb_cons.deleted_at');
            })
            ->leftJoin('sales_order_gmt_color_sizes',function($join){
                $join->on('sales_order_gmt_color_sizes.id','=','budget_emb_cons.sales_order_gmt_color_size_id');
            })
            ->leftJoin('sales_order_countries',function($join){
                $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','sales_order_countries.sale_order_id');
            })
            ->leftJoin('style_gmt_color_sizes',function($join){
                $join->on('style_gmt_color_sizes.id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('style_sizes',function($join){
                $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
            })
            ->leftJoin('sizes',function($join){
                $join->on('sizes.id','=','style_sizes.size_id');
            })
            ->leftJoin('style_colors',function($join){
                $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','style_colors.color_id');
            })
            ->leftJoin('so_emb_items',function($join){
                $join->on('so_emb_items.so_emb_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_emb_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=', $id]])
            ->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoice_orders.id',

                'so_emb_refs.id',
                'so_emb_refs.so_emb_id',
                'so_embs.sales_order_no',
                'gmtsparts.id',
                'so_emb_items.gmtspart_id',
                'style_embelishments.embelishment_size_id',
                'style_embelishments.embelishment_type_id',
                'style_embelishments.embelishment_id',
                'so_emb_items.embelishment_id',
                'so_emb_items.embelishment_type_id',
                'so_emb_items.embelishment_size_id',
                'so_emb_items.color_id',
                'so_emb_items.size_id',
                'so_uoms.code',
                'sales_orders.sale_order_no',
                'so_emb_items.gmt_sale_order_no',
                'item_accounts.item_description',
                'colors.name',
                'sizes.name',
                'local_exp_invoice_orders.qty',
                'local_exp_invoice_orders.rate',
                'local_exp_invoice_orders.amount',
            ])
            ->get()
            ->map(function ($impdocaccept) use($gmtspart,$embelishmentsize,$embelishmenttype,$embelishment,$color,$size){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->gmtspart=$impdocaccept->gmtspart_id?$gmtspart[$impdocaccept->gmtspart_id]:$gmtspart[$impdocaccept->c_gmtspart_id];
                $impdocaccept->emb_size=$impdocaccept->embelishment_size_id?$embelishmentsize[$impdocaccept->embelishment_size_id]:$embelishmentsize[$impdocaccept->c_embelishment_size_id];
                $impdocaccept->emb_name=$impdocaccept->embelishment_id?$embelishment[$impdocaccept->embelishment_id]:$embelishment[$impdocaccept->c_embelishment_id];
                $impdocaccept->gmt_color=$impdocaccept->gmt_color?$impdocaccept->gmt_color:$color[$impdocaccept->c_color_id];
                $impdocaccept->gmt_size=$impdocaccept->gmt_size?$impdocaccept->gmt_size:$size[$impdocaccept->c_size_id];
                $impdocaccept->item_description=$impdocaccept->item_description.','.$impdocaccept->emb_name.','.$impdocaccept->emb_size.','.$impdocaccept->gmtspart.','.$impdocaccept->gmt_color.','.$impdocaccept->gmt_size;
                $impdocaccept->dye_aop_type=$impdocaccept->embelishment_type_id?$embelishmenttype[$impdocaccept->embelishment_type_id]:$embelishmenttype[$impdocaccept->c_embelishment_type_id];
                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                $impdocaccept->uom_code='Pcs'?'Pcs':$impdocaccept->so_uom_name;
                return $impdocaccept;
            });
            
        }

        $amount=$impdocaccept->sum('invoice_amount');
        $currency=$localexpdocaccept['currency_id'];
        $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$currency,'cents');
        $localexpdocaccept['inword']=$inword;

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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
        $challan=str_pad($localexpdocaccept['local_exp_invoice_id'],10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
        $pdf->SetY(10);
        $image_file ='images/logo/'.$localexpdocaccept['logo'];
        $pdf->Image($image_file, 90, 5, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $pdf->SetY(13);
        $pdf->SetFont('helvetica', 'N', 9);
        //$pdf->Text(68, 16, $localexpdocaccept['company_address']);
        $pdf->Cell(0, 40, $localexpdocaccept['company_address'], 0, false, 'C', 0, '', 0, false, 'T', 'M' );
        //$pdf->SetY(16);
        $pdf->SetFont('helvetica', '', 8);
        $view= \View::make('Defult.Commercial.LocalExport.LocalExpCommercialInvoicePdf',['impdocaccept'=>$impdocaccept,'localexpdocaccept'=>$localexpdocaccept,'localpi'=>$localpi,'arrPi'=>$arrPi]);
        $html_content=$view->render();
        $pdf->SetY(35);
        
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/LocalExpCommercialInvoicePdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }

    public function getDCPdf(){
        $id=request('local_exp_invoice_id', 0);

        $localpi=$this->localexpinvoice
        ->join('local_exp_lcs',function($join){
             $join->on('local_exp_lcs.id','=','local_exp_invoices.local_exp_lc_id');
         })
        ->join('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_lc_tag_pis.local_exp_lc_id');
        })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
        })
        ->where([['local_exp_invoices.id','=',$id]])
        ->get([
            'local_exp_invoices.id as local_exp_invoice_id', 
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
        ]);

        $arrPi=array();
	    foreach($localpi as $bar){
		    $arrPi[$bar->local_exp_invoice_id][]=$bar->pi_no.", Dated: ".date('d-M-Y',strtotime($bar->pi_date));
        }
        
        $localexpdocaccepts=$this->localexpinvoice
        ->join('local_exp_lcs',function($join){
             $join->on('local_exp_lcs.id','=','local_exp_invoices.local_exp_lc_id');
         })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->leftJoin('buyer_branches',function($join){
            $join->on('buyers.id','=','buyer_branches.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'local_exp_lcs.beneficiary_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        ->join('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_lc_tag_pis.local_exp_lc_id');
        })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
        })
        ->where([['local_exp_invoices.id','=',$id]])
       // ->where([['local_exp_doc_sub_accepts.id','=',$localexpdocacceptId]])
        ->get([         
           // 'local_exp_doc_sub_accepts.id',
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.*',
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
            'buyers.name as buyer_name',
            'buyer_branches.address as buyer_address',
            'buyer_branches.email as buyer_email',
            'companies.name as beneficiary',
            'companies.address as company_address',
            'companies.logo',
            'currencies.name as currency_id',
            'local_exp_invoices.*',
            'local_exp_invoices.id as local_exp_invoice_id'
        ]);
        foreach ($localexpdocaccepts as $rows) {
            $localexpdocaccept['local_exp_invoice_id']=$rows->id;
            $localexpdocaccept['logo']=$rows->logo;
            $localexpdocaccept['beneficiary']=$rows->beneficiary;
            $localexpdocaccept['company_address']=$rows->company_address;
            $localexpdocaccept['local_invoice_no']=$rows->local_invoice_no;
            $localexpdocaccept['local_invoice_date']=date('d-M-Y',strtotime($rows->local_invoice_date));
            $localexpdocaccept['buyer_name']=$rows->buyer_name;
            $localexpdocaccept['buyer_address']=$rows->buyer_address;
            $localexpdocaccept['buyer_email']=$rows->buyer_email;
            $localexpdocaccept['buyers_bank']=$rows->buyers_bank;
            $localexpdocaccept['pi_no']=implode(" ; ",$arrPi[$rows->local_exp_invoice_id]);
            $localexpdocaccept['pi_date']=date('d-M-Y',strtotime($rows->pi_date));
            $localexpdocaccept['local_lc_no']=$rows->local_lc_no;
            $localexpdocaccept['lc_date']=date('d-M-Y',strtotime($rows->lc_date));
            $localexpdocaccept['customer_lc_sc']=$rows->customer_lc_sc;
            $localexpdocaccept['delivery_place']=$rows->delivery_place;
            $localexpdocaccept['currency_id']=$rows->currency_id;
        }
        $localexpdocaccept['master']=$rows;

        

        $localexpinvoice=$this->localexpinvoice->find($id);
        $localexplc=$this->localexplc->find($localexpinvoice->local_exp_lc_id);
        $production_area_id=$localexplc->production_area_id;
         //Yarn Dyeing Sales Order
         if($production_area_id==5){
            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                sum(local_exp_pi_orders.qty) as pi_qty,
                sum(local_exp_pi_orders.amount) as pi_amount,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                local_exp_invoice_orders.qty as invoice_qty,
                local_exp_invoice_orders.rate as invoice_rate,
                local_exp_invoice_orders.amount as invoice_amount,
                cumulatives.cumulative_amount,
                cumulatives.cumulative_qty
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            }) 
            ->join(\DB::raw("(SELECT
                    local_exp_pi_orders.id as local_exp_pi_order_id,
                    sum(local_exp_invoice_orders.qty) as cumulative_qty,
                    sum(local_exp_invoice_orders.amount) as cumulative_amount 
                FROM local_exp_invoice_orders
                    join local_exp_pi_orders on local_exp_pi_orders.id =local_exp_invoice_orders.local_exp_pi_order_id
                    join local_exp_invoices on  local_exp_invoices.id=local_exp_invoice_orders.local_exp_invoice_id
                where local_exp_invoice_orders.deleted_at is null
                group by local_exp_pi_orders.id) cumulatives"), "cumulatives.local_exp_pi_order_id", "=", "local_exp_pi_orders.id")
            ->leftJoin('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=',$id]])
            ->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoice_orders.id',
                'local_exp_invoice_orders.qty',
                'local_exp_invoice_orders.rate',
                'local_exp_invoice_orders.amount',
                'cumulatives.cumulative_amount',
                'cumulatives.cumulative_qty'
            ])
            ->get()
            ->map(function ($impdocaccept){
                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                $impdocaccept->pi_rate=$impdocaccept->pi_amount/$impdocaccept->pi_qty;
                $impdocaccept->balance_qty=$impdocaccept->qty-$impdocaccept->cumulative_qty;
                return $impdocaccept;
            });


        }
        //Knitting Sales Order
        elseif ($production_area_id==10) {
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
            $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }

            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                so_knit_refs.id as sales_order_ref_id,
                so_knit_refs.so_knit_id,
                constructions.name as constructions_name,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_knit_service_item_qties.dia,
                po_knit_service_item_qties.measurment,
                so_knit_items.gmt_sale_order_no,
                so_knit_items.autoyarn_id as c_autoyarn_id,
                so_knit_items.fabric_look_id as c_fabric_look_id,
                so_knit_items.fabric_shape_id as c_fabric_shape_id,
                so_knit_items.gmtspart_id as c_gmtspart_id,
                so_knit_items.gsm_weight as c_gsm_weight,
                so_knit_items.dia as c_dia,
                so_knit_items.measurment as c_measurment,
                so_color.name as c_fabric_color_name,
                po_color.name as fabric_color_name,
                sales_orders.sale_order_no,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                local_exp_invoice_orders.qty as invoice_qty,
                local_exp_invoice_orders.rate as invoice_rate,
                local_exp_invoice_orders.amount as invoice_amount
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_knit_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_knit_refs.id');
            })
            ->join('so_knits',function($join){
                $join->on('so_knit_refs.so_knit_id','=','so_knits.id');
            })
            ->leftJoin('so_knit_pos',function($join){
                $join->on('so_knit_pos.so_knit_id','=','so_knits.id');
            })
            ->leftJoin('so_knit_po_items',function($join){
                $join->on('so_knit_po_items.so_knit_ref_id','=','so_knit_refs.id');
            })
            ->leftJoin('po_knit_service_item_qties',function($join){
                $join->on('po_knit_service_item_qties.id','=','so_knit_po_items.po_knit_service_item_qty_id');
            })
            ->leftJoin('po_knit_service_items',function($join){
                $join->on('po_knit_service_items.id','=','po_knit_service_item_qties.po_knit_service_item_id')
                ->whereNull('po_knit_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_knit_service_item_qties.sales_order_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
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
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_knit_items',function($join){
                $join->on('so_knit_items.so_knit_ref_id','=','so_knit_refs.id');
            })
            // ->leftJoin('buyers',function($join){
            //     $join->on('buyers.id','=','styles.buyer_id');
            // })
            // ->leftJoin('buyers as gmt_buyer',function($join){
            //     $join->on('gmt_buyer.id','=','so_knit_items.gmt_buyer');
            // })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_knit_items.uom_id');
            })
            ->leftJoin('colors as so_color',function($join){
                $join->on('so_color.id','=','so_knit_items.fabric_color_id');
            })
            ->leftJoin('colors as po_color',function($join){
                $join->on('po_color.id','=','po_knit_service_item_qties.fabric_color_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=',$id]])
            ->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoice_orders.id',
                'so_knit_refs.id',
                'so_knit_refs.so_knit_id',
                'constructions.name',
                'style_fabrications.autoyarn_id',
                'style_fabrications.fabric_look_id',
                'style_fabrications.fabric_shape_id',
                'style_fabrications.gmtspart_id',
                'budget_fabrics.gsm_weight',
                'po_knit_service_item_qties.dia',
                'po_knit_service_item_qties.measurment',
                'so_knit_items.gmt_sale_order_no',
                'so_knit_items.autoyarn_id',
                'so_knit_items.fabric_look_id',
                'so_knit_items.fabric_shape_id',
                'so_knit_items.gmtspart_id',
                'so_knit_items.gsm_weight',
                'so_knit_items.dia',
                'so_knit_items.measurment',
                'so_color.name',
                'po_color.name',
                'sales_orders.sale_order_no',
                'uoms.code',
                'so_uoms.code',
                'local_exp_invoice_orders.qty',
                'local_exp_invoice_orders.rate',
                'local_exp_invoice_orders.amount'
            ])
            ->get()
            ->map(function ($impdocaccept) use($gmtspart,$fabriclooks,$fabricshape,$desDropdown){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->gmtspart=$impdocaccept->gmtspart_id?$gmtspart[$impdocaccept->gmtspart_id]:$gmtspart[$impdocaccept->c_gmtspart_id];
                $impdocaccept->fabriclooks=$impdocaccept->fabric_look_id?$fabriclooks[$impdocaccept->fabric_look_id]:$fabriclooks[$impdocaccept->c_fabric_look_id];
                $impdocaccept->fabricshape=$impdocaccept->fabric_shape_id?$fabricshape[$impdocaccept->fabric_shape_id]:$fabricshape[$impdocaccept->c_fabric_shape_id];
                $impdocaccept->gsm_weight=$impdocaccept->gsm_weight?$impdocaccept->gsm_weight:$impdocaccept->c_gsm_weight;
                $impdocaccept->dia=$impdocaccept->dia?$impdocaccept->dia:$impdocaccept->c_dia;
                $impdocaccept->measurment=$impdocaccept->measurment?$impdocaccept->measurment:$impdocaccept->c_measurment;
                $impdocaccept->fabric_color=$impdocaccept->fabric_color_name?$impdocaccept->fabric_color_name:$impdocaccept->c_fabric_color_name;

                $impdocaccept->item_description=$impdocaccept->fabrication.','.$impdocaccept->gmtspart.','.$impdocaccept->fabriclooks.','.$impdocaccept->fabricshape.','.$impdocaccept->gsm_weight.','.$impdocaccept->fabric_color.','.$impdocaccept->dia.','.$impdocaccept->measurment;
                $impdocaccept->uom_code=$impdocaccept->uom_name?$impdocaccept->uom_code:$impdocaccept->so_uom_name;

                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                return $impdocaccept;
            });
        }
        //Dyeing Sales Order
        elseif ($production_area_id==20) {
            $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
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
                $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');
            $dyetype=array_prepend(config('bprs.dyetype'),'','');

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                so_dyeing_refs.id as so_dyeing_ref_id,
                so_dyeing_refs.so_dyeing_id,
                constructions.name as constructions_name,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_dyeing_service_item_qties.fabric_color_id,
                po_dyeing_service_item_qties.colorrange_id,
                so_dyeing_items.autoyarn_id as c_autoyarn_id,
                so_dyeing_items.fabric_look_id as c_fabric_look_id,
                so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
                so_dyeing_items.gmtspart_id as c_gmtspart_id,
                so_dyeing_items.gsm_weight as c_gsm_weight,
                so_dyeing_items.fabric_color_id as c_fabric_color_id,
                so_dyeing_items.colorrange_id as c_colorrange_id,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                sales_orders.sale_order_no,
                so_dyeing_items.gmt_sale_order_no,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                local_exp_invoice_orders.qty as invoice_qty,
                local_exp_invoice_orders.rate as invoice_rate,
                local_exp_invoice_orders.amount as invoice_amount
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_dyeing_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_dyeing_refs.id');
            })
            ->join('so_dyeings',function($join){
                $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
            })
            ->leftJoin('so_dyeing_pos',function($join){
                $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
            })
            ->leftJoin('so_dyeing_po_items',function($join){
                $join->on('so_dyeing_po_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
            })
            ->leftJoin('po_dyeing_service_item_qties',function($join){
                $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
            })
            ->leftJoin('po_dyeing_service_items',function($join){
                $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
            ->whereNull('po_dyeing_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','po_dyeing_service_item_qties.fabric_color_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
                $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
            })
            ->leftJoin('budget_fabrics',function($join){
                $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->leftJoin('style_fabrications',function($join){
                $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_dyeing_items',function($join){
                $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
            })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_dyeing_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=',$id]])
            ->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoice_orders.id',
                'so_dyeing_refs.id',
                'so_dyeing_refs.so_dyeing_id',
                'constructions.name',
                'style_fabrications.autoyarn_id',
                'style_fabrications.fabric_look_id',
                'style_fabrications.fabric_shape_id',
                'style_fabrications.gmtspart_id',
                'budget_fabrics.gsm_weight',
                'po_dyeing_service_item_qties.fabric_color_id',
                'po_dyeing_service_item_qties.colorrange_id',
                'so_dyeing_items.autoyarn_id',
                'so_dyeing_items.fabric_look_id',
                'so_dyeing_items.fabric_shape_id',
                'so_dyeing_items.gmtspart_id',
                'so_dyeing_items.gsm_weight',
                'so_dyeing_items.fabric_color_id',
                'so_dyeing_items.colorrange_id',
                'uoms.code',
                'so_uoms.code',
                'sales_orders.sale_order_no',
                'so_dyeing_items.gmt_sale_order_no',
                'local_exp_invoice_orders.qty',
                'local_exp_invoice_orders.rate',
                'local_exp_invoice_orders.amount'
            ])
            ->get()
            ->map(function ($impdocaccept) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$colorrange,$color,$dyetype){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->gmtspart=$impdocaccept->gmtspart_id?$gmtspart[$impdocaccept->gmtspart_id]:$gmtspart[$impdocaccept->c_gmtspart_id];
                $impdocaccept->fabriclooks=$impdocaccept->fabric_look_id?$fabriclooks[$impdocaccept->fabric_look_id]:$fabriclooks[$impdocaccept->c_fabric_look_id];
                $impdocaccept->fabricshape=$impdocaccept->fabric_shape_id?$fabricshape[$impdocaccept->fabric_shape_id]:$fabricshape[$impdocaccept->c_fabric_shape_id];
                $impdocaccept->gsm_weight=$impdocaccept->gsm_weight?$impdocaccept->gsm_weight:$impdocaccept->c_gsm_weight;
                $impdocaccept->fabric_color_name=$impdocaccept->fabric_color_id?$color[$impdocaccept->fabric_color_id]:$color[$impdocaccept->c_fabric_color_id];
                $impdocaccept->colorrange_id=$impdocaccept->colorrange_id?$colorrange[$impdocaccept->colorrange_id]:$colorrange[$impdocaccept->c_colorrange_id];
                $impdocaccept->dyeing_type=$impdocaccept->dyeing_type_id?$dyetype[$impdocaccept->dyeing_type_id]:$dyetype[$impdocaccept->c_dyeing_type_id];
                $impdocaccept->item_description=$impdocaccept->fabrication.','.$impdocaccept->gmtspart.','.$impdocaccept->fabriclooks.','.$impdocaccept->fabricshape.','.$impdocaccept->gsm_weight.','.$impdocaccept->fabric_color_name.','.$impdocaccept->colorrange_id.','.$impdocaccept->dyeing_type;
                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                $impdocaccept->uom_code=$impdocaccept->uom_name?$impdocaccept->uom_name:$impdocaccept->so_uom_name;

                return $impdocaccept;
            });

        }
        //AOP Sales Order
        elseif ($production_area_id==25) {
            $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
            $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'','');
            $autoyarn=$this->autoyarn
            ->leftJoin('autoyarnratios', function($join)  {
                $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('compositions',function($join){
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
                $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                so_aop_refs.id as so_aop_ref_id,
                so_aop_refs.so_aop_id,
                constructions.name as constructions_name,
                po_aop_service_item_qties.fabric_color_id,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_aop_service_item_qties.colorrange_id,
                so_aop_items.autoyarn_id as c_autoyarn_id,
                so_aop_items.fabric_look_id as c_fabric_look_id,
                so_aop_items.fabric_shape_id as c_fabric_shape_id,
                so_aop_items.gmtspart_id as c_gmtspart_id,
                so_aop_items.gsm_weight as c_gsm_weight,
                so_aop_items.fabric_color_id as c_fabric_color_id,
                so_aop_items.colorrange_id as c_colorrange_id,
                po_aop_service_item_qties.embelishment_type_id,
                po_aop_service_item_qties.coverage,
                po_aop_service_item_qties.impression,
                so_aop_items.embelishment_type_id as c_embelishment_type_id,
                so_aop_items.coverage as c_coverage,
                so_aop_items.impression as c_impression,
                sales_orders.sale_order_no,
                so_aop_items.gmt_sale_order_no,
                local_exp_pi_orders.sales_order_ref_id,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                local_exp_invoice_orders.qty as invoice_qty,
                local_exp_invoice_orders.rate as invoice_rate,
                local_exp_invoice_orders.amount as invoice_amount
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_aop_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_aop_refs.id');
            })
            ->join('so_aops',function($join){
                $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
            })
            ->leftJoin('so_aop_pos',function($join){
                $join->on('so_aop_pos.so_aop_id','=','so_aops.id');
            })
            ->leftJoin('so_aop_po_items',function($join){
                $join->on('so_aop_po_items.so_aop_ref_id','=','so_aop_refs.id');
            })
            ->leftJoin('po_aop_service_item_qties',function($join){
                $join->on('po_aop_service_item_qties.id','=','so_aop_po_items.po_aop_service_item_qty_id');
            })
            ->leftJoin('po_aop_service_items',function($join){
                $join->on('po_aop_service_items.id','=','po_aop_service_item_qties.po_aop_service_item_id')
            ->whereNull('po_aop_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_aop_service_item_qties.sales_order_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','po_aop_service_item_qties.fabric_color_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
                $join->on('budget_fabric_prods.id','=','po_aop_service_items.budget_fabric_prod_id');
            })
            ->leftJoin('budget_fabrics',function($join){
                $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->leftJoin('style_fabrications',function($join){
                $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_aop_items',function($join){
                $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
            })
            // ->leftJoin('buyers',function($join){
            //     $join->on('buyers.id','=','styles.buyer_id');
            // })
            // ->leftJoin('buyers as gmt_buyer',function($join){
            //     $join->on('gmt_buyer.id','=','so_aop_items.gmt_buyer');
            // })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_aop_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=', $id]])
            ->groupBy([
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoice_orders.id',
                'so_aop_refs.id',
                'so_aop_refs.so_aop_id',
                'constructions.name',
                'po_aop_service_item_qties.fabric_color_id',
                'style_fabrications.autoyarn_id',
                'style_fabrications.fabric_look_id',
                'style_fabrications.fabric_shape_id',
                'style_fabrications.gmtspart_id',
                'budget_fabrics.gsm_weight',
                'po_aop_service_item_qties.colorrange_id',
                'so_aop_items.autoyarn_id',
                'so_aop_items.fabric_look_id',
                'so_aop_items.fabric_shape_id',
                'so_aop_items.gmtspart_id',
                'so_aop_items.gsm_weight',
                'so_aop_items.fabric_color_id',
                'so_aop_items.colorrange_id',
                'po_aop_service_item_qties.embelishment_type_id',
                'po_aop_service_item_qties.coverage',
                'po_aop_service_item_qties.impression',
                'so_aop_items.embelishment_type_id',
                'so_aop_items.coverage',
                'so_aop_items.impression',
                'sales_orders.sale_order_no',
                'so_aop_items.gmt_sale_order_no',
                'local_exp_pi_orders.sales_order_ref_id',
                'uoms.code',
                'so_uoms.code',
                'local_exp_invoice_orders.qty',
                'local_exp_invoice_orders.rate',
                'local_exp_invoice_orders.amount',
            ])
            ->get()
            ->map(function ($impdocaccept) use($color,$gmtspart,$fabriclooks,$fabricshape,$colorrange,$color,$aoptype,$desDropdown){

                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->gmtspart=$impdocaccept->gmtspart_id?$gmtspart[$impdocaccept->gmtspart_id]:$gmtspart[$impdocaccept->c_gmtspart_id];
                $impdocaccept->fabriclooks=$impdocaccept->fabric_look_id?$fabriclooks[$impdocaccept->fabric_look_id]:$fabriclooks[$impdocaccept->c_fabric_look_id];
                $impdocaccept->fabricshape=$impdocaccept->fabric_shape_id?$fabricshape[$impdocaccept->fabric_shape_id]:$fabricshape[$impdocaccept->c_fabric_shape_id];
                $impdocaccept->gsm_weight=$impdocaccept->gsm_weight?$impdocaccept->gsm_weight:$impdocaccept->c_gsm_weight;
                $impdocaccept->fabric_color_name=$impdocaccept->fabric_color_id?$color[$impdocaccept->fabric_color_id]:$color[$impdocaccept->c_fabric_color_id];
                $impdocaccept->colorrange_id=$impdocaccept->colorrange_id?$colorrange[$impdocaccept->colorrange_id]:$colorrange[$impdocaccept->c_colorrange_id];
                $impdocaccept->embelishment_type_id=$impdocaccept->embelishment_type_id?$aoptype[$impdocaccept->embelishment_type_id]:$aoptype[$impdocaccept->c_embelishment_type_id];
                $impdocaccept->item_description=$impdocaccept->fabrication.','.$impdocaccept->gmtspart.','.$impdocaccept->fabriclooks.','.$impdocaccept->fabricshape.','.$impdocaccept->gsm_weight.','.$impdocaccept->fabric_color_name.','.$impdocaccept->colorrange_id.','.$impdocaccept->embelishment_type_id;
                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                $impdocaccept->uom_code=$impdocaccept->uom_name?$impdocaccept->uom_name:$impdocaccept->so_uom_name;

                return $impdocaccept;
            });
    
        }
        //Embelishment Work Order
        elseif ($production_area_id==45 ||$production_area_id==50 || $production_area_id==51) {
            $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');
            $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getEmbelishmentTypes(),'name','id'),'','');
            $embelishment=array_prepend(array_pluck($this->embelishment->get(),'name','id'),'','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
            $size=array_prepend(array_pluck($this->size->get(),'name','id'),'','');

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                so_emb_refs.id as so_emb_ref_id,
                so_emb_refs.so_emb_id,
                so_embs.sales_order_no,
                gmtsparts.id as gmtspart_id,
                so_emb_items.gmtspart_id as c_gmtspart_id,
                style_embelishments.embelishment_size_id,
                style_embelishments.embelishment_type_id,
                style_embelishments.embelishment_id,
                so_emb_items.embelishment_id as c_embelishment_id,
                so_emb_items.embelishment_type_id as c_embelishment_type_id,
                so_emb_items.embelishment_size_id as c_embelishment_size_id,
                so_emb_items.color_id as c_color_id,
                so_emb_items.size_id as c_size_id,
                sales_orders.sale_order_no,
                so_emb_items.gmt_sale_order_no,
                item_accounts.item_description,
                colors.name as gmt_color,
                sizes.name as gmt_size,
                so_uoms.code as so_uom_name,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                local_exp_invoice_orders.qty as invoice_qty,
                local_exp_invoice_orders.rate as invoice_rate,
                local_exp_invoice_orders.amount as invoice_amount
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->leftJoin('so_emb_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin('so_embs',function($join){
                $join->on('so_emb_refs.so_emb_id','=','so_embs.id');
            })
            ->leftJoin('so_emb_pos',function($join){
                $join->on('so_emb_pos.so_emb_id','=','so_embs.id');
            })
            ->leftJoin('so_emb_po_items',function($join){
                $join->on('so_emb_po_items.so_emb_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin('po_emb_service_item_qties',function($join){
                $join->on('po_emb_service_item_qties.id','=','so_emb_po_items.po_emb_service_item_qty_id');
            })
            ->leftJoin('po_emb_service_items',function($join){
                $join->on('po_emb_service_items.id','=','po_emb_service_item_qties.po_emb_service_item_id')
                ->whereNull('po_emb_service_items.deleted_at');
            })
            ->leftJoin('budget_embs',function($join){
                $join->on('budget_embs.id','=','po_emb_service_items.budget_emb_id');
            })
            ->leftJoin('style_embelishments',function($join){
                $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
            })
            ->leftJoin('style_gmts',function($join){
                $join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
            })
            ->leftJoin('item_accounts', function($join) {
                $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
            })
            ->leftJoin('gmtsparts',function($join){
                $join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
            })
            ->leftJoin('embelishments',function($join){
                $join->on('embelishments.id','=','style_embelishments.embelishment_id');
            })
            ->leftJoin('embelishment_types',function($join){
                $join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
            })
            ->leftJoin('budget_emb_cons',function($join){
                $join->on('budget_emb_cons.id','=','po_emb_service_item_qties.budget_emb_con_id')
                ->whereNull('budget_emb_cons.deleted_at');
            })
            ->leftJoin('sales_order_gmt_color_sizes',function($join){
                $join->on('sales_order_gmt_color_sizes.id','=','budget_emb_cons.sales_order_gmt_color_size_id');
            })
            ->leftJoin('sales_order_countries',function($join){
                $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','sales_order_countries.sale_order_id');
            })
            ->leftJoin('style_gmt_color_sizes',function($join){
                $join->on('style_gmt_color_sizes.id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('style_sizes',function($join){
                $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
            })
            ->leftJoin('sizes',function($join){
                $join->on('sizes.id','=','style_sizes.size_id');
            })
            ->leftJoin('style_colors',function($join){
                $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','style_colors.color_id');
            })
            ->leftJoin('so_emb_items',function($join){
                $join->on('so_emb_items.so_emb_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_emb_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=', $id]])
            ->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoice_orders.id',

                'so_emb_refs.id',
                'so_emb_refs.so_emb_id',
                'so_embs.sales_order_no',
                'gmtsparts.id',
                'so_emb_items.gmtspart_id',
                'style_embelishments.embelishment_size_id',
                'style_embelishments.embelishment_type_id',
                'style_embelishments.embelishment_id',
                'so_emb_items.embelishment_id',
                'so_emb_items.embelishment_type_id',
                'so_emb_items.embelishment_size_id',
                'so_emb_items.color_id',
                'so_emb_items.size_id',
                'sales_orders.sale_order_no',
                'so_emb_items.gmt_sale_order_no',
                'item_accounts.item_description',
                'colors.name',
                'sizes.name',
                'so_uoms.code',
                'local_exp_invoice_orders.qty',
                'local_exp_invoice_orders.rate',
                'local_exp_invoice_orders.amount',
            ])
            ->get()
            ->map(function ($impdocaccept) use($gmtspart,$embelishmentsize,$embelishmenttype,$embelishment,$color,$size){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->gmtspart=$impdocaccept->gmtspart_id?$gmtspart[$impdocaccept->gmtspart_id]:$gmtspart[$impdocaccept->c_gmtspart_id];
                $impdocaccept->emb_size=$impdocaccept->embelishment_size_id?$embelishmentsize[$impdocaccept->embelishment_size_id]:$embelishmentsize[$impdocaccept->c_embelishment_size_id];
                $impdocaccept->emb_name=$impdocaccept->embelishment_id?$embelishment[$impdocaccept->embelishment_id]:$embelishment[$impdocaccept->c_embelishment_id];
                $impdocaccept->gmt_color=$impdocaccept->gmt_color?$impdocaccept->gmt_color:$color[$impdocaccept->c_color_id];
                $impdocaccept->gmt_size=$impdocaccept->gmt_size?$impdocaccept->gmt_size:$size[$impdocaccept->c_size_id];
                $impdocaccept->item_description=$impdocaccept->item_description.','.$impdocaccept->emb_name.','.$impdocaccept->emb_size.','.$impdocaccept->gmtspart.','.$impdocaccept->gmt_color.','.$impdocaccept->gmt_size;
                $impdocaccept->dye_aop_type=$impdocaccept->embelishment_type_id?$embelishmenttype[$impdocaccept->embelishment_type_id]:$embelishmenttype[$impdocaccept->c_embelishment_type_id];
                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                $impdocaccept->uom_code='Pcs'?'Pcs':$impdocaccept->so_uom_name;

                return $impdocaccept;
            });
            
        }

        $amount=$impdocaccept->sum('invoice_amount');
        $currency=$localexpdocaccept['currency_id'];
        $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$currency,'cents');
        $localexpdocaccept['inword']=$inword;

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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
        $challan=str_pad($localexpdocaccept['local_exp_invoice_id'],10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
        $pdf->SetY(10);
        $image_file ='images/logo/'.$localexpdocaccept['logo'];
        $pdf->Image($image_file, 90, 5, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $pdf->SetY(13);
        $pdf->SetFont('helvetica', 'N', 9);
        //$pdf->Text(68, 16, $localexpdocaccept['company_address']);
        $pdf->Cell(0, 40, $localexpdocaccept['company_address'], 0, false, 'C', 0, '', 0, false, 'T', 'M' );
        //$pdf->SetY(16);
        $pdf->SetFont('helvetica', '', 8);
        $view= \View::make('Defult.Commercial.LocalExport.LocalExpDeliveryChallanPdf',['impdocaccept'=>$impdocaccept,'localexpdocaccept'=>$localexpdocaccept,'localpi'=>$localpi,'arrPi'=>$arrPi]);
        $html_content=$view->render();
        $pdf->SetY(35);
        
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/LocalExpDeliveryChallanPdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }
    
    public function getBOEPdf(){
        $id=request('id', 0);
        $localpi=$this->localexpdocaccept
        ->join('local_exp_doc_sub_invoices',function($join){
            $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            $join->whereNull('local_exp_doc_sub_invoices.deleted_at');
        })
        ->join('local_exp_invoices',function($join){
            $join->on('local_exp_invoices.id','=','local_exp_doc_sub_invoices.local_exp_invoice_id');
        })
        ->join('local_exp_invoice_orders',function($join){
            $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
            
            $join->whereNull('local_exp_invoice_orders.deleted_at');
        })
        ->join('local_exp_pi_orders', function($join)  {
            $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
        })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
        })
        ->join('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lc_tag_pis.local_exp_pi_id', '=', 'local_exp_pis.id'); 
        })
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_lc_tag_pis.local_exp_lc_id');
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->where([['local_exp_doc_sub_accepts.id','=',$id]])
        ->get([    
            'local_exp_doc_sub_accepts.id', 
            'local_exp_invoices.id as local_exp_invoice_id', 
            'local_exp_doc_sub_invoices.id as local_exp_doc_sub_invoice_id',
            'local_exp_invoices.local_invoice_no', 
            'local_exp_pis.id as local_exp_pi_id',
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
        ]);

        $arrPi=array();
        $arrInvoice=array();
	    foreach($localpi as $bar){
            $arrPi[$bar->local_exp_invoice_id][$bar->local_exp_pi_id]=$bar->pi_no.", Dated: ".date('d-M-Y',strtotime($bar->pi_date));
		    $arrInvoice[$bar->local_exp_invoice_id]=$bar->local_invoice_no;
        }


        $bankbranch=array_prepend(array_pluck(
            $this->bankbranch
            ->leftJoin('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->get([
                'bank_branches.id',
                'bank_branches.branch_name',
                'banks.name as bank_name',
            ])
            ->map(function($bankbranch){
                $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
                return $bankbranch;
            })
            ,'name','id'),'','');

        $localexpdocaccepts=$this->localexpdocaccept
        ->join('local_exp_doc_sub_invoices',function($join){
            $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            $join->whereNull('local_exp_doc_sub_invoices.deleted_at');
        })
        ->join('local_exp_invoices',function($join){
            $join->on('local_exp_invoices.id','=','local_exp_doc_sub_invoices.local_exp_invoice_id');
        })
        ->join('local_exp_invoice_orders',function($join){
            $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
            $join->whereNull('local_exp_invoice_orders.deleted_at');
        })
        ->join('local_exp_pi_orders', function($join)  {
            $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
        })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
        })
        ->join('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lc_tag_pis.local_exp_pi_id', '=', 'local_exp_pis.id'); 
        })
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_lc_tag_pis.local_exp_lc_id');
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->leftJoin('buyer_branches',function($join){
            $join->on('buyers.id','=','buyer_branches.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'local_exp_lcs.beneficiary_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        ->leftJoin(\DB::raw("(SELECT
        local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id,
            sum(local_exp_invoices.local_invoice_value) as cumulative_amount 
        FROM local_exp_doc_sub_accepts
            join local_exp_doc_sub_invoices on local_exp_doc_sub_accepts.id =local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id
            join local_exp_invoices on local_exp_invoices.id =local_exp_doc_sub_invoices.local_exp_invoice_id
        group by local_exp_doc_sub_accepts.id) cumulatives"), "cumulatives.local_exp_doc_sub_accept_id", "=", "local_exp_doc_sub_accepts.id")
        ->where([['local_exp_doc_sub_accepts.id','=',$id]])
       // ->where([['local_exp_doc_sub_accepts.id','=',$localexpdocacceptId]])
        ->get([            
            'local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id',
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.*',
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
            'buyers.name as buyer_name',
            'buyer_branches.address as buyer_address',
            'buyer_branches.email as buyer_email',
            'companies.name as beneficiary',
            'companies.address as company_address',
            'companies.logo',
            'currencies.name as currency_id',
            'currencies.symbol as currency_symbol',
            'local_exp_invoices.*',
            'local_exp_invoices.id as local_exp_invoice_id',
            //'local_exp_doc_sub_invoices.id as local_exp_doc_sub_invoice_id',
            'cumulatives.cumulative_amount'
        ]);
        foreach ($localexpdocaccepts as $rows) {
            $localexpdocaccept['id']=$rows->id;
            $localexpdocaccept['logo']=$rows->logo;
            $localexpdocaccept['beneficiary']=$rows->beneficiary;
            $localexpdocaccept['company_address']=$rows->company_address;
            $localexpdocaccept['exporter_bank_branch']=$bankbranch[$rows->exporter_bank_branch_id];
            $localexpdocaccept['invoice_amount']=$rows->invoice_amount;
            $localexpdocaccept['local_invoice_no']=$rows->local_invoice_no;
            $localexpdocaccept['cumulative_amount']=$rows->cumulative_amount;
            $localexpdocaccept['local_invoice_date']=date('d-M-Y',strtotime($rows->local_invoice_date));
            $localexpdocaccept['buyer_name']=$rows->buyer_name;
            $localexpdocaccept['buyer_address']=$rows->buyer_address;
            $localexpdocaccept['buyer_email']=$rows->buyer_email;
            $localexpdocaccept['buyers_bank']=$rows->buyers_bank;
            $localexpdocaccept['pi_no']=implode(" ; ",$arrPi[$rows->local_exp_invoice_id]);
            $localexpdocaccept['invoice_no']=implode(", ",$arrInvoice);
           // $localexpdocaccept['pi_date']=date('d-M-Y',strtotime($rows->pi_date));
            $localexpdocaccept['local_lc_no']=$rows->local_lc_no;
            $localexpdocaccept['lc_date']=date('d-M-Y',strtotime($rows->lc_date));
            $localexpdocaccept['customer_lc_sc']=$rows->customer_lc_sc;
            $localexpdocaccept['delivery_place']=$rows->delivery_place;
            $localexpdocaccept['currency_id']=$rows->currency_id;
            $localexpdocaccept['currency_symbol']=$rows->currency_symbol;
            $localexpdocaccept['tenor']=$rows->tenor;
        }
        $localexpdocaccept['master']=$rows;

        $amount=$localexpdocaccept['cumulative_amount'];
        $currency=$localexpdocaccept['currency_id'];
        $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$currency,'cents');
        $localexpdocaccept['inword']=$inword;

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(20, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
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
        $challan=str_pad($id,10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
        $pdf->SetY(10);
        $image_file ='images/logo/'.$localexpdocaccept['logo'];
        $pdf->Image($image_file, 90, 5, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $pdf->SetY(13);
        $pdf->SetFont('helvetica', 'N', 9);
        //$pdf->Text(68, 16, $localexpdocaccept['company_address']);
        $pdf->Cell(0, 40, $localexpdocaccept['company_address'], 0, false, 'C', 0, '', 0, false, 'T', 'M' );
        //$pdf->SetY(16);
        $pdf->SetFont('helvetica', '', 8);
        $view= \View::make('Defult.Commercial.LocalExport.LocalExpBillOfExchangePdf',['localexpdocaccept'=>$localexpdocaccept,'localpi'=>$localpi,'image_file'=>$image_file]);
        $html_content=$view->render();
        $pdf->SetY(35);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/LocalExpBillOfExchangePdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }

    public function getPLPdf(){
        $id=request('id', 0);
        $localpi=$this->localexpdocaccept
        ->join('local_exp_doc_sub_invoices',function($join){
            $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            $join->whereNull('local_exp_doc_sub_invoices.deleted_at');
        })
        ->join('local_exp_invoices',function($join){
            $join->on('local_exp_invoices.id','=','local_exp_doc_sub_invoices.local_exp_invoice_id');
        })
        ->join('local_exp_invoice_orders',function($join){
            $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
            
            $join->whereNull('local_exp_invoice_orders.deleted_at');
        })
        ->join('local_exp_pi_orders', function($join)  {
            $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
        })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
        })
        ->join('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lc_tag_pis.local_exp_pi_id', '=', 'local_exp_pis.id'); 
        })
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_lc_tag_pis.local_exp_lc_id');
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->where([['local_exp_doc_sub_accepts.id','=',$id]])
        ->get([    
            'local_exp_doc_sub_accepts.id', 
            'local_exp_invoices.id as local_exp_invoice_id', 
            'local_exp_doc_sub_invoices.id as local_exp_doc_sub_invoice_id',
            'local_exp_invoices.local_invoice_no', 
            'local_exp_pis.id as local_exp_pi_id',
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
        ]);

        $arrPi=array();
        $arrInvoice=array();
      //  $docinvoice=array();
	    foreach($localpi as $bar){
            $arrPi[$bar->local_exp_invoice_id][$bar->local_exp_pi_id]=$bar->pi_no.", Dated: ".date('d-M-Y',strtotime($bar->pi_date));
		    $arrInvoice[$bar->local_exp_invoice_id]=$bar->local_invoice_no;
		   // $docinvoice[$bar->id][$bar->local_exp_invoice_id]=$bar->local_exp_invoice_id;
        }

        $localexpdocaccepts=$this->localexpdocaccept
        ->join('local_exp_doc_sub_invoices',function($join){
            $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            $join->whereNull('local_exp_doc_sub_invoices.deleted_at');
        })
        ->join('local_exp_invoices',function($join){
            $join->on('local_exp_invoices.id','=','local_exp_doc_sub_invoices.local_exp_invoice_id');
        })
        ->join('local_exp_invoice_orders',function($join){
            $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
            
            $join->whereNull('local_exp_invoice_orders.deleted_at');
        })
        ->join('local_exp_pi_orders', function($join)  {
            $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
        })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
        })
        ->join('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lc_tag_pis.local_exp_pi_id', '=', 'local_exp_pis.id'); 
        })
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_lc_tag_pis.local_exp_lc_id');
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->leftJoin('buyer_branches',function($join){
            $join->on('buyers.id','=','buyer_branches.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'local_exp_lcs.beneficiary_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        
        ->where([['local_exp_doc_sub_accepts.id','=',$id]])
       // ->where([['local_exp_doc_sub_accepts.id','=',$localexpdocacceptId]])
        ->get([            
           'local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id',
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.*',
            'local_exp_pis.id as local_exp_pi_id',
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
            'buyers.name as buyer_name',
            'buyer_branches.address as buyer_address',
            'buyer_branches.email as buyer_email',
            'companies.name as beneficiary',
            'companies.address as company_address',
            'companies.logo',
            'currencies.name as currency_id',
            'local_exp_invoices.*',
            'local_exp_invoices.id as local_exp_invoice_id'
        ]);
        foreach ($localexpdocaccepts as $rows) {
            $localexpdocaccept['id']=$rows->local_exp_doc_sub_accept_id;
            $localexpdocaccept['local_exp_invoice_id']=$rows->local_exp_invoice_id;
            $localexpdocaccept['logo']=$rows->logo;
            $localexpdocaccept['beneficiary']=$rows->beneficiary;
            $localexpdocaccept['company_address']=$rows->company_address;
            $localexpdocaccept['local_invoice_no']=$rows->local_invoice_no;
            $localexpdocaccept['local_invoice_date']=date('d-M-Y',strtotime($rows->local_invoice_date));
            $localexpdocaccept['buyer_name']=$rows->buyer_name;
            $localexpdocaccept['buyer_address']=$rows->buyer_address;
            $localexpdocaccept['buyer_email']=$rows->buyer_email;
            $localexpdocaccept['buyers_bank']=$rows->buyers_bank;
            $localexpdocaccept['pi_no']=implode(" ; ",$arrPi[$rows->local_exp_invoice_id]);
            $localexpdocaccept['pi_date']=date('d-M-Y',strtotime($rows->pi_date));
            $localexpdocaccept['local_lc_no']=$rows->local_lc_no;
            $localexpdocaccept['lc_date']=date('d-M-Y',strtotime($rows->lc_date));
            $localexpdocaccept['customer_lc_sc']=$rows->customer_lc_sc;
            $localexpdocaccept['delivery_place']=$rows->delivery_place;
            $localexpdocaccept['currency_id']=$rows->currency_id;
        }
        $localexpdocaccept['master']=$rows;
       

        //$localexpinvoice=$this->localexpinvoice->find($localexpdocaccept['local_exp_invoice_id']);
        $localexpdocsubaccept=$this->localexpdocaccept->find($id);
        $localexplc=$this->localexplc->find($localexpdocsubaccept->local_exp_lc_id);
        $production_area_id=$localexplc->production_area_id;
        //Yarn Dyeing Sales Order
        if($production_area_id==5){
            $impdocaccept=$this->localexpdocaccept
            ->selectRaw('
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                sum(local_exp_pi_orders.qty) as pi_qty,
                sum(local_exp_pi_orders.amount) as pi_amount,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                local_exp_invoice_orders.qty as invoice_qty,
                local_exp_invoice_orders.rate as invoice_rate,
                local_exp_invoice_orders.amount as invoice_amount,
                cumulatives.cumulative_amount,
                cumulatives.cumulative_qty
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
                $join->on('local_exp_lcs.id', '=', 'local_exp_doc_sub_accepts.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            }) 
            ->join(\DB::raw("(SELECT
                    local_exp_pi_orders.id as local_exp_pi_order_id,
                    sum(local_exp_invoice_orders.qty) as cumulative_qty,
                    sum(local_exp_invoice_orders.amount) as cumulative_amount 
                FROM local_exp_invoice_orders
                    join local_exp_pi_orders on local_exp_pi_orders.id =local_exp_invoice_orders.local_exp_pi_order_id
                    join local_exp_invoices on  local_exp_invoices.id=local_exp_invoice_orders.local_exp_invoice_id
                where local_exp_invoice_orders.deleted_at is null
                group by local_exp_pi_orders.id) cumulatives"), "cumulatives.local_exp_pi_order_id", "=", "local_exp_pi_orders.id")
            ->leftJoin('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_doc_sub_accepts.id','=',$localexpdocsubaccept->id]])
            ->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoice_orders.id',
                'local_exp_invoice_orders.qty',
                'local_exp_invoice_orders.rate',
                'local_exp_invoice_orders.amount',
                'cumulatives.cumulative_amount',
                'cumulatives.cumulative_qty'
            ])
            ->get()
            ->map(function ($impdocaccept){
                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                $impdocaccept->pi_rate=$impdocaccept->pi_amount/$impdocaccept->pi_qty;
                $impdocaccept->balance_qty=$impdocaccept->qty-$impdocaccept->cumulative_qty;
                return $impdocaccept;
            });
        }
        //Knitting Sales Order
        elseif ($production_area_id==10) {
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
                $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }

            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');

            $impdocaccept=$this->localexpdocaccept
            ->selectRaw('
                local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id,
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                local_exp_invoices.local_invoice_no,
                so_knit_refs.id as sales_order_ref_id,
                so_knit_refs.so_knit_id,
                constructions.name as constructions_name,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_knit_service_item_qties.dia,
                po_knit_service_item_qties.measurment,
                so_knit_items.gmt_sale_order_no,
                so_knit_items.autoyarn_id as c_autoyarn_id,
                so_knit_items.fabric_look_id as c_fabric_look_id,
                so_knit_items.fabric_shape_id as c_fabric_shape_id,
                so_knit_items.gmtspart_id as c_gmtspart_id,
                so_knit_items.gsm_weight as c_gsm_weight,
                so_knit_items.dia as c_dia,
                so_knit_items.measurment as c_measurment,
                so_color.name as c_fabric_color_name,
                po_color.name as fabric_color_name,
                sales_orders.sale_order_no,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                sum(local_exp_invoice_orders.qty) as invoice_qty,
                avg(local_exp_invoice_orders.rate) as invoice_rate,
                sum(local_exp_invoice_orders.amount) as invoice_amount
            ')
            ->join('local_exp_doc_sub_invoices',function($join){
                $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            })
            ->join('local_exp_invoices',function($join){
                $join->on('local_exp_doc_sub_invoices.local_exp_invoice_id','=','local_exp_invoices.id');
            })
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
                $join->on('local_exp_doc_sub_accepts.local_exp_lc_id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_knit_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_knit_refs.id');
            })
            ->join('so_knits',function($join){
                $join->on('so_knit_refs.so_knit_id','=','so_knits.id');
            })
            ->leftJoin('so_knit_pos',function($join){
                $join->on('so_knit_pos.so_knit_id','=','so_knits.id');
            })
            ->leftJoin('so_knit_po_items',function($join){
                $join->on('so_knit_po_items.so_knit_ref_id','=','so_knit_refs.id');
            })
            ->leftJoin('po_knit_service_item_qties',function($join){
                $join->on('po_knit_service_item_qties.id','=','so_knit_po_items.po_knit_service_item_qty_id');
            })
            ->leftJoin('po_knit_service_items',function($join){
                $join->on('po_knit_service_items.id','=','po_knit_service_item_qties.po_knit_service_item_id')
                ->whereNull('po_knit_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_knit_service_item_qties.sales_order_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
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
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_knit_items',function($join){
                $join->on('so_knit_items.so_knit_ref_id','=','so_knit_refs.id');
            })
            // ->leftJoin('buyers',function($join){
            //     $join->on('buyers.id','=','styles.buyer_id');
            // })
            // ->leftJoin('buyers as gmt_buyer',function($join){
            //     $join->on('gmt_buyer.id','=','so_knit_items.gmt_buyer');
            // })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_knit_items.uom_id');
            })
            ->leftJoin('colors as so_color',function($join){
                $join->on('so_color.id','=','so_knit_items.fabric_color_id');
            })
            ->leftJoin('colors as po_color',function($join){
                $join->on('po_color.id','=','po_knit_service_item_qties.fabric_color_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            // ->join('local_exp_doc_sub_invoices',function($join){
            //     $join->on('local_exp_doc_sub_invoices.local_exp_invoice_id','=','local_exp_invoices.id');
            // })
            ->where([['local_exp_doc_sub_accepts.id','=',$localexpdocsubaccept->id]])
            ->orderBy('local_exp_invoices.id','desc')
            ->groupBy([
                'local_exp_doc_sub_accepts.id',
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoices.local_invoice_no',
                'local_exp_invoice_orders.id',
                'so_knit_refs.id',
                'so_knit_refs.so_knit_id',
                'constructions.name',
                'style_fabrications.autoyarn_id',
                'style_fabrications.fabric_look_id',
                'style_fabrications.fabric_shape_id',
                'style_fabrications.gmtspart_id',
                'budget_fabrics.gsm_weight',
                'po_knit_service_item_qties.dia',
                'po_knit_service_item_qties.measurment',
                'so_knit_items.gmt_sale_order_no',
                'so_knit_items.autoyarn_id',
                'so_knit_items.fabric_look_id',
                'so_knit_items.fabric_shape_id',
                'so_knit_items.gmtspart_id',
                'so_knit_items.gsm_weight',
                'so_knit_items.dia',
                'so_knit_items.measurment',
                'so_color.name',
                'po_color.name',
                'sales_orders.sale_order_no',
                'uoms.code',
                'so_uoms.code',
                // 'local_exp_invoice_orders.qty',
                // 'local_exp_invoice_orders.rate',
                // 'local_exp_invoice_orders.amount'
            ])
            ->get()
            ->map(function ($impdocaccept) use($gmtspart,$fabriclooks,$fabricshape,$desDropdown){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->gmtspart=$impdocaccept->gmtspart_id?$gmtspart[$impdocaccept->gmtspart_id]:$gmtspart[$impdocaccept->c_gmtspart_id];
                $impdocaccept->fabriclooks=$impdocaccept->fabric_look_id?$fabriclooks[$impdocaccept->fabric_look_id]:$fabriclooks[$impdocaccept->c_fabric_look_id];
                $impdocaccept->fabricshape=$impdocaccept->fabric_shape_id?$fabricshape[$impdocaccept->fabric_shape_id]:$fabricshape[$impdocaccept->c_fabric_shape_id];
                $impdocaccept->gsm_weight=$impdocaccept->gsm_weight?$impdocaccept->gsm_weight:$impdocaccept->c_gsm_weight;
                $impdocaccept->dia=$impdocaccept->dia?$impdocaccept->dia:$impdocaccept->c_dia;
                $impdocaccept->measurment=$impdocaccept->measurment?$impdocaccept->measurment:$impdocaccept->c_measurment;
                $impdocaccept->fabric_color=$impdocaccept->fabric_color_name?$impdocaccept->fabric_color_name:$impdocaccept->c_fabric_color_name;

                $impdocaccept->item_description=$impdocaccept->fabrication.','.$impdocaccept->gmtspart.','.$impdocaccept->fabriclooks.','.$impdocaccept->fabricshape.','.$impdocaccept->gsm_weight.','.$impdocaccept->fabric_color.','.$impdocaccept->dia.','.$impdocaccept->measurment;
                $impdocaccept->uom_code=$impdocaccept->uom_name?$impdocaccept->uom_code:$impdocaccept->so_uom_name;
                $impdocaccept->gross_qty=$impdocaccept->invoice_qty+($impdocaccept->invoice_qty*.001);
                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                return $impdocaccept;
            });
        }
        //Dyeing Sales Order
        elseif ($production_area_id==20) {
            $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
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
                $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');
            $dyetype=array_prepend(config('bprs.dyetype'),'','');

            $impdocaccept=$this->localexpdocaccept
            ->selectRaw('
                local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id,
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                local_exp_invoices.local_invoice_no,
                so_dyeing_refs.id as so_dyeing_ref_id,
                so_dyeing_refs.so_dyeing_id,
                constructions.name as constructions_name,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_dyeing_service_item_qties.fabric_color_id,
                po_dyeing_service_item_qties.colorrange_id,
                so_dyeing_items.autoyarn_id as c_autoyarn_id,
                so_dyeing_items.fabric_look_id as c_fabric_look_id,
                so_dyeing_items.fabric_shape_id as c_fabric_shape_id,
                so_dyeing_items.gmtspart_id as c_gmtspart_id,
                so_dyeing_items.gsm_weight as c_gsm_weight,
                so_dyeing_items.fabric_color_id as c_fabric_color_id,
                so_dyeing_items.colorrange_id as c_colorrange_id,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                sales_orders.sale_order_no,
                so_dyeing_items.gmt_sale_order_no,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                sum(local_exp_invoice_orders.qty) as invoice_qty,
                avg(local_exp_invoice_orders.rate) as invoice_rate,
                sum(local_exp_invoice_orders.amount) as invoice_amount
            ')
            ->join('local_exp_doc_sub_invoices',function($join){
                $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            })
            ->join('local_exp_invoices',function($join){
                $join->on('local_exp_doc_sub_invoices.local_exp_invoice_id','=','local_exp_invoices.id');
            })
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
                $join->on('local_exp_doc_sub_accepts.local_exp_lc_id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_dyeing_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_dyeing_refs.id');
            })
            ->join('so_dyeings',function($join){
                $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
            })
            ->leftJoin('so_dyeing_pos',function($join){
                $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
            })
            ->leftJoin('so_dyeing_po_items',function($join){
                $join->on('so_dyeing_po_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
            })
            ->leftJoin('po_dyeing_service_item_qties',function($join){
                $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
            })
            ->leftJoin('po_dyeing_service_items',function($join){
                $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
            ->whereNull('po_dyeing_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','po_dyeing_service_item_qties.fabric_color_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
                $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
            })
            ->leftJoin('budget_fabrics',function($join){
                $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->leftJoin('style_fabrications',function($join){
                $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_dyeing_items',function($join){
                $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
            })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_dyeing_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            //->where([['local_exp_doc_sub_invoices.local_exp_invoice_id','=',$localexpdocsubaccept->id]])
            ->where([['local_exp_doc_sub_accepts.id','=',$localexpdocsubaccept->id]])
            ->orderBy('local_exp_invoices.id','desc')
            ->groupBy([
                'local_exp_doc_sub_accepts.id',
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoices.local_invoice_no',
                'local_exp_invoice_orders.id',
                'so_dyeing_refs.id',
                'so_dyeing_refs.so_dyeing_id',
                'constructions.name',
                'po_dyeing_service_item_qties.fabric_color_id',
                'style_fabrications.autoyarn_id',
                'style_fabrications.fabric_look_id',
                'style_fabrications.fabric_shape_id',
                'style_fabrications.gmtspart_id',
                'budget_fabrics.gsm_weight',
                'po_dyeing_service_item_qties.colorrange_id',
                'so_dyeing_items.autoyarn_id',
                'so_dyeing_items.fabric_look_id',
                'so_dyeing_items.fabric_shape_id',
                'so_dyeing_items.gmtspart_id',
                'so_dyeing_items.gsm_weight',
                'so_dyeing_items.fabric_color_id',
                'so_dyeing_items.colorrange_id',
                'uoms.code',
                'so_uoms.code',
                'sales_orders.sale_order_no',
                'so_dyeing_items.gmt_sale_order_no',
                // 'local_exp_invoice_orders.qty',
                // 'local_exp_invoice_orders.rate',
                // 'local_exp_invoice_orders.amount'
            ])
            ->get()
            ->map(function ($impdocaccept) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$colorrange,$color,$dyetype){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->gmtspart=$impdocaccept->gmtspart_id?$gmtspart[$impdocaccept->gmtspart_id]:$gmtspart[$impdocaccept->c_gmtspart_id];
                $impdocaccept->fabriclooks=$impdocaccept->fabric_look_id?$fabriclooks[$impdocaccept->fabric_look_id]:$fabriclooks[$impdocaccept->c_fabric_look_id];
                $impdocaccept->fabricshape=$impdocaccept->fabric_shape_id?$fabricshape[$impdocaccept->fabric_shape_id]:$fabricshape[$impdocaccept->c_fabric_shape_id];
                $impdocaccept->gsm_weight=$impdocaccept->gsm_weight?$impdocaccept->gsm_weight:$impdocaccept->c_gsm_weight;
                $impdocaccept->fabric_color_name=$impdocaccept->fabric_color_id?$color[$impdocaccept->fabric_color_id]:$color[$impdocaccept->c_fabric_color_id];
                $impdocaccept->colorrange_id=$impdocaccept->colorrange_id?$colorrange[$impdocaccept->colorrange_id]:$colorrange[$impdocaccept->c_colorrange_id];
                $impdocaccept->dyeing_type=$impdocaccept->dyeing_type_id?$dyetype[$impdocaccept->dyeing_type_id]:$dyetype[$impdocaccept->c_dyeing_type_id];
                $impdocaccept->item_description=$impdocaccept->fabrication.','.$impdocaccept->gmtspart.','.$impdocaccept->fabriclooks.','.$impdocaccept->fabricshape.','.$impdocaccept->gsm_weight.','.$impdocaccept->fabric_color_name.','.$impdocaccept->colorrange_id.','.$impdocaccept->dyeing_type;
                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                $impdocaccept->uom_code=$impdocaccept->uom_name?$impdocaccept->uom_name:$impdocaccept->so_uom_name;
                $impdocaccept->gross_qty=$impdocaccept->invoice_qty+($impdocaccept->invoice_qty*.001);
                return $impdocaccept;
            });

        }
        //AOP Sales Order
        elseif ($production_area_id==25) {
            $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
            $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'','');
            $autoyarn=$this->autoyarn
            ->leftJoin('autoyarnratios', function($join)  {
                $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('compositions',function($join){
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
                $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');

            $impdocaccept=$this->localexpdocaccept
            ->selectRaw('
                local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id,
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                local_exp_invoices.local_invoice_no,
                so_aop_refs.id as so_aop_ref_id,
                so_aop_refs.so_aop_id,
                constructions.name as constructions_name,
                po_aop_service_item_qties.fabric_color_id,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                style_fabrications.gmtspart_id,
                budget_fabrics.gsm_weight,
                po_aop_service_item_qties.colorrange_id,
                so_aop_items.autoyarn_id as c_autoyarn_id,
                so_aop_items.fabric_look_id as c_fabric_look_id,
                so_aop_items.fabric_shape_id as c_fabric_shape_id,
                so_aop_items.gmtspart_id as c_gmtspart_id,
                so_aop_items.gsm_weight as c_gsm_weight,
                so_aop_items.fabric_color_id as c_fabric_color_id,
                so_aop_items.colorrange_id as c_colorrange_id,
                po_aop_service_item_qties.embelishment_type_id,
                po_aop_service_item_qties.coverage,
                po_aop_service_item_qties.impression,
                so_aop_items.embelishment_type_id as c_embelishment_type_id,
                so_aop_items.coverage as c_coverage,
                so_aop_items.impression as c_impression,
                sales_orders.sale_order_no,
                so_aop_items.gmt_sale_order_no,
                local_exp_pi_orders.sales_order_ref_id,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                sum(local_exp_invoice_orders.qty) as invoice_qty,
                avg(local_exp_invoice_orders.rate) as invoice_rate,
                sum(local_exp_invoice_orders.amount) as invoice_amount
            ')
            ->join('local_exp_doc_sub_invoices',function($join){
                $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            })
            ->join('local_exp_invoices',function($join){
                $join->on('local_exp_doc_sub_invoices.local_exp_invoice_id','=','local_exp_invoices.id');
            })
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
                $join->on('local_exp_doc_sub_accepts.local_exp_lc_id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_aop_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_aop_refs.id');
            })
            ->join('so_aops',function($join){
                $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
            })
            ->leftJoin('so_aop_pos',function($join){
                $join->on('so_aop_pos.so_aop_id','=','so_aops.id');
            })
            ->leftJoin('so_aop_po_items',function($join){
                $join->on('so_aop_po_items.so_aop_ref_id','=','so_aop_refs.id');
            })
            ->leftJoin('po_aop_service_item_qties',function($join){
                $join->on('po_aop_service_item_qties.id','=','so_aop_po_items.po_aop_service_item_qty_id');
            })
            ->leftJoin('po_aop_service_items',function($join){
                $join->on('po_aop_service_items.id','=','po_aop_service_item_qties.po_aop_service_item_id')
            ->whereNull('po_aop_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_aop_service_item_qties.sales_order_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','po_aop_service_item_qties.fabric_color_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
                $join->on('budget_fabric_prods.id','=','po_aop_service_items.budget_fabric_prod_id');
            })
            ->leftJoin('budget_fabrics',function($join){
                $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->leftJoin('style_fabrications',function($join){
                $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_aop_items',function($join){
                $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
            })
            // ->leftJoin('buyers',function($join){
            //     $join->on('buyers.id','=','styles.buyer_id');
            // })
            // ->leftJoin('buyers as gmt_buyer',function($join){
            //     $join->on('gmt_buyer.id','=','so_aop_items.gmt_buyer');
            // })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_aop_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_doc_sub_accepts.id','=',$localexpdocsubaccept->id]])
            ->orderBy('local_exp_invoices.id','desc')
            ->groupBy([
                'local_exp_doc_sub_accepts.id',
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoices.local_invoice_no',
                'local_exp_invoice_orders.id',
                'so_aop_refs.id',
                'so_aop_refs.so_aop_id',
                'constructions.name',
                'po_aop_service_item_qties.fabric_color_id',
                'style_fabrications.autoyarn_id',
                'style_fabrications.fabric_look_id',
                'style_fabrications.fabric_shape_id',
                'style_fabrications.gmtspart_id',
                'budget_fabrics.gsm_weight',
                'po_aop_service_item_qties.colorrange_id',
                'so_aop_items.autoyarn_id',
                'so_aop_items.fabric_look_id',
                'so_aop_items.fabric_shape_id',
                'so_aop_items.gmtspart_id',
                'so_aop_items.gsm_weight',
                'so_aop_items.fabric_color_id',
                'so_aop_items.colorrange_id',
                'po_aop_service_item_qties.embelishment_type_id',
                'po_aop_service_item_qties.coverage',
                'po_aop_service_item_qties.impression',
                'so_aop_items.embelishment_type_id',
                'so_aop_items.coverage',
                'so_aop_items.impression',
                'sales_orders.sale_order_no',
                'so_aop_items.gmt_sale_order_no',
                'local_exp_pi_orders.sales_order_ref_id',
                'uoms.code',
                'so_uoms.code',
                // 'local_exp_invoice_orders.qty',
                // 'local_exp_invoice_orders.rate',
                // 'local_exp_invoice_orders.amount',
            ])
            ->get()
            ->map(function ($impdocaccept) use($color,$gmtspart,$fabriclooks,$fabricshape,$colorrange,$color,$aoptype,$desDropdown){

                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->gmtspart=$impdocaccept->gmtspart_id?$gmtspart[$impdocaccept->gmtspart_id]:$gmtspart[$impdocaccept->c_gmtspart_id];
                $impdocaccept->fabriclooks=$impdocaccept->fabric_look_id?$fabriclooks[$impdocaccept->fabric_look_id]:$fabriclooks[$impdocaccept->c_fabric_look_id];
                $impdocaccept->fabricshape=$impdocaccept->fabric_shape_id?$fabricshape[$impdocaccept->fabric_shape_id]:$fabricshape[$impdocaccept->c_fabric_shape_id];
                $impdocaccept->gsm_weight=$impdocaccept->gsm_weight?$impdocaccept->gsm_weight:$impdocaccept->c_gsm_weight;
                $impdocaccept->fabric_color_name=$impdocaccept->fabric_color_id?$color[$impdocaccept->fabric_color_id]:$color[$impdocaccept->c_fabric_color_id];
                $impdocaccept->colorrange_id=$impdocaccept->colorrange_id?$colorrange[$impdocaccept->colorrange_id]:$colorrange[$impdocaccept->c_colorrange_id];
                $impdocaccept->embelishment_type_id=$impdocaccept->embelishment_type_id?$aoptype[$impdocaccept->embelishment_type_id]:$aoptype[$impdocaccept->c_embelishment_type_id];
                $impdocaccept->item_description=$impdocaccept->fabrication.','.$impdocaccept->gmtspart.','.$impdocaccept->fabriclooks.','.$impdocaccept->fabricshape.','.$impdocaccept->gsm_weight.','.$impdocaccept->fabric_color_name.','.$impdocaccept->colorrange_id.','.$impdocaccept->embelishment_type_id;
                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                $impdocaccept->uom_code=$impdocaccept->uom_name?$impdocaccept->uom_name:$impdocaccept->so_uom_name;
                $impdocaccept->gross_qty=$impdocaccept->invoice_qty+($impdocaccept->invoice_qty*.001);
                return $impdocaccept;
            });
    
        }
        //Embelishment Work Order
        elseif ($production_area_id==45 ||$production_area_id==50 || $production_area_id==51) {
            $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');
            $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getEmbelishmentTypes(),'name','id'),'','');
            $embelishment=array_prepend(array_pluck($this->embelishment->get(),'name','id'),'','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
            $size=array_prepend(array_pluck($this->size->get(),'name','id'),'','');

            $impdocaccept=$this->localexpdocaccept
            ->selectRaw('
                local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id,
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                local_exp_invoices.local_invoice_no,
                so_emb_refs.id as so_emb_ref_id,
                so_emb_refs.so_emb_id,
                so_embs.sales_order_no,
                gmtsparts.id as gmtspart_id,
                so_emb_items.gmtspart_id as c_gmtspart_id,
                style_embelishments.embelishment_size_id,
                style_embelishments.embelishment_type_id,
                style_embelishments.embelishment_id,
                so_emb_items.embelishment_id as c_embelishment_id,
                so_emb_items.embelishment_type_id as c_embelishment_type_id,
                so_emb_items.embelishment_size_id as c_embelishment_size_id,
                so_emb_items.color_id as c_color_id,
                so_emb_items.size_id as c_size_id,
                sales_orders.sale_order_no,
                so_emb_items.gmt_sale_order_no,
                item_accounts.item_description,
                colors.name as gmt_color,
                sizes.name as gmt_size,
                so_uoms.code as so_uom_name,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                sum(local_exp_invoice_orders.qty) as invoice_qty,
                avg(local_exp_invoice_orders.rate) as invoice_rate,
                sum(local_exp_invoice_orders.amount) as invoice_amount
            ')
            ->join('local_exp_doc_sub_invoices',function($join){
                $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            })
            ->join('local_exp_invoices',function($join){
                $join->on('local_exp_doc_sub_invoices.local_exp_invoice_id','=','local_exp_invoices.id');
            })
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
                $join->on('local_exp_doc_sub_accepts.local_exp_lc_id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->leftJoin('so_emb_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin('so_embs',function($join){
                $join->on('so_emb_refs.so_emb_id','=','so_embs.id');
            })
            ->leftJoin('so_emb_pos',function($join){
                $join->on('so_emb_pos.so_emb_id','=','so_embs.id');
            })
            ->leftJoin('so_emb_po_items',function($join){
                $join->on('so_emb_po_items.so_emb_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin('po_emb_service_item_qties',function($join){
                $join->on('po_emb_service_item_qties.id','=','so_emb_po_items.po_emb_service_item_qty_id');
            })
            ->leftJoin('po_emb_service_items',function($join){
                $join->on('po_emb_service_items.id','=','po_emb_service_item_qties.po_emb_service_item_id')
                ->whereNull('po_emb_service_items.deleted_at');
            })
            ->leftJoin('budget_embs',function($join){
                $join->on('budget_embs.id','=','po_emb_service_items.budget_emb_id');
            })
            ->leftJoin('style_embelishments',function($join){
                $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
            })
            ->leftJoin('style_gmts',function($join){
                $join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
            })
            ->leftJoin('item_accounts', function($join) {
                $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
            })
            ->leftJoin('gmtsparts',function($join){
                $join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
            })
            ->leftJoin('embelishments',function($join){
                $join->on('embelishments.id','=','style_embelishments.embelishment_id');
            })
            ->leftJoin('embelishment_types',function($join){
                $join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
            })
            ->leftJoin('budget_emb_cons',function($join){
                $join->on('budget_emb_cons.id','=','po_emb_service_item_qties.budget_emb_con_id')
                ->whereNull('budget_emb_cons.deleted_at');
            })
            ->leftJoin('sales_order_gmt_color_sizes',function($join){
                $join->on('sales_order_gmt_color_sizes.id','=','budget_emb_cons.sales_order_gmt_color_size_id');
            })
            ->leftJoin('sales_order_countries',function($join){
                $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','sales_order_countries.sale_order_id');
            })
            ->leftJoin('style_gmt_color_sizes',function($join){
                $join->on('style_gmt_color_sizes.id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('style_sizes',function($join){
                $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
            })
            ->leftJoin('sizes',function($join){
                $join->on('sizes.id','=','style_sizes.size_id');
            })
            ->leftJoin('style_colors',function($join){
                $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','style_colors.color_id');
            })
            ->leftJoin('so_emb_items',function($join){
                $join->on('so_emb_items.so_emb_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_emb_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_doc_sub_accepts.id','=',$localexpdocsubaccept->id]])
            ->orderBy('local_exp_invoices.id','desc')
            ->groupBy([
                'local_exp_doc_sub_accepts.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoices.local_invoice_no',
                'local_exp_invoice_orders.id',

                'so_emb_refs.id',
                'so_emb_refs.so_emb_id',
                'so_embs.sales_order_no',
                'gmtsparts.id',
                'so_emb_items.gmtspart_id',
                'style_embelishments.embelishment_size_id',
                'style_embelishments.embelishment_type_id',
                'style_embelishments.embelishment_id',
                'so_emb_items.embelishment_id',
                'so_emb_items.embelishment_type_id',
                'so_emb_items.embelishment_size_id',
                'so_emb_items.color_id',
                'so_emb_items.size_id',
                'sales_orders.sale_order_no',
                'so_emb_items.gmt_sale_order_no',
                'item_accounts.item_description',
                'colors.name',
                'sizes.name',
                'so_uoms.code',
                // 'local_exp_invoice_orders.qty',
                // 'local_exp_invoice_orders.rate',
                // 'local_exp_invoice_orders.amount',
            ])
            ->get()
            ->map(function ($impdocaccept) use($gmtspart,$embelishmentsize,$embelishmenttype,$embelishment,$color,$size){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->gmtspart=$impdocaccept->gmtspart_id?$gmtspart[$impdocaccept->gmtspart_id]:$gmtspart[$impdocaccept->c_gmtspart_id];
                $impdocaccept->emb_size=$impdocaccept->embelishment_size_id?$embelishmentsize[$impdocaccept->embelishment_size_id]:$embelishmentsize[$impdocaccept->c_embelishment_size_id];
                $impdocaccept->emb_name=$impdocaccept->embelishment_id?$embelishment[$impdocaccept->embelishment_id]:$embelishment[$impdocaccept->c_embelishment_id];
                $impdocaccept->gmt_color=$impdocaccept->gmt_color?$impdocaccept->gmt_color:$color[$impdocaccept->c_color_id];
                $impdocaccept->gmt_size=$impdocaccept->gmt_size?$impdocaccept->gmt_size:$size[$impdocaccept->c_size_id];
                $impdocaccept->item_description=$impdocaccept->item_description.','.$impdocaccept->emb_name.','.$impdocaccept->emb_size.','.$impdocaccept->gmtspart.','.$impdocaccept->gmt_color.','.$impdocaccept->gmt_size;
                $impdocaccept->dye_aop_type=$impdocaccept->embelishment_type_id?$embelishmenttype[$impdocaccept->embelishment_type_id]:$embelishmenttype[$impdocaccept->c_embelishment_type_id];
                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                $impdocaccept->uom_code='Pcs'?'Pcs':$impdocaccept->so_uom_name;
                $impdocaccept->gross_qty=$impdocaccept->invoice_qty+($impdocaccept->invoice_qty*.001);
                return $impdocaccept;
            });
            
        }

        

        // $amount=$impdocaccept->sum('invoice_amount');
        // $currency=$localexpdocaccept['currency_id'];
        // $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$currency,'cents');
        // $localexpdocaccept['inword']=$inword;

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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
        $challan=str_pad($id,10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
        $pdf->SetY(10);
        $image_file ='images/logo/'.$localexpdocaccept['logo'];
        $pdf->Image($image_file, 90, 5, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $pdf->SetY(13);
        $pdf->SetFont('helvetica', 'N', 9);
        //$pdf->Text(68, 16, $localexpdocaccept['company_address']);
        $pdf->Cell(0, 40, $localexpdocaccept['company_address'], 0, false, 'C', 0, '', 0, false, 'T', 'M' );
        //$pdf->SetY(16);
        $pdf->SetFont('helvetica', '', 8);
        $view= \View::make('Defult.Commercial.LocalExport.LocalExpPackingListPdf',['impdocaccept'=>$impdocaccept,'localexpdocaccept'=>$localexpdocaccept,'localpi'=>$localpi,'arrPi'=>$arrPi]);
        $html_content=$view->render();
        $pdf->SetY(35);
        
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/LocalExpPackingListPdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }

    public function getCOEPdf(){
        $id=request('id', 0);
        $localpi=$this->localexpdocaccept
        ->join('local_exp_doc_sub_invoices',function($join){
            $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            $join->whereNull('local_exp_doc_sub_invoices.deleted_at');
        })
        ->join('local_exp_invoices',function($join){
            $join->on('local_exp_invoices.id','=','local_exp_doc_sub_invoices.local_exp_invoice_id');
        })
        ->join('local_exp_invoice_orders',function($join){
            $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
            
            $join->whereNull('local_exp_invoice_orders.deleted_at');
        })
        ->join('local_exp_pi_orders', function($join)  {
            $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
        })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
        })
        ->join('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lc_tag_pis.local_exp_pi_id', '=', 'local_exp_pis.id'); 
        })
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_lc_tag_pis.local_exp_lc_id');
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->where([['local_exp_doc_sub_accepts.id','=',$id]])
        ->get([    
            'local_exp_doc_sub_accepts.id', 
            'local_exp_invoices.id as local_exp_invoice_id', 
            'local_exp_doc_sub_invoices.id as local_exp_doc_sub_invoice_id',
            'local_exp_invoices.local_invoice_no', 
            'local_exp_pis.id as local_exp_pi_id',
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
        ]);

        $arrPi=array();
        $arrInvoice=array();
	    foreach($localpi as $bar){
            $arrPi[$bar->local_exp_invoice_id][$bar->local_exp_pi_id]=$bar->pi_no.", Dated: ".date('d-M-Y',strtotime($bar->pi_date));
		    $arrInvoice[$bar->local_exp_invoice_id]=$bar->local_invoice_no;
        }


        $bankbranch=array_prepend(array_pluck(
            $this->bankbranch
            ->leftJoin('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->get([
                'bank_branches.id',
                'bank_branches.branch_name',
                'banks.name as bank_name',
            ])
            ->map(function($bankbranch){
                $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
                return $bankbranch;
            })
            ,'name','id'),'','');

        $localexpdocaccepts=$this->localexpdocaccept
        ->join('local_exp_doc_sub_invoices',function($join){
            $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            $join->whereNull('local_exp_doc_sub_invoices.deleted_at');
        })
        ->join('local_exp_invoices',function($join){
            $join->on('local_exp_invoices.id','=','local_exp_doc_sub_invoices.local_exp_invoice_id');
        })
        ->join('local_exp_invoice_orders',function($join){
            $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
            
            $join->whereNull('local_exp_invoice_orders.deleted_at');
        })
        ->join('local_exp_pi_orders', function($join)  {
            $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
        })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
        })
        ->join('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lc_tag_pis.local_exp_pi_id', '=', 'local_exp_pis.id'); 
        })
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_lc_tag_pis.local_exp_lc_id');
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->leftJoin('buyer_branches',function($join){
            $join->on('buyers.id','=','buyer_branches.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'local_exp_lcs.beneficiary_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        ->leftJoin(\DB::raw("(SELECT
        local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id,
            sum(local_exp_invoice_orders.amount) as cumulative_amount,
             sum(local_exp_invoice_orders.qty) as cumulative_qty
        FROM local_exp_doc_sub_accepts
            join local_exp_doc_sub_invoices on local_exp_doc_sub_accepts.id =local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id
            join local_exp_invoices on local_exp_invoices.id =local_exp_doc_sub_invoices.local_exp_invoice_id
            join local_exp_invoice_orders on local_exp_invoices.id =local_exp_invoice_orders.local_exp_invoice_id
            where local_exp_invoice_orders.deleted_at is null
        group by local_exp_doc_sub_accepts.id) cumulatives"), "cumulatives.local_exp_doc_sub_accept_id", "=", "local_exp_doc_sub_accepts.id")
        ->where([['local_exp_doc_sub_accepts.id','=',$id]])
       // ->where([['local_exp_doc_sub_accepts.id','=',$localexpdocacceptId]])
        ->get([            
            'local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id',
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.*',
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
            'buyers.name as buyer_name',
            'buyer_branches.address as buyer_address',
            'buyer_branches.email as buyer_email',
            'companies.name as beneficiary',
            'companies.address as company_address',
            'companies.logo',
            'currencies.name as currency_id',
            'currencies.symbol as currency_symbol',
            'local_exp_invoices.*',
            'local_exp_invoices.id as local_exp_invoice_id',
            //'local_exp_doc_sub_invoices.id as local_exp_doc_sub_invoice_id',
            'cumulatives.cumulative_qty',
            'cumulatives.cumulative_amount'
        ]);
        foreach ($localexpdocaccepts as $rows) {
            $localexpdocaccept['id']=$rows->id;
            $localexpdocaccept['logo']=$rows->logo;
            $localexpdocaccept['beneficiary']=$rows->beneficiary;
            $localexpdocaccept['company_address']=$rows->company_address;
            $localexpdocaccept['exporter_bank_branch']=$bankbranch[$rows->exporter_bank_branch_id];
            $localexpdocaccept['invoice_amount']=$rows->invoice_amount;
            $localexpdocaccept['local_invoice_no']=$rows->local_invoice_no;
            $localexpdocaccept['cumulative_qty']=$rows->cumulative_qty;
            $localexpdocaccept['cumulative_amount']=$rows->cumulative_amount;
            $localexpdocaccept['local_invoice_date']=date('d-M-Y',strtotime($rows->local_invoice_date));
            $localexpdocaccept['buyer_name']=$rows->buyer_name;
            $localexpdocaccept['buyer_address']=$rows->buyer_address;
            $localexpdocaccept['buyer_email']=$rows->buyer_email;
            $localexpdocaccept['buyers_bank']=$rows->buyers_bank;
            $localexpdocaccept['pi_no']=implode(" ; ",$arrPi[$rows->local_exp_invoice_id]);
            $localexpdocaccept['invoice_no']=implode("; ",$arrInvoice);
           // $localexpdocaccept['pi_date']=date('d-M-Y',strtotime($rows->pi_date));
            $localexpdocaccept['local_lc_no']=$rows->local_lc_no;
            $localexpdocaccept['lc_date']=date('d-M-Y',strtotime($rows->lc_date));
            $localexpdocaccept['customer_lc_sc']=$rows->customer_lc_sc;
            $localexpdocaccept['delivery_place']=$rows->delivery_place;
            $localexpdocaccept['currency_id']=$rows->currency_id;
            $localexpdocaccept['currency_symbol']=$rows->currency_symbol;
            $localexpdocaccept['tenor']=$rows->tenor;
        }
        $localexpdocaccept['master']=$rows;

        $amount=$localexpdocaccept['cumulative_amount'];
        $currency=$localexpdocaccept['currency_id'];
        $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$currency,'cents');
        $localexpdocaccept['inword']=$inword;

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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
        $challan=str_pad($id,10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
        $pdf->SetY(10);
        $image_file ='images/logo/'.$localexpdocaccept['logo'];
        $pdf->Image($image_file, 90, 5, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $pdf->SetY(13);
        $pdf->SetFont('helvetica', 'N', 9);
        //$pdf->Text(68, 16, $localexpdocaccept['company_address']);
        $pdf->Cell(0, 40, $localexpdocaccept['company_address'], 0, false, 'C', 0, '', 0, false, 'T', 'M' );
        $pdf->SetY(16);
        $pdf->SetFont('helvetica', '', 8);
        $view= \View::make('Defult.Commercial.LocalExport.LocalExpCertificateOfOriginPdf',['localexpdocaccept'=>$localexpdocaccept]);
        $html_content=$view->render();
        $pdf->SetY(55);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/LocalExpCertificateOfOriginPdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }

    public function getBCPdf(){
        $id=request('id', 0);
        $localpi=$this->localexpdocaccept
        ->join('local_exp_doc_sub_invoices',function($join){
            $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            $join->whereNull('local_exp_doc_sub_invoices.deleted_at');
        })
        ->join('local_exp_invoices',function($join){
            $join->on('local_exp_invoices.id','=','local_exp_doc_sub_invoices.local_exp_invoice_id');
        })
        ->join('local_exp_invoice_orders',function($join){
            $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
            
            $join->whereNull('local_exp_invoice_orders.deleted_at');
        })
        ->join('local_exp_pi_orders', function($join)  {
            $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
        })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
        })
        ->join('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lc_tag_pis.local_exp_pi_id', '=', 'local_exp_pis.id'); 
        })
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_lc_tag_pis.local_exp_lc_id');
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->where([['local_exp_doc_sub_accepts.id','=',$id]])
        ->get([    
            'local_exp_doc_sub_accepts.id', 
            'local_exp_invoices.id as local_exp_invoice_id', 
            'local_exp_doc_sub_invoices.id as local_exp_doc_sub_invoice_id',
            'local_exp_invoices.local_invoice_no', 
            'local_exp_pis.id as local_exp_pi_id',
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
        ]);

        $arrPi=array();
        $arrInvoice=array();
	    foreach($localpi as $bar){
            $arrPi[$bar->local_exp_invoice_id][$bar->local_exp_pi_id]=$bar->pi_no.", Dated: ".date('d-M-Y',strtotime($bar->pi_date));
		    $arrInvoice[$bar->local_exp_invoice_id]=$bar->local_invoice_no;
        }


        $bankbranch=array_prepend(array_pluck(
            $this->bankbranch
            ->leftJoin('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->get([
                'bank_branches.id',
                'bank_branches.branch_name',
                'banks.name as bank_name',
            ])
            ->map(function($bankbranch){
                $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
                return $bankbranch;
            })
            ,'name','id'),'','');

        $localexpdocaccepts=$this->localexpdocaccept
        ->join('local_exp_doc_sub_invoices',function($join){
            $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            $join->whereNull('local_exp_doc_sub_invoices.deleted_at');
        })
        ->join('local_exp_invoices',function($join){
            $join->on('local_exp_invoices.id','=','local_exp_doc_sub_invoices.local_exp_invoice_id');
        })
        ->join('local_exp_invoice_orders',function($join){
            $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
            $join->whereNull('local_exp_invoice_orders.deleted_at');
        })
        ->join('local_exp_pi_orders', function($join)  {
            $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
        })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
        })
        ->join('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lc_tag_pis.local_exp_pi_id', '=', 'local_exp_pis.id'); 
        })
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_lc_tag_pis.local_exp_lc_id');
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->leftJoin('buyer_branches',function($join){
            $join->on('buyers.id','=','buyer_branches.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'local_exp_lcs.beneficiary_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        ->where([['local_exp_doc_sub_accepts.id','=',$id]])
        ->get([            
            'local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id',
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.*',
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
            'buyers.name as buyer_name',
            'buyer_branches.address as buyer_address',
            'buyer_branches.email as buyer_email',
            'companies.name as beneficiary',
            'companies.address as company_address',
            'companies.logo',
            'currencies.name as currency_id',
            'currencies.symbol as currency_symbol',
            'local_exp_invoices.*',
            'local_exp_invoices.id as local_exp_invoice_id',
            //'local_exp_doc_sub_invoices.id as local_exp_doc_sub_invoice_id',
        ]);
        foreach ($localexpdocaccepts as $rows) {
            $localexpdocaccept['id']=$rows->id;
            $localexpdocaccept['logo']=$rows->logo;
            $localexpdocaccept['beneficiary']=$rows->beneficiary;
            $localexpdocaccept['company_address']=$rows->company_address;
            $localexpdocaccept['exporter_bank_branch']=$bankbranch[$rows->exporter_bank_branch_id];
            $localexpdocaccept['invoice_amount']=$rows->invoice_amount;
            $localexpdocaccept['local_invoice_no']=$rows->local_invoice_no;
            $localexpdocaccept['local_invoice_date']=date('d-M-Y',strtotime($rows->local_invoice_date));
            $localexpdocaccept['buyer_name']=$rows->buyer_name;
            $localexpdocaccept['buyer_address']=$rows->buyer_address;
            $localexpdocaccept['buyer_email']=$rows->buyer_email;
            $localexpdocaccept['buyers_bank']=$rows->buyers_bank;
            $localexpdocaccept['production_area_id']=$rows->production_area_id;
            $localexpdocaccept['pi_no']=implode(" ; ",$arrPi[$rows->local_exp_invoice_id]);
            $localexpdocaccept['invoice_no']=implode(", ",$arrInvoice);
           // $localexpdocaccept['pi_date']=date('d-M-Y',strtotime($rows->pi_date));
            $localexpdocaccept['local_lc_no']=$rows->local_lc_no;
            $localexpdocaccept['lc_date']=date('d-M-Y',strtotime($rows->lc_date));
            $localexpdocaccept['customer_lc_sc']=$rows->customer_lc_sc;

        }
        $localexpdocaccept['master']=$rows;

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(25, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
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
          //$pdf->SetY(5);
        $pdf->SetX(185);
        $qrc ='Value :'.$rows['cumulative_amount']." ,\n".
                'LC No :'.$rows['local_lc_no']." ,\n".
                'LC Date :'.$rows['lc_date']." ,\n".
                'Customer :'.$rows['buyer_name']." ,\n".
                'Company :'.$rows['beneficiary']." ,\n".
                'Submitted By :'.$rows['submitted_by']." ,\n".
                'Invoice No :'.$localexpdocaccept['invoice_no']." ,\n".
                'Bank :'.$rows['buyers_bank'];
        //  $pdf->write2DBarcode($qrc, 'QRCODE,Q', 180, 3, 45, 20, $barcodestyle, 'N');
        $pdf->write2DBarcode($qrc, 'QRCODE,Q', 170, 230, 70, 65, $barcodestyle, 'N');
        $pdf->SetFont('helvetica', 'N', 8);
          //$pdf->Text(170, 250, 'FAMKAM ERP');
        $pdf->Text(170, 254, 'ID :'.$id);
        $pdf->SetY(40);
        $image_file ='images/logo/'.$localexpdocaccept['logo'];
        $pdf->Image($image_file, 90, 5, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $pdf->SetY(13);
        $pdf->SetFont('helvetica', 'N', 9);
        //$pdf->Text(68, 16, $localexpdocaccept['company_address']);
        $pdf->Cell(0, 40, $localexpdocaccept['company_address'], 0, false, 'C', 0, '', 0, false, 'T', 'M' );
        $pdf->SetFont('helvetica', '', 8);
        $view= \View::make('Defult.Commercial.LocalExport.LocalExpBnfCertificatePdf',['localexpdocaccept'=>$localexpdocaccept,'localpi'=>$localpi]);
        $html_content=$view->render();
        $pdf->SetY(35);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/LocalExpBnfCertificatePdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }

    public function getForwardLetterPdf(){
        $id=request('id', 0);
        $localpi=$this->localexpdocaccept
        ->join('local_exp_doc_sub_invoices',function($join){
            $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            $join->whereNull('local_exp_doc_sub_invoices.deleted_at');
        })
        ->join('local_exp_invoices',function($join){
            $join->on('local_exp_invoices.id','=','local_exp_doc_sub_invoices.local_exp_invoice_id');
        })
        ->join('local_exp_invoice_orders',function($join){
            $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
            
            $join->whereNull('local_exp_invoice_orders.deleted_at');
        })
        ->join('local_exp_pi_orders', function($join)  {
            $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
        })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
        })
        ->join('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lc_tag_pis.local_exp_pi_id', '=', 'local_exp_pis.id'); 
        })
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_lc_tag_pis.local_exp_lc_id');
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->where([['local_exp_doc_sub_accepts.id','=',$id]])
        ->get([    
            'local_exp_doc_sub_accepts.id', 
            'local_exp_invoices.id as local_exp_invoice_id', 
            'local_exp_doc_sub_invoices.id as local_exp_doc_sub_invoice_id',
            'local_exp_invoices.local_invoice_no', 
            'local_exp_pis.id as local_exp_pi_id',
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
        ]);

        $arrPi=array();
        $arrInvoice=array();
	    foreach($localpi as $bar){
            $arrPi[$bar->local_exp_invoice_id][$bar->local_exp_pi_id]=$bar->pi_no.", Dated: ".date('d-M-Y',strtotime($bar->pi_date));
		    $arrInvoice[$bar->local_exp_invoice_id]=$bar->local_invoice_no;
        }


        $bankbranch=array_prepend(array_pluck(
            $this->bankbranch
            ->leftJoin('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->get([
                'bank_branches.id',
                'bank_branches.branch_name',
                'banks.name as bank_name',
            ])
            ->map(function($bankbranch){
                $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
                return $bankbranch;
            })
            ,'name','id'),'','');

        $localexpdocaccepts=$this->localexpdocaccept
        ->join('local_exp_doc_sub_invoices',function($join){
            $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            $join->whereNull('local_exp_doc_sub_invoices.deleted_at');
        })
        ->join('local_exp_invoices',function($join){
            $join->on('local_exp_invoices.id','=','local_exp_doc_sub_invoices.local_exp_invoice_id');
        })
        ->join('local_exp_invoice_orders',function($join){
            $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
            
            $join->whereNull('local_exp_invoice_orders.deleted_at');
        })
        ->join('local_exp_pi_orders', function($join)  {
            $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
        })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
        })
        ->join('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lc_tag_pis.local_exp_pi_id', '=', 'local_exp_pis.id'); 
        })
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_lc_tag_pis.local_exp_lc_id');
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->leftJoin('buyer_branches',function($join){
            $join->on('buyers.id','=','buyer_branches.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'local_exp_lcs.beneficiary_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        ->leftJoin(\DB::raw("(SELECT
        local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id,
            sum(local_exp_invoices.local_invoice_value) as cumulative_amount
        FROM local_exp_doc_sub_accepts
            join local_exp_doc_sub_invoices on local_exp_doc_sub_accepts.id =local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id
            join local_exp_invoices on local_exp_invoices.id =local_exp_doc_sub_invoices.local_exp_invoice_id
        group by local_exp_doc_sub_accepts.id) cumulatives"), "cumulatives.local_exp_doc_sub_accept_id", "=", "local_exp_doc_sub_accepts.id")
        ->where([['local_exp_doc_sub_accepts.id','=',$id]])
        ->get([            
            'local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id',
            'local_exp_doc_sub_accepts.submitted_by',
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.*',
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
            'buyers.name as buyer_name',
            'buyer_branches.address as buyer_address',
            'buyer_branches.email as buyer_email',
            'companies.name as beneficiary',
            'companies.address as company_address',
            'companies.logo',
            'currencies.name as currency_id',
            'currencies.symbol as currency_symbol',
            'local_exp_invoices.id as local_exp_invoice_id',
            'local_exp_invoices.local_exp_lc_id',
            'local_exp_invoices.local_invoice_no',
            'local_exp_invoices.local_invoice_date',
            'local_exp_invoices.actual_delivery_date',
            'cumulatives.cumulative_amount',
            'currencies.symbol as currency_symbol',
            //'local_exp_doc_sub_invoices.id as local_exp_doc_sub_invoice_id',
        ]);
        foreach ($localexpdocaccepts as $rows) {
             $localexpdocaccept['local_exp_doc_sub_accept_id']=$rows->local_exp_doc_sub_accept_id;
            $localexpdocaccept['logo']=$rows->logo;
            $localexpdocaccept['beneficiary']=$rows->beneficiary;
            $localexpdocaccept['company_address']=$rows->company_address;
            $localexpdocaccept['exporter_bank_branch']=$bankbranch[$rows->exporter_bank_branch_id];
            //$localexpdocaccept['local_invoice_no']=$rows->local_invoice_no;
            $localexpdocaccept['local_invoice_date']=date('d-M-Y',strtotime($rows->local_invoice_date));
            $localexpdocaccept['buyer_name']=$rows->buyer_name;
            $localexpdocaccept['buyer_address']=$rows->buyer_address;
            $localexpdocaccept['buyer_email']=$rows->buyer_email;
            $localexpdocaccept['buyers_bank']=$rows->buyers_bank;
            $localexpdocaccept['submitted_by']=$rows->submitted_by;
            $localexpdocaccept['pi_no']=implode(" ; ",$arrPi[$rows->local_exp_invoice_id]);
            $localexpdocaccept['invoice_no']=implode(", ",$arrInvoice);
           // $localexpdocaccept['pi_date']=date('d-M-Y',strtotime($rows->pi_date));
            $localexpdocaccept['local_lc_no']=$rows->local_lc_no;
            $localexpdocaccept['lc_date']=date('d-M-Y',strtotime($rows->lc_date));
            $localexpdocaccept['customer_lc_sc']=$rows->customer_lc_sc;
            $localexpdocaccept['currency_symbol']=$rows->currency_symbol;
            $localexpdocaccept['cumulative_amount']=$rows->cumulative_amount;

        }
        $localexpdocaccept['master']=$rows;

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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
        //$pdf->SetY(5);
        $pdf->SetX(185);
        $qrc ='Value :'.$rows['cumulative_amount']." ,\n".
              'LC No :'.$rows['local_lc_no']." ,\n".
              'LC Date :'.$rows['lc_date']." ,\n".
              'Customer :'.$rows['buyer_name']." ,\n".
              'Company :'.$rows['beneficiary']." ,\n".
              'Submitted By :'.$rows['submitted_by']." ,\n".
              'Invoice No :'.$localexpdocaccept['invoice_no']." ,\n".
              'Bank :'.$rows['buyers_bank'];
      //  $pdf->write2DBarcode($qrc, 'QRCODE,Q', 180, 3, 45, 20, $barcodestyle, 'N');
        $pdf->write2DBarcode($qrc, 'QRCODE,Q', 170, 230, 70, 65, $barcodestyle, 'N');
        $pdf->SetFont('helvetica', 'N', 8);
        //$pdf->Text(170, 250, 'FAMKAM ERP');
        $pdf->Text(170, 254, 'ID :'.$id);
        $pdf->SetY(40);
        $image_file ='images/logo/'.$localexpdocaccept['logo'];
        $pdf->Image($image_file, 90, 5, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $pdf->SetY(13);
        $pdf->SetFont('helvetica', 'N', 9);
        //$pdf->Text(68, 16, $localexpdocaccept['company_address']);
        $pdf->Cell(0, 40, $localexpdocaccept['company_address'], 0, false, 'C', 0, '', 0, false, 'T', 'M' );
        //$pdf->SetY(16);
        $pdf->SetFont('helvetica', '', 8);
        $view= \View::make('Defult.Commercial.LocalExport.LocalExpForwardingLetterPdf',['localexpdocaccept'=>$localexpdocaccept,'localpi'=>$localpi]);
        $html_content=$view->render();
        $pdf->SetY(40);  
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/LocalExpForwardingLetterPdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }

    //short pdf
    public function getCIShortPdf(){
        $id=request('local_exp_invoice_id', 0);

        $localpi=$this->localexpinvoice
        ->join('local_exp_invoice_orders',function($join){
             $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
             $join->whereNull('local_exp_invoice_orders.deleted_at');
         })
        ->join('local_exp_pi_orders',function($join){
             $join->on('local_exp_pi_orders.id','=','local_exp_invoice_orders.local_exp_pi_order_id');
         })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pis.id', '=', 'local_exp_pi_orders.local_exp_pi_id');
        })
        ->where([['local_exp_invoices.id','=',$id]])
        ->get([    
            'local_exp_invoices.id as local_exp_invoice_id', 
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
        ]);

        $arrPi=array();
	    foreach($localpi as $bar){
		    $arrPi[$bar->local_exp_invoice_id][$bar->pi_no]=$bar->pi_no.", Dated: ".date('d-M-Y',strtotime($bar->pi_date));
        }

        $localexpdocaccepts=$this->localexpinvoice
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_invoices.local_exp_lc_id');
         })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->leftJoin('buyer_branches',function($join){
            $join->on('buyers.id','=','buyer_branches.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'local_exp_lcs.beneficiary_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        ->join('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_lc_tag_pis.local_exp_lc_id');
        })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
        })
        ->where([['local_exp_invoices.id','=',$id]])
       // ->where([['local_exp_doc_sub_accepts.id','=',$localexpdocacceptId]])
        ->get([            
           // 'local_exp_doc_sub_accepts.id',
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.*',
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
            'buyers.name as buyer_name',
            'buyer_branches.address as buyer_address',
            'buyer_branches.email as buyer_email',
            'companies.name as beneficiary',
            'companies.address as company_address',
            'companies.logo',
            'currencies.name as currency_id',
            'local_exp_invoices.*',
            'local_exp_invoices.id as local_exp_invoice_id'
        ]);
        foreach ($localexpdocaccepts as $rows) {
            $localexpdocaccept['local_exp_invoice_id']=$rows->id;
            $localexpdocaccept['logo']=$rows->logo;
            $localexpdocaccept['beneficiary']=$rows->beneficiary;
            $localexpdocaccept['company_address']=$rows->company_address;
            $localexpdocaccept['local_invoice_no']=$rows->local_invoice_no;
            $localexpdocaccept['local_invoice_date']=date('d-M-Y',strtotime($rows->local_invoice_date));
            $localexpdocaccept['buyer_name']=$rows->buyer_name;
            $localexpdocaccept['buyer_address']=$rows->buyer_address;
            $localexpdocaccept['buyer_email']=$rows->buyer_email;
            $localexpdocaccept['buyers_bank']=$rows->buyers_bank;
            $localexpdocaccept['pi_no']=implode(" ; ",$arrPi[$rows->local_exp_invoice_id]);
           // $localexpdocaccept['pi_date']=date('d-M-Y',strtotime($rows->pi_date));
            $localexpdocaccept['local_lc_no']=$rows->local_lc_no;
            $localexpdocaccept['lc_date']=date('d-M-Y',strtotime($rows->lc_date));
            $localexpdocaccept['customer_lc_sc']=$rows->customer_lc_sc;
            $localexpdocaccept['delivery_place']=$rows->delivery_place;
            $localexpdocaccept['currency_id']=$rows->currency_id;
        }
        $localexpdocaccept['master']=$rows;


        $localexpinvoice=$this->localexpinvoice->find($id);
        $localexplc=$this->localexplc->find($localexpinvoice->local_exp_lc_id);
        $production_area_id=$localexplc->production_area_id;
         //Yarn Dyeing Sales Order
         if($production_area_id==5){
            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                sum(local_exp_pi_orders.qty) as pi_qty,
                sum(local_exp_pi_orders.amount) as pi_amount,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                local_exp_invoice_orders.qty as invoice_qty,
                local_exp_invoice_orders.rate as invoice_rate,
                local_exp_invoice_orders.amount as invoice_amount,
                cumulatives.cumulative_amount,
                cumulatives.cumulative_qty
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
            }) 
            ->join(\DB::raw("(SELECT
                    local_exp_pi_orders.id as local_exp_pi_order_id,
                    sum(local_exp_invoice_orders.qty) as cumulative_qty,
                    sum(local_exp_invoice_orders.amount) as cumulative_amount 
                FROM local_exp_invoice_orders
                    join local_exp_pi_orders on local_exp_pi_orders.id =local_exp_invoice_orders.local_exp_pi_order_id
                    join local_exp_invoices on  local_exp_invoices.id=local_exp_invoice_orders.local_exp_invoice_id
                where local_exp_invoice_orders.deleted_at is null
                group by local_exp_pi_orders.id) cumulatives"), "cumulatives.local_exp_pi_order_id", "=", "local_exp_pi_orders.id")
            ->leftJoin('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=',$id]])
            ->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoice_orders.id',
                'local_exp_invoice_orders.qty',
                'local_exp_invoice_orders.rate',
                'local_exp_invoice_orders.amount',
                'cumulatives.cumulative_amount',
                'cumulatives.cumulative_qty'
            ])
            ->get()
            ->map(function ($impdocaccept){
                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                $impdocaccept->pi_rate=$impdocaccept->pi_amount/$impdocaccept->pi_qty;
                $impdocaccept->balance_qty=$impdocaccept->qty-$impdocaccept->cumulative_qty;
                return $impdocaccept;
            });


        }
        //Knitting Sales Order
        elseif ($production_area_id==10) {
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
            $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }

            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_invoices.id as local_exp_invoice_id,
                style_fabrications.autoyarn_id,
                so_knit_items.gmt_sale_order_no,
                so_knit_items.autoyarn_id as c_autoyarn_id,
                sales_orders.sale_order_no,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                sum(local_exp_invoice_orders.qty) as invoice_qty,
                avg(local_exp_invoice_orders.rate) as invoice_rate,
                sum(local_exp_invoice_orders.amount) as invoice_amount
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_knit_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_knit_refs.id');
            })
            ->join('so_knits',function($join){
                $join->on('so_knit_refs.so_knit_id','=','so_knits.id');
            })
            ->leftJoin('so_knit_pos',function($join){
                $join->on('so_knit_pos.so_knit_id','=','so_knits.id');
            })
            ->leftJoin('so_knit_po_items',function($join){
                $join->on('so_knit_po_items.so_knit_ref_id','=','so_knit_refs.id');
            })
            ->leftJoin('po_knit_service_item_qties',function($join){
                $join->on('po_knit_service_item_qties.id','=','so_knit_po_items.po_knit_service_item_qty_id');
            })
            ->leftJoin('po_knit_service_items',function($join){
                $join->on('po_knit_service_items.id','=','po_knit_service_item_qties.po_knit_service_item_id')
                ->whereNull('po_knit_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_knit_service_item_qties.sales_order_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
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
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_knit_items',function($join){
                $join->on('so_knit_items.so_knit_ref_id','=','so_knit_refs.id');
            })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_knit_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=',$id]])
            //->orderBy('style_fabrications.autoyarn_id','desc')
            ->groupBy([
                'local_exp_invoices.id',
                'style_fabrications.autoyarn_id',
                'so_knit_items.gmt_sale_order_no',
                'so_knit_items.autoyarn_id',
                'uoms.code',
                'so_uoms.code',
                'sales_orders.sale_order_no',
            ])
            ->get()
            ->map(function ($impdocaccept) use($desDropdown){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->item_description=$impdocaccept->fabrication;
                $impdocaccept->uom_code=$impdocaccept->uom_name?$impdocaccept->uom_name:$impdocaccept->so_uom_name;
                return $impdocaccept;
            });
        }
        //Dyeing Sales Order
        elseif ($production_area_id==20) {
            $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
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
                $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');
            $dyetype=array_prepend(config('bprs.dyetype'),'','');

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_invoices.id as local_exp_invoice_id,
                style_fabrications.autoyarn_id,
                so_dyeing_items.autoyarn_id as c_autoyarn_id,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                sales_orders.sale_order_no,
                so_dyeing_items.gmt_sale_order_no,
                sum(local_exp_invoice_orders.qty) as invoice_qty,
                avg(local_exp_invoice_orders.rate) as invoice_rate,
                sum(local_exp_invoice_orders.amount) as invoice_amount
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_dyeing_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_dyeing_refs.id');
            })
            ->join('so_dyeings',function($join){
                $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
            })
            ->leftJoin('so_dyeing_pos',function($join){
                $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
            })
            ->leftJoin('so_dyeing_po_items',function($join){
                $join->on('so_dyeing_po_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
            })
            ->leftJoin('po_dyeing_service_item_qties',function($join){
                $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
            })
            ->leftJoin('po_dyeing_service_items',function($join){
                $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
            ->whereNull('po_dyeing_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','po_dyeing_service_item_qties.fabric_color_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
                $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
            })
            ->leftJoin('budget_fabrics',function($join){
                $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->leftJoin('style_fabrications',function($join){
                $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_dyeing_items',function($join){
                $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
            })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_dyeing_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=',$id]])
            ->groupBy([
                'local_exp_invoices.id',
                'constructions.name',
                'style_fabrications.autoyarn_id',
                'so_dyeing_items.autoyarn_id',
                'uoms.code',
                'so_uoms.code',
                'sales_orders.sale_order_no',
                'so_dyeing_items.gmt_sale_order_no',
               
            ])
            ->get()
            ->map(function ($impdocaccept) use($desDropdown){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->item_description=$impdocaccept->fabrication;
                $impdocaccept->uom_code=$impdocaccept->uom_name?$impdocaccept->uom_name:$impdocaccept->so_uom_name;

                return $impdocaccept;
            });
        }
        //AOP Sales Order
        elseif ($production_area_id==25) {
            $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
            $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'','');
            $autoyarn=$this->autoyarn
            ->leftJoin('autoyarnratios', function($join)  {
                $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('compositions',function($join){
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
                $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_invoices.id as local_exp_invoice_id,
                style_fabrications.autoyarn_id,
                so_aop_items.autoyarn_id as c_autoyarn_id,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                sales_orders.sale_order_no,
                so_aop_items.gmt_sale_order_no,
                sum(local_exp_invoice_orders.qty) as invoice_qty,
                avg(local_exp_invoice_orders.rate) as invoice_rate,
                sum(local_exp_invoice_orders.amount) as invoice_amount
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
            })
            ->join('so_aop_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_aop_refs.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_aops',function($join){
                $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
            })
            ->leftJoin('so_aop_pos',function($join){
                $join->on('so_aop_pos.so_aop_id','=','so_aops.id');
            })
            ->leftJoin('so_aop_po_items',function($join){
                $join->on('so_aop_po_items.so_aop_ref_id','=','so_aop_refs.id');
            })
            ->leftJoin('po_aop_service_item_qties',function($join){
                $join->on('po_aop_service_item_qties.id','=','so_aop_po_items.po_aop_service_item_qty_id');
            })
            ->leftJoin('po_aop_service_items',function($join){
                $join->on('po_aop_service_items.id','=','po_aop_service_item_qties.po_aop_service_item_id')
            ->whereNull('po_aop_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_aop_service_item_qties.sales_order_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','po_aop_service_item_qties.fabric_color_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
                $join->on('budget_fabric_prods.id','=','po_aop_service_items.budget_fabric_prod_id');
            })
            ->leftJoin('budget_fabrics',function($join){
                $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->leftJoin('style_fabrications',function($join){
                $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_aop_items',function($join){
                $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
            })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_aop_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=', $id]])
            ->groupBy([
                'local_exp_invoices.id',
                'constructions.name',
                'style_fabrications.autoyarn_id',
                'so_aop_items.autoyarn_id',
                'uoms.code',
                'so_uoms.code',
                'sales_orders.sale_order_no',
                'so_aop_items.gmt_sale_order_no'
            ])
            ->get()
            ->map(function ($impdocaccept) use($desDropdown){/* $color,$gmtspart,$fabriclooks,$fabricshape,$colorrange,$color,$aoptype, */
                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
               // $impdocaccept->gmtspart=$impdocaccept->gmtspart_id?$gmtspart[$impdocaccept->gmtspart_id]:$gmtspart[$impdocaccept->c_gmtspart_id];
               // $impdocaccept->fabriclooks=$impdocaccept->fabric_look_id?$fabriclooks[$impdocaccept->fabric_look_id]:$fabriclooks[$impdocaccept->c_fabric_look_id];
              //  $impdocaccept->fabricshape=$impdocaccept->fabric_shape_id?$fabricshape[$impdocaccept->fabric_shape_id]:$fabricshape[$impdocaccept->c_fabric_shape_id];
               // $impdocaccept->gsm_weight=$impdocaccept->gsm_weight?$impdocaccept->gsm_weight:$impdocaccept->c_gsm_weight;
              //  $impdocaccept->fabric_color_name=$impdocaccept->fabric_color_id?$color[$impdocaccept->fabric_color_id]:$color[$impdocaccept->c_fabric_color_id];
               // $impdocaccept->colorrange_id=$impdocaccept->colorrange_id?$colorrange[$impdocaccept->colorrange_id]:$colorrange[$impdocaccept->c_colorrange_id];
               // $impdocaccept->embelishment_type_id=$impdocaccept->embelishment_type_id?$aoptype[$impdocaccept->embelishment_type_id]:$aoptype[$impdocaccept->c_embelishment_type_id];
                $impdocaccept->item_description=$impdocaccept->fabrication/* .','.$impdocaccept->gmtspart.','.$impdocaccept->fabriclooks.','.$impdocaccept->fabricshape.','.$impdocaccept->gsm_weight.','.$impdocaccept->fabric_color_name.','.$impdocaccept->colorrange_id.','.$impdocaccept->embelishment_type_id */;
               // $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                $impdocaccept->uom_code=$impdocaccept->uom_name?$impdocaccept->uom_name:$impdocaccept->so_uom_name;
                return $impdocaccept;
            });
    
        }
        //Embelishment Work Order
        elseif ($production_area_id==45 ||$production_area_id==50 || $production_area_id==51) {
            $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');
            $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getEmbelishmentTypes(),'name','id'),'','');
            $embelishment=array_prepend(array_pluck($this->embelishment->get(),'name','id'),'','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
            $size=array_prepend(array_pluck($this->size->get(),'name','id'),'','');

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                --local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                --so_emb_refs.id as so_emb_ref_id,
                --so_emb_refs.so_emb_id,
                so_embs.sales_order_no,
                --gmtsparts.id as gmtspart_id,
                --so_emb_items.gmtspart_id as c_gmtspart_id,
                --style_embelishments.embelishment_size_id,
                --style_embelishments.embelishment_type_id,
                --style_embelishments.embelishment_id,
                --so_emb_items.embelishment_id as c_embelishment_id,
                --so_emb_items.embelishment_type_id as c_embelishment_type_id,
                --so_emb_items.embelishment_size_id as c_embelishment_size_id,
                --so_emb_items.color_id as c_color_id,
                --so_emb_items.size_id as c_size_id,
                so_uoms.code as so_uom_name,
                sales_orders.sale_order_no,
                so_emb_items.gmt_sale_order_no,
                item_accounts.item_description,
                --colors.name as gmt_color,
                --sizes.name as gmt_size,
                --local_exp_invoice_orders.id as local_exp_invoice_order_id,
                sum(local_exp_invoice_orders.qty) as invoice_qty,
                avg(local_exp_invoice_orders.rate) as invoice_rate,
                sum(local_exp_invoice_orders.amount) as invoice_amount
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->leftJoin('so_emb_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin('so_embs',function($join){
                $join->on('so_emb_refs.so_emb_id','=','so_embs.id');
            })
            ->leftJoin('so_emb_pos',function($join){
                $join->on('so_emb_pos.so_emb_id','=','so_embs.id');
            })
            ->leftJoin('so_emb_po_items',function($join){
                $join->on('so_emb_po_items.so_emb_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin('po_emb_service_item_qties',function($join){
                $join->on('po_emb_service_item_qties.id','=','so_emb_po_items.po_emb_service_item_qty_id');
            })
            ->leftJoin('po_emb_service_items',function($join){
                $join->on('po_emb_service_items.id','=','po_emb_service_item_qties.po_emb_service_item_id')
                ->whereNull('po_emb_service_items.deleted_at');
            })
            ->leftJoin('budget_embs',function($join){
                $join->on('budget_embs.id','=','po_emb_service_items.budget_emb_id');
            })
            ->leftJoin('style_embelishments',function($join){
                $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
            })
            ->leftJoin('style_gmts',function($join){
                $join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
            })
            ->leftJoin('item_accounts', function($join) {
                $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
            })
            ->leftJoin('gmtsparts',function($join){
                $join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
            })
            ->leftJoin('embelishments',function($join){
                $join->on('embelishments.id','=','style_embelishments.embelishment_id');
            })
            ->leftJoin('embelishment_types',function($join){
                $join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
            })
            ->leftJoin('budget_emb_cons',function($join){
                $join->on('budget_emb_cons.id','=','po_emb_service_item_qties.budget_emb_con_id')
                ->whereNull('budget_emb_cons.deleted_at');
            })
            ->leftJoin('sales_order_gmt_color_sizes',function($join){
                $join->on('sales_order_gmt_color_sizes.id','=','budget_emb_cons.sales_order_gmt_color_size_id');
            })
            ->leftJoin('sales_order_countries',function($join){
                $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','sales_order_countries.sale_order_id');
            })
            ->leftJoin('style_gmt_color_sizes',function($join){
                $join->on('style_gmt_color_sizes.id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('style_sizes',function($join){
                $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
            })
            ->leftJoin('sizes',function($join){
                $join->on('sizes.id','=','style_sizes.size_id');
            })
            ->leftJoin('style_colors',function($join){
                $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','style_colors.color_id');
            })
            ->leftJoin('so_emb_items',function($join){
                $join->on('so_emb_items.so_emb_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_emb_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=', $id]])
            //->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                //'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                //'local_exp_invoice_orders.id',

                //'so_emb_refs.id',
                //'so_emb_refs.so_emb_id',
                'so_embs.sales_order_no',
               //'gmtsparts.id',
               // 'so_emb_items.gmtspart_id',
               // 'style_embelishments.embelishment_size_id',
               // 'style_embelishments.embelishment_type_id',
               // 'style_embelishments.embelishment_id',
               // 'so_emb_items.embelishment_id',
               // 'so_emb_items.embelishment_type_id',
               // 'so_emb_items.embelishment_size_id',
               // 'so_emb_items.color_id',
               // 'so_emb_items.size_id',
                'so_uoms.code',
                'sales_orders.sale_order_no',
                'so_emb_items.gmt_sale_order_no',
                'item_accounts.item_description',
               // 'colors.name',
               // 'sizes.name',
               
            ])
            ->get()
            ->map(function ($impdocaccept) use($gmtspart,$embelishmentsize,$embelishmenttype,$embelishment,$color,$size){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->gmtspart=$impdocaccept->gmtspart_id?$gmtspart[$impdocaccept->gmtspart_id]:$gmtspart[$impdocaccept->c_gmtspart_id];
                $impdocaccept->emb_size=$impdocaccept->embelishment_size_id?$embelishmentsize[$impdocaccept->embelishment_size_id]:$embelishmentsize[$impdocaccept->c_embelishment_size_id];
                $impdocaccept->emb_name=$impdocaccept->embelishment_id?$embelishment[$impdocaccept->embelishment_id]:$embelishment[$impdocaccept->c_embelishment_id];
                $impdocaccept->gmt_color=$impdocaccept->gmt_color?$impdocaccept->gmt_color:$color[$impdocaccept->c_color_id];
                $impdocaccept->gmt_size=$impdocaccept->gmt_size?$impdocaccept->gmt_size:$size[$impdocaccept->c_size_id];
                $impdocaccept->item_description=$impdocaccept->item_description/* .','.$impdocaccept->emb_name.','.$impdocaccept->emb_size.','.$impdocaccept->gmtspart.','.$impdocaccept->gmt_color.','.$impdocaccept->gmt_size */;
                //$impdocaccept->dye_aop_type=$impdocaccept->embelishment_type_id?$embelishmenttype[$impdocaccept->embelishment_type_id]:$embelishmenttype[$impdocaccept->c_embelishment_type_id];
                //$impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                $impdocaccept->uom_code='Pcs'?'Pcs':$impdocaccept->so_uom_name;
                return $impdocaccept;
            });
            
        }

        $amount=$impdocaccept->sum('invoice_amount');
        $currency=$localexpdocaccept['currency_id'];
        $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$currency,'cents');
        $localexpdocaccept['inword']=$inword;

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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
        $challan=str_pad($localexpdocaccept['local_exp_invoice_id'],10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
        $pdf->SetY(10);
        $image_file ='images/logo/'.$localexpdocaccept['logo'];
        $pdf->Image($image_file, 90, 5, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $pdf->SetY(13);
        $pdf->SetFont('helvetica', 'N', 9);
        //$pdf->Text(68, 16, $localexpdocaccept['company_address']);
        $pdf->Cell(0, 40, $localexpdocaccept['company_address'], 0, false, 'C', 0, '', 0, false, 'T', 'M' );
        //$pdf->SetY(16);
        $pdf->SetFont('helvetica', '', 8);
        $view= \View::make('Defult.Commercial.LocalExport.LocalExpCommercialInvoicePdf',['impdocaccept'=>$impdocaccept,'localexpdocaccept'=>$localexpdocaccept,'localpi'=>$localpi,'arrPi'=>$arrPi]);
        $html_content=$view->render();
        $pdf->SetY(36);
        
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/LocalExpCommercialInvoicePdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }

    
    public function getDCShortPdf(){
        $id=request('local_exp_invoice_id', 0);

        $localpi=$this->localexpinvoice
        ->join('local_exp_lcs',function($join){
             $join->on('local_exp_lcs.id','=','local_exp_invoices.local_exp_lc_id');
         })
        ->join('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_lc_tag_pis.local_exp_lc_id');
        })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
        })
        ->where([['local_exp_invoices.id','=',$id]])
        ->get([
            'local_exp_invoices.id as local_exp_invoice_id', 
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
        ]);

        $arrPi=array();
	    foreach($localpi as $bar){
		    $arrPi[$bar->local_exp_invoice_id][]=$bar->pi_no.", Dated: ".date('d-M-Y',strtotime($bar->pi_date));
        }
        
        $localexpdocaccepts=$this->localexpinvoice
        ->join('local_exp_lcs',function($join){
             $join->on('local_exp_lcs.id','=','local_exp_invoices.local_exp_lc_id');
         })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->leftJoin('buyer_branches',function($join){
            $join->on('buyers.id','=','buyer_branches.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'local_exp_lcs.beneficiary_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        ->join('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_lc_tag_pis.local_exp_lc_id');
        })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
        })
        ->where([['local_exp_invoices.id','=',$id]])
       // ->where([['local_exp_doc_sub_accepts.id','=',$localexpdocacceptId]])
        ->get([         
           // 'local_exp_doc_sub_accepts.id',
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.*',
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
            'buyers.name as buyer_name',
            'buyer_branches.address as buyer_address',
            'buyer_branches.email as buyer_email',
            'companies.name as beneficiary',
            'companies.address as company_address',
            'companies.logo',
            'currencies.name as currency_id',
            'local_exp_invoices.*',
            'local_exp_invoices.id as local_exp_invoice_id'
        ]);
        foreach ($localexpdocaccepts as $rows) {
            $localexpdocaccept['local_exp_invoice_id']=$rows->id;
            $localexpdocaccept['logo']=$rows->logo;
            $localexpdocaccept['beneficiary']=$rows->beneficiary;
            $localexpdocaccept['company_address']=$rows->company_address;
            $localexpdocaccept['local_invoice_no']=$rows->local_invoice_no;
            $localexpdocaccept['local_invoice_date']=date('d-M-Y',strtotime($rows->local_invoice_date));
            $localexpdocaccept['buyer_name']=$rows->buyer_name;
            $localexpdocaccept['buyer_address']=$rows->buyer_address;
            $localexpdocaccept['buyer_email']=$rows->buyer_email;
            $localexpdocaccept['buyers_bank']=$rows->buyers_bank;
            $localexpdocaccept['pi_no']=implode(" ; ",$arrPi[$rows->local_exp_invoice_id]);
            $localexpdocaccept['pi_date']=date('d-M-Y',strtotime($rows->pi_date));
            $localexpdocaccept['local_lc_no']=$rows->local_lc_no;
            $localexpdocaccept['lc_date']=date('d-M-Y',strtotime($rows->lc_date));
            $localexpdocaccept['customer_lc_sc']=$rows->customer_lc_sc;
            $localexpdocaccept['delivery_place']=$rows->delivery_place;
            $localexpdocaccept['currency_id']=$rows->currency_id;
        }
        $localexpdocaccept['master']=$rows;

        

        $localexpinvoice=$this->localexpinvoice->find($id);
        $localexplc=$this->localexplc->find($localexpinvoice->local_exp_lc_id);
        $production_area_id=$localexplc->production_area_id;
         //Yarn Dyeing Sales Order
         if($production_area_id==5){
            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                sum(local_exp_pi_orders.qty) as pi_qty,
                sum(local_exp_pi_orders.amount) as pi_amount,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                local_exp_invoice_orders.qty as invoice_qty,
                local_exp_invoice_orders.rate as invoice_rate,
                local_exp_invoice_orders.amount as invoice_amount,
                cumulatives.cumulative_amount,
                cumulatives.cumulative_qty
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            }) 
            ->join(\DB::raw("(SELECT
                    local_exp_pi_orders.id as local_exp_pi_order_id,
                    sum(local_exp_invoice_orders.qty) as cumulative_qty,
                    sum(local_exp_invoice_orders.amount) as cumulative_amount 
                FROM local_exp_invoice_orders
                    join local_exp_pi_orders on local_exp_pi_orders.id =local_exp_invoice_orders.local_exp_pi_order_id
                    join local_exp_invoices on  local_exp_invoices.id=local_exp_invoice_orders.local_exp_invoice_id
                where local_exp_invoice_orders.deleted_at is null
                group by local_exp_pi_orders.id) cumulatives"), "cumulatives.local_exp_pi_order_id", "=", "local_exp_pi_orders.id")
            ->leftJoin('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=',$id]])
            ->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoice_orders.id',
                'local_exp_invoice_orders.qty',
                'local_exp_invoice_orders.rate',
                'local_exp_invoice_orders.amount',
                'cumulatives.cumulative_amount',
                'cumulatives.cumulative_qty'
            ])
            ->get()
            ->map(function ($impdocaccept){
                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                $impdocaccept->pi_rate=$impdocaccept->pi_amount/$impdocaccept->pi_qty;
                $impdocaccept->balance_qty=$impdocaccept->qty-$impdocaccept->cumulative_qty;
                return $impdocaccept;
            });


        }
        //Knitting Sales Order
        elseif ($production_area_id==10) {
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
            $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }

            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_invoices.id as local_exp_invoice_id,
                style_fabrications.autoyarn_id,
                so_knit_items.gmt_sale_order_no,
                so_knit_items.autoyarn_id as c_autoyarn_id,
                sales_orders.sale_order_no,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                sum(local_exp_invoice_orders.qty) as invoice_qty,
                avg(local_exp_invoice_orders.rate) as invoice_rate,
                sum(local_exp_invoice_orders.amount) as invoice_amount
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_knit_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_knit_refs.id');
            })
            ->join('so_knits',function($join){
                $join->on('so_knit_refs.so_knit_id','=','so_knits.id');
            })
            ->leftJoin('so_knit_pos',function($join){
                $join->on('so_knit_pos.so_knit_id','=','so_knits.id');
            })
            ->leftJoin('so_knit_po_items',function($join){
                $join->on('so_knit_po_items.so_knit_ref_id','=','so_knit_refs.id');
            })
            ->leftJoin('po_knit_service_item_qties',function($join){
                $join->on('po_knit_service_item_qties.id','=','so_knit_po_items.po_knit_service_item_qty_id');
            })
            ->leftJoin('po_knit_service_items',function($join){
                $join->on('po_knit_service_items.id','=','po_knit_service_item_qties.po_knit_service_item_id')
                ->whereNull('po_knit_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_knit_service_item_qties.sales_order_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
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
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_knit_items',function($join){
                $join->on('so_knit_items.so_knit_ref_id','=','so_knit_refs.id');
            })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_knit_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=',$id]])
            //->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                'local_exp_invoices.id',
                'style_fabrications.autoyarn_id',
                'so_knit_items.gmt_sale_order_no',
                'so_knit_items.autoyarn_id',
                'sales_orders.sale_order_no',
                'uoms.code',
                'so_uoms.code',
            ])
            ->get()
            ->map(function ($impdocaccept) use($desDropdown){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->item_description=$impdocaccept->fabrication;
                $impdocaccept->uom_code=$impdocaccept->uom_name?$impdocaccept->uom_code:$impdocaccept->so_uom_name;
                return $impdocaccept;
            });
        }
        //Dyeing Sales Order
        elseif ($production_area_id==20) {
            $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
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
                $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');
            $dyetype=array_prepend(config('bprs.dyetype'),'','');

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_invoices.id as local_exp_invoice_id,
                style_fabrications.autoyarn_id,
                so_dyeing_items.autoyarn_id as c_autoyarn_id,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                sales_orders.sale_order_no,
                so_dyeing_items.gmt_sale_order_no,
                sum(local_exp_invoice_orders.qty) as invoice_qty,
                avg(local_exp_invoice_orders.rate) as invoice_rate,
                sum(local_exp_invoice_orders.amount) as invoice_amount
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_dyeing_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_dyeing_refs.id');
            })
            ->join('so_dyeings',function($join){
                $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
            })
            ->leftJoin('so_dyeing_pos',function($join){
                $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
            })
            ->leftJoin('so_dyeing_po_items',function($join){
                $join->on('so_dyeing_po_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
            })
            ->leftJoin('po_dyeing_service_item_qties',function($join){
                $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
            })
            ->leftJoin('po_dyeing_service_items',function($join){
                $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
            ->whereNull('po_dyeing_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','po_dyeing_service_item_qties.fabric_color_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
                $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
            })
            ->leftJoin('budget_fabrics',function($join){
                $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->leftJoin('style_fabrications',function($join){
                $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_dyeing_items',function($join){
                $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
            })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_dyeing_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=',$id]])
            //->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                'local_exp_invoices.id',
                'style_fabrications.autoyarn_id',
                'so_dyeing_items.autoyarn_id',
                'uoms.code',
                'so_uoms.code',
                'sales_orders.sale_order_no',
                'so_dyeing_items.gmt_sale_order_no',
            ])
            ->get()
            ->map(function ($impdocaccept) use($desDropdown){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->item_description=$impdocaccept->fabrication;
                $impdocaccept->uom_code=$impdocaccept->uom_name?$impdocaccept->uom_name:$impdocaccept->so_uom_name;
                return $impdocaccept;
            });

        }
        //AOP Sales Order
        elseif ($production_area_id==25) {
            $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
            $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'','');
            $autoyarn=$this->autoyarn
            ->leftJoin('autoyarnratios', function($join)  {
                $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('compositions',function($join){
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
                $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_invoices.id as local_exp_invoice_id,
                style_fabrications.autoyarn_id,
                so_aop_items.autoyarn_id as c_autoyarn_id,
                sales_orders.sale_order_no,
                so_aop_items.gmt_sale_order_no,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                sum(local_exp_invoice_orders.qty) as invoice_qty,
                avg(local_exp_invoice_orders.rate) as invoice_rate,
                sum(local_exp_invoice_orders.amount) as invoice_amount
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_aop_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_aop_refs.id');
            })
            ->join('so_aops',function($join){
                $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
            })
            ->leftJoin('so_aop_pos',function($join){
                $join->on('so_aop_pos.so_aop_id','=','so_aops.id');
            })
            ->leftJoin('so_aop_po_items',function($join){
                $join->on('so_aop_po_items.so_aop_ref_id','=','so_aop_refs.id');
            })
            ->leftJoin('po_aop_service_item_qties',function($join){
                $join->on('po_aop_service_item_qties.id','=','so_aop_po_items.po_aop_service_item_qty_id');
            })
            ->leftJoin('po_aop_service_items',function($join){
                $join->on('po_aop_service_items.id','=','po_aop_service_item_qties.po_aop_service_item_id')
            ->whereNull('po_aop_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_aop_service_item_qties.sales_order_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','po_aop_service_item_qties.fabric_color_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
                $join->on('budget_fabric_prods.id','=','po_aop_service_items.budget_fabric_prod_id');
            })
            ->leftJoin('budget_fabrics',function($join){
                $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->leftJoin('style_fabrications',function($join){
                $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_aop_items',function($join){
                $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
            })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_aop_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=', $id]])
            ->groupBy([
                'local_exp_invoices.id',
                'style_fabrications.autoyarn_id',
                'so_aop_items.autoyarn_id',
                'sales_orders.sale_order_no',
                'so_aop_items.gmt_sale_order_no',

                'uoms.code',
                'so_uoms.code',
            ])
            ->get()
            ->map(function ($impdocaccept) use($desDropdown){
                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->item_description=$impdocaccept->fabrication;
                $impdocaccept->uom_code=$impdocaccept->uom_name?$impdocaccept->uom_name:$impdocaccept->so_uom_name;

                return $impdocaccept;
            });
    
        }
        //Embelishment Work Order
        elseif ($production_area_id==45 ||$production_area_id==50 || $production_area_id==51) {
            $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');
            $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getEmbelishmentTypes(),'name','id'),'','');
            $embelishment=array_prepend(array_pluck($this->embelishment->get(),'name','id'),'','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
            $size=array_prepend(array_pluck($this->size->get(),'name','id'),'','');

            $impdocaccept=$this->localexpinvoice
            ->selectRaw('
                local_exp_invoices.id as local_exp_invoice_id,
                so_embs.sales_order_no,
                sales_orders.sale_order_no,
                so_emb_items.gmt_sale_order_no,
                item_accounts.item_description,
                so_uoms.code as so_uom_name,
                sum(local_exp_invoice_orders.qty) as invoice_qty,
                avg(local_exp_invoice_orders.rate) as invoice_rate,
                sum(local_exp_invoice_orders.amount) as invoice_amount
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->leftJoin('so_emb_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin('so_embs',function($join){
                $join->on('so_emb_refs.so_emb_id','=','so_embs.id');
            })
            ->leftJoin('so_emb_pos',function($join){
                $join->on('so_emb_pos.so_emb_id','=','so_embs.id');
            })
            ->leftJoin('so_emb_po_items',function($join){
                $join->on('so_emb_po_items.so_emb_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin('po_emb_service_item_qties',function($join){
                $join->on('po_emb_service_item_qties.id','=','so_emb_po_items.po_emb_service_item_qty_id');
            })
            ->leftJoin('po_emb_service_items',function($join){
                $join->on('po_emb_service_items.id','=','po_emb_service_item_qties.po_emb_service_item_id')
                ->whereNull('po_emb_service_items.deleted_at');
            })
            ->leftJoin('budget_embs',function($join){
                $join->on('budget_embs.id','=','po_emb_service_items.budget_emb_id');
            })
            ->leftJoin('style_embelishments',function($join){
                $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
            })
            ->leftJoin('style_gmts',function($join){
                $join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
            })
            ->leftJoin('item_accounts', function($join) {
                $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
            })
            ->leftJoin('gmtsparts',function($join){
                $join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
            })
            ->leftJoin('embelishments',function($join){
                $join->on('embelishments.id','=','style_embelishments.embelishment_id');
            })
            ->leftJoin('embelishment_types',function($join){
                $join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
            })
            ->leftJoin('budget_emb_cons',function($join){
                $join->on('budget_emb_cons.id','=','po_emb_service_item_qties.budget_emb_con_id')
                ->whereNull('budget_emb_cons.deleted_at');
            })
            ->leftJoin('sales_order_gmt_color_sizes',function($join){
                $join->on('sales_order_gmt_color_sizes.id','=','budget_emb_cons.sales_order_gmt_color_size_id');
            })
            ->leftJoin('sales_order_countries',function($join){
                $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','sales_order_countries.sale_order_id');
            })
            ->leftJoin('style_gmt_color_sizes',function($join){
                $join->on('style_gmt_color_sizes.id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('style_sizes',function($join){
                $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
            })
            ->leftJoin('sizes',function($join){
                $join->on('sizes.id','=','style_sizes.size_id');
            })
            ->leftJoin('style_colors',function($join){
                $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','style_colors.color_id');
            })
            ->leftJoin('so_emb_items',function($join){
                $join->on('so_emb_items.so_emb_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_emb_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_invoices.id','=', $id]])
            //->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                'local_exp_invoices.id',
                'so_embs.sales_order_no',
                'sales_orders.sale_order_no',
                'so_emb_items.gmt_sale_order_no',
                'item_accounts.item_description',
                'so_uoms.code',
            ])
            ->get()
            ->map(function ($impdocaccept) {
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->item_description=$impdocaccept->item_description;
                $impdocaccept->uom_code='Pcs'?'Pcs':$impdocaccept->so_uom_name;

                return $impdocaccept;
            });
            
        }

        $amount=$impdocaccept->sum('invoice_amount');
        $currency=$localexpdocaccept['currency_id'];
        $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$currency,'cents');
        $localexpdocaccept['inword']=$inword;

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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
        $challan=str_pad($localexpdocaccept['local_exp_invoice_id'],10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
        $pdf->SetY(10);
        $image_file ='images/logo/'.$localexpdocaccept['logo'];
        $pdf->Image($image_file, 90, 5, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $pdf->SetY(13);
        $pdf->SetFont('helvetica', 'N', 9);
        //$pdf->Text(68, 16, $localexpdocaccept['company_address']);
        $pdf->Cell(0, 40, $localexpdocaccept['company_address'], 0, false, 'C', 0, '', 0, false, 'T', 'M' );
        //$pdf->SetY(16);
        $pdf->SetFont('helvetica', '', 8);
        $view= \View::make('Defult.Commercial.LocalExport.LocalExpDeliveryChallanPdf',['impdocaccept'=>$impdocaccept,'localexpdocaccept'=>$localexpdocaccept,'localpi'=>$localpi,'arrPi'=>$arrPi]);
        $html_content=$view->render();
        $pdf->SetY(35);
        
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/LocalExpDeliveryChallanPdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }

    public function getPLShortPdf(){
        $id=request('id', 0);
        $localpi=$this->localexpdocaccept
        ->join('local_exp_doc_sub_invoices',function($join){
            $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            $join->whereNull('local_exp_doc_sub_invoices.deleted_at');
        })
        ->join('local_exp_invoices',function($join){
            $join->on('local_exp_invoices.id','=','local_exp_doc_sub_invoices.local_exp_invoice_id');
        })
        ->join('local_exp_invoice_orders',function($join){
            $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
            
            $join->whereNull('local_exp_invoice_orders.deleted_at');
        })
        ->join('local_exp_pi_orders', function($join)  {
            $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
        })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
        })
        ->join('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lc_tag_pis.local_exp_pi_id', '=', 'local_exp_pis.id'); 
        })
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_lc_tag_pis.local_exp_lc_id');
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->where([['local_exp_doc_sub_accepts.id','=',$id]])
        ->get([    
            'local_exp_doc_sub_accepts.id', 
            'local_exp_invoices.id as local_exp_invoice_id', 
            'local_exp_doc_sub_invoices.id as local_exp_doc_sub_invoice_id',
            'local_exp_invoices.local_invoice_no', 
            'local_exp_pis.id as local_exp_pi_id',
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
        ]);

        $arrPi=array();
        $arrInvoice=array();
      //  $docinvoice=array();
        foreach($localpi as $bar){
            $arrPi[$bar->local_exp_invoice_id][$bar->local_exp_pi_id]=$bar->pi_no.", Dated: ".date('d-M-Y',strtotime($bar->pi_date));
            $arrInvoice[$bar->local_exp_invoice_id]=$bar->local_invoice_no;
           // $docinvoice[$bar->id][$bar->local_exp_invoice_id]=$bar->local_exp_invoice_id;
        }

        $localexpdocaccepts=$this->localexpdocaccept
        ->join('local_exp_doc_sub_invoices',function($join){
            $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            $join->whereNull('local_exp_doc_sub_invoices.deleted_at');
        })
        ->join('local_exp_invoices',function($join){
            $join->on('local_exp_invoices.id','=','local_exp_doc_sub_invoices.local_exp_invoice_id');
        })
        ->join('local_exp_invoice_orders',function($join){
            $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
            
            $join->whereNull('local_exp_invoice_orders.deleted_at');
        })
        ->join('local_exp_pi_orders', function($join)  {
            $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
        })
        ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
        })
        ->join('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lc_tag_pis.local_exp_pi_id', '=', 'local_exp_pis.id'); 
        })
        ->join('local_exp_lcs',function($join){
            $join->on('local_exp_lcs.id','=','local_exp_lc_tag_pis.local_exp_lc_id');
            $join->on('local_exp_lcs.id','=','local_exp_doc_sub_accepts.local_exp_lc_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','local_exp_lcs.buyer_id');
        })
        ->leftJoin('buyer_branches',function($join){
            $join->on('buyers.id','=','buyer_branches.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'local_exp_lcs.beneficiary_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','local_exp_lcs.currency_id');
        })
        
        ->where([['local_exp_doc_sub_accepts.id','=',$id]])
       // ->where([['local_exp_doc_sub_accepts.id','=',$localexpdocacceptId]])
        ->get([            
           'local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id',
            'local_exp_lcs.local_lc_no',
            'local_exp_lcs.*',
            'local_exp_pis.id as local_exp_pi_id',
            'local_exp_pis.pi_no',
            'local_exp_pis.pi_date',
            'buyers.name as buyer_name',
            'buyer_branches.address as buyer_address',
            'buyer_branches.email as buyer_email',
            'companies.name as beneficiary',
            'companies.address as company_address',
            'companies.logo',
            'currencies.name as currency_id',
            'local_exp_invoices.*',
            'local_exp_invoices.id as local_exp_invoice_id'
        ]);
        foreach ($localexpdocaccepts as $rows) {
            $localexpdocaccept['id']=$rows->local_exp_doc_sub_accept_id;
            $localexpdocaccept['local_exp_invoice_id']=$rows->local_exp_invoice_id;
            $localexpdocaccept['logo']=$rows->logo;
            $localexpdocaccept['beneficiary']=$rows->beneficiary;
            $localexpdocaccept['company_address']=$rows->company_address;
            $localexpdocaccept['local_invoice_no']=$rows->local_invoice_no;
            $localexpdocaccept['local_invoice_date']=date('d-M-Y',strtotime($rows->local_invoice_date));
            $localexpdocaccept['buyer_name']=$rows->buyer_name;
            $localexpdocaccept['buyer_address']=$rows->buyer_address;
            $localexpdocaccept['buyer_email']=$rows->buyer_email;
            $localexpdocaccept['buyers_bank']=$rows->buyers_bank;
            $localexpdocaccept['pi_no']=implode(" ; ",$arrPi[$rows->local_exp_invoice_id]);
            $localexpdocaccept['pi_date']=date('d-M-Y',strtotime($rows->pi_date));
            $localexpdocaccept['local_lc_no']=$rows->local_lc_no;
            $localexpdocaccept['lc_date']=date('d-M-Y',strtotime($rows->lc_date));
            $localexpdocaccept['customer_lc_sc']=$rows->customer_lc_sc;
            $localexpdocaccept['delivery_place']=$rows->delivery_place;
            $localexpdocaccept['currency_id']=$rows->currency_id;
        }
        $localexpdocaccept['master']=$rows;
       

        //$localexpinvoice=$this->localexpinvoice->find($localexpdocaccept['local_exp_invoice_id']);
        $localexpdocsubaccept=$this->localexpdocaccept->find($id);
        $localexplc=$this->localexplc->find($localexpdocsubaccept->local_exp_lc_id);
        $production_area_id=$localexplc->production_area_id;
        //Yarn Dyeing Sales Order
        if($production_area_id==5){
            $impdocaccept=$this->localexpdocaccept
            ->selectRaw('
                local_exp_pi_orders.id as local_exp_pi_order_id,
                local_exp_pis.pi_no,
                local_exp_pis.pi_date,
                local_exp_invoices.id as local_exp_invoice_id,
                sum(local_exp_pi_orders.qty) as pi_qty,
                sum(local_exp_pi_orders.amount) as pi_amount,
                local_exp_invoice_orders.id as local_exp_invoice_order_id,
                local_exp_invoice_orders.qty as invoice_qty,
                local_exp_invoice_orders.rate as invoice_rate,
                local_exp_invoice_orders.amount as invoice_amount,
                cumulatives.cumulative_amount,
                cumulatives.cumulative_qty
            ')
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
                $join->on('local_exp_lcs.id', '=', 'local_exp_doc_sub_accepts.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            }) 
            ->join(\DB::raw("(SELECT
                    local_exp_pi_orders.id as local_exp_pi_order_id,
                    sum(local_exp_invoice_orders.qty) as cumulative_qty,
                    sum(local_exp_invoice_orders.amount) as cumulative_amount 
                FROM local_exp_invoice_orders
                    join local_exp_pi_orders on local_exp_pi_orders.id =local_exp_invoice_orders.local_exp_pi_order_id
                    join local_exp_invoices on  local_exp_invoices.id=local_exp_invoice_orders.local_exp_invoice_id
                where local_exp_invoice_orders.deleted_at is null
                group by local_exp_pi_orders.id) cumulatives"), "cumulatives.local_exp_pi_order_id", "=", "local_exp_pi_orders.id")
            ->leftJoin('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_doc_sub_accepts.id','=',$localexpdocsubaccept->id]])
            ->orderBy('local_exp_pi_orders.id','desc')
            ->groupBy([
                'local_exp_pi_orders.id',
                'local_exp_pis.pi_no',
                'local_exp_pis.pi_date',
                'local_exp_invoices.id',
                'local_exp_invoice_orders.id',
                'local_exp_invoice_orders.qty',
                'local_exp_invoice_orders.rate',
                'local_exp_invoice_orders.amount',
                'cumulatives.cumulative_amount',
                'cumulatives.cumulative_qty'
            ])
            ->get()
            ->map(function ($impdocaccept){
                $impdocaccept->pi_date=date('d-M-y',strtotime($impdocaccept->pi_date));
                $impdocaccept->pi_rate=$impdocaccept->pi_amount/$impdocaccept->pi_qty;
                $impdocaccept->balance_qty=$impdocaccept->qty-$impdocaccept->cumulative_qty;
                return $impdocaccept;
            });
        }
        //Knitting Sales Order
        elseif ($production_area_id==10) {
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
                $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }

            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');

            $impdocaccept=$this->localexpdocaccept
            ->selectRaw('
                local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id,
                local_exp_invoices.id as local_exp_invoice_id,
                local_exp_invoices.local_invoice_no,
                style_fabrications.autoyarn_id,
                so_knit_items.gmt_sale_order_no,
                so_knit_items.autoyarn_id as c_autoyarn_id,
                sales_orders.sale_order_no,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                sum(local_exp_invoice_orders.qty) as invoice_qty,
                avg(local_exp_invoice_orders.rate) as invoice_rate,
                sum(local_exp_invoice_orders.amount) as invoice_amount
            ')
            ->join('local_exp_doc_sub_invoices',function($join){
                $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            })
            ->join('local_exp_invoices',function($join){
                $join->on('local_exp_doc_sub_invoices.local_exp_invoice_id','=','local_exp_invoices.id');
            })
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
                $join->on('local_exp_doc_sub_accepts.local_exp_lc_id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_knit_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_knit_refs.id');
            })
            ->join('so_knits',function($join){
                $join->on('so_knit_refs.so_knit_id','=','so_knits.id');
            })
            ->leftJoin('so_knit_pos',function($join){
                $join->on('so_knit_pos.so_knit_id','=','so_knits.id');
            })
            ->leftJoin('so_knit_po_items',function($join){
                $join->on('so_knit_po_items.so_knit_ref_id','=','so_knit_refs.id');
            })
            ->leftJoin('po_knit_service_item_qties',function($join){
                $join->on('po_knit_service_item_qties.id','=','so_knit_po_items.po_knit_service_item_qty_id');
            })
            ->leftJoin('po_knit_service_items',function($join){
                $join->on('po_knit_service_items.id','=','po_knit_service_item_qties.po_knit_service_item_id')
                ->whereNull('po_knit_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_knit_service_item_qties.sales_order_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
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
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_knit_items',function($join){
                $join->on('so_knit_items.so_knit_ref_id','=','so_knit_refs.id');
            })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_knit_items.uom_id');
            })
            ->leftJoin('colors as so_color',function($join){
                $join->on('so_color.id','=','so_knit_items.fabric_color_id');
            })
            ->leftJoin('colors as po_color',function($join){
                $join->on('po_color.id','=','po_knit_service_item_qties.fabric_color_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_doc_sub_accepts.id','=',$localexpdocsubaccept->id]])
            ->groupBy([
                'local_exp_doc_sub_accepts.id',
                'local_exp_invoices.id',
                'local_exp_invoices.local_invoice_no',
                'style_fabrications.autoyarn_id',
                'so_knit_items.gmt_sale_order_no',
                'so_knit_items.autoyarn_id',
                'sales_orders.sale_order_no',
                'uoms.code',
                'so_uoms.code',
            ])
            ->get()
            ->map(function ($impdocaccept) use($gmtspart,$fabriclooks,$fabricshape,$desDropdown){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->item_description=$impdocaccept->fabrication;
                $impdocaccept->uom_code=$impdocaccept->uom_name?$impdocaccept->uom_code:$impdocaccept->so_uom_name;
                $impdocaccept->gross_qty=$impdocaccept->invoice_qty+($impdocaccept->invoice_qty*.001);
                return $impdocaccept;
            });
        }
        //Dyeing Sales Order
        elseif ($production_area_id==20) {
            $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
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
                $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');
            $dyetype=array_prepend(config('bprs.dyetype'),'','');

            $impdocaccept=$this->localexpdocaccept
            ->selectRaw('
                local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id,
                local_exp_invoices.local_invoice_no,
                style_fabrications.autoyarn_id,
                so_dyeing_items.autoyarn_id as c_autoyarn_id,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                sales_orders.sale_order_no,
                so_dyeing_items.gmt_sale_order_no,
                sum(local_exp_invoice_orders.qty) as invoice_qty,
                avg(local_exp_invoice_orders.rate) as invoice_rate,
                sum(local_exp_invoice_orders.amount) as invoice_amount
            ')
            ->join('local_exp_doc_sub_invoices',function($join){
                $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            })
            ->join('local_exp_invoices',function($join){
                $join->on('local_exp_doc_sub_invoices.local_exp_invoice_id','=','local_exp_invoices.id');
            })
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
                $join->on('local_exp_doc_sub_accepts.local_exp_lc_id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_dyeing_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_dyeing_refs.id');
            })
            ->join('so_dyeings',function($join){
                $join->on('so_dyeing_refs.so_dyeing_id','=','so_dyeings.id');
            })
            ->leftJoin('so_dyeing_pos',function($join){
                $join->on('so_dyeing_pos.so_dyeing_id','=','so_dyeings.id');
            })
            ->leftJoin('so_dyeing_po_items',function($join){
                $join->on('so_dyeing_po_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
            })
            ->leftJoin('po_dyeing_service_item_qties',function($join){
                $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
            })
            ->leftJoin('po_dyeing_service_items',function($join){
                $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
            ->whereNull('po_dyeing_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','po_dyeing_service_item_qties.fabric_color_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
                $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
            })
            ->leftJoin('budget_fabrics',function($join){
                $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->leftJoin('style_fabrications',function($join){
                $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_dyeing_items',function($join){
                $join->on('so_dyeing_items.so_dyeing_ref_id','=','so_dyeing_refs.id');
            })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_dyeing_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_doc_sub_accepts.id','=',$localexpdocsubaccept->id]])
            ->groupBy([
                'local_exp_doc_sub_accepts.id',
                'local_exp_invoices.local_invoice_no',
                'style_fabrications.autoyarn_id',
                'so_dyeing_items.autoyarn_id',
                'uoms.code',
                'so_uoms.code',
                'sales_orders.sale_order_no',
                'so_dyeing_items.gmt_sale_order_no',
            ])
            ->get()
            ->map(function ($impdocaccept) use($desDropdown,$gmtspart,$fabriclooks,$fabricshape,$colorrange,$color,$dyetype){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->item_description=$impdocaccept->fabrication;
                $impdocaccept->uom_code=$impdocaccept->uom_name?$impdocaccept->uom_name:$impdocaccept->so_uom_name;
                $impdocaccept->gross_qty=$impdocaccept->invoice_qty+($impdocaccept->invoice_qty*.001);
                return $impdocaccept;
            });

        }
        //AOP Sales Order
        elseif ($production_area_id==25) {
            $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
            $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'','');
            $autoyarn=$this->autoyarn
            ->leftJoin('autoyarnratios', function($join)  {
                $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('compositions',function($join){
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
                $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
            }
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');

            $impdocaccept=$this->localexpdocaccept
            ->selectRaw('
                local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id,
                local_exp_invoices.id as local_exp_invoice_id,
                local_exp_invoices.local_invoice_no,
                style_fabrications.autoyarn_id,
                so_aop_items.autoyarn_id as c_autoyarn_id,
                sales_orders.sale_order_no,
                so_aop_items.gmt_sale_order_no,
                uoms.code as uom_name,
                so_uoms.code as so_uom_name,
                sum(local_exp_invoice_orders.qty) as invoice_qty,
                avg(local_exp_invoice_orders.rate) as invoice_rate,
                sum(local_exp_invoice_orders.amount) as invoice_amount
            ')
            ->join('local_exp_doc_sub_invoices',function($join){
                $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            })
            ->join('local_exp_invoices',function($join){
                $join->on('local_exp_doc_sub_invoices.local_exp_invoice_id','=','local_exp_invoices.id');
            })
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
                $join->on('local_exp_doc_sub_accepts.local_exp_lc_id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->join('so_aop_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_aop_refs.id');
            })
            ->join('so_aops',function($join){
                $join->on('so_aop_refs.so_aop_id','=','so_aops.id');
            })
            ->leftJoin('so_aop_pos',function($join){
                $join->on('so_aop_pos.so_aop_id','=','so_aops.id');
            })
            ->leftJoin('so_aop_po_items',function($join){
                $join->on('so_aop_po_items.so_aop_ref_id','=','so_aop_refs.id');
            })
            ->leftJoin('po_aop_service_item_qties',function($join){
                $join->on('po_aop_service_item_qties.id','=','so_aop_po_items.po_aop_service_item_qty_id');
            })
            ->leftJoin('po_aop_service_items',function($join){
                $join->on('po_aop_service_items.id','=','po_aop_service_item_qties.po_aop_service_item_id')
            ->whereNull('po_aop_service_items.deleted_at');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','po_aop_service_item_qties.sales_order_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','po_aop_service_item_qties.fabric_color_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('budget_fabric_prods',function($join){
                $join->on('budget_fabric_prods.id','=','po_aop_service_items.budget_fabric_prod_id');
            })
            ->leftJoin('budget_fabrics',function($join){
                $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
            })
            ->leftJoin('style_fabrications',function($join){
                $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
            })
            ->leftJoin('autoyarns',function($join){
                $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
            })
            ->leftJoin('constructions', function($join)  {
                $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->leftJoin('so_aop_items',function($join){
                $join->on('so_aop_items.so_aop_ref_id','=','so_aop_refs.id');
            })
            ->leftJoin('uoms',function($join){
                $join->on('uoms.id','=','style_fabrications.uom_id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_aop_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_doc_sub_accepts.id','=',$localexpdocsubaccept->id]])
            ->orderBy('local_exp_invoices.id','desc')
            ->groupBy([
                'local_exp_doc_sub_accepts.id',
                'local_exp_invoices.id',
                'local_exp_invoices.local_invoice_no',
                'style_fabrications.autoyarn_id',
                'sales_orders.sale_order_no',
                'so_aop_items.gmt_sale_order_no',
                'so_aop_items.autoyarn_id',
                'uoms.code',
                'so_uoms.code',
            ])
            ->get()
            ->map(function ($impdocaccept) use($color,$gmtspart,$fabriclooks,$fabricshape,$colorrange,$color,$aoptype,$desDropdown){

                $impdocaccept->fabrication=$impdocaccept->autoyarn_id?$desDropdown[$impdocaccept->autoyarn_id]:$desDropdown[$impdocaccept->c_autoyarn_id];
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->item_description=$impdocaccept->fabrication;
                $impdocaccept->uom_code=$impdocaccept->uom_name?$impdocaccept->uom_name:$impdocaccept->so_uom_name;
                $impdocaccept->gross_qty=$impdocaccept->invoice_qty+($impdocaccept->invoice_qty*.001);
                return $impdocaccept;
            });
    
        }
        //Embelishment Work Order
        elseif ($production_area_id==45 ||$production_area_id==50 || $production_area_id==51) {
            $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'','');
            $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');
            $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getEmbelishmentTypes(),'name','id'),'','');
            $embelishment=array_prepend(array_pluck($this->embelishment->get(),'name','id'),'','');
            $color=array_prepend(array_pluck($this->color->get(),'name','id'),'','');
            $size=array_prepend(array_pluck($this->size->get(),'name','id'),'','');

            $impdocaccept=$this->localexpdocaccept
            ->selectRaw('
                local_exp_doc_sub_accepts.id as local_exp_doc_sub_accept_id,
                local_exp_invoices.id as local_exp_invoice_id,
                local_exp_invoices.local_invoice_no,
                so_embs.sales_order_no,
                sales_orders.sale_order_no,
                so_emb_items.gmt_sale_order_no,
                item_accounts.item_description,
                sum(local_exp_invoice_orders.qty) as invoice_qty,
                avg(local_exp_invoice_orders.rate) as invoice_rate,
                sum(local_exp_invoice_orders.amount) as invoice_amount
            ')
            ->join('local_exp_doc_sub_invoices',function($join){
                $join->on('local_exp_doc_sub_invoices.local_exp_doc_sub_accept_id','=','local_exp_doc_sub_accepts.id');
            })
            ->join('local_exp_invoices',function($join){
                $join->on('local_exp_doc_sub_invoices.local_exp_invoice_id','=','local_exp_invoices.id');
            })
            ->join('local_exp_lcs', function($join)  {
                $join->on('local_exp_lcs.id', '=', 'local_exp_invoices.local_exp_lc_id');
                $join->on('local_exp_doc_sub_accepts.local_exp_lc_id', '=', 'local_exp_invoices.local_exp_lc_id');
            })
            ->join('local_exp_lc_tag_pis', function($join) {
                $join->on('local_exp_lc_tag_pis.local_exp_lc_id', '=', 'local_exp_lcs.id');
            })
            ->join('local_exp_pis', function($join)  {
                $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            ->join('local_exp_pi_orders', function($join)  {
                $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
                $join->whereNull('local_exp_pi_orders.deleted_at');
            })
            ->leftJoin('so_emb_refs', function($join)  {
                $join->on('local_exp_pi_orders.sales_order_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin('so_embs',function($join){
                $join->on('so_emb_refs.so_emb_id','=','so_embs.id');
            })
            ->leftJoin('so_emb_pos',function($join){
                $join->on('so_emb_pos.so_emb_id','=','so_embs.id');
            })
            ->leftJoin('so_emb_po_items',function($join){
                $join->on('so_emb_po_items.so_emb_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin('po_emb_service_item_qties',function($join){
                $join->on('po_emb_service_item_qties.id','=','so_emb_po_items.po_emb_service_item_qty_id');
            })
            ->leftJoin('po_emb_service_items',function($join){
                $join->on('po_emb_service_items.id','=','po_emb_service_item_qties.po_emb_service_item_id')
                ->whereNull('po_emb_service_items.deleted_at');
            })
            ->leftJoin('budget_embs',function($join){
                $join->on('budget_embs.id','=','po_emb_service_items.budget_emb_id');
            })
            ->leftJoin('style_embelishments',function($join){
                $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
            })
            ->leftJoin('style_gmts',function($join){
                $join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
            })
            ->leftJoin('item_accounts', function($join) {
                $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
            })
            ->leftJoin('gmtsparts',function($join){
                $join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
            })
            ->leftJoin('embelishments',function($join){
                $join->on('embelishments.id','=','style_embelishments.embelishment_id');
            })
            ->leftJoin('embelishment_types',function($join){
                $join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
            })
            ->leftJoin('budget_emb_cons',function($join){
                $join->on('budget_emb_cons.id','=','po_emb_service_item_qties.budget_emb_con_id')
                ->whereNull('budget_emb_cons.deleted_at');
            })
            ->leftJoin('sales_order_gmt_color_sizes',function($join){
                $join->on('sales_order_gmt_color_sizes.id','=','budget_emb_cons.sales_order_gmt_color_size_id');
            })
            ->leftJoin('sales_order_countries',function($join){
                $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
            })
            ->leftJoin('sales_orders',function($join){
                $join->on('sales_orders.id','=','sales_order_countries.sale_order_id');
            })
            ->leftJoin('style_gmt_color_sizes',function($join){
                $join->on('style_gmt_color_sizes.id','=','sales_order_gmt_color_sizes.style_gmt_color_size_id');
            })
            ->leftJoin('jobs',function($join){
                $join->on('jobs.id','=','sales_orders.job_id');
            })
            ->leftJoin('styles',function($join){
                $join->on('styles.id','=','jobs.style_id');
            })
            ->leftJoin('style_sizes',function($join){
                $join->on('style_sizes.id','=','style_gmt_color_sizes.style_size_id');
            })
            ->leftJoin('sizes',function($join){
                $join->on('sizes.id','=','style_sizes.size_id');
            })
            ->leftJoin('style_colors',function($join){
                $join->on('style_colors.id','=','style_gmt_color_sizes.style_color_id');
            })
            ->leftJoin('colors',function($join){
                $join->on('colors.id','=','style_colors.color_id');
            })
            ->leftJoin('so_emb_items',function($join){
                $join->on('so_emb_items.so_emb_ref_id','=','so_emb_refs.id');
            })
            ->leftJoin('uoms as so_uoms',function($join){
                $join->on('so_uoms.id','=','so_emb_items.uom_id');
            })
            ->join('local_exp_invoice_orders',function($join){
                $join->on('local_exp_invoice_orders.local_exp_pi_order_id','=','local_exp_pi_orders.id');
                $join->on('local_exp_invoice_orders.local_exp_invoice_id','=','local_exp_invoices.id');
                $join->whereNull('local_exp_invoice_orders.deleted_at');
            })
            ->where([['local_exp_doc_sub_accepts.id','=',$localexpdocsubaccept->id]])
            ->orderBy('local_exp_invoices.id','desc')
            ->groupBy([
                'local_exp_doc_sub_accepts.id',
                'local_exp_invoices.id',
                'local_exp_invoices.local_invoice_no',
                'so_embs.sales_order_no',
                'sales_orders.sale_order_no',
                'so_emb_items.gmt_sale_order_no',
                'item_accounts.item_description',
                'so_uoms.code',
            ])
            ->get()
            ->map(function ($impdocaccept) use($gmtspart,$embelishmentsize,$embelishmenttype,$embelishment,$color,$size){
                $impdocaccept->sale_order_no=$impdocaccept->sale_order_no?$impdocaccept->sale_order_no:$impdocaccept->gmt_sale_order_no;
                $impdocaccept->item_description=$impdocaccept->item_description;
                $impdocaccept->uom_code='Pcs'?'Pcs':$impdocaccept->so_uom_name;
                $impdocaccept->gross_qty=$impdocaccept->invoice_qty+($impdocaccept->invoice_qty*.001);
                return $impdocaccept;
            });
            
        }
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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
        $challan=str_pad($id,10,0,STR_PAD_LEFT ) ;
        $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
        $pdf->SetY(10);
        $image_file ='images/logo/'.$localexpdocaccept['logo'];
        $pdf->Image($image_file, 90, 5, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $pdf->SetY(13);
        $pdf->SetFont('helvetica', 'N', 9);
        //$pdf->Text(68, 16, $localexpdocaccept['company_address']);
        $pdf->Cell(0, 40, $localexpdocaccept['company_address'], 0, false, 'C', 0, '', 0, false, 'T', 'M' );
        //$pdf->SetY(16);
        $pdf->SetFont('helvetica', '', 8);
        $view= \View::make('Defult.Commercial.LocalExport.LocalExpPackingListPdf',['impdocaccept'=>$impdocaccept,'localexpdocaccept'=>$localexpdocaccept,'localpi'=>$localpi,'arrPi'=>$arrPi]);
        $html_content=$view->render();
        $pdf->SetY(36);
        
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/LocalExpPackingListPdf.pdf';
        $pdf->output($filename,'I');
        exit();
    }
}