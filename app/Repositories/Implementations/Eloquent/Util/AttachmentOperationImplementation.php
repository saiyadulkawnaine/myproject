<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\AttachmentOperationRepository;
use App\Model\Util\AttachmentOperation;
use App\Traits\Eloquent\MsTraits; 
class AttachmentOperationImplementation implements AttachmentOperationRepository
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
	public function __construct(AttachmentOperation $model)
	{
		$this->model = $model;
	}
}