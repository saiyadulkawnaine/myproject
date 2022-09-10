<?php
namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentRepository;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentOrderRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\TeamRepository;

use App\Library\Template;
use App\Http\Requests\Marketing\BuyerDevelopmentOrderRequest;

class BuyerDevelopmentOrderController extends Controller {

    private $buyerdevelopmentorder;
    private $company;
    private $buyer;
    private $team;
    private $currency;

    public function __construct(
        BuyerDevelopmentOrderRepository $buyerdevelopmentorder, 
        CompanyRepository $company,
        BuyerRepository $buyer,
        TeamRepository $team,
        CurrencyRepository $currency
    ) {
        $this->buyerdevelopmentorder = $buyerdevelopmentorder;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->team = $team;
        $this->currency=$currency;

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
       $rows=$this->buyerdevelopmentorder
       ->join("buyer_development_intms",function($join){
        $join->on("buyer_development_intms.id","=","buyer_development_orders.buyer_development_intm_id");
       })
       ->join("currencies",function($join){
        $join->on("currencies.id","=","buyer_development_orders.currency_id");
       })
       ->where([['buyer_development_intm_id','=',request('buyer_development_intm_id',0)]])
       ->orderBy("buyer_development_orders.id","DESC")
       ->get([
        "buyer_development_orders.*",
        "currencies.name as currency_name",
       ]);

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
    public function store(BuyerDevelopmentOrderRequest $request) {
       // $buyer_development_id=request('buyer_development_id',0);
       // 'buyer_development_id'=>$buyerdevelopmentorder->buyer_development_id,
       $buyerdevelopmentorder = $this->buyerdevelopmentorder->create($request->except(['id']));
        if($buyerdevelopmentorder){
            return response()->json(array('success' => true,'id' =>  $buyerdevelopmentorder->id, 'message' => 'Save Successfully'),200);
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
        $buyerdevelopmentorder = $this->buyerdevelopmentorder->find($id);
        $row ['fromData'] = $buyerdevelopmentorder;
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
    public function update(BuyerDevelopmentOrderRequest $request, $id) {
        $buyerdevelopmentorder=$this->buyerdevelopmentorder->update($id,$request->except(['id']));
        if($buyerdevelopmentorder){
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
        if($this->buyerdevelopmentorder->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}