<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Model\Util\Subsection;
use App\Traits\Eloquent\MsTraits; 
use App\Traits\Eloquent\MsUpdater;
class SubsectionImplementation implements SubsectionRepository
{
	 use MsTraits;
	 
	/**
	 * @var $model
	 */
	private $model;
 
	
	public function __construct(Subsection $model)
	{
		$this->model = $model;
	}
}