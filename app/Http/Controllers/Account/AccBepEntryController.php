<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccBepEntryRepository;
use App\Repositories\Contracts\Account\AccBepRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Library\Template;
use App\Http\Requests\Account\AccBepEntryRequest;

class AccBepEntryController extends Controller {

    private $accbepentry;
    private $accchartctrlhead;

    public function __construct(AccBepEntryRepository $accbepentry,AccBepRepository $accbep,AccChartCtrlHeadRepository $accchartctrlhead) {
        $this->accbepentry = $accbepentry;
        $this->accbep = $accbep;
        $this->accchartctrlhead = $accchartctrlhead;

        $this->middleware('auth');
        $this->middleware('permission:view.accbepentrys',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.accbepentrys', ['only' => ['store']]);
        $this->middleware('permission:edit.accbepentrys',   ['only' => ['update']]);
        $this->middleware('permission:delete.accbepentrys', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $ctrlHead=array_prepend(array_pluck(
        $this->accchartctrlhead->get()
        ->map(function($ctrlHead){
            $ctrlHead->name=$ctrlHead->name." (".$ctrlHead->code." )";
            return $ctrlHead;
        })
        ,'name','id'),'-Select-','');

        $expenseType=array_prepend(config('bprs.expenseType'),'-Select-','');
        $salaryProdBill=array_prepend(config('bprs.salaryProdBill'),'-Select-','0');

       $accbepentries = array();
       $rows=$this->accbepentry
       ->where([['acc_bep_id','=',request('acc_bep_id',0)]])
       ->orderBy('acc_bep_entries.id','asc')
       ->get();
       foreach($rows as $row){
           $accbepentry['id']=$row->id;
           $accbepentry['acc_chart_ctrl_head_id']=$ctrlHead[$row->acc_chart_ctrl_head_id];
           $accbepentry['expense_type_id']=isset($expenseType[$row->expense_type_id])?$expenseType[$row->expense_type_id]:'';
           $accbepentry['amount']=$row->amount;
           $accbepentry['salary_prod_bill_id']=isset($salaryProdBill[$row->salary_prod_bill_id])?$salaryProdBill[$row->salary_prod_bill_id]:'';
           $accbepentry['remarks']=$row->remarks;
           array_push($accbepentries,$accbepentry);
       }
       echo json_encode($accbepentries);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccBepEntryRequest $request) {
        $accbepentry=$this->accbepentry->create($request->except(['id']));
        if($accbepentry){
            return response()->json(array('success' => true,'id' =>  $accbepentry->id,'message' => 'Save Successfully'),200);
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
        $accbepentry=$this->accbepentry->find($id);
        $row ['fromData'] = $accbepentry;
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
    public function update(AccBepEntryRequest $request, $id) {
        $accbepentry=$this->accbepentry->update($id,$request->except(['id']));
        if($accbepentry){
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
        if($this->accbepentry->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}
