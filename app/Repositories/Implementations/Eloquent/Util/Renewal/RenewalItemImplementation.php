<?php
 
namespace App\Repositories\Implementations\Eloquent\Util\Renewal;
use App\Repositories\Contracts\Util\Renewal\RenewalItemRepository;
use App\Model\Util\Renewal\RenewalItem;
use App\Traits\Eloquent\MsTraits; 
class RenewalItemImplementation implements RenewalItemRepository
{
	use MsTraits;
	
	/**
	 * @var $model
	 */
	private $model;
 
	/**
	 *RenewalItemImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(RenewalItem $model)
	{
		$this->model = $model;
	}
	
	
}