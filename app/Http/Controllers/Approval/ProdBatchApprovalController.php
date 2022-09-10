<?php

namespace App\Http\Controllers\Approval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Sms;
class ProdBatchApprovalController extends Controller
{
    private $prodbatch;
    private $user;
    private $buyer;
    private $company;

    public function __construct(
		ProdBatchRepository $prodbatch,
		UserRepository $user,
		BuyerRepository $buyer,
		CompanyRepository $company

    ) {
        $this->prodbatch = $prodbatch;
        $this->user = $user;
        $this->buyer = $buyer;
        $this->company = $company;
        $this->middleware('auth');
        $this->middleware('permission:approve.prodbatches',   ['only' => ['approved', 'index','reportData']]);

    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        return Template::loadView('Approval.ProdBatchApproval',['company'=>$company,'buyer'=>$buyer,'batchfor'=>$batchfor]);
    }
	public function reportData() {
        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $rows=$this->prodbatch
        ->join('companies',function($join){
            $join->on('companies.id','=','prod_batches.company_id');
        })
        ->join('colors',function($join){
            $join->on('colors.id','=','prod_batches.fabric_color_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_batches.machine_id');
        })
        ->leftJoin('colorranges',function($join){
            $join->on('colorranges.id','=','prod_batches.colorrange_id');
        })
         ->leftJoin(\DB::raw("(
            select
            prod_batches.id,
            prod_batches.batch_no
            from prod_batches
            where prod_batches.root_batch_id is  null
        ) rootbatches"),"rootbatches.id","=","prod_batches.root_batch_id")

        ->when(request('company_id'), function ($q) {
        return $q->where('prod_batches.company_id', '=',request('company_id', 0));
        })
        ->when(request('batch_for'), function ($q) {
        return $q->where('prod_batches.batch_for', '=',request('batch_for', 0));
        })

        ->when(request('date_from'), function ($q) {
        return $q->where('prod_batches.batch_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
        return $q->where('prod_batches.batch_date', '<=',request('date_to', 0));
        })
        ->whereNull('prod_batches.approved_at')
        ->orderBy('prod_batches.id','desc')
        ->get([
            'prod_batches.*',
            'companies.code as company_code',
            'colors.name as color_name',
            'asset_quantity_costs.custom_no as machine_no',
            'colorranges.name as color_range_name',
            'rootbatches.batch_no as root_batch_no',
        ])
        ->map(function($rows) use($batchfor){
            $rows->batch_for=$rows->batch_for?$batchfor[$rows->batch_for]:'';
            $rows->batch_date=date('Y-m-d',strtotime($rows->batch_date));
            return $rows;
        });
        echo json_encode($rows);
		/*return response()->json(
			$this->prodbatch
			->leftJoin('buyers', function($join)  {
			$join->on('so_knit_dlvs.buyer_id', '=', 'buyers.id');
			})
			->leftJoin('companies', function($join)  {
			$join->on('so_knit_dlvs.company_id', '=', 'companies.id');
			})
			->when(request('company_id'), function ($q) {
			return $q->where('so_knit_dlvs.company_id', '=',request('company_id', 0));
			})
			->when(request('buyer_id'), function ($q) {
			return $q->where('so_knit_dlvs.buyer_id', '=',request('buyer_id', 0));
			})

			->when(request('date_from'), function ($q) {
			return $q->where('so_knit_dlvs.issue_date', '>=',request('date_from', 0));
			})
			->when(request('date_to'), function ($q) {
			return $q->where('so_knit_dlvs.issue_date', '<=',request('date_to', 0));
			})
			->whereNull('so_knit_dlvs.approved_at')
			->orderBy('so_knit_dlvs.id','desc')
			->get([
			'so_knit_dlvs.*',
			'buyers.name as buyer_id',
			'companies.name as company_id'
			])
			->map(function($rows){
			$rows->issue_date=date('d-M-Y',strtotime($rows->issue_date));
			return $rows;
			})
        );*/
    }

    public function approved (Request $request)
    {
    	$id=request('id',0);
    	$master=$this->prodbatch->find($id);
		$user = \Auth::user();
		$approved_at=date('Y-m-d h:i:s');
		$prodbatch = $this->prodbatch->update($id,[
			'approved_by' => $user->id,  
			'approved_at' =>  $approved_at
		]);

		if($prodbatch){
		return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
		}
    }

    public function reportDataApp() {
        $batchfor=array_prepend(config('bprs.batchfor'),'-Select-','');
        $rows=$this->prodbatch
        ->join('companies',function($join){
            $join->on('companies.id','=','prod_batches.company_id');
        })
        ->join('colors',function($join){
            $join->on('colors.id','=','prod_batches.fabric_color_id');
        })
        ->leftJoin('asset_quantity_costs',function($join){
            $join->on('asset_quantity_costs.id','=','prod_batches.machine_id');
        })
        ->leftJoin('colorranges',function($join){
            $join->on('colorranges.id','=','prod_batches.colorrange_id');
        })
         ->leftJoin(\DB::raw("(
            select
            prod_batches.id,
            prod_batches.batch_no
            from prod_batches
            where prod_batches.root_batch_id is  null
        ) rootbatches"),"rootbatches.id","=","prod_batches.root_batch_id")

        ->when(request('company_id'), function ($q) {
        return $q->where('prod_batches.company_id', '=',request('company_id', 0));
        })
        ->when(request('batch_for'), function ($q) {
        return $q->where('prod_batches.batch_for', '=',request('batch_for', 0));
        })

        ->when(request('date_from'), function ($q) {
        return $q->where('prod_batches.batch_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
        return $q->where('prod_batches.batch_date', '<=',request('date_to', 0));
        })
        ->whereNotNull('prod_batches.approved_at')
        ->orderBy('prod_batches.id','desc')
        ->get([
            'prod_batches.*',
            'companies.code as company_code',
            'colors.name as color_name',
            'asset_quantity_costs.custom_no as machine_no',
            'colorranges.name as color_range_name',
            'rootbatches.batch_no as root_batch_no',
        ])
        ->map(function($rows) use($batchfor){
            $rows->batch_for=$rows->batch_for?$batchfor[$rows->batch_for]:'';
            $rows->batch_date=date('Y-m-d',strtotime($rows->batch_date));
            return $rows;
        });
        echo json_encode($rows);
    }

    public function unapproved (Request $request)
    {
        $id=request('id',0);
        $master=$this->prodbatch->find($id);
        $user = \Auth::user();
        $unapproved_at=date('Y-m-d h:i:s');
        $unapproved_count=$master->unapproved_count+1;
        $master->approved_by=NUll;
        $master->approved_at=NUll;
        $master->unapproved_by=$user->id;
        $master->unapproved_at=$unapproved_at;
        $master->unapproved_count=$unapproved_count;
        $master->timestamps=false;
        $prodbatch=$master->save();

        if($prodbatch){
            return response()->json(array('success' => true,  'message' => 'Approved Successfully'), 200);
        }
    }
}
