<?php

namespace Neoco\Exception;

use \Application\Model\Helpers\MessagesConstants;

class OutOfSeasonException extends InfoException
{
    public function __construct()
    {
        parent::__construct(MessagesConstants::INFO_OUT_OF_SEASON_DESCRIPTION, MessagesConstants::INFO_OUT_OF_SEASON);
    }
}
