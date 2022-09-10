<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyProfitcenterRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\ProfitcenterRepository;

use App\Library\Template;
use App\Http\Requests\CompanyProfitcenterRequest;

class CompanyProfitcenterController extends Controller {

    private $companyprofitcenter;
	private $company;
    private $profitcenter;

    public function __construct(CompanyProfitcenterRepository $companyprofitcenter, ProfitcenterRepository $profitcenter,CompanyRepository $company) {
        $this->companyprofitcenter = $companyprofitcenter;
		$this->company = $company;
        $this->profitcenter = $profitcenter;
        $this->middleware('auth');
        // $this->middleware('permission:view.companyprofitcenters',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.companyprofitcenters', ['only' => ['store']]);
        // $this->middleware('permission:edit.companyprofitcenters',   ['only' => ['update']]);
        // $this->middleware('permission:delete.companyprofitcenters', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $profitcenter=array_prepend(array_pluck($this->profitcenter->get(),'name','id'),'-Select-','');
        $companyprofitcenters=array();
        $rows=$this->companyprofitcenter->get();
        foreach ($rows as $row) {
          $companyprofitcenter['id']=$row->id;
          $companyprofitcenter['name']=$row->name;
          $companyprofitcenter['code']=$row->code;
          $companyprofitcenter['profitcenter']=$profitcenter[$row->profitcenter_id];
          array_push($companyprofitcenters,$companyprofitcenter);
        }
        echo json_encode($companyprofitcenters);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$company=$this->company
		->leftJoin('company_profitcenters', function($join)  {
			$join->on('company_profitcenters.company_id', '=', 'companies.id');
			$join->where('company_profitcenters.profitcenter_id', '=', request('profitcenter_id',0));
			$join->whereNull('company_profitcenters.deleted_at');
		})
		->get([
		'companies.id',
		'companies.name',
		'company_profitcenters.id as company_profitcenter_id'
		]);
		$saved = $company->filter(function ($value) {
			if($value->company_profitcenter_id){
				return $value;
			}
		})->values();
		
		$new = $company->filter(function ($value) {
			if(!$value->company_profitcenter_id){
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
    public function store(CompanyProfitcenterRequest $request) {
		foreach($request->company_id as $index=>$val){
            $companyprofitcenter = $this->companyprofitcenter->updateOrCreate(
				['profitcenter_id' => $request->profitcenter_id, 'company_id' => $request->company_id[$index]]);
		}
        if ($companyprofitcenter) {
            return response()->json(array('success' => true, 'id' => $companyprofitcenter->id, 'message' => 'Save Successfully'), 200);
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
        $companyprofitcenter = $this->companyprofitcenter->find($id);
        $row ['fromData'] = $companyprofitcenter;
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
    public function update(CompanyProfitcenterRequest $request, $id) {
        $companyprofitcenter = $this->companyprofitcenter->update($id, $request->except(['id']));
        if ($companyprofitcenter) {
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
        if ($this->companyprofitcenter->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
