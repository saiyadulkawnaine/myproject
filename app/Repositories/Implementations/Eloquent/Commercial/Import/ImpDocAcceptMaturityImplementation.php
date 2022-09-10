<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Import;
use App\Repositories\Contracts\Commercial\Import\ImpDocAcceptMaturityRepository;
use App\Model\Commercial\Import\ImpDocAcceptMaturity;
use App\Traits\Eloquent\MsTraits; 

class ImpDocAcceptMaturityImplementation implements ImpDocAcceptMaturityRepository
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
	public function __construct(ImpDocAcceptMaturity $model)
	{
		$this->model = $model;
	}
}