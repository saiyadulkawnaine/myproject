<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\LocalExport;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpLcRepository;
use App\Model\Commercial\LocalExport\LocalExpLc;
use App\Traits\Eloquent\MsTraits; 
class LocalExpLcImplementation implements LocalExpLcRepository
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
	public function __construct(LocalExpLc $model)
	{
		$this->model = $model;
	}
	
	
}