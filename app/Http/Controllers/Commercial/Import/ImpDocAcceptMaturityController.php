<?php

namespace App\Http\Controllers\Commercial\Import;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Import\ImpDocAcceptRepository;
use App\Repositories\Contracts\Commercial\Import\ImpDocAcceptMaturityRepository;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
//use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Import\ImpDocAcceptMaturityRequest;

class ImpDocAcceptMaturityController extends Controller {

    private $impdocaccept;
    private $impdocmaturity;
    private $supplier;
    private $bankbranch;
    private $implc;
    private $commercialhead;
    private $company;

    public function __construct(
        ImpDocAcceptRepository $impdocaccept,
        ImpDocAcceptMaturityRepository $impdocmaturity,
        ImpLcRepository $implc,
        SupplierRepository $supplier,
        BankBranchRepository $bankbranch,
        CommercialHeadRepository $commercialhead,
        CompanyRepository $company
        ) {
        $this->impdocaccept = $impdocaccept;
        $this->impdocmaturity = $impdocmaturity;
        $this->supplier = $supplier;
        $this->bankbranch = $bankbranch;
        $this->implc = $implc;
        $this->commercialhead = $commercialhead;
        $this->company = $company;
        

        $this->middleware('auth');
        // $this->middleware('permission:view.impdocmaturitys',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.impdocmaturitys', ['only' => ['store']]);
        // $this->middleware('permission:edit.impdocmaturitys',   ['only' => ['update']]);
        // $this->middleware('permission:delete.impdocmaturitys', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
    //$implc=array_prepend(array_pluck($this->implc->get(),'name','id'),'-Select-','');
    $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'-Select-','');
       $impdocmaturitys = array();
       $rows = $this->impdocmaturity
       ->selectRaw('
        imp_doc_accepts.imp_lc_id,
        imp_doc_accepts.commercial_head_id,
        imp_doc_accepts.invoice_no,
        imp_doc_accepts.invoice_date,
        imp_doc_accepts.shipment_date,
        imp_doc_accepts.company_accep_date,
        imp_doc_accepts.bank_accep_date,
        imp_doc_accepts.bank_ref,
        imp_doc_accepts.loan_ref,
        imp_doc_accepts.doc_value,
        imp_doc_accepts.rate,
        imp_lcs.lc_no_i,imp_lcs.lc_no_ii,
        imp_lcs.lc_no_iii,imp_lcs.lc_no_iv,
        imp_doc_accept_maturities.id,
        imp_doc_accept_maturities.docaccept_maturity_date,
        imp_doc_accept_maturities.imp_doc_accept_id,
        imp_doc_accept_maturities.doc_invoice_id
       ')
       ->join('imp_doc_accepts', function($join)  {
            $join->on('imp_doc_accepts.id', '=', 'imp_doc_accept_maturities.doc_invoice_id');
        })
       ->join('imp_lcs', function($join)  {
            $join->on('imp_lcs.id', '=', 'imp_doc_accepts.imp_lc_id');
        })
        ->where([['imp_doc_accept_id','=',request('imp_doc_accept_id',0)]])
        ->orderBy('imp_doc_accept_maturities.id','desc')
        ->get([
            'imp_doc_accepts.*',
            'imp_doc_accepts.id as imp_doc_accept_id',
            'imp_lcs.lc_no_i',
            'imp_lcs.lc_no_ii',
            'imp_lcs.lc_no_iii',
            'imp_lcs.lc_no_iv',
            'imp_doc_accept_maturities.*'
       ]);
       foreach($rows as $row){
         $impdocmaturity['id']=$row->id;
         $impdocmaturity['imp_doc_accept_id']=$row->imp_doc_accept_id;
         $impdocmaturity['lc_no']=$row->lc_no_i." ".$row->lc_no_ii." ".$row->lc_no_iii." ".$row->lc_no_iv;
         $impdocmaturity['commercial_head_id']=$commercialhead[$row->commercial_head_id];
         $impdocmaturity['invoice_no']=$row->invoice_no;
         $impdocmaturity['invoice_date']=date('Y-m-d',strtotime($row->invoice_date));
         $impdocmaturity['shipment_date']=date('Y-m-d',strtotime($row->shipment_date));
         $impdocmaturity['company_accep_date']=date('Y-m-d',strtotime($row->company_accep_date));
         $impdocmaturity['bank_ref']=$row->bank_ref;
         $impdocmaturity['loan_ref']=$row->loan_ref;
         $impdocmaturity['doc_value']=number_format($row->doc_value,2);
         $impdocmaturity['rate']=$row->rate;
         array_push($impdocmaturitys,$impdocmaturity);
       }
       echo json_encode($impdocmaturitys);
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
    public function store(ImpDocAcceptMaturityRequest $request) {


        $impdocmaturity = $this->impdocmaturity->create(
        [
            'docaccept_maturity_date' => $request->docaccept_maturity_date,
            'doc_invoice_id' => $request->doc_invoice_id,
            'imp_doc_accept_id' => $request->imp_doc_accept_id]);
      
     

        if($impdocmaturity){
            return response()->json(array('success'=>true,'id'=>$impdocmaturity->id,'message'=>'Save Successfully'),200);
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
        $impdocmaturity=$this->impdocmaturity
        ->join('imp_doc_accepts', function($join)  {
            $join->on('imp_doc_accepts.id', '=', 'imp_doc_accept_maturities.doc_invoice_id');
        })
        ->where([['imp_doc_accept_maturities.id','=',$id]])
        ->get([
          'imp_doc_accept_maturities.*',
          'imp_doc_accepts.invoice_no'  
        ])
        ->first();
        $row['fromData']=$impdocmaturity;
        $dropdown['att']='';
        $row['dropDown']=$dropdown;
        echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ImpDocAcceptMaturityRequest $request, $id) {
        $impdocaccept=$this->impdocmaturity->update($id,$request->except([
            'docaccept_maturity_date' => $request->docaccept_maturity_date,
            'doc_invoice_id' => $request->doc_invoice_id,
            'imp_doc_accept_id' => $request->imp_doc_accept_id]));
        if($impdocmaturity){
           return response()->json(array('success'=>true,'id'=>$id,'message'=>'Updated Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $impdocmaturity = $this->impdocmaturity->findOrFail($id);
		if($impdocmaturity->forceDelete()){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}	
		else{
			 return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
		}
    }

    Public function getImpDocAccept(){
       // $impdocacceptId=$this->impdocaccept->find(request('id',0));
        $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'-Select-','');

        $rows = $this->impdocaccept
        ->selectRaw('
            imp_doc_accepts.id,
            imp_doc_accepts.imp_lc_id,
            imp_doc_accepts.commercial_head_id,
            imp_doc_accepts.invoice_no,
            imp_doc_accepts.invoice_date,
            imp_doc_accepts.shipment_date,
            imp_doc_accepts.company_accep_date,
            imp_doc_accepts.bank_accep_date,
            imp_doc_accepts.bank_ref,
            imp_doc_accepts.loan_ref,
            imp_doc_accepts.doc_value,
            imp_doc_accepts.rate,
            imp_lcs.lc_no_i,imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,imp_lcs.lc_no_iv,
            imp_doc_accept_maturities.imp_doc_accept_id
        ')
        ->join('imp_lcs', function($join)  {
            $join->on('imp_lcs.id', '=', 'imp_doc_accepts.imp_lc_id');
        })
        ->leftJoin('imp_doc_accept_maturities', function($join)  {
            $join->on('imp_doc_accepts.id', '=', 'imp_doc_accept_maturities.imp_doc_accept_id');
        })
        //->where([['imp_doc_accepts.id','=',$impdocacceptId]])
        ->orderBy('imp_doc_accepts.id','desc')
        ->get([
            'imp_doc_accepts.*',
            'imp_lcs.lc_no_i',
            'imp_lcs.lc_no_ii',
            'imp_lcs.lc_no_iii',
            'imp_lcs.lc_no_iv',
            'imp_doc_accept_maturities.doc_invoice_id'
        ])
        ->map(function($rows) use($commercialhead){
            $rows['lc_no']=$rows->lc_no_i." ".$rows->lc_no_ii." ".$rows->lc_no_iii." ".$rows->lc_no_iv;
            $rows['commercial_head_id']=$commercialhead[$rows->commercial_head_id];
            $rows['invoice_date']=date('Y-m-d',strtotime($rows->invoice_date));
            $rows['shipment_date']=date('Y-m-d',strtotime($rows->shipment_date));
            $rows['company_accep_date']=date('Y-m-d',strtotime($rows->company_accep_date));
            $rows['doc_value']=number_format($rows->doc_value,2);
            return $rows;
        });
    
        $notsaved = $rows->filter(function ($value) {
        if(!$value->imp_doc_accept_id){
            return $value;
        }
    })->values();
    echo json_encode($notsaved);
   // echo json_encode($impdocaccepts);

    }

   

}
