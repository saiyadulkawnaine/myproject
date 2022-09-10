<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccBepRepository;
use App\Repositories\Contracts\Account\AccBepEntryRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\ProfitcenterRepository;
use App\Library\Template;
use App\Http\Requests\Account\AccBepRequest;

class AccBepController extends Controller {

    private $accbep;
    private $accchartctrlhead;
    private $profitcenter;

    public function __construct(AccBepRepository $accbep, AccBepEntryRepository $accbepentry, AccChartCtrlHeadRepository $accchartctrlhead, CompanyRepository $company,ProfitcenterRepository $profitcenter) {
        $this->accbep = $accbep;
        $this->accbepentry = $accbepentry;
        $this->accchartctrlhead = $accchartctrlhead;
        $this->company = $company;
        $this->profitcenter = $profitcenter;

        $this->middleware('auth');
        $this->middleware('permission:view.accbeps',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.accbeps', ['only' => ['store']]);
        $this->middleware('permission:edit.accbeps',   ['only' => ['update']]);
        $this->middleware('permission:delete.accbeps', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $profitcenter=array_prepend(array_pluck($this->profitcenter->get(),'name','id'),'-Select-','');

       $accbeps = array();
       $rows=$this->accbep
       ->orderBy('acc_beps.id','desc')
       ->get();
       foreach($rows as $row){
           $accbep['id']=$row->id;
           $accbep['company_id']=$company[$row->company_id];
           $accbep['profitcenter_id']=$profitcenter[$row->profitcenter_id];
           $accbep['start_date']=date('Y-m-d',strtotime($row->start_date));
		   $accbep['end_date']=date('Y-m-d',strtotime($row->end_date));
           array_push($accbeps,$accbep);
       }
       echo json_encode($accbeps);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $salaryProdBill=array_prepend(config('bprs.salaryProdBill'),'-Select-','');
        $expenseType=array_prepend(config('bprs.expenseType'),'-Select-','');
        // $ctrlHead=array_prepend(array_pluck( $this->accchartctrlhead
        // ->selectRaw(
        // 'acc_chart_ctrl_heads.root_id,
        // reportHead.id,
        // reportHead.name
        // '
        // )
        // ->leftJoin(\DB::raw("(SELECT acc_chart_ctrl_heads.id,acc_chart_ctrl_heads.name FROM acc_chart_ctrl_heads  group by acc_chart_ctrl_heads.id,acc_chart_ctrl_heads.name) reportHead"), "reportHead.id", "=", "acc_chart_ctrl_heads.root_id")
        // ->where([['acc_chart_ctrl_heads.statement_type_id','=',2]])
        // ->whereNull('acc_chart_ctrl_heads.deleted_at')->orderBy('reportHead.name')->get(),'name','id'),'-Select-','');

      // $ctrlHead=array_prepend(array_pluck($this->accchartctrlhead->get(),'name','id'),'-Select-','');
        $ctrlHead=array_prepend(array_pluck(
        $this->accchartctrlhead->get()
        ->map(function($ctrlHead){
            $ctrlHead->name=$ctrlHead->name." (".$ctrlHead->code." )";
            return $ctrlHead;
        })
        ,'name','id'),'-Select-','');
       $profitcenter=array_prepend(array_pluck($this->profitcenter->get(),'name','id'),'-Select-','');

        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		return Template::loadView('Account.AccBep', ['ctrlHead'=>$ctrlHead,'expenseType'=>$expenseType,'company'=>$company,'salaryProdBill'=>$salaryProdBill,'profitcenter'=>$profitcenter]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccBepRequest $request) {
        $accbep=$this->accbep->create($request->except(['id']));
        if($accbep){
            return response()->json(array('success' => true,'id' =>  $accbep->id,'message' => 'Save Successfully'),200);
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
        $accbep=$this->accbep->find($id);
        $accbep['start_date']=date('Y-m-d',strtotime($accbep->start_date));
		$accbep['end_date']=date('Y-m-d',strtotime($accbep->end_date));
        $row ['fromData'] = $accbep;
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
    public function update(AccBepRequest $request, $id) {
        $accbep=$this->accbep->update($id,$request->except(['id']));
        if($accbep){
            return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->accbep->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}
