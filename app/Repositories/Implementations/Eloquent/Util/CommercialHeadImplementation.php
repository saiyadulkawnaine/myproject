<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Model\Util\CommercialHead;
use App\Traits\Eloquent\MsTraits; 
class CommercialHeadImplementation implements CommercialHeadRepository
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
	public function __construct(CommercialHead $model)
	{
		$this->model = $model;
	}
	
	
}