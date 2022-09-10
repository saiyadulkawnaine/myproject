<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\GmtspartMenuRepository;
use App\Repositories\Contracts\System\MenuRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;

use App\Library\Template;
use App\Http\Requests\GmtspartMenuRequest;

class GmtspartMenuController extends Controller {

    private $gmtspartmenu;
	private $menu;
    private $gmtspart;

    public function __construct(GmtspartMenuRepository $gmtspartmenu, GmtspartRepository $gmtspart,MenuRepository $menu) {
        $this->gmtspartmenu = $gmtspartmenu;
		$this->menu = $menu;
        $this->gmtspart = $gmtspart;
        $this->middleware('auth');
        // $this->middleware('permission:view.gmtspartmenus',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.gmtspartmenus', ['only' => ['store']]);
        // $this->middleware('permission:edit.gmtspartmenus',   ['only' => ['update']]);
        // $this->middleware('permission:delete.gmtspartmenus', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'-Select-','');
        $gmtspartmenus=array();
        $rows=$this->gmtspartmenu->get();
        foreach ($rows as $row) {
          $gmtspartmenu['id']=$row->id;
          $gmtspartmenu['name']=$row->name;
          $gmtspartmenu['code']=$row->code;
          $gmtspartmenu['gmtspart']=$gmtspart[$row->gmtspart_id];
          array_push($gmtspartmenus,$gmtspartmenu);
        }
        echo json_encode($gmtspartmenus);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$menu=$this->menu
		->leftJoin('gmtspart_menus', function($join)  {
			$join->on('gmtspart_menus.menu_id', '=', 'menus.id');
			$join->where('gmtspart_menus.gmtspart_id', '=', request('gmtspart_id',0));
			$join->whereNull('gmtspart_menus.deleted_at');
		})
		->get([
		'menus.id',
		'menus.name',
		'gmtspart_menus.id as gmtspart_menu_id'
		]);
		$saved = $menu->filter(function ($value) {
			if($value->gmtspart_menu_id){
				return $value;
			}
		})->values();
		
		$new = $menu->filter(function ($value) {
			if(!$value->gmtspart_menu_id){
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
    public function store(GmtspartMenuRequest $request) {
		foreach($request->menu_id as $index=>$val){
            $gmtspartmenu = $this->gmtspartmenu->updateOrCreate(
				['gmtspart_id' => $request->gmtspart_id, 'menu_id' => $request->menu_id[$index]]);
		}
        if ($gmtspartmenu) {
            return response()->json(array('success' => true, 'id' => $gmtspartmenu->id, 'message' => 'Save Successfully'), 200);
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
        $gmtspartmenu = $this->gmtspartmenu->find($id);
        $row ['fromData'] = $gmtspartmenu;
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
    public function update(GmtspartMenuRequest $request, $id) {
        $gmtspartmenu = $this->gmtspartmenu->update($id, $request->except(['id']));
        if ($gmtspartmenu) {
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
        if ($this->gmtspartmenu->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
