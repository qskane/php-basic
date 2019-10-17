<?php

class A {
    public static function foo() {
        echo "A called";
        static::who();
    }

    public static function who() {
        echo __CLASS__."\n";
    }
}

class B extends A {
    public static function test() {
        //A::foo();
        //parent::foo();
        self::foo();
    }

    public static function who() {
        echo __CLASS__."\n";
    }
}
class C extends B {
    public static function who() {
        echo 'C who called';
        echo __CLASS__."\n";
    }
}

C::test();