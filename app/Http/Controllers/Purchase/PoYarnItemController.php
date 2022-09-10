<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoYarnRepository;
use App\Repositories\Contracts\Purchase\PoYarnItemRepository;
use App\Repositories\Contracts\Bom\BudgetYarnRepository;

use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Http\Requests\Purchase\PoYarnItemRequest;


class PoYarnItemController extends Controller
{
   private $poyarn;
   private $poyarnitem;
   private $budgetyarn;
   private $poyarnitembom;

	public function __construct(PoYarnRepository $poyarn,PoYarnItemRepository $poyarnitem,BudgetYarnRepository $budgetyarn,ItemAccountRepository $itemaccount)
	{
		$this->poyarn = $poyarn;
        $this->poyarnitem = $poyarnitem;
		$this->budgetyarn = $budgetyarn;
		$this->itemaccount = $itemaccount;
		$this->middleware('auth');
		$this->middleware('permission:view.poyarnitems',   ['only' => ['create', 'index','show']]);
		$this->middleware('permission:create.poyarnitems', ['only' => ['store']]);
		$this->middleware('permission:edit.poyarnitems',   ['only' => ['update']]);
		$this->middleware('permission:delete.poyarnitems', ['only' => ['destroy']]);
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
	  //->orderBy('po_yarn_items.id','desc')
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


		$budgetyarns=array();
		$rows=$this->poyarn
		->join('po_yarn_items',function($join){
		$join->on('po_yarn_items.po_yarn_id','=','po_yarns.id')
		->whereNull('po_yarn_items.deleted_at');
		})
		
		->where([['po_yarns.id','=',request('po_yarn_id',0)]])
		->orderBy('po_yarn_items.id','asc')
		->get([
			'po_yarn_items.*',
		])
		->map(function ($rows) use($yarnDropdown)  {
		$rows->yarn_des = $yarnDropdown[$rows->item_account_id];
		$rows->qty = number_format($rows->qty,2);
        $rows->rate = number_format($rows->rate,4);
        $rows->amount = number_format($rows->amount,2);
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
		$item_account_id=request('item_account_id',0);
		$item_account_id_arr=explode(',',$item_account_id);
		$poyarn=$this->poyarn->find(request('po_yarn_id',0));
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


		$rows=$this->itemaccount
		->join('yarncounts',function($join){
		$join->on('yarncounts.id','=','item_accounts.yarncount_id');
		})
		->join('yarntypes',function($join){
		$join->on('yarntypes.id','=','item_accounts.yarntype_id');
		})
		->join('itemclasses',function($join){
		$join->on('itemclasses.id','=','item_accounts.itemclass_id');
		})

		->join('itemcategories',function($join){
		$join->on('itemcategories.id','=','item_accounts.itemcategory_id');
		})
		->where([['itemcategories.identity','=',1]])
		->whereIn('item_accounts.id',$item_account_id_arr)
		->get([
			'item_accounts.id as item_account_id',
			'yarncounts.count',
			'yarncounts.symbol',
			'yarntypes.name as yarn_type',
			'itemclasses.name as itemclass_name',
		])
		->map(function ($rows) use($yarnDropdown)  {
			$rows->yarn_des = $yarnDropdown[$rows->item_account_id];
			$rows->po_yarn_id = request('po_yarn_id',0);		
			$rows->qty = '';
			$rows->rate = '';
			$rows->amount = '';
			return $rows;
		});
		return Template::loadView('Purchase.PoYarnItem',['rows'=>$rows]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoYarnItemRequest $request)
    {
    	$poyarn=$this->poyarn->find($request->po_yarn_id);
		if($poyarn->approved_at){
			return response()->json(array('success' => false,  'message' => 'Yarn Purchase Order is Approved, Save or Update not Possible'), 200);
		}else {
			foreach($request->item_account_id as $index=>$item_account_id){

				try
				{
					$yarn = $this->poyarnitem->create(['po_yarn_id' => $request->po_yarn_id,'item_account_id' => $item_account_id,'qty' => $request->qty[$index],'rate' => $request->rate[$index],'amount' => $request->amount[$index],'no_of_bag' => $request->no_of_bag[$index],'remarks' => $request->remarks[$index]]);
				}
	            catch(EXCEPTION $e)
	            {
	                \DB::rollback();
	                throw $e;
	            }
			}
			\DB::commit();
			if ($yarn) {
				return response()->json(array('success' => true, 'id' => $yarn->id, 'message' => 'Save Successfully'), 200);
			}
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


    	//$fabricpurchase = $this->fabricpurchase->find($id);
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


		$budgetyarns=array();
		$rows=$this->poyarn
		->join('po_yarn_items',function($join){
		$join->on('po_yarn_items.po_yarn_id','=','po_yarns.id')
		->whereNull('po_yarn_items.deleted_at');
		})
		->leftJoin('pur_yarn_budgets',function($join){
		$join->on('pur_yarn_budgets.pur_yarn_id','=','po_yarn_items.id')
		->whereNull('pur_yarn_budgets.deleted_at');
		})
		->leftJoin('budget_yarns',function($join){
		$join->on('budget_yarns.id','=','pur_yarn_budgets.budget_yarn_id');
		})
		->leftJoin('budget_fabrics',function($join){
		$join->on('budget_fabrics.id','=','budget_yarns.budget_fabric_id');
		})
		->leftJoin('budgets',function($join){
		$join->on('budgets.id','=','budget_yarns.budget_id');
		})
		->leftJoin('jobs',function($join){
		$join->on('jobs.id','=','budgets.job_id');
		})
		->leftJoin('styles', function($join) {
		$join->on('styles.id', '=', 'jobs.style_id');
		})
		->leftJoin('suppliers',function($join){
		$join->on('suppliers.id','=','budget_yarns.supplier_id');
		})
		->where([['po_yarn_items.id','=',$id]])
		->orderBy('po_yarn_items.id','asc')
		->get([
			'po_yarn_items.*',
			'po_yarns.id as po_yarn_id',
			'budget_fabrics.uom_id'
		])
		->map(function ($rows) use($yarnDropdown)  {
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
    public function update(PoYarnItemRequest $request, $id)
    {
    	$poyarnapproved=$this->poyarn->find($request->po_yarn_id);
    	if($poyarnapproved->approved_at){
    	  return response()->json(array('success' => false,  'message' => 'Yarn Purchase Order is Approved, So Save Or Update not Possible'), 200);
    	}

      	$fabricpurchase = $this->poyarnitem->update($id, ['qty'=>$request->qty,'rate'=>$request->rate,'amount'=>$request->amount,'no_of_bag'=>$request->no_of_bag,'remarks'=>$request->remarks]);
		if ($fabricpurchase) {
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
        $poyarnitem = $this->poyarnitem->findOrFail($id);
		$poyarnapproved=$this->poyarn->find($poyarnitem->po_yarn_id);
    	if($poyarnapproved->approved_at){
    	  return response()->json(array('success' => false,  'message' => 'Yarn Purchase Order is Approved, So Save Or Update not Possible'), 200);
    	}
		if($poyarnitem->forceDelete()){
			return response()->json(array('success' => true,'message' => 'Delete Successfully'),200);
		}
		
		else{
			 return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
		}
    }
    
	public function importyarn()
    {

    	    $poyarn=$this->poyarn->find(request('po_yarn_id',0));
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
			$yarnDropdown[$key]=$value['count']." ".implode(",",$yarnCompositionArr[$key])." ".$value['yarn_type'];
			}

    	    $rows=$this->itemaccount
			->join('yarncounts',function($join){
			$join->on('yarncounts.id','=','item_accounts.yarncount_id');
			})
			->join('yarntypes',function($join){
			$join->on('yarntypes.id','=','item_accounts.yarntype_id');
			})
			->join('itemclasses',function($join){
			$join->on('itemclasses.id','=','item_accounts.itemclass_id');
			})
			
			->join('itemcategories',function($join){
			$join->on('itemcategories.id','=','item_accounts.itemcategory_id');
			})
			->where([['item_accounts.status_id','=',1]])
			->where([['itemcategories.identity','=',1]])
			->get([
			'item_accounts.id',
			'yarncounts.count',
			'yarncounts.symbol',
			'yarntypes.name as yarn_type',
			'itemclasses.name as itemclass_name',
			])
			->map(function ($rows) use($yarnDropdown)  {
			$rows->yarn_des = isset($yarnDropdown[$rows->id])?$yarnDropdown[$rows->id]:'';
			$rows->qty = '';
			$rows->rate = '';
			$rows->amount = '';
			$rows->item_account_id = $rows->id;
			return $rows;
			});

    	echo json_encode($rows);
    }
}
