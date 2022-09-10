<?php

namespace App\Repositories\Implementations\Eloquent\Sample\Costing;
use App\Repositories\Contracts\Sample\Costing\SmpCostRepository;
use App\Model\Sample\Costing\SmpCost;
use App\Traits\Eloquent\MsTraits;
class SmpCostImplementation implements SmpCostRepository
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
	public function __construct(SmpCost $model)
	{
		$this->model = $model;
	}

	public function totalFabricCost($id) {
			$fabric = $this->selectRaw(
			'smp_costs.id,
			sum(smp_cost_fabrics.fabric_cons) as qty,
			sum(smp_cost_fabrics.amount) as amount'
			)
			->leftJoin('smp_cost_fabrics', function($join) {
			$join->on('smp_cost_fabrics.smp_cost_id', '=', 'smp_costs.id');
			})
			->where([['smp_costs.id','=',$id]])
			->groupBy([
			'smp_costs.id',
			])
			->get()->first();
			return $fabric->amount;
	}

	public function totalYarnCost($id) {
		    $yarn = $this->selectRaw(
			'smp_costs.id,
			sum(smp_cost_yarns.cons) as qty,
			sum(smp_cost_yarns.amount) as amount'
			)
			->leftJoin('smp_cost_yarns', function($join) {
			$join->on('smp_cost_yarns.smp_cost_id', '=', 'smp_costs.id');
			})
			->where([['smp_costs.id','=',$id]])
			->groupBy([
			'smp_costs.id',
			])
			->get()->first();
			return $yarn->amount;
	}

	public function totalFabricProdCost($id) {
		    $fabProd = $this->selectRaw(
			'smp_costs.id,
			sum(smp_cost_fabric_prods.cons) as qty,
			sum(smp_cost_fabric_prods.amount) as amount'
			)
			->leftJoin('smp_cost_fabric_prods', function($join) {
			$join->on('smp_cost_fabric_prods.smp_cost_id', '=', 'smp_costs.id');
			})
			->where([['smp_costs.id','=',$id]])
			->groupBy([
			'smp_costs.id',
			])
			->get()->first();
			return $fabProd->amount;
	}

	public function totalTrimCost($id) {
		    $trim = $this->selectRaw(
			'smp_costs.id,
			sum(smp_cost_trims.bom_qty) as qty,
			sum(smp_cost_trims.amount) as amount'
			)
			->leftJoin('smp_cost_trims', function($join) {
			$join->on('smp_cost_trims.smp_cost_id', '=', 'smp_costs.id');
			})
			->where([['smp_costs.id','=',$id]])
			->groupBy([
			'smp_costs.id',
			])
			->get()->first();
			return $trim->amount;
	}

	public function totalEmbCost($id) {
		    $emb = $this->selectRaw(
			'smp_costs.id,
			sum(smp_cost_embs.cons) as qty,
			sum(smp_cost_embs.amount) as amount'
			)
			->leftJoin('smp_cost_embs', function($join) {
			$join->on('smp_cost_embs.smp_cost_id', '=', 'smp_costs.id');
			})
			->where([['smp_costs.id','=',$id]])
			->groupBy([
			'smp_costs.id',
			])
			->get()->first();
			return $emb->amount;
	}

	public function totalCmCost($id) {
		    $cm = $this->selectRaw(
			'smp_costs.id,
			sum(smp_cost_cms.bom_amount) as amount'
			)
			->leftJoin('smp_cost_cms', function($join) {
			$join->on('smp_cost_cms.smp_cost_id', '=', 'smp_costs.id');
			})
			->where([['smp_costs.id','=',$id]])
			->groupBy([
			'smp_costs.id',
			])
			->get()->first();
			return $cm->amount;
	}
}
