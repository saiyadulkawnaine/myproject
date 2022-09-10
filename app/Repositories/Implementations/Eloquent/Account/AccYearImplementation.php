<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccYearRepository;
use App\Model\Account\AccYear;
use App\Traits\Eloquent\MsTraits; 
class AccYearImplementation implements AccYearRepository
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
	public function __construct(AccYear $model)
	{
		$this->model = $model;
	}

	public function getBycompany($company_id)
	{
		$accyear=$this
        ->where([['company_id','=',$company_id]])
        ->get();
        return $accyear;

	}
	
	
}
