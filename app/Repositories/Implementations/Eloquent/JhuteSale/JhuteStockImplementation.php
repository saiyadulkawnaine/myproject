<?php
namespace App\Repositories\Implementations\Eloquent\JhuteSale;
use App\Repositories\Contracts\JhuteSale\JhuteStockRepository;
use App\Model\JhuteSale\JhuteStock;
use App\Traits\Eloquent\MsTraits; 
class JhuteStockImplementation implements JhuteStockRepository
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
	public function __construct(JhuteStock $model)
	{
		$this->model = $model;
	}
	
	
}