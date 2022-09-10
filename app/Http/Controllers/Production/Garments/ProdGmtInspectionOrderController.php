<?php

namespace App\Http\Controllers\Production\Garments;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Production\Garments\ProdGmtInspectionOrderRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Library\Template;

use App\Http\Requests\Production\Garments\ProdGmtInspectionOrderRequest;

class ProdGmtInspectionOrderController extends Controller {

    private $company;
    private $inspectionorder;

    public function __construct(ProdGmtInspectionOrderRepository $inspectionorder, SalesOrderGmtColorSizeRepository $salesordergmtcolorsize) {
        $this->inspectionorder = $inspectionorder;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
        $this->middleware('auth');
            /*$this->middleware('permission:view.prodgmtinspectionorders',   ['only' => ['create', 'index','show']]);
            $this->middleware('permission:create.prodgmtinspectionorders', ['only' => ['store']]);
            $this->middleware('permission:edit.prodgmtinspectionorders',   ['only' => ['update']]);
            $this->middleware('permission:delete.prodgmtinspectionorders', ['only' => ['destroy']]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		//
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      //  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdGmtInspectionOrderRequest $request) {
        foreach($request->sales_order_gmt_color_size_id as $index=>$sales_order_gmt_color_size_id){
            if($sales_order_gmt_color_size_id && $request->qty[$index] || $request->failed_qty[$index] || $request->re_check_qty[$index])
            {
                $inspectionorder = $this->inspectionorder->updateOrCreate(
                [
                    'sales_order_gmt_color_size_id' => $sales_order_gmt_color_size_id,
                    'prod_gmt_inspection_id' => $request->prod_gmt_inspection_id
                ],
                [
                    'qty' => $request->qty[$index],
                    're_check_qty' => $request->re_check_qty[$index],
                    'failed_qty' => $request->failed_qty[$index],
                    're_check_remarks' => $request->re_check_remarks[$index],
                    'failed_remarks' => $request->failed_remarks[$index],
                    'expected_exfactory_date' => $request->expected_exfactory_date[$index],
                    'exfactory_qty' => $request->exfactory_qty[$index],
                ]);
            }
        }

        if($inspectionorder){
            return response()->json(array('success' => true,'id' =>  $inspectionorder->id,'message' => 'Save Successfully'),200);
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
        $inspectionorder = $this->inspectionorder->find($id);
        $row ['fromData'] = $inspectionorder;
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
    public function update(ProdGmtInspectionOrderRequest $request, $id) {
        $inspectionorder=$this->inspectionorder->update($id,$request->except(['id']));
        if($inspectionorder){
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
        if($this->inspectionorder->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

}