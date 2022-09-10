<?php
namespace App\Repositories\Implementations\Eloquent\ShowRoom;

use App\Repositories\Contracts\ShowRoom\SrmProductSaleRepository;
use App\Model\ShowRoom\SrmProductSale;
use App\Traits\Eloquent\MsTraits;
class SrmProductSaleImplementation implements SrmProductSaleRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 *SrmProductSaleImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SrmProductSale $model)
	{
		$this->model = $model;
	}
}
