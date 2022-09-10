<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeRepository;
use App\Repositories\Contracts\HRM\RegisterVisitorRepository;
use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\LocationRepository;

use App\Library\Template;
use App\Library\Sms;
use App\Http\Requests\HRM\RegisterVisitorRequest;

class RegisterVisitorController extends Controller {

    private $registervisitor;
    private $employee;
    private $user;
    private $company;
    private $location;


    public function __construct(
      EmployeeRepository $employee,
      RegisterVisitorRepository $registervisitor,CompanyRepository $company, 
      UserRepository $user,LocationRepository $location
    ) {
        $this->employee = $employee;
        $this->registervisitor = $registervisitor;
        $this->user = $user;
        $this->company = $company;
        $this->location = $location;

        $this->middleware('auth');
        // $this->middleware('permission:view.registervisitors',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.registervisitors', ['only' => ['store']]);
        // $this->middleware('permission:edit.registervisitors',   ['only' => ['update']]);
        // $this->middleware('permission:delete.registervisitors', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $new_day=date('Y-m-d');

      $registervisitor=$this->registervisitor
      ->leftJoin('users',function($join){
        $join->on('users.id','=','register_visitors.user_id');
      })
      ->leftJoin('employee_h_rs',function($join){
        $join->on('users.id','=','employee_h_rs.user_id');
      })
      ->leftJoin('departments',function($join){
        $join->on('departments.id','=','employee_h_rs.department_id');
      })
      ->leftJoin('users as approve_user',function($join){
        $join->on('approve_user.id','=','register_visitors.approve_user_id');
      })
      ->leftJoin('employee_h_rs as approve_employee',function($join){
        $join->on('approve_user.id','=','approve_employee.user_id');
      })
      ->when(request('name'), function ($q) {
        return $q->where('register_visitors.name', '=', request('name', 0));
        })
      ->where([['register_visitors.arrival_date','=',$new_day]])
      ->orderBy('register_visitors.id','desc')
      ->get([
        'register_visitors.*',
        'users.name as user_name',
        'approve_user.name as approve_user_name',
        'users.id as user_id',
        'departments.name as department_name',
      //'locations.name as location_name',
      ])
      ->map(function($registervisitor){
        $registervisitor->arrival_date=date('d-M-Y',strtotime($registervisitor->arrival_date));
        if($registervisitor->approved_at){
          $registervisitor->approved_by="Approved";
        }else {
          $registervisitor->approved_by='';
        }
        return $registervisitor;
      });
      echo json_encode($registervisitor);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      $user=array_prepend(array_pluck($this->user->get(),'name','id'),'','');
      $location=array_prepend(array_pluck($this->location->get(),'name','id'),'','');
		  return Template::loadView('HRM.RegisterVisitor', ['user'=>$user,'location'=>$location]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterVisitorRequest $request) {
      
      $registervisitor=$this->registervisitor->create([
        'name'=>$request->name,
        'arrival_date'=>$request->arrival_date,
        'contact_no'=>$request->contact_no,
        'organization_dtl'=>$request->organization_dtl,
        'arrival_time'=>$request->arrival_time,
        'user_id'=>$request->user_id,
        'purpose'=>$request->purpose,
        'approve_user_id'=>$request->approve_user_id,
        'location_id'=>$request->location_id
      ]);
      if($registervisitor){
          $user=array_prepend(array_pluck($this->user->get(),'name','id'),'','');
          $location=array_prepend(array_pluck($this->location->get(),'name','id'),'','');
          
          $title ='Visitor Arrival';
          $text = 
          $title."\n".
          'Name:'.$request->name."\n".
          'Phone:'.$request->contact_no."\n".
          'Organization:'.$request->organization_dtl."\n".
          'Arrival Date:'.$request->arrival_date."\n".
          'Arrived at:'.$request->arrival_time."\n".
          'To Whom:'.$user[$request->user_id]."\n".
          'Purpose:'.$request->purpose."\n".
          'Location:'.$location[$request->location_id]."\n".
          'Approving User:'.$user[$request->approve_user_id];

          $userContact=$this->registervisitor
          ->leftJoin('users',function($join){
            $join->on('users.id','=','register_visitors.user_id');
          })
          ->leftJoin('employee_h_rs',function($join){
            $join->on('users.id','=','employee_h_rs.user_id');
          })
          ->where([['users.id','=',$request->user_id]])
          ->get([
            'users.id as user_id',
            'users.name as user_name',
            'employee_h_rs.contact'
          ])->first();

          $approveUser=$this->registervisitor
          ->leftJoin('users',function($join){
            $join->on('users.id','=','register_visitors.approve_user_id');
          })
          ->leftJoin('employee_h_rs',function($join){
            $join->on('users.id','=','employee_h_rs.user_id');
          })
          ->where([['users.id','=',$request->approve_user_id]])
          ->get([
            'users.id as approve_user_id',
            'users.name as user_name',
            'employee_h_rs.contact'
          ])->first();

        $sms=Sms::send_sms($text, 
        '88'.$userContact->contact.','.
        '88'.$approveUser->contact).',8801714173989,8801715424277,8801321128280';//Alex,Simple,Bivuti Hira
        return response()->json(array('success' => true,'id' =>  $registervisitor->id,'sms' => $sms,'userContact'=>$userContact, 'message' => 'Save Successfully'),200);
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
        $registervisitor = $this->registervisitor->find($id);  
        $row ['fromData'] = $registervisitor;
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
    public function update(RegisterVisitorRequest $request, $id) {
      $arrived = $this->registervisitor->find($id);
      if($arrived->approved_by){
        $registervisitor=$this->registervisitor->update($id,[
          'departure_time'=>$request->departure_time 
          ]);
        if($registervisitor){
            return response()->json(array('success' => true,'message' => 'Visit is approved & Departure Date updated'),200);
        }
        
      }else{
        $registervisitor=$this->registervisitor->update($id,$request->except(['id']));
        if($registervisitor){
          return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
        }
      }
     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->registervisitor->delete($id)){
          return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getVisitorName(Request $request){
      return $this->registervisitor
      ->where([['name','LIKE','%'.$request->q.'%']])
      ->distinct()
      ->orderBy('name','asc')
      ->get(['name']);
  }
  
    public function getApprovedPdf(){
      $user=array_prepend(array_pluck($this->user->get(),'name','id'),'','');
      $id=request('id', 0);
     $regvisit=$this->registervisitor->find($id);
      $approved_by=$regvisit->approved_by;
      if($approved_by==''){
        return '<h2 align="center">not approved</h2>';
      }else {
        $rows=$this->registervisitor
        ->leftJoin('users',function($join){
          $join->on('users.id','=','register_visitors.user_id');
        })
        ->leftJoin('employee_h_rs',function($join){
          $join->on('users.id','=','employee_h_rs.user_id');
        })
        ->leftJoin('departments',function($join){
          $join->on('departments.id','=','employee_h_rs.department_id');
        })
        ->where([['register_visitors.id','=',$id]])
        ->get([
          'register_visitors.*',
          'users.name as user_name',
          'users.id as user_id',
          'departments.name as department_name',
        //'locations.name as location_name',
        ])
        ->map(function($rows){
          $rows->arrival_date=date('d-M-Y',strtotime($rows->arrival_date));
          return $rows;
        });

        
        $company=$this->company
        ->where([['id','=',21]])
        ->get()->first();

        $pdf = new \Pdf('P', 'mm', 'A7 PORTRAIT', true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->setTopMargin(0.5);
        $pdf->SetRightMargin(1);
        $pdf->SetLeftMargin(2.5);
        $pdf->setHeaderMargin(0.5);
        $pdf->SetFooterMargin(0.1);
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 0.1);
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->AddPage('P', 'G9');

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
      
      
        $pdf->SetX(5);
        $qrc =  'Name :'.$regvisit->name ." ,\n".
              'Phone :'.$regvisit->contact_no." ,\n".
              'Organization :'.$regvisit->organization_dtl." ,\n".
              'Arrival Date :'.$regvisit->arrival_date." ,\n".
              'Arrived at :'.$regvisit->arrival_time." ,\n".
              'To Whom :'.$user[$regvisit->user_id];
      $pdf->write2DBarcode($qrc, 'QRCODE,Q', 30, 3, 15, 15, $barcodestyle, 'N');

     // $pdf->Text(18, 10, 'FAMKAM ERP');
     // $pdf->Text(18, 10, 'Employee ID :'.$id);

        $pdf->SetFont('helvetica', '', 6);
        $view= \View::make('Defult.HRM.VisitorApprovedPdf',['rows'=>$rows]);
        $html_content=$view->render();
        $pdf->SetY(15);
        $pdf->WriteHtml($html_content, true, false,true,false,'');
        $filename = storage_path() . '/ScreenPrintPdf.pdf';
        $pdf->output($filename);
        exit();
      }
    }
}
