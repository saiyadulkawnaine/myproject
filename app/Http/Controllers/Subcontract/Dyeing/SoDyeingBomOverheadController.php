<?php

namespace App\Http\Controllers\Subcontract\Dyeing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingBomRepository;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingBomOverheadRepository;
use App\Repositories\Contracts\System\Configuration\CostStandardHeadRepository;
use App\Library\Template;
use App\Http\Requests\Subcontract\Dyeing\SoDyeingBomOverheadRequest;

class SoDyeingBomOverheadController extends Controller {

   
    private $sodyeingbom;
    private $sodyeingbomfabric;
    private $coststandardhead;


    public function __construct(
      SoDyeingBomRepository $sodyeingbom,
      SoDyeingBomOverheadRepository $sodyeingbomoverhead,
      CostStandardHeadRepository $coststandardhead
    ) {
        $this->sodyeingbom = $sodyeingbom;
        $this->sodyeingbomoverhead = $sodyeingbomoverhead;
        $this->coststandardhead = $coststandardhead;
        $this->middleware('auth');
      
        //$this->middleware('permission:view.sodyeingbomoverheads',   ['only' => ['create', 'index','show']]);
        //$this->middleware('permission:create.sodyeingbomoverheads', ['only' => ['store']]);
        //$this->middleware('permission:edit.sodyeingbomoverheads',   ['only' => ['update']]);
        //$this->middleware('permission:delete.sodyeingbomoverheads', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $rows=$this->sodyeingbomoverhead
     ->join('acc_chart_ctrl_heads', function($join){
            $join->on('acc_chart_ctrl_heads.id', '=', 'so_dyeing_bom_overheads.acc_chart_ctrl_head_id');
      })
     ->join('so_dyeing_boms', function($join){
            $join->on('so_dyeing_boms.id', '=', 'so_dyeing_bom_overheads.so_dyeing_bom_id');
      })
     ->where([['so_dyeing_boms.id','=',request('so_dyeing_bom_id',0)]])
     ->get([
      'so_dyeing_bom_overheads.*',
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
    public function store(SoDyeingBomOverheadRequest $request) {
        $sodyeingbomoverhead=$this->sodyeingbomoverhead->create([
        'so_dyeing_bom_id'=>$request->so_dyeing_bom_id,
        'acc_chart_ctrl_head_id'=>$request->acc_chart_ctrl_head_id,
        'cost_per'=>$request->cost_per,
        'amount'=>$request->amount
        ]);

        if($sodyeingbomoverhead){
        return response()->json(array('success' => true,'id' =>  $sodyeingbomoverhead->id,'so_dyeing_bom_id' =>  $request->so_dyeing_bom_id,'message' => 'Save Successfully'),200);
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
        $rows=$this->sodyeingbomoverhead->find($id);
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
    public function update(SoDyeingBomOverheadRequest $request, $id) {
      /*if($request->liqure_ratio <= 0){
        return response()->json(array('success' => false,'id' => $id,'so_dyeing_bom_id' => $request->so_dyeing_bom_id,'message' => '0 Qty Not Allowed'),200);
      }
      if($request->liqure_wgt <= 0){
        return response()->json(array('success' => false,'id' => $id,'so_dyeing_bom_id' => $request->so_dyeing_bom_id,'message' => '0 Rate Not Allowed'),200);
      }*/

      $sodyeingbomoverhead=$this->sodyeingbomoverhead->update($id,[
        //'so_dyeing_bom_id'=>$request->so_dyeing_bom_id,
        'acc_chart_ctrl_head_id'=>$request->acc_chart_ctrl_head_id,
        'cost_per'=>$request->cost_per,
        'amount'=>$request->amount
        ]);

      if($sodyeingbomoverhead){
        return response()->json(array('success' => true,'id' => $id,'so_dyeing_bom_id' => $request->so_dyeing_bom_id,'message' => 'Update Successfully'),200);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
      if($this->sodyeingbomoverhead->delete($id)){
        return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
      }
    }

    public function getCost()
    {
        $coststandardhead=$this->coststandardhead
        ->join('cost_standards', function($join){
        $join->on('cost_standards.id', '=', 'cost_standard_heads.cost_standard_id');
        })
        ->where([['cost_standard_heads.acc_chart_ctrl_head_id','=',request('acc_chart_ctrl_head_id',0)]])
        ->where([['cost_standards.company_id','=',request('company_id',0)]])
        ->where([['cost_standards.configuration_type_id','=',140]])
        ->orderBy('cost_standard_heads.id','desc')
        ->get([
        'cost_standard_heads.*',
        ])
        ->first();
        if($coststandardhead)
        {
            echo $coststandardhead->cost_per;
        }
        else{
            echo 0;
        }

        //echo json_encode($rows);
    }
}