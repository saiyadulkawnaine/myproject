<?php

namespace App\Http\Controllers\Report\Subcontract\Dyeing;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;

use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\PlDyeingItemRepository;
class PlDyeingReportController extends Controller
{
    private $company;
    private $buyer;
    private $location;
    private $assetacquisition;
    private $pldyeingitem;

	public function __construct(
        CompanyRepository $company,
        BuyerRepository $buyer,
        LocationRepository $location,
        AssetAcquisitionRepository $assetacquisition,
        PlDyeingItemRepository $pldyeingitem
    )
    {
		
        $this->company = $company;
        $this->buyer = $buyer;
        $this->location = $location;
        $this->assetacquisition = $assetacquisition;
        $this->pldyeingitem = $pldyeingitem;
        $this->middleware('auth');
        //$this->middleware('permission:view.subinbmarketingreports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        return Template::loadView('Report.Subcontract.Dyeing.PlDyeingReport',['company'=>$company,'buyer'=>$buyer,'location'=>$location]);
    }
	public function getMachine() {
        $machines=$this->assetacquisition
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.asset_acquisition_id','=','asset_acquisitions.id');
        })
        ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_acquisitions.id','=','asset_technical_features.asset_acquisition_id');
        })
        ->when(request('dia_width'), function ($q) {
        return $q->where('asset_technical_features.dia_width', '=>',request('dia_width', 0));
        })
        ->when(request('no_of_feeder'), function ($q) {
        return $q->where('asset_technical_features.no_of_feeder', '<=',request('no_of_feeder', 0));
        })
        ->where([['asset_acquisitions.production_area_id','=',20]])
        ->where([['asset_acquisitions.company_id','=',request('company_id',0)]])
        ->where([['asset_acquisitions.location_id','=',request('location_id',0)]])
        ->orderBy('asset_acquisitions.id','asc')
        ->orderBy('asset_quantity_costs.id','asc')
        ->get([
            'asset_quantity_costs.*',
            'asset_acquisitions.prod_capacity',
            'asset_acquisitions.name as asset_name',
            'asset_acquisitions.origin',
            'asset_acquisitions.brand',
            'asset_technical_features.dia_width',
            'asset_technical_features.gauge',
            'asset_technical_features.extra_cylinder',
            'asset_technical_features.no_of_feeder'
        ]);
        return $machines;
		
    }
    public function no_of_days($date_from,$date_to)
    {
        $earlier = new \DateTime($date_from);
        $later = new \DateTime($date_to);
        $diff = $later->diff($earlier)->format("%a");
        return $diff;
    }
    public function getMonth($date_from,$date_to)
    {
        
        $diff = $this->no_of_days($date_from,$date_to)+1;
        $dateArr=array();
        for($i=1;$i<=$diff;$i++)
        {
            $date_from = date('Y-m-d H:i:s', strtotime($date_from));
            $MonthYear=date('M-y',strtotime($date_from));
            $dateArr[$MonthYear][$date_from]=0;
            $date_from = date('Y-m-d H:i:s', strtotime($date_from . ' +1 day'));
        }
        return $dateArr;
    }

    
    public function getPlan($date_from,$date_to)
    {
        $pldyeingitem=$this->pldyeingitem
        ->join('pl_dyeings', function($join)  {
            $join->on('pl_dyeings.id', '=', 'pl_dyeing_items.pl_dyeing_id');
        })
        ->join('pl_dyeing_item_qties', function($join)  {
            $join->on('pl_dyeing_item_qties.pl_dyeing_item_id', '=', 'pl_dyeing_items.id');
        })
        ->join('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','pl_dyeings.machine_id');
        })
        ->join('colorranges', function($join)  {
            $join->on('colorranges.id', '=', 'pl_dyeing_items.colorrange_id');
        })
        ->join('so_dyeing_refs', function($join)  {
            $join->on('so_dyeing_refs.id', '=', 'pl_dyeing_items.so_dyeing_ref_id');
        })
        ->leftJoin('so_dyeing_po_items', function($join)  {
            $join->on('so_dyeing_po_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
        })
        ->leftJoin('po_dyeing_service_item_qties',function($join){
              $join->on('po_dyeing_service_item_qties.id','=','so_dyeing_po_items.po_dyeing_service_item_qty_id');
        })
        ->leftJoin('po_dyeing_service_items',function($join){
                 $join->on('po_dyeing_service_items.id','=','po_dyeing_service_item_qties.po_dyeing_service_item_id')
                 ->whereNull('po_dyeing_service_items.deleted_at');
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
        ->leftJoin('so_dyeing_items', function($join)  {
            $join->on('so_dyeing_items.so_dyeing_ref_id', '=', 'so_dyeing_refs.id');
        })
        ->leftJoin('colors as so_color',function($join){
            $join->on('so_color.id','=','so_dyeing_items.fabric_color_id');
        })
        ->leftJoin('colors as po_color',function($join){
            $join->on('po_color.id','=','po_dyeing_service_item_qties.fabric_color_id');
        })
        ->leftJoin('sales_orders',function($join){
            $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
        })
        ->where([['pl_dyeings.company_id','=',request('company_id',0)]])
        ->when(request('date_from'), function ($q) use($date_from){
        return $q->where('pl_dyeing_item_qties.pl_date', '>=',$date_from);
        })
        ->when(request('date_to'), function ($q) use($date_to) {
        return $q->where('pl_dyeing_item_qties.pl_date', '<=',$date_to);
        })
        ->orderBy('pl_dyeing_item_qties.pl_date')
        ->get([
            'pl_dyeing_items.*',
            'pl_dyeings.machine_id',
            'pl_dyeing_item_qties.pl_date',
            'pl_dyeing_item_qties.qty as date_qty',
            'so_color.name as c_fabric_color_name',
            'po_color.name as fabric_color_name',
            'so_dyeing_items.gmt_sale_order_no',
            'sales_orders.sale_order_no',
        ])
        ->map(function($pldyeingitem){
            $pldyeingitem->fabric_color=$pldyeingitem->fabric_color_name?$pldyeingitem->fabric_color_name:$pldyeingitem->c_fabric_color_name;
            $pldyeingitem->sales_order_no=$pldyeingitem->sale_order_no?$pldyeingitem->sale_order_no:$pldyeingitem->gmt_sale_order_no;
            return $pldyeingitem;

        });
         $months=array();
         $dateTotal=array();
        foreach($pldyeingitem as $row)
        {
            $MonthYear=date('M-y',strtotime($row->pl_date));
            if(isset($months[$row->machine_id][$MonthYear][$row->pl_date]['qty']))
            {
                $months[$row->machine_id][$MonthYear][$row->pl_date]['qty']+=$row->date_qty;
            }
            else
            {
                $months[$row->machine_id][$MonthYear][$row->pl_date]['qty']=$row->date_qty; 
            }

            $months[$row->machine_id][$MonthYear][$row->pl_date]['color']=$row->fabric_color;
            $months[$row->machine_id][$MonthYear][$row->pl_date]['ord_no']=$row->sales_order_no;

            if(isset($dateTotal[$MonthYear][$row->pl_date]))
            {
                $dateTotal[$MonthYear][$row->pl_date]+=$row->date_qty;
            }
            else
            {
                $dateTotal[$MonthYear][$row->pl_date]=$row->date_qty; 
            }
        }
        return ['months'=>$months,'dateTotal'=>$dateTotal];
    }

    public function html(){
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $machines=$this->getMachine();
        $months=$this->getMonth($date_from,$date_to);
        $noOfDays=$this->no_of_days($date_from,$date_to);
        $tableWidth=($noOfDays*40)+300;
        $planData=$this->getPlan($date_from,$date_to,$months);
        return Template::loadView('Report.Subcontract.Dyeing.PlDyeingReportData',['machines'=>$machines,'months'=>$months,'tableWidth'=>$tableWidth,'planData'=>$planData['months'],'dateTotal'=>$planData['dateTotal']]);
    }

     public function getdetail()
     {
        
     }
}

