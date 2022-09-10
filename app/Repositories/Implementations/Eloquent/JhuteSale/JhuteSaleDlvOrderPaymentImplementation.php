<?php
namespace App\Repositories\Implementations\Eloquent\JhuteSale;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderRepository;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderPaymentRepository;
use App\Model\JhuteSale\JhuteSaleDlvOrderPayment;
use App\Traits\Eloquent\MsTraits; 
class JhuteSaleDlvOrderPaymentImplementation implements JhuteSaleDlvOrderPaymentRepository
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
	public function __construct(JhuteSaleDlvOrderPayment $model)
	{
		$this->model = $model;
	}
	
	
}