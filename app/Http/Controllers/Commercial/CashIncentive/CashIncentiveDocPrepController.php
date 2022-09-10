<?php
namespace App\Http\Controllers\Commercial\CashIncentive;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRefRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveDocPrepRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\CashIncentive\CashIncentiveDocPrepRequest;

class CashIncentiveDocPrepController extends Controller {

    private $cashincentivedocprep;
    private $cashincentiveref;

    public function __construct(CashIncentiveDocPrepRepository $cashincentivedocprep,CashIncentiveRefRepository $cashincentiveref) {
        $this->cashincentivedocprep = $cashincentivedocprep;
        $this->cashincentiveref = $cashincentiveref;

        $this->middleware('auth');

        $this->middleware('permission:view.cashincentivedocpreps',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.cashincentivedocpreps', ['only' => ['store']]);
        $this->middleware('permission:edit.cashincentivedocpreps',   ['only' => ['update']]);
        $this->middleware('permission:delete.cashincentivedocpreps', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
         
        $cashincentivedocpreps=array();
        $rows=$this->cashincentivedocprep
        ->where([['cash_incentive_ref_id','=',request('cash_incentive_ref_id',0)]])
        ->orderBy('cash_incentive_doc_preps.id','desc')
        ->get();
        
        foreach($rows as $row){
            $cashincentivedocprep['id']=$row->id;
            $cashincentivedocprep['exp_lc_sc_arranged']=$row->exp_lc_sc_arranged;
            $cashincentivedocprep['exp_lc_sc_remarks']=$row->exp_lc_sc_remarks;
            $cashincentivedocprep['exp_invoice_arranged']=$row->exp_invoice_arranged;
            $cashincentivedocprep['exp_invoice_remarks']=$row->exp_invoice_remarks;
            $cashincentivedocprep['exp_packinglist_arranged']=$row->exp_packinglist_arranged;
            $cashincentivedocprep['exp_packinglist_remarks']=$row->exp_packinglist_remarks;
            $cashincentivedocprep['bill_of_loading_arranged']=$row->bill_of_loading_arranged;
            $cashincentivedocprep['exp_bill_of_entry_remarks']=$row->exp_bill_of_entry_remarks;   
            array_push($cashincentivedocpreps,$cashincentivedocprep);
        }
        echo json_encode($cashincentivedocpreps);
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
    public function store(CashIncentiveDocPrepRequest $request) {
        $cashincentivedocprep=$this->cashincentivedocprep->updateOrCreate(
            [
                'cash_incentive_ref_id'=>$request->cash_incentive_ref_id
            ],
            [
                'exp_lc_sc_arranged'=>$request->exp_lc_sc_arranged,
                'exp_lc_sc_remarks'=>$request->exp_lc_sc_remarks,
                'exp_invoice_arranged'=>$request->exp_invoice_arranged,
                'exp_invoice_remarks'=>$request->exp_invoice_remarks,
                'exp_packinglist_arranged'=>$request->exp_packinglist_arranged,
                'exp_packinglist_remarks'=>$request->exp_packinglist_remarks,
                'bill_of_loading_arranged'=>$request->bill_of_loading_arranged,
                'bill_of_loading_remarks'=>$request->bill_of_loading_remarks,
                'exp_bill_of_entry_arranged'=>$request->exp_bill_of_entry_arranged,
                'exp_bill_of_entry_remarks'=>$request->exp_bill_of_entry_remarks,
                'exp_form_arranged'=>$request->exp_form_arranged,
                'exp_form_remarks'=>$request->exp_form_remarks,
                'gsp_co_arranged'=>$request->gsp_co_arranged,
                'gsp_co_remarks'=>$request->gsp_co_remarks,
                'prc_bd_format_arranged'=>$request->prc_bd_format_arranged,
                'prc_bd_format_remarks'=>$request->prc_bd_format_remarks,
                'ud_copy_arranged'=>$request->ud_copy_arranged,
                'ud_copy_remarks'=>$request->ud_copy_remarks,
                'btb_lc_arranged'=>$request->btb_lc_arranged,
                'btb_lc_remarks'=>$request->btb_lc_remarks,
                'import_pi_arranged'=>$request->import_pi_arranged,
                'import_pi_remarks'=>$request->import_pi_remarks,
                'gsp_certify_btma_arranged'=>$request->gsp_certify_btma_arranged,
                'gsp_certify_btma_remarks'=>$request->gsp_certify_btma_remarks,
                'vat_eleven_arranged'=>$request->vat_eleven_arranged,
                'vat_eleven_remarks'=>$request->vat_eleven_remarks,
                'rcv_yarn_challan_arranged'=>$request->rcv_yarn_challan_arranged,
                'rcv_yarn_challan_remarks'=>$request->rcv_yarn_challan_remarks,
                'imp_invoice_arranged'=>$request->imp_invoice_arranged,
                'imp_invoice_remarks'=>$request->imp_invoice_remarks,
                'imp_packing_list_arranged'=>$request->imp_packing_list_arranged,
                'imp_packing_list_remarks'=>$request->imp_packing_list_remarks,
                'bnf_certify_spin_mil_arranged'=>$request->bnf_certify_spin_mil_arranged,
                'bnf_certify_spin_mil_remarks'=>$request->bnf_certify_spin_mil_remarks,
                'certificate_of_origin_arranged'=>$request->certificate_of_origin_arranged,
                'certificate_of_origin_remarks'=>$request->certificate_of_origin_remarks,
                'alt_cash_assist_bgmea_arranged'=>$request->alt_cash_assist_bgmea_arranged,
                'alt_cash_assist_bgmea_remarks'=>$request->alt_cash_assist_bgmea_remarks,
                'cash_certify_btma_arranged'=>$request->cash_certify_btma_arranged,
                'cash_certify_btma_remarks'=>$request->cash_certify_btma_remarks,
            ]
        );
        if($cashincentivedocprep){
            return response()->json(array('success' => true,'id' =>  $cashincentivedocprep->id,'message' => 'Save Successfully'),200);
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
        //$cashincentiveref=$this->cashincentiveref->
       $cashincentivedocprep = $this->cashincentivedocprep
       ->where([['cash_incentive_doc_preps.cash_incentive_ref_id' ,'=',$id]])
       ->get()
       ->first();
       $row ['fromData'] = $cashincentivedocprep;
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
    public function update(CashIncentiveDocPrepRequest $request, $id) {
        $cashincentivedocprep=$this->cashincentivedocprep->update($id,$request->except(['id']));
        if($cashincentivedocprep){
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
        if($this->cashincentivedocprep->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}
