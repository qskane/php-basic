# functions

- 所有函数和类都具有全局作用域，可以定义在一个函数之内而在之外调用，反之亦然。
- PHP 不支持函数重载，也不可能取消定义或者重定义已声明的函数。
- 默认情况下，函数参数通过值传递。

## 类型声明

- Class/interface name		PHP 5.0.0
- self						PHP 5.0.0
- array						PHP 5.1.0
- callable					PHP 5.4.0
- bool	  					PHP 7.0.0
- float	   					PHP 7.0.0
- int	   					PHP 7.0.0
- string					PHP 7.0.0

除以上名称以外的类型提示，将被视为类名，如：不能使用boolean替换bool。

### 严格模式
`declare(strict_types=1);`

- 严格模式要求类型声明不被自动转换
- 每个文件可使用不同的严格模式，不同时将遵循调用方使用的模式。


### 可变数量的参数列表

- php 5.6+ 使用 ... 语法实现，5.6以前使用 func_num_args()，func_get_arg()，和 func_get_args() 。
- 也可使用 ... 解包数组传入函数参数。

### 返回值

- return 是语言结构
- 使用list($a,$b) = returnArrayFunction() 获取多个返回值
- 返回引用值,函数声明和接收值都需要使用&符号 
  ```php
		function &returns_reference()
		{
		  return $someref;
		}
		
		$newref =& returns_reference();
  ```
- 返回值类型声明，可选类型和函数参数类型声明相同


## 匿名函数

- 匿名函数目前是通过 Closure 类来实现的。
- 闭包可以从父作用域中继承变量，需要使用use传递。
- php 5.4.0起，类中的匿名函数会自动绑定当前类为$this,若不需要绑定则使用静态匿名函数 static function(){}。
- php 7.1.0起，不能use superglobals, $this,以及和参数同名的变量。
- use 使用的是按值传递。

	

