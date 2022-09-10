<?php
namespace App\Http\Controllers\Subcontract\Inbound;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbEventRepository;
use App\Repositories\Contracts\Util\TeamRepository;

use App\Library\Template;
use App\Http\Requests\Subcontract\Inbound\SubInbEventRequest;

class SubInbEventController extends Controller {

    private $subinbevent;
    private $company;
    private $buyer;
    private $team;

    public function __construct(
        SubInbEventRepository $subinbevent, 
        CompanyRepository $company,
        BuyerRepository $buyer,
        TeamRepository $team
    ) {
        $this->subinbevent = $subinbevent;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->team = $team;

        $this->middleware('auth');
        /*$this->middleware('permission:view.targettransfers',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.targettransfers', ['only' => ['store']]);
        $this->middleware('permission:edit.targettransfers',   ['only' => ['update']]);
        $this->middleware('permission:delete.targettransfers', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() { 

        $meetingtype=array_prepend(config('bprs.meetingtype'),'-Select-','');   
        $rows=$this->subinbevent
        ->where([['sub_inb_marketing_id','=',request('sub_inb_marketing_id',0)]])
        ->orderBy('sub_inb_events.id','desc')
        ->get()
        ->map(function($rows) use($meetingtype){
            $rows->meeting_type_id=$meetingtype[$rows->meeting_type_id];
            $rows->meeting_date=$rows->meeting_date?date('Y-m-d',strtotime($rows->meeting_date)):'--';
            $rows->next_meeting_date=$rows->next_meeting_date?date('Y-m-d',strtotime($rows->next_meeting_date)):'--';
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
 
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubInbEventRequest $request) {
        $subinbevent = $this->subinbevent->create($request->except(['id']));
        if($subinbevent){
            return response()->json(array('success' => true,'id' =>  $subinbevent->id,'message' => 'Save Successfully'),200);
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
        $subinbevent = $this->subinbevent->find($id);
        $row ['fromData'] = $subinbevent;
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
    public function update(SubInbEventRequest $request, $id) {
        $subinbevent=$this->subinbevent->update($id,$request->except(['id']));
        if($subinbevent){
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
        if($this->subinbevent->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}