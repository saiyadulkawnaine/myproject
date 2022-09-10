<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\DyingChargeRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\ConstructionRepository;
use App\Repositories\Contracts\Util\CompositionRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;

use App\Library\Template;
use App\Http\Requests\DyingChargeRequest;

class DyingChargeController extends Controller {

    private $dyingcharge;
    private $company;
    private $composition;
	private $construction;
    private $colorrange;
    private $productionprocess;
    private $uom;
    private $buyer;
    private $supplier;
	private $autoyarn;

    public function __construct(DyingChargeRepository $dyingcharge,CompanyRepository $company,CompositionRepository $composition,ConstructionRepository $construction,ColorrangeRepository $colorrange,ProductionProcessRepository $productionprocess,UomRepository $uom,BuyerRepository $buyer,SupplierRepository $supplier,AutoyarnRepository $autoyarn) {
        $this->dyingcharge = $dyingcharge;
        $this->company = $company;
        $this->composition = $composition;
        $this->colorrange = $colorrange;
        $this->productionprocess = $productionprocess;
		$this->construction = $construction;
        $this->uom = $uom;
        $this->buyer = $buyer;
        $this->supplier = $supplier;
		$this->autoyarn = $autoyarn;
        $this->middleware('auth');
        $this->middleware('permission:view.dyingcharges',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.dyingcharges', ['only' => ['store']]);
        $this->middleware('permission:edit.dyingcharges',   ['only' => ['update']]);
        $this->middleware('permission:delete.dyingcharges', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
      $composition=array_prepend(array_pluck($this->composition->get(),'name','id'),'-Select-','');
      $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
     // $productionprocess=array_prepend(array_pluck($this->productionprocess->get(),'process_name','id'),'-Select-','');
      $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
	  $autoyarn=array_prepend($this->autoyarn->getConstructinComposition(),'-Select-','');
	  $fabricshape=array_prepend(config('bprs.fabricshape'),'-Select-','');
	  $dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
      $dyingcharges=array();
      $rows=$this->dyingcharge->orderBy('id','desc')->get();
      foreach ($rows as $row) {
        $dyingcharge['id']=$row->id;
        $dyingcharge['company']=$company[$row->company_id];
        //$dyingcharge['composition']=$composition[$row->composition_id];
		$dyingcharge['autoyarn_id']=$row->autoyarn_id;
		$dyingcharge['fabrication']=$autoyarn[$row->autoyarn_id];
		$dyingcharge['dyeing_type']=  $dyetype[$row->dyeing_type_id];
		$dyingcharge['dyeing_type_id']=$row->dyeing_type_id;
		$dyingcharge['fabricshape']=$fabricshape[$row->fabric_shape_id];
        $dyingcharge['colorrange']=$colorrange[$row->colorrange_id];
        //$dyingcharge['productionprocess']=$productionprocess[$row->production_process_id];
        $dyingcharge['rate']=$row->rate;
        $dyingcharge['uom']=$uom[$row->uom_id];
        array_push($dyingcharges,$dyingcharge);
      }
        echo json_encode($dyingcharges);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		//$construction=array_prepend(array_pluck($this->construction->get(),'name','id'),'-Select-','');
        //$composition=array_prepend(array_pluck($this->composition->get(),'name','id'),'-Select-','');
        $colorrange=array_prepend(array_pluck($this->colorrange->get(),'name','id'),'-Select-','');
        $fabricshape=array_prepend(config('bprs.fabricshape'),'-Select-','');
		$dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
        //$process_for=array_prepend(config('bprs.productionarea'),'-Select-','');
        //$productionprocess=array_prepend(array_pluck($this->productionprocess->get(),'process_name','id'),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
		$autoyarn=array_prepend($this->autoyarn->getConstructinComposition(),'-Select-','');
        return Template::loadView("Util.DyingCharge",['company'=>$company,'colorrange'=>$colorrange,'fabricshape'=>$fabricshape,'uom'=>$uom,'dyetype'=>$dyetype,'buyer'=>$buyer,'supplier'=>$supplier,'autoyarn'=>$autoyarn]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DyingChargeRequest $request) {
        $dyingcharge = $this->dyingcharge->create($request->except(['id','fabrication']));
        if ($dyingcharge) {
            return response()->json(array('success' => true, 'id' => $dyingcharge->id, 'message' => 'Save Successfully'), 200);
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
		$autoyarn=array_prepend($this->autoyarn->getConstructinComposition(),'-Select-','');
        $dyingcharge = $this->dyingcharge
		->where([['id','=',$id]])
		->get()
		->map(function ($dyingcharge) use($autoyarn) {
			$dyingcharge->fabrication= $autoyarn[$dyingcharge->autoyarn_id];
			return $dyingcharge;
		});
        $row ['fromData'] = $dyingcharge[0];
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
    public function update(DyingChargeRequest $request, $id) {
        $dyingcharge = $this->dyingcharge->update($id, $request->except(['id','fabrication']));
        if ($dyingcharge) {
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
        if ($this->dyingcharge->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
	
	public function getFabric(){
		$autoyarn=$this->autoyarn->join('autoyarnratios', function($join)  {
		$join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
		})
		->join('constructions', function($join)  {
		$join->on('autoyarns.construction_id', '=', 'constructions.id');
		})
		->join('compositions',function($join){
		$join->on('compositions.id','=','autoyarnratios.composition_id');
		})
		->when(request('construction_name'), function ($q) {
			return $q->where('constructions.name', 'LIKE', "%".request('construction_name', 0)."%");
		})
		->when(request('composition_name'), function ($q) {
			return $q->where('compositions.name', 'LIKE', "%".request('composition_name', 0)."%");
		})
		->orderBy('autoyarns.id','desc')
	  ->get([
			'autoyarns.*',
			'constructions.name',
			'compositions.name as composition_name',
			'autoyarnratios.ratio'
		]);

	  $fabricDescriptionArr=array();
	  $fabricCompositionArr=array();
      foreach($autoyarn as $row){
      $fabricDescriptionArr[$row->id]=$row->name;
	  $fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
      }
      $desDropdown=array();
      foreach($fabricDescriptionArr as $key=>$val){
        $desDropdown[$key]=implode(",",$fabricCompositionArr[$key]);
      }
	  
	  $fab=array();
	  $fabs=array();
      foreach($autoyarn as $row){
        $fab[$row->id]['id']=$row->id;
		$fab[$row->id]['name']=$row->name;
		$fab[$row->id]['composition_name']=$desDropdown[$row->id];
		//array_push($fabs,$fab);
      }
	  foreach($fab as $row){
        
		array_push($fabs,$row);
      }
	  echo json_encode($fabs);
	  
	}

}
