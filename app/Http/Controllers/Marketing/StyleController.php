<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Contracts\Marketing\StylePkgRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\ProductdepartmentRepository;
use App\Repositories\Contracts\Util\SeasonRepository;
use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Contracts\Util\EmbelishmentRepository;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Contracts\Util\SizeRepository;
use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Contracts\Util\YarncountRepository;
use App\Repositories\Contracts\Util\GmtssampleRepository;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Contracts\Util\BuyerNatureRepository;
use App\Library\Template;
use App\Http\Requests\StyleRequest;

class StyleController extends Controller {

	private $style;
	private $buyer;
	private $productdepartment;
	private $season;
	private $uom;
	private $team;
	private $teammember;
	private $itemaccount;
	private $embelishment;
	private $embelishmenttype;
	private $color;
	private $size;
	private $stylegmts;
	private $gmtspart;
	private $autoyarn;
	private $yarncount;
	private $gmtssample;
	private $currency;
	private $itemclass;
	private $buyernature;

    public function __construct(
    	StyleRepository $style, 
    	BuyerRepository $buyer, 
    	ProductdepartmentRepository $productdepartment,
    	SeasonRepository $season,
    	UomRepository $uom,
    	TeamRepository $team,
    	TeammemberRepository $teammember,
    	ItemAccountRepository $itemaccount,
    	EmbelishmentRepository $embelishment,
    	EmbelishmentTypeRepository $embelishmenttype,
    	ColorRepository $color,
    	SizeRepository $size,
    	StyleGmtsRepository $stylegmts,
    	GmtspartRepository $gmtspart,
    	AutoyarnRepository $autoyarn, 
        YarncountRepository $yarncount,
        GmtssampleRepository $gmtssample,
        CurrencyRepository $currency,
        ItemclassRepository $itemclass,
        BuyerNatureRepository $buyernature,
        StylePkgRepository $stylepkg
    ) {
        $this->style = $style;
        $this->stylepkg = $stylepkg;
        $this->buyer = $buyer;
        $this->productdepartment = $productdepartment;
        $this->season = $season;
        $this->uom = $uom;
        $this->team = $team;
        $this->teammember = $teammember;
        $this->itemaccount = $itemaccount;
        $this->embelishment = $embelishment;
        $this->embelishmenttype = $embelishmenttype;
        $this->color = $color;
        $this->size = $size;
        $this->stylegmts = $stylegmts;
        $this->gmtspart = $gmtspart;
        $this->autoyarn = $autoyarn;
        $this->yarncount = $yarncount;
        $this->gmtssample   = $gmtssample;
        $this->currency     = $currency;
        $this->itemclass = $itemclass;
        $this->buyernature = $buyernature;
        $this->middleware('auth');
        $this->middleware('permission:view.styles',   ['only' => ['create', 'index','show']]);
        $this->middleware('permission:create.styles', ['only' => ['store']]);
        $this->middleware('permission:edit.styles',   ['only' => ['update']]);
        $this->middleware('permission:delete.styles', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		//$rows=$this->style->getAll();
		return response()->json($this->style->getAll()->take(1000)->map(function($rows){
			$rows->receivedate=date("d-M-Y",strtotime($rows->receive_date));
			$rows->buyer=$rows->buyer_name;
			$rows->deptcategory=$rows->dept_category_name;
			$rows->season=$rows->season_name;
			$rows->uom=$rows->uom_name;
			$rows->team=$rows->team_name;
			$rows->teammember=$rows->team_member_name;
			$rows->productdepartment=$rows->department_name;
			return $rows;
		}));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
		$buyinghouses=array_prepend(array_pluck($this->buyernature->getBuyingHouses(),'name','id'),'-Select-',0);
		$buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'-Select-',0);
		$deptcategory=array_prepend(config('bprs.deptcategory'),'-Select-',0);
		$productdepartment=array_prepend(array_pluck($this->productdepartment->get(),'department_name','id'),'-Select-',0);
		$season=array_prepend(array_pluck($this->season->get(),'name','id'),'-Select-',0);
		$uom=array_prepend(array_pluck($this->uom->get(),'code','id'),'-Select-',0);
		$fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
		$team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-',0);
		$itemaccount=array_prepend(array_pluck($this->itemaccount->where([['item_accounts.itemcategory_id','=',21]])->get(),'item_description','id'),'-Select-','');
		$itemcomplexity=array_prepend(config('bprs.gmtcomplexity'),'-Select-','');
		$embelishment=array_prepend(array_pluck($this->embelishment->getEmbelishments(),'name','id'),'-Select-','');
		$embelishmenttype=array_prepend(array_pluck($this->embelishmenttype->getEmbelishmentTypes(),'name','id'),'','');
		$aoptype=array_prepend(array_pluck($this->embelishmenttype->getAopTypes(),'name','id'),'-Select-','');
		$stylepkg=array_pluck($this->stylepkg->get(),'name','id');
		$color=array_pluck($this->color->get(),'name','id');
		$size=array_pluck($this->size->get(),'name','id');
		$teammember = array_prepend(array_pluck($this->teammember->leftJoin('users', function($join) use ($request) {
		$join->on('teammembers.user_id', '=', 'users.id');
		})
		->get([
		'teammembers.id',
		'users.name',
		]),'name','id'),'-Select-',0);

		$style=array_prepend(array_pluck($this->style->get(),'style_description','id'),'-Select-','');
		$stylegmts = array_prepend(array_pluck($this->stylegmts->leftJoin('item_accounts', function($join) use ($request) {
		$join->on('style_gmts.item_account_id', '=', 'item_accounts.id');
		})
		->where('item_accounts.itemcategory_id','=',21)
		->get([
		'style_gmts.id',
		'item_accounts.item_description',
		]),'item_description','id'),'-Select-',0);

		$gmtspart=array_prepend(array_pluck($this->gmtspart->get(),'name','id'),'','');

		$autoyarn=$this->autoyarn->join('autoyarnratios', function($join) use ($request) {
		$join->on('autoyarns.id', '=', 'autoyarnratios.autoyarn_id');
		})
		->join('constructions', function($join) use ($request) {
		$join->on('autoyarns.construction_id', '=', 'constructions.id');
		})
		->join('compositions',function($join){
		$join->on('compositions.id','=','autoyarnratios.composition_id');
		})
		->get([
		'autoyarns.*',
		'constructions.name',
		'compositions.name as composition_name',
		'autoyarnratios.ratio'
		]);

		$fabricDescriptionArr=array();
		$fabricCompositionArr=array();
		foreach($autoyarn as $row){
		$fabricDescriptionArr[$row->id]=$row->name;
		$fabricCompositionArr[$row->id][]=$row->composition_name." ".$row->ratio."%";
		}
		$desDropdown=array();
		foreach($fabricDescriptionArr as $key=>$val){
		$desDropdown[$key]=$val.",".implode(",",$fabricCompositionArr[$key]);
		}

		$yarncount=array_prepend($this->yarncount->getForCombo(),'-Select-','');
		$materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
		$fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
		$fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
		$yesno=config('bprs.yesno');
		$gmtssample=array_prepend(array_pluck($this->gmtssample->get(),'name','id'),'-Select-','');
		$currency=array_prepend(array_pluck($this->currency->get(),'name','id'),'-Select-','');
		$fabricinstruction=array_prepend(config('bprs.fabricinstructions'),'-Select-','');
		$orderstage=array_prepend(config('bprs.orderstage'),'-Select-','');
		$itemclass=array_prepend(array_pluck($this->itemclass->get(),'name','id'),'-Select-','');
		$assortment=array_prepend(config('bprs.assortment'),'-Select-','');
		$gmtcategory=array_prepend(config('bprs.gmtcategory'),'-Select-','');
		$dyetype=array_prepend(config('bprs.dyetype'),'-Select-','');
		$embelishmentsize=array_prepend(config('bprs.embelishmentsize'),'-Select-','');

		return Template::loadView('Marketing.Style', [
			'buyer'=>$buyer,
			'deptcategory'=>$deptcategory,
			'productdepartment'=>$productdepartment,
			'season'=>$season,
			'uom'=>$uom,
			'team'=>$team,
			'teammember'=>$teammember,
			'itemaccount'=>$itemaccount,
			'itemcomplexity'=>$itemcomplexity,
			'embelishment'=>$embelishment,
			'embelishmenttype'=>$embelishmenttype,
			'color'=>$color,
			'size'=>$size,
			'style'=>$style,
			'stylegmts'=>$stylegmts,
			'gmtspart'=>$gmtspart,
			'autoyarn'=>$desDropdown,
			'yarncount'=>$yarncount,
			'materialsourcing'=>$materialsourcing,
			'fabricnature'=>$fabricnature,
			'fabriclooks'=>$fabriclooks,
			'yesno'=>$yesno,
			'gmtssample'=>$gmtssample,
			'currency'=>$currency,
			'fabricinstruction'=>$fabricinstruction,
			'orderstage'=>$orderstage,
			'itemclass'=>$itemclass,
			'assortment'=>$assortment,
			'gmtcategory'=>$gmtcategory,
			'fabricshape'=>$fabricshape,
			'aoptype'=>$aoptype,
			'dyetype'=>$dyetype,
			'embelishmentsize'=>$embelishmentsize,
			'buyinghouses'=>$buyinghouses,
			'stylepkg'=>$stylepkg
		]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StyleRequest $request) {
        $style = $this->style->create($request->except(['id']));
        if ($style) 
        {
            return response()->json(array('success' => true, 'id' => $style->id, 'message' => 'Save Successfully'), 200);
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
		$style = $this->style->find($id);
		$style->ship_date=date('Y-m-d',strtotime($style->ship_date));
		$row ['fromData'] = $style;
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
    public function update(StyleRequest $request, $id) {
        $style = $this->style->update($id, $request->except(['id']));
        if ($style) 
        {
            return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if ($this->style->delete($id)) {
             return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
        }else{
			 return response()->json(array('success' => false, 'message' => 'Delete Not Successfull Because Subsequent Entry Found'), 200);
		}
    }
	public function upload(StyleRequest $request)
	{
		if($request->uploaddata !='undefined')
		{
			$input['flie_src'] =time().'.'.$request->uploaddata->getClientOriginalExtension();
			$request->uploaddata->move(public_path('images'), $input['flie_src']);
			$this->style->update($request->style_id,$input);
		}
		
		if($request->uploadfiledata !='undefined')
		{
			$input['file_name'] = time().'.'.$request->uploadfiledata->getClientOriginalExtension();
			$request->uploadfiledata->move(public_path('images'),$input['file_name']); 
			$this->style->update($request->style_id,$input);
		}
		return response()->json(array('success' => true, 'message' => 'Uploaded Successfully'), 200);
	}
	
	public function getstyle(Request $request) 
	{
		return $this->style->where([['style_ref', 'LIKE', '%'.$request->q.'%']])->orderBy('style_ref', 'asc')->get(['style_ref as name']);
	}
	
	public function getstyledescription(Request $request)
	{
		return $this->style->where([['style_description', 'LIKE', '%'.$request->q.'%']])->orderBy('style_description', 'asc')->get(['style_description as name']);
	}

	public function getcontact(Request $request) 
	{
		return $this->style
		->where([['contact', 'LIKE', '%'.$request->q.'%']])
		->where([['buying_agent_id', '=', $request->buying_agent_id]])
		->orderBy('contact', 'asc')->get(['contact as name']);
	}

	public function getOldStyle(){
			return response()->json(
			$this->style
			->leftJoin('buyers', function($join)  {
				$join->on('styles.buyer_id', '=', 'buyers.id');
			})
			->leftJoin('buyers as buyingagents', function($join)  {
				$join->on('styles.buying_agent_id', '=', 'buyingagents.id');
			})
			->leftJoin('uoms', function($join)  {
				$join->on('styles.uom_id', '=', 'uoms.id');
			})
			->leftJoin('seasons', function($join)  {
				$join->on('styles.season_id', '=', 'seasons.id');
			})
			->leftJoin('teams', function($join)  {
				$join->on('styles.team_id', '=', 'teams.id');
			})
			->leftJoin('teammembers', function($join)  {
				$join->on('styles.teammember_id', '=', 'teammembers.id');
			})
			->leftJoin('users', function($join)  {
				$join->on('users.id', '=', 'teammembers.user_id');
			})
			->leftJoin('productdepartments', function($join)  {
				$join->on('productdepartments.id', '=', 'styles.productdepartment_id');
			})
			->when(request('buyer_id'), function ($q) {
				return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
			})
			->when(request('style_ref'), function ($q) {
				return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
			})
			->when(request('style_description'), function ($q) {
				return $q->where('styles.style_description', 'like', '%'.request('style_description', 0).'%');
			})
			->orderBy('styles.id','desc')
			->get([
				'styles.*',
				'buyers.code as buyer_name',
				'uoms.name as uom_name',
				'seasons.name as season_name',
				'teams.name as team_name',
				'users.name as team_member_name',
				'productdepartments.department_name',
				'buyingagents.name as buying_agent'
			])
			->map(function($rows){
				$rows->receivedate=date("d-M-Y",strtotime($rows->receive_date));
				$rows->buyer=$rows->buyer_name;
				$rows->deptcategory=$rows->dept_category_name;
				$rows->season=$rows->season_name;
				$rows->uom=$rows->uom_name;
				$rows->team=$rows->team_name;
				$rows->teammember=$rows->team_member_name;
				$rows->productdepartment=$rows->department_name;
				return $rows;
			})
		);
		
	}

}
