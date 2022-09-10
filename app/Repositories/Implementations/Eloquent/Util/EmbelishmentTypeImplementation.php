<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Model\Util\EmbelishmentType;
use App\Traits\Eloquent\MsTraits;
class EmbelishmentTypeImplementation implements EmbelishmentTypeRepository
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
	public function __construct(EmbelishmentType $model)
	{
		$this->model = $model;
	}
	
	public function getAopTypes(){
		$row=$this->join('embelishments', function($join) {
		$join->on('embelishments.id', '=', 'embelishment_types.embelishment_id');
		})
		->join('production_processes', function($join) {
		$join->on('production_processes.id', '=', 'embelishments.production_process_id');
		})
		->where([['production_processes.production_area_id','=',25]])
		->get([
		'embelishment_types.id',
		'embelishment_types.name',
		]);
		return $row;
	}
	
	public function getEmbelishmentTypes(){
		$row=$this->join('embelishments', function($join) {
		$join->on('embelishments.id', '=', 'embelishment_types.embelishment_id');
		})
		->join('production_processes', function($join) {
		$join->on('production_processes.id', '=', 'embelishments.production_process_id');
		})
		->whereIn('production_processes.production_area_id', [45,50,51,58,60])
		->get([
		'embelishment_types.id',
		'embelishment_types.name',
		]);
		return $row;
	}
}
