<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\OperationRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\ResourceRepository;
use App\Repositories\Contracts\Util\AttachmentRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\YarncountRepository;
use App\Library\Template;
use App\Http\Requests\OperationRequest;

class OperationController extends Controller
{
    private $operation;
    private $gmtspart;
    private $resource;
    private $attachment;
    private $autoyarn;
    private $yarncount;

    public function __construct(OperationRepository $operation,GmtspartRepository $gmtspart, AutoyarnRepository $autoyarn, YarncountRepository $yarncount, ResourceRepository $resource, AttachmentRepository $attachment, ProductionProcessRepository $productionarea) {
        $this->operation = $operation;
        $this->gmtspart = $gmtspart;
        $this->resource = $resource;
        $this->autoyarn = $autoyarn;
        $this->yarncount = $yarncount;
        $this->attachment = $attachment;
	    $this->productionarea = $productionarea;

        $this->middleware('auth');
        $this->middleware('permission:view.operations',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.operations', ['only' => ['store']]);
        $this->middleware('permission:edit.operations',   ['only' => ['update']]);
        $this->middleware('permission:delete.operations', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $gmtcategory=array_prepend(config('bprs.gmtcategory'),'-Select-','');
      $deptcategory=array_prepend(config('bprs.deptcategory'),'-Select-','');
      $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
      $smvbasis=array_prepend(config('bprs.smvbasis'),'-Select-','');
      $resource=array_prepend(array_pluck($this->resource->get(),'name','id'),'-Select-','');
	  $productionarea=array_prepend(array_pluck($this->productionarea->get(),'process_name','id'),'-Select-','');
      $operationtype=array_prepend(config('bprs.operationtype'),'-Select-','');
      $attachment=array_prepend(array_pluck($this->attachment->get(),'name','id'),'-Select-','');
      $autoyarn=$this->autoyarn->join('autoyarnratios', function($join) {
        $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
        })
        ->join('constructions', function($join)  {
            $join->on('autoyarns.construction_id', '=', 'constructions.id');
        })
        ->join('compositions',function($join){
            $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
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
        $desDropdown[$key]=$val.",".implode(",",$fabricCompositionArr[$key]);
        }
      $operations=array();
      $rows=$this->operation->get();
      foreach ($rows as $row) {
        $operation['id']=$row->id;
        $operation['name']=$row->name;
        $operation['code']=$row->code;
        $operation['gmtcategory']=$gmtcategory[$row->gmt_category_id];
        $operation['deptcategory']=$deptcategory[$row->dept_category_id];
        $operation['gmtspart']=$gmtspart[$row->gmtspart_id];
        $operation['smvbasis']=$smvbasis[$row->smv_basis_id];
        $operation['productionarea_id']=isset($productionarea[$row->productionarea_id])?$productionarea[$row->productionarea_id]:'';
        $operation['autoyarn_id']=isset($desDropdown[$row->autoyarn_id])?$desDropdown[$row->autoyarn_id]:'';
        $operation['resource']=$resource[$row->resource_id];
        $operation['machinesmv']=$row->machine_smv;
        $operation['manualsmv']=$row->manual_smv;
        $operation['seam_length']=$row->seam_length;
        array_push($operations,$operation);
      }
        echo json_encode($operations);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
	$productionarea=array_prepend(array_pluck($this->productionarea->whereIn('production_area_id',[40,55,65,70])->get(),'process_name','id'),'-Select-','');
        $gmtcategory=array_prepend(config('bprs.gmtcategory'),'-Select-',0);
        $deptcategory=array_prepend(config('bprs.deptcategory'),'-Select-',0);
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'',0);
        $smvbasis=array_prepend(config('bprs.smvbasis'),'-Select-',0);
        $resource=array_prepend(array_pluck($this->resource->get(),'name','id'),'',0);
        $operationtype=array_prepend(config('bprs.operationtype'),'-Select-',0);
        $attachment=array_prepend(array_pluck($this->attachment->get(),'name','id'),'-Select-',0);

		$autoyarn=$this->autoyarn->join('autoyarnratios', function($join) use ($request) {
		    $join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
		})
		->join('constructions', function($join) use ($request) {
		    $join->on('autoyarns.construction_id', '=', 'constructions.id');
		})
		->join('compositions',function($join){
		    $join->on('compositions.id','=','autoyarnratios.composition_id');
		})
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
		$desDropdown[$key]=$val.",".implode(",",$fabricCompositionArr[$key]);
		}
        return Template::loadView("Util.Operation",['gmtcategory'=>$gmtcategory,'deptcategory'=>$deptcategory,'gmtspart'=>$gmtspart,'smvbasis'=>$smvbasis,'resource'=>$resource,'productionarea'=>$productionarea,'operationtype'=>$operationtype,'attachment'=>$attachment,'autoyarn'=>$autoyarn]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OperationRequest $request) {
        $operation = $this->operation->create($request->except(['id','fabrication']));
        if ($operation) {
            return response()->json(array('success' => true, 'id' => $operation->id, 'message' => 'Save Successfully'), 200);
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
        $autoyarn=$this->autoyarn
	   ->join('autoyarnratios', function($join)  {
		$join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
		})
		->join('constructions', function($join)  {
		$join->on('autoyarns.construction_id', '=', 'constructions.id');
		})
		->join('compositions',function($join){
		$join->on('compositions.id','=','autoyarnratios.composition_id');
		})
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
		    $desDropdown[$key]=$val.",".implode(",",$fabricCompositionArr[$key]);
		}
        $operation = $this->operation
        ->where([['operations.id','=',$id]])
        ->get([
            'operations.*'
        ])
        ->map(function ($operation) use($desDropdown) {
			$operation->fabrication= isset($desDropdown[$operation->autoyarn_id])?$desDropdown[$operation->autoyarn_id]:'';
			return $operation;
		})
        ->first();
        $row ['fromData'] = $operation;
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
    public function update(OperationRequest $request, $id) {
        $res = $this->operation->update($id, $request->except(['id','fabrication']));
        if ($res) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
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
        if ($this->operation->delete($id)) {
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
        }
        foreach($fab as $row){
            array_push($fabs,$row);
        }
        echo json_encode($fabs);
	  
	}
}
