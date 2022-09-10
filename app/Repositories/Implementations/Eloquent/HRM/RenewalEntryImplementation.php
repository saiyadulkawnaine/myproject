<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\RenewalEntryRepository;
use App\Model\HRM\RenewalEntry;
use App\Traits\Eloquent\MsTraits; 
class RenewalEntryImplementation implements RenewalEntryRepository
{
	use MsTraits;
	
	/**
	 * @var $model
	 */
	private $model;
 
	/**
	 * RenewalEntryImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(RenewalEntry $model)
	{
		$this->model = $model;
	}
	
	
}