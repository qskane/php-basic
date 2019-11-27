<?php
Class ObjectA{
    public $foo="bar";
};

$objectVar = new ObjectA();
$reference =& $objectVar;
$assignment = $objectVar;

$objectVar = null;
var_dump($objectVar);
var_dump($reference);
var_dump($assignment);
