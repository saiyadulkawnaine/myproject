<?php
namespace App\Repositories\Implementations\Eloquent\JhuteSale;

use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvItemRepository;
use App\Model\JhuteSale\JhuteSaleDlvItem;
use App\Traits\Eloquent\MsTraits; 
class JhuteSaleDlvItemImplementation implements JhuteSaleDlvItemRepository
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
	public function __construct(JhuteSaleDlvItem $model)
	{
		$this->model = $model;
	}
	
	
}