<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\ExchangerateRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Library\Template;
use App\Http\Requests\ExchangerateRequest;

class ExchangerateController extends Controller
{
	private $exchangerate;
	 private $currency;

	public function __construct(ExchangerateRepository $exchangerate,CurrencyRepository $currency)
	{
		$this->exchangerate = $exchangerate;
		$this->currency = $currency;

		$this->middleware('auth');
		$this->middleware('permission:view.exchangerates',   ['only' => ['create', 'index','show']]);
		$this->middleware('permission:create.exchangerates', ['only' => ['store']]);
		$this->middleware('permission:edit.exchangerates',   ['only' => ['update']]);
		$this->middleware('permission:delete.exchangerates', ['only' => ['destroy']]);
	}
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

		$permissions = $this->exchangerate->leftJoin('currencies', function($join) {
		$join->on('exchangerates.currency_id', '=', 'currencies.id');
        })
		->get([
			'exchangerates.*',
			'currencies.name',
        ])
        ->map(function($permissions){
            $permissions->applied_date=date('Y-m-d',strtotime($permissions->applied_date));
            return $permissions;
        });
        echo json_encode($permissions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
		return Template::loadView("Util.Exchangerate",['currency'=>$currency]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExchangerateRequest $request)
    {
        $exchangerate=$this->exchangerate->create($request->except(['id']));
		if($exchangerate){
			return response()->json(array('success' => true,'id' =>  $exchangerate->id,'message' => 'Save Successfully'),200);
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
       $exchangerate = $this->exchangerate->find($id);
       $exchangerate['applied_date']=date('Y-m-d',strtotime($exchangerate->applied_date));
	   $row ['fromData'] = $exchangerate;
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
    public function update(ExchangerateRequest $request, $id)
    {
        $exchangerate=$this->exchangerate->update($id,$request->except(['id']));
		if($exchangerate){
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
        if($this->exchangerate->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }
}
