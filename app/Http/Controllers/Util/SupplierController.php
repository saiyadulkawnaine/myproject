<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Contracts\Util\CompanyRepository;

use App\Library\Template;
use App\Http\Requests\SupplierRequest;

class SupplierController extends Controller
{
    private $supplier;
    private $buyer;
    private $country;
	private $company;

    public function __construct(SupplierRepository $supplier, BuyerRepository $buyer, CountryRepository $country,CompanyRepository $company) {
        $this->supplier = $supplier;
        $this->buyer = $buyer;
        $this->country = $country;
		$this->company = $company;
        $this->middleware('auth');
        $this->middleware('permission:view.suppliers',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.suppliers', ['only' => ['store']]);
        $this->middleware('permission:edit.suppliers',   ['only' => ['update']]);
        $this->middleware('permission:delete.suppliers', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	  $status=array_prepend(array_only(config('bprs.status'), [1, 4]),'-Select-','');	
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-',0);
      $country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-',0);
      $discountmethod=array_prepend(config('bprs.discountmethod'),'-Select-',0);
      $suppliers=array();
      $rows=$this->supplier
      ->leftJoin(\DB::raw("(Select supplier_natures.supplier_id,min(supplier_natures.contact_nature_id) as contact_nature_id
        from supplier_natures 
        group by supplier_natures.supplier_id   
        order by supplier_natures.supplier_id 
        ) supplyNature"),"supplyNature.supplier_id","=","suppliers.id")
        ->leftJoin('contact_natures', function($join)  {
            $join->on('supplyNature.contact_nature_id', '=', 'contact_natures.id');
        })
        ->orderBy('suppliers.id','desc')
        ->get([
          'suppliers.*',
          'contact_natures.name as nature_name'
      ]);
      foreach ($rows as $row) {
        $supplier['id']=$row->id;
        $supplier['name']=$row->name;
        $supplier['code']=$row->code;
        $supplier['address']=$row->address;
        $supplier['nature_name']=$row->nature_name;
        $supplier['vendor_code']=$row->vendor_code;
        $supplier['contact_person']=$row->contact_person;
        $supplier['buyer']=isset($buyer[$row->buyer_id])?$buyer[$row->buyer_id]:0;
        $supplier['country']=isset($country[$row->country_id])?$country[$row->country_id]:0;
        $supplier['company_id']=isset($company[$row->company_id])?$company[$row->company_id]:0;
        $supplier['status_id']=isset($status[$row->status_id])?$status[$row->status_id]:0;
        //$supplier['discountmethod']=$discountmethod[$row->discount_method_Id];
        array_push($suppliers,$supplier);
      }
        echo json_encode($suppliers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-','');
        $discountmethod=array_prepend(config('bprs.discountmethod'),'-Select-',0);
        $yesno=array_prepend(config('bprs.yesno'),'-Select-',0);
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');	
		$status=array_prepend(array_only(config('bprs.status'), [1, 0]),'-Select-','');	
	//$permited=[];

	//$suppliernature=config('bprs.suppliernature');
	//$suppliernaturepermited=[];

        return Template::loadView("Util.Supplier",['buyer'=>$buyer,'country'=>$country,'discountmethod'=>$discountmethod,'yesno'=>$yesno,'company'=>$company,'status'=>$status]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierRequest $request) {
		//$company=explode(",",$request->input('company_id'));
        $supplier= $this->supplier->create($request->except(['id']));
		//$res=$supplier->companies()->sync($company);

        if ($supplier) {
            return response()->json(array('success' => true, 'id' => $supplier->id, 'message' => 'Save Successfully'), 200);
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
		//$companies = $this->company->get();
		$participants = $this->supplier->find($id);
		//$avaiable = $companies->diff($participants);
        $supplier = $this->supplier->find($id);
        $row ['fromData'] = $supplier;
	  	//$dropdown['company_dropDown'] = "'".Template::loadView('Util.CompanyDropDown',['company'=>array_pluck($avaiable,'name','id'),'permited'=>array_pluck(	$participants,'name','id')])."'";
       // $row ['dropDown'] = $dropdown;
        echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SupplierRequest $request, $id) {
		//$company=explode(",",$request->input('company_id'));
        $res = $this->supplier->update($id, $request->except(['id']));
		//$supplier = $this->supplier->find($id);
		//$supplier->companies()->sync($company);
        if ($res) {
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
        if ($this->supplier->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

    public function getSupplier() {

        //echo "monzu";die;
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-',0);
        $country=array_prepend(array_pluck($this->country->get(),'name','id'),'-Select-',0);
        $suppliers=array();
        $rows=$this->supplier
        ->when(request('name'), function ($q) {
        return $q->where('name', 'like','%'.request('name', 0).'%');
        })
        ->when(request('code'), function ($q) {
        return $q->where('code', '=', request('code', 0));
        })
        ->when(request('contact_person'), function ($q) {
        return $q->where('contact_person', '=', request('contact_person', 0));
        })
         ->when(request('vendor_code'), function ($q) {
        return $q->where('vendor_code', '=', request('vendor_code', 0));
        })
         ->orderBy('name','asc')
        ->get();
        foreach($rows as $row){
           $supplier['id']=$row->id; 
           $supplier['name']=$row->name; 
           $supplier['code']=$row->code; 
           $supplier['vendor_code']=$row->vendor_code;
           $supplier['contact_person']=$row->contact_person;
           $supplier['buyer']=isset($buyer[$row->buyer_id])?$buyer[$row->buyer_id]:0;
           $supplier['country']=isset($country[$row->country_id])?$country[$row->country_id]:0;
        array_push($suppliers,$supplier);
        }
        echo json_encode($suppliers);
    }

    public function getOtherParty() {

        //echo "monzu";die;
        $suppliers=array();
        $rows=$this->supplier
        ->join('supplier_natures', function($join) {
                $join->on('supplier_natures.supplier_id', '=', 'suppliers.id');
        })
        ->when(request('name'), function ($q) {
        return $q->where('suppliers.name', 'like','%'.request('name', 0).'%');
        })
        ->when(request('code'), function ($q) {
        return $q->where('suppliers.code', '=', request('code', 0));
        })
        ->when(request('contact_person'), function ($q) {
        return $q->where('suppliers.contact_person', '=', request('contact_person', 0));
        })
         ->when(request('vendor_code'), function ($q) {
        return $q->where('suppliers.vendor_code', '=', request('vendor_code', 0));
        })
         ->where([['supplier_natures.contact_nature_id','=',53]])
         ->orderBy('suppliers.name','asc')
        ->get();
        foreach($rows as $row){
           $supplier['id']=$row->id; 
           $supplier['name']=$row->name; 
           $supplier['code']=$row->code; 
           $supplier['vendor_code']=$row->vendor_code;
           $supplier['contact_person']=$row->contact_person;
        array_push($suppliers,$supplier);
        }
        echo json_encode($suppliers);
    }
}
