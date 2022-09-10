<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Contracts\Util\FloorRepository;

use App\Library\Template;
use App\Http\Requests\SubsectionRequest;
use App\Repositories\Contracts\HRM\EmployeeRepository;
use App\Repositories\Contracts\Util\UomRepository;

class SubsectionController extends Controller
{
	private $subsection;
	private $floor;
	private $employee;
	private $uom;

	public function __construct(SubsectionRepository $subsection, FloorRepository $floor, EmployeeRepository $employee, UomRepository $uom)
	{
		$this->subsection = $subsection;
		$this->floor = $floor;
		$this->employee = $employee;
		$this->uom = $uom;
		$this->middleware('auth');
		$this->middleware('permission:view.subsections',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.subsections', ['only' => ['store']]);
        $this->middleware('permission:edit.subsections',   ['only' => ['update']]);
		$this->middleware('permission:delete.subsections', ['only' => ['destroy']]);
	}
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $floor=array_prepend(array_pluck($this->floor->get(),'name','id'),'-Select-','');
      $employee=array_prepend(array_pluck($this->employee->get(),'name','id'),'','');
      $yesno=config('bprs.yesno');
      $gmtcomplexity=config('bprs.gmtcomplexity');
      $uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'','');
      $status=config('bprs.status');
      $subsections=array();
      $rows=$this->subsection
      ->orderBy('subsections.id','desc')
      ->get();
      foreach ($rows as $row) {
        $subsection['id']=$row->id;
			  $subsection['name']=$row->name;
				$subsection['code']=$row->code;
        $subsection['floor']=isset($floor[$row->floor_id])?$floor[$row->floor_id]:'';
        $subsection['employee_id']=isset($employee[$row->employee_id])?$employee[$row->employee_id]:'';
        $subsection['is_treat_sewing_line']=isset($yesno[$row->is_treat_sewing_line_id])?$yesno[$row->is_treat_sewing_line_id]:'';
        $subsection['is_poly_layout']=isset($yesno[$row->is_poly_layout_id])?$yesno[$row->is_poly_layout_id]:'';
        $subsection['projected_line_id']=isset($yesno[$row->projected_line_id])?$yesno[$row->projected_line_id]:'';
        $subsection['qty']=number_format($row->qty,2,'.',',');
        $subsection['no_of_operator']=number_format($row->no_of_operator,2,'.',',');
        $subsection['no_of_helper']=number_format($row->no_of_helper,2,'.',',');
        $subsection['amount']=number_format($row->amount,2,'.',',');
        $subsection['uom_id']=isset($uom[$row->uom_id])?$uom[$row->uom_id]:'';
        $subsection['gmt_complexity_id']=isset($gmtcomplexity[$row->gmt_complexity_id])?$gmtcomplexity[$row->gmt_complexity_id]:'';
        $subsection['status_id']=isset($status[$row->status_id])?$status[$row->status_id]:'';

        array_push($subsections,$subsection);
      }
        echo json_encode($subsections);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$yesno=array_prepend(config('bprs.yesno'),'-Select-','');
		$gmtcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');
		$status=array_prepend(array_only(config('bprs.status'),[0,1]),'-Select-','');
		$floors=array_prepend(array_pluck($this->floor->get(),'name','id'),'-Select-','');
		$employee=array_prepend(array_pluck($this->employee->get(),'name','id'),'','');
		$uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'','');
        //$productionsource=config('bprs.productionsource');
        $productionsource=array_prepend([1=>"Plant A",5=>"Plant B"],'-Select-','');
		return Template::loadView("Util.Subsection",['yesno'=>$yesno,'floors'=>$floors,'gmtcomplexity'=>$gmtcomplexity,'status'=>$status,'uom'=>$uom,'employee'=>$employee , 'productionsource'=>$productionsource]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubsectionRequest $request)
    {
        $subsection=$this->subsection->create($request->except(['id']));
		if($subsection){
			return response()->json(array('success' => true,'id' =>  $subsection->id,'message' => 'Save Successfully'),200);
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
       $subsection = $this->subsection->find($id);
	   $row ['fromData'] = $subsection;
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
    public function update(SubsectionRequest $request, $id)
    {
        $subsection=$this->subsection->update($id,$request->except(['id']));
		if($subsection){
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
        if($this->subsection->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }
}
