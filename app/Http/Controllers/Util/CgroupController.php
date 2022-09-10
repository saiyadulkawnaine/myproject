<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;


use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CgroupRepository;
use App\Library\Template;
use App\Http\Requests\CgroupRequest;


class CgroupController extends Controller
{
   
   
   private $cgroup;
	
	public function __construct(CgroupRepository $cgroup) 
	{
		$this->cgroup = $cgroup;
		$this->middleware('auth');
		$this->middleware('permission:view.groups',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.groups', ['only' => ['store']]);
        $this->middleware('permission:edit.groups',   ['only' => ['update']]);
		$this->middleware('permission:delete.groups', ['only' => ['destroy']]);
	}
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$str2=date('Y-m-d');
        $yesterday = date('Y-m-d',strtotime("-1 days"));
        $month_start_date=date('Y-m',strtotime($yesterday))."-01";
        $y=date('Y',strtotime($yesterday));
        $m=date('m',strtotime($yesterday));
        $cal = cal_days_in_month(CAL_GREGORIAN, $m,$y);
        echo $cal;*/
        echo json_encode($this->cgroup->get());

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Template::loadView("Util.Cgroup");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CgroupRequest $request)
    {
        $cgroup=$this->cgroup->create($request->except(['id']));
		if($cgroup){
			return response()->json(array('success' => true,'id' =>  $cgroup->id,'message' => 'Save Successfully'),200);
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
       $cgroup = $this->cgroup->find($id);
	   $row ['fromData'] = $cgroup;
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
    public function update(CgroupRequest $request, $id)
    {
        $cgroup=$this->cgroup->update($id,$request->except(['id']));
		if($cgroup){
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
        if($this->cgroup->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }
}
