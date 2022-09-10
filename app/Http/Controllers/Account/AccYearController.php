<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Account\AccYearRepository;
use App\Repositories\Contracts\Account\AccPeriodRepository;

use App\Repositories\Contracts\Util\CompanyRepository;
use App\Library\Template;
use App\Http\Requests\Account\AccYearRequest;

class AccYearController extends Controller {

    private $accyear;
    private $accperiod;
	private $company;

    public function __construct(AccYearRepository $accyear,AccPeriodRepository $accperiod,CompanyRepository $company) {
        $this->accyear = $accyear;
        $this->accperiod = $accperiod;
		$this->company = $company;

        $this->middleware('auth');
        $this->middleware('permission:view.accyears',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.accyears', ['only' => ['store']]);
        $this->middleware('permission:edit.accyears',   ['only' => ['update']]);
        $this->middleware('permission:delete.accyears', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
		$accyears=array();
		$yesno=config('bprs.yesno');
			$rows=$this->accyear->orderBy('id','desc')->get();
			foreach ($rows as $row) {
				$accyear['id']=$row->id;
				$accyear['name']=$row->name;
				$accyear['start_date']=date('Y-m-d',strtotime($row->start_date));
				$accyear['end_date']=date('Y-m-d',strtotime($row->end_date));
				$accyear['is_current']=$yesno[$row->is_current];
				$accyear['company_id']=$company[$row->company_id];
				

				array_push($accyears,$accyear);
			}
        echo json_encode($accyears);
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
        $yesno=config('bprs.yesno');
		return Template::loadView('Account.AccYear', ['yesno'=>$yesno,'company'=>$company]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccYearRequest $request) {
        if ( $this->is_date_in_fiscalyears($request->company_id,$request->start_date))
        {
            return response()->json(array('success' => false,'message' => 'Invalid Start date in fiscal year.'),200); 
            
        }
        if ( $this->is_date_in_fiscalyears($request->company_id,$request->end_date))
        {
            return response()->json(array('success' => false,'message' => 'Invalid End date in fiscal year.'),200); 
            
        }
		$startYear=date('Y',strtotime($request->start_date));
		$endYear=date('y',strtotime($request->end_date));
		$name=$startYear."-".$endYear;
		$request['name']=$name;
        $is_current_year_found=$this->accyear->where([['company_id','=',$request->company_id]])->where([['is_current','=',1]])->get()->count();
        if($is_current_year_found && $request->is_current==1){
           return response()->json(array('success' => false,'message' => 'Current year is found for this company'),200); 
        }

		$accyear=$this->accyear->create($request->except(['id']));
        $prd=$this->make_period($request->start_date,$request->end_date);

        foreach($prd as $index=>$val){
           
                $period = $this->accperiod->updateOrCreate(
                ['acc_year_id' => $accyear->id,  'period' =>  $index, 'start_date' => $val['start_date'],'end_date' => $val['end_date']],['name' => $val['name'],'is_open' => 1]);
            
        }


		if($accyear){
			return response()->json(array('success' => true,'id' =>  $accyear->id,'message' => 'Save Successfully'),200);
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
		
		$accyear = $this->accyear->where([['id','=',$id]])->get()->map(function ($accyear) {
		$accyear->start_date= date('Y-m-d',strtotime($accyear->start_date));
		$accyear->end_date= date('Y-m-d',strtotime($accyear->end_date));
		return $accyear;
		})->first();
	   $row ['fromData'] = $accyear;
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
    public function update(AccYearRequest $request, $id) {

        if ( $this->is_date_in_fiscalyears_update($request->company_id,$request->start_date,$id))
        {
            return response()->json(array('success' => false,'message' => 'Invalid Start date in fiscal year.'),200); 
            
        }

        if ( $this->is_date_in_fiscalyears_update($request->company_id,$request->end_date,$id))
        {
            return response()->json(array('success' => false,'message' => 'Invalid End date in fiscal year.'),200); 
            
        }

         $is_current_year_found=$this->accyear->where([['company_id','=',$request->company_id]])->where([['is_current','=',1]])->where([['id','!=',$id]])->get()->count();
        if($is_current_year_found && $request->is_current==1){
           return response()->json(array('success' => false,'message' => 'Current year is found for this company'),200); 
        }
		$startYear=date('Y',strtotime($request->start_date));
		$endYear=date('y',strtotime($request->end_date));
		$name=$startYear."-".$endYear;
		$request['name']=$name;
		$accyear=$this->accyear->update($id,$request->except(['id']));
		if($accyear){
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
		if($this->accyear->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
        else{
             return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
        }
        
    }

    function is_date_in_fiscalyears($company_id,$date, $closed=true)
    {
        $dd=date('Y-m-d',strtotime($date));
        $accyear=$this->accyear
        ->where([['company_id','=',$company_id]])
        ->whereRaw('? between start_date and end_date', [$date])
        ->get();
        return $accyear->count();
    }

    function is_date_in_fiscalyears_update($company_id,$date,$id, $closed=true)
    {
        $accyear=$this->accyear
        ->where([['company_id','=',$company_id]])
        ->where([['id','!=',$id]])
        ->whereRaw('? between start_date and end_date', [$date])
        ->get();
        return $accyear->count();
    }

    function make_period($start_date,$end_date){
        $start    = (new \DateTime($start_date))->modify('first day of this month');
        $end      = (new \DateTime($end_date))->modify('first day of next month');
        $interval = \DateInterval::createFromDateString('1 month');
        $period   = new \DatePeriod($start, $interval, $end);

        $periodarr=array();
        $i=1;
        foreach ($period as $dt) {
        $start_date= $dt->format("Y-m-d");
        $end_date= $dt->format("Y-m-t");
        $name=$dt->format("F");

        array('start_date'=>$start_date,'end_date'=>$end_date,'name'=>$name);
        $periodarr[$i]=array('start_date'=>$start_date,'end_date'=>$end_date,'name'=>$name);
        $i++;
        }
        $periodarr[0]=$periodarr[1];
        $periodarr[0]['name']='Opening';


        $index=$i-1;
        //array_push($periodarr, $periodarr[$index]);
        //array_push($periodarr, $periodarr[$index]);

        //$max=$i+1;
       
        $periodarr[$i]=$periodarr[$index];
        $periodarr[$i]['name']='Closing';

        $pc=$i+1;
        $periodarr[$pc]=$periodarr[$index];
        $periodarr[$pc]['name']='Post-Closing';
        ksort($periodarr);
        return $periodarr;
    }

    function getBycompany(){
        $accyear=$this->accyear->getBycompany(request('company_id',0));
        echo json_encode($accyear);
    }
    

}
