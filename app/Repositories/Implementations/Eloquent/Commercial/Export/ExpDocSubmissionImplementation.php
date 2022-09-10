<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;

use App\Repositories\Contracts\Commercial\Export\ExpDocSubmissionRepository;
use App\Model\Commercial\Export\ExpDocSubmission;
use App\Traits\Eloquent\MsTraits; 
class ExpDocSubmissionImplementation implements ExpDocSubmissionRepository
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
	public function __construct(ExpDocSubmission $model)
	{
		$this->model = $model;
	}
	
	
}