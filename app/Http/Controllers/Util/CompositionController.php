<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompositionRepository;
use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Library\Template;
use App\Http\Requests\CompositionRequest;

class CompositionController extends Controller {

    private $composition;
	private $itemcategory;

    public function __construct(CompositionRepository $composition, ItemcategoryRepository $itemcategory) {
        $this->composition = $composition;
		$this->itemcategory = $itemcategory;
        $this->middleware('auth');
        $this->middleware('permission:view.compositions',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.compositions', ['only' => ['store']]);
        $this->middleware('permission:edit.compositions',   ['only' => ['update']]);
        $this->middleware('permission:delete.compositions', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $compositions=array();
        $rows=$this->composition->orderBy('compositions.id','desc')->get();
        foreach ($rows as $row) {
          $composition['id']=$row->id;
          $composition['name']=$row->name;
          array_push($compositions,$composition);
        }
        echo json_encode($compositions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$itemcategory=array_pluck($this->itemcategory->get(),'name','id');
        return Template::loadView("Util.Composition",['itemcategory'=>$itemcategory]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompositionRequest $request) {
        $composition = $this->composition->create($request->except(['id']));
        if ($composition) {
            return response()->json(array('success' => true, 'id' => $composition->id, 'message' => 'Save Successfully'), 200);
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
        $composition = $this->composition->find($id);
        $row ['fromData'] = $composition;
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
    public function update(CompositionRequest $request, $id) {
        $res = $this->composition->update($id, $request->except(['id']));
        if ($res) {
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
        if ($this->composition->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
