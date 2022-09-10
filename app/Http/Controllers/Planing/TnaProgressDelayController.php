<?php
namespace App\Http\Controllers\Planing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Planing\TnaOrdRepository;
use App\Repositories\Contracts\Planing\TnaProgressDelayRepository;
use App\Repositories\Contracts\Util\TnataskRepository;
use App\Library\Template;
use App\Http\Requests\Planing\TnaProgressDelayRequest;

class TnaProgressDelayController extends Controller {

    private $tnaprogressdelay;
    private $tnatask;
    private $tnaord;
    private $company;
    private $location;
    private $buyer;

    public function __construct(
        TnaProgressDelayRepository $tnaprogressdelay,
        TnataskRepository $tnatask,
        TnaOrdRepository $tnaord,
        CompanyRepository $company,
        LocationRepository $location,
        DesignationRepository $designation,
        DepartmentRepository $department,
        BuyerRepository $buyer
    ) {
        $this->tnaprogressdelay = $tnaprogressdelay;
        $this->designation = $designation;
        $this->department = $department;
        $this->tnatask = $tnatask;
        $this->tnaord = $tnaord;
        $this->company = $company;
        $this->location = $location;
        $this->buyer = $buyer;

        $this->middleware('auth');
        // $this->middleware('permission:view.tnaprogressdelays',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.tnaprogressdelays', ['only' => ['store']]);
        // $this->middleware('permission:edit.tnaprogressdelays',   ['only' => ['update']]);
        // $this->middleware('permission:delete.tnaprogressdelays', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $salesorder=$this->tnaprogressdelay
        ->join('tna_ords',function($join){
            $join->on('tna_ords.id','=','tna_progress_delays.tna_ord_id');
        })
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
        ->get([
            'tna_progress_delays.*',
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
            'companies.code as company_code',
            'produced_company.name as produced_company_name',
        ])
        ->map(function($salesorder){
            $salesorder->ship_date=date('Y-m-d',strtotime($salesorder->ship_date));
            $salesorder->tna_start_date=($salesorder->tna_start_date)?date('Y-m-d',strtotime($salesorder->tna_start_date)):'--';
            $salesorder->tna_end_date=($salesorder->tna_end_date)?date('Y-m-d',strtotime($salesorder->tna_end_date)):'--';
            $salesorder->acl_start_date=$salesorder->acl_start_date?date('Y-m-d',strtotime($salesorder->acl_start_date)):'--';
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
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'','');
        return Template::loadView("Planing.TnaProgressDelay",['company'=>$company,'buyer'=>$buyer,'designation'=>$designation,'department'=>$department]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TnaProgressDelayRequest $request) {
        $tnaprogressdelay = $this->tnaprogressdelay->create([
            'tna_ord_id'=>$request->tna_ord_id,
        ]);
        if ($tnaprogressdelay) {
            return response()->json(array('success' => true, 'id' => $request->id, 'message' => 'Save Successfully'), 200);
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
        $tnaprogressdelay = $this->tnaprogressdelay
        ->join('tna_ords',function($join){
            $join->on('tna_ords.id','=','tna_progress_delays.tna_ord_id');
        })
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
        ->where([['tna_progress_delays.id','=',$id]])
        ->get([
            'tna_progress_delays.*',
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
            'companies.code as company_code',
            'produced_company.name as produced_company_name',
        ])
        ->first();
        $row ['fromData'] = $tnaprogressdelay;
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
    public function update(TnaProgressDelayRequest $request, $id) {
        $tnaprogressdelay = $this->tnaprogressdelay->update($id, [
            'tna_ord_id'=>$request->tna_ord_id,
        ]);
        if ($tnaprogressdelay) {
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
        if ($this->tnaprogressdelay->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

    public function getTnaSalesOrder(){
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
        ->map(function($salesorder){
            $salesorder->ship_date=date('Y-m-d',strtotime($salesorder->ship_date));
            $salesorder->tna_start_date=($salesorder->tna_start_date)?date('Y-m-d',strtotime($salesorder->tna_start_date)):'--';
            $salesorder->tna_end_date=($salesorder->tna_end_date)?date('Y-m-d',strtotime($salesorder->tna_end_date)):'--';
            return $salesorder;
        });

        echo json_encode($salesorder);
    }

}