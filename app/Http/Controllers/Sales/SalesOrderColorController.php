<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Sales\SalesOrderColorRepository;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Library\Template;
use App\Http\Requests\SalesOrderColorRequest;

class SalesOrderColorController extends Controller {

    private $salesordercolor;
    private $salesordercountry;
    private $gmtcolor;

    public function __construct(SalesOrderColorRepository $salesordercolor, SalesOrderCountryRepository $salesordercountry) {
        $this->salesordercolor = $salesordercolor;
        $this->salesordercountry = $salesordercountry;
        $this->middleware('auth');
        $this->middleware('permission:view.salesordercolors',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.salesordercolors', ['only' => ['store']]);
        $this->middleware('permission:edit.salesordercolors',   ['only' => ['update']]);
        $this->middleware('permission:delete.salesordercolors', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
    $salesordercolors=array();
    $rows=$this->salesordercolor->getAll();
  		foreach($rows as $row)
      {
        $salesordercolor['id']=	$row->id;
        $salesordercolor['job_id']=	$row->job_id;
        $salesordercolor['sale_order_id']=	$row->sale_order_id;
        $salesordercolor['sale_order_country_id']=	$row->sale_order_country_id;
        $salesordercolor['name']=	$row->name;
        $salesordercolor['qty']=	$row->qty;
        if($row->qty && $row->amount){
			$salesordercolor['rate']=	$row->amount/$row->qty;
		}else{
			$salesordercolor['rate']='';
		}
        $salesordercolor['amount']=	$row->amount;
        $salesordercolor['sort']=	$row->sort_id;
        array_push($salesordercolors,$salesordercolor);
      }
      echo json_encode($salesordercolors);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $salesordercountry=array_prepend(array_pluck($this->salesordercountry->get(),'name','id'),'-Select-','');
      $gmtcolor=array_prepend(array_pluck($this->gmtcolor->get(),'name','id'),'-Select-','');
        return Template::loadView('Sales.SalesOrderColor', ['salesordercountry'=>$salesordercountry,'gmtcolor'=>$gmtcolor]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SalesOrderColorRequest $request) {
        $salesordercolor = $this->salesordercolor->create($request->except(['id']));
        if ($salesordercolor) {
            return response()->json(array('success' => true, 'id' => $salesordercolor->id, 'message' => 'Save Successfully'), 200);
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
		$stylesize=$this->salesordercolor->getById($id);
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
    public function update(SalesOrderColorRequest $request, $id) {
        $salesordercolor = $this->salesordercolor->update($id, $request->except(['id']));
        if ($salesordercolor) {
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
        if ($this->salesordercolor->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
