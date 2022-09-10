<?php
namespace App\Repositories\Implementations\Eloquent\Approval;
use App\Repositories\Contracts\Approval\ApprovalCommentHistoryRepository;
use App\Model\Approval\ApprovalCommentHistory;
use App\Traits\Eloquent\MsTraits; 

class ApprovalCommentHistoryImplementation implements ApprovalCommentHistoryRepository
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
	public function __construct(ApprovalCommentHistory $model)
	{
		$this->model = $model;
	}
	
}
