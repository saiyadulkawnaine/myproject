<?php
namespace App\Repositories\Implementations\Eloquent\JhuteSale;
use App\Repositories\Contracts\JhuteSale\JhuteStockRepository;
use App\Repositories\Contracts\JhuteSale\JhuteStockItemRepository;
use App\Model\JhuteSale\JhuteStockItem;
use App\Traits\Eloquent\MsTraits; 
class JhuteStockItemImplementation implements JhuteStockItemRepository
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
	public function __construct(JhuteStockItem $model)
	{
		$this->model = $model;
	}
	
	
}