<?php

namespace App\Repositories\Implementations\Eloquent\Sales;
use App\Repositories\Contracts\Sales\SalesOrderColorRepository;
use App\Model\Sales\SalesOrderColor;
use App\Traits\Eloquent\MsTraits;
class SalesOrderColorImplementation implements SalesOrderColorRepository
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
	public function __construct(SalesOrderColor $model)
	{
		$this->model = $model;
	}

	public function getAll(){
		return $rows = $this->selectRaw(
		'sales_order_colors.id,
		sales_order_colors.style_color_id,
		sales_order_colors.job_id,
		sales_order_colors.sale_order_id,
		sales_order_colors.sale_order_country_id,
		sales_orders.sale_order_no,
		style_colors.color_id,
		style_colors.sort_id,
		colors.code,
		colors.name,
		styles.style_ref,
		countries.name as country_name,
		sum(sales_order_sizes.qty) as qty,
		sum(sales_order_sizes.amount) as amount'
		)
		->leftJoin('sales_order_sizes', function($join) {
		$join->on('sales_order_colors.id', '=', 'sales_order_sizes.sale_order_color_id');
		})
		->join('sales_order_countries', function($join)  {
		$join->on('sales_order_colors.sale_order_country_id', '=', 'sales_order_countries.id');
		})
		->join('sales_orders', function($join)  {
		$join->on('sales_order_colors.sale_order_id', '=', 'sales_orders.id');
		})
		->join('jobs', function($join)  {
		$join->on('sales_order_colors.job_id', '=', 'jobs.id');
		})
		->join('styles', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->join('style_colors', function($join)  {
		$join->on('jobs.style_id', '=', 'style_colors.style_id');
		$join->on('sales_order_colors.style_color_id', '=', 'style_colors.id');
		})
		->join('colors', function($join)  {
		$join->on('style_colors.color_id', '=', 'colors.id');
		})
		->join('countries', function($join)  {
		$join->on('countries.id', '=', 'sales_order_countries.country_id');
		})
		->when(request('sale_order_country_id'), function ($q) {
			return $q->where('sales_order_colors.sale_order_country_id', '=', request('sale_order_country_id', 0));
		})
		->groupBy([
		'sales_order_colors.id',
		'sales_order_colors.style_color_id',
		'sales_order_colors.job_id',
		'sales_order_colors.sale_order_id',
		'sales_order_colors.sale_order_country_id',
		'sales_orders.sale_order_no',
		'style_colors.color_id',
		'style_colors.sort_id',
		'styles.style_ref',
		'colors.code',
		'colors.name',
		'countries.name'
		])
		->get();
	}

	public function getById($id){
		return $rows = $this->join('jobs', function($join)  {
		$join->on('sales_order_colors.job_id', '=', 'jobs.id');
		})
		->join('styles', function($join)  {
		$join->on('jobs.style_id', '=', 'styles.id');
		})
		->rightJoin('style_sizes', function($join)  {
		$join->on('jobs.style_id', '=', 'style_sizes.style_id');
		})

    ->join('sales_orders', function($join)  {
    $join->on('sales_order_colors.sale_order_id', '=', 'sales_orders.id');
    })
		->leftJoin('sales_order_sizes', function($join)  {
		$join->on('style_sizes.id', '=', 'sales_order_sizes.style_size_id');
		$join->on('sales_order_colors.id', '=', 'sales_order_sizes.sale_order_color_id');
		})
    ->join('sizes', function($join)  {
		$join->on('style_sizes.size_id', '=', 'sizes.id');
		})
    ->join('style_colors', function($join)  {
    $join->on('jobs.style_id', '=', 'style_colors.style_id');
    $join->on('sales_order_colors.style_color_id', '=', 'style_colors.id');
    })
    ->join('colors', function($join)  {
    $join->on('style_colors.color_id', '=', 'colors.id');
    })
    ->join('sales_order_countries', function($join)  {
		$join->on('sales_order_colors.sale_order_country_id', '=', 'sales_order_countries.id');
		})
    ->join('countries', function($join)  {
		$join->on('countries.id', '=', 'sales_order_countries.country_id');
		})
		->orderBy('style_sizes.sort_id')
		->where('sales_order_colors.id', '=',$id)
		->get([
		'sales_order_colors.*',
		'style_sizes.id as stylesize',
		'style_sizes.sort_id as size_sequence',
		'style_colors.sort_id',
        'jobs.job_no',
		'sizes.name',
		'sizes.code as size_code',
		'sales_order_sizes.qty',
		'sales_order_sizes.rate',
		'sales_order_sizes.amount',
        'sales_orders.sale_order_no',
        'countries.name as country_name',
        'colors.code as color_code',
		'styles.style_ref'
		]);

	}

}
