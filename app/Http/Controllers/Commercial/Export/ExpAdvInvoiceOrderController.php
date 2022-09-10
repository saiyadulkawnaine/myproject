<?php

namespace App\Http\Controllers\Commercial\Export;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Export\ExpAdvInvoiceOrderRepository;
use App\Repositories\Contracts\Commercial\Export\ExpAdvInvoiceRepository;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Export\ExpAdvInvoiceOrderRequest;

class ExpAdvInvoiceOrderController extends Controller {

    private $expadvinvoice;
    private $expadvinvoiceorder;
    private $location;

    public function __construct(ExpAdvInvoiceOrderRepository $expadvinvoiceorder, ExpAdvInvoiceRepository $expadvinvoice,LocationRepository $location){
        
        $this->expadvinvoice = $expadvinvoice;
        $this->expadvinvoiceorder = $expadvinvoiceorder;
        $this->location = $location;
        $this->middleware('auth');
        // $this->middleware('permission:view.expadvinvoiceorders',   ['only' => ['create', 'index','show']]);
        // $this->middleware('permission:create.expadvinvoiceorders', ['only' => ['store']]);
        // $this->middleware('permission:edit.expadvinvoiceorders',   ['only' => ['update']]);
        // $this->middleware('permission:delete.expadvinvoiceorders', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $expadvinvoiceorders=array();
        $rows=$this->expadvinvoiceorder
        ->where([['exp_adv_invoice_id','=',request('exp_adv_invoice_id',0)]])
        ->get();
        foreach($rows as $row){
            $expadvinvoiceorder['id']=$row->id;
            $expadvinvoiceorder['acceptance_value']=$row->acceptance_value;
            $expadvinvoiceorder['exp_adv_invoice_id']=$row->exp_adv_invoice_id;
            array_push($expadvinvoiceorders,$expadvinvoiceorder);
        }
        echo json_encode($expadvinvoiceorders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $productionsource = array_prepend(config('bprs.productionsource'), '-Select-',''); 
        
        $location=array_prepend(array_pluck($this->location->get(),'name','id'),'-Select-','');

        $impdocaccept=$this->expadvinvoice
        ->selectRaw('
            sales_orders.id,
            sales_orders.sale_order_no,
            sales_orders.ship_date,
            exp_pi_orders.sales_order_id,
            exp_adv_invoices.id as exp_adv_invoice_id,
            exp_pi_orders.id as exp_pi_order_id,
            exp_pi_orders.qty,
            exp_pi_orders.rate,
            exp_adv_invoice_orders.id as exp_adv_invoice_order_id,
            exp_adv_invoice_orders.qty as invoice_qty,
            exp_adv_invoice_orders.rate as invoice_rate,
            exp_adv_invoice_orders.amount as invoice_amount,
            exp_adv_invoice_orders.production_source_id,
            exp_adv_invoice_orders.location_id,
            exp_adv_invoice_orders.commodity,
            cumulatives.cumulative_amount,
            cumulatives.cumulative_qty,
            users.name as marchent
        ')
        ->join('exp_lc_scs', function($join)  {
            $join->on('exp_lc_scs.id', '=', 'exp_adv_invoices.exp_lc_sc_id');
        })
        ->leftJoin('exp_rep_lc_scs', function($join)  {
            $join->on('exp_rep_lc_scs.exp_lc_sc_id', '=', 'exp_lc_scs.id');
        })
        ->join('exp_lc_sc_pis', function($join) {
            $join->on('exp_lc_sc_pis.exp_lc_sc_id', '=', 'exp_lc_scs.id');
            $join->orOn('exp_lc_sc_pis.exp_lc_sc_id','=','exp_rep_lc_scs.replaced_lc_sc_id');
        })
        ->join('exp_pis', function($join)  {
            $join->on('exp_pis.id', '=', 'exp_lc_sc_pis.exp_pi_id');
        })
        ->join('exp_pi_orders', function($join)  {
            $join->on('exp_pi_orders.exp_pi_id', '=', 'exp_pis.id');
        })
        ->join('sales_orders', function($join)  {
            $join->on('sales_orders.id', '=', 'exp_pi_orders.sales_order_id');
        })
        ->join('jobs', function($join)  {
            $join->on('jobs.id', '=', 'sales_orders.job_id');
        })
        ->join('styles', function($join)  {
            $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->leftJoin('teammembers', function($join)  {
            $join->on('teammembers.id', '=', 'styles.teammember_id');
        })
        ->leftJoin('users', function($join)  {
            $join->on('users.id', '=', 'teammembers.user_id');
        })
        ->leftJoin(\DB::raw("(SELECT exp_pi_orders.id as exp_pi_order_id,sum(exp_adv_invoice_orders.qty) as cumulative_qty,sum(exp_adv_invoice_orders.amount) as cumulative_amount FROM exp_adv_invoice_orders join exp_pi_orders on exp_pi_orders.id =exp_adv_invoice_orders.exp_pi_order_id join exp_adv_invoices on  exp_adv_invoices.id=exp_adv_invoice_orders.exp_adv_invoice_id where exp_adv_invoice_orders.deleted_at is null  group by exp_pi_orders.id) cumulatives"), "cumulatives.exp_pi_order_id", "=", "exp_pi_orders.id")
        ->leftJoin('exp_adv_invoice_orders',function($join){
          $join->on('exp_adv_invoice_orders.exp_pi_order_id','=','exp_pi_orders.id');
          $join->on('exp_adv_invoice_orders.exp_adv_invoice_id','=','exp_adv_invoices.id');
          $join->whereNull('exp_adv_invoice_orders.deleted_at');
        })
        ->where([['exp_adv_invoices.id','=',request('exp_adv_invoice_id',0)]])
        ->groupBy([
            'sales_orders.id',
            'sales_orders.sale_order_no',
            'sales_orders.ship_date',
            'exp_pi_orders.sales_order_id',
            'exp_adv_invoices.id',
            'exp_pi_orders.id',
            'exp_pi_orders.qty',
            'exp_pi_orders.rate',
            'exp_adv_invoice_orders.id',
            'exp_adv_invoice_orders.qty',
            'exp_adv_invoice_orders.rate',
            'exp_adv_invoice_orders.amount',
            'exp_adv_invoice_orders.production_source_id',
            'exp_adv_invoice_orders.location_id',
            'exp_adv_invoice_orders.commodity',
            'cumulatives.cumulative_amount',
            'cumulatives.cumulative_qty',
            'users.name'
        ])
        ->get()
        ->map(function ($impdocaccept){
            $impdocaccept->ship_date=date('d-M-y',strtotime($impdocaccept->ship_date));
            return $impdocaccept;
        });

        $saved = $impdocaccept->filter(function ($value) {
            if($value->exp_adv_invoice_order_id){
                return $value;
            }
        });
        $new = $impdocaccept->filter(function ($value) {
            if(!$value->exp_adv_invoice_order_id){
                return $value;
            }
        });
        return Template::LoadView('Commercial.Export.ExpAdvInvoiceOrder',['impdocaccepts'=>$new,'saved'=>$saved,'productionsource'=>$productionsource,'location'=>$location]);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpAdvInvoiceOrderRequest $request) {
       
        $impDocAcceptId=0;
        foreach($request->exp_pi_order_id as $index=>$exp_pi_order_id){
            $expInvoiceId=$request->exp_adv_invoice_id[$index];
            if($exp_pi_order_id && $request->qty[$index])
            {
                $expadvinvoiceorder = $this->expadvinvoiceorder->updateOrCreate(
                [
                  'exp_pi_order_id' => $exp_pi_order_id,'exp_adv_invoice_id' => $request->exp_adv_invoice_id[$index]
                ],
                [
                  'qty' => $request->qty[$index],
                  'rate' => $request->rate[$index],
                  'amount' => $request->amount[$index],
                  'production_source_id' => $request->production_source_id[$index],
                  'location_id' => $request->location_id[$index],
                  'commodity' => $request->commodity[$index],
                ]);
            }
        }
        if($expadvinvoiceorder){
            return response()->json(array('success' => true,'id' =>  $expadvinvoiceorder->id,'exp_adv_invoice_id' =>  $expInvoiceId,'message' => 'Save Successfully'),200);
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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExpAdvInvoiceOrderRequest $request, $id) {
         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $expadvinvoiceorder=$this->expadvinvoiceorder->find($id);
        if($this->expadvinvoiceorder->delete($id)){
            return response()->json(array('success' => true, 'exp_adv_invoice_id' =>  $expadvinvoiceorder->exp_adv_invoice_id,'message' => 'Delete Successfully'),200);
        }
        else{
             return response()->json(array('success' => false, 'exp_adv_invoice_id' =>   $expadvinvoiceorder->exp_adv_invoice_id,  'message' => 'Delete Not Successfull Because Subsequent Entry Found'),  200);
        }
    }
}
