<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\AgreementRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Numbertowords;
use App\Library\Template;
use App\Http\Requests\HRM\AgreementRequest;
use GuzzleHttp\Client;

class AgreementController extends Controller {

    private $agreement;
    private $supplier;
    private $user;

    public function __construct(
    	AgreementRepository $agreement,
    	SupplierRepository $supplier,
    	CompanyRepository $company,
    	UserRepository $user
    ) {
        $this->agreement = $agreement;
        $this->supplier = $supplier;
        $this->company = $company;
        $this->user = $user;

        $this->middleware('auth');
        // $this->middleware('permission:view.agreements',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.agreements', ['only' => ['store']]);
        // $this->middleware('permission:edit.agreements',   ['only' => ['update']]);
        // $this->middleware('permission:delete.agreements', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');

        $agreements=array();
        $rows=$this->agreement
        ->orderBy('agreements.id','desc')
        ->get(['agreements.*']);
        foreach($rows as $row){
          $agreement['id']=$row->id; 
          $agreement['supplier_id']=$supplier[$row->supplier_id];
          $agreement['accept_date']=date('d-M-Y',strtotime($row->accept_date)); 
          $agreement['purpose']=$row->purpose; 
          $agreement['remarks']=$row->remarks;
         
          array_push($agreements,$agreement);
        }
        echo json_encode($agreements);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $menu=array_prepend(array_only(config('bprs.menu'), [1,2,3,4,5,6,7,8,9,10]),'-Select-',''); 
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->where([['status_id','=',1]])->get(),'name','id'),'-Select-','');
		return Template::loadView('HRM.Agreement', ['supplier'=>$supplier,'menu'=>$menu,'company'=>$company]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AgreementRequest $request) {
		$agreement=$this->agreement->create($request->except(['id']));

		if($agreement){
			return response()->json(array('success' => true,'id' =>  $agreement->id,'message' => 'Save Successfully'),200);
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
		
		$agreement = $this->agreement->find($id);
		
		$row ['fromData'] = $agreement;
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
    public function update(AgreementRequest $request, $id) {
       $agreement=$this->agreement->update($id,$request->except(['id']));
		if($agreement){
			return response()->json(array('success' => true,'id' => $id,'message' => 'Update Successfully'),200);
		} 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->agreement->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
    
}