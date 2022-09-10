<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoGeneralServiceRepository;
use App\Repositories\Contracts\Purchase\PoGeneralServiceItemRepository;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Library\Template;
use App\Http\Requests\Purchase\PoGeneralServiceItemRequest;


class PoGeneralServiceItemController extends Controller
{
   private $pogeneralservice;
   private $pogeneralserviceitem;
   private $assetquantitycost;

	public function __construct(
		PoGeneralServiceRepository $pogeneralservice,
		PoGeneralServiceItemRepository $pogeneralserviceitem,
		AssetQuantityCostRepository $assetquantitycost
	)
	{
        $this->pogeneralservice = $pogeneralservice;
        $this->pogeneralserviceitem = $pogeneralserviceitem;
		$this->assetquantitycost = $assetquantitycost;

		$this->middleware('auth');
		$this->middleware('permission:view.pogeneralserviceitems',   ['only' => ['create', 'index','show']]);
		$this->middleware('permission:create.pogeneralserviceitems', ['only' => ['store']]);
		$this->middleware('permission:edit.pogeneralserviceitems',   ['only' => ['update']]);
		$this->middleware('permission:delete.pogeneralserviceitems', ['only' => ['destroy']]);
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$rows=$this->pogeneralserviceitem
    	->join('po_general_services', function($join){
			$join->on('po_general_service_items.po_general_service_id', '=', 'po_general_services.id');
		})
		->leftJoin('departments', function($join){
			$join->on('departments.id', '=', 'po_general_service_items.department_id');
		})
		->leftJoin('users', function($join){
			$join->on('users.id', '=', 'po_general_service_items.demand_by_id');
		})
		->leftJoin('asset_quantity_costs', function($join){
			$join->on('asset_quantity_costs.id', '=', 'po_general_service_items.asset_quantity_cost_id');
		})
		->leftJoin('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
		->leftJoin('uoms', function($join){
			$join->on('uoms.id', '=', 'po_general_service_items.uom_id');
		})
		->where([['po_general_service_items.po_general_service_id','=',request('po_general_service_id',0)]])
		->orderBy('po_general_service_items.id','desc')
		->get([
			'po_general_service_items.*',
			'uoms.code as uom_name',
			//'asset_quantity_costs.*',
			'asset_acquisitions.name as asset_name',
			'asset_acquisitions.origin',
			'asset_acquisitions.brand',
			'asset_acquisitions.asset_group',
			'departments.name as department_name',
			'uoms.code as uom_code',
			'users.name as demand_by'

		])
		->map(function ($rows) {
			$rows->qty = number_format($rows->qty,2);
			$rows->amount = number_format($rows->amount,2);
			$rows->asset_no= str_pad($rows->asset_quantity_cost_id,6,0,STR_PAD_LEFT );
			$rows->asset_desc=$rows->asset_no.";".$rows->asset_name.";".$rows->asset_group.";".$rows->brand." ".$rows->origin;
			return $rows;
		});
		echo json_encode($rows);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    	//
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoGeneralServiceItemRequest $request)
    {
    	$pogeneralservice=$this->pogeneralservice->find($request->po_general_service_id);
		if($pogeneralservice->approved_at){
			return response()->json(array('success' => false,  'message' => 'Approved, Save or Update not Possible'), 200);
		}
		$pogeneralserviceitem=$this->pogeneralserviceitem->create($request->except(['id','asset_desc']));
        if($pogeneralserviceitem){
            return response()->json(array('success' => true,'id' =>  $pogeneralserviceitem->id,'message' => 'Save Successfully'),200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	$rows=$this->pogeneralserviceitem
    	->join('po_general_services', function($join){
			$join->on('po_general_service_items.po_general_service_id', '=', 'po_general_services.id');
		})
		// ->join('departments', function($join){
		// 	$join->on('departments.id', '=', 'po_general_service_items.department_id');
		// })
		// ->join('users', function($join){
		// 	$join->on('users.id', '=', 'po_general_service_items.demand_by_id');
		// })
		->leftJoin('asset_quantity_costs', function($join){
			$join->on('asset_quantity_costs.id', '=', 'po_general_service_items.asset_quantity_cost_id');
		})
		->leftJoin('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
		// ->join('uoms', function($join){
		// 	$join->on('uoms.id', '=', 'po_general_service_items.uom_id');
		// })
		->where([['po_general_service_items.id','=',$id]])
		->orderBy('po_general_service_items.id','desc')
		->get([
			'po_general_service_items.*',
			//'uoms.code as uom_name',
			//'asset_quantity_costs.id as asset_quantity_cost_id',
			'asset_acquisitions.name as asset_name',
			'asset_acquisitions.origin',
			'asset_acquisitions.brand',
			'asset_acquisitions.asset_group',
		])
		->map(function ($rows) {
			$rows->asset_no= str_pad($rows->asset_quantity_cost_id,6,0,STR_PAD_LEFT );
			$rows->asset_desc=$rows->asset_no.";".$rows->asset_name.";".$rows->asset_group.";".$rows->brand." ".$rows->origin;
			return $rows;
		})
		->first();
		$row ['fromData'] = $rows;
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
    public function update(PoGeneralServiceItemRequest $request, $id)
    {
    	$pogeneralservice=$this->pogeneralservice->find($request->po_general_service_id);
		if($pogeneralservice->approved_at){
			return response()->json(array('success' => false,  'message' => 'Approved, Save or Update not Possible'), 200);
		}
    	if($request->qty<=0 || $request->qty==''){
    		return response()->json(array('success' => false,'id' => '','message' => 'Please insert qty'),200);
    	}

    	$pogeneralserviceitem=$this->pogeneralserviceitem->update($id,$request->except(['id','asset_desc']));
		if($pogeneralserviceitem){
			return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
		}
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->pogeneralserviceitem->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
		else{
			 return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
		}
    }

	public function getAsset()
    {
        $machine=$this->assetquantitycost
        ->join('asset_acquisitions',function($join){
            $join->on('asset_acquisitions.id','=','asset_quantity_costs.asset_acquisition_id');
        })
        ->leftJoin('asset_technical_features',function($join){
            $join->on('asset_acquisitions.id','=','asset_technical_features.asset_acquisition_id');
        })
        ->when(request('brand'), function ($q) {
        return $q->where('asset_acquisitions.brand', 'like','%'.request('brand', 0).'%');
        })
        ->when(request('machine_no'), function ($q) {
        return $q->where('asset_quantity_costs.custom_no', '=',request('machine_no', 0));
        })
        //->where([['asset_acquisitions.production_area_id','=',20]])
        ->orderBy('asset_acquisitions.id','asc')
        ->orderBy('asset_quantity_costs.id','asc')
        ->get([
            'asset_quantity_costs.*',
            'asset_acquisitions.prod_capacity',
            'asset_acquisitions.name as asset_name',
            'asset_acquisitions.asset_group',
            'asset_acquisitions.origin',
            'asset_acquisitions.brand',
            'asset_technical_features.dia_width',
            'asset_technical_features.gauge',
            'asset_technical_features.extra_cylinder',
            'asset_technical_features.no_of_feeder'
        ])
		->map(function($machine){
			$machine->asset_no= str_pad($machine->id,6,0,STR_PAD_LEFT );
			$machine->asset_desc=$machine->asset_no.";".$machine->asset_name.";".$machine->asset_group.";".$machine->brand." ".$machine->origin;
			return $machine;
		});
        echo json_encode($machine);
    }
    
}
