<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Import;
use App\Repositories\Contracts\Commercial\Import\ImpDocMaturityDtlRepository;
use App\Model\Commercial\Import\ImpDocMaturityDtl;
use App\Traits\Eloquent\MsTraits; 

class ImpDocMaturityDtlImplementation implements ImpDocMaturityDtlRepository
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
	public function __construct(ImpDocMaturityDtl $model)
	{
		$this->model = $model;
	}
}