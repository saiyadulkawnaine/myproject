<?php
namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemRepository;
use App\Repositories\Contracts\Production\Kniting\ProdKnitRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CompanyRepository;

class ProdFabricCapacityAchievementController extends Controller
{
	private $no_of_days;
	private $exch_rate;
	
	private $salesorder;
	private $assetacquisition;
	private $plknititem;
	private $prodknit;
	private $supplier;
	private $company;
	public function __construct(
		SalesOrderRepository $salesorder,
		AssetAcquisitionRepository $assetacquisition,
		PlKnitItemRepository $plknititem,
		ProdKnitRepository $prodknit,
		SupplierRepository $supplier,
		CompanyRepository $company
	)
    {
		$this->no_of_days                = 26;
		$this->exch_rate                 = 82;
		$this->salesorder                = $salesorder;
		$this->assetacquisition          = $assetacquisition;
		$this->plknititem                = $plknititem;
		$this->prodknit                  = $prodknit;
		$this->supplier                  = $supplier;
		$this->company                   = $company;
		$this->middleware('auth');
		//$this->middleware('permission:view.prodgmtcapacityachievereports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
    	$date_to=date('Y-m-d');
    	return Template::loadView('Report.FabricCapacityAchivment',['date_to'=>$date_to]);
    }
    

    public function formatOne(){
    	$date_from=request('date_to',0);
    	$date_to=request('date_to',0);

    	$machineCapacity=
    	$this->assetacquisition
    	->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.asset_acquisition_id','=','asset_acquisitions.id');
        })
        ->selectRaw(
           'asset_acquisitions.id,
            sum(asset_acquisitions.prod_capacity) as prod_capacity
           '
        )
        ->groupBy(['asset_acquisitions.id'])
        ->get();

        $plknititem=$this->plknititem
        ->selectRaw(
           'pl_knits.company_id,
            sum(pl_knit_item_qties.qty) as qty
           '
        )
        
        ->join('pl_knits', function($join)  {
            $join->on('pl_knits.id', '=', 'pl_knit_items.pl_knit_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'pl_knits.company_id');
        })
        ->join('pl_knit_item_qties', function($join)  {
            $join->on('pl_knit_item_qties.pl_knit_item_id', '=', 'pl_knit_items.id');
        })
        ->when($date_from, function ($q) use($date_from){
        return $q->where('pl_knit_item_qties.pl_date', '>=',$date_from);
        })
        ->when($date_to, function ($q) use($date_to) {
        return $q->where('pl_knit_item_qties.pl_date', '<=',$date_to);
        })
        ->where([['companies.knitting_capacity_qty','>',0]])
        ->groupBy(['pl_knits.company_id'])
        ->get()
        ->first();
        $todayTerget=0;
        if($plknititem){
	        $todayTerget=$plknititem->qty;
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
        ->when($date_from, function ($q) use($date_from){
        return $q->where('prod_knits.prod_date', '>=',$date_from);
        })
        ->when($date_to, function ($q) use($date_to) {
        return $q->where('prod_knits.prod_date', '<=',$date_to);
        })
        ->where([['prod_knits.basis_id','=',1]])
        ->get()
        ->first();
        $todayknit=0;
        if($prodknit){
	        $todayknit=$prodknit->roll_weight;
        }


        $dailyCapacity=$machineCapacity->sum('prod_capacity');
        $monthCapacity=$dailyCapacity*$this->no_of_days;
        $monthTarget=$this->monthTarget();
        $monthAchivement=$this->monthAchivement();
    	return Template::loadView('Report.FabricCapacityAchivmentColorSizeMatrix',['monthCapacity'=>$monthCapacity,'monthTarget'=>$monthTarget,'dailyCapacity'=>$dailyCapacity,'todayTerget'=>$todayTerget,'todayknit'=>$todayknit,'monthAchivement'=>$monthAchivement]);
    }

    public function monthTarget() {
    	$str2=request('date_to',0);
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
    	$date_to = date('Y-m-d', strtotime($str2));
    	$last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
	    $knit = collect(\DB::select("
			select      
			sum(budget_fabric_cons.fin_fab) as fin_fab,
			sum(budget_fabric_cons.grey_fab) as grey_fab
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
			join budget_fabrics on budget_fabrics.id = budget_fabric_cons.budget_fabric_id
			join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
			where sales_orders.ship_date>='".$start_date."' and 
			sales_orders.ship_date<='".$end_date."' and style_fabrications.material_source_id in(10,15) and budget_fabric_cons.deleted_at is null and sales_order_gmt_color_sizes.deleted_at is null
			"
	    ))->first();
		$aop = collect(\DB::select("
			select      
			sum(budget_fabric_prod_cons.bom_qty) as bom_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
			join jobs on jobs.id = sales_orders.job_id
			join styles on styles.id = jobs.style_id
			join budgets on budgets.style_id=styles.id
			join budget_fabric_prods on budget_fabric_prods.budget_id=budgets.id
			join budget_fabric_prod_cons on budget_fabric_prod_cons.budget_fabric_prod_id=budget_fabric_prods.id
			and budget_fabric_prod_cons.sales_order_id=sales_orders.id 
			left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
			where sales_orders.ship_date>='".$start_date."' and 
			sales_orders.ship_date<='".$end_date."' and
			production_processes.production_area_id =25"
	    ))->first();
	   return $monthTarget=['yarn'=>$knit->grey_fab,'knit'=>$knit->grey_fab,'dyeing'=>$knit->grey_fab,'finish'=>$knit->fin_fab,'aop'=>$aop->bom_qty];

    }
    public function monthAchivement() {
    	$str2=request('date_to',0);
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
    	$date_to = date('Y-m-d', strtotime($str2));
    	$last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));

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
        ->when($start_date, function ($q) use($start_date){
        return $q->where('prod_knits.prod_date', '>=',$start_date);
        })
        ->when($end_date, function ($q) use($end_date) {
        return $q->where('prod_knits.prod_date', '<=',$end_date);
        })
        //->where([['prod_knits.basis_id','=',1]])
        ->get()
        ->first();
        return $monthAchivement=['knit'=>$prodknit->roll_weight];
        
    }

    public function reportData() {
    	$str2=request('date_to',0);
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
    	$date_to = date('Y-m-d', strtotime($str2));
    	$last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
	    
    }

    public function fabricmonthTarget()
    {
    	$str2=request('date_to',0);
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
    	$date_to = date('Y-m-d', strtotime($str2));
    	$last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));

        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

        $fabricDescription = collect(\DB::select("
			select
			autoyarns.id,
			constructions.name as construction,
			compositions.name,
			autoyarnratios.ratio
			FROM autoyarns
			join autoyarnratios on autoyarnratios.autoyarn_id = autoyarns.id
			join compositions on compositions.id = autoyarnratios.composition_id
			join constructions on constructions.id = autoyarns.construction_id
			"
	    ));

	    $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($fabricDescription as $row){
            $fabricDescriptionArr[$row->id]=$row->construction;
            $fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
        }
        
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
            $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }

	    $knit = collect(\DB::select("
			select
			companies.code as company_name,
			produced_companies.code as produced_company_name,
			styles.style_ref,
			buyers.name as buyer_name,
			style_fabrications.autoyarn_id,
			style_fabrications.fabric_look_id,
            style_fabrications.fabric_shape_id,
			sales_orders.id, 
			sales_orders.sale_order_no, 
			sales_orders.ship_date,
			budget_fabrics.gsm_weight,
			budget_fabric_cons.dia,
			colors.name as fabric_color,
			salesorderqties.qty, 
			users.name as team_member_name,    
			sum(budget_fabric_cons.fin_fab) as fin_fab,
			sum(budget_fabric_cons.grey_fab) as grey_fab
			FROM sales_orders
			join jobs on jobs.id = sales_orders.job_id
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
			join budget_fabrics on budget_fabrics.id = budget_fabric_cons.budget_fabric_id
			join style_fabrications on style_fabrications.id = budget_fabrics.style_fabrication_id
			join styles on styles.id = style_fabrications.style_id
			join companies on companies.id = jobs.company_id
			join companies  produced_companies on produced_companies.id = sales_orders.produced_company_id
			join buyers on buyers.id = styles.buyer_id
			join autoyarns on autoyarns.id=style_fabrications.autoyarn_id
			join colors on colors.id=budget_fabric_cons.fabric_color
			left join teammembers on teammembers.id=styles.factory_merchant_id
			left join users on users.id=teammembers.user_id

			join(
			select
			sales_orders.id, 
			sum(sales_order_gmt_color_sizes.qty) as qty,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			group by  sales_orders.id
			) salesorderqties on salesorderqties.id=sales_orders.id

			where sales_orders.ship_date>='".$start_date."' and 
			sales_orders.ship_date<='".$end_date."' and style_fabrications.material_source_id in(10,15) and budget_fabric_cons.deleted_at is null and sales_order_gmt_color_sizes.deleted_at is null
			group by 
			companies.code,
			produced_companies.id,
			produced_companies.code,
			styles.style_ref,
			buyers.name,
			style_fabrications.autoyarn_id,
			style_fabrications.fabric_look_id,
            style_fabrications.fabric_shape_id,
			sales_orders.id,
			sales_orders.sale_order_no,
			sales_orders.ship_date,
			budget_fabrics.gsm_weight,
			budget_fabric_cons.dia,
			colors.name,
			salesorderqties.qty,
			users.name
			order by produced_companies.id
			"
	    ))->map(function($knit) use($desDropdown,$fabriclooks,$fabricshape){
	    	$knit->fabrication=$desDropdown[$knit->autoyarn_id];
	    	$knit->fabriclooks=$fabriclooks[$knit->fabric_look_id];
	    	$knit->fabricshape=$fabricshape[$knit->fabric_shape_id];
	    	$knit->qty=number_format($knit->qty,0,'.',',');
	    	$knit->fin_qty=$knit->fin_fab;
	    	$knit->grey_qty=$knit->grey_fab;
	    	$knit->fin_fab=number_format($knit->fin_fab,2,'.',',');
	    	$knit->grey_fab=number_format($knit->grey_fab,2,'.',',');
	    	$knit->ship_date=date('d-M-Y',strtotime($knit->ship_date));
	    	return $knit;
	    })->filter(function ($knit){
	    	if($knit->grey_qty || $knit->fin_qty){
	    		return $knit;
	    	}
	    })->values()->groupBy('produced_company_name');
	    $datas=array();
	    foreach($knit as $company_name=>$value){
	    	$groups = collect(['team_member_name'=>$company_name,'group_name'=>1,'fin_fab'=>'','grey_fab'=>'']);
	    	array_push($datas,$groups);
	    	$grey_fab=0;
	    	$fin_fab=0;
	    	foreach($value as $row){
	    		$grey_fab+=$row->grey_qty;
	    	    $fin_fab+=$row->fin_qty;
	    		array_push($datas,$row);
	    	}
	    	$subTot = collect(['company_name'=>'Sub Total','grey_fab'=>number_format($grey_fab,'0','.',','),'fin_fab'=>number_format($fin_fab,'0','.',',')]);
			array_push($datas,$subTot);
	    }
	    echo json_encode($datas);
    }


    public function getAopMonthTgt()
    {
    	$str2=request('date_to',0);
    	$date_to = date('Y-m-d', strtotime($str2));
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
        $last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
    	$company_id=request('company_id',0);
    	$data=$this->salesorder
    	->selectRaw('
    		styles.style_ref,
    		styles.flie_src,
    		buyers.code as buyer_code,
    		bcompanies.name as company_id,
    		companies.name as pcompany,
    		users.name as dl_marchent,
    		sales_orders.sale_order_no,
    		sales_orders.ship_date,
			saleorders.rate as order_rate,
			saleorders.qty as order_qty,
			saleorders.plan_cut_qty as order_plan_cut_qty,
			saleorders.amount as order_amount,
			sum(budget_fabric_prod_cons.bom_qty) as req_qty
    		')
    	->join('sales_order_countries', function($join) {
			$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        
        ->join('budgets', function($join)  {
            $join->on('budgets.job_id', '=', 'jobs.id');
        })
        ->join('budget_fabric_prods', function($join)  {
            $join->on('budget_fabric_prods.budget_id', '=', 'budgets.id');
        })
        ->join('budget_fabric_prod_cons', function($join)  {
            $join->on('budget_fabric_prod_cons.budget_fabric_prod_id', '=', 'budget_fabric_prods.id');
            $join->on('budget_fabric_prod_cons.sales_order_id', '=', 'sales_orders.id');
        })
        ->join('production_processes', function($join)  {
            $join->on('production_processes.id', '=', 'budget_fabric_prods.production_process_id');
        })
		
		->leftJoin('companies', function($join)  {
			$join->on('companies.id', '=', 'sales_orders.produced_company_id');
		})
		
        ->leftJoin('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->leftJoin('buyers', function($join)  {
        $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->leftJoin('companies as bcompanies', function($join)  {
			$join->on('bcompanies.id', '=', 'jobs.company_id');
		})
		->leftJoin('teams', function($join)  {
			$join->on('styles.team_id', '=', 'teams.id');
		})
		->leftJoin('teammembers', function($join)  {
			$join->on('styles.factory_merchant_id', '=', 'teammembers.id');
		})
		->leftJoin('users', function($join)  {
			$join->on('users.id', '=', 'teammembers.user_id');
		})
		->leftJoin(\DB::raw("(SELECT 
			sales_orders.id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			avg(sales_order_gmt_color_sizes.rate) as rate,
			sum(sales_order_gmt_color_sizes.amount) as amount,
			sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
			FROM sales_orders
			join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
			join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_country_id = sales_order_countries.id
			group by sales_orders.id
			) saleorders"), "saleorders.id", "=", "sales_orders.id")
		->when($start_date, function ($q) use($start_date){
		return $q->where('sales_orders.ship_date', '>=',$start_date);
		})
		->when($end_date, function ($q) use($end_date){
		return $q->where('sales_orders.ship_date', '<=',$end_date);
		})
		->where([['sales_orders.produced_company_id','=',$company_id]])
		->where([['production_processes.production_area_id','=',25]])
		->groupBy([
			'styles.style_ref',
			'styles.flie_src',
			'buyers.code',
			'bcompanies.name',
    		'companies.name',
    		'users.name',
    		'sales_orders.sale_order_no',
    		'sales_orders.id',
    		'sales_orders.ship_date',
    		'saleorders.rate',
    		'saleorders.qty',
    		'saleorders.plan_cut_qty',
    		'saleorders.amount',
		])
		->get()
		->map(function($data){
			 $data->ship_date=date('d-M-Y',strtotime($data->ship_date));
			 $data->order_amount=$data->order_amount;
			 $data->order_qty=number_format($data->order_qty,0);
			 $data->order_plan_cut_qty=number_format($data->order_plan_cut_qty,0);
			 $data->order_rate=number_format($data->order_rate,2);
			 $data->order_amount=number_format($data->order_amount,2);
			 $data->req_qty=number_format($data->req_qty,0);
            return $data;
        });
        echo json_encode($data);
	}
	

	//Row 8 Today Achievement 	
	public function todayAchiveRcvYarn(){
		$str2=request('date_to',0);
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
    	$date_to = date('Y-m-d', strtotime($str2));
    	$last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
		return Template::loadView('Report.FabricCapacityAchievePopUp.TodayAchieveRcvYarnMatrix');
	}
	public function todayAchiveKnitYarnIssue(){
		$str2=request('date_to',0);
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
    	$date_to = date('Y-m-d', strtotime($str2));
    	$last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
		return Template::loadView('Report.FabricCapacityAchievePopUp.TodayAchieveKnitYarnIssueMatrix');
	}
	public function todayAchiveDyeing(){
		$str2=request('date_to',0);
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
    	$date_to = date('Y-m-d', strtotime($str2));
    	$last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
		return Template::loadView('Report.FabricCapacityAchievePopUp.TodayAchieveDyeingMatrix');
	}
	public function todayAchiveAop(){
		$str2=request('date_to',0);
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
    	$date_to = date('Y-m-d', strtotime($str2));
    	$last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
		return Template::loadView('Report.FabricCapacityAchievePopUp.TodayAchieveAopMatrix');
	}
	
	public function knitTodayAchive(){
		$str2=request('capacity_date_to',0);
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
    	$date_to = date('Y-m-d', strtotime($str2));
    	$last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));

    	$supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-',0);

    	
    	$shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');

    	 $fabricDescription = collect(\DB::select("
			select
			autoyarns.id,
			constructions.name as construction,
			compositions.name,
			autoyarnratios.ratio
			FROM autoyarns
			join autoyarnratios on autoyarnratios.autoyarn_id = autoyarns.id
			join compositions on compositions.id = autoyarnratios.composition_id
			join constructions on constructions.id = autoyarns.construction_id
			"
	    ));

	    $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($fabricDescription as $row){
            $fabricDescriptionArr[$row->id]=$row->construction;
            $fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
        }
        
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
            $desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
        }

    	

		/*$results = \DB::select('
		select 
		    prod_knits.supplier_id,
		    suppliers.company_id,
		    suppliers.name as supplier_name,
		    jobs.company_id as b_company_id,
		    sales_orders.produced_company_id,
		    companies.name as b_company_name,
		    produced_companies.name as p_company_name,
		    sales_orders.id,
		    sales_orders.sale_order_no,
		    styles.style_ref,
		    styles.flie_src,
		    buyers.name as  buyer_name,
		    asset_quantity_costs.id as asset_quantity_cost_id,
		    asset_quantity_costs.custom_no,
		    asset_technical_features.dia_width as machine_dia,
		    asset_technical_features.gauge as machine_gg,
		    employee_h_rs.name as operator_name,
		    asset_acquisitions.prod_capacity,
		    so_knits.sales_order_no,
		    so_knit_items.gmt_buyer,
		    so_knit_items.gmt_style_ref,
		    so_knit_items.gmt_sale_order_no,
		    prod_knit_items.dia,
		    prod_knit_items.gsm_weight,
		    prod_knit_items.stitch_length,
		    pl_knit_items.colorrange_id,
		    sum(prod_knit_item_rolls.roll_weight) as roll_weight
			from prod_knits 
				join prod_knit_items on prod_knits.id=prod_knit_items.prod_knit_id
				join prod_knit_item_rolls on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id
				join suppliers on suppliers.id=prod_knits.supplier_id
				join asset_quantity_costs on asset_quantity_costs.id=prod_knit_items.asset_quantity_cost_id
				join asset_technical_features on asset_technical_features.asset_acquisition_id=asset_quantity_costs.asset_acquisition_id
				join asset_acquisitions on asset_acquisitions.id=asset_quantity_costs.asset_acquisition_id
				left join employee_h_rs on employee_h_rs.id=prod_knit_items.operator_id

				join pl_knit_items on pl_knit_items.id=prod_knit_items.pl_knit_item_id
				join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
				join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
				join so_knits on so_knits.id=so_knit_refs.so_knit_id
				left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
				left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
				left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
				left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
				and po_knit_service_items.deleted_at is null
				left join sales_orders on sales_orders.id=po_knit_service_item_qties.sales_order_id
				left join jobs on jobs.id=sales_orders.job_id
				left join styles on styles.id=jobs.style_id
				left join buyers on buyers.id=styles.buyer_id
				left join companies on companies.id=jobs.company_id
				left join companies produced_companies on produced_companies.id=sales_orders.produced_company_id
				left join budget_fabric_prods on budget_fabric_prods.id=po_knit_service_items.budget_fabric_prod_id
				left join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
				left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
				where prod_knits.prod_date=?
			group by 
				prod_knits.supplier_id,
				suppliers.company_id,
				suppliers.name,
				jobs.company_id,
				sales_orders.produced_company_id,
				companies.name,
				produced_companies.name,
				sales_orders.id,
				sales_orders.sale_order_no,
				styles.style_ref,
				styles.flie_src,
				buyers.name,
				asset_quantity_costs.id,
				asset_quantity_costs.custom_no,
				asset_technical_features.dia_width,
				asset_technical_features.gauge,
				employee_h_rs.name,
				asset_acquisitions.prod_capacity,
				so_knits.sales_order_no,
				so_knit_items.gmt_buyer,
				so_knit_items.gmt_style_ref,
				so_knit_items.gmt_sale_order_no,
				prod_knit_items.dia,
				prod_knit_items.gsm_weight,
				prod_knit_items.stitch_length,
				pl_knit_items.colorrange_id
			order by 
		    suppliers.company_id,
			sales_orders.id',[$date_to]);*/
			


	$results = \DB::select('
		select 
		m.id,
		m.supplier_id,
		suppliers.company_id,
		m.shift_id,
		m.dia,
		m.gsm_weight,
		m.stitch_length,
		m.colorrange_id,
		m.sales_order_id,
		m.gmt_buyer,
		m.gmt_style_ref,
		m.gmt_sale_order_no,
		jobs.company_id as b_company_id,
		sales_orders.produced_company_id,
		companies.code as b_company_name,
		produced_companies.code as p_company_name,
		sales_orders.id as sales_order_id,
		sales_orders.sale_order_no,
		styles.style_ref,
		styles.flie_src,
		buyers.name as  buyer_name,
		asset_quantity_costs.id as asset_quantity_cost_id,
		asset_quantity_costs.custom_no,
		asset_technical_features.dia_width as machine_dia,
		asset_technical_features.gauge as machine_gg,
		asset_acquisitions.prod_capacity,
		employee_h_rs.name as operator_name,
		CASE 
		WHEN m.autoyarn_id is not null and m.autoyarn_id >0 
		THEN
		m.autoyarn_id
		ELSE 
		style_fabrications.autoyarn_id
		END as autoyarn_id,
		colorranges.name as colorrange_name,
		sum(m.roll_weight) as  roll_weight
		from
		(
			select 
			prod_knits.id,
			prod_knits.supplier_id,
			prod_knits.shift_id,
			prod_knit_items.id as prod_knit_item_id,
			prod_knit_items.pl_knit_item_id,
			prod_knit_items.po_knit_service_item_qty_id,
			prod_knit_items.dia,
			prod_knit_items.gsm_weight,
			prod_knit_items.stitch_length,
			prod_knit_items.asset_quantity_cost_id,
			prod_knit_items.operator_id,
			CASE
			WHEN prod_knit_items.pl_knit_item_id is not null and prod_knit_items.pl_knit_item_id >0
			THEN plknititems.colorrange_id
			ELSE poknititems.colorrange_id
			END AS colorrange_id,
			CASE
			WHEN prod_knit_items.pl_knit_item_id is not null and prod_knit_items.pl_knit_item_id >0
			THEN plknititems.sales_order_id
			ELSE poknititems.sales_order_id
			END AS sales_order_id,
			CASE
			WHEN prod_knit_items.pl_knit_item_id is not null and prod_knit_items.pl_knit_item_id >0
			THEN plknititems.budget_fabric_prod_id
			ELSE poknititems.budget_fabric_prod_id
			END AS budget_fabric_prod_id,
			plknititems.knit_sales_order_no,
			plknititems.autoyarn_id,
			plknititems.gmt_buyer,
			plknititems.gmt_style_ref,
			plknititems.gmt_sale_order_no,
			sum(prod_knit_item_rolls.roll_weight) as roll_weight
			from prod_knits
			join prod_knit_items on prod_knits.id=prod_knit_items.prod_knit_id
			join prod_knit_item_rolls on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id

			left join (
				select 
				pl_knit_items.id,
				pl_knit_items.colorrange_id,
				po_knit_service_item_qties.sales_order_id,
				po_knit_service_items.budget_fabric_prod_id, 
				so_knits.sales_order_no as knit_sales_order_no,
				so_knit_items.autoyarn_id,
				so_knit_items.gmt_buyer,
				so_knit_items.gmt_style_ref,
				so_knit_items.gmt_sale_order_no
				from 
				pl_knit_items
				join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
				join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
				join so_knits on so_knits.id=so_knit_refs.so_knit_id
				left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
				left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
				left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
				left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
				and po_knit_service_items.deleted_at is null
			) plknititems on plknititems.id=prod_knit_items.pl_knit_item_id

			left join (
				select 
				po_knit_service_item_qties.id,
				po_knit_service_item_qties.colorrange_id,
				po_knit_service_item_qties.sales_order_id,
				po_knit_service_items.budget_fabric_prod_id 
				from 
				po_knit_service_item_qties
				left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
				and po_knit_service_items.deleted_at is null
			) poknititems on poknititems.id=prod_knit_items.po_knit_service_item_qty_id
			where prod_knits.prod_date=?
			group by
			prod_knits.id,
			prod_knits.supplier_id,
			prod_knits.shift_id,
			prod_knit_items.id,
			prod_knit_items.pl_knit_item_id,
			prod_knit_items.po_knit_service_item_qty_id,
			prod_knit_items.dia,
			prod_knit_items.gsm_weight,
			prod_knit_items.stitch_length,
			prod_knit_items.asset_quantity_cost_id,
			prod_knit_items.operator_id,
			plknititems.colorrange_id,
			poknititems.colorrange_id,
			plknititems.sales_order_id,
			poknititems.sales_order_id,
			plknititems.budget_fabric_prod_id,
			poknititems.budget_fabric_prod_id,
			plknititems.knit_sales_order_no,
			plknititems.autoyarn_id,
			plknititems.gmt_buyer,
			plknititems.gmt_style_ref,
			plknititems.gmt_sale_order_no
		) m  

		left join suppliers on suppliers.id=m.supplier_id
		left join sales_orders on sales_orders.id=m.sales_order_id
		left join jobs on jobs.id=sales_orders.job_id
		left join styles on styles.id=jobs.style_id
		left join buyers on buyers.id=styles.buyer_id
		left join companies on companies.id=jobs.company_id
		left join companies produced_companies on produced_companies.id=sales_orders.produced_company_id
		left join budget_fabric_prods on budget_fabric_prods.id=m.budget_fabric_prod_id
		left join budget_fabrics on budget_fabrics.id=budget_fabric_prods.budget_fabric_id
		left join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id 

		left join asset_quantity_costs on asset_quantity_costs.id=m.asset_quantity_cost_id
		left join asset_technical_features on asset_technical_features.asset_acquisition_id=asset_quantity_costs.asset_acquisition_id
		left join asset_acquisitions on asset_acquisitions.id=asset_quantity_costs.asset_acquisition_id
		left join employee_h_rs on employee_h_rs.id=m.operator_id
		left join colorranges on colorranges.id=m.colorrange_id

		group by 
			m.id,
			m.supplier_id,
			suppliers.company_id,
			m.shift_id,
			m.dia,
			m.gsm_weight,
			m.stitch_length,
			m.colorrange_id,
			m.sales_order_id,
			m.gmt_buyer,
			m.gmt_style_ref,
			m.gmt_sale_order_no,
			jobs.company_id,
			sales_orders.produced_company_id,
			companies.code,
			produced_companies.code,
			sales_orders.id,
			sales_orders.sale_order_no,
			styles.style_ref,
			styles.flie_src,
			buyers.name,
			asset_quantity_costs.id,
			asset_quantity_costs.custom_no,
			asset_technical_features.dia_width,
			asset_technical_features.gauge,
			asset_acquisitions.prod_capacity,
			employee_h_rs.name,
			m.autoyarn_id,
			style_fabrications.autoyarn_id,
			colorranges.name
		order by
			suppliers.company_id,
			sales_orders.id',[$date_to]);
		$data=collect($results)->groupBy(['supplier_id','shift_id']);



		return Template::loadView('Report.FabricCapacityAchievePopUp.TodayAchieveKnittingMatrix',['data'=>$data,'supplier'=>$supplier,'shiftname'=>$shiftname,'desDropdown'=>$desDropdown]);
	}
	//=======Row 17 Month Achievement
	public function MonthAchieveRcvYarn(){
		$str2=request('date_to',0);
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
    	$date_to = date('Y-m-d', strtotime($str2));
    	$last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));

        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
		$fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
		return Template::loadView('Report.FabricCapacityAchievePopUp.MonthAchieveRcvYarnMatrix');
	}
	public function MonthAchieveKnitYarnIssue(){
		$str2=request('date_to',0);
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
    	$date_to = date('Y-m-d', strtotime($str2));
    	$last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));

        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
		$fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
		return Template::loadView('Report.FabricCapacityAchievePopUp.MonthAchieveKnitYarnIssueMatrix');
	}
	public function MonthAchieveKnitting(){
		$str2=request('capacity_date_to',0);
    	$start_date=date('Y-m', strtotime($str2))."-01";
    	$end_date=date("Y-m-t", strtotime($str2));
    	$date_to = date('Y-m-d', strtotime($str2));
    	$last_month=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $start_date) ) ));
    	$company=array_prepend(array_pluck($this->company->get(),'name','id'),'Sub-Contract',0);

    	//\DB::enableQueryLog();

    	$results = \DB::select('
		
select
CASE 
WHEN  inhousedata.company_id is not null
THEN inhousedata.company_id
ELSE subcondata.company_id
END as company_id,
CASE 
WHEN  inhousedata.sales_order_id is not null
THEN inhousedata.sales_order_id
ELSE subcondata.sales_order_id
END as sales_order_id,
CASE 
WHEN  inhousedata.b_company_id is not null
THEN inhousedata.b_company_id
ELSE subcondata.b_company_id
END as b_company_id,
CASE 
WHEN  inhousedata.produced_company_id is not null
THEN inhousedata.produced_company_id
ELSE subcondata.produced_company_id
END as produced_company_id,

CASE 
WHEN  inhousedata.b_company_name is not null
THEN inhousedata.b_company_name
ELSE subcondata.b_company_name
END as b_company_name,
CASE 
WHEN  inhousedata.sale_order_no is not null
THEN inhousedata.sale_order_no
ELSE subcondata.sale_order_no
END as sale_order_no,

CASE 
WHEN  inhousedata.style_ref is not null
THEN inhousedata.style_ref
ELSE subcondata.style_ref
END as style_ref,

CASE 
WHEN  inhousedata.flie_src is not null
THEN inhousedata.flie_src
ELSE subcondata.flie_src
END as flie_src,
CASE 
WHEN  inhousedata.buyer_name is not null
THEN inhousedata.buyer_name
ELSE subcondata.buyer_name
END as buyer_name,
inhousedata.roll_weight as inhouse_this_month,
inhousedata.lastmonth_roll_weight as inhouse_lastmonth_roll_weight,
subcondata.roll_weight as subcon_this_month,
subcondata.lastmonth_roll_weight as subcon_lastmonth_roll_weight
from
(
select 
inhouse.company_id,
inhouse.sales_order_id,
inhouse.b_company_id,
inhouse.produced_company_id,
inhouse.b_company_name,
inhouse.sale_order_no,
inhouse.style_ref,
inhouse.flie_src,
inhouse.buyer_name,
inhouse.roll_weight as  roll_weight,
inhouse.lastmonth_roll_weight
from (select
alldata.company_id,
alldata.sales_order_id,
alldata.b_company_id,
alldata.produced_company_id,
alldata.b_company_name,

alldata.sale_order_no,
alldata.style_ref,
alldata.flie_src,
alldata.buyer_name,
sum(alldata.roll_weight) as  roll_weight,
sum(alldata.lastmonth_roll_weight) as  lastmonth_roll_weight from 
(select 
suppliers.company_id,
thismonth.sales_order_id,
jobs.company_id as b_company_id,
sales_orders.produced_company_id,
companies.name as b_company_name,

sales_orders.sale_order_no,
styles.style_ref,
styles.flie_src,
buyers.name as  buyer_name,
sum(thismonth.roll_weight) as  roll_weight,
lastmonth.roll_weight as lastmonth_roll_weight
from
(
select 
prod_knits.id,
prod_knit_items.pl_knit_item_id,
prod_knit_items.po_knit_service_item_qty_id,
CASE
WHEN prod_knit_items.pl_knit_item_id is not null and prod_knit_items.pl_knit_item_id >0
THEN plknititems.sales_order_id
ELSE poknititems.sales_order_id
END AS sales_order_id,
sum(prod_knit_item_rolls.roll_weight) as roll_weight
from prod_knits
join prod_knit_items on prod_knits.id=prod_knit_items.prod_knit_id
join prod_knit_item_rolls on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id

left join (
select 
pl_knit_items.id,
CASE 
WHEN po_knit_service_item_qties.sales_order_id is null  or po_knit_service_item_qties.sales_order_id=0
THEN 0
ELSE
po_knit_service_item_qties.sales_order_id
END AS sales_order_id
from 
pl_knit_items
join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
join so_knits on so_knits.id=so_knit_refs.so_knit_id
left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
and po_knit_service_items.deleted_at is null
) plknititems on plknititems.id=prod_knit_items.pl_knit_item_id

left join (
select 
po_knit_service_item_qties.id,

CASE 
WHEN po_knit_service_item_qties.sales_order_id is null  or po_knit_service_item_qties.sales_order_id=0
THEN 0
ELSE
po_knit_service_item_qties.sales_order_id
END AS sales_order_id
from 
po_knit_service_item_qties
left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
and po_knit_service_items.deleted_at is null
) poknititems on poknititems.id=prod_knit_items.po_knit_service_item_qty_id
where prod_date between ? and ?

group by
prod_knits.id,
prod_knits.supplier_id,
prod_knits.shift_id,
prod_knit_items.id,
prod_knit_items.pl_knit_item_id,
prod_knit_items.po_knit_service_item_qty_id,
plknititems.sales_order_id,
poknititems.sales_order_id
) 
thismonth
left join (
select 
m.id,
m.sales_order_id,
sum(m.roll_weight) as  roll_weight
from
(
select 
prod_knits.id,
prod_knit_items.pl_knit_item_id,
prod_knit_items.po_knit_service_item_qty_id,
CASE
WHEN prod_knit_items.pl_knit_item_id is not null and prod_knit_items.pl_knit_item_id >0
THEN plknititems.sales_order_id
ELSE poknititems.sales_order_id
END AS sales_order_id,
sum(prod_knit_item_rolls.roll_weight) as roll_weight
from prod_knits
join prod_knit_items on prod_knits.id=prod_knit_items.prod_knit_id
join prod_knit_item_rolls on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id

left join (
select 
pl_knit_items.id,
CASE 
WHEN po_knit_service_item_qties.sales_order_id is null  or po_knit_service_item_qties.sales_order_id=0
THEN 0
ELSE
po_knit_service_item_qties.sales_order_id
END AS sales_order_id
from 
pl_knit_items
join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
join so_knits on so_knits.id=so_knit_refs.so_knit_id
left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
and po_knit_service_items.deleted_at is null
) plknititems on plknititems.id=prod_knit_items.pl_knit_item_id

left join (
select 
po_knit_service_item_qties.id,
CASE 
WHEN po_knit_service_item_qties.sales_order_id is null  or po_knit_service_item_qties.sales_order_id=0
THEN 0
ELSE
po_knit_service_item_qties.sales_order_id
END AS sales_order_id
from 
po_knit_service_item_qties
left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
and po_knit_service_items.deleted_at is null
) poknititems on poknititems.id=prod_knit_items.po_knit_service_item_qty_id
where prod_date <=?

group by
prod_knits.id,
prod_knits.supplier_id,
prod_knits.shift_id,
prod_knit_items.id,
prod_knit_items.pl_knit_item_id,
prod_knit_items.po_knit_service_item_qty_id,
plknititems.sales_order_id,
poknititems.sales_order_id
) 
m 
group by 
m.id,
m.sales_order_id
) lastmonth on lastmonth.id=thismonth.id and lastmonth.sales_order_id=thismonth.sales_order_id
left join prod_knits on prod_knits.id=thismonth.id
left join suppliers on suppliers.id=prod_knits.supplier_id
left join sales_orders on sales_orders.id=thismonth.sales_order_id
left join jobs on jobs.id=sales_orders.job_id
left join styles on styles.id=jobs.style_id
left join buyers on buyers.id=styles.buyer_id
left join companies on companies.id=jobs.company_id
left join companies produced_companies on produced_companies.id=sales_orders.produced_company_id


group by 
thismonth.id,
suppliers.company_id,
thismonth.sales_order_id,
lastmonth.roll_weight,
jobs.company_id,
sales_orders.produced_company_id,
companies.name,
sales_orders.id,
sales_orders.sale_order_no,
styles.style_ref,
styles.flie_src,
buyers.name
order by 
suppliers.company_id,
sales_orders.id
) alldata

group by 
alldata.company_id,
alldata.sales_order_id,
alldata.b_company_id,
alldata.produced_company_id,
alldata.b_company_name,
alldata.sale_order_no,
alldata.style_ref,
alldata.flie_src,
alldata.buyer_name) inhouse where inhouse.company_id is not null
) inhousedata

FULL JOIN 
(
select 
subcon.company_id,
subcon.sales_order_id,
subcon.b_company_id,
subcon.produced_company_id,
subcon.b_company_name,

subcon.sale_order_no,
subcon.style_ref,
subcon.flie_src,
subcon.buyer_name,
subcon.roll_weight as  roll_weight,
subcon.lastmonth_roll_weight
from (select
alldata.company_id,
alldata.sales_order_id,
alldata.b_company_id,
alldata.produced_company_id,
alldata.b_company_name,

alldata.sale_order_no,
alldata.style_ref,
alldata.flie_src,
alldata.buyer_name,
sum(alldata.roll_weight) as  roll_weight,
sum(alldata.lastmonth_roll_weight) as  lastmonth_roll_weight from 
(select 
suppliers.company_id,
thismonth.sales_order_id,
jobs.company_id as b_company_id,
sales_orders.produced_company_id,
companies.name as b_company_name,

sales_orders.sale_order_no,
styles.style_ref,
styles.flie_src,
buyers.name as  buyer_name,
sum(thismonth.roll_weight) as  roll_weight,
lastmonth.roll_weight as lastmonth_roll_weight
from
(
select 
prod_knits.id,
prod_knit_items.pl_knit_item_id,
prod_knit_items.po_knit_service_item_qty_id,
CASE
WHEN prod_knit_items.pl_knit_item_id is not null and prod_knit_items.pl_knit_item_id >0
THEN plknititems.sales_order_id
ELSE poknititems.sales_order_id
END AS sales_order_id,
sum(prod_knit_item_rolls.roll_weight) as roll_weight
from prod_knits
join prod_knit_items on prod_knits.id=prod_knit_items.prod_knit_id
join prod_knit_item_rolls on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id

left join (
select 
pl_knit_items.id,
CASE 
WHEN po_knit_service_item_qties.sales_order_id is null  or po_knit_service_item_qties.sales_order_id=0
THEN 0
ELSE
po_knit_service_item_qties.sales_order_id
END AS sales_order_id
from 
pl_knit_items
join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
join so_knits on so_knits.id=so_knit_refs.so_knit_id
left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
and po_knit_service_items.deleted_at is null
) plknititems on plknititems.id=prod_knit_items.pl_knit_item_id

left join (
select 
po_knit_service_item_qties.id,

CASE 
WHEN po_knit_service_item_qties.sales_order_id is null  or po_knit_service_item_qties.sales_order_id=0
THEN 0
ELSE
po_knit_service_item_qties.sales_order_id
END AS sales_order_id
from 
po_knit_service_item_qties
left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
and po_knit_service_items.deleted_at is null
) poknititems on poknititems.id=prod_knit_items.po_knit_service_item_qty_id
where prod_date between ? and ?

group by
prod_knits.id,
prod_knits.supplier_id,
prod_knits.shift_id,
prod_knit_items.id,
prod_knit_items.pl_knit_item_id,
prod_knit_items.po_knit_service_item_qty_id,
plknititems.sales_order_id,
poknititems.sales_order_id
) 
thismonth
left join (
select 
m.id,
m.sales_order_id,
sum(m.roll_weight) as  roll_weight
from
(
select 
prod_knits.id,
prod_knit_items.pl_knit_item_id,
prod_knit_items.po_knit_service_item_qty_id,
CASE
WHEN prod_knit_items.pl_knit_item_id is not null and prod_knit_items.pl_knit_item_id >0
THEN plknititems.sales_order_id
ELSE poknititems.sales_order_id
END AS sales_order_id,
sum(prod_knit_item_rolls.roll_weight) as roll_weight
from prod_knits
join prod_knit_items on prod_knits.id=prod_knit_items.prod_knit_id
join prod_knit_item_rolls on prod_knit_items.id=prod_knit_item_rolls.prod_knit_item_id

left join (
select 
pl_knit_items.id,
CASE 
WHEN po_knit_service_item_qties.sales_order_id is null  or po_knit_service_item_qties.sales_order_id=0
THEN 0
ELSE
po_knit_service_item_qties.sales_order_id
END AS sales_order_id
from 
pl_knit_items
join pl_knits on pl_knits.id=pl_knit_items.pl_knit_id
join so_knit_refs on so_knit_refs.id=pl_knit_items.so_knit_ref_id
join so_knits on so_knits.id=so_knit_refs.so_knit_id
left join so_knit_po_items on so_knit_po_items.so_knit_ref_id=so_knit_refs.id
left join so_knit_items on so_knit_items.so_knit_ref_id=so_knit_refs.id
left join po_knit_service_item_qties on po_knit_service_item_qties.id=so_knit_po_items.po_knit_service_item_qty_id
left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
and po_knit_service_items.deleted_at is null
) plknititems on plknititems.id=prod_knit_items.pl_knit_item_id

left join (
select 
po_knit_service_item_qties.id,
CASE 
WHEN po_knit_service_item_qties.sales_order_id is null  or po_knit_service_item_qties.sales_order_id=0
THEN 0
ELSE
po_knit_service_item_qties.sales_order_id
END AS sales_order_id
from 
po_knit_service_item_qties
left join po_knit_service_items on po_knit_service_items.id=po_knit_service_item_qties.po_knit_service_item_id
and po_knit_service_items.deleted_at is null
) poknititems on poknititems.id=prod_knit_items.po_knit_service_item_qty_id
where prod_date <=?

group by
prod_knits.id,
prod_knits.supplier_id,
prod_knits.shift_id,
prod_knit_items.id,
prod_knit_items.pl_knit_item_id,
prod_knit_items.po_knit_service_item_qty_id,
plknititems.sales_order_id,
poknititems.sales_order_id
) 
m 
group by 
m.id,
m.sales_order_id
) lastmonth on lastmonth.id=thismonth.id and lastmonth.sales_order_id=thismonth.sales_order_id
left join prod_knits on prod_knits.id=thismonth.id
left join suppliers on suppliers.id=prod_knits.supplier_id
left join sales_orders on sales_orders.id=thismonth.sales_order_id
left join jobs on jobs.id=sales_orders.job_id
left join styles on styles.id=jobs.style_id
left join buyers on buyers.id=styles.buyer_id
left join companies on companies.id=jobs.company_id
left join companies produced_companies on produced_companies.id=sales_orders.produced_company_id


group by 
thismonth.id,
suppliers.company_id,
thismonth.sales_order_id,
lastmonth.roll_weight,
jobs.company_id,
sales_orders.produced_company_id,
companies.name,
sales_orders.id,
sales_orders.sale_order_no,
styles.style_ref,
styles.flie_src,
buyers.name
order by 
suppliers.company_id,
sales_orders.id
) alldata

group by 
alldata.company_id,
alldata.sales_order_id,
alldata.b_company_id,
alldata.produced_company_id,
alldata.b_company_name,
alldata.sale_order_no,
alldata.style_ref,
alldata.flie_src,
alldata.buyer_name) subcon where subcon.company_id is  null
) subcondata on subcondata.sales_order_id=inhousedata.sales_order_id

',[$start_date,$date_to,$last_month,$start_date,$date_to,$last_month]);
//dd(\DB::getQueryLog()); // Show results of log

;
$data=collect($results)->groupBy('produced_company_id');
//echo json_encode($data);
//die;


	return Template::loadView('Report.FabricCapacityAchievePopUp.MonthAchieveKnittingMatrix',['data'=>$data,'company'=>$company]);
	}
}