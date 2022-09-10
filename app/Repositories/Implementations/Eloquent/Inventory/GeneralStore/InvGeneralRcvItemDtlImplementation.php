<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\GeneralStore;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralRcvItemDtlRepository;
use App\Model\Inventory\GeneralStore\InvGeneralRcvItemDtl;
use App\Traits\Eloquent\MsTraits; 
class InvGeneralRcvItemDtlImplementation implements InvGeneralRcvItemDtlRepository
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
	public function __construct(InvGeneralRcvItemDtl $model)
	{
		$this->model = $model;
	}
	
	
}