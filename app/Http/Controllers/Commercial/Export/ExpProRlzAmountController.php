<?php

namespace App\Http\Controllers\Commercial\Export;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpProRlzRepository;
use App\Repositories\Contracts\Commercial\Export\ExpProRlzAmountRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Export\ExpProRlzAmountRequest;

class ExpProRlzAmountController extends Controller {

    private $explcsc;
    private $expprorlzamount;
    private $expprorlz;
    private $currency;
    private $buyer;
    private $company;

    public function __construct(ExpProRlzRepository $expprorlz, ExpProRlzAmountRepository $expprorlzamount, ExpLcScRepository $explcsc,CurrencyRepository $currency,BuyerRepository $buyer,CompanyRepository $company) {
        $this->explcsc = $explcsc;
        $this->expproforeign = $expprorlzamount;
        $this->expprorlz = $expprorlz;
        $this->currency = $currency;
        $this->buyer = $buyer;
        $this->company = $company;

        $this->middleware('auth');

        $this->middleware('permission:view.expprorlzamounts',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.expprorlzamounts', ['only' => ['store']]);
        $this->middleware('permission:edit.expprorlzamounts',   ['only' => ['update']]);
        $this->middleware('permission:delete.expprorlzamounts', ['only' => ['destroy']]);
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
         
        $expproforeigns=array();
        $rows=$this->expproforeign
        ->where([['exp_pro_rlzs.id','=',request('exp_pro_rlz_id',0)]])
        ->get();

        foreach($rows as $row){
            $expprorlzamount['id']=$row->id;
            $expprorlzamount['exp_pro_rlz_id']=$row->exp_pro_rlz_id;
            
            
            array_push($expproforeigns,$expprorlzamount);
        }
        echo json_encode($expproforeigns);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {



        //return Template::LoadView('Commercial.Export.ExpProRlz',['company'=>$company,'currency'=>$currency,'buyer'=>$buyer]);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpProRlzAmountRequest $request) {
        $expprorlzamount=$this->expproforeign->create($request->except(['id']));
        if($expprorlzamount){
            return response()->json(array('success' => true,'id' =>  $expprorlzamount->id,'message' => 'Save Successfully'),200);
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
       $expprorlzamount = $this->expproforeign->find($id);
       $row ['fromData'] = $expprorlzamount;
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
    public function update(ExpProRlzAmountRequest $request, $id) {
        $expprorlzamount=$this->expproforeign->update($id,$request->except(['id']));
        if($expprorlzamount){
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
        if($this->expproforeign->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    
}