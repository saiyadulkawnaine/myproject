<?php
namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentRepository;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentIntmRepository;
use App\Repositories\Contracts\Util\TeamRepository;

use App\Library\Template;
use App\Http\Requests\Marketing\BuyerDevelopmentIntmRequest;

class BuyerDevelopmentIntmController extends Controller {

    private $buyerdevelopment;
    private $buyerdevelopmentintm;
    private $company;
    private $buyer;
    private $team;

    public function __construct(
        BuyerDevelopmentRepository $buyerdevelopment, 
        BuyerDevelopmentIntmRepository $buyerdevelopmentintm, 
        CompanyRepository $company,
        BuyerRepository $buyer,
        TeamRepository $team
    ) {
        $this->buyerdevelopment = $buyerdevelopment;
        $this->buyerdevelopmentintm = $buyerdevelopmentintm;
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

        $rows=$this->buyerdevelopmentintm
        ->join('buyers',function($join){
        $join->on('buyers.id','=','buyer_development_intms.buyer_id');
        })
        ->where([['buyer_development_intms.buyer_development_id','=',request('buyer_development_id',0)]])
        ->orderBy('buyer_development_intms.id','desc')
        ->get([
            'buyer_development_intms.*',
            'buyers.name as buyer_name'
        ])
        ->map(function($rows){
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
    public function store(BuyerDevelopmentIntmRequest $request) {
        $buyerdevelopmentintm = $this->buyerdevelopmentintm->create($request->except(['id']));
        if($buyerdevelopmentintm){
            return response()->json(array('success' => true,'id' =>  $buyerdevelopmentintm->id,'message' => 'Save Successfully'),200);
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
        $buyerdevelopmentintm = $this->buyerdevelopmentintm->find($id);
        $row ['fromData'] = $buyerdevelopmentintm;
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
    public function update(BuyerDevelopmentIntmRequest $request, $id) {
        $buyerdevelopmentintm=$this->buyerdevelopmentintm->update($id,$request->except(['id']));
        if($buyerdevelopmentintm){
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
        if($this->buyerdevelopmentintm->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}