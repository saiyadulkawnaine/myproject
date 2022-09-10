<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Import;
use App\Repositories\Contracts\Commercial\Import\ImpShippingMarkRepository;
use App\Model\Commercial\Import\ImpShippingMark;
use App\Traits\Eloquent\MsTraits; 

class ImpShippingMarkImplementation implements ImpShippingMarkRepository
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
	public function __construct(ImpShippingMark $model)
	{
		$this->model = $model;
	}
}