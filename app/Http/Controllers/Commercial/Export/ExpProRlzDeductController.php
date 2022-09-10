<?php

namespace App\Http\Controllers\Commercial\Export;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpProRlzRepository;
use App\Repositories\Contracts\Commercial\Export\ExpProRlzDeductRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Export\ExpProRlzDeductRequest;

class ExpProRlzDeductController extends Controller {

    private $explcsc;
    private $expprorlzdeduct;
    private $expprorlz;
    private $currency;
    private $buyer;
    private $company;

    public function __construct(ExpProRlzRepository $expprorlz, ExpProRlzDeductRepository $expprorlzdeduct, ExpLcScRepository $explcsc,CurrencyRepository $currency,BuyerRepository $buyer,CompanyRepository $company) {
        $this->explcsc = $explcsc;
        $this->expprorlzdeduct = $expprorlzdeduct;
        $this->expprorlz = $expprorlz;
        $this->currency = $currency;
        $this->buyer = $buyer;
        $this->company = $company;

        $this->middleware('auth');

        $this->middleware('permission:view.expprorlzdeducts',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.expprorlzdeducts', ['only' => ['store']]);
        $this->middleware('permission:edit.expprorlzdeducts',   ['only' => ['update']]);
        $this->middleware('permission:delete.expprorlzdeducts', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $currency=array_prepend(array_pluck($this->currency->get(),'code','id'),'-Select-','');
        $expprorlzdeducts=array();
        $rows=$this->expprorlzdeduct
        ->where([['exp_pro_rlzs.id','=',request('exp_pro_rlz_id',0)]])
        ->get();

        /*foreach($rows as $row){
            $expprorlzdeduct['id']=$row->id;
            $expprorlzdeduct['exp_pro_rlz_id']=$row->exp_pro_rlz_id;
            
            
            array_push($expprorlzdeducts,$expprorlzdeduct);
        }*/
        echo json_encode($rows);
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
    public function store(ExpProRlzDeductRequest $request) {
        $expprorlzdeduct=$this->expprorlzdeduct->create($request->except(['id']));
        if($expprorlzdeduct){
            return response()->json(array('success' => true,'id' =>  $expprorlzdeduct->id,'message' => 'Save Successfully'),200);
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
       $expprorlzdeduct = $this->expprorlzdeduct->find($id);
       $row ['fromData'] = $expprorlzdeduct;
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
    public function update(ExpProRlzDeductRequest $request, $id) {
        $expprorlzdeduct=$this->expprorlzdeduct->update($id,$request->except(['id']));
        if($expprorlzdeduct){
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
        if($this->expprorlzdeduct->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}