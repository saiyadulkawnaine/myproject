<?php
namespace App\Repositories\Implementations\Eloquent\JhuteSale;
use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvRepository;
use App\Model\JhuteSale\JhuteSaleDlv;
use App\Traits\Eloquent\MsTraits; 
class JhuteSaleDlvImplementation implements JhuteSaleDlvRepository
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
	public function __construct(JhuteSaleDlv $model)
	{
		$this->model = $model;
	}
	
	
}