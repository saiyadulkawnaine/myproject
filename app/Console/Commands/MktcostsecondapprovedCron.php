<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\SendMailMktcostApproved;
use Mail;
use Illuminate\Support\Facades\DB;

class MktcostsecondapprovedCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mktcostsecondapproved:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marketing Cost Second Approved';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        \Log::info("Cron is working fine!");
        $row = DB::table("mkt_costs")
        ->select("mkt_costs.*",
        "buyers.code as buyer_name",
        "styles.style_ref",
        "styles.style_description",
        "styles.flie_src",
        "seasons.name as season_name",
        "productdepartments.department_name",
        "uoms.code as uom_code",
        'mkt_cost_quote_prices.quote_price as price',
        'mkt_cost_quote_prices.submission_date',
        'mkt_cost_quote_prices.confirm_date',
        'mkt_cost_quote_prices.refused_date',
        'mkt_cost_quote_prices.cancel_date',
        'mkt_cost_target_prices.target_price as  t_price',
        'users.name as team_member',
        'teams.name as team_name',
        DB::raw("(SELECT SUM(mkt_cost_fabrics.amount) FROM mkt_cost_fabrics
        WHERE mkt_cost_fabrics.mkt_cost_id = mkt_costs.id
        GROUP BY mkt_cost_fabrics.mkt_cost_id) as fab_amount"),

        DB::raw("(SELECT SUM(mkt_cost_yarns.amount) FROM mkt_cost_yarns
        WHERE mkt_cost_yarns.mkt_cost_id = mkt_costs.id
        GROUP BY mkt_cost_yarns.mkt_cost_id) as yarn_amount"),

        DB::raw("(SELECT SUM(mkt_cost_fabric_prods.amount) FROM mkt_cost_fabric_prods
        WHERE mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
        GROUP BY mkt_cost_fabric_prods.mkt_cost_id) as prod_amount"),

        DB::raw("(SELECT SUM(mkt_cost_trims.amount) FROM mkt_cost_trims
        WHERE mkt_cost_trims.mkt_cost_id = mkt_costs.id
        GROUP BY mkt_cost_trims.mkt_cost_id) as trim_amount"),

        DB::raw("(SELECT SUM(mkt_cost_embs.amount) FROM mkt_cost_embs
        WHERE mkt_cost_embs.mkt_cost_id = mkt_costs.id
        GROUP BY mkt_cost_embs.mkt_cost_id) as emb_amount"),

        DB::raw("(SELECT SUM(mkt_cost_cms.amount) FROM mkt_cost_cms
        WHERE mkt_cost_cms.mkt_cost_id = mkt_costs.id
        GROUP BY mkt_cost_cms.mkt_cost_id) as cm_amount"),

        DB::raw("(SELECT SUM(mkt_cost_others.amount) FROM mkt_cost_others
        WHERE mkt_cost_others.mkt_cost_id = mkt_costs.id
        GROUP BY mkt_cost_others.mkt_cost_id) as other_amount"),

        DB::raw("(SELECT SUM(mkt_cost_commercials.amount) FROM mkt_cost_commercials
        WHERE mkt_cost_commercials.mkt_cost_id = mkt_costs.id
        GROUP BY mkt_cost_commercials.mkt_cost_id) as commercial_amount"),

        DB::raw("(SELECT SUM(mkt_cost_profits.amount) FROM mkt_cost_profits
        WHERE mkt_cost_profits.mkt_cost_id = mkt_costs.id
        GROUP BY mkt_cost_profits.mkt_cost_id) as profit_amount"),

        DB::raw("(SELECT SUM(mkt_cost_commissions.amount) FROM mkt_cost_commissions
        WHERE mkt_cost_commissions.mkt_cost_id = mkt_costs.id
        GROUP BY mkt_cost_commissions.mkt_cost_id) as commission_amount"),

        DB::raw("(SELECT SUM(mkt_cost_commissions.rate) FROM mkt_cost_commissions
        WHERE mkt_cost_commissions.mkt_cost_id = mkt_costs.id
        GROUP BY mkt_cost_commissions.mkt_cost_id) as commission_rate")
        )
        ->join('styles',function($join){
            $join->on('mkt_costs.style_id','=','styles.id');
        })
        ->join('buyers',function($join){
            $join->on('styles.buyer_id','=','buyers.id');
        })
        ->leftJoin('seasons',function($join){
            $join->on('styles.season_id','=','seasons.id');
        })
        ->leftJoin('productdepartments',function($join){
            $join->on('styles.productdepartment_id','=','productdepartments.id');
        })
        ->leftJoin('uoms',function($join){
            $join->on('styles.uom_id','=','uoms.id');
        })
        ->leftJoin('teammembers',function($join){
            $join->on('teammembers.id','=','styles.teammember_id');
        })
        ->leftJoin('users',function($join){
            $join->on('users.id','=','teammembers.user_id');
        })
        ->leftJoin('teams',function($join){
            $join->on('teams.id','=','styles.team_id');
        })
        ->leftJoin('mkt_cost_quote_prices',function($join){
            $join->on('mkt_costs.id','=','mkt_cost_quote_prices.mkt_cost_id');
        })
        ->leftJoin('mkt_cost_target_prices',function($join){
            $join->on('mkt_costs.id','=','mkt_cost_target_prices.mkt_cost_id');
        })
        ->when(request('buyer_id'), function ($q) {
            return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
        })
        ->when(request('team_id'), function ($q) {
            return $q->where('styles.team_id', '=', request('team_id', 0));
        })
        ->when(request('teammember_id'), function ($q) {
            return $q->where('styles.teammember_id', '=', request('teammember_id', 0));
        })
        ->when(request('style_ref'), function ($q) {
            return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
        })
        ->when(request('date_from'), function ($q) {
            return $q->where('mkt_costs.est_ship_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
            return $q->where('mkt_costs.est_ship_date', '<=',request('date_to', 0));
        })
        ->when(request('confirm_from'), function ($q) {
            return $q->where('mkt_cost_quote_prices.confirm_date', '>=',request('confirm_from', 0));
        })
        ->when(request('confirm_to'), function ($q) {
            return $q->where('mkt_cost_quote_prices.confirm_date', '<=',request('confirm_to', 0));
        })
        ->when(request('costing_from'), function ($q) {
            return $q->where('mkt_costs.quot_date', '>=',request('costing_from', 0));
        })
        ->when(request('costing_to'), function ($q) {
            return $q->where('mkt_costs.quot_date', '<=',request('costing_to', 0));
        })
        /*->when($approval_type_id, function ($q) use ($approval_type_id){
            if($approval_type_id==1){
                return $q->whereNull('mkt_costs.first_approved_at');
            }
            if($approval_type_id==2){
            return $q->whereNotNull('mkt_costs.first_approved_at')->whereNull('mkt_costs.second_approved_at');
            }
            if($approval_type_id==3){
            return $q->whereNotNull('mkt_costs.second_approved_at')->whereNull('mkt_costs.third_approved_at');
            }
            if($approval_type_id==10){
            return $q->whereNotNull('mkt_costs.third_approved_at')
            ->whereNull('mkt_costs.final_approved_at');
            }
        })*/
        ->whereNull('mkt_costs.third_approved_at')
        ->whereNotNull('mkt_costs.second_approved_at')
        ->whereNotNull('mkt_costs.confirmed_at')
        ->whereDate('mkt_costs.quot_date', '>', '2021-12-31')
        ->orderBy('mkt_costs.id','desc')
        ->get()
        ->map(function($row){
        $row->amount=number_format($row->price*$row->offer_qty,2,'.',',');
        $row->offer_qty=number_format($row->offer_qty,0,'.',',');
        $row->price=number_format($row->price,4,'.',',');
        $row->est_ship_date=date("d-M-Y",strtotime($row->est_ship_date));
        $row->quot_date=date("d-M-Y",strtotime($row->quot_date));
        $row->price_bfr_commission=$row->fab_amount+$row->yarn_amount+$row->prod_amount+$row->trim_amount+$row->emb_amount+$row->cm_amount+$row->other_amount+$row->commercial_amount+$row->profit_amount;
        $row->price_aft_commission=number_format(($row->fab_amount+$row->yarn_amount+$row->prod_amount+$row->trim_amount+$row->emb_amount+$row->cm_amount+$row->other_amount+$row->commercial_amount+$row->profit_amount+$row->commission_amount)/$row->costing_unit_id,4,'.',',');
        $commission_on_quoted_price_dzn=((($row->price*$row->costing_unit_id))*$row->commission_rate)/100;
        $commission_on_quoted_price_pcs=$commission_on_quoted_price_dzn/$row->costing_unit_id;
        $row->commission_on_quoted_price_dzn=number_format($commission_on_quoted_price_dzn,4,'.',',');
        $row->commission_on_quoted_price_pcs=number_format($commission_on_quoted_price_pcs,4,'.',',');

        $row->total_cost=$row->fab_amount+$row->yarn_amount+$row->prod_amount+$row->trim_amount+$row->emb_amount+$row->cm_amount+$row->other_amount+$row->commercial_amount+$commission_on_quoted_price_dzn;

        $cost_per_pcs=$row->total_cost/$row->costing_unit_id;
        $row->cost_per_pcs=number_format($cost_per_pcs,4,'.',',');

        $row->comments=($row->cost_per_pcs > $row->price)?"Less Than Cost":"";
        $row->cm=number_format(($row->price*$row->costing_unit_id)-($row->total_cost-$row->cm_amount),4,'.',',');
        return $row;

        });
        
        
        Mail::to(['monzu@lithegroup.com','md@lithegroup.com','siddiquee@lithegroup.com'])->send(new SendMailMktcostApproved($row));
        //$this->info('Demo:Cron Cummand Run successfully!');

        /*$contracts = Contract::where('end_date', Carbon::parse(today())->toDateString())->get();
        foreach ($contracts as $contract) {
            Mail::to(Setting::first()->value('email'))->send(new SendMailTarget($contract));
        }*/
    }
}
