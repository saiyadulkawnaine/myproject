<?php

namespace App\Http\Controllers\Report\Renewal;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\HRM\RenewalEntryRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\Renewal\RenewalItemRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\CgroupRepository;
class RenewalReportController extends Controller {

    private $renewalentry;
    private $renewalitem;
    private $user;
    private $company;
    private $cgroup;

    
    public function __construct(RenewalEntryRepository $renewalentry,RenewalItemRepository $renewalitem, UserRepository $user,CompanyRepository $company,CgroupRepository $cgroup) {
        
        $this->renewalentry = $renewalentry;
        $this->renewalitem = $renewalitem;
        $this->user = $user;
        $this->company = $company;
        $this->cgroup = $cgroup;
		$this->middleware('auth');

		//$this->middleware('permission:view.employeelists',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		// $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        //$department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        //$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        //$yesno = config('bprs.yesno');
        //$renewalitems=$renewalitem->groupBy(['renewal_item_id','company_id','entry_id']);
      return Template::loadView('Report.Renewal.RenewalReport',[]);
	 }
	 
     public function getData(){
        
        // $id=request('id', 0);
         $cgroup=$this->cgroup
         ->where([['id','=',1]])
         ->get([
             'name as company_name',
             'address as company_address'
             ])
         ->first();

         $renewalitem=$this->renewalitem
         ->leftJoin('renewal_entries',function($join){
             $join->on('renewal_items.id','=','renewal_entries.renewal_item_id');
         })
         ->leftJoin('companies',function($join){
             $join->on('companies.id','=','renewal_entries.company_id');
         })
         ->when(request('validity_end'), function ($q) {
            return $q->where('renewal_entries.validity_end', '>=',request('date_from', 0));
            })
         ->get([
             'renewal_items.id as renewal_item_id',
             'renewal_items.renewal_item',
             'renewal_entries.id as entry_id',
             'renewal_entries.company_id',
             'renewal_entries.validity_end',
             'renewal_entries.applied_date',
             'renewal_entries.renewed_date',
             'renewal_entries.remarks',
             'companies.name as company_name'
         ])
         ->map(function($renewalitem) {
             $current_year=date('Y') ;
             $current_monthDay=date('d-M',strtotime($renewalitem->validity_end));
             $renewalitem->expire_date=$current_monthDay.'-'.$current_year;
             //$renewalitem->whereDate('date', '<=', '2014-07-10');
 
             $expired=strtotime($renewalitem->expire_date);
             $validityEnd=strtotime($renewalitem->validity_end);
             $renewalitem->exp=$expired;
             $renewalitem->validend=$validityEnd;
 
             if($expired>$validityEnd && $validityEnd != ''){
                 $renewalitem->exp="background-color:red";
             }
 
             if($renewalitem->renewed_date){
                 $renewalitem->status=" Renewed ";
             }
             if(!$renewalitem->renewed_date && $renewalitem->applied_date){
                 $renewalitem->status=" Applied ";
             }
             if(!$renewalitem->renewed_date && !$renewalitem->applied_date){
                 $renewalitem->status=" Not Applied ";
             }
 
             return $renewalitem;
         });
 
         $itemNameArr=array();
         $companyArr=array();
        // $docArr=array();
         $data=array();
         foreach($renewalitem as $rows){
            $itemNameArr[$rows->renewal_item_id]['renewal_item'] = $rows->renewal_item;
            $companyArr[$rows->company_id]=$rows->company_id;
            $data[$rows->renewal_item_id][$rows->company_id]['expire_date']=$rows->expire_date;
            $data[$rows->renewal_item_id][$rows->company_id]['exp']=$rows->exp;
            $data[$rows->renewal_item_id][$rows->company_id]['validend']=$rows->validend;
            $data[$rows->renewal_item_id][$rows->company_id]['status']=$rows->status;
            $data[$rows->renewal_item_id][$rows->company_id]['remarks']=$rows->remarks;
         }
 
 
         $renewalitems=$renewalitem->groupBy(['renewal_item_id','company_id','entry_id']);
         return Template::loadView('Report.Renewal.RenewalEntryData',['renewalitems'=>$renewalitems, 'cgroup'=>$cgroup,'itemNameArr'=>$itemNameArr,'companyArr'=>$companyArr,'data'=>$data]);
     }
 
     public function getRenewEntryRemarks(){
        $company_id=request('company_id', 0);
        $renewal_item_id=request('renewal_item_id', 0);

         $renewalentry=$this->renewalentry
         ->leftJoin('renewal_items',function($join){
            $join->on('renewal_items.id','=','renewal_entries.renewal_item_id');
        })
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','renewal_entries.company_id');
        })
        ->when(request('company_id'), function ($q) use($company_id) {
            return $q->where('renewal_entries.company_id', '=', $company_id );
        })
        ->when(request('renewal_item_id'), function ($q) use($renewal_item_id) {
            return $q->where('renewal_entries.renewal_item_id', '=',$renewal_item_id);
        })
        ->get([
            'renewal_entries.id as renewal_item_id',
            'renewal_entries.id as entry_id',
            'renewal_entries.company_id',
            'renewal_entries.remarks',
        ])
        ->map(function($renewalentry){
            $renewalentry->remarks=nl2br(e($renewalentry->remarks));
            return $renewalentry;
        });
         /* $remarks=array();
         foreach($renewalentry as $rows){
            $remarks[$rows->renewal_item_id][$rows->company_id]['remarks']=$rows->remarks;
         } */
         echo json_encode($renewalentry);
        // return Template::loadView('Report.Renewal.RenewalRemarks',['renewalentry'=>$renewalentry/* ,'remarks'=>$remarks */]);
     }

}
