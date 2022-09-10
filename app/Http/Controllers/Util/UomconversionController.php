<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\UomconversionRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Library\Template;
use App\Http\Requests\UomconversionRequest;

class UomconversionController extends Controller
{
	private $uomconversion;
	private $uom;

	public function __construct(UomconversionRepository $uomconversion,UomRepository $uom)
	{
		$this->uomconversion = $uomconversion;
		$this->uom = $uom;
		$this->uomArr=array_prepend(array_pluck($this->uom->get(),'name','id'),'-Select-','');

		$this->middleware('auth');
		$this->middleware('permission:view.uomconversions',   ['only' => ['create', 'index','show']]);
    $this->middleware('permission:create.uomconversions', ['only' => ['store']]);
    $this->middleware('permission:edit.uomconversions',   ['only' => ['update']]);
		$this->middleware('permission:delete.uomconversions', ['only' => ['destroy']]);
	}
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		/*$allMessages = Messages::with('user')
                       ->where('conv_id', $conv_id)
                       ->take(10)
                       ->get();*/
		//$this->uomconversion->with('uom')->get();
		$rows = array();

		$uomconversions=$this->uomconversion->get();
		foreach($uomconversions as $uomconversion ){
			$row['id']=$uomconversion->id;
			$row['uom_id']=$uomconversion->uom_id;
			$row['uom_name']=$this->uomArr[$uomconversion->uom_id];
			$row['uom_to']=$uomconversion->uom_to;
			$row['uom_to_name']=$this->uomArr[$uomconversion->uom_to];
			$row['coversion_factor']=$uomconversion->coversion_factor;
			array_push($rows, $row);
		}

        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		return Template::loadView("Util.Uomconversion",['uom'=>$this->uomArr]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UomconversionRequest $request)
    {
        $uomconversion=$this->uomconversion->create($request->except(['id']));
		if($uomconversion){
			return response()->json(array('success' => true,'id' =>  $uomconversion->id,'message' => 'Save Successfully'),200);
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
       $uomconversion = $this->uomconversion->find($id);
	   $row ['fromData'] = $uomconversion;
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
    public function update(UomconversionRequest $request, $id)
    {
        $uomconversion=$this->uomconversion->update($id,$request->except(['id']));
		if($uomconversion){
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
        if($this->uomconversion->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }
}
