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

class TodayAopAchievementGraphController extends Controller
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
    	return Template::loadView('Report.TodayAopAchivmentGraph', ['today'=>$today]);
    }

   

    private function makeChart($monCusTgts,$monCusRcvs,$monCusDlvs,$dayCusTgts,$dayCusRcvs,$dayCusDlvs,$monMktTgts,$monMktRcvs,$monMktDlvs,$dayMktTgts,$dayMktRcvs,$dayMktDlvs){
        $monCusConfigArr=[];
        foreach($monCusTgts as $monCusTgt){
           $monCusConfigArr[$monCusTgt->buyer_id]=['Tgt'=>0,'Rcv'=>0 ,'Dlv'=>0];
        }
        foreach($monCusRcvs as $monCusRcv){
           $monCusConfigArr[$monCusRcv->buyer_id]=['Tgt'=>0,'Rcv'=>0,'Dlv'=>0];
        }
        foreach($monCusDlvs as $monCusDlv){
           $monCusConfigArr[$monCusDlv->buyer_id]=['Tgt'=>0,'Rcv'=>0,'Dlv'=>0];
        }


        $dayCusConfigArr=[];
        foreach($dayCusTgts as $dayCusTgt){
           $dayCusConfigArr[$dayCusTgt->buyer_id]=['Tgt'=>0,'Rcv'=>0 ,'Dlv'=>0];
        }
        foreach($dayCusRcvs as $dayCusRcv){
           $dayCusConfigArr[$dayCusRcv->buyer_id]=['Tgt'=>0,'Rcv'=>0,'Dlv'=>0];
        }
        foreach($dayCusDlvs as $dayCusDlv){
           $dayCusConfigArr[$dayCusDlv->buyer_id]=['Tgt'=>0,'Rcv'=>0,'Dlv'=>0];
        }



        
        
       

        
       
        $buyer_name=[];
        $monCusQty=$monCusConfigArr;
        $monCusAmt=$monCusConfigArr;

        $monCusTgtQtyTot=0;
        $monCusTgtAmtTot=0;

        foreach($monCusTgts as $monCusTgt){
            $index=$monCusTgt->buyer_id;
            $monCusQty[$index]['Tgt']+=$monCusTgt->qty;
            $monCusAmt[$index]['Tgt']+=$monCusTgt->amount;
            $buyer_name[$index]=$monCusTgt->buyer_name;

            $monCusTgtQtyTot+=$monCusTgt->qty;
            $monCusTgtAmtTot+=$monCusTgt->amount;
            
        }

        $monCusRcvQtyTot=0;
        $monCusRcvAmtTot=0;

        foreach($monCusRcvs as $monCusRcv){
            $index=$monCusRcv->buyer_id;
            $monCusQty[$index]['Rcv']+=$monCusRcv->qty;
            $monCusAmt[$index]['Rcv']+=$monCusRcv->amount;
            $buyer_name[$index]=$monCusRcv->buyer_name;

            $monCusRcvQtyTot+=$monCusRcv->qty;
            $monCusRcvAmtTot+=$monCusRcv->amount;
        }

        $monCusDlvQtyTot=0;
        $monCusDlvAmtTot=0;

        $monCusDlvGreyQtyTot=0;

        foreach($monCusDlvs as $monCusDlv){
            $index=$monCusDlv->buyer_id;
            $monCusQty[$index]['Dlv']+=$monCusDlv->fin_qty;
            $monCusAmt[$index]['Dlv']+=$monCusDlv->amount;
            $buyer_name[$index]=$monCusDlv->buyer_name;

            $monCusDlvQtyTot+=$monCusDlv->fin_qty;
            $monCusDlvAmtTot+=$monCusDlv->amount;

            $monCusDlvGreyQtyTot+=$monCusDlv->grey_qty;
        }



       


        $dayCusQty=$dayCusConfigArr;
        $dayCusAmt=$dayCusConfigArr;

        $dayCusTgtQtyTot=0;
        $dayCusTgtAmtTot=0;

        foreach($dayCusTgts as $dayCusTgt){
            $index=$dayCusTgt->buyer_id;
            $dayCusQty[$index]['Tgt']+=$dayCusTgt->qty;
            $dayCusAmt[$index]['Tgt']+=$dayCusTgt->amount;

            $dayCusTgtQtyTot+=$dayCusTgt->qty;
            $dayCusTgtAmtTot+=$dayCusTgt->amount;
            
        }
        
        $dayCusRcvQtyTot=0;
        $dayCusRcvAmtTot=0;

        foreach($dayCusRcvs as $dayCusRcv){
            $index=$dayCusRcv->buyer_id;
            $dayCusQty[$index]['Rcv']+=$dayCusRcv->qty;
            $dayCusAmt[$index]['Rcv']+=$dayCusRcv->amount;

            $dayCusRcvQtyTot+=$dayCusRcv->qty;
            $dayCusRcvAmtTot+=$dayCusRcv->amount;
        }


        $dayCusDlvQtyTot=0;
        $dayCusDlvAmtTot=0;

        $dayCusDlvGreyQtyTot=0;

        foreach($dayCusDlvs as $dayCusDlv){
            $index=$dayCusDlv->buyer_id;
            $dayCusQty[$index]['Dlv']+=$dayCusDlv->fin_qty;
            $dayCusAmt[$index]['Dlv']+=$dayCusDlv->amount;

            $dayCusDlvQtyTot+=$dayCusDlv->fin_qty;
            $dayCusDlvAmtTot+=$dayCusDlv->amount;

            $dayCusDlvGreyQtyTot+=$dayCusDlv->grey_qty;
        }

        
        $monCusQtydata=[];
        $monCusQtydatas=[];
        foreach($monCusQty as $key=>$value){
            $monCusQtydata['name']=$buyer_name[$key];
            $monCusQtydata['tgt']=$value['Tgt'];
            $monCusQtydata['rcv']=$value['Rcv'];
            $monCusQtydata['dlv']=$value['Dlv'];
            array_push($monCusQtydatas,$monCusQtydata);
        }


        $monCusAmtdata=[];
        $monCusAmtdatas=[];
        foreach($monCusAmt as $key=>$value){
            $monCusAmtdata['name']=$buyer_name[$key];
            $monCusAmtdata['tgt']=$value['Tgt'];
            $monCusAmtdata['rcv']=$value['Rcv'];
            $monCusAmtdata['dlv']=$value['Dlv'];
            array_push($monCusAmtdatas,$monCusAmtdata);
        }


        $dayCusQtydata=[];
        $dayCusQtydatas=[];
        foreach($dayCusQty as $key=>$value){
            $dayCusQtydata['name']=$buyer_name[$key];
            $dayCusQtydata['tgt']=$value['Tgt'];
            $dayCusQtydata['rcv']=$value['Rcv'];
            $dayCusQtydata['dlv']=$value['Dlv'];
            array_push($dayCusQtydatas,$dayCusQtydata);
        }

        $dayCusAmtdata=[];
        $dayCusAmtdatas=[];
        foreach($dayCusAmt as $key=>$value){
            $dayCusAmtdata['name']=$buyer_name[$key];
            $dayCusAmtdata['tgt']=$value['Tgt'];
            $dayCusAmtdata['rcv']=$value['Rcv'];
            $dayCusAmtdata['dlv']=$value['Dlv'];
            array_push($dayCusAmtdatas,$dayCusAmtdata);
        }

        //=========================

        $monMktConfigArr=[];
        foreach($monMktTgts as $monMktTgt){
           $monMktConfigArr[$monMktTgt->teammember_id]=['Tgt'=>0,'Rcv'=>0 ,'Dlv'=>0];
        }
        foreach($monMktRcvs as $monMktRcv){
           $monMktConfigArr[$monMktRcv->teammember_id]=['Tgt'=>0,'Rcv'=>0,'Dlv'=>0];
        }
        foreach($monMktDlvs as $monMktDlv){
           $monMktConfigArr[$monMktDlv->teammember_id]=['Tgt'=>0,'Rcv'=>0,'Dlv'=>0];
        }

        $dayMktConfigArr=[];
        foreach($dayMktTgts as $dayMktTgt){
           $dayMktConfigArr[$dayMktTgt->teammember_id]=['Tgt'=>0,'Rcv'=>0 ,'Dlv'=>0];
        }
        foreach($dayMktRcvs as $dayMktRcv){
           $dayMktConfigArr[$dayMktRcv->teammember_id]=['Tgt'=>0,'Rcv'=>0,'Dlv'=>0];
        }
        foreach($dayMktDlvs as $dayMktDlv){
           $dayMktConfigArr[$dayMktDlv->teammember_id]=['Tgt'=>0,'Rcv'=>0,'Dlv'=>0];
        }

        $user_name=[];
        $monMktQty=$monMktConfigArr;
        $monMktAmt=$monMktConfigArr;
        foreach($monMktTgts as $monMktTgt){
            $index=$monMktTgt->teammember_id;
            $monMktQty[$index]['Tgt']+=$monMktTgt->qty;
            $monMktAmt[$index]['Tgt']+=$monMktTgt->amount;
            $user_name[$index]=$monMktTgt->user_name;
            
        }
        
        foreach($monMktRcvs as $monMktRcv){
            $index=$monMktRcv->teammember_id;
            $monMktQty[$index]['Rcv']+=$monMktRcv->qty;
            $monMktAmt[$index]['Rcv']+=$monMktRcv->amount;
            $user_name[$index]=$monMktRcv->user_name;
        }

        foreach($monMktDlvs as $monMktDlv){
            $index=$monMktDlv->teammember_id;
            $monMktQty[$index]['Dlv']+=$monMktDlv->fin_qty;
            $monMktAmt[$index]['Dlv']+=$monMktDlv->amount;
            $user_name[$index]=$monMktDlv->user_name;
        }

        $dayMktQty=$dayMktConfigArr;
        $dayMktAmt=$dayMktConfigArr;
        foreach($dayMktTgts as $dayMktTgt){
            $index=$dayMktTgt->teammember_id;
            $dayMktQty[$index]['Tgt']+=$dayMktTgt->qty;
            $dayMktAmt[$index]['Tgt']+=$dayMktTgt->amount;
            
        }
        
        foreach($dayMktRcvs as $dayMktRcv){
            $index=$dayMktRcv->teammember_id;
            $dayMktQty[$index]['Rcv']+=$dayMktRcv->qty;
            $dayMktAmt[$index]['Rcv']+=$dayMktRcv->amount;
        }

        foreach($dayMktDlvs as $dayMktDlv){
            $index=$dayMktDlv->teammember_id;
            $dayMktQty[$index]['Dlv']+=$dayMktDlv->fin_qty;
            $dayMktAmt[$index]['Dlv']+=$dayMktDlv->amount;
        }


        $monMktQtydata=[];
        $monMktQtydatas=[];
        foreach($monMktQty as $key=>$value){
            $monMktQtydata['name']=$user_name[$key];;
            $monMktQtydata['tgt']=$value['Tgt'];
            $monMktQtydata['rcv']=$value['Rcv'];
            $monMktQtydata['dlv']=$value['Dlv'];
            array_push($monMktQtydatas,$monMktQtydata);
        }


        $monMktAmtdata=[];
        $monMktAmtdatas=[];
        foreach($monMktAmt as $key=>$value){
            $monMktAmtdata['name']=$user_name[$key];
            $monMktAmtdata['tgt']=$value['Tgt'];
            $monMktAmtdata['rcv']=$value['Rcv'];
            $monMktAmtdata['dlv']=$value['Dlv'];
            array_push($monMktAmtdatas,$monMktAmtdata);
        }


        $dayMktQtydata=[];
        $dayMktQtydatas=[];
        foreach($dayMktQty as $key=>$value){
            $dayMktQtydata['name']=$user_name[$key];;
            $dayMktQtydata['tgt']=$value['Tgt'];
            $dayMktQtydata['rcv']=$value['Rcv'];
            $dayMktQtydata['dlv']=$value['Dlv'];
            array_push($dayMktQtydatas,$dayMktQtydata);
        }

        $dayMktAmtdata=[];
        $dayMktAmtdatas=[];
        foreach($dayMktAmt as $key=>$value){
            $dayMktAmtdata['name']=$user_name[$key];;
            $dayMktAmtdata['tgt']=$value['Tgt'];
            $dayMktAmtdata['rcv']=$value['Rcv'];
            $dayMktAmtdata['dlv']=$value['Dlv'];
            array_push($dayMktAmtdatas,$dayMktAmtdata);
        }



        //=========================
       
        $tempdata_1="'".Template::loadView('Report.TodayAopAchivmentGraphData1',[
            'dayCusTgtQtyTot'=>$dayCusTgtQtyTot,
            //'dayCusTgtAmtTot'=>$dayCusTgtAmtTot,
            'dayCusRcvQtyTot'=>$dayCusRcvQtyTot, 
            //'dayCusRcvAmtTot'=>$dayCusRcvAmtTot,
            'dayCusDlvQtyTot'=>$dayCusDlvQtyTot,
            //'dayCusDlvAmtTot'=>$dayCusDlvAmtTot,
            'dayCusDlvGreyQtyTot'=>$dayCusDlvGreyQtyTot,
        ])."'";

        $tempdata_2="'".Template::loadView('Report.TodayAopAchivmentGraphData2',[
            //'dayCusTgtQtyTot'=>$dayCusTgtQtyTot,
            'dayCusTgtAmtTot'=>$dayCusTgtAmtTot,
            //'dayCusRcvQtyTot'=>$dayCusRcvQtyTot, 
            'dayCusRcvAmtTot'=>$dayCusRcvAmtTot,
            //'dayCusDlvQtyTot'=>$dayCusDlvQtyTot,
            'dayCusDlvAmtTot'=>$dayCusDlvAmtTot,
            //'dayCusDlvGreyQtyTot'=>$dayCusDlvGreyQtyTot,
        ])."'";

        $tempdata_3="'".Template::loadView('Report.TodayAopAchivmentGraphData3',[
            'monCusTgtQtyTot'=>$monCusTgtQtyTot,
            //'dayCusTgtAmtTot'=>$dayCusTgtAmtTot,
            'monCusRcvQtyTot'=>$monCusRcvQtyTot, 
            //'dayCusRcvAmtTot'=>$dayCusRcvAmtTot,
            'monCusDlvQtyTot'=>$monCusDlvQtyTot,
            //'dayCusDlvAmtTot'=>$dayCusDlvAmtTot,
            'monCusDlvGreyQtyTot'=>$monCusDlvGreyQtyTot,
        ])."'";

        $tempdata_4="'".Template::loadView('Report.TodayAopAchivmentGraphData4',[
            //'dayCusTgtQtyTot'=>$dayCusTgtQtyTot,
            'monCusTgtAmtTot'=>$monCusTgtAmtTot,
            //'dayCusRcvQtyTot'=>$dayCusRcvQtyTot, 
            'monCusRcvAmtTot'=>$monCusRcvAmtTot,
            //'dayCusDlvQtyTot'=>$dayCusDlvQtyTot,
            'monCusDlvAmtTot'=>$monCusDlvAmtTot,
            //'dayCusDlvGreyQtyTot'=>$dayCusDlvGreyQtyTot,
        ])."'";

       
        echo json_encode([
            
            'dayCusQtydatas'=>['graphdata'=>$dayCusQtydatas,'htmldata'=>$tempdata_1],
            'dayCusAmtdatas'=>['graphdata'=>$dayCusAmtdatas,'htmldata'=>$tempdata_2],
            'monCusQtydatas'=>['graphdata'=>$monCusQtydatas,'htmldata'=>$tempdata_3],
            'monCusAmtdatas'=>['graphdata'=>$monCusAmtdatas,'htmldata'=>$tempdata_4],
            

            
            'dayMktQtydatas'=>['graphdata'=>$dayMktQtydatas,'htmldata'=>$tempdata_1],
            'dayMktAmtdatas'=>['graphdata'=>$dayMktAmtdatas,'htmldata'=>$tempdata_2],
            'monMktQtydatas'=>['graphdata'=>$monMktQtydatas,'htmldata'=>$tempdata_3],
            'monMktAmtdatas'=>['graphdata'=>$monMktAmtdatas,'htmldata'=>$tempdata_4],
           
            //'tempdata_1'=>$tempdata_1,
        ]);
    }
    public function getGraph()
    {
        $date_to=request('date_to',0);
        $today=$date_to ? $date_to : date('y-m-d');
        $YM=date('Y-m',strtotime($today));
        $from=$YM."-1";
        $to=date('Y-m-t',strtotime($from));
    	
        /*$companies = collect(
        \DB::select("
                select companies.id,companies.code as company_code
                from 
                companies
                where companies.id=$company_id 
                order by companies.id
            ")
        );*/

        

    	

        $monCusTgts = collect(
        \DB::select("
            select
            so_aop_targets.buyer_id,
            buyers.name as buyer_name,
            sum(so_aop_targets.qty) as qty,
            sum(so_aop_targets.qty*so_aop_targets.rate) as amount
            from
            so_aop_targets
            join buyers on buyers.id=so_aop_targets.buyer_id
            where so_aop_targets.execute_month>=?
            and so_aop_targets.execute_month<=?
            group by
            so_aop_targets.buyer_id,
            buyers.name
            order by buyers.name
            ",[$from,$to])
        );
        
        

        $monCusRcvs = collect(
        \DB::select("
            select 
            m.buyer_id,
            m.buyer_name,
            sum(m.qty) as qty,
            sum(m.amount) as amount
            from
            (select 
            so_aops.buyer_id,
            buyers.name as buyer_name,
            so_aop_fabric_rcv_items.qty,
            so_aops.currency_id,
            so_aop_items.rate,
            CASE
            WHEN so_aops.currency_id=1 
            THEN so_aop_fabric_rcv_items.qty*(so_aop_items.rate*83)
            ELSE so_aop_fabric_rcv_items.qty*so_aop_items.rate
            END as amount
            from
            so_aop_fabric_rcvs
            join so_aops on  so_aops.id=so_aop_fabric_rcvs.so_aop_id
            join buyers on  buyers.id=so_aops.buyer_id
            join so_aop_fabric_rcv_items on  so_aop_fabric_rcv_items.so_aop_fabric_rcv_id=so_aop_fabric_rcvs.id
            join so_aop_refs on so_aop_refs.id=so_aop_fabric_rcv_items.so_aop_ref_id
            join so_aop_items on so_aop_items.so_aop_ref_id=so_aop_refs.id
            where so_aop_fabric_rcvs.receive_date >= ?
            and so_aop_fabric_rcvs.receive_date <= ?
            ) m 
            group by 
            m.buyer_id, 
            m.buyer_name
            order by
            m.buyer_name
            ",[$from,$to])
        );

        $monCusDlvs = collect(
        \DB::select("
            select
            m.buyer_id,
            m.buyer_name,
            sum(m.qty) as fin_qty,
            sum(m.grey_used_qty) as grey_qty,
            sum(m.amount) as amount
            from
            (
            select
            so_aop_dlvs.buyer_id,
            buyers.name as buyer_name,
            so_aop_dlv_items.so_aop_ref_id,
            so_aop_dlv_items.qty,
            so_aop_dlv_items.grey_used as grey_used_qty,
            so_aop_dlv_items.rate,
            so_aop_dlvs.currency_id,
            CASE
            WHEN so_aop_dlvs.currency_id=1 
            THEN so_aop_dlv_items.amount*83
            ELSE so_aop_dlv_items.amount
            END as amount
            from 
            so_aop_dlvs
            join buyers on buyers.id=so_aop_dlvs.buyer_id
            join so_aop_dlv_items on so_aop_dlvs.id=so_aop_dlv_items.so_aop_dlv_id
            where 
            so_aop_dlvs.issue_date >= ?
            and so_aop_dlvs.issue_date <= ?
            and so_aop_dlv_items.deleted_at is null
            and so_aop_dlvs.deleted_at is null
            ) m
            group by 
            m.buyer_id,
            m.buyer_name
            order by
            m.buyer_name
            ",[$from,$to])
        );


        $dayCusTgts = collect(
        \DB::select("
            select
            so_aop_targets.buyer_id,
            buyers.name as buyer_name,
            sum(so_aop_targets.qty) as qty,
            sum(so_aop_targets.qty*so_aop_targets.rate) as amount
            from
            so_aop_targets
            join buyers on buyers.id=so_aop_targets.buyer_id
            where so_aop_targets.execute_month>=?
            and so_aop_targets.execute_month<=?
            group by
            so_aop_targets.buyer_id,
            buyers.name
            order by buyers.name
            ",[$today,$today])
        );
        
        

        $dayCusRcvs = collect(
        \DB::select("
            select 
            m.buyer_id,
            m.buyer_name,
            sum(m.qty) as qty,
            sum(m.amount) as amount
            from
            (select 
            so_aops.buyer_id,
            buyers.name as buyer_name,
            so_aop_fabric_rcv_items.qty,
            so_aops.currency_id,
            so_aop_items.rate,
            CASE
            WHEN so_aops.currency_id=1 
            THEN so_aop_fabric_rcv_items.qty*(so_aop_items.rate*83)
            ELSE so_aop_fabric_rcv_items.qty*so_aop_items.rate
            END as amount
            from
            so_aop_fabric_rcvs
            join so_aops on  so_aops.id=so_aop_fabric_rcvs.so_aop_id
            join buyers on  buyers.id=so_aops.buyer_id
            join so_aop_fabric_rcv_items on  so_aop_fabric_rcv_items.so_aop_fabric_rcv_id=so_aop_fabric_rcvs.id
            join so_aop_refs on so_aop_refs.id=so_aop_fabric_rcv_items.so_aop_ref_id
            join so_aop_items on so_aop_items.so_aop_ref_id=so_aop_refs.id
            where so_aop_fabric_rcvs.receive_date >= ?
            and so_aop_fabric_rcvs.receive_date <= ?
            ) m 
            group by 
            m.buyer_id, 
            m.buyer_name
            order by
            m.buyer_name
            ",[$today,$today])
        );

        $dayCusDlvs = collect(
        \DB::select("
            select
            m.buyer_id,
            m.buyer_name,
            sum(m.qty) as fin_qty,
            sum(m.grey_used_qty) as grey_qty,
            sum(m.amount) as amount
            from
            (
            select
            so_aop_dlvs.buyer_id,
            buyers.name as buyer_name,
            so_aop_dlv_items.so_aop_ref_id,
            so_aop_dlv_items.qty,
            so_aop_dlv_items.grey_used as grey_used_qty,
            so_aop_dlv_items.rate,
            so_aop_dlvs.currency_id,
            CASE
            WHEN so_aop_dlvs.currency_id=1 
            THEN so_aop_dlv_items.amount*83
            ELSE so_aop_dlv_items.amount
            END as amount
            from 
            so_aop_dlvs
            join buyers on buyers.id=so_aop_dlvs.buyer_id
            join so_aop_dlv_items on so_aop_dlvs.id=so_aop_dlv_items.so_aop_dlv_id
            where 
            so_aop_dlvs.issue_date >= ?
            and so_aop_dlvs.issue_date <= ?
            and so_aop_dlv_items.deleted_at is null
            and so_aop_dlvs.deleted_at is null
            ) m
            group by 
            m.buyer_id,
            m.buyer_name
            order by
            m.buyer_name
            ",[$today,$today])
        );


        $monMktTgts = collect(
        \DB::select("
            select
            so_aop_targets.teammember_id,
            users.name as user_name,
            sum(so_aop_targets.qty) as qty,
            sum(so_aop_targets.qty*so_aop_targets.rate) as amount
            from
            so_aop_targets
            left join teammembers on teammembers.id=so_aop_targets.teammember_id
            left join users on users.id=teammembers.user_id
            where so_aop_targets.execute_month>=?
            and so_aop_targets.execute_month<=?
            group by
            so_aop_targets.teammember_id,
            users.name
            order by users.name
            ",[$from,$to])
        );
        
        

        $monMktRcvs = collect(
        \DB::select("
            select 
            m.teammember_id,
            m.user_name,
            sum(m.qty) as qty,
            sum(m.amount) as amount
            from
            (
            select 
            so_aops.teammember_id,
            users.name as user_name,
            so_aop_fabric_rcv_items.qty as qty,
            so_aops.currency_id,
            so_aop_items.rate,
            CASE
            WHEN so_aops.currency_id=1 
            THEN so_aop_fabric_rcv_items.qty*(so_aop_items.rate*83)
            ELSE so_aop_fabric_rcv_items.qty*so_aop_items.rate
            END as amount
            from
            so_aop_fabric_rcvs
            join so_aops on  so_aops.id=so_aop_fabric_rcvs.so_aop_id
            left join teammembers on  teammembers.id=so_aops.teammember_id
            left join users on users.id=teammembers.user_id
            join so_aop_fabric_rcv_items on  so_aop_fabric_rcv_items.so_aop_fabric_rcv_id=so_aop_fabric_rcvs.id
            join so_aop_refs on so_aop_refs.id=so_aop_fabric_rcv_items.so_aop_ref_id
            join so_aop_items on so_aop_items.so_aop_ref_id=so_aop_refs.id
            where so_aop_fabric_rcvs.receive_date>=?
            and so_aop_fabric_rcvs.receive_date<=?
            ) m
            group by
            m.teammember_id,
            m.user_name
            order by m.user_name
            ",[$from,$to])
        );

        $monMktDlvs = collect(
        \DB::select("
            select
            m.teammember_id,
            m.user_name,
            sum(m.qty) as fin_qty,
            sum(m.grey_used_qty) as grey_qty,
            sum(m.amount) as amount
            from
            (
            select
            so_aops.teammember_id,
            users.name as user_name,
            so_aop_dlv_items.so_aop_ref_id,
            so_aop_dlv_items.qty,
            so_aop_dlv_items.grey_used as grey_used_qty,
            so_aop_dlv_items.rate,
            so_aop_dlvs.currency_id,
            CASE
            WHEN so_aop_dlvs.currency_id=1 
            THEN so_aop_dlv_items.amount*83
            ELSE so_aop_dlv_items.amount
            END as amount
            from 
            so_aop_dlvs
            join so_aop_dlv_items on so_aop_dlvs.id=so_aop_dlv_items.so_aop_dlv_id
            join so_aop_refs on so_aop_refs.id=so_aop_dlv_items.so_aop_ref_id
            join so_aops on so_aops.id=so_aop_refs.so_aop_id
            left join teammembers on  teammembers.id=so_aops.teammember_id
            left join users on users.id=teammembers.user_id
            where 
            so_aop_dlvs.issue_date >= ?
            and so_aop_dlvs.issue_date <= ?
            and so_aop_dlv_items.deleted_at is null
            and so_aop_dlvs.deleted_at is null
            ) m
            group by 
            m.teammember_id,
            m.user_name
            order by
            m.user_name
            ",[$from,$to])
        );

        //=====

        $dayMktTgts = collect(
        \DB::select("
            select
            so_aop_targets.teammember_id,
            users.name as user_name,
            sum(so_aop_targets.qty) as qty,
            sum(so_aop_targets.qty*so_aop_targets.rate) as amount
            from
            so_aop_targets
            left join teammembers on teammembers.id=so_aop_targets.teammember_id
            left join users on users.id=teammembers.user_id
            where so_aop_targets.execute_month>=?
            and so_aop_targets.execute_month<=?
            group by
            so_aop_targets.teammember_id,
            users.name
            order by users.name
            ",[$today,$today])
        );
        
        

        $dayMktRcvs = collect(
        \DB::select("
            select 
            m.teammember_id,
            m.user_name,
            sum(m.qty) as qty,
            sum(m.amount) as amount
            from
            (
            select 
            so_aops.teammember_id,
            users.name as user_name,
            so_aop_fabric_rcv_items.qty as qty,
            so_aops.currency_id,
            so_aop_items.rate,
            CASE
            WHEN so_aops.currency_id=1 
            THEN so_aop_fabric_rcv_items.qty*(so_aop_items.rate*83)
            ELSE so_aop_fabric_rcv_items.qty*so_aop_items.rate
            END as amount
            from
            so_aop_fabric_rcvs
            join so_aops on  so_aops.id=so_aop_fabric_rcvs.so_aop_id
            left join teammembers on  teammembers.id=so_aops.teammember_id
            left join users on users.id=teammembers.user_id
            join so_aop_fabric_rcv_items on  so_aop_fabric_rcv_items.so_aop_fabric_rcv_id=so_aop_fabric_rcvs.id
            join so_aop_refs on so_aop_refs.id=so_aop_fabric_rcv_items.so_aop_ref_id
            join so_aop_items on so_aop_items.so_aop_ref_id=so_aop_refs.id
            where so_aop_fabric_rcvs.receive_date>=?
            and so_aop_fabric_rcvs.receive_date<=?
            ) m
            group by
            m.teammember_id,
            m.user_name
            order by m.user_name
            ",[$today,$today])
        );

        $dayMktDlvs = collect(
        \DB::select("
            select
            m.teammember_id,
            m.user_name,
            sum(m.qty) as fin_qty,
            sum(m.grey_used_qty) as grey_qty,
            sum(m.amount) as amount
            from
            (
            select
            so_aops.teammember_id,
            users.name as user_name,
            so_aop_dlv_items.so_aop_ref_id,
            so_aop_dlv_items.qty,
            so_aop_dlv_items.grey_used as grey_used_qty,
            so_aop_dlv_items.rate,
            so_aop_dlvs.currency_id,
            CASE
            WHEN so_aop_dlvs.currency_id=1 
            THEN so_aop_dlv_items.amount*83
            ELSE so_aop_dlv_items.amount
            END as amount
            from 
            so_aop_dlvs
            join so_aop_dlv_items on so_aop_dlvs.id=so_aop_dlv_items.so_aop_dlv_id
            join so_aop_refs on so_aop_refs.id=so_aop_dlv_items.so_aop_ref_id
            join so_aops on so_aops.id=so_aop_refs.so_aop_id
            left join teammembers on  teammembers.id=so_aops.teammember_id
            left join users on users.id=teammembers.user_id
            where 
            so_aop_dlvs.issue_date >= ?
            and so_aop_dlvs.issue_date <= ?
            and so_aop_dlv_items.deleted_at is null
            and so_aop_dlvs.deleted_at is null
            ) m
            group by 
            m.teammember_id,
            m.user_name
            order by
            m.user_name
            ",[$today,$today])
        );
        $this->makeChart($monCusTgts,$monCusRcvs,$monCusDlvs,$dayCusTgts,$dayCusRcvs,$dayCusDlvs,$monMktTgts,$monMktRcvs,$monMktDlvs,$dayMktTgts,$dayMktRcvs,$dayMktDlvs);
    }
}