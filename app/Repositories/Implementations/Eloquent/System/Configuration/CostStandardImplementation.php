<?php
namespace App\Repositories\Implementations\Eloquent\System\Configuration;
use App\Repositories\Contracts\System\Configuration\CostStandardRepository;
use App\Model\System\Configuration\CostStandard;
use App\Traits\Eloquent\MsTraits; 
class CostStandardImplementation implements CostStandardRepository
{
	use MsTraits;
	
	/**
	 * @var $model
	 */
	private $model;
 
	/**
	 * IleConfigImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(CostStandard $model)
	{
		$this->model = $model;
	}
	
	
}