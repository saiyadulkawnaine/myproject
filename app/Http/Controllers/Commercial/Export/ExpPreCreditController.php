<?php

namespace App\Http\Controllers\Commercial\Export;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpPreCreditRepository;
use App\Repositories\Contracts\Commercial\Export\ExpPreCreditLcScRepository;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Util\BankAccountRepository;
use App\Repositories\Contracts\Account\AccTermLoanRepository;
use App\Repositories\Contracts\Account\AccTermLoanInstallmentRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Export\ExpPreCreditRequest;

class ExpPreCreditController extends Controller {

    private $expprecredit;
    private $expprecreditlcsc;
    private $commercialhead;
    private $company;
    private $explcsc;
    private $bankaccount;
    private $acctermloan;
    private $acctermloaninstallment;

    public function __construct(
        ExpPreCreditRepository $expprecredit,
        CompanyRepository $company, 
        ExpLcScRepository $explcsc, 
        CommercialHeadRepository $commercialhead,
        BankAccountRepository $bankaccount,
        ExpPreCreditLcScRepository $expprecreditlcsc,
        AccTermLoanRepository $acctermloan,
        AccTermLoanInstallmentRepository $acctermloaninstallment
        ) {

        $this->explcsc = $explcsc;
        $this->expprecredit = $expprecredit;
        $this->expprecreditlcsc = $expprecreditlcsc;
        $this->commercialhead = $commercialhead;
        $this->bankaccount = $bankaccount;
        $this->company = $company;
        $this->acctermloan = $acctermloan;
        $this->acctermloaninstallment = $acctermloaninstallment;

        $this->middleware('auth');
        $this->middleware('permission:view.expprecredits',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.expprecredits', ['only' => ['store']]);
        $this->middleware('permission:edit.expprecredits',   ['only' => ['update']]);
        $this->middleware('permission:delete.expprecredits', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'-Select-','');
        $loantype = array_prepend(config('bprs.loantype'), '-Select-','');
         
        $expprecredits=array();
        $rows=$this->expprecredit
        ->leftjoin('bank_accounts',function($join){
            $join->on('exp_pre_credits.bank_account_id','=','bank_accounts.id');
        })
        ->leftjoin('commercial_heads',function($join){
            $join->on('commercial_heads.id','=','bank_accounts.account_type_id');
        })
        ->orderBy('exp_pre_credits.id','desc')
        ->get([
            'exp_pre_credits.*',
            'commercial_heads.name as commercial_head_name'
        ]);
        foreach($rows as $row){
            $expprecredit['id']=$row->id;
            $expprecredit['company_id']=$company[$row->company_id];
            $expprecredit['cr_date']=date('Y-m-d',strtotime($row->cr_date));
            $expprecredit['loan_type_id']=$loantype[$row->loan_type_id];
            $expprecredit['loan_no']=$row->loan_no;
            $expprecredit['commercial_head_id']=$commercialhead[$row->commercial_head_id];
            $expprecredit['commercial_head_name']=$row->commercial_head_name;
            $expprecredit['tenor']=$row->tenor;
            $expprecredit['rate']=$row->rate;
            $expprecredit['amount']=$row->amount;
            $expprecredit['remarks']=$row->remarks;
            $expprecredit['maturity_date']=date('Y-m-d',strtotime($row->maturity_date));
            array_push($expprecredits,$expprecredit);
        }
        echo json_encode($expprecredits);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        //$commercial=array_prepend(array_pluck($this->commercial->where([['commercialhead_type_id','=',8]])->get(),'name','id'),'-Select-','');
        $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'-Select-','');
        $loantype = array_prepend(config('bprs.loantype'), '-Select-','');
        return Template::LoadView('Commercial.Export.ExpPreCredit',['company'=>$company,'commercialhead'=>$commercialhead,'loantype'=>$loantype]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpPreCreditRequest $request) {
        $inputs=$request->except(['id','commercial_head_name']);
        \DB::beginTransaction();
        try
        {
            $acctermloan=$this->acctermloan->create([
                'loan_ref_no'=>$inputs['loan_no'],
                'loan_date'=>$inputs['cr_date'],
                'amount'=>0,
                'grace_period'=>$inputs['tenor'],
                'rate'=>$inputs['rate'],
                'installment_amount'=>0,
                'no_of_installment'=>1,
                'term_loan_for'=>2,
                'bank_account_id'=>$inputs['bank_account_id'],
                'remarks'=>$inputs['remarks'],
            ]);
            $this->acctermloaninstallment->create([
                'acc_term_loan_id'=>$acctermloan->id,
                'amount'=>0,
                'sort_id'=>1,
                'due_date'=>$inputs['maturity_date'],
            ]);
            $inputs['acc_term_loan_id']=$acctermloan->id;
            $expprecredit=$this->expprecredit->create($inputs);
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();

        if($expprecredit){
            return response()->json(array('success' => true,'id' =>  $expprecredit->id,'message' => 'Save Successfully'),200);
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
        $expprecredit = $this->expprecredit
        ->leftjoin('bank_accounts',function($join){
            $join->on('exp_pre_credits.bank_account_id','=','bank_accounts.id');
        })
        ->leftjoin('commercial_heads',function($join){
            $join->on('commercial_heads.id','=','bank_accounts.account_type_id');
        })
        ->where([['exp_pre_credits.id','=',$id]])
        ->get([
            'exp_pre_credits.*',
            'commercial_heads.name as commercial_head_name'
        ])
        ->first();
        $row ['fromData'] = $expprecredit;
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
    public function update(ExpPreCreditRequest $request, $id) {
    	$pc=$this->expprecredit->find($id);
        $expprecreditlcsc=$this->expprecreditlcsc->where([['exp_pre_credit_id','=',$id]])->get()->first();
        \DB::beginTransaction();
        try
        {
            if($expprecreditlcsc){
               $inputs=$request->except(['id','commercial_head_name','company_id','bank_account_id']);
               $expprecredit=$this->expprecredit->update($id,$inputs); 
            }
            else{
                $inputs=$request->except(['id','commercial_head_name']);
                $expprecredit=$this->expprecredit->update($id,$inputs);
            }
            $acctermloan=$this->acctermloan->update($pc->acc_term_loan_id,[
            'loan_ref_no'=>$inputs['loan_no'],
            'loan_date'=>$inputs['cr_date'],
            'amount'=>$pc->amount,
            'grace_period'=>$inputs['tenor'],
            'rate'=>$inputs['rate'],
            'installment_amount'=>$pc->amount,
            'no_of_installment'=>1,
            'term_loan_for'=>2,
            'remarks'=>$inputs['remarks'],
            ]);

            $this->acctermloaninstallment
            ->where([['acc_term_loan_id','=',$pc->acc_term_loan_id]])
            ->update([
            'acc_term_loan_id'=>$pc->acc_term_loan_id,
            'amount'=>$pc->amount,
            'sort_id'=>1,
            'due_date'=>$inputs['maturity_date'],
            ]);
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();

        if($expprecredit){
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
        if($this->expprecredit->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getBankAccount()
    {
        $commercialhead=array_prepend(array_pluck($this->commercialhead->get(),'name','id'),'-Select-','');
        $rows=$this->bankaccount
        ->join('bank_branches', function($join)  {
            $join->on('bank_branches.id', '=', 'bank_accounts.bank_branch_id');
        })
        ->join('banks',function($join){
            $join->on('bank_branches.bank_id','=','banks.id');
        })
        ->join('commercial_heads',function($join){
            $join->on('commercial_heads.id','=','bank_accounts.account_type_id');
        })
        ->when(request('branch_name'), function ($q) {
            return $q->where('bank_branches.name', 'LIKE', "%".request('branch_name', 0)."%");
        })
        ->when(request('account_no'), function ($q) {
            return $q->where('bank_accounts.account_no', 'LIKE', "%".request('account_no', 0)."%");
        })
        ->whereIn('commercial_heads.commercialhead_type_id',[5,6])
        ->where([['bank_accounts.company_id','=',request('company_id', 0)]])
        ->orderBy('bank_accounts.id','desc')
        ->get([
            'bank_accounts.*',
            'banks.name',
            'bank_branches.branch_name',
            'commercial_heads.name as commercial_head_name'
        ]);
        echo json_encode($rows);
    }

    public function getPc()
    {
        $loantype = array_prepend(config('bprs.loantype'), '-Select-','');
        $id=request('id',0);

        $rows=$this->expprecredit
        ->join('exp_pre_credit_lc_scs',function($join){
            $join->on('exp_pre_credits.id','=','exp_pre_credit_lc_scs.exp_pre_credit_id');
        })
        ->join('exp_lc_scs', function($join){
            $join->on('exp_lc_scs.id', '=', 'exp_pre_credit_lc_scs.exp_lc_sc_id');
        })
        ->leftJoin('bank_branches', function($join){
            $join->on('bank_branches.id', '=', 'exp_lc_scs.exporter_bank_branch_id');
        })
        ->leftJoin('banks', function($join){
            $join->on('banks.id', '=', 'bank_branches.bank_id');
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
        ->where([['exp_pre_credits.id','=',$id]])
        ->get([
            'exp_pre_credits.*',            
            'exp_lc_scs.exporter_bank_branch_id',
            'exp_lc_scs.buyer_id',
            'exp_lc_scs.beneficiary_id',
            'exp_lc_scs.currency_id',
            'exp_lc_scs.file_no',
            'exp_lc_scs.sc_or_lc',
            'buyers.name as buyer_name',
            'banks.id as bank_id',
            'banks.name as bank_name',
            'bank_branches.branch_name',
            'bank_branches.address as bank_address',
            'bank_branches.contact',
            'companies.name as beneficiary_id',
            'currencies.code as currency_code',
            'currencies.symbol as currency_symbol',
        ])
        ->map(function($rows){
            if($rows->sc_or_lc==1)
            {
              $rows->sc_or_lc_name='Sales Contract'; 
            }
            else if($rows->sc_or_lc==2){
              $rows->sc_or_lc_name='Export Lc'; 
            }
            //  $rows->lc_sc_date=date('d-M-Y',strtotime($rows->lc_sc_date));
            return $rows;
        })
        ->first();

        

        $explcscs=$this->explcsc
        ->selectRaw('
            exp_lc_scs.id,
            exp_lc_scs.lc_sc_no,
            exp_lc_scs.sc_or_lc,
            exp_lc_scs.lc_sc_date,
            exp_lc_scs.lc_sc_value,
            exp_pre_credit_lc_scs.exp_lc_sc_id
        ')
        ->join('exp_pre_credit_lc_scs', function($join){
            $join->on('exp_pre_credit_lc_scs.exp_lc_sc_id', '=', 'exp_lc_scs.id');
        })
        ->join('exp_pre_credits',function($join){
            $join->on('exp_pre_credits.id','=','exp_pre_credit_lc_scs.exp_pre_credit_id');
        })
        // ->join('companies', function($join)  {
        //     $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
        // })
        // ->join('buyers', function($join)  {
        //     $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
        // })
        ->when(request('lc_sc_no'), function ($q) {
            return $q->where('exp_lc_scs.lc_sc_no', '=', request('lc_sc_no', 0));
        })
        ->where([['exp_pre_credits.id','=',$id]])
        ->get();
        $lc_sc_amount=0;
        $lc="";
        $sc="";
        foreach ($explcscs as $explcsc) {
            if($explcsc->sc_or_lc==2){
                $lc.=$explcsc->lc_sc_no." date:".date('d-m-Y',strtotime($explcsc->lc_sc_date)).",";
            }
            if ($explcsc->sc_or_lc==1) {
                $sc.=$explcsc->lc_sc_no." date:".date('d-m-Y',strtotime($explcsc->lc_sc_date)).",";
            }
            $lc_sc_amount+=$explcsc->lc_sc_value;
        }
        $lc_string="";
        if($lc){
            $lc_string.="Export LC no: ".$lc;
        }
        $sc_string="";
        if($sc){
            $sc_string.="Sales Contract No ".$sc;
        }

        
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->AddPage();
        
        $pdf->SetFont('helvetica', 'N', 10);
        
        $sub="Sub : Application for sanctioning ".config('bprs.loantype.'.$rows['loan_type_id'])." of TK ".$rows['amount']." against ".$lc_string." ".$sc_string." ".$rows['currency_code']." ".$rows['currency_symbol'] .number_format($lc_sc_amount,3)." .";

        $body="We would like to request to sanction ".config('bprs.loantype.'.$rows['loan_type_id'])." of TK ".$rows['amount']." against ".$rows['sc_or_lc_name']." as mentioned in subject line that need to settle " .$rows['purpose'];

        $ttp2="Thanking you in advance for your kind co-operation.";

        $view= \View::make('Defult.Commercial.Export.ExpPreCreditPcPdf',['rows'=>$rows,'sub'=>$sub,'body'=>$body,/* 'ttp1'=>$ttp1, */'ttp2'=>$ttp2,'lc_sc_amount'=>$lc_sc_amount]);
        $html_content=$view->render();
        $pdf->SetY(40);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $barcodestyle = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );
        $pdf->SetX(150);
        $qrc=$rows['bank_name'].', VALUE USD '.number_format($lc_sc_amount,2).", ".$rows['buyer_id'];

        $qrc =  'Contract value:'.$rows['currency_symbol'].' '.number_format($lc_sc_amount,2).',Contract No:'.$lc_string.' '.$sc_string;
        $pdf->write2DBarcode($qrc, 'QRCODE,Q', 170, 220, 40, 40, $barcodestyle, 'N');
        $pdf->Text(170, 244, 'File No:'.$rows['file_no']);
        $pdf->Text(170, 247, 'ID  :'.$id);

        $pdf->SetFont('helvetica', 'N', 10);
        $pdf->SetFont('helvetica', '', 8);
        $filename = storage_path() . '/ExpPreCreditPcPdf.pdf';
        $pdf->output($filename);
    }
}
