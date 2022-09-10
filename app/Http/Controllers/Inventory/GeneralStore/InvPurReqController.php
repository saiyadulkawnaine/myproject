<?php

namespace App\Http\Controllers\Inventory\GeneralStore;

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
use App\Library\Numbertowords;
//use Illuminate\Support\Facades\DB;
//use App\Library\pdf;

use App\Library\Template;
use App\Http\Requests\Inventory\GeneralStore\InvPurReqRequest;

class InvPurReqController extends Controller
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

    public function __construct(InvPurReqRepository $invpurreq, InvPurReqItemRepository $invpurreqitem, CompanyRepository $company, LocationRepository $location, CurrencyRepository $currency, ItemAccountRepository $itemaccount, ItemclassRepository $itemclass, ItemcategoryRepository $itemcategory, DepartmentRepository $department, UserRepository $user, DesignationRepository $designation)
    {
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

        // $this->middleware('permission:view.invpurreqs',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.invpurreqs', ['only' => ['store']]);
        // $this->middleware('permission:edit.invpurreqs',   ['only' => ['update']]);
        // $this->middleware('permission:delete.invpurreqs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paymode = config('bprs.paymode');
        $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
        $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
        $currency = array_prepend(array_pluck($this->currency->get(), 'name', 'id'), '-Select-', '');
        $yesno = array_prepend(config('bprs.yesno'), '-Select-', '');

        $invpurreqs = array();
        $rows = $this->invpurreq
            ->where([['inv_pur_reqs.requisition_type_id', 1]])
            ->orderBy('inv_pur_reqs.id', 'desc')
            ->take(1000)
            ->get();
        foreach ($rows as $row) {
            $invpurreq['id'] = $row->id;
            $invpurreq['requisition_no'] = $row->requisition_no;
            $invpurreq['requisition_type_id'] = $row->requisition_type_id;
            $invpurreq['company_id'] = isset($company[$row->company_id]) ? $company[$row->company_id] : '';
            $invpurreq['req_date'] = date('Y-m-d', strtotime($row->req_date));
            $invpurreq['delivery_by'] = date('Y-m-d', strtotime($row->req_date));
            $invpurreq['pay_mode'] = isset($paymode[$row->pay_mode]) ? $paymode[$row->pay_mode] : '';
            $invpurreq['currency_id'] = $currency[$row->currency_id];
            $invpurreq['location_id'] = isset($location[$row->location_id]) ? $location[$row->location_id] : '';
            $invpurreq['ready_to_approve_id'] = isset($yesno[$row->ready_to_approve_id]) ? $yesno[$row->ready_to_approve_id] : '';
            $invpurreq['remarks'] = $row->remarks;
            $invpurreq['final_approved_by'] = $row->final_approved_by;
            array_push($invpurreqs, $invpurreq);
        }
        echo json_encode($invpurreqs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $paymode = array_prepend(array_only(config('bprs.paymode'), [2, 3, 4, 5, 6]), '-Select-', '');
        $company = array_prepend(array_pluck($this->company->where([['status_id', '=', 1]])->get(), 'name', 'id'), '-Select-', '');
        $department = array_prepend(array_pluck($this->department->get(), 'name', 'id'), '', '');
        $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
        $currency = array_prepend(array_pluck($this->currency->get(), 'name', 'id'), '-Select-', '');
        $itemcategory = array_prepend(array_pluck($this->itemcategory->get(), 'name', 'id'), '-Select-', '');
        $itemclass = array_prepend(array_pluck($this->itemclass->get(), 'name', 'id'), '-Select-', '');
        $itemnature = array_prepend(config('bprs.itemnature'), '-Select-', '');
        $yesno = array_prepend(config('bprs.yesno'), '-Select-', '');
        $user = array_prepend(array_pluck($this->user->get(), 'name', 'id'), '-Select-', '');

        return Template::loadView('Inventory.GeneralStore.InvPurReq', ['paymode' => $paymode, 'company' => $company, 'location' => $location, 'currency' => $currency, 'itemcategory' => $itemcategory, 'itemclass' => $itemclass, 'itemnature' => $itemnature, 'department' => $department, 'user' => $user, 'yesno' => $yesno]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvPurReqRequest $request)
    {
        $max = $this->invpurreq
            ->where([['company_id', $request->company_id]])
            ->max('requisition_no');
        $requisition_no = $max + 1;
        $request->request->add(['requisition_type_id' => 1]);
        $invpurreq = $this->invpurreq->create([
            'requisition_no' => $requisition_no,
            'requisition_type_id' => $request->requisition_type_id,
            'company_id' => $request->company_id,
            'location_id' => $request->location_id,
            'req_date' => $request->req_date,
            'delivery_by' => $request->delivery_by,
            'disburse_by' => $request->disburse_by,
            'currency_id' => $request->currency_id,
            'demand_by_id' => $request->demand_by_id,
            'price_verified_by_id' => $request->price_verified_by_id,
            'pay_mode' => $request->pay_mode,
            'job_done_id' => $request->job_done_id,
            'job_completion_date' => $request->job_completion_date,
            'ready_to_approve_id' => 0,
            'remarks' => $request->remarks
        ]);
        if ($invpurreq) {
            return response()->json(array('success' => true, 'id' =>  $invpurreq->id, 'requisition_no' => $requisition_no, 'message' => 'Save Successfully'), 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invpurreq = $this->invpurreq->find($id);
        $row['fromData'] = $invpurreq;
        $dropdown['att'] = '';
        $row['dropDown'] = $dropdown;
        echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InvPurReqRequest $request, $id)
    {
        $req = $this->invpurreq->find($id);
        if ($req->first_approved_by) {
            $this->invpurreq->update($id, ['job_done_id' => $request->job_done_id, 'job_completion_date' => $request->job_completion_date]);
            return response()->json(array('success' => false, 'message' => 'This Requisition is approved so update not allowed except Job Done & Completion Date'), 200);
        }
        $invpurreq = $this->invpurreq->update($id, $request->except(['id', 'requisition_no', 'company_id']));
        if ($invpurreq) {
            return response()->json(array('success' => true, 'id' =>  $id, 'message' => 'Update Successfully'), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $req = $this->invpurreq->find($id);
        if ($req->first_approved_by) {
            return response()->json(array('success' => false, 'message' => 'This Requisition is approved so delete not allowed'), 200);
        }
        if ($this->invpurreq->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

    private function getData($id)
    {
        $paymode = config('bprs.paymode');
        $designation = array_prepend(array_pluck($this->designation->get(), 'name', 'id'), ' ', '');
        $rows = $this->invpurreq
            ->join('companies', function ($join) {
                $join->on('companies.id', '=', 'inv_pur_reqs.company_id');
            })
            ->join('currencies', function ($join) {
                $join->on('currencies.id', '=', 'inv_pur_reqs.currency_id');
            })
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'inv_pur_reqs.created_by');
            })
            ->leftJoin('users as updated_user', function ($join) {
                $join->on('updated_user.id', '=', 'inv_pur_reqs.updated_by');
            })

            ->leftJoin('users as demand_user', function ($join) {
                $join->on('demand_user.id', '=', 'inv_pur_reqs.demand_by_id');
            })
            ->leftJoin('employee_h_rs', function ($join) {
                $join->on('demand_user.id', '=', 'employee_h_rs.user_id');
            })

            ->leftJoin('users as price_varify_user', function ($join) {
                $join->on('price_varify_user.id', '=', 'inv_pur_reqs.price_verified_by_id');
            })
            ->leftJoin('employee_h_rs as varify_emp', function ($join) {
                $join->on('price_varify_user.id', '=', 'varify_emp.user_id');
            })

            ->leftJoin('users as first_approval', function ($join) {
                $join->on('first_approval.id', '=', 'inv_pur_reqs.first_approved_by');
            })
            ->leftJoin('employee_h_rs as first_approval_emp', function ($join) {
                $join->on('first_approval.id', '=', 'first_approval_emp.user_id');
            })
            ->leftJoin('users as second_approval', function ($join) {
                $join->on('second_approval.id', '=', 'inv_pur_reqs.second_approved_by');
            })
            ->leftJoin('users as third_approval', function ($join) {
                $join->on('third_approval.id', '=', 'inv_pur_reqs.third_approved_by');
            })
            ->leftJoin('users as final_approval', function ($join) {
                $join->on('final_approval.id', '=', 'inv_pur_reqs.final_approved_by');
            })
            ->where([['inv_pur_reqs.id', '=', $id]])
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
                'demand_user.signature_file as demand_user_signature',
                'price_varify_user.signature_file as price_varify_signature',

                'first_approval.name as first_approval_name',
                'first_approval.signature_file as first_approval_signature',
                'first_approval_emp.name as first_approval_emp_name',
                'first_approval_emp.contact as first_approval_emp_contact',
                'first_approval_emp.designation_id as first_approval_emp_designation',

                'second_approval.name as second_approval_name',
                'second_approval.signature_file as second_approval_signature',
                'third_approval.name as third_approval_name',
                'third_approval.signature_file as third_approval_signature',
                'final_approval.name as final_approval_name',
                'final_approval.signature_file as final_approval_signature',
            ]);

        foreach ($rows as $row) {
            $invpurreq['id'] = $row->id;
            $invpurreq['requisition_no'] = $row->requisition_no;
            $invpurreq['requisition_type_id'] = $row->requisition_type_id;
            $invpurreq['company_id'] = $row->company_id;
            $invpurreq['req_date'] = date('d-M-Y', strtotime($row->req_date));
            $invpurreq['delivery_by'] = ($row->delivery_by !== null) ? date('d-M-Y', strtotime($row->delivery_by)) : null;
            $invpurreq['demand_by'] = $row->demand_by;
            $invpurreq['pay_mode'] = isset($paymode[$row->pay_mode]) ? $paymode[$row->pay_mode] : '';
            $invpurreq['currency_name'] = $row->currency_name;
            //$invpurreq['location_id']=isset($location[$row->location_id])?$location[$row->location_id]:'';
            $invpurreq['remarks'] = $row->remarks;
            $invpurreq['user_name'] = $row->user_name;
            $invpurreq['created_at'] = date('d-M-Y', strtotime($row->created_at));

            $created_at = strtotime($row->created_at);
            $updated_at = strtotime($row->updated_at);
            if ($created_at == $updated_at) {
                $invpurreq['update_user_name'] = '';
                $invpurreq['updated_at'] = '';
            } else {
                $invpurreq['update_user_name'] = $row->update_user_name;
                $invpurreq['updated_at'] = date('d-M-Y', strtotime($row->updated_at));
            }

            $invpurreq['demand_user_name'] = $row->demand_user_name;
            $invpurreq['demand_contact'] = $row->demand_contact;
            $invpurreq['price_varify_user_name'] = $row->price_varify_user_name;
            $invpurreq['price_varify_user_contact'] = $row->price_varify_user_contact;
            $invpurreq['dd_designation'] = $designation[$row->dd_designation];
            $invpurreq['pv_designation'] = $designation[$row->pv_designation];

            $invpurreq['first_approval_name'] = $row->first_approval_name;
            $invpurreq['first_approval_emp_name'] = $row->first_approval_emp_name;
            $invpurreq['first_approval_emp_contact'] = $row->first_approval_emp_contact;
            $invpurreq['first_approval_emp_designation'] = $designation[$row->first_approval_emp_designation];

            $invpurreq['second_approval_name'] = $row->second_approval_name;
            $invpurreq['third_approval_name'] = $row->third_approval_name;
            $invpurreq['final_approval_name'] = $row->final_approval_name;

            $invpurreq['first_approved_at'] = $row->first_approved_at ? date('d-M-Y', strtotime($row->first_approved_at)) : '';
            $invpurreq['second_approved_at'] = $row->second_approved_at ? date('d-M-Y', strtotime($row->second_approved_at)) : '';
            $invpurreq['third_approved_at'] = $row->third_approved_at ? date('d-M-Y', strtotime($row->third_approved_at)) : '';
            $invpurreq['final_approved_at'] = $row->final_approved_at ? date('d-M-Y', strtotime($row->final_approved_at)) : '';

            $invpurreq['demand_user_signature'] = $row->demand_user_signature ? 'images/signature/' . $row->demand_user_signature : null;
            $invpurreq['price_varify_signature'] = $row->price_varify_signature ? 'images/signature/' . $row->price_varify_signature : null;

            $invpurreq['first_approval_signature'] = $row->first_approval_signature ? 'images/signature/' . $row->first_approval_signature : null;
            $invpurreq['second_approval_signature'] = $row->second_approval_signature ? 'images/signature/' . $row->second_approval_signature : null;
            $invpurreq['third_approval_signature'] = $row->third_approval_signature ? 'images/signature/' . $row->third_approval_signature : null;
            $invpurreq['final_approval_signature'] = $row->final_approval_signature ? 'images/signature/' . $row->final_approval_signature : null;
        }

        $company = $this->company->where([['id', '=', $invpurreq['company_id']]])->get()->first();
        $companyid = $company->id;

        $invpurreqitem = $this->invpurreq
            ->selectRaw('
            inv_pur_reqs.id as inv_pur_req_id,
            inv_pur_reqs.currency_id,
            inv_pur_req_items.id,
            inv_pur_req_items.item_account_id,
            inv_pur_req_items.department_id,   
            inv_pur_req_items.remarks as item_remarks,        
            item_accounts.item_description,
            item_accounts.sub_class_name,
            item_accounts.specification,
            item_accounts.uom_id,
            item_accounts.reorder_level,
            itemcategories.name as itemcategory_name,
            departments.name as department_name,           
            uoms.code as uom_code,
            sum(inv_pur_req_items.qty) as item_qty,   
            avg(inv_pur_req_items.rate) as item_rate,
            sum(inv_pur_req_items.amount) as item_amount,
            max_req_dt.req_date,
            max_req_no.requisition_no,
            max_req_qty.last_qty,
            stockgn.qty as general_stock_qty,
            stockdc.qty as dye_chem_stock_qty,
            max_rcv_no_gn.receive_no as max_receive_no_gn,
            max_rcv_sup_gn.code as supplier_name_gn,
            max_rcv_dt_gn.receive_date as max_receive_date_gn,
            max_rcv_qty_gn.qty as rcv_qty_gn,
            max_rcv_qty_gn.rate as rcv_rate_gn,

            max_rcv_no_dc.receive_no as max_receive_no_dc,
            max_rcv_sup_dc.code as supplier_name_dc,
            max_rcv_dt_dc.receive_date as max_receive_date_dc,
            max_rcv_qty_dc.qty as rcv_qty_dc,
            max_rcv_qty_dc.rate as rcv_rate_dc
        ')
            ->join('inv_pur_req_items', function ($join) {
                $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_items.inv_pur_req_id');
            })
            ->join('item_accounts', function ($join) {
                $join->on('item_accounts.id', '=', 'inv_pur_req_items.item_account_id');
            })
            ->leftJoin('itemclasses', function ($join) {
                $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
            })
            ->leftJoin('itemcategories', function ($join) {
                $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
            })
            ->leftJoin('uoms', function ($join) {
                $join->on('uoms.id', '=', 'item_accounts.uom_id');
            })
            ->leftJoin('departments', function ($join) {
                $join->on('departments.id', '=', 'inv_pur_req_items.department_id');
            })

            ->leftJoin(\DB::raw(
                "(
            select 
            
            inv_pur_req_items.item_account_id,
            max(inv_pur_reqs.req_date) as req_date
            from inv_pur_reqs
            join inv_pur_req_items on inv_pur_req_items.inv_pur_req_id=inv_pur_reqs.id
            where 
            inv_pur_reqs.id < " . $id . " 
            and inv_pur_reqs.company_id =" . $companyid . " 
            and inv_pur_reqs.deleted_at is null
            and inv_pur_req_items.deleted_at is null
            group by 
            
            inv_pur_req_items.item_account_id
            ) max_req_dt"
            ), "max_req_dt.item_account_id", "=", "item_accounts.id")
            ->leftJoin(\DB::raw(
                "(
            select 
            
            inv_pur_req_items.item_account_id,
            inv_pur_reqs.req_date,
            max(inv_pur_reqs.requisition_no) as requisition_no
            from inv_pur_reqs
            join inv_pur_req_items on inv_pur_req_items.inv_pur_req_id=inv_pur_reqs.id
            where
            inv_pur_reqs.id < " . $id . " 
            and inv_pur_reqs.company_id =" . $companyid . "
            and inv_pur_reqs.deleted_at is null
            and inv_pur_req_items.deleted_at is null
            group by 
            
            inv_pur_reqs.req_date,
            inv_pur_req_items.item_account_id
            ) max_req_no"
            ), [["max_req_no.req_date", "=", "max_req_dt.req_date"], ["max_req_no.item_account_id", "=", "item_accounts.id"]])

            ->leftJoin(\DB::raw(
                "(
            select 
            
            inv_pur_req_items.item_account_id,
            inv_pur_reqs.req_date,
            max(inv_pur_req_items.qty) as last_qty
            from inv_pur_reqs
            join inv_pur_req_items on inv_pur_req_items.inv_pur_req_id=inv_pur_reqs.id
            where 
            inv_pur_reqs.id < " . $id . "
            and inv_pur_reqs.company_id =" . $companyid . "
            and inv_pur_reqs.deleted_at is null
            and inv_pur_req_items.deleted_at is null
            group by 
            
            inv_pur_reqs.req_date,
            inv_pur_req_items.item_account_id
            ) max_req_qty"
            ), [["max_req_qty.req_date", "=", "max_req_dt.req_date"], ["max_req_qty.item_account_id", "=", "item_accounts.id"]])
            ->leftJoin(\DB::raw("(SELECT 
          inv_general_transactions.item_account_id,
          sum(inv_general_transactions.store_qty) as qty 
          FROM inv_general_transactions 
          where  inv_general_transactions.deleted_at is null
          and inv_general_transactions.company_id =" . $companyid . "
          group by inv_general_transactions.item_account_id
        ) stockgn"), "stockgn.item_account_id", "=", "item_accounts.id")
            ->leftJoin(\DB::raw("(SELECT 
          inv_dye_chem_transactions.item_account_id,
          sum(inv_dye_chem_transactions.store_qty) as qty 
          FROM inv_dye_chem_transactions 
          where  inv_dye_chem_transactions.deleted_at is null
          and inv_dye_chem_transactions.company_id =" . $companyid . "
          group by inv_dye_chem_transactions.item_account_id
        ) stockdc"), "stockdc.item_account_id", "=", "item_accounts.id")

            ->leftJoin(\DB::raw("(
        select 
        inv_general_rcv_items.item_account_id,
        max(inv_rcvs.receive_date) as receive_date
        from inv_general_rcv_items
        join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
        join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
        where 
        inv_rcvs.receive_basis_id in (1,2,3)
        and inv_general_transactions.deleted_at is null
        and inv_general_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_general_transactions.trans_type_id=1
        and inv_rcvs.company_id =" . $companyid . "
        group by inv_general_rcv_items.item_account_id
        ) max_rcv_dt_gn"), "max_rcv_dt_gn.item_account_id", "=", "item_accounts.id")

            ->leftJoin(\DB::raw("(
        select 
        inv_general_rcv_items.item_account_id,
        inv_rcvs.receive_date,
        max(inv_rcvs.id) as id
        from inv_general_rcv_items
        join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
        join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
        where  inv_rcvs.receive_basis_id in (1,2,3)
        and inv_general_transactions.deleted_at is null
        and inv_general_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_general_transactions.trans_type_id=1
        and inv_rcvs.company_id =" . $companyid . "
        group by 
        inv_general_rcv_items.item_account_id,
        inv_rcvs.receive_date
        ) max_rcv_id_gn"), [["max_rcv_id_gn.receive_date", "=", "max_rcv_dt_gn.receive_date"], ["max_rcv_id_gn.item_account_id", "=", "item_accounts.id"]])

            ->leftJoin(\DB::raw("(
        select 
        inv_general_rcv_items.item_account_id,
        inv_rcvs.id,
        max(inv_rcvs.receive_no) as receive_no
        from inv_general_rcv_items
        join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
        join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
        where  inv_rcvs.receive_basis_id in (1,2,3)
        and inv_general_transactions.deleted_at is null
        and inv_general_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_general_transactions.trans_type_id=1
        and inv_rcvs.company_id =" . $companyid . "
        group by 
        inv_general_rcv_items.item_account_id,
        inv_rcvs.id
        ) max_rcv_no_gn"), [["max_rcv_no_gn.id", "=", "max_rcv_id_gn.id"], ["max_rcv_no_gn.item_account_id", "=", "item_accounts.id"]])

            ->leftJoin(\DB::raw("(
        select 
        inv_general_rcv_items.item_account_id,
        inv_rcvs.id,
        suppliers.code
        from inv_general_rcv_items
        join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
        join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
        join suppliers on suppliers.id=inv_rcvs.supplier_id
        where  inv_rcvs.receive_basis_id in (1,2,3)
        and inv_general_transactions.deleted_at is null
        and inv_general_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_general_transactions.trans_type_id=1
        and inv_rcvs.company_id =" . $companyid . "
        group by 
        inv_general_rcv_items.item_account_id,
        inv_rcvs.id,
        suppliers.code
        ) max_rcv_sup_gn"), [["max_rcv_sup_gn.id", "=", "max_rcv_id_gn.id"], ["max_rcv_sup_gn.item_account_id", "=", "item_accounts.id"]])

            ->leftJoin(\DB::raw("(
        select 
        inv_general_rcv_items.item_account_id,
        inv_rcvs.id,
        sum(inv_general_transactions.store_qty) as qty,
        avg(inv_general_transactions.store_rate) as rate
        from inv_general_rcv_items
        join inv_general_transactions on inv_general_transactions.inv_general_rcv_item_id=inv_general_rcv_items.id
        join inv_general_rcvs on inv_general_rcvs.id=inv_general_rcv_items.inv_general_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_general_rcvs.inv_rcv_id
        where  inv_rcvs.receive_basis_id in (1,2,3)
        and inv_general_transactions.deleted_at is null
        and inv_general_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_general_transactions.trans_type_id=1
        and inv_rcvs.company_id =" . $companyid . "
        group by 
        inv_general_rcv_items.item_account_id,
        inv_rcvs.id

        ) max_rcv_qty_gn"), [["max_rcv_qty_gn.id", "=", "max_rcv_id_gn.id"], ["max_rcv_qty_gn.item_account_id", "=", "item_accounts.id"]])


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
        and inv_rcvs.company_id =" . $companyid . "
        group by inv_dye_chem_rcv_items.item_account_id
        ) max_rcv_dt_dc"), "max_rcv_dt_dc.item_account_id", "=", "item_accounts.id")

            ->leftJoin(\DB::raw("(
        select 
        inv_dye_chem_rcv_items.item_account_id,
        inv_rcvs.receive_date,
        max(inv_rcvs.id) as id
        from inv_dye_chem_rcv_items
        join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
        join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
        where  inv_rcvs.receive_basis_id in (1,2,3)
        and inv_dye_chem_transactions.deleted_at is null
        and inv_dye_chem_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_dye_chem_transactions.trans_type_id=1
        and inv_rcvs.company_id =" . $companyid . "
        group by 
        inv_dye_chem_rcv_items.item_account_id,
        inv_rcvs.receive_date
        ) max_rcv_id_dc"), [["max_rcv_id_dc.receive_date", "=", "max_rcv_dt_dc.receive_date"], ["max_rcv_id_dc.item_account_id", "=", "item_accounts.id"]])

            ->leftJoin(\DB::raw("(
        select 
        inv_dye_chem_rcv_items.item_account_id,
        inv_rcvs.id,
        max(inv_rcvs.receive_no) as receive_no
        from inv_dye_chem_rcv_items
        join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
        join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
        where  inv_rcvs.receive_basis_id in (1,2,3)
        and inv_dye_chem_transactions.deleted_at is null
        and inv_dye_chem_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_dye_chem_transactions.trans_type_id=1
        and inv_rcvs.company_id =" . $companyid . "
        group by 
        inv_dye_chem_rcv_items.item_account_id,
        inv_rcvs.id
        ) max_rcv_no_dc"), [["max_rcv_no_dc.id", "=", "max_rcv_id_dc.id"], ["max_rcv_no_dc.item_account_id", "=", "item_accounts.id"]])

            ->leftJoin(\DB::raw("(
        select 
        inv_dye_chem_rcv_items.item_account_id,
        inv_rcvs.id,
        suppliers.code
        from inv_dye_chem_rcv_items
        join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
        join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
        join suppliers on suppliers.id=inv_rcvs.supplier_id
        where  inv_rcvs.receive_basis_id in (1,2,3)
        and inv_dye_chem_transactions.deleted_at is null
        and inv_dye_chem_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_dye_chem_transactions.trans_type_id=1
        and inv_rcvs.company_id =" . $companyid . "
        group by 
        inv_dye_chem_rcv_items.item_account_id,
        inv_rcvs.id,
        suppliers.code
        ) max_rcv_sup_dc"), [["max_rcv_sup_dc.id", "=", "max_rcv_id_dc.id"], ["max_rcv_sup_dc.item_account_id", "=", "item_accounts.id"]])

            ->leftJoin(\DB::raw("(
        select 
        inv_dye_chem_rcv_items.item_account_id,
        inv_rcvs.id,
        sum(inv_dye_chem_transactions.store_qty) as qty,
        avg(inv_dye_chem_transactions.store_rate) as rate
        from inv_dye_chem_rcv_items
        join inv_dye_chem_transactions on inv_dye_chem_transactions.inv_dye_chem_rcv_item_id=inv_dye_chem_rcv_items.id
        join inv_dye_chem_rcvs on inv_dye_chem_rcvs.id=inv_dye_chem_rcv_items.inv_dye_chem_rcv_id
        join inv_rcvs on inv_rcvs.id=inv_dye_chem_rcvs.inv_rcv_id
        where  inv_rcvs.receive_basis_id in (1,2,3)
        and inv_dye_chem_transactions.deleted_at is null
        and inv_dye_chem_rcv_items.deleted_at is null
        and inv_rcvs.deleted_at is null
        and inv_dye_chem_transactions.trans_type_id=1
        and inv_rcvs.company_id =" . $companyid . "
        group by 
        inv_dye_chem_rcv_items.item_account_id,
        inv_rcvs.id

        ) max_rcv_qty_dc"), [["max_rcv_qty_dc.id", "=", "max_rcv_id_dc.id"], ["max_rcv_qty_dc.item_account_id", "=", "item_accounts.id"]])




            ->where([['inv_pur_reqs.id', '=', $id]])
            ->orderBy('inv_pur_req_items.id', 'asc')
            ->groupBy([
                'inv_pur_reqs.id',
                'inv_pur_reqs.currency_id',
                'inv_pur_req_items.id',
                'inv_pur_req_items.item_account_id',
                'inv_pur_req_items.department_id',
                'inv_pur_req_items.remarks',
                'item_accounts.item_description',
                'item_accounts.sub_class_name',
                'item_accounts.specification',
                'item_accounts.uom_id',
                'item_accounts.reorder_level',
                'itemcategories.name',
                'departments.name',
                'uoms.code',
                'max_req_dt.req_date',
                'max_req_no.requisition_no',
                'max_req_qty.last_qty',
                'stockgn.qty',
                'stockdc.qty',
                'max_rcv_no_gn.receive_no',
                'max_rcv_sup_gn.code',
                'max_rcv_dt_gn.receive_date',
                'max_rcv_qty_gn.qty',
                'max_rcv_qty_gn.rate',

                'max_rcv_no_dc.receive_no',
                'max_rcv_sup_dc.code',
                'max_rcv_dt_dc.receive_date',
                'max_rcv_qty_dc.qty',
                'max_rcv_qty_dc.rate'
            ])
            ->get()
            ->map(function ($invpurreqitem) {
                $invpurreqitem->currency_name = $invpurreqitem->currency_name;
                $invpurreqitem->item_description = $invpurreqitem->sub_class_name . ", " . $invpurreqitem->item_description . ", " . $invpurreqitem->specification;
                $invpurreqitem->req_date = '';
                if ($invpurreqitem->req_date) {
                    $invpurreqitem->req_date = date('d-M-Y', strtotime($invpurreqitem->req_date));
                }
                $invpurreqitem->last_qty = number_format($invpurreqitem->last_qty, 2);
                $invpurreqitem->stock_qty = $invpurreqitem->general_stock_qty ? number_format($invpurreqitem->general_stock_qty, 2) : number_format($invpurreqitem->dye_chem_stock_qty, 2);

                $invpurreqitem->receive_no = $invpurreqitem->max_receive_no_gn ? $invpurreqitem->max_receive_no_gn : $invpurreqitem->max_receive_no_dc;
                $invpurreqitem->supplier_name = $invpurreqitem->supplier_name_gn ? $invpurreqitem->supplier_name_gn : $invpurreqitem->supplier_name_dc;

                $invpurreqitem->receive_date = '';
                if ($invpurreqitem->max_receive_date_gn) {
                    $invpurreqitem->receive_date = date('d-M-Y', strtotime($invpurreqitem->max_receive_date_gn));
                }
                if ($invpurreqitem->max_receive_date_dc) {
                    $invpurreqitem->receive_date = date('d-M-Y', strtotime($invpurreqitem->max_receive_date_dc));
                }

                $invpurreqitem->receive_qty = $invpurreqitem->rcv_qty_gn ? number_format($invpurreqitem->rcv_qty_gn, 2) : number_format($invpurreqitem->rcv_qty_dc, 2);
                $invpurreqitem->receive_rate = $invpurreqitem->rcv_rate_gn ? number_format($invpurreqitem->rcv_rate_gn, 2) : number_format($invpurreqitem->rcv_rate_dc, 2);


                //$invpurreqitem->supplier_name=$invpurreqitem->supplier_name_gn?$invpurreqitem->supplier_name_gn:$invpurreqitem->supplier_name_dc,
                return $invpurreqitem;
            });

        $reason = array_prepend(config('bprs.reason'), '-Select-', '');
        $decision = array_prepend(config('bprs.decision'), '-Select-', '');

        $invpurreqassetbreakdown = $this->invpurreq
            ->join('inv_pur_req_asset_breakdowns', function ($join) {
                $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_asset_breakdowns.inv_pur_req_id');
            })
            ->join('asset_breakdowns', function ($join) {
                $join->on('asset_breakdowns.id', '=', 'inv_pur_req_asset_breakdowns.asset_breakdown_id');
            })
            ->join('asset_quantity_costs', function ($join) {
                $join->on('asset_quantity_costs.id', '=', 'asset_breakdowns.asset_quantity_cost_id');
            })
            ->join('asset_acquisitions', function ($join) {
                $join->on('asset_acquisitions.id', '=', 'asset_quantity_costs.asset_acquisition_id');
            })
            ->where([['inv_pur_reqs.id', '=', $id]])
            ->orderBy('inv_pur_req_asset_breakdowns.id', 'desc')
            ->get([
                'inv_pur_req_asset_breakdowns.id as inv_pur_req_asset_breakdown_id',
                'asset_breakdowns.id as asset_breakdown_id',
                'asset_breakdowns.reason_id',
                'asset_breakdowns.decision_id',
                'asset_breakdowns.breakdown_at',
                'asset_breakdowns.remarks',
                'asset_quantity_costs.custom_no',
                'asset_acquisitions.name as asset_name',
                'asset_acquisitions.asset_group',
                'asset_acquisitions.brand',
                'asset_acquisitions.origin'
            ])
            ->map(function ($invpurreqassetbreakdown) use ($reason, $decision) {
                $invpurreqassetbreakdown->asset_name = $invpurreqassetbreakdown->asset_name . ", " . $invpurreqassetbreakdown->asset_group . ", " . $invpurreqassetbreakdown->brand . ", " . $invpurreqassetbreakdown->origin;
                $invpurreqassetbreakdown->reason = isset($reason[$invpurreqassetbreakdown->reason_id]) ? $reason[$invpurreqassetbreakdown->reason_id] : '';
                $invpurreqassetbreakdown->decision = isset($decision[$invpurreqassetbreakdown->decision_id]) ? $decision[$invpurreqassetbreakdown->decision_id] : '';
                $invpurreqassetbreakdown->breakdown_date = date('Y-m-d', strtotime($invpurreqassetbreakdown->breakdown_at));
                return $invpurreqassetbreakdown;
            });


        //$lastRequisition=$invpurreqitem->get(['inv_pur_req_paids.amount as req_amount']);



        $invpurreqpaid = $this->invpurreq
            ->join('inv_pur_req_paids', function ($join) {
                $join->on('inv_pur_reqs.id', '=', 'inv_pur_req_paids.inv_pur_req_id');
            })
            ->leftJoin('users', function ($join) {
                $join->on('users.id', '=', 'inv_pur_req_paids.user_id');
            })
            ->leftJoin('users as updated_user', function ($join) {
                $join->on('updated_user.id', '=', 'inv_pur_req_paids.updated_by');
            })
            ->leftJoin(\DB::raw("(SELECT 
            inv_pur_reqs.id as inv_pur_req_id,
            sum(inv_pur_req_paids.amount) as paid_amount
            FROM inv_pur_reqs
            join inv_pur_req_paids on inv_pur_reqs.id=inv_pur_req_paids.inv_pur_req_id
            where inv_pur_reqs.id =12
            GROUP BY
            inv_pur_reqs.id ) paid"), 'paid.inv_pur_req_id', '=', 'inv_pur_reqs.id')
            ->where([['inv_pur_reqs.id', '=', $id]])
            ->get([
                'inv_pur_reqs.id as inv_pur_req_id',
                'inv_pur_req_paids.user_id',
                'inv_pur_req_paids.amount as paid_amount',
                'inv_pur_req_paids.paid_date',
                'users.name as user_name',
                'updated_user.name as updatedby_user_name',
                'inv_pur_req_paids.updated_by',
                'inv_pur_req_paids.updated_at as entry_date',
            ])
            ->map(function ($invpurreqpaid) {
                $invpurreqpaid->paid_date = date('d-M-Y', strtotime($invpurreqpaid->paid_date));
                $invpurreqpaid->entry_date = date('d-M-Y', strtotime($invpurreqpaid->entry_date));
                return $invpurreqpaid;
            });

        $item_amount = $invpurreqitem->sum('item_amount');
        $paid_amount = $invpurreqpaid->sum('paid_amount');
        $amount = $item_amount - $paid_amount;
        $inword = Numbertowords::ntow(number_format($item_amount, 2, '.', ''), $row->currency_name);
        $invpurreqitem->inword = $inword;

        $comment_histories = $this->invpurreq
            ->join('approval_comment_histories', function ($join) {
                $join->on('inv_pur_reqs.id', '=', 'approval_comment_histories.model_id');
            })
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'approval_comment_histories.comments_by');
            })
            ->where([['approval_comment_histories.model_type', '=', 'inv_pur_reqs']])
            ->where([['inv_pur_reqs.id', '=', $id]])
            ->orderBy('approval_comment_histories.id')
            ->get(['approval_comment_histories.*', 'users.name as user_name']);



        $invpurreq['invpurreqitem'] = $invpurreqitem;
        $invpurreq['invpurreqpaid'] = $invpurreqpaid;
        $invpurreq['paid_amount'] = $paid_amount;
        $invpurreq['invpurreqassetbreakdown'] = $invpurreqassetbreakdown;
        $invpurreq['company'] = $company;
        $invpurreq['comment_histories'] = $comment_histories;

        return $invpurreq;
    }

    public function getPrPdf()
    {

        $id = request('id', 0);
        $invpurreq = $this->getData($id);

        $company = $invpurreq['company'];
        //dd($data['master']);die;
        $pdf = new \Pdf('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $header = ['logo' => $company->logo, 'address' => $company->address, 'title' => 'PURCHASE REQUISITION : ' . $invpurreq['remarks']];
        $pdf->setCustomHeader($header);
        $pdf->SetPrintHeader(true);
        //$pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(true);
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(10, 42, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->AddPage();
        $barcodestyle = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );
        $pdf->SetY(5);
        $pdf->SetX(190);
        $challan = str_pad($id, 10, 0, STR_PAD_LEFT);
        $pdf->write1DBarcode(str_pad($challan, 10, 0, STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');
        $pdf->SetFont('helvetica', 'N', 10);
        /* $pdf->SetY(10);
        $txt = $prodgmtdlvinput['screenPrint']->supplier_name;
        $pdf->Write(0, 'Challan', '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetY(5);
        $pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTitle($txt); */
        $pdf->SetFont('helvetica', '', 8);
        $view = \View::make('Defult.Inventory.GeneralStore.InvPurReqPdf', ['invpurreq' => $invpurreq, 'is_html' => 0]);
        $html_content = $view->render();
        $pdf->SetY(42);
        $pdf->WriteHtml($html_content, true, false, true, false, '');
        $filename = storage_path() . '/InvPurReqPdf.pdf';
        $pdf->output($filename);
        exit();
    }

    public function getHtml()
    {
        $id = request('id', 0);
        $approval_type = request('approval_type', 0);
        $invpurreq = $this->getData($id);
        $company = $invpurreq['company'];
        return Template::loadView('Inventory.GeneralStore.InvPurReqPdf', [
            'invpurreq' => $invpurreq,
            'company' => $company,
            'is_html' => 1,
            'approval_type' => $approval_type,
        ]);
    }
    public function getAllInvPurReq()
    {
        $paymode = config('bprs.paymode');
        $company = array_prepend(array_pluck($this->company->get(), 'name', 'id'), '-Select-', '');
        $location = array_prepend(array_pluck($this->location->get(), 'name', 'id'), '-Select-', '');
        $currency = array_prepend(array_pluck($this->currency->get(), 'name', 'id'), '-Select-', '');


        $invpurreqs = array();
        $rows = $this->invpurreq
            ->when(request('company_id'), function ($q) {
                return $q->where('inv_pur_reqs.company_id', '=', request('company_id', 0));
            })
            ->when(request('date_from'), function ($q) {
                return $q->where('inv_pur_reqs.req_date', '>=', request('date_from', 0));
            })
            ->when(request('date_to'), function ($q) {
                return $q->where('inv_pur_reqs.req_date', '<=', request('date_to', 0));
            })
            ->where([['inv_pur_reqs.requisition_type_id', 1]])
            ->orderBy('inv_pur_reqs.id', 'desc')
            ->get();
        foreach ($rows as $row) {
            $invpurreq['id'] = $row->id;
            $invpurreq['requisition_no'] = $row->requisition_no;
            $invpurreq['requisition_type_id'] = $row->requisition_type_id;
            $invpurreq['company_id'] = isset($company[$row->company_id]) ? $company[$row->company_id] : '';
            $invpurreq['req_date'] = date('Y-m-d', strtotime($row->req_date));
            $invpurreq['delivery_by'] = date('Y-m-d', strtotime($row->req_date));
            $invpurreq['pay_mode'] = isset($paymode[$row->pay_mode]) ? $paymode[$row->pay_mode] : '';
            $invpurreq['currency_id'] = $currency[$row->currency_id];
            $invpurreq['location_id'] = isset($location[$row->location_id]) ? $location[$row->location_id] : '';
            $invpurreq['remarks'] = $row->remarks;
            $invpurreq['first_approved_by'] = $row->first_approved_by;
            array_push($invpurreqs, $invpurreq);
        }
        echo json_encode($invpurreqs);
    }
}
