<?php
namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Repositories\Contracts\Production\Garments\ProdGmtSewingRepository;
use App\Repositories\Contracts\Account\AccBepRepository;
use App\Repositories\Contracts\HRM\EmployeeAttendenceRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Util\KeycontrolRepository;
use Illuminate\Support\Carbon;

class TodayKnitingAchievementGraphController extends Controller
{
    private $no_of_days;
	private $exch_rate;
	private $company;
    private $subsection;
	private $wstudylinesetup;
	private $prodgmtsewing;
	private $accbep;
	private $attendence;
	private $salesorder;
    private $keycontrol;
	public function __construct(
		CompanyRepository $company,
        SubsectionRepository $subsection,
        WstudyLineSetupRepository $wstudylinesetup,
		ProdGmtSewingRepository $prodgmtsewing,
		AccBepRepository $accbep,
		EmployeeAttendenceRepository $attendence,
		SalesOrderRepository $salesorder,
        KeycontrolRepository $keycontrol
	)
    {
        $this->no_of_days                = 26;
		$this->exch_rate                 = 82;
		$this->subsection                = $subsection;
        $this->company                   = $company;
		$this->wstudylinesetup           = $wstudylinesetup;
		$this->prodgmtsewing             = $prodgmtsewing;
		$this->accbep                    = $accbep;
		$this->attendence                = $attendence;
		$this->salesorder                = $salesorder;
        $this->keycontrol = $keycontrol;
		$this->middleware('auth');
		//$this->middleware('permission:view.prodgmtcapacityachievereports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
        $today=date('Y-m-d');
    	return Template::loadView('Report.TodayKnitingAchivmentGraph', ['today'=>$today]);
    }

   

    private function makeChart($monCaps,$monTgts,$monProds,$dayCaps,$dayTgts,$dayProds){

        $monMacArr=[];
        $monCapMacTot=0;
        foreach($monCaps as  $monCap){
             $monMacArr[$monCap->machine_id]['name']=$monCap->name."-".$monCap->dia_width.'"';
             $monMacArr[$monCap->machine_id]['capAmt']=$monCap->amount;
             $monMacArr[$monCap->machine_id]['capQty']=$monCap->qty;
             $monCapMacTot+=1;
        }

        $dayMacArr=[];
        $dayCapMacTot=0;
        foreach($dayCaps as  $dayCap){
             $dayMacArr[$dayCap->machine_id]['name']=$dayCap->name."-".$dayCap->dia_width.'"';
             $dayMacArr[$dayCap->machine_id]['capAmt']=$dayCap->amount;
             $dayMacArr[$dayCap->machine_id]['capQty']=$dayCap->qty;
             $dayCapMacTot+=1;
        }


        $monConfigArr=[];
        foreach($monTgts as $monTgt){
           $monConfigArr[$monTgt->machine_id]=['Cap'=>0,'Tgt'=>0 ,'Prod'=>0];
        }
        foreach($monProds as $monProd){
           $monConfigArr[$monProd->machine_id]=['Cap'=>0,'Tgt'=>0 ,'Prod'=>0];
        }
        


        $dayConfigArr=[];
        foreach($dayTgts as $dayTgt){
           $dayConfigArr[$dayTgt->machine_id]=['Cap'=>0,'Tgt'=>0 ,'Prod'=>0];
        }
        foreach($dayProds as $dayProd){
           $dayConfigArr[$dayProd->machine_id]=['Cap'=>0,'Tgt'=>0 ,'Prod'=>0];
        }
       


       
       
        $monQty=$monConfigArr;
        $monAmt=$monConfigArr;

        $monTgtQtyTot=0;
        $monTgtAmtTot=0;
        $monTgtMacTot=0;

        $monCapQtyTot=0;
        $monCapAmtTot=0;


        foreach($monTgts as $monTgt){
            $index=$monTgt->machine_id;

            $monQty[$index]['Tgt']+=$monTgt->qty;
            $monAmt[$index]['Tgt']+=$monTgt->amount;

            $monTgtQtyTot+=$monTgt->qty;
            $monTgtAmtTot+=$monTgt->amount;
            $monTgtMacTot+=1;

            $monQty[$index]['Cap']+=$monMacArr[$index]['capQty'];
            $monAmt[$index]['Cap']+=$monMacArr[$index]['capAmt'];

            $monCapQtyTot+=$monMacArr[$index]['capQty'];
            $monCapAmtTot+=$monMacArr[$index]['capAmt'];

            
            
        }

        $monProdQtyTot=0;
        $monProdAmtTot=0;

        foreach($monProds as $monProd){
            $index=$monProd->machine_id;
            $monQty[$index]['Prod']+=$monProd->qty;
            $monAmt[$index]['Prod']+=$monProd->amount;

            $monProdQtyTot+=$monProd->qty;
            $monProdAmtTot+=$monProd->amount;
            //$monQty[$index]['Cap']+=$monMacArr[$index]['capQty'];
            //$monAmt[$index]['Cap']+=$monMacArr[$index]['capAmt'];
        }



        $dayQty=$dayConfigArr;
        $dayAmt=$dayConfigArr;

        $dayTgtQtyTot=0;
        $dayTgtAmtTot=0;
        $dayTgtMacTot=0;

        $dayCapQtyTot=0;
        $dayCapAmtTot=0;

        foreach($dayTgts as $dayTgt){
            $index=$dayTgt->machine_id;

            $dayQty[$index]['Tgt']+=$dayTgt->qty;
            $dayAmt[$index]['Tgt']+=$dayTgt->amount;

            $dayTgtQtyTot+=$dayTgt->qty;
            $dayTgtAmtTot+=$dayTgt->amount;
            $dayTgtMacTot+=1;

            $dayQty[$index]['Cap']+=$dayMacArr[$index]['capQty'];
            $dayAmt[$index]['Cap']+=$dayMacArr[$index]['capAmt'];

            $dayCapQtyTot+=$dayMacArr[$index]['capQty'];
            $dayCapAmtTot+=$dayMacArr[$index]['capAmt'];

            
            
        }

        $dayProdQtyTot=0;
        $dayProdAmtTot=0;

        foreach($dayProds as $dayProd){
            $index=$dayProd->machine_id;
            $dayQty[$index]['Prod']+=$dayProd->qty;
            $dayAmt[$index]['Prod']+=$dayProd->amount;

            $dayProdQtyTot+=$dayProd->qty;
            $dayProdAmtTot+=$dayProd->amount;

            //$dayQty[$index]['Cap']+=$dayMacArr[$index]['capQty'];
            //$dayAmt[$index]['Cap']+=$dayMacArr[$index]['capAmt'];
        }

        



       


        

//echo json_encode($monQty); die;
        
        $monQtydata=[];
        $monQtydatas=[];
        foreach($monQty as $key=>$value){
            $monQtydata['name']=$monMacArr[$key]['name'];
            $monQtydata['cap']=$value['Cap'];
            $monQtydata['tgt']=$value['Tgt'];
            $monQtydata['prod']=$value['Prod'];
            array_push($monQtydatas,$monQtydata);
        }


        $monAmtdata=[];
        $monAmtdatas=[];
        foreach($monAmt as $key=>$value){
            $monAmtdata['name']=$monMacArr[$key]['name'];
            $monAmtdata['cap']=$value['Cap'];
            $monAmtdata['tgt']=$value['Tgt'];
            $monAmtdata['prod']=$value['Prod'];
            array_push($monAmtdatas,$monAmtdata);
        }


        $dayQtydata=[];
        $dayQtydatas=[];
        foreach($dayQty as $key=>$value){
            $dayQtydata['name']=$dayMacArr[$key]['name'];
            $dayQtydata['cap']=$value['Cap'];
            $dayQtydata['tgt']=$value['Tgt'];
            $dayQtydata['prod']=$value['Prod'];
            array_push($dayQtydatas,$dayQtydata);
        }


        $dayAmtdata=[];
        $dayAmtdatas=[];
        foreach($dayAmt as $key=>$value){
            $dayAmtdata['name']=$dayMacArr[$key]['name'];
            $dayAmtdata['cap']=$value['Cap'];
            $dayAmtdata['tgt']=$value['Tgt'];
            $dayAmtdata['prod']=$value['Prod'];
            array_push($dayAmtdatas,$dayAmtdata);
        }


        

        

        

        $tempdata_1="'".Template::loadView('Report.TodayKnitingAchivmentGraphData1',[
            'dayCapAmtTot'=>$dayCapAmtTot,
            'dayProdAmtTot'=>$dayProdAmtTot 
        ])."'";

        $tempdata_2="'".Template::loadView('Report.TodayKnitingAchivmentGraphData2',[
            'dayCapQtyTot'=>$dayCapQtyTot,
            'dayTgtQtyTot'=>$dayTgtQtyTot,
            'dayProdQtyTot'=>$dayProdQtyTot,
            'dayCapMacTot'=>$dayCapMacTot,
            'dayTgtMacTot'=>$dayTgtMacTot,
        ])."'";

        $tempdata_3="'".Template::loadView('Report.TodayKnitingAchivmentGraphData3',[
            'monCapAmtTot'=>$monCapAmtTot,
            'monProdAmtTot'=>$monProdAmtTot 
        ])."'";

        $tempdata_4="'".Template::loadView('Report.TodayKnitingAchivmentGraphData4',[
            'monCapQtyTot'=>$monCapQtyTot,
            'monTgtQtyTot'=>$monTgtQtyTot,
            'monProdQtyTot'=>$monProdQtyTot,
            'monCapMacTot'=>$monCapMacTot,
            'monTgtMacTot'=>$monTgtMacTot,
        ])."'";

       
        echo json_encode([
            'dayAmtdatas'=>['graphdata'=>$dayAmtdatas,'htmldata'=>$tempdata_1],
            'dayQtydatas'=>['graphdata'=>$dayQtydatas,'htmldata'=>$tempdata_2],
            
            'monAmtdatas'=>['graphdata'=>$monAmtdatas,'htmldata'=>$tempdata_3],
            'monQtydatas'=>['graphdata'=>$monQtydatas,'htmldata'=>$tempdata_4],
        ]);
    }
    public function getGraph()
    {
        $date_to=request('date_to',0);
        $today=$date_to ? $date_to : date('y-m-d');
        $YM=date('Y-m',strtotime($today));
        $from=$YM."-1";
        $to=date('Y-m-t',strtotime($from));
    	
        

        

    	

        $monCaps = collect(
        \DB::select("
                select
                asset_acquisitions.company_id,
                asset_quantity_costs.id as machine_id,
                asset_acquisitions.prod_capacity*26 as qty,
                (asset_acquisitions.prod_capacity*keycontrol.value*26) as amount,
                asset_quantity_costs.custom_no as name,
                asset_technical_features.dia_width
                from
                asset_acquisitions
                join asset_quantity_costs on asset_quantity_costs.asset_acquisition_id=asset_acquisitions.id
                left join asset_technical_features on asset_technical_features.asset_acquisition_id=asset_acquisitions.id
                left join (
                select
                keycontrols.company_id,
                keycontrol_parameters.value
                from 
                keycontrols
                join keycontrol_parameters on keycontrol_parameters.keycontrol_id=keycontrols.id
                where keycontrol_parameters.parameter_id=13
                and ? between keycontrol_parameters.from_date and keycontrol_parameters.to_date
                ) keycontrol on keycontrol.company_id=asset_acquisitions.company_id

                where asset_acquisitions.production_area_id=10 and asset_acquisitions.company_id=4 order by asset_quantity_costs.id
            ",[$today])
        );
        
        

        $monTgts = collect(
        \DB::select("
            select 
            m.machine_id,
            sum(m.qty) as qty,
            sum(m.amount) as amount
            from 
            (select 
            pl_knit_items.machine_id,
            pl_knit_item_qties.qty,
            so_knit_items.rate,
            po_knit_service_item_qties.rate as po_rate,
            po_knit_services.currency_id,
            so_knits.currency_id,
            CASE
            WHEN so_knit_items.rate is not null and so_knits.currency_id=1
            THEN pl_knit_item_qties.qty*so_knit_items.rate*83
            WHEN so_knit_items.rate is not null and so_knits.currency_id=2
            THEN pl_knit_item_qties.qty*so_knit_items.rate

            WHEN po_knit_service_item_qties.rate is not null and po_knit_services.currency_id=1
            THEN pl_knit_item_qties.qty*po_knit_service_item_qties.rate*83
            WHEN po_knit_service_item_qties.rate is not null and po_knit_services.currency_id=2
            THEN pl_knit_item_qties.qty*po_knit_service_item_qties.rate
            ELSE 0 
            END as amount

            from 
            pl_knits
            join pl_knit_items on pl_knit_items.pl_knit_id=pl_knits.id
            join pl_knit_item_qties on pl_knit_item_qties.pl_knit_item_id=pl_knit_items.id
            join so_knit_refs on so_knit_refs.id = pl_knit_items.so_knit_ref_id
            join so_knits on so_knits.id = so_knit_refs.so_knit_id
            left join so_knit_items on so_knit_items.so_knit_ref_id = so_knit_refs.id
            left join so_knit_po_items on so_knit_po_items.so_knit_ref_id = so_knit_refs.id
            left join po_knit_service_item_qties on po_knit_service_item_qties.id = so_knit_po_items.po_knit_service_item_qty_id
            left join po_knit_service_items on po_knit_service_items.id = po_knit_service_item_qties.po_knit_service_item_id
            left join po_knit_services on po_knit_services.id = po_knit_service_items.po_knit_service_id
            where
            pl_knit_item_qties.pl_date>=?
            and pl_knit_item_qties.pl_date<=?
            and pl_knit_items.machine_id is not null
            ) m group by m.machine_id order by m.machine_id
            ",[$from,$to])
        );

        $monProds = collect(
        \DB::select("
            select 
            m.machine_id,
            sum(m.qty) as qty,
            sum(m.amount) as amount
            from
            (select 
            prod_knit_items.pl_knit_item_id,
            prod_knit_items.asset_quantity_cost_id as machine_id,
            prod_knit_item_rolls.roll_weight as qty,
            so_knit_items.rate,
            po_knit_service_item_qties.rate as po_rate,
            po_knit_services.currency_id,
            so_knits.currency_id,
            CASE
            WHEN so_knit_items.rate is not null and so_knits.currency_id=1
            THEN prod_knit_item_rolls.roll_weight*so_knit_items.rate*83
            WHEN so_knit_items.rate is not null and so_knits.currency_id=2
            THEN prod_knit_item_rolls.roll_weight*so_knit_items.rate

            WHEN po_knit_service_item_qties.rate is not null and po_knit_services.currency_id=1
            THEN prod_knit_item_rolls.roll_weight*po_knit_service_item_qties.rate*83
            WHEN po_knit_service_item_qties.rate is not null and po_knit_services.currency_id=2
            THEN prod_knit_item_rolls.roll_weight*po_knit_service_item_qties.rate
            ELSE 0 
            END as amount
            FROM prod_knits
            join prod_knit_items on prod_knit_items.prod_knit_id = prod_knits.id
            join prod_knit_item_rolls on prod_knit_item_rolls.prod_knit_item_id = prod_knit_items.id
            join pl_knit_items on pl_knit_items.id = prod_knit_items.pl_knit_item_id
            join so_knit_refs on so_knit_refs.id = pl_knit_items.so_knit_ref_id
            join so_knits on so_knits.id = so_knit_refs.so_knit_id
            left join so_knit_items on so_knit_items.so_knit_ref_id = so_knit_refs.id
            left join so_knit_po_items on so_knit_po_items.so_knit_ref_id = so_knit_refs.id
            left join po_knit_service_item_qties on po_knit_service_item_qties.id = so_knit_po_items.po_knit_service_item_qty_id
            left join po_knit_service_items on po_knit_service_items.id = po_knit_service_item_qties.po_knit_service_item_id
            left join po_knit_services on po_knit_services.id = po_knit_service_items.po_knit_service_id
            where prod_knits.prod_date>=? and 
            prod_knits.prod_date<=? and 
            prod_knits.basis_id=1 and
            prod_knits.deleted_at is null and
            prod_knit_items.deleted_at is null and
            prod_knit_item_rolls.deleted_at is null
            and prod_knit_items.asset_quantity_cost_id is not null
            ) m  group by m.machine_id order by m.machine_id 
            ",[$from,$to])
        );


        $dayCaps = collect(
        \DB::select("
            select
            asset_acquisitions.company_id,
            asset_quantity_costs.id as machine_id,
            asset_acquisitions.prod_capacity as qty,
            (asset_acquisitions.prod_capacity*keycontrol.value) as amount,
            asset_quantity_costs.custom_no as name,
            asset_technical_features.dia_width
            from
            asset_acquisitions
            join asset_quantity_costs on asset_quantity_costs.asset_acquisition_id=asset_acquisitions.id
            left join asset_technical_features on asset_technical_features.asset_acquisition_id=asset_acquisitions.id
            left join (
            select
            keycontrols.company_id,
            keycontrol_parameters.value
            from 
            keycontrols
            join keycontrol_parameters on keycontrol_parameters.keycontrol_id=keycontrols.id
            where keycontrol_parameters.parameter_id=13
            and ? between keycontrol_parameters.from_date and keycontrol_parameters.to_date
            ) keycontrol on keycontrol.company_id=asset_acquisitions.company_id

            where asset_acquisitions.production_area_id=10 and asset_acquisitions.company_id=4 order by asset_quantity_costs.id
            ",[$today])
        );
        
        

        $dayTgts = collect(
        \DB::select("
            select 
            m.machine_id,
            sum(m.qty) as qty,
            sum(m.amount) as amount
            from 
            (select 
            pl_knit_items.machine_id,
            pl_knit_item_qties.qty,
            so_knit_items.rate,
            po_knit_service_item_qties.rate as po_rate,
            po_knit_services.currency_id,
            so_knits.currency_id,
            CASE
            WHEN so_knit_items.rate is not null and so_knits.currency_id=1
            THEN pl_knit_item_qties.qty*so_knit_items.rate*83
            WHEN so_knit_items.rate is not null and so_knits.currency_id=2
            THEN pl_knit_item_qties.qty*so_knit_items.rate

            WHEN po_knit_service_item_qties.rate is not null and po_knit_services.currency_id=1
            THEN pl_knit_item_qties.qty*po_knit_service_item_qties.rate*83
            WHEN po_knit_service_item_qties.rate is not null and po_knit_services.currency_id=2
            THEN pl_knit_item_qties.qty*po_knit_service_item_qties.rate
            ELSE 0 
            END as amount

            from 
            pl_knits
            join pl_knit_items on pl_knit_items.pl_knit_id=pl_knits.id
            join pl_knit_item_qties on pl_knit_item_qties.pl_knit_item_id=pl_knit_items.id
            join so_knit_refs on so_knit_refs.id = pl_knit_items.so_knit_ref_id
            join so_knits on so_knits.id = so_knit_refs.so_knit_id
            left join so_knit_items on so_knit_items.so_knit_ref_id = so_knit_refs.id
            left join so_knit_po_items on so_knit_po_items.so_knit_ref_id = so_knit_refs.id
            left join po_knit_service_item_qties on po_knit_service_item_qties.id = so_knit_po_items.po_knit_service_item_qty_id
            left join po_knit_service_items on po_knit_service_items.id = po_knit_service_item_qties.po_knit_service_item_id
            left join po_knit_services on po_knit_services.id = po_knit_service_items.po_knit_service_id
            where
            pl_knit_item_qties.pl_date>=?
            and pl_knit_item_qties.pl_date<=?
            and pl_knit_items.machine_id is not null
            ) m group by m.machine_id order by m.machine_id
            ",[$today,$today])
        );

        $dayProds = collect(
        \DB::select("
            select 
            m.machine_id,
            sum(m.qty) as qty,
            sum(m.amount) as amount
            from
            (select 
            prod_knit_items.pl_knit_item_id,
            prod_knit_items.asset_quantity_cost_id as machine_id,
            prod_knit_item_rolls.roll_weight as qty,
            so_knit_items.rate,
            po_knit_service_item_qties.rate as po_rate,
            po_knit_services.currency_id,
            so_knits.currency_id,
            CASE
            WHEN so_knit_items.rate is not null and so_knits.currency_id=1
            THEN prod_knit_item_rolls.roll_weight*so_knit_items.rate*83
            WHEN so_knit_items.rate is not null and so_knits.currency_id=2
            THEN prod_knit_item_rolls.roll_weight*so_knit_items.rate

            WHEN po_knit_service_item_qties.rate is not null and po_knit_services.currency_id=1
            THEN prod_knit_item_rolls.roll_weight*po_knit_service_item_qties.rate*83
            WHEN po_knit_service_item_qties.rate is not null and po_knit_services.currency_id=2
            THEN prod_knit_item_rolls.roll_weight*po_knit_service_item_qties.rate
            ELSE 0 
            END as amount
            FROM prod_knits
            join prod_knit_items on prod_knit_items.prod_knit_id = prod_knits.id
            join prod_knit_item_rolls on prod_knit_item_rolls.prod_knit_item_id = prod_knit_items.id
            join pl_knit_items on pl_knit_items.id = prod_knit_items.pl_knit_item_id
            join so_knit_refs on so_knit_refs.id = pl_knit_items.so_knit_ref_id
            join so_knits on so_knits.id = so_knit_refs.so_knit_id
            left join so_knit_items on so_knit_items.so_knit_ref_id = so_knit_refs.id
            left join so_knit_po_items on so_knit_po_items.so_knit_ref_id = so_knit_refs.id
            left join po_knit_service_item_qties on po_knit_service_item_qties.id = so_knit_po_items.po_knit_service_item_qty_id
            left join po_knit_service_items on po_knit_service_items.id = po_knit_service_item_qties.po_knit_service_item_id
            left join po_knit_services on po_knit_services.id = po_knit_service_items.po_knit_service_id
            where prod_knits.prod_date>=? and 
            prod_knits.prod_date<=? and 
            prod_knits.basis_id=1 and
            prod_knits.deleted_at is null and
            prod_knit_items.deleted_at is null and
            prod_knit_item_rolls.deleted_at is null
            and prod_knit_items.asset_quantity_cost_id is not null
            ) m  group by m.machine_id order by m.machine_id
            ",[$today,$today])
        );


        $this->makeChart($monCaps,$monTgts,$monProds,$dayCaps,$dayTgts,$dayProds);
    }
}