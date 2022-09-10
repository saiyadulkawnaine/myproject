<?php
namespace App\Http\Controllers\Report\Subcontract\Kniting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRcvRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\BuyerBranchRepository;

class SubConKnitingTargetController extends Controller
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
    return Template::loadView('Report.Subcontract.Kniting.SubConKnitingTarget',['from'=>$from,'to'=>$to]);
  }
  public function reportData() {
        

        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $results = collect(
        \DB::select("
        select 
        so_knit_targets.company_id,
        so_knit_targets.buyer_id,
        so_knit_targets.execute_month,
        so_knit_targets.target_date,
        sum(so_knit_targets.qty) as qty,
        avg(so_knit_targets.rate) as rate,
        buyers.name as buyer_name,
        buyerbranches.contact_person,
        buyerbranches.designation,
        buyerbranches.email,
        buyers.cell_no,
        buyerbranches.address,
        companies.code as company_name,
        yarn_rcv.qty as receive_qty,
        yarn_dlv.qty as fin_qty,
        yarn_used.qty as grey_used_qty,
        yarn_used.amount as grey_used_amount,
        teamleaders.name as team_leader_name
        from so_knit_targets
        join buyers on buyers.id=so_knit_targets.buyer_id
        join companies on companies.id=so_knit_targets.company_id
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
        so_knits.buyer_id,
        sum(so_knit_yarn_rcv_items.qty) as qty,
        sum(so_knit_yarn_rcv_items.amount) as amount
        from 
        so_knit_yarn_rcvs
        join so_knit_yarn_rcv_items on so_knit_yarn_rcvs.id=so_knit_yarn_rcv_items.so_knit_yarn_rcv_id
        join so_knits on so_knits.id=so_knit_yarn_rcvs.so_knit_id
        where 
        so_knit_yarn_rcvs.receive_date >= ?
        and so_knit_yarn_rcvs.receive_date <= ?
        and so_knit_yarn_rcv_items.deleted_at is null
        and so_knit_yarn_rcvs.deleted_at is null
        and so_knits.deleted_at is null
        group by 
        so_knits.buyer_id
        ) yarn_rcv on yarn_rcv.buyer_id=buyers.id

        left join (
        select
        so_knit_dlvs.buyer_id,
        sum(so_knit_dlv_item_yarns.qty) as qty,
        sum(so_knit_dlv_item_yarns.qty*so_knit_yarn_rcv_items.rate) as amount
        from 
        so_knit_dlvs
        join so_knit_dlv_items on so_knit_dlvs.id=so_knit_dlv_items.so_knit_dlv_id
        join so_knit_dlv_item_yarns on so_knit_dlv_item_yarns.so_knit_dlv_item_id=so_knit_dlv_items.id
        join so_knit_yarn_rcv_items on so_knit_yarn_rcv_items.id=so_knit_dlv_item_yarns.so_knit_yarn_rcv_item_id
        where 
        so_knit_dlvs.issue_date >= ?
        and so_knit_dlvs.issue_date <= ?
        and so_knit_dlvs.deleted_at is null
        and so_knit_dlv_items.deleted_at is null
        and so_knit_dlv_item_yarns.deleted_at is null
        group by 
        so_knit_dlvs.buyer_id
        ) yarn_used on yarn_used.buyer_id=buyers.id

        left join (
        select
        so_knit_dlvs.buyer_id,
        sum(so_knit_dlv_items.qty) as qty
        from 
        so_knit_dlvs
        join so_knit_dlv_items on so_knit_dlvs.id=so_knit_dlv_items.so_knit_dlv_id
        where 
        so_knit_dlvs.issue_date >= ?
        and so_knit_dlvs.issue_date <= ?
        and so_knit_dlvs.deleted_at is null
        and so_knit_dlv_items.deleted_at is null
        group by 
        so_knit_dlvs.buyer_id
        ) yarn_dlv on yarn_dlv.buyer_id=buyers.id

        where so_knit_targets.execute_month >= ? and
        so_knit_targets.execute_month <= ?
        group by
        so_knit_targets.company_id,
        so_knit_targets.buyer_id,
        so_knit_targets.execute_month,
        so_knit_targets.target_date,
        buyers.name,
        buyerbranches.contact_person,
        buyerbranches.designation,
        buyerbranches.email,
        buyers.cell_no,
        buyerbranches.address,
        companies.code,
        yarn_rcv.qty,
        yarn_dlv.qty,
        yarn_used.qty,
        yarn_used.amount,
        teamleaders.name
        ", [$date_from,$date_to,$date_from,$date_to,$date_from,$date_to,$date_from,$date_to])
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
