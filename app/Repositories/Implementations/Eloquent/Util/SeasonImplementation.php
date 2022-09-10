<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\SeasonRepository;
use App\Model\Util\Season;
use App\Traits\Eloquent\MsTraits; 
class SeasonImplementation implements SeasonRepository
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
	public function __construct(Season $model)
	{
		$this->model = $model;
	}
}