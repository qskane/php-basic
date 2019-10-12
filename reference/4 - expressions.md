# EXPRESSIONS

任何有值的东西


仅在for语句中可使用逗号定义不同的变量。
```php
$a = 2, $b = 4; // parse error

// works
for ($a = 2, $b = 4; $a < 3; $a++)
{
  echo $a."\n";
  echo $b."\n";
}
```

echo 不返回值，不是函数，不能作为表达式的一部分，而是语法结构。
```
php ($val) ? echo('true') : echo('false');
```
