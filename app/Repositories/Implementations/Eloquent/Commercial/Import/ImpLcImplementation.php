<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Import;
use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Model\Commercial\Import\ImpLc;
use App\Traits\Eloquent\MsTraits; 
class ImpLcImplementation implements ImpLcRepository
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
	public function __construct(ImpLc $model)
	{
		$this->model = $model;
	}
	
	
}