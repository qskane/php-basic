# generators

- 生成器提供了一种更容易的方法来实现简单的对象迭代，相比较定义类实现 Iterator 接口的方式，性能开销和复杂性大大降低。
- 一个生成器不可以返回值： 这样做会产生一个编译错误。然而return空是一个有效的语法并且它将会终止生成器继续执行。
- PHP的数组支持关联键值对数组，生成器也一样支持。 `yield $key => $value;`
- 生成器类似一个普通函数，把需要返回的值使用yield代替return中，且上下文的值可以连续。
- 没有参数传入的情况下被调用来生成一个 NULL值并配对一个自动的键名。
- 在PHP 7中，生成器委托允许您通过使用yield from关键字从另一个生成器，Traversable对象或数组生成值。

```php
    yield from [3, 4];
    yield from new ArrayIterator([5, 6]);
    yield from seven_eight();
```


 

