<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Import;
use App\Repositories\Contracts\Commercial\Import\ImpDocMaturityRepository;
use App\Model\Commercial\Import\ImpDocMaturity;
use App\Traits\Eloquent\MsTraits; 

class ImpDocMaturityImplementation implements ImpDocMaturityRepository
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
	public function __construct(ImpDocMaturity $model)
	{
		$this->model = $model;
	}
}