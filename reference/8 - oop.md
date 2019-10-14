# oop

- 每个变量都持有对象的引用。除非用clone显式赋值。
- 类内部可使用 new self 和 new parent 创建实例。
- 属性名与方法名可相同。若熟悉名被赋值为一个匿名函数，则调用时：($obj->bar)();
- ClassName::class 你可以获取一个字符串，包含了类 ClassName 的完全限定名称。发生于解析阶段，尚未自动加载。

**php 与对象实例之间有一层句柄，通过句柄再连接到对象实例，而不是直接引用连接，所以实例变量赋值新变量后设为null，新变量仍然有值。**
```php
// https://www.php.net/manual/zh/language.oop5.basic.php#79856
Class Object{
   public $foo="bar";
};

$objectVar = new Object();
$reference =& $objectVar;
$assignment = $objectVar

$objectVar = null;
print_r($objectVar);
print_r($reference);
print_r($assignment);

//
// $objectVar --->+---------+
//                |  NULL   |
// $reference --->+---------+
//                          
//                +---------+
// $assignment -->|(handle1)----+
//                +---------+   |
//                              |
//                              v
//                  Object(1):foo="qux"

``` 

## 属性

- static 和 const 用 :: 访问，否则用 -> 访问

## 类的自动加载

- spl_autoload_register() 注册自动加载器
- 5.3+,可以在加载器中throw自定义错误，然后被catch后处理。
- 自动加载不可用于 PHP 的 CLI 交互模式。
 
## 构造函数和析构函数
- __construct ([ mixed $args [, $... ]] ) : void
- 5.3.0 - 5.3.2 中使用类名同名方法作为构造函数。
- __destruct ( void ) : void
- 析构函数会在到某个对象的所有引用都被删除或者当对象被显式销毁时执行。
- 试图在析构函数（在脚本终止时被调用）中抛出一个异常会导致致命错误。
- 当两个对象相互赋值引用时，GC不会回收变量导致内存泄漏。除非手动调用 gc_collect_cycles() 回收。


## 访问控制 public / protected / private
- 同一个类的对象即使不是同一个实例也可以互相访问对方的私有与受保护成员。这是由于在这些对象的内部具体实现的细节都是已知的。


## 范围解析操作符 （::）
static , const, static function 可同名
```php
class A {

    public static $B = '1'; # Static class variable.

    const B = '2'; # Class constant.
   
    public static function B() { # Static class function.
        return '3';
    }
}
echo A::$B . A::B . A::B(); # Outputs: 123
```

self 指向代码所在的类，$this 指向继承后的类
```php
class ParentClass {
    function test() {
        self::who();    // will output 'parent'
        $this->who();    // will output 'child'
    }

    function who() {
        echo 'parent';
    }
}

class ChildClass extends ParentClass {
    function who() {
        echo 'child';
    }
}
$obj = new ChildClass();
$obj->test();
```
