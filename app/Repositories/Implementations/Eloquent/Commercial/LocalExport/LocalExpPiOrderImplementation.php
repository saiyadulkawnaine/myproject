<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\LocalExport;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpPiOrderRepository;
use App\Model\Commercial\LocalExport\LocalExpPiOrder;
use App\Traits\Eloquent\MsTraits; 
class LocalExpPiOrderImplementation implements LocalExpPiOrderRepository
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
	public function __construct(LocalExpPiOrder $model)
	{
		$this->model = $model;
	}
	
	
}