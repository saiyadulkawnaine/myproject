<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Model\Util\Currency;
use App\Traits\Eloquent\MsTraits; 
class CurrencyImplementation implements CurrencyRepository
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
	public function __construct(Currency $model)
	{
		$this->model = $model;
	}
}