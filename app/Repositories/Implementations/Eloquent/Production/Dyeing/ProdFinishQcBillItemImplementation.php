<?php
namespace App\Repositories\Implementations\Eloquent\Production\Dyeing;
use App\Repositories\Contracts\Production\Dyeing\ProdFinishQcBillItemRepository;
use App\Model\Production\Dyeing\ProdFinishQcBillItem;
use App\Traits\Eloquent\MsTraits; 
class ProdFinishQcBillItemImplementation implements ProdFinishQcBillItemRepository
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
	public function __construct(ProdFinishQcBillItem $model)
	{
		$this->model = $model;
	}
	
}
