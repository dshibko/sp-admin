<?php

namespace Neoco\Exception;

class NotFoundException extends \Exception
{

    const DEFAULT_MESSAGE_PATTERN = '"%s" was not found';

    public function __construct($message, $skipDefaultText = false)
    {
        if (!$skipDefaultText)
            $message = sprintf(self::DEFAULT_MESSAGE_PATTERN, $message);

        parent::__construct($message);
    }

}
