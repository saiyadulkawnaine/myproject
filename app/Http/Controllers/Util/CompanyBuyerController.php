<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\CompanyBuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Template;
use App\Http\Requests\CompanyBuyerRequest;

class CompanyBuyerController extends Controller {

    private $companybuyer;
	private $company;
    private $buyer;

    public function __construct(CompanyBuyerRepository $companybuyer, BuyerRepository $buyer,CompanyRepository $company) {
        $this->companybuyer = $companybuyer;
		$this->company = $company;
        $this->buyer = $buyer;
        $this->middleware('auth');
        $this->middleware('permission:view.companybuyers',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.companybuyers', ['only' => ['store']]);
        $this->middleware('permission:edit.companybuyers',   ['only' => ['update']]);
        $this->middleware('permission:delete.companybuyers', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $companybuyers=array();
        $rows=$this->companybuyer->get();
        foreach ($rows as $row) {
          $companybuyer['id']=$row->id;
          $companybuyer['name']=$row->name;
          $companybuyer['code']=$row->code;
          $companybuyer['buyer']=$buyer[$row->buyer_id];
          array_push($companybuyers,$companybuyer);
        }
        echo json_encode($companybuyers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$company=$this->company
		->leftJoin('company_buyers', function($join)  {
			$join->on('company_buyers.company_id', '=', 'companies.id');
			$join->where('company_buyers.buyer_id', '=', request('buyer_id',0));
			$join->whereNull('company_buyers.deleted_at');
		})
		->get([
		'companies.id',
		'companies.name',
		'company_buyers.id as company_buyer_id'
		]);
		$saved = $company->filter(function ($value) {
			if($value->company_buyer_id){
				return $value;
			}
		})->values();
		
		$new = $company->filter(function ($value) {
			if(!$value->company_buyer_id){
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
    public function store(CompanyBuyerRequest $request) {
		foreach($request->company_id as $index=>$val){
				$companybuyer = $this->companybuyer->updateOrCreate(
				['buyer_id' => $request->buyer_id, 'company_id' => $request->company_id[$index]]);
		}
        if ($companybuyer) {
            return response()->json(array('success' => true, 'id' => $companybuyer->id, 'message' => 'Save Successfully'), 200);
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
        $companybuyer = $this->companybuyer->find($id);
        $row ['fromData'] = $companybuyer;
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
    public function update(CompanyBuyerRequest $request, $id) {
        $companybuyer = $this->companybuyer->update($id, $request->except(['id']));
        if ($companybuyer) {
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
        if ($this->companybuyer->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
