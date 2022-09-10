<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\ExchangerateRepository;
use App\Model\Util\Exchangerate;
use App\Traits\Eloquent\MsTraits; 
class ExchangerateImplementation implements ExchangerateRepository
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
	public function __construct(Exchangerate $model)
	{
		$this->model = $model;
	}
}