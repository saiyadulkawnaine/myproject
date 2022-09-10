<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Import;
use App\Repositories\Contracts\Commercial\Import\ImpDocAcceptRepository;
use App\Model\Commercial\Import\ImpDocAccept;
use App\Traits\Eloquent\MsTraits; 

class ImpDocAcceptImplementation implements ImpDocAcceptRepository
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
	public function __construct(ImpDocAccept $model)
	{
		$this->model = $model;
	}
}