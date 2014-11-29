<?php
/** 
 * Methods related to WP's Conditional Tags
 *
 * Common snippets for Conditional Tags as ezClasses methods(). (@link http://codex.wordpress.org/Conditional_Tags)
 *
 * PHP version 5.3
 *
 * LICENSE: TODO
 *
 * @package WPezClasses
 * @author Mark Simchock <mark.simchock@alchemyunited.com>
 * @since 0.5.0
 * @license TODO
 */
 
/**
 * == Change Log == 
 *
 */


if ( !defined('ABSPATH') ) {
	header('HTTP/1.0 403 Forbidden');
    die();
}


if (! class_exists('Class_WP_ezClasses_ezCore_Conditional_Tags') ) {
	class Class_WP_ezClasses_ezCore_Conditional_Tags extends Class_WP_ezClasses_Master_Singleton {

		public function __construct(){
			parent::__construct();
		}
		
		/**
		 * Kinda like the __construct(), but different. the get_instance() in the master parent calls the ez_init()
		 */
		public function ezc_init(){

		}
		
		/**
		 * Takes an array of format conditional_tag => condition, evaluates across the entire array and returns true or false
		 *
		 * A simplified programatic if conditional for WP conditional tags. Example: Use this method for defining which .js files should be enqueue'd when.  
		 *
		 * - arr_args[]
		 * --- 'not'		'', '!' or true - Take the tags + operator evalution and invert it? Default: '' (i.e., no not)
		 * --- 'operator'	'and' or 'or' - How should the elements of the 'tags' array be evaluted? Default: 'and'.
		 * --- 'tags'		array( conditional_tag1 => condition1, conditional_tag2 => condition2...)
		 */ 
		public function conditional_tags_evaluate($arr_args = NULL){
			$str_return_source = 'Class_WP_ezClasses_Utilities_Conditional_Tags :: conditional_tags_evaluate()'; 

			global $post;
			
			//  null or empty? 
			if ( $arr_args === NULL || (isset($arr_args) && empty($arr_args)) || (!isset($arr_args['tags']) || (isset($arr_args['tags']) && empty($arr_args['tags']))) ){
				$arr_defaults = $this->conditional_tags_evaluate_defaults();
				if ( $arr_defaults['empty_returns'] === true ) {
					return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_args);
				} else {
					// note this is one of the few time that 'msg' => 'success' will be 'status' => false
					return array('status' => false, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_args);
				}
			}

			if ( ! is_array($arr_args) ){	
				return array('status' => false, 'msg' => 'ERROR: arr_args !is_array()', 'source' => $str_return_source, 'arr_args' => 'error');
			}
			
			if ( ! isset($arr_args['tags']) || !is_array($arr_args['tags']) ){
				return array('status' => false, 'msg' => 'ERROR: arr_args[tags] !isset() || !is_array()', 'source' => $str_return_source, 'arr_args' => 'error');			
			}

			// default: operator  = 'and'
			$str_operator = 'and';
			if ( isset($arr_args['operator']) && strtolower($arr_args['operator']) == 'or' ){
				$str_operator = 'or';
			}
			
			// Note: not => true feels awkward and prehaps confusing so we'll also use !
			$bool_not = '';
			if ( isset($arr_args['not']) && ( $arr_args['not'] == '!' || $arr_args['not'] == true ) ){
				$bool_not = '!';
			}

			$arr_msg = array();
			$bool_evaluate = array();
			
			//merge all the _supported
			$arr_tags_supported = array_merge($this->conditional_tags_supported(), $this->browser_detection_supported(), $this->conditional_tags_supported_only_in_loop(), $this->conditional_tags_supported_other());

			foreach ($arr_args['tags'] as $str_tag => $mixed_condition){
							
				$arr_msg_detail = array();
				
				if ( ! isset($str_tag) || ! isset($mixed_condition) ){	
					$arr_msg_detail[] = 'ERROR: conditional_tag and/or condition ! isset()';
				} elseif ( ! isset($arr_tags_supported[$str_tag]) ){			
					$arr_msg_detail[] = 'ERROR: conditional_tag ' . $str_tag . ' not supported';			
				} elseif ( ! is_bool($mixed_condition) ){ 
					
					if ( $arr_tags_supported[$str_tag]['bool_only'] === true ){		
						$arr_msg_detail[] = 'ERROR: conditional_tag accepts only condition bool';	
						// for when the conditon is part of the conditional_tag "question" + get_post_type() is a special case where we must test for == (and not get_post_type($mix_cond)
					} elseif ( (( is_string($mixed_condition) || is_array($mixed_condition) ) && $str_tag($mixed_condition)) || ( $str_tag == 'get_post_type' && get_post_type() == $mixed_condition ) ){
						$bool_evaluate['true'] = true;
					} else {
						$bool_evaluate['false'] = false;
					}
								
				} elseif ( is_bool($mixed_condition) ){
					// browser_detection_supported() tags are a special case and we need to work some magic
					$arr_bts = $this->browser_detection_supported();
					if ( isset($arr_bts[$str_tag])){
						global $$str_tag;
						if ($$str_tag === $mixed_condition){
							$bool_evaluate['true'] = true;
						} else {
							$bool_evaluate['false'] = false;
						}
					// test the tag against the condition
					} elseif ( $str_tag() === $mixed_condition){
						$bool_evaluate['true'] = true;
					} else {
						$bool_evaluate['false'] = false;
					}
				}
				// msg details?
				if ( !empty($arr_msg_detail) ){
					$arr_msg[$str_tag] = $arr_msg_detail;
				}
				
				// TODO - perhaps there are cases where we can exit the foreach early? e.g., operstor = and && isset(bool_ev[false]) = it's over
			}
			
				
			// done looping...do we have an error msgs?
			if ( ! empty($arr_msg)){
				return array('status' => false, 'msg' => $arr_msg, 'source' => $str_return_source, 'arr_args' => 'error');
			}
			
			//TODO - this probably a better way to do this next set of if / elseifs
			
			// if it's not not, then it's true
			if ( $bool_not != '!' ) {
			
					// no errors + (operator = and (i.e., all must be true)) && (we have at least one false) = true
				if ( $str_operator == 'and' && isset($bool_evaluate['false']) ) {
					return array('status' => false, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_args);
					
					// no errors + (operator = and (i.e., all must be true)) && (we have no false) = true
				} elseif ( $str_operator == 'and' && !isset($bool_evaluate['false']) ) {				
					return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_args);
				
					// no erros + (operator = or) && (we have at least one true_ = true
				} elseif ( $str_operator == 'or'  && isset($bool_evaluate['true']) ) {				
					return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_args);
					
					// no errors + (operator = or) &&  (we have no true) = false
				} elseif ( $str_operator == 'or' && !isset($bool_evaluate['true']) ) {				
					return array('status' => false, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_args);
					
					// WTF?
				} else {
					return array('status' => false, 'msg' => 'ERROR: conditional_tags_evaluate - evaluate failed. Note: This should never happen.', 'source' => $str_return_source, 'arr_args' => 'error');
				}
				
			} else {
			
				/*
				 * When 'not' == true we want to return the opposite of above. Except the Error will continue to return false 
				 */
			
					// no errors + (operator = and (i.e., all must be true)) && (we have at least one false) = true
				if ( $str_operator == 'and' && isset($bool_evaluate['false']) ) {				
					return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_args);
					
					// no errors + (operator = and (i.e., all must be true)) && (we have no false) = true
				} elseif ( $str_operator == 'and' && !isset($bool_evaluate['false']) ) {				
					return array('status' => false, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_args);
				
					// no erros + (operator = or) && (we have at least one true_ = true
				} elseif ( $str_operator == 'or'  && isset($bool_evaluate['true']) ) {				
					return array('status' => false, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_args);
					
					// no errors + (operator = or) &&  (we have no true) = false
				} elseif ( $str_operator == 'or' && !isset($bool_evaluate['true']) ) {				
					return array('status' => true, 'msg' => 'success', 'source' => $str_return_source, 'arr_args' => $arr_args);
					
				// WTF?
				} else {
					return array('status' => false, 'msg' => 'ERROR: conditional_tags_evaluate - evaluate failed. Note: This should never happen.', 'source' => $str_return_source, 'arr_args' => 'error');
				}
			}
		}
		
		/**
		 *
		 */
		public function conditional_tags_evaluate_defaults(){
		
			$arr_defaults = array(
								'empty_returns'	=> true,
							);
			return $arr_defaults;
		}
		
		/**
		 * http://codex.wordpress.org/Conditional_Tags#Conditional_Tags_Index
		 */
		public function conditional_tags_supported(){
		
			return array(	
						'is_404'						=> array('status' => true, 'bool_only' => true), 
						'is_admin'						=> array('status' => true, 'bool_only' => true),
						'is_archive'					=> array('status' => true, 'bool_only' => true),
						'is_attachment'					=> array('status' => true, 'bool_only' => true),
						'is_author'						=> array('status' => true, 'bool_only' => false), // false means ! bool are accepted as arguments. in this case, e.g. author ID might be passed
						
						'is_category'					=> array('status' => true, 'bool_only' => false),
						'is_child_theme'				=> array('status' => true, 'bool_only' => true), 
						'is_comments_popup'				=> array('status' => true, 'bool_only' => true),
						'is_date'						=> array('status' => true, 'bool_only' => true),
						'is_day'						=> array('status' => true, 'bool_only' => true),
						
						'is_feed'						=> array('status' => true, 'bool_only' => true),
						'is_front_page'					=> array('status' => true, 'bool_only' => true),
						'is_home'						=> array('status' => true, 'bool_only' => true),
						'is_month'						=> array('status' => true, 'bool_only' => true),
						'is_multi_author'				=> array('status' => true, 'bool_only' => true),
						
						'is_multisite'					=> array('status' => true, 'bool_only' => true),
						'is_main_site'					=> array('status' => true, 'bool_only' => true),
						'is_new_day'					=> array('status' => true, 'bool_only' => true),
						'is_page'						=> array('status' => true, 'bool_only' => false),
						'is_page_template'				=> array('status' => true, 'bool_only' => false),
						
						'is_paged'						=> array('status' => true, 'bool_only' => true),
						'is_post_type_archive' 			=> array('status' => true, 'bool_only' => false),
						'is_post_type_hierarchical'		=> array('status' => true, 'bool_only' => false),
						'is_preview'					=> array('status' => true, 'bool_only' => true),
						'is_rtl'						=> array('status' => true, 'bool_only' => true),
						
						'is_search'						=> array('status' => true, 'bool_only' => true),
						'is_single'						=> array('status' => true, 'bool_only' => false),
						'is_singular'					=> array('status' => true, 'bool_only' => false),
						'is_sticky'						=> array('status' => true, 'bool_only' => false),
						'is_super_admin'				=> array('status' => true, 'bool_only' => true),
						
						'is_tag'						=> array('status' => true, 'bool_only' => false),
						'is_tax'						=> array('status' => true, 'bool_only' => false),
						'is_time'						=> array('status' => true, 'bool_only' => true),
						'is_trackback'					=> array('status' => true, 'bool_only' => true),
						'is_year'						=> array('status' => true, 'bool_only' => true),

						'post_type_exists'				=> array('status' => true, 'bool_only' => false), 					
						'taxonomy_exists'				=> array('status' => true, 'bool_only' => false),
					);
		}
		
		/**
		 *
		 */
		public function conditional_tags_supported_only_in_loop(){
			return array(
						'in_category'					=> array('status' => true, 'bool_only' => false),
						'comments_open'					=> array('status' => true, 'bool_only' => true), 
						'get_post_type'					=> array('status' => true, 'bool_only' => false),
						'has_excerpt'					=> array('status' => true, 'bool_only' => false),

						'has_tag'						=> array('status' => true, 'bool_only' => false), // post parm (currently) not supported by ezClasses http://codex.wordpress.org/Function_Reference/has_tag
						'has_term'						=> array('status' => true, 'bool_only' => false), 
						'pings_open'					=> array('status' => true, 'bool_only' => true),
					);
		}
		
		/**
		 * Note: Perhaps not always conditional tags
		 */
		public function conditional_tags_supported_other(){
		
			return array(	
						'get_option'					=> array('status' => true, 'bool_only' => false),
						'is_active_sidebar'				=> array('status' => true, 'bool_only' => false),
						'wp_attachment_is_image'		=> array('status' => true, 'bool_only' => true),
						'current_user_can'				=> array('status' => true, 'bool_only' => false),  // NOTE: args are NOT supported, just the capability
						
						'is'							=> array('status' => true, 'bool_only' => true),
						'is_IIS'						=> array('status' => true, 'bool_only' => true),
						'is_iis7'						=> array('status' => true, 'bool_only' => true),
												
					);
		}


		/**
		 * http://codex.wordpress.org/Global_Variables
		 */
		public function browser_detection_supported(){
		
			return array(
						'is_iphone'		=> array('status' => true, 'bool_only' => true), 
						'is_chrome'		=> array('status' => true, 'bool_only' => true),
						'is_safari'		=> array('status' => true, 'bool_only' => true), 
						'is_NS4'		=> array('status' => true, 'bool_only' => true),
						'is_opera'		=> array('status' => true, 'bool_only' => true), 
						'is_macIE'		=> array('status' => true, 'bool_only' => true),
						'is_winIE'		=> array('status' => true, 'bool_only' => true),
						'is_gecko'		=> array('status' => true, 'bool_only' => true),  // = firefox
						'is_lynx'		=> array('status' => true, 'bool_only' => true), 
						'is_IE'			=> array('status' => true, 'bool_only' => true),
					);
		}
		
	}  // close: class
} // close: if class_exists