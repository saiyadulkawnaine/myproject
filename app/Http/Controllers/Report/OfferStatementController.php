<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Template;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Contracts\Util\BuyerBranchRepository;
use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Contracts\Util\TeammemberRepository;
class OfferStatementController extends Controller
{
	private $mktcost;
	private $company;
	private $buyer;
	private $team;
	private $teammember;
	private $buyerbranch;
	public function __construct(MktCostRepository $mktcost,CompanyRepository $company,BuyerRepository $buyer,TeamRepository $team,TeammemberRepository $teammember,BuyerBranchRepository $buyerbranch)
    {
		$this->mktcost=$mktcost;
		$this->company  = $company;
		$this->buyer    = $buyer;
		$this->team = $team;
        $this->teammember = $teammember;
		$this->buyerbranch = $buyerbranch;
		$this->middleware('auth');
		$this->middleware('permission:view.offerstatementreports',   ['only' => ['create', 'index','show']]);
    }
    public function index() {
		$company=array_prepend(array_pluck($this->company->where([['nature_id','=',1]])->get(),'name','id'),'-Select-','');
		$buyer=array_prepend(array_pluck($this->buyer->buyers(),'name','id'),'-Select-',0);
		$team=array_prepend(array_pluck($this->team->get(),'name','id'),'-Select-',0);
		 $teammember = array_prepend(array_pluck($this->teammember->leftJoin('users', function($join)  {
		$join->on('teammembers.user_id', '=', 'users.id');
		})
		->get([
			'teammembers.id',
			'users.name',
		]),'name','id'),'-Select-',0);
      return Template::loadView('Report.OfferStatement',['company'=>$company,'buyer'=>$buyer,'team'=>$team,'teammember'=>$teammember]);
    }
	public function reportData() {
      $data = DB::table("mkt_costs")
          ->select("mkt_costs.*",
				   "buyers.name as buyer_name",
				   /* "styles.id as style_id", */
				    "styles.style_ref",
					"styles.style_description",
					"styles.flie_src",
					"seasons.name as season_name",
					"productdepartments.department_name",
					"uoms.code as uom_code",
					'mkt_cost_quote_prices.quote_price as price',
					'mkt_cost_target_prices.target_price as  t_price',
					'users.name as team_member',
				DB::raw("(SELECT SUM(mkt_cost_fabrics.amount) FROM mkt_cost_fabrics
				WHERE mkt_cost_fabrics.mkt_cost_id = mkt_costs.id
				GROUP BY mkt_cost_fabrics.mkt_cost_id) as fab_amount"),
								
				DB::raw("(SELECT SUM(mkt_cost_yarns.amount) FROM mkt_cost_yarns
				WHERE mkt_cost_yarns.mkt_cost_id = mkt_costs.id
				GROUP BY mkt_cost_yarns.mkt_cost_id) as yarn_amount"),
				
				DB::raw("(SELECT SUM(mkt_cost_fabric_prods.amount) FROM mkt_cost_fabric_prods
                WHERE mkt_cost_fabric_prods.mkt_cost_id = mkt_costs.id
                GROUP BY mkt_cost_fabric_prods.mkt_cost_id) as prod_amount"),
				
				DB::raw("(SELECT SUM(mkt_cost_trims.amount) FROM mkt_cost_trims
				WHERE mkt_cost_trims.mkt_cost_id = mkt_costs.id
				GROUP BY mkt_cost_trims.mkt_cost_id) as trim_amount"),
				
				DB::raw("(SELECT SUM(mkt_cost_embs.amount) FROM mkt_cost_embs
				WHERE mkt_cost_embs.mkt_cost_id = mkt_costs.id
				GROUP BY mkt_cost_embs.mkt_cost_id) as emb_amount"),
				
				DB::raw("(SELECT SUM(mkt_cost_cms.amount) FROM mkt_cost_cms
				WHERE mkt_cost_cms.mkt_cost_id = mkt_costs.id
				GROUP BY mkt_cost_cms.mkt_cost_id) as cm_amount"),
				
				DB::raw("(SELECT SUM(mkt_cost_others.amount) FROM mkt_cost_others
				WHERE mkt_cost_others.mkt_cost_id = mkt_costs.id
				GROUP BY mkt_cost_others.mkt_cost_id) as other_amount"),
				
				DB::raw("(SELECT SUM(mkt_cost_commercials.amount) FROM mkt_cost_commercials
				WHERE mkt_cost_commercials.mkt_cost_id = mkt_costs.id
				GROUP BY mkt_cost_commercials.mkt_cost_id) as commercial_amount"),
				
				DB::raw("(SELECT SUM(mkt_cost_profits.amount) FROM mkt_cost_profits
				WHERE mkt_cost_profits.mkt_cost_id = mkt_costs.id
				GROUP BY mkt_cost_profits.mkt_cost_id) as profit_amount"),
				
				DB::raw("(SELECT SUM(mkt_cost_commissions.amount) FROM mkt_cost_commissions
				WHERE mkt_cost_commissions.mkt_cost_id = mkt_costs.id
				GROUP BY mkt_cost_commissions.mkt_cost_id) as commission_amount"),
				
				DB::raw("(SELECT SUM(mkt_cost_commissions.rate) FROM mkt_cost_commissions
				WHERE mkt_cost_commissions.mkt_cost_id = mkt_costs.id
				GROUP BY mkt_cost_commissions.mkt_cost_id) as commission_rate")
		   )
		   ->join('styles',function($join){
			   $join->on('mkt_costs.style_id','=','styles.id');
		   })
		   ->join('buyers',function($join){
			   $join->on('styles.buyer_id','=','buyers.id');
		   })
		    ->join('seasons',function($join){
			   $join->on('styles.season_id','=','seasons.id');
		   })
		    ->join('productdepartments',function($join){
			   $join->on('styles.productdepartment_id','=','productdepartments.id');
		   })
		   ->join('uoms',function($join){
			   $join->on('styles.uom_id','=','uoms.id');
		   })
		    ->join('teammembers',function($join){
			   $join->on('teammembers.id','=','styles.teammember_id');
		   })
		     ->join('users',function($join){
			   $join->on('users.id','=','teammembers.user_id');
		   })
		    ->leftJoin('mkt_cost_quote_prices',function($join){
			   $join->on('mkt_costs.id','=','mkt_cost_quote_prices.mkt_cost_id');
		   })
		    ->leftJoin('mkt_cost_target_prices',function($join){
			   $join->on('mkt_costs.id','=','mkt_cost_target_prices.mkt_cost_id');
		   })
		   ->when(request('buyer_id'), function ($q) {
			return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		   })
		   ->when(request('team_id'), function ($q) {
			return $q->where('styles.team_id', '=', request('team_id', 0));
		   })
		    ->when(request('teammember_id'), function ($q) {
			return $q->where('styles.teammember_id', '=', request('teammember_id', 0));
		   })
		   ->when(request('style_ref'), function ($q) {
			return $q->where('styles.style_ref', 'LIKE', "%".request('style_ref', 0)."%");
		   })
		   ->when(request('date_from'), function ($q) {
			return $q->where('mkt_costs.est_ship_date', '>=',request('date_from', 0));
		   })
		   ->when(request('date_to'), function ($q) {
			return $q->where('mkt_costs.est_ship_date', '<=',request('date_to', 0));
		   })
		   ->when(request('confirm_from'), function ($q) {
			return $q->where('mkt_cost_quote_prices.confirm_date', '>=',request('confirm_from', 0));
		   })
		   ->when(request('confirm_to'), function ($q) {
			return $q->where('mkt_cost_quote_prices.confirm_date', '<=',request('confirm_to', 0));
		   })
		    ->when(request('costing_from'), function ($q) {
			return $q->where('mkt_costs.quot_date', '>=',request('costing_from', 0));
		   })
		   ->when(request('costing_to'), function ($q) {
			return $q->where('mkt_costs.quot_date', '<=',request('costing_to', 0));
		   })
		   ->orderBy('mkt_costs.id','desc')
		   ->get();
		   $datas=array();
		   $totOffer=0;
		   $totAmt=0;
		   foreach($data as $row){
			   $row->amount=number_format($row->price*$row->offer_qty,2,'.',',');
			   $row->offer_qty=number_format($row->offer_qty,0,'.',',');
			   $row->price=number_format($row->price,4,'.',',');
			    $row->est_ship_date=date("d-M-Y",strtotime($row->est_ship_date));
				$row->quot_date=date("d-M-Y",strtotime($row->quot_date));
			   $row->price_bfr_commission=$row->fab_amount+$row->yarn_amount+$row->prod_amount+$row->trim_amount+$row->emb_amount+$row->cm_amount+$row->other_amount+$row->commercial_amount+$row->profit_amount;
			    $row->price_aft_commission=number_format(($row->fab_amount+$row->yarn_amount+$row->prod_amount+$row->trim_amount+$row->emb_amount+$row->cm_amount+$row->other_amount+$row->commercial_amount+$row->profit_amount+$row->commission_amount)/$row->costing_unit_id,4,'.',',');
				$commission_on_quoted_price_dzn=((($row->price*$row->costing_unit_id))*$row->commission_rate)/100;
				$commission_on_quoted_price_pcs=$commission_on_quoted_price_dzn/$row->costing_unit_id;
				$row->commission_on_quoted_price_dzn=number_format($commission_on_quoted_price_dzn,4,'.',',');
				$row->commission_on_quoted_price_pcs=number_format($commission_on_quoted_price_pcs,4,'.',',');

				$row->total_cost=$row->fab_amount+$row->yarn_amount+$row->prod_amount+$row->trim_amount+$row->emb_amount+$row->cm_amount+$row->other_amount+$row->commercial_amount+$commission_on_quoted_price_dzn;
				
				$cost_per_pcs=$row->total_cost/$row->costing_unit_id;
				$row->cost_per_pcs=number_format($cost_per_pcs,4,'.',',');
				
				$row->comments=($row->cost_per_pcs > $row->price)?"Less Than Cost":"";
				$row->cm=number_format(($row->price*$row->costing_unit_id)-($row->total_cost-$row->cm_amount),4,'.',',');
				
				$totOffer+=$row->offer_qty;
		        $totAmt+= $row->amount;
			   array_push($datas,$row);
			   
		   }
		   		$dd=array('total'=>1,'rows'=>$datas,'footer'=>array(0=>array('ID'=>'','buyer_name'=>'','style_ref'=>'','style_description'=>'','season_name'=>'','department_name'=>'','offer_qty'=>'','uom_code'=>'','price'=>'','amount'=>'')));

	
		echo json_encode($dd);
    }
	public function getpdf(){
		/*$id=request('id',0);
		//$id=ltrim(',',$id)
		$view= \View::make('Defult.Report.MktCostofferPdf',['id'=>$id]);
	    $html_content=$view->render();
		echo $html_content;*/
		$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	$pdf->SetFont('helvetica', 'B', 12);
	$pdf->AddPage();
	$pdf->SetY(10);
	$txt = "Lithe Group";
	//$pdf->Write(0, 'Lithe Group', '', 0, 'C', true, 0, false, false, 0);
	$pdf->SetY(5);
	$pdf->Text(90, 5, $txt);
	$pdf->SetY(10);
	$pdf->Text(90, 10, "Price Offer");
	$pdf->SetFont('helvetica', '', 8);
	//$pdf->SetTitle('Price Offer');
	$id=request('id',0);
	$idarray=explode(',',$id);
	foreach($idarray as $key=>$mkid){
		$mktcost[$mkid]['QuotedPrice']=$this->mktcost->totalQuotePrice($mkid);
	}

	$costingunit=array_prepend(config('bprs.costingunit'),'-Select-','');
	//$buyer=array_prepend(array_pluck($this->buyer->get(),'name','id'),'-Select-','');
	$buyercontact=array_prepend(array_pluck($this->buyerbranch->get(),'contact_person','buyer_id'),'-Select-','');

	$mktcosts=array();
	$rows=$this->mktcost->join('styles',function($join){
	$join->on('styles.id','=','mkt_costs.style_id');
	})
	->join('buyers',function($join){
	$join->on('buyers.id','=','styles.buyer_id');
	})
	->join('teams',function($join){
	$join->on('teams.id','=','styles.team_id');
	})
	->join('currencies',function($join){
	$join->on('currencies.id','=','mkt_costs.currency_id');
	})
	->join('uoms',function($join){
	$join->on('uoms.id','=','styles.uom_id');
	})
	->join('seasons',function($join){
	$join->on('seasons.id','=','styles.season_id');
	})
	->whereIn('mkt_costs.id',$idarray)
	->get([
	'mkt_costs.*',
	'styles.style_ref',
	'styles.flie_src',
	'buyers.name as buyer_name',
	'buyers.buying_agent_id',
	'teams.name as team_name',
	'currencies.code as currency_code',
	'uoms.code as uom_code',
	'seasons.name as season_name'
	]);
	foreach($rows as $row){
	$mktcost[$row->id]['id']=	$row->id;
	$mktcost[$row->id]['costingunit']=	$costingunit[$row->costing_unit_id];
	$mktcost[$row->id]['costingunitqty']=$row->costing_unit_id;
	$mktcost[$row->id]['quotdate']=	$row->quot_date;
	$mktcost[$row->id]['offerqty']=	$row->offer_qty;
	$mktcost[$row->id]['estshipdate']=	date("d-m-Y",strtotime($row->est_ship_date));
	$mktcost[$row->id]['style']=	$row->style_ref;
	$mktcost[$row->id]['currency']=	$row->currency_code;
	$mktcost['buyer']=	$row->buyer_name;
	$mktcost['buyer_agent']=	$buyercontact[$row->buying_agent_id];
	}
	
	
	//$mktcost['QuotedPrice']=$this->mktcost->totalQuotePrice($id);
	
	
	
	
	
	//$mktcost['fabrics']=$this->mktcost->fabricCost($id);
	
	
	$materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
	$fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
	$fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
	$fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
	$fabricDescription=$this->mktcost->selectRaw(
	'style_fabrications.id,
	constructions.name as construction,
	autoyarnratios.composition_id,
	compositions.name,
	autoyarnratios.ratio'
	)
	->join('styles',function($join){
	$join->on('styles.id','=','mkt_costs.style_id');
	})
	->join('style_fabrications',function($join){
	$join->on('style_fabrications.style_id','=','mkt_costs.style_id');
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
	->join('constructions',function($join){
	$join->on('constructions.id','=','autoyarns.construction_id');
	})
	->whereIn('mkt_costs.id',$idarray)
	->get();
	$fabricDescriptionArr=array();
	$fabricCompositionArr=array();
	foreach($fabricDescription as $row){
	$fabricDescriptionArr[$row->id]=$row->construction;
	$fabricCompositionArr[$row->id][]=$row->name." ".$row->ratio."%";
	}
	$desDropdown=array();
	foreach($fabricDescriptionArr as $key=>$val){
	$desDropdown[$key]=$val." ".implode(",",$fabricCompositionArr[$key]);
	}
	
	$fabrics=$this->mktcost->selectRaw(
	'mkt_costs.id as mkt_cost_id,
	style_fabrications.id as style_fabrication_id,
	style_fabrications.material_source_id,
	style_fabrications.fabric_nature_id,
	style_fabrications.fabric_look_id,
	style_fabrications.fabric_shape_id,
	style_fabrications.is_narrow,
	style_gmts.id as style_gmt_id,
	gmtsparts.name as gmtspart_name,
	item_accounts.item_description,
	uoms.code as uom_name,
	mkt_cost_fabrics.gsm_weight,
	mkt_cost_fabrics.id
	'
	)
	->join('styles',function($join){
	$join->on('styles.id','=','mkt_costs.style_id');
	})
	->join('style_fabrications',function($join){
	$join->on('style_fabrications.style_id','=','mkt_costs.style_id');
	})
	->join('style_gmts',function($join){
	$join->on('style_gmts.id','=','style_fabrications.style_gmt_id');
	})
	->join('item_accounts', function($join) {
	$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
	})
	->join('gmtsparts',function($join){
	$join->on('gmtsparts.id','=','style_fabrications.gmtspart_id');
	})
	->join('autoyarns',function($join){
	$join->on('autoyarns.id','=','style_fabrications.autoyarn_id');
	})
	
	->join('uoms',function($join){
	$join->on('uoms.id','=','style_fabrications.uom_id');
	})
	->leftJoin('mkt_cost_fabrics',function($join){
	$join->on('mkt_cost_fabrics.mkt_cost_id','=','mkt_costs.id');
	$join->on('mkt_cost_fabrics.style_fabrication_id','=','style_fabrications.id');
	})
	->whereIn('mkt_costs.id',$idarray)
	->orderBy('mkt_cost_fabrics.id','asc')
	->get();
	$styleitems=array();
	foreach($fabrics as $row){
	$stylefabrication[$row->mkt_cost_id][$row->style_fabrication_id]['style_gmt']=	$row->item_description;
	$stylefabrication[$row->mkt_cost_id][$row->style_fabrication_id]['gmtspart']=	$row->gmtspart_name;
	$stylefabrication[$row->mkt_cost_id][$row->style_fabrication_id]['fabric_description']=	$desDropdown[$row->style_fabrication_id];
	$stylefabrication[$row->mkt_cost_id][$row->style_fabrication_id]['fabriclooks']=	$fabriclooks[$row->fabric_look_id];
	$stylefabrication[$row->mkt_cost_id][$row->style_fabrication_id]['gsm_weight']=	$row->gsm_weight;
	$styleitems[$row->mkt_cost_id][$row->style_gmt_id]=$row->item_description;
	}
	
	$mktcost['fabrics']=$stylefabrication;
	$mktcost['stylegmt']=$styleitems;
	$view= \View::make('Defult.Report.MktCostofferPdf',['mktcost'=>$mktcost,'idarray'=>$idarray]);
	$html_content=$view->render();
	$pdf->SetY(15);
	$pdf->WriteHtml($html_content, true, false,true,false,'');
	$filename = storage_path() . '/MktCostofferPdf.pdf';
	//echo $html_content;
	//$pdf->output($filename);
	$pdf->output($filename,'I');
	exit();
	//$pdf->output($filename,'F');
	//return response()->download($filename);
	}
}
