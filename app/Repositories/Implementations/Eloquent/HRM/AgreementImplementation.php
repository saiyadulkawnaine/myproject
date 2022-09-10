<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\AgreementRepository;
use App\Model\HRM\Agreement;
use App\Traits\Eloquent\MsTraits; 
class AgreementImplementation implements AgreementRepository
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
	public function __construct(Agreement $model)
	{
		$this->model = $model;
	}
	
	
}