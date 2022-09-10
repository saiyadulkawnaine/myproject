<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Subcontract\Kniting\RqYarnRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Sms;
class RqYarnApprovalController extends Controller
{
    private $rqyarn;
    private $user;
    private $supplier;
    private $company;

    public function __construct(
		RqYarnRepository $rqyarn,
		UserRepository $user,
		SupplierRepository $supplier,
		CompanyRepository $company

    ) {
        $this->rqyarn = $rqyarn;
        $this->user = $user;
        $this->supplier = $supplier;
        $this->company = $company;
        $this->middleware('auth');
       $this->middleware('permission:approve.rqyarns',   ['only' => ['approved', 'index','reportData','reportDataApp','unapproved']]);

    }

    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        return Template::loadView('Approval.RqYarnApproval',['company'=>$company,'supplier'=>$supplier]);
    }

	public function reportData() {
        $menu=array_prepend(config('bprs.menu'),'-Select-','');

        $rows=$this->rqyarn
        ->join('companies',function($join){
            $join->on('companies.id','=','rq_yarns.company_id');
        })
        ->join('suppliers',function($join){
            $join->on('suppliers.id','=','rq_yarns.supplier_id');
        })
        ->leftJoin(\DB::raw("(
            select
            rq_yarns.id as rq_yarn_id,
            sum(rq_yarn_items.qty) as yarn_qty
            from
            rq_yarns
            join rq_yarn_fabrications on rq_yarn_fabrications.rq_yarn_id=rq_yarns.id
            join rq_yarn_items on rq_yarn_items.rq_yarn_fabrication_id=rq_yarn_fabrications.id
            where rq_yarn_items.deleted_at is null
            and rq_yarn_fabrications.deleted_at is null
            group by
            rq_yarns.id
        ) rqyarnqty"), "rqyarnqty.rq_yarn_id", "=", "rq_yarns.id")
        ->when(request('company_id'), function ($q) {
            return $q->where('rq_yarns.company_id', '=',request('company_id', 0));
        })
        ->when(request('supplier_id'), function ($q) {
            return $q->where('rq_yarns.supplier_id', '=',request('supplier_id', 0));
        })
        ->when(request('date_from'), function ($q) {
            return $q->where('rq_yarns.rq_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
            return $q->where('rq_yarns.rq_date', '<=',request('date_to', 0));
        })
        ->where([['rq_yarns.ready_to_approve_id','=',1]])
        ->whereNull('rq_yarns.approved_at')
        ->orderBy('rq_yarns.id','desc')
        ->get([
            'rq_yarns.*',
            'companies.name as company_name',
            'suppliers.name as supplier_name',
            'rqyarnqty.yarn_qty'
        ])
        ->map(function($rows)use($menu){
            $rows->basis_name=$menu[$rows->rq_against_id];
            $rows->yarn_qty=number_format($rows->yarn_qty,2);
            $rows->rq_date=date('d-M-Y',strtotime($rows->rq_date));
            return $rows;
        });
        echo json_encode($rows);
    }

    public function approved (Request $request)
    {
    	$id=request('id',0);
    	$master=$this->rqyarn->find($id);
		$user = \Auth::user();
		$approved_at=date('Y-m-d h:i:s');

        $master->approved_by=$user->id;
        $master->approved_at=$approved_at;
        $master->unapproved_by=NULL;
        $master->unapproved_at=NULL;
        $master->timestamps=false;
        $rqyarn=$master->save();
		

		if($rqyarn){
            $rows=$this->rqyarn
            ->join('companies',function($join){
                $join->on('companies.id','=','rq_yarns.company_id');
            })
            ->join('suppliers',function($join){
                $join->on('suppliers.id','=','rq_yarns.supplier_id');
            })
            ->leftJoin('users',function($join){
                $join->on('users.id','=','rq_yarns.approved_by');
            })
            ->leftJoin(\DB::raw("(
                select
                rq_yarns.id as rq_yarn_id,
                sum(rq_yarn_items.qty) as yarn_qty
                from
                rq_yarns
                join rq_yarn_fabrications on rq_yarn_fabrications.rq_yarn_id=rq_yarns.id
                join rq_yarn_items on rq_yarn_items.rq_yarn_fabrication_id=rq_yarn_fabrications.id
                where rq_yarn_items.deleted_at is null
                and rq_yarn_fabrications.deleted_at is null
                group by
                rq_yarns.id
            ) rqyarnqty"), "rqyarnqty.rq_yarn_id", "=", "rq_yarns.id")
            ->where([['rq_yarns.id','=',$id]])
            ->get([
                'rq_yarns.*',
                'companies.name as company_nane',
                'suppliers.name as supplier_name',
                'users.name as approved_user',
                'rqyarnqty.yarn_qty',
            ])
            ->first();

            $title ='YARN ISSUE REQUISITION APPROVED';
            $text = 
            $title."\n".
            'Requision No:'.$rows->rq_no."\n".
            'Prod.Company:'.$rows->company_nane."\n".
            'Knit Company:'.$rows->supplier_name."\n".
            'Yarn Qty:'.$rows->yarn_qty."\n".
            'Approved By:'.$rows->approved_user;

            $userContact=collect(\DB::select("
                select
                permissions.id as permission_id,
                employee_h_rs.contact
                from
                permissions
                join permission_role on permission_role.permission_id=permissions.id
                join roles on roles.id = permission_role.role_id
                join role_user on role_user.role_id = roles.id
                join users on users.id=role_user.user_id
                join employee_h_rs on employee_h_rs.user_id=users.id
                where permissions.id=1347 
                and users.id not in (363,123)
                group by
                permissions.id,
                employee_h_rs.contact
            "));

            //(Yarn Requisition)view.rqyarns  permission ID=1834
            //(Yarn Issue)view.invyarnisu  permission ID=1346
            //(Yarn Issue)create.invyarnisu  permission ID=1347

           $approveduserArr=[];
           foreach ($userContact as $data) {
               $approveduserArr[1347][]='88'.$data->contact;
           }

           $contact=implode(',',$approveduserArr[1347]);
          // dd($contact);die;

           $sms=Sms::send_sms($text, $contact);
            return response()->json(array('success' => true, 'sms'=>$sms, 'message' => 'Approved Successfully'), 200);
        }
    }

    public function reportDataApp() {
        $menu=array_prepend(config('bprs.menu'),'-Select-','');
        $rows=$this->rqyarn
        ->join('companies',function($join){
            $join->on('companies.id','=','rq_yarns.company_id');
        })
        ->join('suppliers',function($join){
            $join->on('suppliers.id','=','rq_yarns.supplier_id');
        })
        ->leftJoin(\DB::raw("(
            select
            rq_yarns.id as rq_yarn_id,
            sum(rq_yarn_items.qty) as yarn_qty
            from
            rq_yarns
            join rq_yarn_fabrications on rq_yarn_fabrications.rq_yarn_id=rq_yarns.id
            join rq_yarn_items on rq_yarn_items.rq_yarn_fabrication_id=rq_yarn_fabrications.id
            where rq_yarn_items.deleted_at is null
            and rq_yarn_fabrications.deleted_at is null
            group by
            rq_yarns.id
        ) rqyarnqty"), "rqyarnqty.rq_yarn_id", "=", "rq_yarns.id")
        ->when(request('company_id'), function ($q) {
            return $q->where('rq_yarns.company_id', '=',request('company_id', 0));
        })
        ->when(request('supplier_id'), function ($q) {
            return $q->where('rq_yarns.supplier_id', '=',request('supplier_id', 0));
        })
        ->when(request('date_from'), function ($q) {
            return $q->where('rq_yarns.rq_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
            return $q->where('rq_yarns.rq_date', '<=',request('date_to', 0));
        })
        ->where([['rq_yarns.ready_to_approve_id','=',1]])
        ->whereNotNull('rq_yarns.approved_at')
        ->orderBy('rq_yarns.id','desc')
        ->get([
            'rq_yarns.*',
            'companies.name as company_name',
            'suppliers.name as supplier_name',
            'rqyarnqty.yarn_qty'
        ])
        ->map(function($rows)use($menu){
            $rows->basis_name=$menu[$rows->rq_against_id];
            $rows->yarn_qty=number_format($rows->yarn_qty,2);
            $rows->rq_date=date('d-M-Y',strtotime($rows->rq_date));
            return $rows;
        });
        echo json_encode($rows);
    }

    public function unapproved (Request $request)
    {
        $id=request('id',0);
        $master=$this->rqyarn->find($id);
        $user = \Auth::user();
        $unapproved_at=date('Y-m-d h:i:s');
        $unapproved_count=$master->unapproved_count+1;
        $master->approved_by=NUll;
        $master->approved_at=NUll;
        $master->unapproved_by=$user->id;
        $master->unapproved_at=$unapproved_at;
        $master->unapproved_count=$unapproved_count;
        $master->timestamps=false;
        $rqyarn=$master->save();


        if($rqyarn){
        return response()->json(array('success' => true,  'message' => 'Unapproved Successfully'), 200);
        }
    }
}
