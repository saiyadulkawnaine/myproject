<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\BankRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\BankAccountRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnItemRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Library\Sms;
use Illuminate\Support\Carbon;
use App\Library\Numbertowords;

class ImpLcApprovalController extends Controller
{
    private $implc;
    private $user;
    private $buyer;
    private $company;
    private $currency;
    private $country;
    private $supplier;
    private $bank;
    private $itemcategory;
    private $explcsc;
    private $bankbranch;
    private $bankaccount;
    private $implcpo;
    private $budgetfabric;

  public function __construct(
    ImpLcRepository $implc,
    ImpLcPoRepository $implcpo,
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
    BuyerRepository $buyer,
    SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,
    ItemAccountRepository $itemaccount,
    BudgetFabricRepository $budgetfabric,
    InvYarnItemRepository $invyarnitem,
    EmbelishmentTypeRepository $embelishmenttype

  ) {
      $this->implc = $implc;
      $this->implcpo = $implcpo;
      $this->user = $user;
      $this->company = $company;
      $this->buyer = $buyer;
      $this->currency=$currency;
      $this->country=$country;
      $this->supplier=$supplier;
      $this->bank=$bank;
      $this->itemcategory=$itemcategory;
      $this->explcsc=$explcsc;
      $this->bankbranch=$bankbranch;
      $this->bankaccount=$bankaccount;
      $this->salesordergmtcolorsize = $salesordergmtcolorsize;
      $this->itemaccount = $itemaccount;
      $this->invyarnitem = $invyarnitem;
      $this->budgetfabric = $budgetfabric;
      $this->embelishmenttype = $embelishmenttype;

      $this->middleware('auth');
      $this->middleware('permission:view.implcapproval',   ['only' => [ 'index','reportData','reportDataApp']]);
      $this->middleware('permission:approve.implcs',   ['only' => ['approved','unapproved']]);
      //$this->middleware('permission:approve.implcs',   ['only' => ['approved', 'index','reportData','reportDataApp','unapproved']]);
  }
    
  public function index() {
    $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
    $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
    $supplier=array_prepend(array_pluck($this->supplier->where([['status_id','=',1]])->get(),'name','id'),'-Select-','');
    $menu=array_prepend(array_only(config('bprs.menu'),[1,2,3,4,5,6,7,8,9,10,11]),'-Select-','');
    return Template::loadView('Approval.ImpLcApproval',['company'=>$company,'buyer'=>$buyer,'supplier'=>$supplier,'menu'=>$menu]);
  }

  public function reportData() {
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
      }),'name','id'),'-Select-','');
    $lctype = array_prepend(config('bprs.lctype'), '-Select-','');
    $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
    $menu=array_prepend(config('bprs.menu'),'-Select-','');

    $company_id=request('company_id',0);
    $supplier_id=request('supplier_id',0);
    $menu_id=request('menu_id',0);
    $lc_to_id=request('lc_to_id',0);

    $companyId=null;
    $supplierId=null;
    $menuId=null;
    $lctoId=null;
    if ($company_id) {
        $companyId="and imp_lcs.company_id=$company_id";
    }
    if ($supplier_id) {
        $supplierId="and imp_lcs.supplier_id=$supplier_id";
    }
    if ($menu_id) {
        $menuId="and imp_lcs.menu_id=$menu_id";
    }
    if ($lc_to_id) {
        $lctoId="and imp_lcs.lc_to_id=$lc_to_id";
    }

    $explcfile = $this->implc
			->join('imp_backed_exp_lc_scs',function($join){
				$join->on('imp_lcs.id','=','imp_backed_exp_lc_scs.imp_lc_id');
			})
			->join('exp_lc_scs',function($join){
				$join->on('exp_lc_scs.id','=','imp_backed_exp_lc_scs.exp_lc_sc_id');
			})
			->get([
				'imp_lcs.id',
				'exp_lc_scs.file_no',
			]);

		$explcfileArr=[];
		foreach($explcfile as $data){
			$explcfileArr[$data->id]=$data->file_no;
		}

    $implcs=array();
    $rows = collect(\DB::select("
    select
      imp_lcs.id,
      imp_lcs.menu_id,
      imp_lcs.lc_date,
      imp_lcs.company_id,
      imp_lcs.supplier_id,
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
      exp_lc_scs.file_no,
      implcvalue.lc_amount,
      lcscvalue.lc_sc_value,
      LcScRep.replaced_amount,
      BTBOpened.btb_opened_amount,
      BTB.btb_opening_amount
      from imp_lcs  
      left join imp_backed_exp_lc_scs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id 
      left join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
      left join (
        SELECT 
        imp_lcs.id as imp_lc_id,
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
        FROM imp_lcs  
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
        group by 
        imp_lcs.id,
        imp_lcs.menu_id 
      )implcvalue on implcvalue.imp_lc_id=imp_lcs.id
      left join (
          select 
          exp_lc_scs.file_no,
          sum(exp_lc_scs.lc_sc_value) as lc_sc_value 
          from exp_lc_scs 
          group by
          exp_lc_scs.file_no
      ) lcscvalue on lcscvalue.file_no=exp_lc_scs.file_no
      left join(
          SELECT 
          exp_lc_scs.file_no,
          sum(exp_rep_lc_scs.replaced_amount) as replaced_amount 
          FROM exp_rep_lc_scs 
          right join exp_lc_scs on exp_lc_scs.id = exp_rep_lc_scs.replaced_lc_sc_id   
          group by exp_lc_scs.file_no
      ) LcScRep on LcScRep.file_no=exp_lc_scs.file_no
      left join (
          SELECT 
          exp_lc_scs.file_no,
          sum(imp_backed_exp_lc_scs.amount) as btb_opened_amount 
          FROM imp_backed_exp_lc_scs 
          right join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
          right join imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id   
          where  imp_lcs.lc_date is not null  
          group by exp_lc_scs.file_no
      ) BTBOpened on BTBOpened.file_no=exp_lc_scs.file_no
      left join (
          SELECT 
          exp_lc_scs.file_no,
          sum(imp_backed_exp_lc_scs.amount) as btb_opening_amount 
          FROM imp_backed_exp_lc_scs 
          right join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
          right join imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id   
          where  imp_lcs.lc_date is null 
          group by exp_lc_scs.file_no
      ) BTB on BTB.file_no=exp_lc_scs.file_no
      where imp_lcs.ready_to_approve_id=1
      and imp_lcs.approved_by is null
      $companyId $supplierId $menuId $lctoId
      group by 
      imp_lcs.id,
      imp_lcs.menu_id,
      imp_lcs.lc_date,
      imp_lcs.company_id,
      imp_lcs.supplier_id,
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
      exp_lc_scs.file_no,
      implcvalue.lc_amount,
      lcscvalue.lc_sc_value,
      BTBOpened.btb_opened_amount,
      BTB.btb_opening_amount,
      LcScRep.replaced_amount
      order by imp_lcs.id desc
    "
    ));
    foreach($rows as $row){
      $implc['id']=$row->id;
      $implc['company']=isset($company[$row->company_id])?$company[$row->company_id]:'--';
      $implc['supplier']=isset($supplier[$row->supplier_id])?$supplier[$row->supplier_id]:'--';
     // $implc['lc_type_id']=[$row->lc_type_id];
      $implc['lc_type_id']=isset($lctype[$row->lc_type_id])?$lctype[$row->lc_type_id]:'--';
      $implc['bankbranch']=isset($bankbranch[$row->issuing_bank_branch_id])?$bankbranch[$row->issuing_bank_branch_id]:'--';
      $implc['last_delivery_date']=($row->last_delivery_date !== null)?date("Y-m-d",strtotime($row->last_delivery_date)):'--';
      $implc['expiry_date']=($row->expiry_date !== null)?date("Y-m-d",strtotime($row->expiry_date)):'--';
      $implc['lc_date']=($row->lc_date !== null)?date("Y-m-d",strtotime($row->lc_date)):'--';
      $implc['lc_application_date']=($row->lc_application_date !== null)?date('Y-m-d',strtotime($row->lc_application_date)):'--';
      $implc['lc_no']=$row->lc_no_i." ".$row->lc_no_ii." ".$row->lc_no_iii." ".$row->lc_no_iv;
      $implc['pay_term_id']=isset($payterm[$row->pay_term_id])?$payterm[$row->pay_term_id]:'--';
      $implc['lc_amount']=$row->lc_amount;
      $lc_sc_value=$row->lc_sc_value-$row->replaced_amount;
      $limit_btb_open=($lc_sc_value*70)/100;
      $yet_btb_open=$limit_btb_open-$row->btb_opened_amount;
      $limit_btb_booked=$row->btb_opening_amount-$row->lc_amount;
      $implc['fund_available']=$yet_btb_open-$limit_btb_booked;
      $implc['menu_id']=isset($menu[$row->menu_id])?$menu[$row->menu_id]:'--';
      $implc['file_no']=isset($explcfileArr[$row->id])?$explcfileArr[$row->id]:'--';
      array_push($implcs,$implc);
    }
    echo json_encode($implcs);
  }

  public function approved (Request $request)
  {
        $id=request('id',0);
        $master=$this->implc->find($id);
        $user = \Auth::user();
        $approved_at=date('Y-m-d h:i:s');
        $master->approved_by=$user->id;
        $master->approved_at=$approved_at;
        $master->unapproved_by=NULL;
        $master->unapproved_at=NULL;
        $master->timestamps=false;
        $implc=$master->save();
        if($implc){
        return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
      }
  }

  public function reportDataApp() {
      $company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
      $country=array_prepend(array_pluck($this->country->get(),'code','id'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'','');
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
      $lctype = array_prepend(config('bprs.lctype'), '-Select-','');
      $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
      $menu=array_prepend(config('bprs.menu'),'-Select-','');

      
      $company_id=request('company_id',0);
      $supplier_id=request('supplier_id',0);
      $menu_id=request('menu_id',0);
      $lc_to_id=request('lc_to_id',0);

      $companyId=null;
      $supplierId=null;
      $menuId=null;
      $lctoId=null;
      if ($company_id) {
          $companyId="and imp_lcs.company_id=$company_id";
      }
      if ($supplier_id) {
          $supplierId="and imp_lcs.supplier_id=$supplier_id";
      }
      if ($menu_id) {
          $menuId="and imp_lcs.menu_id=$menu_id";
      }
      if ($lc_to_id) {
          $lctoId="and imp_lcs.lc_to_id=$lc_to_id";
      }

      $explcfile = $this->implc
			->join('imp_backed_exp_lc_scs',function($join){
				$join->on('imp_lcs.id','=','imp_backed_exp_lc_scs.imp_lc_id');
			})
			->join('exp_lc_scs',function($join){
				$join->on('exp_lc_scs.id','=','imp_backed_exp_lc_scs.exp_lc_sc_id');
			})
			->get([
				'imp_lcs.id',
				'exp_lc_scs.file_no',
			]);

		$explcfileArr=[];
		foreach($explcfile as $data){
			$explcfileArr[$data->id]=$data->file_no;
		}

    $implcs=array();
    $rows = collect(\DB::select("
      select 
      imp_lcs.id,
      imp_lcs.menu_id,
      imp_lcs.lc_date,
      imp_lcs.company_id,
      imp_lcs.supplier_id,
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
      where imp_lcs.approved_by is not null
      $companyId $supplierId $menuId $lctoId
      group by 
      imp_lcs.id,
      imp_lcs.menu_id,
      imp_lcs.lc_date,
      imp_lcs.company_id,
      imp_lcs.supplier_id,
      imp_lcs.lc_type_id,
      imp_lcs.issuing_bank_branch_id,
      imp_lcs.last_delivery_date,
      imp_lcs.expiry_date,
      imp_lcs.lc_application_date,
      imp_lcs.lc_no_i,
      imp_lcs.lc_no_ii,
      imp_lcs.lc_no_iii,
      imp_lcs.lc_no_iv,
      imp_lcs.pay_term_id
      order by imp_lcs.id desc
    "));

    foreach($rows as $row){
      $implc['id']=$row->id;
      $implc['company']=isset($company[$row->company_id])?$company[$row->company_id]:'--';
      $implc['supplier']=isset($supplier[$row->supplier_id])?$supplier[$row->supplier_id]:'--';
      $implc['lc_type_id']=isset($lctype[$row->lc_type_id])?$lctype[$row->lc_type_id]:'--';
      $implc['bankbranch']=isset($bankbranch[$row->issuing_bank_branch_id])?$bankbranch[$row->issuing_bank_branch_id]:'--';
      $implc['last_delivery_date']=($row->last_delivery_date !== null)?date("Y-m-d",strtotime($row->last_delivery_date)):'--';
      $implc['expiry_date']=($row->expiry_date !== null)?date("Y-m-d",strtotime($row->expiry_date)):'--';
      $implc['lc_date']=($row->lc_date !== null)?date("Y-m-d",strtotime($row->lc_date)):'--';
      $implc['lc_application_date']=($row->lc_application_date !== null)?date('Y-m-d',strtotime($row->lc_application_date)):'--';
      $implc['lc_no']=$row->lc_no_i." ".$row->lc_no_ii." ".$row->lc_no_iii." ".$row->lc_no_iv;
      $implc['pay_term_id']=isset($payterm[$row->pay_term_id])?$payterm[$row->pay_term_id]:'--';
      $implc['lc_amount']=number_format($row->lc_amount,2);
      $implc['menu_id']=isset($menu[$row->menu_id])?$menu[$row->menu_id]:'--';
      $implc['file_no']=isset($explcfileArr[$row->id])?$explcfileArr[$row->id]:'--';
      array_push($implcs,$implc);
    }
    echo json_encode($implcs);
  }

  public function unapproved (Request $request)
  {
      $id=request('id',0);
      $master=$this->implc->find($id);
      $user = \Auth::user();
      $unapproved_at=date('Y-m-d h:i:s');
      $unapproved_count=$master->unapproved_count+1;
      $master->approved_by=NUll;
      $master->approved_at=NUll;
      $master->unapproved_by=$user->id;
      $master->unapproved_at=$unapproved_at;
      $master->unapproved_count=$unapproved_count;
      $master->timestamps=false;
      $implc=$master->save();


      if($implc){
          return response()->json(array('success' => true,  'message' => 'Unapproved Successfully'), 200);
      }
  }

  public function impLcProposalPdf(){
      $id=request('id',0);
      $lctype = array_prepend(config('bprs.lctype'), '--','');
      $menu=array_prepend(config('bprs.menu'),'--','');

      $rows = $this->implc
      ->join('bank_branches', function($join){
        $join->on('bank_branches.id', '=', 'imp_lcs.issuing_bank_branch_id');
      })
      ->join('suppliers', function($join){
        $join->on('suppliers.id', '=', 'imp_lcs.supplier_id');
      })
      ->join('banks', function($join){
        $join->on('banks.id', '=', 'bank_branches.bank_id');
      })
      ->join('companies', function($join){
        $join->on('companies.id', '=', 'imp_lcs.company_id');
      })
      ->join('currencies', function($join){
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
      ->leftJoin('buyers', function($join){
        $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
      })
      ->leftJoin(\DB::raw("(
          SELECT 
          imp_lcs.id as imp_lc_id,
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
          FROM imp_lcs  
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
          where imp_lcs.id='".$id."'
          group by 
          imp_lcs.id,
          imp_lcs.menu_id
          ) implcvalue"
      ), "implcvalue.imp_lc_id", "=", "imp_lcs.id")
      ->leftJoin(\DB::raw("(
        select 
        exp_lc_scs.file_no,
        sum(exp_lc_scs.lc_sc_value) as lc_sc_value 
        from exp_lc_scs 
        group by
        exp_lc_scs.file_no
        ) lcscvalue"
      ), "lcscvalue.file_no", "=", "exp_lc_scs.file_no")
      ->leftJoin(\DB::raw("(
        select 
        exp_lc_scs.file_no,
        sum(exp_rep_lc_scs.replaced_amount) as replaced_amount 
        from exp_rep_lc_scs 
        right join exp_lc_scs on exp_lc_scs.id = exp_rep_lc_scs.replaced_lc_sc_id   
        group by exp_lc_scs.file_no) LcScRep"), "LcScRep.file_no", "=", "exp_lc_scs.file_no")
      ->leftJoin(\DB::raw("(
        SELECT 
        exp_lc_scs.file_no,
        sum(imp_backed_exp_lc_scs.amount) as btb_opened_amount 
        from imp_backed_exp_lc_scs 
        right join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
        right join imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id   
        where  imp_lcs.lc_date is not null  
        group by exp_lc_scs.file_no) BTBOpened"
      ), "BTBOpened.file_no", "=", "exp_lc_scs.file_no")
      ->leftJoin(\DB::raw("(
        SELECT 
        exp_lc_scs.file_no,
        sum(imp_backed_exp_lc_scs.amount) as btb_opening_amount 
        from imp_backed_exp_lc_scs 
        right join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
        right join imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id   
        where  imp_lcs.lc_date is null 
        group by exp_lc_scs.file_no) BTB"
      ), "BTB.file_no", "=", "exp_lc_scs.file_no")
      ->leftJoin(\DB::raw("(
        select 
          exp_lc_scs.file_no, 
          sum(imp_backed_exp_lc_scs.amount) as fabric_btb_amount
        from 
        imp_backed_exp_lc_scs 
        join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
        join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
        where imp_lcs.menu_id=1
        group by exp_lc_scs.file_no) fabricbtb"
      ), "fabricbtb.file_no", "=", "exp_lc_scs.file_no")
      ->leftJoin(\DB::raw("(
        select 
          exp_lc_scs.file_no, 
          sum(imp_backed_exp_lc_scs.amount) as trims_btb_amount
        from 
        imp_backed_exp_lc_scs 
        join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
        join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
        where imp_lcs.menu_id=2
        group by exp_lc_scs.file_no) trimsbtb"
      ), "trimsbtb.file_no", "=", "exp_lc_scs.file_no")
      ->leftJoin(\DB::raw("(
        select 
          exp_lc_scs.file_no, 
          sum(imp_backed_exp_lc_scs.amount) as yarn_btb_amount
        from 
        imp_backed_exp_lc_scs 
        join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
        join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
        where imp_lcs.menu_id=3
        group by exp_lc_scs.file_no) yarnbtb"
      ), "yarnbtb.file_no", "=", "exp_lc_scs.file_no")
      ->leftJoin(\DB::raw("(
        select 
          exp_lc_scs.file_no, 
          sum(imp_backed_exp_lc_scs.amount) as knit_btb_amount
        from
        imp_backed_exp_lc_scs 
        join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
        join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
        where imp_lcs.menu_id=4
        group by exp_lc_scs.file_no) knitbtb"
      ), "knitbtb.file_no", "=", "exp_lc_scs.file_no")      
      ->leftJoin(\DB::raw("(
        select 
          exp_lc_scs.file_no, 
          sum(imp_backed_exp_lc_scs.amount) as aop_btb_amount
        from 
        imp_backed_exp_lc_scs 
        join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
        join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
        where imp_lcs.menu_id=5
        group by exp_lc_scs.file_no) aopbtb"
      ), "aopbtb.file_no", "=", "exp_lc_scs.file_no")
      ->leftJoin(\DB::raw("(
        select 
          exp_lc_scs.file_no, 
          sum(imp_backed_exp_lc_scs.amount) as dyeing_service_btb_amount
        from imp_backed_exp_lc_scs 
        join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
        join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
        where imp_lcs.menu_id=6
        group by exp_lc_scs.file_no) dyeingservicebtb"
      ), "dyeingservicebtb.file_no", "=", "exp_lc_scs.file_no")
      ->leftJoin(\DB::raw("(
        select 
          exp_lc_scs.file_no, 
          sum(imp_backed_exp_lc_scs.amount) as dye_chem_btb_amount
        from imp_backed_exp_lc_scs 
        join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
        join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
        where imp_lcs.menu_id=7
        group by exp_lc_scs.file_no) dyechembtb"
      ), "dyechembtb.file_no", "=", "exp_lc_scs.file_no")
      ->leftJoin(\DB::raw("(
        select 
          exp_lc_scs.file_no, 
          sum(imp_backed_exp_lc_scs.amount) as yarn_dyeing_btb_amount
        from imp_backed_exp_lc_scs 
        join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
        join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
        where imp_lcs.menu_id=9
        group by exp_lc_scs.file_no) yarndyeingbtb"
      ), "yarndyeingbtb.file_no", "=", "exp_lc_scs.file_no")
      ->leftJoin(\DB::raw("(
        select 
          exp_lc_scs.file_no, 
          sum(imp_backed_exp_lc_scs.amount) as others_btb_amount
        from imp_backed_exp_lc_scs 
        join exp_lc_scs on exp_lc_scs.id = imp_backed_exp_lc_scs.exp_lc_sc_id 
        join  imp_lcs on imp_lcs.id = imp_backed_exp_lc_scs.imp_lc_id
        where imp_lcs.menu_id in (8,10,11)
        group by exp_lc_scs.file_no) othersbtb"
      ), "othersbtb.file_no", "=", "exp_lc_scs.file_no")
      ->leftJoin(\DB::raw("(
        select
        n.file_no,
        sum(n.yarn_req_amount) as yarn_req_amount,
        sum(n.trim_amount) as trim_req_amount,
        sum(n.fin_fab_req_amount) as fin_fab_req_amount,
        sum(n.dying_amount) as dying_amount,
        sum(n.overhead_amount) as overhead_amount,
        sum(n.kniting_amount) as kniting_amount,
        sum(n.aop_amount) as aop_amount,
        sum(n.aop_overhead_amount) as aop_overhead_amount,
        sum(n.dyed_yarn_rq_amount) as dyed_yarn_rq_amount
        from(
        select 
            exp_lc_scs.file_no,
            sales_orders.id,
            budgetYarn.yarn_req_amount,
            budgetTrim.trim_amount,
            budgetFinfab.fin_fab_req_amount,
            budgetDyeing.dying_amount,
            budgetDyeing.overhead_amount,
            budgetKniting.kniting_amount,
            budgetAop.aop_amount,
            budgetAop.aop_overhead_amount ,
            budgetYarnDyeing.dyed_yarn_rq_amount
            from exp_lc_sc_pis 
            join exp_lc_scs on exp_lc_scs.id = exp_lc_sc_pis.exp_lc_sc_id 
            join exp_pis on exp_pis.id = exp_lc_sc_pis.exp_pi_id 
            join exp_pi_orders on exp_pi_orders.exp_pi_id = exp_pis.id
            join sales_orders  on sales_orders.id=exp_pi_orders.sales_order_id
            join jobs  on jobs.id=sales_orders.job_id
            join styles  on styles.id=jobs.style_id
            left join (
                select 
                m.id as sale_order_id,
                sum(m.yarn) as yarn_req,
                sum(m.yarn_amount) as yarn_req_amount  
                from (
                    select 
                    budget_yarns.id as budget_yarn_id ,
                    budget_yarns.ratio,
                    budget_yarns.cons,
                    budget_yarns.rate,
                    budget_yarns.amount,
                    sum(budget_fabric_cons.grey_fab) as grey_fab,
                    sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100)) as yarn,
                    (sum(((budget_fabric_cons.grey_fab*budget_yarns.ratio)/100))*budget_yarns.rate) as yarn_amount,sales_orders.id as id  
                    from budget_yarns 
                    join budget_fabric_cons on budget_yarns.budget_fabric_id=budget_fabric_cons.budget_fabric_id 
                    join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.id = budget_fabric_cons.sales_order_gmt_color_size_id 
                    join sales_orders on sales_orders.id=sales_order_gmt_color_sizes.sale_order_id 
                    --where sales_order_gmt_color_sizes.qty > 0
                    group by 
                    budget_yarns.id,
                    budget_yarns.ratio,
                    budget_yarns.cons,
                    budget_yarns.rate,
                    budget_yarns.amount,
                    sales_orders.id,
                    sales_orders.sale_order_no
                ) m 
                group by m.id
            ) budgetYarn on budgetYarn.sale_order_id=sales_orders.id
            left join(
                select 
                sales_orders.id as sales_order_id,
                sum(budget_trim_cons.amount) as trim_amount 
                from sales_orders 
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
                join budget_trim_cons on budget_trim_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
                join budget_trims on budget_trims.id = budget_trim_cons.budget_trim_id
                where sales_order_gmt_color_sizes.qty > 0
                group by sales_orders.id
            )budgetTrim on budgetTrim.sales_order_id=sales_orders.id
            left join (
              select
                sales_orders.id as sales_order_id,
                sum(budget_fabric_cons.amount) as fin_fab_req_amount
                from sales_orders 
                join sales_order_gmt_color_sizes on sales_order_gmt_color_sizes.sale_order_id = sales_orders.id
                join budget_fabric_cons on budget_fabric_cons.sales_order_gmt_color_size_id = sales_order_gmt_color_sizes.id
                join budget_fabrics on budget_fabrics.id=budget_fabric_cons.budget_fabric_id
                join style_fabrications on style_fabrications.id=budget_fabrics.style_fabrication_id
                join jobs on jobs.id = sales_orders.job_id 
                join styles on styles.id = jobs.style_id 
                where sales_orders.order_status !=2
                and style_fabrications.material_source_id=1
                and sales_order_gmt_color_sizes.qty > 0
              group by
              sales_orders.id
            )budgetFinfab on budgetFinfab.sales_order_id=sales_orders.id
            left join (
              SELECT 
              sales_orders.id as sales_order_id,
              sum(budget_yarn_dyeing_cons.amount) as dyed_yarn_rq_amount
              FROM sales_orders 
              join budget_yarn_dyeing_cons on budget_yarn_dyeing_cons.sales_order_id = sales_orders.id
              join jobs on jobs.id = sales_orders.job_id 
              join styles on styles.id = jobs.style_id
              group by sales_orders.id
            )budgetYarnDyeing on budgetYarnDyeing.sales_order_id=sales_orders.id
            left join (
                select 
                sales_orders.id as sale_order_id,
                production_processes.production_area_id,
                sum(budget_fabric_prod_cons.amount) as dying_amount,
                sum(budget_fabric_prod_cons.overhead_amount) as overhead_amount
                from budget_fabric_prod_cons 
                left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
                left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
                left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
                where production_processes.production_area_id =20
                group by sales_orders.id,
                production_processes.production_area_id
            )budgetDyeing on budgetDyeing.sale_order_id=sales_orders.id
            left join (
                select 
                sales_orders.id as sale_order_id,
                production_processes.production_area_id,
                sum(budget_fabric_prod_cons.amount) as kniting_amount
            from budget_fabric_prod_cons 
            left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
            left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
            left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
            where production_processes.production_area_id =10
            group by 
            sales_orders.id,
            production_processes.production_area_id
            )budgetKniting on budgetKniting.sale_order_id=sales_orders.id
            left join (
                select sales_orders.id as sale_order_id,
                production_processes.production_area_id,
                sum(budget_fabric_prod_cons.amount) as aop_amount,
                sum(budget_fabric_prod_cons.overhead_amount) as aop_overhead_amount
            from budget_fabric_prod_cons 
            left join sales_orders on sales_orders.id = budget_fabric_prod_cons.sales_order_id 
            left join budget_fabric_prods on budget_fabric_prods.id=budget_fabric_prod_cons.budget_fabric_prod_id 
            left join production_processes on production_processes.id=budget_fabric_prods.production_process_id 
            where production_processes.production_area_id =25
            group by 
            sales_orders.id,
            production_processes.production_area_id
            )budgetAop on budgetAop.sale_order_id=sales_orders.id
            group by 
            exp_lc_scs.file_no,
            sales_orders.id,
            budgetYarn.yarn_req_amount,
            budgetTrim.trim_amount,
            budgetFinfab.fin_fab_req_amount,
            budgetDyeing.dying_amount,
            budgetDyeing.overhead_amount,
            budgetKniting.kniting_amount,
            budgetAop.aop_amount,
            budgetAop.aop_overhead_amount,
            budgetYarnDyeing.dyed_yarn_rq_amount
        ) n
        group by n.file_no) budgetReq"
      ), "budgetReq.file_no", "=", "exp_lc_scs.file_no")
      ->leftJoin(\DB::raw("(
        SELECT 
        exp_lc_scs.file_no,
        sum(exp_pi_orders.qty) as so_qty,
        avg(exp_pi_orders.rate) as so_rate,
        sum(exp_pi_orders.amount) as so_amount 
        FROM exp_lc_sc_pis 
        left join exp_lc_scs on exp_lc_scs.id = exp_lc_sc_pis.exp_lc_sc_id 
        left join exp_pis on exp_pis.id = exp_lc_sc_pis.exp_pi_id 
        left join exp_pi_orders on exp_pi_orders.exp_pi_id = exp_pis.id  
        group by 
        exp_lc_scs.file_no
      ) ExpSO"), "ExpSO.file_no", "=", "exp_lc_scs.file_no")
      ->where([['imp_lcs.id','=',$id]])
      ->selectRaw('
        imp_lcs.id,
        imp_lcs.commodity,
        imp_lcs.lc_type_id,
        imp_lcs.margin_deposit,
        imp_lcs.remarks,
        banks.name as bank_name,
        bank_branches.branch_name,
        bank_branches.address as bank_address,
        bank_branches.contact,
        companies.name as company_name,
        companies.address as company_address,
        currencies.code as currency_code,
        currencies.symbol as currency_symbol,
        commercial_heads.name as account_type,
        bank_accounts.account_no,
        exp_lc_scs.file_no,
        suppliers.name as supplier_name,
        implcvalue.lc_amount,
        lcscvalue.lc_sc_value,
        LcScRep.replaced_amount,
        BTBOpened.btb_opened_amount,
        BTB.btb_opening_amount,
        buyers.name as buyer_name,
        fabricbtb.fabric_btb_amount,
        trimsbtb.trims_btb_amount,
        yarnbtb.yarn_btb_amount,
        knitbtb.knit_btb_amount,
        aopbtb.aop_btb_amount,
        dyeingservicebtb.dyeing_service_btb_amount,
        dyechembtb.dye_chem_btb_amount,
        yarndyeingbtb.yarn_dyeing_btb_amount,
        othersbtb.others_btb_amount,
        budgetReq.fin_fab_req_amount,
        budgetReq.trim_req_amount,
        budgetReq.yarn_req_amount,
        budgetReq.dying_amount,
        budgetReq.overhead_amount,
        budgetReq.kniting_amount,
        budgetReq.aop_amount,
        budgetReq.aop_overhead_amount,
        budgetReq.dyed_yarn_rq_amount,
        ExpSO.so_amount,
        max(exp_lc_scs.last_delivery_date) as last_delivery_date
      ')
      ->groupBy([
        'imp_lcs.id',
        'imp_lcs.commodity',
        'imp_lcs.lc_type_id',
        'imp_lcs.margin_deposit',
        'imp_lcs.remarks',
        'banks.name',
        'bank_branches.branch_name',
        'bank_branches.address',
        'bank_branches.contact',
        'companies.name',
        'companies.address',
        'currencies.code',
        'currencies.symbol',
        'commercial_heads.name',
        'bank_accounts.account_no',
        'exp_lc_scs.file_no',
        'suppliers.name',
        'implcvalue.lc_amount',
        'lcscvalue.lc_sc_value',
        'LcScRep.replaced_amount',
        'BTBOpened.btb_opened_amount',
        'BTB.btb_opening_amount',
        'buyers.name',
        'fabricbtb.fabric_btb_amount',
        'trimsbtb.trims_btb_amount',
        'yarnbtb.yarn_btb_amount',
        'knitbtb.knit_btb_amount',
        'aopbtb.aop_btb_amount',
        'dyeingservicebtb.dyeing_service_btb_amount',
        'dyechembtb.dye_chem_btb_amount',
        'yarndyeingbtb.yarn_dyeing_btb_amount',
        'othersbtb.others_btb_amount',
        'budgetReq.fin_fab_req_amount',
        'budgetReq.trim_req_amount',
        'budgetReq.yarn_req_amount',
        'budgetReq.dying_amount',
        'budgetReq.overhead_amount',
        'budgetReq.kniting_amount',
        'budgetReq.aop_amount',
        'budgetReq.aop_overhead_amount',
        'budgetReq.dyed_yarn_rq_amount',
        'ExpSO.so_amount',
      ])
      ->get()
      ->first();

      $rows->lc_type=$lctype[$rows->lc_type_id];
      $rows->lc_sc_value=$rows->lc_sc_value-$rows->replaced_amount;
      $rows->limit_btb_open=($rows->lc_sc_value*70)/100;
      $rows->yet_btb_open=$rows->limit_btb_open-$rows->btb_opened_amount;
      $rows->limit_btb_booked=$rows->btb_opening_amount-$rows->lc_amount;
      $rows->fund_available=$rows->yet_btb_open-$rows->limit_btb_booked;
      $rows->dying_req_amount=$rows->dying_amount+$rows->overhead_amount;
      $rows->aop_req_amount=$rows->aop_amount+$rows->aop_overhead_amount;
      $rows->last_delivery_date=$rows->last_delivery_date?date('d-M-Y',strtotime($rows->last_delivery_date)):'--';

      //dd($lc_sc_value);die;
      $explcfile = $this->implc
			->join('imp_backed_exp_lc_scs',function($join){
				$join->on('imp_lcs.id','=','imp_backed_exp_lc_scs.imp_lc_id');
			})
			->join('exp_lc_scs',function($join){
				$join->on('exp_lc_scs.id','=','imp_backed_exp_lc_scs.exp_lc_sc_id');
			})
			->get([
				'imp_lcs.id',
				'exp_lc_scs.file_no',
			]);

      $explcfileArr=[];
      foreach($explcfile as $data){
        $explcfileArr[$data->id]=$data->file_no;
      }


      $implc=$this->implc->find($id);
      $menu_id=$implc->menu_id; 
      //Fabric Purchase Order
      if ($menu_id==1) {
          $fabricDescription=$this->budgetfabric
          ->join('style_fabrications',function($join){
            $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
          })
          ->join('autoyarns',function($join){
            $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
          })
          ->join('autoyarnratios',function($join){
            $join->on('autoyarns.id','=','autoyarnratios.autoyarn_id');
          })
          ->join('compositions',function($join){
            $join->on('compositions.id','=','autoyarnratios.composition_id');
          })
          ->join('constructions',function($join){
            $join->on('constructions.id','=','autoyarns.construction_id');
          })
          ->join('po_fabric_items',function($join){
            $join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id')
            ->whereNull('po_fabric_items.deleted_at');
          })
          ->join('po_fabrics',function($join){
            $join->on('po_fabrics.id','=','po_fabric_items.po_fabric_id');
          })
          ->join('imp_lc_pos',function($join){
              $join->on('imp_lc_pos.purchase_order_id','=','po_fabrics.id');
          })
          ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
          ->get([
            'style_fabrications.id',
            'constructions.name as construction',
            'autoyarnratios.composition_id',
            'compositions.name',
            'autoyarnratios.ratio',
          ]);
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
          $purchaseorder =$this->implc
          ->selectRaw('
            imp_lc_pos.purchase_order_id,
            po_fabrics.po_no,
            po_fabrics.remarks,
            po_fabrics.approved_by,
            po_fabrics.amount as fabric_amount,
            budget_fabrics.style_fabrication_id,
            budget_fabrics.gsm_weight,
            fabric_colors.name as fabric_color_name,
            gmtsparts.name as gmtspart_name,
            uoms.code as uom_code,
            users.signature_file,
            employee_h_rs.name as user_name,
            designations.name as user_designation,
            sum(po_fabric_item_qties.qty) as po_qty,
            avg(po_fabric_item_qties.rate) as po_rate,
            sum(po_fabric_item_qties.amount) as po_amount
          ')
          ->join('imp_lc_pos',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
          })
          ->join('po_fabrics',function($join){
            $join->on('imp_lc_pos.purchase_order_id','=','po_fabrics.id');
          })
          ->leftJoin('users',function($join){
            $join->on('users.id','=','po_fabrics.approved_by');
          })
          ->leftJoin('employee_h_rs',function($join){
            $join->on('employee_h_rs.user_id','=','users.id');
          })
          ->leftJoin('designations',function($join){
            $join->on('employee_h_rs.designation_id','=','designations.id');
          })
          ->join('po_fabric_items',function($join){
            $join->on('po_fabrics.id','=','po_fabric_items.po_fabric_id');
          })
          ->join('budget_fabrics',function($join){
            $join->on('po_fabric_items.budget_fabric_id','=','budget_fabrics.id')
            ->whereNull('po_fabric_items.deleted_at');
          })
          ->join('style_fabrications',function($join){
            $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
          })
          ->join('style_gmts',function($join){
            $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
          })
          ->join('gmtsparts',function($join){
            $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
          })
          ->join('autoyarns',function($join){
            $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
          })
          ->join('uoms',function($join){
            $join->on('uoms.id','=','style_fabrications.uom_id');
          })
          ->join('budget_fabric_cons',function($join){
            $join->on('budget_fabric_cons.budget_fabric_id','=','po_fabric_items.budget_fabric_id')
            ->whereNull('budget_fabric_cons.deleted_at');
          })
          ->join('colors as fabric_colors',function($join){
            $join->on('fabric_colors.id','=','budget_fabric_cons.fabric_color');
          })  
          ->join('po_fabric_item_qties',function($join){
            $join->on('po_fabric_item_qties.po_fabric_item_id','=','po_fabric_items.id');
            $join->on('po_fabric_item_qties.budget_fabric_con_id','=','budget_fabric_cons.id');
          })
          ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
          ->orderBy('po_fabrics.po_no','desc')
          ->groupBy([
            'imp_lc_pos.purchase_order_id',
            'po_fabrics.po_no',
            'po_fabrics.remarks',
            'po_fabrics.approved_by',
            'po_fabrics.amount',
            'users.signature_file',
            'employee_h_rs.name',
            'designations.name',
            'budget_fabrics.style_fabrication_id',
            'budget_fabrics.gsm_weight',
            'fabric_colors.name',
            'gmtsparts.name',
            'uoms.code'
          ])
          ->get()
          ->map(function($purchaseorder) use($desDropdown) {
            $purchaseorder->menu_name="Fabrics";
            $purchaseorder->fabric_description=$desDropdown[$purchaseorder->style_fabrication_id]?$desDropdown[$purchaseorder->style_fabrication_id]:'';
            $purchaseorder->item_description = $purchaseorder->gmtspart_name.", ".$purchaseorder->fabric_description.", ".$purchaseorder->fabric_color_name;
            return $purchaseorder;
          });

        $datas=$purchaseorder->groupBy(['purchase_order_id']);
        $poNoArr=[];
        foreach($purchaseorder as $data){
          $poNoArr[$data->purchase_order_id]['po_no']=$data->po_no;
          $poNoArr[$data->purchase_order_id]['remarks']=$data->remarks;
          $poNoArr[$data->purchase_order_id]['approved_by']=$data->approved_by?"Approved By: ".$data->user_name:'';
          $poNoArr[$data->purchase_order_id]['approved_by_designation']=$data->user_designation?" Designation: ".$data->user_designation:'';
          $poNoArr[$data->purchase_order_id]['approved_by_signature']=$data->signature_file?'images/signature/'.$data->signature_file:null;
        }

      }
      //Trims Purchase Order
      elseif($menu_id==2){
        $purchaseorder =$this->implc
          ->selectRaw('
            imp_lc_pos.purchase_order_id,
            imp_lc_pos.imp_lc_id,
            po_trims.po_no,
            po_trims.remarks,
            po_trims.approved_by,
            itemcategories.name as itemcategory,
            itemclasses.name as itemclass_name,
            po_trim_item_reports.description,
            po_trim_item_reports.measurment,
            uoms.code as uom_code,
            users.signature_file,
            employee_h_rs.name as user_name,
            designations.name as user_designation,
            sum(po_trim_item_reports.qty) as po_qty,
            avg(po_trim_item_reports.rate) as po_rate,
            sum(po_trim_item_reports.amount) as po_amount
          ')
          ->join('imp_lc_pos',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
          })
          ->leftJoin('po_trims',function($join){
            $join->on('imp_lc_pos.purchase_order_id','=','po_trims.id');
          })
          ->leftJoin('users',function($join){
            $join->on('users.id','=','po_trims.approved_by');
          })
          ->leftJoin('employee_h_rs',function($join){
            $join->on('employee_h_rs.user_id','=','users.id');
          })
          ->leftJoin('designations',function($join){
            $join->on('employee_h_rs.designation_id','=','designations.id');
          })
          ->join('po_trim_items',function($join){
            $join->on('po_trims.id','=','po_trim_items.po_trim_id');
          })
          ->join('budget_trims',function($join){
            $join->on('po_trim_items.budget_trim_id','=','budget_trims.id')
          ->whereNull('po_trim_items.deleted_at');
          })
          ->join('budgets',function($join){
            $join->on('budgets.id','=','budget_trims.budget_id');
          })
          ->join('jobs',function($join){
            $join->on('jobs.id','=','budgets.job_id');
          })
          ->join('styles', function($join) {
            $join->on('styles.id', '=', 'jobs.style_id');
          })
          ->join('buyers', function($join) {
            $join->on('buyers.id', '=', 'styles.buyer_id');
          })
          ->leftJoin('itemclasses', function($join){
            $join->on('itemclasses.id', '=','budget_trims.itemclass_id');
          })
          ->leftJoin('uoms',function($join){
            $join->on('uoms.id','=','budget_trims.uom_id');
          })
          ->leftJoin('itemcategories', function($join){
            $join->on('itemcategories.id', '=','itemclasses.itemcategory_id');
          })
          ->join('po_trim_item_reports',function($join){
            $join->on('po_trim_item_reports.po_trim_item_id','=','po_trim_items.id');
          })
          ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
          ->groupBy([
            'imp_lc_pos.purchase_order_id',
            'imp_lc_pos.imp_lc_id',
            'po_trims.po_no',
            'po_trims.remarks',
            'po_trims.approved_by',
            'itemcategories.name',
            'itemclasses.name',
            'uoms.code',
            'po_trim_item_reports.description',
            'po_trim_item_reports.measurment',
            'users.signature_file',
            'employee_h_rs.name',
            'designations.name',
          ])
          ->orderBy('po_trims.po_no','desc')
          ->get()
          ->map(function($purchaseorder) use($explcfileArr){
            $purchaseorder->file_no=isset($explcfileArr[$purchaseorder->imp_lc_id])?$explcfileArr[$purchaseorder->imp_lc_id]:'--';
            $purchaseorder->po_no=$purchaseorder->po_no.", File:".$purchaseorder->file_no;
            $purchaseorder->menu_name="Accessories";
            $purchaseorder->item_description=$purchaseorder->itemclass_name.", ".$purchaseorder->description.", ".$purchaseorder->measurment;
            return $purchaseorder;
          });

          $datas=$purchaseorder->groupBy(['purchase_order_id']);
          $poNoArr=[];
          foreach($purchaseorder as $data){
            $poNoArr[$data->purchase_order_id]['po_no']=$data->po_no;
            $poNoArr[$data->purchase_order_id]['remarks']=$data->remarks;
            $poNoArr[$data->purchase_order_id]['approved_by']=$data->approved_by?"Approved By: ".$data->user_name:'';
            $poNoArr[$data->purchase_order_id]['approved_by_designation']=$data->user_designation?" Designation: ".$data->user_designation:'';
            $poNoArr[$data->purchase_order_id]['approved_by_signature']=$data->signature_file?'images/signature/'.$data->signature_file:null;
          }

      }
      //Yarn Purchase Order
      elseif ($menu_id==3) {
        $yarnDescription=$this->itemaccount
        ->leftJoin('item_account_ratios',function($join){
          $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
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
        ->leftJoin('compositions',function($join){
          $join->on('compositions.id','=','item_account_ratios.composition_id');
        })
        ->leftJoin('itemcategories',function($join){
          $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        //->where([['itemcategories.identity','=',1]])
        ->get([
          'item_accounts.id',
          'yarncounts.count',
          'yarncounts.symbol',
          'yarntypes.name as yarn_type',
          'itemclasses.name as itemclass_name',
          'compositions.name as composition_name',
          'item_account_ratios.ratio'
        ]);
        $itemaccountArr=array();
        $yarnCompositionArr=array();
        foreach($yarnDescription as $row){
            $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
            $itemaccountArr[$row->id]['yarn_type']=$row->yarn_type;
            //$itemaccountArr[$row->id]['itemclass_name']=$row->itemclass_name;
            $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $yarnDropdown=array();
        foreach($itemaccountArr as $key=>$value){
            $yarnDropdown[$key]=/* $value['itemclass_name']." ". */$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
        }

        $purchaseorder =$this->implc
        ->selectRaw('
          imp_lc_pos.purchase_order_id,
          imp_lc_pos.imp_lc_id,
          po_yarns.po_no,
          po_yarns.remarks,
          po_yarns.approved_by,
          uoms.code as uom_code,
          users.signature_file,
          employee_h_rs.name as user_name,
          designations.name as user_designation,
          po_yarn_items.item_account_id,
          po_yarn_items.qty as po_qty,
          po_yarn_items.rate as po_rate,
          po_yarn_items.amount as po_amount
        ')
        ->join('imp_lc_pos',function($join){
          $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
        })
        ->join('po_yarns',function($join){
          $join->on('imp_lc_pos.purchase_order_id','=','po_yarns.id');
        })
        ->leftJoin('users',function($join){
          $join->on('users.id','=','po_yarns.approved_by');
        })
        ->leftJoin('employee_h_rs',function($join){
          $join->on('employee_h_rs.user_id','=','users.id');
        })
        ->leftJoin('designations',function($join){
          $join->on('employee_h_rs.designation_id','=','designations.id');
        })
        ->join('po_yarn_items',function($join){
          $join->on('po_yarn_items.po_yarn_id','=','po_yarns.id');
        //->whereNull('po_yarn_items.deleted_at');
        })
        ->leftJoin('item_accounts', function($join){
          $join->on('item_accounts.id', '=', 'po_yarn_items.item_account_id');
        })
        ->leftJoin('uoms',function($join){
          $join->on('uoms.id','=','item_accounts.uom_id');
        })
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        ->orderBy('po_yarns.po_no','desc')
        ->get()
        ->map(function($purchaseorder) use($yarnDropdown) {
          $purchaseorder->menu_name="Yarn";
          $purchaseorder->item_description = $yarnDropdown[$purchaseorder->item_account_id];
          return $purchaseorder;
        });

        $datas=$purchaseorder->groupBy(['purchase_order_id']);
        $poNoArr=[];
        foreach($purchaseorder as $data){
          $poNoArr[$data->purchase_order_id]['po_no']=$data->po_no;
          $poNoArr[$data->purchase_order_id]['remarks']=$data->remarks;
          $poNoArr[$data->purchase_order_id]['approved_by']=$data->approved_by?"Approved By: ".$data->user_name:'';
          $poNoArr[$data->purchase_order_id]['approved_by_designation']=$data->user_designation?" Designation: ".$data->user_designation:'';
          $poNoArr[$data->purchase_order_id]['approved_by_signature']=$data->signature_file?'images/signature/'.$data->signature_file:null;
        }

      }
      //knit Service
      elseif ($menu_id==4) {
        $materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
        $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

        $fabricDescription=$this->budgetfabric
        ->join('budget_fabric_prods',function($join){
          $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->join('po_knit_service_items',function($join){
          $join->on('budget_fabric_prods.id','=','po_knit_service_items.budget_fabric_prod_id');
        })
        ->join('po_knit_services',function($join){
          $join->on('po_knit_service_items.po_knit_service_id','=','po_knit_services.id');
        })
        ->join('imp_lc_pos',function($join){
          $join->on('imp_lc_pos.purchase_order_id','=','po_knit_services.id');
        })
        ->join('style_fabrications',function($join){
          $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->join('autoyarns',function($join){
          $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
        })
        ->leftJoin('autoyarnratios',function($join){
          $join->on('autoyarns.id','=','autoyarnratios.autoyarn_id');
        })
        ->leftJoin('compositions',function($join){
          $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->leftJoin('constructions',function($join){
          $join->on('constructions.id','=','autoyarns.construction_id');
        })
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        ->get([
          'style_fabrications.id',
          'constructions.name as construction',
          'autoyarnratios.composition_id',
          'compositions.name',
          'autoyarnratios.ratio',
        ]);

        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($fabricDescription as $row){
          $fabricDescriptionArr[$row->id]=$row->construction;
          $fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
        }
        
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
            $desDropdown[$key]=$val.", ".implode(",",$fabricCompositionArr[$key]);
        }

        $purchaseorder =$this->implc
        ->selectRaw('
          imp_lc_pos.purchase_order_id,
          imp_lc_pos.imp_lc_id,
          po_knit_services.po_no,
          po_knit_services.remarks,
          po_knit_services.approved_by,
          users.signature_file,
          employee_h_rs.name as user_name,
          designations.name as user_designation,
          budget_fabrics.style_fabrication_id,
          budget_fabrics.gsm_weight,
          gmtsparts.name as gmtspart_name,
          po_knit_service_item_qties.dia,
          uoms.code as uom_code,
          sum(po_knit_service_item_qties.qty) as po_qty,
          avg(po_knit_service_item_qties.rate) as po_rate,
          sum(po_knit_service_item_qties.amount) as po_amount
        ')
        ->join('imp_lc_pos',function($join){
          $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
        })
        ->join('po_knit_services',function($join){
          $join->on('imp_lc_pos.purchase_order_id','=','po_knit_services.id');
        })
        ->leftJoin('users',function($join){
          $join->on('users.id','=','po_knit_services.approved_by');
        })
        ->leftJoin('employee_h_rs',function($join){
          $join->on('employee_h_rs.user_id','=','users.id');
        })
        ->leftJoin('designations',function($join){
          $join->on('employee_h_rs.designation_id','=','designations.id');
        })
        ->join('po_knit_service_items',function($join){
          $join->on('po_knit_service_items.po_knit_service_id','=','po_knit_services.id');
        })
        ->join('po_knit_service_item_qties',function($join){
          $join->on('po_knit_service_item_qties.po_knit_service_item_id','=','po_knit_service_items.id');
        })
        ->join('sales_orders',function($join){
          $join->on('sales_orders.id','=','po_knit_service_item_qties.sales_order_id');
        })
        ->join('budget_fabric_prods',function($join){
          $join->on('budget_fabric_prods.id','=','po_knit_service_items.budget_fabric_prod_id');
        })
        ->join('budget_fabrics',function($join){
          $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->join('style_fabrications',function($join){
          $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->join('gmtsparts',function($join){
          $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
        })
        ->leftJoin('uoms',function($join){
          $join->on('uoms.id','=','style_fabrications.uom_id');
        })
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        ->orderBy('po_knit_services.po_no','desc')
        ->groupBy([
          'imp_lc_pos.purchase_order_id',
          'po_knit_services.po_no',
          'po_knit_services.remarks',
          'po_knit_services.approved_by',
          'users.signature_file',
          'employee_h_rs.name',
          'designations.name',
          'budget_fabrics.style_fabrication_id',
          'budget_fabrics.gsm_weight',
          'gmtsparts.name',
          'po_knit_service_item_qties.dia',
          'uoms.code'
        ])
        ->get()
        ->map(function($purchaseorder) use($desDropdown){
          $purchaseorder->menu_name="Knit Service";
          $purchaseorder->fabric_description=$desDropdown[$purchaseorder->style_fabrication_id]?$desDropdown[$purchaseorder->style_fabrication_id]:'';
          $purchaseorder->item_description = $purchaseorder->gmtspart_name.", ".$purchaseorder->fabric_description.", GSM:".$purchaseorder->gsm_weight.", Dia:".$purchaseorder->dia;
          return $purchaseorder;
        });

        $datas=$purchaseorder->groupBy(['purchase_order_id']);
        $poNoArr=[];
        foreach($purchaseorder as $data){
          $poNoArr[$data->purchase_order_id]['po_no']=$data->po_no;
          $poNoArr[$data->purchase_order_id]['remarks']=$data->remarks;
          $poNoArr[$data->purchase_order_id]['approved_by']=$data->approved_by?"Approved By: ".$data->user_name:'';
          $poNoArr[$data->purchase_order_id]['approved_by_designation']=$data->user_designation?" Designation: ".$data->user_designation:'';
          $poNoArr[$data->purchase_order_id]['approved_by_signature']=$data->signature_file?'images/signature/'.$data->signature_file:null;
        }
      }
      //AOP Service
      elseif ($menu_id==5) {
        $materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
        $aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');
        $fabricDescription=$this->budgetfabric
        ->join('budget_fabric_prods',function($join){
          $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->join('po_aop_service_items',function($join){
          $join->on('budget_fabric_prods.id','=','po_aop_service_items.budget_fabric_prod_id');
        })
        ->join('po_aop_services',function($join){
          $join->on('po_aop_service_items.po_aop_service_id','=','po_aop_services.id');
        })
        ->leftJoin('imp_lc_pos',function($join){
          $join->on('imp_lc_pos.purchase_order_id','=','po_aop_services.id');
        })
        ->join('style_fabrications',function($join){
          $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->join('autoyarns',function($join){
          $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
        })
        ->join('autoyarnratios',function($join){
          $join->on('autoyarns.id','=','autoyarnratios.autoyarn_id');
        })
        ->join('compositions',function($join){
            $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->join('constructions',function($join){
            $join->on('constructions.id','=','autoyarns.construction_id');
        })
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        ->get([
          'style_fabrications.id',
          'constructions.name as construction',
          'autoyarnratios.composition_id',
          'compositions.name',
          'autoyarnratios.ratio',
        ]);
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

        $purchaseorder =$this->implc
        ->selectRaw('
          imp_lc_pos.purchase_order_id,
          po_aop_services.po_no,
          po_aop_services.remarks,
          po_aop_services.approved_by,
          budget_fabrics.style_fabrication_id,
          budget_fabrics.gsm_weight,
          gmtsparts.name as gmtspart_name,
          colors.name as fabric_color,
          po_aop_service_item_qties.coverage,
          po_aop_service_item_qties.impression,
          po_aop_service_item_qties.embelishment_type_id,
          uoms.code as uom_code,
          users.signature_file,
          employee_h_rs.name as user_name,
          designations.name as user_designation,
          sum(po_aop_service_item_qties.qty) as po_qty,
          avg(po_aop_service_item_qties.rate) as po_rate,
          sum(po_aop_service_item_qties.amount) as po_amount
        ')
        ->join('imp_lc_pos',function($join){
          $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
        })
        ->leftJoin('po_aop_services',function($join){
          $join->on('imp_lc_pos.purchase_order_id','=','po_aop_services.id');
        })
        ->join('po_aop_service_items',function($join){
          $join->on('po_aop_service_items.po_aop_service_id','=','po_aop_services.id');
        })
        ->join('po_aop_service_item_qties',function($join){
          $join->on('po_aop_service_item_qties.po_aop_service_item_id','=','po_aop_service_items.id');
          $join->whereNull('po_aop_service_item_qties.deleted_at');
        })
        ->join('sales_orders',function($join){
          $join->on('sales_orders.id','=','po_aop_service_item_qties.sales_order_id');
        })
        ->join('budget_fabric_prods',function($join){
          $join->on('budget_fabric_prods.id','=','po_aop_service_items.budget_fabric_prod_id');
        })
        ->join('budget_fabrics',function($join){
          $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->join('colors',function($join){
          $join->on('colors.id','=','po_aop_service_item_qties.fabric_color_id');
        })
        ->join('style_fabrications',function($join){
          $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->join('gmtsparts',function($join){
          $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
        })
        ->join('uoms',function($join){
          $join->on('uoms.id','=','style_fabrications.uom_id');
        })
        ->leftJoin('users',function($join){
          $join->on('users.id','=','po_aop_services.approved_by');
        })
        ->leftJoin('employee_h_rs',function($join){
          $join->on('employee_h_rs.user_id','=','users.id');
        })
        ->leftJoin('designations',function($join){
          $join->on('employee_h_rs.designation_id','=','designations.id');
        })
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        ->orderBy('po_aop_services.po_no','desc')
        ->groupBy([
          'imp_lc_pos.purchase_order_id',
          'po_aop_services.po_no',
          'po_aop_services.remarks',
          'po_aop_services.approved_by',
          'budget_fabrics.style_fabrication_id',
          'budget_fabrics.gsm_weight',
          'gmtsparts.name',
          'colors.name',
          'po_aop_service_item_qties.coverage',
          'po_aop_service_item_qties.impression',
          'po_aop_service_item_qties.embelishment_type_id',
          'uoms.code',
          'users.signature_file',
          'employee_h_rs.name',
          'designations.name',
        ])
        ->get()
        ->map(function($purchaseorder) use($desDropdown,$aoptype){
          $purchaseorder->menu_name="AOP Service";
          $purchaseorder->embelishment_type_id = isset($aoptype[$purchaseorder->embelishment_type_id])?$aoptype[$purchaseorder->embelishment_type_id]:'';
          $purchaseorder->fabric_description=$desDropdown[$purchaseorder->style_fabrication_id]?$desDropdown[$purchaseorder->style_fabrication_id]:'';
          $purchaseorder->item_description = $purchaseorder->gmtspart_name.", ".$purchaseorder->fabric_description.", Aop Type: ".$purchaseorder->embelishment_type_id.", Coverage:".$purchaseorder->coverage.", No of Color: ".$purchaseorder->impression;
          return $purchaseorder;
        });

        $datas=$purchaseorder->groupBy(['purchase_order_id']);
        $poNoArr=[];
        foreach($purchaseorder as $data){
          $poNoArr[$data->purchase_order_id]['po_no']=$data->po_no;
          $poNoArr[$data->purchase_order_id]['remarks']=$data->remarks;
          $poNoArr[$data->purchase_order_id]['approved_by']=$data->approved_by?"Approved By: ".$data->user_name:'';
          $poNoArr[$data->purchase_order_id]['approved_by_designation']=$data->user_designation?" Designation: ".$data->user_designation:'';
          $poNoArr[$data->purchase_order_id]['approved_by_signature']=$data->signature_file?'images/signature/'.$data->signature_file:null;
        }

      }
      //Dyeing Service 
      elseif ($menu_id==6) {

        $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');

        $fabricDescription=$this->budgetfabric
        ->join('budget_fabric_prods',function($join){
          $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->join('po_dyeing_service_items',function($join){
          $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
        })
        ->join('po_dyeing_services',function($join){
          $join->on('po_dyeing_service_items.po_dyeing_service_id','=','po_dyeing_services.id');
        })
        ->leftJoin('imp_lc_pos',function($join){
          $join->on('imp_lc_pos.purchase_order_id','=','po_dyeing_services.id');
        })
        ->join('style_fabrications',function($join){
          $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->join('autoyarns',function($join){
          $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
        })
        ->leftJoin('autoyarnratios',function($join){
          $join->on('autoyarns.id','=','autoyarnratios.autoyarn_id');
        })
        ->leftJoin('compositions',function($join){
          $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->leftJoin('constructions',function($join){
          $join->on('constructions.id','=','autoyarns.construction_id');
        })
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        ->get([
          'style_fabrications.id',
          'constructions.name as construction',
          'autoyarnratios.composition_id',
          'compositions.name',
          'autoyarnratios.ratio',
        ]);
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

        $purchaseorder =$this->implc
        ->selectRaw('
          imp_lc_pos.purchase_order_id,
          po_dyeing_services.po_no,
          po_dyeing_services.remarks,
          po_dyeing_services.approved_by,
          budget_fabrics.style_fabrication_id,
          budget_fabrics.gsm_weight,
          style_fabrications.dyeing_type_id,
          gmtsparts.name as gmtspart_name,
          colors.name as fabric_color,
          po_dyeing_service_item_qties.fabric_color_id,
          colorranges.name as colorrange_name,
          po_dyeing_service_item_qties.dia,
          uoms.code as uom_code,
          users.signature_file,
          employee_h_rs.name as user_name,
          designations.name as user_designation,
          sum(po_dyeing_service_item_qties.qty) as po_qty,
          avg(po_dyeing_service_item_qties.rate) as po_rate,
          sum(po_dyeing_service_item_qties.amount) as po_amount
        ')
        ->join('imp_lc_pos',function($join){
          $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
        })
        ->leftJoin('po_dyeing_services',function($join){
          $join->on('imp_lc_pos.purchase_order_id','=','po_dyeing_services.id');
        })
        ->join('po_dyeing_service_items',function($join){
          $join->on('po_dyeing_service_items.po_dyeing_service_id','=','po_dyeing_services.id');
        })
        ->join('po_dyeing_service_item_qties',function($join){
          $join->on('po_dyeing_service_item_qties.po_dyeing_service_item_id','=','po_dyeing_service_items.id');
          $join->whereNull('po_dyeing_service_item_qties.deleted_at');
        })
        ->join('sales_orders',function($join){
          $join->on('sales_orders.id','=','po_dyeing_service_item_qties.sales_order_id');
        })
        ->join('colors',function($join){
          $join->on('colors.id','=','po_dyeing_service_item_qties.fabric_color_id');
        })
        ->join('budget_fabric_prods',function($join){
          $join->on('budget_fabric_prods.id','=','po_dyeing_service_items.budget_fabric_prod_id');
        })
        ->join('budget_fabrics',function($join){
          $join->on('budget_fabrics.id','=','budget_fabric_prods.budget_fabric_id');
        })
        ->join('style_fabrications',function($join){
          $join->on('style_fabrications.id','=','budget_fabrics.style_fabrication_id');
        })
        ->join('gmtsparts',function($join){
          $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
        })
        ->leftJoin('colorranges',function($join){
          $join->on('colorranges.id','=','po_dyeing_service_item_qties.colorrange_id');
        })
        ->join('uoms',function($join){
          $join->on('uoms.id','=','style_fabrications.uom_id');
        })
        ->leftJoin('users',function($join){
          $join->on('users.id','=','po_dyeing_services.approved_by');
        })
        ->leftJoin('employee_h_rs',function($join){
          $join->on('employee_h_rs.user_id','=','users.id');
        })
        ->leftJoin('designations',function($join){
          $join->on('employee_h_rs.designation_id','=','designations.id');
        })
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        ->orderBy('po_dyeing_services.po_no','desc')
        ->groupBy([
          'imp_lc_pos.purchase_order_id',
          'po_dyeing_services.po_no',
          'po_dyeing_services.remarks',
          'po_dyeing_services.approved_by',
          'budget_fabrics.style_fabrication_id',
          'budget_fabrics.gsm_weight',
          'style_fabrications.dyeing_type_id',
          'gmtsparts.name',
          'colors.name',
          'po_dyeing_service_item_qties.fabric_color_id',
          'colorranges.name',
          'po_dyeing_service_item_qties.dia',
          'uoms.code',
          'users.signature_file',
          'employee_h_rs.name',
          'designations.name',
        ])
        ->get()
        ->map(function($purchaseorder) use($desDropdown,$dyetype){
          $purchaseorder->fabric_description=$desDropdown[$purchaseorder->style_fabrication_id]?$desDropdown[$purchaseorder->style_fabrication_id]:'';
          $purchaseorder->dyeingtype=$dyetype[$purchaseorder->dyeing_type_id]?$dyetype[$purchaseorder->dyeing_type_id]:'';
          $purchaseorder->item_description = $purchaseorder->gmtspart_name.", ".$purchaseorder->fabric_description.", GSM:".$purchaseorder->gsm_weight.", Dia:".$purchaseorder->dia.", Dye Type:".$purchaseorder->dyeingtype.", Color Range: ".$purchaseorder->colorrange_name;
          $purchaseorder->menu_name="Dyeing Service";
          return $purchaseorder;
        });

        $datas=$purchaseorder->groupBy(['purchase_order_id']);
        $poNoArr=[];
        foreach($purchaseorder as $data){
          $poNoArr[$data->purchase_order_id]['po_no']=$data->po_no;
          $poNoArr[$data->purchase_order_id]['remarks']=$data->remarks;
          $poNoArr[$data->purchase_order_id]['approved_by']=$data->approved_by?"Approved By: ".$data->user_name:'';
          $poNoArr[$data->purchase_order_id]['approved_by_designation']=$data->user_designation?" Designation: ".$data->user_designation:'';
          $poNoArr[$data->purchase_order_id]['approved_by_signature']=$data->signature_file?'images/signature/'.$data->signature_file:null;
        }

      }
      //Dyes & Chemical Purchase
      elseif ($menu_id==7) {
        $purchaseorder =$this->implc
        ->selectRaw('
          imp_lc_pos.purchase_order_id,
          po_dye_chems.po_no,
          po_dye_chems.remarks,
          po_dye_chems.approved_by,
          itemcategories.name as itemcategory,
          itemclasses.name as itemclass_name,
          item_accounts.sub_class_name,
          item_accounts.item_description,
          item_accounts.specification,
          uoms.code as uom_code,
          users.signature_file,
          employee_h_rs.name as user_name,
          designations.name as user_designation,
          sum(po_dye_chem_items.qty) as po_qty,
          avg(po_dye_chem_items.rate) as po_rate,
          sum(po_dye_chem_items.amount) as po_amount
        ')
        ->join('imp_lc_pos',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
          })
        ->join('po_dye_chems',function($join){
            $join->on('imp_lc_pos.purchase_order_id','=','po_dye_chems.id');
          })
        ->join('po_dye_chem_items', function($join){
          $join->on('po_dye_chems.id', '=', 'po_dye_chem_items.po_dye_chem_id');
        })
        ->join('inv_pur_req_items', function($join){
          $join->on('inv_pur_req_items.id', '=', 'po_dye_chem_items.inv_pur_req_item_id');
        })
        ->join('item_accounts', function($join){
          $join->on('item_accounts.id', '=', 'inv_pur_req_items.item_account_id');
        })
        ->join('itemclasses', function($join){
          $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
        })
        ->join('itemcategories', function($join){
          $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
        })
        ->leftJoin('uoms', function($join){
          $join->on('uoms.id', '=', 'item_accounts.uom_id');
        })
        ->leftJoin('users',function($join){
          $join->on('users.id','=','po_dye_chems.approved_by');
        })
        ->leftJoin('employee_h_rs',function($join){
          $join->on('employee_h_rs.user_id','=','users.id');
        })
        ->leftJoin('designations',function($join){
          $join->on('employee_h_rs.designation_id','=','designations.id');
        })
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        ->orderBy('po_dye_chems.po_no','desc')
        ->groupBy([
          'imp_lc_pos.purchase_order_id',
          'po_dye_chems.po_no',
          'po_dye_chems.remarks',
          'po_dye_chems.approved_by',
          'itemcategories.name',
          'itemclasses.name',
          'item_accounts.sub_class_name',
          'item_accounts.item_description',
          'item_accounts.specification',
          'uoms.code',
          'users.signature_file',
          'employee_h_rs.name',
          'designations.name',
        ])
        ->get()
        ->map(function($purchaseorder){
          $purchaseorder->menu_name="Dyes & Chemical";
          $purchaseorder->item_description=$purchaseorder->sub_class_name.", ".$purchaseorder->item_description.", ".$purchaseorder->specification;
          return $purchaseorder; 
        });

        $datas=$purchaseorder->groupBy(['purchase_order_id']);
        $poNoArr=[];
        foreach($purchaseorder as $data){
          $poNoArr[$data->purchase_order_id]['po_no']=$data->po_no;
          $poNoArr[$data->purchase_order_id]['remarks']=$data->remarks;
          $poNoArr[$data->purchase_order_id]['approved_by']=$data->approved_by?"Approved By: ".$data->user_name:'';
          $poNoArr[$data->purchase_order_id]['approved_by_designation']=$data->user_designation?" Designation: ".$data->user_designation:'';
          $poNoArr[$data->purchase_order_id]['approved_by_signature']=$data->signature_file?'images/signature/'.$data->signature_file:null;
        }

      }
      //General Item Purchase Order
      elseif ($menu_id==8) {
        $purchaseorder =$this->implc
        ->selectRaw('
          imp_lc_pos.purchase_order_id,
          po_generals.po_no,
          po_generals.remarks,
          po_generals.approved_by,
          itemcategories.name as itemcategory,
          itemclasses.name as itemclass_name,
          item_accounts.sub_class_name,
          item_accounts.item_description,
          item_accounts.specification,
          uoms.code as uom_code,
          po_general_items.remarks,
          users.signature_file,
          employee_h_rs.name as user_name,
          designations.name as user_designation,
          sum(po_general_items.qty) as po_qty,
          avg(po_general_items.rate) as po_rate,
          sum(po_general_items.amount) as po_amount
          ')
        ->join('imp_lc_pos',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
          })
        ->leftJoin('po_generals',function($join){
          $join->on('imp_lc_pos.purchase_order_id','=','po_generals.id');
        })
        ->join('po_general_items', function($join){
          $join->on('po_general_items.po_general_id', '=', 'po_generals.id');
        })
        ->join('inv_pur_req_items', function($join){
          $join->on('inv_pur_req_items.id', '=', 'po_general_items.inv_pur_req_item_id');
        })
        ->join('item_accounts', function($join){
          $join->on('item_accounts.id', '=', 'inv_pur_req_items.item_account_id');
        })
        ->join('itemclasses', function($join){
          $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
        })
        ->join('itemcategories', function($join){
          $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
        })
        ->leftJoin('uoms', function($join){
          $join->on('uoms.id', '=', 'item_accounts.uom_id');
        })
        ->leftJoin('users',function($join){
          $join->on('users.id','=','po_generals.approved_by');
        })
        ->leftJoin('employee_h_rs',function($join){
          $join->on('employee_h_rs.user_id','=','users.id');
        })
        ->leftJoin('designations',function($join){
          $join->on('employee_h_rs.designation_id','=','designations.id');
        })
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        ->orderBy('po_generals.po_no','desc')
        ->groupBy([
          'imp_lc_pos.purchase_order_id',
          'po_generals.po_no',
          'po_generals.remarks',
          'po_generals.approved_by',
          'itemcategories.name',
          'itemclasses.name',
          'item_accounts.sub_class_name',
          'item_accounts.item_description',
          'item_accounts.specification',
          'uoms.code',
          'po_general_items.remarks',
          'users.signature_file',
          'employee_h_rs.name',
          'designations.name',
        ])
        ->get()
        ->map(function($purchaseorder){
          $purchaseorder->menu_name="General Item";
          $purchaseorder->item_description=$purchaseorder->sub_class_name.", ".$purchaseorder->item_description.", ".$purchaseorder->specification.", ".$purchaseorder->remarks;
          return $purchaseorder;
        });

        $datas=$purchaseorder->groupBy(['purchase_order_id']);
        $poNoArr=[];
        foreach($purchaseorder as $data){
          $poNoArr[$data->purchase_order_id]['po_no']=$data->po_no;
          $poNoArr[$data->purchase_order_id]['remarks']=$data->remarks;
          $poNoArr[$data->purchase_order_id]['approved_by']=$data->approved_by?"Approved By: ".$data->user_name:'';
          $poNoArr[$data->purchase_order_id]['approved_by_designation']=$data->user_designation?" Designation: ".$data->user_designation:'';
          $poNoArr[$data->purchase_order_id]['approved_by_signature']=$data->signature_file?'images/signature/'.$data->signature_file:null;
        }

      }
      //Yarn Dyeing
      elseif ($menu_id==9) {

        $yarnDescription=$this->invyarnitem
        ->join('item_accounts',function($join){
            $join->on('item_accounts.id','=','inv_yarn_items.item_account_id'); 
        })
        ->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','inv_yarn_items.supplier_id'); 
        })
        ->leftJoin('colors',function($join){
            $join->on('colors.id','=','inv_yarn_items.color_id'); 
        })
        ->leftJoin('item_account_ratios',function($join){
            $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
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
        ->leftJoin('compositions',function($join){
            $join->on('compositions.id','=','item_account_ratios.composition_id');
        })
        ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        ->where([['itemcategories.identity','=',1]])
        ->get([
            'inv_yarn_items.id as inv_yarn_item_id',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'itemclasses.name as itemclass_name',
            'compositions.name as composition_name',
            'item_account_ratios.ratio',
        ]);
        $itemaccountArr=array();
        $yarnCompositionArr=array();
        foreach($yarnDescription as $row){
            $itemaccountArr[$row->inv_yarn_item_id]['count']=$row->count."/".$row->symbol;
            $itemaccountArr[$row->inv_yarn_item_id]['yarn_type']=$row->yarn_type;
            $itemaccountArr[$row->inv_yarn_item_id]['itemclass_name']=$row->itemclass_name;
            $yarnCompositionArr[$row->inv_yarn_item_id][]=$row->composition_name." ".$row->ratio."%";
        }
        $yarnDropdown=array();
        foreach($itemaccountArr as $key=>$value){
            $yarnDropdown[$key]=$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
        }

        $purchaseorder =$this->implc
        ->selectRaw('
            imp_lc_pos.purchase_order_id,
            po_yarn_dyeings.po_no,
            po_yarn_dyeings.remarks,
            po_yarn_dyeing_items.inv_yarn_item_id,
            colors.name as yarn_color_name,
            sum(po_yarn_dyeing_item_bom_qties.qty) as po_qty,
            avg(po_yarn_dyeing_item_bom_qties.rate) as po_rate,
            sum(po_yarn_dyeing_item_bom_qties.amount) as po_amount
          ')
        ->join('imp_lc_pos',function($join){
          $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
        })
        ->leftJoin('po_yarn_dyeings',function($join){
          $join->on('imp_lc_pos.purchase_order_id','=','po_yarn_dyeings.id');
        })
        ->join('po_yarn_dyeing_items', function($join){
          $join->on('po_yarn_dyeing_items.po_yarn_dyeing_id', '=', 'po_yarn_dyeings.id');
        })
        ->leftJoin('inv_yarn_items',function($join){
          $join->on('inv_yarn_items.id','=','po_yarn_dyeing_items.inv_yarn_item_id'); 
        })
        ->leftJoin('item_accounts',function($join){
          $join->on('item_accounts.id','=','inv_yarn_items.item_account_id'); 
        })
        ->join('po_yarn_dyeing_item_bom_qties',function($join){
          $join->on('po_yarn_dyeing_item_bom_qties.po_yarn_dyeing_item_id', '=' , 'po_yarn_dyeing_items.id');
         })
        ->join('budget_yarn_dyeing_cons',function($join){
          $join->on('budget_yarn_dyeing_cons.id', '=' , 'po_yarn_dyeing_item_bom_qties.budget_yarn_dyeing_con_id');
         })
        ->join('sales_orders',function($join){
          $join->on('budget_yarn_dyeing_cons.sales_order_id', '=' , 'sales_orders.id');
         })
        ->join('style_fabrication_stripes',function($join){
            $join->on('style_fabrication_stripes.id', '=' , 'budget_yarn_dyeing_cons.style_fabrication_stripe_id');
         })
        ->join('style_colors',function($join){
            $join->on('style_colors.id', '=' , 'style_fabrication_stripes.style_color_id');
         })
        ->join('colors',function($join){
          $join->on('colors.id','=','style_fabrication_stripes.color_id');
        })
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        ->orderBy('po_yarn_dyeings.po_no','desc')
        ->groupBy([
          'imp_lc_pos.purchase_order_id',
          'po_yarn_dyeings.po_no',
          'po_yarn_dyeings.remarks',
          'po_yarn_dyeing_items.inv_yarn_item_id',
          'colors.name'
        ])
        ->get()
        ->map(function($purchaseorder) use($yarnDropdown){
          $purchaseorder->menu_name="Yarn Dyeing";
          $purchaseorder->yarn_desc=isset($yarnDropdown[$purchaseorder->inv_yarn_item_id])?$yarnDropdown[$purchaseorder->inv_yarn_item_id]:'';
          $purchaseorder->item_description=$purchaseorder->yarn_desc.", ".$purchaseorder->yarn_color_name;
          $purchaseorder->uom_code='Kg';
          return $purchaseorder;
        });

        $datas=$purchaseorder->groupBy(['purchase_order_id']);
        $poNoArr=[];
        foreach($purchaseorder as $data){
          $poNoArr[$data->purchase_order_id]['po_no']=$data->po_no;
          $poNoArr[$data->purchase_order_id]['remarks']=$data->remarks;
          $poNoArr[$data->purchase_order_id]['approved_by']=$data->approved_by?"Approved By: ".$data->user_name:'';
          $poNoArr[$data->purchase_order_id]['approved_by_designation']=$data->user_designation?" Designation: ".$data->user_designation:'';
          $poNoArr[$data->purchase_order_id]['approved_by_signature']=$data->signature_file?'images/signature/'.$data->signature_file:null;
        }

      }
      //Embelishment Work Order
      elseif ($menu_id==10) {
        $embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');
        $purchaseorder =$this->implc
        ->selectRaw('
          imp_lc_pos.purchase_order_id,
          po_emb_services.po_no,
          po_emb_services.remarks,
          po_emb_services.approved_by,
          style_embelishments.embelishment_size_id,
          embelishments.name as embelishment_name,
          embelishment_types.name as embelishment_type,
          gmtsparts.name as gmtspart_name,
          uoms.code as uom_code,
          users.signature_file,
          employee_h_rs.name as user_name,
          designations.name as user_designation,
          sum(po_emb_service_items.qty) as po_qty,
          avg(po_emb_service_items.rate) as po_rate,
          sum(po_emb_service_items.amount) as po_amount
        ')
        ->join('imp_lc_pos',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
        })
        ->join('po_emb_services',function($join){
          $join->on('imp_lc_pos.purchase_order_id','=','po_emb_services.id');
        })
        ->join('po_emb_service_items',function($join){
          $join->on('po_emb_service_items.po_emb_service_id','=','po_emb_services.id')
        ->whereNull('po_emb_service_items.deleted_at');
        })
        ->join('budget_embs',function($join){
          $join->on('budget_embs.id','=','po_emb_service_items.budget_emb_id');
        })
        ->leftJoin('style_embelishments',function($join){
          $join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
        })
        ->leftJoin('style_gmts',function($join){
          $join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
        })
        ->leftJoin('item_accounts', function($join) {
          $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->leftJoin('uoms', function($join) {
          $join->on('uoms.id', '=', 'item_accounts.uom_id');
        })
        ->leftJoin('gmtsparts',function($join){
          $join->on('gmtsparts.id','=','style_embelishments.gmtspart_id');
        })
        ->leftJoin('embelishments',function($join){
          $join->on('embelishments.id','=','style_embelishments.embelishment_id');
        })
        ->leftJoin('embelishment_types',function($join){
          $join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
        })
        ->leftJoin('users',function($join){
          $join->on('users.id','=','po_emb_services.approved_by');
        })
        ->leftJoin('employee_h_rs',function($join){
          $join->on('employee_h_rs.user_id','=','users.id');
        })
        ->leftJoin('designations',function($join){
          $join->on('employee_h_rs.designation_id','=','designations.id');
        })
        ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
        ->orderBy('po_emb_services.po_no','desc')
        ->groupBy([
          'imp_lc_pos.purchase_order_id',
          'po_emb_services.po_no',
          'po_emb_services.remarks',
          'po_emb_services.approved_by',
          'style_embelishments.embelishment_size_id',
          'embelishments.name',
          'embelishment_types.name',
          'gmtsparts.name', 
          'uoms.code',
          'users.signature_file',
          'employee_h_rs.name',
          'designations.name',
        ])
        ->get()
        ->map(function($purchaseorder) use($embelishmentsize) {
          $purchaseorder->menu_name="Embelishment";
          $purchaseorder->embelishment_size = $embelishmentsize[$purchaseorder->embelishment_size_id];
          $purchaseorder->item_description=$purchaseorder->item_description.','.$purchaseorder->gmtspart_name.','.$purchaseorder->embelishment_name.','.$purchaseorder->embelishment_size.','.$purchaseorder->embelishment_type;
          return $purchaseorder;
        });

        $datas=$purchaseorder->groupBy(['purchase_order_id']);
        $poNoArr=[];
        foreach($purchaseorder as $data){
          $poNoArr[$data->purchase_order_id]['po_no']=$data->po_no;
          $poNoArr[$data->purchase_order_id]['remarks']=$data->remarks;
          $poNoArr[$data->purchase_order_id]['approved_by']=$data->approved_by?"Approved By: ".$data->user_name:'';
          $poNoArr[$data->purchase_order_id]['approved_by_designation']=$data->user_designation?" Designation: ".$data->user_designation:'';
          $poNoArr[$data->purchase_order_id]['approved_by_signature']=$data->signature_file?'images/signature/'.$data->signature_file:null;
        }

      }
      //General Service Work Order
      elseif ($menu_id==11) {
        $purchaseorder = $this->implc
          ->selectRaw('
            imp_lc_pos.purchase_order_id,
            po_general_services.po_no,
            po_general_services.remarks,
            po_general_services.approved_by,
            po_general_service_items.service_description,
            uoms.code as uom_code,
            users.signature_file,
            employee_h_rs.name as user_name,
            designations.name as user_designation,
            po_general_service_items.qty as po_qty,
            po_general_service_items.rate as po_rate,
            po_general_service_items.amount as po_amount
          ')
          ->join('imp_lc_pos',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
          })
          ->join('po_general_services',function($join){
            $join->on('imp_lc_pos.purchase_order_id','=','po_general_services.id');
          })
          ->join('po_general_service_items',function($join){
            $join->on('po_general_services.id','=','po_general_service_items.po_general_service_id')
            ->whereNull('po_general_service_items.deleted_at');
          })
          ->leftJoin('departments', function($join){
            $join->on('departments.id', '=', 'po_general_service_items.department_id');
          })
          ->leftJoin('asset_quantity_costs', function($join){
            $join->on('asset_quantity_costs.id', '=', 'po_general_service_items.asset_quantity_cost_id');
          })
          ->leftJoin('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
          })
          ->leftJoin('uoms', function($join){
            $join->on('uoms.id', '=', 'po_general_service_items.uom_id');
          })
          ->leftJoin('users',function($join){
            $join->on('users.id','=','po_general_services.approved_by');
          })
          ->leftJoin('employee_h_rs',function($join){
            $join->on('employee_h_rs.user_id','=','users.id');
          })
          ->leftJoin('designations',function($join){
            $join->on('employee_h_rs.designation_id','=','designations.id');
          })
          ->where([['imp_lc_pos.imp_lc_id','=',$implc->id]])
          ->orderBy('po_general_services.po_no','desc')
          ->get()
          ->map(function($purchaseorder){
            $purchaseorder->menu_name="General Service";
            $purchaseorder->item_description=$purchaseorder->service_description;
            return $purchaseorder;
          });

        $datas=$purchaseorder->groupBy(['purchase_order_id']);
        $poNoArr=[];
        foreach($purchaseorder as $data){
          $poNoArr[$data->purchase_order_id]['po_no']=$data->po_no;
          $poNoArr[$data->purchase_order_id]['remarks']=$data->remarks;
          $poNoArr[$data->purchase_order_id]['approved_by']=$data->approved_by?"Approved By: ".$data->user_name:'';
          $poNoArr[$data->purchase_order_id]['approved_by_designation']=$data->user_designation?" Designation: ".$data->user_designation:'';
          $poNoArr[$data->purchase_order_id]['approved_by_signature']=$data->signature_file?'images/signature/'.$data->signature_file:null;
        }

      }

      $amount=$rows->lc_amount;
      $inword=Numbertowords::ntow(number_format($amount,2,'.',''),$rows->currency_code,'cents only');
      $rows->inword=$inword;

      $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetPrintHeader(false);
      $pdf->SetPrintFooter(false);
      $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->SetMargins(10, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->AddPage();

      $pdf->SetFont('helvetica', 'N', 7);
      $view= \View::make('Defult.Approval.ImpLcApprovalPdf',['rows'=>$rows,'datas'=>$datas,'poNoArr'=>$poNoArr]);
      $html_content=$view->render();
      $pdf->SetY(5);
      $pdf->WriteHtml($html_content, true, false,true,false,'');
      $filename = storage_path() . '/ImpLcApprovalPdf.pdf';
      $pdf->output($filename,'I');
      exit();
  }

}
