<?php
namespace App\Repositories\Implementations\Eloquent\System\Configuration;
use App\Repositories\Contracts\System\Configuration\IleConfigRepository;
use App\Model\System\Configuration\IleConfig;
use App\Traits\Eloquent\MsTraits; 
class IleConfigImplementation implements IleConfigRepository
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
	public function __construct(IleConfig $model)
	{
		$this->model = $model;
	}
	
	
}