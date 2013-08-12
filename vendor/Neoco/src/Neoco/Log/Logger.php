<?php

namespace Neoco\Log;

use \Zend\Log\Logger as BasicLogger;

class Logger extends BasicLogger
{

    /**
     * @param int $priority
     * @throws \Exception
     * @return string
     */
    public function getPriorityName($priority) {
        if (!array_key_exists($priority, $this->priorities))
            throw new \Exception('Wrong logger priority selected');
        return $this->priorities[$priority];
    }

}
