<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccChartSubGroupRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Repositories\Contracts\Account\AccChartLocationRepository;
use App\Repositories\Contracts\Account\AccChartDivisionRepository;
use App\Repositories\Contracts\Account\AccChartDepartmentRepository;

use App\Repositories\Contracts\Account\AccChartSectionRepository;
use App\Repositories\Contracts\HRM\EmployeeRepository;



use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Library\Template;
use App\Http\Requests\Account\AccChartCtrlHeadRequest;

class AccChartCtrlHeadController extends Controller {

    private $accchartctrlhead;
    private $accchartsubgroup;
    //private $ctrlHead;
    private $currency;
    private $buyer;
    private $supplier;
    private $location;
    private $division;
    private $department;
    private $section;
    private $employee;

    public function __construct(AccChartCtrlHeadRepository $accchartctrlhead,AccChartSubGroupRepository $accchartsubgroup,CurrencyRepository $currency,BuyerRepository $buyer,SupplierRepository $supplier,AccChartLocationRepository $location,AccChartDivisionRepository $division,AccChartDepartmentRepository $department,AccChartSectionRepository $section,EmployeeRepository $employee) {
        $this->accchartctrlhead = $accchartctrlhead;
        $this->accchartsubgroup = $accchartsubgroup;
        $this->currency = $currency;
        $this->buyer = $buyer;
        $this->supplier = $supplier;
        $this->location = $location;
        $this->division = $division;
        $this->department = $department;
        $this->section = $section;
        $this->employee = $employee;
        //$this->ctrlHead = $ctrlHead;

        $this->middleware('auth');
        $this->middleware('permission:view.accchartheads',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.accchartheads', ['only' => ['store']]);
        $this->middleware('permission:edit.accchartheads',   ['only' => ['update']]);
        $this->middleware('permission:delete.accchartheads', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //$accchartsubgroup=array_prepend(array_pluck($this->accchartsubgroup->get(),'name','id'),'','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        $ctrlHead=array_prepend(array_pluck($this->accchartctrlhead->get(),'name','id'),'','0');
        $ctrlheadtype=array_prepend(config('bprs.ctrlheadtype'),'-Select-','0');
        $statementType=array_prepend(config('bprs.statementType'),'-Select-','0');
        $controlname=array_prepend(config('bprs.controlname'),'-Select-','0');
        $otherType=array_prepend(config('bprs.otherType'),'-Select-','0');
        $normalbalance=array_prepend(config('bprs.normalbalance'),'-Select-','0');
        $accchartgroup=array_prepend(config('bprs.accchartgroup'),'-Select-','0');
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
	    $expenseType=array_prepend(config('bprs.expenseType'),'-Select-','0');
        $accchartctrlheads=array();
        $rows=$this->accchartctrlhead
        ->join('acc_chart_sub_groups',function($join){
            $join->on('acc_chart_sub_groups.id', '=', 'acc_chart_ctrl_heads.acc_chart_sub_group_id');
        })
        ->orderBy('code','asc')
        ->get([
            'acc_chart_ctrl_heads.*',
            'acc_chart_sub_groups.name as sub_group_name',
            'acc_chart_sub_groups.acc_chart_group_id',
        ]);
        foreach ($rows as $row) {
        $accchartctrlhead['id']=$row->id;
          $accchartctrlhead['name']=$row->name;
          $accchartctrlhead['code']=$row->code;
          $accchartctrlhead['sort_id']=$row->sort_id;
          $accchartctrlhead['root_id']=isset($ctrlHead[$row->root_id])?$ctrlHead[$row->root_id]:0;
          $accchartctrlhead['acc_chart_sub_group_id']=$row->sub_group_name;
           $accchartctrlhead['main_group']=isset($accchartgroup[$row->acc_chart_group_id])?$accchartgroup[$row->acc_chart_group_id]:'';
           $accchartctrlhead['row_status']=isset($yesno[$row->row_status])?$yesno[$row->row_status]:'';

          $accchartctrlhead['ctrlhead_type_id']=isset($ctrlheadtype[$row->ctrlhead_type_id])?$ctrlheadtype[$row->ctrlhead_type_id]:'';
          $accchartctrlhead['statement_type_id']=isset($statementType[$row->statement_type_id])?$statementType[$row->statement_type_id]:'';
          $accchartctrlhead['retained_earning_account_id']=$row->retained_earning_account_id;
          $accchartctrlhead['control_name_id']=isset($controlname[$row->control_name_id])?$controlname[$row->control_name_id]:'';
          $accchartctrlhead['other_type_id']=isset($otherType[$row->other_type_id])?$otherType[$row->other_type_id]:'';
          $accchartctrlhead['currency_id']=isset($currency[$row->currency_id])?$currency[$row->currency_id]:'';
          $accchartctrlhead['normal_balance_id']=isset($normalbalance[$row->normal_balance_id])?$normalbalance[$row->normal_balance_id]:'';
          $accchartctrlhead['is_cm_expense']=isset($yesno[$row->is_cm_expense])?$yesno[$row->is_cm_expense]:'';
          $accchartctrlhead['expense_type_id']=isset($expenseType[$row->expense_type_id])?$expenseType[$row->expense_type_id]:'';

          array_push($accchartctrlheads,$accchartctrlhead);
        }
        echo json_encode($accchartctrlheads);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $ctrlheadtype=array_prepend(config('bprs.ctrlheadtype'),'-Select-','');
        $statementType=array_prepend(config('bprs.statementType'),'-Select-','');
        $controlname=array_prepend(config('bprs.controlname'),'-Select-','');
        $otherType=array_prepend(config('bprs.otherType'),'-Select-','0');
        $normalbalance=array_prepend(config('bprs.normalbalance'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        $status=array_prepend(config('bprs.status'),'-Select-','');
        $yesno = array_prepend(config('bprs.yesno'),'-Select-',' ');
        $expenseType=array_prepend(config('bprs.expenseType'),'-Select-','0');
        $accchartsubgroup=array_prepend(array_pluck($this->accchartsubgroup->orderBy('name','asc')->get(),'name','id'),'','');
        $ctrlHead=array_prepend(array_pluck($this->accchartctrlhead->get(),'name','id'),'-Select-','');
		return Template::loadView('Account.AccChartHead', ['accchartsubgroup'=>$accchartsubgroup,'ctrlHead'=>$ctrlHead,'statementType'=>$statementType,'controlname'=>$controlname,'otherType'=>$otherType,'normalbalance'=>$normalbalance,'currency'=>$currency,'ctrlheadtype'=>$ctrlheadtype,'status'=>$status,'yesno'=>$yesno,'expenseType'=>$expenseType]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccChartCtrlHeadRequest $request) {
		$accchartctrlhead=$this->accchartctrlhead->create($request->except(['id']));
		if($accchartctrlhead){
			return response()->json(array('success' => true,'id' =>  $accchartctrlhead->id,'message' => 'Save Successfully'),200);
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
       $accchartctrlhead = $this->accchartctrlhead->find($id);
	   $row ['fromData'] = $accchartctrlhead;
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
    public function update(AccChartCtrlHeadRequest $request, $id) {
        $accchartctrlhead=$this->accchartctrlhead->update($id,$request->except(['id']));
		if($accchartctrlhead){
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
        if($this->accchartctrlhead->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
        else{
             return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
        }
    }

    public function getroot() {
        $childs = array();
        $data=$this->accchartctrlhead
        ->where([['row_status','=',1]])
        ->orderBy('id','asc')
        ->get([
            'id',
            'root_id',
            'code',
            'name as text'
        ]);

        foreach($data as $item){
            $childs[$item->root_id][] = $item;  
        }

        foreach($data as $item){
            if (isset($childs[$item->id])){
                $item->children = $childs[$item->id];  
            }
        } 

        $tree = $childs[0];
        echo json_encode($tree);
    }

    public function retainedearningaccount() {
        $data=$this->accchartctrlhead->where([['statement_type_id','=',3]])->orderBy('id','asc')
        ->get([
            'id',
            'name as text'
        ]);
        echo json_encode($data);
    }

    
    public function getjsonbycode(){
        $accchartctrlhead = $this->accchartctrlhead
        ->when(request('code'), function ($q)  {
        return $q->where('code', '=', request('code', 0));
        })
        ->when(request('id'), function ($q)  {
        return $q->where('id', '=', request('id', 0));
        })
        ->get()
        ->first();
        $accchartctrlhead['party']=array();
        if($accchartctrlhead->control_name_id ==5 || $accchartctrlhead->control_name_id ==6 || $accchartctrlhead->control_name_id ==30 || $accchartctrlhead->control_name_id ==31 || $accchartctrlhead->control_name_id == 40 || $accchartctrlhead->control_name_id ==45 || $accchartctrlhead->control_name_id ==50 || $accchartctrlhead->control_name_id ==60)
        {
           $accchartctrlhead['party']=$this->buyer->get();  
        }

        if($accchartctrlhead->control_name_id ==1 || $accchartctrlhead->control_name_id ==2 || $accchartctrlhead->control_name_id ==10 || $accchartctrlhead->control_name_id ==15 || $accchartctrlhead->control_name_id == 20 || $accchartctrlhead->control_name_id ==35 || $accchartctrlhead->control_name_id == 62)
        {
           $accchartctrlhead['party']=$this->supplier->get();  
        }

        if($accchartctrlhead->control_name_id ==38)
        {
           $accchartctrlhead['party']=$this->supplier->otherPartise();  
        }
        
        $accchartctrlhead['location']=$this->location->getByChartId($accchartctrlhead->id);
        $accchartctrlhead['division']=$this->division->getByChartId($accchartctrlhead->id);
        $accchartctrlhead['department']=$this->department->getByChartId($accchartctrlhead->id);
        $accchartctrlhead['section']=$this->section->getByChartId($accchartctrlhead->id);
        $accchartctrlhead['employee']=$this->employee->get();
        echo json_encode($accchartctrlhead);
    }

}
