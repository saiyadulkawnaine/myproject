<?php
namespace App\Http\Controllers\System\Configuration;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\System\Configuration\CostStandardRepository;
use App\Repositories\Contracts\System\Configuration\CostStandardHeadRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Library\Template;
use App\Http\Requests\System\Configuration\CostStandardHeadRequest;

class CostStandardHeadController extends Controller {

    private $coststandard;
    private $coststandardhead;
    private $company;
    private $accchartctrlhead;

    public function __construct(
        CostStandardRepository $coststandard,
        CostStandardHeadRepository $coststandardhead,
        CompanyRepository $company,
        AccChartCtrlHeadRepository $accchartctrlhead
    ) {
        $this->coststandard = $coststandard;
        $this->coststandardhead = $coststandardhead;
        $this->company = $company;
        $this->accchartctrlhead = $accchartctrlhead;
        $this->middleware('auth');
       /*  $this->middleware('permission:view.ileconfigs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.ileconfigs', ['only' => ['store']]);
        $this->middleware('permission:edit.ileconfigs',   ['only' => ['update']]);
        $this->middleware('permission:delete.ileconfigs', ['only' => ['destroy']]); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
    $rows=$this->coststandardhead
     ->join('acc_chart_ctrl_heads', function($join){
            $join->on('acc_chart_ctrl_heads.id', '=', 'cost_standard_heads.acc_chart_ctrl_head_id');
      })
     ->join('cost_standards', function($join){
            $join->on('cost_standards.id', '=', 'cost_standard_heads.cost_standard_id');
      })
     ->where([['cost_standards.id','=',request('cost_standard_id',0)]])
     ->orderBy('cost_standard_heads.id','desc')
     ->get([
      'cost_standard_heads.*',
      'acc_chart_ctrl_heads.name as acc_head'
     ]);
      echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CostStandardHeadRequest $request) {
		$coststandardhead=$this->coststandardhead->create([
            'cost_standard_id'=>$request->cost_standard_id,
            'acc_chart_ctrl_head_id'=>$request->acc_chart_ctrl_head_id,
            'cost_per'=>$request->cost_per,
            'remarks'=>$request->remarks
        ]);
		if($coststandardhead){
			return response()->json(array('success' => true,'id' =>  $coststandardhead->id,'message' => 'Save Successfully'),200);
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
       $coststandardhead = $this->coststandardhead->find($id);
	   $row ['fromData'] = $coststandardhead;
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
    public function update(CostStandardHeadRequest $request, $id) {
       $coststandardhead=$this->coststandardhead->update($id,[
            'acc_chart_ctrl_head_id'=>$request->acc_chart_ctrl_head_id,
            'cost_per'=>$request->cost_per,
            'remarks'=>$request->remarks
        ]);
		if($coststandardhead){
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
        if($this->coststandardhead->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}
