<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sales\SalesOrderItemRepository;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Library\Template;
use App\Http\Requests\SalesOrderItemRequest;

class SalesOrderItemController extends Controller {

    private $salesorderitem;
    private $salesordercountry;

    public function __construct(SalesOrderItemRepository $salesorderitem, SalesOrderCountryRepository $salesordercountry) {
        $this->salesorderitem = $salesorderitem;
        $this->salesordercountry = $salesordercountry;
        $this->middleware('auth');
        $this->middleware('permission:view.salesorderitems',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.salesorderitems', ['only' => ['store']]);
        $this->middleware('permission:edit.salesorderitems',   ['only' => ['update']]);
        $this->middleware('permission:delete.salesorderitems', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
    $salesorderitems=array();
    $rows=$this->salesorderitem->getAll();
  		foreach($rows as $row)
      {
        $salesorderitem['id']=	$row->id;
        $salesorderitem['job_id']=	$row->job_id;
        $salesorderitem['sale_order_id']=	$row->sale_order_id;
        $salesorderitem['sale_order_country_id']=	$row->sale_order_country_id;
        $salesorderitem['name']=	$row->country_name;
        $salesorderitem['qty']=	$row->qty;
        if($row->qty && $row->amount){
			$salesorderitem['rate']=	$row->amount/$row->qty;
		}else{
			$salesorderitem['rate']='';
		}
        $salesorderitem['amount']=	$row->amount;
        $salesorderitem['article']=	$row->article_no;
        array_push($salesorderitems,$salesorderitem);
      }
      echo json_encode($salesorderitems);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      //$salesordercountry=array_prepend(array_pluck($this->salesordercountry->get(),'name','id'),'-Select-','');
      //$gmtcolor=array_prepend(array_pluck($this->gmtcolor->get(),'name','id'),'-Select-','');
       return Template::loadView('Sales.SalesOrderItem');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SalesOrderItemRequest $request) {
        $salesorderitem = $this->salesorderitem->create($request->except(['id']));
        if ($salesorderitem) {
            return response()->json(array('success' => true, 'id' => $salesorderitem->id, 'message' => 'Save Successfully'), 200);
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
		$stylesize=$this->salesorderitem->getById($id);
        $row ['fromData'] = $stylesize[0];
        $dropdown['sizetable'] = "'".Template::loadView('Sales.SalesOrderSize', ['stylesize'=>$stylesize])."'";
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
    public function update(SalesOrderItemRequest $request, $id) {
        $salesorderitem = $this->salesorderitem->update($id, $request->except(['id']));
        if ($salesorderitem) {
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
        if ($this->salesorderitem->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
