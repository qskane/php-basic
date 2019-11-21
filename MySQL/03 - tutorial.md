
## 连接及反馈

开启连接
```bash
mysql -h host -u user -p  YOUR_DATABASE_NAME 
# 连接时指定database
# 若需要直接指定密码 使用无间隔的 -p+密码 参数 -pPASSWORD

```

`QUIT` 或 Unix上 Control + D 断开连接

`\g` 或 `;` 命令结束

`\c` 取消正在输入的查询

```
mysql>	准备进行新查询
->	等待多行查询的下一行
'>	等待下一行，等待以单引号（'）开头的字符串的完成
">	等待下一行，等待以双引号（"）开头的字符串的完成
`>	等待下一行，等待以反引号（`）开头的标识符的完成
/*>	等待下一行，等待以开头的注释的完成 /*
```

```mysql
SELECT VERSION(),    # 5.6.1-m4-log 
       CURRENT_DATE, # 2010-08-06 
       NOW(),        # 2010-08-06 12:17:13 
       DATABASE(),   # test
       USER(),       # jon@localhost
       SIN(PI()/4),  # 0.70710678118655
       (4+1)*5;      # 25
```

## 3.3创建和使用数据库
```mysql
SHOW DATABASES;
SHOW TABLES;
SHOW CREATE TABLE pet;
SHOW WARNINGS;
USE test;
GRANT ALL ON your_database_name.* TO 'your_mysql_user'@'your_client_host';
DESCRIBE pet; # DESC pet

 # 在Unix下，数据库名称是区分大小写的（不像SQL关键字）
CREATE DATABASE menagerie;

# LINES TERMINATED BY '\r\n';  # windows 下使用\r\n换行
# LINES TERMINATED BY '\r'。   # macOS 下使用\r换行
# null应该使用 \N 表示
# 每列数据用制表符间隔(TAB)
# 空行TAB会插入一条值全为默认值或NULL 的记录
LOAD DATA LOCAL INFILE '/path/pet.txt' INTO TABLE pet; 

```

### 3.3.4 检索信息 

#### ORDER BY
```mysql
ORDER BY BINARY col_name # 区分大小写排序
ORDER BY col1 ASC, col2 DESC
```

#### 日期计算
YEAR(), MONTH(), DAYOFMONTH()
```mysql
SELECT TIMESTAMPDIFF(YEAR,birth,CURDATE()) AS age FROM pet WHERE death IS NOT NULL;


# 获取下月生日的行
SELECT name, birth FROM pet
       WHERE MONTH(birth) = MONTH(DATE_ADD(CURDATE(),INTERVAL 1 MONTH));

SELECT name, birth FROM pet
	   WHERE MONTH(birth) = MOD(MONTH(CURDATE()), 12) + 1;
# MOD(n,m)  n%m  
# DATE_ADD() 错误的日期将返回NULL，并产生Warning 
```

####  NULL 
在排序中处于最小的位置
`IS NULL`, `IS NOT NULL` 比较值

#### 模式匹配
LIKE 和 NOT LIKE
`_` 单个字符
`%` 任意字符

REGEXP 和 NOT REGEXP (RLIKE 和 NOT RLIKE)
```mysql
WHERE name REGEXP '^.{5}[a-z]{2}$';
```

#### 计数行
- count(*) 相比 count(col) 更佳
- count(col) 时会忽略col中为null的行
- ONLY_FULL_GROUP_BY 若指定count列则必须在group by中聚合
```mysql
SET sql_mode = 'ONLY_FULL_GROUP_BY';
```

#### 批处理模式

```
shell> mysql < batch-file > mysql.out
C:\> mysql -e "source batch-file"
mysql> source filename;
mysql> \. filename
```

#### 常见查询示

- MAX(article)

- 获取价格最高的一条记录
```mysql
# 选项一
SELECT article, dealer, price
FROM   shop
WHERE  price=(SELECT MAX(price) FROM shop);

# 选项二
SELECT s1.article, s1.dealer, s1.price
FROM shop s1
LEFT JOIN shop s2 ON s1.price < s2.price
WHERE s2.article IS NULL;

# 选项三 
SELECT article, dealer, price
FROM shop
ORDER BY price DESC
LIMIT 1;
```

- 每件商品最高价格
- 每件商品最高价格购买记录

```mysql
SELECT article, dealer, price FROM shop WHERE price IN (
select MAX(price) FROM shop  GROUP BY dealer
) ORDER BY article;

SELECT article, dealer, price
FROM   shop s1
WHERE  price=(SELECT MAX(s2.price)
              FROM shop s2
              WHERE s1.article = s2.article)
ORDER BY article;

SELECT s1.article, s1.dealer, s1.price
FROM shop s1
LEFT JOIN shop s2 ON s1.article = s2.article AND s1.price < s2.price
WHERE s2.article IS NULL
ORDER BY s1.article;


SELECT s1.article, dealer, s1.price
FROM shop s1
JOIN (
  SELECT article, MAX(price) AS price
  FROM shop
  GROUP BY article) AS s2
  ON s1.article = s2.article AND s1.price = s2.price
ORDER BY article;
```

- 使用变量
```mysql
SELECT @min_price:=MIN(price),@max_price:=MAX(price) FROM shop;
SELECT * FROM shop WHERE price=@min_price OR price=@max_price;
```

#### 外键

`REFERENCES other_table_name(col)`

[constraint '外键名'] foreign key(外键字段) references 主表(主键);
after table 从表 add [constraint '外键名'] foreign key(外键字段) references 主表(主键);
alter table 从表 drop foreign key 外键名字;
alter table 表名 drop index 索引名;

#### 计算每天访问量

```mysql
CREATE TABLE t1 (year YEAR, month INT UNSIGNED,
             day INT UNSIGNED);
INSERT INTO t1 VALUES(2000,1,1),(2000,1,20),(2000,1,30),(2000,2,2),
            (2000,2,23),(2000,2,23);

# 该查询将计算表中每个年/月组合出现多少次，并自动删除重复条目。
SELECT year,month,BIT_COUNT(BIT_OR(1<<day)) AS days FROM t1
       GROUP BY year,month;
```

#### AUTO_INCREMENT

- `NO_AUTO_VALUE_ON_ZERO` 该模式下不自增value为0的值
- `LAST_INSERT_ID()` 特定于当前连接的最新插入的值,批量插入多行时返回第一条记录id
- `ALTER TABLE tbl AUTO_INCREMENT = 100;` 以指定值开始自增
**MyISAM**
若自增列位于第二列，则只在第一列与已出现的值不同时才+1。
[https://dev.mysql.com/doc/refman/5.6/en/example-auto-increment.html](https://dev.mysql.com/doc/refman/5.6/en/example-auto-increment.html)