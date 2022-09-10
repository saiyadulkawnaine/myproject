<?php
namespace App\Http\Controllers\Commercial\CashIncentive;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRefRepository;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveYarnBtbLcRepository;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Library\Template;
use App\Http\Requests\Commercial\CashIncentive\CashIncentiveYarnBtbLcRequest;






class CashIncentiveYarnBtbLcController extends Controller {

    private $incentiveyarnbtblc;
    private $cashincentiveref;
    private $supplier;
    private $implc;
    private $itemaccount;

    public function __construct(CashIncentiveYarnBtbLcRepository $incentiveyarnbtblc,SupplierRepository $supplier,ImpLcRepository $implc,ItemAccountRepository $itemaccount,CashIncentiveRefRepository $cashincentiveref) {
        $this->incentiveyarnbtblc = $incentiveyarnbtblc;
        $this->cashincentiveref = $cashincentiveref;
        $this->supplier = $supplier;
        $this->implc = $implc;
        $this->itemaccount = $itemaccount;

        $this->middleware('auth');

        $this->middleware('permission:view.cashincentiveyarnbtblcs',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.cashincentiveyarnbtblcs', ['only' => ['store']]);
        $this->middleware('permission:edit.cashincentiveyarnbtblcs',   ['only' => ['update']]);
        $this->middleware('permission:delete.cashincentiveyarnbtblcs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $yarnDescription=$this->itemaccount
        ->join('item_account_ratios',function($join){
        $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
        })
        ->join('yarncounts',function($join){
        $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->join('yarntypes',function($join){
        $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->join('itemclasses',function($join){
        $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->join('compositions',function($join){
        $join->on('compositions.id','=','item_account_ratios.composition_id');
        })
        ->join('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        //->where([['itemcategories.identity','=',1]])
        ->get([
            'item_accounts.id',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'itemclasses.name as itemclass_name',
            'compositions.name as composition_name',
            'item_account_ratios.ratio',
        ]);
        $itemaccountArr=array();
        $yarnCompositionArr=array();
        foreach($yarnDescription as $row){
            $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
            $itemaccountArr[$row->id]['yarn_type']=$row->yarn_type;
            //$itemaccountArr[$row->id]['itemclass_name']=$row->itemclass_name;
            $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $yarnDropdown=array();
        foreach($itemaccountArr as $key=>$value){
            $yarnDropdown[$key]=/* $value['itemclass_name']." ". */$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
        }
         
        //$incentiveyarnbtblcs=array();
        $rows=$this->incentiveyarnbtblc
            ->join('imp_lcs', function($join)  {
                $join->on('imp_lcs.id', '=', 'cash_incentive_yarn_btb_lcs.imp_lc_id');
            })
            ->join('po_yarn_items', function($join)  {
                $join->on('po_yarn_items.id', '=', 'cash_incentive_yarn_btb_lcs.po_yarn_item_id');
            })
            ->join('item_accounts', function($join){
                $join->on('item_accounts.id', '=', 'po_yarn_items.item_account_id');
            })
            ->join('itemclasses', function($join){
                $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
            })
            ->join('itemcategories', function($join){
                $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
            })
            ->join('suppliers', function($join)  {
                $join->on('suppliers.id', '=', 'imp_lcs.supplier_id');
            })
            ->leftJoin(\DB::raw("
            (
            Select 
                imp_lcs.id as imp_lc_id,
                po_yarn_items.id as po_yarn_item_id,
                sum(cash_incentive_yarn_btb_lcs.consumed_qty) as cumulative_qty
                from cash_incentive_yarn_btb_lcs
                right join imp_lcs on imp_lcs.id=cash_incentive_yarn_btb_lcs.imp_lc_id
                right join po_yarn_items on po_yarn_items.id=cash_incentive_yarn_btb_lcs.po_yarn_item_id
                where imp_lcs.deleted_at is null
    			and po_yarn_items.deleted_at is null
                group by 
                imp_lcs.id,
                po_yarn_items.id
            ) cumulatives"), "cumulatives.po_yarn_item_id","=","po_yarn_items.id")
            ->where([['cash_incentive_ref_id','=',request('cash_incentive_ref_id',0)]])
            ->orderBy('cash_incentive_yarn_btb_lcs.id','desc')
            ->get([
                'cash_incentive_yarn_btb_lcs.*',
                'imp_lcs.lc_no_i',
                'imp_lcs.lc_no_ii',
                'imp_lcs.lc_no_iii',
                'imp_lcs.lc_no_iv',
                'po_yarn_items.item_account_id',
                'po_yarn_items.qty as lc_yarn_qty',
                'po_yarn_items.rate',
                'po_yarn_items.amount as lc_yarn_amount',
                'suppliers.name as supplier_name',
                'cumulatives.cumulative_qty'
            ])
            ->map(function($rows) use($yarnDropdown){
                $rows->lc_no=$rows->lc_no_i."".$rows->lc_no_ii."".$rows->lc_no_iii."".$rows->lc_no_iv;
                $rows->item_description = $yarnDropdown[$rows->item_account_id];
                $rows->prev_used_qty=$rows->cumulative_qty-$rows->consumed_qty;
                $rows->balance_qty=$rows->lc_yarn_qty-$rows->consumed_qty-$rows->prev_used_qty;
                $rows->consumed_qty=number_format($rows->consumed_qty,2);
                $rows->comsumed_amount=number_format($rows->comsumed_amount,2);
                $rows->lc_yarn_qty=number_format($rows->lc_yarn_qty,2);
                $rows->lc_yarn_amount=number_format($rows->lc_yarn_amount,2);
                $rows->prev_used_qty=number_format($rows->prev_used_qty,2);
                $rows->balance_qty=number_format($rows->balance_qty,2);
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
        //   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CashIncentiveYarnBtbLcRequest $request) {

        $incentiveyarnbtblc=$this->incentiveyarnbtblc->create([
            'cash_incentive_ref_id'=>$request->cash_incentive_ref_id,
            'imp_lc_id'=>$request->imp_lc_id,
            'po_yarn_item_id'=>$request->po_yarn_item_id,
            'consumed_qty'=>$request->consumed_qty,
            'comsumed_amount'=>$request->comsumed_amount,
            'remarks'=>$request->remarks
        ]);
        
        $totalQty=$this->incentiveyarnbtblc
        ->join('cash_incentive_refs', function($join)  {
            $join->on('cash_incentive_yarn_btb_lcs.cash_incentive_ref_id','=','cash_incentive_refs.id');
        })
        ->where([['cash_incentive_refs.id','=',$request->cash_incentive_ref_id]])
        ->get([
            'cash_incentive_yarn_btb_lcs.consumed_qty',
            'cash_incentive_yarn_btb_lcs.comsumed_amount',
            ]);
        $qty=$totalQty->sum('consumed_qty');
        $amount=$totalQty->sum('comsumed_amount');
        $avg_rate=$amount/$qty;


        $this->cashincentiveref->where([['id','=',$request->cash_incentive_ref_id]])->update(['avg_rate'=>$avg_rate]);

        if($incentiveyarnbtblc){
            return response()->json(array('success' => true,'id' =>  $incentiveyarnbtblc->id,'message' => 'Save Successfully'),200);
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
        $yarnDescription=$this->itemaccount
        ->join('item_account_ratios',function($join){
        $join->on('item_account_ratios.item_account_id','=','item_accounts.id');
        })
        ->join('yarncounts',function($join){
        $join->on('yarncounts.id','=','item_accounts.yarncount_id');
        })
        ->join('yarntypes',function($join){
        $join->on('yarntypes.id','=','item_accounts.yarntype_id');
        })
        ->join('itemclasses',function($join){
        $join->on('itemclasses.id','=','item_accounts.itemclass_id');
        })
        ->join('compositions',function($join){
        $join->on('compositions.id','=','item_account_ratios.composition_id');
        })
        ->join('itemcategories',function($join){
        $join->on('itemcategories.id','=','item_accounts.itemcategory_id');
        })
        //->where([['itemcategories.identity','=',1]])
        ->get([
        'item_accounts.id',
        'yarncounts.count',
        'yarncounts.symbol',
        'yarntypes.name as yarn_type',
        'itemclasses.name as itemclass_name',
        'compositions.name as composition_name',
        'item_account_ratios.ratio',
        ]);
        $itemaccountArr=array();
        $yarnCompositionArr=array();
        foreach($yarnDescription as $row){
            $itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
            $itemaccountArr[$row->id]['yarn_type']=$row->yarn_type;
            //$itemaccountArr[$row->id]['itemclass_name']=$row->itemclass_name;
            $yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
        }
        $yarnDropdown=array();
        foreach($itemaccountArr as $key=>$value){
            $yarnDropdown[$key]=/* $value['itemclass_name']." ". */$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
        }
         
       $incentiveyarnbtblc = $this->incentiveyarnbtblc
       ->join('imp_lcs', function($join)  {
        $join->on('imp_lcs.id', '=', 'cash_incentive_yarn_btb_lcs.imp_lc_id');
        })
        ->join('po_yarn_items', function($join)  {
            $join->on('po_yarn_items.id', '=', 'cash_incentive_yarn_btb_lcs.po_yarn_item_id');
        })
        ->join('item_accounts', function($join){
            $join->on('item_accounts.id', '=', 'po_yarn_items.item_account_id');
        })
        ->join('itemclasses', function($join){
            $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
        })
        ->join('itemcategories', function($join){
            $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
        })
        ->join('suppliers', function($join)  {
            $join->on('suppliers.id', '=', 'imp_lcs.supplier_id');
        })
        ->leftJoin(\DB::raw("
            (
            Select 
                imp_lcs.id as imp_lc_id,
                po_yarn_items.id as po_yarn_item_id,
                sum(cash_incentive_yarn_btb_lcs.consumed_qty) as cumulative_qty
                from cash_incentive_yarn_btb_lcs
                right join imp_lcs on imp_lcs.id=cash_incentive_yarn_btb_lcs.imp_lc_id
                right join po_yarn_items on po_yarn_items.id=cash_incentive_yarn_btb_lcs.po_yarn_item_id
                where imp_lcs.deleted_at is null
    			and po_yarn_items.deleted_at is null
                group by 
                imp_lcs.id,
                po_yarn_items.id
            ) cumulatives"), "cumulatives.po_yarn_item_id","=","po_yarn_items.id")
       ->where([['cash_incentive_yarn_btb_lcs.id','=',$id]])
       ->get([
        'cash_incentive_yarn_btb_lcs.*',
        'imp_lcs.lc_no_i',
        'imp_lcs.lc_no_ii',
        'imp_lcs.lc_no_iii',
        'imp_lcs.lc_no_iv',
        'imp_lcs.lc_date',
        'po_yarn_items.item_account_id',
        'po_yarn_items.qty as lc_yarn_qty',
        'po_yarn_items.rate',
        'po_yarn_items.amount as lc_yarn_amount',
        'suppliers.name as supplier_name',
        'cumulatives.cumulative_qty'
       ])
       ->map(function($incentiveyarnbtblc) use($yarnDropdown){
            //$incentiveyarnbtblc->comsumed_amount=$incentiveyarnbtblc->comsumed_amount;
            $incentiveyarnbtblc->lc_no=$incentiveyarnbtblc->lc_no_i."".$incentiveyarnbtblc->lc_no_ii."".$incentiveyarnbtblc->lc_no_iii."".$incentiveyarnbtblc->lc_no_iv;
            $incentiveyarnbtblc->item_description = $yarnDropdown[$incentiveyarnbtblc->item_account_id];
            $incentiveyarnbtblc->lc_date=date('d-M-Y',strtotime($incentiveyarnbtblc->lc_date));
            $incentiveyarnbtblc->prev_used_qty=$incentiveyarnbtblc->cumulative_qty-$incentiveyarnbtblc->consumed_qty;
            $incentiveyarnbtblc->balance_qty=$incentiveyarnbtblc->lc_yarn_qty-($incentiveyarnbtblc->consumed_qty-$incentiveyarnbtblc->prev_used_qty);
            return $incentiveyarnbtblc;
        })
       ->first();
       $row ['fromData'] = $incentiveyarnbtblc;
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
    public function update(CashIncentiveYarnBtbLcRequest $request, $id) {
        $incentiveyarnbtblc=$this->incentiveyarnbtblc->update($id,[
            'cash_incentive_ref_id'=>$request->cash_incentive_ref_id,
            'imp_lc_id'=>$request->imp_lc_id,
            'po_yarn_item_id'=>$request->po_yarn_item_id,
            'consumed_qty'=>$request->consumed_qty,
            'comsumed_amount'=>$request->comsumed_amount,
            'remarks'=>$request->remarks
        ]);
        
        $totalQty=$this->incentiveyarnbtblc
        ->join('cash_incentive_refs', function($join)  {
            $join->on('cash_incentive_yarn_btb_lcs.cash_incentive_ref_id','=','cash_incentive_refs.id');
        })
        ->where([['cash_incentive_refs.id','=',$request->cash_incentive_ref_id]])
        ->get([
            'cash_incentive_yarn_btb_lcs.consumed_qty',
            'cash_incentive_yarn_btb_lcs.comsumed_amount',
        ]);
        $qty=$totalQty->sum('consumed_qty');
        $amount=$totalQty->sum('comsumed_amount');
        $avg_rate=$amount/$qty;

        $this->cashincentiveref->where([['id','=',$request->cash_incentive_ref_id]])->update(['avg_rate'=>$avg_rate]);

        if($incentiveyarnbtblc){
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
        if($this->incentiveyarnbtblc->delete($id)){
            return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
        }
    }

    public function getBtbImpLc(){
        $lctype = array_prepend(config('bprs.lctype'), '-Select-','');
        $payterm = array_prepend(config('bprs.payterm'), '-Select-','');
         
        $implcs=array();
        $rows=$this->implc
        ->join('companies', function($join)  {
            $join->on('companies.id', '=', 'imp_lcs.company_id');
        })
        ->join('suppliers', function($join)  {
            $join->on('suppliers.id', '=', 'imp_lcs.supplier_id');
        })
       ->join('bank_branches', function($join)  {
            $join->on('bank_branches.id', '=', 'imp_lcs.issuing_bank_branch_id');
        })
       ->join('banks', function($join)  {
            $join->on('banks.id', '=', 'bank_branches.bank_id');
        })
        ->when(request('company_id'), function ($q) {
            return $q->where('imp_lcs.company_id', '=', request('company_id', 0));
           })
        ->when(request('supplier_id'), function ($q) {
            return $q->where('imp_lcs.supplier_id', '=', request('supplier_id', 0));
           })
        ->when(request('last_delilvery_date'), function ($q) {
            return $q->where('imp_lcs.last_delilvery_date', 'like','%'. request('last_delilvery_date', 0)).'%';
        })
        /*
        ->when(request('issuing_bank_branch_id'), function ($q) {
            return $q->where('imp_lcs.issuing_bank_branch_id', '=', request('issuing_bank_branch_id', 0));
           })
        */
        ->where([['imp_lcs.lc_type_id','=',1]])
        ->where([['imp_lcs.menu_id','=',3]])
        ->get([
            'imp_lcs.*',
           // 'imp_lcs.id as imp_lc_id',
            'companies.name as company_name',
            'suppliers.name as supplier_name',
            'bank_branches.branch_name',
            'banks.name as bank_name',
        ]);
        foreach($rows as $row){
            $implc['id']=$row->id;
            $implc['company_name'] = $row->company_name;
            $implc['supplier_name']= $row->supplier_name;
            $implc['issuing_bank_branch_id']=$row->bank_name."(".$row->branch_name.")";
            $implc['lc_type_id']=  $lctype[$row->lc_type_id];
            $implc['last_delilvery_date']=date('d-M-Y',strtotime($row->last_delilvery_date));
            $implc['lc_sc_date']=date('d-M-Y',strtotime($row->lc_sc_date));
            $implc['expiry_date']=date('d-M-Y',strtotime($row->expiry_date));
            $implc['lc_no']=$row->lc_no_i."".$row->lc_no_ii."".$row->lc_no_iii."".$row->lc_no_iv;
            $implc['pay_term_id']=$payterm[$row->pay_term_id];
            $implc['exch_rate']=$row->exch_rate;
            $implc['tenor']=$row->tenor;
            
            array_push($implcs,$implc);
        }
        echo json_encode($implcs);
    }

     public function getYarnBtpItemDesc(){

        $yarnDescription=$this->itemaccount
		->join('item_account_ratios',function($join){
		$join->on('item_account_ratios.item_account_id','=','item_accounts.id');
		})
		->join('yarncounts',function($join){
		$join->on('yarncounts.id','=','item_accounts.yarncount_id');
		})
		->join('yarntypes',function($join){
		$join->on('yarntypes.id','=','item_accounts.yarntype_id');
		})
		->join('itemclasses',function($join){
		$join->on('itemclasses.id','=','item_accounts.itemclass_id');
		})
		->join('compositions',function($join){
		$join->on('compositions.id','=','item_account_ratios.composition_id');
		})
		->join('itemcategories',function($join){
      	$join->on('itemcategories.id','=','item_accounts.itemcategory_id');
      	})
	    ->where([['itemcategories.identity','=',1]])
		->get([
            'item_accounts.id',
            'yarncounts.count',
            'yarncounts.symbol',
            'yarntypes.name as yarn_type',
            'itemclasses.name as itemclass_name',
            'compositions.name as composition_name',
            'item_account_ratios.ratio',
		]);
		$itemaccountArr=array();
		$yarnCompositionArr=array();
		foreach($yarnDescription as $row){
		$itemaccountArr[$row->id]['count']=$row->count."/".$row->symbol;
		$itemaccountArr[$row->id]['yarn_type']=$row->yarn_type;
		$itemaccountArr[$row->id]['itemclass_name']=$row->itemclass_name;
		$yarnCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
		}
		$yarnDropdown=array();
		foreach($itemaccountArr as $key=>$value){
		$yarnDropdown[$key]=$value['itemclass_name']." ".$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
		}

        $paymode=config('bprs.paymode');
        $implcid=request('imp_lc_id',0 );
        $data=$this->implc
        ->selectRaw(
        '
          po_yarns.company_id,
          po_yarns.supplier_id,
          po_yarns.currency_id,
          po_yarn_items.id as po_yarn_item_id,
          po_yarn_items.po_yarn_id,
          po_yarn_items.item_account_id,
          po_yarn_items.remarks as item_remarks,
          po_yarn_items.qty as lc_yarn_qty,
          po_yarn_items.rate,
          po_yarn_items.amount as lc_yarn_amount,
          uoms.code as uom_code,
          companies.name as company_name,
          currencies.code as currency_name,
          suppliers.name as supplier_name
        '
        )
        ->join('imp_lc_pos',function($join){
            $join->on('imp_lcs.id','=','imp_lc_pos.imp_lc_id');
        })
        ->join('po_yarns',function($join){
            $join->on('imp_lc_pos.purchase_order_id','=','po_yarns.id');
        })
        ->join('po_yarn_items', function($join){
            $join->on('po_yarn_items.po_yarn_id', '=', 'po_yarns.id')
            ->whereNull('po_yarn_items.deleted_at');
        })
        ->join('item_accounts', function($join){
            $join->on('item_accounts.id', '=', 'po_yarn_items.item_account_id');
        })
        ->join('itemclasses', function($join){
            $join->on('itemclasses.id', '=', 'item_accounts.itemclass_id');
        })
        ->join('itemcategories', function($join){
            $join->on('itemcategories.id', '=', 'itemclasses.itemcategory_id');
        })
        ->leftJoin('uoms', function($join){
            $join->on('uoms.id', '=', 'item_accounts.uom_id');
        })
        ->join('companies',function($join){
            $join->on('companies.id','=','po_yarns.company_id');
        })
        ->join('currencies',function($join){
        $join->on('currencies.id','=','po_yarns.currency_id');
        })
        ->join('suppliers',function($join){
        $join->on('suppliers.id','=','po_yarns.supplier_id');
        })
        ->where([['imp_lcs.id','=',$implcid]])
        ->groupBy([
          'po_yarn_items.amount',
          'po_yarns.company_id',
          'po_yarns.supplier_id',
          'po_yarns.currency_id',
          'po_yarn_items.id',
          'po_yarn_items.po_yarn_id',
          'po_yarn_items.item_account_id',
          'po_yarn_items.remarks',
          'po_yarn_items.qty',
          'po_yarn_items.rate',
          'uoms.code',
          'companies.name',
          'currencies.code',
          'suppliers.name',
          'suppliers.address', 
        ])
        ->get()
        ->map(function ($data) use($yarnDropdown) {
            $data->item_description = isset($yarnDropdown[$data->item_account_id])?$yarnDropdown[$data->item_account_id]:'';
            //$data->pay_mode=$paymode[$data->pay_mode];
            return $data;
        });
      //$amount=$data->sum('amount');
      echo json_encode($data);
    }
}
