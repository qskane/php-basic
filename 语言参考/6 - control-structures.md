# control-structures


## if / else / elseif (else if)

- elseif 与 else if在使用{}时表现相同，使用冒号：表达时else if导致解析错误。
- if，while，for，foreach 和 switch 都可使用替代语法{改为冒号: ，}改为endfor等...

```php
// style 1
if(expr)
	statement
	
// style 2
if(expr){
	statement
}

// style 3
if(expr):
	statement
endif;
```

```php
// 将标量置于变量前，可避免少写一个等号导致的错误逻辑 
if(3 == $foo){
}
```

## for / while/ do-while / foreach

- for 中的三个语句可写多个表达式以逗号分隔，也可留空。
- foreach 不支持用“@”来抑制错误信息的能力。

可以使用 list 解包数组
```php
$array = [
    [1, 2],
    [3, 4],
];
foreach ($array as list($a, $b)) {
    echo "A: $a; B: $b\n";
}

```

## break / continue
- break 可接一个数字表示跳出几层
- 放于循环外的 break 会导致脚本终止执行

## switch
- 松散比较，即 ==
- continue 同 break
- 允许使用分号代替 case 语句后的冒号

## directive
https://www.php.net/manual/zh/control-structures.declare.php

- ticks（时钟周期）是一个在 declare 代码段中解释器每执行 N 条可计时的低级语句就会发生的事件。N 的值是在 declare 中的 directive 部分用 ticks=N 来指定的。
- encoding 指令来对每段脚本指定其编码方式
- ticks可用作实现性能分析工具
```php
declare(ticks=1);

declare(encoding='ISO-8859-1');
```
## return
- 中止函数，转交include文件控制权，中止主要程序
- return 是语言结构，不应该加括号


由于PHP在运行文件之前对其进行了处理，因此即使未执行该文件，在包含的文件中定义的所有功能仍然可用。
```
	// a.php
	<?php
	include 'b.php';
	
	foo();
	?>
	
	// b.php
	<?php
	return;
	
	function foo() {
		 echo 'foo';
	}
	
	// output 'foo'
	?>
```

## require / include / require_once / include_once

- require 失败导致E_COMPILE_ERROR 级别错误，include 失败导致E_WARNING不中断脚本。
- 当一个文件被包含时，其中所包含的代码继承了 require/include 所在行的变量范围。
- 所有在被包含文件中定义的函数和类都具有全局作用域。
- 当一个文件被包含时，语法解析器在目标文件的开头脱离 PHP 模式并进入 HTML 模式，到文件结尾处恢复。
  由于此原因，目标文件中需要作为 PHP 代码执行的任何代码都必须被包括在有效的 PHP 起始和结束标记之中。
- _once 仅会包含文件一次，避免相同函数命名冲突，变量重新赋值等。


## goto

- goto 操作符可以用来跳转到程序中的另一位置
- 目标位置只能位于同一个文件和作用域
- 通常的用法是用 goto 代替多层的 break。

```php
goto a;
echo 'Foo';
 
a:
echo 'Bar';
```








