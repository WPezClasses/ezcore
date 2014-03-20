<?php
/** 
 * Use the log method to write strings, bool, arrays and objects to the file you specify
 *
 * Note: The default (write-to) file is the WP debug.log. There's also a global property (bool) in the wpezClassesMasterParent class for turning off the logging en mass. 
 *
 * PHP version 5.3
 *
 * LICENSE: MIT
 *
 * @package WPezClasses
 * @author Mark Simchock <mark.simchock@alchemyunited.com>
 * @since 0.5.0
 * @license MIT
 */
 
/*
 * == Change Log == 
 *
 */
 
/* TODO => Remove this section?
 *
 * "global" properties inherited from wpezClassesMasterParent
 *
 * protected $_bool_ezc_log 			=> default: true 	- turns the log (wpezToolsClassesLog() on and off 
 * protected $_bool_ezc_validate		=> default: true; 	- turns off _validation methods
 * protected $_bool_ezc_apply_filters	=> default: false;	- if you want to use the filters then set this to true
 */

 
// No WP? Die! Now!!
if (!defined('ABSPATH')) {
	header( 'HTTP/1.0 403 Forbidden' );
    die();
}

if (!class_exists('Class_WP_ezClasses_Core_DevTools_Log')) {
	class Class_WP_ezClasses_Core_DevTools_Log extends Class_WP_ezClasses_Master_Singleton {
	
		protected $_arr_args;
		
		public function __construct(){
			parent::__construct();
		}
		
		/**
		 * Kinda like the __construct(), but different. the get_instance() in the master parent calls the ez_init()
		 */
		public function ezc_init($arr_args = NULL){		
			$this->dev_log_init($arr_args);
			
		} 
		
		
		/*
		 * args can be passed when the method is init'ed
		 */
		protected function dev_log_init($arr_args = NULL) {

			/*
			 * ezc "global" property ezCONFIGS('log') *must be* true to log (anything else is "false")
			 */ 
			if ( $this->ezCONFIGS('log') !== true ){
				return array('status' => false, 'msg' => "Log is off (ezCONFIGS('log') !== true). Check setting in Class_WP_ezClasses_Master_Singleton", 'arr_args' => 'log off');			
			} else {
				$bool_validate = $this->ezCONFIGS('validate');
				if ( isset($arr_args['validate']) && is_bool($arr_args['validate']) ) {
					$bool_validate = $arr_args['validate'];
				} 
							
				if ( $bool_validate !== false ){
					$arr_validate_return = $this->log_validate($arr_args);
					if ($arr_validate_return['status'] === true ) {	
						$arr_args = $arr_validate_return['arr_args'];	
					} else {
						return $arr_validate_return;		
					}
				}		
				$this->_arr_args = array_merge($this->log_defaults(), $arr_args);	
			}
		}
		
		/*
		 * args can be passed in on a ->log() by ->log() basis (but they are not required).
		 */
		public function log($mix_to_log = NULL, $arr_args = NULL){
			/*
			 * ezc global property _bool_ezc_log *must be* true to log (anything else is "false")
			 */ 
			 
			 // TODO isset()
			if ( $this->ezCONFIGS('log') !== true ){
				return array('status' => false, 'msg' => "Log is off (ezCONFIGS('log') !== true). Check setting in Class_WP_ezClasses_Master_Singleton", 'arr_args' => 'log off');			
			} else {
		
				/*
				 * Validate? 
				 */
				 
				// TODO isset()
				$bool_validate = $this->ezCONFIGS('validate');
				if ( isset($arr_args['validate']) && is_bool($arr_args['validate']) ) {
					$bool_validate = $arr_args['validate'];
				}  
				
				// *must be* false to not validate (anything else is "true")
				if ( $bool_validate !== false ){
				
					$arr_validate_return = $this->log_validate($arr_args);
					if ($arr_validate_return['status'] === true ) {	
						$arr_args = $arr_validate_return['arr_args'];	
					} else {
						return $arr_validate_return;		
					}
				}	

				/*
				 * merge the "local" args over top of the init's args (which override the defaults)
				 */
				$arr_args = array_merge($this->_arr_args, $arr_args);
				
				/*
				 * Is the log on (true)?
				 */
				if ( $arr_args['active'] !== true ) {
					return array('status' => false, 'msg' => 'Log is off ( arr_args[status] !== true ). ', 'arr_args' => 'log off');
				}
				
				if ( is_null($mix_to_log) ){
					$mix_to_log = $arr_args['default_string'];
				}
				
				$str_error = '';
				
				/*
				 * lets play nice and turn bools to a string. else you can't actually see false and 1 (for true) might be hard to spot in a cluttered log
				 */
				if ( is_bool($mix_to_log) ){
					$mix_to_log = 'true (is_bool)';
					if ( $mix_to_log == false )  {
						$mix_to_log = 'false (is_bool)';					
					}
				} elseif  ( is_array($mix_to_log) ) { // and if it's an array or object make it a string, please
					$mix_to_log = 'is_array() ' . print_r($mix_to_log, true);
				} elseif ( is_object($mix_to_log) ) {
					$mix_to_log = 'is_object() ' . print_r($mix_to_log, true);
				}
					
				$str_stamp = '';
				if ( $arr_args['date_time_stamp'] ) {
					$str_stamp = '[' . date( $arr_args['date_time_stamp_format'] ) . '] '; // Format: [28-Feb-2013 04:34:28] with an AM or PM too
				}
								
				/**
				 * Make the magic happen! file_put_contents()
				 * http://php.net/manual/en/function.file-put-contents.php
				 */
				
				// TODO - write to top of file instead of append to bottom. Note: This is NOT what WP does and could make debugging difficult. 
				
				$bool_file_put_contents_return = file_put_contents( $arr_args['path'] . $arr_args['log_file'], $str_stamp . $arr_args['prefix'] . $mix_to_log . $str_error . "\n\n", FILE_USE_INCLUDE_PATH | FILE_APPEND );
				
				if ( $bool_file_put_contents_return == true ) {
				
					return array('status' => true, 'msg' => 'success', 'arr_args' => $arr_args, 'mix_to_log' => $mix_to_log);
				
				} else {
				
					return array('status' => false, 'msg' => 'Error: file_put_contents() failed (returned false). ', 'arr_args' => 'error', 'mix_to_log' => $mix_to_log);

				}
			}

		} // close method: log()

		
		protected function log_validate($arr_args = NULL) {
		
			// TODO isset()
			if ( $this->ezCONFIGS('validate') === true ) {
	
				/*
				 * for the log we're not going to be too picky. when in doubt, just use the defaults 
				 */ 
				if ( ! is_array($arr_args) ){
					return array('status' => true, 'msg' => 'defaults', 'arr_args' => $this->log_defaults());
				} elseif ( (!isset($arr_args['validate_unset'])) || ( isset($arr_args['validate_unset']) && !is_bool($arr_args['validate_unset']) ) ){
					$arr_args['validate_unset'] = true;
				}
				
				$arr_msg = array();
				$arr_args_clean = $arr_args;
			
				foreach ( $arr_args as $str_key => $mix_value ) {
				
					switch( $str_key ) {
					
						case 'active':
						case 'date_time_stamp':
							if ( ! is_bool($arr_args[$str_key]) ){
								$arr_msg[] = 'ERROR: arr_arg[' . $str_key . '] !is_bool())';
								unset($arr_args_clean[$str_key]);
							}
						break;
						
						case 'debug_log':
						case 'prefix':
						case 'default_string':
						case 'date_time_stamp_format':   //TODO - check for valid 
							if ( ! is_string($arr_args[$str_key]) ) {
								$arr_msg[] = 'ERROR: arr_arg[' . $str_key . '] !is_string())';
								unset($arr_args_clean[$str_key]);
							}
						break;
						
						case 'path':
							if ( ! is_string($arr_args['path']) || ! file_exists($arr_args['path']) ) {
								$arr_msg[] = 'ERROR: arr_arg[' . $str_key . '] !is_string() || !file_exists(). ';
								unset($arr_args_clean[$str_key]);
							} 
						break;
							
						case 'log_file':
							if ( $arr_args['log_file'] !== sanitize_file_name($arr_args['log_file']) ) {
								$arr_msg[] = 'ERROR: arr_arg[' . $str_key . '] is not a valid file name. ';
								unset($arr_args_clean[$str_key]);
							}
						
						break;
					}
				} // close: foreach
				
				if ( empty($arr_msg) ){
					return array('status' => true, 'msg' => 'success', 'arr_args' => $arr_args);
				} elseif ( $arr_args['validate_unset'] === true ){
					return array('status' => true, 'msg' => $arr_msg, 'arr_args' => $arr_args_clean);
				} else {
					return array('status' => false, 'msg' => $arr_msg, 'arr_args' => 'error');
				}
				
			} // close: if validate 
		} // close method: log_validate()
		
		
		public function log_defaults(){
		
			$str_path = ABSPATH . 'wp-content';
			if ( defined(WP_CONTENT_DIR) ) {
				$str_path = WP_CONTENT_DIR;
			}
		
			$arr_defaults = array(
								'active' 					=> true,
								'validate'					=> true,
								'validate_unset'			=> true,							// false will return an error, instead of doing an upset() and continuing
								'path'						=> $str_path . '/',
								'log_file' 					=> 'debug.log',
								'prefix' 					=> 'WP ezClasses Log >>> ',
								'date_time_stamp'			=> true,
								'date_time_stamp_format'	=> 'd-M-Y h:i:s A',
								'default_string' 			=> 'No string passed to log().',
							);
			/*
			 * Does the Master Singleton allow for the use of filters?
			 */
			$bool_filters = $this->ezCONFIGS('filters');
			if ( isset($bool_filters) && is_bool($bool_filters) && $this->ezCONFIGS('filters') ){
				$arr_defaults_via_filter = apply_filters('filter_ezc_devtools_log_log_defaults', $arr_defaults);
				if ( is_array($arr_defaults_via_filter) ){
					$arr_defaults = array_merge($arr_defaults, $arr_defaults_via_filter);
				}
			}
			return $arr_defaults;	
		}
		
	} // close class
} // close class_existss

?>