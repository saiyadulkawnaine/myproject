<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\LocalExport;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpPiRepository;
use App\Model\Commercial\LocalExport\LocalExpPi;
use App\Traits\Eloquent\MsTraits; 
class LocalExpPiImplementation implements LocalExpPiRepository
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
	public function __construct(LocalExpPi $model)
	{
		$this->model = $model;
	}
	
	
}