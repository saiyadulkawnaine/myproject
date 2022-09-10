<?php
namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Marketing\DayTargetTransferRepository;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemRepository;
use App\Repositories\Contracts\Production\Kniting\ProdKnitRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;

use App\Library\Template;
use App\Library\Sms;
use App\Http\Requests\Marketing\DayTargetTransferRequest;

class DayTargetTransferController extends Controller {

    private $targettransfer;
    private $salesorder;
    private $company;
    private $plknititem;
    private $prodknit;
    private $wstudylinesetup;
   

    public function __construct(
        DayTargetTransferRepository $targettransfer, 
        CompanyRepository $company,
        SalesOrderRepository $salesorder,
        PlKnitItemRepository $plknititem,
        ProdKnitRepository $prodknit,
        WstudyLineSetupRepository $wstudylinesetup
    ) {
        $this->targettransfer = $targettransfer;
        $this->company = $company;
        $this->salesorder = $salesorder;
        $this->plknititem  = $plknititem;
        $this->prodknit    = $prodknit;
        $this->wstudylinesetup  = $wstudylinesetup;
        $this->middleware('auth');
        /*$this->middleware('permission:view.targettransfers',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.targettransfers', ['only' => ['store']]);
        $this->middleware('permission:edit.targettransfers',   ['only' => ['update']]);
        $this->middleware('permission:delete.targettransfers', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {        
        $tergetProcess=array_prepend(config('bprs.tergetProcess'),'-Select-','');	
        $rows=$this->targettransfer
        ->join('sales_orders',function($join){
        $join->on('sales_orders.id','=','day_target_transfers.sales_order_id');
        })
        ->join('companies',function($join){
        $join->on('companies.id','=','day_target_transfers.produced_company_id');
        })
        ->orderBy('day_target_transfers.id','desc')
        ->get([
            'day_target_transfers.*',
            'sales_orders.sale_order_no',
            'sales_orders.ship_date',
            'companies.name as company_name'
        ])
        ->map(function($rows) use($tergetProcess){
        	$rows->process=$tergetProcess[$rows->process_id];
        	$rows->ship_date=date('d-M-Y',strtotime($rows->ship_date));
        	$rows->target_date=date('d-M-Y',strtotime($rows->target_date));
        	return $rows;
        });
        
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
 
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$tergetProcess=array_prepend(config('bprs.tergetProcess'),'-Select-','');	
        return Template::loadView('Marketing.DayTargetTransfer', ['tergetProcess'=> $tergetProcess, 'company'=> $company ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DayTargetTransferRequest $request) {
        $max=$this->targettransfer->max('entry_id');
        $entry_id=$max+1;
        $targettransfer=$this->targettransfer->create([
            'entry_id'=>$entry_id,
            'sales_order_id'=>$request->sales_order_id,
            'produced_company_id'=>$request->produced_company_id,
            'process_id'=>$request->process_id,
            'target_date'=>$request->target_date,
            'qty'=>$request->qty,
            'prod_qty'=>$request->prod_qty
        ]);
        if($targettransfer){
            return response()->json(array('success' => true,'id' =>  $targettransfer->id, 'entry_id'=>$entry_id ,'message' => 'Save Successfully'),200);
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

    $targettransfer=$this->targettransfer   
    ->leftJoin('sales_orders',function($join){
        $join->on('sales_orders.id','=','day_target_transfers.sales_order_id');
    })
    ->where([['day_target_transfers.id','=',$id]])
    ->get([
       'day_target_transfers.*',
       'sales_orders.id as sales_order_id',
       'sales_orders.sale_order_no',
       'sales_orders.ship_date'
   ])
   ->map(function($targettransfer){
        $targettransfer->ship_date=date('Y-m-d',strtotime($targettransfer->ship_date));
        $targettransfer->target_date=date('Y-m-d',strtotime($targettransfer->target_date));
        return $targettransfer;
   })
   ->first();

        $row ['fromData'] = $targettransfer;
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
    public function update(DayTargetTransferRequest $request, $id) {
        $targettransfer=$this->targettransfer->update($id,$request->except(['id','entry_id','sale_order_no']));
        if($targettransfer){
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
        if($this->targettransfer->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getTargetTransfer(){

        $salesorder=$this->salesorder
        ->selectRaw('
            sales_orders.id as sales_order_id,
            sales_orders.sale_order_no,
            sales_orders.ship_date,
            sales_orders.produced_company_id,
            styles.style_ref,
            styles.id as style_id,
            jobs.job_no,
            buyers.code as buyer_name,
            companies.id as company_id,
            companies.name as company_name,
            produced_company.name as produced_company_name,
            countries.name as country_id,
            sum(sales_order_gmt_color_sizes.qty) as order_qty,
            avg(sales_order_gmt_color_sizes.rate) as order_rate,
            sum(sales_order_gmt_color_sizes.amount) as order_amount
         ')
         ->join('sales_order_countries',function($join){
            $join->on('sales_orders.id', '=' , 'sales_order_countries.sale_order_id');
         })
        ->join('countries', function($join) {
            $join->on('countries.id', '=', 'sales_order_countries.country_id');
         })
        
        ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
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
        ->join('sales_order_gmt_color_sizes', function($join)  {
            $join->on('sales_order_gmt_color_sizes.sale_order_id', '=', 'sales_orders.id');
         })
        ->join('style_gmts',function($join){
            $join->on('style_gmts.id','=','sales_order_gmt_color_sizes.style_gmt_id');
         })
        ->join('buyers', function($join)  {
            $join->on('buyers.id', '=', 'styles.buyer_id');
         }) 
        ->when(request('style_ref'), function ($q) {
            return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
         })
        ->when(request('job_no'), function ($q) {
            return $q->where('jobs.job_no', 'LIKE', "%".request('job_no', 0)."%");
         })
        ->when(request('sale_order_no'), function ($q) {
            return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
         })
        ->groupBy([
            'sales_orders.id',
            'sales_orders.sale_order_no',
            'sales_orders.ship_date',
            'sales_orders.produced_company_id',
            'styles.style_ref',
            'styles.id',
            'jobs.job_no',
            'buyers.code',
            'companies.id',
            'companies.name',
            'produced_company.name',
            'countries.name',
        ])
        ->get()
        ->map(function ($salesorder){
            $salesorder->ship_date=date('d-M-Y',strtotime($salesorder->ship_date));
           return $salesorder;
         });
        echo json_encode($salesorder);
    }

    public function sendSms()
    {
        $target_date=request('target_date',0);
        if(!$target_date){
            $target_date=date('Y-m-d');
        }

        $plknititem=$this->plknititem
        ->selectRaw(
           'pl_knits.company_id,
            sum(pl_knit_item_qties.qty) as qty
           '
        )
        ->join('pl_knits', function($join)  {
            $join->on('pl_knits.id', '=', 'pl_knit_items.pl_knit_id');
        })
        ->join('pl_knit_item_qties', function($join)  {
            $join->on('pl_knit_item_qties.pl_knit_item_id', '=', 'pl_knit_items.id');
        })
        ->when($target_date, function ($q) use($target_date){
        return $q->where('pl_knit_item_qties.pl_date', '>=',$target_date);
        })
        ->when($target_date, function ($q) use($target_date) {
        return $q->where('pl_knit_item_qties.pl_date', '<=',$target_date);
        })
        ->groupBy(['pl_knits.company_id'])
        ->get()->first();
        $todayKnitTerget=0;
        if($plknititem){
            $todayKnitTerget=$plknititem->qty;
        }

        $prodknit=$this->prodknit
        ->selectRaw(
           'sum(prod_knit_item_rolls.roll_weight) as roll_weight
           '
        )
        ->join('prod_knit_items', function($join)  {
            $join->on('prod_knit_items.prod_knit_id', '=', 'prod_knits.id');
        })
        ->join('prod_knit_item_rolls', function($join)  {
            $join->on('prod_knit_item_rolls.prod_knit_item_id', '=', 'prod_knit_items.id');
        })
        ->when($target_date, function ($q) use($target_date){
        return $q->where('prod_knits.prod_date', '>=',$target_date);
        })
        ->when($target_date, function ($q) use($target_date) {
        return $q->where('prod_knits.prod_date', '<=',$target_date);
        })
        ->where([['prod_knits.basis_id','=',1]])
        ->get()
        ->first();
        $todayknit=0;
        if($prodknit){
            $todayknit=$prodknit->roll_weight;
        }

        $text="Day Target Transfer(".date('d-M-Y',strtotime($target_date)).")\n";
        $knittingvar=$todayknit - $todayKnitTerget;
        $text.="Knitting (FFL)\n";
        $text.="Tgt. ".number_format($todayKnitTerget,0)."\n";
        $text.="Achv. ".number_format($todayknit,0)."\n";
        $text.="Var. ".number_format($knittingvar,0)."\n";

        /*$prodgmtsewing=$this->wstudylinesetup
        ->join('wstudy_line_setup_dtls', function($join) use($date_to) {
        $join->on('wstudy_line_setup_dtls.wstudy_line_setup_id', '=', 'wstudy_line_setups.id');
        $join->where('wstudy_line_setup_dtls.from_date', '>=',$target_date);
        $join->where('wstudy_line_setup_dtls.to_date', '<=',$target_date);
        })*/
        $results = \DB::select("
        select 
        sewingdata.company_id,
        sewingdata.company_name,
        sum(sewingdata.sew_qty) as sew_qty,
        sum(sewingdata.day_target) as day_target from (
        select 
        wstudy_line_setups.id,
        wstudy_line_setups.company_id,
        companies.code as company_name,
        wstudy_line_setup_dtls.working_hour,
        wstudy_line_setup_dtls.overtime_hour,
        wstudy_line_setup_dtls.target_per_hour,
        wstudy_line_setup_dtls.target_per_hour*(wstudy_line_setup_dtls.working_hour+wstudy_line_setup_dtls.overtime_hour) as day_target,
        sew.qty as sew_qty
        from wstudy_line_setups
        join wstudy_line_setup_dtls on wstudy_line_setups.id=wstudy_line_setup_dtls.wstudy_line_setup_id
        join companies on companies.id=wstudy_line_setups.company_id

        left join (
            SELECT m.id,sum(m.qty) as qty,sum(m.amount) as amount,sum(m.smv) as smv from 
            (  
                SELECT 
                wstudy_line_setups.id,
                prod_gmt_sewing_qties.qty,
                prod_gmt_sewing_qties.qty*sales_order_gmt_color_sizes.rate as amount,
                prod_gmt_sewing_qties.qty*style_gmts.smv as smv
                FROM prod_gmt_sewings
                join prod_gmt_sewing_orders on prod_gmt_sewing_orders.prod_gmt_sewing_id = prod_gmt_sewings.id
                join wstudy_line_setups on wstudy_line_setups.id = prod_gmt_sewing_orders.wstudy_line_setup_id 
                join wstudy_line_setup_dtls on wstudy_line_setup_dtls.wstudy_line_setup_id = wstudy_line_setups.id and
                wstudy_line_setup_dtls.from_date >= ? and 
                wstudy_line_setup_dtls.to_date<= ?
                join sales_order_countries on sales_order_countries.id = prod_gmt_sewing_orders.sales_order_country_id
                join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
                join jobs on jobs.id = sales_orders.job_id
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
                join style_gmt_color_sizes on style_gmt_color_sizes.id = sales_order_gmt_color_sizes.style_gmt_color_size_id
                join style_gmts on style_gmts.id = style_gmt_color_sizes.style_gmt_id

                join prod_gmt_sewing_qties on prod_gmt_sewing_qties.prod_gmt_sewing_order_id = prod_gmt_sewing_orders.id  and prod_gmt_sewing_qties.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
                where prod_gmt_sewings.sew_qc_date>= ? and 
                prod_gmt_sewings.sew_qc_date<= ?
                group by 
                wstudy_line_setups.id,
                prod_gmt_sewing_qties.id,prod_gmt_sewing_qties.qty,
                sales_order_gmt_color_sizes.id,sales_order_gmt_color_sizes.rate,
                style_gmts.smv
            ) m group by m.id
        ) sew on sew.id=wstudy_line_setups.id

        where 
        wstudy_line_setup_dtls.from_date>= ? and
        wstudy_line_setup_dtls.to_date<= ?  and 
        wstudy_line_setups.deleted_at is null and
        wstudy_line_setup_dtls.deleted_at is null) sewingdata
        group by sewingdata.company_id,sewingdata.company_name


        ",[$target_date,$target_date,$target_date,$target_date,$target_date,$target_date]);
        $prodsewings=collect($results)
        ->map(function($prodsewings){
            $prodsewings->var=$prodsewings->sew_qty-$prodsewings->day_target;
            $prodsewings->head="Sewing ( ".$prodsewings->company_name." )";
            return $prodsewings;
        });
        $textp="Day Target Transfer(".date('d-M-Y',strtotime($target_date)).")\n";
        foreach($prodsewings as $prodsewing){
            $textp.=$prodsewing->head."\n";
            $textp.="Tgt. ".number_format($prodsewing->day_target,0)."\n";
            $textp.="Achv. ".number_format($prodsewing->sew_qty,0)."\n";
            $textp.="Var. ".number_format($prodsewing->var,0)."\n";
        }

        $tergetProcess=array_prepend(config('bprs.tergetProcess'),'-Select-','');   
        $rows=$this->targettransfer
        ->selectRaw(
        '
        day_target_transfers.produced_company_id,
        companies.code as company_name,
        day_target_transfers.target_date,
        day_target_transfers.process_id,
        sum(day_target_transfers.qty) as qty,
        sum(day_target_transfers.prod_qty) as prod_qty
        '
        )
        ->join('companies', function($join)  {
        $join->on('companies.id', '=', 'day_target_transfers.produced_company_id');
        })
        ->where([['day_target_transfers.target_date','=',$target_date]])
        ->where([['day_target_transfers.process_id','!=',1]])
        ->where([['day_target_transfers.process_id','!=',8]])
        ->orderBy('day_target_transfers.process_id')
        ->groupBy([
        'day_target_transfers.produced_company_id',
        'companies.code',
        'day_target_transfers.target_date',
        'day_target_transfers.process_id'
        ])
        ->get()
        ->map(function($rows) use($tergetProcess){
            $rows->process=$tergetProcess[$rows->process_id];
            $rows->var=$rows->prod_qty-$rows->qty;
            $rows->head=$rows->process."( ".$rows->company_name." )";
            return $rows;
        });
        
        foreach($rows as $row){
            if($row->process_id==2 || $row->process_id==3 || $row->process_id==4){
                $text.=$row->head."\n";
                $text.="Tgt. ".number_format($row->qty,0)."\n";
                $text.="Achv. ".number_format($row->prod_qty,0)."\n";
                $text.="Var. ".number_format($row->var,0)."\n";
            }
            else{
                $textp.=$row->head."\n";
                $textp.="Tgt. ".number_format($row->qty,0)."\n";
                $textp.="Achv. ".number_format($row->prod_qty,0)."\n";
                $textp.="Var. ".number_format($row->var,0)."\n";
            }
        }

        /*$knitting = $rows->filter(function ($value) {
            if($value->process_id==1){
            return $value;
            }
        })->first();*/
        
        /*$dyeing = $rows->filter(function ($value) {
            if($value->process_id==2){
            return $value;
            }
        })->first();

        if($dyeing)
        {
            $dyeing->var=$dyeing->prod_qty-$dyeing->qty;
            $text.="Dyeing (FDL)\n";
            $text.="Tgt.".$dyeing->qty."\n";
            $text.="Achv.".$dyeing->prod_qty."\n";
            $text.="Var.".$dyeing->var."\n";
        }

         
        $dyeingFin = $rows->filter(function ($value) {
            if($value->process_id==3){
            return $value;
            }
        })->first();

        if($dyeingFin)
        {
            $dyeingFin->var=$dyeingFin->prod_qty-$dyeingFin->qty;
            $text.="Dyeing Finishing (FDL)\n";
            $text.="Tgt.".$dyeingFin->qty."\n";
            $text.="Achv.".$dyeingFin->prod_qty."\n";
            $text.="Var.".$dyeingFin->var."\n";
        }
        $aop = $rows->filter(function ($value) {
            if($value->process_id==4){
            return $value;
            }
        })->first();

        if($aop)
        {
            $aop->var=$aop->prod_qty-$aop->qty;
            $text.="AOP (FPL)\n";
            $text.="Tgt.".$aop->qty."\n";
            $text.="Achv.".$aop->prod_qty."\n";
            $text.="Var.".$aop->var."\n";
        }*/



        /*$cutting = $rows->filter(function ($value) {
            if($value->process_id==5){
            return $value;
            }
        })->first();

        if($cutting)
        {
            $cutting->var=$cutting->prod_qty-$cutting->qty;
            $textp.="Cutting\n";
            $textp.="Tgt.".$cutting->qty."\n";
            $textp.="Achv.".$cutting->prod_qty."\n";
            $textp.="Var.".$cutting->var."\n";
        }
        $scprint = $rows->filter(function ($value) {
            if($value->process_id==6){
            return $value;
            }
        })->first();

        if($scprint)
        {
            $scprint->var=$scprint->prod_qty-$scprint->qty;
            $textp.="Screen Printing\n";
            $textp.="Tgt.".$scprint->qty."\n";
            $textp.="Achv.".$scprint->prod_qty."\n";
            $textp.="Var.".$scprint->var."\n";
        }
        $emb = $rows->filter(function ($value) {
            if($value->process_id==7){
            return $value;
            }
        })->first();

        if($emb)
        {
            $emb->var=$emb->prod_qty-$emb->qty;
            $textp.="Embroidery\n";
            $textp.="Tgt.".$emb->qty."\n";
            $textp.="Achv.".$emb->prod_qty."\n";
            $textp.="Var.".$emb->var."\n";
        }

        $sewing = $rows->filter(function ($value) {
            if($value->process_id==8){
            return $value;
            }
        })->first();
        if($sewing)
        {
            $sewing->var=$sewing->prod_qty-$sewing->qty;
            $textp.="Sewing\n";
            $textp.="Tgt.".$sewing->qty."\n";
            $textp.="Achv.".$sewing->prod_qty."\n";
            $textp.="Var.".$sewing->var."\n";
        }

        $fin = $rows->filter(function ($value) {
            if($value->process_id==9){
            return $value;
            }
        })->first();
        if($fin)
        {
            $fin->var=$fin->prod_qty-$fin->qty;
            $textp.="Finishing\n";
            $textp.="Tgt.".$fin->qty."\n";
            $textp.="Achv.".$fin->prod_qty."\n";
            $textp.="Var.".$fin->var."\n";
        }

        $exf = $rows->filter(function ($value) {
            if($value->process_id==10){
            return $value;
            }
        })->first();
        if($exf)
        {
            $exf->var=$exf->prod_qty-$exf->qty;
            $textp.="Ex Factory\n";
            $textp.="Tgt.".$exf->qty."\n";
            $textp.="Achv.".$exf->prod_qty."\n";
            $textp.="Var.".$exf->var."\n";
        }*/
        //8801711563231,8801730595836,

        if($text){
            $sms=Sms::send_sms($text, '8801711563231,8801781738866,8801713043117,8801730595836,8801620913828,8801741448766,8801918864289,8801786651983,8801714064806,8801712887778,8801758304131,8801712213447,8801714786757,8801715856873,8801841642069,8801738513409,8801758392796,8801737299649');
        }
        if($textp){
            $sms=Sms::send_sms($textp, '8801711563231,8801781738866,8801713043117,8801730595836,8801620913828,8801741448766,8801918864289,8801786651983,8801714064806,8801712887778,8801758304131,8801712213447,8801714786757,8801715856873,8801841642069,8801738513409,8801758392796,8801737299649');
        }
        
        
        echo json_encode($rows);
    }
}