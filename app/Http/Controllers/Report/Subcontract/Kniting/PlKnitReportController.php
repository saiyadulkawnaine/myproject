<?php

namespace App\Http\Controllers\Report\Subcontract\Kniting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;

use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemRepository;
class PlKnitReportController extends Controller
{
    private $company;
    private $buyer;
    private $location;
    private $assetacquisition;
    private $plknititem;

	public function __construct(
        CompanyRepository $company,
        BuyerRepository $buyer,
        LocationRepository $location,
        AssetAcquisitionRepository $assetacquisition,
        PlKnitItemRepository $plknititem
    )
    {
		
        $this->company = $company;
        $this->buyer = $buyer;
        $this->location = $location;
        $this->assetacquisition = $assetacquisition;
        $this->plknititem = $plknititem;
        $this->middleware('auth');
        //$this->middleware('permission:view.subinbmarketingreports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        return Template::loadView('Report.Subcontract.Kniting.PlKnitReport',['company'=>$company,'buyer'=>$buyer,'location'=>$location]);
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
        ->where([['asset_acquisitions.production_area_id','=',10]])
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
        $plknititem=$this->plknititem
        ->join('pl_knits', function($join)  {
            $join->on('pl_knits.id', '=', 'pl_knit_items.pl_knit_id');
        })
        ->join('pl_knit_item_qties', function($join)  {
            $join->on('pl_knit_item_qties.pl_knit_item_id', '=', 'pl_knit_items.id');
        })
        ->where([['pl_knits.company_id','=',request('company_id',0)]])
        ->when(request('date_from'), function ($q) use($date_from){
        return $q->where('pl_knit_item_qties.pl_date', '>=',$date_from);
        })
        ->when(request('date_to'), function ($q) use($date_to) {
        return $q->where('pl_knit_item_qties.pl_date', '<=',$date_to);
        })
        ->orderBy('pl_knit_item_qties.pl_date')
        ->get([
            'pl_knit_items.*',
            'pl_knit_item_qties.pl_date',
            'pl_knit_item_qties.qty as date_qty'
        ]);
         $months=array();
         $dateTotal=array();
        foreach($plknititem as $row)
        {
            $MonthYear=date('M-y',strtotime($row->pl_date));
            if(isset($months[$row->machine_id][$MonthYear][$row->pl_date]))
            {
                $months[$row->machine_id][$MonthYear][$row->pl_date]+=$row->date_qty;
            }
            else
            {
                $months[$row->machine_id][$MonthYear][$row->pl_date]=$row->date_qty; 
            }

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
        return Template::loadView('Report.Subcontract.Kniting.PlKnitReportData',['machines'=>$machines,'months'=>$months,'tableWidth'=>$tableWidth,'planData'=>$planData['months'],'dateTotal'=>$planData['dateTotal']]);
    }

     public function getdetail()
     {
        
     }
}

