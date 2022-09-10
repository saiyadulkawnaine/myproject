<?php
namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Marketing\TargetTransferRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;

use App\Library\Template;
use App\Http\Requests\Marketing\TargetTransferRequest;

class TargetTransferController extends Controller {

    private $targettransfer;
    private $salesorder;
    private $company;
    private $autoyarn;
   

    public function __construct(
        TargetTransferRepository $targettransfer, 
        CompanyRepository $company,
        SalesOrderRepository $salesorder,
        AutoyarnRepository $autoyarn
    ) {
        $this->targettransfer = $targettransfer;
        $this->company = $company;
        $this->salesorder = $salesorder;
        $this->autoyarn = $autoyarn;

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
        $join->on('sales_orders.id','=','target_transfers.sales_order_id');
        })
        ->join('style_gmts',function($join){
        $join->on('style_gmts.id','=','target_transfers.style_gmt_id');
        })

        ->join('item_accounts', function($join)  {
        $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->join('companies',function($join){
        $join->on('companies.id','=','target_transfers.produced_company_id');
        })
        ->orderBy('target_transfers.id','desc')
        ->get([
            'target_transfers.*',
            'sales_orders.sale_order_no',
            'sales_orders.ship_date',
            'companies.name as company_name',
            'item_accounts.item_description as style_gmt_name'
        ])
        ->map(function($rows) use($tergetProcess){
        	$rows->process=$tergetProcess[$rows->process_id];
        	$rows->ship_date=date('d-M-Y',strtotime($rows->ship_date));
        	$rows->date_from=date('d-M-Y',strtotime($rows->date_from));
        	$rows->date_to=date('d-M-Y',strtotime($rows->date_to));

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
        //$productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
        $productionsource=array_prepend([1=>"Plant A",5=>"Plant B"],'-Select-','');
        return Template::loadView('Marketing.TargetTransfer', ['tergetProcess'=> $tergetProcess, 'company'=> $company  , 'productionsource'=>$productionsource]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TargetTransferRequest $request) {
        $max=$this->targettransfer->max('entry_id');
        $entry_id=$max+1;
        $targettransfer=$this->targettransfer->create([
            'entry_id'=>$entry_id,
            'sales_order_id'=>$request->sales_order_id,
            'produced_company_id'=>$request->produced_company_id,
            'process_id'=>$request->process_id,
            'style_gmt_id'=>$request->style_gmt_id,
            'date_from'=>$request->date_from,
            'date_to'=>$request->date_to,
            'qty'=>$request->qty
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
        $join->on('sales_orders.id','=','target_transfers.sales_order_id');
    })
    ->join('style_gmts',function($join){
    $join->on('style_gmts.id','=','target_transfers.style_gmt_id');
    })

    ->join('item_accounts', function($join)  {
    $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
    }) 
    ->where([['target_transfers.id','=',$id]])
    ->get([
       'target_transfers.*',
       'sales_orders.id as sales_order_id',
       'sales_orders.sale_order_no',
       'sales_orders.ship_date',
       'item_accounts.item_description as style_gmt_name'
   ])
   ->map(function($targettransfer){
        $targettransfer->ship_date=date('Y-m-d',strtotime($targettransfer->ship_date));
        $targettransfer->date_from=date('Y-m-d',strtotime($targettransfer->date_from));
        $targettransfer->date_to=date('Y-m-d',strtotime($targettransfer->date_to));
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
    public function update(TargetTransferRequest $request, $id) {
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
        /*$style_gmts = collect(\DB::select("
            select 
            sales_orders.id,
            style_gmts.id as style_gmt_id
            from
            target_transfers
            join
            sales_orders on sales_orders.id=target_transfers.sales_order_id
            join jobs on jobs.id=sales_orders.job_id
            join styles on styles.id=jobs.style_id
            join style_gmts on style_gmts.style_id=styles.id
            order by
            sales_orders.id
        "));
        \DB::beginTransaction();
        try
        {
        foreach($style_gmts as $style_gmt){
        
        $this->targettransfer->where([['sales_order_id','=',$style_gmt->id]])->update(['style_gmt_id'=>$style_gmt->style_gmt_id]);
        }
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
        return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);*/
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
            style_gmts.id as style_gmt_id,
            item_accounts.item_description,
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
        ->join('item_accounts', function($join)  {
            $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
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
            'style_gmts.id',
            'item_accounts.item_description',
        ])
        ->get()
        ->map(function ($salesorder){
            $salesorder->ship_date=date('d-M-Y',strtotime($salesorder->ship_date));
           return $salesorder;
         });
        echo json_encode($salesorder);
    }

    public function getInfo(){
        $process_id=request('process_id',0);
        $sales_order_id=request('sales_order_id',0);
        $style_gmt_id=request('style_gmt_id',0);

        if( $process_id==1)
        {
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
            $autoyarn=$this->autoyarn->join('autoyarnratios', function($join) {
            $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
            })
            ->join('constructions', function($join)  {
            $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->join('compositions',function($join){
            $join->on('compositions.id','=','autoyarnratios.composition_id');
            })
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

            $kniting = \DB::select("
                select 
                sales_orders.id,
                style_gmts.id as style_gmt_id,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                budget_fabrics.gsm_weight,
                budget_fabric_cons.dia
                from
                target_transfers
                join sales_orders on sales_orders.id=target_transfers.sales_order_id
                join style_gmts on style_gmts.id=target_transfers.style_gmt_id
                join style_fabrications on style_fabrications.style_gmt_id=style_gmts.id
                join budget_fabrics on style_fabrications.id=budget_fabrics.style_fabrication_id
                join budget_fabric_cons on budget_fabric_cons.budget_fabric_id=budget_fabrics.id
                join budget_fabric_prods on budget_fabric_prods.id=budget_fabrics.id
                join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
                where 
                production_processes.production_area_id=10
                and  sales_orders.id=?
                and  style_gmts.id=?
                and target_transfers.deleted_at is null
                and sales_orders.deleted_at is null
                and budget_fabric_prods.deleted_at is null
                group by 
                sales_orders.id,
                style_gmts.id,
                style_fabrications.autoyarn_id,
                style_fabrications.fabric_look_id,
                style_fabrications.fabric_shape_id,
                budget_fabrics.gsm_weight,
                budget_fabric_cons.dia
            ",[$sales_order_id,$style_gmt_id]);
            $prodknitings=collect($kniting);
            $knitingArr=[];
            foreach($prodknitings as $prodkniting)
            {
            $knitingArr[$prodkniting->id][$prodkniting->style_gmt_id][]=$desDropdown[$prodkniting->autoyarn_id].','.$fabriclooks[$prodkniting->fabric_look_id].','.$fabricshape[$prodkniting->fabric_shape_id].','.$prodkniting->gsm_weight.','.$prodkniting->dia;
            }

            $results = \DB::select("
            select 
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id,
            ords.qty as order_qty,
            ords.ship_date,
            min(target_transfers.date_from) as date_from,
            max(target_transfers.date_to) as date_to,
            kniting.plan_qty
            from
            target_transfers
            left join(
            select 
            sales_orders.id,
            style_gmts.id as style_gmt_id,
            sales_orders.ship_date,
            sum(budget_fabric_prod_cons.bom_qty) as qty
            from
            sales_orders
            join budget_fabric_prod_cons on budget_fabric_prod_cons.sales_order_id=sales_orders.id
            join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id
            join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
            join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
            join style_gmts on style_gmts.id=style_fabrications.style_gmt_id
                
            join production_processes on production_processes.id=budget_fabric_prods.production_process_id             
            where 
            production_processes.production_area_id=10
            and sales_orders.deleted_at is null
            and budget_fabric_prod_cons.deleted_at is null
            and budget_fabric_prods.deleted_at is null
            group by 
            sales_orders.id,
            style_gmts.id,
            sales_orders.ship_date
            ) ords on ords.id=target_transfers.sales_order_id and ords.style_gmt_id=target_transfers.style_gmt_id

            left join (
            select 
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id,
            sum(target_transfers.qty) as plan_qty

            from
            target_transfers
            where target_transfers.process_id=1
            group by target_transfers.sales_order_id,target_transfers.style_gmt_id
            ) kniting on kniting.sales_order_id=target_transfers.sales_order_id and kniting.style_gmt_id=target_transfers.style_gmt_id
            where target_transfers.deleted_at is null
            and target_transfers.process_id=2
            and target_transfers.sales_order_id=?
            and target_transfers.style_gmt_id=?
            group by
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id,
            ords.qty,
            ords.ship_date,
            kniting.plan_qty
            ",[$sales_order_id,$style_gmt_id]);

            $prodsewings=collect($results)
            ->map(function($prodsewings) use ($knitingArr){
            $prodsewings->yet_plan_qty=number_format($prodsewings->order_qty-$prodsewings->plan_qty,0);
            $prodsewings->plan_qty=number_format($prodsewings->plan_qty,0);
            $prodsewings->order_qty=number_format($prodsewings->order_qty,0);
            $prodsewings->ship_date=date('d-M-Y',strtotime($prodsewings->ship_date));
            $prodsewings->date_from=date('d-M-Y',strtotime($prodsewings->date_from));
            $prodsewings->date_to=date('d-M-Y',strtotime($prodsewings->date_to));
            $prodsewings->desc=implode('<br/>',$knitingArr[$prodsewings->sales_order_id][$prodsewings->style_gmt_id]);
            return $prodsewings;
            })->first();
            $tpl='';
            if($prodsewings){
            $tpl="<table border=1>";

            $tpl.="<tr>";
            $tpl.="<td width='100px'>Min Cuting Date</td>";
            $tpl.="<td width='100px'>". $prodsewings->date_from."</td>";
            $tpl.="<td rowspan='6'>". $prodsewings->desc."</td>";

            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Max Cuting Date</td>";
            $tpl.="<td>". $prodsewings->date_to."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Order Qty</td>";
            $tpl.="<td>". $prodsewings->order_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Target Done</td>";
            $tpl.="<td>". $prodsewings->plan_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Yet to Target</td>";
            $tpl.="<td>". $prodsewings->yet_plan_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Ship Date</td>";
            $tpl.="<td>".$prodsewings->ship_date."</td>";
            $tpl.="</tr>";

            $tpl.="</table>";  
            }

            
            echo $tpl;
        }

        if( $process_id==2)
        {
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
            $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
            $autoyarn=$this->autoyarn->join('autoyarnratios', function($join) {
            $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
            })
            ->join('constructions', function($join)  {
            $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->join('compositions',function($join){
            $join->on('compositions.id','=','autoyarnratios.composition_id');
            })
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

            $dyeing = \DB::select("
            select 
            sales_orders.id,
            style_gmts.id as style_gmt_id,
            style_fabrications.autoyarn_id,
            style_fabrications.fabric_look_id,
            style_fabrications.fabric_shape_id,
            style_fabrications.dyeing_type_id,
            budget_fabrics.gsm_weight,
            budget_fabric_cons.dia
            from
            target_transfers
            join sales_orders on sales_orders.id=target_transfers.sales_order_id
            join style_gmts on style_gmts.id=target_transfers.style_gmt_id
            join style_fabrications on style_fabrications.style_gmt_id=style_gmts.id
            join budget_fabrics on style_fabrications.id=budget_fabrics.style_fabrication_id
            join budget_fabric_cons on budget_fabric_cons.budget_fabric_id=budget_fabrics.id
            join budget_fabric_prods on budget_fabric_prods.id=budget_fabrics.id
            join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
            where 
            production_processes.production_area_id=20
            and  sales_orders.id=?
            and  style_gmts.id=?
            and target_transfers.deleted_at is null
            and sales_orders.deleted_at is null
            and budget_fabric_prods.deleted_at is null
            group by 
            sales_orders.id,
            style_gmts.id,
            style_fabrications.autoyarn_id,
            style_fabrications.fabric_look_id,
            style_fabrications.fabric_shape_id,
            style_fabrications.dyeing_type_id,
            budget_fabrics.gsm_weight,
            budget_fabric_cons.dia
            ",[$sales_order_id,$style_gmt_id]);
            $proddyeings=collect($dyeing);
            $dyeingArr=[];
            foreach($proddyeings as $proddyeing)
            {
            $dyeingArr[$proddyeing->id][$proddyeing->style_gmt_id][]=$desDropdown[$proddyeing->autoyarn_id].','.$fabriclooks[$proddyeing->fabric_look_id].','.$fabricshape[$proddyeing->fabric_shape_id].','.$proddyeing->gsm_weight.','.$proddyeing->dia.','.$dyetype[$proddyeing->dyeing_type_id];
            }

            /*$results = \DB::select("
            select 
            target_transfers.sales_order_id,
            ords.qty as order_qty,
            ords.ship_date,
            min(target_transfers.date_from) as date_from,
            max(target_transfers.date_to) as date_to,
            dyeing.plan_qty
            from
            target_transfers
            left join(
            select 
            sales_orders.id,
            sales_orders.ship_date,
            sum(budget_fabric_prod_cons.bom_qty) as qty
            from
            sales_orders
            --join sales_orders on sales_orders.id=target_transfers.sales_order_id
            join budget_fabric_prod_cons on budget_fabric_prod_cons.sales_order_id=sales_orders.id
            join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id
            join production_processes on production_processes.id=budget_fabric_prods.production_process_id             
            where 
            production_processes.production_area_id=20
            --and target_transfers.deleted_at is null
            and sales_orders.deleted_at is null
            and budget_fabric_prod_cons.deleted_at is null
            and budget_fabric_prods.deleted_at is null
            group by 
            sales_orders.id,
            sales_orders.ship_date

            ) ords on ords.id=target_transfers.sales_order_id

            left join (
            select 
            target_transfers.sales_order_id,
            sum(target_transfers.qty) as plan_qty

            from
            target_transfers
            where target_transfers.process_id=2
            group by target_transfers.sales_order_id
            ) dyeing on dyeing.sales_order_id=target_transfers.sales_order_id
            where target_transfers.deleted_at is null
            and target_transfers.process_id=5
            and target_transfers.sales_order_id=?
            group by
            target_transfers.sales_order_id,
            ords.qty,
            ords.ship_date,
            dyeing.plan_qty
            ",[$sales_order_id]);*/
            $results = \DB::select("
            select 
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id,
            ords.qty as order_qty,
            ords.ship_date,
            min(target_transfers.date_from) as date_from,
            max(target_transfers.date_to) as date_to,
            dyeing.plan_qty
            from
            target_transfers
            left join(
            select 
            sales_orders.id,
            style_gmts.id as style_gmt_id,
            sales_orders.ship_date,
            sum(budget_fabric_prod_cons.bom_qty) as qty
            from
            sales_orders
            join budget_fabric_prod_cons on budget_fabric_prod_cons.sales_order_id=sales_orders.id
            join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id
            join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
            join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
            join style_gmts on style_gmts.id=style_fabrications.style_gmt_id

            join production_processes on production_processes.id=budget_fabric_prods.production_process_id             
            where 
            production_processes.production_area_id=20
            and sales_orders.deleted_at is null
            and budget_fabric_prod_cons.deleted_at is null
            and budget_fabric_prods.deleted_at is null
            group by 
            sales_orders.id,
            style_gmts.id,
            sales_orders.ship_date
            ) ords on ords.id=target_transfers.sales_order_id and ords.style_gmt_id=target_transfers.style_gmt_id

            left join (
            select 
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id,
            sum(target_transfers.qty) as plan_qty

            from
            target_transfers
            where target_transfers.process_id=2
            group by target_transfers.sales_order_id,target_transfers.style_gmt_id
            ) dyeing on dyeing.sales_order_id=target_transfers.sales_order_id and dyeing.style_gmt_id=target_transfers.style_gmt_id
            where target_transfers.deleted_at is null
            and target_transfers.process_id=5
            and target_transfers.sales_order_id=?
            and target_transfers.style_gmt_id=?
            group by
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id,
            ords.qty,
            ords.ship_date,
            dyeing.plan_qty
            ",[$sales_order_id,$style_gmt_id]);

            $prodsewings=collect($results)
            ->map(function($prodsewings) use($dyeingArr){
            $prodsewings->yet_plan_qty=number_format($prodsewings->order_qty-$prodsewings->plan_qty,0);
            $prodsewings->plan_qty=number_format($prodsewings->plan_qty,0);
            $prodsewings->order_qty=number_format($prodsewings->order_qty,0);
            $prodsewings->ship_date=date('d-M-Y',strtotime($prodsewings->ship_date));
            $prodsewings->date_from=date('d-M-Y',strtotime($prodsewings->date_from));
            $prodsewings->date_to=date('d-M-Y',strtotime($prodsewings->date_to));
            $prodsewings->desc=implode('<br/>',$dyeingArr[$prodsewings->sales_order_id][$prodsewings->style_gmt_id]);
            return $prodsewings;
            })->first();
            $tpl='';
            if($prodsewings){
            $tpl="<table border=1>";
            $tpl.="<tr>";
            $tpl.="<td width='100px'>Min Cuting Date</td>";
            $tpl.="<td width='100px'>". $prodsewings->date_from."</td>";
            $tpl.="<td rowspan='6'>". $prodsewings->desc."</td>";

            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Max Cuting Date</td>";
            $tpl.="<td>". $prodsewings->date_to."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Order Qty</td>";
            $tpl.="<td>". $prodsewings->order_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Target Done</td>";
            $tpl.="<td>". $prodsewings->plan_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Yet to Target</td>";
            $tpl.="<td>". $prodsewings->yet_plan_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Ship Date</td>";
            $tpl.="<td>".$prodsewings->ship_date."</td>";
            $tpl.="</tr>";

            $tpl.="</table>";  
            }


            echo $tpl;
        }

        if( $process_id==4)
        {
            $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
            $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');



            $autoyarn=$this->autoyarn->join('autoyarnratios', function($join) {
            $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
            })
            ->join('constructions', function($join)  {
            $join->on('autoyarns.construction_id', '=', 'constructions.id');
            })
            ->join('compositions',function($join){
            $join->on('compositions.id','=','autoyarnratios.composition_id');
            })
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

            /*$aop = \DB::select("
            select 
            sales_orders.id,
            style_fabrications.autoyarn_id,
            style_fabrications.fabric_look_id,
            style_fabrications.fabric_shape_id,
            style_fabrications.embelishment_type_id,
            embelishment_types.name as type_name,
            budget_fabrics.gsm_weight,
            budget_fabric_cons.dia

            from
            target_transfers
            join sales_orders on sales_orders.id=target_transfers.sales_order_id
            join budget_fabric_prod_cons on budget_fabric_prod_cons.sales_order_id=sales_orders.id
            join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id
            join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
            join budget_fabric_cons on budget_fabric_cons.budget_fabric_id=budget_fabrics.id
            join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
            join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id 
            join embelishment_types on embelishment_types.id=style_fabrications.embelishment_type_id             
            where 
            production_processes.production_area_id=25
            and  sales_orders.id=?
            and target_transfers.deleted_at is null
            and sales_orders.deleted_at is null
            and budget_fabric_prod_cons.deleted_at is null
            and budget_fabric_prods.deleted_at is null
            group by 
            sales_orders.id,
            style_fabrications.autoyarn_id,
            style_fabrications.fabric_look_id,
            style_fabrications.fabric_shape_id,
            style_fabrications.embelishment_type_id,
            embelishment_types.name,
            budget_fabrics.gsm_weight,
            budget_fabric_cons.dia
            ",[$sales_order_id]);
            $prodaops=collect($aop);*/
            $aop = \DB::select("
            select 
            sales_orders.id,
            style_gmts.id as style_gmt_id,
            style_fabrications.autoyarn_id,
            style_fabrications.fabric_look_id,
            style_fabrications.fabric_shape_id,
            style_fabrications.embelishment_type_id,
            embelishment_types.name as type_name,
            budget_fabrics.gsm_weight,
            budget_fabric_cons.dia
            from
            target_transfers
            join sales_orders on sales_orders.id=target_transfers.sales_order_id
            join style_gmts on style_gmts.id=target_transfers.style_gmt_id
            join style_fabrications on style_fabrications.style_gmt_id=style_gmts.id
            join budget_fabrics on style_fabrications.id=budget_fabrics.style_fabrication_id
            join budget_fabric_cons on budget_fabric_cons.budget_fabric_id=budget_fabrics.id
            join budget_fabric_prods on budget_fabric_prods.id=budget_fabrics.id
            join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
            join embelishment_types on embelishment_types.id=style_fabrications.embelishment_type_id 
            where 
            production_processes.production_area_id=25
            and  sales_orders.id=?
            and  style_gmts.id=?
            and target_transfers.deleted_at is null
            and sales_orders.deleted_at is null
            and budget_fabric_prods.deleted_at is null
            group by 
            sales_orders.id,
            style_gmts.id,
            style_fabrications.autoyarn_id,
            style_fabrications.fabric_look_id,
            style_fabrications.fabric_shape_id,
            style_fabrications.embelishment_type_id,
            embelishment_types.name,
            budget_fabrics.gsm_weight,
            budget_fabric_cons.dia
            ",[$sales_order_id,$style_gmt_id]);
            $prodaops=collect($aop);
            $aopArr=[];
            foreach($prodaops as $prodaop)
            {
            $aopArr[$prodaop->id][$prodaop->style_gmt_id][]=$desDropdown[$prodaop->autoyarn_id].','.$fabriclooks[$prodaop->fabric_look_id].','.$fabricshape[$prodaop->fabric_shape_id].','.$prodaop->gsm_weight.','.$prodaop->dia.','.$prodaop->type_name;
            }



            /*$results = \DB::select("
            select 
            target_transfers.sales_order_id,
            ords.qty as order_qty,
            ords.ship_date,

            min(target_transfers.date_from) as date_from,
            max(target_transfers.date_to) as date_to,
            aop.plan_qty
            from
            target_transfers
            left join(
            select 
            sales_orders.id,
            sales_orders.ship_date,
            sum(budget_fabric_prod_cons.bom_qty) as qty
            from
            sales_orders
            --join sales_orders on sales_orders.id=target_transfers.sales_order_id
            join budget_fabric_prod_cons on budget_fabric_prod_cons.sales_order_id=sales_orders.id
            join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id
            join production_processes on production_processes.id=budget_fabric_prods.production_process_id             
            where 
            production_processes.production_area_id=25
            --and target_transfers.deleted_at is null
            and sales_orders.deleted_at is null
            and budget_fabric_prod_cons.deleted_at is null
            and budget_fabric_prods.deleted_at is null
            group by 
            sales_orders.id,
            sales_orders.ship_date

            ) ords on ords.id=target_transfers.sales_order_id

            left join (
            select 
            target_transfers.sales_order_id,
            sum(target_transfers.qty) as plan_qty

            from
            target_transfers
            where target_transfers.process_id=4
            group by target_transfers.sales_order_id
            ) aop on aop.sales_order_id=target_transfers.sales_order_id
            where target_transfers.deleted_at is null
            and target_transfers.process_id=5
            and target_transfers.sales_order_id=?
            group by
            target_transfers.sales_order_id,
            ords.qty,
            ords.ship_date,
            aop.plan_qty
            ",[$sales_order_id]);*/
            $results = \DB::select("
            select 
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id,
            ords.qty as order_qty,
            ords.ship_date,
            min(target_transfers.date_from) as date_from,
            max(target_transfers.date_to) as date_to,
            dyeing.plan_qty
            from
            target_transfers
            left join(
            select 
            sales_orders.id,
            style_gmts.id as style_gmt_id,
            sales_orders.ship_date,
            sum(budget_fabric_prod_cons.bom_qty) as qty
            from
            sales_orders
            join budget_fabric_prod_cons on budget_fabric_prod_cons.sales_order_id=sales_orders.id
            join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id
            join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
            join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
            join style_gmts on style_gmts.id=style_fabrications.style_gmt_id

            join production_processes on production_processes.id=budget_fabric_prods.production_process_id             
            where 
            production_processes.production_area_id=25
            and sales_orders.deleted_at is null
            and budget_fabric_prod_cons.deleted_at is null
            and budget_fabric_prods.deleted_at is null
            group by 
            sales_orders.id,
            style_gmts.id,
            sales_orders.ship_date
            ) ords on ords.id=target_transfers.sales_order_id and ords.style_gmt_id=target_transfers.style_gmt_id

            left join (
            select 
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id,
            sum(target_transfers.qty) as plan_qty

            from
            target_transfers
            where target_transfers.process_id=4
            group by target_transfers.sales_order_id,target_transfers.style_gmt_id
            ) dyeing on dyeing.sales_order_id=target_transfers.sales_order_id and dyeing.style_gmt_id=target_transfers.style_gmt_id
            where target_transfers.deleted_at is null
            and target_transfers.process_id=5
            and target_transfers.sales_order_id=?
            and target_transfers.style_gmt_id=?
            group by
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id,
            ords.qty,
            ords.ship_date,
            dyeing.plan_qty
            ",[$sales_order_id,$style_gmt_id]);

            $prodsewings=collect($results)
            ->map(function($prodsewings) use ($aopArr){
            $prodsewings->yet_plan_qty=number_format($prodsewings->order_qty-$prodsewings->plan_qty,0);
            $prodsewings->plan_qty=number_format($prodsewings->plan_qty,0);
            $prodsewings->order_qty=number_format($prodsewings->order_qty,0);
            $prodsewings->ship_date=date('d-M-Y',strtotime($prodsewings->ship_date));
            $prodsewings->date_from=date('d-M-Y',strtotime($prodsewings->date_from));
            $prodsewings->date_to=date('d-M-Y',strtotime($prodsewings->date_to));
            $prodsewings->desc=implode('<br/>',$aopArr[$prodsewings->sales_order_id][$prodsewings->style_gmt_id]);
            return $prodsewings;
            })->first();
            $tpl='';
            if($prodsewings){
            $tpl="<table border=1>";

            $tpl.="<tr>";
            $tpl.="<td width='100px'>Min Cuting Date</td>";
            $tpl.="<td width='100px'>". $prodsewings->date_from."</td>";
            $tpl.="<td rowspan='6'>". $prodsewings->desc."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Max Cuting Date</td>";
            $tpl.="<td>". $prodsewings->date_to."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Order Qty</td>";
            $tpl.="<td>". $prodsewings->order_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Target Done</td>";
            $tpl.="<td>". $prodsewings->plan_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Yet to Target</td>";
            $tpl.="<td>". $prodsewings->yet_plan_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Ship Date</td>";
            $tpl.="<td>".$prodsewings->ship_date."</td>";
            $tpl.="</tr>";

            $tpl.="</table>"; 
            }
            echo $tpl;
        }

        if( $process_id==5)
        {
            /*$results = \DB::select("
            select 
            target_transfers.sales_order_id,
            min(target_transfers.date_from) as date_from,
            max(target_transfers.date_to) as date_to,
            ords.qty as order_qty,
            ords.ship_date,
            cutting.plan_qty

            from
            target_transfers
            left join(
            select 
            sales_orders.id,
            sales_orders.ship_date,
            sum(sales_order_gmt_color_sizes.qty) as qty
            from
            sales_orders
            --join sales_orders on sales_orders.id=target_transfers.sales_order_id
            join sales_order_countries on sales_order_countries.sale_order_id=sales_orders.id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id
            where 
            --target_transfers.deleted_at is null
            sales_orders.deleted_at is null
            and sales_order_countries.deleted_at is null
            and sales_order_gmt_color_sizes.deleted_at is null
            group by 
            sales_orders.id,
            sales_orders.ship_date
            ) ords on ords.id=target_transfers.sales_order_id

            left join (
            select 
            target_transfers.sales_order_id,
            sum(target_transfers.qty) as plan_qty
            from
            target_transfers
            where target_transfers.process_id=5
            group by target_transfers.sales_order_id
            ) cutting on cutting.sales_order_id=target_transfers.sales_order_id

            where 
            target_transfers.deleted_at is null
            and target_transfers.process_id=8
            and target_transfers.sales_order_id=?
            group by
            target_transfers.sales_order_id,
            ords.qty,
            ords.ship_date,
            cutting.plan_qty
            ",[$sales_order_id]);*/

            $results = \DB::select("
            select 
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id,
            min(target_transfers.date_from) as date_from,
            max(target_transfers.date_to) as date_to,
            ords.qty as order_qty,
            ords.ship_date,
            cutting.plan_qty

            from
            target_transfers
            left join(
            select 
            sales_orders.id,
            style_gmts.id as style_gmt_id,
            sales_orders.ship_date,
            sum(sales_order_gmt_color_sizes.qty) as qty
            from
            sales_orders
            join sales_order_countries on sales_order_countries.sale_order_id=sales_orders.id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id
            join style_gmt_color_sizes on style_gmt_color_sizes.id=sales_order_gmt_color_sizes.style_gmt_color_size_id
            join style_gmts on style_gmts.id=style_gmt_color_sizes.style_gmt_id
            where 
            sales_orders.deleted_at is null
            and sales_order_countries.deleted_at is null
            and sales_order_gmt_color_sizes.deleted_at is null
            group by 
            sales_orders.id,
            style_gmts.id,
            sales_orders.ship_date
            ) ords on ords.id=target_transfers.sales_order_id and ords.style_gmt_id=target_transfers.style_gmt_id

            left join (
            select 
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id,
            sum(target_transfers.qty) as plan_qty
            from
            target_transfers
            where target_transfers.process_id=5
            group by target_transfers.sales_order_id,target_transfers.style_gmt_id
            ) cutting on cutting.sales_order_id=target_transfers.sales_order_id and cutting.style_gmt_id=target_transfers.style_gmt_id

            where 
            target_transfers.deleted_at is null
            and target_transfers.process_id=8
            and target_transfers.sales_order_id=?
            and target_transfers.style_gmt_id=?
            group by
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id,
            ords.qty,
            ords.ship_date,
            cutting.plan_qty
            ",[$sales_order_id,$style_gmt_id]);

            $prodsewings=collect($results)
            ->map(function($prodsewings){
            $prodsewings->yet_plan_qty=number_format($prodsewings->order_qty-$prodsewings->plan_qty,0);
            $prodsewings->plan_qty=number_format($prodsewings->plan_qty,0);
            $prodsewings->order_qty=number_format($prodsewings->order_qty,0);
            $prodsewings->ship_date=date('d-M-Y',strtotime($prodsewings->ship_date));
            $prodsewings->date_from=date('d-M-Y',strtotime($prodsewings->date_from));
            $prodsewings->date_to=date('d-M-Y',strtotime($prodsewings->date_to));
            return $prodsewings;
            })->first();
            $tpl='';
            if($prodsewings){
            $tpl="<table border=1>";

            $tpl.="<tr>";
            $tpl.="<td width='100px'>Min Sew Date</td>";
            $tpl.="<td width='100px'>". $prodsewings->date_from."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Max Sew Date</td>";
            $tpl.="<td>". $prodsewings->date_to."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Order Qty</td>";
            $tpl.="<td>". $prodsewings->order_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Target Done</td>";
            $tpl.="<td>". $prodsewings->plan_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Yet to Target</td>";
            $tpl.="<td>". $prodsewings->yet_plan_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Ship Date</td>";
            $tpl.="<td>".$prodsewings->ship_date."</td>";
            $tpl.="</tr>";

            $tpl.="</table>"; 
            }
            
            echo $tpl;
        }

        if( $process_id==6)
        {

            $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');

            $emb = \DB::select("
            select 
            sales_orders.id,
            embelishments.name,
            embelishment_types.name as type_name,
            style_embelishments.id as style_embelishment_id,
            style_embelishments.embelishment_size_id
            from
            target_transfers
            join sales_orders on sales_orders.id=target_transfers.sales_order_id
            join sales_order_countries on sales_order_countries.sale_order_id=sales_orders.id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id
            join budget_emb_cons on budget_emb_cons.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
            join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
            join style_gmts on style_gmts.id=style_embelishments.style_gmt_id
            join embelishments on embelishments.id=style_embelishments.embelishment_id 
            join embelishment_types on embelishment_types.id=style_embelishments.embelishment_type_id  
            join production_processes on production_processes.id=embelishments.production_process_id             
            where 
            production_processes.production_area_id=45
            and sales_orders.id=?
            and style_gmts.id=?
            and target_transfers.deleted_at is null
            and sales_orders.deleted_at is null
            and sales_order_countries.deleted_at is null
            and sales_order_gmt_color_sizes.deleted_at is null
            and budget_emb_cons.deleted_at is null
            and budget_embs.deleted_at is null
            and style_embelishments.deleted_at is null
            group by 
            sales_orders.id,
            embelishments.name,
            embelishment_types.name,
            style_embelishments.id,
            style_embelishments.embelishment_size_id
            order by style_embelishments.id
            ",[$sales_order_id,$style_gmt_id]);

            $prodembs=collect($emb);
            $embArr=[];
            foreach($prodembs as $prodemb)
            {
            $embArr[$prodemb->id][$prodemb->style_embelishment_id]=$prodemb->name.','.$prodemb->type_name.','.$embelishmentsize[$prodemb->embelishment_size_id];

            }

            $results = \DB::select("
            select 
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id,
            ords.qty as order_qty,
            ords.ship_date,
            min(target_transfers.date_from) as date_from,
            max(target_transfers.date_to) as date_to,
            srcp.plan_qty
            from
            target_transfers
            left join(
            select 
            sales_orders.id,
            style_gmts.id as style_gmt_id,
            sales_orders.ship_date,
            sum(budget_emb_cons.req_cons) as qty
            from
            sales_orders
            join sales_order_countries on sales_order_countries.sale_order_id=sales_orders.id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id
            join budget_emb_cons on budget_emb_cons.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
            join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
            join style_gmts on style_gmts.id=style_embelishments.style_gmt_id
            join embelishments on embelishments.id=style_embelishments.embelishment_id 
            join production_processes on production_processes.id=embelishments.production_process_id             
            where 
            production_processes.production_area_id=45
            and sales_orders.deleted_at is null
            and sales_order_countries.deleted_at is null
            and sales_order_gmt_color_sizes.deleted_at is null
            and budget_emb_cons.deleted_at is null
            and budget_embs.deleted_at is null
            and style_embelishments.deleted_at is null

            group by 
            sales_orders.id,
            style_gmts.id,
            sales_orders.ship_date

            ) ords on ords.id=target_transfers.sales_order_id and ords.style_gmt_id=target_transfers.style_gmt_id

            left join (
            select 
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id,
            sum(target_transfers.qty) as plan_qty
            from
            target_transfers
            where target_transfers.process_id=6
            group by target_transfers.sales_order_id,target_transfers.style_gmt_id
            ) srcp on srcp.sales_order_id=target_transfers.sales_order_id and srcp.style_gmt_id=target_transfers.style_gmt_id
            where target_transfers.deleted_at is null
            and target_transfers.process_id=8
            and target_transfers.sales_order_id=?
            and target_transfers.style_gmt_id=?
            group by
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id,
            ords.qty,
            ords.ship_date,
            srcp.plan_qty
            ",[$sales_order_id,$style_gmt_id]);

            $prodsewings=collect($results)
            ->map(function($prodsewings) use ($embArr) {
            $prodsewings->yet_plan_qty=number_format($prodsewings->order_qty-$prodsewings->plan_qty,0);
            $prodsewings->plan_qty=number_format($prodsewings->plan_qty,0);
            $prodsewings->order_qty=number_format($prodsewings->order_qty,0);
            $prodsewings->ship_date=date('d-M-Y',strtotime($prodsewings->ship_date));
            $prodsewings->date_from=date('d-M-Y',strtotime($prodsewings->date_from));
            $prodsewings->date_to=date('d-M-Y',strtotime($prodsewings->date_to));
            $prodsewings->desc=implode('<br/>',$embArr[$prodsewings->sales_order_id]);

            return $prodsewings;
            })->first();
            $tpl='';
            if($prodsewings){
            $tpl="<table border=1>";

            $tpl.="<tr>";
            $tpl.="<td width='100px'>Min Sew Date</td>";
            $tpl.="<td width='100px'>". $prodsewings->date_from."</td>";
            $tpl.="<td rowspan='6'>". $prodsewings->desc."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Max Sew Date</td>";
            $tpl.="<td>". $prodsewings->date_to."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Budget Qty</td>";
            $tpl.="<td>". $prodsewings->order_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Target Done</td>";
            $tpl.="<td>". $prodsewings->plan_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Yet to Target</td>";
            $tpl.="<td>". $prodsewings->yet_plan_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Ship Date</td>";
            $tpl.="<td>".$prodsewings->ship_date."</td>";
            $tpl.="</tr>";

            $tpl.="</table>";
            }

            
            echo $tpl;
        }

        if( $process_id==7)
        {

            $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');

            $emb = \DB::select("
            select 
            sales_orders.id,
            embelishments.name,
            embelishment_types.name as type_name,
            style_embelishments.id as style_embelishment_id,
            style_embelishments.embelishment_size_id
            from
            target_transfers
            join sales_orders on sales_orders.id=target_transfers.sales_order_id
            join sales_order_countries on sales_order_countries.sale_order_id=sales_orders.id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id
            join budget_emb_cons on budget_emb_cons.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
            join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
            join style_gmts on style_gmts.id=style_embelishments.style_gmt_id
            join embelishments on embelishments.id=style_embelishments.embelishment_id 
            join embelishment_types on embelishment_types.id=style_embelishments.embelishment_type_id  
            join production_processes on production_processes.id=embelishments.production_process_id             
            where 
            production_processes.production_area_id=50
            and sales_orders.id=?
            and style_gmts.id=?
            and target_transfers.deleted_at is null
            and sales_orders.deleted_at is null
            and sales_order_countries.deleted_at is null
            and sales_order_gmt_color_sizes.deleted_at is null
            and budget_emb_cons.deleted_at is null
            and budget_embs.deleted_at is null
            and style_embelishments.deleted_at is null
            group by 
            sales_orders.id,
            embelishments.name,
            embelishment_types.name,
            style_embelishments.id,
            style_embelishments.embelishment_size_id
            order by style_embelishments.id
            ",[$sales_order_id,$style_gmt_id]);

            $prodembs=collect($emb);
            $embArr=[];
            foreach($prodembs as $prodemb)
            {
            $embArr[$prodemb->id][$prodemb->style_embelishment_id]=$prodemb->name.','.$prodemb->type_name.','.$embelishmentsize[$prodemb->embelishment_size_id];

            }


            $results = \DB::select("
            select 
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id,
            ords.qty as order_qty,
            ords.ship_date,
            min(target_transfers.date_from) as date_from,
            max(target_transfers.date_to) as date_to,
            srcp.plan_qty
            from
            target_transfers
            left join(
            select 
            sales_orders.id,
            style_gmts.id as style_gmt_id,
            sales_orders.ship_date,
            sum(budget_emb_cons.req_cons) as qty
            from
            sales_orders
            join sales_order_countries on sales_order_countries.sale_order_id=sales_orders.id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id
            join budget_emb_cons on budget_emb_cons.sales_order_gmt_color_size_id=sales_order_gmt_color_sizes.id
            join budget_embs on budget_embs.id=budget_emb_cons.budget_emb_id
            join style_embelishments on style_embelishments.id=budget_embs.style_embelishment_id
            join style_gmts on style_gmts.id=style_embelishments.style_gmt_id
            join embelishments on embelishments.id=style_embelishments.embelishment_id 
            join production_processes on production_processes.id=embelishments.production_process_id             
            where 
            production_processes.production_area_id=50
            and sales_orders.deleted_at is null
            and sales_order_countries.deleted_at is null
            and sales_order_gmt_color_sizes.deleted_at is null
            and budget_emb_cons.deleted_at is null
            and budget_embs.deleted_at is null
            and style_embelishments.deleted_at is null

            group by 
            sales_orders.id,
            style_gmts.id,
            sales_orders.ship_date

            ) ords on ords.id=target_transfers.sales_order_id and ords.style_gmt_id=target_transfers.style_gmt_id

            left join (
            select 
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id,
            sum(target_transfers.qty) as plan_qty
            from
            target_transfers
            where target_transfers.process_id=7
            group by target_transfers.sales_order_id,target_transfers.style_gmt_id
            ) srcp on srcp.sales_order_id=target_transfers.sales_order_id and srcp.style_gmt_id=target_transfers.style_gmt_id
            where target_transfers.deleted_at is null
            and target_transfers.process_id=8
            and target_transfers.sales_order_id=?
            and target_transfers.style_gmt_id=?
            group by
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id,
            ords.qty,
            ords.ship_date,
            srcp.plan_qty
            ",[$sales_order_id,$style_gmt_id]);

            $prodsewings=collect($results)
            ->map(function($prodsewings) use( $embArr){
            $prodsewings->yet_plan_qty=number_format($prodsewings->order_qty-$prodsewings->plan_qty,0);
            $prodsewings->plan_qty=number_format($prodsewings->plan_qty,0);
            $prodsewings->order_qty=number_format($prodsewings->order_qty,0);
            $prodsewings->ship_date=date('d-M-Y',strtotime($prodsewings->ship_date));
            $prodsewings->date_from=date('d-M-Y',strtotime($prodsewings->date_from));
            $prodsewings->date_to=date('d-M-Y',strtotime($prodsewings->date_to));
            $prodsewings->desc=implode('<br/>',$embArr[$prodsewings->sales_order_id]);
            return $prodsewings;
            })->first();
            $tpl='';
            if($prodsewings){
            $tpl="<table border=1>";

            $tpl.="<tr>";
            $tpl.="<td width='100px'>Min Sew Date</td>";
            $tpl.="<td width='100px'>". $prodsewings->date_from."</td>";
            $tpl.="<td rowspan='6'>". $prodsewings->desc."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Max Sew Date</td>";
            $tpl.="<td>". $prodsewings->date_to."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Budget Qty</td>";
            $tpl.="<td>". $prodsewings->order_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Target Done</td>";
            $tpl.="<td>". $prodsewings->plan_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Yet to Target</td>";
            $tpl.="<td>". $prodsewings->yet_plan_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Ship Date</td>";
            $tpl.="<td>".$prodsewings->ship_date."</td>";
            $tpl.="</tr>";

            $tpl.="</table>";  
            }

            
            echo $tpl;
        }
    
        if( $process_id==8)
        {
            /*$results = \DB::select("
            select 
            target_transfers.sales_order_id,
            sum(target_transfers.qty) as plan_qty,
            ords.qty as order_qty,
            ords.ship_date

            from
            target_transfers
            join(
            select 
            sales_orders.id,
            sales_orders.ship_date,
            sum(sales_order_gmt_color_sizes.qty) as qty
            from
            sales_orders
            --join sales_orders on sales_orders.id=target_transfers.sales_order_id
            join sales_order_countries on sales_order_countries.sale_order_id=sales_orders.id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id
            where sales_orders.deleted_at is null
            --and target_transfers.deleted_at is null
            and sales_order_countries.deleted_at is null
            and sales_order_gmt_color_sizes.deleted_at is null
            group by 
            sales_orders.id,
            sales_orders.ship_date

            ) ords on ords.id=target_transfers.sales_order_id
            where target_transfers.deleted_at is null
            and target_transfers.process_id=8
            and target_transfers.sales_order_id=?
            group by
            target_transfers.sales_order_id,
            ords.qty,
            ords.ship_date
            ",[$sales_order_id]);*/

            $results = \DB::select("
            select 
            sales_orders.id as sales_order_id,
            style_gmts.id as style_gmt_id,
            ords.plan_qty,
            sum(sales_order_gmt_color_sizes.qty) as order_qty,
            sales_orders.ship_date

            from
            sales_orders
            join sales_order_countries on sales_order_countries.sale_order_id=sales_orders.id
            join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id=sales_order_countries.id
            join style_gmt_color_sizes on style_gmt_color_sizes.id=sales_order_gmt_color_sizes.style_gmt_color_size_id
            join style_gmts on style_gmts.id=style_gmt_color_sizes.style_gmt_id
            left join(
            select 
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id,
            sum(target_transfers.qty) as plan_qty

            from
            target_transfers
            where target_transfers.deleted_at is null
            and target_transfers.process_id=8

            group by
            target_transfers.sales_order_id,
            target_transfers.style_gmt_id

            ) ords on ords.sales_order_id=sales_orders.id and ords.style_gmt_id=style_gmts.id
            where sales_orders.deleted_at is null
            and sales_order_countries.deleted_at is null
            and sales_order_gmt_color_sizes.deleted_at is null
            and sales_orders.id=?
            and style_gmts.id=?
            group by 
            sales_orders.id,
            style_gmts.id,
            sales_orders.ship_date,
            ords.plan_qty
            ",[$sales_order_id,$style_gmt_id]);


            $prodsewings=collect($results)
            ->map(function($prodsewings){
            $prodsewings->yet_plan_qty=number_format($prodsewings->order_qty-$prodsewings->plan_qty,0);
            $prodsewings->plan_qty=number_format($prodsewings->plan_qty,0);
            $prodsewings->order_qty=number_format($prodsewings->order_qty,0);
            $prodsewings->ship_date=date('d-M-Y',strtotime($prodsewings->ship_date));
            return $prodsewings;
            })->first();
            $tpl='';
            if($prodsewings){
            $tpl="<table border=1>";

            $tpl.="<tr>";
            $tpl.="<td width='100px'>Order Qty</td>";
            $tpl.="<td width='100px'>". $prodsewings->order_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Target Done</td>";
            $tpl.="<td>". $prodsewings->plan_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Yet to Target</td>";
            $tpl.="<td>". $prodsewings->yet_plan_qty."</td>";
            $tpl.="</tr>";

            $tpl.="<tr>";
            $tpl.="<td>Ship Date</td>";
            $tpl.="<td>".$prodsewings->ship_date."</td>";
            $tpl.="</tr>";
            $tpl.="</table>"; 
            }

            
            echo $tpl;
        }
    }
}