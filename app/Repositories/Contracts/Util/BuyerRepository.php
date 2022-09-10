<?php

namespace App\Repositories\Contracts\Util;

use App\Repositories\Contracts\MsRepository;

interface BuyerRepository extends  MsRepository
{
    public function buyers();
    public function consignee();
    public function notifyingParties();
    public function embelishmentSubcontact();
    public function soEmbCutpanel();
}
