<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\System\MenuRepository;
use App\Library\Template;
use App\Http\Requests\GmtspartRequest;

class GmtspartController extends Controller {

    private $gmtspart;
    private $menu;

    public function __construct(GmtspartRepository $gmtspart,MenuRepository $menu) {
        $this->gmtspart = $gmtspart;
        $this->menu = $menu;

        $this->middleware('auth');
        $this->middleware('permission:view.gmtsparts',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.gmtsparts', ['only' => ['store']]);
        $this->middleware('permission:edit.gmtsparts',   ['only' => ['update']]);
        $this->middleware('permission:delete.gmtsparts', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $gmtsparts=array();
      $rows=$this->gmtspart->orderBy('id','desc')->get();
      foreach ($rows as $row) {
        $gmtspart['id']=$row->id;
        $gmtspart['name']=$row->name;
        array_push($gmtsparts,$gmtspart);
      }
        echo json_encode($gmtsparts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $menu=array_prepend(array_pluck($this->menu->get(),'name','id'),'-Select-','');
        $parttype=array_prepend(config('bprs.parttype'),'-Select-','');
        $gmtcategory=array_prepend(config('bprs.gmtcategory'),'-Select-','');
        
        return Template::loadView("Util.Gmtspart",['menu'=>$menu,'parttype'=>$parttype,'gmtcategory'=>$gmtcategory]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GmtspartRequest $request) {
        $gmtspart = $this->gmtspart->create($request->except(['id']));
        if ($gmtspart) {
            return response()->json(array('success' => true, 'id' => $gmtspart->id, 'message' => 'Save Successfully'), 200);
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

        $gmtspart = $this->gmtspart->find($id);
        $row ['fromData'] = $gmtspart;
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
    public function update(GmtspartRequest $request, $id) {
        $res = $this->gmtspart->update($id, $request->except(['id']));
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
        if ($this->gmtspart->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
