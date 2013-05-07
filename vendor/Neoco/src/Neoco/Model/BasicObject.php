<?php

namespace Neoco\Model;

class BasicObject {

    public function getArrayCopy() {
        return get_object_vars($this);
    }

}
