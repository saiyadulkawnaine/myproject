<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanySubsectionRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Library\Template;
use App\Http\Requests\CompanySubsectionRequest;

class CompanySubsectionController extends Controller {

    private $companysubsection;
	private $company;
    private $subsection;

    public function __construct(CompanySubsectionRepository $companysubsection, SubsectionRepository $subsection,CompanyRepository $company) {
        $this->companysubsection = $companysubsection;
		$this->company = $company;
        $this->subsection = $subsection;
        $this->middleware('auth');
       /*  $this->middleware('permission:view.companysubsections',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.companysubsections', ['only' => ['store']]);
        $this->middleware('permission:edit.companysubsections',   ['only' => ['update']]);
        $this->middleware('permission:delete.companysubsections', ['only' => ['destroy']]); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $subsection=array_prepend(array_pluck($this->subsection->get(),'name','id'),'-Select-','');
        $companysubsections=array();
        $rows=$this->companysubsection->get();
        foreach ($rows as $row) {
          $companysubsection['id']=$row->id;
          $companysubsection['name']=$row->name;
          $companysubsection['code']=$row->code;
          $companysubsection['subsection']=$subsection[$row->subsection_id];
          array_push($companysubsections,$companysubsection);
        }
        echo json_encode($companysubsections);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$company=$this->company
		->leftJoin('company_subsections', function($join)  {
			$join->on('company_subsections.company_id', '=', 'companies.id');
			$join->where('company_subsections.subsection_id', '=', request('subsection_id',0));
			$join->whereNull('company_subsections.deleted_at');
		})
		->get([
		'companies.id',
		'companies.name',
		'company_subsections.id as company_subsection_id'
		]);
		$saved = $company->filter(function ($value) {
			if($value->company_subsection_id){
				return $value;
			}
		})->values();
		
		$new = $company->filter(function ($value) {
			if(!$value->company_subsection_id){
				return $value;
			}
		})->values();
		$row ['unsaved'] = $new;
		$row ['saved'] = $saved;
		echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanySubsectionRequest $request) {
		foreach($request->company_id as $index=>$val){
				$companysubsection = $this->companysubsection->updateOrCreate(
				['subsection_id' => $request->subsection_id, 'company_id' => $request->company_id[$index]]);
		}
        if ($companysubsection) {
            return response()->json(array('success' => true, 'id' => $companysubsection->id, 'message' => 'Save Successfully'), 200);
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
        $companysubsection = $this->companysubsection->find($id);
        $row ['fromData'] = $companysubsection;
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
    public function update(CompanySubsectionRequest $request, $id) {
        $companysubsection = $this->companysubsection->update($id, $request->except(['id']));
        if ($companysubsection) {
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
        if ($this->companysubsection->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
