<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CapacityDistRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Library\Template;
use App\Http\Requests\CapacityDistRequest;

class CapacityDistController extends Controller {

    private $capacitydist;
    private $company;
    private $itemcategory;

    public function __construct(CapacityDistRepository $capacitydist, CompanyRepository $company, ItemcategoryRepository $itemcategory) {
        $this->capacitydist = $capacitydist;
        $this->company = $company;
        $this->itemcategory = $itemcategory;

        $this->middleware('auth');
        $this->middleware('permission:view.capacitydists',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.capacitydists', ['only' => ['store']]);
        $this->middleware('permission:edit.capacitydists',   ['only' => ['update']]);
        $this->middleware('permission:delete.capacitydists', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $itemcategory=array_prepend(array_pluck($this->itemcategory->get(),'name','id'),'-Select-','');
      $productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
      $week=array_prepend(config('bprs.week'),'-Select-','');
      $capacitydists=array();
      $rows=$this->capacitydist->get();
      foreach($rows as $row){
        $capacitydist['id']=$row->id;
        $capacitydist['company']=$company[$row->company_id];
        $capacitydist['prodtype']=$itemcategory[$row->prod_type_id];
        $capacitydist['prodsource']=$productionsource[$row->prod_source_id];
        $capacitydist['week']=$week[$row->week_id];
        $capacitydist['year']=$row->year;
        $capacitydist['mktsmv']=$row->mkt_smv;
        $capacitydist['mktpcs']=$row->mkt_pcs;
        $capacitydist['prodsmv']=$row->prod_smv;
        $capacitydist['prodpcs']=$row->prod_pcs;
        array_push($capacitydists,$capacitydist);
      }
        echo json_encode($capacitydists);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $itemcategory=array_prepend(array_pluck($this->itemcategory->get(),'name','id'),'-Select-','');
        $location=array_prepend(config('bprs.location'),'-Select-','');
        $productionsource=array_prepend(config('bprs.productionsource'),'-Select-','');
        $week=array_prepend(config('bprs.week'),'-Select-','');
        return Template::loadView("Util.CapacityDist",['company'=>$company,'location'=>$location,'itemcategory'=>$itemcategory,'productionsource'=>$productionsource,'week'=>$week]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CapacityDistRequest $request) {
        $capacitydist = $this->capacitydist->create($request->except(['id']));
        if ($capacitydist) {
            return response()->json(array('success' => true, 'id' => $capacitydist->id, 'message' => 'Save Successfully'), 200);
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
        $capacitydist = $this->capacitydist->find($id);
        $row ['fromData'] = $capacitydist;
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
    public function update(CapacityDistRequest $request, $id) {
        $capacitydist = $this->capacitydist->update($id, $request->except(['id']));
        if ($capacitydist) {
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
        if ($this->capacitydist->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
