<?php
namespace App\Http\Controllers\Report\Subcontract\Dyeing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRcvRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\BuyerBranchRepository;

class SubConDyeingTargetController extends Controller
{

  private $soknityarnrcv;
  private $itemaccount;
  private $autoyarn;
  private $buyerbranch;

  public function __construct(
    SoKnitYarnRcvRepository $soknityarnrcv,
    ItemAccountRepository $itemaccount,
    AutoyarnRepository $autoyarn,
    BuyerBranchRepository $buyerbranch
  )
  {
    $this->soknityarnrcv=$soknityarnrcv;
    $this->itemaccount=$itemaccount;
    $this->autoyarn=$autoyarn;
    $this->buyerbranch=$buyerbranch;
    $this->middleware('auth');
    //$this->middleware('permission:view.prodgmtdailyreports',   ['only' => ['create', 'index','show']]);
  }
  public function index() {
    $from=date('Y-m')."-01";
    $to=date('Y-m-t',strtotime($from));
    return Template::loadView('Report.Subcontract.Dyeing.SubConDyeingTarget',['from'=>$from,'to'=>$to]);
  }
  public function reportData() {
        

        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $results = collect(
        \DB::select("
        select 
        so_dyeing_targets.company_id,
        so_dyeing_targets.buyer_id,
        so_dyeing_targets.execute_month,
        so_dyeing_targets.target_date,
        sum(so_dyeing_targets.qty) as qty,
        avg(so_dyeing_targets.rate) as rate,
        buyers.name as buyer_name,
        buyerbranches.contact_person,
        buyerbranches.designation,
        buyerbranches.email,
        buyers.cell_no,
        buyerbranches.address,
        companies.code as company_name,
        fabric_rcv.qty as receive_qty,
        fabric_dlv.fin_qty,
        fabric_dlv.grey_used_qty,
        fabric_dlv.grey_used_amount,
        teamleaders.name as team_leader_name
        from so_dyeing_targets
        join buyers on buyers.id=so_dyeing_targets.buyer_id
        join companies on companies.id=so_dyeing_targets.company_id
        left join teams on teams.id=buyers.team_id
        left join( 
        select 
        count(buyer_branches.id) as tid,
        buyer_branches.buyer_id,
        buyer_branches.contact_person,
        buyer_branches.email,
        buyer_branches.designation,
        buyer_branches.address
        from buyer_branches
        group by 
        buyer_branches.buyer_id,
        buyer_branches.contact_person,
        buyer_branches.email,
        buyer_branches.designation,
        buyer_branches.address
        ) buyerbranches on buyerbranches.buyer_id=buyers.id

        left join( 
        select
        users.name,
        teammembers.team_id
        from 
        teammembers
        left join users on users.id=teammembers.user_id
        where teammembers.type_id=2
        group by 
        users.name,
        teammembers.team_id
        ) teamleaders on teamleaders.team_id=teams.id

        left join (
        select
        so_dyeings.buyer_id,
        sum(so_dyeing_fabric_rcv_items.qty) as qty,
        avg(so_dyeing_fabric_rcv_items.rate) as rate,
        sum(so_dyeing_fabric_rcv_items.amount) as amount
        from 
        so_dyeing_fabric_rcvs
        join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcvs.id=so_dyeing_fabric_rcv_items.so_dyeing_fabric_rcv_id
        join so_dyeings on so_dyeings.id=so_dyeing_fabric_rcvs.so_dyeing_id
        where 
        so_dyeing_fabric_rcvs.receive_date >= ?
        and so_dyeing_fabric_rcvs.receive_date <= ?
        and so_dyeing_fabric_rcv_items.deleted_at is null
        and so_dyeing_fabric_rcvs.deleted_at is null
        and so_dyeings.deleted_at is null
        group by 
        so_dyeings.buyer_id
        ) fabric_rcv on fabric_rcv.buyer_id=buyers.id

        left join (
        select
        m.buyer_id, 
        sum(m.fin_qty) as fin_qty,
        sum(m.grey_used_qty) as grey_used_qty,
        sum(m.grey_used_amount) as grey_used_amount
        from 
        (
            select
            so_dyeing_dlvs.buyer_id,
            so_dyeing_dlv_items.so_dyeing_ref_id,
            sum(so_dyeing_dlv_items.qty) as fin_qty,
            avg(so_dyeing_dlv_items.rate) as fin_rate,
            sum(so_dyeing_dlv_items.amount) as fin_amount,
            sum(so_dyeing_dlv_items.grey_used) as grey_used_qty,
            greyusedrate.rate as grey_used_rate,
            sum(so_dyeing_dlv_items.grey_used)*greyusedrate.rate as grey_used_amount
            from 
            so_dyeing_dlvs
            join so_dyeing_dlv_items on so_dyeing_dlvs.id=so_dyeing_dlv_items.so_dyeing_dlv_id
            join(
            select 
            so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
            avg(so_dyeing_fabric_rcv_items.rate) as rate
            from so_dyeing_fabric_rcv_items
            where so_dyeing_fabric_rcv_items.qty>0
            and so_dyeing_fabric_rcv_items.rate >0 
            group by 
            so_dyeing_fabric_rcv_items.so_dyeing_ref_id
            )greyusedrate on greyusedrate.so_dyeing_ref_id=so_dyeing_dlv_items.so_dyeing_ref_id
            where 
            so_dyeing_dlvs.issue_date >= ?
            and so_dyeing_dlvs.issue_date <= ?
            and so_dyeing_dlv_items.deleted_at is null
            and so_dyeing_dlvs.deleted_at is null
            group by 
            so_dyeing_dlvs.buyer_id,
            so_dyeing_dlv_items.so_dyeing_ref_id,
            greyusedrate.rate
            ) m group by m.buyer_id
        ) fabric_dlv on fabric_dlv.buyer_id=buyers.id

        where 
        so_dyeing_targets.execute_month >= ? and
        so_dyeing_targets.execute_month <= ?
        group by 
        so_dyeing_targets.company_id,
        so_dyeing_targets.buyer_id,
        so_dyeing_targets.execute_month,
        so_dyeing_targets.target_date,
        buyers.name,
        buyerbranches.contact_person,
        buyerbranches.designation,
        buyerbranches.email,
        buyers.cell_no,
        buyerbranches.address,
        companies.code,
        fabric_rcv.qty,
        fabric_dlv.fin_qty,
        fabric_dlv.grey_used_qty,
        fabric_dlv.grey_used_amount,
        teamleaders.name

        ", [$date_from,$date_to,$date_from,$date_to,$date_from,$date_to])
        )
        ->sortByDesc('qty')
        ->values()
        ->map(function($results){
        	
        	$results->amount=$results->qty*$results->rate;
            $results->receive_per=0;
            if($results->qty){
            $results->receive_per=number_format(($results->receive_qty/$results->qty)*100,0);
            }
        	$results->bal_qty=number_format($results->qty-$results->receive_qty,2);
            $results->qty=number_format($results->qty,2);
        	$results->rate=number_format($results->rate,2);
        	$results->amount=number_format($results->amount,2);
            $results->execute_month=date('d-M-Y',strtotime($results->execute_month));
            $results->target_date=date('d-M-Y',strtotime($results->target_date));
            $results->receive_qty=number_format($results->receive_qty,2);
            $results->fin_qty=number_format($results->fin_qty,2);
            $results->grey_used_qty=number_format($results->grey_used_qty,2);
            $results->grey_used_amount=number_format($results->grey_used_amount,2);
            


            return $results;
        });
        
        echo json_encode($results);
    }

    public function getBuyerInfo(){
        $buyer_id=request('buyer_id',0);

        $results = collect(\DB::select("
        select 
        buyers.name as buyer_name,
        buyerbranches.contact_person,
        buyerbranches.designation,
        buyerbranches.email,
        buyers.cell_no,
        buyerbranches.address
        
        from buyers
        left join( 
        select 
        count(buyer_branches.id) as tid,
        buyer_branches.buyer_id,
        buyer_branches.contact_person,
        buyer_branches.email,
        buyer_branches.designation,
        buyer_branches.address
        from buyer_branches
        group by 
        buyer_branches.buyer_id,
        buyer_branches.contact_person,
        buyer_branches.email,
        buyer_branches.designation,
        buyer_branches.address
        ) buyerbranches on buyerbranches.buyer_id=buyers.id
        where buyers.id = ? 
        ", [$buyer_id])
        );
        echo json_encode($results);
    }
}
