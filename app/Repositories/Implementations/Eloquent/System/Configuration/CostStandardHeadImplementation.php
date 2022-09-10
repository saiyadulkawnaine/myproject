<?php
namespace App\Repositories\Implementations\Eloquent\System\Configuration;
use App\Repositories\Contracts\System\Configuration\CostStandardHeadRepository;
use App\Model\System\Configuration\CostStandardHead;
use App\Traits\Eloquent\MsTraits; 
class CostStandardHeadImplementation implements CostStandardHeadRepository
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
	public function __construct(CostStandardHead $model)
	{
		$this->model = $model;
	}
	
	
}