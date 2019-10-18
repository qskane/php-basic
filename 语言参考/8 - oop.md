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

## Static （静态）关键字
- static 方法不可使用 $this 变量。
- static 定义的属性和方法使用 :: 访问。
- 函数内部的静态变量作用域为函数内，但当程序离开此函数后，该静态变量的值不会丢失。

## abstract
## interface / implements

- 接口中可以定义常量。

## trait
- 覆盖优先级： 当前类的方法 >  trait 的方法 > 被继承的方法
- 两个 trait 都插入了一个同名的方法，如果没有明确解决冲突将会产生一个致命错误。使用 insteadof 操作符来明确指定使用冲突方法中的哪一个。或使用as 改名。
- 使用 as 语法还可以用来调整方法的访问控制。
- 可以通过trait组合成trait。
- trait 支持抽象方法。
- Traits 可以被静态成员静态方法定义。
- Trait 可以定义属性。
- 如果一个trait具有静态属性，则使用该trait的每个类都具有这些属性的独立实例。

```php
class Aliased_Talker {
    use A, B {
        B::smallTalk insteadof A;
        A::bigTalk insteadof B;
        B::bigTalk as talk;
    }
}

// 使用as 改名+修改访问可见性
class MyClass2 {
    use HelloWorld { sayHello as private myPrivateHello; }
}
```

## 重载

PHP所提供的重载（overloading）是指动态地创建类属性和方法。我们是通过魔术方法（magic methods）来实现的。

**应尽量避免使用此类魔术方法，设计不当的使用会导致很多BUG**
- IDE无法正确提示名称
- 访问任意错误名称
- 对非public名称发起访问
- 由于错误的__get()返回值导致逻辑错乱


###属性重载
属性重载只能在对象中进行，不能声明为static，静态调用也不会触发魔术方法。

- `$a = $obj->b = 8;` 此情况下 `__get()`不会被调用。
```
public __set ( string $name , mixed $value ) : void
public __get ( string $name ) : mixed
public __isset ( string $name ) : bool
public __unset ( string $name ) : void
```

### 方法重载

```
public __call ( string $name , array $arguments ) : mixed
public static __callStatic ( string $name , array $arguments ) : mixed
```

## 遍历对象

- 对普通对象foreach遍历可以访问所有可见属性，类外部可见public，类内部所有属性。
- 实现Iterator接口，自主控制循环输出。
- 实现IteratorAggregate 接口，自主控制循环输出。


## 魔术方法

__construct()， __destruct()
__call()， __callStatic()， __get()， __set()， __isset()， __unset()
__sleep()， __wakeup()
__toString()
__invoke()
__set_state()
__clone()
__debugInfo()

### __sleep() 和 __wakeup()
serialize() 和 unserialize()调用之前分别调用

### __toString()
- `public __toString ( void ) : string`
- 不能在 __toString() 方法中抛出异常
- 当类被当做字符串操作时调用

### __invoke()
PHP 5.3.0+,当尝试以调用函数的方式调用一个对象时，__invoke() 方法会被自动调用。
```
$obj = new CallableClass;
$obj(5); // int(5)
var_dump(is_callable($obj)); // bool(true)
```

### __set_state()

- `static __set_state ( array $properties ) : object`
- 自 PHP 5.1.0 起当调用 var_export() 导出类时，此静态 方法会被调用。

### __debugInfo() 
- PHP 5.6.0 起，调用 `var_dump()` 时会调用，用于调整 `var_dump()` 显示结果


## Final

- final 方法无法被覆盖，final类无法被继承。
- 属性不能被定义为 final。

## 对象复制
- 通过 clone 关键字来完成对象复制，将触发调用 `__clone()`。
- 当对象被复制后，PHP 5 会对对象的所有属性执行一个**浅复制**（shallow copy）。所有的引用属性 仍然会是一个指向原来的变量的引用。

## 对象比较
- 使用 `==` 比较时，两对象属性属性和值相同，且来自同一个对象，则为 `true`。
- 使用 `===` 比较时, 两变量必须来自同一个实例。

## 类型约束

## 后期静态绑定

- ClassA::staticFunc() 无论在何处调用，staticFunc中的static指向ClassA。
- parent::staticFunc() / self::staticFunc() staticFunc() 内的 static 会被后期静态绑定，指向继承末端的class，即调用最初来源方。
- self 关键字指向代码所在的class。

## 对象和引用

- PHP 的引用是别名，就是两个不同的变量名字指向相同的内容。
- 在 PHP 5，一个对象变量已经不再保存整个对象的值。只是保存一个标识符来访问真正的对象内容。

## 对象序列化

- serialize() unserialize()
- __sleep() __wakeup()
