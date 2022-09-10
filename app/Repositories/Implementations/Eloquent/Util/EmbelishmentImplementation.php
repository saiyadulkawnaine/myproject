<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\EmbelishmentRepository;
use App\Model\Util\Embelishment;
use App\Traits\Eloquent\MsTraits;
class EmbelishmentImplementation implements EmbelishmentRepository
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
	public function __construct(Embelishment $model)
	{
		$this->model = $model;
	}
	public  function getEmbelishments(){
		$row=$this
		->join('production_processes', function($join) {
		$join->on('production_processes.id', '=', 'embelishments.production_process_id');
		})
		->whereIn('production_area_id', [45,50,51,58,60])
		->get([
		'embelishments.id',
		'production_processes.process_name as name',
		]);
		return $row;
	}
}
