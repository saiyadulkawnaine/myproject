<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\CompositionRepository;
use App\Model\Util\Composition;
use App\Traits\Eloquent\MsTraits; 
class CompositionImplementation implements CompositionRepository
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
	public function __construct(Composition $model)
	{
		$this->model = $model;
	}
}