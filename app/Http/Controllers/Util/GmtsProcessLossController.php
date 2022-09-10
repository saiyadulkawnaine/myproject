<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\GmtsProcessLossRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Library\Template;
use App\Http\Requests\GmtsProcessLossRequest;

class GmtsProcessLossController extends Controller {

    private $gmtsprocessloss;
    private $company;
    private $buyer;
    private $productionprocess;
    private $embelishmenttype;

    public function __construct(GmtsProcessLossRepository $gmtsprocessloss,CompanyRepository $company,BuyerRepository $buyer,ProductionProcessRepository $productionprocess,EmbelishmentTypeRepository $embelishmenttype) {
        $this->gmtsprocessloss = $gmtsprocessloss;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->productionprocess = $productionprocess;
        $this->embelishmenttype = $embelishmenttype;

        $this->middleware('auth');
        $this->middleware('permission:view.gmtsprocesslosses',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.gmtsprocesslosses', ['only' => ['store']]);
        $this->middleware('permission:edit.gmtsprocesslosses',   ['only' => ['update']]);
        $this->middleware('permission:delete.gmtsprocesslosses', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      //$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      //$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
      //$gmtsprocesslosses=array();
      $rows=$this->gmtsprocessloss
	  ->join('companies',function($join){
		  $join->on("companies.id","=","gmts_process_losses.company_id");
	  })
	   ->join('buyers',function($join){
		  $join->on("buyers.id","=","gmts_process_losses.buyer_id");
	  })
	  ->orderBy("gmts_process_losses.id","desc")
	  ->get([
	  'gmts_process_losses.*',
	  'companies.code as company',
	  'buyers.name as buyer'
	  ]);
      /*foreach ($rows as $row) {
        $gmtsprocessloss['id']=$row->id;
        $gmtsprocessloss['rangestart']=$row->gmt_qty_range_start;
        $gmtsprocessloss['rangeend']=$row->gmt_qty_range_end;
        $gmtsprocessloss['company']=$company[$row->company_id];
        $gmtsprocessloss['buyer']=$buyer[$row->buyer_id];
        array_push($gmtsprocesslosses,$gmtsprocessloss);
      }*/
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $productionprocess=array_prepend(array_pluck($this->productionprocess->get(),'process_name','id'),'-Select-','');
        $embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->get(),'name','id'),'-Select-','');
        return Template::loadView("Util.GmtsProcessLoss",['company'=>$company,'buyer'=>$buyer,'productionprocess'=>$productionprocess,'embelishmenttype'=>$embelishmenttype]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GmtsProcessLossRequest $request) {
        $gmtsprocessloss = $this->gmtsprocessloss->create($request->except(['id']));
        if ($gmtsprocessloss) {
            return response()->json(array('success' => true, 'id' => $gmtsprocessloss->id, 'message' => 'Save Successfully'), 200);
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
        $gmtsprocessloss = $this->gmtsprocessloss->find($id);
        $row ['fromData'] = $gmtsprocessloss;
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
    public function update(GmtsProcessLossRequest $request, $id) {
        $gmtsprocessloss = $this->gmtsprocessloss->update($id, $request->except(['id']));
        if ($gmtsprocessloss) {
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
        if ($this->gmtsprocessloss->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
