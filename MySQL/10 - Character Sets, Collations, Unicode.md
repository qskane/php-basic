

字符集设置级别：服务器，数据库，表和列

```mysql
SHOW CHARACTER SET LIKE 'latin%';
SHOW COLLATION WHERE Charset = 'latin1';


SET NAMES utf8; SELECT 'abc';
SELECT _utf8'def';
SELECT N'MySQL';

-- 指定使用utf8字符集
SET NAMES 'utf8';

```

排序规则区分大小写的后缀
后缀	含义
_ai	口音不敏感
_as	口音敏感
_ci	不区分大小写
_cs	区分大小写
_bin	二元



## 服务器字符集和排序规则
当前服务器的字符集和排序规则 character_set_server和 collation_server
`select @@character_set_server;`

指定数据库字符集和排序规则
```
CREATE DATABASE db_name
    [[DEFAULT] CHARACTER SET charset_name]
    [[DEFAULT] COLLATE collation_name]

ALTER DATABASE db_name
    [[DEFAULT] CHARACTER SET charset_name]
    [[DEFAULT] COLLATE collation_name]
    
```

查看默认字符集和默认排序规则
```mysql
SELECT DEFAULT_CHARACTER_SET_NAME, DEFAULT_COLLATION_NAME
FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'db_name';
```


## 表的字符集和排序规则

```
CREATE TABLE tbl_name (column_list)
    [[DEFAULT] CHARACTER SET charset_name]
    [COLLATE collation_name]]

ALTER TABLE tbl_name
    [[DEFAULT] CHARACTER SET charset_name]
    [COLLATE collation_name]
```

## 列的字符集和排序规则

```
col_name {CHAR | VARCHAR | TEXT} (col_length)
    [CHARACTER SET charset_name]
    [COLLATE collation_name]
    
col_name {ENUM | SET} (val_list)
	[CHARACTER SET charset_name]
	[COLLATE collation_name]
```

如果ALTER TABLE用于将一列从一个字符集转换为另一个字符集，MySQL会尝试映射数据值，但是如果字符集不兼容，则可能会丢失数据。


未完成 
https://dev.mysql.com/doc/refman/5.6/en/charset-connection.html