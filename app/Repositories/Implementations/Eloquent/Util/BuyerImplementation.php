<?php

namespace App\Repositories\Implementations\Eloquent\Util;

use App\Repositories\Contracts\Util\BuyerRepository;
use App\Model\Util\Buyer;
use App\Traits\Eloquent\MsTraits;

class BuyerImplementation implements BuyerRepository
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
	public function __construct(Buyer $model)
	{
		$this->model = $model;
	}

	public function notifyingParties()
	{
		$notifyingParties = $this->selectRaw(
			'buyers.*'
		)
			->join('buyer_natures', function ($join) {
				$join->on('buyer_natures.buyer_id', '=', 'buyers.id');
			})
			->where([['buyer_natures.contact_nature_id', '=', 11]])
			->get();
		return $notifyingParties;
	}

	public function consignee()
	{
		$consignees = $this->selectRaw(
			'buyers.*'
		)
			->join('buyer_natures', function ($join) {
				$join->on('buyer_natures.buyer_id', '=', 'buyers.id');
			})
			->where([['buyer_natures.contact_nature_id', '=', 12]])
			->get();
		return $consignees;
	}
	public function buyers()
	{
		$buyer = [];
		if (\Auth::user()->level() == 5) {
			$buyer = $this->selectRaw(
				'buyers.*'
			)
				->join('buyer_natures', function ($join) {
					$join->on('buyer_natures.buyer_id', '=', 'buyers.id');
				})
				->where([['buyer_natures.contact_nature_id', '=', 1]])
				->orderBy('buyers.name')
				->get();
		} else {
			$buyer = $this->selectRaw(
				'buyers.*'
			)
				->join('buyer_natures', function ($join) {
					$join->on('buyer_natures.buyer_id', '=', 'buyers.id');
				})
				->join('buyer_users', function ($join) {
					$join->on('buyer_users.buyer_id', '=', 'buyers.id');
				})
				->where([['buyer_natures.contact_nature_id', '=', 1]])
				->where([['buyer_users.user_id', '=', \Auth::user()->id]])
				->orderBy('buyers.name')
				->get();
		}
		return $buyer;
	}
	public function embelishmentSubcontact()
	{
		$embelishmentSubcontact = $this->selectRaw(
			'buyers.id,
			buyers.name
			'
		)
			->join('buyer_natures', function ($join) {
				$join->on('buyer_natures.buyer_id', '=', 'buyers.id');
			})
			->where([['buyer_natures.contact_nature_id', '=', 7]])
			->get();
		return $embelishmentSubcontact;
	}

	public function soEmbCutpanel()
	{
		$soembcutpanel = $this->selectRaw(
			'buyers.id,
			buyers.name
			'
		)
			->join('buyer_natures', function ($join) {
				$join->on('buyer_natures.buyer_id', '=', 'buyers.id');
			})
			->where([['buyer_natures.contact_nature_id', '=', 7]])
			->get();
		return $soembcutpanel;
	}
}
