<?php
namespace App\Http\Controllers\Planing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Planing\TnaOrdRepository;
use App\Repositories\Contracts\Util\TnataskRepository;
use App\Library\Template;
use App\Http\Requests\Planing\TnaOrdRequest;

class TnaActualController extends Controller {

    private $tnatask;
    private $tnaord;
    private $company;
    private $location;
    private $buyer;

    public function __construct(
        TnataskRepository $tnatask,
        TnaOrdRepository $tnaord,
        CompanyRepository $company,
        LocationRepository $location,
        BuyerRepository $buyer
    ) {
        $this->tnatask = $tnatask;
        $this->tnaord = $tnaord;
        $this->company = $company;
        $this->location = $location;
        $this->buyer = $buyer;

        $this->middleware('auth');
        // $this->middleware('permission:view.tnaactuals',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.tnaactuals', ['only' => ['store']]);
        // $this->middleware('permission:edit.tnaactuals',   ['only' => ['update']]);
        // $this->middleware('permission:delete.tnaactuals', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $salesorder=$this->tnatask
        ->join('tna_ords',function($join){
            $join->on('tna_ords.tna_task_id','=','tnatasks.tna_task_id');
        })
        ->join('sales_orders',function($join){
            $join->on('sales_orders.id', '=' , 'tna_ords.sales_order_id');
        })
        ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'jobs.company_id');
        })
        ->leftJoin('companies as produced_company', function($join)  {
            $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
        })
        ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('buyers', function($join)  {
            $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->where([['tnatasks.is_auto_completion','=',0]])
        ->whereNotNull('tna_ords.acl_start_date')
        ->get([
            'tna_ords.id',
            'tna_ords.tna_task_id',
            'tna_ords.tna_start_date',
            'tna_ords.tna_end_date',
            'tna_ords.acl_start_date',
            'tna_ords.acl_end_date',
            'tnatasks.task_name',
            'sales_orders.sale_order_no',
            'sales_orders.ship_date',
            'styles.style_ref',
            'jobs.job_no',
            'buyers.name as buyer_name',
            'companies.name as company_name',
            'produced_company.name as produced_company_name',
        ])
        ->map(function($salesorder){
            $salesorder->ship_date=date('Y-m-d',strtotime($salesorder->ship_date));
            $salesorder->tna_start_date=($salesorder->tna_start_date)?date('Y-m-d',strtotime($salesorder->tna_start_date)):'--';
            $salesorder->tna_end_date=($salesorder->tna_end_date)?date('Y-m-d',strtotime($salesorder->tna_end_date)):'--';
            $salesorder->acl_start_date=date('Y-m-d',strtotime($salesorder->acl_start_date));
            $salesorder->acl_end_date=($salesorder->acl_end_date)?date('Y-m-d',strtotime($salesorder->acl_end_date)):'--';
            return $salesorder;
        });

        echo json_encode($salesorder);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        return Template::loadView("Planing.TnaActual",['company'=>$company,'buyer'=>$buyer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TnaOrdRequest $request) {
        $id = $this->tnaord->find($request->id);
        $tnaord = $this->tnaord->where([['id','=',$id]])->update([
            'acl_start_date'=>$request->acl_start_date,
            'acl_end_date'=>$request->acl_end_date,
        ]);
        if ($tnaord) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
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
        $tnaord = $this->tnaord
        ->join('tnatasks',function($join){
            $join->on('tna_ords.tna_task_id','=','tnatasks.tna_task_id');
        })
        ->join('sales_orders',function($join){
            $join->on('sales_orders.id', '=' , 'tna_ords.sales_order_id');
         })
         ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
         })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'jobs.company_id');
         })
        ->leftJoin('companies as produced_company', function($join)  {
            $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
        })
        ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
         })
        ->join('buyers', function($join)  {
            $join->on('buyers.id', '=', 'styles.buyer_id');
         }) 
        ->where([['tna_ords.id','=',$id]])
        ->get([
            'tna_ords.*',
            'tnatasks.task_name',
            'sales_orders.sale_order_no',
            'sales_orders.ship_date',
            'styles.style_ref',
            'jobs.job_no',
            'buyers.name as buyer_name',
            'companies.name as company_name',
            'produced_company.name as produced_company_name',
        ])
        ->first();
        $row ['fromData'] = $tnaord;
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
    public function update(TnaOrdRequest $request, $id) {
        $tnaord = $this->tnaord->update($id, [
            'acl_start_date'=>$request->acl_start_date,
            'acl_end_date'=>$request->acl_end_date,
        ]);
        if ($tnaord) {
            //dd($tnaord);die;
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if ($this->tnaord->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

    public function getSalesOrder(){
        $salesorder=$this->tnatask
        ->join('tna_ords',function($join){
            $join->on('tna_ords.tna_task_id','=','tnatasks.tna_task_id');
        })
        ->join('sales_orders',function($join){
            $join->on('sales_orders.id', '=' , 'tna_ords.sales_order_id');
         })
        ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
         })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'jobs.company_id');
         })
        ->leftJoin('companies as produced_company', function($join)  {
            $join->on('produced_company.id', '=', 'sales_orders.produced_company_id');
        })
        ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
         })
        ->join('buyers', function($join)  {
            $join->on('buyers.id', '=', 'styles.buyer_id');
         }) 
        ->when(request('style_ref'), function ($q) {
            return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
         })
        ->when(request('job_no'), function ($q) {
            return $q->where('jobs.job_no', 'LIKE', "%".request('job_no', 0)."%");
         })
        ->when(request('sale_order_no'), function ($q) {
            return $q->where('sales_orders.sale_order_no', 'LIKE', "%".request('sale_order_no', 0)."%");
         })
        ->when(request('buyer_id'), function ($q) {
            return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
        })
        ->when(request('date_from'), function ($q) {
            return $q->where('sales_orders.ship_date', '>=',request('date_from', 0));
        })
        ->when(request('date_to'), function ($q) {
            return $q->where('sales_orders.ship_date', '<=',request('date_to', 0));
        })
        ->where([['tnatasks.is_auto_completion','=',0]])
        ->get([
            'tna_ords.*',
            // 'tna_ords.tna_task_id',
            // 'tna_ords.tna_start_date',
            // 'tna_ords.tna_end_date',
            // 'tna_ords.sales_order_id',
            'tnatasks.task_name',
            'sales_orders.sale_order_no',
            'sales_orders.ship_date',
            'styles.style_ref',
            'jobs.job_no',
            'buyers.name as buyer_name',
            'companies.name as company_name',
            'produced_company.name as produced_company_name',
        ])
        ->map(function($salesorder){
            $salesorder->ship_date=date('Y-m-d',strtotime($salesorder->ship_date));
            $salesorder->tna_start_date=($salesorder->tna_start_date)?date('Y-m-d',strtotime($salesorder->tna_start_date)):'--';
            $salesorder->tna_end_date=($salesorder->tna_end_date)?date('Y-m-d',strtotime($salesorder->tna_end_date)):'--';
            return $salesorder;
        });

        echo json_encode($salesorder);
    }

}
