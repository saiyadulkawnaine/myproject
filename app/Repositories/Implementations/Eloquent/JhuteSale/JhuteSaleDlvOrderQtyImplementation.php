<?php
namespace App\Repositories\Implementations\Eloquent\JhuteSale;

use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderQtyRepository;
use App\Model\JhuteSale\JhuteSaleDlvOrderQty;
use App\Traits\Eloquent\MsTraits;

class JhuteSaleDlvOrderQtyImplementation implements JhuteSaleDlvOrderQtyRepository
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
	public function __construct(JhuteSaleDlvOrderQty $model)
	{
		$this->model = $model;
	}
}
