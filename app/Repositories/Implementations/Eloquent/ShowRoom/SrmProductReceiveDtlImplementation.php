<?php
namespace App\Repositories\Implementations\Eloquent\ShowRoom;

use App\Repositories\Contracts\ShowRoom\SrmProductReceiveDtlRepository;
use App\Model\ShowRoom\SrmProductReceiveDtl;
use App\Traits\Eloquent\MsTraits;
class SrmProductReceiveDtlImplementation implements SrmProductReceiveDtlRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 *SrmProductReceiveDtlImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SrmProductReceiveDtl $model)
	{
		$this->model = $model;
	}
}
