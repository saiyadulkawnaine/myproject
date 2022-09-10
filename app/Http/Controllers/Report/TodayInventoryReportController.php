<?php
namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnItemRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabItemRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;

class TodayInventoryReportController extends Controller
{
	private $company;
    private $supplier;
    private $invyarnitem;
    private $itemaccount;
    private $store;
    private $invgreyfabitem;
    private $gmtspart;
	public function __construct(
		CompanyRepository $company,
        SupplierRepository $supplier,
        InvYarnItemRepository $invyarnitem,
        ItemAccountRepository $itemaccount,
        StoreRepository $store,
        InvGreyFabItemRepository $invgreyfabitem,
        GmtspartRepository $gmtspart

	)
    {
        $this->company = $company;
        $this->supplier = $supplier;
        $this->invyarnitem=$invyarnitem;
        $this->itemaccount=$itemaccount;
        $this->store=$store;
        $this->invgreyfabitem=$invgreyfabitem;
        $this->gmtspart=$gmtspart;
		$this->middleware('auth');
		//$this->middleware('permission:view.prodgmtcapacityachievereports',   ['only' => ['create', 'index','show']]);
    }

    public function index() {
    	$date_to=date('Y-m-d');
    	return Template::loadView('Report.TodayInventoryReport',['date_to'=>$date_to]);
    }
    
    public function reportData() {
    	$trans_date=request('trans_date',0);
        //$last_trans_date=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $trans_date) ) ));
        $date_from=$trans_date;
        $date_to=$trans_date;
        $yarn_rcv_open = collect(\DB::select("
            select 
            sum(inv_yarn_transactions.store_qty) as qty,
            sum(inv_yarn_transactions.store_amount) as amount
            from inv_yarn_rcv_items
            join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
            join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
            join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
            where inv_rcvs.receive_date < '".$date_from."'
            and inv_yarn_transactions.deleted_at is null
            and inv_yarn_rcv_items.deleted_at is null
            and inv_rcvs.deleted_at is null
            and inv_yarn_transactions.trans_type_id=1"
        ))
        ->first();

         $yarn_isu_open = collect(\DB::select("
            select
            sum(inv_yarn_transactions.store_qty) as qty,
            sum(inv_yarn_transactions.store_amount) as amount
            from inv_yarn_isu_items
            join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_isu_item_id=inv_yarn_isu_items.id
            join inv_isus on inv_isus.id=inv_yarn_isu_items.inv_isu_id
            where inv_isus.issue_date < '".$date_from."' 
            and inv_yarn_transactions.deleted_at is null
            and inv_yarn_isu_items.deleted_at is null
            and inv_isus.deleted_at is null
            and inv_yarn_transactions.trans_type_id=2"
        ))
        ->first();


        $yarn_rcv = collect(\DB::select("
            select 
            sum(inv_yarn_transactions.store_qty) as qty,
            sum(inv_yarn_transactions.store_amount) as amount
            from inv_yarn_rcv_items
            join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
            join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
            join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
            where inv_rcvs.receive_date >= '".$date_from."'
            and inv_rcvs.receive_date <= '".$date_to."'
            and inv_yarn_transactions.deleted_at is null
            and inv_yarn_rcv_items.deleted_at is null
            and inv_rcvs.deleted_at is null
            and inv_yarn_transactions.trans_type_id=1"
        ))
        ->first();

         $yarn_isu = collect(\DB::select("
            select
            sum(inv_yarn_transactions.store_qty) as qty,
            sum(inv_yarn_transactions.store_amount) as amount
            from inv_yarn_isu_items
            join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_isu_item_id=inv_yarn_isu_items.id
            join inv_isus on inv_isus.id=inv_yarn_isu_items.inv_isu_id
            where inv_isus.issue_date >= '".$date_from."' 
            and inv_isus.issue_date <= '".$date_to."'
            and inv_yarn_transactions.deleted_at is null
            and inv_yarn_isu_items.deleted_at is null
            and inv_isus.deleted_at is null
            and inv_yarn_transactions.trans_type_id=2"
        ))
        ->first();

        $yarn_opening_qty=$yarn_rcv_open->qty+$yarn_isu_open->qty;
        $yarn_opening_amount=$yarn_rcv_open->amount-$yarn_isu_open->amount;

        $yarn_stock_qty=$yarn_opening_qty+($yarn_rcv->qty+$yarn_isu->qty);
        $yarn_stock_amount=$yarn_opening_amount+($yarn_rcv->amount-$yarn_isu->amount);

        $yarn_arr=[
            'yarn_opening_qty'=>number_format($yarn_opening_qty,0),
            'yarn_opening_amount'=>number_format($yarn_opening_amount,0),
            'yarn_rcv_qty'=>number_format($yarn_rcv->qty,0),
            'yarn_rcv_amount'=>number_format($yarn_rcv->amount,0),
            'yarn_isu_qty'=>number_format($yarn_isu->qty*-1,0),
            'yarn_isu_amount'=>number_format($yarn_isu->amount,0),
            'yarn_stock_qty'=>number_format($yarn_stock_qty,0),
            'yarn_stock_amount'=>number_format($yarn_stock_amount,0),
        ];


        $dyechem = collect(\DB::select("
            select
            companies.code as company_code, 
            m.company_id,
            m.company_id as id,
            sum(m.open_rcv_amount) as open_rcv_amount,
            sum(m.open_isu_amount) as open_isu_amount,
            sum(m.rcv_amount) as rcv_amount,
            sum(m.isu_amount) as isu_amount,
            sum(m.open_rcv_qty) as open_rcv_qty,
            sum(m.open_isu_qty) as open_isu_qty,
            sum(m.rcv_qty) as rcv_qty,
            sum(m.isu_qty) as isu_qty
            from 
            (
            select
            all_rcv.company_id,
            item_accounts.id,
            open_rcv.qty as open_rcv_qty,
            open_rcv.amount as open_rcv_amount,
            open_isu.qty as open_isu_qty,
            open_isu.amount as open_isu_amount,
            rcv.qty as rcv_qty,
            rcv.amount as rcv_amount,
            isu.qty as isu_qty,
            isu.amount as isu_amount
            from item_accounts
            left join itemclasses on itemclasses.id= item_accounts.itemclass_id 
            inner join itemcategories on itemcategories.id = item_accounts.itemcategory_id
            inner join uoms on uoms.id = item_accounts.uom_id  

            join (
            select 
            inv_dye_chem_transactions.company_id,
            inv_dye_chem_rcv_items.item_account_id,
            sum(inv_dye_chem_transactions.store_qty) as qty,
            sum(inv_dye_chem_transactions.store_amount) as amount
            from inv_dye_chem_rcv_items
            join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
            join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
            join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
            where inv_rcvs.receive_date<='".$date_to."' 
            and inv_dye_chem_transactions.deleted_at is null
            and inv_dye_chem_rcv_items.deleted_at is null
            and inv_rcvs.deleted_at is null
            and inv_dye_chem_transactions.trans_type_id=1
            group by 
            inv_dye_chem_rcv_items.item_account_id,
            inv_dye_chem_transactions.company_id
            ) all_rcv on all_rcv.item_account_id = item_accounts.id
            left join(

            select 
            inv_dye_chem_transactions.company_id,
            inv_dye_chem_rcv_items.item_account_id,
            sum(inv_dye_chem_transactions.store_qty) as qty,
            sum(inv_dye_chem_transactions.store_amount) as amount
            from inv_dye_chem_rcv_items
            join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
            join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
            join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
            where inv_rcvs.receive_date  < '".$date_from."' 

            and inv_dye_chem_transactions.deleted_at is null
            and inv_dye_chem_rcv_items.deleted_at is null
            and inv_rcvs.deleted_at is null
            and inv_dye_chem_transactions.trans_type_id=1
            group by 
            inv_dye_chem_transactions.company_id,
            inv_dye_chem_rcv_items.item_account_id
            ) open_rcv on open_rcv.item_account_id=all_rcv.item_account_id and open_rcv.company_id=all_rcv.company_id 

            left join(
            select
            inv_dye_chem_transactions.company_id,
            inv_dye_chem_isu_items.item_account_id,
            sum(inv_dye_chem_transactions.store_qty) as qty,
            sum(inv_dye_chem_transactions.store_amount) as amount
            from inv_dye_chem_isu_items
            join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
            join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
            where inv_isus.issue_date < '".$date_from."' 
            and inv_dye_chem_transactions.deleted_at is null
            and inv_dye_chem_isu_items.deleted_at is null
            and inv_isus.deleted_at is null
            and inv_dye_chem_transactions.trans_type_id=2
            group by 
            inv_dye_chem_transactions.company_id,
            inv_dye_chem_isu_items.item_account_id
            ) open_isu on open_isu.item_account_id=all_rcv.item_account_id and open_isu.company_id=all_rcv.company_id 


            left join (
            select 
            inv_dye_chem_transactions.company_id,
            inv_dye_chem_rcv_items.item_account_id,
            sum(inv_dye_chem_transactions.store_qty) as qty,
            sum(inv_dye_chem_transactions.store_amount) as amount
            from inv_dye_chem_rcv_items
            join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
            join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
            join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
            where inv_rcvs.receive_date >= '".$date_from."' 
            and inv_rcvs.receive_date <= '".$date_to."' 
            and inv_dye_chem_transactions.deleted_at is null
            and inv_dye_chem_rcv_items.deleted_at is null
            and inv_rcvs.deleted_at is null
            and inv_dye_chem_transactions.trans_type_id=1
            group by 
            inv_dye_chem_transactions.company_id,
            inv_dye_chem_rcv_items.item_account_id
            ) rcv on rcv.item_account_id=all_rcv.item_account_id and rcv.company_id=all_rcv.company_id 
            left join (

            select 
            inv_dye_chem_transactions.company_id,
            inv_dye_chem_isu_items.item_account_id,
            sum(inv_dye_chem_transactions.store_qty) as qty,
            sum(inv_dye_chem_transactions.store_amount) as amount
            from inv_dye_chem_isu_items
            join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
            join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
            where inv_isus.issue_date >='".$date_from."' 
            and inv_isus.issue_date <='".$date_to."' 
            and inv_dye_chem_transactions.deleted_at is null
            and inv_dye_chem_isu_items.deleted_at is null
            and inv_isus.deleted_at is null
            and inv_dye_chem_transactions.trans_type_id=2
            group by 
            inv_dye_chem_transactions.company_id,
            inv_dye_chem_isu_items.item_account_id
            ) isu on isu.item_account_id=all_rcv.item_account_id and isu.company_id=all_rcv.company_id  
            where itemcategories.identity in (7, 8) and item_accounts.deleted_at is null
            ) m 
            join companies on companies.id=m.company_id
            where companies.id in(5,6)
            group by m.company_id,companies.code"
        ))
        ->map(function($dyechem){
            $dyechem->dyechem_opening_qty=$dyechem->open_rcv_qty+$dyechem->open_isu_qty;
            $dyechem->dyechem_opening_amount=$dyechem->open_rcv_amount-($dyechem->open_isu_amount);

            $dyechem->dyechem_rcv_qty=$dyechem->rcv_qty;
            $dyechem->dyechem_rcv_amount=$dyechem->rcv_amount;
            $dyechem->dyechem_isu_qty=$dyechem->isu_qty;
            $dyechem->dyechem_isu_amount=$dyechem->isu_amount;
            $dyechem->dyechem_stock_qty=$dyechem->dyechem_opening_qty+($dyechem->rcv_qty+$dyechem->isu_qty);
            $dyechem->dyechem_stock_amount=($dyechem->dyechem_opening_amount+$dyechem->rcv_amount)-($dyechem->isu_amount);

            $dyechem->dyechem_opening_qty=number_format($dyechem->dyechem_opening_qty,0);
            $dyechem->dyechem_opening_amount=number_format($dyechem->dyechem_opening_amount,0);

            $dyechem->dyechem_rcv_qty=number_format($dyechem->dyechem_rcv_qty,0);
            $dyechem->dyechem_rcv_amount=number_format($dyechem->dyechem_rcv_amount,0);

            $dyechem->dyechem_isu_qty=number_format($dyechem->dyechem_isu_qty*-1,0);
            $dyechem->dyechem_isu_amount=number_format($dyechem->dyechem_isu_amount,0);

            $dyechem->dyechem_stock_qty=number_format($dyechem->dyechem_stock_qty,0);
            $dyechem->dyechem_stock_amount=number_format($dyechem->dyechem_stock_amount,0);
            return $dyechem;

        });


        $greyfab = collect(\DB::select("
        select
        companies.code as company_code, 
        m.company_id,
        m.company_id as id,
        sum(m.open_rcv_amount) as open_rcv_amount,
        sum(m.open_isu_amount) as open_isu_amount,
        sum(m.rcv_amount) as rcv_amount,
        sum(m.isu_amount) as isu_amount,
        sum(m.open_rcv_qty) as open_rcv_qty,
        sum(m.open_isu_qty) as open_isu_qty,
        sum(m.rcv_qty) as rcv_qty,
        sum(m.isu_qty) as isu_qty
        from 
        (
        select
        inv_grey_fab_items.id,
        all_rcv.company_id,
        open_rcv.qty as open_rcv_qty,
        open_rcv.amount as open_rcv_amount,
        open_isu.qty as open_isu_qty,
        open_isu.amount as open_isu_amount,
        rcv.qty as rcv_qty,
        rcv.amount as rcv_amount,
        isu.qty as isu_qty,
        isu.amount as isu_amount

        from inv_grey_fab_items
        join autoyarns on autoyarns.id=inv_grey_fab_items.autoyarn_id
        join gmtsparts on gmtsparts.id=inv_grey_fab_items.gmtspart_id
        left join colorranges on colorranges.id=inv_grey_fab_items.colorrange_id
        join(

        select 
        inv_grey_fab_transactions.company_id,
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_rcv_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
        join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
        where inv_rcvs.receive_date <= '".$date_to."' 
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=1
        group by 
        inv_grey_fab_transactions.company_id,
        inv_grey_fab_rcv_items.inv_grey_fab_item_id
        ) all_rcv on all_rcv.inv_grey_fab_item_id=inv_grey_fab_items.id

        left join(
        select 
        inv_grey_fab_transactions.company_id,
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_rcv_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
        join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
        where inv_rcvs.receive_date < '".$date_from."'  
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=1
        group by 
        inv_grey_fab_transactions.company_id,
        inv_grey_fab_rcv_items.inv_grey_fab_item_id
        ) open_rcv on open_rcv.inv_grey_fab_item_id=all_rcv.inv_grey_fab_item_id and open_rcv.company_id=all_rcv.company_id

        left join(
        select
        inv_grey_fab_transactions.company_id,
        inv_grey_fab_isu_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_isu_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
        join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
        where inv_isus.issue_date < '".$date_from."' 
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_isu_items.deleted_at is null
        and inv_isus.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=2
        group by 
        inv_grey_fab_transactions.company_id,
        inv_grey_fab_isu_items.inv_grey_fab_item_id
        ) open_isu on open_isu.inv_grey_fab_item_id=all_rcv.inv_grey_fab_item_id and open_isu.company_id=all_rcv.company_id


        left join (
        select 
        inv_grey_fab_transactions.company_id,
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_rcv_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
        join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
        where inv_rcvs.receive_date >= '".$date_from."' 
        and inv_rcvs.receive_date <= '".$date_to."' 
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=1
        group by 
        inv_grey_fab_transactions.company_id,
        inv_grey_fab_rcv_items.inv_grey_fab_item_id
        ) rcv on rcv.inv_grey_fab_item_id=all_rcv.inv_grey_fab_item_id and rcv.company_id=all_rcv.company_id
        left join (

        select 
        inv_grey_fab_transactions.company_id,
        inv_grey_fab_isu_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_isu_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
        join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
        where inv_isus.issue_date >= '".$date_from."' 
        and inv_isus.issue_date <= '".$date_to."' 
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_isu_items.deleted_at is null
        and inv_isus.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=2
        group by 
        inv_grey_fab_transactions.company_id,
        inv_grey_fab_isu_items.inv_grey_fab_item_id
        ) isu on isu.inv_grey_fab_item_id=all_rcv.inv_grey_fab_item_id and isu.company_id=all_rcv.company_id
        ) m 
        left join companies on companies.id=m.company_id
        where companies.id in(1,2,4)
        group by m.company_id,companies.code
        "
        ))
        ->map(function($greyfab){
            $greyfab->greyfab_opening_qty=$greyfab->open_rcv_qty-($greyfab->open_isu_qty*-1);
            $greyfab->greyfab_opening_amount=$greyfab->open_rcv_amount-$greyfab->open_isu_amount;
            $greyfab->greyfab_rcv_qty=$greyfab->rcv_qty;
            $greyfab->greyfab_rcv_amount=$greyfab->rcv_amount;
            $greyfab->greyfab_isu_qty=$greyfab->isu_qty;
            $greyfab->greyfab_isu_amount=$greyfab->isu_amount;
            $greyfab->greyfab_stock_qty=($greyfab->greyfab_opening_qty+$greyfab->rcv_qty)-($greyfab->isu_qty*-1);
            $greyfab->greyfab_stock_amount=$greyfab->greyfab_opening_amount+($greyfab->rcv_amount-$greyfab->isu_amount);

            $greyfab->greyfab_opening_qty=number_format($greyfab->greyfab_opening_qty,0);
            $greyfab->greyfab_opening_amount=number_format($greyfab->greyfab_opening_amount,0);
            $greyfab->greyfab_rcv_qty=number_format($greyfab->greyfab_rcv_qty,0);
            $greyfab->greyfab_rcv_amount=number_format($greyfab->greyfab_rcv_amount,0);
            $greyfab->greyfab_isu_qty=number_format($greyfab->greyfab_isu_qty*-1,0);
            $greyfab->greyfab_isu_amount=number_format($greyfab->greyfab_isu_amount,0);
            $greyfab->greyfab_stock_qty=number_format($greyfab->greyfab_stock_qty,0);
            $greyfab->greyfab_stock_amount=number_format($greyfab->greyfab_stock_amount,0);
            return $greyfab;

        });



        $subcondyeing = collect(\DB::select("
        select
        --m.id,
        --m.buyer_name as buyer_name,
        sum(m.rcv_all_qty) as rcv_all_qty,
        avg(m.rcv_all_rate) as rcv_all_rate,
        sum(m.rcv_all_amount) as rcv_all_amount,
        sum(m.rcv_open_qty) as rcv_open_qty,
        avg(m.rcv_open_rate) as rcv_open_rate,
        sum(m.rcv_open_amount) as rcv_open_amount,

        sum(m.dlv_fin_open_qty) as dlv_fin_open_qty,
        avg(m.dlv_fin_open_rate) as dlv_fin_open_rate,
        sum(m.dlv_fin_open_amount) as dlv_fin_open_amount,
        sum(m.dlv_grey_used_open_qty) as dlv_grey_used_open_qty,
        avg(m.dlv_grey_used_open_rate) as dlv_grey_used_open_rate,
        sum(m.dlv_grey_used_open_amount) as dlv_grey_used_open_amount,

        sum(m.rtn_open_qty) as rtn_open_qty,
        avg(m.rtn_open_rate) as rtn_open_rate,
        sum(m.rtn_open_amount) as rtn_open_amount,

        sum(m.rcv_qty) as rcv_qty,
        avg(m.rcv_rate) as rcv_rate,
        sum(m.rcv_amount) as rcv_amount,

        sum(m.dlv_fin_qty) as dlv_fin_qty,
        avg(m.dlv_fin_rate) as dlv_fin_rate,
        sum(m.dlv_fin_amount) as dlv_fin_amount,
        sum(m.dlv_grey_used_qty) as dlv_grey_used_qty,
        avg(m.dlv_grey_used_rate) as dlv_grey_used_rate,
        sum(m.dlv_grey_used_amount) as dlv_grey_used_amount,

        sum(m.rtn_qty) as rtn_qty,
        avg(m.rtn_rate) as rtn_rate,
        sum(m.rtn_amount) as rtn_amount

        from (select 
        buyers.id,
        buyers.name as buyer_name,
        fabric_rcv_all.qty as rcv_all_qty,
        fabric_rcv_all.rate as rcv_all_rate,
        fabric_rcv_all.amount as rcv_all_amount,

        fabric_rcv_opening.qty as rcv_open_qty,
        fabric_rcv_opening.rate as rcv_open_rate,
        fabric_rcv_opening.amount as rcv_open_amount,

        fabric_dlv_opening.fin_qty as dlv_fin_open_qty,
        fabric_dlv_opening.fin_rate as dlv_fin_open_rate,
        fabric_dlv_opening.fin_amount as dlv_fin_open_amount,
        fabric_dlv_opening.grey_used_qty as dlv_grey_used_open_qty,
        fabric_dlv_opening.grey_used_rate as dlv_grey_used_open_rate,
        fabric_dlv_opening.grey_used_amount as dlv_grey_used_open_amount,

        fabric_rtn_opening.qty as rtn_open_qty,
        fabric_rtn_opening.rate as rtn_open_rate,
        fabric_rtn_opening.amount as rtn_open_amount,

        fabric_rcv.qty as rcv_qty,
        fabric_rcv.rate as rcv_rate,
        fabric_rcv.amount as rcv_amount,

        fabric_dlv.fin_qty as dlv_fin_qty,
        fabric_dlv.fin_rate as dlv_fin_rate,
        fabric_dlv.fin_amount as dlv_fin_amount,
        fabric_dlv.grey_used_qty as dlv_grey_used_qty,
        fabric_dlv.grey_used_rate as dlv_grey_used_rate,
        fabric_dlv.grey_used_amount as dlv_grey_used_amount,

        fabric_rtn.qty as rtn_qty,
        fabric_rtn.rate as rtn_rate,
        fabric_rtn.amount as rtn_amount

        from buyers
        join buyer_natures on buyers.id=buyer_natures.buyer_id
        join (
        select
        so_dyeings.buyer_id,
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
        sum(so_dyeing_fabric_rcv_items.qty) as qty,
        avg(so_dyeing_fabric_rcv_items.rate) as rate,
        sum(so_dyeing_fabric_rcv_items.amount) as amount
        from 
        so_dyeing_fabric_rcvs
        join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcvs.id=so_dyeing_fabric_rcv_items.so_dyeing_fabric_rcv_id
        join so_dyeings on so_dyeings.id=so_dyeing_fabric_rcvs.so_dyeing_id
        where 
        so_dyeing_fabric_rcvs.receive_date <= ?
        and so_dyeing_fabric_rcv_items.deleted_at is null
        and so_dyeing_fabric_rcvs.deleted_at is null
        and so_dyeings.deleted_at is null
        and so_dyeings.company_id = 5
        group by 
        so_dyeings.buyer_id,
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id
        ) fabric_rcv_all on fabric_rcv_all.buyer_id=buyers.id

        left join (
        select
        so_dyeings.buyer_id,
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
        sum(so_dyeing_fabric_rcv_items.qty) as qty,
        avg(so_dyeing_fabric_rcv_items.rate) as rate,
        sum(so_dyeing_fabric_rcv_items.amount) as amount
        from 
        so_dyeing_fabric_rcvs
        join so_dyeing_fabric_rcv_items on so_dyeing_fabric_rcvs.id=so_dyeing_fabric_rcv_items.so_dyeing_fabric_rcv_id
        join so_dyeings on so_dyeings.id=so_dyeing_fabric_rcvs.so_dyeing_id
        where 
        so_dyeing_fabric_rcvs.receive_date < ?
        and so_dyeing_fabric_rcv_items.deleted_at is null
        and so_dyeing_fabric_rcvs.deleted_at is null
        and so_dyeings.deleted_at is null
        group by 
        so_dyeings.buyer_id,
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id
        ) fabric_rcv_opening on fabric_rcv_opening.buyer_id=buyers.id and fabric_rcv_all.so_dyeing_ref_id=fabric_rcv_opening.so_dyeing_ref_id

        left join (
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
        so_dyeing_dlvs.issue_date < ?
        and so_dyeing_dlv_items.deleted_at is null
        and so_dyeing_dlvs.deleted_at is null
        group by 
        so_dyeing_dlvs.buyer_id,
        so_dyeing_dlv_items.so_dyeing_ref_id,
        greyusedrate.rate
        ) fabric_dlv_opening on fabric_dlv_opening.buyer_id=buyers.id and fabric_rcv_all.so_dyeing_ref_id=fabric_dlv_opening.so_dyeing_ref_id

        left join (
        select
        so_dyeing_fabric_rtns.buyer_id,
        so_dyeing_fabric_rtn_items.so_dyeing_ref_id,

        sum(so_dyeing_fabric_rtn_items.qty) as qty,
        greyrtnrate.rate as rate,
        sum(so_dyeing_fabric_rtn_items.qty)*greyrtnrate.rate as amount
        from 
        so_dyeing_fabric_rtns
        join so_dyeing_fabric_rtn_items on so_dyeing_fabric_rtns.id=so_dyeing_fabric_rtn_items.so_dyeing_fabric_rtn_id
        join(
        select 
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
        avg(so_dyeing_fabric_rcv_items.rate) as rate
        from so_dyeing_fabric_rcv_items
        where so_dyeing_fabric_rcv_items.qty>0
        and so_dyeing_fabric_rcv_items.rate >0 
        group by 
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id
        )greyrtnrate on greyrtnrate.so_dyeing_ref_id=so_dyeing_fabric_rtn_items.so_dyeing_ref_id


        where 
        so_dyeing_fabric_rtns.return_date < ?
        and so_dyeing_fabric_rtn_items.deleted_at is null
        and so_dyeing_fabric_rtns.deleted_at is null
        group by 
        so_dyeing_fabric_rtns.buyer_id,
        so_dyeing_fabric_rtn_items.so_dyeing_ref_id,
        greyrtnrate.rate
        ) fabric_rtn_opening on fabric_rtn_opening.buyer_id=buyers.id and fabric_rcv_all.so_dyeing_ref_id=fabric_rtn_opening.so_dyeing_ref_id

        left join (
        select
        so_dyeings.buyer_id,
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
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
        so_dyeings.buyer_id,
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id
        ) fabric_rcv on fabric_rcv.buyer_id=buyers.id and fabric_rcv_all.so_dyeing_ref_id=fabric_rcv.so_dyeing_ref_id


        left join (
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
        ) fabric_dlv on fabric_dlv.buyer_id=buyers.id and fabric_rcv_all.so_dyeing_ref_id=fabric_dlv.so_dyeing_ref_id

        left join (
        select
        so_dyeing_fabric_rtns.buyer_id,
        so_dyeing_fabric_rtn_items.so_dyeing_ref_id,

        sum(so_dyeing_fabric_rtn_items.qty) as qty,
        greyrtnrate.rate as rate,
        sum(so_dyeing_fabric_rtn_items.qty)*greyrtnrate.rate as amount
        from 
        so_dyeing_fabric_rtns
        join so_dyeing_fabric_rtn_items on so_dyeing_fabric_rtns.id=so_dyeing_fabric_rtn_items.so_dyeing_fabric_rtn_id
        join(
        select 
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id,
        avg(so_dyeing_fabric_rcv_items.rate) as rate
        from so_dyeing_fabric_rcv_items
        where so_dyeing_fabric_rcv_items.qty>0
        and so_dyeing_fabric_rcv_items.rate >0 
        group by 
        so_dyeing_fabric_rcv_items.so_dyeing_ref_id
        )greyrtnrate on greyrtnrate.so_dyeing_ref_id=so_dyeing_fabric_rtn_items.so_dyeing_ref_id


        where 
        so_dyeing_fabric_rtns.return_date >= ?
        and so_dyeing_fabric_rtns.return_date <= ?
        and so_dyeing_fabric_rtn_items.deleted_at is null
        and so_dyeing_fabric_rtns.deleted_at is null
        group by 
        so_dyeing_fabric_rtns.buyer_id,
        so_dyeing_fabric_rtn_items.so_dyeing_ref_id,
        greyrtnrate.rate
        ) fabric_rtn on fabric_rtn.buyer_id=buyers.id and fabric_rcv_all.so_dyeing_ref_id=fabric_rtn.so_dyeing_ref_id
        where buyer_natures.contact_nature_id = 3) m 
        --group by m.buyer_name,m.id 
        --order by m.id
        ", [$date_to,$date_from, $date_from, $date_from, $date_from, $date_to, $date_from, $date_to, $date_from, $date_to]
        ))
        ->map(function($subcondyeing){
            $subcondyeing->opening_qty=$subcondyeing->rcv_open_qty-($subcondyeing->dlv_grey_used_open_qty+$subcondyeing->rtn_open_qty);
            $subcondyeing->opening_amount=$subcondyeing->rcv_open_amount-($subcondyeing->dlv_grey_used_open_amount+$subcondyeing->rtn_open_amount);
            $subcondyeing->total_rcv_qty=$subcondyeing->rcv_qty+$subcondyeing->opening_qty;

            $subcondyeing->total_rcv_amount=$subcondyeing->rcv_amount+$subcondyeing->opening_amount;

            $subcondyeing->total_adjusted=$subcondyeing->dlv_grey_used_qty+$subcondyeing->rtn_qty;
            $subcondyeing->total_adjusted_amount=$subcondyeing->dlv_grey_used_amount+$subcondyeing->rtn_amount;
            $subcondyeing->stock_qty=$subcondyeing->total_rcv_qty-$subcondyeing->total_adjusted;
            $subcondyeing->stock_value=$subcondyeing->total_rcv_amount-$subcondyeing->total_adjusted_amount;
            $subcondyeing->rate=0;
            if ($subcondyeing->stock_qty) {
                $subcondyeing->rate=$subcondyeing->stock_value/$subcondyeing->stock_qty;
            }
            //$subcondyeing->rate=$subcondyeing->stock_value/$subcondyeing->stock_qty;
            $subcondyeing->opening_qty=number_format($subcondyeing->opening_qty,0);
            $subcondyeing->opening_amount=number_format($subcondyeing->opening_amount,0);
            $subcondyeing->rcv_qty=number_format($subcondyeing->rcv_qty,0);
            $subcondyeing->total_rcv_qty=number_format($subcondyeing->total_rcv_qty,0);
            $subcondyeing->dlv_fin_qty=number_format($subcondyeing->dlv_fin_qty,0);
            $subcondyeing->dlv_grey_used_qty=number_format($subcondyeing->dlv_grey_used_qty,0);
            $subcondyeing->rtn_qty=number_format($subcondyeing->rtn_qty,0);
            $subcondyeing->total_adjusted=number_format($subcondyeing->total_adjusted,0);
            $subcondyeing->total_adjusted_amount=number_format($subcondyeing->total_adjusted_amount,0);
            $subcondyeing->stock_qty=number_format($subcondyeing->stock_qty,0);
            $subcondyeing->rate=number_format($subcondyeing->rate,0);
            $subcondyeing->stock_value=number_format($subcondyeing->stock_value,0);
            return $subcondyeing;
        });

        $subconaop = collect(
        \DB::select("
        select
        --m.id,
        --m.buyer_name as buyer_name,
        sum(m.rcv_all_qty) as rcv_all_qty,
        avg(m.rcv_all_rate) as rcv_all_rate,
        sum(m.rcv_all_amount) as rcv_all_amount,
        sum(m.rcv_open_qty) as rcv_open_qty,
        avg(m.rcv_open_rate) as rcv_open_rate,
        sum(m.rcv_open_amount) as rcv_open_amount,

        sum(m.dlv_fin_open_qty) as dlv_fin_open_qty,
        avg(m.dlv_fin_open_rate) as dlv_fin_open_rate,
        sum(m.dlv_fin_open_amount) as dlv_fin_open_amount,
        sum(m.dlv_grey_used_open_qty) as dlv_grey_used_open_qty,
        avg(m.dlv_grey_used_open_rate) as dlv_grey_used_open_rate,
        sum(m.dlv_grey_used_open_amount) as dlv_grey_used_open_amount,

        sum(m.rtn_open_qty) as rtn_open_qty,
        avg(m.rtn_open_rate) as rtn_open_rate,
        sum(m.rtn_open_amount) as rtn_open_amount,

        sum(m.rcv_qty) as rcv_qty,
        avg(m.rcv_rate) as rcv_rate,
        sum(m.rcv_amount) as rcv_amount,

        sum(m.dlv_fin_qty) as dlv_fin_qty,
        avg(m.dlv_fin_rate) as dlv_fin_rate,
        sum(m.dlv_fin_amount) as dlv_fin_amount,
        sum(m.dlv_grey_used_qty) as dlv_grey_used_qty,
        avg(m.dlv_grey_used_rate) as dlv_grey_used_rate,
        sum(m.dlv_grey_used_amount) as dlv_grey_used_amount,

        sum(m.rtn_qty) as rtn_qty,
        avg(m.rtn_rate) as rtn_rate,
        sum(m.rtn_amount) as rtn_amount

        from (select 
        buyers.id,
        buyers.name as buyer_name,
        fabric_rcv_all.qty as rcv_all_qty,
        fabric_rcv_all.rate as rcv_all_rate,
        fabric_rcv_all.amount as rcv_all_amount,

        fabric_rcv_opening.qty as rcv_open_qty,
        fabric_rcv_opening.rate as rcv_open_rate,
        fabric_rcv_opening.amount as rcv_open_amount,

        fabric_dlv_opening.fin_qty as dlv_fin_open_qty,
        fabric_dlv_opening.fin_rate as dlv_fin_open_rate,
        fabric_dlv_opening.fin_amount as dlv_fin_open_amount,
        fabric_dlv_opening.grey_used_qty as dlv_grey_used_open_qty,
        fabric_dlv_opening.grey_used_rate as dlv_grey_used_open_rate,
        fabric_dlv_opening.grey_used_amount as dlv_grey_used_open_amount,

        fabric_rtn_opening.qty as rtn_open_qty,
        fabric_rtn_opening.rate as rtn_open_rate,
        fabric_rtn_opening.amount as rtn_open_amount,

        fabric_rcv.qty as rcv_qty,
        fabric_rcv.rate as rcv_rate,
        fabric_rcv.amount as rcv_amount,

        fabric_dlv.fin_qty as dlv_fin_qty,
        fabric_dlv.fin_rate as dlv_fin_rate,
        fabric_dlv.fin_amount as dlv_fin_amount,
        fabric_dlv.grey_used_qty as dlv_grey_used_qty,
        fabric_dlv.grey_used_rate as dlv_grey_used_rate,
        fabric_dlv.grey_used_amount as dlv_grey_used_amount,

        fabric_rtn.qty as rtn_qty,
        fabric_rtn.rate as rtn_rate,
        fabric_rtn.amount as rtn_amount

        from buyers
        join buyer_natures on buyers.id=buyer_natures.buyer_id
        join (
        select
        so_aops.buyer_id,
        so_aop_fabric_rcv_items.so_aop_ref_id,
        sum(so_aop_fabric_rcv_items.qty) as qty,
        avg(so_aop_fabric_rcv_items.rate) as rate,
        sum(so_aop_fabric_rcv_items.amount) as amount
        from 
        so_aop_fabric_rcvs
        join so_aop_fabric_rcv_items on so_aop_fabric_rcvs.id=so_aop_fabric_rcv_items.so_aop_fabric_rcv_id
        join so_aops on so_aops.id=so_aop_fabric_rcvs.so_aop_id
        where 
        so_aop_fabric_rcvs.receive_date <= ?
        and so_aop_fabric_rcv_items.deleted_at is null
        and so_aop_fabric_rcvs.deleted_at is null
        and so_aops.deleted_at is null
        and so_aops.company_id = 6
        group by 
        so_aops.buyer_id,
        so_aop_fabric_rcv_items.so_aop_ref_id
        ) fabric_rcv_all on fabric_rcv_all.buyer_id=buyers.id

        left join (
        select
        so_aops.buyer_id,
        so_aop_fabric_rcv_items.so_aop_ref_id,
        sum(so_aop_fabric_rcv_items.qty) as qty,
        avg(so_aop_fabric_rcv_items.rate) as rate,
        sum(so_aop_fabric_rcv_items.amount) as amount
        from 
        so_aop_fabric_rcvs
        join so_aop_fabric_rcv_items on so_aop_fabric_rcvs.id=so_aop_fabric_rcv_items.so_aop_fabric_rcv_id
        join so_aops on so_aops.id=so_aop_fabric_rcvs.so_aop_id
        where 
        so_aop_fabric_rcvs.receive_date < ?
        and so_aop_fabric_rcv_items.deleted_at is null
        and so_aop_fabric_rcvs.deleted_at is null
        and so_aops.deleted_at is null
        group by 
        so_aops.buyer_id,
        so_aop_fabric_rcv_items.so_aop_ref_id
        ) fabric_rcv_opening on fabric_rcv_opening.buyer_id=buyers.id and fabric_rcv_all.so_aop_ref_id=fabric_rcv_opening.so_aop_ref_id

        left join (
        select
        so_aop_dlvs.buyer_id,
        so_aop_dlv_items.so_aop_ref_id,
        sum(so_aop_dlv_items.qty) as fin_qty,
        avg(so_aop_dlv_items.rate) as fin_rate,
        sum(so_aop_dlv_items.amount) as fin_amount,
        sum(so_aop_dlv_items.grey_used) as grey_used_qty,
        greyusedrate.rate as grey_used_rate,
        sum(so_aop_dlv_items.grey_used)*greyusedrate.rate as grey_used_amount
        from 
        so_aop_dlvs
        join so_aop_dlv_items on so_aop_dlvs.id=so_aop_dlv_items.so_aop_dlv_id
        join(
        select 
        so_aop_fabric_rcv_items.so_aop_ref_id,
        avg(so_aop_fabric_rcv_items.rate) as rate
        from so_aop_fabric_rcv_items
        where so_aop_fabric_rcv_items.qty>0
        and so_aop_fabric_rcv_items.rate >0 
        group by 
        so_aop_fabric_rcv_items.so_aop_ref_id
        )greyusedrate on greyusedrate.so_aop_ref_id=so_aop_dlv_items.so_aop_ref_id
        where 
        so_aop_dlvs.issue_date < ?
        and so_aop_dlv_items.deleted_at is null
        and so_aop_dlvs.deleted_at is null
        group by 
        so_aop_dlvs.buyer_id,
        so_aop_dlv_items.so_aop_ref_id,
        greyusedrate.rate
        ) fabric_dlv_opening on fabric_dlv_opening.buyer_id=buyers.id and fabric_rcv_all.so_aop_ref_id=fabric_dlv_opening.so_aop_ref_id

        left join (
        select
        so_aop_fabric_rtns.buyer_id,
        so_aop_fabric_rtn_items.so_aop_ref_id,

        sum(so_aop_fabric_rtn_items.qty) as qty,
        greyrtnrate.rate as rate,
        sum(so_aop_fabric_rtn_items.qty)*greyrtnrate.rate as amount
        from 
        so_aop_fabric_rtns
        join so_aop_fabric_rtn_items on so_aop_fabric_rtns.id=so_aop_fabric_rtn_items.so_aop_fabric_rtn_id
        join(
        select 
        so_aop_fabric_rcv_items.so_aop_ref_id,
        avg(so_aop_fabric_rcv_items.rate) as rate
        from so_aop_fabric_rcv_items
        where so_aop_fabric_rcv_items.qty>0
        and so_aop_fabric_rcv_items.rate >0 
        group by 
        so_aop_fabric_rcv_items.so_aop_ref_id
        )greyrtnrate on greyrtnrate.so_aop_ref_id=so_aop_fabric_rtn_items.so_aop_ref_id


        where 
        so_aop_fabric_rtns.return_date < ?
        and so_aop_fabric_rtn_items.deleted_at is null
        and so_aop_fabric_rtns.deleted_at is null
        group by 
        so_aop_fabric_rtns.buyer_id,
        so_aop_fabric_rtn_items.so_aop_ref_id,
        greyrtnrate.rate
        ) fabric_rtn_opening on fabric_rtn_opening.buyer_id=buyers.id and fabric_rcv_all.so_aop_ref_id=fabric_rtn_opening.so_aop_ref_id

        left join (
        select
        so_aops.buyer_id,
        so_aop_fabric_rcv_items.so_aop_ref_id,
        sum(so_aop_fabric_rcv_items.qty) as qty,
        avg(so_aop_fabric_rcv_items.rate) as rate,
        sum(so_aop_fabric_rcv_items.amount) as amount
        from 
        so_aop_fabric_rcvs
        join so_aop_fabric_rcv_items on so_aop_fabric_rcvs.id=so_aop_fabric_rcv_items.so_aop_fabric_rcv_id
        join so_aops on so_aops.id=so_aop_fabric_rcvs.so_aop_id
        where 
        so_aop_fabric_rcvs.receive_date >= ?
        and so_aop_fabric_rcvs.receive_date <= ?
        and so_aop_fabric_rcv_items.deleted_at is null
        and so_aop_fabric_rcvs.deleted_at is null
        and so_aops.deleted_at is null
        group by 
        so_aops.buyer_id,
        so_aop_fabric_rcv_items.so_aop_ref_id
        ) fabric_rcv on fabric_rcv.buyer_id=buyers.id and fabric_rcv_all.so_aop_ref_id=fabric_rcv.so_aop_ref_id


        left join (
        select
        so_aop_dlvs.buyer_id,
        so_aop_dlv_items.so_aop_ref_id,
        sum(so_aop_dlv_items.qty) as fin_qty,
        avg(so_aop_dlv_items.rate) as fin_rate,
        sum(so_aop_dlv_items.amount) as fin_amount,
        sum(so_aop_dlv_items.grey_used) as grey_used_qty,
        greyusedrate.rate as grey_used_rate,
        sum(so_aop_dlv_items.grey_used)*greyusedrate.rate as grey_used_amount
        from 
        so_aop_dlvs
        join so_aop_dlv_items on so_aop_dlvs.id=so_aop_dlv_items.so_aop_dlv_id
        join(
        select 
        so_aop_fabric_rcv_items.so_aop_ref_id,
        avg(so_aop_fabric_rcv_items.rate) as rate
        from so_aop_fabric_rcv_items
        where so_aop_fabric_rcv_items.qty>0
        and so_aop_fabric_rcv_items.rate >0 
        group by 
        so_aop_fabric_rcv_items.so_aop_ref_id
        )greyusedrate on greyusedrate.so_aop_ref_id=so_aop_dlv_items.so_aop_ref_id
        where 
        so_aop_dlvs.issue_date >= ?
        and so_aop_dlvs.issue_date <= ?
        and so_aop_dlv_items.deleted_at is null
        and so_aop_dlvs.deleted_at is null
        group by 
        so_aop_dlvs.buyer_id,
        so_aop_dlv_items.so_aop_ref_id,
        greyusedrate.rate
        ) fabric_dlv on fabric_dlv.buyer_id=buyers.id and fabric_rcv_all.so_aop_ref_id=fabric_dlv.so_aop_ref_id

        left join (
        select
        so_aop_fabric_rtns.buyer_id,
        so_aop_fabric_rtn_items.so_aop_ref_id,

        sum(so_aop_fabric_rtn_items.qty) as qty,
        greyrtnrate.rate as rate,
        sum(so_aop_fabric_rtn_items.qty)*greyrtnrate.rate as amount
        from 
        so_aop_fabric_rtns
        join so_aop_fabric_rtn_items on so_aop_fabric_rtns.id=so_aop_fabric_rtn_items.so_aop_fabric_rtn_id
        join(
        select 
        so_aop_fabric_rcv_items.so_aop_ref_id,
        avg(so_aop_fabric_rcv_items.rate) as rate
        from so_aop_fabric_rcv_items
        where so_aop_fabric_rcv_items.qty>0
        and so_aop_fabric_rcv_items.rate >0 
        group by 
        so_aop_fabric_rcv_items.so_aop_ref_id
        )greyrtnrate on greyrtnrate.so_aop_ref_id=so_aop_fabric_rtn_items.so_aop_ref_id


        where 
        so_aop_fabric_rtns.return_date >= ?
        and so_aop_fabric_rtns.return_date <= ?
        and so_aop_fabric_rtn_items.deleted_at is null
        and so_aop_fabric_rtns.deleted_at is null
        group by 
        so_aop_fabric_rtns.buyer_id,
        so_aop_fabric_rtn_items.so_aop_ref_id,
        greyrtnrate.rate
        ) fabric_rtn on fabric_rtn.buyer_id=buyers.id and fabric_rcv_all.so_aop_ref_id=fabric_rtn.so_aop_ref_id
        where buyer_natures.contact_nature_id = 58) m 
        --group by m.buyer_name,m.id 
        --order by m.id
        ", [$date_to,$date_from, $date_from, $date_from, $date_from, $date_to, $date_from, $date_to, $date_from, $date_to])
        )
        ->map(function($subconaop){
            $subconaop->opening_qty=$subconaop->rcv_open_qty-($subconaop->dlv_grey_used_open_qty+$subconaop->rtn_open_qty);
            $subconaop->opening_amount=$subconaop->rcv_open_amount-($subconaop->dlv_grey_used_open_amount+$subconaop->rtn_open_amount);
            $subconaop->total_rcv_qty=$subconaop->rcv_qty+$subconaop->opening_qty;

            $subconaop->total_rcv_amount=$subconaop->rcv_amount+$subconaop->opening_amount;

            $subconaop->total_adjusted=$subconaop->dlv_grey_used_qty+$subconaop->rtn_qty;
            $subconaop->total_adjusted_amount=$subconaop->dlv_grey_used_amount+$subconaop->rtn_amount;
            $subconaop->stock_qty=$subconaop->total_rcv_qty-$subconaop->total_adjusted;
            $subconaop->stock_value=$subconaop->total_rcv_amount-$subconaop->total_adjusted_amount;
            $subconaop->rate=0;
            if($subconaop->stock_qty){
            $subconaop->rate=$subconaop->stock_value/$subconaop->stock_qty;
            }
            $subconaop->opening_qty=number_format($subconaop->opening_qty,0);
            $subconaop->opening_amount=number_format($subconaop->opening_amount,0);
            $subconaop->rcv_qty=number_format($subconaop->rcv_qty,0);
            $subconaop->total_rcv_qty=number_format($subconaop->total_rcv_qty,0);
            $subconaop->dlv_fin_qty=number_format($subconaop->dlv_fin_qty,0);
            $subconaop->dlv_grey_used_qty=number_format($subconaop->dlv_grey_used_qty,0);
            $subconaop->rtn_qty=number_format($subconaop->rtn_qty,0);
            $subconaop->total_adjusted=number_format($subconaop->total_adjusted,0);
            $subconaop->total_adjusted_amount=number_format($subconaop->total_adjusted_amount,0);
            $subconaop->stock_qty=number_format($subconaop->stock_qty,0);
            $subconaop->rate=number_format($subconaop->rate,0);
            $subconaop->stock_value=number_format($subconaop->stock_value,0);
            return $subconaop;
        });


        $subconkniting = collect(
        \DB::select("
                    select
                    --m.id,
                    --m.buyer_name as buyer_name,
                    --sum(m.rcv_all_qty) as rcv_all_qty,
                    --avg(m.rcv_all_rate) as rcv_all_rate,
                    --sum(m.rcv_all_amount) as rcv_all_amount,
                    sum(m.rcv_open_qty) as rcv_open_qty,
                    --avg(m.rcv_open_rate) as rcv_open_rate,
                    sum(m.rcv_open_amount) as rcv_open_amount,

                    sum(m.dlv_fin_open_qty) as dlv_fin_open_qty,
                    --avg(m.dlv_fin_open_rate) as dlv_fin_open_rate,
                    --sum(m.dlv_fin_open_amount) as dlv_fin_open_amount,

                    sum(m.dlv_grey_used_open_qty) as dlv_grey_used_open_qty,
                    --avg(m.dlv_grey_used_open_rate) as dlv_grey_used_open_rate,
                    sum(m.dlv_grey_used_open_amount) as dlv_grey_used_open_amount,

                    sum(m.rtn_open_qty) as rtn_open_qty,
                   -- avg(m.rtn_open_rate) as rtn_open_rate,
                    sum(m.rtn_open_amount) as rtn_open_amount,

                    sum(m.rcv_qty) as rcv_qty,
                    --avg(m.rcv_rate) as rcv_rate,
                    sum(m.rcv_amount) as rcv_amount,

                    sum(m.dlv_fin_qty) as dlv_fin_qty,
                    --avg(m.dlv_fin_rate) as dlv_fin_rate,
                    --sum(m.dlv_fin_amount) as dlv_fin_amount,

                    sum(m.dlv_grey_used_qty) as dlv_grey_used_qty,
                    --avg(m.dlv_grey_used_rate) as dlv_grey_used_rate,
                    sum(m.dlv_grey_used_amount) as dlv_grey_used_amount,

                    sum(m.rtn_qty) as rtn_qty,
                    --avg(m.rtn_rate) as rtn_rate,
                    sum(m.rtn_amount) as rtn_amount

                    from (
                    select 
                    buyers.id,
                    buyers.name as buyer_name,
                    yarn_rcv_opening.qty as rcv_open_qty,
                    yarn_rcv_opening.amount as rcv_open_amount,

                    yarn_used_opening.qty as dlv_grey_used_open_qty,
                    yarn_used_opening.amount as dlv_grey_used_open_amount,
                    yarn_dlv_opening.qty as dlv_fin_open_qty,

                    yarn_rtn_opening.qty as rtn_open_qty,
                    yarn_rtn_opening.amount as rtn_open_amount,

                    yarn_rcv.qty as rcv_qty,
                    yarn_rcv.amount as rcv_amount,

                    yarn_used.qty as dlv_grey_used_qty,
                    yarn_used.amount as dlv_grey_used_amount,
                    yarn_dlv.qty as dlv_fin_qty,

                    yarn_rtn.qty as rtn_qty,
                    yarn_rtn.amount as rtn_amount
                    from buyers
                    join buyer_natures on buyers.id=buyer_natures.buyer_id
                    join (
                    select
                    so_knits.buyer_id,
                    sum(so_knit_yarn_rcv_items.qty) as qty,
                    sum(so_knit_yarn_rcv_items.amount) as amount
                    from 
                    so_knit_yarn_rcvs
                    join so_knit_yarn_rcv_items on so_knit_yarn_rcvs.id=so_knit_yarn_rcv_items.so_knit_yarn_rcv_id
                    join so_knits on so_knits.id=so_knit_yarn_rcvs.so_knit_id
                    where 
                    so_knit_yarn_rcvs.receive_date < ?
                    and so_knit_yarn_rcv_items.deleted_at is null
                    and so_knit_yarn_rcvs.deleted_at is null
                    and so_knits.deleted_at is null
                    group by 
                    so_knits.buyer_id
                    ) yarn_rcv_all on yarn_rcv_all.buyer_id=buyers.id


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
                    so_knit_yarn_rcvs.receive_date < ?
                    and so_knit_yarn_rcv_items.deleted_at is null
                    and so_knit_yarn_rcvs.deleted_at is null
                    and so_knits.deleted_at is null
                    group by 
                    so_knits.buyer_id
                    ) yarn_rcv_opening on yarn_rcv_opening.buyer_id=buyers.id

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
                    so_knit_dlvs.issue_date < ?
                    and so_knit_dlvs.deleted_at is null
                    and so_knit_dlv_items.deleted_at is null
                    and so_knit_dlv_item_yarns.deleted_at is null
                    group by 
                    so_knit_dlvs.buyer_id
                    ) yarn_used_opening on yarn_used_opening.buyer_id=buyers.id


                    left join (
                    select
                    so_knit_dlvs.buyer_id,
                    sum(so_knit_dlv_items.qty) as qty
                    from 
                    so_knit_dlvs
                    join so_knit_dlv_items on so_knit_dlvs.id=so_knit_dlv_items.so_knit_dlv_id
                    where 
                    so_knit_dlvs.issue_date < ?
                    and so_knit_dlvs.deleted_at is null
                    and so_knit_dlv_items.deleted_at is null
                    group by 
                    so_knit_dlvs.buyer_id
                    ) yarn_dlv_opening on yarn_dlv_opening.buyer_id=buyers.id



                    left join (
                    select
                    so_knit_yarn_rtns.buyer_id,
                    sum(so_knit_yarn_rtn_items.qty) as qty,
                    sum(so_knit_yarn_rtn_items.amount) as amount
                    from 
                    so_knit_yarn_rtns
                    join so_knit_yarn_rtn_items on so_knit_yarn_rtns.id=so_knit_yarn_rtn_items.so_knit_yarn_rtn_id
                    where 
                    so_knit_yarn_rtns.return_date < ?
                    and so_knit_yarn_rtns.deleted_at is null
                    and so_knit_yarn_rtn_items.deleted_at is null
                    group by 
                    so_knit_yarn_rtns.buyer_id
                    ) yarn_rtn_opening on yarn_rtn_opening.buyer_id=buyers.id

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

                    left join (
                    select
                    so_knit_yarn_rtns.buyer_id,
                    sum(so_knit_yarn_rtn_items.qty) as qty,
                    sum(so_knit_yarn_rtn_items.amount) as amount
                    from 
                    so_knit_yarn_rtns
                    join so_knit_yarn_rtn_items on so_knit_yarn_rtns.id=so_knit_yarn_rtn_items.so_knit_yarn_rtn_id
                    where 
                    so_knit_yarn_rtns.return_date >= ?
                    and so_knit_yarn_rtns.return_date <= ?
                    and so_knit_yarn_rtns.deleted_at is null
                    and so_knit_yarn_rtn_items.deleted_at is null
                    group by 
                    so_knit_yarn_rtns.buyer_id
                    ) yarn_rtn on yarn_rtn.buyer_id=buyers.id
                    where 
                    buyer_natures.contact_nature_id = 2
                    and buyer_natures.deleted_at is null
                    order by 
                    buyers.name) m
        ",[ $date_to, $date_from, $date_from, $date_from, $date_from, $date_from,$date_to, $date_from,$date_to, $date_from,$date_to, $date_from,$date_to])
        )
        ->map(function($subconkniting){
            $subconkniting->opening_qty=$subconkniting->rcv_open_qty-($subconkniting->dlv_grey_used_open_qty+$subconkniting->rtn_open_qty);
            $subconkniting->opening_amount=$subconkniting->rcv_open_amount-($subconkniting->dlv_grey_used_open_amount+$subconkniting->rtn_open_amount);
            $subconkniting->total_rcv_qty=$subconkniting->rcv_qty+$subconkniting->opening_qty;
            $subconkniting->total_rcv_amount=$subconkniting->rcv_amount+$subconkniting->opening_amount;

            $subconkniting->total_adjusted=$subconkniting->dlv_grey_used_qty+$subconkniting->rtn_qty;
            $subconkniting->total_adjusted_amount=$subconkniting->dlv_grey_used_amount+$subconkniting->rtn_amount;
            $subconkniting->stock_qty=$subconkniting->total_rcv_qty-$subconkniting->total_adjusted;
            $subconkniting->stock_value=$subconkniting->total_rcv_amount-$subconkniting->total_adjusted_amount;
            $subconkniting->rate=0;
            if($subconkniting->stock_qty){
            $subconkniting->rate=$subconkniting->stock_value/$subconkniting->stock_qty;
            }
            $subconkniting->opening_qty=number_format($subconkniting->opening_qty,0);
            $subconkniting->opening_amount=number_format($subconkniting->opening_amount,0);
            $subconkniting->rcv_qty=number_format($subconkniting->rcv_qty,0);
            $subconkniting->total_rcv_qty=number_format($subconkniting->total_rcv_qty,0);
            $subconkniting->dlv_fin_qty=number_format($subconkniting->dlv_fin_qty,0);
            $subconkniting->dlv_grey_used_qty=number_format($subconkniting->dlv_grey_used_qty,0);
            $subconkniting->rtn_qty=number_format($subconkniting->rtn_qty,0);
            $subconkniting->total_adjusted=number_format($subconkniting->total_adjusted,0);
            $subconkniting->total_adjusted_amount=number_format($subconkniting->total_adjusted_amount,0);
            $subconkniting->stock_qty=number_format($subconkniting->stock_qty,0);
            $subconkniting->rate=number_format($subconkniting->rate,0);
            $subconkniting->stock_value=number_format($subconkniting->stock_value,0);
            return $subconkniting;
        });


        $gmts = collect(
        \DB::select("
        select
        companies.id,
        companies.code as company_code,
        open_carton.car_open_qty,
        carton.car_qty,
        open_exfactory.exf_open_qty,
        exfactory.exf_qty
        from companies
         left join(
        SELECT
        jobs.company_id, 
        sum(style_pkg_ratios.qty) as car_open_qty 
        FROM prod_gmt_carton_entries
        join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
        join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
        join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
        join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
        join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
        join jobs on jobs.id = sales_orders.job_id
        where prod_gmt_carton_entries.carton_date < ?

        and prod_gmt_carton_entries.deleted_at is null
        and prod_gmt_carton_details.deleted_at is null
        and sales_orders.order_status=1
        group by jobs.company_id
        ) open_carton on open_carton.company_id=companies.id
        left join(
        SELECT
        jobs.company_id, 
        sum(style_pkg_ratios.qty) as car_qty 
        FROM prod_gmt_carton_entries
        join prod_gmt_carton_details on prod_gmt_carton_details.prod_gmt_carton_entry_id = prod_gmt_carton_entries.id 
        join style_pkgs on style_pkgs.id = prod_gmt_carton_details.style_pkg_id 
        join style_pkg_ratios on style_pkg_ratios.style_pkg_id = style_pkgs.id 
        join sales_order_countries on sales_order_countries.id = prod_gmt_carton_details.sales_order_country_id
        join sales_orders on sales_orders.id = sales_order_countries.sale_order_id
        join jobs on jobs.id = sales_orders.job_id
        where prod_gmt_carton_entries.carton_date>=?
        and prod_gmt_carton_entries.carton_date<=?
        and prod_gmt_carton_entries.deleted_at is null
        and prod_gmt_carton_details.deleted_at is null
        and sales_orders.order_status=1
        group by jobs.company_id
        ) carton on carton.company_id=companies.id

        left join (
        SELECT 
        jobs.company_id, 
        sum(style_pkg_ratios.qty) as exf_open_qty 
        FROM sales_orders  
        join jobs on jobs.id = sales_orders.job_id 
        join styles on styles.id = jobs.style_id 
        join style_pkgs on style_pkgs.style_id = styles.id 
        join style_pkg_ratios on style_pkgs.id = style_pkg_ratios.style_pkg_id 
        join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
        join prod_gmt_carton_details on prod_gmt_carton_details.style_pkg_id = style_pkgs.id 
        and prod_gmt_carton_details.sales_order_country_id = sales_order_countries.id 
        join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id = prod_gmt_carton_details.id 
        join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.Prod_Gmt_Ex_Factory_Id
        where 
        prod_gmt_ex_factories.exfactory_date < ?
        and prod_gmt_carton_details.deleted_at is null
        and sales_orders.order_status=1
        group by jobs.company_id
        ) open_exfactory on open_exfactory.company_id=companies.id

       
        left join (
        SELECT 
        jobs.company_id, 
        sum(style_pkg_ratios.qty) as exf_qty 
        FROM sales_orders  
        join jobs on jobs.id = sales_orders.job_id 
        join styles on styles.id = jobs.style_id 
        join style_pkgs on style_pkgs.style_id = styles.id 
        join style_pkg_ratios on style_pkgs.id = style_pkg_ratios.style_pkg_id 
        join sales_order_countries on sales_order_countries.sale_order_id = sales_orders.id 
        join prod_gmt_carton_details on prod_gmt_carton_details.style_pkg_id = style_pkgs.id 
        and prod_gmt_carton_details.sales_order_country_id = sales_order_countries.id 
        join prod_gmt_ex_factory_qties on prod_gmt_ex_factory_qties.prod_gmt_carton_detail_id = prod_gmt_carton_details.id 
        join prod_gmt_ex_factories on prod_gmt_ex_factories.id=prod_gmt_ex_factory_qties.Prod_Gmt_Ex_Factory_Id
        where 
        prod_gmt_ex_factories.exfactory_date>=?
        and prod_gmt_ex_factories.exfactory_date<=?

        and prod_gmt_carton_details.deleted_at is null
        and sales_orders.order_status=1
        group by jobs.company_id
        ) exfactory on exfactory.company_id=companies.id
        where companies.id in(1,2,4)
        order by companies.id

        

        ",[ $date_from, $date_from, $date_to, $date_from,$date_from, $date_to])
        )
        ->map(function($gmts){
            $gmts->opening_qty=$gmts->car_open_qty-$gmts->exf_open_qty;
            $gmts->stock_qty=$gmts->opening_qty+($gmts->car_qty-$gmts->exf_qty);
            $gmts->opening_qty=number_format($gmts->opening_qty,0);
            $gmts->car_qty=number_format($gmts->car_qty,0);
            $gmts->exf_qty=number_format($gmts->exf_qty,0);
            $gmts->stock_qty=number_format($gmts->stock_qty,0);
            return $gmts;
        });


        $general = collect(
        \DB::select("
            select
            m.itemcategory_id,
            m.itemcategory_name,
            sum(m.open_receive_qty) as open_receive_qty,
            sum(m.open_receive_amount) as open_receive_amount,
            sum(m.receive_qty) as receive_qty,
            sum(m.receive_amount) as receive_amount,

            sum(m.open_issue_qty) as open_issue_qty,
            sum(m.open_issue_amount) as open_issue_amount,
            sum(m.issue_qty) as issue_qty,
            sum(m.issue_amount) as issue_amount
            from
            (
            select
            itemcategories.id as itemcategory_id,
            itemcategories.name as itemcategory_name,
            itemclasses.name as itemclass_name,
            item_accounts.id,
            general_rcv.qty as receive_qty,
            general_rcv.amount as receive_amount,
            open_general_rcv.qty as open_receive_qty,
            open_general_rcv.amount as open_receive_amount,
            general_isu.qty as issue_qty,
            general_isu.amount as issue_amount,
            open_general_isu.qty as open_issue_qty,
            open_general_isu.amount as open_issue_amount
            from
            item_accounts
            join itemclasses on itemclasses.id=item_accounts.itemclass_id
            join itemcategories on itemcategories.id=item_accounts.itemcategory_id

            join (
            select 
            inv_general_rcv_items.item_account_id,
            sum(inv_general_transactions.store_qty) as qty,
            sum(inv_general_transactions.store_amount) as amount
            from inv_general_rcv_items
            join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
            join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
            join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
            where inv_rcvs.receive_date <= ?
            and inv_general_transactions.deleted_at is null
            and inv_general_rcv_items.deleted_at is null
            and inv_rcvs.deleted_at is null
            and inv_general_transactions.trans_type_id=1
            group by inv_general_rcv_items.item_account_id
            ) general_rcv_all on general_rcv_all.item_account_id=item_accounts.id

            left join (
            select 
            inv_general_rcv_items.item_account_id,
            sum(inv_general_transactions.store_qty) as qty,
            sum(inv_general_transactions.store_amount) as amount
            from inv_general_rcv_items
            join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
            join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
            join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
            where inv_rcvs.receive_date < ?
            and inv_general_transactions.deleted_at is null
            and inv_general_rcv_items.deleted_at is null
            and inv_rcvs.deleted_at is null
            and inv_general_transactions.trans_type_id=1
            group by inv_general_rcv_items.item_account_id
            ) open_general_rcv on open_general_rcv.item_account_id=general_rcv_all.item_account_id

            left join (
            select 
            inv_general_rcv_items.item_account_id,
            sum(inv_general_transactions.store_qty) as qty,
            sum(inv_general_transactions.store_amount) as amount
            from inv_general_rcv_items
            join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
            join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
            join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
            where inv_rcvs.receive_date>=?
            and inv_rcvs.receive_date<=?
            and inv_general_transactions.deleted_at is null
            and inv_general_rcv_items.deleted_at is null
            and inv_rcvs.deleted_at is null
            and inv_general_transactions.trans_type_id=1
            group by inv_general_rcv_items.item_account_id
            ) general_rcv on general_rcv.item_account_id=general_rcv_all.item_account_id
            left join (
            select 
            inv_general_isu_items.item_account_id,
            sum(inv_general_transactions.store_qty) as qty,
            sum(inv_general_transactions.store_amount) as amount
            from inv_general_isu_items
            join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
            join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
            where inv_isus.issue_date < ?
            and inv_general_transactions.deleted_at is null
            and inv_general_isu_items.deleted_at is null
            and inv_isus.deleted_at is null
            and inv_general_transactions.trans_type_id=2
            group by inv_general_isu_items.item_account_id
            ) open_general_isu on open_general_isu.item_account_id=general_rcv_all.item_account_id
            left join (
            select 
            inv_general_isu_items.item_account_id,
            sum(inv_general_transactions.store_qty) as qty,
            sum(inv_general_transactions.store_amount) as amount
            from inv_general_isu_items
            join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
            join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
            where inv_isus.issue_date>=?
            and inv_isus.issue_date<=?
            and inv_general_transactions.deleted_at is null
            and inv_general_isu_items.deleted_at is null
            and inv_isus.deleted_at is null
            and inv_general_transactions.trans_type_id=2
            group by inv_general_isu_items.item_account_id
            ) general_isu on general_isu.item_account_id=general_rcv_all.item_account_id
            where itemcategories.identity=9
            order by itemcategories.id
            ) m
            group by
            m.itemcategory_id,
            m.itemcategory_name
            order by 
            m.itemcategory_name
        ",[ $date_to, $date_from, $date_from, $date_to, $date_from,$date_from, $date_to])
        )
        ->map(function($general){
            $general->opening_qty=$general->open_receive_qty+$general->open_issue_qty;
            $general->opening_amount=$general->open_receive_amount-$general->open_issue_amount;
            $general->stock_qty=$general->opening_qty+($general->receive_qty+$general->issue_qty);
            $general->stock_amount=$general->opening_amount+($general->receive_amount-$general->issue_amount);
            //$general->opening_qty=number_format($general->opening_qty,0);
            //$general->opening_amount=number_format($general->opening_amount,0);
            //$general->receive_qty=number_format($general->receive_qty,0);
            //$general->receive_amount=number_format($general->receive_amount,0);
            //$general->issue_qty=number_format($general->issue_qty,0);
            //$general->issue_amount=number_format($general->issue_amount,0);
            //$general->stock_qty=number_format($general->stock_qty,0);
            //$general->stock_amount=number_format($general->stock_amount,0);
            return $general;
        });






    	return Template::loadView('Report.TodayInventoryReportData',[
            'yarn_arr'=>$yarn_arr,
            'dyechems'=>$dyechem,
            'greyfabs'=>$greyfab,
            'subcondyeings'=>$subcondyeing,
            'subconaops'=>$subconaop,
            'subconknitings'=>$subconkniting,
            'gmts'=>$gmts,
            'generals'=>$general,
        ]);
    }

    public function generalRcv(){
        $item_category_id=request('item_category_id',0);
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $company_id=request('company_id',0);
        $store_id=request('store_id',0);
        $companyCond='';
        if($company_id){
        $companyCond= ' and inv_general_transactions.company_id= '.$company_id;
        }
        else{
        $companyCond= '';
        }

        $storeCond='';
        if($store_id){
        $storeCond= ' and inv_general_transactions.store_id= '.$store_id;
        }
        else{
        $storeCond= '';
        }
        $invgeneralrcvitem=$this->itemaccount
      ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
      })
      ->join('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
      })
      ->join('uoms',function($join){
      $join->on('uoms.id','=','item_accounts.uom_id');
      })
      ->join(\DB::raw("(
      select 
      inv_general_rcv_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_rcv_items
      join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
      join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_general_transactions.deleted_at is null
      and inv_general_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_general_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_general_rcv_items.item_account_id
      ) all_rcv"), "all_rcv.item_account_id", "=", "item_accounts.id")
      ->leftJoin(\DB::raw("(
      select 
      inv_general_rcv_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_rcv_items
      join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
      join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
      where inv_rcvs.receive_date <'".$date_from."'
      and inv_general_transactions.deleted_at is null
      and inv_general_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_general_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_general_rcv_items.item_account_id
      ) open_general_rcv"), "open_general_rcv.item_account_id", "=", "all_rcv.item_account_id")
      ->leftJoin(\DB::raw("(
      select 
      inv_general_rcv_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_rcv_items
      join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
      join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id in (1,2,3)
      and inv_general_transactions.deleted_at is null
      and inv_general_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_general_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_general_rcv_items.item_account_id

      ) pur_rcv"), "pur_rcv.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_rcv_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_rcv_items
      join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
      join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id = 9
      and inv_general_transactions.deleted_at is null
      and inv_general_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_general_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_general_rcv_items.item_account_id

      ) trans_in_rcv"), "trans_in_rcv.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_rcv_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_rcv_items
      join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
      join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id = 4
      and inv_general_transactions.deleted_at is null
      and inv_general_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_general_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_general_rcv_items.item_account_id

      ) isu_rtn_rcv"), "isu_rtn_rcv.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_rcv_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_rcv_items
      join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
      join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_general_transactions.deleted_at is null
      and inv_general_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_general_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_general_rcv_items.item_account_id

      ) general_rcv"), "general_rcv.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_isu_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_isu_items
      join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
      join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
      where inv_isus.issue_date<'".$date_from."'
      and inv_general_transactions.deleted_at is null
      and inv_general_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_general_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_general_isu_items.item_account_id

      ) open_general_isu"), "open_general_isu.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_isu_items.item_account_id,
      abs(sum(inv_general_transactions.store_qty)) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_isu_items
      join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
      join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id in (1,2)
      and inv_general_transactions.deleted_at is null
      and inv_general_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_general_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_general_isu_items.item_account_id
      ) regular_isu"), "regular_isu.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_isu_items.item_account_id,
      abs(sum(inv_general_transactions.store_qty)) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_isu_items
      join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
      join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id  = 9
      and inv_general_transactions.deleted_at is null
      and inv_general_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_general_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_general_isu_items.item_account_id
      ) trans_out_isu"), "trans_out_isu.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_isu_items.item_account_id,
      abs(sum(inv_general_transactions.store_qty)) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_isu_items
      join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
      join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id  = 11
      and inv_general_transactions.deleted_at is null
      and inv_general_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_general_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_general_isu_items.item_account_id
      ) rcv_rtn_isu"), "rcv_rtn_isu.item_account_id", "=", "all_rcv.item_account_id")
      
      ->leftJoin(\DB::raw("(
      select 
      inv_general_isu_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_isu_items
      join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
      join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'
      and inv_general_transactions.deleted_at is null
      and inv_general_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_general_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_general_isu_items.item_account_id
      ) general_isu"), "general_isu.item_account_id", "=", "all_rcv.item_account_id")

      


      

      
      
        ->where([['itemcategories.identity','=',9]])
        ->when(request('item_category_id'), function ($q) {
        return $q->where('itemcategories.id', '=', request('item_category_id', 0));
        })
        ->when(request('consumption_level_id'), function ($q) {
        return $q->where('item_accounts.consumption_level_id', '=', request('consumption_level_id', 0));
        })
      ->orderBy('itemcategories.id')
      ->orderBy('itemclasses.id')
      ->orderBy('item_accounts.item_description')
      ->get([
      'itemcategories.name as itemcategory_name',
      'itemclasses.name as itemclass_name',
      'item_accounts.id',
      'item_accounts.item_description',
      'item_accounts.specification',
      'uoms.code as uom_code',

      'pur_rcv.qty as pur_qty',
      'trans_in_rcv.qty as trans_in_qty',
      'isu_rtn_rcv.qty as isu_rtn_qty',
      'general_rcv.qty as receive_qty',
      'general_rcv.amount as receive_amount',
      'open_general_rcv.qty as open_receive_qty',
      'open_general_rcv.amount as open_receive_amount',

      'regular_isu.qty as regular_issue_qty',
      'trans_out_isu.qty as trans_out_issue_qty',
      'rcv_rtn_isu.qty as rcv_rtn_issue_qty',
      'general_isu.qty as issue_qty',
      'general_isu.amount as issue_amount',
      'open_general_isu.qty as open_issue_qty',
      'open_general_isu.amount as open_issue_amount',
      ])
      ->map(function($invgeneralrcvitem)  {
      $invgeneralrcvitem->item_desc=$invgeneralrcvitem->item_description.", ".$invgeneralrcvitem->specification;

      $invgeneralrcvitem->issue_qty=$invgeneralrcvitem->issue_qty*-1;

      $invgeneralrcvitem->issue_amount=$invgeneralrcvitem->issue_amount;

      $invgeneralrcvitem->opening_qty=$invgeneralrcvitem->open_receive_qty-($invgeneralrcvitem->open_issue_qty*-1);
      $invgeneralrcvitem->opening_amount=$invgeneralrcvitem->open_receive_amount-($invgeneralrcvitem->open_issue_amount);
      $invgeneralrcvitem->stock_qty=($invgeneralrcvitem->opening_qty+$invgeneralrcvitem->receive_qty)-($invgeneralrcvitem->issue_qty);
      $invgeneralrcvitem->stock_value=($invgeneralrcvitem->opening_amount+$invgeneralrcvitem->receive_amount)-($invgeneralrcvitem->issue_amount);
      $invgeneralrcvitem->rate=0;
      if($invgeneralrcvitem->stock_qty){
      $invgeneralrcvitem->rate=$invgeneralrcvitem->stock_value/$invgeneralrcvitem->stock_qty;
      }

      

      

       


      $invgeneralrcvitem->opening_qty=number_format($invgeneralrcvitem->opening_qty,2);
      $invgeneralrcvitem->pur_qty=number_format($invgeneralrcvitem->pur_qty,2);
      $invgeneralrcvitem->trans_in_qty=number_format($invgeneralrcvitem->trans_in_qty,2);
      $invgeneralrcvitem->isu_rtn_qty=number_format($invgeneralrcvitem->isu_rtn_qty,2);
      $invgeneralrcvitem->receive_qty=number_format($invgeneralrcvitem->receive_qty,2);
      $invgeneralrcvitem->receive_amount=number_format($invgeneralrcvitem->receive_amount,2);
      $invgeneralrcvitem->regular_issue_qty=number_format($invgeneralrcvitem->regular_issue_qty,2);
      $invgeneralrcvitem->trans_out_issue_qty=number_format($invgeneralrcvitem->trans_out_issue_qty,2);
      $invgeneralrcvitem->rcv_rtn_issue_qty=number_format($invgeneralrcvitem->rcv_rtn_issue_qty,2);
      $invgeneralrcvitem->issue_qty=number_format($invgeneralrcvitem->issue_qty,2);
      $invgeneralrcvitem->issue_amount=number_format($invgeneralrcvitem->issue_amount,2);
      $invgeneralrcvitem->stock_qty=number_format($invgeneralrcvitem->stock_qty,2);
      $invgeneralrcvitem->rate=number_format($invgeneralrcvitem->rate,2);
      $invgeneralrcvitem->stock_value=number_format($invgeneralrcvitem->stock_value,2);
      return $invgeneralrcvitem;
      })
      /*->filter(function($invgeneralrcvitem){
            if($invgeneralrcvitem->opening_qty*1 || $invgeneralrcvitem->receive_qty*1){
                  return $invgeneralrcvitem;
            }

      })
      ->values()*/; 
      echo json_encode($invgeneralrcvitem);

    }

    public function generalIsu(){
        $item_category_id=request('item_category_id',0);
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $company_id=request('company_id',0);
        $store_id=request('store_id',0);
        $companyCond='';
        if($company_id){
        $companyCond= ' and inv_general_transactions.company_id= '.$company_id;
        }
        else{
        $companyCond= '';
        }

        $storeCond='';
        if($store_id){
        $storeCond= ' and inv_general_transactions.store_id= '.$store_id;
        }
        else{
        $storeCond= '';
        }
        $invgeneralrcvitem=$this->itemaccount
      ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
      })
      ->join('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
      })
      ->join('uoms',function($join){
      $join->on('uoms.id','=','item_accounts.uom_id');
      })
      ->join(\DB::raw("(
      select 
      inv_general_isu_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_isu_items
      join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
      join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'
      and inv_general_transactions.deleted_at is null
      and inv_general_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_general_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_general_isu_items.item_account_id
      ) all_rcv"), "all_rcv.item_account_id", "=", "item_accounts.id")
      ->leftJoin(\DB::raw("(
      select 
      inv_general_rcv_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_rcv_items
      join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
      join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
      where inv_rcvs.receive_date <'".$date_from."'
      and inv_general_transactions.deleted_at is null
      and inv_general_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_general_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_general_rcv_items.item_account_id
      ) open_general_rcv"), "open_general_rcv.item_account_id", "=", "all_rcv.item_account_id")
      ->leftJoin(\DB::raw("(
      select 
      inv_general_rcv_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_rcv_items
      join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
      join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id in (1,2,3)
      and inv_general_transactions.deleted_at is null
      and inv_general_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_general_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_general_rcv_items.item_account_id

      ) pur_rcv"), "pur_rcv.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_rcv_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_rcv_items
      join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
      join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id = 9
      and inv_general_transactions.deleted_at is null
      and inv_general_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_general_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_general_rcv_items.item_account_id

      ) trans_in_rcv"), "trans_in_rcv.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_rcv_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_rcv_items
      join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
      join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id = 4
      and inv_general_transactions.deleted_at is null
      and inv_general_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_general_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_general_rcv_items.item_account_id

      ) isu_rtn_rcv"), "isu_rtn_rcv.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_rcv_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_rcv_items
      join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
      join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_general_transactions.deleted_at is null
      and inv_general_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_general_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_general_rcv_items.item_account_id

      ) general_rcv"), "general_rcv.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_isu_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_isu_items
      join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
      join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
      where inv_isus.issue_date<'".$date_from."'
      and inv_general_transactions.deleted_at is null
      and inv_general_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_general_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_general_isu_items.item_account_id

      ) open_general_isu"), "open_general_isu.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_isu_items.item_account_id,
      abs(sum(inv_general_transactions.store_qty)) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_isu_items
      join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
      join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id in (1,2)
      and inv_general_transactions.deleted_at is null
      and inv_general_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_general_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_general_isu_items.item_account_id
      ) regular_isu"), "regular_isu.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_isu_items.item_account_id,
      abs(sum(inv_general_transactions.store_qty)) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_isu_items
      join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
      join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id  = 9
      and inv_general_transactions.deleted_at is null
      and inv_general_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_general_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_general_isu_items.item_account_id
      ) trans_out_isu"), "trans_out_isu.item_account_id", "=", "all_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_general_isu_items.item_account_id,
      abs(sum(inv_general_transactions.store_qty)) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_isu_items
      join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
      join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id  = 11
      and inv_general_transactions.deleted_at is null
      and inv_general_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_general_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_general_isu_items.item_account_id
      ) rcv_rtn_isu"), "rcv_rtn_isu.item_account_id", "=", "all_rcv.item_account_id")
      
      ->leftJoin(\DB::raw("(
      select 
      inv_general_isu_items.item_account_id,
      sum(inv_general_transactions.store_qty) as qty,
      sum(inv_general_transactions.store_amount) as amount
      from inv_general_isu_items
      join inv_general_transactions on inv_general_transactions.inv_general_isu_item_id=inv_general_isu_items.id
      join inv_isus on inv_isus.id=inv_general_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'
      and inv_general_transactions.deleted_at is null
      and inv_general_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_general_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_general_isu_items.item_account_id
      ) general_isu"), "general_isu.item_account_id", "=", "all_rcv.item_account_id")

      


      

      
      
        ->where([['itemcategories.identity','=',9]])
        ->when(request('item_category_id'), function ($q) {
        return $q->where('itemcategories.id', '=', request('item_category_id', 0));
        })
        ->when(request('consumption_level_id'), function ($q) {
        return $q->where('item_accounts.consumption_level_id', '=', request('consumption_level_id', 0));
        })
      ->orderBy('itemcategories.id')
      ->orderBy('itemclasses.id')
      ->orderBy('item_accounts.item_description')
      ->get([
      'itemcategories.name as itemcategory_name',
      'itemclasses.name as itemclass_name',
      'item_accounts.id',
      'item_accounts.item_description',
      'item_accounts.specification',
      'uoms.code as uom_code',

      'pur_rcv.qty as pur_qty',
      'trans_in_rcv.qty as trans_in_qty',
      'isu_rtn_rcv.qty as isu_rtn_qty',
      'general_rcv.qty as receive_qty',
      'general_rcv.amount as receive_amount',
      'open_general_rcv.qty as open_receive_qty',
      'open_general_rcv.amount as open_receive_amount',

      'regular_isu.qty as regular_issue_qty',
      'trans_out_isu.qty as trans_out_issue_qty',
      'rcv_rtn_isu.qty as rcv_rtn_issue_qty',
      'general_isu.qty as issue_qty',
      'general_isu.amount as issue_amount',
      'open_general_isu.qty as open_issue_qty',
      'open_general_isu.amount as open_issue_amount',
      ])
      ->map(function($invgeneralrcvitem)  {
      $invgeneralrcvitem->item_desc=$invgeneralrcvitem->item_description.", ".$invgeneralrcvitem->specification;

      $invgeneralrcvitem->issue_qty=$invgeneralrcvitem->issue_qty*-1;

      $invgeneralrcvitem->issue_amount=$invgeneralrcvitem->issue_amount;

      $invgeneralrcvitem->opening_qty=$invgeneralrcvitem->open_receive_qty-($invgeneralrcvitem->open_issue_qty*-1);
      $invgeneralrcvitem->opening_amount=$invgeneralrcvitem->open_receive_amount-($invgeneralrcvitem->open_issue_amount);
      $invgeneralrcvitem->stock_qty=($invgeneralrcvitem->opening_qty+$invgeneralrcvitem->receive_qty)-($invgeneralrcvitem->issue_qty);
      $invgeneralrcvitem->stock_value=($invgeneralrcvitem->opening_amount+$invgeneralrcvitem->receive_amount)-($invgeneralrcvitem->issue_amount);
      $invgeneralrcvitem->rate=0;
      if($invgeneralrcvitem->stock_qty){
      $invgeneralrcvitem->rate=$invgeneralrcvitem->stock_value/$invgeneralrcvitem->stock_qty;
      }

      

      

       


      $invgeneralrcvitem->opening_qty=number_format($invgeneralrcvitem->opening_qty,2);
      $invgeneralrcvitem->pur_qty=number_format($invgeneralrcvitem->pur_qty,2);
      $invgeneralrcvitem->trans_in_qty=number_format($invgeneralrcvitem->trans_in_qty,2);
      $invgeneralrcvitem->isu_rtn_qty=number_format($invgeneralrcvitem->isu_rtn_qty,2);
      $invgeneralrcvitem->receive_qty=number_format($invgeneralrcvitem->receive_qty,2);
      $invgeneralrcvitem->receive_amount=number_format($invgeneralrcvitem->receive_amount,2);
      $invgeneralrcvitem->regular_issue_qty=number_format($invgeneralrcvitem->regular_issue_qty,2);
      $invgeneralrcvitem->trans_out_issue_qty=number_format($invgeneralrcvitem->trans_out_issue_qty,2);
      $invgeneralrcvitem->rcv_rtn_issue_qty=number_format($invgeneralrcvitem->rcv_rtn_issue_qty,2);
      $invgeneralrcvitem->issue_qty=number_format($invgeneralrcvitem->issue_qty,2);
      $invgeneralrcvitem->issue_amount=number_format($invgeneralrcvitem->issue_amount,2);
      $invgeneralrcvitem->stock_qty=number_format($invgeneralrcvitem->stock_qty,2);
      $invgeneralrcvitem->rate=number_format($invgeneralrcvitem->rate,2);
      $invgeneralrcvitem->stock_value=number_format($invgeneralrcvitem->stock_value,2);
      return $invgeneralrcvitem;
      })
      /*->filter(function($invgeneralrcvitem){
            if($invgeneralrcvitem->opening_qty*1 || $invgeneralrcvitem->receive_qty*1){
                  return $invgeneralrcvitem;
            }

      })
      ->values()*/; 
      echo json_encode($invgeneralrcvitem);

    }

    public function yarnRcv(){
        $store_id=request('store_id',0);
      $date_from=request('date_from',0);
      $date_to=request('date_to',0);
      $start_date=date('Y-m-d', strtotime($date_from));
      $yesterday = date('Y-m-d', strtotime('-1 days', strtotime($start_date)));
      $storeCond='';
      if($store_id){
       $storeCond= ' and inv_yarn_transactions.store_id= '.$store_id;
      }
      else{
       $storeCond= '';
      }
      $yarnDescription=$this->itemaccount
      ->leftJoin('item_account_ratios',function($join){
      $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
      })
      ->leftJoin('compositions',function($join){
      $join->on('compositions.id','=','item_account_ratios.composition_id');
      })
      ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
      })
      ->leftJoin('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
      })

      ->where([['itemcategories.identity','=',1]])
      ->orderBy('item_account_ratios.ratio','desc')
      ->get([
      'item_accounts.id',
      'compositions.name as composition_name',
      'item_account_ratios.ratio',
      ]);

      $itemaccountArr=array();
      $yarnCompositionArr=array();
      foreach($yarnDescription as $row){
      $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
      $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
      }

      $yarnDropdown=array();
      foreach($itemaccountArr as $key=>$value){
      $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
      }

      $invyarnrcvitem=$this->invyarnitem
      ->join('item_accounts',function($join){
      $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
      })
      ->leftJoin('yarncounts',function($join){
      $join->on('yarncounts.id','=','item_accounts.yarncount_id');
      })
      ->leftJoin('yarntypes',function($join){
      $join->on('yarntypes.id','=','item_accounts.yarntype_id');
      })
      ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
      })
      ->join('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
      })
      ->join('uoms',function($join){
      $join->on('uoms.id','=','item_accounts.uom_id');
      })
      ->join('colors',function($join){
      $join->on('colors.id','=','inv_yarn_items.color_id');
      })
      ->join('suppliers',function($join){
      $join->on('suppliers.id','=','inv_yarn_items.supplier_id');
      })
      ->join(\DB::raw("(
      select 
      inv_yarn_rcv_items.inv_yarn_item_id,
      sum(inv_yarn_transactions.store_qty) as qty,
      sum(inv_yarn_transactions.store_amount) as amount
      from inv_yarn_rcv_items
      join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
      join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_yarn_transactions.deleted_at is null
      and inv_yarn_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_yarn_transactions.trans_type_id=1
      $storeCond
      group by inv_yarn_rcv_items.inv_yarn_item_id

      ) yarn_rcv"), "yarn_rcv.inv_yarn_item_id", "=", "inv_yarn_items.id")
      ->leftJoin(\DB::raw("(
      select 
      inv_yarn_rcv_items.inv_yarn_item_id,
      sum(inv_yarn_transactions.store_qty) as qty,
      sum(inv_yarn_transactions.store_amount) as amount
      from inv_yarn_rcv_items
      join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
      join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
      where inv_rcvs.receive_date<'".$date_from."'
      and inv_yarn_transactions.deleted_at is null
      and inv_yarn_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_yarn_transactions.trans_type_id=1
      $storeCond
      group by inv_yarn_rcv_items.inv_yarn_item_id

      ) open_yarn_rcv"), "open_yarn_rcv.inv_yarn_item_id", "=", "yarn_rcv.inv_yarn_item_id")
      ->leftJoin(\DB::raw("(
      select 
      inv_yarn_isu_items.inv_yarn_item_id,
      sum(inv_yarn_transactions.store_qty) as qty,
      sum(inv_yarn_transactions.store_amount) as amount
      from inv_yarn_isu_items
      join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_isu_item_id=inv_yarn_isu_items.id
      join inv_isus on inv_isus.id=inv_yarn_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'
      and inv_yarn_transactions.deleted_at is null
      and inv_yarn_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_yarn_transactions.trans_type_id=2
      $storeCond
      group by inv_yarn_isu_items.inv_yarn_item_id

      ) yarn_isu"), "yarn_isu.inv_yarn_item_id", "=", "yarn_rcv.inv_yarn_item_id")
      ->leftJoin(\DB::raw("(
      select 
      inv_yarn_isu_items.inv_yarn_item_id,
      sum(inv_yarn_transactions.store_qty) as qty,
      sum(inv_yarn_transactions.store_amount) as amount
      from inv_yarn_isu_items
      join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_isu_item_id=inv_yarn_isu_items.id
      join inv_isus on inv_isus.id=inv_yarn_isu_items.inv_isu_id
      where inv_isus.issue_date<'".$date_from."'
      and inv_yarn_transactions.deleted_at is null
      and inv_yarn_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_yarn_transactions.trans_type_id=2
      $storeCond
      group by inv_yarn_isu_items.inv_yarn_item_id

      ) open_yarn_isu"), "open_yarn_isu.inv_yarn_item_id", "=", "yarn_rcv.inv_yarn_item_id")
      ->orderBy('yarncounts.count','desc')
      ->get([
      'itemcategories.name as itemcategory_name',
      'itemclasses.name as itemclass_name',
      'item_accounts.id as item_account_id',
      'yarncounts.count',
      'yarncounts.symbol',
      'yarntypes.name as yarn_type',
      'uoms.code as uom_code',
      'inv_yarn_items.id',
      'inv_yarn_items.lot',
      'inv_yarn_items.brand',
      'colors.name as color_name',
      'suppliers.name as supplier_name',
      'yarn_rcv.qty as receive_qty',
      'yarn_rcv.amount as receive_amount',
      'open_yarn_rcv.qty as open_receive_qty',
      'open_yarn_rcv.amount as open_receive_amount',
      'yarn_isu.qty as issue_qty',
      'yarn_isu.amount as issue_amount',
      'open_yarn_isu.qty as open_issue_qty',
      'open_yarn_isu.amount as open_issue_amount',
      ])
      ->map(function($invyarnrcvitem) use($yarnDropdown) {
      $invyarnrcvitem->count_name=$invyarnrcvitem->count."/".$invyarnrcvitem->symbol;
      $invyarnrcvitem->type_name=$invyarnrcvitem->yarn_type;
      $invyarnrcvitem->composition=isset($yarnDropdown[$invyarnrcvitem->item_account_id])?$yarnDropdown[$invyarnrcvitem->item_account_id]:'';
      $invyarnrcvitem->issue_qty=$invyarnrcvitem->issue_qty*-1;

      $invyarnrcvitem->issue_amount=$invyarnrcvitem->issue_amount;

      $invyarnrcvitem->opening_qty=$invyarnrcvitem->open_receive_qty-($invyarnrcvitem->open_issue_qty*-1);
      $invyarnrcvitem->opening_amount=$invyarnrcvitem->open_receive_amount-($invyarnrcvitem->open_issue_amount);
      $invyarnrcvitem->stock_qty=($invyarnrcvitem->opening_qty+$invyarnrcvitem->receive_qty)-($invyarnrcvitem->issue_qty);
      $invyarnrcvitem->stock_value=($invyarnrcvitem->opening_amount+$invyarnrcvitem->receive_amount)-($invyarnrcvitem->issue_amount);
      $invyarnrcvitem->rate=0;
      if($invyarnrcvitem->stock_qty){
      $invyarnrcvitem->rate=$invyarnrcvitem->stock_value/$invyarnrcvitem->stock_qty;
      }
      $invyarnrcvitem->opening_qty=number_format($invyarnrcvitem->opening_qty,2);
      $invyarnrcvitem->receive_qty=number_format($invyarnrcvitem->receive_qty,2);
      $invyarnrcvitem->receive_amount=number_format($invyarnrcvitem->receive_amount,2);
      $invyarnrcvitem->issue_qty=number_format($invyarnrcvitem->issue_qty,2);
      $invyarnrcvitem->issue_amount=number_format($invyarnrcvitem->issue_amount,2);
      $invyarnrcvitem->stock_qty=number_format($invyarnrcvitem->stock_qty,2);
      $invyarnrcvitem->rate=number_format($invyarnrcvitem->rate,2);
      $invyarnrcvitem->stock_value=number_format($invyarnrcvitem->stock_value,2);
      return $invyarnrcvitem;
      }); 
      echo json_encode($invyarnrcvitem);
    }

    public function yarnIsu(){
        $store_id=request('store_id',0);
      $date_from=request('date_from',0);
      $date_to=request('date_to',0);
      $start_date=date('Y-m-d', strtotime($date_from));
      $yesterday = date('Y-m-d', strtotime('-1 days', strtotime($start_date)));
      $storeCond='';
      if($store_id){
       $storeCond= ' and inv_yarn_transactions.store_id= '.$store_id;
      }
      else{
       $storeCond= '';
      }
      $yarnDescription=$this->itemaccount
      ->leftJoin('item_account_ratios',function($join){
      $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
      })
      ->leftJoin('compositions',function($join){
      $join->on('compositions.id','=','item_account_ratios.composition_id');
      })
      ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
      })
      ->leftJoin('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
      })

      ->where([['itemcategories.identity','=',1]])
      ->orderBy('item_account_ratios.ratio','desc')
      ->get([
      'item_accounts.id',
      'compositions.name as composition_name',
      'item_account_ratios.ratio',
      ]);

      $itemaccountArr=array();
      $yarnCompositionArr=array();
      foreach($yarnDescription as $row){
      $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
      $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
      }

      $yarnDropdown=array();
      foreach($itemaccountArr as $key=>$value){
      $yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
      }

      $invyarnrcvitem=$this->invyarnitem
      ->join('item_accounts',function($join){
      $join->on('inv_yarn_items.item_account_id','=','item_accounts.id');
      })
      ->leftJoin('yarncounts',function($join){
      $join->on('yarncounts.id','=','item_accounts.yarncount_id');
      })
      ->leftJoin('yarntypes',function($join){
      $join->on('yarntypes.id','=','item_accounts.yarntype_id');
      })
      ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
      })
      ->join('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
      })
      ->join('uoms',function($join){
      $join->on('uoms.id','=','item_accounts.uom_id');
      })
      ->join('colors',function($join){
      $join->on('colors.id','=','inv_yarn_items.color_id');
      })
      ->join('suppliers',function($join){
      $join->on('suppliers.id','=','inv_yarn_items.supplier_id');
      })
      ->join(\DB::raw("(
      select 
      inv_yarn_isu_items.inv_yarn_item_id,
      sum(inv_yarn_transactions.store_qty) as qty,
      sum(inv_yarn_transactions.store_amount) as amount
      from inv_yarn_isu_items
      join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_isu_item_id=inv_yarn_isu_items.id
      join inv_isus on inv_isus.id=inv_yarn_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'
      and inv_yarn_transactions.deleted_at is null
      and inv_yarn_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_yarn_transactions.trans_type_id=2
      $storeCond
      group by inv_yarn_isu_items.inv_yarn_item_id

      ) yarn_isu"), "yarn_isu.inv_yarn_item_id", "=", "inv_yarn_items.id")
      ->leftJoin(\DB::raw("(
      select 
      inv_yarn_isu_items.inv_yarn_item_id,
      sum(inv_yarn_transactions.store_qty) as qty,
      sum(inv_yarn_transactions.store_amount) as amount
      from inv_yarn_isu_items
      join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_isu_item_id=inv_yarn_isu_items.id
      join inv_isus on inv_isus.id=inv_yarn_isu_items.inv_isu_id
      where inv_isus.issue_date<'".$date_from."'
      and inv_yarn_transactions.deleted_at is null
      and inv_yarn_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_yarn_transactions.trans_type_id=2
      $storeCond
      group by inv_yarn_isu_items.inv_yarn_item_id

      ) open_yarn_isu"), "open_yarn_isu.inv_yarn_item_id", "=", "yarn_isu.inv_yarn_item_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_yarn_rcv_items.inv_yarn_item_id,
      sum(inv_yarn_transactions.store_qty) as qty,
      sum(inv_yarn_transactions.store_amount) as amount
      from inv_yarn_rcv_items
      join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
      join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_yarn_transactions.deleted_at is null
      and inv_yarn_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_yarn_transactions.trans_type_id=1
      $storeCond
      group by inv_yarn_rcv_items.inv_yarn_item_id

      ) yarn_rcv"), "yarn_rcv.inv_yarn_item_id", "=", "yarn_isu.inv_yarn_item_id")
      ->leftJoin(\DB::raw("(
      select 
      inv_yarn_rcv_items.inv_yarn_item_id,
      sum(inv_yarn_transactions.store_qty) as qty,
      sum(inv_yarn_transactions.store_amount) as amount
      from inv_yarn_rcv_items
      join inv_yarn_transactions on inv_yarn_transactions.inv_yarn_rcv_item_id=inv_yarn_rcv_items.id
      join inv_yarn_rcvs on inv_yarn_rcvs.id=inv_yarn_rcv_items.inv_yarn_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_yarn_rcvs.inv_rcv_id
      where inv_rcvs.receive_date<'".$date_from."'
      and inv_yarn_transactions.deleted_at is null
      and inv_yarn_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_yarn_transactions.trans_type_id=1
      $storeCond
      group by inv_yarn_rcv_items.inv_yarn_item_id

      ) open_yarn_rcv"), "open_yarn_rcv.inv_yarn_item_id", "=", "yarn_isu.inv_yarn_item_id")
      
      ->orderBy('yarncounts.count','desc')
      ->get([
      'itemcategories.name as itemcategory_name',
      'itemclasses.name as itemclass_name',
      'item_accounts.id as item_account_id',
      'yarncounts.count',
      'yarncounts.symbol',
      'yarntypes.name as yarn_type',
      'uoms.code as uom_code',
      'inv_yarn_items.id',
      'inv_yarn_items.lot',
      'inv_yarn_items.brand',
      'colors.name as color_name',
      'suppliers.name as supplier_name',
      'yarn_rcv.qty as receive_qty',
      'yarn_rcv.amount as receive_amount',
      'open_yarn_rcv.qty as open_receive_qty',
      'open_yarn_rcv.amount as open_receive_amount',
      'yarn_isu.qty as issue_qty',
      'yarn_isu.amount as issue_amount',
      'open_yarn_isu.qty as open_issue_qty',
      'open_yarn_isu.amount as open_issue_amount',
      ])
      ->map(function($invyarnrcvitem) use($yarnDropdown) {
      $invyarnrcvitem->count_name=$invyarnrcvitem->count."/".$invyarnrcvitem->symbol;
      $invyarnrcvitem->type_name=$invyarnrcvitem->yarn_type;
      $invyarnrcvitem->composition=isset($yarnDropdown[$invyarnrcvitem->item_account_id])?$yarnDropdown[$invyarnrcvitem->item_account_id]:'';
      $invyarnrcvitem->issue_qty=$invyarnrcvitem->issue_qty*-1;

      $invyarnrcvitem->issue_amount=$invyarnrcvitem->issue_amount;

      $invyarnrcvitem->opening_qty=$invyarnrcvitem->open_receive_qty-($invyarnrcvitem->open_issue_qty*-1);
      $invyarnrcvitem->opening_amount=$invyarnrcvitem->open_receive_amount-($invyarnrcvitem->open_issue_amount);
      $invyarnrcvitem->stock_qty=($invyarnrcvitem->opening_qty+$invyarnrcvitem->receive_qty)-($invyarnrcvitem->issue_qty);
      $invyarnrcvitem->stock_value=($invyarnrcvitem->opening_amount+$invyarnrcvitem->receive_amount)-($invyarnrcvitem->issue_amount);
      $invyarnrcvitem->rate=0;
      if($invyarnrcvitem->stock_qty){
      $invyarnrcvitem->rate=$invyarnrcvitem->stock_value/$invyarnrcvitem->stock_qty;
      }
      $invyarnrcvitem->opening_qty=number_format($invyarnrcvitem->opening_qty,2);
      $invyarnrcvitem->receive_qty=number_format($invyarnrcvitem->receive_qty,2);
      $invyarnrcvitem->receive_amount=number_format($invyarnrcvitem->receive_amount,2);
      $invyarnrcvitem->issue_qty=number_format($invyarnrcvitem->issue_qty,2);
      $invyarnrcvitem->issue_amount=number_format($invyarnrcvitem->issue_amount,2);
      $invyarnrcvitem->stock_qty=number_format($invyarnrcvitem->stock_qty,2);
      $invyarnrcvitem->rate=number_format($invyarnrcvitem->rate,2);
      $invyarnrcvitem->stock_value=number_format($invyarnrcvitem->stock_value,2);
      return $invyarnrcvitem;
      }); 
      echo json_encode($invyarnrcvitem);
    }

    public function dyechemRcv(){
        $company_id=request('company_id',0);
      $store_id=request('store_id',0);
      $date_from=request('date_from',0);
      $date_to=request('date_to',0);
      $start_date=date('Y-m-d', strtotime($date_from));
      $yesterday = date('Y-m-d', strtotime('-1 days', strtotime($start_date)));
      $companyCond='';
      if($company_id){
       $companyCond= ' and inv_dye_chem_transactions.company_id= '.$company_id;
      }
      else{
       $companyCond= '';
      }

      $storeCond='';
      if($store_id){
       $storeCond= ' and inv_dye_chem_transactions.store_id= '.$store_id;
      }
      else{
       $storeCond= '';
      }
      



      $invdyechemrcvitem=$this->itemaccount
      ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
      })
      ->join('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
      })
      ->join('uoms',function($join){
      $join->on('uoms.id','=','item_accounts.uom_id');
      })
      ->join(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_dye_chem_rcv_items.item_account_id

      ) dyechem_rcv"), "dyechem_rcv.item_account_id", "=", "item_accounts.id")
      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where inv_rcvs.receive_date<'".$date_from."'
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_dye_chem_rcv_items.item_account_id
      ) open_dye_chem_rcv"), "open_dye_chem_rcv.item_account_id", "=", "dyechem_rcv.item_account_id")
      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id in (1,2,3)
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_dye_chem_rcv_items.item_account_id

      ) pur_rcv"), "pur_rcv.item_account_id", "=", "dyechem_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id = 9
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_dye_chem_rcv_items.item_account_id

      ) trans_in_rcv"), "trans_in_rcv.item_account_id", "=", "dyechem_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id = 4
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_dye_chem_rcv_items.item_account_id

      ) isu_rtn_rcv"), "isu_rtn_rcv.item_account_id", "=", "dyechem_rcv.item_account_id")

      

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where inv_isus.issue_date<'".$date_from."'
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id

      ) open_dye_chem_isu"), "open_dye_chem_isu.item_account_id", "=", "dyechem_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      abs(sum(inv_dye_chem_transactions.store_qty)) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id in (1,2)
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id
      ) regular_isu"), "regular_isu.item_account_id", "=", "dyechem_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      abs(sum(inv_dye_chem_transactions.store_qty)) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id  = 9
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id
      ) trans_out_isu"), "trans_out_isu.item_account_id", "=", "dyechem_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      abs(sum(inv_dye_chem_transactions.store_qty)) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id  = 11
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id
      ) rcv_rtn_isu"), "rcv_rtn_isu.item_account_id", "=", "dyechem_rcv.item_account_id")
      
      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id
      ) dyechem_isu"), "dyechem_isu.item_account_id", "=", "dyechem_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      max(inv_rcvs.receive_date) as receive_date
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where 
      inv_rcvs.receive_basis_id in (1,2,3)
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_dye_chem_rcv_items.item_account_id

      ) max_rcv_dt"), "max_rcv_dt.item_account_id", "=", "dyechem_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      inv_rcvs.receive_date as receive_date,
      sum(inv_dye_chem_transactions.store_qty) as qty
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where  inv_rcvs.receive_basis_id in (1,2,3)
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by 
      inv_dye_chem_rcv_items.item_account_id,
      inv_rcvs.receive_date

      ) max_rcv_qty"), [["max_rcv_qty.receive_date", "=", "max_rcv_dt.receive_date"],["max_rcv_qty.item_account_id", "=", "dyechem_rcv.item_account_id"]])


      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      max(inv_isus.issue_date) as issue_date
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where  inv_isus.isu_basis_id in (1,2)
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id
      ) max_isu_dt"), "max_isu_dt.item_account_id", "=", "dyechem_rcv.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      inv_isus.issue_date as issue_date,
      abs(sum(inv_dye_chem_transactions.store_qty)) as qty
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where 
      inv_isus.isu_basis_id in (1,2)
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id,inv_isus.issue_date
      ) max_isu_qty"), [["max_isu_qty.issue_date", "=", "max_isu_dt.issue_date"],["max_isu_qty.item_account_id", "=", "dyechem_rcv.item_account_id"]])
      
      //->where([['itemcategories.identity','=',9]])
      ->whereIn('itemcategories.identity',[7,8])
      ->when(request('item_category_id'), function ($q) {
        return $q->where('itemcategories.id', '=', request('item_category_id', 0));
    })
      ->when(request('consumption_level_id'), function ($q) {
            return $q->where('item_accounts.consumption_level_id', '=', request('consumption_level_id', 0));
      })
      ->orderBy('itemcategories.id')
      ->orderBy('itemclasses.id')
      ->orderBy('item_accounts.item_description')
      ->get([
      'itemcategories.name as itemcategory_name',
      'itemclasses.name as itemclass_name',
      'item_accounts.id',
      'item_accounts.item_description',
      'item_accounts.specification',
      'uoms.code as uom_code',

      'pur_rcv.qty as pur_qty',
      'trans_in_rcv.qty as trans_in_qty',
      'isu_rtn_rcv.qty as isu_rtn_qty',
      'dyechem_rcv.qty as receive_qty',
      'dyechem_rcv.amount as receive_amount',
      'open_dye_chem_rcv.qty as open_receive_qty',
      'open_dye_chem_rcv.amount as open_receive_amount',
      'regular_isu.qty as regular_issue_qty',
      'trans_out_isu.qty as trans_out_issue_qty',
      'rcv_rtn_isu.qty as rcv_rtn_issue_qty',
      'dyechem_isu.qty as issue_qty',
      'dyechem_isu.amount as issue_amount',
      'open_dye_chem_isu.qty as open_issue_qty',
      'open_dye_chem_isu.amount as open_issue_amount',
      'max_rcv_dt.receive_date as max_receive_date',
      'max_rcv_qty.qty as max_receive_qty',
      'max_isu_dt.issue_date as max_issue_date',
      'max_isu_qty.qty as max_issue_qty',
      ])
      ->map(function($invdyechemrcvitem)  {
      $invdyechemrcvitem->item_desc=$invdyechemrcvitem->item_description.", ".$invdyechemrcvitem->specification;

      $invdyechemrcvitem->issue_qty=$invdyechemrcvitem->issue_qty*-1;

      $invdyechemrcvitem->issue_amount=$invdyechemrcvitem->issue_amount;

      $invdyechemrcvitem->opening_qty=$invdyechemrcvitem->open_receive_qty-($invdyechemrcvitem->open_issue_qty*-1);
      $invdyechemrcvitem->opening_amount=$invdyechemrcvitem->open_receive_amount-($invdyechemrcvitem->open_issue_amount);
      $invdyechemrcvitem->stock_qty=($invdyechemrcvitem->opening_qty+$invdyechemrcvitem->receive_qty)-($invdyechemrcvitem->issue_qty);
      $invdyechemrcvitem->stock_value=$invdyechemrcvitem->opening_amount+($invdyechemrcvitem->receive_amount-$invdyechemrcvitem->issue_amount);

      $invdyechemrcvitem->rate=0;
      if($invdyechemrcvitem->stock_qty){
      $invdyechemrcvitem->rate=$invdyechemrcvitem->stock_value/$invdyechemrcvitem->stock_qty;
      }

      

      if($invdyechemrcvitem->max_receive_date){
      $invdyechemrcvitem->last_receive=date('d-M-Y',strtotime($invdyechemrcvitem->max_receive_date));
      }
      else{
          $invdyechemrcvitem->last_receive='';
      }

      if($invdyechemrcvitem->max_issue_date){
      $invdyechemrcvitem->last_issue=date('d-M-Y',strtotime($invdyechemrcvitem->max_issue_date));//
      }
      else{
          $invdyechemrcvitem->last_issue='';
      }

        $now = time(); 
        $max_issue_date = strtotime($invdyechemrcvitem->max_issue_date);
        $datediff = $now - $max_issue_date;
        if($invdyechemrcvitem->max_issue_date)
        {
        $invdyechemrcvitem->diff_days=round($datediff / (60 * 60 * 24));
        }
        else
        {
        $invdyechemrcvitem->diff_days='';
        }


      $invdyechemrcvitem->opening_qty=number_format($invdyechemrcvitem->opening_qty,2);
      $invdyechemrcvitem->pur_qty=number_format($invdyechemrcvitem->pur_qty,2);
      $invdyechemrcvitem->trans_in_qty=number_format($invdyechemrcvitem->trans_in_qty,2);
      $invdyechemrcvitem->isu_rtn_qty=number_format($invdyechemrcvitem->isu_rtn_qty,2);
      $invdyechemrcvitem->receive_qty=number_format($invdyechemrcvitem->receive_qty,2);
      $invdyechemrcvitem->receive_amount=number_format($invdyechemrcvitem->receive_amount,2);
      $invdyechemrcvitem->regular_issue_qty=number_format($invdyechemrcvitem->regular_issue_qty,2);
      $invdyechemrcvitem->trans_out_issue_qty=number_format($invdyechemrcvitem->trans_out_issue_qty,2);
      $invdyechemrcvitem->rcv_rtn_issue_qty=number_format($invdyechemrcvitem->rcv_rtn_issue_qty,2);
      $invdyechemrcvitem->issue_qty=number_format($invdyechemrcvitem->issue_qty,2);
      $invdyechemrcvitem->issue_amount=number_format($invdyechemrcvitem->issue_amount,2);
      $invdyechemrcvitem->stock_qty=number_format($invdyechemrcvitem->stock_qty,2);
      $invdyechemrcvitem->rate=number_format($invdyechemrcvitem->rate,2);
      $invdyechemrcvitem->stock_value=number_format($invdyechemrcvitem->stock_value,2);
      $invdyechemrcvitem->max_receive_qty=number_format($invdyechemrcvitem->max_receive_qty,2);
      $invdyechemrcvitem->max_issue_qty=number_format($invdyechemrcvitem->max_issue_qty,2);
      return $invdyechemrcvitem;
      }); 
      echo json_encode($invdyechemrcvitem);
    }

    public function dyechemIsu(){
        $company_id=request('company_id',0);
      $store_id=request('store_id',0);
      $date_from=request('date_from',0);
      $date_to=request('date_to',0);
      $start_date=date('Y-m-d', strtotime($date_from));
      $yesterday = date('Y-m-d', strtotime('-1 days', strtotime($start_date)));
      $companyCond='';
      if($company_id){
       $companyCond= ' and inv_dye_chem_transactions.company_id= '.$company_id;
      }
      else{
       $companyCond= '';
      }

      $storeCond='';
      if($store_id){
       $storeCond= ' and inv_dye_chem_transactions.store_id= '.$store_id;
      }
      else{
       $storeCond= '';
      }
      



      $invdyechemrcvitem=$this->itemaccount
      ->leftJoin('itemclasses',function($join){
      $join->on('itemclasses.id','=','item_accounts.itemclass_id');
      })
      ->join('itemcategories',function($join){
      $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
      })
      ->join('uoms',function($join){
      $join->on('uoms.id','=','item_accounts.uom_id');
      })
      ->join(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id
      ) dyechem_isu"), "dyechem_isu.item_account_id", "=", "item_accounts.id")
      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where inv_isus.issue_date<'".$date_from."'
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id

      ) open_dye_chem_isu"), "open_dye_chem_isu.item_account_id", "=", "dyechem_isu.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      abs(sum(inv_dye_chem_transactions.store_qty)) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id in (1,2)
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id
      ) regular_isu"), "regular_isu.item_account_id", "=", "dyechem_isu.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      abs(sum(inv_dye_chem_transactions.store_qty)) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id  = 9
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id
      ) trans_out_isu"), "trans_out_isu.item_account_id", "=", "dyechem_isu.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      abs(sum(inv_dye_chem_transactions.store_qty)) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where inv_isus.issue_date>='".$date_from."' 
      and inv_isus.issue_date<='".$date_to."'

      and inv_isus.isu_basis_id  = 11
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id
      ) rcv_rtn_isu"), "rcv_rtn_isu.item_account_id", "=", "dyechem_isu.item_account_id")
      
      
      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_dye_chem_rcv_items.item_account_id

      ) dyechem_rcv"), "dyechem_rcv.item_account_id", "=", "dyechem_isu.item_account_id")
      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where inv_rcvs.receive_date<'".$date_from."'
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_dye_chem_rcv_items.item_account_id
      ) open_dye_chem_rcv"), "open_dye_chem_rcv.item_account_id", "=", "dyechem_isu.item_account_id")
      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id in (1,2,3)
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_dye_chem_rcv_items.item_account_id

      ) pur_rcv"), "pur_rcv.item_account_id", "=", "dyechem_isu.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id = 9
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_dye_chem_rcv_items.item_account_id

      ) trans_in_rcv"), "trans_in_rcv.item_account_id", "=", "dyechem_isu.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      sum(inv_dye_chem_transactions.store_qty) as qty,
      sum(inv_dye_chem_transactions.store_amount) as amount
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where inv_rcvs.receive_date>='".$date_from."' 
      and inv_rcvs.receive_date<='".$date_to."'
      and inv_rcvs.receive_basis_id = 4
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_dye_chem_rcv_items.item_account_id

      ) isu_rtn_rcv"), "isu_rtn_rcv.item_account_id", "=", "dyechem_isu.item_account_id")

      

      

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      max(inv_rcvs.receive_date) as receive_date
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where 
      inv_rcvs.receive_basis_id in (1,2,3)
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by inv_dye_chem_rcv_items.item_account_id

      ) max_rcv_dt"), "max_rcv_dt.item_account_id", "=", "dyechem_isu.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_rcv_items.item_account_id,
      inv_rcvs.receive_date as receive_date,
      sum(inv_dye_chem_transactions.store_qty) as qty
      from inv_dye_chem_rcv_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
      join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
      join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
      where  inv_rcvs.receive_basis_id in (1,2,3)
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_rcv_items.deleted_at is null
      and inv_rcvs.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=1
      $storeCond
      $companyCond
      group by 
      inv_dye_chem_rcv_items.item_account_id,
      inv_rcvs.receive_date

      ) max_rcv_qty"), [["max_rcv_qty.receive_date", "=", "max_rcv_dt.receive_date"],["max_rcv_qty.item_account_id", "=", "dyechem_isu.item_account_id"]])


      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      max(inv_isus.issue_date) as issue_date
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where  inv_isus.isu_basis_id in (1,2)
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id
      ) max_isu_dt"), "max_isu_dt.item_account_id", "=", "dyechem_isu.item_account_id")

      ->leftJoin(\DB::raw("(
      select 
      inv_dye_chem_isu_items.item_account_id,
      inv_isus.issue_date as issue_date,
      abs(sum(inv_dye_chem_transactions.store_qty)) as qty
      from inv_dye_chem_isu_items
      join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_isu_item_id=inv_dye_chem_isu_items.id
      join inv_isus on inv_isus.id=inv_dye_chem_isu_items.inv_isu_id
      where 
      inv_isus.isu_basis_id in (1,2)
      and inv_dye_chem_transactions.deleted_at is null
      and inv_dye_chem_isu_items.deleted_at is null
      and inv_isus.deleted_at is null
      and inv_dye_chem_transactions.trans_type_id=2
      $storeCond
      $companyCond
      group by inv_dye_chem_isu_items.item_account_id,inv_isus.issue_date
      ) max_isu_qty"), [["max_isu_qty.issue_date", "=", "max_isu_dt.issue_date"],["max_isu_qty.item_account_id", "=", "dyechem_isu.item_account_id"]])
      
      //->where([['itemcategories.identity','=',9]])
      ->whereIn('itemcategories.identity',[7,8])
      ->when(request('item_category_id'), function ($q) {
        return $q->where('itemcategories.id', '=', request('item_category_id', 0));
    })
      ->when(request('consumption_level_id'), function ($q) {
            return $q->where('item_accounts.consumption_level_id', '=', request('consumption_level_id', 0));
      })
      ->orderBy('itemcategories.id')
      ->orderBy('itemclasses.id')
      ->orderBy('item_accounts.item_description')
      ->get([
      'itemcategories.name as itemcategory_name',
      'itemclasses.name as itemclass_name',
      'item_accounts.id',
      'item_accounts.item_description',
      'item_accounts.specification',
      'uoms.code as uom_code',

      'pur_rcv.qty as pur_qty',
      'trans_in_rcv.qty as trans_in_qty',
      'isu_rtn_rcv.qty as isu_rtn_qty',
      'dyechem_rcv.qty as receive_qty',
      'dyechem_rcv.amount as receive_amount',
      'open_dye_chem_rcv.qty as open_receive_qty',
      'open_dye_chem_rcv.amount as open_receive_amount',
      'regular_isu.qty as regular_issue_qty',
      'trans_out_isu.qty as trans_out_issue_qty',
      'rcv_rtn_isu.qty as rcv_rtn_issue_qty',
      'dyechem_isu.qty as issue_qty',
      'dyechem_isu.amount as issue_amount',
      'open_dye_chem_isu.qty as open_issue_qty',
      'open_dye_chem_isu.amount as open_issue_amount',
      'max_rcv_dt.receive_date as max_receive_date',
      'max_rcv_qty.qty as max_receive_qty',
      'max_isu_dt.issue_date as max_issue_date',
      'max_isu_qty.qty as max_issue_qty',
      ])
      ->map(function($invdyechemrcvitem)  {
      $invdyechemrcvitem->item_desc=$invdyechemrcvitem->item_description.", ".$invdyechemrcvitem->specification;

      $invdyechemrcvitem->issue_qty=$invdyechemrcvitem->issue_qty*-1;

      $invdyechemrcvitem->issue_amount=$invdyechemrcvitem->issue_amount;

      $invdyechemrcvitem->opening_qty=$invdyechemrcvitem->open_receive_qty-($invdyechemrcvitem->open_issue_qty*-1);
      $invdyechemrcvitem->opening_amount=$invdyechemrcvitem->open_receive_amount-($invdyechemrcvitem->open_issue_amount);
      $invdyechemrcvitem->stock_qty=($invdyechemrcvitem->opening_qty+$invdyechemrcvitem->receive_qty)-($invdyechemrcvitem->issue_qty);
      $invdyechemrcvitem->stock_value=$invdyechemrcvitem->opening_amount+($invdyechemrcvitem->receive_amount-$invdyechemrcvitem->issue_amount);

      $invdyechemrcvitem->rate=0;
      if($invdyechemrcvitem->stock_qty){
      $invdyechemrcvitem->rate=$invdyechemrcvitem->stock_value/$invdyechemrcvitem->stock_qty;
      }

      

      if($invdyechemrcvitem->max_receive_date){
      $invdyechemrcvitem->last_receive=date('d-M-Y',strtotime($invdyechemrcvitem->max_receive_date));
      }
      else{
          $invdyechemrcvitem->last_receive='';
      }

      if($invdyechemrcvitem->max_issue_date){
      $invdyechemrcvitem->last_issue=date('d-M-Y',strtotime($invdyechemrcvitem->max_issue_date));//
      }
      else{
          $invdyechemrcvitem->last_issue='';
      }

        $now = time(); 
        $max_issue_date = strtotime($invdyechemrcvitem->max_issue_date);
        $datediff = $now - $max_issue_date;
        if($invdyechemrcvitem->max_issue_date)
        {
        $invdyechemrcvitem->diff_days=round($datediff / (60 * 60 * 24));
        }
        else
        {
        $invdyechemrcvitem->diff_days='';
        }


      $invdyechemrcvitem->opening_qty=number_format($invdyechemrcvitem->opening_qty,2);
      $invdyechemrcvitem->pur_qty=number_format($invdyechemrcvitem->pur_qty,2);
      $invdyechemrcvitem->trans_in_qty=number_format($invdyechemrcvitem->trans_in_qty,2);
      $invdyechemrcvitem->isu_rtn_qty=number_format($invdyechemrcvitem->isu_rtn_qty,2);
      $invdyechemrcvitem->receive_qty=number_format($invdyechemrcvitem->receive_qty,2);
      $invdyechemrcvitem->receive_amount=number_format($invdyechemrcvitem->receive_amount,2);
      $invdyechemrcvitem->regular_issue_qty=number_format($invdyechemrcvitem->regular_issue_qty,2);
      $invdyechemrcvitem->trans_out_issue_qty=number_format($invdyechemrcvitem->trans_out_issue_qty,2);
      $invdyechemrcvitem->rcv_rtn_issue_qty=number_format($invdyechemrcvitem->rcv_rtn_issue_qty,2);
      $invdyechemrcvitem->issue_qty=number_format($invdyechemrcvitem->issue_qty,2);
      $invdyechemrcvitem->issue_amount=number_format($invdyechemrcvitem->issue_amount,2);
      $invdyechemrcvitem->stock_qty=number_format($invdyechemrcvitem->stock_qty,2);
      $invdyechemrcvitem->rate=number_format($invdyechemrcvitem->rate,2);
      $invdyechemrcvitem->stock_value=number_format($invdyechemrcvitem->stock_value,2);
      $invdyechemrcvitem->max_receive_qty=number_format($invdyechemrcvitem->max_receive_qty,2);
      $invdyechemrcvitem->max_issue_qty=number_format($invdyechemrcvitem->max_issue_qty,2);
      return $invdyechemrcvitem;
      }); 
      echo json_encode($invdyechemrcvitem);
    }

    public function greyfabRcv(){
        $company_id=request('company_id',0);
        $store_id=request('store_id',0);
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $start_date=date('Y-m-d', strtotime($date_from));
        $yesterday = date('Y-m-d', strtotime('-1 days', strtotime($start_date)));
        $companyCond='';
        if($company_id){
        $companyCond= ' and inv_grey_fab_transactions.company_id= '.$company_id;
        }
        else{
        $companyCond= '';
        }

        $storeCond='';
        if($store_id){
        $storeCond= ' and inv_grey_fab_transactions.store_id= '.$store_id;
        }
        else{
        $storeCond= '';
        }

        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        //$gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
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




        $invgreyfabrcvitem=$this->invgreyfabitem
        ->join('autoyarns',function($join){
        $join->on('autoyarns.id','=','inv_grey_fab_items.autoyarn_id');
        })
        ->join('gmtsparts',function($join){
        $join->on('gmtsparts.id','=','inv_grey_fab_items.gmtspart_id');
        })
        ->leftJoin('colorranges',function($join){
        $join->on('colorranges.id','=','inv_grey_fab_items.colorrange_id');
        })
        ->join(\DB::raw("(
        select 
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_rcv_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
        join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
        where inv_rcvs.receive_date >='".$date_to."'
        and inv_rcvs.receive_date <='".$date_to."'
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=1
        $storeCond
        $companyCond
        group by inv_grey_fab_rcv_items.inv_grey_fab_item_id
        ) all_rcv"), "all_rcv.inv_grey_fab_item_id", "=", "inv_grey_fab_items.id")
        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_rcv_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
        join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
        where inv_rcvs.receive_date <'".$date_from."'
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=1
        $storeCond
        $companyCond
        group by inv_grey_fab_rcv_items.inv_grey_fab_item_id
        ) open_grey_fab_rcv"), "open_grey_fab_rcv.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")
        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_rcv_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
        join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
        where inv_rcvs.receive_date>='".$date_from."' 
        and inv_rcvs.receive_date<='".$date_to."'
        and inv_rcvs.receive_basis_id in (1,2,3)
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=1
        $storeCond
        $companyCond
        group by inv_grey_fab_rcv_items.inv_grey_fab_item_id

        ) pur_rcv"), "pur_rcv.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_rcv_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
        join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
        where inv_rcvs.receive_date>='".$date_from."' 
        and inv_rcvs.receive_date<='".$date_to."'
        and inv_rcvs.receive_basis_id = 9
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=1
        $storeCond
        $companyCond
        group by inv_grey_fab_rcv_items.inv_grey_fab_item_id

        ) trans_in_rcv"), "trans_in_rcv.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_rcv_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
        join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
        where inv_rcvs.receive_date>='".$date_from."' 
        and inv_rcvs.receive_date<='".$date_to."'
        and inv_rcvs.receive_basis_id = 4
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=1
        $storeCond
        $companyCond
        group by inv_grey_fab_rcv_items.inv_grey_fab_item_id

        ) isu_rtn_rcv"), "isu_rtn_rcv.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_rcv_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
        join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
        where inv_rcvs.receive_date>='".$date_from."' 
        and inv_rcvs.receive_date<='".$date_to."'
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=1
        $storeCond
        $companyCond
        group by inv_grey_fab_rcv_items.inv_grey_fab_item_id

        ) greyfab_rcv"), "greyfab_rcv.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_isu_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_isu_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
        join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
        where inv_isus.issue_date<'".$date_from."'
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_isu_items.deleted_at is null
        and inv_isus.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=2
        $storeCond
        $companyCond
        group by inv_grey_fab_isu_items.inv_grey_fab_item_id

        ) open_grey_fab_isu"), "open_grey_fab_isu.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_isu_items.inv_grey_fab_item_id,
        abs(sum(inv_grey_fab_transactions.store_qty)) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_isu_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
        join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
        where inv_isus.issue_date>='".$date_from."' 
        and inv_isus.issue_date<='".$date_to."'

        and inv_isus.isu_basis_id in (1,2)
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_isu_items.deleted_at is null
        and inv_isus.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=2
        $storeCond
        $companyCond
        group by inv_grey_fab_isu_items.inv_grey_fab_item_id
        ) regular_isu"), "regular_isu.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_isu_items.inv_grey_fab_item_id,
        abs(sum(inv_grey_fab_transactions.store_qty)) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_isu_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
        join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
        where inv_isus.issue_date>='".$date_from."' 
        and inv_isus.issue_date<='".$date_to."'

        and inv_isus.isu_basis_id  = 9
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_isu_items.deleted_at is null
        and inv_isus.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=2
        $storeCond
        $companyCond
        group by inv_grey_fab_isu_items.inv_grey_fab_item_id
        ) trans_out_isu"), "trans_out_isu.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_isu_items.inv_grey_fab_item_id,
        abs(sum(inv_grey_fab_transactions.store_qty)) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_isu_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
        join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
        where inv_isus.issue_date>='".$date_from."' 
        and inv_isus.issue_date<='".$date_to."'

        and inv_isus.isu_basis_id  = 11
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_isu_items.deleted_at is null
        and inv_isus.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=2
        $storeCond
        $companyCond
        group by inv_grey_fab_isu_items.inv_grey_fab_item_id
        ) rcv_rtn_isu"), "rcv_rtn_isu.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_isu_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_isu_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
        join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
        where inv_isus.issue_date>='".$date_from."' 
        and inv_isus.issue_date<='".$date_to."'
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_isu_items.deleted_at is null
        and inv_isus.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=2
        $storeCond
        $companyCond
        group by inv_grey_fab_isu_items.inv_grey_fab_item_id
        ) greyfab_isu"), "greyfab_isu.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        max(inv_rcvs.receive_date) as receive_date
        from inv_grey_fab_rcv_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
        join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
        where 
        inv_rcvs.receive_basis_id in (1,2,3)
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=1
        $storeCond
        $companyCond
        group by inv_grey_fab_rcv_items.inv_grey_fab_item_id

        ) max_rcv_dt"), "max_rcv_dt.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        inv_rcvs.receive_date as receive_date,
        sum(inv_grey_fab_transactions.store_qty) as qty
        from inv_grey_fab_rcv_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
        join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
        where  inv_rcvs.receive_basis_id in (1,2,3)
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=1
        $storeCond
        $companyCond
        group by 
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        inv_rcvs.receive_date

        ) max_rcv_qty"), [["max_rcv_qty.receive_date", "=", "max_rcv_dt.receive_date"],["max_rcv_qty.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id"]])


        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_isu_items.inv_grey_fab_item_id,
        max(inv_isus.issue_date) as issue_date
        from inv_grey_fab_isu_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
        join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
        where  inv_isus.isu_basis_id in (1,2)
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_isu_items.deleted_at is null
        and inv_isus.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=2
        $storeCond
        $companyCond
        group by inv_grey_fab_isu_items.inv_grey_fab_item_id
        ) max_isu_dt"), "max_isu_dt.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_isu_items.inv_grey_fab_item_id,
        inv_isus.issue_date as issue_date,
        abs(sum(inv_grey_fab_transactions.store_qty)) as qty
        from inv_grey_fab_isu_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
        join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
        where 
        inv_isus.isu_basis_id in (1,2)
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_isu_items.deleted_at is null
        and inv_isus.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=2
        $storeCond
        $companyCond
        group by inv_grey_fab_isu_items.inv_grey_fab_item_id,inv_isus.issue_date
        ) max_isu_qty"), [["max_isu_qty.issue_date", "=", "max_isu_dt.issue_date"],["max_isu_qty.inv_grey_fab_item_id", "=", "all_rcv.inv_grey_fab_item_id"]])

        //->where([['itemcategories.identity','=',9]])
        /*->whereIn('itemcategories.identity',[7,8])
        ->when(request('item_category_id'), function ($q) {
        return $q->where('itemcategories.id', '=', request('item_category_id', 0));
        })*/

        ->orderBy('inv_grey_fab_items.id')
        ->get([
        'inv_grey_fab_items.id',
        'inv_grey_fab_items.autoyarn_id',
        'inv_grey_fab_items.gmtspart_id',
        'inv_grey_fab_items.fabric_look_id',
        'inv_grey_fab_items.fabric_shape_id',
        'inv_grey_fab_items.gsm_weight',
        'inv_grey_fab_items.dia',
        'inv_grey_fab_items.measurment',
        'inv_grey_fab_items.roll_length',
        'inv_grey_fab_items.stitch_length',
        'inv_grey_fab_items.shrink_per',
        'inv_grey_fab_items.colorrange_id',
        'gmtsparts.name as gmtspart_name',
        'colorranges.name as colorrange_name',

        'pur_rcv.qty as pur_qty',
        'trans_in_rcv.qty as trans_in_qty',
        'isu_rtn_rcv.qty as isu_rtn_qty',
        'greyfab_rcv.qty as receive_qty',
        'greyfab_rcv.amount as receive_amount',
        'open_grey_fab_rcv.qty as open_receive_qty',
        'open_grey_fab_rcv.amount as open_receive_amount',

        'regular_isu.qty as regular_issue_qty',
        'trans_out_isu.qty as trans_out_issue_qty',
        'rcv_rtn_isu.qty as rcv_rtn_issue_qty',
        'greyfab_isu.qty as issue_qty',
        'greyfab_isu.amount as issue_amount',
        'open_grey_fab_isu.qty as open_issue_qty',
        'open_grey_fab_isu.amount as open_issue_amount',
        'max_rcv_dt.receive_date as max_receive_date',

        'max_rcv_qty.qty as max_receive_qty',
        'max_isu_dt.issue_date as max_issue_date',
        'max_isu_qty.qty as max_issue_qty',
        ])
        ->map(function($invgreyfabrcvitem) use($shiftname,$desDropdown,$fabriclooks,$fabricshape) {
        //$invgreyfabrcvitem->item_desc=$invgreyfabrcvitem->item_description.", ".$invgreyfabrcvitem->specification;
        $invgreyfabrcvitem->shift_name=$shiftname[$invgreyfabrcvitem->shift_id];
        $invgreyfabrcvitem->fabrication=$invgreyfabrcvitem->autoyarn_id?$desDropdown[$invgreyfabrcvitem->autoyarn_id]:'';
        $invgreyfabrcvitem->fabric_look=$invgreyfabrcvitem->fabric_look_id?$fabriclooks[$invgreyfabrcvitem->fabric_look_id]:'';
        $invgreyfabrcvitem->fabric_shape=$invgreyfabrcvitem->fabric_shape_id?$fabricshape[$invgreyfabrcvitem->fabric_shape_id]:'';
        //$invgreyfabrcvitem->body_part=$invgreyfabrcvitem->gmtspart_id?$gmtspart[$invgreyfabrcvitem->gmtspart_id]:'';


        $invgreyfabrcvitem->issue_qty=$invgreyfabrcvitem->issue_qty*-1;
        $invgreyfabrcvitem->issue_amount=$invgreyfabrcvitem->issue_amount;
        $invgreyfabrcvitem->opening_qty=$invgreyfabrcvitem->open_receive_qty-($invgreyfabrcvitem->open_issue_qty*-1);
        $invgreyfabrcvitem->opening_amount=$invgreyfabrcvitem->open_receive_amount-($invgreyfabrcvitem->open_issue_amount);
        $invgreyfabrcvitem->stock_qty=($invgreyfabrcvitem->opening_qty+$invgreyfabrcvitem->receive_qty)-($invgreyfabrcvitem->issue_qty);
        $invgreyfabrcvitem->stock_value=($invgreyfabrcvitem->opening_amount+$invgreyfabrcvitem->receive_amount)-($invgreyfabrcvitem->issue_amount);
        $invgreyfabrcvitem->rate=0;
        if($invgreyfabrcvitem->stock_qty){
        $invgreyfabrcvitem->rate=$invgreyfabrcvitem->stock_value/$invgreyfabrcvitem->stock_qty;
        }

        if($invgreyfabrcvitem->max_receive_date){
        $invgreyfabrcvitem->last_receive=date('d-M-Y',strtotime($invgreyfabrcvitem->max_receive_date));
        }
        else{
        $invgreyfabrcvitem->last_receive='';
        }

        if($invgreyfabrcvitem->max_issue_date){
        $invgreyfabrcvitem->last_issue=date('d-M-Y',strtotime($invgreyfabrcvitem->max_issue_date));
        }
        else{
        $invgreyfabrcvitem->last_issue='';
        }

        $now = time(); // or your date as well
        $max_issue_date = strtotime($invgreyfabrcvitem->max_issue_date);
        $datediff = $now - $max_issue_date;
        if($invgreyfabrcvitem->max_issue_date)
        {
        $invgreyfabrcvitem->diff_days=round($datediff / (60 * 60 * 24));
        }
        else
        {
        $invgreyfabrcvitem->diff_days='';
        }


        $invgreyfabrcvitem->opening_qty=number_format($invgreyfabrcvitem->opening_qty,2);
        $invgreyfabrcvitem->pur_qty=number_format($invgreyfabrcvitem->pur_qty,2);
        $invgreyfabrcvitem->trans_in_qty=number_format($invgreyfabrcvitem->trans_in_qty,2);
        $invgreyfabrcvitem->isu_rtn_qty=number_format($invgreyfabrcvitem->isu_rtn_qty,2);
        $invgreyfabrcvitem->receive_qty=number_format($invgreyfabrcvitem->receive_qty,2);
        $invgreyfabrcvitem->receive_amount=number_format($invgreyfabrcvitem->receive_amount,2);
        $invgreyfabrcvitem->regular_issue_qty=number_format($invgreyfabrcvitem->regular_issue_qty,2);
        $invgreyfabrcvitem->trans_out_issue_qty=number_format($invgreyfabrcvitem->trans_out_issue_qty,2);
        $invgreyfabrcvitem->rcv_rtn_issue_qty=number_format($invgreyfabrcvitem->rcv_rtn_issue_qty,2);
        $invgreyfabrcvitem->issue_qty=number_format($invgreyfabrcvitem->issue_qty,2);
        $invgreyfabrcvitem->issue_amount=number_format($invgreyfabrcvitem->issue_amount,2);
        $invgreyfabrcvitem->stock_qty=number_format($invgreyfabrcvitem->stock_qty,2);
        $invgreyfabrcvitem->rate=number_format($invgreyfabrcvitem->rate,2);
        $invgreyfabrcvitem->stock_value=number_format($invgreyfabrcvitem->stock_value,2);
        $invgreyfabrcvitem->max_receive_qty=number_format($invgreyfabrcvitem->max_receive_qty,2);
        $invgreyfabrcvitem->max_issue_qty=number_format($invgreyfabrcvitem->max_issue_qty,2);
        return $invgreyfabrcvitem;
        }); 
        echo json_encode($invgreyfabrcvitem);
    }
    public function greyfabIsu(){
        $company_id=request('company_id',0);
        $store_id=request('store_id',0);
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $start_date=date('Y-m-d', strtotime($date_from));
        $yesterday = date('Y-m-d', strtotime('-1 days', strtotime($start_date)));
        $companyCond='';
        if($company_id){
        $companyCond= ' and inv_grey_fab_transactions.company_id= '.$company_id;
        }
        else{
        $companyCond= '';
        }

        $storeCond='';
        if($store_id){
        $storeCond= ' and inv_grey_fab_transactions.store_id= '.$store_id;
        }
        else{
        $storeCond= '';
        }

        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
        //$gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
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




        $invgreyfabrcvitem=$this->invgreyfabitem
        ->join('autoyarns',function($join){
        $join->on('autoyarns.id','=','inv_grey_fab_items.autoyarn_id');
        })
        ->join('gmtsparts',function($join){
        $join->on('gmtsparts.id','=','inv_grey_fab_items.gmtspart_id');
        })
        ->leftJoin('colorranges',function($join){
        $join->on('colorranges.id','=','inv_grey_fab_items.colorrange_id');
        })
        ->join(\DB::raw("(
        select 
        inv_grey_fab_isu_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_isu_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
        join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
        where inv_isus.issue_date>='".$date_from."' 
        and inv_isus.issue_date<='".$date_to."'
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_isu_items.deleted_at is null
        and inv_isus.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=2
        $storeCond
        $companyCond
        group by inv_grey_fab_isu_items.inv_grey_fab_item_id
        ) all_isu"), "all_isu.inv_grey_fab_item_id", "=", "inv_grey_fab_items.id")
        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_rcv_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
        join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
        where inv_rcvs.receive_date <'".$date_from."'
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=1
        $storeCond
        $companyCond
        group by inv_grey_fab_rcv_items.inv_grey_fab_item_id
        ) open_grey_fab_rcv"), "open_grey_fab_rcv.inv_grey_fab_item_id", "=", "all_isu.inv_grey_fab_item_id")
        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_rcv_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
        join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
        where inv_rcvs.receive_date>='".$date_from."' 
        and inv_rcvs.receive_date<='".$date_to."'
        and inv_rcvs.receive_basis_id in (1,2,3)
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=1
        $storeCond
        $companyCond
        group by inv_grey_fab_rcv_items.inv_grey_fab_item_id

        ) pur_rcv"), "pur_rcv.inv_grey_fab_item_id", "=", "all_isu.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_rcv_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
        join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
        where inv_rcvs.receive_date>='".$date_from."' 
        and inv_rcvs.receive_date<='".$date_to."'
        and inv_rcvs.receive_basis_id = 9
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=1
        $storeCond
        $companyCond
        group by inv_grey_fab_rcv_items.inv_grey_fab_item_id

        ) trans_in_rcv"), "trans_in_rcv.inv_grey_fab_item_id", "=", "all_isu.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_rcv_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
        join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
        where inv_rcvs.receive_date>='".$date_from."' 
        and inv_rcvs.receive_date<='".$date_to."'
        and inv_rcvs.receive_basis_id = 4
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=1
        $storeCond
        $companyCond
        group by inv_grey_fab_rcv_items.inv_grey_fab_item_id

        ) isu_rtn_rcv"), "isu_rtn_rcv.inv_grey_fab_item_id", "=", "all_isu.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_rcv_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
        join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
        where inv_rcvs.receive_date>='".$date_from."' 
        and inv_rcvs.receive_date<='".$date_to."'
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=1
        $storeCond
        $companyCond
        group by inv_grey_fab_rcv_items.inv_grey_fab_item_id

        ) greyfab_rcv"), "greyfab_rcv.inv_grey_fab_item_id", "=", "all_isu.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_isu_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_isu_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
        join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
        where inv_isus.issue_date<'".$date_from."'
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_isu_items.deleted_at is null
        and inv_isus.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=2
        $storeCond
        $companyCond
        group by inv_grey_fab_isu_items.inv_grey_fab_item_id

        ) open_grey_fab_isu"), "open_grey_fab_isu.inv_grey_fab_item_id", "=", "all_isu.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_isu_items.inv_grey_fab_item_id,
        abs(sum(inv_grey_fab_transactions.store_qty)) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_isu_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
        join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
        where inv_isus.issue_date>='".$date_from."' 
        and inv_isus.issue_date<='".$date_to."'

        and inv_isus.isu_basis_id in (1,2)
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_isu_items.deleted_at is null
        and inv_isus.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=2
        $storeCond
        $companyCond
        group by inv_grey_fab_isu_items.inv_grey_fab_item_id
        ) regular_isu"), "regular_isu.inv_grey_fab_item_id", "=", "all_isu.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_isu_items.inv_grey_fab_item_id,
        abs(sum(inv_grey_fab_transactions.store_qty)) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_isu_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
        join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
        where inv_isus.issue_date>='".$date_from."' 
        and inv_isus.issue_date<='".$date_to."'

        and inv_isus.isu_basis_id  = 9
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_isu_items.deleted_at is null
        and inv_isus.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=2
        $storeCond
        $companyCond
        group by inv_grey_fab_isu_items.inv_grey_fab_item_id
        ) trans_out_isu"), "trans_out_isu.inv_grey_fab_item_id", "=", "all_isu.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_isu_items.inv_grey_fab_item_id,
        abs(sum(inv_grey_fab_transactions.store_qty)) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_isu_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
        join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
        where inv_isus.issue_date>='".$date_from."' 
        and inv_isus.issue_date<='".$date_to."'

        and inv_isus.isu_basis_id  = 11
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_isu_items.deleted_at is null
        and inv_isus.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=2
        $storeCond
        $companyCond
        group by inv_grey_fab_isu_items.inv_grey_fab_item_id
        ) rcv_rtn_isu"), "rcv_rtn_isu.inv_grey_fab_item_id", "=", "all_isu.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_isu_items.inv_grey_fab_item_id,
        sum(inv_grey_fab_transactions.store_qty) as qty,
        sum(inv_grey_fab_transactions.store_amount) as amount
        from inv_grey_fab_isu_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
        join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
        where inv_isus.issue_date>='".$date_from."' 
        and inv_isus.issue_date<='".$date_to."'
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_isu_items.deleted_at is null
        and inv_isus.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=2
        $storeCond
        $companyCond
        group by inv_grey_fab_isu_items.inv_grey_fab_item_id
        ) greyfab_isu"), "greyfab_isu.inv_grey_fab_item_id", "=", "all_isu.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        max(inv_rcvs.receive_date) as receive_date
        from inv_grey_fab_rcv_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
        join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
        where 
        inv_rcvs.receive_basis_id in (1,2,3)
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=1
        $storeCond
        $companyCond
        group by inv_grey_fab_rcv_items.inv_grey_fab_item_id

        ) max_rcv_dt"), "max_rcv_dt.inv_grey_fab_item_id", "=", "all_isu.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        inv_rcvs.receive_date as receive_date,
        sum(inv_grey_fab_transactions.store_qty) as qty
        from inv_grey_fab_rcv_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_rcv_item_id=inv_grey_fab_rcv_items.id
        join inv_grey_fab_rcvs on inv_grey_fab_rcvs.id=inv_grey_fab_rcv_items.inv_grey_fab_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_grey_fab_rcvs.inv_rcv_id
        where  inv_rcvs.receive_basis_id in (1,2,3)
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=1
        $storeCond
        $companyCond
        group by 
        inv_grey_fab_rcv_items.inv_grey_fab_item_id,
        inv_rcvs.receive_date

        ) max_rcv_qty"), [["max_rcv_qty.receive_date", "=", "max_rcv_dt.receive_date"],["max_rcv_qty.inv_grey_fab_item_id", "=", "all_isu.inv_grey_fab_item_id"]])


        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_isu_items.inv_grey_fab_item_id,
        max(inv_isus.issue_date) as issue_date
        from inv_grey_fab_isu_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
        join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
        where  inv_isus.isu_basis_id in (1,2)
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_isu_items.deleted_at is null
        and inv_isus.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=2
        $storeCond
        $companyCond
        group by inv_grey_fab_isu_items.inv_grey_fab_item_id
        ) max_isu_dt"), "max_isu_dt.inv_grey_fab_item_id", "=", "all_isu.inv_grey_fab_item_id")

        ->leftJoin(\DB::raw("(
        select 
        inv_grey_fab_isu_items.inv_grey_fab_item_id,
        inv_isus.issue_date as issue_date,
        abs(sum(inv_grey_fab_transactions.store_qty)) as qty
        from inv_grey_fab_isu_items
        join inv_grey_fab_transactions on inv_grey_fab_transactions.inv_grey_fab_isu_item_id=inv_grey_fab_isu_items.id
        join inv_isus on inv_isus.id=inv_grey_fab_isu_items.inv_isu_id
        where 
        inv_isus.isu_basis_id in (1,2)
        and inv_grey_fab_transactions.deleted_at is null
        and inv_grey_fab_isu_items.deleted_at is null
        and inv_isus.deleted_at is null
        and inv_grey_fab_transactions.trans_type_id=2
        $storeCond
        $companyCond
        group by inv_grey_fab_isu_items.inv_grey_fab_item_id,inv_isus.issue_date
        ) max_isu_qty"), [["max_isu_qty.issue_date", "=", "max_isu_dt.issue_date"],["max_isu_qty.inv_grey_fab_item_id", "=", "all_isu.inv_grey_fab_item_id"]])

        //->where([['itemcategories.identity','=',9]])
        /*->whereIn('itemcategories.identity',[7,8])
        ->when(request('item_category_id'), function ($q) {
        return $q->where('itemcategories.id', '=', request('item_category_id', 0));
        })*/

        ->orderBy('inv_grey_fab_items.id')
        ->get([
        'inv_grey_fab_items.id',
        'inv_grey_fab_items.autoyarn_id',
        'inv_grey_fab_items.gmtspart_id',
        'inv_grey_fab_items.fabric_look_id',
        'inv_grey_fab_items.fabric_shape_id',
        'inv_grey_fab_items.gsm_weight',
        'inv_grey_fab_items.dia',
        'inv_grey_fab_items.measurment',
        'inv_grey_fab_items.roll_length',
        'inv_grey_fab_items.stitch_length',
        'inv_grey_fab_items.shrink_per',
        'inv_grey_fab_items.colorrange_id',
        'gmtsparts.name as gmtspart_name',
        'colorranges.name as colorrange_name',

        'pur_rcv.qty as pur_qty',
        'trans_in_rcv.qty as trans_in_qty',
        'isu_rtn_rcv.qty as isu_rtn_qty',
        'greyfab_rcv.qty as receive_qty',
        'greyfab_rcv.amount as receive_amount',
        'open_grey_fab_rcv.qty as open_receive_qty',
        'open_grey_fab_rcv.amount as open_receive_amount',

        'regular_isu.qty as regular_issue_qty',
        'trans_out_isu.qty as trans_out_issue_qty',
        'rcv_rtn_isu.qty as rcv_rtn_issue_qty',
        'greyfab_isu.qty as issue_qty',
        'greyfab_isu.amount as issue_amount',
        'open_grey_fab_isu.qty as open_issue_qty',
        'open_grey_fab_isu.amount as open_issue_amount',
        'max_rcv_dt.receive_date as max_receive_date',

        'max_rcv_qty.qty as max_receive_qty',
        'max_isu_dt.issue_date as max_issue_date',
        'max_isu_qty.qty as max_issue_qty',
        ])
        ->map(function($invgreyfabrcvitem) use($shiftname,$desDropdown,$fabriclooks,$fabricshape) {
        //$invgreyfabrcvitem->item_desc=$invgreyfabrcvitem->item_description.", ".$invgreyfabrcvitem->specification;
        $invgreyfabrcvitem->shift_name=$shiftname[$invgreyfabrcvitem->shift_id];
        $invgreyfabrcvitem->fabrication=$invgreyfabrcvitem->autoyarn_id?$desDropdown[$invgreyfabrcvitem->autoyarn_id]:'';
        $invgreyfabrcvitem->fabric_look=$invgreyfabrcvitem->fabric_look_id?$fabriclooks[$invgreyfabrcvitem->fabric_look_id]:'';
        $invgreyfabrcvitem->fabric_shape=$invgreyfabrcvitem->fabric_shape_id?$fabricshape[$invgreyfabrcvitem->fabric_shape_id]:'';
        //$invgreyfabrcvitem->body_part=$invgreyfabrcvitem->gmtspart_id?$gmtspart[$invgreyfabrcvitem->gmtspart_id]:'';


        $invgreyfabrcvitem->issue_qty=$invgreyfabrcvitem->issue_qty*-1;
        $invgreyfabrcvitem->issue_amount=$invgreyfabrcvitem->issue_amount;
        $invgreyfabrcvitem->opening_qty=$invgreyfabrcvitem->open_receive_qty-($invgreyfabrcvitem->open_issue_qty*-1);
        $invgreyfabrcvitem->opening_amount=$invgreyfabrcvitem->open_receive_amount-($invgreyfabrcvitem->open_issue_amount);
        $invgreyfabrcvitem->stock_qty=($invgreyfabrcvitem->opening_qty+$invgreyfabrcvitem->receive_qty)-($invgreyfabrcvitem->issue_qty);
        $invgreyfabrcvitem->stock_value=($invgreyfabrcvitem->opening_amount+$invgreyfabrcvitem->receive_amount)-($invgreyfabrcvitem->issue_amount);
        $invgreyfabrcvitem->rate=0;
        if($invgreyfabrcvitem->stock_qty){
        $invgreyfabrcvitem->rate=$invgreyfabrcvitem->stock_value/$invgreyfabrcvitem->stock_qty;
        }

        if($invgreyfabrcvitem->max_receive_date){
        $invgreyfabrcvitem->last_receive=date('d-M-Y',strtotime($invgreyfabrcvitem->max_receive_date));
        }
        else{
        $invgreyfabrcvitem->last_receive='';
        }

        if($invgreyfabrcvitem->max_issue_date){
        $invgreyfabrcvitem->last_issue=date('d-M-Y',strtotime($invgreyfabrcvitem->max_issue_date));
        }
        else{
        $invgreyfabrcvitem->last_issue='';
        }

        $now = time(); // or your date as well
        $max_issue_date = strtotime($invgreyfabrcvitem->max_issue_date);
        $datediff = $now - $max_issue_date;
        if($invgreyfabrcvitem->max_issue_date)
        {
        $invgreyfabrcvitem->diff_days=round($datediff / (60 * 60 * 24));
        }
        else
        {
        $invgreyfabrcvitem->diff_days='';
        }


        $invgreyfabrcvitem->opening_qty=number_format($invgreyfabrcvitem->opening_qty,2);
        $invgreyfabrcvitem->pur_qty=number_format($invgreyfabrcvitem->pur_qty,2);
        $invgreyfabrcvitem->trans_in_qty=number_format($invgreyfabrcvitem->trans_in_qty,2);
        $invgreyfabrcvitem->isu_rtn_qty=number_format($invgreyfabrcvitem->isu_rtn_qty,2);
        $invgreyfabrcvitem->receive_qty=number_format($invgreyfabrcvitem->receive_qty,2);
        $invgreyfabrcvitem->receive_amount=number_format($invgreyfabrcvitem->receive_amount,2);
        $invgreyfabrcvitem->regular_issue_qty=number_format($invgreyfabrcvitem->regular_issue_qty,2);
        $invgreyfabrcvitem->trans_out_issue_qty=number_format($invgreyfabrcvitem->trans_out_issue_qty,2);
        $invgreyfabrcvitem->rcv_rtn_issue_qty=number_format($invgreyfabrcvitem->rcv_rtn_issue_qty,2);
        $invgreyfabrcvitem->issue_qty=number_format($invgreyfabrcvitem->issue_qty,2);
        $invgreyfabrcvitem->issue_amount=number_format($invgreyfabrcvitem->issue_amount,2);
        $invgreyfabrcvitem->stock_qty=number_format($invgreyfabrcvitem->stock_qty,2);
        $invgreyfabrcvitem->rate=number_format($invgreyfabrcvitem->rate,2);
        $invgreyfabrcvitem->stock_value=number_format($invgreyfabrcvitem->stock_value,2);
        $invgreyfabrcvitem->max_receive_qty=number_format($invgreyfabrcvitem->max_receive_qty,2);
        $invgreyfabrcvitem->max_issue_qty=number_format($invgreyfabrcvitem->max_issue_qty,2);
        return $invgreyfabrcvitem;
        }); 
        echo json_encode($invgreyfabrcvitem);
    }
}