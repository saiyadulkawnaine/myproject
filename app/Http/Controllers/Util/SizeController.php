<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\SizeRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Template;
use App\Http\Requests\SizeRequest;

class SizeController extends Controller {

    private $size;
    private $buyer;

    public function __construct(SizeRepository $size,BuyerRepository $buyer) {
        $this->size = $size;
        $this->buyer = $buyer;

        $this->middleware('auth');
        $this->middleware('permission:view.sizes',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.sizes', ['only' => ['store']]);
        $this->middleware('permission:edit.sizes',   ['only' => ['update']]);
        $this->middleware('permission:delete.sizes', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

      $sizes=array();
      $rows=$this->size->orderBy('id','desc')->get();
      foreach ($rows as $row) {
        $size['id']=$row->id;
        $size['name']=$row->name;
        $size['code']=$row->code;
        array_push($sizes,$size);
      }
        echo json_encode($sizes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-',0);
        return Template::loadView("Util.Size",['buyer'=>$buyer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SizeRequest $request) {
        $size = $this->size->create($request->except(['id']));
        if ($size) {
            return response()->json(array('success' => true, 'id' => $size->id, 'message' => 'Save Successfully'), 200);
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

        $size = $this->size->find($id);
        $row ['fromData'] = $size;
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
    public function update(SizeRequest $request, $id) {
        $res = $this->size->update($id, $request->except(['id']));
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
        if ($this->size->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
	
	public function getsize(Request $request) {
		return $this->size->where([['name', 'LIKE', '%'.$request->q.'%']])->orderBy('name','asc')->get();
	}

}
