<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\SectionRepository;
use App\Model\Util\Section;
use App\Traits\Eloquent\MsTraits; 
use App\Traits\Eloquent\MsUpdater;
class SectionImplementation implements SectionRepository
{
	 use MsTraits;
	 
	/**
	 * @var $model
	 */
	private $model;
 
	
	public function __construct(Section $model)
	{
		$this->model = $model;
	}
}