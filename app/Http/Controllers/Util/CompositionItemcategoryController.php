<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompositionItemcategoryRepository;
use App\Repositories\Contracts\Util\CompositionRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Library\Template;
use App\Http\Requests\CompositionItemcategoryRequest;

class CompositionItemcategoryController extends Controller {

    private $compositionitemcategory;
	private $composition;
    private $itemcategory;

    public function __construct(CompositionItemcategoryRepository $compositionitemcategory, ItemcategoryRepository $itemcategory,CompositionRepository $composition) {
        $this->compositionitemcategory = $compositionitemcategory;
		$this->composition = $composition;
        $this->itemcategory = $itemcategory;
        $this->middleware('auth');
        // $this->middleware('permission:view.compositionitemcategories',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.compositionitemcategories', ['only' => ['store']]);
        // $this->middleware('permission:edit.compositionitemcategories',   ['only' => ['update']]);
        // $this->middleware('permission:delete.compositionitemcategories', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $composition=array_prepend(array_pluck($this->composition->get(),'name','id'),'-Select-','');
        $compositionitemcategories=array();
        $rows=$this->compositionitemcategory->get();
        foreach ($rows as $row) {
          $compositionitemcategory['id']=$row->id;
          $compositionitemcategory['name']=$row->name;
          $compositionitemcategory['code']=$row->code;
          $compositionitemcategory['composition']=$composition[$row->composition_id];
          array_push($compositionitemcategories,$compositionitemcategory);
        }
        echo json_encode($compositionitemcategories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$itemcategory=$this->itemcategory
		->leftJoin('composition_itemcategories', function($join)  {
			$join->on('composition_itemcategories.itemcategory_id', '=', 'itemcategories.id');
			$join->where('composition_itemcategories.composition_id', '=', request('composition_id',0));
			$join->whereNull('composition_itemcategories.deleted_at');
		})
		->get([
		'itemcategories.id',
		'itemcategories.name',
		'itemcategories.code',
		'composition_itemcategories.id as composition_itemcategory_id'
		]);
		$saved = $itemcategory->filter(function ($value) {
			if($value->composition_itemcategory_id){
				return $value;
			}
		})->values();
		
		$new = $itemcategory->filter(function ($value) {
			if(!$value->composition_itemcategory_id){
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
    public function store(CompositionItemcategoryRequest $request) {
		foreach($request->itemcategory_id as $index=>$val){
				$compositionitemcategory = $this->compositionitemcategory->updateOrCreate(
				['composition_id' => $request->composition_id, 'itemcategory_id' => $request->itemcategory_id[$index]]);
		}
        if ($compositionitemcategory) {
            return response()->json(array('success' => true, 'id' => $compositionitemcategory->id, 'message' => 'Save Successfully'), 200);
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
        $compositionitemcategory = $this->compositionitemcategory->find($id);
        $row ['fromData'] = $compositionitemcategory;
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
    public function update(CompositionItemcategoryRequest $request, $id) {
        $compositionitemcategory = $this->compositionitemcategory->update($id, $request->except(['id']));
        if ($compositionitemcategory) {
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
        if ($this->compositionitemcategory->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
