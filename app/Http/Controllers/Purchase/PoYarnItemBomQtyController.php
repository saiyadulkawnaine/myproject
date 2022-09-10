<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Library\Template;


use App\Repositories\Contracts\Purchase\PoYarnRepository;
use App\Repositories\Contracts\Purchase\PoYarnItemRepository;
use App\Repositories\Contracts\Purchase\PoYarnItemBomQtyRepository;
use App\Repositories\Contracts\Bom\BudgetYarnRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Http\Requests\Purchase\PoYarnItemBomQtyRequest;

class PoYarnItemBomQtyController extends Controller
{
   private $poyarn;
   private $poyarnitem;
   private $poyarnitembom;
   private $poyarnitembomqty;
   private $budgetyarn;
   private $itemaccount;

	public function __construct(
    PoYarnRepository $poyarn,
    PoYarnItemRepository $poyarnitem,
    PoYarnItemBomQtyRepository $poyarnitembomqty,
    BudgetYarnRepository $budgetyarn,
    ItemAccountRepository $itemaccount
  )
	{
        $this->poyarn       = $poyarn;
        $this->poyarnitem   = $poyarnitem;
    	$this->poyarnitembomqty      = $poyarnitembomqty;
        $this->budgetyarn      = $budgetyarn;
        $this->itemaccount     = $itemaccount;
        $this->middleware('auth');
        $this->middleware('permission:view.poyarnitembomqties',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.poyarnitembomqties', ['only' => ['store']]);
        $this->middleware('permission:edit.poyarnitembomqties',   ['only' => ['update']]);
        $this->middleware('permission:delete.poyarnitembomqties', ['only' => ['destroy']]);
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
        $rows=$this->poyarnitem
        ->selectRaw('
        budget_yarns.item_account_id, 
        budget_yarns.id as budget_yarn_id,
        budget_yarns.ratio,
        budget_yarns.rate as bom_rate,
        sales_orders.id as sale_order_id, 
        sales_orders.sale_order_no, 
        sales_orders.ship_date, 
        companies.name as company_name, 
        pcompanies.name as p_company_name, 
        styles.style_ref,
        buyers.code as buyer_name,
        po_yarn_items.id as po_yarn_item_id,
        po_yarn_items.rate as pur_yarn_rate,
        po_yarn_item_bom_qties.id,
        po_yarn_item_bom_qties.qty,
        po_yarn_item_bom_qties.rate,
        po_yarn_item_bom_qties.amount,
        cumulatives.cumulative_qty,
        sum (budget_fabric_cons.grey_fab) as grey_fab,
        sum (sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
        ')
        ->join('budget_yarns',function($join){
        $join->on('budget_yarns.item_account_id','=','po_yarn_items.item_account_id');
        })
        ->join('budgets',function($join){
        $join->on('budgets.id','=','budget_yarns.budget_id');
        })
        ->join('budget_fabrics',function($join){
        $join->on('budget_fabrics.id','=','budget_yarns.budget_fabric_id');
        })
        ->join('budget_fabric_cons',function($join){
        $join->on('budget_fabric_cons.budget_fabric_id','=','budget_fabrics.id')
        ->whereNull('budget_fabric_cons.deleted_at');
        })
        ->join('sales_order_gmt_color_sizes',function($join){
        $join->on('sales_order_gmt_color_sizes.id','=','budget_fabric_cons.sales_order_gmt_color_size_id')
        ->whereNull('sales_order_gmt_color_sizes.deleted_at');
        })
        ->join('sales_order_countries',function($join){
        $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
        })
        ->join('sales_orders',function($join){
        $join->on('sales_orders.id','=','sales_order_countries.sale_order_id');
        })
        ->join('jobs',function($join){
        $join->on('jobs.id','=','budgets.job_id');
        })
        ->join('companies',function($join){
        $join->on('companies.id','=','jobs.company_id');
        })
        ->leftJoin('companies pcompanies',function($join){
        $join->on('pcompanies.id','=','sales_orders.produced_company_id');
        })
        ->join('styles', function($join) {
        $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('buyers', function($join) {
        $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->leftJoin('suppliers',function($join){
        $join->on('suppliers.id','=','budget_yarns.supplier_id');
        })
        ->join(\DB::raw("(
        SELECT sales_orders.id as sale_order_id,
        po_yarn_item_bom_qties.budget_yarn_id,
        sum(po_yarn_item_bom_qties.qty) as cumulative_qty 
        FROM po_yarn_item_bom_qties 
        join sales_orders on sales_orders.id =po_yarn_item_bom_qties.sale_order_id 
        join po_yarn_items on  po_yarn_items.id=po_yarn_item_bom_qties.po_yarn_item_id 
        where 1=1 
        --and po_yarn_items.id=".request('po_yarn_item_id',0)."  
        group by sales_orders.id,po_yarn_item_bom_qties.budget_yarn_id
        ) cumulatives"), [["cumulatives.sale_order_id", "=", "sales_orders.id"],["cumulatives.budget_yarn_id", "=", "budget_yarns.id"]])
        ->join('po_yarn_item_bom_qties',function($join){
            $join->on('po_yarn_item_bom_qties.po_yarn_item_id','=','po_yarn_items.id');
            $join->on('po_yarn_item_bom_qties.budget_yarn_id','=','budget_yarns.id');
            $join->on('po_yarn_item_bom_qties.sale_order_id','=','sales_orders.id');
        })
        ->groupBy([
            'budget_yarns.id',
            'budget_yarns.item_account_id',
            'budget_yarns.ratio',
            'budget_yarns.rate',
            'sales_orders.id',
            'sales_orders.sale_order_no',
            'sales_orders.ship_date',
            'companies.name', 
            'pcompanies.name',
            'styles.style_ref',
            'buyers.code',
            'cumulatives.cumulative_qty',
            'po_yarn_items.rate',
            'po_yarn_items.id',
            'po_yarn_item_bom_qties.id',
            'po_yarn_item_bom_qties.qty',
            'po_yarn_item_bom_qties.rate',
            'po_yarn_item_bom_qties.amount'
        ])
        ->orderBy('po_yarn_item_bom_qties.id')
        ->where([['po_yarn_items.id','=',request('po_yarn_item_id',0)]])
        ->get(['budget_yarns.*'])
        ->map(function ($rows) use($yarnDropdown) {
            $rows->yarn_des = $yarnDropdown[$rows->item_account_id];
            $rows->bom_qty = ($rows->ratio/100)*$rows->grey_fab;
            $rows->bom_rate = $rows->bom_rate;
            $rows->bom_amount = $rows->bom_qty*$rows->bom_rate;
            $rows->prev_po_qty = $rows->cumulative_qty-$rows->qty;
            $rows->balance_qty = $rows->bom_qty-$rows->prev_po_qty;
            $rows->balance_amount = $rows->balance_qty*$rows->bom_rate;

            $rows->bom_qty = number_format($rows->bom_qty,2);
            $rows->bom_amount = number_format($rows->bom_amount,2);
            $rows->prev_po_qty = number_format($rows->prev_po_qty,2);
            $rows->balance_qty = number_format($rows->balance_qty,2);
            $rows->balance_amount = number_format($rows->balance_amount,2);
            
            $rows->qty = number_format($rows->qty,2);
            $rows->rate = number_format($rows->rate,4);
            $rows->amount = number_format($rows->amount,2);
            $rows->ship_date = date('d-M-Y',strtotime($rows->ship_date));

            return $rows;
        });
        echo json_encode($rows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $rows=$this->poyarnitem
        ->selectRaw('
        budget_yarns.item_account_id, 
        budget_yarns.id as budget_yarn_id,
        budget_yarns.ratio,
        budget_yarns.rate as bom_rate,
        sales_orders.id as sale_order_id, 
        sales_orders.sale_order_no, 
        sales_orders.ship_date, 
        companies.name as company_name, 
        pcompanies.name as p_company_name, 
        po_yarn_items.id as po_yarn_item_id,
        po_yarn_items.rate as pur_yarn_rate,
        po_yarn_item_bom_qties.id as po_yarn_item_bom_qty_id,
        po_yarn_item_bom_qties.qty,
        po_yarn_item_bom_qties.rate,
        po_yarn_item_bom_qties.amount,
        cumulatives.cumulative_qty,
        sum (budget_fabric_cons.grey_fab) as grey_fab,
        sum (sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
        ')
        ->join('budget_yarns',function($join){
        $join->on('budget_yarns.item_account_id','=','po_yarn_items.item_account_id');
        })
        ->join('budgets',function($join){
        $join->on('budgets.id','=','budget_yarns.budget_id');
        })
        ->join('budget_fabrics',function($join){
        $join->on('budget_fabrics.id','=','budget_yarns.budget_fabric_id');
        })
        ->join('budget_fabric_cons',function($join){
        $join->on('budget_fabric_cons.budget_fabric_id','=','budget_fabrics.id')
        ->whereNull('budget_fabric_cons.deleted_at');
        })
        ->join('sales_order_gmt_color_sizes',function($join){
        $join->on('sales_order_gmt_color_sizes.id','=','budget_fabric_cons.sales_order_gmt_color_size_id')
        ->whereNull('sales_order_gmt_color_sizes.deleted_at');
        })
        ->join('sales_order_countries',function($join){
        $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
        })
        ->join('sales_orders',function($join){
        $join->on('sales_orders.id','=','sales_order_countries.sale_order_id');
        })
        ->join('jobs',function($join){
        $join->on('jobs.id','=','budgets.job_id');
        })
        ->join('companies',function($join){
        $join->on('companies.id','=','jobs.company_id');
        })
        ->leftJoin('companies pcompanies',function($join){
        $join->on('pcompanies.id','=','sales_orders.produced_company_id');
        })
        ->join('styles', function($join) {
        $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->leftJoin('suppliers',function($join){
        $join->on('suppliers.id','=','budget_yarns.supplier_id');
        })
        
        ->leftJoin(\DB::raw("(
        SELECT sales_orders.id as sale_order_id,
        po_yarn_item_bom_qties.budget_yarn_id,
        sum(po_yarn_item_bom_qties.qty) as cumulative_qty 
        FROM po_yarn_item_bom_qties 
        join sales_orders on sales_orders.id =po_yarn_item_bom_qties.sale_order_id 
        join po_yarn_items on  po_yarn_items.id=po_yarn_item_bom_qties.po_yarn_item_id 
        where 1=1 
        --and po_yarn_items.id=".request('po_yarn_item_id',0)."  
        group by sales_orders.id,po_yarn_item_bom_qties.budget_yarn_id
        ) cumulatives"), [["cumulatives.sale_order_id", "=", "sales_orders.id"],["cumulatives.budget_yarn_id", "=", "budget_yarns.id"]])
        ->leftJoin('po_yarn_item_bom_qties',function($join){
            $join->on('po_yarn_item_bom_qties.po_yarn_item_id','=','po_yarn_items.id');
            $join->on('po_yarn_item_bom_qties.budget_yarn_id','=','budget_yarns.id');
            $join->on('po_yarn_item_bom_qties.sale_order_id','=','sales_orders.id');
        })
        ->groupBy([
            'budget_yarns.id',
            'budget_yarns.item_account_id',
            'budget_yarns.ratio',
            'budget_yarns.rate',
            'sales_orders.id',
            'sales_orders.sale_order_no',
            'sales_orders.ship_date', 
            'companies.name', 
            'pcompanies.name',
            'cumulatives.cumulative_qty',
            'po_yarn_items.rate',
            'po_yarn_items.id',
            'po_yarn_item_bom_qties.id',
            'po_yarn_item_bom_qties.qty',
            'po_yarn_item_bom_qties.rate',
            'po_yarn_item_bom_qties.amount'
        ])
        ->orderBy('budget_yarns.id')
        ->where([['po_yarn_items.id','=',request('po_yarn_item_id',0)]])
        ->whereIn('sales_orders.id',explode(',',request('sales_order_id')))
        ->whereIn('budget_yarns.id',explode(',',request('budget_yarn_id')))
        ->get(['budget_yarns.*'])
        ->map(function ($rows) use($yarnDropdown) {
            $rows->yarn_des = $yarnDropdown[$rows->item_account_id];
            $rows->ratio = $rows->ratio;
            $rows->bom_qty = ($rows->ratio/100)*$rows->grey_fab;
            $rows->bom_rate = $rows->bom_rate;
            $rows->bom_amount = $rows->bom_qty*$rows->bom_rate;
            $rows->id = $rows->id;
            $rows->item_account_id = $rows->item_account_id;
            $rows->prev_po_qty = $rows->cumulative_qty-$rows->qty;
            $rows->balance_qty = $rows->bom_qty-$rows->prev_po_qty;
            $rows->balance_amount = $rows->balance_qty*$rows->bom_rate;
            $rows->ship_date = date('d-M-Y',strtotime($rows->ship_date));
            return $rows;
        });
        $saved = $rows->filter(function ($value) {
            if($value->po_yarn_item_bom_qty_id){
                return $value;
            }
        });
        $new = $rows->filter(function ($value) {
            if(!$value->po_yarn_item_bom_qty_id){
                return $value;
            }
        });
        $dropdown['poyarnitembomqty'] = "'".Template::loadView('Purchase.PoYarnItemBomQty',['colorsizes'=>$saved,'new'=>$new])."'";
        $row ['dropDown'] = $dropdown;
        echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoYarnItemBomQtyRequest $request)
    {
        $poyarnapproved=$this->poyarn->find(request('po_yarn_id',0));
        if($poyarnapproved->approved_by){
            return response()->json(array('success' => false,  'message' => 'Yarn Purchase Order is Approved, So Save Or Update not Possible'), 200);
        }
        
        $poYarnItemId=0;
        foreach($request->po_yarn_item_id as $index=>$po_yarn_item_id){
            $poYarnItemId=$po_yarn_item_id;
            if($po_yarn_item_id && $request->qty[$index]>0){
                $poyarnitembomqty = $this->poyarnitembomqty->updateOrCreate(
                ['po_yarn_item_id' => $request->po_yarn_item_id[$index],'budget_yarn_id' => $request->budget_yarn_id[$index],'sale_order_id' => $request->sale_order_id[$index]],
                ['qty' => $request->qty[$index],'rate' => $request->rate[$index],'amount' => $request->amount[$index]]
                );
            }
        }
        if ($poyarnitembomqty){
            return response()->json(array('success' => true, 'id' => $poyarnitembomqty->id,'po_yarn_item_id' => $poYarnItemId,  'message' => 'Save Successfully'), 200);
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$this->poyarnitembomqty
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

        $rows=$this->poyarnitembomqty
        ->selectRaw('
        budget_yarns.item_account_id, 
        sales_orders.id as sale_order_id, 
        sales_orders.sale_order_no,
        po_yarn_item_bom_qties.id, 
        po_yarn_item_bom_qties.budget_yarn_id,
        po_yarn_item_bom_qties.qty,
        po_yarn_item_bom_qties.rate,
        po_yarn_item_bom_qties.amount
        ')
        ->join('budget_yarns',function($join){
        $join->on('budget_yarns.id','=','po_yarn_item_bom_qties.budget_yarn_id');
        })
        ->join('sales_orders',function($join){
        $join->on('sales_orders.id','=','po_yarn_item_bom_qties.sale_order_id');
        })
        ->where([['po_yarn_item_bom_qties.id','=',$id]])
        ->get()
        ->map(function ($rows) use($yarnDropdown) {
            $rows->item_description = $yarnDropdown[$rows->item_account_id];
            return $rows;
        })->first();
        $row ['fromData'] = $rows;
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
    public function update(PoYarnItemBomQtyRequest $request, $id)
    {
        $poyarnapproved=$this->poyarn->find(request('po_yarn_id',0));
        if($poyarnapproved->approved_by){
            return response()->json(array('success' => false,  'message' => 'Yarn Purchase Order is Approved, So Save Or Update not Possible'), 200);
        }

        $poyarnitembomqty = $this->poyarnitembomqty->update($id, ['qty'=>$request->qty,'rate'=>$request->rate,'amount'=>$request->amount]);
        if ($poyarnitembomqty) {
            return response()->json(array('success' => true, 'id' => $id, 'po_yarn_item_id'=>$request->po_yarn_item_id,'message' => 'Update Successfully'), 200);
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
        
    }


    public function getOrder()
    {
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
        $rows=$this->poyarnitem
        ->selectRaw('
        budget_yarns.item_account_id, 
        budget_yarns.id as budget_yarn_id,
        budget_yarns.ratio,
        budget_yarns.rate as bom_rate,
        sales_orders.id as sale_order_id, 
        sales_orders.sale_order_no,
        sales_orders.ship_date,
        companies.name as company_name,
        pcompanies.name as p_company_name,
        styles.style_ref,
        buyers.code as buyer_name, 
        po_yarn_items.id as po_yarn_item_id,
        po_yarn_items.rate as pur_yarn_rate,
        po_yarn_item_bom_qties.id as po_yarn_item_bom_qty_id,
        po_yarn_item_bom_qties.qty,
        po_yarn_item_bom_qties.rate,
        po_yarn_item_bom_qties.amount,
        cumulatives.cumulative_qty,
        sum (budget_fabric_cons.grey_fab) as grey_fab,
        sum (sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty
        ')
        ->join('budget_yarns',function($join){
        $join->on('budget_yarns.item_account_id','=','po_yarn_items.item_account_id');
        })
        ->join('budgets',function($join){
        $join->on('budgets.id','=','budget_yarns.budget_id');
        })
        //->join('budget_approvals',function($join){
        //   $join->on('budgets.id','=','budget_approvals.budget_id');
        //})
        ->join('budget_fabrics',function($join){
        $join->on('budget_fabrics.id','=','budget_yarns.budget_fabric_id');
        })
        ->join('budget_fabric_cons',function($join){
        $join->on('budget_fabric_cons.budget_fabric_id','=','budget_fabrics.id')
        ->whereNull('budget_fabric_cons.deleted_at');
        })
        ->join('sales_order_gmt_color_sizes',function($join){
        $join->on('sales_order_gmt_color_sizes.id','=','budget_fabric_cons.sales_order_gmt_color_size_id')
        ->whereNull('sales_order_gmt_color_sizes.deleted_at');
        })
        ->join('sales_order_countries',function($join){
        $join->on('sales_order_countries.id','=','sales_order_gmt_color_sizes.sale_order_country_id');
        })
        ->join('sales_orders',function($join){
        $join->on('sales_orders.id','=','sales_order_countries.sale_order_id');
        })
        ->join('jobs',function($join){
        $join->on('jobs.id','=','budgets.job_id');
        })
        ->join('companies',function($join){
        $join->on('companies.id','=','jobs.company_id');
        })
        ->leftJoin('companies pcompanies',function($join){
        $join->on('pcompanies.id','=','sales_orders.produced_company_id');
        })
        ->join('styles', function($join) {
        $join->on('styles.id', '=', 'jobs.style_id');
        })
        ->join('buyers', function($join) {
        $join->on('buyers.id', '=', 'styles.buyer_id');
        })
        ->leftJoin('suppliers',function($join){
        $join->on('suppliers.id','=','budget_yarns.supplier_id');
        })
        ->leftJoin(\DB::raw("(
        SELECT sales_orders.id as sale_order_id,
        po_yarn_item_bom_qties.budget_yarn_id,
        sum(po_yarn_item_bom_qties.qty) as cumulative_qty 
        FROM po_yarn_item_bom_qties 
        join sales_orders on sales_orders.id =po_yarn_item_bom_qties.sale_order_id 
        join po_yarn_items on  po_yarn_items.id=po_yarn_item_bom_qties.po_yarn_item_id 
        where 1=1 
        --and po_yarn_items.id=".request('po_yarn_item_id',0)."  
        group by sales_orders.id,po_yarn_item_bom_qties.budget_yarn_id
        ) cumulatives"), [["cumulatives.sale_order_id", "=", "sales_orders.id"],["cumulatives.budget_yarn_id", "=", "budget_yarns.id"]])
        ->leftJoin('po_yarn_item_bom_qties',function($join){
            $join->on('po_yarn_item_bom_qties.po_yarn_item_id','=','po_yarn_items.id');
            $join->on('po_yarn_item_bom_qties.budget_yarn_id','=','budget_yarns.id');
            $join->on('po_yarn_item_bom_qties.sale_order_id','=','sales_orders.id');
        })
        //->whereNotNull('budget_approvals.yarn_final_approved_at')
        ->groupBy([
            'budget_yarns.id',
            'budget_yarns.item_account_id',
            'budget_yarns.ratio',
            'budget_yarns.rate',
            'sales_orders.id',
            'sales_orders.sale_order_no',
            'sales_orders.ship_date',
            'companies.name',
            'pcompanies.name',
            'styles.style_ref',
            'buyers.code',
            'cumulatives.cumulative_qty',
            'po_yarn_items.rate',
            'po_yarn_items.id',
            'po_yarn_item_bom_qties.id',
            'po_yarn_item_bom_qties.qty',
            'po_yarn_item_bom_qties.rate',
            'po_yarn_item_bom_qties.amount'
        ])
        ->orderBy('budget_yarns.id')
        ->where([['po_yarn_items.id','=',request('po_yarn_item_id',0)]])
        ->get(['budget_yarns.*'])
        ->map(function ($rows) use($yarnDropdown) {
            $rows->yarn_des = $yarnDropdown[$rows->item_account_id];
            $rows->ratio = $rows->ratio;
            $rows->bom_qty = ($rows->ratio/100)*$rows->grey_fab;
            $rows->bom_rate = $rows->bom_rate;
            $rows->bom_amount = $rows->bom_qty*$rows->bom_rate;
            $rows->id = $rows->id;
            $rows->item_account_id = $rows->item_account_id;
            $rows->prev_po_qty = $rows->cumulative_qty-$rows->qty;
            $rows->balance_qty = $rows->bom_qty-$rows->prev_po_qty;
            $rows->balance_amount = $rows->balance_qty*$rows->bom_rate;
            $rows->ship_date = date('d-M-Y',strtotime($rows->ship_date));
            return $rows;
        });
        echo json_encode($rows);
    }
	
}
