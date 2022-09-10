<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\LocalExport;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpLcTagPiRepository;
use App\Model\Commercial\LocalExport\LocalExpLcTagPi;
use App\Traits\Eloquent\MsTraits; 
class LocalExpLcTagPiImplementation implements LocalExpLcTagPiRepository
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
	public function __construct(LocalExpLcTagPi $model)
	{
		$this->model = $model;
	}
	
	
}