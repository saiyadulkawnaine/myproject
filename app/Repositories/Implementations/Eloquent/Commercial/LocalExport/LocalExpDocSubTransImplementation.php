<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\LocalExport;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpDocSubTransRepository;
use App\Model\Commercial\LocalExport\LocalExpDocSubTrans;
use App\Traits\Eloquent\MsTraits; 
class LocalExpDocSubTransImplementation implements LocalExpDocSubTransRepository
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
	public function __construct(LocalExpDocSubTrans $model)
	{
		$this->model = $model;
	}
	
	
}