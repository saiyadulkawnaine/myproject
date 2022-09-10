<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\AttachmentRepository;
use App\Model\Util\Attachment;
use App\Traits\Eloquent\MsTraits; 
class AttachmentImplementation implements AttachmentRepository
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
	public function __construct(Attachment $model)
	{
		$this->model = $model;
	}
}