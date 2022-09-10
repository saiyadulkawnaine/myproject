<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\MailSetupEmailToRepository;
use App\Model\Util\MailSetupEmailTo;
use App\Traits\Eloquent\MsTraits; 
class MailSetupEmailToImplementation implements MailSetupEmailToRepository
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
	public function __construct(MailSetupEmailTo $model)
	{
		$this->model = $model;
	}
}