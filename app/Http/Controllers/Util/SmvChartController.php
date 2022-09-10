<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\SmvChartRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Library\Template;
use App\Http\Requests\SmvChartRequest;

class SmvChartController extends Controller {

    private $smvchart;
    private $company;
    private $location;

    public function __construct(SmvChartRepository $smvchart,CompanyRepository $company,LocationRepository $location) {
        $this->smvchart = $smvchart;
        $this->company = $company;
		    $this->location = $location;

        $this->middleware('auth');
        $this->middleware('permission:view.smvcharts',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.smvcharts', ['only' => ['store']]);
        $this->middleware('permission:edit.smvcharts',   ['only' => ['update']]);
        $this->middleware('permission:delete.smvcharts', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-',0);
	  $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
      $gmtcategory=array_prepend(config('bprs.gmtcategory'),'-Select-',0);
      $gmtcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-',0);
      $smvcharts=array();
      $rows=$this->smvchart->get();
      foreach($rows as $row){
         $smvchart['id']=	$row->id;
         $smvchart['company']=	$company[$row->company_id];
         $smvchart['gmtcategory']=	$gmtcategory[$row->gmt_category_id];
         $smvchart['gmtcomplexity']=	$gmtcomplexity[$row->gmt_complexity_id];
         $smvchart['dew_efficiency_per']=	$row->dew_efficiency_per;
         $smvchart['man_power_line']=	$row->man_power_line;
         $smvchart['gmt_smv']=	$row->gmt_smv;
         $smvchart['sew_target_per_hour']=	$row->sew_target_per_hour;
         array_push($smvcharts,$smvchart);
        }
          echo json_encode($smvcharts);
      }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-',0);
		$location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $gmtcategory=array_prepend(config('bprs.gmtcategory'),'-Select-',0);
        $gmtcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-',0);
        return Template::loadView("Util.SmvChart",['company'=>$company,'location'=>$location,'gmtcategory'=>$gmtcategory,'gmtcomplexity'=>$gmtcomplexity]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SmvChartRequest $request) {
        $smvchart = $this->smvchart->create($request->except(['id']));
        if ($smvchart) {
            return response()->json(array('success' => true, 'id' => $smvchart->id, 'message' => 'Save Successfully'), 200);
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
        $smvchart = $this->smvchart->find($id);
        $row ['fromData'] = $smvchart;
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
    public function update(SmvChartRequest $request, $id) {
        $smvchart = $this->smvchart->update($id, $request->except(['id']));
        if ($smvchart) {
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
        if ($this->smvchart->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
