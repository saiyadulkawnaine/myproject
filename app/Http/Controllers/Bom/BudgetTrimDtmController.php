<?php

namespace App\Http\Controllers\Bom;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Bom\BudgetTrimDtmRepository;
use App\Repositories\Contracts\Bom\BudgetTrimRepository;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Library\Template;
use App\Http\Requests\Bom\BudgetTrimDtmRequest;

class BudgetTrimDtmController extends Controller {

    private $budgettrimdtm;
    private $budgettrim;
  	private $budget;
  	private $stylegmtcolorsize;
  	private $salesordergmtcolorsize;
  	private $color;

    public function __construct(BudgetTrimDtmRepository $budgettrimdtm,BudgetTrimRepository $budgettrim,BudgetRepository $budget,StyleGmtColorSizeRepository $stylegmtcolorsize,SalesOrderGmtColorSizeRepository $salesordergmtcolorsize,ColorRepository $color) {
        $this->budgettrimdtm = $budgettrimdtm;
        $this->budgettrim = $budgettrim;
      	$this->budget = $budget;
      	$this->stylegmtcolorsize = $stylegmtcolorsize;
        $this->salesordergmtcolorsize = $salesordergmtcolorsize;
    	$this->color = $color;
        $this->middleware('auth');
        $this->middleware('permission:view.budgettrimdtms',   ['only' => ['create', 'index','show']]);
       	$this->middleware('permission:create.budgettrimdtms', ['only' => ['store']]);
        $this->middleware('permission:edit.budgettrimdtms',   ['only' => ['update']]);
        $this->middleware('permission:delete.budgettrimdtms', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
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
			
			$fabric=$this->budget
			->join('budget_fabrics',function($join){
			$join->on('budget_fabrics.budget_id','=','budgets.id');
			})
			->join('budget_trims',function($join){
			$join->on('budget_trims.budget_id','=','budgets.id');
			})
			->join('budget_fabric_cons',function($join){
			$join->on('budget_fabrics.id','=','budget_fabric_cons.budget_fabric_id')
			->whereNull('budget_fabric_cons.deleted_at');
			})
			->leftJoin('budget_trim_dtms',function($join){
			$join->on('budget_trim_dtms.budget_trim_id','=','budget_trims.id')
			->on('budget_trim_dtms.budget_fabric_id','=','budget_fabrics.id')
			->on('budget_trim_dtms.fabric_color','=','budget_fabric_cons.fabric_color')
			->whereNull('budget_trim_dtms.deleted_at');
			})
			->join('colors',function($join){
			$join->on('colors.id','=','budget_fabric_cons.fabric_color')
			->whereNull('budget_fabric_cons.deleted_at');
			})
			->where([['budgets.id','=',request('budget_id',0)]])
			->groupBy([
			'budget_fabrics.budget_id',
			'budget_fabrics.id',
			'budget_fabric_cons.fabric_color',
			'colors.name',
			'budget_trim_dtms.qty',
			])
			->OrderBy('budget_fabrics.id','asc')
			->get([
			'budget_fabrics.budget_id',
			'budget_fabrics.id as budget_fabric_id',
			'budget_fabric_cons.fabric_color',
			'colors.name as color_name',
			'budget_trim_dtms.qty'
			])->map(function ($fabric, $key) use($desDropdown) {
				 $fabric->fabric_description=$desDropdown[$fabric->budget_fabric_id];
				 return $fabric;
			});
        $dropdown['trimdtmscs'] = "'".Template::loadView('Bom.BudgetTrimDtmMatrix',['colorsizes'=>$fabric,'budget_trim_id'=>request('id',0)])."'";
		$row ['dropDown'] = $dropdown;
        echo json_encode($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetTrimDtmRequest $request) {
		$budget_id=0;
		foreach($request->budget_trim_id as $index=>$budget_trim_id){
				if($request->qty[$index]){
				$budgettrimdtm = $this->budgettrimdtm->updateOrCreate(
				['budget_trim_id' => $budget_trim_id,'budget_fabric_id' => $request->budget_fabric_id[$index],'fabric_color' => $request->fabric_color[$index]],
				['qty' => $request->qty[$index]]
				);
				}
			}
		return response()->json(array('success' => true, 'id' => $budgettrimdtm->id, 'budget_id' => $budget_id, 'message' => 'Save Successfully'), 200);

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
    public function update(BudgetTrimDtmRequest $request, $id) {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if ($this->budgettrimdtm->delete($id)) {
            return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }
    }

}
