<?php

namespace App\Http\Controllers\Commercial\Import;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Import\ImpLiabilityAdjustRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLiabilityAdjustChldRepository;
use App\Repositories\Contracts\Commercial\Import\ImpDocAcceptRepository;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Repositories\Contracts\Util\BankRepository;
use App\Repositories\Contracts\Util\BankBranchRepository;

use App\Library\Template;

use App\Http\Requests\Commercial\Import\ImpLiabilityAdjustRequest;

class ImpLiabilityAdjustController extends Controller {

    private $impliabilityadjust;
    private $impliabladjustchld;
    private $commercialhead;
    private $impdocaccept;
    private $bank;
    private $bankbranch;

    public function __construct(ImpLiabilityAdjustRepository $impliabilityadjust, ImpLiabilityAdjustChldRepository $impliabladjustchld,CommercialHeadRepository $commercialhead,ImpDocAcceptRepository $impdocaccept,BankRepository $bank,BankBranchRepository $bankbranch) {
        $this->impliabilityadjust = $impliabilityadjust;
        $this->impliabladjustchld = $impliabladjustchld;
        $this->commercialhead = $commercialhead;
        $this->impdocaccept = $impdocaccept;
        $this->bank = $bank;
        $this->bankbranch = $bankbranch;

        $this->middleware('auth');
        $this->middleware('permission:view.impliabilityadjusts',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.impliabilityadjusts', ['only' => ['store']]);
        $this->middleware('permission:edit.impliabilityadjusts',   ['only' => ['update']]);
        $this->middleware('permission:delete.impliabilityadjusts', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $bankbranch=array_prepend(array_pluck(
            $this->bankbranch
            ->leftJoin('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->get([
                'bank_branches.id',
                'bank_branches.branch_name',
                'banks.name as bank_name',
            ])
            ->map(function($bankbranch){
                $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
                return $bankbranch;
            })
            ,'name','id'),'-Select-','');
         

        $rows=$this->impliabilityadjust
        ->selectRaw('
            imp_liability_adjusts.id,
            imp_liability_adjusts.imp_liability_adjust_no,
            imp_liability_adjusts.imp_doc_accept_id,
            imp_liability_adjusts.payment_date,
            imp_liability_adjusts.remarks,
            imp_doc_accepts.bank_ref,
            imp_doc_accepts.invoice_no,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv,
            imp_lcs.supplier_id,
            imp_lcs.company_id,
            imp_lcs.issuing_bank_branch_id,
            suppliers.name as supplier_name,
            companies.name as company_name,
            sum(imp_acc_com_details.acceptance_value) as acceptance_value
        ')
        ->join('imp_doc_accepts',function($join){
            $join->on('imp_doc_accepts.id','=','imp_liability_adjusts.imp_doc_accept_id');
        })
        ->leftJoin('imp_acc_com_details', function($join)  {
            $join->on('imp_doc_accepts.id', '=', 'imp_acc_com_details.imp_doc_accept_id');
        })
        ->join('imp_lcs', function($join)  {
            $join->on('imp_lcs.id', '=', 'imp_doc_accepts.imp_lc_id');
        })
        ->join('suppliers', function($join)  {
            $join->on('suppliers.id', '=', 'imp_lcs.supplier_id');
        })
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'imp_lcs.company_id');
        })
        ->orderBy('imp_liability_adjusts.id','desc')
        ->groupBy([
            'imp_liability_adjusts.id',
            'imp_liability_adjusts.imp_liability_adjust_no',
            'imp_liability_adjusts.imp_doc_accept_id',
            'imp_liability_adjusts.payment_date',
            'imp_liability_adjusts.remarks',
            'imp_doc_accepts.bank_ref',
            'imp_doc_accepts.invoice_no',
            'imp_lcs.lc_no_i',
            'imp_lcs.lc_no_ii',
            'imp_lcs.lc_no_iii',
            'imp_lcs.lc_no_iv',
            'imp_lcs.supplier_id',
            'imp_lcs.company_id',
            'imp_lcs.issuing_bank_branch_id',
            'suppliers.name',
            'companies.name',
        ])
        ->get()
        ->map(function($rows) use($bankbranch){
            $rows->payment_date=date('d-M-Y',strtotime($rows->payment_date));
            $rows->lc_no=$rows->lc_no_i." ".$rows->lc_no_ii." ".$rows->lc_no_iii." ".$rows->lc_no_iv;
            $rows->issuing_bank_branch_id = $bankbranch[$rows->issuing_bank_branch_id];
            return $rows;
        });
        
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $impdocaccept=array_prepend(array_pluck($this->impdocaccept->get(),'imp_lc_id','id'),'-Select-','');
        $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'-Select-','');
        $bankbranch=array_prepend(array_pluck(
            $this->bankbranch
            ->leftJoin('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->get([
                'bank_branches.id',
                'bank_branches.branch_name',
                'banks.name as bank_name',
            ])
            ->map(function($bankbranch){
                $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
                return $bankbranch;
            })
            ,'name','id'),'-Select-','');
        $menu=array_prepend(config('bprs.menu'),'-Select-','');
        return Template::LoadView('Commercial.Import.ImpLiabilityAdjust',['commercialhead'=>$commercialhead,'impdocaccept'=>$impdocaccept,'bankbranch'=>$bankbranch,'menu'=>$menu]);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImpLiabilityAdjustRequest $request) {

        $max = $this->impliabilityadjust->max('imp_liability_adjust_no');
        $imp_liability_adjust_no=$max+1;
        $impliabilityadjust=$this->impliabilityadjust->create([
            'imp_liability_adjust_no'=>$imp_liability_adjust_no,'imp_doc_accept_id'=>$request->imp_doc_accept_id,
            'payment_date'=>$request->payment_date,
            'remarks'=>$request->remarks
        ]);
        if($impliabilityadjust){
            return response()->json(array('success' => true,'id' =>  $impliabilityadjust->id,'message' => 'Save Successfully'),200);
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
        $bankbranch=array_prepend(array_pluck(
            $this->bankbranch
            ->leftJoin('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->get([
                'bank_branches.id',
                'bank_branches.branch_name',
                'banks.name as bank_name',
            ])
            ->map(function($bankbranch){
                $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
                return $bankbranch;
            })
            ,'name','id'),'-Select-','');
        $menu=array_prepend(config('bprs.menu'),'-Select-','');

        $impliabilityadjust=$this->impliabilityadjust
        ->selectRaw('
            imp_doc_accepts.id as imp_doc_accept_id,
            imp_doc_accepts.bank_ref,
            imp_liability_adjusts.id,
            imp_liability_adjusts.imp_liability_adjust_no,
            imp_liability_adjusts.payment_date,
            imp_liability_adjusts.remarks,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv,
            imp_lcs.supplier_id,
            imp_lcs.menu_id,
            imp_lcs.issuing_bank_branch_id,
            suppliers.name as supplier_name,
            sum(imp_acc_com_details.acceptance_value) as acceptance_value
        ')
        ->leftJoin('imp_doc_accepts',function($join){
            $join->on('imp_doc_accepts.id','=','imp_liability_adjusts.imp_doc_accept_id');
        })
        ->leftJoin('imp_acc_com_details', function($join)  {
            $join->on('imp_doc_accepts.id', '=', 'imp_acc_com_details.imp_doc_accept_id');
        })
        ->leftJoin('imp_lcs', function($join)  {
            $join->on('imp_lcs.id', '=', 'imp_doc_accepts.imp_lc_id');
        })
        ->leftJoin('suppliers', function($join)  {
            $join->on('suppliers.id', '=', 'imp_lcs.supplier_id');
        })
        ->where([['imp_liability_adjusts.id','=',$id]])
        ->groupBy([
            'imp_doc_accepts.id',
            'imp_doc_accepts.bank_ref',
            'imp_liability_adjusts.id',
            'imp_liability_adjusts.imp_liability_adjust_no',
            'imp_liability_adjusts.payment_date',
            'imp_liability_adjusts.remarks',
            'imp_lcs.lc_no_i',
            'imp_lcs.lc_no_ii',
            'imp_lcs.lc_no_iii',
            'imp_lcs.lc_no_iv',
            'imp_lcs.supplier_id',
            'imp_lcs.menu_id',
            'imp_lcs.issuing_bank_branch_id',
            'suppliers.name'
        ])
        ->get()
        ->map(function($impliabilityadjust) use($menu,$bankbranch){
            $impliabilityadjust->lc_no=$impliabilityadjust->lc_no_i." ".$impliabilityadjust->lc_no_ii." ".$impliabilityadjust->lc_no_iii." ".$impliabilityadjust->lc_no_iv;
            $impliabilityadjust->issuing_bank_branch_id = $bankbranch[$impliabilityadjust->issuing_bank_branch_id];
            $impliabilityadjust->menu_id = $menu[$impliabilityadjust->menu_id];
            return $impliabilityadjust;
        })
        ->first();

        

        $row ['fromData'] = $impliabilityadjust;
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
    public function update(ImpLiabilityAdjustRequest $request, $id) {
        $impliabilityadjust=$this->impliabilityadjust->update($id,
        [
            'payment_date'=>$request->payment_date,
            'remarks'=>$request->remarks
        ]);
        if($impliabilityadjust){
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
        if($this->impliabilityadjust->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function GetImpDocAccept(){
        $bankbranch=array_prepend(array_pluck(
            $this->bankbranch
            ->leftJoin('banks',function($join){
                $join->on('banks.id','=','bank_branches.bank_id');
            })
            ->get([
                'bank_branches.id',
                'bank_branches.branch_name',
                'banks.name as bank_name',
            ])
            ->map(function($bankbranch){
                $bankbranch->name=$bankbranch->bank_name.' (' .$bankbranch->branch_name. ' )';
                return $bankbranch;
            })
            ,'name','id'),'-Select-','');
        $menu=array_prepend(config('bprs.menu'),'-Select-','');

        $impdocaccepts=collect(\DB::SELECT("
        select 
            imp_lcs.id,
            imp_lcs.menu_id,
            imp_lcs.supplier_id,
            imp_lcs.issuing_bank_branch_id,
            imp_lcs.lc_no_i,
            imp_lcs.lc_no_ii,
            imp_lcs.lc_no_iii,
            imp_lcs.lc_no_iv,
            imp_doc_accepts.id as imp_doc_accept_id,
            imp_doc_accepts.imp_lc_id,
            imp_doc_accepts.commercial_head_id,
            imp_doc_accepts.bank_ref,
            imp_doc_accepts.invoice_no,
            imp_doc_accepts.invoice_date,
            suppliers.name as supplier_name,
            imp_liability_adjusts.id as imp_liability_adjust_id,
            sum(imp_acc_com_details.acceptance_value) as acceptance_value,
            case when 
            imp_lcs.menu_id=1
            then sum(po_fabrics.amount)
            when 
            imp_lcs.menu_id=2
            then sum(po_trims.amount)
            when 
            imp_lcs.menu_id=3
            then sum(po_yarns.amount)
            when 
            imp_lcs.menu_id=4
            then sum(po_knit_services.amount)
            when 
            imp_lcs.menu_id=5
            then sum(po_aop_services.amount)
            when 
            imp_lcs.menu_id=6
            then sum(po_dyeing_services.amount)
            when 
            imp_lcs.menu_id=7
            then sum(po_dye_chems.amount)
            when 
            imp_lcs.menu_id=8
            then sum(po_generals.amount)
            when 
            imp_lcs.menu_id=9
            then sum(po_yarn_dyeings.amount)
            when 
            imp_lcs.menu_id=10
            then sum(po_emb_services.amount)
            else 0
            end as lc_amount
            from imp_doc_accepts
            left join imp_acc_com_details on imp_doc_accepts.id=imp_acc_com_details.imp_doc_accept_id
            join imp_lcs on imp_doc_accepts.imp_lc_id=imp_lcs.id 
            join suppliers on suppliers.id=imp_lcs.supplier_id
            left join commercial_heads on commercial_heads.id=imp_doc_accepts.commercial_head_id
            left join imp_liability_adjusts on imp_liability_adjusts.imp_doc_accept_id=imp_doc_accepts.id
            
            join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id and imp_acc_com_details.imp_lc_po_id=imp_lc_pos.id
            left join po_fabrics on po_fabrics.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=1
            left join po_trims on po_trims.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=2
            left join po_yarns on po_yarns.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=3
            left join po_knit_services on po_knit_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=4
            left join po_aop_services on po_aop_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=5
            left join po_dyeing_services on po_dyeing_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=6
            left join po_dye_chems on po_dye_chems.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=7
            left join po_generals on po_generals.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=8
            left join po_yarn_dyeings on po_yarn_dyeings.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=9
            left join po_emb_services on po_emb_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=10
            group by 
                imp_lcs.id,
                imp_lcs.menu_id,
                imp_lcs.supplier_id,
                imp_lcs.issuing_bank_branch_id,
                imp_lcs.lc_no_i,
                imp_lcs.lc_no_ii,
                imp_lcs.lc_no_iii,
                imp_lcs.lc_no_iv,
                imp_doc_accepts.id,
                imp_doc_accepts.imp_lc_id,
                imp_doc_accepts.commercial_head_id,
                imp_doc_accepts.bank_ref,
                imp_doc_accepts.invoice_no,
                imp_doc_accepts.invoice_date,
                suppliers.name,
                imp_liability_adjusts.id 
            "))
            ->map(function($impdocaccepts) use($menu,$bankbranch){
                $impdocaccepts->lc_no=$impdocaccepts->lc_no_i." ".$impdocaccepts->lc_no_ii." ".$impdocaccepts->lc_no_iii." ".$impdocaccepts->lc_no_iv;
                $impdocaccepts->issuing_bank_branch_id = $bankbranch[$impdocaccepts->issuing_bank_branch_id];
                $impdocaccepts->menu_id = $menu[$impdocaccepts->menu_id];
                $impdocaccepts->lc_amount = number_format($impdocaccepts->lc_amount,2);
                $impdocaccepts->invoice_date = date('d-m-Y',strtotime($impdocaccepts->invoice_date));
                return $impdocaccepts;
            });

        echo json_encode($impdocaccepts);
    }
}
