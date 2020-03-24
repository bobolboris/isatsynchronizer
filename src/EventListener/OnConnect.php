<?php

namespace App\EventListener;

use  Doctrine\DBAL\Event\ConnectionEventArgs;

class OnConnect
{
    protected $queries = [
        "SET SQL_MODE='ALLOW_INVALID_DATES'",
        "SET NAMES UTF8",
    ];
    /**
     * @param ConnectionEventArgs $event
     * @throws \Doctrine\DBAL\DBALException
     */
    public function postConnect(ConnectionEventArgs $event)
    {
        $event->getConnection()->executeQuery(implode(';', $this->queries));
    }
}