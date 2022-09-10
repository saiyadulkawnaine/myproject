<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Library\Template;
use App\Http\Requests\ColorrangeRequest;

class ColorrangeController extends Controller {

    private $colorrange;

    public function __construct(ColorrangeRepository $colorrange) {
        $this->colorrange = $colorrange;
        $this->middleware('auth');
        $this->middleware('permission:view.colorranges',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.colorranges', ['only' => ['store']]);
        $this->middleware('permission:edit.colorranges',   ['only' => ['update']]);
        $this->middleware('permission:delete.colorranges', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        echo json_encode($this->colorrange->orderBy('id','desc')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return Template::loadView("Util.Colorrange");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ColorrangeRequest $request) {
        $colorrange = $this->colorrange->create($request->except(['id']));
        if ($colorrange) {
            return response()->json(array('success' => true, 'id' => $colorrange->id, 'message' => 'Save Successfully'), 200);
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
        $colorrange = $this->colorrange->find($id);
        $row ['fromData'] = $colorrange;
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
    public function update(ColorrangeRequest $request, $id) {
        $colorrange = $this->colorrange->update($id, $request->except(['id']));
        if ($colorrange) {
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
        if ($this->colorrange->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
