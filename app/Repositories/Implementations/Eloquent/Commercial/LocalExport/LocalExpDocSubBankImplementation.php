<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\LocalExport;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpDocSubBankRepository;
use App\Model\Commercial\LocalExport\LocalExpDocSubBank;
use App\Traits\Eloquent\MsTraits; 
class LocalExpDocSubBankImplementation implements LocalExpDocSubBankRepository
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
	public function __construct(LocalExpDocSubBank $model)
	{
		$this->model = $model;
	}
	
	
}