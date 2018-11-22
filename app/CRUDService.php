<?php

namespace App;

abstract class CRUDService
{
    public $modelClass;

    abstract public function columnsFilter(): array;
}