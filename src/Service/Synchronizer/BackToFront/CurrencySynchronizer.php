<?php

namespace App\Service\Synchronizer\BackToFront;

use App\Contract\BackToFront\CurrencySynchronizerContract;
use App\Service\Synchronizer\BackToFront\Implementation\CurrencySynchronizer as CurrencySynchronizerBase;

class CurrencySynchronizer extends CurrencySynchronizerBase implements CurrencySynchronizerContract
{
    /**
     *
     */
    public function load(): void
    {
        parent::load();
    }

    /**
     *
     */
    public function synchronizeAll(): void
    {
        $currenciesFront = $this->currencyFrontRepository->findAll();

        foreach ($currenciesFront as $currencyFront) {
            $this->synchronizeCurrency($currencyFront);
        }
    }
}