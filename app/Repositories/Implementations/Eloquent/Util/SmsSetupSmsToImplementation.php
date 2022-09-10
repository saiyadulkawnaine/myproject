<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\SmsSetupSmsToRepository;
use App\Model\Util\SmsSetupSmsTo;
use App\Traits\Eloquent\MsTraits; 
class SmsSetupSmsToImplementation implements SmsSetupSmsToRepository
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
	public function __construct(SmsSetupSmsTo $model)
	{
		$this->model = $model;
	}
}