<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\AgreementFileRepository;
use App\Model\HRM\AgreementFile;
use App\Traits\Eloquent\MsTraits; 
class AgreementFileImplementation implements AgreementFileRepository
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
	public function __construct(AgreementFile $model)
	{
		$this->model = $model;
	}
	
	
}