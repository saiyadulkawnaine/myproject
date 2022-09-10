<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;


use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PurchaseOrderRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Library\Template;
use App\Http\Requests\Purchase\PurchaseOrderRequest;


class FabricServiceController extends Controller
{
   private $bulkfabricpurchase;
   private $company;
   private $supplier;
   private $currency;

	public function __construct(PurchaseOrderRepository $bulkfabricpurchase,CompanyRepository $company,SupplierRepository $supplier,CurrencyRepository $currency)
	{
        $this->bulkfabricpurchase = $bulkfabricpurchase;
		$this->company = $company;
		$this->supplier = $supplier;
		$this->currency = $currency;
		// $this->middleware('auth');
		// $this->middleware('permission:view.bulkfabricpurchases',   ['only' => ['create', 'index','show']]);
		// $this->middleware('permission:create.bulkfabricpurchases', ['only' => ['store']]);
		// $this->middleware('permission:edit.bulkfabricpurchases',   ['only' => ['update']]);
		// $this->middleware('permission:delete.bulkfabricpurchases', ['only' => ['destroy']]);
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $source = array_prepend(config('bprs.materialsourcing'),'-Select-','');
      $paymode=array_prepend(config('bprs.costpaymode'),'-Select-','');
      $bulkfabricpurchase=array();
      $bulkfabricpurchases=array();
	    $rows=$this->bulkfabricpurchase->where([['order_type_id', 6]])->get();
        foreach ($rows as $row) {
          $bulkfabricpurchase['id']=$row->id;
		      $bulkfabricpurchase['pur_order_no']=$row->pur_order_no;
          $bulkfabricpurchase['company']=$company[$row->company_id];
          $bulkfabricpurchase['source']=$source[$row->source_id];
          $bulkfabricpurchase['delv_start_date']=$row->delv_start_date;
          $bulkfabricpurchase['delv_end_date']=$row->delv_end_date;
          $bulkfabricpurchase['paymode']=$paymode[$row->pay_mode];
          array_push($bulkfabricpurchases,$bulkfabricpurchase);
        }
        echo json_encode($bulkfabricpurchases);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $company=array_prepend(array_pluck($this->company->get(),'name','id'),'-Select-','');
      $source = array_prepend(config('bprs.materialsourcing'),'-Select-','');
      $paymode=array_prepend(config('bprs.costpaymode'),'-Select-','');
      $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
      $currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
          return Template::loadView("Purchase.FabricService", ['company'=>$company,'source'=>$source,'supplier'=>$supplier,'currency'=>$currency,'paymode'=>$paymode,'order_type_id'=>6]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PurchaseOrderRequest $request)
    {
      $max = $this->bulkfabricpurchase->where([['company_id', $request->company_id]])->where([['order_type_id', $request->order_type_id]])->max('pur_order_no');
      $pur_order_no=$max+1;
      $bulkfabricpurchase = $this->bulkfabricpurchase->create(['pur_order_no'=>$pur_order_no,'order_type_id'=>$request->order_type_id,'company_id'=>$request->company_id,'source_id'=>$request->source_id,'basis_id'=>$request->basis_id,'supplier_id'=>$request->supplier_id,'currency_id'=>$request->currency_id,'exch_rate'=>$request->exch_rate,'delv_start_date'=>$request->delv_start_date,'delv_end_date'=>$request->delv_end_date,'pay_mode'=>$request->pay_mode,'pi_no'=>$request->pi_no,'pi_date'=>$request->pi_date,'remarks'=>$request->remarks]);

      if ($bulkfabricpurchase) {
      return response()->json(array('success' => true, 'id' => $bulkfabricpurchase->id, 'message' => 'Save Successfully'), 200);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $bulkfabricpurchase = $this->bulkfabricpurchase->find($id);
      $row ['fromData'] = $bulkfabricpurchase;
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
    public function update(PurchaseOrderRequest $request, $id)
    {
      $bulkfabricpurchase = $this->bulkfabricpurchase->update($id, $request->except(['id']));
      if ($bulkfabricpurchase) {
          return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->bulkfabricpurchase->delete($id)){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
    }
}
