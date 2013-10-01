<?php

namespace Neoco\Exception;

class AppClubNotFoundException extends NotFoundException
{

    const NOT_FOUND_TARGET = 'App Club';

    public function __construct()
    {
        parent::__construct(self::NOT_FOUND_TARGET);
    }

}
