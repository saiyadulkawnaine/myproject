<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\System\MenuRepository;
use App\Library\Template;
use App\Http\Requests\MenuRequest;

class MenuController extends Controller
{

  private $menu;
  public function __construct(MenuRepository $menu)
  {
    $this->menu = $menu;
    $this->middleware('auth');
    $this->middleware('permission:view.menus',   ['only' => ['create', 'index', 'show']]);
    $this->middleware('permission:create.menus', ['only' => ['store']]);
    $this->middleware('permission:edit.menus',   ['only' => ['update']]);
    $this->middleware('permission:delete.menus', ['only' => ['destroy']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    $menu = $this->menu
      ->orderBy('menus.id', 'desc')
      ->get();
    echo json_encode($menu);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {

    $menus = array_prepend(array_pluck($this->menu->orderBy('menus.id', 'desc')->get(), 'name', 'id'), '-Select-', 0);
    return Template::loadView("System.Menu.Menu", ['menus' => $menus]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  Request  $request
   * @return Response
   */
  public function store(MenuRequest $request)
  {
    $menu = $this->menu->create($request->except(['id']));
    if ($menu) {
      return response()->json(array('success' => true, 'id' =>  $menu->id, 'message' => 'Save Successfully'), 200);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    $menu = $this->menu->find($id);
    $row['fromData'] = $menu;
    $dropdown['att'] = '';
    $row['dropDown'] = $dropdown;
    echo json_encode($row);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  Request  $request
   * @param  int  $id
   * @return Response
   */
  public function update(MenuRequest $request, $id)
  {
    $menu = $this->menu->update($id, $request->except(['id']));
    if ($menu) {
      return response()->json(array('success' => true, 'id' =>  $id, 'message' => 'Update Successfully'), 200);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    if ($this->menu->delete($id)) {
      return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
    }
  }

  public function getjson()
  {
    $childs = array();
    $data = $this->menu->orderBy('id', 'asc')
      ->get([
        'id',
        'root_id',
        'name as text'
      ]);

    foreach ($data as $item) {
      $childs[$item->root_id][] = $item;
    }

    foreach ($data as $item) {
      if (isset($childs[$item->id])) {
        $item->children = $childs[$item->id];
      }
    }

    $tree = $childs[0];
    echo json_encode($tree);
    //print_r($tree);
  }

  public function hasChild($id)
  {
    $sql = $this->menu->where([['root_id', '=', $id]])->get();
    $row = $sql->count();
    return $row > 0 ? true : false;
  }
}
