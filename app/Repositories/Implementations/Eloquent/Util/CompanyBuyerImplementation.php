<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\CompanyBuyerRepository;
use App\Model\Util\CompanyBuyer;
use App\Traits\Eloquent\MsTraits; 
class CompanyBuyerImplementation implements CompanyBuyerRepository
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
	public function __construct(CompanyBuyer $model)
	{
		$this->model = $model;
	}
	
	
}