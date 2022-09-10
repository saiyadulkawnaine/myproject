<?php
namespace App\Http\Controllers\Planing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Planing\TnaOrdRepository;
use App\Repositories\Contracts\Planing\TnaProgressDelayRepository;
use App\Repositories\Contracts\Planing\TnaTemplateRepository;
use App\Repositories\Contracts\Util\TnataskRepository;
use App\Library\Template;
use App\Http\Requests\Planing\TnaTemplateRequest;

class TnaTemplateController extends Controller {

    private $tnatemplate;
    private $tnaprogressdelay;
    private $tnatask;
    private $tnaord;
    private $buyer;

    public function __construct(
        TnaTemplateRepository $tnatemplate,
        TnaProgressDelayRepository $tnaprogressdelay,
        TnataskRepository $tnatask,
        TnaOrdRepository $tnaord,
        BuyerRepository $buyer
    ) {
        $this->tnatemplate = $tnatemplate;
        $this->tnaprogressdelay = $tnaprogressdelay;
        $this->tnatask = $tnatask;
        $this->tnaord = $tnaord;
        $this->buyer = $buyer;

        $this->middleware('auth');
        // $this->middleware('permission:view.tnatemplates',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.tnatemplates', ['only' => ['store']]);
        // $this->middleware('permission:edit.tnatemplates',   ['only' => ['update']]);
        // $this->middleware('permission:delete.tnatemplates', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $yesno=array_prepend(config('bprs.yesno'), '-Select-','');
        $tnatemplates=array();
        $rows=$this->tnatemplate->orderBy('id','desc')->get();

        foreach($rows as $row){
            $tnatemplate['id']=$row->id;
            $tnatemplate['buyer_name']=$buyer[$row->buyer_id];
            $tnatemplate['lead_days']=$row->lead_days;
            $tnatemplate['imported_material_id']=$yesno[$row->imported_material_id];
            $tnatemplate['embelishment']=$yesno[$row->embelishment_needed_id];
            $tnatemplate['dyed_yarn']=$yesno[$row->dyed_yarn_needed_id];
            $tnatemplate['aop']=$yesno[$row->aop_needed_id];
            array_push($tnatemplates,$tnatemplate);
        }

        echo json_encode($tnatemplates);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $tnatask=array_prepend(array_pluck($this->tnatask->get(),'task_name','id'),'','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'','');
        $yesno=array_prepend(config('bprs.yesno'), '-Select-','');
        $startendbasis=array_prepend(config('bprs.startendbasis'), '-Select-','');
        return Template::loadView("Planing.TnaTemplate",['tnatask'=>$tnatask,'buyer'=>$buyer,'yesno'=>$yesno,'startendbasis'=>$startendbasis]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TnaTemplateRequest $request) {
        $tnatemplate = $this->tnatemplate->create($request->except(['id']));
        if ($tnatemplate) {
            return response()->json(array('success' => true, 'id' => $tnatemplate->id, 'message' => 'Save Successfully'), 200);
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
        $tnatemplate = $this->tnatemplate->find($id);
        $row ['fromData'] = $tnatemplate;
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
    public function update(TnaTemplateRequest $request, $id) {
        $tnatemplate = $this->tnatemplate->update($id, $request->except(['id']));
        if ($tnatemplate) {
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
        if ($this->tnatemplate->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}