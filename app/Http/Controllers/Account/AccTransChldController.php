<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccTransChldRepository;
use App\Repositories\Contracts\Account\AccTransPrntRepository;
use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Repositories\Contracts\Account\AccPeriodRepository;
use App\Repositories\Contracts\Account\AccYearRepository;
use App\Repositories\Contracts\Account\AccTransPurchaseRepository;
use App\Repositories\Contracts\Account\AccTransSalesRepository;
use App\Repositories\Contracts\Account\AccTransEmployeeRepository;
use App\Repositories\Contracts\Account\AccTransOtherPartyRepository;
use App\Repositories\Contracts\Account\AccTransLoanRefRepository;
use App\Repositories\Contracts\Account\AccTransOtherRefRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierSettingRepository;
use App\Library\Template;
use App\Http\Requests\Account\AccTransChldRequest;

class AccTransChldController extends Controller {

    private $transchld;
	private $transprnt;
	private $accchartctrlhead;
	private $currency;
    private $period;
    private $accyear;
    private $accpurchase;
    private $accsales;
    private $accemployee;
    private $accotherparty;
    private $accloanref;
    private $acctransotherref;
    private $company;
    private $suppliersetting;

    public function __construct(
        AccTransChldRepository $transchld, 
        AccTransPrntRepository $transprnt, 
        AccChartCtrlHeadRepository $accchartctrlhead,
        CurrencyRepository $currency,
        AccPeriodRepository $period,
        AccYearRepository $accyear,
        AccTransPurchaseRepository $accpurchase,
        AccTransSalesRepository $accsales,
        AccTransEmployeeRepository $accemployee,
        AccTransOtherPartyRepository $accotherparty,
        AccTransLoanRefRepository $accloanref,
        AccTransOtherRefRepository $acctransotherref,
        CompanyRepository $company,
        SupplierSettingRepository $suppliersetting
    ) 
    {
		
        $this->transchld = $transchld;
		$this->transprnt = $transprnt;
		$this->accchartctrlhead = $accchartctrlhead;
        $this->currency = $currency;
        $this->period = $period;
        $this->accyear = $accyear;
        $this->accpurchase = $accpurchase;

        $this->accsales = $accsales;
        $this->accemployee = $accemployee;
        $this->accotherparty = $accotherparty;
        $this->accloanref = $accloanref;
        $this->acctransotherref = $acctransotherref;
        $this->company = $company;
        $this->suppliersetting = $suppliersetting;
        $this->middleware('auth');
        $this->middleware('permission:view.acctranschlds',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.acctranschlds', ['only' => ['store']]);
        $this->middleware('permission:edit.acctranschlds',   ['only' => ['update']]);
        $this->middleware('permission:delete.acctranschlds', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccTransChldRequest $request) {

        $companyprofitcenter=$this->company->find($request->company_id);
        
        if($request->total_debit != $request->total_credit){
        return response()->json(array('success' => false,'message' => 'Debit & credit not equal'),200);
        }

        $validtransdate=$this->accyear
            ->whereRaw('? between start_date and end_date', [$request->trans_date])
            ->where([['id','=',$request->acc_year_id]])
            ->where([['company_id','=',$request->company_id]])
            ->get(['id'])->count();

        if(! $validtransdate){
            return response()->json(array('success' => false,'message' => 'Please Select a valid transaction Date'),200);
        }

        $accYear=$this->accyear
        ->where([['id', '=', $request->acc_year_id]])
        ->where([['company_id','=',$request->company_id]])
        ->get()->first();

        if(!$accYear->is_open){
            return response()->json(array('success' => false,'message' => 'This year is closed'),200);
        }

        $yearStart=date('Y-m-d',strtotime($accYear->start_date));

        $trans_date=date('Y-m-d',strtotime($request->trans_date));

        if($request->trans_type_id==0 && $yearStart != $trans_date){
            return response()->json(array('success' => false,'message' => 'Please Select a valid transaction Date for opening balance'),200);
        }
		

        $periodid=0;
        if($request->trans_type_id==0){
            $periodid=$this->period
            ->join('acc_years',function($join){
            $join->on('acc_years.id','=','acc_periods.acc_year_id');
            })
            ->where([['acc_years.company_id','=',$request->company_id]])
            ->whereRaw('? between acc_periods.start_date and acc_periods.end_date', [$request->trans_date])
            ->where([['acc_periods.period','=',0]])
            ->where([['acc_periods.acc_year_id','=',$request->acc_year_id]])
            ->get(['acc_periods.id'])->first();

        }else{
            $periodid=$this->period
            ->join('acc_years',function($join){
            $join->on('acc_years.id','=','acc_periods.acc_year_id');
            })
            ->where([['acc_years.company_id','=',$request->company_id]])
            ->whereRaw('? between acc_periods.start_date and acc_periods.end_date', [$request->trans_date])
            ->where([['acc_periods.period','>',0]])
            ->where([['acc_periods.acc_year_id','=',$request->acc_year_id]])
            ->get(['acc_periods.id'])->first();
        }



        

        $max = $this->transprnt
        ->where([['company_id', $request->company_id]])
        ->where([['acc_year_id', $request->acc_year_id]])
        ->where([['trans_type_id', $request->trans_type_id]])
        ->max('trans_no');
        $trans_no=$max+1;
        \DB::beginTransaction();

        $deletedRows = $this->transchld->where([['acc_trans_prnt_id','=', $request->id]])->delete();

        $transprnt=$this->transprnt->create([
        	'company_id'=>$request->company_id,
        	'acc_year_id'=>$request->acc_year_id,
        	'trans_date'=>$request->trans_date,
        	'trans_type_id'=>$request->trans_type_id,
        	'trans_no'=>$trans_no,
        	'narration'=>$request->narration,
        	'acc_period_id'=>$periodid->id,
        	'amount'=>$request->total_debit,
        	'instrument_no'=>$request->instrument_no,
        	'pay_to'=>$request->pay_to,
        	'place_date'=>$request->place_date,
        ]);

        

        foreach($request->code as $index=>$code)
        {
            $amount=$request->amount_debit[$index];
            if(!$amount){
                $amount=$request->amount_credit[$index]*-1;  
            }

            $amount_foreign=$request->amount_foreign_debit[$index];
            if(!$amount_foreign){
                $amount_foreign=$request->amount_foreign_credit[$index]*-1;  
            }
            if(! $amount){
				\DB::rollback();
				return response()->json(array('success' => false,'message' => 'Debit or credit not found'),200);
				
            }
            if($request->amount_debit[$index] && $request->amount_credit[$index]){
                \DB::rollback();
                return response()->json(array('success' => false,'message' => 'Both Debit and credit not allowed'),200);
            }
			$accchartctrlhead= $this->accchartctrlhead->find($request->acc_chart_ctrl_head_id[$index]);

			
			if (($accchartctrlhead->control_name_id==12 || $accchartctrlhead->control_name_id==36 || $accchartctrlhead->control_name_id==37) && ($request->employee_id[$index]=='' || $request->employee_id[$index]==0))
			{
				\DB::rollback();
				return response()->json(array('success' => false,'message' => 'Please Select Employee'),200);
			}
			
			if ($accchartctrlhead->ctrlhead_type_id==2)
			{
				\DB::rollback();
				return response()->json(array('success' => false,'message' => 'You have seleted Report Head,Please Select Chart of Account'),200);
			}

            if(($accchartctrlhead->control_name_id ==1 || $accchartctrlhead->control_name_id ==2 || $accchartctrlhead->control_name_id ==10 || $accchartctrlhead->control_name_id ==15 || $accchartctrlhead->control_name_id == 20 || $accchartctrlhead->control_name_id ==35 || $accchartctrlhead->control_name_id == 62 || $accchartctrlhead->control_name_id ==5 || $accchartctrlhead->control_name_id ==6 || $accchartctrlhead->control_name_id ==30 || $accchartctrlhead->control_name_id ==31 || $accchartctrlhead->control_name_id == 40 || $accchartctrlhead->control_name_id ==45 || $accchartctrlhead->control_name_id ==50 || $accchartctrlhead->control_name_id ==60 || $accchartctrlhead->control_name_id==38) && ($request->party_id[$index]=='' || $request->party_id[$index]==0))
                 {
                     \DB::rollback();
                     return response()->json(array('success' => false,'message' => 'Please Select Party'),200);
                 }

                /* if(($accchartctrlhead->control_name_id ==30 || $accchartctrlhead->control_name_id ==31) &&  $request->amount_debit[$index])
                {

 

                     $bill_no=$this->transchld->where([['bill_no','=', $request->bill_no[$index]]])
                     ->get(['bill_no'])
                     ->first();
                     
                     if($bill_no->bill_no)
                     {
                        \DB::rollback();
                        return response()->json(array('success' => false,'message' => 'Duplicate Invoice Found'),200);
                     }
                }*/
                
                if($accchartctrlhead->statement_type_id ==2 && $companyprofitcenter->profit_center && ! $request->profitcenter_id[$index]){
                \DB::rollback();
                return response()->json(array('success' => false,'message' => 'Please Select Profit Center'),200);            
                }


                $payment_blocked=$this->suppliersetting
                ->where([['supplier_id','=',$request->party_id[$index]]])
                ->where([['payment_blocked_id','=',1]])
                ->get()
                ->first();

                if(($accchartctrlhead->control_name_id ==1 || $accchartctrlhead->control_name_id ==2 || $accchartctrlhead->control_name_id ==10 || $accchartctrlhead->control_name_id ==15 || $accchartctrlhead->control_name_id == 20 || $accchartctrlhead->control_name_id ==35 || $accchartctrlhead->control_name_id == 62) && $payment_blocked && $request->amount_debit[$index])
                 {
                    \DB::rollback();
                    return response()->json(array('success' => false,'message' => 'Selected supplier payment is blocked'),200); 
                 }

            
            try
            {
             $transchld=$this->transchld->create([
             	'acc_trans_prnt_id'=>$transprnt->id,
             	'acc_chart_ctrl_head_id'=>$request->acc_chart_ctrl_head_id[$index],
             	'party_id'=>$request->party_id[$index],
             	'employee_id'=>$request->employee_id[$index],
             	'bill_no'=>$request->bill_no[$index],
             	'amount'=>$amount,
             	'exch_rate'=>$request->exch_rate[$index],
             	'amount_foreign'=>$amount_foreign,
             	'profitcenter_id'=>$request->profitcenter_id[$index],
             	'location_id'=>$request->location_id[$index],
             	'division_id'=>$request->division_id[$index],
             	'department_id'=>$request->department_id[$index],
             	'section_id'=>$request->section_id[$index],
             	'loan_ref_no'=>$request->loan_ref_no[$index],
             	'other_ref_no'=>$request->other_ref_no[$index],
             	'chld_narration'=>$request->chld_narration[$index],
             	'import_lc_ref_no'=>$request->import_lc_ref_no[$index],
             	'export_lc_ref_no'=>$request->export_lc_ref_no[$index]
             ]);

                 
                if($accchartctrlhead->control_name_id ==1 || $accchartctrlhead->control_name_id ==2 || $accchartctrlhead->control_name_id ==10 || $accchartctrlhead->control_name_id ==15 || $accchartctrlhead->control_name_id == 20 || $accchartctrlhead->control_name_id ==35 || $accchartctrlhead->control_name_id == 62)
                 {//purchase
                     $this->accpurchase->create([
                     	'acc_trans_prnt_id'=>$transprnt->id,
                     	'acc_chart_ctrl_head_id'=>$request->acc_chart_ctrl_head_id[$index],
                     	'supplier_id'=>$request->party_id[$index],
                     	'employee_id'=>$request->employee_id[$index],
                     	'bill_no'=>$request->bill_no[$index],
                     	'amount'=>$amount,
                     	'exch_rate'=>$request->exch_rate[$index],
                     	'amount_foreign'=>$amount_foreign,
                     	'profitcenter_id'=>$request->profitcenter_id[$index],
                     	'location_id'=>$request->location_id[$index],
                     	'division_id'=>$request->division_id[$index],
                     	'department_id'=>$request->department_id[$index],
                     	'section_id'=>$request->section_id[$index],
                     	'chld_narration'=>$request->chld_narration[$index]
                     ]);
                 }
                 else if($accchartctrlhead->control_name_id ==5 || $accchartctrlhead->control_name_id ==6 || $accchartctrlhead->control_name_id ==30 || $accchartctrlhead->control_name_id ==31 || $accchartctrlhead->control_name_id == 40 || $accchartctrlhead->control_name_id ==45 || $accchartctrlhead->control_name_id ==50 || $accchartctrlhead->control_name_id ==60)
                 {//sales
                    $this->accsales->create([
                    	'acc_trans_prnt_id'=>$transprnt->id,
                    	'acc_chart_ctrl_head_id'=>$request->acc_chart_ctrl_head_id[$index],
                    	'buyer_id'=>$request->party_id[$index],
                    	'employee_id'=>$request->employee_id[$index],
                    	'bill_no'=>$request->bill_no[$index],
                    	'amount'=>$amount,
                    	'exch_rate'=>$request->exch_rate[$index],
                    	'amount_foreign'=>$amount_foreign,
                    	'profitcenter_id'=>$request->profitcenter_id[$index],
                    	'location_id'=>$request->location_id[$index],
                    	'division_id'=>$request->division_id[$index],
                    	'department_id'=>$request->department_id[$index],
                    	'section_id'=>$request->section_id[$index],
                    	'chld_narration'=>$request->chld_narration[$index]
                    ]);
        
                 }

                

                 else if ($accchartctrlhead->control_name_id==38)
                 {//other Party
                     $this->accotherparty->create([
                     	'acc_trans_prnt_id'=>$transprnt->id,
                     	'acc_chart_ctrl_head_id'=>$request->acc_chart_ctrl_head_id[$index],
                     	'supplier_id'=>$request->party_id[$index],
                     	'employee_id'=>$request->employee_id[$index],
                     	'bill_no'=>$request->bill_no[$index],
                     	'amount'=>$amount,
                     	'exch_rate'=>$request->exch_rate[$index],
                     	'amount_foreign'=>$amount_foreign,
                     	'profitcenter_id'=>$request->profitcenter_id[$index],
                     	'location_id'=>$request->location_id[$index],
                     	'division_id'=>$request->division_id[$index],
                     	'department_id'=>$request->department_id[$index],
                     	'section_id'=>$request->section_id[$index],
                     	'chld_narration'=>$request->chld_narration[$index]
                     ]);
                 }
				 
				 if($request->employee_id[$index])
                 {
                    $this->accemployee->create([
                    	'acc_trans_prnt_id'=>$transprnt->id,
                    	'acc_chart_ctrl_head_id'=>$request->acc_chart_ctrl_head_id[$index],
                    	'employee_id'=>$request->employee_id[$index],
                    	'party_id'=>$request->party_id[$index],
                    	'bill_no'=>$request->bill_no[$index],
                    	'amount'=>$amount,
                    	'exch_rate'=>$request->exch_rate[$index],
                    	'amount_foreign'=>$amount_foreign,
                    	'profitcenter_id'=>$request->profitcenter_id[$index],
                    	'location_id'=>$request->location_id[$index],
                    	'division_id'=>$request->division_id[$index],
                    	'department_id'=>$request->department_id[$index],
                    	'section_id'=>$request->section_id[$index],
                    	'chld_narration'=>$request->chld_narration[$index]
                    ]);
                 }
				 

                 if($request->loan_ref_no[$index])
                 {
                    $this->accloanref->create([
                    	'acc_trans_prnt_id'=>$transprnt->id,
                    	'acc_chart_ctrl_head_id'=>$request->acc_chart_ctrl_head_id[$index],
                    	'loan_ref_no'=>$request->loan_ref_no[$index],
                    	'employee_id'=>$request->employee_id[$index],
                    	'party_id'=>$request->party_id[$index],
                    	'amount'=>$amount,
                    	'exch_rate'=>$request->exch_rate[$index],
                    	'amount_foreign'=>$amount_foreign,
                    	'profitcenter_id'=>$request->profitcenter_id[$index],
                    	'location_id'=>$request->location_id[$index],
                    	'division_id'=>$request->division_id[$index],
                    	'department_id'=>$request->department_id[$index],
                    	'section_id'=>$request->section_id[$index],
                    	'chld_narration'=>$request->chld_narration[$index]
                    ]);
                 }

                 if($request->other_ref_no[$index])
                 {
                    $this->acctransotherref->create([
                    	'acc_trans_prnt_id'=>$transprnt->id,
                    	'acc_chart_ctrl_head_id'=>$request->acc_chart_ctrl_head_id[$index],
                    	'other_ref_no'=>$request->other_ref_no[$index],
                    	'employee_id'=>$request->employee_id[$index],
                    	'party_id'=>$request->party_id[$index],
                    	'amount'=>$amount,
                    	'exch_rate'=>$request->exch_rate[$index],
                    	'amount_foreign'=>$amount_foreign,
                    	'profitcenter_id'=>$request->profitcenter_id[$index],
                    	'location_id'=>$request->location_id[$index],
                    	'division_id'=>$request->division_id[$index],
                    	'department_id'=>$request->department_id[$index],
                    	'section_id'=>$request->section_id[$index],
                    	'chld_narration'=>$request->chld_narration[$index]
                    ]);
                 }


            }
            catch(EXCEPTION $e)
            {
                \DB::rollback();
                throw $e;
            }
        }
        \DB::commit();
        if($transprnt){
	        return response()->json(array('success' => true,'id' =>  $transprnt->id,'trans_no' =>  $trans_no,'message' => 'Save Successfully'),200);
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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AccTransChldRequest $request, $id) {
        $companyprofitcenter=$this->company->find($request->company_id);
        if($request->total_debit != $request->total_credit){
            return response()->json(array('success' => false,'message' => 'Debit & credit not equal'),200);
        }

        $validtransdate=$this->accyear
            ->whereRaw('? between start_date and end_date', [$request->trans_date])
            ->where([['id','=',$request->acc_year_id]])
            ->where([['company_id','=',$request->company_id]])
            ->get(['id'])->count();

        if(! $validtransdate){
            return response()->json(array('success' => false,'message' => 'Please Select a valid transaction Date'),200);
        }

        $accYear=$this->accyear
        ->where([['id', '=', $request->acc_year_id]])
        ->where([['company_id','=',$request->company_id]])
        ->get()->first();

        if(!$accYear->is_open){
            return response()->json(array('success' => false,'message' => 'This year is closed'),200);
        }

        $yearStart=date('Y-m-d',strtotime($accYear->start_date));

        $trans_date=date('Y-m-d',strtotime($request->trans_date));

        if($request->trans_type_id==0 && $yearStart != $trans_date){
            return response()->json(array('success' => false,'message' => 'Please Select a valid transaction Date for opening balance'),200);
        }


        $periodid=0;
        if($request->trans_type_id==0){
            $periodid=$this->period
            ->join('acc_years',function($join){
            $join->on('acc_years.id','=','acc_periods.acc_year_id');
            })
            ->where([['acc_years.company_id','=',$request->company_id]])
            ->whereRaw('? between acc_periods.start_date and acc_periods.end_date', [$request->trans_date])
            ->where([['acc_periods.period','=',0]])
            ->where([['acc_periods.acc_year_id','=',$request->acc_year_id]])
            ->get(['acc_periods.id'])->first();
        }else{
            $periodid=$this->period
            ->join('acc_years',function($join){
            $join->on('acc_years.id','=','acc_periods.acc_year_id');
            })
            ->where([['acc_years.company_id','=',$request->company_id]])
            ->whereRaw('? between acc_periods.start_date and acc_periods.end_date', [$request->trans_date])
            
            ->where([['acc_periods.period','>',0]])
            ->where([['acc_periods.acc_year_id','=',$request->acc_year_id]])
            ->get(['acc_periods.id'])->first();
        }


        $max = $this->transprnt
        ->where([['company_id', $request->company_id]])
        ->where([['acc_year_id', $request->acc_year_id]])
        ->where([['trans_type_id', $request->trans_type_id]])
        ->max('trans_no');
        $trans_no=$max+1;

        \DB::beginTransaction();

        $this->transchld->where([['acc_trans_prnt_id','=',$id]])->delete();
        $this->accpurchase->where([['acc_trans_prnt_id','=',$id]])->delete();
        $this->accsales->where([['acc_trans_prnt_id','=',$id]])->delete();
        $this->accemployee->where([['acc_trans_prnt_id','=',$id]])->delete();
        $this->accotherparty->where([['acc_trans_prnt_id','=',$id]])->delete();
        $this->accloanref->where([['acc_trans_prnt_id','=',$id]])->delete();
        $this->acctransotherref->where([['acc_trans_prnt_id','=',$id]])->delete();

        $transprnt=$this->transprnt->update($id,[
        	'trans_date'=>$request->trans_date,
        	'narration'=>$request->narration,
        	'acc_period_id'=>$periodid->id,
        	'amount'=>$request->total_debit,
        	'instrument_no'=>$request->instrument_no,
        	'pay_to'=>$request->pay_to,
        	'place_date'=>$request->place_date
        ]);
        foreach($request->code as $index=>$code)
        {
            

            $amount=$request->amount_debit[$index];
            if(!$amount){
                $amount=$request->amount_credit[$index]*-1;  
            }

            $amount_foreign=$request->amount_foreign_debit[$index];
            if(!$amount_foreign){
                $amount_foreign=$request->amount_foreign_credit[$index]*-1;  
            }

            if(! $amount){
				\DB::rollback();
                return response()->json(array('success' => false,'message' => 'Debit or credit not found'),200);
            }

            if($request->amount_debit[$index] && $request->amount_credit[$index]){
                \DB::rollback();
                return response()->json(array('success' => false,'message' => 'Both Debit and credit not allowed'),200);
            }
			
			$accchartctrlhead= $this->accchartctrlhead->find($request->acc_chart_ctrl_head_id[$index]);

			if (($accchartctrlhead->control_name_id==12 || $accchartctrlhead->control_name_id==36 || $accchartctrlhead->control_name_id==37) && ($request->employee_id[$index]=='' || $request->employee_id[$index]==0))
			{
				\DB::rollback();
				return response()->json(array('success' => false,'message' => 'Please Select Employee'),200);
			}
			if ($accchartctrlhead->ctrlhead_type_id==2)
			{
				\DB::rollback();
				return response()->json(array('success' => false,'message' => 'You have seleted Report Head,Please Select Chart of Account'),200);
			}

            if(($accchartctrlhead->control_name_id ==1 || $accchartctrlhead->control_name_id ==2 || $accchartctrlhead->control_name_id ==10 || $accchartctrlhead->control_name_id ==15 || $accchartctrlhead->control_name_id == 20 || $accchartctrlhead->control_name_id ==35 || $accchartctrlhead->control_name_id == 62 || $accchartctrlhead->control_name_id ==5 || $accchartctrlhead->control_name_id ==6 || $accchartctrlhead->control_name_id ==30 || $accchartctrlhead->control_name_id ==31 || $accchartctrlhead->control_name_id == 40 || $accchartctrlhead->control_name_id ==45 || $accchartctrlhead->control_name_id ==50 || $accchartctrlhead->control_name_id ==60 || $accchartctrlhead->control_name_id==38) && ($request->party_id[$index]=='' || $request->party_id[$index]==0))
                 {
                     \DB::rollback();
                     return response()->json(array('success' => false,'message' => 'Please Select Party'),200);
                 }

                /*if(($accchartctrlhead->control_name_id ==30 || $accchartctrlhead->control_name_id ==31) &&  $request->amount_debit[$index])
                {

 

                     $bill_no=$this->transchld->where([['bill_no','=', $request->bill_no[$index]]])
                     ->get(['bill_no'])
                     ->first();
                     
                     if($bill_no->bill_no)
                     {
                        \DB::rollback();
                        return response()->json(array('success' => false,'message' => 'Duplicate Invoice Found'),200);
                     }
                }*/

                if($accchartctrlhead->statement_type_id ==2 && $companyprofitcenter->profit_center && ! $request->profitcenter_id[$index]){
                \DB::rollback();
                return response()->json(array('success' => false,'message' => 'Please Select Profit Center'),200);            
                }

                $payment_blocked=$this->suppliersetting
                ->where([['supplier_id','=',$request->party_id[$index]]])
                ->where([['payment_blocked_id','=',1]])
                ->get()
                ->first();

                if(($accchartctrlhead->control_name_id ==1 || $accchartctrlhead->control_name_id ==2 || $accchartctrlhead->control_name_id ==10 || $accchartctrlhead->control_name_id ==15 || $accchartctrlhead->control_name_id == 20 || $accchartctrlhead->control_name_id ==35 || $accchartctrlhead->control_name_id == 62) && $payment_blocked && $request->amount_debit[$index])
                 {
                    \DB::rollback();
                    return response()->json(array('success' => false,'message' => 'Selected supplier payment is blocked'),200); 
                 }


            try
            {
                $transchld=$this->transchld->create([
                	'acc_trans_prnt_id'=>$id,
                	'acc_chart_ctrl_head_id'=>$request->acc_chart_ctrl_head_id[$index],
                	'party_id'=>$request->party_id[$index],
                	'employee_id'=>$request->employee_id[$index],
                	'bill_no'=>$request->bill_no[$index],
                	'amount'=>$amount,
                	'exch_rate'=>$request->exch_rate[$index],
                	'amount_foreign'=>$amount_foreign,
                	'profitcenter_id'=>$request->profitcenter_id[$index],
                	'location_id'=>$request->location_id[$index],
                	'division_id'=>$request->division_id[$index],
                	'department_id'=>$request->department_id[$index],
                	'section_id'=>$request->section_id[$index],
                	'loan_ref_no'=>$request->loan_ref_no[$index],
                	'other_ref_no'=>$request->other_ref_no[$index],
                	'chld_narration'=>$request->chld_narration[$index],
                	'import_lc_ref_no'=>$request->import_lc_ref_no[$index],
                	'export_lc_ref_no'=>$request->export_lc_ref_no[$index]
                ]);

                if($accchartctrlhead->control_name_id ==1 || $accchartctrlhead->control_name_id ==2 || $accchartctrlhead->control_name_id ==10 || $accchartctrlhead->control_name_id ==15 || $accchartctrlhead->control_name_id == 20 || $accchartctrlhead->control_name_id ==35 || $accchartctrlhead->control_name_id == 62)
                {
                    $this->accpurchase->create([
                    	'acc_trans_prnt_id'=>$id,
                    	'acc_chart_ctrl_head_id'=>$request->acc_chart_ctrl_head_id[$index],
                    	'supplier_id'=>$request->party_id[$index],
                    	'employee_id'=>$request->employee_id[$index],
                    	'bill_no'=>$request->bill_no[$index],
                    	'amount'=>$amount,
                    	'exch_rate'=>$request->exch_rate[$index],
                    	'amount_foreign'=>$amount_foreign,
                    	'profitcenter_id'=>$request->profitcenter_id[$index],
                    	'location_id'=>$request->location_id[$index],
                    	'division_id'=>$request->division_id[$index],
                    	'department_id'=>$request->department_id[$index],
                    	'section_id'=>$request->section_id[$index],
                    	'chld_narration'=>$request->chld_narration[$index]
                    ]);
                }
                
                else if($accchartctrlhead->control_name_id ==5 || $accchartctrlhead->control_name_id ==6 || $accchartctrlhead->control_name_id ==30 || $accchartctrlhead->control_name_id ==31 || $accchartctrlhead->control_name_id == 40 || $accchartctrlhead->control_name_id ==45 || $accchartctrlhead->control_name_id ==50 || $accchartctrlhead->control_name_id ==60)////sales
                {
                    $this->accsales->create([
                    	'acc_trans_prnt_id'=>$id,
                    	'acc_chart_ctrl_head_id'=>$request->acc_chart_ctrl_head_id[$index],
                    	'buyer_id'=>$request->party_id[$index],
                    	'employee_id'=>$request->employee_id[$index],
                    	'bill_no'=>$request->bill_no[$index],
                    	'amount'=>$amount,
                    	'exch_rate'=>$request->exch_rate[$index],
                    	'amount_foreign'=>$amount_foreign,
                    	'profitcenter_id'=>$request->profitcenter_id[$index],
                    	'location_id'=>$request->location_id[$index],
                    	'division_id'=>$request->division_id[$index],
                    	'department_id'=>$request->department_id[$index],
                    	'section_id'=>$request->section_id[$index],
                    	'chld_narration'=>$request->chld_narration[$index]
                    ]);
                }
				else if ($accchartctrlhead->control_name_id==38)//other Party
                {
                    $this->accotherparty->create([
                    	'acc_trans_prnt_id'=>$id,
                    	'acc_chart_ctrl_head_id'=>$request->acc_chart_ctrl_head_id[$index],
                    	'supplier_id'=>$request->party_id[$index],
                    	'employee_id'=>$request->employee_id[$index],
                    	'bill_no'=>$request->bill_no[$index],
                    	'amount'=>$amount,
                    	'exch_rate'=>$request->exch_rate[$index],
                    	'amount_foreign'=>$amount_foreign,
                    	'profitcenter_id'=>$request->profitcenter_id[$index],
                    	'location_id'=>$request->location_id[$index],
                    	'division_id'=>$request->division_id[$index],
                    	'department_id'=>$request->department_id[$index],
                    	'section_id'=>$request->section_id[$index],
                    	'chld_narration'=>$request->chld_narration[$index]
                    ]);
                }
				
				if($request->employee_id[$index])
                {
                    $this->accemployee->create([
                    	'acc_trans_prnt_id'=>$id,
                    	'acc_chart_ctrl_head_id'=>$request->acc_chart_ctrl_head_id[$index],
                    	'employee_id'=>$request->employee_id[$index],
                    	'party_id'=>$request->party_id[$index],
                    	'bill_no'=>$request->bill_no[$index],
                    	'amount'=>$amount,
                    	'exch_rate'=>$request->exch_rate[$index],
                    	'amount_foreign'=>$amount_foreign,
                    	'profitcenter_id'=>$request->profitcenter_id[$index],
                    	'location_id'=>$request->location_id[$index],
                    	'division_id'=>$request->division_id[$index],
                    	'department_id'=>$request->department_id[$index],
                    	'section_id'=>$request->section_id[$index],
                    	'chld_narration'=>$request->chld_narration[$index]
                    ]);
                }

                if($request->loan_ref_no[$index])
                {
                    $this->accloanref->create([
                    	'acc_trans_prnt_id'=>$id,
                    	'acc_chart_ctrl_head_id'=>$request->acc_chart_ctrl_head_id[$index],
                    	'loan_ref_no'=>$request->loan_ref_no[$index],
                    	'employee_id'=>$request->employee_id[$index],
                    	'party_id'=>$request->party_id[$index],
                    	'amount'=>$amount,
                    	'exch_rate'=>$request->exch_rate[$index],
                    	'amount_foreign'=>$amount_foreign,
                    	'profitcenter_id'=>$request->profitcenter_id[$index],
                    	'location_id'=>$request->location_id[$index],
                    	'division_id'=>$request->division_id[$index],
                    	'department_id'=>$request->department_id[$index],
                    	'section_id'=>$request->section_id[$index],
                    	'chld_narration'=>$request->chld_narration[$index]
                    ]);
                }
                 if($request->other_ref_no[$index])
                 {
                    $this->acctransotherref->create([
                    	'acc_trans_prnt_id'=>$id,
                    	'acc_chart_ctrl_head_id'=>$request->acc_chart_ctrl_head_id[$index],
                    	'other_ref_no'=>$request->other_ref_no[$index],
                    	'employee_id'=>$request->employee_id[$index],
                    	'party_id'=>$request->party_id[$index],
                    	'amount'=>$amount,
                    	'exch_rate'=>$request->exch_rate[$index],
                    	'amount_foreign'=>$amount_foreign,
                    	'profitcenter_id'=>$request->profitcenter_id[$index],
                    	'location_id'=>$request->location_id[$index],
                    	'division_id'=>$request->division_id[$index],
                    	'department_id'=>$request->department_id[$index],
                    	'section_id'=>$request->section_id[$index],
                    	'chld_narration'=>$request->chld_narration[$index]
                    ]);
                 }
            }
            catch(EXCEPTION $e)
            {
                \DB::rollback();
                throw $e;
            }
        }

        \DB::commit();

        if($transprnt){
        return response()->json(array('success' => true,'id' =>  $id,'fucus'=>'code','trans_no' =>  $trans_no,'message' => 'Save Successfully'),200);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
         $transprnt=$this->transprnt->find();
         $accYear=$this->accyear
        ->where([['id', '=', $transprnt->acc_year_id]])
        ->where([['company_id','=',$transprnt->company_id]])
        ->get()->first();
        
        if(!$accYear->is_open){
            return response()->json(array('success' => false,'message' => 'This year is closed'),200);
        }

        \DB::beginTransaction();
        try
        {
            $this->transchld->where([['acc_trans_prnt_id','=',$id]])->delete();
            $this->accpurchase->where([['acc_trans_prnt_id','=',$id]])->delete();
            $this->accsales->where([['acc_trans_prnt_id','=',$id]])->delete();
            $this->accemployee->where([['acc_trans_prnt_id','=',$id]])->delete();
            $this->accotherparty->where([['acc_trans_prnt_id','=',$id]])->delete();
            $this->accloanref->where([['acc_trans_prnt_id','=',$id]])->delete();
            $this->acctransotherref->where([['acc_trans_prnt_id','=',$id]])->delete();
            $this->transprnt->where([['id','=',$id]])->delete();
        }
        catch(EXCEPTION $e)
        {
            \DB::rollback();
            throw $e;
        }
        \DB::commit();
        return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        
    }

    public function getbillno(Request $request) {
        return $this->transchld->where([['bill_no', 'LIKE', '%'.$request->q.'%']])->orderBy('bill_no','asc')->get(['bill_no as name']);
    }

    public function getimportlc(Request $request) {
        return $this->transchld->where([['import_lc_ref_no', 'LIKE', '%'.$request->q.'%']])->orderBy('import_lc_ref_no','asc')->get(['import_lc_ref_no as name']);
    }
    public function getexportlc(Request $request) {
        return $this->transchld->where([['export_lc_ref_no', 'LIKE', '%'.$request->q.'%']])->orderBy('export_lc_ref_no','asc')->get(['export_lc_ref_no as name']);
    }
    public function getloanrefno(Request $request) {
        return $this->transchld->where([['loan_ref_no', 'LIKE', '%'.$request->q.'%']])->orderBy('loan_ref_no','asc')->get(['loan_ref_no as name']);
    }
    public function getotherrefno(Request $request) {
        return $this->transchld->where([['other_ref_no', 'LIKE', '%'.$request->q.'%']])->orderBy('other_ref_no','asc')->get(['other_ref_no as name']);
    }

}
