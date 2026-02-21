<?php

namespace App\Repositories;

use App\Models\Tenant\FixedAsset;

class FixedAssetRepository extends BaseRepository
{
    public function __construct(FixedAsset $asset)
    {
        $this->setInstance($asset);
    }
}
