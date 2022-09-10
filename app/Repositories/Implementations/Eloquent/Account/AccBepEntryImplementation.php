<?php
namespace App\Repositories\Implementations\Eloquent\Account;
use App\Repositories\Contracts\Account\AccBepEntryRepository;
use App\Model\Account\AccBepEntry;
use App\Traits\Eloquent\MsTraits; 
class AccBepEntryImplementation implements AccBepEntryRepository
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
	public function __construct(AccBepEntry $model)
	{
		$this->model = $model;
	}
	
}
