<?php

namespace App\Http\Controllers\Commercial\Import;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Commercial\Import\ImpBackedExpLcScRepository;
use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\Import\ImpBackedExpLcScRequest;

class ImpBackedExpLcScController extends Controller {

    private $impbackexplcsc;
    private $explcsc;
    private $buyer;
    private $implc;

    public function __construct(ImpBackedExpLcScRepository $impbackexplcsc, ExpLcScRepository $explcsc, BuyerRepository $buyer,ImpLcRepository $implc, CompanyRepository $company) {
        $this->impbackexplcsc = $impbackexplcsc;
        $this->explcsc = $explcsc;
        $this->buyer = $buyer;
        $this->implc = $implc;

        $this->middleware('auth');
        $this->middleware('permission:view.impbackedexplcscs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.impbackedexplcscs', ['only' => ['store']]);
        $this->middleware('permission:edit.impbackedexplcscs',   ['only' => ['update']]);
        $this->middleware('permission:delete.impbackedexplcscs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       $explcsc =$this->explcsc
       ->selectRaw('
            exp_lc_scs.id as exp_lc_sc_id,
            exp_lc_scs.lc_sc_no ,
            exp_lc_scs.lc_sc_date ,
            exp_lc_scs.lc_sc_value ,
            exp_lc_scs.file_no ,
            buyers.code as buyer,
            companies.code as company,
            imp_backed_exp_lc_scs.exp_lc_sc_id,
            imp_backed_exp_lc_scs.id,
            imp_backed_exp_lc_scs.amount
        ')
       ->join('imp_backed_exp_lc_scs',function($join){
          $join->on('imp_backed_exp_lc_scs.exp_lc_sc_id','=','exp_lc_scs.id');
        })
       ->join('imp_lcs',function($join){
          $join->on('imp_lcs.id','=','imp_backed_exp_lc_scs.imp_lc_id');
        })
       ->join('companies', function($join)  {
        $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
        })
        ->join('buyers', function($join)  {
        $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
        })
       ->when(request('lc_sc_no'), function ($q) {
            return $q->where('exp_lc_scs.lc_sc_no', '=', request('lc_sc_no', 0));
        })
       ->where([['imp_lcs.id','=',request('imp_lc_id',0)]])
       ->get();

       echo json_encode($explcsc);
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
    public function store(ImpBackedExpLcScRequest $request) {
      /*$impbackexplcsc=$this->impbackexplcsc->create($request>except(['id']));
      if($impbackexplcsc){
         return response()->json(array('success'=>true,'id'=>$impbackexplcsc->id,'message'=>'Save Successfully'),200);
      }*/
      /*$imp_lc=$this->implc
        ->selectRaw('
        imp_lcs.id,
        sum(purchase_orders.amount) as lc_amount
        
        ')
        ->leftJoin('imp_lc_pos',function($join){
          $join->on('imp_lc_pos.imp_lc_id','=','imp_lcs.id');
        })
        ->leftJoin('purchase_orders',function($join){
          $join->on('purchase_orders.id','=','imp_lc_pos.purchase_order_id');
        })
        ->where([['imp_lcs.id','=',$request->imp_lc_id]])
        ->groupBy([
        'imp_lcs.id',
        ])
        ->orderBy('imp_lcs.id','desc')
        ->get()->first();*/

        $imp_lc = collect(\DB::select("
        select 
        imp_lcs.id,
        case when 
        imp_lcs.menu_id=1
        then sum(po_fabrics.amount)
        when 
        imp_lcs.menu_id=2
        then sum(po_trims.amount)
        when 
        imp_lcs.menu_id=3
        then sum(po_yarns.amount)
        when 
        imp_lcs.menu_id=4
        then sum(po_knit_services.amount)
        when 
        imp_lcs.menu_id=5
        then sum(po_aop_services.amount)
        when 
        imp_lcs.menu_id=6
        then sum(po_dyeing_services.amount)
        when 
        imp_lcs.menu_id=7
        then sum(po_dye_chems.amount)
        when 
        imp_lcs.menu_id=8
        then sum(po_generals.amount)
        when 
        imp_lcs.menu_id=9
        then sum(po_yarn_dyeings.amount)
        when 
        imp_lcs.menu_id=10
        then sum(po_emb_services.amount)
        when 
        imp_lcs.menu_id=11
        then sum(po_general_services.amount)
        else 0
        end as lc_amount
        from imp_lcs  
        left join imp_lc_pos on imp_lcs.id=imp_lc_pos.imp_lc_id
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
        where imp_lcs.id='".$request->imp_lc_id."' 
        group by 
        imp_lcs.id,
        imp_lcs.menu_id
        order by imp_lcs.id desc
        "
        ))
        ->first();


        foreach($request->exp_lc_sc_id as $index=>$exp_lc_sc_id){
            if($exp_lc_sc_id)
            {
                $lc_sc_value=$request->lc_sc_value[$index];
                $amount=($imp_lc->lc_amount/$request->total_lc_sc_value)*$lc_sc_value;
                $impbackexplcsc = $this->impbackexplcsc->create(
                ['exp_lc_sc_id' => $exp_lc_sc_id,'imp_lc_id' => $request->imp_lc_id,'amount' => $amount]);
            }
        }

        $results = \DB::select("
        select
        imp_lcs.id as imp_lc_id,
        imp_backed_exp_lc_scs.id,  
        exp_lc_scs.lc_sc_value,
        totlcscvalue.total_lc_sc_value
        from
        imp_lcs
        join imp_backed_exp_lc_scs on imp_backed_exp_lc_scs.imp_lc_id=imp_lcs.id
        join exp_lc_scs on exp_lc_scs.id=imp_backed_exp_lc_scs.exp_lc_sc_id
        left join(
        select
        imp_lcs.id as imp_lc_id,
        sum(exp_lc_scs.lc_sc_value) as total_lc_sc_value
        from 
        exp_lc_scs
        join imp_backed_exp_lc_scs on imp_backed_exp_lc_scs.exp_lc_sc_id=exp_lc_scs.id
        join imp_lcs on imp_lcs.id=imp_backed_exp_lc_scs.imp_lc_id
        where imp_lcs.id='".$request->imp_lc_id."'
        group by
        imp_lcs.id
        ) totlcscvalue on totlcscvalue.imp_lc_id=imp_lcs.id
        where imp_lcs.id='".$request->imp_lc_id."'");

        $data=collect($results);
        foreach($data as $row){
            $amount=($imp_lc->lc_amount/$row->total_lc_sc_value)*$row->lc_sc_value;
            $this->impbackexplcsc->update($row->id,['amount'=>$amount]);
        }

        if($impbackexplcsc){
            return response()->json(array('success' => true,'id' =>  $impbackexplcsc->id,'message' => 'Save Successfully'),200);
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
        $impbackexplcsc=$this->impbackexplcsc->find($id);
        $row['fromData']=$impbackexplcsc;
        $dropdown['att']='';
        $row['dropDown']=$dropdown;
        echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ImpBackedExpLcScRequest $request, $id) {
        $impbackexplcsc=$this->impbackexplcsc->update($id,$request->except(['id']));
        if($impbackexplcsc){
           return response()->json(array('success'=>true,'id'=>$id,'message'=>'Updated Successfully'),200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $importbackbylc=$this->impbackexplcsc->find($id);

        $approved=$this->implc->find($importbackbylc->imp_lc_id);
        if ($approved->approved_by) {
            return response()->json(array('success'=>false,'message'=>'Import Lc Approved.Delete Unsuccessfully'),200);
        }
        
        $last_delete_date=date('Y-m-d H:i:s');

        if($this->impbackexplcsc->delete($id)){
            $this->implc->where([['id','=',$importbackbylc->imp_lc_id]])->update(['last_untagged_lc_at'=>$last_delete_date]);
           return response()->json(array('success'=>true,'message'=>'Delete Successfully'),200);
        }
    }

    public function importlcsc ()
    {
       $implc=$this->implc->find(request('implcid',0));

       $explcsc =$this->explcsc
       ->selectRaw('
            exp_lc_scs.id ,
            exp_lc_scs.lc_sc_no ,
            exp_lc_scs.lc_sc_date ,
            exp_lc_scs.lc_sc_value ,
            exp_lc_scs.file_no ,
            buyers.code as buyer,
            companies.code as company,
            imp_backed_exp_lc_scs.exp_lc_sc_id
        ')
       ->leftJoin('imp_backed_exp_lc_scs',function($join) use($implc){
          $join->on('imp_backed_exp_lc_scs.imp_lc_id','=','exp_lc_scs.id');
          $join->where('imp_backed_exp_lc_scs.imp_lc_id','=',$implc->id);
        })
       ->join('companies', function($join)  {
        $join->on('companies.id', '=', 'exp_lc_scs.beneficiary_id');
        })
        ->join('buyers', function($join)  {
        $join->on('buyers.id', '=', 'exp_lc_scs.buyer_id');
        })
       ->when(request('lc_sc_no'), function ($q) {
            return $q->where('exp_lc_scs.lc_sc_no', '=', request('lc_sc_no', 0));
        })
       ->where([['exp_lc_scs.beneficiary_id','=',$implc->company_id]])
       ->where([['exp_lc_scs.currency_id','=',$implc->currency_id]])
       ->get();
       $notsaved = $explcsc->filter(function ($value) {
            if(!$value->exp_lc_sc_id){
                return $value;
            }
        })->values();
       echo json_encode($notsaved);
    }

}
