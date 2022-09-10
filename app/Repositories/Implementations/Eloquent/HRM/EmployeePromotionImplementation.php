<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\EmployeePromotionRepository;
use App\Model\HRM\EmployeePromotion;
use App\Traits\Eloquent\MsTraits; 
class EmployeePromotionImplementation implements EmployeePromotionRepository
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
	public function __construct(EmployeePromotion $model)
	{
		$this->model = $model;
	}
	
	
}