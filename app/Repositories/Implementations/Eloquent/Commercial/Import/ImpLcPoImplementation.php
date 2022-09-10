<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Import;
use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Model\Commercial\Import\ImpLcPo;
use App\Traits\Eloquent\MsTraits; 
class ImpLcPoImplementation implements ImpLcPoRepository
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
	public function __construct(ImpLcPo $model)
	{
		$this->model = $model;
	}
	
}