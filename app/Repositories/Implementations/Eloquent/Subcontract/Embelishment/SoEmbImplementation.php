<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRepository;
use App\Model\Subcontract\Embelishment\SoEmb;
use App\Traits\Eloquent\MsTraits;
class SoEmbImplementation implements SoEmbRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 *SoKnitImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoEmb $model)
	{
		$this->model = $model;
	}
}
