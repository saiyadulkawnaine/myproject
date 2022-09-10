<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\LocalExport;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpProRlzDeductRepository;
use App\Model\Commercial\LocalExport\LocalExpProRlzDeduct;
use App\Traits\Eloquent\MsTraits; 
class LocalExpProRlzDeductImplementation implements LocalExpProRlzDeductRepository
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
	public function __construct(LocalExpProRlzDeduct $model)
	{
		$this->model = $model;
	}
	
	
}