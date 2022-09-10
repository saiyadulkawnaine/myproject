<?php

namespace App\Http\Controllers\Report\ItemBank;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqRepository;
use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqItemRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\DesignationRepository;

use App\Library\Template;


class PurchaseRequisitionReportController extends Controller
{
    private $invpurreq;
    private $invpurreqitem;
    private $company;
    private $location;
    private $currency;
    private $itemaccount;
    private $itemclass;
    private $itemcategory;
    private $department;
    private $designation;

    public function __construct(InvPurReqRepository $invpurreq,InvPurReqItemRepository $invpurreqitem,CompanyRepository $company,LocationRepository $location,CurrencyRepository $currency,ItemAccountRepository $itemaccount,ItemclassRepository $itemclass,ItemcategoryRepository $itemcategory,DepartmentRepository $department,UserRepository $user,DesignationRepository $designation) {

        $this->invpurreq = $invpurreq;
        $this->invpurreqitem = $invpurreqitem;
        $this->company = $company;
        $this->location = $location;
        $this->currency = $currency;
        $this->itemaccount = $itemaccount;
        $this->itemclass = $itemclass;
        $this->itemcategory = $itemcategory;
        $this->department = $department;
        $this->user = $user;
        $this->designation = $designation;

        $this->middleware('auth');
		// $this->middleware('permission:view.poaopserviceitems',   ['only' => ['create', 'index','show']]);

	}

    public function index()
    {
        $paymode=array_prepend(config('bprs.paymode'),'IOU','1');
        $company=array_prepend(array_pluck($this->company->where([['status_id','=',1]])->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        $itemcategory=array_prepend(array_pluck($this->itemcategory->get(),'name','id'),'-Select-','');
        $itemclass=array_prepend(array_pluck($this->itemclass->get(),'name','id'),'-Select-','');
        $itemnature=array_prepend(config('bprs.itemnature'),'-Select-','');
        $user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');

        return Template::loadView('Report.ItemBank.PurchaseRequisitionReport',['paymode'=>$paymode, 'company'=>$company,'location'=>$location,'currency'=>$currency,'itemcategory'=>$itemcategory,'itemclass'=>$itemclass,'itemnature'=>$itemnature,'department'=>$department,'user'=>$user]);
    }

    public function reportData() {
        $paymode=array_prepend(config('bprs.paymode'),'IOU','1');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'','');
        $date_from=request('date_from',0);
        $date_to=request('date_to',0);
        $company_id=request('company_id', 0);
        $requisition_no=request('requisition_no', 0);
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),' ','');
        $yesno=array_prepend(config('bprs.yesno'),'--','');

        $invpurreqs=array();
        $rows=$this->invpurreq
        ->join('companies',function($join){
            $join->on('companies.id','=','inv_pur_reqs.company_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','inv_pur_reqs.currency_id');
        })
        ->join('users',function($join){
            $join->on('users.id','=','inv_pur_reqs.created_by');
        })
        ->leftJoin('users as updated_user',function($join){
            $join->on('updated_user.id','=','inv_pur_reqs.updated_by');
        })

        ->leftJoin('users as demand_user',function($join){
            $join->on('demand_user.id','=','inv_pur_reqs.demand_by_id');
        })
        ->leftJoin('employee_h_rs',function($join){
            $join->on('demand_user.id','=','employee_h_rs.user_id');
        })

        ->leftJoin('users as price_varify_user',function($join){
            $join->on('price_varify_user.id','=','inv_pur_reqs.price_verified_by_id');
        })
        ->leftJoin('employee_h_rs as varify_emp',function($join){
            $join->on('price_varify_user.id','=','varify_emp.user_id');
        })
        ->leftJoin(\DB::raw("(select
            inv_pur_reqs.id as inv_pur_req_id,
            sum(inv_pur_req_items.qty) as qty,
            avg(inv_pur_req_items.rate) as rate,
            sum(inv_pur_req_items.amount) as amount
            from inv_pur_reqs
            join inv_pur_req_items on inv_pur_req_items.inv_pur_req_id=inv_pur_reqs.id
            group by inv_pur_reqs.id) puritem"), "puritem.inv_pur_req_id", "=", "inv_pur_reqs.id")
        ->leftJoin(\DB::raw("(select
            inv_pur_reqs.id as inv_pur_req_id,
            sum(inv_pur_req_paids.amount) as paid_amount
            from inv_pur_reqs
            join inv_pur_req_paids on inv_pur_req_paids.inv_pur_req_id=inv_pur_reqs.id
            group by inv_pur_reqs.id) puritempaid"), "puritempaid.inv_pur_req_id", "=", "inv_pur_reqs.id")
        ->when(request('date_from'), function ($q) use($date_from) {
            return $q->where('inv_pur_reqs.req_date', '>=',$date_from);
        })
        ->when(request('date_to'), function ($q) use($date_to) {
            return $q->where('inv_pur_reqs.req_date', '<=', $date_to);
        })
        ->when(request('company_id'), function ($q) use($company_id) {
            return $q->where('inv_pur_reqs.company_id', '=', $company_id);
        })
        ->when(request('requisition_no'), function ($q) use($requisition_no) {
            return $q->where('inv_pur_reqs.requisition_no', '=', $requisition_no);
        })
        ->get([
            'inv_pur_reqs.*',
            'companies.id as company_id',
            'companies.name as company_name',
            'currencies.code as currency_name',
            'users.name as user_name',
            'updated_user.name as update_user_name',
            'employee_h_rs.name as demand_user_name',
            'employee_h_rs.contact as demand_contact',
            'employee_h_rs.designation_id as dd_designation',
            'varify_emp.name as price_varify_user_name',
            'varify_emp.contact as price_varify_user_contact',
            'varify_emp.designation_id as pv_designation',
            'puritem.qty',
            'puritem.rate',
            'puritem.amount',
            'puritempaid.paid_amount',
        ])
        ->map(function($rows){
            $rows->balance_amount=$rows->amount-$rows->paid_amount;
            return $rows;
        });
        
        foreach($rows as $row){
            $invpurreq['id']=$row->id;
            $invpurreq['requisition_no']=$row->requisition_no;
            $invpurreq['requisition_type_id']=$row->requisition_type_id;
            $invpurreq['company_id']=$row->company_name;
            $invpurreq['req_date']=date('d-M-Y',strtotime($row->req_date));
            $invpurreq['delivery_by']=$row->delivery_by?date('d-M-Y',strtotime($row->delivery_by)):'--';
            $invpurreq['demand_by']=$row->demand_by;
            $invpurreq['pay_mode']=isset($paymode[$row->pay_mode])?$paymode[$row->pay_mode]:'';
            $invpurreq['currency_name']=$row->currency_name;
            $invpurreq['location_id']=isset($location[$row->location_id])?$location[$row->location_id]:'';
            $invpurreq['remarks']=$row->remarks;
            $invpurreq['user_name']=$row->user_name;
            $invpurreq['job_done']=$yesno[$row->job_done_id];
            $invpurreq['job_completion_date']=$row->job_completion_date?date('d-M-Y',strtotime($row->job_completion_date)):'--';
            $invpurreq['created_at']=date('d-M-Y',strtotime($row->created_at));
            $invpurreq['qty']=number_format($row->qty,2,'.',',');
            $invpurreq['rate']=number_format($row->rate,4,'.',',');
            $invpurreq['amount']=number_format($row->amount,2,'.',',');
            $invpurreq['paid_amount']=number_format($row->paid_amount,2,'.',',');
            $invpurreq['balance_amount']=number_format($row->balance_amount,2,'.',',');
            
            if ($row->first_approved_at && !$row->second_approved_at) {
                $invpurreq['approve_status']='First Approval';
            }
            elseif ($row->second_approved_at && !$row->third_approved_at) {
                $invpurreq['approve_status']='Second Approval';
            }
            elseif ($row->third_approved_at && !$row->final_approved_at) {
                $invpurreq['approve_status']='Third Approval';
            }
            elseif ($row->final_approved_at) {
                $invpurreq['approve_status']='Final Approval';
            }else {
                $invpurreq['approve_status']='--';
            }
            
            $invpurreq['demand_user_name']=$row->demand_user_name;
            $invpurreq['demand_contact']=$row->demand_contact;
            $invpurreq['price_varify_user_name']=$row->price_varify_user_name;
            $invpurreq['price_varify_user_contact']=$row->price_varify_user_contact;
            $invpurreq['dd_designation']=$designation[$row->dd_designation];
            $invpurreq['pv_designation']=$designation[$row->pv_designation];
           
            array_push($invpurreqs,$invpurreq);
        }
        echo json_encode($invpurreqs);
    }

    public function getRequisition(){
        $paymode=config('bprs.paymode');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        $company_id=request('company_id', 0);
        $requisition_no=request('requisition_no', 0); 

        $rows=$this->invpurreq
        ->when(request('company_id'), function ($q) use($company_id) {
            return $q->where('inv_pur_reqs.company_id', '=', $company_id);
        })
        ->when(request('requisition_no'), function ($q) use($requisition_no) {
            return $q->where('inv_pur_reqs.requisition_no', '=', $requisition_no);
        })
        //->where([['inv_pur_reqs.requisition_type_id',1]])
        ->orderBy('inv_pur_reqs.id','desc')
        ->get(['inv_pur_reqs.*'])
        ->map(function($rows) use($company,$paymode,$location,$currency){
            $rows->company=isset($company[$rows->company_id])?$company[$rows->company_id]:'';
            $rows->req_date=date('Y-m-d',strtotime($rows->req_date));
            $rows->delivery_by=date('Y-m-d',strtotime($rows->req_date));
            $rows->pay_mode=isset($paymode[$rows->pay_mode])?$paymode[$rows->pay_mode]:'';
            $rows->currency_id=$currency[$rows->currency_id];
            $rows->location_id=isset($location[$rows->location_id])?$location[$rows->location_id]:'';
            return $rows;
        });

        echo json_encode($rows);
    }

}