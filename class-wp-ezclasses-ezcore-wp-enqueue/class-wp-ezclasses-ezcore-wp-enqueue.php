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
	
		protected $_bool_validate_only_active_true;
		protected $_arr_wp_enqueue_web_fonts;
		protected $_arr_wp_enqueue_scripts_and_styles;
			
		public function __construct() {
			parent::__construct();
		}
		
		public function ezc_init($arr_args = NULL){
			$this->theme_wp_enqueue_init($arr_args);
		}
		
		/**
		 *
		 */
		public function theme_wp_enqueue_init($arr_args = NULL){
		
			// TODO - get this property with a set()
			$arr_defaults = $this->theme_wp_enqueue_init_defaults();
			$this->_bool_validate_only_active_true = $arr_defaults['bool_validate_only_active_true'];
			if ( isset($arr_args['bool_validate_only_active_true']) && is_bool($arr_args['bool_validate_only_active_true']) ){
				$this->_bool_validate_only_active_true = $arr_args['bool_validate_only_active_true'];
			}
		}
		
		protected function theme_wp_enqueue_init_defaults(){
		
			$arr_defaults = array(
								'bool_validate_only_active_true' => true,
							);
			return $arr_defaults;
		}

/*
 * ===============================================================================================
 * ==> wp_enqueue
 * ===============================================================================================
 */	


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
		
		/*
		*
		*/
		static public function wp_enqueue_css_media_supported(){
		
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
		
		

		/**
		 * 
		 */
		public function wp_enqueue_active_true($arr_args = NULL) {
			$str_return_source = 'Class_WP_ezClasses_Core_WP_Enqueue :: wp_enqueue_active_true()'; 

		
			if ( is_array($arr_args) && !empty($arr_args) ) {
				$arr_active_true = array();	
				foreach ( $arr_args as $str_key => $arr_value ) {
					if ( $arr_value['active'] === true){
						$arr_active_true[$str_key] = $arr_value;
					}
				}
				return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_active_true);
			} 
			return array('status' => false, 'msg' => 'ERROR: arr_args[] == ( ! is_array() || empty() )', 'source' => $str_return_source, 'arr_args' => 'error');
		}

		
		/**
		 * 
		 */		
		public function wp_enqueue_do($arr_args){
			$str_return_source = 'Class_WP_ezClasses_Core_WP_Enqueue :: wp_enqueue_do()'; 

			if ( is_array($arr_args) ){
				$obj_general_conditional_tags = Class_WP_ezClasses_ezCore_Conditional_Tags::ezc_get_instance();
				
				foreach ( $arr_args as $str_key => $arr_value ) {

					$arr_ret = $obj_general_conditional_tags->conditional_tags_evaluate($arr_value['conditional_tags']);
					if ( $arr_ret['status'] === true ){
						if ( $arr_value['type'] == 'style' ){
						
							wp_register_style($arr_value['handle'], $arr_value['src'], $arr_value['deps'] ,$arr_value['ver'], $arr_value['media'] );
							wp_enqueue_style($arr_value['handle']);
							
						} elseif ( $arr_value['type'] == 'script' ){
						
							// TODO - register then enqueue?
							wp_enqueue_script($arr_value['handle'], $arr_value['src'], $arr_value['deps'] ,$arr_value['ver'], $arr_value['in_footer'] );
							
						}
					}
				}			
				return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_args);
			}
			return array('status' => false, 'msg' => 'ERROR: arr_args !is_array()', 'source' => $str_return_source, 'arr_args' => 'error');
		}

		
/*
 * ===============================================================================================
 * ==> wp_enqueue_web_fonts - leans on the wp_enqueue section above
 * ===============================================================================================
 */		

		/**
		 * 
		 */
 		public function wp_enqueue_web_fonts_validate($arr_args = NULL){
		
			return $this->wp_enqueue_validate($arr_args);
	
		}

		
		/**
		 * 
		 */
		public function wp_enqueue_web_fonts_active_true($arr_args = NULL) {
					
			return $this->wp_enqueue_active_true($arr_args);

		}
		
		/**
		 *
		 */
		public function wp_enqueue_web_fonts_set($arr_args = NULL) {
			$str_return_source = 'Theme \ WP Enqueue :: wp_enqueue_web_fonts_set()'; 

	
			if ( is_array($arr_args) ){
				$this->_arr_wp_enqueue_web_fonts = $arr_args;			
				return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_args);
			}
			return array('status' => false, 'msg' => 'ERROR: arr_args !is_array()', 'source' => $str_return_source, 'arr_args' => 'error');
		}
		
		/*
		 *
		 */	
		public function add_action_wp_enqueue_web_fonts_do($str_hook = 'wp_enqueue_scripts', $int_priority = 10){
		
			add_action($str_hook, array( $this, 'wp_enqueue_web_fonts_do'), $int_priority = 10);
		}
	
		/**
		 *
		 */		
		public function wp_enqueue_web_fonts_do() {
					
			return $this->wp_enqueue_do($this->_arr_wp_enqueue_web_fonts);

		}


/*
 * ===============================================================================================
 * ==> wp_enqueue_scripts_and_styles - leans on the wp_enqueue section above
 * ===============================================================================================
 */	
 
 		/**
		 *
		 */	
		public function wp_enqueue_scripts_and_styles_validate($arr_args = NULL){
		
			return $this->wp_enqueue_validate($arr_args);
		} 
		
		/**
		 * 
		 */
		public function wp_enqueue_scripts_and_styles_active_true($arr_args = NULL) {
		
			return $this->wp_enqueue_active_true($arr_args);
		}

		/**
		 * 
		 */		
		public function wp_enqueue_scripts_and_styles_set($arr_args = NULL) {
			$str_return_source = 'Theme \ WP Enqueue :: wp_enqueue_scripts_and_styles_set()'; 

			if ( is_array($arr_args) ){
				$this->_arr_wp_enqueue_scripts_and_styles = $arr_args;			
				return array('status' => true, 'msg' => 'success', 'source' => $str_return_source , 'arr_args' => $arr_args);
			}
			return array('status' => false, 'msg' => 'ERROR: arr_args !is_array()', 'source' => $str_return_source, 'arr_args' => 'error');
		}
		
		
		/*
		 *
		 */	
		public function add_action_wp_enqueue_scripts_and_styles_do($str_hook = 'wp_enqueue_scripts', $int_priority = 10){
		
			add_action($str_hook, array( $this, 'wp_enqueue_scripts_and_styles_do'), $int_priority = 10);
		
		}

		/**
		 * 
		 */
		public function wp_enqueue_scripts_and_styles_do() {
							
			return $this->wp_enqueue_do($this->_arr_wp_enqueue_scripts_and_styles);
		}
		
	} // END: class
} // END: if class exists
?>