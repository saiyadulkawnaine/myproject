<?php

namespace App\Http\Controllers\Bom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Bom\BudgetYarnRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
use App\Http\Requests\Bom\BudgetYarnRequest;

class BudgetYarnController extends Controller {

    private $budgetyarn;
    private $budget;
    private $supplier;
    private $budgetfabric;
	private $itemaccount;

    public function __construct(BudgetYarnRepository $budgetyarn,BudgetRepository $budget,SupplierRepository $supplier,BudgetFabricRepository $budgetfabric,ItemAccountRepository $itemaccount) {
        $this->budgetyarn = $budgetyarn;
        $this->budget = $budget;
        $this->supplier = $supplier;
        $this->budgetfabric = $budgetfabric;
		$this->itemaccount = $itemaccount;
        $this->middleware('auth');
        $this->middleware('permission:view.budgetyarns',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.budgetyarns', ['only' => ['store']]);
        $this->middleware('permission:edit.budgetyarns',   ['only' => ['update']]);
        $this->middleware('permission:delete.budgetyarns', ['only' => ['destroy']]);
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
		$rows = $this->budgetyarn->selectRaw(
		'budget_yarns.item_account_id,
		sum(budget_yarns.cons) as cons,sum(budget_yarns.amount) as amount'
		)
		->where([['budget_yarns.budget_id','=',request('budget_id',0)]])
		->groupBy([
		'budget_yarns.item_account_id',
		])
		->get();
		$tot=0;
		$totCons=0;
		foreach($rows as $row){
		$budgetyarn['yarn_cons']=	$row->cons;
		$budgetyarn['yarn_rate']=	$row->rate;
		$budgetyarn['yarn_amount']=	$row->amount;
		$tot+=$row->amount;
		$totCons+=$row->cons;
		$budgetyarn['yarn_des']=	$yarnDropdown[$row->item_account_id];
		array_push($budgetyarns,$budgetyarn);
		}
		$dd=array('total'=>1,'rows'=>$budgetyarns,'footer'=>array(0=>array('yarn_des'=>'','yarn_ratio'=>'Total','yarn_cons'=>$totCons,'yarn_rate'=>'','yarn_amount'=>$tot)));
		echo json_encode($dd);

    }

	public function getPopuplist(){
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
		$rows=$this->budgetyarn->where([['budget_yarns.budget_fabric_id','=',request('budget_fabric_id',0)]])->get();
		$tot=0;
		$totCons=0;
		foreach($rows as $row){
		$budgetyarn['id']=	$row->id;
		$budgetyarn['yarn_ratio']=	$row->ratio;
		$budgetyarn['yarn_cons']=	$row->cons;
		$budgetyarn['yarn_rate']=	$row->rate;
		$budgetyarn['yarn_amount']=	$row->amount;
		$totCons+=$row->cons;
		$tot+=$row->amount;
		$budgetyarn['yarn_des']=	$yarnDropdown[$row->item_account_id];
		array_push($budgetyarns,$budgetyarn);
		}
		$dd=array('total'=>1,'rows'=>$budgetyarns,'footer'=>array(0=>array('yarn_des'=>'','yarn_ratio'=>'','yarn_cons'=>$totCons,'yarn_rate'=>'Total','yarn_amount'=>$tot)));
		echo json_encode($dd);
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$fabricyarn=$this->budgetfabric
		->selectRaw(
			'budget_fabrics.id,
			budget_fabrics.budget_id,
			sum(budget_fabric_cons.grey_fab) as req_cons
			'
		)
        ->join('budget_fabric_cons',function($join){
          $join->on('budget_fabric_cons.budget_fabric_id','=','budget_fabrics.id');
        })
		->groupBy([
		'budget_fabrics.id',
		'budget_fabrics.budget_id',
		])
		->where([['budget_fabrics.id','=',request('budget_fabric_id',0)]])
        ->get()->first();

        /*return Template::loadView('Marketing.budgetYarnMatrix', ['fabricyarn'=>$fabricyarn]);*/
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
	  //dd($yarnDescription);
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
    $supplier=array_prepend(array_pluck($this->supplier->get(),'name','id'),'-Select-','');
		return Template::loadView('Bom.BudgetYarn',['supplier'=>$supplier,'yarnDropdown'=>$yarnDropdown,'fabricyarn'=>$fabricyarn]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetYarnRequest $request) {
		/*$budgetId=0;
		foreach($request->budget_id as $index=>$budget_id){
			    $budgetId=$budget_id;
				if($request->cons[$index]){
				$budgetyarn = $this->budgetyarn->updateOrCreate(
				['budget_id' => $budget_id,'budget_fabric_id' => $request->budget_fabric_id[$index],'autoyarnratio_id' => $request->autoyarnratio_id[$index]],
				['cons' => $request->cons[$index],'rate' => $request->rate[$index],'amount' =>$request->amount[$index]]
				);
				}
			}
		$totalCost=$this->budget->totalCost($budgetId);
		return response()->json(array('success' => true, 'id' => $budgetyarn->id, 'budget_id' => $budgetId, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);*/
		$budgetapp=$this->budget->find($request->budget_id);
        if($budgetapp->approved_at){
        return response()->json(array('success' => false,  'message' => 'Budget is Approved, So Save Or Update not Possible'), 200);

        }


        $budgetyarn = $this->budgetyarn->create($request->except(['id','fab_cons','item_description']));
        if ($budgetyarn) {
			$totalCost=$this->budget->totalCost($request->budget_id);
			return response()->json(array('success' => true, 'id' => $budgetyarn->id, 'budget_id' => $request->budget_id, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);
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
		//$budgetyarn = $this->budgetyarn->find($id);
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
		$budgetyarn = $this->budgetyarn
		->where([['id','=',$id]])
		->get()
		->map(function ($budgetyarn) use($yarnDropdown) {
			$budgetyarn->item_description= $yarnDropdown[$budgetyarn->item_account_id];
			return $budgetyarn;
		});
        $row ['fromData'] = $budgetyarn[0];
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
    public function update(budgetYarnRequest $request, $id) {
    	$po=$this->budgetyarn
    	->join('po_yarn_item_bom_qties',function($join){
		$join->on('po_yarn_item_bom_qties.budget_yarn_id','=','budget_yarns.id');
		})
		->where([['budget_yarns.id','=',$id]])
		->get();
		if($po->first())
		{
		return response()->json(array('success' => false, 'id' => $id, 'budget_id' => $request->budget_id, 'message' => 'Update Not Possible, Purchase Order Found'), 200);
		}

		$budgetapp=$this->budget->find($request->budget_id);
        if($budgetapp->approved_at){
        return response()->json(array('success' => false,  'message' => 'Budget is Approved, So Save Or Update not Possible'), 200);

        }

        $budgetyarn = $this->budgetyarn->update($id, $request->except(['id','fab_cons','item_description']));
        if ($budgetyarn) {
            //return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
			$totalCost=$this->budget->totalCost($request->budget_id);
			return response()->json(array('success' => true, 'id' => $id, 'budget_id' => $request->budget_id, 'message' => 'Update Successfully','totalcost' => $totalCost), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if ($this->budgetyarn->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
	}
	
	public function getbudgetyarn(){
		

		$yarnDescription=$this->budgetfabric
		->leftJoin('smp_cost_fabrics',function($join){
		$join->on('smp_cost_fabrics.style_fabrication_id','=','budget_fabrics.style_fabrication_id');
		})
		->leftJoin('smp_cost_yarns',function($join){
		$join->on('smp_cost_yarns.smp_cost_fabric_id','=','smp_cost_fabrics.id');
		})
		->leftJoin('item_accounts',function($join){
		$join->on('item_accounts.id','=','smp_cost_yarns.item_account_id');
		})
		->leftJoin('item_account_ratios',function($join){
		$join->on('item_account_ratios.item_account_id','=','item_accounts.id');
		})
		->leftJoin('yarncounts',function($join){
		$join->on('yarncounts.id','=','item_accounts.yarncount_id');
		})
		->leftJoin('yarntypes',function($join){
		$join->on('yarntypes.id','=','item_accounts.yarntype_id');
		})
		->leftJoin('itemclasses',function($join){
		$join->on('itemclasses.id','=','item_accounts.itemclass_id');
		})
		->leftJoin('compositions',function($join){
		$join->on('compositions.id','=','item_account_ratios.composition_id');
		})
		->leftJoin('itemcategories',function($join){
		$join->on('itemcategories.id','=','item_accounts.itemcategory_id');
		})
		->where([['budget_fabrics.id','=',request('budget_fabric_id',0)]])
		->where([['itemcategories.identity','=',1]])
		->when(request('count_name'), function ($q) {
			return $q->where('yarncounts.count', 'LIKE', "%".request('count_name', 0)."%");
		})
		->when(request('type_name'), function ($q) {
			return $q->where('yarntypes.name', 'LIKE', "%".request('type_name', 0)."%");
		})
		->get([
		'item_accounts.id',
		'yarncounts.count',
		'yarncounts.symbol',
		'yarntypes.name as yarn_type',
		'itemclasses.name as itemclass_name',
		'compositions.name as composition_name',
		'item_account_ratios.ratio',
		'smp_cost_yarns.ratio as smp_ratio',
		'smp_cost_yarns.rate',
		]);
		if($yarnDescription->count()<1){
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
		/*->leftJoin('smp_cost_yarns',function($join){
		$join->on('smp_cost_yarns.item_account_id','=','item_accounts.id');
		})*/
		->where([['itemcategories.identity','=',1]])
		->when(request('count_name'), function ($q) {
			return $q->where('yarncounts.count', 'LIKE', "%".request('count_name', 0)."%");
		})
		->when(request('type_name'), function ($q) {
			return $q->where('yarntypes.name', 'LIKE', "%".request('type_name', 0)."%");
		})
		->get([
		'item_accounts.id',
		'yarncounts.count',
		'yarncounts.symbol',
		'yarntypes.name as yarn_type',
		'itemclasses.name as itemclass_name',
		'compositions.name as composition_name',
		'item_account_ratios.ratio'
		]);
		}

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
		$yarnDropdown[$key]=implode(",",$yarnCompositionArr[$key]);
		}
		
	  $yarn=array();
	  $yarns=array();
      foreach($yarnDescription as $row){
        $yarn[$row->id]['id']=$row->id;
		$yarn[$row->id]['itemclass_name']=$row->itemclass_name;
		$yarn[$row->id]['count']=$row->count."/".$row->symbol;
		$yarn[$row->id]['yarn_type']=$row->yarn_type;
		$yarn[$row->id]['composition_name']=$yarnDropdown[$row->id];
		$yarn[$row->id]['smp_ratio']=$row->smp_ratio;
		$yarn[$row->id]['rate']=$row->rate;
      }
	  foreach($yarn as $row){
		array_push($yarns,$row);
      }
	  echo json_encode($yarns);
	}
}
