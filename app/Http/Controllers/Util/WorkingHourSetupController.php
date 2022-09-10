<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\WorkingHourSetupRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\FloorRepository;
use App\Library\Template;
use App\Http\Requests\WorkingHourSetupRequest;

class WorkingHourSetupController extends Controller
{
    private $workinghoursetup;
    private $company;
    private $location;
    private $floor;    

    public function __construct(WorkingHourSetupRepository $workinghoursetup,CompanyRepository $company, LocationRepository $location, FloorRepository $floor) {
        $this->workinghoursetup = $workinghoursetup;
        $this->company = $company;
        $this->location = $location;
        $this->floor = $floor;        
        $this->middleware('auth');
        /*$this->middleware('permission:view.workinghoursetups',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.workinghoursetups', ['only' => ['store']]);
        $this->middleware('permission:edit.workinghoursetups',   ['only' => ['update']]);
        $this->middleware('permission:delete.workinghoursetups', ['only' => ['destroy']]);*/
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $floor=array_prepend(array_pluck($this->floor->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');        
        $workinghoursetups=array();
        $rows=$this->workinghoursetup->get();
        foreach ($rows as $row){
            $workinghoursetup['id']=$row->id;
            $workinghoursetup['company']=$company[$row->company_id];
            $workinghoursetup['location']=$location[$row->location_id];
            $workinghoursetup['floor_id']=$floor[$row->floor_id];
            $workinghoursetup['shiftname_id']=$shiftname[$row->shiftname_id];      
            $workinghoursetup['shift_start']=$row->shift_start;            
            $workinghoursetup['shift_end']=$row->shift_end;            
            $workinghoursetup['lunch_start']=$row->lunch_start;            
            $workinghoursetup['lunch_duration']=$row->lunch_duration;    
            array_push($workinghoursetups,$workinghoursetup);
        }
        echo json_encode($workinghoursetups);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $floor=array_prepend(array_pluck($this->floor->get(),'name','id'),'-Select-','');
		$location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $shiftname=array_prepend(config('bprs.shiftname'),'-Select-','');
        return Template::loadView("Util.WorkingHourSetup",['company'=>$company,'location'=>$location,'floor'=>$floor, 'shiftname'=> $shiftname]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WorkingHourSetupRequest $request) {
        $workinghoursetup= $this->workinghoursetup->create($request->except(['id']));
        if ($workinghoursetup) {
            return response()->json(array('success' => true, 'id' => $workinghoursetup->id, 'message' => 'Save Successfully'), 200);
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
        $workinghoursetup = $this->workinghoursetup->find($id);
        $row ['fromData'] = $workinghoursetup;
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
    public function update(WorkingHourSetupRequest $request, $id) {
        $workinghoursetup = $this->workinghoursetup->update($id, $request->except(['id']));
        if ($workinghoursetup) {
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
        if ($this->workinghoursetup->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
