# references

- 引用类似于同一个人拥有了2个名字
- 当 unset 一个引用，只是断开了变量名和变量内容之间的绑定。这并不意味着变量内容被销毁了。
  ```php
	  $a = 1;
	  $b =& $a;
	  unset($a); // $b = 1;
  ```
- 通过在函数定义时方法名前加&，并且在函数时调用时加& 来达到引用函数返回值的目的。
```php

class Obj{
	public function &getValue() {
			return $this->value;
	}
}

$obj = new Obj;
$obj = & $obj->getValue();
```