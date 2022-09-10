<?php

namespace App\Repositories\Implementations\Eloquent\Sales;
use App\Repositories\Contracts\Sales\SalesOrderItemRepository;
use App\Model\Sales\SalesOrderItem;
use App\Traits\Eloquent\MsTraits;
class SalesOrderItemImplementation implements SalesOrderItemRepository
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
	public function __construct(SalesOrderItem $model)
	{
		$this->model = $model;
	}

	public function getAll(){
		return $rows = $this->selectRaw(
		'sales_order_items.id,
		sales_order_items.job_id,
		sales_order_items.sale_order_id,
		sales_order_items.sale_order_country_id,
		sales_order_items.article_no,
		sales_orders.sale_order_no,
		styles.style_ref,
		countries.name as country_name,
		sum(sales_order_color_sizes.qty) as qty,
		sum(sales_order_color_sizes.amount) as amount'
		)
		->leftJoin('sales_order_color_sizes', function($join) {
		$join->on('sales_order_color_sizes.sale_order_item_id', '=', 'sales_order_items.id');
		})
		->join('sales_order_countries', function($join)  {
		$join->on('sales_order_countries.id', '=', 'sales_order_items.sale_order_country_id');
		})
		->join('sales_orders', function($join)  {
		$join->on('sales_orders.id', '=', 'sales_order_items.sale_order_id');
		})
		->join('jobs', function($join)  {
		$join->on('jobs.id', '=', 'sales_order_items.job_id');
		})
		->join('styles', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->join('countries', function($join)  {
		$join->on('countries.id', '=', 'sales_order_countries.country_id');
		})
		->when(request('sale_order_country_id'), function ($q) {
			return $q->where('sales_order_items.sale_order_country_id', '=', request('sale_order_country_id', 0));
		})
		->groupBy([
		'sales_order_items.id',
		'sales_order_items.job_id',
		'sales_order_items.sale_order_id',
		'sales_order_items.sale_order_country_id',
		'sales_order_items.article_no',
		'sales_orders.sale_order_no',
		'styles.style_ref',
		'countries.name'
		])
		->get();
	}

	public function getById($id){
		return $rows = $this->join('jobs', function($join)  {
		$join->on('sales_order_items.job_id', '=', 'jobs.id');
		})
		->join('sales_orders', function($join)  {
		$join->on('sales_order_items.sale_order_id', '=', 'sales_orders.id');
		})
		->join('sales_order_countries', function($join)  {
		$join->on('sales_order_items.sale_order_country_id', '=', 'sales_order_countries.id');
		})
		->join('countries', function($join)  {
		$join->on('countries.id', '=', 'sales_order_countries.country_id');
		})
		->where('sales_order_items.id', '=',$id)
		->get([
		'sales_order_items.*',
		'jobs.job_no',
		'sales_orders.sale_order_no',
		'countries.name as country_name',
		]);
		
		}

}
