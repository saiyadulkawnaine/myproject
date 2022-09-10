<?php
namespace App\Http\Controllers\System\Configuration;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\System\Configuration\CostStandardRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Library\Template;
use App\Http\Requests\System\Configuration\CostStandardRequest;

class CostStandardController extends Controller {

    private $coststandard;
    private $company;
    private $accchartctrlhead;

    public function __construct(
        CostStandardRepository $coststandard,
        CompanyRepository $company,
        AccChartCtrlHeadRepository $accchartctrlhead
    ) {
        $this->coststandard = $coststandard;
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
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $configuration=array_prepend(config('bprs.configuration'),'-Select-','');
        $rows=$this->coststandard
        ->orderBy('cost_standards.id','desc')
        ->get()
        ->map(function($rows) use($configuration,$company){
            $rows->configuration_type_id=$configuration[$rows->configuration_type_id];
            $rows->company_id=$company[$rows->company_id];
            return $rows;
        });
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $configuration=array_prepend(config('bprs.configuration'),'-Select-','');
        $ctrlHead=array_prepend(array_pluck( $this->accchartctrlhead
        ->selectRaw(
        'acc_chart_ctrl_heads.root_id,
        reportHead.id,
        reportHead.name
        '
        )
        ->leftJoin(\DB::raw("(SELECT acc_chart_ctrl_heads.id,acc_chart_ctrl_heads.name FROM acc_chart_ctrl_heads  group by acc_chart_ctrl_heads.id,acc_chart_ctrl_heads.name) reportHead"), "reportHead.id", "=", "acc_chart_ctrl_heads.root_id")
        ->where([['acc_chart_ctrl_heads.statement_type_id','=',2]])
        ->whereNull('acc_chart_ctrl_heads.deleted_at')->orderBy('reportHead.name')->get(),'name','id'),'-Select-','');
		return Template::loadView('System.Configuration.CostStandard', ['company'=>$company,'configuration'=>$configuration,'ctrlHead'=>$ctrlHead]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CostStandardRequest $request) {
		$coststandard=$this->coststandard->create([
            'configuration_type_id'=>$request->configuration_type_id,
            'company_id'=>$request->company_id,
            'remarks'=>$request->remarks
        ]);
		if($coststandard){
			return response()->json(array('success' => true,'id' =>  $coststandard->id,'message' => 'Save Successfully'),200);
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
       $coststandard = $this->coststandard->find($id);
	   $row ['fromData'] = $coststandard;
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
    public function update(CostStandardRequest $request, $id) {
       $coststandard=$this->coststandard->update($id,[
        'configuration_type_id'=>$request->configuration_type_id,
        'company_id'=>$request->company_id,
        'remarks'=>$request->remarks
    ]);
		if($coststandard){
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
        if($this->coststandard->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}
