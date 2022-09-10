<?php

namespace App\Repositories\Contracts\Util;
use App\Repositories\Contracts\MsRepository;

interface SupplierRepository extends  MsRepository
{
	public function otherPartise();
	public function forwardingAgents();
	public function transportAgents();
	public function shippingLines();
	public function yarnSupplier();
	public function garmentSubcontractors();
	public function embellishmentSubcontractor();
	public function knitSubcontractor();
	public function DyeFinSubcontractor();
	public function AopSubcontractor();
	public function trimsSupplier();
	public function DyesAndChemSupplier();
	public function GeneralItemSupplier();
	public function YarnDyeingSubcontractor();
	public function indentor();
	public function insuranceCompany();
	public function fabricSupplier();
}
