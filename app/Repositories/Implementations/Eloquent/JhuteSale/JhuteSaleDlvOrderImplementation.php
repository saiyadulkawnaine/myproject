<?php
namespace App\Repositories\Implementations\Eloquent\JhuteSale;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderRepository;
use App\Model\JhuteSale\JhuteSaleDlvOrder;
use App\Traits\Eloquent\MsTraits; 
class JhuteSaleDlvOrderImplementation implements JhuteSaleDlvOrderRepository
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
	public function __construct(JhuteSaleDlvOrder $model)
	{
		$this->model = $model;
	}
	
	
}