<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\BuyerNatureRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\CountryRepository;

use App\Library\Template;
use App\Http\Requests\BuyerRequest;

class BuyerController extends Controller {

    private $buyer;
    private $supplier;
    private $team;
    private $company;
	private $buyernature;
	private $country;

    public function __construct(BuyerRepository $buyer, SupplierRepository $supplier, TeamRepository $team, TeammemberRepository $teammember,CompanyRepository $company,BuyerNatureRepository $buyernature, CountryRepository $country) {
		$this->buyer = $buyer;
		$this->supplier = $supplier;
		$this->team = $team;
		$this->teammember = $teammember;
		$this->company = $company;
		$this->buyernature = $buyernature;
		$this->country = $country;
		$this->middleware('auth');
		$this->middleware('permission:view.buyers',   ['only' => ['create', 'index','show']]);
		$this->middleware('permission:create.buyers', ['only' => ['store']]);
		$this->middleware('permission:edit.buyers',   ['only' => ['update']]);
		$this->middleware('permission:delete.buyers', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-',0);
        $team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-',0);
        $teammember=array_prepend(array_pluck($this->teammember->get(),'name','id'),'-Select-',0);
        $discountmethod=array_prepend(config('bprs.discountmethod'),'-Select-',0);
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-',0);
        $yesno=array_prepend(config('bprs.yesno'),'-Select-',0);
        $buyers=array();
        $rows=$this->buyer->orderBy('id','desc')->get();
        foreach ($rows as $row) {
         $buyer['id']=$row->id;
		  $buyer['name']=$row->name;
		  $buyer['company_id']=isset($company[$row->company_id])?$company[$row->company_id]:'';
		  $buyer['code']=$row->code;
		  $buyer['vendor_code']=$row->vendor_code;
          array_push($buyers,$buyer);
        }
        echo json_encode($buyers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-',0);
        $team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-',0);
        $teammember=array_prepend(array_pluck($this->teammember->get(),'name','id'),'-Select-',0);
        $discountmethod=array_prepend(config('bprs.discountmethod'),'-Select-',0);
        $yesno=array_prepend(config('bprs.yesno'),'-Select-',0);
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$buyinghouses=array_prepend(array_pluck($this->buyernature->getBuyingHouses(),'name','id'),'-Select-',0);
        $status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-',''); 
		$permited=[];
        return Template::loadView("Util.Buyer",["supplier"=> $supplier, "team"=> $team, "teammember"=>$teammember, 'discountmethod'=>$discountmethod,'yesno'=>$yesno,'company'=>$company,'permited'=>$permited,'buyinghouses'=>$buyinghouses,'country'=>$country,'status'=>$status]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BuyerRequest $request) {
        $buyer = $this->buyer->create($request->except(['id']));
        if ($buyer) {
            return response()->json(array('success' => true, 'id' => $buyer->id, 'message' => 'Save Successfully'), 200);
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
        $buyer = $this->buyer->find($id);
        $row ['fromData'] = $buyer;
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
    public function update(BuyerRequest $request, $id) {
        $buyer = $this->buyer->update($id, $request->except(['id']));
        if ($buyer) {
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
        if ($this->buyer->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

    public function getBuyer() {

        $buyers=array();
        $rows=$this->buyer
        ->when(request('name'), function ($q) {
        return $q->where('name', 'like','%'.request('name', 0).'%');
        })
        ->when(request('code'), function ($q) {
        return $q->where('code', '=', request('code', 0));
        })
        ->orderBy('name','asc')
        ->get();
        foreach($rows as $row){
           $buyer['id']=$row->id; 
           $buyer['name']=$row->name; 
           $buyer['code']=$row->code; 
           $buyer['vendor_code']=$row->vendor_code; 
          
        array_push($buyers,$buyer);
        }
        echo json_encode($buyers);
    }

}
