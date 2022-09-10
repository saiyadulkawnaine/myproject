<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\HRM\EmployeeMovementRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Library\Template;
use App\Library\Sms;
use App\Library\Numbertowords;
use App\Http\Requests\HRM\EmployeeMovementRequest;

class EmployeeMovementController extends Controller {

    private $employeemovement;
    private $employeehr;
    private $designation;
    private $department;
    private $location;
    private $user;

    public function __construct(
        EmployeeHRRepository $employeehr, 
        EmployeeMovementRepository $employeemovement,
        DesignationRepository $designation, 
        DepartmentRepository $department,
        CompanyRepository $company, 
        UserRepository $user, 
        LocationRepository $location
    ) {
        $this->employeehr = $employeehr;
        $this->employeemovement = $employeemovement;
        $this->user = $user;
        $this->designation = $designation;
        $this->department = $department;
        $this->company = $company;
        $this->location = $location;

        $this->middleware('auth');
        // $this->middleware('permission:view.employeemovements',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.employeemovements', ['only' => ['store']]);
        // $this->middleware('permission:edit.employeemovements',   ['only' => ['update']]);
        // $this->middleware('permission:delete.employeemovements', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
       $user = \Auth::user();
        $rows=$this->employeemovement
        ->join('employee_h_rs',function($join){
            $join->on('employee_movements.employee_h_r_id','=','employee_h_rs.id');
        })
        ->leftJoin('users',function($join){
            $join->on('users.id','=','employee_h_rs.user_id');
        })
        ->where([['employee_movements.created_by','=',$user->id]])
        ->orderBy('employee_movements.id','desc')
        ->get([
            'employee_movements.*',
            'employee_h_rs.id as employee_h_r_id',
            'employee_h_rs.company_id',
            'employee_h_rs.location_id',
            'employee_h_rs.department_id',
            'employee_h_rs.designation_id',
            'employee_h_rs.code',
            'employee_h_rs.contact',
            'employee_h_rs.name',
        ])
        ->map(function($rows) use($designation,$department,$company,$location){
            $rows->post_date=date('d-M-Y',strtotime($rows->post_date));
            $rows->company_id=isset($company[$rows->company_id])?$company[$rows->company_id]:'';
            $rows->designation_id=isset($designation[$rows->designation_id])?$designation[$rows->designation_id]:'';
            $rows->department_id=isset($department[$rows->department_id])?$department[$rows->department_id]:'';
            $rows->location_id=isset($location[$rows->location_id])?$location[$rows->location_id]:'';
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
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');
        $user=array_prepend(array_pluck($this->user->get(),'name','id'),'','');
        $purpose = array_prepend(config('bprs.purpose'),'-Select-','');
        $transportmode = array_prepend(config('bprs.transportmode'),'-Select-','');
        //$user_emp = \Auth::user();
        // $userData=$this->user
        // ->leftJoin('employee_h_rs',function($join){
        //     $join->on('users.id','=','employee_h_rs.user_id');
        // })
        // ->leftJoin('companies',function($join){
        //     $join->on('companies.id','=','employee_h_rs.company_id');
        // })
        // ->leftJoin('departments',function($join){
        //     $join->on('departments.id','=','employee_h_rs.department_id');
        // })
        // ->leftJoin('designations',function($join){
        //     $join->on('designations.id','=','employee_h_rs.designation_id');
        // })
        // ->leftJoin('locations',function($join){
        //     $join->on('locations.id','=','employee_h_rs.location_id');
        // })
        // ->where([['users.id','=',$user_emp->id]])
        // ->get([
	    //     'users.name as user_name',
	    //     'users.id as user_id',
	    //     'employee_h_rs.id as employee_h_r_id',
	    //     'employee_h_rs.code',
	    //     'employee_h_rs.contact',
	    //     'employee_h_rs.name as employee_name',
	    //     'companies.name as company_id',
	    //     'departments.name as department_id',
	    //     'designations.name as designation_id',
	    //     'locations.name as location_id',
        // ])
        // ->first();
		return Template::loadView('HRM.EmployeeMovement', ['user'=>$user,/* 'userData'=>$userData, */'designation'=>$designation,'department'=>$department,'company'=>$company,'location'=>$location,'purpose'=>$purpose,'transportmode'=>$transportmode]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeMovementRequest $request) {
		$employeemovement=$this->employeemovement->create([
            'employee_h_r_id'=>$request->employee_h_r_id,
            'post_date'=>$request->post_date,
            
        ]);
		if($employeemovement){
			return response()->json(array('success' => true,'id' =>  $employeemovement->id,'message' => 'Save Successfully'),200);
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

        $employeemovement = $this->employeemovement
        ->leftJoin('employee_h_rs',function($join){
            $join->on('employee_movements.employee_h_r_id','=','employee_h_rs.id');
        })
        ->leftJoin('users',function($join){
            $join->on('users.id','=','employee_h_rs.user_id');
        })
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','employee_h_rs.company_id');
        })
        ->leftJoin('departments',function($join){
            $join->on('departments.id','=','employee_h_rs.department_id');
        })
        ->leftJoin('designations',function($join){
            $join->on('designations.id','=','employee_h_rs.designation_id');
        })
        ->leftJoin('locations',function($join){
            $join->on('locations.id','=','employee_h_rs.location_id');
        })
        ->where([['employee_movements.id','=',$id]])
        ->get([
            'employee_movements.*',
            'employee_h_rs.id as employee_h_r_id',
            'employee_h_rs.code',
            'employee_h_rs.contact',
            'employee_h_rs.name as employee_name',
            'companies.name as company_id',
            'departments.name as department_id',
            'designations.name as designation_id',
            'locations.name as location_id',
        ])
        ->map(function($employeemovement) {
            return $employeemovement;
        })
        ->first();
	    $row ['fromData'] = $employeemovement;
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

    public function update(EmployeeMovementRequest $request, $id) {
        $employeemovement=$this->employeemovement->update($id,[
            'employee_h_r_id'=>$request->employee_h_r_id,
            'post_date'=>$request->post_date,
            
        ]);
		if($employeemovement){
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
        if($this->employeemovement->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getEmployee(){
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');

        $employeehr=$this->employeehr
        ->when(request('company_id'), function ($q) {
          return $q->where('employee_h_rs.company_id','=',request('company_id', 0));
        })
        ->when(request('designation_id'), function ($q) {
          return $q->where('employee_h_rs.designation_id','=',request('designation_id', 0));
        })   
        ->when(request('department_id'), function ($q) {
          return $q->where('employee_h_rs.department_id','=',request('department_id', 0));
        }) 
        ->get([
          'employee_h_rs.*',
        ])
        ->map(function($employeehr) use($company,$designation,$department){
          $employeehr->employee_name=$employeehr->name;
          $employeehr->company_id=$company[$employeehr->company_id];
          $employeehr->designation_id=isset($designation[$employeehr->designation_id])?$designation[$employeehr->designation_id]:'';
          $employeehr->department_id=isset($department[$employeehr->department_id])?$department[$employeehr->department_id]:'';
          $employeehr->location_id=isset($location[$employeehr->location_id])?$location[$employeehr->location_id]:'';
          $employeehr->address='';
          return $employeehr;
        });

        echo json_encode($employeehr);
    }

   public function empTicket(){
    {
       // $user=array_prepend(array_pluck($this->user->get(),'name','id'),'-Select-','');
        $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
        $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
        $purpose = array_prepend(config('bprs.purpose'),'-Select-','');
        $transportmode = array_prepend(config('bprs.transportmode'),'-Select-','');
        $id=request('id',0);
        // $movement=$this->employeemovement->find($id);
        // $approved_by=$movement->approved_by;
        // if($approved_by==''){
        //   return '<h2 align="center">not approved</h2>';
        // }
        // else {
            $rows=$this->employeemovement
            ->join('employee_h_rs',function($join){
                $join->on('employee_movements.employee_h_r_id','=','employee_h_rs.id');
            })
            ->join('companies',function($join){
                $join->on('companies.id','=','employee_h_rs.company_id');
            })
            ->leftJoin('departments',function($join){
                $join->on('departments.id','=','employee_h_rs.department_id');
            })
            ->leftJoin('designations',function($join){
                $join->on('designations.id','=','employee_h_rs.designation_id');
            })
            ->leftJoin('locations',function($join){
                $join->on('locations.id','=','employee_h_rs.location_id');
            })
            ->join('users',function($join){
                $join->on('users.id','=','employee_movements.created_by');
            })
            ->leftJoin('users as updated_user',function($join){
                $join->on('updated_user.id','=','employee_movements.updated_by');
            })
            ->leftJoin('users as approval',function($join){
                $join->on('approval.id','=','employee_movements.approved_by');
            })
            // ->leftJoin('employee_h_rs as approval_emp',function($join){
            //     $join->on('approval.id','=','approval_emp.user_id');
            // })
            ->where([['employee_movements.id','=',$id]])
            ->get([
                'employee_movements.*',
                'employee_h_rs.id as employee_h_r_id',
                'employee_h_rs.company_id',
                'employee_h_rs.location_id',
                'employee_h_rs.department_id',
                'employee_h_rs.designation_id',
                'employee_h_rs.code',
                'employee_h_rs.contact',
                'employee_h_rs.name',
                'companies.name as company_name',
                'companies.logo as logo',
                'companies.address as company_address',
                'designations.name as designation_name',
                'departments.name as department_name',
                'locations.name as location_name',
                'locations.address as location_address',
                'users.name as user_name',
                'approval.name as approval_user_name',
                'updated_user.name as updated_user_name',
            ])
            ->first();

            $empmovedtail=$this->employeemovement
            ->join('employee_movement_dtls',function($join){
                $join->on('employee_movement_dtls.employee_movement_id','=','employee_movements.id');
            })
            ->orderBy('employee_movement_dtls.out_date_time','asc')
            ->where([['employee_movements.id','=',$id]])
            ->get([
                'employee_movement_dtls.*'
            ])
            ->map(function($empmovedtail) use($purpose,$transportmode){
                $empmovedtail->out_date=($empmovedtail->out_date_time!==null)?date('d-m-Y',strtotime($empmovedtail->out_date_time)):null;
                $empmovedtail->out_time=($empmovedtail->out_date_time!==null)?date('h:i:s A',strtotime($empmovedtail->out_date_time)):null;
                $empmovedtail->return_date=($empmovedtail->return_date_time!==null)?date('d-m-Y',strtotime($empmovedtail->return_date_time)):null;
                $empmovedtail->return_time=($empmovedtail->return_date_time!==null)?date('h:i:s A',strtotime($empmovedtail->return_date_time)):null;
                $empmovedtail->purpose_id=isset($empmovedtail->purpose_id)?$purpose[$empmovedtail->purpose_id]:'';
                $empmovedtail->transport_mode_id=isset($empmovedtail->transport_mode_id)?$transportmode[$empmovedtail->transport_mode_id]:'';
                return $empmovedtail;
            });


            $convAmount=$empmovedtail->sum('amount');
            $taDaAmount=$empmovedtail->sum('ta_da_amount');
            //$details=$data->groupBy('requisition_no');

            $inword=Numbertowords::ntow(number_format($convAmount+$taDaAmount,2,'.',''),'TK','paisa');
            $rows->inword=$inword;
            // $employeehr['master']=$rows;
                
            $company=$this->company
            ->where([['id','=',$rows->company_id]])
            ->get()->first();

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
            $pdf->SetFont('helvetica', 'N', 8);
            $pdf->AddPage();
        
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
            $pdf->SetY(5);
            $pdf->SetX(160);
            $challan=str_pad($id,10,0,STR_PAD_LEFT ) ;
            $pdf->write1DBarcode(str_pad($challan,10,0,STR_PAD_LEFT), 'C39', '', '', '', 18, 0.4, $barcodestyle, 'N');

            $pdf->SetY(10);
            $image_file ='images/logo/'.$company->logo;
            $pdf->Image($image_file, 90, 2, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
            $pdf->SetY(12);
            $pdf->SetFont('helvetica', 'N', 8);
            //$pdf->Text(64, 12, $company->address);
            $pdf->Cell(0, 40, $company->address, 0, false, 'C', 0, '', 0, false, 'T', 'M' );
           // $pdf->SetY(16);
           // $pdf->SetFont('helvetica', 'N', 10);
          //  $pdf->Write(0, '', '', 0, 'C', true, 0, false, false, 0);
            $pdf->SetFont('helvetica', 'N', 8);
            //$pdf->SetTitle('Appointment Letter');

            $view= \View::make('Defult.HRM.EmployeeMovementTicketPdf',['rows'=>$rows,'empmovedtail'=>$empmovedtail]);
            $html_content=$view->render();
            $pdf->SetY(35);
            $pdf->WriteHtml($html_content, true, false,true,false,'');
            $filename = storage_path() . '/AppintmentLetterPdf.pdf';
            $pdf->output($filename);
           // }
        }
    }
}