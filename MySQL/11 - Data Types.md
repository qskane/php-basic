

MySQL支持的数据类型
- 数字类型
- 日期和时间类型
- 字符串（字符和字节）类型
- 空间类型

## 数值类型
ZEROFILL 会自动添加UNSIGNED，自动在数值前面补0至目标长度。
确切的数值数据类型  INT(INTEGER)， SMALLINT， DECIMAL(DEC、FIXED)和 NUMERIC
近似数值数据类类型 FLOAT， REAL，和 DOUBLE(DOUBLE PRECISION)
默认情况下整数值之间的减法（其中一个类型为UNSIGNED ）会产生无符号结果。如果结果为负则将导致错误。`SELECT CAST(0 AS UNSIGNED) - 1;`
### DECIMAL，NUMERIC
存储精确的数值数据，可以用于存储货币
DECIMAL以二进制格式存储值
NUMERIC是DECIMAL的相同实现







- BIT[(M)] 位值类型  
  M表示位数，从1到64。如果M省略，默认值为1。
- TINYINT[(M)] [UNSIGNED] [ZEROFILL] 一个非常小的整数
  SINGED范围是 -128到127。UNSIGNED的范围是0到 255。  2**8 -1
- BOOL， BOOLEAN 同义词 TINYINT(1)
  0为FALSE , 非零值为TRUE
  `0 = FALSE => TRUE`  
  `1 = TRUE => TRUE`
  `2 = TRUE => FALSE`
- SMALLINT[(M)] [UNSIGNED] [ZEROFILL]
  SIGNED -32768到32767
  UNSIGNED 0到 65535   2**16 -1
- MEDIUMINT[(M)] [UNSIGNED] [ZEROFILL]
  SIGNED -8388608到8388607
  UNSIGNED 0到 16777215   2**24 -1
- INT[(M)] [UNSIGNED] [ZEROFILL]
  同义词 INTEGER[(M)] [UNSIGNED] [ZEROFILL]
  SIGNED -2147483648到 2147483647。
  UNSIGNED 0到4294967295   2**32 -1
  
- BIGINT[(M)] [UNSIGNED] [ZEROFILL]
  SIGNED -9223372036854775808到 9223372036854775807
  UNSIGNED  0到 18446744073709551615   2**64 -1
  `SERIAL` 是别名 `BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE`
- DECIMAL[(M[,D])] [UNSIGNED] [ZEROFILL]
  M 最大65，D最大30，M默认10，D默认0。符号不计入M计数。
  以下为同义词，FIXED可与其他数据库兼容
  - DEC[(M[,D])] [UNSIGNED] [ZEROFILL]
  - NUMERIC[(M[,D])] [UNSIGNED] [ZEROFILL]
  - FIXED[(M[,D])] [UNSIGNED] [ZEROFILL]
- FLOAT[(M,D)] [UNSIGNED] [ZEROFILL]
  一个小的（单精度）浮点数
- FLOAT(p) [UNSIGNED] [ZEROFILL]
  p值0到24转为FLOAT
  p值25到53转为DOUBLE
- DOUBLE[(M,D)] [UNSIGNED] [ZEROFILL] 
  普通大小（双精度）浮点数
  同义词 DOUBLE  
	- DOUBLE PRECISION[(M,D)] [UNSIGNED] [ZEROFILL]
	- REAL[(M,D)] [UNSIGNED] [ZEROFILL]

## 日期和时间

时间自动更新：
- 5.6 以上TIMESTAMP/DATETIME 支持时间自动更新
- 当使用`DEFAULT CURRENT_TIMESTAMP` 或 `ON UPDATE CURRENT_TIMESTAMP` 时表中只能有一个 `timestamp` 类型的列，
  即使其他列不需要自动更新。
- `my_col TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP`。
- INSERT NULL 到 timestamp 类型的列时,除非指定可为NULL，否则会自动更新为 `CURRENT_TIMESTAMP`。
  所以即使定义时指定了NOT NULL仍然可以插入NULL值，被自动转换。
  DATETIME列不会主动转换NULL为CURRENT_TIMESTAMP，仅当不忽略该列时DEFAULT CURRENT_TIMESTAMP才生效。 
- 超出微秒精度的值将被四舍五入

时间转换
- 如果超出有效范围，将被赋予0值：DATETIME '1968-01-01 00:00:00' => TIMESTAMP '00-00-00 00:00:00'
- 未包含的缺失时间部分将被赋予0值： DATE '2019-01-01' => DATETIME '2019-01-01 00:00:00'
- 通过将时间值加零来获得一个数值：`SELECT CURTIME(), CURTIME()+0, CURTIME(3)+0;`

时间类型
- DATE
  '1000-01-01'到 '9999-12-31'
- DATETIME[(fsp)]
  '1000-01-01 00:00:00.000000'到 '9999-12-31 23:59:59.999999'
- TIMESTAMP[(fsp)]
  '1970-01-01 00:00:01.000000'UTC到'2038-01-19 03:14:07.999999'UTC
- TIME[(fsp)]
  '-838:59:59.000000' 至'838:59:59.000000'
- YEAR[(2|4)]
  YEAR(4) 1901到 2155或0000
  YEAR(2) 00 到 99，0-69 转换为20xx，70-99转换为19xx
  
SUM()和 AVG()聚合函数不能在时间值上生效，但可以通过转换后使用：
```mysql
SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(time_col))) FROM tbl_name;
SELECT FROM_DAYS(SUM(TO_DAYS(date_col))) FROM tbl_name;
```

## 字符串

### CHAR 与 VARCHAR
字符串比较相等性时忽略比较双方的末尾空格，LIKE查询仅忽略存储值后的空格。
```
'Jones' = CHAR(10) 'Jones'  # TRUE
'Jones     ' = CHAR(10) 'Jones'  # TRUE

'Jones' LIKE CHAR(10) 'Jones'  # TRUE
'Jones     ' LIKE CHAR(10) 'Jones'  # FALSE
```

具有唯一键的列不能同时插入仅末尾空格不同的值： 'a' 'a ' 将导致唯一键冲突

### BINARY 与 VARBINARY
`BINARY`、`VARBINARY` 和 `CHAR BINARY` 、`VARCHAR BINARY`类型是不相同的：
- BINARY(5) 存储5字节（而不是5字符）的字节字符串
- CHAR(5) BINARY 与 CHAR(5)的区别仅仅是`CHAR(5) BINARY`使用该字符集带 _bin 后缀的排序规则,行为同：
  `CHAR(5) CHARACTER SET latin1 COLLATE latin1_bin`
- BINARY(3)将会填充末尾空格，'a '=>'a \0' ,  'a\0'=>'a\0\0'
- 比较时（包括ORDER BY和 DISTINCT），填充的字符也将一起用作比较，0x00（自动填充的零字节） < 空格。
- VARBINARY 原样保留插入的值，不删除不填充
- BINARY(3)列比较实例：
```mysql
 CREATE TABLE t (c BINARY(3));
 INSERT INTO t SET c = 'a';
 SELECT HEX(c), c = 'a', c = 'a\0\0' from t;
 # HEX(c)       610000 
 # c = 'a'      0
 # c = 'a\0\0'  1
```

### BLOB 与 TEXT

TINYBLOB，BLOB， MEDIUMBLOB，和LONGBLOB
TINYTEXT，TEXT， MEDIUMTEXT，和LONGTEXT

- TEXT和BLOB列都将原样保留字符，不删除填充空格。
- TEXT无论SQL模式如何， 从要插入列的值截断多余的尾随空格总是会产生警告。
- BLOB、TEXT 上的索引必须指定前缀长度，CHAR和 VARCHAR前缀长度可选。
- BLOB、TEXT列不能有DEFAULT值。
- LONG 和 LONG VARCHAR类型将映射到 MEDIUMTEXT类型
- 仅适用max_sort_length第一个字节排序
- 适用临时表处理查询结果，且包含BLOB或TEXT列的会导致服务器在磁盘而不是内存中使用表。导致性能下降严重也导致memory引擎不支持该类型。
- BLOB、TEXT对象的最大大小由其类型确定，但实际由客户端和服务器之间传输的最大值、可用内存、通信缓冲区的大小确定。

### ENUM
是一个字符串对象，值从定义时明确枚举出来，具有以下优点：
- 在列可能值有限个数的情况下，压缩数据存储（会比使用varchar更节省存储空间）。会自动编码字符串为数字存储。
- 读取时将自动解码数字为字符串。
- 枚举值最多包含65535（实际限制为3000）个唯一的值，一个表所有集合元素不相同的SET，ENUM最多包含255列
  存储内部会分配一个数字索引一一对应给出的可能值
  ```
  VALUE      INDEX
  NULL	     NULL
  ''	     0
  'Mercury'	 1
  'Venus'	 2
  'Earth'	 3
  ```
  当枚举值为类数字型的值时会对待该值作为一个索引
  ```mysql
  create table t (
  	numbers ENUM('0','1','2')
  );
  INSERT INTO t (numbers) VALUES(2),('2'),('3');
  SELECT * FROM t;
  # 输出numbers
  # 1   -- int 2 被认为是索引值，取得枚举index为2的值即 '1'
  # 2   -- varchar '2' 存在于枚举中，取得 '2'
  # 2   -- varchar '3' 不存在与枚举中，被解析为索引值3，取得值 '2' 
  ```
- 查看可能的值： `SHOW COLUMNS FROM tbl_name LIKE 'enum_col'`
- 空字符串作为插入列表未枚举的值，索引为0。该列允许NULL时，NULL为默认值，否则为枚举的第一个元素。
- 排序时按照枚举索引排序，NULl < '' < 元素，为了正常排序，可用以下选项之一：
  - 按照正确排序规则安放枚举值 ENUM('a','b'...)
  - 转换为编码或词法排序`ORDER BY CAST(col AS CHAR)`,  `ORDER BY CONCAT(col)`
- 会删除末尾空格
- 枚举值不能是表达式或变量。
	```mysql
	# 语法错误
	CREATE TABLE sizes (
		size ENUM('small', CONCAT('med','ium'), 'large')
	);
	
	# 语法错误
	SET @mysize = 'medium';
	CREATE TABLE sizes (
		size ENUM('small', @mysize, 'large')
	);
	```
### SET
可以具有零个或多个值的字符串对象。元素间由逗号分隔，所以元素中不能包含逗号。

- `SET('one', 'two') NOT NULL`可以具有以下可能的值：
	```
	''
	'one'
	'two'
	'one,two'
	```
- SET最多可包含64个不同的成员。一个表所有集合元素不相同的SET，ENUM最多包含255列。
- 会删除末尾空格
- SET以数字方式存储，在数字上下文中将被转换为内部数字的值：
	```
	SELECT set_col+0 FROM tbl_name;
	
	SET member	十进制值	二进制值
	'a'			1		0001
	'b'			2		0010
	'c'			4		0100
	'd'			8		1000
	```
	如果存入int 9 到该列，9的二进制1001导致该列的存储值为'a,d'。
- 插入时的SET元素内容顺序不重要，且会删除相同元素，查询时会按照定义表时的顺序列出：
	```
	CREATE TABLE myset (col SET('a', 'b', 'c', 'd'));
	INSERT INTO myset (col) VALUES ('a,d'), ('d,a'), ('a,d,a'), ('a,d,d'), ('d,a,d');
	SELECT col FROM myset;
	-- 删除相同元素,重新排序
	+------+
	| col  |
	+------+
	| a,d  |
	| a,d  |
	| a,d  |
	| a,d  |
	| a,d  |
	+------+
	```
-  查找
	```mysql
	FIND_IN_SET('value',set_col)>0  -- 查找包含value元素的行，需完整匹配该元素
	set_col LIKE '%value%'  -- 查找包含 value 字符串的行，可能是元素的子字符串未完整匹配
	set_col & 1             -- 查找第一个set成员的值
	set_col = 'val1,val2'   -- 顺序需要与存储时一致，不会被重新排序
	SHOW COLUMNS FROM tbl_name LIKE set_colSETType
	```
- 排序时 NULL < '' < SET元素 


### 指定字符串类型列的字符集和排序规则
- CHARACTER SET
- CHARACTER SET binary
  `c1 VARCHAR(10) CHARACTER SET binary` 将导致该字符类型变为响应的二进制字符类型： 
  - CHAR变为 BINARY
  - VARCHAR变为 VARBINARY
  - TEXT变为 BLOB
  - ENUM和 SET 不变
- BINARY
  用于指定_bin列字符集（如果未指定列字符集，则为表默认字符集）的二进制排序的简写形式
	```mysql
	CREATE TABLE t
	(
	  c1 VARCHAR(10) CHARACTER SET latin1 BINARY,
	  c2 TEXT BINARY
	) CHARACTER SET utf8mb4;
	-- 结果为
	CREATE TABLE t (
	  c1 VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_bin,
	  c2 TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin
	) CHARACTER SET utf8mb4;
	```
- ASCII
  是`CHARACTER SET latin1`的简写
- UNICODE
  是`CHARACTER SET ucs2`的简写


### 字符类型分类
- [NATIONAL] CHAR[(M)] [CHARACTER SET charset_name] [COLLATE collation_name]
  - 是`CHARACTER`的简写
  - `CHAR BYTE`是`BINARY`的别名
  - 范围M是0到255。如果M省略，则长度是1
  - 插入值时填充空格到末尾，检索时除非启用 PAD_CHAR_TO_FULL_LENGTH，否则将删除尾部空格后取出。
  - CHAR(0) 可能的值：'' 和 NULL
  - ATIONAL CHAR 等效的简短格式 NCHAR
  - innoDB 将超出768字节的CHAR类型转换为对应的VARCHAR类型， 
    CHAR(255) = 255字符 * 3字节/字符 = 765字节，需要每个字符最大字节大于3才满足如 utf8mb4 最大字节为4(most bytes 4)
- [NATIONAL] VARCHAR(M) [CHARACTER SET charset_name] [COLLATE collation_name]
  - M范围 0到65,535。以字符为单位。
    但是M的值与当前字符集相关：utf8字符每个字符最多需要三个字节，因此VARCHAR使用该utf8字符集的列可以声明为最多21,844个字符。
  - MySQL使用1字节或2字节的前缀保存数据的长度。如果不超过255字节使用1个字节，多于255个字节使用2个字节。(1字节8位，2**8即最大表示数值256)
  - 原样保留末尾空格
  - 是CHARACTER VARYING的简写
  - NVARCHAR 是 NATIONAL VARCHAR 的简写
  
- TINYTEXT [CHARACTER SET charset_name] [COLLATE collation_name]
  - 最大长度为255（2**8 − 1）个字符，如果该值包含多字节字符，则有效最大长度会更少
  - 使用1字节的前缀存储该值中的字节数
- TEXT[(M)] [CHARACTER SET charset_name] [COLLATE collation_name]
  - 最大长度 65,535（2**16 − 1）个字符，如果该值包含多字节字符，则有效最大长度会更少
  - 使用2字节的前缀存储该值中的字节数
  - 若M被指定，则MySQL会根据该值创建足够容纳M个字节的最小的TEXT类型。
- MEDIUMTEXT [CHARACTER SET charset_name] [COLLATE collation_name]
  - 最大长度为16,777,215（2 24 − 1）个字符
  - 使用3字节的前缀存储该值中的字节数
- LONGTEXT [CHARACTER SET charset_name] [COLLATE collation_name]
  - 最大长度为4,294,967,295或4GB（2 32 − 1）字符
  - 使用4字节的前缀存储该值中的字节数
  - 有效最大长度 取决于客户端/服务器协议中配置的最大数据包大小和可用内存
  
- BINARY[(M)]
  - 类似CHAR，存储二进制字节字符串，M表示以字节为单位的列长度，默认1
- VARBINARY(M)
  - 类似VARCHAR，存储二进制字节字符串
- TINYBLOB
  - 最大长度为255（2**8 − 1）个字节
  - 使用1字节的前缀存储该值中的字节数
- BLOB[(M)]
  - 最大长度为65,535（2**16 − 1）个字节
  - 使用2字节的前缀存储该值中的字节数
  - 若M被指定，则MySQL会根据该值创建足够容纳M个字节的最小的BLOB类型。
- MEDIUMBLOB
  - 最大长度16,777,215（2**24 - 1）个字节
  - 使用3字节的前缀存储该值中的字节数
- LONGBLOB
  - 最大长度4,294,967,295（2**32 - 1）个字节
  - 使用4字节的前缀存储该值中的字节数
  - 有效最大长度 取决于客户端/服务器协议中配置的最大数据包大小和可用内存
  
- ENUM('value1','value2',...) [CHARACTER SET charset_name] [COLLATE collation_name]
  - ENUM列最多可包含65,535个不同的元素。（实际限制是小于3000)
  - 表的唯一元素列表定义不能超过255个
- SET('value1','value2',...) [CHARACTER SET charset_name] [COLLATE collation_name]
  - SET列最多可包含64个不同的元素。
  - 表的唯一元素列表定义不能超过255个



## 空间数据类型

https://dev.mysql.com/doc/refman/5.6/en/spatial-types.html


## 默认值

显示默认值： `DEFAULT value`
- DEFAULT子句中指定的默认值 必须为文字常量；它不能是函数或表达式
- 日期不能指定NOW()或CURRENT_DATE，唯有 TIMESTAMP和DATETIME 可默认为 CURRENT_TIMESTAMP
- BLOB和 TEXT数据类型不能指定一个默认值

隐式默认值
- 未指定 `NOT NULL`，则隐式包含 `DEFAULT NULL`
- 若指定 `NOT NULL` 且未定义 `DEFAULT` 子句，则MySQL会根据类型添加默认值：
  - 数值类型 默认值0
  - 日期和时间 默认零值 
    启用 explicit_defaults_for_timestamp 后，TIMESTAMP默认为当前时间，MySQL默认未启用该项。
  - 除ENUM外的字符串类型默认为空字符串 '', ENUM 默认为首个元素。
  - `PRIMARY KEY` 列必须包含 `NOT NULL`子句 ,MySQL会自动添加 `NOT NULL DEFAULT 类型默认值`

## 数据类型存储要求

- YEAR 只需1个字节保存末尾2位数然后使用 1900 + year ，所以最大值为 2155
- VARCHAR 使用1或2个字节存储长度+数据，VARCHAR(255)存储情况：
  - latin1字符集（1字节1字符）， 'abcd' = 5个字节 = 1字节存长度+4个字符所需的字节
  - ucs2字符集(2个字节1个字符)， 'abcd' = 10个字节 = 2字节存长度+8个字符所需的字节，
    因为最大可能长度255个字符 * 2字节每字符 = 510字节，超出1个字节存长度时的最大值255字节，所以需要2个字节保存长度。
- VARCHAR列最大单字节字符集字符数 2**32-1，且在同表各列间共享长度。若使用3字节字符集则最多 (2**32-1)/3个字符。
- innoDB中，当CHAR(255)使用超过3字节的字符集时，会改变为VARCHAR类型存储。
