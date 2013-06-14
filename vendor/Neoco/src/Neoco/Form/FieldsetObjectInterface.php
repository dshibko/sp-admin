<?php

namespace Neoco\Form;

interface FieldsetObjectInterface{
    public function setData($data);
    public function getData();
    public function initFieldsetByObject($object);
}

