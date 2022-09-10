<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\LocalExport;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpProRlzRepository;
use App\Model\Commercial\LocalExport\LocalExpProRlz;
use App\Traits\Eloquent\MsTraits; 
class LocalExpProRlzImplementation implements LocalExpProRlzRepository
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
	public function __construct(LocalExpProRlz $model)
	{
		$this->model = $model;
	}
	
	
}