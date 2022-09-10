<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Library\Template;
use App\Http\Requests\CurrencyRequest;

class CurrencyController extends Controller
{
	private $section;
	private $country;

	public function __construct(CurrencyRepository $currency,CountryRepository $country)
	{
		$this->currency = $currency;
		$this->country=$country;
		$this->middleware('auth');
		$this->middleware('permission:view.currencys',   ['only' => ['create', 'index','show']]);
    $this->middleware('permission:create.currencys', ['only' => ['store']]);
    $this->middleware('permission:edit.currencys',   ['only' => ['update']]);
		$this->middleware('permission:delete.currencys', ['only' => ['destroy']]);
	}
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
			$country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
			$currencies=array();
			$rows=$this->currency->get();
			foreach ($rows as $row) {
				$currency['id']=$row->id;
				$currency['name']=$row->name;
				$currency['code']=$row->code;
				$currency['symbol']=$row->symbol;
				$currency['country']=$country[$row->country_id];
				array_push($currencies,$currency);
			}
        echo json_encode($currencies);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$yesno=config('bprs.yesno');
		$country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
		//return Template::loadView("Util\Subsection",['yesno'=>$yesno,'floors'=>$floors]);
		return Template::loadView("Util.Currency",['yesno'=>$yesno,'country'=>$country]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CurrencyRequest $request)
    {
        $currency=$this->currency->create($request->except(['id']));
		if($currency){
			return response()->json(array('success' => true,'id' =>  $currency->id,'message' => 'Save Successfully'),200);
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
        $currency = $this->currency->find($id);
        $row ['fromData'] = $currency;
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
    public function update(CurrencyRequest $request, $id)
    {
        $currency=$this->currency->update($id,$request->except(['id']));
		if($currency){
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
        if($this->currency->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }
}
