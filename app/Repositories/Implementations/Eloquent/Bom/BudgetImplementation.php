<?php

namespace App\Repositories\Implementations\Eloquent\Bom;
use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Model\Bom\Budget;
use App\Traits\Eloquent\MsTraits;
class BudgetImplementation implements BudgetRepository
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
	public function __construct(Budget $model)
	{
		$this->model = $model;
	}
	public function getAll(){
		$rows=$this->model
		->join('jobs',function($join){
			$join->on('jobs.id','=','budgets.job_id');
		})
		->join('styles',function($join){
			$join->on('styles.id','=','jobs.style_id');
		})
		->join('buyers',function($join){
			$join->on('buyers.id','=','styles.buyer_id');
		})
		->join('companies',function($join){
			$join->on('companies.id','=','jobs.company_id');
		})
		->join('teams',function($join){
			$join->on('teams.id','=','styles.team_id');
		})
		->join('currencies',function($join){
			$join->on('currencies.id','=','jobs.currency_id');
		})
		->join('uoms',function($join){
			$join->on('uoms.id','=','styles.uom_id');
		})
		->orderBy('budgets.id','desc')
		->take(500)
		->get([
		'budgets.*',
		//'jobs.id as job_id',
		'jobs.job_no',
		'jobs.exch_rate',
		'styles.style_ref',
		'buyers.code as buyer_name',
		'teams.name as team_name',
		'currencies.code as currency_code',
		'companies.code as company_name',
		'uoms.code as uom_code'
		]);
		return $rows;
	}

	public function totalFabricCost($id) {
		$fabric = $this->selectRaw(
			'budgets.id,
			sum(budget_fabric_cons.cons) as qty,
			sum(budget_fabric_cons.amount) as amount'
			)
			->leftJoin('budget_fabrics', function($join) {
			$join->on('budget_fabrics.budget_id', '=', 'budgets.id');
			})
			->leftJoin('budget_fabric_cons', function($join) {
			$join->on('budget_fabric_cons.budget_fabric_id', '=', 'budget_fabrics.id');
			})
			->where([['budgets.id','=',$id]])
			->groupBy([
			'budgets.id',
			])
			->get()->first();
			/*$fabric = $this->selectRaw(
			'budgets.id,
			sum(budget_fabrics.fabric_cons) as qty,
			sum(budget_fabrics.amount) as amount'
			)
			->leftJoin('budget_fabrics', function($join) {
			$join->on('budget_fabrics.budget_id', '=', 'budgets.id');
			})
			->where([['budgets.id','=',$id]])
			->groupBy([
			'budgets.id',
			])
			->get()->first();*/
			return $fabric->amount;
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
		->join('constructions',function($join){
		$join->on('constructions.id','=','autoyarns.construction_id');
		})
		->where([['budgets.id','=',$id]])
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
			'budgets.id as budget_id,
			style_fabrications.id as style_fabrication_id,
			style_fabrications.material_source_id,
			style_fabrications.fabric_nature_id,
			style_fabrications.fabric_look_id,
			style_fabrications.fabric_shape_id,
			style_fabrications.is_narrow,
			gmtsparts.name as gmtspart_name,
			item_accounts.item_description,
			uoms.code as uom_name,
			budget_fabrics.gsm_weight,
			budget_fabrics.id,
			budget_fabrics.fabric_cons,
			budget_fabrics.rate,
			budget_fabrics.amount,
			sum(budget_fabric_cons.grey_fab) as req_cons,
			sum(budget_fabric_cons.fin_fab) as req_fin_cons
			'
		)
		->join('styles',function($join){
		$join->on('styles.id','=','budgets.style_id');
		})
		->join('style_fabrications',function($join){
		$join->on('style_fabrications.style_id','=','budgets.style_id');
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
		->leftJoin('budget_fabrics',function($join){
		$join->on('budget_fabrics.budget_id','=','budgets.id');
		$join->on('budget_fabrics.style_fabrication_id','=','style_fabrications.id');
		})
		->leftJoin('budget_fabric_cons',function($join){
		$join->on('budget_fabric_cons.budget_fabric_id','=','budget_fabrics.id');
		})

		->where([['budgets.id','=',$id]])
		//->where([['style_fabrications.is_narrow','=',0]])
		->groupBy([
		'budgets.id',
		'style_fabrications.id',
		'style_fabrications.material_source_id',
		'style_fabrications.fabric_nature_id',
		'style_fabrications.fabric_look_id',
		'style_fabrications.fabric_shape_id',
		'style_fabrications.is_narrow',
		'gmtsparts.name',
		'item_accounts.item_description',
		'uoms.code',
		'budget_fabrics.gsm_weight',
		'budget_fabrics.id',
		'budget_fabrics.fabric_cons',
			'budget_fabrics.rate',
			'budget_fabrics.amount'
		])
		->get();
		$stylefabrications=array();
		$stylenarrowfabrications=array();
        foreach($fabrics as $row){
			if($row->is_narrow==0){
			  $stylefabrication['id']=	$row->id;
			  $stylefabrication['budget_id']=	$row->budget_id;
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
			  $stylefabrication['fabric_cons']=	$row->fabric_cons;
			  $stylefabrication['req_fin_cons']=	$row->req_fin_cons;
			  $stylefabrication['rate']=	$row->rate;
			  $stylefabrication['amount']=	$row->amount;
			  array_push($stylefabrications,$stylefabrication);
			}else{
			  $stylefabrication['id']=	$row->id;
			  $stylefabrication['budget_id']=	$row->budget_id;
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
			  $stylefabrication['fabric_cons']=	$row->fabric_cons;
			  $stylefabrication['req_fin_cons']=	$row->req_fin_cons;
			  $stylefabrication['rate']=	$row->rate;
			  $stylefabrication['amount']=	$row->amount;
			  array_push($stylenarrowfabrications,$stylefabrication);
			}
    	}
		return array('main'=>$stylefabrications,'narrow'=>$stylenarrowfabrications);
	}

	public function totalYarnCost($id) {
		    $yarn = $this->selectRaw(
			'budgets.id,
			sum(budget_yarns.cons) as qty,
			sum(budget_yarns.amount) as amount'
			)
			->leftJoin('budget_yarns', function($join) {
			$join->on('budget_yarns.budget_id', '=', 'budgets.id');
			})
			->where([['budgets.id','=',$id]])
			->groupBy([
			'budgets.id',
			])
			->get()->first();
			return $yarn->amount;
	}

	public function yarnCost($id) {
		$yarn = $this->selectRaw(
		'budgets.id,
		budget_yarns.*'
		)
		->leftJoin('budget_yarns', function($join) {
		$join->on('budget_yarns.budget_id', '=', 'budgets.id');
		})
		->where([['budgets.id','=',$id]])
		->get();

		return $yarn;
	}

	public function totalFabricProdCost($id) {
		    $fabProd = $this->selectRaw(
			'budgets.id,
			sum(budget_fabric_prod_cons.bom_qty) as qty,
			sum(budget_fabric_prod_cons.amount) as amount,
			sum(budget_fabric_prod_cons.overhead_amount) as overhead_amount
			'
			)
			->leftJoin('budget_fabric_prods', function($join) {
			$join->on('budget_fabric_prods.budget_id', '=', 'budgets.id');
			})
			->leftJoin('budget_fabric_prod_cons', function($join) {
			$join->on('budget_fabric_prod_cons.budget_fabric_prod_id', '=', 'budget_fabric_prods.id');
			})
			->where([['budgets.id','=',$id]])
			->groupBy([
			'budgets.id',
			])
			->get()->first();
			return $fabProd->amount+$fabProd->overhead_amount;
	}

	public function fabricProdCost($id) {
		    $fabProd = $this->selectRaw(
			'budgets.id,
			production_processes.process_name,
			budget_fabric_prods.*'
			)
			->leftJoin('budget_fabric_prods', function($join) {
			$join->on('budget_fabric_prods.budget_id', '=', 'budgets.id');
			})
			->join('production_processes',function($join){
			$join->on('production_processes.id','=','budget_fabric_prods.production_process_id');
			})
			->where([['budgets.id','=',$id]])
			->get();
			return $fabProd;
	}


	public function totalYarnDyeingCost($id) {
		    $fabProd = $this->selectRaw(
			'budgets.id,
			sum(budget_yarn_dyeing_cons.bom_qty) as qty,
			sum(budget_yarn_dyeing_cons.amount) as amount,
			sum(budget_yarn_dyeing_cons.overhead_amount) as overhead_amount
			'
			)
			->leftJoin('budget_yarn_dyeings', function($join) {
			$join->on('budget_yarn_dyeings.budget_id', '=', 'budgets.id');
			})
			->leftJoin('budget_yarn_dyeing_cons', function($join) {
			$join->on('budget_yarn_dyeing_cons.budget_yarn_dyeing_id', '=', 'budget_yarn_dyeings.id');
			})
			->where([['budgets.id','=',$id]])
			->groupBy([
			'budgets.id',
			])
			->get()->first();
			return $fabProd->amount+$fabProd->overhead_amount;
	}

	public function yarnDyeingCost($id) {
		    $fabProd = $this->selectRaw(
			'budgets.id,
			production_processes.process_name,
			budget_yarn_dyeings.*'
			)
			->leftJoin('budget_yarn_dyeings', function($join) {
			$join->on('budget_yarn_dyeings.budget_id', '=', 'budgets.id');
			})
			->join('production_processes',function($join){
			$join->on('production_processes.id','=','budget_yarn_dyeings.production_process_id');
			})
			->where([['budgets.id','=',$id]])
			->get();
			return $fabProd;
	}

	public function totalTrimCost($id) {
		    $trim = $this->selectRaw(
			'budgets.id,
			sum(budget_trim_cons.bom_trim) as qty,
			sum(budget_trim_cons.amount) as amount'
			)
			->leftJoin('budget_trims', function($join) {
			$join->on('budget_trims.budget_id', '=', 'budgets.id');
			})
			->leftJoin('budget_trim_cons', function($join) {
			$join->on('budget_trim_cons.budget_trim_id', '=', 'budget_trims.id');
			})
			->where([['budgets.id','=',$id]])
			->groupBy([
			'budgets.id',
			])
			->get()->first();
			return $trim->amount;
	}

	public function TrimCost($id) {
		    $trims = $this->selectRaw(
			'budgets.id,
			itemclasses.name,
			uoms.code,
			budget_trims.*'
			)
			->leftJoin('budget_trims', function($join) {
			$join->on('budget_trims.budget_id', '=', 'budgets.id');
			})
			->leftJoin('itemclasses',function($join){
			$join->on('itemclasses.id','=','budget_trims.itemclass_id');
			})
			->leftJoin('itemcategories',function($join){
			$join->on('itemcategories.id','=','itemclasses.itemcategory_id');
			})
			->leftJoin('uoms',function($join){
			$join->on('uoms.id','=','budget_trims.uom_id');
			})
			->where([['budgets.id','=',$id]])
			->get();
			return $trims;
	}

	public function totalEmbCost($id) {
		    $emb = $this->selectRaw(
			'budgets.id,
			sum(budget_emb_cons.req_cons) as qty,
			sum(budget_emb_cons.amount) as amount,
			sum(budget_emb_cons.overhead_amount) as overhead_amount'
			)
			->leftJoin('budget_embs', function($join) {
			$join->on('budget_embs.budget_id', '=', 'budgets.id');
			})
			->leftJoin('budget_emb_cons', function($join) {
			$join->on('budget_emb_cons.budget_emb_id', '=', 'budget_embs.id');
			})
			->where([['budgets.id','=',$id]])
			->groupBy([
			'budgets.id',
			])
			->get()->first();
			return $emb->amount+$emb->overhead_amount;
	}

	public function EmbCost($id) {
		$embs = $this->selectRaw(
		'embelishments.name as embelishment_name,
		embelishment_types.name as embelishment_type,
		item_accounts.item_description,
		budgets.costing_unit_id,
		budget_embs.*'
		)
		->leftJoin('budget_embs', function($join) {
		$join->on('budget_embs.budget_id', '=', 'budgets.id');
		})

		->join('style_embelishments',function($join){
		$join->on('style_embelishments.id','=','budget_embs.style_embelishment_id');
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
		->where([['budgets.id','=',$id]])
		->get();
		return $embs;
	}

	public function totalOtherCost($id) {
		    $other = $this->selectRaw(
			'budgets.id,
			sum(budget_others.bom_amount) as amount'
			)
			->leftJoin('budget_others', function($join) {
			$join->on('budget_others.budget_id', '=', 'budgets.id');
			})
			->where([['budgets.id','=',$id]])
			->groupBy([
			'budgets.id',
			])
			->get()->first();
			return $other->amount;
	}

	public function otherCost($id) {
		    $other = $this->selectRaw(
			'budgets.id,
			cost_head_id,
			bom_amount'
			)
			->leftJoin('budget_others', function($join) {
			$join->on('budget_others.budget_id', '=', 'budgets.id');
			})
			->where([['budgets.id','=',$id]])
			->get();
			$otherArr=array();
			foreach($other as $row){
				$otherArr[$row->cost_head_id]=$row->bom_amount;
			}
			return $otherArr;
	}

	public function totalCmCost($id) {
		    $cm = $this->selectRaw(
			'budgets.id,
			sum(budget_cms.bom_amount) as amount'
			)
			->leftJoin('budget_cms', function($join) {
			$join->on('budget_cms.budget_id', '=', 'budgets.id');
			})
			->where([['budgets.id','=',$id]])
			->groupBy([
			'budgets.id',
			])
			->get()->first();
			return $cm->amount;
	}

	public function totalCommercialCost($id) {
		    $commercial = $this->selectRaw(
			'budgets.id,
			sum(budget_commercials.amount) as amount'
			)
			->leftJoin('budget_commercials', function($join) {
			$join->on('budget_commercials.budget_id', '=', 'budgets.id');
			})
			->where([['budgets.id','=',$id]])
			->groupBy([
			'budgets.id',
			])
			->get()->first();
			return $commercial->amount;
	}

	public function CommercialCost($id) {
		    $commercial = $this->selectRaw(
			'budgets.id,
			budget_commercials.rate,
			budget_commercials.amount'
			)
			->leftJoin('budget_commercials', function($join) {
			$join->on('budget_commercials.budget_id', '=', 'budgets.id');
			})
			->where([['budgets.id','=',$id]])
			->get();
			return $commercial;
	}

	

	public function totalCommission($id) {
		    $commission = $this->selectRaw(
			'budgets.id,
			sum(budget_commissions.amount) as amount'
			)
			->leftJoin('budget_commissions', function($join) {
			$join->on('budget_commissions.budget_id', '=', 'budgets.id');
			})
			->where([['budgets.id','=',$id]])
			->groupBy([
			'budgets.id',
			])
			->get()->first();
			return $commission->amount;
	}

	public function Commission($id) {
		    $commission = $this->selectRaw(
			'budgets.id,
			budget_commissions.for_id,
			budget_commissions.rate,
			budget_commissions.amount'
			)
			->leftJoin('budget_commissions', function($join) {
			$join->on('budget_commissions.budget_id', '=', 'budgets.id');
			})
			->where([['budgets.id','=',$id]])

			->get();
			return $commission;
	}

	public function totalCost($id) {
			return $this->totalFabricCost($id)+$this->totalYarnCost($id)+$this->totalFabricProdCost($id)+$this->totalTrimCost($id)+$this->totalEmbCost($id)+$this->totalOtherCost($id)+$this->totalCmCost($id)+$this->totalCommercialCost($id)+$this->totalCommission($id)+$this->totalYarnDyeingCost($id);
    }

	

	

	
	
}
