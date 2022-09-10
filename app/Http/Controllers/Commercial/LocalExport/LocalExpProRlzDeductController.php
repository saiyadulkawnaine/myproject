<?php

namespace App\Http\Controllers\Commercial\LocalExport;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpProRlzRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpProRlzDeductRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpLcRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\LocalExport\LocalExpProRlzDeductRequest;

class LocalExpProRlzDeductController extends Controller {

    private $explcsc;
    private $localexpprorlzdeduct;
    private $expprorlz;
    private $currency;
    private $buyer;
    private $company;

    public function __construct(LocalExpProRlzRepository $expprorlz, LocalExpProRlzDeductRepository $localexpprorlzdeduct, LocalExpLcRepository $explcsc,CurrencyRepository $currency,BuyerRepository $buyer,CompanyRepository $company) {
        $this->explcsc = $explcsc;
        $this->localexpprorlzdeduct = $localexpprorlzdeduct;
        $this->expprorlz = $expprorlz;
        $this->currency = $currency;
        $this->buyer = $buyer;
        $this->company = $company;

        $this->middleware('auth');

        // $this->middleware('permission:view.localexpprorlzdeducts',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.localexpprorlzdeducts', ['only' => ['store']]);
        // $this->middleware('permission:edit.localexpprorlzdeducts',   ['only' => ['update']]);
        // $this->middleware('permission:delete.localexpprorlzdeducts', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
        $localexpprorlzdeducts=array();
        $rows=$this->localexpprorlzdeduct
        ->where([['local_exp_pro_rlzs.id','=',request('local_exp_pro_rlz_id',0)]])
        ->get();

        /*foreach($rows as $row){
            $localexpprorlzdeduct['id']=$row->id;
            $localexpprorlzdeduct['local_exp_pro_rlz_id']=$row->local_exp_pro_rlz_id;
            array_push($localexpprorlzdeducts,$localexpprorlzdeduct);
        }*/
        echo json_encode($rows);
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
    public function store(LocalExpProRlzDeductRequest $request) {
        $localexpprorlzdeduct=$this->localexpprorlzdeduct->create($request->except(['id']));
        if($localexpprorlzdeduct){
            return response()->json(array('success' => true,'id' =>  $localexpprorlzdeduct->id,'message' => 'Save Successfully'),200);
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
       $localexpprorlzdeduct = $this->localexpprorlzdeduct->find($id);
       $row ['fromData'] = $localexpprorlzdeduct;
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
    public function update(LocalExpProRlzDeductRequest $request, $id) {
        $localexpprorlzdeduct=$this->localexpprorlzdeduct->update($id,$request->except(['id']));
        if($localexpprorlzdeduct){
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
        if($this->localexpprorlzdeduct->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}