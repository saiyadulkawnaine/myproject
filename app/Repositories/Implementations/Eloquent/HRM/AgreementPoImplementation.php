<?php
namespace App\Repositories\Implementations\Eloquent\HRM;
use App\Repositories\Contracts\HRM\AgreementPoRepository;
use App\Model\HRM\AgreementPo;
use App\Traits\Eloquent\MsTraits; 
class AgreementPoImplementation implements AgreementPoRepository
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
	public function __construct(AgreementPo $model)
	{
		$this->model = $model;
	}
	
	
}