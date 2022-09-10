<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\StoreItemcategoryRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Contracts\Util\StoreRepository;
use App\Library\Template;
use App\Http\Requests\StoreItemcategoryRequest;

class StoreItemcategoryController extends Controller {

    private $itemcategory;
    private $storeitemcat;
    private $store;


    public function __construct(StoreItemcategoryRepository $storeitemcat,ItemcategoryRepository $itemcategory,StoreRepository $store) {
        $this->itemcategory = $itemcategory;
        $this->storeitemcat = $storeitemcat;
        $this->store = $store;
        
        
        $this->middleware('auth');
        $this->middleware('permission:view.storeitemcategories',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.storeitemcategories', ['only' => ['store']]);
        $this->middleware('permission:edit.storeitemcategories',   ['only' => ['update']]);
        $this->middleware('permission:delete.storeitemcategories', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $store=array_prepend(array_pluck($this->store->get(),'name','id'),'-Select-','');
        $storeitemcats=array();
        $rows=$this->$storeitemcat->get();
        foreach ($rows as $row) {
          $storeitemcat['id']=$row->id;
          $storeitemcat['name']=$row->name;
          $storeitemcat['code']=$row->code;
          $storeitemcat['store']=$store[$row->store_id];
          array_push($storeitemcats,$storeitemcat);
        }
        echo json_encode($storeitemcats);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$itemcategory=$this->itemcategory
		->leftJoin('store_itemcategories', function($join)  {
			$join->on('store_itemcategories.itemcategory_id', '=', 'itemcategories.id');
			$join->where('store_itemcategories.store_id', '=', request('store_id',0));
			$join->whereNull('store_itemcategories.deleted_at');
		})
		->get([
		'itemcategories.id',
		'itemcategories.name',
		'store_itemcategories.id as store_itemcategory_id'
		]);
		$saved = $itemcategory->filter(function ($value) {
			if($value->store_itemcategory_id){
				return $value;
			}
		})->values();
		
		$new = $itemcategory->filter(function ($value) {
			if(!$value->store_itemcategory_id){
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
    public function store(StoreItemcategoryRequest $request) {
		foreach($request->itemcategory_id as $index=>$val){
				$storeitemcat = $this->storeitemcat->updateOrCreate(
				['store_id' => $request->store_id, 'itemcategory_id' => $request->itemcategory_id[$index]]);
		}
        if ($storeitemcat) {
            return response()->json(array('success' => true, 'id' => $storeitemcat->id, 'message' => 'Save Successfully'), 200);
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
        $storeitemcat = $this->storeitemcat->find($id);
        $row ['fromData'] = $storeitemcat;
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
    public function update(StoreItemcategoryRequest $request, $id) {
        $storeitemcat = $this->storeitemcat->update($id, $request->except(['id']));
        if ($storeitemcat) {
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
        if ($this->storeitemcat->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
