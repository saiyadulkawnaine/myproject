<?php

namespace App\Http\Controllers\Commercial\Import;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\BankRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\BankAccountRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Account\AccTermLoanRepository;
use App\Repositories\Contracts\Account\AccTermLoanInstallmentRepository;
use Illuminate\Support\Carbon;
use App\Library\Template;
use App\Library\Numbertowords;
use App\Library\Sms;
use App\Http\Requests\Commercial\Import\ImpLcRequest;

class ImpLcController extends Controller {

    private $implc;
    private $currency;
    private $supplier;
    private $bank;
    private $company;
    private $country;
    private $itemcategory;
    private $explcsc;
    private $bankbranch;
    private $bankaccount;
    private $user;
    private $acctermloan;
    private $acctermloaninstallment;

    public function __construct(
        ImpLcRepository $implc,
        CurrencyRepository $currency,
        CountryRepository $country,
        SupplierRepository $supplier,
        BankRepository $bank,
        CompanyRepository $company,
        ItemcategoryRepository $itemcategory,
        ExpLcScRepository $explcsc,
        BankBranchRepository $bankbranch,
        BankAccountRepository $bankaccount, 
        UserRepository $user,
        AccTermLoanRepository $acctermloan,
        AccTermLoanInstallmentRepository $acctermloaninstallment
    ) {
        $this->implc = $implc;
        $this->currency = $currency;
        $this->supplier = $supplier;
        $this->bank = $bank;
        $this->bankbranch = $bankbranch;
        $this->company = $company;
        $this->country = $country;
        $this->itemcategory = $itemcategory;
        $this->explcsc = $explcsc;
        $this->bankaccount = $bankaccount;
        $this->user = $user;
        $this->acctermloan = $acctermloan;
        $this->acctermloaninstallment = $acctermloaninstallment;

        $this->middleware('auth');
        $this->middleware('permission:view.implcs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.implcs', ['only' => ['store']]);
        $this->middleware('permission:edit.implcs',   ['only' => ['update']]);
        $this->middleware('permission:delete.implcs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
        $country=array_prepend(array_pluck($this->country->get(),'code','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $bankbranch=array_prepend(array_pluck(
          $this->bankbranch
          ->leftJoin('banks',function($join){
              $join->on('banks.id','=','bank_branches.bank_id');
          })
          ->get([
              'bank_branches.id',
              'bank_branches.branch_name',
              'banks.name as bank_name',
          ])
          ->map(function($bankbranch){
              $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
              return $bankbranch;
          })
          ,'name','id'),'-Select-','');
        //$bank=array_prepend(array_pluck($this->bank->get(),'name','id'),'-Select-','');
        $lctype = array_prepend(config('bprs.lctype'), '-Select-','');
        $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
        //$incoterm = array_prepend(config('bprs.incoterm'), '-Select-','');
        //$deliveryMode = array_prepend(config('bprs.deliveryMode'), '-Select-','');
        $menu=array_prepend(config('bprs.menu'),'-Select-','');
         
        $implcs=array();
        /*$rows=$this->implc
         ->selectRaw('
        imp_lcs.id,
        imp_lcs.company_id,
        imp_lcs.supplier_id,
        imp_lcs.lc_type_id,
        imp_lcs.issuing_bank_id,
        imp_lcs.last_delivery_date,
        imp_lcs.expiry_date,
        imp_lcs.lc_no_i,
        imp_lcs.lc_no_ii,
        imp_lcs.lc_no_iii,
        imp_lcs.lc_no_iv,
        imp_lcs.pay_term_id,
        imp_lcs.exch_rate,
        sum(purchase_orders.amount) as lc_amount
        ')
        ->leftJoin('imp_lc_pos',function($join){
          $join->on('imp_lc_pos.imp_lc_id','=','imp_lcs.id');
        })
        ->leftJoin('purchase_orders',function($join){
          $join->on('purchase_orders.id','=','imp_lc_pos.purchase_order_id');
        })
        ->groupBy([
        'imp_lcs.id',
        'imp_lcs.company_id',
        'imp_lcs.supplier_id',
        'imp_lcs.lc_type_id',
        'imp_lcs.issuing_bank_id',
        'imp_lcs.last_delivery_date',
        'imp_lcs.expiry_date',
        'imp_lcs.lc_no_i',
        'imp_lcs.lc_no_ii',
        'imp_lcs.lc_no_iii',
        'imp_lcs.lc_no_iv',
        'imp_lcs.pay_term_id',
        'imp_lcs.exch_rate'
        ]) 
        ->orderBy('imp_lcs.id','desc')
        ->get();*/
        $rows = collect(\DB::select("
        select 
        imp_lcs.id,
        imp_lcs.menu_id,
        imp_lcs.lc_date,
        imp_lcs.company_id,
        imp_lcs.supplier_id,
        imp_lcs.lc_to_id,
        imp_lcs.lc_type_id,
        imp_lcs.issuing_bank_branch_id,
        imp_lcs.last_delivery_date,
        imp_lcs.expiry_date,
        imp_lcs.lc_application_date,
        imp_lcs.lc_no_i,
        imp_lcs.lc_no_ii,
        imp_lcs.lc_no_iii,
        imp_lcs.lc_no_iv,
        imp_lcs.pay_term_id,
        imp_lcs.exch_rate,
        imp_lcs.ud_no,
        imp_lcs.ud_date,
        imp_lcs.approved_by,
        commercial_heads.name as commercial_head_name,
        case when 
        imp_lcs.menu_id=1
        then sum(po_fabrics.amount)
        when 
        imp_lcs.menu_id=2
        then sum(po_trims.amount)
        when 
        imp_lcs.menu_id=3
        then sum(po_yarns.amount)
        when 
        imp_lcs.menu_id=4
        then sum(po_knit_services.amount)
        when 
        imp_lcs.menu_id=5
        then sum(po_aop_services.amount)
        when 
        imp_lcs.menu_id=6
        then sum(po_dyeing_services.amount)
        when 
        imp_lcs.menu_id=7
        then sum(po_dye_chems.amount)
        when 
        imp_lcs.menu_id=8
        then sum(po_generals.amount)
        when 
        imp_lcs.menu_id=9
        then sum(po_yarn_dyeings.amount)
        when 
        imp_lcs.menu_id=10
        then sum(po_emb_services.amount)
        when 
        imp_lcs.menu_id=11
        then sum(po_general_services.amount)
        else 0
        end as lc_amount
        from imp_lcs  
        left join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id
        left join po_fabrics on po_fabrics.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=1
        left join po_trims on po_trims.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=2
        left join po_yarns on po_yarns.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=3
        left join po_knit_services on po_knit_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=4
        left join po_aop_services on po_aop_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=5
        left join po_dyeing_services on po_dyeing_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=6
        left join po_dye_chems on po_dye_chems.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=7
        left join po_generals on po_generals.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=8
        left join po_yarn_dyeings on po_yarn_dyeings.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=9
        left join po_emb_services on po_emb_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=10
        left join po_general_services on po_general_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=11
        left join bank_accounts on bank_accounts.id=imp_lcs.bank_account_id
        left join commercial_heads on commercial_heads.id=bank_accounts.account_type_id
        group by 
        imp_lcs.id,
        imp_lcs.menu_id,
        imp_lcs.lc_date,
        imp_lcs.company_id,
        imp_lcs.supplier_id,
        imp_lcs.lc_to_id,
        imp_lcs.lc_type_id,
        imp_lcs.issuing_bank_branch_id,
        imp_lcs.last_delivery_date,
        imp_lcs.expiry_date,
        imp_lcs.lc_application_date,
        imp_lcs.lc_no_i,
        imp_lcs.lc_no_ii,
        imp_lcs.lc_no_iii,
        imp_lcs.lc_no_iv,
        imp_lcs.pay_term_id,
        imp_lcs.exch_rate,
        imp_lcs.ud_no,
        imp_lcs.ud_date,
        imp_lcs.approved_by,
        commercial_heads.name
        order by imp_lcs.id desc
        "
        ))->map(function($rows){
            $today=Carbon::parse(date('Y-m-d'));
            $lc_date = Carbon::parse($rows->lc_date);
            $ud_date = Carbon::parse($rows->ud_date);
            $lc_without_ud=$today->diffInDays($lc_date)+1;
            $lc_with_ud=$ud_date->diffInDays($lc_date)+1;
            if ($rows->ud_date == '') {
                $rows->days_taken_ud=$lc_without_ud;
            }
            if($rows->ud_date){
                $rows->days_taken_ud=$lc_with_ud;
            }
            $rows->approval="--";
            if ($rows->approved_by) {
                $rows->approval="Approved";
            }
            return $rows;
        });
        foreach($rows as $row){
            $implc['id']=$row->id;
            $implc['company']=isset($company[$row->company_id])?$company[$row->company_id]:'--';
            $implc['supplier']=isset($supplier[$row->supplier_id])?$supplier[$row->supplier_id]:'--';
            $implc['lc_to']=isset($supplier[$row->lc_to_id])?$supplier[$row->lc_to_id]:'--';
            $implc['lc_type_id']=isset($lctype[$row->lc_type_id])?$lctype[$row->lc_type_id]:'--';
            $implc['bankbranch']=isset($bankbranch[$row->issuing_bank_branch_id])?$bankbranch[$row->issuing_bank_branch_id]:'--';
            $implc['last_delivery_date']=($row->last_delivery_date !== null)?date("Y-m-d",strtotime($row->last_delivery_date)):'--';
            $implc['expiry_date']=($row->expiry_date !== null)?date("Y-m-d",strtotime($row->expiry_date)):'--';
            $implc['lc_date']=($row->lc_date !== null)?date("Y-m-d",strtotime($row->lc_date)):'--';
            $implc['ud_date']=($row->ud_date !== null)?date("Y-m-d",strtotime($row->ud_date)):'--';
            $implc['lc_application_date']=($row->lc_application_date !== null)?date('Y-m-d',strtotime($row->lc_application_date)):'--';
            $implc['lc_no']=$row->lc_no_i." ".$row->lc_no_ii." ".$row->lc_no_iii." ".$row->lc_no_iv;
            $implc['pay_term_id']=isset($payterm[$row->pay_term_id])?$payterm[$row->pay_term_id]:'--';
            $implc['exch_rate']=$row->exch_rate;
            $implc['lc_amount']=number_format($row->lc_amount,2);
            $implc['ud_no']=($row->ud_no)?$row->ud_no:'--';
            $implc['days_taken_ud']=$row->days_taken_ud;
            $implc['approval']=$row->approval;
            $implc['commercial_head_name']=$row->commercial_head_name;
            $implc['menu_id']=isset($menu[$row->menu_id])?$menu[$row->menu_id]:'--';
            array_push($implcs,$implc);
        }
        echo json_encode($implcs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->where([['status_id','=',1]])->get(),'name','id'),'','');
        $bank=array_prepend(array_pluck($this->bank->get(),'name','id'),'-Select-','');
        $bankbranch=array_prepend(array_pluck(
          $this->bankbranch
          ->leftJoin('banks',function($join){
            $join->on('banks.id','=','bank_branches.bank_id');
          })
          ->get([
              'bank_branches.id',
              'bank_branches.branch_name',
              'banks.name as bank_name',
          ])
          ->map(function($bankbranch){
              $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
              return $bankbranch;
          })
          ,'name','id'),'-Select-','');
        $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
        $yesno = array_prepend(config('bprs.yesno'), '-Select-','');
        $incoterm = array_prepend(config('bprs.incoterm'), '-Select-','');
        $deliveryMode = array_prepend(config('bprs.deliveryMode'), '-Select-','');
        $lctype=array_prepend(array_only(config('bprs.lctype'), [1, 2, 3, 5]),'-Select-','');
        $maturityform = array_prepend(config('bprs.maturityform'), '-Select-','');
        $shippingLines=array_prepend(array_pluck($this->supplier->shippingLines(),'name','id'),'-Select-','');
        $itemcategory=array_prepend(array_pluck($this->itemcategory->get(),'name','id'),'-Select-','');
        $menu=array_prepend(array_only(config('bprs.menu'),[1,2,3,4,5,6,7,8,9,10,11]),'-Select-','');
        $bankaccount=array_prepend(array_pluck(
            $this->bankaccount
            ->leftJoin('commercial_heads',function($join) use ($request) {
                $join->on('commercial_heads.id','=','bank_accounts.account_type_id');
            })
            ->whereIn('commercial_heads.commercialhead_type_id',[12,13,14,18])
            ->get([
                'bank_accounts.id',
                'commercial_heads.name',
                'bank_accounts.account_no',
            ])
            ->map(function($bankaccount){
                $bankaccount->name=$bankaccount->name.' (' .$bankaccount->account_no. ' )';
                return $bankaccount;
            }),
            'name','id'),'-Select-','');
        $insuranceCompany=array_prepend(array_pluck($this->supplier->insuranceCompany(),'name','id'),'-Select-','');
        $inoutcharges = array_prepend(config('bprs.inoutcharges'), '-Select-','');

        return Template::LoadView('Commercial.Import.ImpLc',['company'=>$company,'supplier'=>$supplier,'bank'=>$bank, 'bankbranch'=>$bankbranch,'payterm'=>$payterm,'incoterm'=>$incoterm,'deliveryMode'=>$deliveryMode,'shippingLines'=>$shippingLines,'country'=>$country,'yesno'=>$yesno,'lctype'=>$lctype,'maturityform'=>$maturityform,'currency'=>$currency,'itemcategory'=> $itemcategory,'menu'=>$menu,'bankaccount'=>$bankaccount,'insuranceCompany'=>$insuranceCompany,'inoutcharges'=>$inoutcharges]);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImpLcRequest $request) {

        
        \DB::beginTransaction();
        try
        {
        
        if($request->lc_type_id == 1 || $request->lc_type_id == 2)
        {
            $loan_date=$request->lc_date?$request->lc_date:$request->lc_application_date;
            $due_date=date("Y-m-d",strtotime($loan_date."+ 180 days"));
            $acctermloan=$this->acctermloan->create([
            'loan_ref_no'=>0000,
            'loan_date'=>$loan_date,
            'amount'=>0,
            'grace_period'=>0,
            'rate'=>0,
            'installment_amount'=>0,
            'no_of_installment'=>1,
            'term_loan_for'=>2,
            'bank_account_id'=>$request->bank_account_id,
            'remarks'=>NULL,
            ]);
            $this->acctermloaninstallment->create([
            'acc_term_loan_id'=>$acctermloan->id,
            'amount'=>0,
            'sort_id'=>1,
            'due_date'=>$due_date,
            ]);
            $request->request->add(['acc_term_loan_id' => $acctermloan->id]);
        }
        

        $request->request->add(['lc_no_iv' =>'0000']);
        $request->request->add(['ready_to_approve_id' => 0]);
        $request->request->add(['lc_date' => null]);

        $implc=$this->implc->create($request->except(['id','commercial_head_name']));
        }
        catch(EXCEPTION $e)
        {
        \DB::rollback();
        throw $e;
        }
        \DB::commit();

        if($implc){
            return response()->json(array('success' => true,'id' =>  $implc->id,'message' => 'Save Successfully'),200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $implc = $this->implc->leftJoin('bank_accounts',function($join){
            $join->on('bank_accounts.id','=','imp_lcs.bank_account_id');
        })
        ->leftJoin('commercial_heads',function($join){
            $join->on('bank_accounts.account_type_id','=','commercial_heads.id');
        })
        ->where([['imp_lcs.id','=',$id]])
        ->get([
            'imp_lcs.*',
            'commercial_heads.name as commercial_head_name',
        ])
        ->first();

        $row ['fromData'] = $implc;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        echo json_encode($row); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ImpLcRequest $request, $id) {
        // $implc=$this->implc->update($id,$request->except(['id','menu_id','supplier_id','currency_id','commercial_head_name']));
        // if($implc){
        //     return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
        // }  

        $approvedimplc=$this->implc->find($id);
        \DB::beginTransaction();
        try
        {
            if ($approvedimplc->approved_by==null) {
                $implc=$this->implc->update($id,$request->except(['id','menu_id','supplier_id','currency_id','lc_date','lc_no_iv','commercial_head_name']));
                if ($request->ready_to_approve_id==1) {
                $rows=$this->implc
                ->leftJoin('bank_branches', function($join){
                $join->on('bank_branches.id', '=', 'imp_lcs.issuing_bank_branch_id');
                })
                ->leftJoin('banks', function($join){
                $join->on('banks.id', '=', 'bank_branches.bank_id');
                })
                ->leftJoin('companies', function($join){
                $join->on('companies.id', '=', 'imp_lcs.company_id');
                })
                ->leftJoin('suppliers', function($join){
                $join->on('suppliers.id', '=', 'imp_lcs.supplier_id');
                })
                ->leftJoin('suppliers as lc_to_suppliers', function($join){
                $join->on('lc_to_suppliers.id', '=', 'imp_lcs.lc_to_id');
                })
                ->leftJoin('suppliers as insurance', function($join){
                $join->on('insurance.id', '=', 'imp_lcs.insurance_company_id');
                })
                ->leftJoin('currencies', function($join){
                $join->on('currencies.id', '=', 'imp_lcs.currency_id');
                })
                ->leftJoin('countries', function($join){
                $join->on('countries.id', '=', 'imp_lcs.origin_id');
                })
                ->leftJoin('bank_accounts', function($join){
                $join->on('bank_accounts.id', '=', 'imp_lcs.debit_ac_id');
                })
                ->leftJoin('commercial_heads', function($join){
                $join->on('commercial_heads.id', '=', 'bank_accounts.account_type_id');
                })
                ->where([['imp_lcs.id','=',$id]])
                ->get([
                'imp_lcs.*',
                'banks.id as bank_id',
                'banks.name as bank_name',
                'bank_branches.branch_name',
                'bank_branches.address as bank_address',
                'bank_branches.contact',
                'suppliers.name as supplier_name',
                'suppliers.contact_person as supplier_contact',
                'suppliers.address as supplier_address',
                'suppliers.factory_address',
                'lc_to_suppliers.name as lcto_supplier_name',
                'lc_to_suppliers.contact_person as lcto_supplier_contact',
                'lc_to_suppliers.address as lcto_supplier_address',
                'lc_to_suppliers.factory_address as lcto_factory_address',
                'insurance.name as insurance_company_name',
                'insurance.address as insurance_company_address',
                'suppliers.email as supplier_email',
                'companies.name as company_name',
                'companies.address as company_address',
                'companies.tin_number',
                'companies.irc_no',
                'companies.ban_bank_reg_no',
                'companies.ban_bank_reg_date',
                'currencies.code as currency_code',
                'currencies.name as currency_name',
                'commercial_heads.name as account_type',
                'bank_accounts.account_no',
                'countries.name as origin_name',
                ])
                ->map(function($rows) {
                $rows->lc_no=$rows->lc_no_i." ".$rows->lc_no_ii." ".$rows->lc_no_iii." ".$rows->lc_no_iv;
                $rows->lc_date=$rows->lc_date?date('d-M-Y',strtotime($rows->lc_date)):'';
                $rows->expiry_date=$rows->expiry_date?date('d-M-Y',strtotime($rows->expiry_date)):'';
                return $rows;
                })
                ->first();

                //  dd($rows);die;

                $approveuser=$this->user
                ->join('permission_user',function($join){
                $join->on('users.id','=','permission_user.user_id');
                })
                ->join('permissions',function($join){
                $join->on('permissions.id','=','permission_user.permission_id');
                })
                ->join('employee_h_rs',function($join){
                $join->on('users.id','=','employee_h_rs.user_id');
                })
                ->where([['permissions.id','=',3170]])
                ->get([
                'permissions.id',
                'employee_h_rs.contact'
                ]);

                $approvalArr=[];
                foreach ($approveuser as $data) {
                $approvalArr[3170][]='88'.$data->contact;
                }
                $approvalusercontact=implode(',',$approvalArr[3170]);

                $title ='LC Proposal Approval Request';
                $text = 
                $title."\n".
                'LC No:'.$rows->lc_no."\n".
                'LC Date:'.$rows->lc_date."\n".
                'Supplier:'.$rows->supplier_name;
                $sms=Sms::send_sms($text, $approvalusercontact);
                }
                
            }
            else {
                $implc=$this->implc->update($id,$request->except(['id','menu_id','supplier_id','currency_id','commercial_head_name']));
            }

            if($request->lc_type_id == 1 || $request->lc_type_id == 2)
            {
                $loan_date=$request->lc_date?$request->lc_date:$request->lc_application_date;
                $due_date=date("Y-m-d",strtotime($loan_date."+ 180 days"));

                $acctermloan=$this->acctermloan->update($approvedimplc->acc_term_loan_id,[
                'loan_ref_no'=>$approvedimplc->id,
                'loan_date'=>$loan_date,
                'amount'=>0,
                'grace_period'=>0,
                'rate'=>0,
                'installment_amount'=>0,
                'no_of_installment'=>1,
                'term_loan_for'=>2,
                'bank_account_id'=>$request->bank_account_id,
                'remarks'=>NULL,
                ]);
                $this->acctermloaninstallment->where([['acc_term_loan_id','=',$approvedimplc->acc_term_loan_id]])->update([
                'acc_term_loan_id'=>$approvedimplc->acc_term_loan_id,
                'amount'=>0,
                'sort_id'=>1,
                'due_date'=>$due_date,
                ]);
            }
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();

        if ($implc) {
        return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        /*$btblcloans = collect(\DB::select("
        select 
        imp_lcs.id,
        imp_lcs.lc_date,
        imp_lcs.lc_application_date,
        imp_lcs.bank_account_id,
        case when 
        imp_lcs.menu_id=1
        then sum(po_fabrics.amount) * imp_lcs.exch_rate
        when 
        imp_lcs.menu_id=2
        then sum(po_trims.amount) * imp_lcs.exch_rate
        when 
        imp_lcs.menu_id=3
        then sum(po_yarns.amount) * imp_lcs.exch_rate
        when 
        imp_lcs.menu_id=4
        then sum(po_knit_services.amount) * imp_lcs.exch_rate
        when 
        imp_lcs.menu_id=5
        then sum(po_aop_services.amount) * imp_lcs.exch_rate
        when 
        imp_lcs.menu_id=6
        then sum(po_dyeing_services.amount) * imp_lcs.exch_rate
        when 
        imp_lcs.menu_id=7
        then sum(po_dye_chems.amount) * imp_lcs.exch_rate
        when 
        imp_lcs.menu_id=8
        then sum(po_generals.amount) * imp_lcs.exch_rate
        when 
        imp_lcs.menu_id=9
        then sum(po_yarn_dyeings.amount) * imp_lcs.exch_rate
        when 
        imp_lcs.menu_id=10
        then sum(po_emb_services.amount) * imp_lcs.exch_rate
        when 
        imp_lcs.menu_id=11
        then sum(po_general_services.amount) * imp_lcs.exch_rate
        else 0
        end as lc_amount
        from imp_lcs 
        left join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id
        left join po_fabrics on po_fabrics.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=1
        left join po_trims on po_trims.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=2
        left join po_yarns on po_yarns.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=3
        left join po_knit_services on po_knit_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=4
        left join po_aop_services on po_aop_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=5
        left join po_dyeing_services on po_dyeing_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=6
        left join po_dye_chems on po_dye_chems.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=7
        left join po_generals on po_generals.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=8
        left join po_yarn_dyeings on po_yarn_dyeings.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=9
        left join po_emb_services on po_emb_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=10
        left join po_general_services on po_general_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=11
        left join bank_branches on bank_branches.id=imp_lcs.issuing_bank_branch_id
        left join bank_accounts on bank_accounts.bank_branch_id=bank_branches.id and bank_accounts.company_id=imp_lcs.company_id
        left join commercial_heads on commercial_heads.id=bank_accounts.account_type_id
        where 
         
        imp_lcs.lc_type_id=1
        and  commercial_heads.commercialhead_type_id=28
        and imp_lcs.bank_account_id > 0
        and imp_lcs.acc_term_loan_id is null
        group by 
        imp_lcs.id,
        imp_lcs.menu_id,
        imp_lcs.lc_date,
        imp_lcs.lc_application_date,
        imp_lcs.company_id,
        imp_lcs.lc_type_id,
        imp_lcs.exch_rate,
        imp_lcs.issuing_bank_branch_id,
        bank_branches.bank_id,
        bank_accounts.id,
        imp_lcs.bank_account_id,
        commercial_heads.id,
        commercial_heads.commercialhead_type_id,
        commercial_heads.name
        "));
        \DB::beginTransaction();
        try
        {
        foreach($btblcloans as $btblcloan){
        $loan_date=$btblcloan->lc_date?$btblcloan->lc_date:$btblcloan->lc_application_date;
        $due_date=date("Y-m-d",strtotime($loan_date."+ 180 days"));
        $acctermloan=$this->acctermloan->create([
        'loan_ref_no'=>$btblcloan->id,
        'loan_date'=>$loan_date,
        'amount'=>$btblcloan->lc_amount?$btblcloan->lc_amount:0,
        'grace_period'=>0,
        'rate'=>0,
        'installment_amount'=>$btblcloan->lc_amount?$btblcloan->lc_amount:0,
        'no_of_installment'=>1,
        'term_loan_for'=>2,
        'bank_account_id'=>$btblcloan->bank_account_id,
        'remarks'=>NULL,
        ]);
        //$due_date=strtotime($btblcloan->lc_date."+ 180 days");
        //$due_date=date("Y-m-d",strtotime($btblcloan->lc_date."+ 180 days"));
        $this->acctermloaninstallment->create([
        'acc_term_loan_id'=>$acctermloan->id,
        'amount'=>$btblcloan->lc_amount?$btblcloan->lc_amount:0,
        'sort_id'=>1,
        'due_date'=>$due_date,
        ]);
        $implc=$this->implc->update($btblcloan->id,['acc_term_loan_id'=>$acctermloan->id]);
        }
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
        return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);*/
        $implc=$this->implc->find($id);
        \DB::beginTransaction();
        try
        {
            $this->implc->delete($id);
            $this->acctermloan->delete($implc->acc_term_loan_id);
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
        return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
    }

    public function GetimplcBankAccount(){
        $issuing_bank_branch_id=request('issuing_bank_branch_id',0);
        $company_id=request('company_id',0);
        $lc_type_id=request('lc_type_id',0);
        if ($lc_type_id==1) {
            $rows=$this->bankaccount
            ->join('bank_branches', function($join)  {
                $join->on('bank_branches.id', '=', 'bank_accounts.bank_branch_id');
            })
            ->join('banks',function($join){
                $join->on('bank_branches.bank_id','=','banks.id');
            })
            ->join('commercial_heads',function($join){
            $join->on('bank_accounts.account_type_id','=','commercial_heads.id');
            })
            ->when(request('branch_name'), function ($q) {
                return $q->where('bank_branches.name', 'LIKE', "%".request('branch_name', 0)."%");
            })
            ->when(request('account_no'), function ($q) {
                return $q->where('bank_accounts.account_no', 'LIKE', "%".request('account_no', 0)."%");
            })
            ->orderBy('bank_accounts.id','desc')
            ->where([['bank_branches.id','=',$issuing_bank_branch_id]])
            ->where([['bank_accounts.company_id','=',$company_id]])
            ->whereIn('commercial_heads.commercialhead_type_id',[28])
            ->get([
                'bank_accounts.*',
                'banks.name',
                'bank_branches.branch_name',
                'commercial_heads.name as commercial_head_name'
            ]);
            echo json_encode($rows);
        }
        if ($lc_type_id==2) {
            $rows=$this->bankaccount
            ->join('bank_branches', function($join)  {
                $join->on('bank_branches.id', '=', 'bank_accounts.bank_branch_id');
            })
            ->join('banks',function($join){
                $join->on('bank_branches.bank_id','=','banks.id');
            })
            ->join('commercial_heads',function($join){
            $join->on('bank_accounts.account_type_id','=','commercial_heads.id');
            })
            ->when(request('branch_name'), function ($q) {
                return $q->where('bank_branches.name', 'LIKE', "%".request('branch_name', 0)."%");
            })
            ->when(request('account_no'), function ($q) {
                return $q->where('bank_accounts.account_no', 'LIKE', "%".request('account_no', 0)."%");
            })
            ->orderBy('bank_accounts.id','desc')
            ->where([['bank_branches.id','=',$issuing_bank_branch_id]])
            ->where([['bank_accounts.company_id','=',$company_id]])
            ->whereIn('commercial_heads.commercialhead_type_id',[29])
            ->get([
                'bank_accounts.*',
                'banks.name',
                'bank_branches.branch_name',
                'commercial_heads.name as commercial_head_name'
            ]);
            echo json_encode($rows);
        }
        
    }

    public function getLatter()
    {
        $id=request('id',0);
        $implc = $this->implc
        ->leftJoin('bank_branches', function($join){
            $join->on('bank_branches.id', '=', 'imp_lcs.issuing_bank_branch_id');
        })
        ->leftJoin('banks', function($join){
            $join->on('banks.id', '=', 'bank_branches.bank_id');
        })
        ->leftJoin('companies', function($join){
            $join->on('companies.id', '=', 'imp_lcs.company_id');
        })
        ->leftJoin('currencies', function($join){
            $join->on('currencies.id', '=', 'imp_lcs.currency_id');
        })
        ->leftJoin('bank_accounts', function($join){
            $join->on('bank_accounts.id', '=', 'imp_lcs.debit_ac_id');
        })
        ->leftJoin('commercial_heads', function($join){
            $join->on('commercial_heads.id', '=', 'bank_accounts.account_type_id');
        })
        ->leftJoin('imp_backed_exp_lc_scs',function($join){
            $join->on('imp_lcs.id','=','imp_backed_exp_lc_scs.imp_lc_id');
        })
        ->leftJoin('exp_lc_scs',function($join){
            $join->on('imp_backed_exp_lc_scs.exp_lc_sc_id','=','exp_lc_scs.id');
            })
        ->where([['imp_lcs.id','=',$id]])
        ->get([
            'imp_lcs.*',
            'banks.id as bank_id',
            'banks.name as bank_name',
            'bank_branches.branch_name',
            'bank_branches.address as bank_address',
            'bank_branches.contact',
            'companies.name as company_name',
            'companies.address as company_address',
            'currencies.code as currency_code',
            'commercial_heads.name as account_type',
            'bank_accounts.account_no',
            'exp_lc_scs.file_no',
        ])
        ->first();
        
        $is_local='';
        if($implc->lc_no_iii=='04'){
            $is_local="LOCAL";
        }
        if($implc->lc_no_iii=='06' || $implc->lc_no_iii=='12'){
            $is_local="FOREGIN";
        }
        if($implc->lc_no_iii=='15' || $implc->lc_no_iii=='12'){
            $is_local="Telegraphic Transfer (TT)";
        }
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');

        $pi = collect(\DB::select("
            select 
            imp_lcs.id as imp_lc_id,
            imp_lcs.lc_to_id,
            imp_lc_pos.id as imp_lc_po_id,
            imp_lc_pos.purchase_order_id,
            case when 
            imp_lcs.menu_id=1
            then po_fabrics.supplier_id
            when 
            imp_lcs.menu_id=2
            then po_trims.supplier_id
            when 
            imp_lcs.menu_id=3
            then po_yarns.supplier_id
            when 
            imp_lcs.menu_id=4
            then po_knit_services.supplier_id
            when 
            imp_lcs.menu_id=5
            then po_aop_services.supplier_id
            when 
            imp_lcs.menu_id=6
            then po_dyeing_services.supplier_id
            when 
            imp_lcs.menu_id=7
            then po_dye_chems.supplier_id
            when 
            imp_lcs.menu_id=8
            then po_generals.supplier_id
            when 
            imp_lcs.menu_id=9
            then po_yarn_dyeings.supplier_id
            when 
            imp_lcs.menu_id=10
            then po_emb_services.supplier_id
            when 
            imp_lcs.menu_id=11
            then po_general_services.supplier_id
            else null
            end as supplier_id,

            case when 
            imp_lcs.menu_id=1
            then po_fabrics.pi_no
            when 
            imp_lcs.menu_id=2
            then po_trims.pi_no
            when 
            imp_lcs.menu_id=3
            then po_yarns.pi_no
            when 
            imp_lcs.menu_id=4
            then po_knit_services.pi_no
            when 
            imp_lcs.menu_id=5
            then po_aop_services.pi_no
            when 
            imp_lcs.menu_id=6
            then po_dyeing_services.pi_no
            when 
            imp_lcs.menu_id=7
            then po_dye_chems.pi_no
            when 
            imp_lcs.menu_id=8
            then po_generals.pi_no
            when 
            imp_lcs.menu_id=9
            then po_yarn_dyeings.pi_no
            when 
            imp_lcs.menu_id=10
            then po_emb_services.pi_no
            when 
            imp_lcs.menu_id=11
            then po_general_services.pi_no
            else null
            end as pi_no,

            case when 
            imp_lcs.menu_id=1
            then po_fabrics.pi_date
            when 
            imp_lcs.menu_id=2
            then po_trims.pi_date
            when 
            imp_lcs.menu_id=3
            then po_yarns.pi_date
            when 
            imp_lcs.menu_id=4
            then po_knit_services.pi_date
            when 
            imp_lcs.menu_id=5
            then po_aop_services.pi_date
            when 
            imp_lcs.menu_id=6
            then po_dyeing_services.pi_date
            when 
            imp_lcs.menu_id=7
            then po_dye_chems.pi_date
            when 
            imp_lcs.menu_id=8
            then po_generals.pi_date
            when 
            imp_lcs.menu_id=9
            then po_yarn_dyeings.pi_date
            when 
            imp_lcs.menu_id=10
            then po_emb_services.pi_date
            when 
            imp_lcs.menu_id=11
            then po_general_services.pi_date
            else null
            end as pi_date,
            case when 
            imp_lcs.menu_id=1
            then po_fabrics.id
            when 
            imp_lcs.menu_id=2
            then po_trims.id
            when 
            imp_lcs.menu_id=3
            then po_yarns.id
            when 
            imp_lcs.menu_id=4
            then po_knit_services.id
            when 
            imp_lcs.menu_id=5
            then po_aop_services.id
            when 
            imp_lcs.menu_id=6
            then po_dyeing_services.id
            when 
            imp_lcs.menu_id=7
            then po_dye_chems.id
            when 
            imp_lcs.menu_id=8
            then po_generals.id
            when 
            imp_lcs.menu_id=9
            then po_yarn_dyeings.id
            when 
            imp_lcs.menu_id=10
            then po_emb_services.id
            when 
            imp_lcs.menu_id=11
            then po_general_services.id
            else 0
            end as id,
            case when 
            imp_lcs.menu_id=1
            then po_fabrics.po_no
            when 
            imp_lcs.menu_id=2
            then po_trims.po_no
            when 
            imp_lcs.menu_id=3
            then po_yarns.po_no
            when 
            imp_lcs.menu_id=4
            then po_knit_services.po_no
            when 
            imp_lcs.menu_id=5
            then po_aop_services.po_no
            when 
            imp_lcs.menu_id=6
            then po_dyeing_services.po_no
            when 
            imp_lcs.menu_id=7
            then po_dye_chems.po_no
            when 
            imp_lcs.menu_id=8
            then po_generals.po_no
            when 
            imp_lcs.menu_id=9
            then po_yarn_dyeings.po_no
            when 
            imp_lcs.menu_id=10
            then po_emb_services.po_no
            when 
            imp_lcs.menu_id=11
            then po_general_services.po_no
            else 0
            end as po_no,
            case when 
            imp_lcs.menu_id=1
            then po_fabrics.amount
            when 
            imp_lcs.menu_id=2
            then po_trims.amount
            when 
            imp_lcs.menu_id=3
            then po_yarns.amount
            when 
            imp_lcs.menu_id=4
            then po_knit_services.amount
            when 
            imp_lcs.menu_id=5
            then po_aop_services.amount
            when 
            imp_lcs.menu_id=6
            then po_dyeing_services.amount
            when 
            imp_lcs.menu_id=7
            then po_dye_chems.amount
            when 
            imp_lcs.menu_id=8
            then po_generals.amount
            when 
            imp_lcs.menu_id=9
            then po_yarn_dyeings.amount
            when 
            imp_lcs.menu_id=10
            then po_emb_services.amount
            when 
            imp_lcs.menu_id=11
            then po_general_services.amount
            else 0
            end as amount
            from 
            imp_lcs
            join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id
            left join po_fabrics on po_fabrics.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=1
            left join po_trims on po_trims.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=2
            left join po_yarns on po_yarns.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=3
            left join po_knit_services on po_knit_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=4
            left join po_aop_services on po_aop_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=5
            left join po_dyeing_services on po_dyeing_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=6
            left join po_dye_chems on po_dye_chems.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=7
            left join po_generals on po_generals.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=8
            left join po_yarn_dyeings on po_yarn_dyeings.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=9
            left join po_emb_services on po_emb_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=10
            left join po_general_services on po_general_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=11
            where imp_lcs.id=".$id."
            "
        ))
        ->map(function($pi) use($supplier){
            // $pi->pi_date=date('d-m-Y',strtotime($pi->pi_date));
            $pi->pi_date=($pi->pi_date !== null)?date('d-m-Y',strtotime($pi->pi_date)):null;
            if($pi->lc_to_id){
                $pi->supplier_name=$supplier[$pi->lc_to_id];
            }else{
                $pi->supplier_name=$supplier[$pi->supplier_id];
            }
            return $pi;
        });

        $amount_pi=0;

        $supplier_name='';
        
        foreach($pi as $row){
            $amount_pi+=$row->amount;
            $supplier_name=$row->supplier_name;
        }

        $explcscs =$this->explcsc
        ->selectRaw('
            exp_lc_scs.id ,
            exp_lc_scs.lc_sc_no ,
            exp_lc_scs.sc_or_lc ,
            exp_lc_scs.lc_sc_date ,
            exp_lc_scs.lc_sc_value ,
            exp_lc_scs.file_no ,
            buyers.code as buyer,
            companies.code as company,
            imp_backed_exp_lc_scs.exp_lc_sc_id
        ')
        ->join('imp_backed_exp_lc_scs',function($join){
            $join->on('imp_backed_exp_lc_scs.exp_lc_sc_id','=','exp_lc_scs.id');
        })
        ->join('imp_lcs',function($join){
            $join->on('imp_lcs.id','=','imp_backed_exp_lc_scs.imp_lc_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
        })
        ->join('buyers', function($join)  {
            $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
        })
        ->when(request('lc_sc_no'), function ($q) {
            return $q->where('exp_lc_scs.lc_sc_no', '=', request('lc_sc_no', 0));
        })
        ->where([['imp_lcs.id','=',$id]])
        ->get();
        $lc_sc_amount=0;
        $LC=""; 
        $SC="";
        foreach($explcscs as $explcsc){
            if($explcsc->sc_or_lc==2){
                $LC.=$explcsc->lc_sc_no."/Dt:".date('d-m-Y',strtotime($explcsc->lc_sc_date)).",";
            }
            if($explcsc->sc_or_lc==1){
                $SC.=$explcsc->lc_sc_no."/Dt:".date('d-m-Y',strtotime($explcsc->lc_sc_date)).",";
            }
            $lc_sc_amount+=$explcsc->lc_sc_value;
        }
        $lc_string='';
        if($LC){
            $lc_string.='EXPORT LC NO '.$LC;
        }
        $sc_string='';
        if($SC){
            $sc_string.='SALES CONTRACT NO '.$SC;
        }


        if (!$implc->approved_by) {
            return "<h2 align='center' style='margin:100px auto;'>Get Approval From Authority First</h2>";
        }
        else {
            $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(false);
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->AddPage();
            
            $pdf->SetFont('helvetica', 'N', 8);
            /*$pdf->Text(14, 50,'Date:'.date('d-m-Y',strtotime($implc->lc_application_date)));
            $pdf->Text(14, 60,'To');
            $pdf->Text(14, 64,'THE SENIOR VICE PRESIDENT');
            $pdf->Text(14, 68,strtoupper($implc->bank_name));
            $pdf->Text(14, 72,strtoupper($implc->bank_address));
            $html="<p style='text-align:justify;'>SUB:REQUEST FOR OPENING ".$is_local." ".strtoupper(config('bprs.lctype.'.$implc->lc_type_id))." FOR ".$implc->currency_code." ".number_format($amount_pi,0)." AGAINST ".$lc_string." ".$sc_string." USD ".number_format($lc_sc_amount,0).".</p>";*/
            if($implc->bank_id==62){
            $sub="SUB : REQUEST FOR ISSUING ".strtoupper(config('bprs.lctype.'.$implc->lc_type_id))." FOR ".$implc->currency_code." ".number_format($amount_pi,3)." ".strtoupper($implc->doc_release)." AGAINST ".$lc_string." ".$sc_string." USD ".number_format($lc_sc_amount,3)."";
            }else {
            $sub="SUB : REQUEST FOR ISSUING ".$is_local." ".strtoupper(config('bprs.lctype.'.$implc->lc_type_id))." FOR ".$implc->currency_code." ".number_format($amount_pi,3)." AGAINST ".$lc_string." ".$sc_string." USD ".number_format($lc_sc_amount,3)."";
            }
            //$pdf->SetY(80);
            //$pdf->WriteHtml($html);
            //$pdf->Text(14, 105,'DEAR SIR,');
            //$bodyy="<p style='text-align:justify;'>WE M/S ". strtoupper($implc->company_name).", ".strtoupper($implc->company_address)." AS THE BENEFICIARY OF THE CAPTIONED EXPORT LC/SALES CONTRACT, WOULD LIKE TO REQUEST YOU TO OPEN ".strtoupper(config('bprs.lctype.'.$implc->lc_type_id))." AGAINST THE PROFORMA INVOICE MENTIONED BELOW.</p>";
            $body="WE M/S ". strtoupper($implc->company_name).", ".strtoupper($implc->company_address)." AS THE BENEFICIARY OF THE CAPTIONED EXPORT LC/SALES CONTRACT, WOULD LIKE TO REQUEST YOU TO OPEN ".strtoupper(config('bprs.lctype.'.$implc->lc_type_id))." AGAINST THE PROFORMA INVOICE MENTIONED BELOW.";
            ///$pdf->SetY(115);
            //$pdf->WriteHtml($bodyy);
            //$pdf->SetY(120);
            //$pdf->WriteHtml($pi_table);
            $ttp1="PLEASE DEBIT THE SAME AMOUNT AND OTHER CHARGES FROM OUR ".strtoupper($implc->account_type)." ACCOUNT NO ".strtoupper($implc->account_no)." WHICH IS MAINTAINING WITH YOUR GOOD BANK BY PROVIDING US APPROPRIATE DEBIT VOUCHERS.";

            $ttp2="WE THEREFORE, REQUEST YOU TO ISSUE THE  ".strtoupper(config('bprs.lctype.'.$implc->lc_type_id))." AS PER ABOVE REQUEST UNDER LCAF NO.".$implc->lcaf_no."  REGARDING BANGLADESH BANK GUIDE LINE.";
            //$ttp3= $implc->lcaf_no;

                //LC Type = Margin LC
            $sub_margin_lc="SUB : REQUEST FOR OPENING LC FOR ".$implc->currency_code." ".number_format($amount_pi,3)." AT ".$implc->margin_deposit."% MARGIN FOR ".strtoupper($implc->commodity)." IN FAVOR OF ".strtoupper($supplier_name).".";
            
            $body_margin_lc="WE WOULD LIKE TO REQUEST TO OPEN LC AS MENTIONED IN THE SUBJECT LINE ABOVE AGAINST FOLLOWING PI NUMBERS.";

            $ttp1_margin_lc="YOU ARE ADVISED TO DEBIT OUR ".strtoupper($implc->account_type).", A/C NO: ".$implc->account_no." FOR ".$implc->margin_deposit."% MARGIN AND NECESSARY CHARGES.";

            $ttp2_margin_lc="PAYMENT TO BE MADE IN ".$implc->tenor." DAYS FROM THE DATE OF ".strtoupper(config('bprs.maturityform.'.$implc->maturity_form_id)).".";

            $ttp3_margin_lc="DOCUMENT TO BE RELEASED ".strtoupper($implc->doc_release).".";
                
            $view= \View::make('Defult.Commercial.Import.ImpLcLatterPi',['pi'=>$pi,'implc'=>$implc,'sub'=>$sub,'body'=>$body,'ttp1'=>$ttp1,'ttp2'=>$ttp2,'sub_margin_lc'=>$sub_margin_lc,'body_margin_lc'=>$body_margin_lc,'ttp1_margin_lc'=>$ttp1_margin_lc,'ttp2_margin_lc'=>$ttp2_margin_lc,'ttp3_margin_lc'=>$ttp3_margin_lc]);
            $html_content=$view->render();
            $pdf->SetY(55);
            $pdf->WriteHtml($html_content, true, false,true,false,'');
            $barcodestyle = array(
                'position' => '',
                'align' => 'C',
                'stretch' => false,
                'fitwidth' => true,
                'cellfitalign' => '',
                'border' => false,
                'hpadding' => 'auto',
                'vpadding' => 'auto',
                'fgcolor' => array(0,0,0),
                'bgcolor' => false, //array(255,255,255),
                'text' => true,
                'font' => 'helvetica',
                'fontsize' => 8,
                'stretchtext' => 4
            );
            $pdf->SetX(150);
            $qrc=$implc->bank_name.', VALUE USD '.number_format($amount_pi,3).", ".$supplier_name;
            $pdf->write2DBarcode($qrc, 'QRCODE,Q', 170, 230, 20, 20, $barcodestyle, 'N');
            $pdf->Text(170, 250, 'FAMKAM ERP');
            $pdf->Text(170, 254, 'LC ID :'.$implc->id);
            $pdf->Text(170, 258, 'File No :'.$implc->file_no);

            $pdf->SetFont('helvetica', 'N', 10);
            $pdf->SetFont('helvetica', '', 8);
            $filename = storage_path() . '/ImpLcLatterPiPdf.pdf';
            $pdf->output($filename);
        }
        

    }

    public function getLatterw()
    {
         
      $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetPrintHeader(false);
      $pdf->SetPrintFooter(false);
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->AddPage();

      $pdf->SetFont('helvetica', 'N', 8);


      $view= \View::make('Defult.Commercial.Import.ImpLcLatterPiQr',[]);
      $html_content=$view->render();
      $pdf->SetY(55);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $barcodestyle = array(
      'position' => '',
      'align' => 'C',
      'stretch' => false,
      'fitwidth' => true,
      'cellfitalign' => '',
      'border' => false,
      'hpadding' => 'auto',
      'vpadding' => 'auto',
      'fgcolor' => array(0,0,0),
      'bgcolor' => false,
      'text' => true,
      'font' => 'helvetica',
      'fontsize' => 8,
      'stretchtext' => 4
      );
      $pdf->SetX(150);
      //$qrc="FM KABIR MOHIUDDIN\nMANAGING DIRECTOR\nLithe Group\nA.H Tower, 12th & 13th Floor,\nPlot# 56, Road# 2, Sector# 3,\nAzampur, Uttara C/A\nDhaka-1230\nBangladesh\n+8801711563231\n+880258950459\nmd@famkam.com\nmd@lithegroup.com\nwww.lithegroup.com";
       // here our data
      $name         = 'FM KABIR MOHIUDDIN';
      $sortName     = 'MOHIUDDIN;FM;KABIR';
      $phone        = '+880241090456';
      $phonePrivate = '';
      $phoneCell    = '+8801711563231';
      $orgName      = 'Lithe Group';

      $email        = 'md@famkam.com';
      $emailGroup   = 'md@lithegroup.com';
      $urlGroup     = 'http://www.lithegroup.com';
      $url          = 'http://www.famkam.com';
      $title        = 'Chairman & Managing Director';

      // if not used - leave blank!
      $addressLabel     = 'Corporate Office';
      $addressPobox     = '';
      $addressExt       = 'A.H Tower,';
      $addressStreet    = '12th - 13th Floor,';
      $addressTown      = 'Plot# 56, Road# 2, Sector# 3,';
      $addressRegion    = 'Azampur,Uttara C/A';
      $addressPostCode  = 'Dhaka-1230';
      $addressCountry   = 'Bangladesh';

      $addressLabelF     = 'Factory';
      $addressPoboxF     = '';
      $addressExtF       = 'Shirir Chala,';
      $addressStreetF    = 'Bagher Bazar,';
      $addressTownF      = 'Gazipur Shador';
      $addressRegionF    = 'Gazipur,';
      $addressPostCodeF  = '';
      $addressCountryF   = 'Bangladesh';



      // we building raw data
      $codeContents  = 'BEGIN:VCARD'."\n";
      $codeContents .= 'VERSION:3.0'."\n";
      $codeContents .= 'N:'.$sortName."\n";
      $codeContents .= 'FN:'.$name."\n";
      $codeContents .= 'TITLE:'.$title."\n";
      $codeContents .= 'ORG:'.$orgName."\n";

      $codeContents .= 'TEL;WORK;VOICE:'.$phone."\n";
      $codeContents .= 'TEL;TYPE=cell:'.$phoneCell."\n";
      $codeContents .= 'TEL;HOME;VOICE:'.$phonePrivate."\n";
     

      $codeContents .= 'ADR;TYPE=work:'
      .$addressLabel.';'
      .$addressPobox.';'
      .$addressExt.';'
      .$addressStreet.';'
      .$addressTown.';'
      .$addressPostCode.';'
      .$addressCountry.';'
      ."\n";

     

      $codeContents .= 'EMAIL:'.$email."\n";
      $codeContents .= 'EMAIL:'.$emailGroup."\n";
      //$codeContents .= 'URL:'.$urlGroup."\n";
      $codeContents .= 'URL:'.$url."\n";

      $codeContents .= 'END:VCARD';
      $pdf->write2DBarcode($codeContents, 'QRCODE,H', 85, 115, 30, 30, $barcodestyle, 'N');
      

      $pdf->SetFont('helvetica', 'N', 10);
      $pdf->SetFont('helvetica', '', 8);
      $filename = storage_path() . '/ImpLcLatterPiPdfQr.pdf';
      $pdf->output($filename);

    }

    public function getCreditLatter()
    {
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'','');
        $payterm = array_prepend(config('bprs.payterm'), '','');
        $maturityform = array_prepend(config('bprs.maturityform'), '','');
        $incoterm = array_prepend(config('bprs.incoterm'), '','');
        $deliveryMode = array_prepend(config('bprs.deliveryMode'), '','');
        $inoutcharges = array_prepend(config('bprs.inoutcharges'), '','');
        $poType= [
            1=>"Fabric",
            2=>"Accessories",
            3=>"Yarn",
            4=>"Kniting Charge",
            5=>"AOP Charge",
            6=>"Dyeing Charge",
            7=>"Dyes & Chemical",
            8=>"General Item" ,
            9=>"Yarn Dyeing",
            10=>"Embellishment Charge",
            11=>"General Service"
        ];
        $bankaccount=array_prepend(array_pluck(
            $this->bankaccount
            ->leftJoin('commercial_heads',function($join){
                $join->on('commercial_heads.id','=','bank_accounts.account_type_id');
            })
            ->get([
                'bank_accounts.id',
                'commercial_heads.name',
                'bank_accounts.account_no',
            ])
            ->map(function($bankaccount){
                $bankaccount->name=$bankaccount->name.' (' .$bankaccount->account_no. ' )';
                return $bankaccount;
            }),
            'name','id'),'','');

        $id=request('id',0);
        $implc = $this->implc
        ->leftJoin('bank_branches', function($join){
            $join->on('bank_branches.id', '=', 'imp_lcs.issuing_bank_branch_id');
        })
        ->leftJoin('banks', function($join){
            $join->on('banks.id', '=', 'bank_branches.bank_id');
        })
        ->leftJoin('companies', function($join){
            $join->on('companies.id', '=', 'imp_lcs.company_id');
        })
        ->leftJoin('suppliers', function($join){
            $join->on('suppliers.id', '=', 'imp_lcs.supplier_id');
        })
        ->leftJoin('suppliers as lc_to_suppliers', function($join){
            $join->on('lc_to_suppliers.id', '=', 'imp_lcs.lc_to_id');
        })
        ->leftJoin('suppliers as insurance', function($join){
            $join->on('insurance.id', '=', 'imp_lcs.insurance_company_id');
        })
        ->leftJoin('currencies', function($join){
            $join->on('currencies.id', '=', 'imp_lcs.currency_id');
        })
        ->leftJoin('countries', function($join){
            $join->on('countries.id', '=', 'imp_lcs.origin_id');
        })
        ->leftJoin('bank_accounts', function($join){
            $join->on('bank_accounts.id', '=', 'imp_lcs.debit_ac_id');
        })
        ->leftJoin('commercial_heads', function($join){
            $join->on('commercial_heads.id', '=', 'bank_accounts.account_type_id');
        })
        ->where([['imp_lcs.id','=',$id]])
        ->get([
            'imp_lcs.*',
            'banks.id as bank_id',
            'banks.name as bank_name',
            'bank_branches.branch_name',
            'bank_branches.address as bank_address',
            'bank_branches.contact',
            'suppliers.name as supplier_name',
            'suppliers.contact_person as supplier_contact',
            'suppliers.address as supplier_address',
            'suppliers.factory_address',
            'lc_to_suppliers.name as lcto_supplier_name',
            'lc_to_suppliers.contact_person as lcto_supplier_contact',
            'lc_to_suppliers.address as lcto_supplier_address',
            'lc_to_suppliers.factory_address as lcto_factory_address',
            'insurance.name as insurance_company_name',
            'insurance.address as insurance_company_address',
            'suppliers.email as supplier_email',
            'companies.name as company_name',
            'companies.address as company_address',
            'companies.tin_number',
            'companies.irc_no',
            'companies.ban_bank_reg_no',
            'companies.ban_bank_reg_date',
            'currencies.code as currency_code',
            'currencies.name as currency_name',
            'commercial_heads.name as account_type',
            'bank_accounts.account_no',
            'countries.name as origin_name',
        ])
        ->map(function($implc) use($payterm,$deliveryMode,$maturityform,$poType,$incoterm,$inoutcharges,$bankaccount){
            $implc->lc_no=$implc->lc_no_i." ".$implc->lc_no_ii." ".$implc->lc_no_iii/* ." ".$implc->lc_no_iv */;
            $implc->lc_date=$implc->lc_date?date('d-M-Y',strtotime($implc->lc_date)):'';
            $implc->ban_bank_reg_date=$implc->ban_bank_reg_date?date('d-M-Y',strtotime($implc->ban_bank_reg_date)):'';
            $implc->last_ship_date=$implc->last_delivery_date?date('d-M-Y',strtotime($implc->last_delivery_date)):'';
            $implc->expiry_date=$implc->expiry_date?date('d-M-Y',strtotime($implc->expiry_date)):'';
            $implc->cover_note_date=$implc->cover_note_date?date('d-M-Y',strtotime($implc->cover_note_date)):'';
            
            $implc->pay_term=$payterm[$implc->pay_term_id];
            if($implc->pay_term_id==1){
                $implc->credit_availed="Deferred Payment";
            }elseif ($implc->pay_term_id==2) {
                $implc->credit_availed="Sight Payment";
            }else {
                $implc->credit_availed=$payterm[$implc->pay_term_id];
            }

            if($implc->partial_shipment_id==1){
                $implc->partial_shipment="Allowed";
            }else {
                $implc->partial_shipment="Not Allowed";
            }

            if($implc->transhipment_id==1){
                $implc->transhipment="Allowed";
            }else {
                $implc->transhipment="Not Allowed";
            }
            
            if ($implc->add_conf_ref_id==1) {
                $implc->add_conf_ref="Not requested";
            }else {
                $implc->add_conf_ref="Requested";
            } 
            
            $implc->add_conf_charge=$inoutcharges[$implc->add_conf_charge_id];
            $implc->maturity_form=$maturityform[$implc->maturity_form_id];
            $implc->delivery_mode=$deliveryMode[$implc->delivery_mode_id];
            $implc->incoterm_id=$incoterm[$implc->incoterm_id];
            $implc->inside_charge_id=$inoutcharges[$implc->inside_charge_id];
            $implc->outside_charge_id=$inoutcharges[$implc->outside_charge_id];
            $implc->debit_ac_id=$bankaccount[$implc->debit_ac_id];
            $implc->po_type=$poType[$implc->menu_id];
            if ($implc->pay_term_id==2) {
                $implc->tenor_days="sight";
            }else {
                $implc->tenor_days=$implc->tenor."  Days";
            }
            return $implc;
        })
        ->first();
        

        $pi = collect(\DB::select("
            select 
            imp_lcs.id as imp_lc_id,
            imp_lcs.lc_to_id,
            imp_lc_pos.id as imp_lc_po_id,
            imp_lc_pos.purchase_order_id,
            case when 
            imp_lcs.menu_id=1
            then po_fabrics.supplier_id
            when 
            imp_lcs.menu_id=2
            then po_trims.supplier_id
            when 
            imp_lcs.menu_id=3
            then po_yarns.supplier_id
            when 
            imp_lcs.menu_id=4
            then po_knit_services.supplier_id
            when 
            imp_lcs.menu_id=5
            then po_aop_services.supplier_id
            when 
            imp_lcs.menu_id=6
            then po_dyeing_services.supplier_id
            when 
            imp_lcs.menu_id=7
            then po_dye_chems.supplier_id
            when 
            imp_lcs.menu_id=8
            then po_generals.supplier_id
            when 
            imp_lcs.menu_id=9
            then po_yarn_dyeings.supplier_id
            when 
            imp_lcs.menu_id=10
            then po_emb_services.supplier_id
            when 
            imp_lcs.menu_id=11
            then po_general_services.supplier_id
            else null
            end as supplier_id,

            case when 
            imp_lcs.menu_id=1
            then po_fabrics.pi_no
            when 
            imp_lcs.menu_id=2
            then po_trims.pi_no
            when 
            imp_lcs.menu_id=3
            then po_yarns.pi_no
            when 
            imp_lcs.menu_id=4
            then po_knit_services.pi_no
            when 
            imp_lcs.menu_id=5
            then po_aop_services.pi_no
            when 
            imp_lcs.menu_id=6
            then po_dyeing_services.pi_no
            when 
            imp_lcs.menu_id=7
            then po_dye_chems.pi_no
            when 
            imp_lcs.menu_id=8
            then po_generals.pi_no
            when 
            imp_lcs.menu_id=9
            then po_yarn_dyeings.pi_no
            when 
            imp_lcs.menu_id=10
            then po_emb_services.pi_no
            when 
            imp_lcs.menu_id=11
            then po_general_services.pi_no
            else null
            end as pi_no,

            case when 
            imp_lcs.menu_id=1
            then po_fabrics.pi_date
            when 
            imp_lcs.menu_id=2
            then po_trims.pi_date
            when 
            imp_lcs.menu_id=3
            then po_yarns.pi_date
            when 
            imp_lcs.menu_id=4
            then po_knit_services.pi_date
            when 
            imp_lcs.menu_id=5
            then po_aop_services.pi_date
            when 
            imp_lcs.menu_id=6
            then po_dyeing_services.pi_date
            when 
            imp_lcs.menu_id=7
            then po_dye_chems.pi_date
            when 
            imp_lcs.menu_id=8
            then po_generals.pi_date
            when 
            imp_lcs.menu_id=9
            then po_yarn_dyeings.pi_date
            when 
            imp_lcs.menu_id=10
            then po_emb_services.pi_date
            when 
            imp_lcs.menu_id=11
            then po_general_services.pi_date
            else null
            end as pi_date,
            case when 
            imp_lcs.menu_id=1
            then po_fabrics.id
            when 
            imp_lcs.menu_id=2
            then po_trims.id
            when 
            imp_lcs.menu_id=3
            then po_yarns.id
            when 
            imp_lcs.menu_id=4
            then po_knit_services.id
            when 
            imp_lcs.menu_id=5
            then po_aop_services.id
            when 
            imp_lcs.menu_id=6
            then po_dyeing_services.id
            when 
            imp_lcs.menu_id=7
            then po_dye_chems.id
            when 
            imp_lcs.menu_id=8
            then po_generals.id
            when 
            imp_lcs.menu_id=9
            then po_yarn_dyeings.id
            when 
            imp_lcs.menu_id=10
            then po_emb_services.id
            when 
            imp_lcs.menu_id=11
            then po_general_services.id
            else 0
            end as id,
            case when 
            imp_lcs.menu_id=1
            then po_fabrics.po_no
            when 
            imp_lcs.menu_id=2
            then po_trims.po_no
            when 
            imp_lcs.menu_id=3
            then po_yarns.po_no
            when 
            imp_lcs.menu_id=4
            then po_knit_services.po_no
            when 
            imp_lcs.menu_id=5
            then po_aop_services.po_no
            when 
            imp_lcs.menu_id=6
            then po_dyeing_services.po_no
            when 
            imp_lcs.menu_id=7
            then po_dye_chems.po_no
            when 
            imp_lcs.menu_id=8
            then po_generals.po_no
            when 
            imp_lcs.menu_id=9
            then po_yarn_dyeings.po_no
            when 
            imp_lcs.menu_id=10
            then po_emb_services.po_no
            when 
            imp_lcs.menu_id=11
            then po_general_services.po_no
            else 0
            end as po_no,
            case when 
            imp_lcs.menu_id=1
            then po_fabrics.amount
            when 
            imp_lcs.menu_id=2
            then po_trims.amount
            when 
            imp_lcs.menu_id=3
            then po_yarns.amount
            when 
            imp_lcs.menu_id=4
            then po_knit_services.amount
            when 
            imp_lcs.menu_id=5
            then po_aop_services.amount
            when 
            imp_lcs.menu_id=6
            then po_dyeing_services.amount
            when 
            imp_lcs.menu_id=7
            then po_dye_chems.amount
            when 
            imp_lcs.menu_id=8
            then po_generals.amount
            when 
            imp_lcs.menu_id=9
            then po_yarn_dyeings.amount
            when 
            imp_lcs.menu_id=10
            then po_emb_services.amount
            when 
            imp_lcs.menu_id=11
            then po_general_services.amount
            else 0
            end as amount
            from 
            imp_lcs
            join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id
            left join po_fabrics on po_fabrics.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=1
            left join po_trims on po_trims.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=2
            left join po_yarns on po_yarns.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=3
            left join po_knit_services on po_knit_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=4
            left join po_aop_services on po_aop_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=5
            left join po_dyeing_services on po_dyeing_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=6
            left join po_dye_chems on po_dye_chems.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=7
            left join po_generals on po_generals.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=8
            left join po_yarn_dyeings on po_yarn_dyeings.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=9
            left join po_emb_services on po_emb_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=10
            left join po_general_services on po_general_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=11
            where imp_lcs.id=".$id."
            "
        ))
        ->map(function($pi) use($supplier){
            // $pi->pi_date=date('d-m-Y',strtotime($pi->pi_date));
            $pi->pi_date=($pi->pi_date !== null)?date('d-M-Y',strtotime($pi->pi_date)):null;
            if($pi->lc_to_id){
                $pi->supplier_name=$supplier[$pi->lc_to_id];
            }else{
                $pi->supplier_name=$supplier[$pi->supplier_id];
            }
            return $pi;
        });

        
        $amount_pi=0;

        $supplier_name='';
        $piNoArr=[];
        
        foreach($pi as $row){
            $amount_pi+=$row->amount;
            $supplier_name=$supplier[$row->supplier_id];
            $piNoArr[$row->pi_date]=$row->pi_no.' Date:'.$row->pi_date;
        }

        $explcscs =$this->explcsc
        ->selectRaw('
            exp_lc_scs.id ,
            exp_lc_scs.lc_sc_no ,
            exp_lc_scs.sc_or_lc ,
            exp_lc_scs.lc_sc_date ,
            exp_lc_scs.lc_sc_value ,
            exp_lc_scs.file_no ,
            exp_lc_scs.last_delivery_date ,
            buyers.code as buyer,
            companies.code as company,
            imp_backed_exp_lc_scs.exp_lc_sc_id
        ')
        ->join('imp_backed_exp_lc_scs',function($join){
            $join->on('imp_backed_exp_lc_scs.exp_lc_sc_id','=','exp_lc_scs.id');
        })
        ->join('imp_lcs',function($join){
            $join->on('imp_lcs.id','=','imp_backed_exp_lc_scs.imp_lc_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
        })
        ->join('buyers', function($join)  {
            $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
        })
        ->when(request('lc_sc_no'), function ($q) {
            return $q->where('exp_lc_scs.lc_sc_no', '=', request('lc_sc_no', 0));
        })
        ->where([['imp_lcs.id','=',$id]])
        ->get();

        $lc_sc_amount=0;
        $LC=""; 
        $SC="";

        foreach($explcscs as $explcsc){
            if($explcsc->sc_or_lc==2){
                $LC.=$explcsc->lc_sc_no."    "."Date:".date('d-M-Y',strtotime($explcsc->lc_sc_date)).",";
            }
            if($explcsc->sc_or_lc==1){
                $SC.=$explcsc->lc_sc_no."    "."Date:".date('d-M-Y',strtotime($explcsc->lc_sc_date)).",";
            }
            $lc_sc_amount+=$explcsc->lc_sc_value;
        }
        $lc_string='';
        if($LC){
            $lc_string.='Export LC No: '.$LC;
        }
        $sc_string='';
        if($SC){
            $sc_string.='Sales Contract No: '.$SC;
        }

        $amount=$amount_pi;
        $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$implc->currency_name,'cents only');
        $implc->inword=$inword;

        $implc->exp_lc_sc=$lc_string." ".$sc_string;
        $implc->lc_sc_amount=$lc_sc_amount;
        //dd(implode(';',$piNoArr));die;
        $implc->pi_no=implode('; ',$piNoArr);
        
        
        if (!$implc->approved_by) {
            return "<h2 align='center' style='margin:100px auto;'>Get Approval From Authority First</h2>";
        }
        else {
            if ( $implc->bank_id==1) {
                $pdf = new \TCPDF('P', PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
                $pdf->SetPrintHeader(false);
                $pdf->SetPrintFooter(false);
                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                $pdf->SetAutoPageBreak(TRUE, 10);
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->AddPage();
                $pdf->SetY(5);
                $image_file ='images/logo/islami_bank_logo.png';
                $pdf->Image($image_file, 10, 5, 20, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
                $pdf->SetY(15);
                $pdf->SetFont('helvetica', 'N', 9);
                $view= \View::make('Defult.Commercial.Import.ImpLcBankLetterIBBL',['implc'=>$implc,'amount_pi'=>$amount_pi]);
                $html_content=$view->render();
                $pdf->WriteHtml($html_content, true, false,true,false,'');
                $filename = storage_path() . '/ImpLcBankLetterIBBL.pdf';
                $pdf->output($filename,'I');
                exit();
            }
            if ( $implc->bank_id==62) {
                $pdf = new \TCPDF('P', PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
                $pdf->SetPrintHeader(false);
                $pdf->SetPrintFooter(false);
                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                $pdf->SetMargins(10, 10, 10);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                $pdf->SetAutoPageBreak(TRUE, 10);
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->AddPage();
            // $pdf->SetY(5);
            // $image_file ='images/logo/islami_bank_logo.png';
                //$pdf->Image($image_file, 10, 5, 20, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
                $pdf->SetY(20);
                $pdf->SetFont('helvetica', 'N', 9);
                $view= \View::make('Defult.Commercial.Import.ImpLcBankLetterPBL',['implc'=>$implc,'amount_pi'=>$amount_pi]);
                $html_content=$view->render();
                $pdf->WriteHtml($html_content, true, false,true,false,'');
                $filename = storage_path() . '/ImpLcBankLetterPBL.pdf';
                $pdf->output($filename,'I');
                exit();
            }
        }
    
    }
}
