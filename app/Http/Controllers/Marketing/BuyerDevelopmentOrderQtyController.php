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
use App\Repositories\Contracts\Marketing\BuyerDevelopmentOrderQtyRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\TeamRepository;

use App\Library\Template;
use App\Http\Requests\Marketing\BuyerDevelopmentOrderQtyRequest;

class BuyerDevelopmentOrderQtyController extends Controller {

    private $buyerdevelopmentorderqty;
    private $company;
    private $buyer;
    private $team;
    private $currency;
    private $buyerdevelopmentorder;

    public function __construct(
        BuyerDevelopmentOrderQtyRepository $buyerdevelopmentorderqty, 
        CompanyRepository $company,
        BuyerRepository $buyer,
        TeamRepository $team,
        CurrencyRepository $currency,
        BuyerDevelopmentOrderRepository $buyerdevelopmentorder
    ) {
        $this->buyerdevelopmentorderqty = $buyerdevelopmentorderqty;
        $this->company = $company;
        $this->buyer = $buyer;
        $this->team = $team;
        $this->currency=$currency;
        $this->buyerdevelopmentorder=$buyerdevelopmentorder;

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
       $rows=$this->buyerdevelopmentorderqty
       ->join("buyer_development_orders",function($join){
        $join->on("buyer_development_orders.id","=","buyer_development_order_qties.buyer_development_order_id");
       })
       ->where([['buyer_development_order_qties.buyer_development_order_id','=',request('buyer_development_order_id',0)]])
       ->orderBy("buyer_development_order_qties.id","DESC")
       ->get([
        "buyer_development_order_qties.*",
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
    public function store(BuyerDevelopmentOrderQtyRequest $request) {
       $buyerdevelopmentorderqty = $this->buyerdevelopmentorderqty->create($request->except(['id']));
        if($buyerdevelopmentorderqty){
            return response()->json(array('success' => true,'id' =>  $buyerdevelopmentorderqty->id, 'message' => 'Save Successfully'),200);
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
        $buyerdevelopmentorderqty = $this->buyerdevelopmentorderqty->find($id);
        $row ['fromData'] = $buyerdevelopmentorderqty;
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
    public function update(BuyerDevelopmentOrderQtyRequest $request, $id) {
        $buyerdevelopmentorderqty=$this->buyerdevelopmentorderqty->update($id,$request->except(['id']));
        if($buyerdevelopmentorderqty){
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
        if($this->buyerdevelopmentorderqty->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}