<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\IncentiveRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\DivisionRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\SectionRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\DesignationRepository;

use App\Library\Template;
use App\Http\Requests\IncentiveRequest;

class IncentiveController extends Controller {

    private $incentive;
    private $company;
    private $division;
    private $department;
    private $section;
    private $productionprocess;
  	private $location;
  	private $designation;

    public function __construct(IncentiveRepository $incentive, CompanyRepository $company,DivisionRepository $division, DepartmentRepository $department,SectionRepository $section,ProductionProcessRepository $productionprocess,LocationRepository $location,DesignationRepository $designation) {
        $this->incentive = $incentive;
        $this->company = $company;
        $this->division = $division;
        $this->department = $department;
        $this->section = $section;
        $this->productionprocess = $productionprocess;
  	    $this->location = $location;
  		  $this->designation = $designation;


        $this->middleware('auth');
        $this->middleware('permission:view.incentives',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.incentives', ['only' => ['store']]);
        $this->middleware('permission:edit.incentives',   ['only' => ['update']]);
        $this->middleware('permission:delete.incentives', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-',0);
	  $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
      $division=array_prepend(array_pluck($this->division->get(),'name','id'),'-Select-',0);
      $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-',0);
      $section=array_prepend(array_pluck($this->section->get(),'name','id'),'-Select-',0);
      $productionprocess=array_prepend(array_pluck($this->productionprocess->get(),'process_name','id'),'-Select-',0);
      $incentivebasis=array_prepend(config('bprs.incentivebasis'),'-Select-',0);
      $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
      $incentives=array();
      $rows=$this->incentive->get();
      foreach ($rows as $row) {
        $incentive['id']=$row->id;
        $incentive['company']=$company[$row->company_id];
        $incentive['location']=$location[$row->location_id];
        $incentive['division']=$division[$row->division_id];
        $incentive['department']=$department[$row->department_id];
        $incentive['section']=$section[$row->section_id];
        $incentive['productionprocess']=$productionprocess[$row->productionprocess_id];
        $incentive['incentivebasis']=$incentivebasis[$row->incentivebasis_id];
        $incentive['designation']=$designation[$row->designation_id];
        array_push($incentives,$incentive);
      }
        echo json_encode($incentives);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-',0);
		$location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $division=array_prepend(array_pluck($this->division->get(),'name','id'),'-Select-',0);
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-',0);
        $section=array_prepend(array_pluck($this->section->get(),'name','id'),'-Select-',0);
        $productionprocess=array_prepend(array_pluck($this->productionprocess->get(),'process_name','id'),'-Select-',0);
        $incentivebasis=array_prepend(config('bprs.incentivebasis'),'-Select-',0);
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        return Template::loadView("Util.Incentive",['company'=>$company,'location'=>$location,'division'=>$division,'department'=>$department,'section'=>$section,'productionprocess'=>$productionprocess,'incentivebasis'=>$incentivebasis,'designation'=>$designation]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IncentiveRequest $request) {
        $incentive = $this->incentive->create($request->except(['id']));
        if ($incentive) {
            return response()->json(array('success' => true, 'id' => $incentive->id, 'message' => 'Save Successfully'), 200);
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
        $incentive = $this->incentive->find($id);
        $row ['fromData'] = $incentive;
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
    public function update(IncentiveRequest $request, $id) {
        $incentive = $this->incentive->update($id, $request->except(['id']));
        if ($incentive) {
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
        if ($this->incentive->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
