<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\LocalExport;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpDocSubAcceptRepository;
use App\Model\Commercial\LocalExport\LocalExpDocSubAccept;
use App\Traits\Eloquent\MsTraits; 
class LocalExpDocSubAcceptImplementation implements LocalExpDocSubAcceptRepository
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
	public function __construct(LocalExpDocSubAccept $model)
	{
		$this->model = $model;
	}
	
	
}