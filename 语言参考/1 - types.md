# TYPES

- 9种原始类型，4种标量类型，3种复合类型，2种特殊类型
- 5种伪类型

## boolean
- 仅有 true | false
- 使用(bool)|(boolean)强制转换

强制转换为false的情况
- (integer) 0  => false
- (float) 0.0 => false
- (string) "","0" => false
- (array) [] => false
- null => false
- 空标记生成的simpleXML对象 => false

其余情况一律为true
- -1,-2 => **true**
- 任何resource类型

## integer

- 可以有不同的进制表示integer
	```
	- 十进制(decimal) : [1-9][0-9]* | 0
	- 二进制(binary) **0b** :0b[01]+
	- 八进制(octal)  **0** : 0[0-7]+
	- 十六进制(hexadecimal) **0x** : 0[xX][0-9a-fA-F]+
	- integer :   [+-]?decimal
				| [+-]?hexadecimal
				| [+-]?octal
				| [+-]?binary
	```
- 整数溢出：当integer超出最大值时会被转换为float
  - 32位系统最大值为 符号2**31，大约二十一亿
  - 64位系统最大值为 符号2**63
- 没有整除的运算符
- 类型转换
	- float => integer 直接舍弃小数,向下取整。 
	- 超出integer范围的float会转为0（php7.0+）
	- resource => integer  结果会是 PHP 运行时为 resource 分配的唯一资源号
	- false => integer = 0 ，true =>integer = 1
	- 从其他类型转为integer类型，行为不可预期
	- string => integer
	  - 该字符串的开始部分决定了它的值
	  - 如果该字符串以合法的数值开始，则使用该数值，否则为0。
	  - 合法数值由可选的正负号，后面跟着一个或多个数字（可能有小数点），再跟着可选的指数部分。指数部分由 'e' 或 'E' 后面跟着一个或多个数字构成。
		  ```
			"10.5" => float(10.5)
			"-1.3e3" => float(-1.3e3)
			"bob-1.3e3" => integer(0)
			"bob3" => integer(0) 
			"10 Small Pigs"=> integer(10)
			"0123" => integer(123) // 不会使用8进制
		  ```
## float(double)

### 精度
- 浮点数的精度有限,不能被内部所使用的二进制精确表示。
- floor((0.1+0.7)*10)通常会返回 7 而不是预期中的 8，该结果内部的表示其实是类似 7.9999999999999991118...。
- 永远不要相信浮点数结果精确到了最后一位，也永远不要比较两个浮点数是否相等
### 比较浮点数
- 迂回方式比较
```php
	$a = 1.23456789;
	$b = 1.23456780;
	$epsilon = 0.00001;
	
	if(abs($a-$b) < $epsilon) {
		echo "true";
	}
```
- 舍弃部分精度,round($x,2)

### 常量NAN
- 一个在浮点数运算中未定义或不可表述的值
- NAN 与其他任何值比较，结果都为false，唯一例外： 
	```
	NAN == true => true  // NAN转为boolean为true
	``` 
- 使用is_nan检查

```
$x = 8 - 6.4;  // var_dump($x) => float(1.6)
$y = 1.6;
var_dump($x == $y);  // false  $x实际为1.5999...
var_dump(round($x,2) == round($y));  // true 
```

## string
- PHP 中的 string 的实现方式是一个由字节组成的数组再加上一个整数指明缓冲区长度
- 一个字符 等于 一个字节
- 只能支持256 的字符集，不支持Unicode 
- string 最大可以达到 2GB
- 如果字符串变量中紧跟字符会导致无法识别变量边界，可用`{}`明确表示
- 特殊示例
	- "{${getName()}}" 输出方法返回值
	- "${great}" `$`可位于`{`之前

### 表达形式
- 单引号
  - 不会转义 \r \n 等特殊符号
  - \\ 输出 \ ， \' 输出 ' 
- 双引号
  - 会转义 \r \n 等特殊符号
  - 会解析变量

- heredoc
  - 使用 `<<<` + 自定义字符开始， 结束定界符所在行只能有合法换行(\r\n)和分号
  - 字符串行为同双引号字符串
- nowdoc
  - `<<<'自定义字符开始'`开始， 结束定界符所在行只能有合法换行(\r\n)和分号
  - 字符串行为同单引号字符串

### 修改string
- `$str[0]` 和 `$str{0}` 访问第一个字符
- PHP 的字符串在内部是字节组成的数组
- 字符串可以用 '.'（点）运算符连接起来

### 转换
-  转为string: (string),strval(),自动转换,settype()
- (boolean)true => "1" , (boolean)false => ""
- (array) $array => "Array"
- PHP4 (object)$obj => "Object"
- 资源 resource => "Resource id #1"
- NULL => ""
- string -> integer 
  如果该字符串没有包含 '.'，'e' 或 'E' 并且其数字值在整型的范围之内（由 PHP_INT_MAX 所定义），
  该字符串将被当成 integer 来取值。其它所有情况下都被作为 float 来取值。
 

## array
- PHP 中的数组实际上是一个有序映射
- key 可以是 integer 或者 string。value 可以是任意类型

key的强制转换
- 包含有合法整型值的字符串会被转换为整型 "8"->8 , "08"->"08"
- 浮点数 => 整型
- 布尔值 => 整型
- null => ""
- 数组和对象不能被用为键名
- 同一个键名，只使用了最后一个，之前的都被覆盖
- 最大整数键名不一定当前就在数组中。它只要在上次数组重新生成索引后曾经存在过就行

### 转换

- integer，float，string，boolean 和 resource 得到一个仅有一个元素的数组，其下标为 0，该元素即为此标量的值
- null -> []
- 一个 object 类型转换为 array，则结果为一个数组，其单元为该对象的属性
  - 整数属性不可访问
  - 私有变量前会加上类名作前缀
  - 保护变量前会加上一个 '*' 做前缀
  - 前缀的前后都各有一个 NULL 字符


## object

##  resource

##  null
-被赋值为 NULL。
-尚未被赋值。
-被 unset()。
```PHP

$a = array();
$a == null  //return true
$a === null // return false

null == 0 // returns true
null === 0 // returns false
```


##  callable

## iterable

## 伪类型
文档里用于指示参数使用
- mixed 
- number 
- callback 
- array|object
- void 
- ...  ( $... 表示等等的意思。当一个函数可以接受任意个参数时使用此变量名 )

