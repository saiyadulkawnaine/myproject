<?php

namespace App\Http\Controllers\Commercial\LocalExport;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpLcTagPiRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpPiRepository;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpLcRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\LocalExport\LocalExpLcTagPiRequest;

class LocalExpLcTagPiController extends Controller {

    private $localexplctagpi;
    private $localexplc;
    private $localexppi;

    public function __construct(LocalExpLcTagPiRepository $localexplctagpi,LocalExpPiRepository $localexppi,LocalExpLcRepository $localexplc) {
        $this->localexplctagpi = $localexplctagpi;
        $this->localexplc = $localexplc;
        $this->localexppi = $localexppi;

        $this->middleware('auth');
        // $this->middleware('permission:view.localexplctagpis',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.localexplctagpis', ['only' => ['store']]);
        // $this->middleware('permission:edit.localexplctagpis',   ['only' => ['update']]);
        // $this->middleware('permission:delete.localexplctagpis', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

            $localexppi =$this->localexplctagpi
            ->selectRaw('
            local_exp_lc_tag_pis.id,
            local_exp_lc_tag_pis.local_exp_pi_id,
            local_exp_lc_tag_pis.local_exp_lc_id,
            local_exp_pis.pi_no,
            customers.code as customer_code,
            salesorder_company.code as sales_company_code,
            sum(local_exp_pis.qty) as qty,
            sum(local_exp_pis.amount) as amount
            ')
            ->join('local_exp_pis', function($join)  {
            $join->on('local_exp_pis.id', '=', 'local_exp_lc_tag_pis.local_exp_pi_id');
            })
            /*->join('local_exp_pi_orders', function($join)  {
            $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
            })*/
            ->join('buyers as customers', function($join)  {
            $join->on('local_exp_pis.buyer_id', '=', 'customers.id');
            })
            ->join('companies as salesorder_company', function($join){
            $join->on('local_exp_pis.company_id','=','salesorder_company.id');
            })
            ->where([['local_exp_lc_tag_pis.local_exp_lc_id','=',request('local_exp_lc_id',0)]])
            ->groupBy([
            'local_exp_lc_tag_pis.id',
            'local_exp_lc_tag_pis.local_exp_pi_id',
            'local_exp_lc_tag_pis.local_exp_lc_id',
            'local_exp_pis.pi_no',
            'customers.code',
            'salesorder_company.code'
            ])
            ->get()
            ->map(function($localexppi){
            $localexppi->rate=0;
            if ($localexppi->qty) {
            $localexppi->rate=$localexppi->amount/$localexppi->qty;
            }

            return $localexppi;
            });
            echo json_encode($localexppi);
       
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
    public function store(LocalExpLcTagPiRequest $request) {
        foreach($request->local_exp_pi_id as $index=>$local_exp_pi_id){
            if($local_exp_pi_id)
            {
                $localexplctagpi = $this->localexplctagpi->create(
                ['local_exp_pi_id' => $local_exp_pi_id,'local_exp_lc_id' => $request->local_exp_lc_id]);
            }
        }
        if($localexplctagpi){
            return response()->json(array('success' => true,'id' =>  $localexplctagpi->id,'message' => 'Save Successfully'),200);
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
       $localexplctagpi=$this->localexplctagpi->find($id);
       $row['fromData']=$localexplctagpi;
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
    public function update(LocalExpLcTagPiRequest $request, $id) {
        $localexplctagpi=$this->localexplctagpi->update($id,$request->except(['id']));
        if($localexplctagpi){
            return response()->json(array('success'=>true,'id'=>$id,'message'=>'Update Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if($this->localexplctagpi->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function importLocalPi()
    {

       $localexplc=$this->localexplc->find(request('localexppiid',0));

       $localexppi =$this->localexppi
       ->selectRaw('
            local_exp_pis.id,
            local_exp_pis.pi_no,
            local_exp_lc_tag_pis.local_exp_pi_id,
            sum(local_exp_pis.qty) as qty,
            sum(local_exp_pis.amount) as amount
        ')
        /*->join('local_exp_pi_orders', function($join)  {
            $join->on('local_exp_pi_orders.local_exp_pi_id', '=', 'local_exp_pis.id');
        })*/
        ->leftJoin('local_exp_lc_tag_pis',function($join){
            $join->on('local_exp_lc_tag_pis.local_exp_pi_id','=','local_exp_pis.id');
        })
        ->when(request('pi_no'), function ($q) {
            return $q->where('local_exp_pis.pi_no', '=', request('pi_no', 0));
        })
        ->where([['local_exp_pis.company_id','=',$localexplc->beneficiary_id]])
        ->where([['local_exp_pis.buyer_id','=',$localexplc->buyer_id]])
        ->where([['local_exp_pis.production_area_id','=',$localexplc->production_area_id]])
        ->groupBy([
                'local_exp_pis.id',
                'local_exp_pis.pi_no',
                'local_exp_lc_tag_pis.local_exp_pi_id'
        ])
        ->get()
        ->map(function($localexppi){
                $localexppi->rate=0;
                if ($localexppi->qty) {
                    $localexppi->rate=$localexppi->amount/$localexppi->qty;
                }
                return $localexppi;
        });
       $notsaved = $localexppi->filter(function ($value) {
            if(!$value->local_exp_pi_id){
                return $value;
            }
        })->values();
       echo json_encode($notsaved);
        
    }

}
