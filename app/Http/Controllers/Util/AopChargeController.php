<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\AopChargeRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Library\Template;
use App\Http\Requests\AopChargeRequest;

class AopChargeController extends Controller {

    private $aopcharge;
    private $company;
    private $buyer;
    private $supplier;
	private $autoyarn;
	private $uom;
	private $embelishmenttype;

    public function __construct(AopChargeRepository $aopcharge,CompanyRepository $company,BuyerRepository $buyer,SupplierRepository $supplier,AutoyarnRepository $autoyarn,UomRepository $uom,EmbelishmentTypeRepository $embelishmenttype) {
        $this->aopcharge = $aopcharge;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->supplier = $supplier;
		$this->autoyarn = $autoyarn;
		$this->uom = $uom;
		$this->embelishmenttype = $embelishmenttype;
        $this->middleware('auth');
        $this->middleware('permission:view.aopcharges',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.aopcharges', ['only' => ['store']]);
        $this->middleware('permission:edit.aopcharges',   ['only' => ['update']]);
        $this->middleware('permission:delete.aopcharges', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
	  $autoyarn=array_prepend($this->autoyarn->getConstructinComposition(),'-Select-','');
	  $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
	  		$embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');

      $aopcharges=array();
      $rows=$this->aopcharge->orderBy('id','desc')->get();
      foreach ($rows as $row) {
        $aopcharge['id']=$row->id;
        $aopcharge['fromgsm']=$row->from_gsm;
        $aopcharge['togsm']=$row->to_gsm;
        $aopcharge['company']=$company[$row->company_id];
		$aopcharge['autoyarn_id']=$row->autoyarn_id;
		$aopcharge['fabrication']=$autoyarn[$row->autoyarn_id];
		$aopcharge['aop_type']=$embelishmenttype[$row->embelishment_type_id];
		$aopcharge['from_impression']=$row->from_impression;
		$aopcharge['to_impression']=$row->to_impression;
		$aopcharge['from_coverage']=$row->from_coverage;
		$aopcharge['to_coverage']=$row->to_coverage;
        $aopcharge['rate']=$row->rate;
		$aopcharge['uom']=$uom[$row->uom_id];
        array_push($aopcharges,$aopcharge);
      }
        echo json_encode($aopcharges);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
		$autoyarn=array_prepend($this->autoyarn->getConstructinComposition(),'-Select-','');
        $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-','');
		$embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');
        return Template::loadView("Util.AopCharge",['company'=>$company,'uom'=>$uom,'buyer'=>$buyer,'supplier'=>$supplier,'autoyarn'=>$autoyarn,'embelishmenttype'=>$embelishmenttype]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AopChargeRequest $request) {
        $aopcharge = $this->aopcharge->create($request->except(['id','fabrication']));
        if ($aopcharge) {
            return response()->json(array('success' => true, 'id' => $aopcharge->id, 'message' => 'Save Successfully'), 200);
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
        $aopcharge = $this->aopcharge
		->where([['id','=',$id]])
		->get()
		->map(function ($aopcharge) use($autoyarn) {
			$aopcharge->fabrication= $autoyarn[$aopcharge->autoyarn_id];
			return $aopcharge;
		});
        $row ['fromData'] = $aopcharge[0];
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
    public function update(AopChargeRequest $request, $id) {
        $aopcharge = $this->aopcharge->update($id, $request->except(['id','fabrication']));
        if ($aopcharge) {
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
        if ($this->aopcharge->delete($id)) {
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
