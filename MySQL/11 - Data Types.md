

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
  
  // FIXME https://dev.mysql.com/doc/refman/5.6/en/timestamp-initialization.html
  

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

可以指定字符串类型列的字符集和排序规则
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


字符类型分类
- [NATIONAL] CHAR[(M)] [CHARACTER SET charset_name] [COLLATE collation_name]
  - 是`CHARACTER`的简写
  - `CHAR BYTE`是`BINARY`的别名
  - 范围M是0到255。如果M省略，则长度是1
  - 除非启用 PAD_CHAR_TO_FULL_LENGTH，否则在检索值时将删除尾部空格。
  - CHAR(0) 可能的值：'' 和 NULL
  -  NATIONAL CHAR 等效的简短格式 NCHAR
- [NATIONAL] VARCHAR(M) [CHARACTER SET charset_name] [COLLATE collation_name]
  - M范围 0到65,535。以字符为单位。
    但是M的值与当前字符集相关：utf8字符每个字符最多需要三个字节，因此VARCHAR使用该utf8字符集的列可以声明为最多21,844个字符。
  - MySQL使用1字节或2字节的前缀保存数据的长度。如果不超过255字节使用1个字节，多于255个字节使用2个字节。(1字节8位，2**8即最大表示数值256)
  - 不会删除末尾空格
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
  
https://dev.mysql.com/doc/refman/5.6/en/numeric-types.html


  
  