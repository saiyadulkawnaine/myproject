<?php
namespace App\Repositories\Implementations\Eloquent\JhuteSale;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderRepository;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderItemRepository;
use App\Model\JhuteSale\JhuteSaleDlvOrderItem;
use App\Traits\Eloquent\MsTraits; 
class JhuteSaleDlvOrderItemImplementation implements JhuteSaleDlvOrderItemRepository
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
	public function __construct(JhuteSaleDlvOrderItem $model)
	{
		$this->model = $model;
	}
	
	
}