<?php
namespace App\Repositories\Implementations\Eloquent\ShowRoom;

use App\Repositories\Contracts\ShowRoom\SrmProductScanRepository;
use App\Model\ShowRoom\SrmProductScan;
use App\Traits\Eloquent\MsTraits;
class SrmProductScanImplementation implements SrmProductScanRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 *SrmProductScanImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SrmProductScan $model)
	{
		$this->model = $model;
	}
}