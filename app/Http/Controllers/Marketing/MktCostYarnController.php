<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\MktCostYarnRepository;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Repositories\Contracts\Marketing\MktCostFabricRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Library\Template;
//use App\Http\Requests\MktCostYarnRequest;

class MktCostYarnController extends Controller {

    private $mktcostyarn;
    private $mktcost;
    private $mktcostfabric;
  	private $itemaccount;

    public function __construct(MktCostYarnRepository $mktcostyarn,MktCostRepository $mktcost,MktCostFabricRepository $mktcostfabric,ItemAccountRepository $itemaccount) {
        $this->mktcostyarn = $mktcostyarn;
        $this->mktcost = $mktcost;
        $this->mktcostfabric = $mktcostfabric;
    	$this->itemaccount = $itemaccount;
        $this->middleware('auth');
        $this->middleware('permission:view.mktcostyarns',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.mktcostyarns', ['only' => ['store']]);
        $this->middleware('permission:edit.mktcostyarns',   ['only' => ['update']]);
        $this->middleware('permission:delete.mktcostyarns', ['only' => ['destroy']]);
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


		$mktcostyarns=array();
		$rows=$this->mktcostyarn->where([['mkt_cost_yarns.mkt_cost_id','=',request('mkt_cost_id',0)]])->get();
		$tot=0;
		$totCons=0;
		foreach($rows as $row){
		$mktcostyarn['id']=	$row->id;
		$mktcostyarn['yarn_ratio']=	$row->ratio;
		$mktcostyarn['yarn_cons']=	$row->cons;
		$mktcostyarn['yarn_rate']=	$row->rate;
		$mktcostyarn['yarn_amount']=	$row->amount;
		$tot+=$row->amount;
		$totCons+=$row->cons;
		$mktcostyarn['yarn_des']=	$yarnDropdown[$row->item_account_id];
		array_push($mktcostyarns,$mktcostyarn);
		}
		$dd=array('total'=>1,'rows'=>$mktcostyarns,'footer'=>array(0=>array('yarn_des'=>'','yarn_ratio'=>'Total','yarn_cons'=>$totCons,'yarn_rate'=>'','yarn_amount'=>$tot)));
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


		$mktcostyarns=array();
		$rows=$this->mktcostyarn->where([['mkt_cost_yarns.mkt_cost_fabric_id','=',request('mkt_cost_fabric_id',0)]])->get();
		$tot=0;
		foreach($rows as $row){
		$mktcostyarn['id']=	$row->id;
		$mktcostyarn['yarn_ratio']=	$row->ratio;
		$mktcostyarn['yarn_cons']=	$row->cons;
		$mktcostyarn['yarn_rate']=	$row->rate;
		$mktcostyarn['yarn_amount']=	$row->amount;
		$tot+=$row->amount;
		$mktcostyarn['yarn_des']=	$yarnDropdown[$row->item_account_id];
		array_push($mktcostyarns,$mktcostyarn);
		}
		$dd=array('total'=>1,'rows'=>$mktcostyarns,'footer'=>array(0=>array('yarn_des'=>'','yarn_ratio'=>'','yarn_cons'=>'','yarn_rate'=>'Total','yarn_amount'=>$tot)));
		echo json_encode($dd);
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$fabricyarn=$this->mktcostfabric
		->selectRaw(
			'mkt_cost_fabrics.id,
			mkt_cost_fabrics.mkt_cost_id,
			avg(mkt_cost_fabric_cons.req_cons) as req_cons
			'
		)
        ->join('mkt_cost_fabric_cons',function($join){
          $join->on('mkt_cost_fabric_cons.mkt_cost_fabric_id','=','mkt_cost_fabrics.id');
        })
		->groupBy([
		'mkt_cost_fabrics.id',
		'mkt_cost_fabrics.mkt_cost_id',
		])
		->where([['mkt_cost_fabrics.id','=',request('mkt_cost_fabric_id',0)]])
        ->get()->first();

        /*return Template::loadView('Marketing.MktCostYarnMatrix', ['fabricyarn'=>$fabricyarn]);*/
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

		return Template::loadView('Marketing.MktCostYarn',['yarnDropdown'=>$yarnDropdown,'fabricyarn'=>$fabricyarn]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
		/*$mktCostId=0;
		foreach($request->mkt_cost_id as $index=>$mkt_cost_id){
			    $mktCostId=$mkt_cost_id;
				if($request->cons[$index]){
				$mktcostyarn = $this->mktcostyarn->updateOrCreate(
				['mkt_cost_id' => $mkt_cost_id,'mkt_cost_fabric_id' => $request->mkt_cost_fabric_id[$index],'autoyarnratio_id' => $request->autoyarnratio_id[$index]],
				['cons' => $request->cons[$index],'rate' => $request->rate[$index],'amount' =>$request->amount[$index]]
				);
				}
			}
		$totalCost=$this->mktcost->totalCost($mktCostId);
		return response()->json(array('success' => true, 'id' => $mktcostyarn->id, 'mkt_cost_id' => $mktCostId, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);*/
		$approved=$this->mktcost->find($request->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        $mktcostyarn = $this->mktcostyarn->create($request->except(['id','fab_cons','item_description']));
        if ($mktcostyarn) {
			$totalCost=$this->mktcost->totalCost($request->mkt_cost_id);
			return response()->json(array('success' => true, 'id' => $mktcostyarn->id, 'mkt_cost_id' => $request->mkt_cost_id, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);
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
        //$mktcostyarn = $this->mktcostyarn->find($id);
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
		$mktcostyarn = $this->mktcostyarn
		->where([['id','=',$id]])
		->get()
		->map(function ($mktcostyarn) use($yarnDropdown) {
			$mktcostyarn->item_description= $yarnDropdown[$mktcostyarn->item_account_id];
			return $mktcostyarn;
		});
        $row ['fromData'] = $mktcostyarn[0];
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
    public function update(Request $request, $id) {
    	$approved=$this->mktcost->find($request->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        $mktcostyarn = $this->mktcostyarn->update($id, $request->except(['id','fab_cons','item_description']));
        if ($mktcostyarn) {
            //return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
			$totalCost=$this->mktcost->totalCost($request->mkt_cost_id);
			return response()->json(array('success' => true, 'id' => $id, 'mkt_cost_id' => $request->mkt_cost_id, 'message' => 'Update Successfully','totalcost' => $totalCost), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
    	$mktcostyarn=$this->mktcostyarn->find($id);
    	$approved=$this->mktcost->find($mktcostyarn->mkt_cost_id);
        if($approved->first_approved_by){
        return response()->json(array('success' => false,  'message' => 'This Cost Approved, So Save/Update/Delete not possible '), 200);
        }
        if ($this->mktcostyarn->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
	
	public function getyarn(){
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
		//array_push($fabs,$fab);
      }
	  foreach($yarn as $row){
        
		array_push($yarns,$row);
      }
	  		 echo json_encode($yarns);

	 
	}

}
