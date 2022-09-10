<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\LocalExport;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpProRlzAmountRepository;
use App\Model\Commercial\LocalExport\LocalExpProRlzAmount;
use App\Traits\Eloquent\MsTraits; 
class LocalExpProRlzAmountImplementation implements LocalExpProRlzAmountRepository
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
	public function __construct(LocalExpProRlzAmount $model)
	{
		$this->model = $model;
	}
	
	
}