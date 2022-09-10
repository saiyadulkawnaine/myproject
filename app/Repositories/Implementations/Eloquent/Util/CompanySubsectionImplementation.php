<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\CompanySubsectionRepository;
use App\Model\Util\CompanySubsection;
use App\Traits\Eloquent\MsTraits; 
use App\Traits\Eloquent\MsUpdater;
class CompanySubsectionImplementation implements CompanySubsectionRepository
{
	 use MsTraits;
	 
	/**
	 * @var $model
	 */
	private $model;
 
	
	public function __construct(CompanySubsection $model)
	{
		$this->model = $model;
	}
}