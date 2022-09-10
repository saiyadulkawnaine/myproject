<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Import;
use App\Repositories\Contracts\Commercial\Import\ImpBackedExpLcScRepository;
use App\Model\Commercial\Import\ImpBackedExpLcSc;
use App\Traits\Eloquent\MsTraits; 

class ImpBackedExpLcScImplementation implements ImpBackedExpLcScRepository
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
	public function __construct(ImpBackedExpLcSc $model)
	{
		$this->model = $model;
	}
}