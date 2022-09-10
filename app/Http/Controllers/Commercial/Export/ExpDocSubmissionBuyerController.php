<?php

namespace App\Http\Controllers\Commercial\Export;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpDocSubmissionRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Commercial\Export\ExpDocSubInvoiceRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Export\ExpDocSubmissionRequest;
use App\Repositories\Contracts\Util\CommercialHeadRepository;

class ExpDocSubmissionBuyerController extends Controller {

    private $expdocsubmission;
    private $company;
    private $buyer;
    private $currency;
    private $explcsc;

    public function __construct(ExpDocSubmissionRepository $expdocsubmission,ExpLcScRepository $explcsc,CompanyRepository $company, BuyerRepository $buyer,CurrencyRepository $currency, CommercialHeadRepository $commercialhead, ExpDocSubInvoiceRepository $expdocsubinvoice ) {

        $this->explcsc = $explcsc;
        $this->expdocsubmission = $expdocsubmission;
        $this->commercialhead = $commercialhead;
        $this->expdocsubinvoice = $expdocsubinvoice;
        $this->currency = $currency;
        $this->company = $company;
        $this->buyer = $buyer;

    $this->middleware('auth');

    $this->middleware('permission:view.expdocsubmissionbuyers',   ['only' => ['create', 'index','show']]);
    $this->middleware('permission:create.expdocsubmissionbuyers', ['only' => ['store']]);
    $this->middleware('permission:edit.expdocsubmissionbuyers',   ['only' => ['update']]);
    $this->middleware('permission:delete.expdocsubmissionbuyers', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {   
        $submissiontype=array_prepend(config('bprs.submissiontype'), '','');      
        $expdocsubmissions=array();
        $rows=$this->expdocsubmission
        ->join('exp_lc_scs',function($join){
            $join->on('exp_lc_scs.id','=','exp_doc_submissions.exp_lc_sc_id');
        })
        ->join('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
        })
        ->join('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        ->where([['exp_doc_submissions.doc_submitted_to_id',2]])
        ->orderBy('exp_doc_submissions.id','desc')
        ->get([            
            'exp_doc_submissions.*',
            'exp_lc_scs.lc_sc_no',
            'exp_lc_scs.beneficiary_id',
            'exp_lc_scs.buyer_id', 
            'exp_lc_scs.buyers_bank', 
            'exp_lc_scs.currency_id', 
            'buyers.name as buyer_id',
            'companies.name as beneficiary_id',
            'currencies.name as currency_id',
            ]);
        //->get();
        foreach($rows as $row){
            $expdocsubmission['id']=$row->id;
            $expdocsubmission['exp_lc_sc_id']=$row->exp_lc_sc_id;
            $expdocsubmission['lc_sc_no']=$row->lc_sc_no;
            $expdocsubmission['submission_date']=($row->submission_date !== null)?date("Y-m-d",strtotime($row->submission_date)):null;
            $expdocsubmission['submission_type_id']=$submissiontype[$row->submission_type_id];
            $expdocsubmission['bank_ref_bill_no']=$row->bank_ref_bill_no;
            $expdocsubmission['negotiation_date']=($row->negotiation_date !== null)?date('Y-m-d',strtotime($row->negotiation_date)):null;
            $expdocsubmission['days_to_realize']=$row->days_to_realize;
            $expdocsubmission['possible_realization_date']=($row->possible_realization_date !== null)?date('Y-m-d',strtotime($row->possible_realization_date)):null;
            $expdocsubmission['courier_recpt_no']=$row->courier_recpt_no;
            array_push($expdocsubmissions,$expdocsubmission);
        }
        echo json_encode($expdocsubmissions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $submissiontype=array_prepend(config('bprs.submissiontype'), '-Select-','');
        $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'-Select-','');

        return Template::LoadView('Commercial.Export.ExpDocSubmissionBuyer',['currency'=>$currency,'commercialhead'=>$commercialhead,'submissiontype'=>$submissiontype,'company'=>$company]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpDocSubmissionRequest $request) {
        $request->request->add(['doc_submitted_to_id' =>2]);
        $expdocsubmission=$this->expdocsubmission->create($request->except(['id','beneficiary_id','buyer_id','currency_id','buyers_bank','lc_sc_no']));
        if($expdocsubmission){
            return response()->json(array('success' => true,'id' =>  $expdocsubmission->id,'message' => 'Save Successfully'),200);
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
       $expdocsubmission = $this->expdocsubmission
        ->join('exp_lc_scs',function($join){
                $join->on('exp_lc_scs.id','=','exp_doc_submissions.exp_lc_sc_id');
            })
            ->join('companies',function($join){
                $join->on('companies.id','=','exp_lc_scs.beneficiary_id');
            })
        ->join('buyers',function($join){
                $join->on('buyers.id','=','exp_lc_scs.buyer_id');
            })
        ->join('currencies',function($join){
                $join->on('currencies.id','=','exp_lc_scs.currency_id');
            })
            ->where([['exp_doc_submissions.id','=',$id]])
            ->get([
                'exp_doc_submissions.*',
                'exp_lc_scs.id as exp_lc_sc_id',
                'exp_lc_scs.lc_sc_no',
                'exp_lc_scs.beneficiary_id',
                'exp_lc_scs.buyer_id', 
                'exp_lc_scs.buyers_bank', 
                'exp_lc_scs.currency_id', 
                'buyers.name as buyer_id',
                'companies.name as beneficiary_id',
                'currencies.name as currency_id'
            ])
        ->first();
       
        $expdocsubmission['submission_date']=($expdocsubmission->submission_date !== null)?date("Y-m-d",strtotime($expdocsubmission->submission_date)):null;
        $expdocsubmission['negotiation_date']=($expdocsubmission->negotiation_date !== null )?date("Y-m-d",strtotime($expdocsubmission->negotiation_date)):null;
        $expdocsubmission['possible_realization_date']=($expdocsubmission->possible_realization_date !== null)?date("Y-m-d",strtotime($expdocsubmission->possible_realization_date)):null;
        $row ['fromData'] = $expdocsubmission;
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
    public function update(ExpDocSubmissionRequest $request, $id) {
        $expdocsubmission=$this->expdocsubmission->update($id,$request->except(['id','beneficiary_id','buyer_id','currency_id','buyers_bank','lc_sc_no']));
        if($expdocsubmission){
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
        if($this->expdocsubmission->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getDocSubBuyerLc(){
        

        $rows=$this->explcsc
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','exp_lc_scs.beneficiary_id');
        })
       ->leftJoin('buyers',function($join){
            $join->on('buyers.id','=','exp_lc_scs.buyer_id');
        })
       ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','exp_lc_scs.currency_id');
        })
        ->when(request('lc_sc_no'), function ($q) {
            return $q->where('exp_lc_scs.lc_sc_no', 'LIKE', "%".request('lc_sc_no', 0)."%");
        }) 
         ->when(request('beneficiary_id'), function ($q) {
            return $q->where('exp_lc_scs.beneficiary_id', '=', request('beneficiary_id', 0));
        })
        //->groupBy(['exp_lc_scs.id'])
        ->orderBy('exp_lc_scs.id','asc')
        ->get([
            'exp_lc_scs.*',
           /* 'exp_lc_scs.lc_sc_no as exp_lc_sc_id',
            'exp_lc_scs.beneficiary_id',
            'exp_lc_scs.buyer_id', 
            'exp_lc_scs.buyers_bank', 
            'exp_lc_scs.currency_id', */
            'buyers.name as buyer_id',
            'companies.name as beneficiary_id',
            'currencies.name as currency_id'
        ]);
            //return $rows;
            echo json_encode($rows);
    }
}
