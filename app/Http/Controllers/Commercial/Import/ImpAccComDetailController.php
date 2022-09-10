<?php

namespace App\Http\Controllers\Commercial\Import;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\Import\ImpDocAcceptRepository;
use App\Repositories\Contracts\Commercial\Import\ImpAccComDetailRepository;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Import\ImpAccComDetailRequest;

class ImpAccComDetailController extends Controller {

    private $impdocaccept;
    private $acccomdetail;
    private $commercialhead;

    public function __construct(ImpDocAcceptRepository $impdocaccept, ImpAccComDetailRepository $acccomdetail, CommercialHeadRepository $commercialhead) {
        
        $this->impdocaccept = $impdocaccept;
        $this->acccomdetail = $acccomdetail;
        $this->commercialhead = $commercialhead;
        $this->middleware('auth');
        $this->middleware('permission:view.impacccomdetails',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.impacccomdetails', ['only' => ['store']]);
        $this->middleware('permission:edit.impacccomdetails',   ['only' => ['update']]);
        $this->middleware('permission:delete.impacccomdetails', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $acccomdetails=array();
        $rows=$this->acccomdetail
        ->where([['imp_doc_accept_id','=',request('imp_doc_accept_id',0)]])
        ->get();
        foreach($rows as $row){
            $acccomdetail['id']=$row->id;
            $acccomdetail['acceptance_value']=$row->acceptance_value;
            $acccomdetail['imp_doc_accept_id']=$row->imp_doc_accept_id;
            array_push($acccomdetails,$acccomdetail);
        }
        echo json_encode($acccomdetails);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $paymode = array_prepend(config('bprs.paymode'), '-Select-',''); 

        /*$impdocaccept=$this->impdocaccept
        ->selectRaw('purchase_orders.id,
        purchase_orders.pur_order_no,
        purchase_orders.itemcategory_id,
        purchase_orders.currency_id,
        purchase_orders.company_id,
        purchase_orders.pi_no,
        purchase_orders.pay_mode,
        purchase_orders.amount,
        companies.code as company_name,
        currencies.code as currency_name,
        suppliers.code as supplier_name,
        itemcategories.name as itemcategory,

        imp_lc_pos.purchase_order_id,
        imp_lc_pos.id as imp_lc_po_id,
        imp_doc_accepts.id as imp_doc_accept_id,
        imp_acc_com_details.id as imp_acc_com_detail_id,
        imp_acc_com_details.acceptance_value,
        cumulatives.cumulative_qty')
        ->join('imp_lcs', function($join)  {
        $join->on('imp_lcs.id', '=', 'imp_doc_accepts.imp_lc_id');
        })
        ->join('imp_lc_pos', function($join)  {
        $join->on('imp_lc_pos.imp_lc_id', '=', 'imp_lcs.id');
        })
        ->join('purchase_orders', function($join)  {
            $join->on('purchase_orders.id', '=', 'imp_lc_pos.purchase_order_id');
        })
        ->leftJoin('companies',function($join){
            $join->on('companies.id','=','purchase_orders.company_id');
        })
        ->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','purchase_orders.supplier_id');
        })
        ->leftJoin('currencies',function($join){
            $join->on('currencies.id','=','purchase_orders.currency_id');
        })
        ->leftJoin('itemcategories',function($join){
            $join->on('itemcategories.id','=','purchase_orders.itemcategory_id');
        })

        ->leftJoin(\DB::raw("(SELECT imp_lc_pos.id as imp_lc_po_id,sum(imp_acc_com_details.acceptance_value) as cumulative_qty FROM imp_acc_com_details join imp_lc_pos on imp_lc_pos.id =imp_acc_com_details.imp_lc_po_id join imp_doc_accepts on  imp_doc_accepts.id=imp_acc_com_details.imp_doc_accept_id  group by imp_lc_pos.id) cumulatives"), "cumulatives.imp_lc_po_id", "=", "imp_lc_pos.id")
        ->leftJoin('imp_acc_com_details',function($join){
          $join->on('imp_acc_com_details.imp_lc_po_id','=','imp_lc_pos.id');
          $join->on('imp_acc_com_details.imp_doc_accept_id','=','imp_doc_accepts.id');
        })
        ->where([['imp_doc_accepts.id','=',request('imp_doc_accept_id',0)]])
        ->get()
        ->map(function ($impdocaccept) use($paymode){
        $impdocaccept->paymode=$paymode[$impdocaccept->pay_mode];
        return $impdocaccept;
        });*/

        $impdocaccept = collect(\DB::select("
        select 
        imp_lcs.menu_id,
        imp_lc_pos.id as imp_lc_po_id,
        imp_lc_pos.purchase_order_id,
        imp_doc_accepts.id as imp_doc_accept_id,
        imp_acc_com_details.id as imp_acc_com_detail_id,
        imp_acc_com_details.acceptance_value,
        cumulatives.cumulative_qty,

        case when 
        imp_lcs.menu_id=1
        then 'Fabric'
        when 
        imp_lcs.menu_id=2
        then 'Trim'
        when 
        imp_lcs.menu_id=3
        then 'Yarn'
        when 
        imp_lcs.menu_id=4
        then 'Knit Service'
        when 
        imp_lcs.menu_id=5
        then 'Aop Service'
        when 
        imp_lcs.menu_id=6
        then 'Dyeing Service'
        when 
        imp_lcs.menu_id=7
        then 'Dyes & Chem'
        when 
        imp_lcs.menu_id=8
        then 'Genaral'
        when 
        imp_lcs.menu_id=9
        then 'Yarn Dyeing'
        when 
        imp_lcs.menu_id=10
        then 'Embelishment'
        when
        imp_lcs.menu_id=11
        then 'General Service'
        else null
        end as itemcategory,

        case when 
        imp_lcs.menu_id=1
        then po_fabrics.pay_mode
        when 
        imp_lcs.menu_id=2
        then po_trims.pay_mode
        when 
        imp_lcs.menu_id=3
        then po_yarns.pay_mode
        when 
        imp_lcs.menu_id=4
        then po_knit_services.pay_mode
        when 
        imp_lcs.menu_id=5
        then po_aop_services.pay_mode
        when 
        imp_lcs.menu_id=6
        then po_dyeing_services.pay_mode
        when 
        imp_lcs.menu_id=7
        then po_dye_chems.pay_mode
        when 
        imp_lcs.menu_id=8
        then po_generals.pay_mode
        when 
        imp_lcs.menu_id=9
        then po_yarn_dyeings.pay_mode
        when 
        imp_lcs.menu_id=10
        then po_emb_services.pay_mode
        when 
        imp_lcs.menu_id=11
        then po_general_services.pay_mode
        else null
        end as pay_mode,

        case when 
        imp_lcs.menu_id=1
        then po_fabrics.pi_no
        when 
        imp_lcs.menu_id=2
        then po_trims.pi_no
        when 
        imp_lcs.menu_id=3
        then po_yarns.pi_no
        when 
        imp_lcs.menu_id=4
        then po_knit_services.pi_no
        when 
        imp_lcs.menu_id=5
        then po_aop_services.pi_no
        when 
        imp_lcs.menu_id=6
        then po_dyeing_services.pi_no
        when 
        imp_lcs.menu_id=7
        then po_dye_chems.pi_no
        when 
        imp_lcs.menu_id=8
        then po_generals.pi_no
        when 
        imp_lcs.menu_id=9
        then po_yarn_dyeings.pi_no
        when 
        imp_lcs.menu_id=10
        then po_emb_services.pi_no
        when 
        imp_lcs.menu_id=11
        then po_general_services.pi_no
        else null
        end as pi_no,
        case when 
        imp_lcs.menu_id=1
        then po_fabrics.id
        when 
        imp_lcs.menu_id=2
        then po_trims.id
        when 
        imp_lcs.menu_id=3
        then po_yarns.id
        when 
        imp_lcs.menu_id=4
        then po_knit_services.id
        when 
        imp_lcs.menu_id=5
        then po_aop_services.id
        when 
        imp_lcs.menu_id=6
        then po_dyeing_services.id
        when 
        imp_lcs.menu_id=7
        then po_dye_chems.id
        when 
        imp_lcs.menu_id=8
        then po_generals.id
        when 
        imp_lcs.menu_id=9
        then po_yarn_dyeings.id
        when 
        imp_lcs.menu_id=10
        then po_emb_services.id
        when 
        imp_lcs.menu_id=11
        then po_general_services.id
        else 0
        end as id,
        case when 
        imp_lcs.menu_id=1
        then po_fabrics.po_no
        when 
        imp_lcs.menu_id=2
        then po_trims.po_no
        when 
        imp_lcs.menu_id=3
        then po_yarns.po_no
        when 
        imp_lcs.menu_id=4
        then po_knit_services.po_no
        when 
        imp_lcs.menu_id=5
        then po_aop_services.po_no
        when 
        imp_lcs.menu_id=6
        then po_dyeing_services.po_no
        when 
        imp_lcs.menu_id=7
        then po_dye_chems.po_no
        when 
        imp_lcs.menu_id=8
        then po_generals.po_no
        when 
        imp_lcs.menu_id=9
        then po_yarn_dyeings.po_no
        when 
        imp_lcs.menu_id=10
        then po_emb_services.po_no
        when 
        imp_lcs.menu_id=11
        then po_general_services.po_no
        else 0
        end as po_no,
        case when 
        imp_lcs.menu_id=1
        then po_fabrics.amount
        when 
        imp_lcs.menu_id=2
        then po_trims.amount
        when 
        imp_lcs.menu_id=3
        then po_yarns.amount
        when 
        imp_lcs.menu_id=4
        then po_knit_services.amount
        when 
        imp_lcs.menu_id=5
        then po_aop_services.amount
        when 
        imp_lcs.menu_id=6
        then po_dyeing_services.amount
        when 
        imp_lcs.menu_id=7
        then po_dye_chems.amount
        when 
        imp_lcs.menu_id=8
        then po_generals.amount
        when 
        imp_lcs.menu_id=9
        then po_yarn_dyeings.amount
        when 
        imp_lcs.menu_id=10
        then po_emb_services.amount
        when 
        imp_lcs.menu_id=11
        then po_general_services.amount
        else 0
        end as amount
        from 
        imp_doc_accepts
        join imp_lcs on imp_doc_accepts.imp_lc_id=imp_lcs.id 
        join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id
        left join po_fabrics on po_fabrics.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=1
        left join po_trims on po_trims.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=2
        left join po_yarns on po_yarns.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=3
        left join po_knit_services on po_knit_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=4
        left join po_aop_services on po_aop_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=5
        left join po_dyeing_services on po_dyeing_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=6
        left join po_dye_chems on po_dye_chems.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=7
        left join po_generals on po_generals.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=8
        left join po_yarn_dyeings on po_yarn_dyeings.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=9
        left join po_emb_services on po_emb_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=10
        left join po_general_services on po_general_services.id=imp_lc_pos.purchase_order_id and imp_lcs.menu_id=11
        left join (
        SELECT imp_lc_pos.id as imp_lc_po_id,sum(imp_acc_com_details.acceptance_value) as cumulative_qty FROM imp_acc_com_details join imp_lc_pos on imp_lc_pos.id =imp_acc_com_details.imp_lc_po_id join imp_doc_accepts on  imp_doc_accepts.id=imp_acc_com_details.imp_doc_accept_id  group by imp_lc_pos.id
        ) cumulatives on cumulatives.imp_lc_po_id=imp_lc_pos.id
        left join imp_acc_com_details on imp_acc_com_details.imp_lc_po_id=imp_lc_pos.id and imp_acc_com_details.imp_doc_accept_id=imp_doc_accepts.id
        where imp_doc_accepts.id=".request('imp_doc_accept_id',0)."
        "
        ))
        ->map(function ($impdocaccept) use($paymode){
        $impdocaccept->paymode=$paymode[$impdocaccept->pay_mode];
        return $impdocaccept;
        });

        $saved = $impdocaccept->filter(function ($value) {
            if($value->imp_acc_com_detail_id){
                return $value;
            }
        });
        $new = $impdocaccept->filter(function ($value) {
            if(!$value->imp_acc_com_detail_id){
                return $value;
            }
        });

        return Template::LoadView('Commercial.Import.ImpDocAcceptCommodity',['impdocaccepts'=>$new,'saved'=>$saved]);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImpAccComDetailRequest $request) {
        /*$acccomdetail=$this->acccomdetail->create($request->except(['id']));
        if($acccomdetail){
            return response()->json(array('success' => true,'id' =>  $acccomdetail->id,'message' => 'Save Successfully'),200);
        }*/
        $impDocAcceptId=0;
        foreach($request->imp_lc_po_id as $index=>$imp_lc_po_id){
            $impDocAcceptId=$request->imp_doc_accept_id[$index];
            if($imp_lc_po_id && $request->acceptance_value[$index])
            {
                $acccomdetail = $this->acccomdetail->updateOrCreate(
                ['imp_lc_po_id' => $imp_lc_po_id,'imp_doc_accept_id' => $request->imp_doc_accept_id[$index]],['acceptance_value' => $request->acceptance_value[$index]]);
            }
        }
        if($acccomdetail){
            return response()->json(array('success' => true,'id' =>  $acccomdetail->id,'imp_doc_accept_id' =>  $impDocAcceptId,'message' => 'Save Successfully'),200);
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
       $acccomdetail = $this->acccomdetail->find($id);
       $row ['fromData'] = $acccomdetail;
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
    public function update(ImpAccComDetailRequest $request, $id) {
        $acccomdetail=$this->acccomdetail->update($id,$request->except(['id']));
        if($acccomdetail){
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
        if($this->acccomdetail->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }
}
