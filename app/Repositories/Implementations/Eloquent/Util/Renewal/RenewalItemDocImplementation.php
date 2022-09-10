<?php
 
namespace App\Repositories\Implementations\Eloquent\Util\Renewal;
use App\Repositories\Contracts\Util\Renewal\RenewalItemDocRepository;
use App\Model\Util\Renewal\RenewalItemDoc;
use App\Traits\Eloquent\MsTraits; 
class RenewalItemDocImplementation implements RenewalItemDocRepository
{
	use MsTraits;
	
	/**
	 * @var $model
	 */
	private $model;
 
	/**
	 *RenewalItemDocImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(RenewalItemDoc $model)
	{
		$this->model = $model;
	}
	
	
}