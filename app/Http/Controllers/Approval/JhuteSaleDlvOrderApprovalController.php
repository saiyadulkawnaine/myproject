<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Sms;

class JhuteSaleDlvOrderApprovalController extends Controller
{
    private $jhutesaledlvorder;
    private $user;
    private $buyer;
    private $company;

    public function __construct(
		JhuteSaleDlvOrderRepository $jhutesaledlvorder,
		UserRepository $user,
		BuyerRepository $buyer,
		CompanyRepository $company

    ) {
        $this->jhutesaledlvorder = $jhutesaledlvorder;
        $this->user = $user;
        $this->company = $company;
        $this->buyer = $buyer;

        $this->middleware('auth');

        $this->middleware('permission:approve.jhutesaledlvorderapproval',   ['only' => ['approved', 'index','reportData','reportDataApp','unapproved']]);

    }
    
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        return Template::loadView('Approval.JhuteSaleDlvOrderApproval',['company'=>$company,'buyer'=>$buyer]);
    }
	public function reportData() {
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
        $dofor=array_prepend(config('bprs.dofor'),'-Select-','');

        $rows=$this->jhutesaledlvorder
        ->join('companies',function($join){
            $join->on('companies.id','=','jhute_sale_dlv_orders.company_id');
        })
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','jhute_sale_dlv_orders.buyer_id');
        })
        ->leftJoin('locations',function($join){
            $join->on('locations.id','=','jhute_sale_dlv_orders.location_id');
        })
        ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','jhute_sale_dlv_orders.currency_id');
        })
        ->leftJoin('users',function($join){
            $join->on('users.id','=','jhute_sale_dlv_orders.advised_by_id');
        })
        ->leftJoin('users as price_verifies',function($join){
            $join->on('price_verifies.id','=','jhute_sale_dlv_orders.price_verified_by_id');
        })
        ->leftJoin(\DB::raw("(
            select
            jhute_sale_dlv_orders.id as jhute_sale_dlv_order_id,
            sum(jhute_sale_dlv_order_items.qty) as item_qty,
            sum(jhute_sale_dlv_order_items.amount) as item_amount
            from jhute_sale_dlv_orders
            join jhute_sale_dlv_order_items on jhute_sale_dlv_order_items.jhute_sale_dlv_order_id=jhute_sale_dlv_orders.id
            group by jhute_sale_dlv_orders.id
        ) saleitems"), "saleitems.jhute_sale_dlv_order_id", "=", "jhute_sale_dlv_orders.id")
        ->leftJoin(\DB::raw("(
            select
            jhute_sale_dlv_orders.id as jhute_sale_dlv_order_id,
            sum(jhute_sale_dlv_order_payments.amount) as paid_amount
            from jhute_sale_dlv_orders
            join jhute_sale_dlv_order_payments on jhute_sale_dlv_order_payments.jhute_sale_dlv_order_id=jhute_sale_dlv_orders.id
            group by jhute_sale_dlv_orders.id
        ) payments"), "payments.jhute_sale_dlv_order_id", "=", "jhute_sale_dlv_orders.id")
        ->when(request('company_id'), function ($q) {
            return $q->where('jhute_sale_dlv_orders.company_id', '=',request('company_id', 0));
        })
        ->when(request('buyer_id'), function ($q) {
            return $q->where('jhute_sale_dlv_orders.buyer_id', '=',request('buyer_id', 0));
        })
        ->when(request('date_from'), function ($q) {
            return $q->where('jhute_sale_dlv_orders.do_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
            return $q->where('jhute_sale_dlv_orders.do_date', '<=',request('date_to', 0));
        })
        ->whereNull('jhute_sale_dlv_orders.approved_at')
        ->where([['jhute_sale_dlv_orders.status_id','=',1]])
        ->where([['jhute_sale_dlv_orders.ready_to_approve_id','=',1]])
        ->orderBy('jhute_sale_dlv_orders.id','desc')
        ->get(
        [
            'jhute_sale_dlv_orders.*',
            'companies.code as company_code',
            'buyers.name as buyer_name',
            'locations.name as location_name',
            'currencies.code as currency_code',
            'users.name as advised_by',
            'price_verifies.name as price_verified_by',
            'saleitems.item_qty',
            'saleitems.item_amount',
            'payments.paid_amount'
        ])
        ->map(function($rows) use($yesno,$dofor){
            $rows->payment_before_dlv=$yesno[$rows->payment_before_dlv_id];
            $rows->do_for=$dofor[$rows->do_for];
            $rows->do_date=date('Y-m-d',strtotime($rows->do_date));
            $rows->etd_date=$rows->etd_date?date('Y-m-d',strtotime($rows->etd_date)):'--';
            $rows->item_qty=$rows->item_qty;
            $rows->item_amount=$rows->item_amount;
            $rows->paid_amount=$rows->paid_amount;
            return $rows;
        });
        echo json_encode($rows);
    }

    public function approved (Request $request)
    {
    	$id=request('id',0);
    	$master=$this->jhutesaledlvorder->find($id);
		$user = \Auth::user();
		$approved_at=date('Y-m-d h:i:s');

        $master->approved_by=$user->id;
        $master->approved_at=$approved_at;
        $master->unapproved_by=NULL;
        $master->unapproved_at=NULL;
        $master->timestamps=false;
        $jhutesaledlvorder=$master->save();
		

		if($jhutesaledlvorder){
		return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
		}
    }

    public function reportDataApp() {
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
        $dofor=array_prepend(config('bprs.dofor'),'-Select-','');

        $rows=$this->jhutesaledlvorder
        ->join('companies',function($join){
            $join->on('companies.id','=','jhute_sale_dlv_orders.company_id');
        })
        ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','jhute_sale_dlv_orders.buyer_id');
        })
        ->leftJoin('locations',function($join){
            $join->on('locations.id','=','jhute_sale_dlv_orders.location_id');
        })
        ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','jhute_sale_dlv_orders.currency_id');
        })
        ->leftJoin('users',function($join){
            $join->on('users.id','=','jhute_sale_dlv_orders.advised_by_id');
        })
        ->leftJoin('users as price_verifies',function($join){
            $join->on('price_verifies.id','=','jhute_sale_dlv_orders.price_verified_by_id');
        })
        ->leftJoin(\DB::raw("(
            select
            jhute_sale_dlv_orders.id as jhute_sale_dlv_order_id,
            sum(jhute_sale_dlv_order_items.qty) as item_qty,
            sum(jhute_sale_dlv_order_items.amount) as item_amount
            from jhute_sale_dlv_orders
            join jhute_sale_dlv_order_items on jhute_sale_dlv_order_items.jhute_sale_dlv_order_id=jhute_sale_dlv_orders.id
            group by jhute_sale_dlv_orders.id
        ) saleitems"), "saleitems.jhute_sale_dlv_order_id", "=", "jhute_sale_dlv_orders.id")
        ->leftJoin(\DB::raw("(
            select
            jhute_sale_dlv_orders.id as jhute_sale_dlv_order_id,
            sum(jhute_sale_dlv_order_payments.amount) as paid_amount
            from jhute_sale_dlv_orders
            join jhute_sale_dlv_order_payments on jhute_sale_dlv_order_payments.jhute_sale_dlv_order_id=jhute_sale_dlv_orders.id
            group by jhute_sale_dlv_orders.id
        ) payments"), "payments.jhute_sale_dlv_order_id", "=", "jhute_sale_dlv_orders.id")
        ->when(request('company_id'), function ($q) {
            return $q->where('jhute_sale_dlv_orders.company_id', '=',request('company_id', 0));
        })
        ->when(request('buyer_id'), function ($q) {
            return $q->where('jhute_sale_dlv_orders.buyer_id', '=',request('buyer_id', 0));
        })
        ->when(request('date_from'), function ($q) {
            return $q->where('jhute_sale_dlv_orders.do_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
            return $q->where('jhute_sale_dlv_orders.do_date', '<=',request('date_to', 0));
        })
        ->whereNotNull('jhute_sale_dlv_orders.approved_at')
        ->orderBy('jhute_sale_dlv_orders.id','desc')
        ->get([
            'jhute_sale_dlv_orders.*',
            'companies.code as company_code',
            'buyers.name as buyer_name',
            'locations.name as location_name',
            'currencies.code as currency_code',
            'users.name as advised_by',
            'price_verifies.name as price_verified_by',
            'saleitems.item_qty',
            'saleitems.item_amount',
            'payments.paid_amount'
        ])
        ->map(function($rows) use($yesno,$dofor){
            $rows->payment_before_dlv=$yesno[$rows->payment_before_dlv_id];
            $rows->do_for=$dofor[$rows->do_for];
            $rows->do_date=date('Y-m-d',strtotime($rows->do_date));
            $rows->etd_date=$rows->etd_date?date('Y-m-d',strtotime($rows->etd_date)):'--';
            $rows->item_qty=number_format($rows->item_qty,2);
            $rows->item_amount=number_format($rows->item_amount,2);
            $rows->paid_amount=number_format($rows->paid_amount,2);
            return $rows;
        });

        echo json_encode($rows);
    }

    public function unapproved (Request $request)
    {
        $id=request('id',0);
        $master=$this->jhutesaledlvorder->find($id);
        $user = \Auth::user();
        $unapproved_at=date('Y-m-d h:i:s');
        $unapproved_count=$master->unapproved_count+1;
        $master->approved_by=NUll;
        $master->approved_at=NUll;
        $master->unapproved_by=$user->id;
        $master->unapproved_at=$unapproved_at;
        $master->unapproved_count=$unapproved_count;
        $master->timestamps=false;
        $jhutesaledlvorder=$master->save();


        if($jhutesaledlvorder){
            return response()->json(array('success' => true,  'message' => 'Unapproved Successfully'), 200);
        }
    }
}
