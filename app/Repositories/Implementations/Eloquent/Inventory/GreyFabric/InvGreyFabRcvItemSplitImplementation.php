<?php
namespace App\Repositories\Implementations\Eloquent\Inventory\GreyFabric;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabRcvItemSplitRepository;
use App\Model\Inventory\GreyFabric\InvGreyFabRcvItemSplit;
use App\Traits\Eloquent\MsTraits; 
class InvGreyFabRcvItemSplitImplementation implements InvGreyFabRcvItemSplitRepository
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
	public function __construct(InvGreyFabRcvItemSplit $model)
	{
		$this->model = $model;
	}
}