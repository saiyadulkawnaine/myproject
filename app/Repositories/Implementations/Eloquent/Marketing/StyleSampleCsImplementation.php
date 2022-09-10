<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\StyleSampleCsRepository;
use App\Model\Marketing\StyleSampleCs;
use App\Traits\Eloquent\MsTraits;
class StyleSampleCsImplementation implements StyleSampleCsRepository
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
	public function __construct(StyleSampleCs $model)
	{
		$this->model = $model;
	}
	
	public  function matrix($style_sample_id){
		return $this->makeMatrix($style_sample_id);
		
	}
	private function makeMatrix($style_sample_id){
		$matrix=array();
		$samplecses = $this->where([['style_sample_id','=',$style_sample_id]])->get();
		foreach ($samplecses as $samplecs) {
			$matrix[$samplecs->style_color_id][$samplecs->style_size_id]['qty']=$samplecs->qty;
			$matrix[$samplecs->style_color_id][$samplecs->style_size_id]['rate']=$samplecs->rate;
			$matrix[$samplecs->style_color_id][$samplecs->style_size_id]['amount']=$samplecs->amount;
		}
		return $matrix;
	}
}
