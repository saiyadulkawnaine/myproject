<?php
namespace App\Repositories\Implementations\Eloquent\System\Configuration;
use App\Repositories\Contracts\System\Configuration\ExpDocPrepStdDayRepository;
use App\Model\System\Configuration\ExpDocPrepStdDay;
use App\Traits\Eloquent\MsTraits; 
class ExpDocPrepStdDayImplementation implements ExpDocPrepStdDayRepository
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
	public function __construct(ExpDocPrepStdDay $model)
	{
		$this->model = $model;
	}
	
	
}