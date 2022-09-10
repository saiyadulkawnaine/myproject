<?php

namespace App\Http\Controllers\Commercial\LocalExport;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpProRlzRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpProRlzAmountRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpLcRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\LocalExport\LocalExpProRlzAmountRequest;

class LocalExpProRlzAmountController extends Controller {

    private $explcsc;
    private $localexpprorlzamount;
    private $expprorlz;
    private $currency;
    private $buyer;
    private $company;

    public function __construct(LocalExpProRlzRepository $expprorlz, LocalExpProRlzAmountRepository $localexpprorlzamount, LocalExpLcRepository $explcsc,CurrencyRepository $currency,BuyerRepository $buyer,CompanyRepository $company) {
        $this->explcsc = $explcsc;
        $this->localexpproforeign = $localexpprorlzamount;
        $this->expprorlz = $expprorlz;
        $this->currency = $currency;
        $this->buyer = $buyer;
        $this->company = $company;

        $this->middleware('auth');

        // $this->middleware('permission:view.localexpprorlzamounts',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.localexpprorlzamounts', ['only' => ['store']]);
        // $this->middleware('permission:edit.localexpprorlzamounts',   ['only' => ['update']]);
        // $this->middleware('permission:delete.localexpprorlzamounts', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //$company=array_prepend(array_pluck($this->company->get(),'code','id'),'-Select-','');
        $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
        //$buyer=array_prepend(array_pluck($this->buyer->get(),'code','id'),'-Select-','');
         
        $localexpproforeigns=array();
        $rows=$this->localexpproforeign
        ->where([['local_exp_pro_rlzs.id','=',request('local_exp_pro_rlz_id',0)]])
        ->get();

        foreach($rows as $row){
            $localexpprorlzamount['id']=$row->id;
            $localexpprorlzamount['local_exp_pro_rlz_id']=$row->local_exp_pro_rlz_id;
            
            
            array_push($localexpproforeigns,$localexpprorlzamount);
        }
        echo json_encode($localexpproforeigns);
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

    public function store(LocalExpProRlzAmountRequest $request) {
        $localexpprorlzamount=$this->localexpproforeign->create($request->except(['id']));
        if($localexpprorlzamount){
            return response()->json(array('success' => true,'id' =>  $localexpprorlzamount->id,'message' => 'Save Successfully'),200);
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
       $localexpprorlzamount = $this->localexpproforeign->find($id);
       $row ['fromData'] = $localexpprorlzamount;
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

    public function update(LocalExpProRlzAmountRequest $request, $id) {
        $localexpprorlzamount=$this->localexpproforeign->update($id,$request->except(['id']));
        if($localexpprorlzamount){
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
        if($this->localexpproforeign->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}