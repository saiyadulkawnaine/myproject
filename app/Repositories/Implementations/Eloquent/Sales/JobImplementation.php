<?php

namespace App\Repositories\Implementations\Eloquent\Sales;
use App\Repositories\Contracts\Sales\JobRepository;
use App\Model\Sales\Job;
use App\Traits\Eloquent\MsTraits;
class JobImplementation implements JobRepository
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
	public function __construct(Job $model)
	{
		$this->model = $model;
	}
	public function gmtItemRatioQty($id) {
		$style_gmts = $this->selectRaw(
		'jobs.id,
		sum(style_gmts.gmt_qty) as gmt_qty_ratio'
		)
		->join('style_gmts', function($join) {
		$join->on('style_gmts.style_id', '=', 'jobs.style_id');
		})
		->where([['jobs.id','=',$id]])
		->groupBy([
		'jobs.id',
		])
		->get()->first();
		return $style_gmts->gmt_qty_ratio;
	}
	public function totalJobQty($id) {
		
		
		$jobsQty = $this->selectRaw(
		'jobs.id,
		sum(sales_order_gmt_color_sizes.qty) as qty'
		)
		->join('sales_order_gmt_color_sizes', function($join) {
		$join->on('sales_order_gmt_color_sizes.job_id', '=', 'jobs.id');
		})
		->where([['jobs.id','=',$id]])
		->groupBy([
		'jobs.id',
		])
		->get()->first();
		return $jobsQty->qty/$this->gmtItemRatioQty($id);
	}
	
	public function totalJobCutQty($id) {
		$jobsQty = $this->selectRaw(
		'jobs.id,
		sum(sales_order_gmt_color_sizes.plan_cut_qty) as plan_cut_qty'
		)
		->join('sales_order_gmt_color_sizes', function($join) {
		$join->on('sales_order_gmt_color_sizes.job_id', '=', 'jobs.id');
		})
		->where([['jobs.id','=',$id]])
		->groupBy([
		'jobs.id',
		])
		->get()->first();
		return $jobsQty->plan_cut_qty/$this->gmtItemRatioQty($id);
	}
	
	public function totalJobAmount($id) {
		$jobsQty = $this->selectRaw(
		'jobs.id,
		sum(sales_order_gmt_color_sizes.amount) as amount'
		)
		->join('sales_order_gmt_color_sizes', function($join) {
		$join->on('sales_order_gmt_color_sizes.job_id', '=', 'jobs.id');
		})
		->where([['jobs.id','=',$id]])
		->groupBy([
		'jobs.id',
		])
		->get()->first();
		return $jobsQty->amount;
	}

	public function totalJobGmtItemQty($id,$gmt_item_id) {
		
		
		$jobsQty = $this->selectRaw(
		'jobs.id,
		sum(sales_order_gmt_color_sizes.qty) as qty'
		)
		->join('sales_order_gmt_color_sizes', function($join) {
		$join->on('sales_order_gmt_color_sizes.job_id', '=', 'jobs.id');
		})
		->join('style_gmt_color_sizes', function($join) {
		$join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
		})
		->join('style_gmts', function($join) {
		$join->on('style_gmts.id', '=', 'style_gmt_color_sizes.style_gmt_id');
		})
		->where([['jobs.id','=',$id]])
		->where([['style_gmts.id','=',$gmt_item_id]])
		->groupBy([
		'jobs.id',
		])
		->get()->first();
		return $jobsQty->qty;
	}
	public function totalJobGmtItemCutQty($id,$gmt_item_id) {
		
		
		$jobsQty = $this->selectRaw(
		'jobs.id,
		sum(sales_order_gmt_color_sizes.plan_cut_qty) as qty'
		)
		->join('sales_order_gmt_color_sizes', function($join) {
		$join->on('sales_order_gmt_color_sizes.job_id', '=', 'jobs.id');
		})
		->join('style_gmt_color_sizes', function($join) {
		$join->on('style_gmt_color_sizes.id', '=', 'sales_order_gmt_color_sizes.style_gmt_color_size_id');
		})
		->join('style_gmts', function($join) {
		$join->on('style_gmts.id', '=', 'style_gmt_color_sizes.style_gmt_id');
		})
		->where([['jobs.id','=',$id]])
		->where([['style_gmts.id','=',$gmt_item_id]])
		->groupBy([
		'jobs.id',
		])
		->get()->first();
		return $jobsQty->qty;
	}
}
