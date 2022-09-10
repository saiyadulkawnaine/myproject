<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccTransPrntRepository;
use App\Repositories\Contracts\Account\AccTransChldRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\CompanyUserRepository;
use App\Repositories\Contracts\Account\AccYearRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\Util\DivisionRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\SectionRepository;
use App\Repositories\Contracts\Util\ProfitcenterRepository;
use App\Repositories\Contracts\Util\BankRepository;

use App\Repositories\Contracts\HRM\EmployeeRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Library\Template;
use App\Library\Numbertowords;
use App\Http\Requests\Account\AccTransPrntRequest;

class AccTransPrntController extends Controller {

    private $transprnt;
	private $company;
	private $accyear;
	private $region;
	private $division;
	private $department;
	private $section;
	private $profitcenter;
    private $transchld;
    private $companyuser;
    private $bank;
    private $buyer;
    private $supplier;
    private $employee;

    public function __construct(AccTransPrntRepository $transprnt,CompanyRepository $company,AccYearRepository $accyear,LocationRepository $location,DivisionRepository $division,DepartmentRepository $department,SectionRepository $section,ProfitcenterRepository $profitcenter,AccTransChldRepository $transchld,CompanyUserRepository $companyuser,BankRepository $bank,BuyerRepository $buyer,SupplierRepository $supplier,EmployeeRepository $employee) {
        $this->transprnt = $transprnt;
        $this->company = $company;
        $this->accyear = $accyear;
        $this->location = $location;
        $this->division = $division;
        $this->department = $department;
        $this->section = $section;
        $this->profitcenter = $profitcenter;
        $this->transchld = $transchld;
        $this->companyuser = $companyuser;
        $this->bank = $bank;
        $this->buyer = $buyer;
        $this->supplier = $supplier;
        $this->employee = $employee;


        $this->middleware('auth');
        $this->middleware('permission:view.acctransprnts',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.acctransprnts', ['only' => ['store']]);
        $this->middleware('permission:edit.acctransprnts',   ['only' => ['update']]);
        $this->middleware('permission:delete.acctransprnts', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $bank=array_prepend(array_pluck($this->bank->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $accyear=array_prepend(array_pluck($this->accyear->get(),'name','id'),'-Select-','');
        $journalType=config('bprs.journalType');
        $transprnts = array();
        $rows = $this->transprnt
        ->leftJoin('users',function($join){
        $join->on('users.id','=','acc_trans_prnts.created_by');
        })
        ->leftJoin('users as updatedbys',function($join){
        $join->on('updatedbys.id','=','acc_trans_prnts.updated_by');
        })
        ->when(request('company_id'), function ($q) {
        return $q->where('acc_trans_prnts.company_id', '=', request('company_id', 0));
        })
        ->when(request('acc_year_id'), function ($q) {
        return $q->where('acc_trans_prnts.acc_year_id', '=', request('acc_year_id', 0));
        })
        ->when(request('trans_type_id'), function ($q) {
        return $q->where('acc_trans_prnts.trans_type_id', '=', request('trans_type_id', 0));
        })
        ->when(request('trans_no'), function ($q) {
        return $q->where('acc_trans_prnts.trans_no', '=', request('trans_no', 0));
        })
        ->when(request('trans_date'), function ($q) {
        return $q->where('acc_trans_prnts.trans_date', '=',request('trans_date', 0));
        })
        ->orderBy('acc_trans_prnts.trans_type_id','asc')
        ->orderBy('acc_trans_prnts.trans_no','asc')
        ->get([
        'acc_trans_prnts.*',
        'users.name',
        'updatedbys.name as updated_by',

    ]);
	  foreach($rows as $row){
      $transprnt['id']=$row->id;
      $transprnt['company_id']=$company[$row->company_id] ;
      $transprnt['user_name']=$row->name;
      $transprnt['updated_by']=$row->updated_by;
      $transprnt['trans_type']=$row->trans_type_id ;
      $transprnt['trans_type_id']=$journalType[$row->trans_type_id] ;
      $transprnt['trans_no']=$row->trans_no ;
      $transprnt['trans_date']=date('Y-m-d',strtotime($row->trans_date)) ;
      $transprnt['acc_year_name']=$accyear[$row->acc_year_id] ;
      $transprnt['acc_year_id']=$row->acc_year_id ;
      $transprnt['is_locked']=$row->is_locked ;
      $transprnt['amount']=$row->amount;
      $transprnt['bank_id']=$bank[$row->bank_id] ;
      $transprnt['instrument_no']=$row->instrument_no ;
      $transprnt['pay_to']=$row->pay_to ;
      $transprnt['place_date']=date('Y-m-d',strtotime($row->place_date)) ;
      $transprnt['created_at']=date('d-M-Y h:i:s',strtotime($row->created_at)) ;
      $transprnt['updated_at']=date('d-M-Y h:i:s',strtotime($row->updated_at)) ;
      $transprnt['narration']=$row->narration ;
      array_push($transprnts, $transprnt);
      }
      echo json_encode($transprnts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $journalType=array_prepend(config('bprs.journalType'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        //$company=array_prepend(array_pluck($this->companyuser->getCompany(),'name','id'),'-Select-','');
        //$this->companyuser
        $accyear=array_prepend(array_pluck($this->accyear->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $division=array_prepend(array_pluck($this->division->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $section=array_prepend(array_pluck($this->section->get(),'name','id'),'-Select-','');
        $profitcenter=array_prepend(array_pluck($this->profitcenter->get(),'name','id'),'-Select-','');
        $bank=array_prepend(array_pluck($this->bank->get(),'name','id'),'-Select-','');
        return Template::loadView('Account.AccTransPrnt',['journalType'=>$journalType,'company'=>$company,'accyear'=>$accyear,'location'=>$location,'division'=>$division,'department'=>$department,'section'=>$section,'profitcenter'=>$profitcenter,'bank'=>$bank]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccTransPrntRequest $request) {
		$transprnt=$this->transprnt->create($request->except(['id']));
		if($transprnt){
			return response()->json(array('success' => true,'id' =>  $transprnt->id,'message' => 'Save Successfully'),200);
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

        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $division=array_prepend(array_pluck($this->division->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $section=array_prepend(array_pluck($this->section->get(),'name','id'),'-Select-','');
        $profitcenter=array_prepend(array_pluck($this->profitcenter->get(),'name','id'),'-Select-','');

       $transprnt = $this->transprnt->find($id);
	   $row ['fromData'] = $transprnt;
	   $dropdown['att'] = '';
	   $row ['dropDown'] = $dropdown;

       $transchld= $this->transchld
       ->leftJoin('acc_chart_ctrl_heads',function($join){
        $join->on('acc_chart_ctrl_heads.id','=','acc_trans_chlds.acc_chart_ctrl_head_id');
        })
       ->leftJoin('locations',function($join){
        $join->on('locations.id','=','acc_trans_chlds.location_id');
        })
       ->leftJoin('divisions',function($join){
        $join->on('divisions.id','=','acc_trans_chlds.division_id');
        })
       ->leftJoin('departments',function($join){
        $join->on('departments.id','=','acc_trans_chlds.department_id');
        })
       ->leftJoin('sections',function($join){
        $join->on('sections.id','=','acc_trans_chlds.section_id');
        })
       ->where([['acc_trans_prnt_id','=',$id]])
       ->get([
        'acc_trans_chlds.*',
        'acc_chart_ctrl_heads.name as acc_chart_ctrl_head_name',
        'acc_chart_ctrl_heads.code as code',
        'locations.name as location_name',
        'divisions.name  as division_name',
        'departments.name  as department_name',
        'sections.name  as section_name',
       ])
        ->map(function ($transchld) {
            if($transchld->amount < 0 ){
                $transchld->amount_credit =$transchld->amount*-1;

            }
            else
            {
               $transchld->amount_debit =$transchld->amount;
            }

            if($transchld->amount_foreign < 0 ){
                $transchld->amount_foreign_credit =$transchld->amount_foreign*-1;

            }
            else
            {
               $transchld->amount_foreign_debit =$transchld->amount_foreign;
            }
            return $transchld;
        
        });
        

       $row ['transchld'] = $transchld;
       $row ['accyear'] = $accyear=$this->accyear->getBycompany($transprnt->company_id);

       echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AccTransPrntRequest $request, $id) {
        $transprnt=$this->transprnt->update($id,$request->except(['id']));
		if($transprnt){
			return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->transprnt->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
        else{
             return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
        }
        
    }


    public function journalpdf () {

            $id=request('id',0);
            $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
            $accyear=array_prepend(array_pluck($this->accyear->get(),'name','id'),'-Select-','');
            $journalType=config('bprs.journalType');
            $transprnts = array();
            $rows = $this->transprnt
            ->join('companies',function($join){
                $join->on('companies.id','=','acc_trans_prnts.company_id');
            })
            ->join('acc_years',function($join){
                $join->on('acc_years.id','=','acc_trans_prnts.acc_year_id');
            })
            ->join('users',function($join){
                $join->on('users.id','=','acc_trans_prnts.created_by');
            })
            ->join('users as editusers',function($join){
                $join->on('editusers.id','=','acc_trans_prnts.updated_by');
            })
            ->where([['acc_trans_prnts.id','=',$id]])
            ->get([
                'acc_trans_prnts.*',
                'companies.name as company_name',
                'companies.logo',
                'companies.address',
                'acc_years.name as year_name',
                'users.name as created_by',
                'editusers.name as updated_by'
            ]);
            foreach($rows as $row)
            {
            $transprnt['id']=$row->id;
            $transprnt['company_id']=$row->company_id ;
            $transprnt['company_name']=$row->company_name ;
            $transprnt['logo']=$row->logo ;
            $transprnt['address']=$row->address ;
            $transprnt['trans_type_id']=$row->trans_type_id ;
            $transprnt['trans_type']=$journalType[$row->trans_type_id] ;
            $transprnt['trans_no']=$row->trans_no ;
            $transprnt['trans_date']=date('d-M-Y',strtotime($row->trans_date)) ;
            $transprnt['acc_year_id']=$row->acc_year_id ;
            $transprnt['year_name']=$row->year_name ;
            $transprnt['is_locked']=$row->is_locked ;
            $transprnt['narration']=$row->narration ;
            $transprnt['instrument_no']=$row->instrument_no ;
            $created_at=strtotime($row->created_at);
            $updated_at=strtotime($row->updated_at);
            $transprnt['created_by']=$row->created_by ;
            $transprnt['created_at']=date('d-M-Y',strtotime($row->created_at)) ;
                if($created_at==$updated_at){
                    $transprnt['updated_by']='' ;
                    $transprnt['updated_at']='' ;
                }
                else
                {
                    $transprnt['updated_by']=$row->updated_by ;
                    $transprnt['updated_at']=date('d-M-Y',strtotime($row->updated_at)) ;
                }
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
        //$pdf->SetY(10);
        //$txt = $transprnt['company_name'];
        //$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
        //$pdf->SetY(5);
        //$pdf->Text(90, 5, $txt);
        //$image_file = url('/').'/images/logo/'.$transprnt['logo'];
        $image_file ='images/logo/'.$transprnt['logo'];
        $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);

        $pdf->SetY(10);
        $pdf->SetFont('helvetica', 'N', 10);
        $pdf->Cell(0, 40, $transprnt['address'], 0, false, 'C', 0, '', 0, false, 'T', 'M' );
        //$pdf->Text(60, 12, $transprnt['address']);



        $pdf->SetFont('helvetica', '', 8);
        //$pdf->SetTitle('Price Offer');
           
            
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'',''); //$this->buyer->get();
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'','');//$this->supplier->get();
        $otherPartise=array_prepend(array_pluck($this->supplier->otherPartise(),'name','id'),'-Select-','');//$this->supplier->otherPartise();
        $employee=array_prepend(array_pluck($this->employee->get(),'name','id'),'','');//$this->employee->get();

        $transchld= $this->transchld
           ->leftJoin('acc_chart_ctrl_heads',function($join){
            $join->on('acc_chart_ctrl_heads.id','=','acc_trans_chlds.acc_chart_ctrl_head_id');
            })
           ->leftJoin('locations',function($join){
            $join->on('locations.id','=','acc_trans_chlds.location_id');
            })
           ->leftJoin('profitcenters',function($join){
            $join->on('profitcenters.id','=','acc_trans_chlds.profitcenter_id');
            })
           ->leftJoin('divisions',function($join){
            $join->on('divisions.id','=','acc_trans_chlds.division_id');
            })
           ->leftJoin('departments',function($join){
            $join->on('departments.id','=','acc_trans_chlds.department_id');
            })
           ->leftJoin('sections',function($join){
            $join->on('sections.id','=','acc_trans_chlds.section_id');
            })
           ->leftJoin('employees',function($join){
                $join->on('employees.id','=','acc_trans_chlds.employee_id');
            })
           ->where([['acc_trans_prnt_id','=',$id]])
    	   ->orderBy('acc_trans_chlds.id','asc')
           ->get([
            'acc_trans_chlds.*',
            'acc_chart_ctrl_heads.name as acc_chart_ctrl_head_name',
            'acc_chart_ctrl_heads.code as code',
            "acc_chart_ctrl_heads.control_name_id",
            'locations.name as location_name',
            'divisions.name  as division_name',
            'departments.name  as department_name',
            'sections.name  as section_name',
            'profitcenters.name as profitcenter_name',
            'employees.name'
           ])
            ->map(function ($transchld) use($supplier,$buyer,$otherPartise){
                if($transchld->amount < 0 ){
                    $transchld->amount_credit =$transchld->amount*-1;

                }
                else
                {
                   $transchld->amount_debit =$transchld->amount;
                }

                if($transchld->amount_foreign < 0 ){
                    $transchld->amount_foreign_credit =$transchld->amount_foreign*-1;

                }
                else
                {
                   $transchld->amount_foreign_debit =$transchld->amount_foreign;
                }

                //==============
            $transchld->party_name='';
            if($transchld->control_name_id ==1 || $transchld->control_name_id ==2 || $transchld->control_name_id ==10 || $transchld->control_name_id ==15 || $transchld->control_name_id == 20 || $transchld->control_name_id ==35 || $transchld->control_name_id == 62)
            {//purchase
                $transchld->party_name =isset($supplier[$transchld->party_id])?$supplier[$transchld->party_id]:'';
            }

            else if($transchld->control_name_id ==5 || $transchld->control_name_id ==6 || $transchld->control_name_id ==30 || $transchld->control_name_id ==31 || $transchld->control_name_id == 40 || $transchld->control_name_id ==45 || $transchld->control_name_id ==50 || $transchld->control_name_id ==60)
            {//sales
                        
                $transchld->party_name =isset($buyer[$transchld->party_id])?$buyer[$transchld->party_id]:'';

            }

             else if ($transchld->control_name_id==38)
             {//other Party
                $transchld->party_name =isset($otherPartise[$transchld->party_id])?$otherPartise[$transchld->party_id]:'';
             }

             $partyarr=array();
             if($transchld->party_name)
             {
                array_push($partyarr,$transchld->party_name);

             }
             if($transchld->name)
             {
                array_push($partyarr,$transchld->name);

             }

             $transchld->emp_party_name=implode(',',$partyarr);


            //===============

                return $transchld;
            
            });

            
            $transprnt['total_amount_credit']=number_format($transchld->sum('amount_credit'),2,'.',',');
            $transprnt['total_amount_debit']=number_format($transchld->sum('amount_debit'),2,'.',',');
            $total_amount_credit=number_format($transchld->sum('amount_credit'),2,'.','');
            $inword=Numbertowords::ntow(number_format($total_amount_credit,2,'.',''),'Taka','paisa');
            $transprnt['inword']= $inword;
            

          $transprnt['transchld']=$transchld;


        $view= \View::make('Defult.Account.JournalPdf',['transprnt'=>$transprnt]);
        $html_content=$view->render();
        $pdf->SetY(32);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/JournalPdf.pdf';
        $pdf->output($filename);
        //echo $html_content;
        //$pdf->output($filename,'I');
        exit();
        //$pdf->output($filename,'F');
        //return response()->download($filename);
    }

    public function mrpdf () {

            $id=request('id',0);

            $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
            $accyear=array_prepend(array_pluck($this->accyear->get(),'name','id'),'-Select-','');
            $journalType=config('bprs.journalType');
            $transprnts = array();
            $rows = $this->transprnt
            ->join('companies',function($join){
                $join->on('companies.id','=','acc_trans_prnts.company_id');
            })
            ->join('acc_years',function($join){
                $join->on('acc_years.id','=','acc_trans_prnts.acc_year_id');
            })
            ->join('users',function($join){
                $join->on('users.id','=','acc_trans_prnts.created_by');
            })
            ->where([['acc_trans_prnts.id','=',$id]])
            ->get([
                'acc_trans_prnts.*',
                'companies.name as company_name',
                'companies.logo',
                'companies.address',
                'acc_years.name as year_name',
                'users.name as created_by'
            ]);
            foreach($rows as $row)
            {
            $transprnt['id']=$row->id;
            $transprnt['company_id']=$row->company_id ;
            $transprnt['company_name']=$row->company_name ;
            $transprnt['address']=$row->address ;
            $transprnt['logo']=$row->logo ;
            $transprnt['trans_type_id']=$row->trans_type_id ;
            $transprnt['trans_type']=$journalType[$row->trans_type_id] ;
            $transprnt['trans_no']=$row->trans_no ;
            $transprnt['trans_date']=date('Y-m-d',strtotime($row->trans_date)) ;
            $transprnt['acc_year_id']=$row->acc_year_id ;
            $transprnt['year_name']=$row->year_name ;
            $transprnt['is_locked']=$row->is_locked ;
            $transprnt['narration']=$row->narration ;
            $transprnt['instrument_no']=$row->instrument_no ;

            if($row->trans_type_id==1)
            {
              $transprnt['by']='cash';
            }
             if($row->trans_type_id==2)
            {
              $transprnt['by']='cheque';
            }
            $transprnt['amount']=$row->amount ;
            $transprnt['place_date']=date('d-M-Y',strtotime($row->place_date)) ;
            $transprnt['created_by']=$row->created_by ;
            $transprnt['created_at']=date('d-M-Y',strtotime($row->created_at)) ;
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
        $pdf->SetY(10);
        $txt = $transprnt['company_name'];
        //$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
        //$pdf->SetY(5);
        //$pdf->Text(90, 5, $txt);
        //$image_file = url('/').'/images/logo/'.$transprnt['logo'];
        $image_file ='images/logo/'.$transprnt['logo'];
        $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $pdf->SetY(100);
        $pdf->SetFont('helvetica', 'N', 10);
        $pdf->Text(60, 12, $transprnt['address']);



        $pdf->SetFont('helvetica', '', 8);
        //$pdf->SetTitle('Price Offer');
           
            
        $buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'',''); //$this->buyer->get();
        $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'','');//$this->supplier->get();
        $otherPartise=array_prepend(array_pluck($this->supplier->otherPartise(),'name','id'),'-Select-','');//$this->supplier->otherPartise();
        $employee=array_prepend(array_pluck($this->employee->get(),'name','id'),'','');//$this->employee->get();

        $transchld= $this->transchld
           ->leftJoin('acc_chart_ctrl_heads',function($join){
            $join->on('acc_chart_ctrl_heads.id','=','acc_trans_chlds.acc_chart_ctrl_head_id');
            })
           ->leftJoin('locations',function($join){
            $join->on('locations.id','=','acc_trans_chlds.location_id');
            })
           ->leftJoin('profitcenters',function($join){
            $join->on('profitcenters.id','=','acc_trans_chlds.profitcenter_id');
            })
           ->leftJoin('divisions',function($join){
            $join->on('divisions.id','=','acc_trans_chlds.division_id');
            })
           ->leftJoin('departments',function($join){
            $join->on('departments.id','=','acc_trans_chlds.department_id');
            })
           ->leftJoin('sections',function($join){
            $join->on('sections.id','=','acc_trans_chlds.section_id');
            })
           ->leftJoin('employees',function($join){
                $join->on('employees.id','=','acc_trans_chlds.employee_id');
            })
           ->where([['acc_trans_prnt_id','=',$id]])
           ->orderBy('acc_trans_chlds.id','asc')
           ->get([
            'acc_trans_chlds.*',
            'acc_chart_ctrl_heads.name as acc_chart_ctrl_head_name',
            'acc_chart_ctrl_heads.code as code',
            "acc_chart_ctrl_heads.control_name_id",
            'locations.name as location_name',
            'divisions.name  as division_name',
            'departments.name  as department_name',
            'sections.name  as section_name',
            'profitcenters.name as profitcenter_name',
            'employees.name'
           ]);
           $partyArr=array();
           foreach($transchld as $row)
           {
             $partyArr[$row->party_id]=isset($buyer[$row->party_id])?$buyer[$row->party_id]:'';
            //$transchld->party_name =isset($buyer[$transchld->party_id])?$buyer[$transchld->party_id]:'';

           }

           $transprnt['receiverom']= implode(',',$partyArr);


            /*->map(function ($transchld) use($supplier,$buyer,$otherPartise){
                if($transchld->amount < 0 ){
                    $transchld->amount_credit =$transchld->amount*-1;

                }
                else
                {
                   $transchld->amount_debit =$transchld->amount;
                }

                if($transchld->amount_foreign < 0 ){
                    $transchld->amount_foreign_credit =$transchld->amount_foreign*-1;

                }
                else
                {
                   $transchld->amount_foreign_debit =$transchld->amount_foreign;
                }

                //==============
            $transchld->party_name='';
            if($transchld->control_name_id ==1 || $transchld->control_name_id ==2 || $transchld->control_name_id ==10 || $transchld->control_name_id ==15 || $transchld->control_name_id == 20 || $transchld->control_name_id ==35 || $transchld->control_name_id == 62)
            {//purchase
                $transchld->party_name =isset($supplier[$transchld->party_id])?$supplier[$transchld->party_id]:'';
            }

            else if($transchld->control_name_id ==5 || $transchld->control_name_id ==6 || $transchld->control_name_id ==30 || $transchld->control_name_id ==31 || $transchld->control_name_id == 40 || $transchld->control_name_id ==45 || $transchld->control_name_id ==50 || $transchld->control_name_id ==60)
            {//sales
                        
                $transchld->party_name =isset($buyer[$transchld->party_id])?$buyer[$transchld->party_id]:'';

            }

             else if ($transchld->control_name_id==38)
             {//other Party
                $transchld->party_name =isset($otherPartise[$transchld->party_id])?$otherPartise[$transchld->party_id]:'';
             }

             $partyarr=array();
             if($transchld->party_name)
             {
                array_push($partyarr,$transchld->party_name);

             }
             if($transchld->name)
             {
                array_push($partyarr,$transchld->name);

             }

             $transchld->emp_party_name=implode(',',$partyarr);


            //===============

                return $transchld;
            
            });*/

            
            //$transprnt['total_amount_credit']=number_format($transchld->sum('amount_credit'),2,'.',',');
            //$transprnt['total_amount_debit']=number_format($transchld->sum('amount_debit'),2,'.',',');
            //$total_amount_credit=number_format($transchld->sum('amount_credit'),2,'.','');
            $inword=Numbertowords::ntow(number_format($transprnt['amount'],2,'.',''),'Taka','paisa');
            $transprnt['inword']= $inword;
            

          $transprnt['transchld']=$transchld;


        $view= \View::make('Defult.Account.MrPdf',['transprnt'=>$transprnt]);
        $html_content=$view->render();
        $pdf->SetY(18);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/MrPdf.pdf';
        $pdf->output($filename);
        //echo $html_content;
        //$pdf->output($filename,'I');
        exit();
        //$pdf->output($filename,'F');
        //return response()->download($filename);
    }


    public function cqpdf () {

            $id=request('id',0);
            //$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
            //$accyear=array_prepend(array_pluck($this->accyear->get(),'name','id'),'-Select-','');
            //$journalType=config('bprs.journalType');
            $transprnts = array();
            $rows = $this->transprnt
            ->where([['acc_trans_prnts.id','=',$id]])
            ->get([
                'acc_trans_prnts.*'
            ]);
            foreach($rows as $row)
            {
            $transprnt['pay_to']=$row->pay_to ;
            $transprnt['amount']=$row->amount ;
            $transprnt['place_date']=date('d-M-Y',strtotime($row->place_date)) ;
            $transprnt['place_day']=date('d',strtotime($row->place_date)) ;
            $transprnt['place_month']=date('m',strtotime($row->place_date)) ;
            $transprnt['place_year']=date('Y',strtotime($row->place_date)) ;
            }
            $place_day=$transprnt['place_day'];
            $place_month=$transprnt['place_month'];
            $place_year=$transprnt['place_year'];
            $inword=Numbertowords::ntow(number_format($transprnt['amount'],2,'.',''),'taka','paisa');

            $pdf = new \TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(false);
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(0, 0, 0);
            $pdf->SetHeaderMargin(false);
            $pdf->SetFooterMargin(false);
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->SetFont('helvetica', 'R', 12);
            $pdf->AddPage('L',array('Rotate'=>90));
            $pdf->SetFillColor(255, 255, 255);

            $pdf->SetY(74);
            $pdf->SetX(145);
            $pdf->MultiCell(55, 5, $place_day[0], 0, 'L', 1, 0, '', '', true);

            $pdf->SetY(74);
            $pdf->SetX(151);
            $pdf->MultiCell(55, 5, $place_day[1], 0, 'L', 1, 0, '', '', true);

            $pdf->SetY(74);
            $pdf->SetX(158);
            $pdf->MultiCell(55, 5, $place_month[0], 0, 'L', 1, 0, '', '', true);

            $pdf->SetY(74);
            $pdf->SetX(164);
            $pdf->MultiCell(55, 5, $place_month[1], 0, 'L', 1, 0, '', '', true);

            $pdf->SetY(74);
            $pdf->SetX(173);
            $pdf->MultiCell(55, 5, $place_year[0], 0, 'L', 1, 0, '', '', true);

            $pdf->SetY(74);
            $pdf->SetX(179);
            $pdf->MultiCell(55, 5, $place_year[1], 0, 'L', 1, 0, '', '', true);

            $pdf->SetY(74);
            $pdf->SetX(185);
            $pdf->MultiCell(55, 5, $place_year[2], 0, 'L', 1, 0, '', '', true);

            $pdf->SetY(74);
            $pdf->SetX(191);
            $pdf->MultiCell(55, 5, $place_year[3], 0, 'L', 1, 0, '', '', true);


            $pdf->SetY(90);
            $pdf->SetX(22);
            $pdf->MultiCell(110, 5, $transprnt['pay_to'], 0, 'L', 1, 0, '', '', true);

            $pdf->SetY(99);
            $pdf->SetX(37);
            $pdf->MultiCell(90, 5, $inword.' only', 0, 'L', 1, 0, '', '', true);
            $pdf->SetY(103);
            $pdf->SetX(145);
            $pdf->MultiCell(55, 5, ' ='.number_format($transprnt['amount'],2,'.',','), 0, 'L', 1, 0, '', '', true);
            $pdf->SetFont('helvetica', '', 8);
            $filename = storage_path() . '/Cheque.pdf';
            $pdf->output($filename);
            exit();
    }

}
