<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Library\Template;
use App\Http\Requests\ItemcategoryRequest;

class ItemcategoryController extends Controller {

    private $itemcategory;

    public function __construct(ItemcategoryRepository $itemcategory) {
        $this->itemcategory = $itemcategory;
        $this->middleware('auth');
        $this->middleware('permission:view.itemcategorys',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.itemcategorys', ['only' => ['store']]);
        $this->middleware('permission:edit.itemcategorys',   ['only' => ['update']]);
        $this->middleware('permission:delete.itemcategorys', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $identity_id=array_prepend(config('bprs.identity'),'-Select-',0);
      $itemcategories=array();
      $rows=$this->itemcategory->get();
      foreach ($rows as $row) {
        $itemcategory['id']=$row->id;
        $itemcategory['name']=$row->name;
        $itemcategory['code']=$row->code;
        array_push($itemcategories,$itemcategory);
      }
        echo json_encode($itemcategories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$identity_id=array_prepend(config('bprs.identity'),'-Select-',0);
        return Template::loadView("Util.Itemcategory",['identity_id'=>$identity_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemcategoryRequest $request) {
        $itemcategory = $this->itemcategory->create($request->except(['id']));
        if ($itemcategory) {
            return response()->json(array('success' => true, 'id' => $itemcategory->id, 'message' => 'Save Successfully'), 200);
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
        $itemcategory = $this->itemcategory->find($id);
        $row ['fromData'] = $itemcategory;
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
    public function update(ItemcategoryRequest $request, $id) {
        $itemcategory = $this->itemcategory->update($id, $request->except(['id']));
        if ($itemcategory) {
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
        if ($this->itemcategory->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
