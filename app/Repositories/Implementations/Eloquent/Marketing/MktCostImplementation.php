<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Model\Marketing\MktCost;
use App\Traits\Eloquent\MsTraits;
class MktCostImplementation implements MktCostRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * MsSysUserImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(MktCost $model)
	{
		$this->model = $model;
	}
	
	public function totalFabricCost($id) {
		/*$fabric = $this->selectRaw(
			'mkt_costs.id,
			sum(mkt_cost_fabric_cons.cons) as qty,
			sum(mkt_cost_fabric_cons.amount) as amount'
			)
			->leftJoin('mkt_cost_fabrics', function($join) {
			$join->on('mkt_cost_fabrics.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->leftJoin('mkt_cost_fabric_cons', function($join) {
			$join->on('mkt_cost_fabric_cons.mkt_cost_fabric_id', '=', 'mkt_cost_fabrics.id');
			})
			->where([['mkt_costs.id','=',$id]])
			->groupBy([
			'mkt_costs.id',
			])
			->get()->first();*/
			$fabric = $this->selectRaw(
			'mkt_costs.id,
			sum(mkt_cost_fabrics.fabric_cons) as qty,
			sum(mkt_cost_fabrics.amount) as amount'
			)
			->leftJoin('mkt_cost_fabrics', function($join) {
			$join->on('mkt_cost_fabrics.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->where([['mkt_costs.id','=',$id]])
			->groupBy([
			'mkt_costs.id',
			])
			->get()->first();
			return $fabric->amount;
	}

	public function totalFabricCons($id) {
			$fabric = $this->selectRaw(
			'mkt_costs.id,
			sum(mkt_cost_fabrics.fabric_cons) as qty,
			sum(mkt_cost_fabrics.amount) as amount'
			)
			->leftJoin('mkt_cost_fabrics', function($join) {
			$join->on('mkt_cost_fabrics.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->where([['mkt_costs.id','=',$id]])
			->groupBy([
			'mkt_costs.id',
			])
			->get()->first();
			return $fabric->qty;
	}

	public function avgFabricProcessLoss($id) {
			$fabric = $this->selectRaw(
			'mkt_costs.id,
			avg(mkt_cost_fabric_cons.process_loss) as process_loss'
			)
			->leftJoin('mkt_cost_fabrics', function($join) {
			$join->on('mkt_cost_fabrics.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->leftJoin('mkt_cost_fabric_cons',function($join){
			$join->on('mkt_cost_fabric_cons.mkt_cost_fabric_id','=','mkt_cost_fabrics.id');
			})
			->where([['mkt_costs.id','=',$id]])
			->groupBy([
			'mkt_costs.id',
			])
			->get()->first();
			return $fabric->process_loss;
	}
	public function fabricCost($id) {
		$materialsourcing=array_prepend(config('bprs.materialsourcing'),'-Select-','');
        $fabricnature=array_prepend(config('bprs.fabricnature'),'-Select-','');
        $fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');
	    $fabricshape = array_prepend(config('bprs.fabricshape'),'-Select-','');
		$fabricDescription=$this->selectRaw(
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
		->where([['mkt_costs.id','=',$id]])
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
		$fabrics=$this
		->selectRaw(
			'mkt_costs.id as mkt_cost_id,
			style_fabrications.id as style_fabrication_id,
			style_fabrications.material_source_id,
			style_fabrications.fabric_nature_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			style_fabrications.is_narrow,
			gmtsparts.name as gmtspart_name,
			item_accounts.item_description,
			uoms.code as uom_name,
			mkt_cost_fabrics.gsm_weight,
			mkt_cost_fabrics.id,
			avg(mkt_cost_fabric_cons.req_cons) as req_cons,
			avg(mkt_cost_fabric_cons.rate) as rate
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
		->leftJoin('mkt_cost_fabric_cons',function($join){
		$join->on('mkt_cost_fabric_cons.mkt_cost_fabric_id','=','mkt_cost_fabrics.id');
		})
		
		->where([['mkt_costs.id','=',$id]])
		//->where([['style_fabrications.is_narrow','=',0]])
		->groupBy([
		'mkt_costs.id',
		'style_fabrications.id',
		'style_fabrications.material_source_id',
		'style_fabrications.fabric_nature_id',
		'style_fabrications.fabric_look_id',
		'style_fabrications.fabric_shape_id',
		'style_fabrications.is_narrow',
		'gmtsparts.name',
		'item_accounts.item_description',
		'uoms.code',
		'mkt_cost_fabrics.gsm_weight',
		'mkt_cost_fabrics.id',
		])
		->get();
		$stylefabrications=array();
		$stylenarrowfabrications=array();
        foreach($fabrics as $row){
			if($row->is_narrow==0){
			  $stylefabrication['id']=	$row->id;
			  $stylefabrication['mkt_cost_id']=	$row->mkt_cost_id;
			  $stylefabrication['style_fabrication_id']=	$row->style_fabrication_id;
			  $stylefabrication['style_gmt']=	$row->item_description;
			  $stylefabrication['gmtspart']=	$row->gmtspart_name;
			  $stylefabrication['fabric_description']=	$desDropdown[$row->style_fabrication_id];
			  $stylefabrication['uom_name']=	$row->uom_name;
			  $stylefabrication['materialsourcing']=	$materialsourcing[$row->material_source_id];
			  $stylefabrication['fabricnature']=	$fabricnature[$row->fabric_nature_id];
			  $stylefabrication['fabriclooks']=	$fabriclooks[$row->fabric_look_id];
			  $stylefabrication['fabricshape']=	$fabricshape[$row->fabric_shape_id];
			  $stylefabrication['gsm_weight']=	$row->gsm_weight;
			  $stylefabrication['req_cons']=	$row->req_cons;
			  $stylefabrication['rate']=	$row->rate;
			  $stylefabrication['amount']=	number_format($row->req_cons*$row->rate,4); 
			  array_push($stylefabrications,$stylefabrication);
			}else{
			  $stylefabrication['id']=	$row->id;
			  $stylefabrication['mkt_cost_id']=	$row->mkt_cost_id;
			  $stylefabrication['style_fabrication_id']=	$row->style_fabrication_id;
			  $stylefabrication['style_gmt']=	$row->item_description;
			  $stylefabrication['gmtspart']=	$row->gmtspart_name;
			  $stylefabrication['fabric_description']=	$desDropdown[$row->style_fabrication_id];
			  $stylefabrication['uom_name']=	$row->uom_name;
			  $stylefabrication['materialsourcing']=	$materialsourcing[$row->material_source_id];
			  $stylefabrication['fabricnature']=	$fabricnature[$row->fabric_nature_id];
			  $stylefabrication['fabriclooks']=	$fabriclooks[$row->fabric_look_id];
			  $stylefabrication['fabricshape']=	$fabricshape[$row->fabric_shape_id];
			  $stylefabrication['gsm_weight']=	$row->gsm_weight;
			  $stylefabrication['req_cons']=	$row->req_cons;
			  $stylefabrication['rate']=	$row->rate;
			  $stylefabrication['amount']=	number_format($row->req_cons*$row->rate,4); 
			  array_push($stylenarrowfabrications,$stylefabrication);
			}
    	}
		return array('main'=>$stylefabrications,'narrow'=>$stylenarrowfabrications);
	}
	
	public function totalYarnCost($id) {
		    $yarn = $this->selectRaw(
			'mkt_costs.id,
			sum(mkt_cost_yarns.cons) as qty,
			sum(mkt_cost_yarns.amount) as amount'
			)
			->leftJoin('mkt_cost_yarns', function($join) {
			$join->on('mkt_cost_yarns.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->where([['mkt_costs.id','=',$id]])
			->groupBy([
			'mkt_costs.id',
			])
			->get()->first();
			return $yarn->amount;
	}
	
	public function yarnCost($id) {
		$yarn = $this->selectRaw(
		'mkt_costs.id,
		mkt_cost_yarns.*'
		)
		->leftJoin('mkt_cost_yarns', function($join) {
		$join->on('mkt_cost_yarns.mkt_cost_id', '=', 'mkt_costs.id');
		})
		->where([['mkt_costs.id','=',$id]])
		->get();
		
		return $yarn;
	}
	
	public function totalFabricProdCost($id) {
		    $fabProd = $this->selectRaw(
			'mkt_costs.id,
			sum(mkt_cost_fabric_prods.cons) as qty,
			sum(mkt_cost_fabric_prods.amount) as amount'
			)
			->leftJoin('mkt_cost_fabric_prods', function($join) {
			$join->on('mkt_cost_fabric_prods.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->where([['mkt_costs.id','=',$id]])
			->groupBy([
			'mkt_costs.id',
			])
			->get()->first();
			return $fabProd->amount;
	}
	
	public function fabricProdCost($id) {
		    $fabProd = $this->selectRaw(
			'mkt_costs.id,
			production_processes.process_name,
			mkt_cost_fabric_prods.*'
			)
			->leftJoin('mkt_cost_fabric_prods', function($join) {
			$join->on('mkt_cost_fabric_prods.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->join('production_processes',function($join){
			$join->on('production_processes.id','=','mkt_cost_fabric_prods.production_process_id');
			})
			->where([['mkt_costs.id','=',$id]])
			->get();
			return $fabProd;
	}
	
	public function totalTrimCost($id) {
		    $trim = $this->selectRaw(
			'mkt_costs.id,
			sum(mkt_cost_trims.cons) as qty,
			sum(mkt_cost_trims.amount) as amount'
			)
			->leftJoin('mkt_cost_trims', function($join) {
			$join->on('mkt_cost_trims.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->where([['mkt_costs.id','=',$id]])
			->groupBy([
			'mkt_costs.id',
			])
			->get()->first();
			return $trim->amount;
	}
	
	public function TrimCost($id) {
		    $trims = $this->selectRaw(
			'mkt_costs.id,
			itemclasses.name,
			uoms.code,
			mkt_cost_trims.*'
			)
			->leftJoin('mkt_cost_trims', function($join) {
			$join->on('mkt_cost_trims.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->leftJoin('itemclasses',function($join){
			$join->on('itemclasses.id','=','mkt_cost_trims.itemclass_id');
			})
			->leftJoin('itemcategories',function($join){
			$join->on('itemcategories.id','=','itemclasses.itemcategory_id');
			})
			->leftJoin('uoms',function($join){
			$join->on('uoms.id','=','mkt_cost_trims.uom_id');
			})
			->where([['mkt_costs.id','=',$id]])
			->get();
			return $trims;
	}
	
	public function totalEmbCost($id) {
		    $emb = $this->selectRaw(
			'mkt_costs.id,
			sum(mkt_cost_embs.cons) as qty,
			sum(mkt_cost_embs.amount) as amount'
			)
			->leftJoin('mkt_cost_embs', function($join) {
			$join->on('mkt_cost_embs.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->where([['mkt_costs.id','=',$id]])
			->groupBy([
			'mkt_costs.id',
			])
			->get()->first();
			return $emb->amount;
	}
	
	public function EmbCost($id) {
		$embs = $this->selectRaw(
				'embelishments.name as embelishment_name,
				embelishment_types.name as embelishment_type,
				item_accounts.item_description,
				mkt_costs.costing_unit_id,
				mkt_cost_embs.*'
			)
			->leftJoin('mkt_cost_embs', function($join) {
			$join->on('mkt_cost_embs.mkt_cost_id', '=', 'mkt_costs.id');
			})
			
			 ->join('style_embelishments',function($join){
          $join->on('style_embelishments.id','=','mkt_cost_embs.style_embelishment_id');
        })
		->leftJoin('embelishments',function($join){
			$join->on('embelishments.id','=','style_embelishments.embelishment_id');
			})
			->leftJoin('embelishment_types',function($join){
			$join->on('embelishment_types.id','=','style_embelishments.embelishment_type_id');
			})
			->leftJoin('style_gmts',function($join){
			$join->on('style_gmts.id','=','style_embelishments.style_gmt_id');
			})
			->leftJoin('item_accounts',function($join){
			$join->on('item_accounts.id','=','style_gmts.item_account_id');
			})
			->where([['mkt_costs.id','=',$id]])
			->get();
			return $embs;
		  
	}
	
	public function totalOtherCost($id) {
		    $other = $this->selectRaw(
			'mkt_costs.id,
			sum(mkt_cost_others.amount) as amount'
			)
			->leftJoin('mkt_cost_others', function($join) {
			$join->on('mkt_cost_others.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->where([['mkt_costs.id','=',$id]])
			->groupBy([
			'mkt_costs.id',
			])
			->get()->first();
			return $other->amount;
	}
	
	public function otherCost($id) {
		    $other = $this->selectRaw(
			'mkt_costs.id,
			cost_head_id,
			amount'
			)
			->leftJoin('mkt_cost_others', function($join) {
			$join->on('mkt_cost_others.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->where([['mkt_costs.id','=',$id]])
			->get();
			$otherArr=array();
			foreach($other as $row){
				$otherArr[$row->cost_head_id]=$row->amount;
			}
			return $otherArr;
	}
	
	public function totalCmCost($id) {
		    $cm = $this->selectRaw(
			'mkt_costs.id,
			sum(mkt_cost_cms.amount) as amount'
			)
			->leftJoin('mkt_cost_cms', function($join) {
			$join->on('mkt_cost_cms.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->where([['mkt_costs.id','=',$id]])
			->groupBy([
			'mkt_costs.id',
			])
			->get()->first();
			return $cm->amount;
	}

	public function CmCost($id) {
		$cm = $this->selectRaw(
		'mkt_costs.id,
		mkt_cost_cms.amount,          
		mkt_cost_cms.smv,             
		mkt_cost_cms.sewing_effi_per,  
		mkt_cost_cms.cm_per_pcs,      
		mkt_cost_cms.cpm,              
		mkt_cost_cms.no_of_man_power,  
		mkt_cost_cms.prod_per_hour,    
		mkt_cost_cms.style_gmt_id,
        style_gmts.gmt_qty,
        item_accounts.item_description as name'
		)
		->leftJoin('mkt_cost_cms', function($join) {
		$join->on('mkt_cost_cms.mkt_cost_id', '=', 'mkt_costs.id');
		})
		->leftJoin('style_gmts', function($join)  {
		$join->on('style_gmts.id', '=', 'mkt_cost_cms.style_gmt_id');
		})
		->leftJoin('item_accounts', function($join)  {
		$join->on('style_gmts.item_account_id', '=', 'item_accounts.id');
		})
		->where([['mkt_costs.id','=',$id]])
		->get();
		return $cm;
	}
	
	public function totalCommercialCost($id) {
		    $commercial = $this->selectRaw(
			'mkt_costs.id,
			sum(mkt_cost_commercials.amount) as amount'
			)
			->leftJoin('mkt_cost_commercials', function($join) {
			$join->on('mkt_cost_commercials.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->where([['mkt_costs.id','=',$id]])
			->groupBy([
			'mkt_costs.id',
			])
			->get()->first();
			return $commercial->amount;
	}
	
	public function CommercialCost($id) {
		    $commercial = $this->selectRaw(
			'mkt_costs.id,
			mkt_cost_commercials.rate,
			mkt_cost_commercials.amount'
			)
			->leftJoin('mkt_cost_commercials', function($join) {
			$join->on('mkt_cost_commercials.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->where([['mkt_costs.id','=',$id]])
			->get();
			return $commercial;
	}
	
	public function totalProfit($id) {
		    $profit = $this->selectRaw(
			'mkt_costs.id,
			sum(mkt_cost_profits.amount) as amount'
			)
			->leftJoin('mkt_cost_profits', function($join) {
			$join->on('mkt_cost_profits.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->where([['mkt_costs.id','=',$id]])
			->groupBy([
			'mkt_costs.id',
			])
			->get()->first();
			return $profit->amount;
	}
	public function totalProfitRate($id) {
		    $profit = $this->selectRaw(
			'mkt_costs.id,
			sum(mkt_cost_profits.rate) as rate'
			)
			->leftJoin('mkt_cost_profits', function($join) {
			$join->on('mkt_cost_profits.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->where([['mkt_costs.id','=',$id]])
			->groupBy([
			'mkt_costs.id',
			])
			->get()->first();
			return $profit->rate;
	}
	
	public function totalCommission($id) {
		    $commission = $this->selectRaw(
			'mkt_costs.id,
			sum(mkt_cost_commissions.amount) as amount'
			)
			->leftJoin('mkt_cost_commissions', function($join) {
			$join->on('mkt_cost_commissions.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->where([['mkt_costs.id','=',$id]])
			->groupBy([
			'mkt_costs.id',
			])
			->get()->first();
			return $commission->amount;
	}
	
	public function Commission($id) {
		    $commission = $this->selectRaw(
			'mkt_costs.id,
			mkt_cost_commissions.for_id,
			mkt_cost_commissions.rate,
			mkt_cost_commissions.amount'
			)
			->leftJoin('mkt_cost_commissions', function($join) {
			$join->on('mkt_cost_commissions.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->where([['mkt_costs.id','=',$id]])
			
			->get();
			return $commission;
	}
	
	public function totalCost($id) {
			return $this->totalFabricCost($id)+$this->totalYarnCost($id)+$this->totalFabricProdCost($id)+$this->totalTrimCost($id)+$this->totalEmbCost($id)+$this->totalOtherCost($id)+$this->totalCmCost($id)+$this->totalCommercialCost($id);
    }
	
	public function totalPriceBeforeCommission($id) {
			return $this->totalCost($id)+$this->totalProfit($id);
    }
	
	public function totalPriceAfterCommission($id) {
			return $this->totalPriceBeforeCommission($id)+$this->totalCommission($id);
    }
	
	public function totalQuotePrice($id) {
		    $QuotedPrice = $this->selectRaw(
			'mkt_costs.id,
			mkt_cost_quote_prices.qprice_date,
			mkt_cost_quote_prices.quote_price'
			)
			->leftJoin('mkt_cost_quote_prices', function($join) {
			$join->on('mkt_cost_quote_prices.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->where([['mkt_costs.id','=',$id]])
			
			->get()->first();
			return $QuotedPrice;
	}
	public function totalTargetPrice($id) {
		    $TargetPrice = $this->selectRaw(
			'mkt_costs.id,
			mkt_cost_target_prices.price_date,
			mkt_cost_target_prices.target_price'
			)
			->leftJoin('mkt_cost_target_prices', function($join) {
			$join->on('mkt_cost_target_prices.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->where([['mkt_costs.id','=',$id]])
			
			->get()->first();
			return $TargetPrice;
	}

	public function smsData($id) {
		    $style = $this->selectRaw(
			'mkt_costs.id,
			mkt_costs.costing_unit_id,
			mkt_costs.offer_qty,
			mkt_costs.op_date,
			mkt_costs.lead_time,
			mkt_costs.est_ship_date,
			styles.style_ref,
			styles.id as style_id,
			styles.buying_agent_id,
			styles.contact,
			buyers.code as buyer_code,
			uoms.code as uom_name,
			users.name as member_name,
			mkt_cost_quote_prices.qprice_date,
			mkt_cost_quote_prices.submission_date,
			mkt_cost_quote_prices.confirm_date,
			mkt_cost_quote_prices.refused_date,
			mkt_cost_quote_prices.cancel_date,
			mkt_cost_quote_prices.quote_price'
			)
			->leftJoin('styles', function($join) {
				$join->on('styles.id', '=', 'mkt_costs.style_id');
			})
			->leftJoin('buyers', function($join) {
				$join->on('buyers.id', '=', 'styles.buyer_id');
			})
			->leftJoin('teammembers', function($join) {
				$join->on('teammembers.id', '=', 'styles.teammember_id');
			})
			->leftJoin('uoms', function($join) {
				$join->on('uoms.id', '=', 'styles.uom_id');
			})
			->leftJoin('users', function($join) {
				$join->on('users.id', '=', 'teammembers.user_id');
			})
			->leftJoin('mkt_cost_quote_prices', function($join) {
			$join->on('mkt_cost_quote_prices.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->where([['mkt_costs.id','=',$id]])
			->get()->first();

			$gmt = $this->selectRaw(
			'item_accounts.item_description'
			)
			->leftJoin('styles', function($join) {
				$join->on('styles.id', '=', 'mkt_costs.style_id');
			})
			->leftJoin('style_gmts', function($join) {
				$join->on('style_gmts.style_id', '=', 'styles.id');
			})
			->leftJoin('item_accounts', function($join) {
				$join->on('item_accounts.id', '=', 'style_gmts.item_account_id');
			})
			->where([['mkt_costs.id','=',$id]])
			->get(['item_accounts.item_description']);
			$item=array();
			foreach($gmt as $row){
             $item[]=$row->item_description;
			}

			$buying_house = $this->selectRaw(
			'buyers.name,buyer_branches.contact_person as contact'
			)
			->leftJoin('styles', function($join) {
				$join->on('styles.id', '=', 'mkt_costs.style_id');
			})
			->leftJoin('buyers', function($join) {
				$join->on('buyers.id', '=', 'styles.buying_agent_id');
			})
			->leftJoin('buyer_branches', function($join) {
				$join->on('buyer_branches.buyer_id', '=', 'buyers.id');
			})
			
			->where([['mkt_costs.id','=',$id]])
			->get()->first();

			$fabriclooks=array_prepend(config('bprs.fabriclooks'),'-Select-','');

			$fabrics = $this->selectRaw(
			'constructions.id as contruction_id,constructions.name as contruction_name,style_fabrications.fabric_look_id,mkt_cost_fabrics.fabric_cons'
			)
			->leftJoin('styles', function($join) {
				$join->on('styles.id', '=', 'mkt_costs.style_id');
			})
			->leftJoin('mkt_cost_fabrics', function($join) {
				$join->on('mkt_cost_fabrics.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->leftJoin('style_fabrications', function($join) {
				$join->on('style_fabrications.id', '=', 'mkt_cost_fabrics.style_fabrication_id');
			})
			->leftJoin('autoyarns', function($join) {
				$join->on('autoyarns.id', '=', 'style_fabrications.autoyarn_id');
			})
			->leftJoin('constructions', function($join) {
				$join->on('constructions.id', '=', 'autoyarns.construction_id');
			})
			->leftJoin('buyers', function($join) {
				$join->on('buyers.id', '=', 'styles.buying_agent_id');
			})
			->leftJoin('buyer_branches', function($join) {
				$join->on('buyer_branches.buyer_id', '=', 'buyers.id');
			})
			->where([['mkt_costs.id','=',$id]])
			->where([['style_fabrications.is_narrow','=',0]])
			->get();
			$contruction=array();
			$value_addition=array();
			$cons=0;
			$i=0;
			foreach($fabrics as $fabric)
			{
               $contruction[$fabric->contruction_id]=$fabric->contruction_name;
               $cons+=$fabric->fabric_cons;
               if($fabric->fabric_look_id==25 || $fabric->fabric_look_id==30)
               {
               	$value_addition[$fabric->fabric_look_id]=$fabriclooks[$fabric->fabric_look_id];
               }
               $i++;
               

			}
			$consDzn=number_format($cons/$i,4);


			$embelishments = $this->selectRaw(
			'embelishments.id as embelishment_id,embelishments.name as embelishment_name'
			)
			->leftJoin('styles', function($join) {
				$join->on('styles.id', '=', 'mkt_costs.style_id');
			})
			->leftJoin('mkt_cost_embs', function($join) {
				$join->on('mkt_cost_embs.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->leftJoin('style_embelishments', function($join) {
				$join->on('style_embelishments.id', '=', 'mkt_cost_embs.style_embelishment_id');
			})
			->leftJoin('embelishments', function($join) {
				$join->on('embelishments.id', '=', 'style_embelishments.embelishment_id');
			})
			->where([['mkt_costs.id','=',$id]])
			->get();

			foreach($embelishments as $embelishment)
			{
               	$value_addition[$embelishment->embelishment_id]=$embelishment->embelishment_name;
			}


			$totSubmited = $this->selectRaw(
			'mkt_costs.id,
			mkt_cost_quote_price_audits.quote_price'
			)
			->leftJoin('mkt_cost_quote_price_audits', function($join) {
			$join->on('mkt_cost_quote_price_audits.mkt_cost_id', '=', 'mkt_costs.id');
			})
			->where([['mkt_costs.id','=',$id]])
			->whereNotNull('mkt_cost_quote_price_audits.submission_date')
			->get()->count();
            $totSubmited=$totSubmited+1;
			$cofirmed="";
			if($style->confirm_date){
				$cofirmed="Confirmed";
			}
			else if ( $style->submission_date ){
				$cofirmed="Submited (" . $totSubmited. " )";
			}
			$totalCostDzn=$this->totalCost($id);
			$totalCostPcs=number_format($totalCostDzn/$style->costing_unit_id,4);
			$text="FamKam ERP\n";
			$text.="Price ".$cofirmed."\n";
			$text.="Marketer : ".$style->member_name."\n";
			$text.="Buyer : ".$style->buyer_code."\n";
			$text.="Buying House : ".$buying_house->name.", ".$style->contact."\n";
			$text.="Value Addition : ".implode(", ",$value_addition)."\n";
			$text.="Fabric Type : ".implode(", ",$contruction)."\n";
			$text.="Item : ".implode(", ",$item)."\n";
			$text.="Style : ".$style->style_ref."\n";
			$text.="Est. OP Date : ".date('d-M-Y',strtotime($style->op_date))."\n";
			$text.="Est. Ship Date : ".date('d-M-Y',strtotime($style->est_ship_date))."\n";
			$text.="Lead Time : ".$style->lead_time." Days\n"; 
			$text.="GMT Qty : ".number_format($style->offer_qty,0,'.',',')."\n";
			$text.="Order UOM : ".$style->uom_name."\n";
			$text.="Fabric Cons/Dzn : ".$consDzn."\n";
            $text.="Price/Pcs : ".$style->quote_price."\n";
			$text.="Cost/Pcs : ".$totalCostPcs."\n";
			return $text;
	}
}
