<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\BuyerGmtssampleRepository;
use App\Model\Util\BuyerGmtssample;
use App\Traits\Eloquent\MsTraits; 
class BuyerGmtssampleImplementation implements BuyerGmtssampleRepository
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
	public function __construct(BuyerGmtssample $model)
	{
		$this->model = $model;
	}
}