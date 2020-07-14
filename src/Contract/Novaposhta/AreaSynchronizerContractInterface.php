<?php

namespace App\Contract\Novaposhta;

use App\Contract\CanClearInterface;
use App\Contract\CanLoadInterface;
use App\Contract\CanReloadInterface;
use App\Contract\CanSynchronizeAll;

interface AreaSynchronizerContract extends CanLoadInterface, CanSynchronizeAll, CanClearInterface, CanReloadInterface
{
}