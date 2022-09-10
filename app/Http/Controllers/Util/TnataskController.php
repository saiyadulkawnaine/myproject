<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\TnataskRepository;
use App\Library\Template;
use App\Http\Requests\TnataskRequest;

class TnataskController extends Controller
{
    private $tnatask;

    public function __construct(TnataskRepository $tnatask) {
        $this->tnatask = $tnatask;

        $this->middleware('auth');
        $this->middleware('permission:view.tnatasks',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.tnatasks', ['only' => ['store']]);
        $this->middleware('permission:edit.tnatasks',   ['only' => ['update']]);
        $this->middleware('permission:delete.tnatasks', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
        $rows=$this->tnatask
        ->orderBy('id','desc')
        ->get()
        ->map(function($rows) use($yesno){
            $rows->autocompletion=$rows->is_auto_completion?$yesno[$rows->is_auto_completion]:'';
            return $rows;

        })
        ;
        
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $yesno=array_prepend(config('bprs.yesno'),'-Select-','');
        $tnatask=array_prepend(config('bprs.tnatask'),'-Select-','');
        return Template::loadView("Util.Tnatask",['yesno'=>$yesno,'tnatask'=>$tnatask]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TnataskRequest $request) {
        $tnatask = $this->tnatask->create($request->except(['id']));
        if ($tnatask) {
            return response()->json(array('success' => true, 'id' => $tnatask->id, 'message' => 'Save Successfully'), 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tnatask = $this->tnatask->find($id);
        $row ['fromData'] = $tnatask;
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
    public function update(TnataskRequest $request, $id) {
        $tnatask = $this->tnatask->update($id, $request->except(['id']));
        if ($tnatask) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->tnatask->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
}
