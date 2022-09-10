<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\ProfitcenterRepository;
use App\Repositories\Contracts\Util\UomRepository;

use App\Library\Template;
use App\Http\Requests\ItemclassRequest;

class ItemclassController extends Controller {

    private $itemclass;
	private $itemcategory;
	private $profitcenter;
    private $uom;

    public function __construct(ItemclassRepository $itemclass,ItemcategoryRepository $itemcategory,ProfitcenterRepository $profitcenter,UomRepository $uom) {
        $this->itemclass = $itemclass;
    		$this->itemcategory = $itemcategory;
    		$this->profitcenter = $profitcenter;
    		$this->uom = $uom;
        $this->middleware('auth');
        $this->middleware('permission:view.itemclasses',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.itemclasses', ['only' => ['store']]);
        $this->middleware('permission:edit.itemclasses',   ['only' => ['update']]);
        $this->middleware('permission:delete.itemclasses', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $itemcategory=array_prepend(array_pluck($this->itemcategory->get(),'name','id'),'-Select-',0);
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
  		$itemnature=array_prepend(config('bprs.itemnature'),'-Select-','');
  		$trimstype=array_prepend(config('bprs.trimstype'),'-Select-',0);
  		$uomclass=array_prepend(config('bprs.uomclass'),'-Select-','');
  		$calculatorneed=array_prepend(config('bprs.calculatorneed'),'-Select-',0);
  		$is_pre_account=config('bprs.yesno');
  		$profitcenter=array_prepend(array_pluck($this->profitcenter->get(),'name','id'),'-Select-',0);
      $itemclasses=array();
      $rows=$this->itemclass->get();
      foreach ($rows as $row) {
        $itemclass['id']=$row->id;
        $itemclass['name']=$row->name;
        $itemclass['itemcategory']=$itemcategory[$row->itemcategory_id];
        $itemclass['itemnature']=$itemnature[$row->item_nature_id];
        $itemclass['uomclass']=$uomclass[$row->uomclass_id];
        $itemclass['uom']=$uom[$row->costing_uom_id];
        $itemclass['is_pre_account']=$is_pre_account[$row->pre_account_req_id];
        array_push($itemclasses,$itemclass);
      }
        echo json_encode($itemclasses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
		$itemcategory=array_prepend(array_pluck($this->itemcategory->orderBy('name','asc')->get(),'name','id'),'-Select-',0);
		$itemnature=array_prepend(config('bprs.itemnature'),'-Select-','');
		$trimstype=array_prepend(config('bprs.trimstype'),'-Select-',0);
		$uomclass=array_prepend(config('bprs.uomclass'),'-Select-','');
		$calculatorneed=array_prepend(config('bprs.calculatorneed'),'-Select-',0);
		$is_pre_account=config('bprs.yesno');
		$profitcenter=array_prepend(array_pluck($this->profitcenter->get(),'name','id'),'-Select-',0);
        $sensivity=array_prepend(config('bprs.sensivity'),'-Select-','');
		//$permited=[];
        return Template::loadView("Util.Itemclass",['itemcategory'=>$itemcategory,'itemnature'=>$itemnature,'trimstype'=>$trimstype,'uomclass'=>$uomclass,'calculatorneed'=>$calculatorneed,'is_pre_account'=>$is_pre_account,'profitcenter'=>$profitcenter,/* 'permited'=>$permited, */'uom'=>$uom,'sensivity'=> $sensivity]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemclassRequest $request) {
		//$profitcenter=explode(",",$request->input('profitcenter_id'));
        $itemclass = $this->itemclass->create($request->except(['id']));
		//$res=$itemclass->profitcenters()->sync($profitcenter);
        if ($itemclass) {
            return response()->json(array('success' => true, 'id' => $itemclass->id, 'message' => 'Save Successfully'), 200);
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
		// $profitcenters = $this->profitcenter->get();
        // $participants = $this->itemclass->find($id)->profitcenters->sortBy('id');
        // $avaiable = $profitcenters->diff($participants);

        $itemclass = $this->itemclass->find($id);
        $row ['fromData'] = $itemclass;
       // $dropdown['profitcenter_dropDown'] = "'".Template::loadView('Util.ProfitcenterDropDown',['profitcenter'=>array_pluck($avaiable,'name','id'),'permited'=>array_pluck(	$participants,'name','id')])."'";
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
    public function update(ItemclassRequest $request, $id) {
		//$profitcenter=explode(",",$request->input('profitcenter_id'));
        $res = $this->itemclass->update($id, $request->except(['id']));
		//$itemclass = $this->itemclass->find($id);
    	//$itemclass->profitcenters()->sync($profitcenter);
        if ($res ) {
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
        if ($this->itemclass->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
    
    public function getUomCodes(){
        $uom = $this->uom->where([['uomclass_id','=',request('uomclass_id',0)]])->get();
        return json_encode($uom);
    }

}
