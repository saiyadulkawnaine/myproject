<?php

namespace App\Http\Controllers\Report\HRM;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
//use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
class EmployeeInformationController extends Controller
{
	private $employeehr;
	private $company;
	private $designation;
	private $department;

	public function __construct(EmployeeHRRepository $employeehr,DesignationRepository $designation,DepartmentRepository $department,CompanyRepository $company)
    {
		$this->employeehr = $employeehr;
      	$this->designation = $designation;
      	$this->department = $department;
      	$this->company = $company;

		$this->middleware('auth');

		$this->middleware('permission:view.employeeinformations',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
	  $designation=array_prepend(array_pluck($this->designation->get(),'name','id'),'-Select-','');
      $department=array_prepend(array_pluck($this->department->get(),'name','id'),'-Select-','');
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $yesno = config('bprs.yesno');
	  $gender = array_prepend(config('bprs.gender'),'-Select-','');
	  $status=array_prepend(array_only(config('bprs.status'), [1,0]),'-All-','');
	  $employeetype=array_prepend(config('bprs.employeetype'), '-All-','');
	  
	  //$status=array_prepend(config('bprs.status'),'-Select-','');
      return Template::loadView('Report.HRM.EmployeeInformation',['designation'=>$designation,'department'=>$department,'yesno'=>$yesno,'gender'=>$gender,'company'=>$company,'status'=>$status,'employeetype'=>$employeetype]);
	 }
	 
	public function html() {
	  	
      	$yesno = config('bprs.yesno');
		$gender = array_prepend(config('bprs.gender'),'-Select-','');
	
		$designation=request('employee_type_id',0);
      	$department=request('department_id',0);
		$designation=request('designation_id',0);
      	$department=request('department_id',0);
      	$company=request('company_id',0);
      	$name=request('name',0);
	  	$status_id=request('status_id',NULL);
	  	$date_from=request('date_from', 0);
		$date_to=request('date_to', 0);
		// if($status_id){
		// 	$status_id=" and employee_h_rs.status_id = $status_id ";
		// }
		
		
		if($status_id==NULL){
			$employeehr=$this->employeehr
			->selectRaw('employee_h_rs.id,
				employee_h_rs.name,
				employee_h_rs.code,
				employee_h_rs.date_of_join,
				employee_h_rs.date_of_birth,
				employee_h_rs.gender_id,
				employee_h_rs.national_id,
				employee_h_rs.tin,
				employee_h_rs.religion,
				employee_h_rs.contact,
				employee_h_rs.email,
				employee_h_rs.address,
				employee_h_rs.grade,
				employee_h_rs.status_id,
				companies.id as company_id,
				companies.name as company_name,
				departments.id as department_id,
				designations.name as designation_name,
				departments.name as department_name,
				locations.name as location_name,
				divisions.name as division_name,
				sections.name as section_name,
				subsections.name as subsection_name
				')		/* */
		   	->join('companies',function($join){
			   $join->on('employee_h_rs.company_id','=','companies.id');
		   	})
		   	->leftJoin('locations',function($join){
			   $join->on('employee_h_rs.location_id','=','locations.id');
		   	})
		   	->leftJoin('divisions',function($join){
			   $join->on('employee_h_rs.division_id','=','divisions.id');
		   	})
		   	->leftJoin('departments',function($join){
			   $join->on('employee_h_rs.department_id','=','departments.id');
		   	})
		   	->leftJoin('sections',function($join){
			   $join->on('employee_h_rs.section_id','=','sections.id');
		   	})
		   	->leftJoin('subsections',function($join){
			   $join->on('employee_h_rs.subsection_id','=','subsections.id');
		   	})
		    ->leftJoin('designations',function($join){
			   $join->on('employee_h_rs.designation_id','=','designations.id');
		   	})
		    ->when(request('company_id'), function ($q) {
				return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
		   	})
		   	->when(request('department_id'), function ($q) {
				return $q->where('employee_h_rs.department_id', '=', request('department_id', 0));
		   	})
		    ->when(request('designation_id'), function ($q) {
				return $q->where('employee_h_rs.designation_id', '=', request('designation_id', 0));
		   	})
		    ->when(request('employee_type_id'), function ($q) {
				return $q->where('employee_h_rs.employee_type_id', '=', request('employee_type_id', 0));
		   	})
		   	->when(request('code'), function ($q) {
				return $q->where('employee_h_rs.code', '=', request('code', 0));
		   	})
		   	->when(request('date_from'), function ($q) {
				return $q->where('employee_h_rs.date_of_join', '>=',request('date_from', 0));
			})
           	->when(request('date_to'), function ($q) {
				return $q->where('employee_h_rs.date_of_join', '<=',request('date_to', 0));
			})
			->where([['employee_h_rs.status_id','!=',2]])
		   	->orderBy('employee_h_rs.id','desc')
		   	->get()
		   	->map(function($employeehr) use($gender,$yesno){
				$employeehr->company_id = $employeehr->company_name;
				$employeehr->gender_id =	isset($gender[$employeehr->gender_id])?$gender[$employeehr->gender_id]:'';
				$employeehr->designation_id =$employeehr->designation_name;
				$employeehr->department_id =$employeehr->department_name;
				$employeehr->salary =number_format($employeehr->salary,0);
				$employeehr->is_advanced_applicable =isset($yesno[$employeehr->is_advanced_applicable])?$yesno[$employeehr->is_advanced_applicable]:'';
				return $employeehr;
		   	});
			echo json_encode($employeehr);
		}
		elseif($status_id==1) {
			$employeehr=$this->employeehr
			->selectRaw('employee_h_rs.id,
				employee_h_rs.name,
				employee_h_rs.code,
				employee_h_rs.date_of_join,
				employee_h_rs.date_of_birth,
				employee_h_rs.gender_id,
				employee_h_rs.national_id,
				employee_h_rs.tin,
				employee_h_rs.religion,
				employee_h_rs.contact,
				employee_h_rs.email,
				employee_h_rs.address,
				employee_h_rs.grade,
				employee_h_rs.status_id,
				companies.id as company_id,
				companies.name as company_name,
				departments.id as department_id,
				designations.name as designation_name,
				departments.name as department_name,
				locations.name as location_name,
				divisions.name as division_name,
				sections.name as section_name,
				subsections.name as subsection_name
				')		/* */
		   	->join('companies',function($join){
			   $join->on('employee_h_rs.company_id','=','companies.id');
		   	})
		   	->leftJoin('locations',function($join){
			   $join->on('employee_h_rs.location_id','=','locations.id');
		   	})
		   	->leftJoin('divisions',function($join){
			   $join->on('employee_h_rs.division_id','=','divisions.id');
		   	})
		   	->leftJoin('departments',function($join){
			   $join->on('employee_h_rs.department_id','=','departments.id');
		   	})
		   	->leftJoin('sections',function($join){
			   $join->on('employee_h_rs.section_id','=','sections.id');
		   	})
		   	->leftJoin('subsections',function($join){
			   $join->on('employee_h_rs.subsection_id','=','subsections.id');
		   	})
		    ->leftJoin('designations',function($join){
			   $join->on('employee_h_rs.designation_id','=','designations.id');
		   	})
		    ->when(request('company_id'), function ($q) {
				return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
		   	})
		   	->when(request('department_id'), function ($q) {
				return $q->where('employee_h_rs.department_id', '=', request('department_id', 0));
		   	})
		    ->when(request('designation_id'), function ($q) {
				return $q->where('employee_h_rs.designation_id', '=', request('designation_id', 0));
		   	})
			->when(request('employee_type_id'), function ($q) {
				return $q->where('employee_h_rs.employee_type_id', '=', request('employee_type_id', 0));
		   	})
		   	->when(request('code'), function ($q) {
				return $q->where('employee_h_rs.code', '=', request('code', 0));
		   	})
		   	->when(request('date_from'), function ($q) {
				return $q->where('employee_h_rs.date_of_join', '>=',request('date_from', 0));
			})
           	->when(request('date_to'), function ($q) {
				return $q->where('employee_h_rs.date_of_join', '<=',request('date_to', 0));
			})
			//   ->when(request('status_id'),function($q) {
			// 		return $q->where('employee_h_rs.status_id','=',request('status_id',0));
			// 	})

			->when($status_id, function ($q) use ($status_id) {
				return $q->where("employee_h_rs.status_id",$status_id);
			})
		   
			//->where([['employee_h_rs.status_id','!=',2]])
			//->where([['employee_h_rs.status_id','=',1]])
			->orderBy('employee_h_rs.id','desc')
			->get()
		   	->map(function($employeehr) use($gender,$yesno){
				$employeehr->company_id = $employeehr->company_name;
				$employeehr->gender_id =	isset($gender[$employeehr->gender_id])?$gender[$employeehr->gender_id]:'';
				$employeehr->designation_id =$employeehr->designation_name;
				$employeehr->department_id =$employeehr->department_name;
				$employeehr->salary =number_format($employeehr->salary,0);
				$employeehr->is_advanced_applicable =isset($yesno[$employeehr->is_advanced_applicable])?$yesno[$employeehr->is_advanced_applicable]:'';
				return $employeehr;
		   	});
			//dd($rows);
			//echo json_encode($employeehr);
			return $employeehr;
		}
		elseif($status_id==0) {
			$employeehr=$this->employeehr
			->selectRaw('employee_h_rs.id,
				employee_h_rs.name,
				employee_h_rs.code,
				employee_h_rs.date_of_join,
				employee_h_rs.date_of_birth,
				employee_h_rs.gender_id,
				employee_h_rs.national_id,
				employee_h_rs.tin,
				employee_h_rs.religion,
				employee_h_rs.contact,
				employee_h_rs.email,
				employee_h_rs.address,
				employee_h_rs.grade,
				employee_h_rs.status_id,
				companies.id as company_id,
				companies.name as company_name,
				departments.id as department_id,
				designations.name as designation_name,
				departments.name as department_name,
				locations.name as location_name,
				divisions.name as division_name,
				sections.name as section_name,
				subsections.name as subsection_name
				')		/* */
		   	->join('companies',function($join){
			   $join->on('employee_h_rs.company_id','=','companies.id');
		   	})
		   	->leftJoin('locations',function($join){
			   $join->on('employee_h_rs.location_id','=','locations.id');
		   	})
		   	->leftJoin('divisions',function($join){
			   $join->on('employee_h_rs.division_id','=','divisions.id');
		   	})
		   	->leftJoin('departments',function($join){
			   $join->on('employee_h_rs.department_id','=','departments.id');
		   	})
		   	->leftJoin('sections',function($join){
			   $join->on('employee_h_rs.section_id','=','sections.id');
		   	})
		   	->leftJoin('subsections',function($join){
			   $join->on('employee_h_rs.subsection_id','=','subsections.id');
		   	})
		    ->leftJoin('designations',function($join){
			   $join->on('employee_h_rs.designation_id','=','designations.id');
		   	})
		    ->when(request('company_id'), function ($q) {
				return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
		   	})
		   	->when(request('department_id'), function ($q) {
				return $q->where('employee_h_rs.department_id', '=', request('department_id', 0));
		   	})
		    ->when(request('designation_id'), function ($q) {
				return $q->where('employee_h_rs.designation_id', '=', request('designation_id', 0));
		   	})
		   	->when(request('code'), function ($q) {
				return $q->where('employee_h_rs.code', '=', request('code', 0));
		   	})
		   	->when(request('date_from'), function ($q) {
				return $q->where('employee_h_rs.date_of_join', '>=',request('date_from', 0));
			})
           	->when(request('date_to'), function ($q) {
				return $q->where('employee_h_rs.date_of_join', '<=',request('date_to', 0));
			})
			->when(request('employee_type_id'), function ($q) {
				return $q->where('employee_h_rs.employee_type_id', '=', request('employee_type_id', 0));
		   	})
			->when($status_id, function ($q) use ($status_id) {
				return $q->where("employee_h_rs.status_id",$status_id);
			})
		   
			//->where([['employee_h_rs.status_id','!=',2]])
			//->where([['employee_h_rs.status_id','=',0]])
			->orderBy('employee_h_rs.id','desc')
			->get()
		   	->map(function($employeehr) use($gender,$yesno){
				$employeehr->company_id = $employeehr->company_name;
				$employeehr->gender_id =	isset($gender[$employeehr->gender_id])?$gender[$employeehr->gender_id]:'';
				$employeehr->designation_id =$employeehr->designation_name;
				$employeehr->department_id =$employeehr->department_name;
				$employeehr->salary =number_format($employeehr->salary,0);
				$employeehr->is_advanced_applicable =isset($yesno[$employeehr->is_advanced_applicable])?$yesno[$employeehr->is_advanced_applicable]:'';
				return $employeehr;
		   	});
			//dd($rows);
			echo json_encode($employeehr);
			//return $employeehr;
		}

		
	 }
	 

//    public function html(){
//       return response()->json($this->reportData());

//    }
	public function getpdf(){
		$id=request('id',0);
		$employeehr=$this->employeehr
			->selectRaw('employee_h_rs.id,
				employee_h_rs.name,
				employee_h_rs.code,
				employee_h_rs.date_of_join,
				employee_h_rs.date_of_birth,
				employee_h_rs.gender_id,
				employee_h_rs.national_id,
				employee_h_rs.tin,
				employee_h_rs.religion,
				employee_h_rs.contact,
				employee_h_rs.email,
				employee_h_rs.address,
				employee_h_rs.status_id,
				companies.id as company_id,
				companies.name as company_name,
				departments.id as department_id,
				designations.name as designation_name,departments.name as department_name')		/* */
		   	->join('companies',function($join){
			   $join->on('employee_h_rs.company_id','=','companies.id');
		   	})
		   	->leftJoin('departments',function($join){
			   $join->on('employee_h_rs.department_id','=','departments.id');
		   	})
		    ->leftJoin('designations',function($join){
			   $join->on('employee_h_rs.designation_id','=','designations.id');
		   	})
			->where([['employee_h_rs.id','=',$id]])
		   	->orderBy('employee_h_rs.id','desc')
		   	->get()
		   	->map(function($employeehr){/*  use($gender,$yesno) */
				$employeehr->company_id = $employeehr->company_name;
				//$employeehr->gender_id =	isset($gender[$employeehr->gender_id])?$gender[$employeehr->gender_id]:'';
				$employeehr->designation_id =$employeehr->designation_name;
				$employeehr->department_id =$employeehr->department_name;
				$employeehr->salary =number_format($employeehr->salary,0);
				//$employeehr->is_advanced_applicable =isset($yesno[$employeehr->is_advanced_applicable])?$yesno[$employeehr->is_advanced_applicable]:'';
				return $employeehr;
			   })
			->first();

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
		$employeelist['master'] = $employeehr;

		$txt = "Lithe Group Employee";
		//$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
		$pdf->SetY(5);
		$pdf->Text(90, 5, $txt);
		$pdf->SetY(10);
		$pdf->SetFont('helvetica', 'N', 10);
		//$pdf->Text(60, 10, $data['company']->address);
		$pdf->SetFont('helvetica', '', 8);
		


		$view= \View::make('Defult.Report.HRM.EmployeeListPdf',['employeelist'=>$employeelist]);
		$html_content=$view->render();
		$pdf->SetY(15);
		$pdf->WriteHtml($html_content, true, false,true,false,'');
		$filename = storage_path() . '/EmployeeListPdf.pdf';
		//echo $html_content;
		//$pdf->output($filename);
		$pdf->output($filename,'I');
		exit();
		//$pdf->output($filename,'F');
		//return response()->download($filename);
	}
}
