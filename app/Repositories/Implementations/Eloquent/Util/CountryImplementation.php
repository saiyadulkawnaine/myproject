<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\CountryRepository;
use App\Model\Util\Country;
use App\Traits\Eloquent\MsTraits; 
class CountryImplementation implements CountryRepository
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
	public function __construct(Country $model)
	{
		$this->model = $model;
	}
}