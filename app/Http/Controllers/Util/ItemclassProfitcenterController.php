<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\ItemclassProfitcenterRepository;
use App\Repositories\Contracts\Util\ProfitcenterRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Library\Template;
use App\Http\Requests\ItemclassProfitcenterRequest;

class ItemclassProfitcenterController extends Controller {

    private $itemclassprofitcenter;
	private $profitcenter;
    private $itemclass;

    public function __construct(ItemclassProfitcenterRepository $itemclassprofitcenter, ItemclassRepository $itemclass,ProfitcenterRepository $profitcenter) {
        $this->itemclassprofitcenter = $itemclassprofitcenter;
		$this->profitcenter = $profitcenter;
        $this->itemclass = $itemclass;
        $this->middleware('auth');
        // $this->middleware('permission:view.itemclassprofitcenters',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.itemclassprofitcenters', ['only' => ['store']]);
        // $this->middleware('permission:edit.itemclassprofitcenters',   ['only' => ['update']]);
        // $this->middleware('permission:delete.itemclassprofitcenters', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $itemclass=array_prepend(array_pluck($this->itemclass->get(),'name','id'),'-Select-','');
        $itemclassprofitcenters=array();
        $rows=$this->itemclassprofitcenter->get();
        foreach ($rows as $row) {
          $itemclassprofitcenter['id']=$row->id;
          $itemclassprofitcenter['name']=$row->name;
          $itemclassprofitcenter['code']=$row->code;
          $itemclassprofitcenter['itemclass']=$itemclass[$row->itemclass_id];
          array_push($itemclassprofitcenters,$itemclassprofitcenter);
        }
        echo json_encode($itemclassprofitcenters);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$profitcenter=$this->profitcenter
		->leftJoin('itemclass_profitcenters', function($join)  {
			$join->on('itemclass_profitcenters.profitcenter_id', '=', 'profitcenters.id');
			$join->where('itemclass_profitcenters.itemclass_id', '=', request('itemclass_id',0));
			$join->whereNull('itemclass_profitcenters.deleted_at');
		})
        //->where([['profitcenters.nature_type','=',2]])
        ->orderBy('profitcenters.name','asc')
		->get([
		'profitcenters.id',
		'profitcenters.name',
		'itemclass_profitcenters.id as itemclass_profitcenter_id'
		]);
		$saved = $profitcenter->filter(function ($value) {
			if($value->itemclass_profitcenter_id){
				return $value;
			}
		})->values();
		
		$new = $profitcenter->filter(function ($value) {
			if(!$value->itemclass_profitcenter_id){
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
    public function store(ItemclassProfitcenterRequest $request) {

		foreach($request->profitcenter_id as $index=>$val){
				$itemclassprofitcenter = $this->itemclassprofitcenter->updateOrCreate(
				['itemclass_id' => $request->itemclass_id, 'profitcenter_id' => $request->profitcenter_id[$index]]);
		}
        if ($itemclassprofitcenter) {
            return response()->json(array('success' => true, 'id' => $itemclassprofitcenter->id, 'message' => 'Save Successfully'), 200);
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
        $itemclassprofitcenter = $this->itemclassprofitcenter->find($id);
        $row ['fromData'] = $itemclassprofitcenter;
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
    public function update(ItemclassProfitcenterRequest $request, $id) {
        $itemclassprofitcenter = $this->itemclassprofitcenter->update($id, $request->except(['id']));
        if ($itemclassprofitcenter) {
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
        if ($this->itemclassprofitcenter->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
