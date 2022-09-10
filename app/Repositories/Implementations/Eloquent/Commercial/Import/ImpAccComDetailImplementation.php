<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Import;

use App\Repositories\Contracts\Commercial\Import\ImpAccComDetailRepository;
use App\Model\Commercial\Import\ImpAccComDetail;
use App\Traits\Eloquent\MsTraits; 

class ImpAccComDetailImplementation implements ImpAccComDetailRepository
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
	public function __construct(ImpAccComDetail $model)
	{
		$this->model = $model;
	}
}