Class WP ezClasses Methods Static
=================================

Easy to reach for (static) methods to handle common WordPress and PHP tasks. 



The Methods
===========

- get_stylesheet_directory_uri_ez() - Experimental.

- file_exists_ez() - Experimental.

- starts_with() - String compare: Does the haystack start with the needle.

- ends_with() - String compare: Does the haystack end with the needle.

- implode_obj() - Converts an obj->property to an array and implode()s it. Pass in an object, specify the property and this returns an array of values for that property for the object. 

- array_merge_ez() - Takes an array of arrays, checks them for their array-ness and then does an array_merge. More or less PHP array_merge without pulling an error if you one of the arrays is not an array. 

- switch_to_blog_ez() - Tricked out version of WP multisite's swtich_to_blog().

- restore_current_blog_ez() - Tricked out version of WP multisite's restore_current_blog().

- blog_id_pass() - Tests to see if the blog_id a valid blog_id on the network.

- lremove() - Starting from the left, replaces everything up to and including the needle with ''. aka Left Remove.

- array_keys_for_value_true() - Takes an associative array of value pairs key => bool and returns a normal array of just the keys that have a value of true.

- array_pass() -  Tests to see if an array is isset() && is_array() && !empty(). Returns true, else false.

- array_key_pass() - Tests to see if an array[key] is isset() && is_array() && !empty(). Returns true, else false. That is, is there a non-empty array within an array.

- home_path() - Returns the path to there WP is installed.
