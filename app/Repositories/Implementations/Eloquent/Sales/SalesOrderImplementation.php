<?php

namespace App\Repositories\Implementations\Eloquent\Sales;
use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Model\Sales\SalesOrder;
use App\Traits\Eloquent\MsTraits;
class SalesOrderImplementation implements SalesOrderRepository
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
	public function __construct(SalesOrder $model)
	{
		$this->model = $model;
	}

	public function getAll(){
			return $rows = $this->selectRaw(
			'sales_orders.id,
			sales_orders.sale_order_no,
			sales_orders.job_id,
			jobs.job_no,
			sales_orders.projection_id,
			sales_orders.place_date,
			sales_orders.receive_date,
			sales_orders.ship_date,
			sales_orders.file_no,
			sales_orders.remarks,
			sales_orders.tna_to,
			sales_orders.tna_from,
			sales_orders.produced_company_id,
			sum(sales_order_gmt_color_sizes.qty) as qty,
			sum(sales_order_gmt_color_sizes.amount) as amount'
			)
			->leftJoin('jobs', function($join) {
			$join->on('jobs.id', '=', 'sales_orders.job_id');
			})
			->leftJoin('sales_order_gmt_color_sizes', function($join) {
			$join->on('sales_orders.id', '=', 'sales_order_gmt_color_sizes.sale_order_id');
			})
			->when(request('job_id'), function ($q) {
			return $q->where('sales_orders.job_id', '=', request('job_id', 0));
		    })
			->groupBy([
			'sales_orders.id',
			'sales_orders.sale_order_no',
			'sales_orders.job_id',
			'jobs.job_no',
			'sales_orders.projection_id',
			'sales_orders.place_date',
			'sales_orders.receive_date',
			'sales_orders.ship_date',
			'sales_orders.file_no',
			'sales_orders.remarks',
			'sales_orders.tna_to',
			'sales_orders.tna_from',
			'sales_orders.produced_company_id',
			])
			->orderBy('id','desc')
			->get();
	}
}
