<?php

namespace App\Http\Controllers\Bom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Bom\BudgetFabricProdRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Repositories\Contracts\Util\KeycontrolRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Library\Template;
use App\Http\Requests\Bom\BudgetFabricProdRequest;

class BudgetFabricProdController extends Controller {

    private $budgetfabricprod;
    private $budget;
    private $budgetfabric;
    private $yarn;
    private $keycontrol;
     private $company;

    public function __construct(
      BudgetFabricProdRepository $budgetfabricprod,
      BudgetRepository $budget,
      BudgetFabricRepository $budgetfabric,
      KeycontrolRepository $keycontrol,
      CompanyRepository $company

    ) {
        $this->budgetfabricprod = $budgetfabricprod;
        $this->budget = $budget;
        $this->budgetfabric = $budgetfabric;
        $this->keycontrol = $keycontrol;
        $this->company = $company;
        $this->middleware('auth');
        $this->middleware('permission:view.budgetfabricprods',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.budgetfabricprods', ['only' => ['store']]);
        $this->middleware('permission:edit.budgetfabricprods',   ['only' => ['update']]);
        $this->middleware('permission:delete.budgetfabricprods', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $budgetfabricprods=array();
        $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
        $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

        $fabricDescription=$this->budget
        ->join('styles',function($join){
        $join->on('styles.id','=','budgets.style_id');
        })
        ->join('style_fabrications',function($join){
        $join->on('style_fabrications.style_id','=','budgets.style_id');
        })
        ->join('autoyarns',function($join){
        $join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
        })
        ->join('autoyarnratios',function($join){
        $join->on('autoyarns.id','=','autoyarnratios.autoyarn_id');
        })
        ->join('compositions',function($join){
        $join->on('compositions.id','=','autoyarnratios.composition_id');
        })
        ->join('budget_fabrics',function($join){
        $join->on('budget_fabrics.budget_id','=','budgets.id');
        $join->on('budget_fabrics.style_fabrication_id','=','style_fabrications.id');
        })
        ->join('constructions',function($join){
        $join->on('constructions.id','=','autoyarns.construction_id');
        })
        ->join('gmtsparts',function($join){
        $join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
        })
        ->join('style_gmts',function($join){
        $join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
        })
        ->join('item_accounts', function($join) {
        $join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
        })
        ->where([['budgets.id','=',request('budget_id',0)]])
        ->groupBy([
        'budget_fabrics.id',
        'budget_fabrics.gsm_weight',
        'style_fabrications.fabric_nature_id',
        'style_fabrications.fabric_look_id',
        'style_fabrications.fabric_shape_id',
        'item_accounts.item_description',
        'gmtsparts.name',
        'autoyarnratios.composition_id',
        'constructions.name',
        'compositions.name',
        'autoyarnratios.ratio',
        ])
        ->get([
        'budget_fabrics.id',
        'budget_fabrics.gsm_weight',
        'style_fabrications.fabric_nature_id',
        'style_fabrications.fabric_look_id',
        'style_fabrications.fabric_shape_id',
        'gmtsparts.name as gmtspart_name',
        'item_accounts.item_description',
        'autoyarnratios.composition_id',
        'constructions.name as construction',
        'compositions.name',
        'autoyarnratios.ratio',
        ]);
        $fabricDescriptionArr=array();
        $fabricCompositionArr=array();
        foreach($fabricDescription as $row){
          $fabricDescriptionArr[$row->id]=$row->item_description.", ".$row->gmtspart_name.", ".$fabricnature[$row->fabric_nature_id].", ".$fabriclooks[$row->fabric_look_id].", ".$fabricshape[$row->fabric_shape_id].", ".$row->construction.", ".$row->gsm_weight;
          $fabricCompositionArr[$row->id][]=$row->name.", ".$row->ratio."%";
        }
        $desDropdown=array();
        foreach($fabricDescriptionArr as $key=>$val){
          $desDropdown[$key]=$val.", ".implode(",",$fabricCompositionArr[$key]);
        }




        $rows=$this->budgetfabricprod
        ->join('production_processes',function($join){
        $join->on('production_processes.id','=','budget_fabric_prods.production_process_id');
        })
        ->where([['budget_fabric_prods.budget_id','=',request('budget_id',0)]])
        ->get([
          'budget_fabric_prods.*',
          'production_processes.process_name'
        ]);
        $tot=0;
        foreach($rows as $row){
          $budgetfabricprod['id']=	$row->id;
          $budgetfabricprod['process_id']=	$row->process_name;
          $budgetfabricprod['cons']=	$row->req_cons;
          $budgetfabricprod['rate']=	$row->rate;
          $budgetfabricprod['amount']=	$row->amount;
          $budgetfabricprod['overhead_rate']=  $row->overhead_rate;
          $budgetfabricprod['overhead_amount']=  $row->overhead_amount;
          $budgetfabricprod['total_amount']=  $row->amount+$row->overhead_amount;
          $budgetfabricprod['budgetfabric']=	$desDropdown[$row->budget_fabric_id];
          $tot+=$row->amount;
          array_push($budgetfabricprods,$budgetfabricprod);
        }
        $dd=array('total'=>1,'rows'=>$budgetfabricprods,'footer'=>array(0=>array('id'=>'','budgetfabric'=>'','process_id'=>'','cons'=>'','rate'=>'Total','amount'=>$tot,'add_con'=>'')));
        echo json_encode(['list'=>$dd,'dropdown'=>$desDropdown]);
        //echo json_encode($budgetfabricprods);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		/*$fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
		$fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
		$fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');

		$fabricDescription=$this->budget
		->join('styles',function($join){
		$join->on('styles.id','=','budgets.style_id');
		})
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.style_id','=','budgets.style_id');
		})
		->join('autoyarns',function($join){
		$join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
		})
		->join('autoyarnratios',function($join){
		$join->on('autoyarns.id','=','autoyarnratios.autoyarn_id');
		})
		->join('compositions',function($join){
		$join->on('compositions.id','=','autoyarnratios.composition_id');
		})
		->join('budget_fabrics',function($join){
		$join->on('budget_fabrics.budget_id','=','budgets.id');
		$join->on('budget_fabrics.style_fabrication_id','=','style_fabrications.id');
		})
		->join('constructions',function($join){
		$join->on('constructions.id','=','autoyarns.construction_id');
		})
		->join('gmtsparts',function($join){
		$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
		})
		->join('style_gmts',function($join){
		$join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
		})
		->join('item_accounts', function($join) {
		$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
		})
		->where([['budgets.id','=',request('budget_id',0)]])
		->groupBy([
		'budget_fabrics.id',
		'budget_fabrics.gsm_weight',
		'style_fabrications.fabric_nature_id',
		'style_fabrications.fabric_look_id',
		'style_fabrications.fabric_shape_id',
		'item_accounts.item_description',
		'gmtsparts.name',
		'autoyarnratios.composition_id',
		'constructions.name',
		'compositions.name',
		'autoyarnratios.ratio',
		])
		->get([
		'budget_fabrics.id',
		'budget_fabrics.gsm_weight',
		'style_fabrications.fabric_nature_id',
		'style_fabrications.fabric_look_id',
		'style_fabrications.fabric_shape_id',
		'gmtsparts.name as gmtspart_name',
		'item_accounts.item_description',
		'autoyarnratios.composition_id',
		'constructions.name as construction',
		'compositions.name',
		'autoyarnratios.ratio',
		]);
		$fabricDescriptionArr=array();
		$fabricCompositionArr=array();
		foreach($fabricDescription as $row){
		$fabricDescriptionArr[$row->id]=$row->item_description.", ".$row->gmtspart_name.", ".$fabricnature[$row->fabric_nature_id].", ".$fabriclooks[$row->fabric_look_id].", ".$fabricshape[$row->fabric_shape_id].", ".$row->construction.", ".$row->gsm_weight;
		$fabricCompositionArr[$row->id][]=$row->name.", ".$row->ratio."%";
		}
		$desDropdown=array();
		foreach($fabricDescriptionArr as $key=>$val){
		$desDropdown[$key]=$val." ,".implode(",",$fabricCompositionArr[$key]);
		}
		echo json_encode($desDropdown);*/
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetFabricProdRequest $request) 
    {
        
        $parameter_id=0;
        if($request->production_process_id==4)
        {
          $parameter_id=8;
        }
        if($request->production_process_id==61)
        {
          $parameter_id=9;
        }
        $budget=$this->budget->find($request->budget_id);
        $overheadRate=0;
        if($parameter_id)
        {
            $keycontrol=$this->keycontrol
            ->join('keycontrol_parameters', function($join)  {
            $join->on('keycontrol_parameters.keycontrol_id', '=', 'keycontrols.id');
            })
            ->where([['parameter_id','=',$parameter_id]])
            ->where([['keycontrols.company_id','=',$request->company_id]])
            ->whereRaw('? between keycontrol_parameters.from_date and keycontrol_parameters.to_date', [$budget->budget_date])
            ->get([
            'keycontrol_parameters.value'
            ])->first();
            $overheadRate=$keycontrol->value;
        }
        $request->request->add(['overhead_rate' => $overheadRate]);

        $budgetfabricprod = $this->budgetfabricprod->create($request->except(['id']));
        $totalCost=$this->budget->totalCost($request->budget_id);
        if ($budgetfabricprod) {
        return response()->json(array('success' => true, 'id' => $budgetfabricprod->id, 'message' => 'Save Successfully','totalcost' => $totalCost), 200);
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
        
        $budgetfabricprod = $this->budgetfabricprod->find($id);
        $budgetfabric=$this->budgetfabric->find($budgetfabricprod->budget_fabric_id);
        $budgetfabricprod->req_cons=$budgetfabric->fabric_cons;
        $companyArr=$this->getCompany($budgetfabricprod->production_process_id);
        $row ['fromData'] = $budgetfabricprod;
        $dropdown['att'] = '';
        $row ['dropDown'] = $dropdown;
        $row ['companyArr'] = $companyArr;
        echo json_encode($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BudgetFabricProdRequest $request, $id) {
      $parameter_id=0;
        if($request->production_process_id==4)
        {
          $parameter_id=8;
        }
        if($request->production_process_id==61)
        {
          $parameter_id=9;
        }
        $budget=$this->budget->find($request->budget_id);
        $overheadRate=0;
        if($parameter_id)
        {
            $keycontrol=$this->keycontrol
            ->join('keycontrol_parameters', function($join)  {
            $join->on('keycontrol_parameters.keycontrol_id', '=', 'keycontrols.id');
            })
            ->where([['parameter_id','=',$parameter_id]])
            ->where([['keycontrols.company_id','=',$request->company_id]])
            ->whereRaw('? between keycontrol_parameters.from_date and keycontrol_parameters.to_date', [$budget->budget_date])
            ->get([
            'keycontrol_parameters.value'
            ])->first();
            $overheadRate=$keycontrol->value;
        }
        $request->request->add(['overhead_rate' => $overheadRate]);
        
        $budgetfabricprod = $this->budgetfabricprod->update($id, $request->except(['id']));
        
		    $totalCost=$this->budget->totalCost($request->budget_id);
        if ($budgetfabricprod) {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully','totalcost' => $totalCost), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if ($this->budgetfabricprod->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }
	public function getCons()
  {
		 $rows =$this->budgetfabric->selectRaw(
			'budget_fabrics.id,
			sum(budget_fabric_cons.grey_fab) as req_cons'
			)
			->leftJoin('budget_fabric_cons', function($join) {
			$join->on('budget_fabric_cons.budget_fabric_id', '=', 'budget_fabrics.id');
			})
			->where([['budget_fabrics.id','=',request('budget_fabric_id',0)]])
			->groupBy([
			'budget_fabrics.id',
			])
			->get();
			echo json_encode($rows);
	}
  public function processChange()
  {
     $companyArr=$this->getCompany(request('production_process_id',0));
     echo json_encode($companyArr);
  }

  private function getCompany($production_process_id){
    $nature_id=0;
    if($production_process_id==4){
    $nature_id=10;
    }
    if($production_process_id==61){
    $nature_id=11;
    }
    $company=$this->company->where([['nature_id','=',$nature_id]])->get();
    $companyArr=array();
    if(!$nature_id){
      $companyArr=array_pluck($this->company->get(),'name','id');
    }else{
      $companyArr=array_pluck($company,'name','id');
    }

    return $companyArr; 
  }

  
}
