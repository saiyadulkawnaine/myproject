<?php

namespace App\Http\Controllers\Commercial\LocalExport;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpDocSubBankRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpLcRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpDocSubTransRepository;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\LocalExport\LocalExpDocSubTransRequest;

class LocalExpDocSubTransController extends Controller {

    private $localexpdocsubbank;
    private $localexpdocsubtrans;
    private $company;
    private $buyer;
    private $currency;
    private $explcsc;
    private $commercialhead;

    public function __construct(LocalExpDocSubBankRepository $localexpdocsubbank, LocalExpDocSubTransRepository $localexpdocsubtrans, LocalExpLcRepository $explcsc,CompanyRepository $company, BuyerRepository $buyer, CurrencyRepository $currency, CommercialHeadRepository $commercialhead ) {

        $this->explcsc = $explcsc;
        $this->localexpdocsubtrans = $localexpdocsubtrans;
        $this->localexpdocsubbank = $localexpdocsubbank;
        $this->commercialhead = $commercialhead;
        $this->currency = $currency;
        $this->company = $company;
        $this->buyer = $buyer;

        $this->middleware('auth');
        // $this->middleware('permission:view.localexpdocsubtrans',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.localexpdocsubtrans', ['only' => ['store']]);
        // $this->middleware('permission:edit.localexpdocsubtrans',   ['only' => ['update']]);
        // $this->middleware('permission:delete.localexpdocsubtrans', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() { 
        $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        
        $localexpdocsubtranses=array();
        $rows=$this->localexpdocsubtrans
        ->where([['local_exp_doc_sub_bank_id',request('local_exp_doc_sub_bank_id',0)]])
        ->get();
        foreach($rows as $row){
            $localexpdocsubtrans['id']=$row->id;
            $localexpdocsubtrans['local_exp_doc_sub_bank_id']=$row->local_exp_doc_sub_bank_id;
            $localexpdocsubtrans['commercialhead_id']=$commercialhead[$row->commercialhead_id];
            $localexpdocsubtrans['currency_id']=$currency[$row->currency_id];
            $localexpdocsubtrans['ac_loan_no']=$row->ac_loan_no;
            $localexpdocsubtrans['dom_value']=number_format($row->dom_value,2,'.',',');
            $localexpdocsubtrans['exch_rate']= $row->exch_rate;
            $localexpdocsubtrans['doc_value']=number_format($row->doc_value,2,'.',',');
            
            array_push($localexpdocsubtranses,$localexpdocsubtrans);
        }
        echo json_encode($localexpdocsubtranses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocalExpDocSubTransRequest $request) {
        $localexpdocsubtrans=$this->localexpdocsubtrans->create($request->except(['id','aa']));
        if($localexpdocsubtrans){
            return response()->json(array('success' => true,'id' =>  $localexpdocsubtrans->id,'message' => 'Save Successfully'),200);
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
       $localexpdocsubtrans = $this->localexpdocsubtrans->find($id);
        $row ['fromData'] = $localexpdocsubtrans;
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
    public function update(LocalExpDocSubTransRequest $request, $id) {
        $localexpdocsubtrans=$this->localexpdocsubtrans->update($id,$request->except(['id','aa']));
        if($localexpdocsubtrans){
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
        if($this->localexpdocsubtrans->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}
