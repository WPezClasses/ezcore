Class WP ezClasses DevTools Log
===============================

Write to the WordPress debug.log or the file of your choice.

No need to worry about what type it is (e.g., string, bool, array or object), the class will sort that out for you and write it to file. 



How To #1 (Recommended)
=======================

Use the WP ezPlugins plugin: WP ezClasses Autoload (https://github.com/WPezPlugins/wp-ezclasses-autoload). Once you get this in place then growing and using WP ezClasses becomes nearly painless. 



Dependencies
============

- WP ezClasses Master Singleton: https://github.com/WPezClasses/wp-ezclasses-master-singleton



Using Class WP ezClasses DevTools Log
=====================================

- e.g. #1 - Basic

```
$obj_log = Class_WP_ezClasses_DevTools_Log::ez_new();
$x = array('A', 'B','C');
$obj_log->log($x);
```


- e.g. #2 - Overiding the values specified in the method log_defaults(). Anything that's in the defaults you can pass in when you do the ->log().

```
$arr_local_log_args = array(
						'log_file' 				=> 'debug2.log',
					);

$obj_log = Class_WP_ezClasses_DevTools_Log::ez_new();
$x = array('A', 'B','C');
$obj_log->log($x, $arr_local_log_args);
```


Yup. That's about it. No more echo'ing to screen. Or perhaps you're just wanting to log some event to file. Now it's uber ez. 