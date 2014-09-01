<?php
/** 
 * Takes your arrays (of fonts, scripts and/or styles) and enqueues them. 
 *
 * More info: (@link http://codex.wordpress.org/Function_Reference/get_search_form)
 *
 * PHP version 5.3
 *
 * LICENSE: TODO
 *
 * @package WP ezClasses
 * @author Mark Simchock <mark.simchock@alchemyunited.com>
 * @since 0.5.0
 */
 
/*
 * == Change Log ==
 *
 * -- 14 July 2013 - Ready!
 */
 
if ( ! class_exists('Class_WP_ezClasses_ezCore_WP_Enqueue') ) {
  class Class_WP_ezClasses_ezCore_WP_Enqueue extends Class_WP_ezClasses_Master_Singleton {
  
    protected $_arr_init;
	
	public function __construct() {
	  parent::__construct();
	}
		
    public function ezc_init($arr_args = NULL){
      $this->_arr_init = $this->init_defaults();

	}
		
    protected function init_defaults(){
	
	  $arr_defaults = array(
	    'active' 		=> true,
		'active_true'	=> true,
		'filters' 		=> false,
		'validation' 	=> false,
		'arr_args'		=> array(),
        ); 
	  return $arr_defaults;
	}
	
	/**
	 * wp_register_script + wp_register_style
	 */ 
    public function ez_rs($arr_args = ''){
	
	  if ( ! WP_ezMethods::array_pass($arr_args) ){
	    return array('status' => false, 'msg' => 'ERROR: arr_args is not valid', 'source' => get_class() . ' ' . __METHOD__, 'arr_args' => 'error');
	  }

	    $arr_args = WP_ezMethods::ez_array_merge(array( $this->_arr_init, $arr_args)); 
	
	    if ( $arr_args['active'] === true && WP_ezMethods::array_pass($arr_args['arr_args']) ){
		
		  $arr_wp_register = $arr_args['arr_args'];

			
				// validate - optional
				/* TODO
				if ( isset($arr_args['validate']) && $arr_args['validate'] === true ){
					$bool_return = false;
					$arr_validate_response_merge = array();
					
					$arr_validate_response = $this->_obj_ezc_theme_register_sidebar->register_sidebar_base_validate($arr_register_sidebar_base);
					if ( $arr_validate_response['status'] !== true){
						$arr_validate_response_merge[] = $arr_validate_response;
						$bool_return = true;
					} else {
						$arr_register_sidebar_base = $arr_validate_response['arr_args'];
					}
					
					
					$arr_validate_response = $this->_obj_ezc_theme_register_sidebar->register_sidebar_validate($arr_register_sidebar);
					if ( $arr_validate_response['status'] !== true){
						$arr_validate_response_merge[] = $arr_validate_response;
						$bool_return = true;
					} else {
						$arr_register_sidebar = $arr_validate_response['arr_args'];
					}
					
					if  ( $bool_return === true ) {
						return $arr_validate_response_merge;
					}	
				}
				*/
							
				/**
				 * returns arr_args that are active. if you don't really have an active => false then checking this is not really necessary (but it can help). 
				 */
				if ( WP_ezMethods::ez_true($arr_args['active_true']) ){
				
					$arr_active_true_response = $this->wp_register_enqueue_active_true($arr_wp_register);
					
					if ( $arr_active_true_response['status'] === false ){
						return $arr_active_true_response;
					}
					$arr_wp_register = $arr_active_true_response['arr_args'];
				}
				
				/**
				 * At this point we should be good to go. Do!
				 */ 
				 
				$this->wp_register_do($arr_wp_register);

				return true;
			
		} else {
			//TODO - not an array
		}
	}  
	
	/**
	 * wp_enqueue_script + wp_enqueue_style
	 */ 
    public function ez_es($arr_args = ''){
	
	  if ( ! WP_ezMethods::array_pass($arr_args) ){
	    return array('status' => false, 'msg' => 'ERROR: arr_args is not valid', 'source' => get_class() . ' ' . __METHOD__, 'arr_args' => 'error');
	  }
	  
	    $arr_args = WP_ezMethods::ez_array_merge(array( $this->_arr_init, $arr_args)); 
	
	    if ( $arr_args['active'] === true && WP_ezMethods::array_pass($arr_args['arr_args']) ){
		
		  $arr_wp_enque = $arr_args['arr_args'];

			
				// validate - optional
				/* TODO
				if ( isset($arr_args['validate']) && $arr_args['validate'] === true ){
					$bool_return = false;
					$arr_validate_response_merge = array();
					
					$arr_validate_response = $this->_obj_ezc_theme_register_sidebar->register_sidebar_base_validate($arr_register_sidebar_base);
					if ( $arr_validate_response['status'] !== true){
						$arr_validate_response_merge[] = $arr_validate_response;
						$bool_return = true;
					} else {
						$arr_register_sidebar_base = $arr_validate_response['arr_args'];
					}
					
					
					$arr_validate_response = $this->_obj_ezc_theme_register_sidebar->register_sidebar_validate($arr_register_sidebar);
					if ( $arr_validate_response['status'] !== true){
						$arr_validate_response_merge[] = $arr_validate_response;
						$bool_return = true;
					} else {
						$arr_register_sidebar = $arr_validate_response['arr_args'];
					}
					
					if  ( $bool_return === true ) {
						return $arr_validate_response_merge;
					}	
				}
				*/
							
				/**
				 * returns arr_args that are active. if you don't really have an active => false then checking this is not really necessary (but it can help). 
				 */
				if ( WP_ezMethods::ez_true($arr_args['active_true']) ){
				
					$arr_active_true_response = $this->wp_register_enqueue_active_true($arr_wp_enqueue);
					
					if ( $arr_active_true_response['status'] === false ){
						return $arr_active_true_response;
					}
					$arr_wp_enqueue = $arr_active_true_response['arr_args'];
				}
				
				/**
				 * At this point we should be good to go. Do!
				 */ 
				$this->wp_enqueue_do($arr_wp_register);

				return true;
			
		} else {
			//TODO - not an array
		}
	}  





/* TODO
		protected function wp_enqueue_validate($arr_args = NULL){
			$str_return_source = 'Class_WP_ezClasses_Core_WP_Enqueue :: wp_enqueue_validate()'; 

		
			if ( ! is_array($arr_args) ){
				return array('status' => false, 'msg' => 'ERROR: arr_args[] ! is_array()', 'source' => $str_return_source, 'arr_args' => 'error');
			}
					
			$arr_css_media_supported = $this->wp_enqueue_css_media_supported();	
			$arr_msg = array();
			foreach ( $arr_args as $str_key => $arr_value ) {
				
				if ( ( $this->_bool_validate_only_active_true == true && isset($arr_value['active']) && $arr_value['active'] !== true ) ){
					continue;
				}

				$arr_msg_detail = array();
				if ( is_array($arr_value) ){
	
					if ( !isset($arr_value['active']) || !isset($arr_value['conditional_tags'])  ||  !isset($arr_value['type']) 
					 || !isset($arr_value['handle'])  || !isset($arr_value['src'])  || !isset($arr_value['deps']) || !isset($arr_value['ver']) 
					 || (($arr_value['type'] == 'style' && !isset($arr_value['media'])) || ($arr_value['type'] == 'script'  && !isset($arr_value['in_footer']))) ){
						$arr_msg_detail[] = 'ERROR: one of the keys !isset()';
					}
					
					if ( isset($arr_value['active']) && !is_bool($arr_value['active'])) {
						$arr_msg_detail[] = 'ERROR: arr_arg[status] !is_bool()';
					}
					if ( isset($arr_value['conditional_tags']) && (empty($arr_value['conditional_tags']) || !is_array($arr_value['conditional_tags'])) ) {
						$arr_msg_detail[] = 'ERROR: arr_arg[conditional_tags] !is_array()';
					}
					if ( isset($arr_value['type']) && $arr_value['type'] != 'style' && $arr_value['type'] != 'script' ) {
						$arr_msg_detail[] = 'ERROR: arr_arg[type] != style && != script';
					}
					if ( isset($arr_value['handle']) && ! is_string($arr_value['handle'])) {
						$arr_msg_detail[] = 'ERROR: arr_arg[handle] ! is_string()';
					}	
					if ( isset($arr_value['src']) && ! filter_var($arr_value['src'],FILTER_VALIDATE_URL ) ) {
						$arr_msg_detail[] = 'ERROR: arr_arg[src] ! filter_var(FILTER_VALIDATE_URL)';
					}
					if ( isset($arr_value['deps']) && ( ! is_array($arr_value['deps']) && $arr_value['deps'] !== false ) ) {
						$arr_msg_detail[] = 'ERROR: arr_arg[deps] ! is_array() || !false';
					}
					if ( isset($arr_value['ver']) && ! is_string($arr_value['ver'])) {
						$arr_msg_detail[] = 'ERROR: arr_arg[ver] ! is_string()';
					}
					if ( (isset($arr_value['type']) && $arr_value['type'] == 'style') && (isset($arr_value['media']) && ! in_array($arr_value['media'], $arr_css_media_supported)) ) {
						$arr_msg_detail[] = 'ERROR: arr_arg[media] == style && arr_arg[media] not a valid value for media';
					}
					if ( (isset($arr_value['type']) && $arr_value['type'] == 'script') && (isset($arr_value['in_footer']) && ! is_bool($arr_value['in_footer'])) ) {
						$arr_msg_detail[] = 'ERROR: arr_arg[media] == script && arr_arg[in_footer] !is_bool()';
					}
				} else {
					$arr_msg_detail[] = 'ERROR: Expected value of type array for this key';
				}
				
				if ( ! empty($arr_msg_detail) ){
					$arr_msg[$str_key] = $arr_msg_detail;
				}

			}

			if ( empty($arr_msg) ){
				return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_args);
			} else {
				return array('status' => false, 'msg' => $arr_msg, 'source' => $str_return_source, 'arr_args' => 'error');
			}
		} 
*/
		
		/*
		*
		*/
		/*
		public function wp_enqueue_css_media_supported(){
		
			return array(
						'all' 			=> true,
						'braille'		=> true,
						'embossed'		=> true,
						'handheld'		=> true,
						'print'			=> true,
						'projection'	=> true,
						'screen'		=> true,
						'speech'		=> true,
						'tty'			=> true,
						'tv'			=> true,
					);
		}
		*/
		
		public function wp_enqueue_script_defaults(){
		
		  return array(
		    'active'			=> true,
			'conditional_tags'	=> array(),
			'type'				=> 'required',  //legit values are 'script' and 'style'. if type is required the _do will bypass it. 
		    'src'				=> false,
			'deps'				=> array(),
			'ver'				=> false,
			'media'				=> 'all',
			'in_footer'			=> false
			);
		}
		
		

		/**
		 * 
		 */
		public function wp_register_enqueue_active_true($arr_args = '') {
		  $str_return_source = get_class() . ' ' . __METHOD__;
		
			if ( WP_ezMethods::array_pass($arr_args) ) {
				$arr_active_true = array();	
				foreach ( $arr_args as $str_key => $arr_value ) {
					if ( WP_ezMethods::ez_true($arr_value['active'] === true) ){
						$arr_active_true[$str_key] = $arr_value;
					}
				}
				return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_active_true);
			} 
			return array('status' => false, 'msg' => 'ERROR: arr_args is not valid', 'source' => $str_return_source, 'arr_args' => 'error');
		}

		
		/**
		 * 
		 */		
		public function wp_register_do($arr_args = ''){
		  $str_return_source = get_class() . ' ' . __METHOD__;

			if ( WP_ezMethods::array_pass($arr_args) ){
				
				foreach ( $arr_args as $str_key => $arr_value ) {
				
				  // we'll slide the defaults underneath just in case
				  $arr_value = WP_ezMethods::ez_array_merge(array($this->wp_enqueue_script_defaults(), $arr_value));
				
				  // validation is a TODO
				  if ( $arr_value['active'] === true ){
				
					// $arr_ret = $obj_ezcore_conditional_tags->conditional_tags_evaluate($arr_value['conditional_tags']);
					/// if ( $arr_ret['status'] === true ){
						if ( $arr_value['type'] == 'style' ){
						
							wp_register_style($arr_value['handle'], $arr_value['src'], $arr_value['deps'] ,$arr_value['ver'], $arr_value['media'] );
		//	echo '<br>' .$arr_value['handle'] . $arr_value['src'] . ' - ' . $arr_value['deps'] . ' - ' . $arr_value['ver'] . $arr_value['media'] . '</br>';
						} elseif ( $arr_value['type'] == 'script' ){
		//	echo '<br>' .$arr_value['handle'] . $arr_value['src'] . ' - ' . $arr_value['deps'] . ' - ' . $arr_value['ver'] . $arr_value['in_footer'] . '</br>';
		
							wp_register_script($arr_value['handle'], $arr_value['src'], $arr_value['deps'] ,$arr_value['ver'], $arr_value['in_footer'] );
		//	echo '<br> script </br>';
						} else {
						  // TODO - style must have been invalid. 
						}
		//print_r($arr_value);
					//}
				  }
				}
//die('c');				
				return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_args);
			}
			return array('status' => false, 'msg' => 'ERROR: arr_args !is_array()', 'source' => $str_return_source, 'arr_args' => 'error');
		}
		
		/**
		 * 
		 */		
		public function wp_enqueue_do($arr_args = ''){
		  $str_return_source = get_class() . ' ' . __METHOD__;

			if ( WP_ezMethods::array_pass($arr_args) ){
			
				$obj_ezcore_conditional_tags = Class_WP_ezClasses_ezCore_Conditional_Tags::ezc_get_instance();
				$arr_wp_enqueue_script_defaults = $this->wp_enqueue_script_defaults();
				
				foreach ( $arr_args as $str_key => $arr_value ) {
				
				  $arr_value = array_merge($arr_wp_enqueue_script_defaults,  $arr_value);
				
				  // validation is a TODO
				  if ( $arr_value['active'] === true ){
				
					$arr_ret = $obj_ezcore_conditional_tags->conditional_tags_evaluate($arr_value['conditional_tags']);
					if ( $arr_ret['status'] === true ){
				//	echo $arr_value['handle'];
						if ( wp_style_is($arr_value['handle'], 'registered') ){
						
							wp_enqueue_style($arr_value['handle'] );
			//echo $str_key . ' ';	
						} elseif ( wp_script_is($arr_value['handle'], 'registered') ){
			///echo $str_key . ' ';
							wp_enqueue_script($arr_value['handle']);
							
						} else {
						  // TODO - style must have been invalid. 
						}
					}
				  }
	// echo $str_key . ' ';
				}
//die('n');				
				return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_args);
			}
			return array('status' => false, 'msg' => 'ERROR: arr_args !is_array()', 'source' => $str_return_source, 'arr_args' => 'error');
		}
	
	} // END: class
} // END: if class exists
?>