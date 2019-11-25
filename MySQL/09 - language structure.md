- 9.1.1 String Literals
	- 彼此相邻的带引号的字符串被串联为单个字符串 `'a string'` = `'a' ' ' 'string'`  
- 9.1.2 Numeric Literals
	- 精确值 DECIMAL NUMERIC， DEC， FIXED
	- 近似值 FLOAT or DOUBLE are DOUBLE PRECISION and REAL.
- 9.1.3 Date and Time Literals
    - 两位数的年转换 
		- 范围内的年份值70-99转换为1970-1999。
    	- 范围内的年份值00-69转换为2000-2069。
	- DATE 
	    - 可识别 'YYYY-MM-DD' 'YY-MM-DD' 格式，可替换 - 为任何分隔符如 '2012/12/31' 
		- 字符串和数字格式 YYYYMMDD 或 YYMMDD 需要该数值为一个合法的日期，20070523'=> '2007-05-23' , '071332'=>'0000-00-00'
		
	- TIMESTAMP
		- 年的范围 1970-2038
		- 可识别 'YYYY-MM-DD hh:mm:ss'或'YY-MM-DD hh:mm:ss' 且可替换 - 或 : 符号
		- 可用 T 间隔日期和时间部分  `'2012-12-31 11:30:45'` = `'2012-12-31T11:30:45'`
		- 字符串或数字格式的 YYYYMMDDhhmmss或 YYMMDDhhmmss 需要该值有意义，否则转换为 `'0000-00-00 00:00:00'`
		- 无需为小于两位数的值补足0 
			- '2015-6-9'与 '2015-06-09' 相同
			- '2015-10-30 1:2:3'相同'2015-10-30 01:02:03'与
			- 6、8、12、14 的数字解析为格式 YYMMDD 、YYYYMMDD 、YYMMDDhhmmss 、YYYYMMDDhhmmss 若不是此类数字位数的数值前面补0至最接近的位数
			- 字符串值解析时将查找尽可能多的字符匹配
	- TIME
		- 'D hh:mm:ss' 'hh:mm:ss'， 'hh:mm'，'D hh:mm'，'D hh'，或 'ss' D取值范围 0-34.
		- 可解析有意义的 'hhmmss'字符串格式时间，否则'00:00:00'
		- 可解析ss， mmss，或 hhmmss数字格式时间
		-  'D hh:mm:ss.fraction'， 'hh:mm:ss.fraction'， 'hhmmss.fraction'，和 hhmmss.fraction 可使用点号作为分秒界定符，支持6位
	
- 9.1.4 Hexadecimal Literals
	- 合法 `x'01af'` `0x01AF`
	  非法`X'0G' (G is not a hexadecimal digit)` 
	       `0X01AF (0X must be written as 0x)`
      
- 9.1.5 Bit-Value Literals
	- 合法 `b'01'` `B'01'` `0b01`
	  非法 `b'2'    (2 is not a binary digit)`
          `0B01    (0B must be written as 0b)`
- 9.1.6 Boolean Literals
- 9.1.7 NULL Values
    - `LOAD DATA` 或 `SELECT ... INTO OUTFILE` 时文本中的 `\N`（区分大小写）作为NULL值
    - 排序时作为最小值
    

## 标识符
- 引号是反引号`\``
- UNIX下区分大小写：数据库，表,触发器
  任何系统都不区分大小写：列，索引，列别名，存储过程，事件
  WINDOWS不区分大小写，但同一条语句中不能同时使用不一致的大小：`SELECT * FROM my_table WHERE MY_TABLE.col=1;`
## 函数
- 支持 内置函数，用户定义函数和存储函数
- 解析器仅在解析预期为表达式的内容时，才应将内置函数的名称识别为指示函数调用，在非表达式上下文中，允许使用函数名作为标识符。
- 解析规则
	- 要将名称用作表达式中的函数调用，名称和后面的(括号字符之间必须没有空格
	- 要将函数名称用作标识符，切勿在其后立即加上括号
	- 函数名称后不能有空格的内置函数列表：(https://dev.mysql.com/doc/refman/5.6/en/function-resolution.html)[https://dev.mysql.com/doc/refman/5.6/en/function-resolution.html]
- 解析注意：
	- `SET sql_mode = 'IGNORE_SPACE';`
	- `IGNORE_SPACE` disabled状态时
	  ```mysql
	      -- You have an error in your SQL syntax
	      CREATE TABLE count(i INT);

		 -- 正确用法
		  CREATE TABLE count (i INT);  -- 添加空格
		  CREATE TABLE `count`(i INT);  -- ``包裹
		  CREATE TABLE `count` (i INT); -- ``包裹+空格
	  ```
	- `IGNORE_SPACE` enable状态时
	  ```mysql
		  SELECT COUNT(*) FROM mytable;
		  SELECT COUNT (*) FROM mytable;
	  ```
- Function Name Resolution
	- 不能与内置函数同名
	- 存储函数可以与内置函数同名，但是需要加限定符如：`test.PI()`
	- 用户定义的函数和存储的函数共享相同的名称空间,不能重名
	
## 关键字和保留字

保留关键词列表 [https://dev.mysql.com/doc/refman/5.6/en/keywords.html](https://dev.mysql.com/doc/refman/5.6/en/keywords.html)
```mysql
 -- interval为保留关键字需要引用，begin是关键字但不是保留关键字则无限制
 CREATE TABLE `interval` (begin INT, end INT);
 -- 限定名称中句点后的单词必须是标识符，故无需加引号
 CREATE TABLE mydb.interval (begin INT, end INT);
``` 

## 自定义变量

```
SET @var_name = expr [, @var_name = expr] ...
```

- 合法的组成部分：`A-Z` `.` `_` `$`, 若包含于字符串或标识符中时，则可以使用其他字符： @'my-var', @"my-var", @`my-var`。
- 使用 `=` 或 `:=` 符号赋值。
- 赋值变量特定于SESSION有效。
- 合法的赋值类型：整数，十进制，浮点数，二进制或非二进制字符串，NULL；其他类型被转换为此类型中的一种
- 不要再同一条语句中同时设置和读取值。
- 每个SELECT表达式仅在发送到客户端时才进行求值，在HAVING，GROUP BY或ORDER BY中取值不会按预期工作。
- 不能直接在SQL语句中直接用作标识符或标识符的一部分，但可以组装为字符串后执行SQL。
  ```mysql
    SET @col = "c1";
    SELECT `@col` FROM t; -- c1 列存在于表t中
    -- ERROR 1054 (42S22): Unknown column '@col' in 'field list'
    
    -- 但是可以用作构造字符串用于执行SQL
    SET @s = CONCAT("SELECT ", @c, " FROM t");
    PREPARE stmt FROM @s;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
  ```

## 表达式

- 列表 https://dev.mysql.com/doc/refman/5.6/en/expressions.html
- 时间间隔 INTERVAL expr unit，unix始终为单数格式：2 day，3 year...
  date + INTERVAL expr unit
  date - INTERVAL expr unit
- `EXTRACT(YEAR FROM '2019-07-01')`
- ```mysql
	 CREATE EVENT myevent
	  ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 1 HOUR
	  DO
		UPDATE myschema.mytable SET mycol = mycol + 1;
  ```
- 超出月份最大日期转换为最大值：`SELECT DATE_ADD('2019-01-30', INTERVAL 1 MONTH); -- '2019-02-28'`
- 错误日期运算后为NULL， `SELECT '2005-03-32' + INTERVAL 1 MONTH; -- NULL` 
  
## 注释

- `#`
- `-- ` 其后必须包含一个空格或制表符或换行符等
- `/* */`
- `CREATE TABLE t1(a INT, KEY (a)) /*!50110 KEY_BLOCK_SIZE=1024 */;` 指定MYSQL版本高于5.1.10时才执行注释中的代码。

  



