<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Import;
use App\Repositories\Contracts\Commercial\Import\ImpLcFileRepository;
use App\Model\Commercial\Import\ImpLcFile;
use App\Traits\Eloquent\MsTraits; 
class ImpLcFileImplementation implements ImpLcFileRepository
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
	public function __construct(ImpLcFile $model)
	{
		$this->model = $model;
	}
	
	
}