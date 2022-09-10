<?php
namespace App\Http\Controllers\System\Configuration;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\System\Configuration\IleConfigRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Library\Template;
use App\Http\Requests\System\Configuration\IleConfigRequest;

class IleConfigController extends Controller {

    private $ileconfig;
    private $company;
    private $itemclass;
    private $itemcategory;
    private $uom;

    public function __construct(IleConfigRepository $ileconfig,CompanyRepository $company,ItemclassRepository $itemclass,ItemcategoryRepository $itemcategory,UomRepository $uom) {
        $this->ileconfig = $ileconfig;
        $this->company = $company;
        $this->itemclass = $itemclass;
        $this->itemcategory = $itemcategory;
        $this->uom = $uom;
        

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
        $itemclass=array_prepend(array_pluck($this->itemclass->get(),'name','id'),'-Select-','');
        $configuration=array_prepend(config('bprs.configuration'),'-Select-','');
        $status=array_prepend(config('bprs.status'),'-Select-','');
        $purchasesource=array_prepend(config('bprs.purchasesource'),'-Select-','');
        $ileconfigs=array();
        $rows=$this->ileconfig->get();
        foreach($rows as $row){
           $ileconfig['id']=$row->id; 
           $ileconfig['company_id']= $company[$row->company_id];   
           $ileconfig['configuration_type_id']= $configuration[$row->configuration_type_id];   
           $ileconfig['percent']=$row->percent;
           $ileconfig['source_id']=$purchasesource[$row->source_id];
           $ileconfig['status_id']=$status[$row->status_id];
           $ileconfig['itemclass_id']= $itemclass[$row->itemclass_id];

        
        array_push($ileconfigs,$ileconfig);
        }
        echo json_encode($ileconfigs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
        $configuration=array_only(config('bprs.configuration'),[95]);
        $purchasesource=array_prepend(config('bprs.purchasesource'),'-Select-','');
        $status=array_only(config('bprs.status'),[0,1]);
        $itemcategory=array_prepend(array_pluck($this->itemcategory->get(),'name','id'),'-Select-',0);
        $itemnature=array_prepend(config('bprs.itemnature'),'-Select-','');
        $trimstype=array_prepend(config('bprs.trimstype'),'-Select-',0);
  		  $uomclass=array_prepend(config('bprs.uomclass'),'-Select-','');
        $is_pre_account=config('bprs.yesno');
		return Template::loadView('System.Configuration.IleConfig', ['company'=>$company,'configuration'=>$configuration,'purchasesource'=>$purchasesource,'status'=>$status,'itemcategory'=>$itemcategory,'itemnature'=>$itemnature,'trimstype'=>$trimstype,'uom'=>$uom,'uomclass'=>$uomclass,'is_pre_account'=>$is_pre_account]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IleConfigRequest $request) {
		$ileconfig=$this->ileconfig->create([
            'configuration_type_id'=>$request->configuration_type_id,
            'company_id'=>$request->company_id,
            'itemclass_id'=>$request->itemclass_id,
            'source_id'=>$request->source_id,
            'percent'=>$request->percent,
            'status_id'=>$request->status_id
        ]);
		if($ileconfig){
			return response()->json(array('success' => true,'id' =>  $ileconfig->id,'message' => 'Save Successfully'),200);
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
       $ileconfig = $this->ileconfig
       ->leftJoin('itemclasses',function($join){
           $join->on('itemclasses.id','=','ile_configs.itemclass_id');
       })
       ->get([
         'ile_configs.*',
         'itemclasses.name as itemclass_name'
       ])
       ->first();
	   $row ['fromData'] = $ileconfig;
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
    public function update(IleConfigRequest $request, $id) {
       $ileconfig=$this->ileconfig->update($id,[
        'configuration_type_id'=>$request->configuration_type_id,
        'company_id'=>$request->company_id,
        'itemclass_id'=>$request->itemclass_id,
        'source_id'=>$request->source_id,
        'percent'=>$request->percent,
        'status_id'=>$request->status_id
    ]);
		if($ileconfig){
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
        if($this->ileconfig->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getItemGroup(){
      $itemcategory=array_prepend(array_pluck($this->itemcategory->get(),'name','id'),'-Select-',0);
      $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
    $itemnature=array_prepend(config('bprs.itemnature'),'-Select-','');
    $trimstype=array_prepend(config('bprs.trimstype'),'-Select-',0);
    $uomclass=array_prepend(config('bprs.uomclass'),'-Select-','');
    $calculatorneed=array_prepend(config('bprs.calculatorneed'),'-Select-',0);
    $is_pre_account=config('bprs.yesno');

    $itemclasses=array();
    $rows=$this->itemclass->get();
    foreach ($rows as $row) {
      $itemclass['id']=$row->id;
      $itemclass['itemclass_name']=$row->name;
      $itemclass['itemcategory']=$itemcategory[$row->itemcategory_id];
      $itemclass['itemnature']=$itemnature[$row->item_nature_id];
      $itemclass['uomclass']=$uomclass[$row->uomclass_id];
      $itemclass['uom']=$uom[$row->costing_uom_id];
      $itemclass['is_pre_account']=$is_pre_account[$row->pre_account_req_id];
      array_push($itemclasses,$itemclass);
    }
      echo json_encode($itemclasses);
    }

}
