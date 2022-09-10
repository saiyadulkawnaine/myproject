<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\ContactNatureRepository;
use App\Model\Util\ContactNature;
use App\Traits\Eloquent\MsTraits; 
class ContactNatureImplementation implements ContactNatureRepository
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
	public function __construct(ContactNature $model)
	{
		$this->model = $model;
	}
}