<?php

namespace App\Repositories\Implementations\Eloquent\Sales;
use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Model\Sales\SalesOrderCountry;
use App\Traits\Eloquent\MsTraits;
class SalesOrderCountryImplementation implements SalesOrderCountryRepository
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
	public function __construct(SalesOrderCountry $model)
	{
		$this->model = $model;
	}
	
	public function getAll(){
		return $rows = $this->selectRaw(
		'sales_order_countries.id,
		sales_order_countries.country_ship_date,
		sales_order_countries.breakdown_basis,
		sales_order_countries.sam,
		sales_order_countries.style_gmt_id,
		sales_order_countries.country_id,
		sales_order_countries.fabric_looks,
		sales_orders.sale_order_no,
		countries.name,
		sum(sales_order_gmt_color_sizes.qty) as qty,
		sum(sales_order_gmt_color_sizes.amount) as amount'
		)
		->leftJoin('sales_order_gmt_color_sizes', function($join) {
		$join->on('sales_order_countries.id', '=', 'sales_order_gmt_color_sizes.sale_order_country_id');
		})
		->join('sales_orders', function($join)  {
		$join->on('sales_order_countries.sale_order_id', '=', 'sales_orders.id');
		})
		->join('countries', function($join)  {
		$join->on('countries.id', '=', 'sales_order_countries.country_id');
		})
		->when(request('sale_order_id'), function ($q) {
			return $q->where('sales_order_countries.sale_order_id', '=', request('sale_order_id', 0));
		})
		->groupBy([
		'sales_order_countries.id',
		'sales_order_countries.country_ship_date',
		'sales_order_countries.breakdown_basis',
		'sales_order_countries.sam',
		'sales_order_countries.style_gmt_id',
		'sales_order_countries.country_id',
		'sales_order_countries.fabric_looks',
		'sales_orders.sale_order_no',
		'countries.name'
		])
		->get();
	}
}
