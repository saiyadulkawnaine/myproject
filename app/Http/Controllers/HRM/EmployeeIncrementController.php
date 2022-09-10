<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Contracts\HRM\EmployeeIncrementRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Library\Template;
use App\Http\Requests\HRM\EmployeeIncrementRequest;
use GuzzleHttp\Client;

class EmployeeIncrementController extends Controller {

    private $employeehr;
    private $increment;
    private $company;

    public function __construct(
        EmployeeHRRepository $employeehr,
        EmployeeIncrementRepository $increment,
        CompanyRepository $company
    ) {
        $this->employeehr = $employeehr;
        $this->increment = $increment;
        $this->company = $company;
        $this->middleware('auth');
        $this->middleware('permission:view.employeeincrements',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.employeeincrements', ['only' => ['store']]);
        $this->middleware('permission:edit.employeeincrements',   ['only' => ['update']]);
        $this->middleware('permission:delete.employeeincrements', ['only' => ['destroy']]);     }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $rows=$this->increment
      ->orderBy('employee_increments.id','desc')
      ->get();
      echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
  		return Template::loadView('HRM.EmployeeIncrement', []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeIncrementRequest $request) {


        if($request->file_src->getClientOriginalExtension()!=='csv'){
        return response()->json(array('success' => false,  'message' => 'Wrong File Format, Please Select a .csv file'), 200);
        }

        $name =time().'.'.$request->file_src->getClientOriginalExtension();
        $request->file_src->move(public_path('increments'), $name);
        \DB::beginTransaction();
        $increment=$this->increment->create([
        'file_src'=> $name,
        ]);

        $path= public_path('increments').'/'.$name;
        $row = 1;
        //$emps=[];

        if (($handle = fopen($path, "r")) !== FALSE) {
        while (($data = fgetcsv($handle)) !== FALSE) {
        //if($row<=6){
        if($row==1){
        }
        else{
        try
        {
        if($data[0]!=NULL){
        $effective_date=date('Y-m-d',strtotime($data[8]));
        \DB::table('employee_increment_dtls')->insert([
        'employee_increment_id'=>$increment->id,
        'employee_h_r_id'=>$data[0],
        'prev_gross'=>$data[1],
        'increment_per'=>$data[5],
        'increment_amount'=>$data[6],
        'new_gross'=>$data[7],
        'effective_date'=>$effective_date,
        ]
        );
        //$emp=['company_id'=>1,'emp_id'=>$data[0],'increment_amount'=>$data[6],'effective_date'=> $effective_date];
        //array_push($emps, $emp);

        \DB::table('employee_h_rs')
        ->where('id', $data[0])
        ->update(['SALARY' => $data[7]]);
        }
        }
        catch(EXCEPTION $e)
        {
        \DB::rollback();
        throw $e;
        }
        }
        //}
        $row++;
        }
        fclose($handle);
        }
        \DB::commit();

        $emps=$this->increment
        ->join('employee_increment_dtls',function($join){
        $join->on('employee_increment_dtls.employee_increment_id','=','employee_increments.id');
        })
        ->join('employee_h_rs',function($join){
        $join->on('employee_h_rs.id','=','employee_increment_dtls.employee_h_r_id');
        })
        ->where([['employee_increments.id','=',$increment->id]])
        //->where([['employee_h_rs.company_id','=',6]])
        ->get([
        'employee_h_rs.company_id',
        'employee_h_rs.id as emp_id',
        'employee_increment_dtls.increment_amount',
        'employee_increment_dtls.effective_date',
        ])
        ->map(function($emps){
        $emps->effective_date=date('Y-m-d',strtotime($emps->effective_date));
        return $emps;

        });

        $empdata = json_encode($emps);

        try
        {
        $client = new Client();
        $response = $client->request('POST', 'http://192.168.32.10:8082/Token',
        [
        'form_params' => [
        'grant_type' => 'password',
        'username' => 'erpadmin',
        'password' => 'admin@erp',
        ]
        ]);
        //$code = $response->getStatusCode();
        $body=json_decode($response->getBody());
        $token=$body->access_token;
        $headers = [
        'Authorization' => 'Bearer ' . $token,        
        'Accept'        => 'application/json',
        "Content-Type"  => "application/json"
        ]; 
        //echo $token; die;
        $res=$client->post('http://192.168.32.10:8082/Api/Erp/Increment', ['body' => $empdata, 'headers' => $headers]);
        //echo $res->getBody();
        $ApiStatus=json_decode($res->getBody());

        }
        catch(\GuzzleHttp\Exception\RequestException $e)
        {
        if($increment){
        return response()->json(array('success' => true,'id' =>  $increment->id,'message' => 'ERP Save Successfully, Maxecho Faild to update'),200);
        }
        throw $e;
        }
        //echo json_encode($emps);
        if ($increment) {
        return response()->json(array('success' => true, 'id' => $increment->id, 'message' => 'Save Successfully'), 200);
        }


        /*$increment=$this->increment->create($request->except(['id']));
        if($increment){
        return response()->json(array('success' => true,'id' =>  $increment->id,'message' => 'Save Successfully'),200);
        }*/
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
        $increment = $this->increment->find($id);
        $row ['fromData'] = $increment;
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
    public function update(EmployeeAttendenceRequest $request, $id) {
     /* $increment=$this->increment->update($id,$request->except(['id']));
      if($increment){
      return response()->json(array('success' => true,'id' =>  $id,'message' => 'Update Successfully'),200);
      } */ 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->increment->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
    public function sendToApi(){
        $id=request('id',0);
        $emp=$this->increment
        ->join('employee_increment_dtls',function($join){
          $join->on('employee_increment_dtls.employee_increment_id','=','employee_increments.id');
        })
        ->join('employee_h_rs',function($join){
          $join->on('employee_h_rs.id','=','employee_increment_dtls.employee_h_r_id');
        })
        ->where([['employee_increments.id','=',$id]])
        //->where([['employee_h_rs.company_id','=',6]])
        ->get([
            'employee_h_rs.company_id',
            'employee_h_rs.id as emp_id',
            'employee_increment_dtls.increment_amount',
            'employee_increment_dtls.effective_date',
        ])
        ->map(function($emp){
            $emp->effective_date=date('Y-m-d',strtotime($emp->effective_date));
            return $emp;

        });
        
        $empdata = json_encode($emp);

        try
        {
          $client = new Client();
          $response = $client->request('POST', 'http://192.168.32.10:8082/Token',
          [
            'form_params' => [
            'grant_type' => 'password',
            'username' => 'erpadmin',
            'password' => 'admin@erp',
          ]
          ]);
          //$code = $response->getStatusCode();
          $body=json_decode($response->getBody());
          $token=$body->access_token;
          $headers = [
            'Authorization' => 'Bearer ' . $token,        
            'Accept'        => 'application/json',
            "Content-Type"  => "application/json"
          ]; 
          //echo $token; die;
          $res=$client->post('http://192.168.32.10:8082/Api/Erp/Increment', ['body' => $empdata, 'headers' => $headers]);
          //echo $res->getBody();
          $ApiStatus=json_decode($res->getBody());
          
        }
        catch(\GuzzleHttp\Exception\RequestException $e)
        {
          
            return response()->json(array('success' => false,'message' => ' Maxecho Faild to Save'),200);
            throw $e;
        }
        return response()->json(array('success' => true, 'message' => 'Maxecho Save Successfully'), 200);
       
    }

}
