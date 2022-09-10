<?php

namespace App\Http\Controllers\Commercial\Import;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Import\ImpDocAcceptRepository;
use App\Repositories\Contracts\Commercial\Import\ImpDocMaturityRepository;
use App\Repositories\Contracts\Commercial\Import\ImpDocMaturityDtlRepository;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
//use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Import\ImpDocMaturityDtlRequest;

class ImpDocMaturityDtlController extends Controller {

    private $impdocaccept;
    private $impdocmaturity;
    private $impdocmaturitydtl;
    private $supplier;
    private $bankbranch;
    private $implc;
    private $commercialhead;
    private $company;

    public function __construct(
        ImpDocAcceptRepository $impdocaccept,
        ImpDocMaturityRepository $impdocmaturity,
        ImpDocMaturityDtlRepository $impdocmaturitydtl,
        ImpLcRepository $implc,
        SupplierRepository $supplier,
        BankBranchRepository $bankbranch,
        CommercialHeadRepository $commercialhead,
        CompanyRepository $company
        ) {
        $this->impdocaccept = $impdocaccept;
        $this->impdocmaturity = $impdocmaturity;
        $this->impdocmaturitydtl = $impdocmaturitydtl;
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
       $rows = $this->impdocmaturitydtl
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
        imp_doc_maturities.id as imp_doc_maturity_id,
        imp_doc_maturities.doc_maturity_date,
        imp_doc_maturity_dtls.id,
        imp_doc_maturity_dtls.imp_doc_accept_id,
        imp_doc_maturity_dtls.imp_doc_maturity_id
       ')
       ->leftJoin('imp_doc_maturities', function($join)  {
        $join->on('imp_doc_maturities.id', '=', 'imp_doc_maturity_dtls.imp_doc_maturity_id');
        })
        ->leftJoin('imp_doc_accepts', function($join)  {
            $join->on('imp_doc_accepts.id', '=', 'imp_doc_maturity_dtls.imp_doc_accept_id');
        })
       ->leftJoin('imp_lcs', function($join)  {
            $join->on('imp_lcs.id', '=', 'imp_doc_accepts.imp_lc_id');
        })
        ->when(request('imp_doc_maturity_id'), function ($q) {
			return $q->where('imp_doc_maturity_id', '=', request('imp_doc_maturity_id', 0));
		})
        //->where([['imp_doc_maturity_dtls.imp_doc_maturity_id','=',request('imp_doc_maturity_id',0)]])
        //->orderBy('imp_doc_maturity_dtls.id','desc')
        ->get(/* [
            'imp_doc_accepts.*',
            'imp_doc_accepts.id as imp_doc_accept_id',
            'imp_lcs.lc_no_i',
            'imp_lcs.lc_no_ii',
            'imp_lcs.lc_no_iii',
            'imp_lcs.lc_no_iv',
            'imp_doc_maturities.doc_maturity_date',
            'imp_doc_maturity_dtls.*'
       ] */);
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
        return Template::LoadView('Commercial.Import.ImpDocMaturity');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImpDocMaturityDtlRequest $request) {

        $impdocmaturitydtl = $this->impdocmaturitydtl->create(
        [
            'imp_doc_maturity_id' => $request->imp_doc_maturity_id,
            'imp_doc_accept_id' => $request->imp_doc_accept_id
        ]);
      
        if($impdocmaturitydtl){
            return response()->json(array('success'=>true,'id'=>$impdocmaturitydtl->id,'message'=>'Save Successfully'),200);
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
        $impdocmaturitydtl=$this->impdocmaturitydtl
        ->join('imp_doc_maturities', function($join)  {
            $join->on('imp_doc_maturities.id', '=', 'imp_doc_maturity_dtls.imp_doc_maturity_id');
        })
        ->join('imp_doc_accepts', function($join)  {
            $join->on('imp_doc_accepts.id', '=', 'imp_doc_maturity_dtls.imp_doc_accept_id');
        })
        ->where([['imp_doc_maturity_dtls.id','=',$id]])
        ->get([
          'imp_doc_maturity_dtls.*',
          'imp_doc_accepts.invoice_no'  
        ])
        ->first();
        $row['fromData']=$impdocmaturitydtl;
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
    public function update(ImpDocMaturityRequest $request, $id) {
        $impdocmaturitydtl=$this->impdocmaturitydtl->update($id,$request->except([
            'imp_doc_maturity_id' => $request->imp_doc_maturity_id,
            'imp_doc_accept_id' => $request->imp_doc_accept_id
        ]));
        if($impdocmaturitydtl){
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
        $impdocmaturitydtl = $this->impdocmaturitydtl->findOrFail($id);
		if($impdocmaturitydtl->forceDelete()){
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
            imp_doc_maturity_dtls.imp_doc_accept_id
        ')
        ->join('imp_lcs', function($join)  {
            $join->on('imp_lcs.id', '=', 'imp_doc_accepts.imp_lc_id');
        })
        ->leftJoin('imp_doc_maturity_dtls', function($join)  {
            $join->on('imp_doc_accepts.id', '=', 'imp_doc_maturity_dtls.imp_doc_accept_id');
        })
        //->where([['imp_doc_accepts.id','=',$impdocacceptId]])
        ->orderBy('imp_doc_accepts.id','desc')
        ->get([
            'imp_doc_accepts.*',
            'imp_lcs.lc_no_i',
            'imp_lcs.lc_no_ii',
            'imp_lcs.lc_no_iii',
            'imp_lcs.lc_no_iv',
            'imp_doc_maturity_dtls.imp_doc_accept_id'
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