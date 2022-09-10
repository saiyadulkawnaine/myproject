<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BankRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;
use App\Repositories\Contracts\Util\BankAccountRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Library\Template;
use App\Http\Requests\BankRequest;



class BankController extends Controller {

    private $bank;
    private $currency;
    private $bankbranch;
    private $bankaccount;
    private $commercialhead;
    private $company;

    public function __construct(BankRepository $bank,
    CurrencyRepository $currency,
    CompanyRepository $company,
    BankBranchRepository $bankbranch,
    BankAccountRepository $bankaccount,
    CommercialHeadRepository $commercialhead) {
        $this->bank = $bank;
		$this->currency = $currency;
        $this->company = $company;
		$this->bankbranch = $bankbranch;
		$this->bankaccount = $bankaccount;
        $this->commercialhead = $commercialhead;
        
        $this->middleware('auth');
        $this->middleware('permission:view.banks',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.banks', ['only' => ['store']]);
        $this->middleware('permission:edit.banks',   ['only' => ['update']]);
        $this->middleware('permission:delete.banks', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $banks=array();
        $bankType=config('bprs.bankType');
        $yesno=config('bprs.yesno');
            $rows=$this->bank
            ->orderBy('id','desc')
            ->get();
            foreach ($rows as $row){
                $bank['id']=$row->id;
                $bank['name']=$row->name;
                $bank['code']=$row->code;
                $bank['swift_code']=$row->swift_code;
                $bank['address']=$row->address;
                $bank['bank_type_id']=$bankType[$row->bank_type_id];
                array_push($banks,$bank);
            }
        echo json_encode($banks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        $bank=array_prepend(array_pluck($this->bank->get(),'name','id'),'','');
		$bankType=config('bprs.bankType');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        //$accountType=config('bprs.accountType');
        $yesno = array_prepend(config('bprs.yesno'), '-Select-','');
        $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'','');
		return Template::loadView('Util.Bank', [
            'bankType'=>$bankType,
            'commercialhead'=>$commercialhead,
            'currency'=>$currency,
            'company'=>$company,
            'bank'=>$bank,
            'yesno'=>$yesno,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankRequest $request) {
		$bank=$this->bank->create($request->except(['id']));
        if($bank){
            return response()->json(array('success'=>true,'id'=>$bank->id,'message'=>'Save Successfully'),200);
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
        $bank = $this->bank->find($id);
        $row ['fromData'] = $bank;
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
    public function update(BankRequest $request, $id) {
        $bank=$this->bank->update($id,$request->except(['id']));
        if($bank){
            return response()->json(array('success'=>true,'id'=>$id,'message'=>'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->bank->delete($id)){
            return response()->json(array('success'=>true,'message' => 'Delete Successfully'),200);
        }
    }

}
