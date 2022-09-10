<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\LocationRepository;
use App\Model\Util\Location;
use App\Traits\Eloquent\MsTraits; 
use App\Traits\Eloquent\MsUpdater;
class LocationImplementation implements LocationRepository
{
	 use MsTraits;
	 
	/**
	 * @var $model
	 */
	private $model;
 
	
	public function __construct(Location $model)
	{
		$this->model = $model;
	}
}