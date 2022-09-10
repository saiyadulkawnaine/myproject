<?php

namespace App\Http\Controllers\Commercial\Export;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpPiRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Export\ExpPiRequest;

class ExpPiController extends Controller {

    private $exppi;
    private $itemclass;
    private $buyer;
    private $salesorder;
    private $Job;
    private $company;

    public function __construct(ExpPiRepository $exppi,ItemclassRepository $itemclass,BuyerRepository $buyer,ItemAccountRepository $itemaccount,SalesOrderRepository $salesorder, JobRepository $job, CompanyRepository $company) {
        $this->exppi = $exppi;
        $this->itemclass = $itemclass;
        $this->buyer = $buyer;
        $this->salesorder = $salesorder;
        $this->job = $job;
        $this->company = $company;

        $this->middleware('auth');
        $this->middleware('permission:view.exppis',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.exppis', ['only' => ['store']]);
        $this->middleware('permission:edit.exppis',   ['only' => ['update']]);
        $this->middleware('permission:delete.exppis', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      /* $exppi=$this->exppi->where([['company_id','=',2]])->orderBy('id')->get();
       $i=1;
      foreach($exppi as $row){
        $exppiorder=$this->exppi->update($row->id,[
            'sys_pi_no'=>$i
        ]);
       $i++;
      }*/
       $itemclass = array_prepend(array_pluck($this->itemclass->where([['item_nature_id','=',100]])->orderBy('name')->get(),'name','id'), '-Select-','');
       $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
       $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
       $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
       $incoterm = array_prepend(config('bprs.incoterm'), '-Select-','');
       $exppis=array();
       $rows=$this->exppi->orderBy('id','desc')->get();
       foreach($rows as $row){
           $exppi['id']=$row->id;
           $exppi['pi_no']=$row->pi_no;
           $exppi['sys_pi_no']=$row->sys_pi_no;
           $exppi['itemclass_id']=isset($itemclass[$row->itemclass_id])?$itemclass[$row->itemclass_id]:'';
           $exppi['company_id']=isset($company[$row->company_id])?$company[$row->company_id]:'';
           $exppi['buyer_id']=isset($buyer[$row->buyer_id])?$buyer[$row->buyer_id]:'';
           $exppi['pi_validity_days']=$row->pi_validity_days;
           $exppi['pi_date']=date('Y-m-d',strtotime($row->pi_date));
           $exppi['file_no']=$row->file_no;
           $exppi['pay_term_id']=isset($payterm[$row->pay_term_id])?$payterm[$row->pay_term_id]:'';
           $exppi['tenor']=$row->tenor;
           $exppi['incoterm_id']=isset($incoterm[$row->incoterm_id])?$incoterm[$row->incoterm_id]:'';
           $exppi['incoterm_place']=$row->incoterm_place;
           $exppi['delivery_date']=date('Y-m-d',strtotime($row->delivery_date));
           $exppi['port_of_entry']=$row->port_of_entry;
           $exppi['port_of_loading']=$row->port_of_loading;
           $exppi['port_of_discharge']=$row->port_of_discharge;
           $exppi['final_destination']=$row->final_destination;
           $exppi['etd_port']=$row->etd_port;
           $exppi['eta_port']=$row->eta_port;
           $exppi['hs_code']=$row->hs_code;
           $exppi['remarks']=$row->remarks;
           array_push($exppis, $exppi);
       }
       echo json_encode($exppis);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $job=array_prepend(array_pluck($this->job->get(),'job_no','id'),'-Select-','');
      $salesorder=array_prepend(array_pluck($this->salesorder->get(),'job_no','id'),'-Select-','');
      $exppi = array_prepend(array_pluck($this->exppi->get(),'name','id'), '-Select-','');
      $itemclass = array_prepend(array_pluck($this->itemclass->where([['item_nature_id','=',100]])->orderBy('name')->get(),'name','id'), '-Select-','');
      $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
      $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
      $incoterm = array_prepend(config('bprs.incoterm'), '-Select-','');
      return Template::loadView('Commercial.Export.ExpPi',['itemclass'=>$itemclass,'buyer'=>$buyer,'payterm'=>$payterm,'incoterm'=>$incoterm,'exppi'=>$exppi,'salesorder'=>$salesorder,'job'=>$job,'company'=>$company]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpPiRequest $request) {
    $max = $this->exppi->where([['company_id', $request->company_id]])->max('sys_pi_no');
    $sys_pi_no=$max+1;
    $request->request->add(['sys_pi_no' => $sys_pi_no]);
		$exppi=$this->exppi->create($request->except(['id']));
        if($exppi){
            return response()->json(array('success'=>true,'id'=>$exppi->id,'message'=>'Save Successfully'),200);
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
       $exppi=$this->exppi->find($id);
       $row['fromData']=$exppi;
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
    public function update(ExpPiRequest $request, $id) {
        $exppi=$this->exppi->update($id,$request->except(['id','sys_pi_no','company_id']));
        if($exppi){
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
        if($this->exppi->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		    }
        else
        {
          return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
        } 
    }

}
