<?php
namespace App\Repositories\Implementations\Eloquent\ShowRoom;

use App\Repositories\Contracts\ShowRoom\SrmProductReceiveRepository;
use App\Model\ShowRoom\SrmProductReceive;
use App\Traits\Eloquent\MsTraits;
class SrmProductReceiveImplementation implements SrmProductReceiveRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 *SrmProductReceiveImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SrmProductReceive $model)
	{
		$this->model = $model;
	}
}
