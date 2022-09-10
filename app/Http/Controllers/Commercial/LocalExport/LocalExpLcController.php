<?php

namespace App\Http\Controllers\Commercial\LocalExport;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpLcRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\BuyerBranchRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\BankRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;

use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpPiRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpPiOrderRepository;

use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\ColorrangeRepository;

use App\Library\Template;
use App\Http\Requests\Commercial\LocalExport\LocalExpLcRequest;

class LocalExpLcController extends Controller {

    private $localexplc;
    private $currency;
    private $buyer;
    private $supplier;
    private $bank;
    private $company;
    private $exppiorder;
    private $buyerbranch;

    private $budgetfabric;
    private $budget;
    private $gmtspart;
    private $autoyarn;
    private $colorrange;
    private $bankbranch;

    public function __construct(LocalExpLcRepository $localexplc,CurrencyRepository $currency,BuyerRepository $buyer,SupplierRepository $supplier,BankRepository $bank,CompanyRepository $company,ItemAccountRepository $itemaccount, SalesOrderRepository $salesorder, StyleRepository $style, JobRepository $job,LocalExpPiRepository $exppi, LocalExpPiOrderRepository $exppiorder, BuyerBranchRepository $buyerbranch, 
    BudgetFabricRepository $budgetfabric,BudgetRepository $budget,GmtspartRepository $gmtspart,AutoyarnRepository $autoyarn,ColorrangeRepository $colorrange, BankBranchRepository $bankbranch) {
        $this->localexplc = $localexplc;
        $this->currency = $currency;
        $this->buyer = $buyer;
        $this->supplier = $supplier;
        $this->bank = $bank;
        $this->bankbranch = $bankbranch;
        $this->company = $company;
        $this->buyerbranch = $buyerbranch;
        $this->itemaccount = $itemaccount;
        $this->salesorder = $salesorder;
        $this->job = $job;
        $this->style = $style;
        $this->exppi = $exppi;
        $this->exppiorder = $exppiorder;

        $this->budgetfabric = $budgetfabric;
        $this->budget = $budget;
        $this->gmtspart = $gmtspart;
        $this->autoyarn = $autoyarn;
        $this->colorrange = $colorrange;


        $this->middleware('auth');
        // $this->middleware('permission:view.localexplcs',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.localexplcs', ['only' => ['store']]);
        // $this->middleware('permission:edit.localexplcs',   ['only' => ['update']]);
        // $this->middleware('permission:delete.localexplcs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'code','id'),'-Select-','');
        $productionarea=array_prepend(array_only(config('bprs.productionarea'),[5,10,20,25,45,50,51]),'-Select-','');
         
        $localexplcs=array();
        $rows=$this->localexplc
        ->orderBy('local_exp_lcs.id','desc')
        ->get();
        foreach($rows as $row){
            $localexplc['id']=$row->id;
            $localexplc['local_lc_no']=$row->local_lc_no;
            $localexplc['beneficiary']=$company[$row->beneficiary_id];//combo
            $localexplc['buyer']=$buyer[$row->buyer_id];
            $localexplc['lc_date']=date('d-M-Y',strtotime($row->lc_date));
            $localexplc['lc_value']=$row->lc_value;
            $localexplc['currency']=$currency[$row->currency_id];
            $localexplc['exch_rate']=$row->exch_rate;
            $localexplc['productionarea']=$productionarea[$row->production_area_id];
            array_push($localexplcs,$localexplc);
        }
        echo json_encode($localexplcs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
        $supplier=array_prepend(array_pluck($this->supplier->where([['status_id','=',1]])->get(),'name','id'),'-Select-','');
        $bank=array_prepend(array_pluck($this->bank->get(),'name','id'),'-Select-','');
        $bankbranch=array_prepend(array_pluck(
            $this->bankbranch
            ->leftJoin('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->get([
                'bank_branches.id',
                'bank_branches.branch_name',
                'banks.name as bank_name',
            ])
            ->map(function($bankbranch){
                $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
                return $bankbranch;
            })
            ,'name','id'),'-Select-','');
        $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
        $yesno = array_prepend(config('bprs.yesno'), '-Select-','');
        $incoterm = array_prepend(config('bprs.incoterm'), '-Select-','');
        $deliveryMode = array_prepend(config('bprs.deliveryMode'), '-Select-','');
        $productionarea=array_prepend(array_only(config('bprs.productionarea'),[5,10,20,25,45,50,51]),'-Select-','');

        return Template::LoadView('Commercial.LocalExport.LocalExpLc',['company'=>$company,'currency'=>$currency,'buyer'=>$buyer,'supplier'=>$supplier,'bank'=>$bank,'payterm'=>$payterm,'incoterm'=>$incoterm,'deliveryMode'=>$deliveryMode,'yesno'=>$yesno,'bankbranch'=>$bankbranch,'productionarea'=>$productionarea]);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocalExpLcRequest $request) {
		$localexplc=$this->localexplc->create($request->except(['id']));
		if($localexplc){
			return response()->json(array('success' => true,'id' =>  $localexplc->id,'message' => 'Save Successfully'),200);
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
       $localexplc = $this->localexplc->find($id);
	   $row ['fromData'] = $localexplc;
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
    public function update(LocalExpLcRequest $request, $id) {
        $localexplc=$this->localexplc->update($id,$request->except(['id']));
		if($localexplc){
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
        if($this->localexplc->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }


}
