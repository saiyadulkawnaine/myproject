<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\UomRepository;
use App\Library\Template;
use App\Http\Requests\UomRequest;

class UomController extends Controller
{
	private $uom;

	public function __construct(UomRepository $uom)
	{
		$this->uom = $uom;
		$this->middleware('auth');
		$this->middleware('permission:view.uoms',   ['only' => ['create', 'index','show']]);
    $this->middleware('permission:create.uoms', ['only' => ['store']]);
    $this->middleware('permission:edit.uoms',   ['only' => ['update']]);
		$this->middleware('permission:delete.uoms', ['only' => ['destroy']]);
	}
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
			$uomclass=array_prepend(config('bprs.uomclass'),'-Select-','');
			$uoms=array();
			$rows=$this->uom->get();
      foreach ($rows as $row) {
				$uom['id']=$row->id;
				$uom['name']=$row->name;
        $uom['code']=$row->code;
        $uom['uomclass']=$uomclass[$row->uomclass_id];
        array_push($uoms,$uom);
      }
        echo json_encode($uoms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$uomclass=array_prepend(config('bprs.uomclass'),'-Select-','');
		return Template::loadView("Util.Uom",['uomclass'=>$uomclass]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UomRequest $request)
    {
        $uom=$this->uom->create($request->except(['id']));
		if($uom){
			return response()->json(array('success' => true,'id' =>  $uom->id,'message' => 'Save Successfully'),200);
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
       $uom = $this->uom->find($id);
	   $row ['fromData'] = $uom;
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
    public function update(UomRequest $request, $id)
    {
        $uom=$this->uom->update($id,$request->except(['id']));
		if($uom){
			return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
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
        if($this->uom->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }
}
