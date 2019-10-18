# Errors

在php7中，大多数错误被作为 Error 异常抛出，如果没有匹配的 catch 块，则调用异常处理函数（事先通过 set_exception_handler() 注册）进行处理。
如果尚未注册异常处理函数，则按照传统方式处理：被报告为一个致命错误（Fatal Error）。
不能使用catch(Exception $e) 来捕获Error，可以用 catch (Error $e) { ... }捕获。

可以使用catch（Throwable $t） 兼容捕获Error 或 Exception。


Throwable
	Error
	  ArithmeticError
		DivisionByZeroError
	  AssertionError
	  ParseError
	  TypeError
		ArgumentCountError
	Exception
	  ClosedGeneratorException
	  DOMException
	  ErrorException
	  IntlException
	  LogicException
		BadFunctionCallException
		  BadMethodCallException
		DomainException
		InvalidArgumentException
		LengthException
		OutOfRangeException
	  PharException
	  ReflectionException
	  RuntimeException
		OutOfBoundsException
		OverflowException
		PDOException
		RangeException
		UnderflowException
		UnexpectedValueException
	  SodiumException