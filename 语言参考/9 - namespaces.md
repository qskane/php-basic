# namespaces

名为PHP或php的命名空间，以及以这些名字开头的命名空间（例如PHP\Classes）被保留用作语言内核使用。

- 允许将同一个命名空间的内容分割存放在不同的文件中。
- 允许在同一个文件中定义多个命名空间，不推荐。
- `__NAMESPACE__`
- namespace 关键字可用于访问当前命名空间中的元素，等价于类中的self，例：`namespace\func();`
- `use` 关键字导入命名空间，需在全局或命名空间范围使用，因为在编译阶段执行故不能存在于局部使用。
  use 的三个使用用途
  - to import/alias classes, traits, constants, etc. in namespaces,
  - to insert traits in classes,
  - to inherit variables in closures.
- include 的文件默认使用全局命名空间
- 查找命名空间顺序为 当前命名空间 > 全局命名空间

