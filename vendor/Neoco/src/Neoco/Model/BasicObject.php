<?php

namespace Neoco\Model;

class BasicObject {

    public function getArrayCopy() {
        return get_object_vars($this);
    }

    public function hasEmptyFields() {
        $fieldsArray = $this->getArrayCopy();
        foreach ($fieldsArray as $field)
            if (empty($field))
                return true;
        return false;
    }

}
