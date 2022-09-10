<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpLcTagPiRepository;
use App\Model\Commercial\Export\ExpLcTagPi;
use App\Traits\Eloquent\MsTraits; 
class ExpLcTagPiImplementation implements ExpLcTagPiRepository
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
	public function __construct(ExpLcTagPi $model)
	{
		$this->model = $model;
	}
	
	
}