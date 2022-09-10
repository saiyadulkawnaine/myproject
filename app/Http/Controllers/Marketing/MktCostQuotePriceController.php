<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\MktCostQuotePriceRepository;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Library\Template;
use App\Library\Sms;
use App\Http\Requests\MktCostQuotePriceRequest;

class MktCostQuotePriceController extends Controller {

    private $mktcostquoteprice;
    private $mktcost;

    public function __construct(MktCostQuotePriceRepository $mktcostquoteprice,MktCostRepository $mktcost) {
        $this->mktcostquoteprice = $mktcostquoteprice;
        $this->mktcost = $mktcost;
        $this->middleware('auth');
        $this->middleware('permission:view.mktcostquoteprices',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.mktcostquoteprices', ['only' => ['store']]);
        $this->middleware('permission:edit.mktcostquoteprices',   ['only' => ['update']]);
        $this->middleware('permission:delete.mktcostquoteprices', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		$tot=0;
        $mktcostquoteprices=array();
	    $rows=$this->mktcostquoteprice->where([['mkt_cost_id','=',request('mkt_cost_id',0)]])->get();
  		foreach($rows as $row){
        $mktcostquoteprice['id']=	$row->id;
        $mktcostquoteprice['quote_price']= $row->quote_price;
        $mktcostquoteprice['qprice_date']=	$row->qprice_date;
        $mktcostquoteprice['submission_date']=  $row->submission_date;
        $mktcostquoteprice['confirm_date']=  $row->confirm_date;
        $mktcostquoteprice['refused_date']=  $row->refused_date;
        $mktcostquoteprice['cancel_date']=	$row->cancel_date;
		$tot+=$row->amount;
  		   array_push($mktcostquoteprices,$mktcostquoteprice);
  		}
		$dd=array('total'=>1,'rows'=>$mktcostquoteprices,'footer'=>array(0=>array('id'=>'','price_date'=>'Total','quote_price'=>$tot)));
        echo json_encode($dd);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return Template::loadView('Marketing.mktcostquoteprice', ['mktcost'=>$mktcost]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MktCostQuotePriceRequest $request) {
        //$mktcostquoteprice = $this->mktcostquoteprice->create($request->except(['id']));
        $approved=$this->mktcost->find($request->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }

		$mktcostquoteprice = $this->mktcostquoteprice->updateOrCreate(
				['mkt_cost_id' => $request->mkt_cost_id],['qprice_date' => $request->qprice_date,'submission_date' => $request->submission_date,'confirm_date' => $request->confirm_date,'refused_date' => $request->refused_date,'cancel_date' => $request->cancel_date,'quote_price' => $request->quote_price]
				);


		$totalPriceBeforeCommission=$this->mktcost->totalPriceBeforeCommission($request->mkt_cost_id);
        if ($mktcostquoteprice) {
            $sms=0;
             if($request->submission_date){
                $text=$this->mktcost->smsData($request->mkt_cost_id);
                //$sms=Sms::send_sms($text, '8801711563231,8801713043117,8801781738866');
                $sms=Sms::send_sms($text, '8801711563231,8801713043117,8801781738866,8801730595836');
             }

             if($request->confirm_date){
                $text=$this->mktcost->smsData($request->mkt_cost_id);
                //$sms=Sms::send_sms($text, '8801711563231,8801713043117,8801781738866');
                $sms=Sms::send_sms($text, '8801711563231,8801713043117,8801781738866,8801730595836');
             }

            return response()->json(array('success' => true, 'id' => $mktcostquoteprice->id,'sms' => $sms, 'message' => 'Save Successfully','price_before_commission' => $totalPriceBeforeCommission), 200);
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
        $mktcostquoteprice = $this->mktcostquoteprice->find($id);
        $row ['fromData'] = $mktcostquoteprice;
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
    public function update(MktCostQuotePriceRequest $request, $id) {
        $approved=$this->mktcost->find($request->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        $old= $this->mktcostquoteprice
        ->where([['mkt_cost_id','=',$request->mkt_cost_id]])
        ->where([['quote_price','=',$request->quote_price]])
        ->where([['submission_date','=',$request->submission_date]])
        ->get();

        $mktcostquoteprice = $this->mktcostquoteprice->update($id, $request->except(['id']));
		$totalPriceBeforeCommission=$this->mktcost->totalPriceBeforeCommission($request->mkt_cost_id);
        if ($mktcostquoteprice) {
             $sms=0;
             if(!$old->count() && $request->submission_date){
                $text=$this->mktcost->smsData($request->mkt_cost_id);
                $sms=Sms::send_sms($text, '8801711563231,8801713043117,8801781738866,8801730595836');
                //$sms=Sms::send_sms($text, '8801913955201');

             }

             if($request->confirm_date){
                $text=$this->mktcost->smsData($request->mkt_cost_id);
                $sms=Sms::send_sms($text, '8801711563231,8801713043117,8801781738866,8801730595836');
                //$sms=Sms::send_sms($text, '8801913955201');
             }
            return response()->json(array('success' => true, 'id' => $id, 'sms' => $sms, 'message' => 'Update Successfully','price_before_commission' => $totalPriceBeforeCommission), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $mktcostquoteprice=$this->mktcostquoteprice->find($id);
        $approved=$this->mktcost->find($mktcostquoteprice->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        if ($this->mktcostquoteprice->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
