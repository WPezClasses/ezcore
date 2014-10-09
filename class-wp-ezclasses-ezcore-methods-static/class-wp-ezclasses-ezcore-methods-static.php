<?php
/** 
 * Assorted snippets / helpers wrapped up in ez to use static methods 
 *
 * Note: Some of these are *not* WP-centric and can be used with plain vanilla PHP.
 *
 * PHP version 5.3
 *
 * LICENSE: MIT
 *
 * @package WP ezClasses
 * @since 0.5.0
 */
 
/*
 * == CHANGE LOG == 
 *
 *  -- Thur 9 Oct 2014 - Added: get_image_sizes() 
 *
 *  -- Sun 11 Aug 2013 - Added: implode_obj()
 *
 *  -- Mon 5 May 2013 - Added ez_validate_url()
 *
 *  -- Wed 3 April 2013 - Added: responsive_decode()
 */

 
if ( !defined('ABSPATH') ) {
	header('HTTP/1.0 403 Forbidden');
    die();
}

if ( ! class_exists('Class_WP_ezClasses_ezCore_Methods_Static')) {
	class Class_WP_ezClasses_ezCore_Methods_Static extends Class_WP_ezClasses_Master_Singleton {
	
		/**
		* Var short description TODO
		*
		* Potential values are TODO
		*
		* @var string TODO
		*/
		
		// instance of wpezToolsClassesLog for ->log()
		protected $ezc_tools;
		
		/**
		 * Note: We're not using the construct other than to get "global" properties defined in the master parent
		 */
		protected function __construct(){
			parent::__construct();
		//	$this->ezc_tools = wpezToolsClassesLog::ezc_get_instance();  << TODO - Fix / update. Helpful for when debugging new methods.
		}
		
		/**
		 * 
		 */
		public function ezc_init(){}	


		
// == Start: Boilerplate

/**
 * Method short description
 *
 * Method detailed description
 *
 * @author Mark Simchock <mark.simchock@alchemyunited.com>
 * 
 * @param 	string	$arg1	the string to quote
 * @param 	int		$arg2	an integer of how many problems happened.
 *                    		Indent to the description's starting point
 *                     		for long ones.
 *
 * @return int the integer of the set mode used. FALSE if foo foo could not be set.
 *             
 */
 
/*
* - - Change Log - - 
*
*/

// == End: Boilerplate



		/** TODO - THIS IS *VERY* EXPERIMENTAL (and 100% *untested*)
		 *
		 * Similar to get_stylesheet_directory_uri() but will fallback to the parent if the file does not exist in the child. If the parent parent doesn't have it, it returns ''
		 *
		 * Kinda like get_template_part but slightly different. 
		 *
		 * @author Mark Simchock <mark.simchock@alchemyunited.com>
		 * 
		 * @param 	string	$arg1	the string of 'folder/file.ext' - DO NOT include a leading slash
		 *
		 * @return string - get_stylesheet_directory_uri() if the file is in the child, get_template_directory_uri() if not and it's in the parent, else '' (blank) if neither have the folder/file
		 *             
		 */
		 
		/*
		* - - Change Log - - 
		*
		*/

		static public function get_stylesheet_directory_uri_ez( $str_folder_file_ext = array() ){

			if ( is_string($str_folder_file_ext) ){
	
				$str_uri = get_stylesheet_directory_uri();
				if ( self::file_exists_ez( $str_uri . $str_folder_file_ext ) ){
				
					return $str_uri;
				
				} else{
					$str_uri = get_template_directory_uri();
					if ( self::file_exists_ez( $str_uri . $str_folder_file_ext ) ){
			
					return $str_uri;
					}
				}	
			}
			return '';
		
		}
		
		/*  
		 * TODO - THIS IS UN-FULLY TESTED
		 */
		static public function file_exists_ez($str_url){
		
			// do we have a valid URL?
			if( ! filter_var($str_url, FILTER_VALIDATE_URL) ) {

				return false;
			}
		
			// if the URL is good then we can check for the file
			$str_file_headers = @get_headers($str_url);
			if( strpos($str_file_headers[0], '404 Not Found') ) {
			
				return false;
			}
	
			return true;
		}

  /**
   * Similar to PHP's Filter: VALIDATE URL but with a bit more fire-power.  
   *
   * Takes an array of URLs: loops thru to checks each for a scheme (protocol), if none, adds the (default) protocol, then runs that past FILTER_VALIDATE_URL. returns the first valid URL in the array to pass, else returns false;
   *
   * @author Mark Simchock <mark.simchock@alchemyunited.com>
   * 
   * @param 	string		$arg1	key = 'protocol' - e.g. 'https://'. Default: 'http://'
   * @param 	array		$arg2	key = 'urls' - array of the strings to be VALIDATE URL'ed. The first to pass, will - if necessary - be prefixed with the protocol.
   *
   * @return int the integer of the set mode used. FALSE if foo foo could not be set.
   *             
   */
 
  /*
   * - - Change Log - - 
   *
   */

    static public function ez_validate_url( $arr_args = array() ){
	
	  if ( self::array_pass($arr_args) && self::array_key_pass( $arr_args,'urls' ) ){
	    $arr_defaults = array(
	                      'protocol' => 'http://'
						);
						
        $arr_args = array_merge($arr_defaults, $arr_args);
		//$arr_urls = $arr_args['urls']
	
		foreach ( $arr_args['urls'] as $str_url ){
		
		  $arr_parse_url = parse_url($str_url);
		  if ( ! isset($arr_parse_url['scheme']) || empty($arr_parse_url['scheme']) ){
		    $str_url = $arr_args['protocol'] . $str_url;
		  }
		  if ( filter_var($str_url, FILTER_VALIDATE_URL) ){
            return $str_url;
		  }
	    }
		return false;
      }
	}
	

  /**
   * Get all current image sizes (native and custom) as well as their settings
   * 
   * As found here: http://codex.wordpress.org/Function_Reference/get_intermediate_image_sizes
   *
   * @author Mark Simchock <mark.simchock@alchemyunited.com>
   * 
   * @param 	mixed		$mixed	
   *
   * @return array()
   *             
   */
 
  /*
   * - - Change Log - - 
   *
   */

    static public function get_image_sizes( $str_get_size = '' ){

	  $arr_native_sizes = array( 'thumbnail', 'medium', 'large' );
	
	  global $_wp_additional_image_sizes;
	  $arr_wp_additional_image_sizes = $_wp_additional_image_sizes;
	  
	  $arr_sizes = array();
	  $arr_get_intermediate_image_sizes = get_intermediate_image_sizes();
	  
	  // Create the full array with sizes and crop info
	  foreach( $arr_get_intermediate_image_sizes as $str_size ) {
	  
	    if ( in_array( $str_size, $arr_native_sizes ) ) {
		
		  $arr_sizes[ $str_size ]['width'] = get_option( $str_size . '_size_w' );
		  $arr_sizes[ $str_size ]['height'] = get_option( $str_size . '_size_h' );
		  $arr_sizes[ $str_size ]['crop'] = (bool) get_option( $str_size . '_crop' );
		  
		} elseif ( isset( $arr_wp_additional_image_sizes[ $str_size ] ) ) {
		
		  $arr_sizes[ $str_size ] = array( 
		    'width' => $arr_wp_additional_image_sizes[ $str_size ]['width'],
			'height' => $arr_wp_additional_image_sizes[ $str_size ]['height'],
			'crop' =>  $arr_wp_additional_image_sizes[ $str_size ]['crop']
           );
		}
      }

	  // Get only 1 size if found
      if ( $str_get_size ) {
	    
		if( isset( $arr_sizes[ $str_get_size ] ) ) {
		
		  return $arr_sizes[ $str_get_size ];
        } else {
		  return false;
		}
	  }
	  return $arr_sizes;
	}	

	
  /**
   * Is the value passed in true
   * 
   * Useful for testing arrays and not having to do: ( isset($arr['key']) && $arr['key'] === true )
   *
   * @author Mark Simchock <mark.simchock@alchemyunited.com>
   * 
   * @param 	mixed		$mixed	
   *
   * @return bool  true if mixed is true, else false.
   *             
   */
 
  /*
   * - - Change Log - - 
   *
   */

    static public function ez_true( $mixed = '' ){
	
	  $bool_ret = false;
	  if ($mixed === true){
	     $bool_ret = true;
	  } 
	  return $bool_ret;
	}
	
	
  /**
   * Is the value passed in false
   * 
   * Useful for testing arrays and not having to do: ( isset($arr['key']) && $arr['key'] === true )
   *
   * @author Mark Simchock <mark.simchock@alchemyunited.com>
   * 
   * @param 	mixed		$mixed	
   *
   * @return bool  false if mixed is false, else true.
   *             
   */
 
  /*
   * - - Change Log - - 
   *
   */

    static public function ez_false( $mixed = '' ){
	
	  $bool_ret = true;
	  if ($mixed === false){
	     $bool_ret = false;
	  } 
	  return $bool_ret;
	}
	
	
  /**
   * Similar to WP's get_template_part() 
   *
   * Adds a bool flag in order to bypass the get_template_part attempt
   *
   * @author Mark Simchock <mark.simchock@alchemyunited.com>
   * 
   * @param 	string		$str_slug		The slug name for the generic template. (required) 
   * @param 	string		$str_name		The name of the specialized template. (optional) 
   * @param 	bool		$bool_active	must be true to continue, anything else is considered false
   *
   * @return 	bool						if bool_active === false || str_slug == '', else returns true. 
   *             
   */
 
  /*
   * - - Change Log - - 
   *
   */

    static public function ez_gtp( $str_slug = '', $str_name = '',  $bool_active = true ) {
	
	  if ( $bool_active !== true  || ! is_string($str_slug) || $str_slug == '' ) {
	    return false;
	  }
	  get_template_part($str_slug, $str_name);

	}
	
	
  /**
   * Similar to WP's dynamic_sidebar(). Ref: http://codex.wordpress.org/Function_Reference/dynamic_sidebar
   *
   * Adds a bool flag in order to bypass the get_template_part attempt
   *
   * @author Mark Simchock <mark.simchock@alchemyunited.com>
   * 
   * @param 	string		$str_index		Name or ID of dynamic sidebar (optional) 
   * @param 	bool		$bool_active	must be true to continue, anything else is considered false
   *
   * @return 	bool						if bool_active === false || str_slug == '', else returns true. 
   *             
   */
 
  /*
   * - - Change Log - - 
   *
   */

    static public function ez_ds( $str_index = '', $bool_active = true ) {
	
	  if ( $bool_active !== true || ! is_string($str_index) ) {
	    return false;
	  }
	  dynamic_sidebar($str_index);

	}
	
  /**
   * Similar to WP's is_active_sidebar(). Ref: http://codex.wordpress.org/Function_Reference/is_active_sidebar
   *
   * Adds a bool flag in order to bypass the get_template_part attempt
   *
   * @author Mark Simchock <mark.simchock@alchemyunited.com>
   * 
   * @param 	string		$str_index		Name or ID of dynamic sidebar (optional) 
   * @param 	bool		$bool_active	must be true to continue, anything else is considered false
   *
   * @return 	bool						 
   *             
   */
 
  /*
   * - - Change Log - - 
   *
   */

    static public function ez_ias( $str_index = '', $bool_active = true ) {
	
	  if ( $bool_active !== true || ! is_string($str_index) ) {
	    return false;
	  }
	  
	  return is_active_sidebar($str_index);
	}
	
		
		/**
		 * 
		 *
		 * 
		 *
		 * @author Mark Simchock <mark.simchock@alchemyunited.com>
		 * 
		 * @param 	string	$str_to_decode		explodes a hyphen'ed string.
		 *                     		
		 * @return	sting						framework friendly class        
		 */
		 
		/*
		 * == CHANGE LOG == 
		 *
		 */
		static public function responsive_decode($str_to_decode = array()){
		
			if ( ! is_string($str_to_decode) || empty($str_to_decode) ){
				return false;
			}
			
			$arr_to_decode = explode( '-' , $str_to_decode);
			/**
			 * [0] = the framework, so we should have at least one [1], else something is wrong with the str in. 
			 */
			if ( isset($arr_to_decode[1]) ){
				// what framework?
				$str_framework = $arr_to_decode[0]; 
				// get the framework presets
				$arr_framework = self::responsive_decode_presets($str_framework);
				// remove the framework and reindex the array
				unset($arr_to_decode[0]);
				$arr_to_decode = array_merge($arr_to_decode);
				// build the class array
				$arr_return = array();
				foreach ( $arr_to_decode as $int_key => $int_value ){
					if ( isset($arr_framework[$int_key]) ){
						$arr_return[] = $arr_framework[$int_key] . $int_value;
					}
				}
				// implode the array into a string
				return implode(' ', $arr_return);
			}	
		}
		
		protected function responsive_decode_presets($str_framework = ''){
		
			switch($str_framework){
			
				// bootstrap 3.x
				case 'bs3':
				
					return array(
								'col-xs-',
								'col-sm-',
								'col-md-',
								'col-lg-',
								'col-xl-',
								);
								
				break;
				
				default:
				
					return array(
								'col-xs-',
								'col-sm-',
								'col-sm-',
								'col-lg-',
								'col-xl-',
								);
								
				break;
			
			}
		}
		
	
		/**
		 * String compare: Does the haystack start with the needle
		 *
		 * As seen here: (@link http://stackoverflow.com/questions/834303/php-startswith-and-endswith-functionsp)
		 *
		 * @author Salman A
		 * 
		 * @param 	string	$str_haystack		the haystack string
		 * @param 	string	$str_needle		 	the needle string
		 *                     		
		 * @return bool        
		 */
		 
		/*
		 * == CHANGE LOG == 
		 *
		 */
		static public function starts_with($str_haystack , $str_needle){
		
			return $str_needle === "" || strpos($str_haystack, $str_needle) === 0;
		}


		
		/**
		 * String compare: Does the haystack end with the needle
		 *
		 * As seen here: (@link http://stackoverflow.com/questions/834303/php-startswith-and-endswith-functionsp)
		 *
		 * @author Salman A
		 * 
		 * @param 	string	$str_haystack		the haystack string
		 * @param 	string	$str_needle		 	the needle string
		 *                     		
		 * @return bool        
		 */
		 
		/*
		 * == CHANGE LOG == 
		 *
		 */
		static public function ends_with($str_haystack , $str_needle){
		
			return $str_needle === "" || substr($str_haystack, -strlen($str_needle)) === $str_needle;
		}
		
		
		
		/**
		 * Converts an obj->property to an array and implode()s it. Pass in an object, specify the property and this returns an array of values for that property for the object. 
		 *
		 * A object-friendly version of implode() @link http://www.php.net/manual/en/function.implode.php). 
		 *
		 * @author Mark Simchock <mark.simchock@alchemyunited.com>
		 * 
		 * @param 	string	$str_glue		the string to quote
		 * @param 	object	$obj_pieces		the object to implode
		 * @param	string	$str_property	the property within the obj 
		 *                     		
		 * @return string from implode(). FALSE if params don't pass simple validation          
		 */
		 
		/*
		 * == CHANGE LOG == 
		 *
		 */
		static public function implode_obj($str_glue, $obj_pieces, $str_property){
		
			if ( is_string($str_glue) && is_object($obj_pieces) && is_string($str_property) ){
									
				$arr_pieces = array();
				foreach($obj_pieces as $piece) { 
					// is categories an array, then implode()
					$arr_pieces[] = $piece->trim($str_property);
				}
				return implode($str_glue, $arr_pieces);
			}
			return false;
		}	
		
		
		/**
		 * Similar to PHP array_merge() but...
		 *
		 * Takes an array of args, checks them for their array-ness and then does an array_merge. More or less PHP array_merge without pulling an error if you one of the arrays is not an array. 
		 *
		 * No need to worry if one of your arrays is not an array. Merge is done from "left to right" just like traditional array_merge() (@link http://php.net/manual/en/function.array-merge.php)
		 *
		 * @author Mark Simchock <mark.simchock@alchemyunited.com>
		 * 
		 * @param 	array	$arr_args		an array of the arrays to be array_merged
		 *                     		
		 * @return	array 					merged $arr_args, else false if params don't pass simple validation          
		 */
		 
		/*
		 * == CHANGE LOG == 
		 *
		 */
		static public function ez_array_merge($arr_args){
		
			if ( is_array($arr_args)){
				$arr_clean = array();
				foreach ($arr_args as $arr_is){
					if ( is_array($arr_is) && ! empty($arr_is) ){
						$arr_clean = array_merge($arr_clean, $arr_is);
					}
				}
				return $arr_clean;
			}
			return false;
		}	
	
		/**
		 * Tricked out version of WP multisite's swtich_to_blog()
		 *
		 * Tests for multisite, as well as whether the blog_id to swtich to is a legit blog_in in the network
		 *
		 * @author Mark Simchock <mark.simchock@alchemyunited.com>
		 * 
		 * @param 	int		$int_blog_id	an integer of how many problems happened.
		 *                    				Indent to the description's starting point
		 *                     				for long ones.
		 *
		 * @return bool		false			if not multisite or non-int is passed in for blog_id
		 * @return bool		true			if multisite and switch is a success
		 * @return int						if swtich to id is not an idea in the network then that int gets returned				            
		 */
		 
		/*
		* - - Change Log - - 
		*
		*/
		static public function switch_to_blog_ez($int_blog_id = NULL ){
		
			if ( is_multisite() && is_int($int_blog_id) ) {
				$int_current_blog_id = get_current_blog_id();
				if ( $int_blog_id != $int_current_blog_id ){
				
					// if the blog_id isn't legit then we can't switch to it.
					if ( self::blog_id_pass($int_blog_id) === true ) {
						switch_to_blog($int_blog_id);
						return true;
					} else {
						// if the blog_id isn't a legit blog_id on the network then send it back, literally
						return $int_blog_id;
					}

				} else {
					// nothing is really changing but we'll return true
					return true;
				}
				
			} else {
				return false;
			}
		}

		
		/**
		 * Tricked out version of WP multisite's restore_current_blog()
		 *
		 * Saves you from having to check for is_multisite() every time you do a restore_current_blog
		 *
		 * @author Mark Simchock <mark.simchock@alchemyunited.com>
		 * 
		 * @param 	int		$int_blog_id	an integer of how many problems happened.
		 *                    				Indent to the description's starting point
		 *                     				for long ones.
		 *
		 * @return bool		false			if not multisite	
		 * @return bool		true			is multisite (and we presume the swtich went ok).	 
		 */
		 
		/*
		* - - Change Log - - 
		*
		*/
		static public function restore_current_blog_ez(){
		
			if ( is_multisite() ) {

				restore_current_blog();
				return true;
				
			} else {
				return false;
			}
		}

		
		/**
		 * Tests to see if the blog_id a valid blog_id on the network
		 *
		 * TODO - Long desc
		 *
		 * @author Mark Simchock <mark.simchock@alchemyunited.com>
		 * 
		 * @param 	int		$int_blog_id	an integer of how many problems happened.
		 *                    				Indent to the description's starting point
		 *                     				for long ones.
		 *
		 * @return bool		false			if not multisite or non-int is passed in for blog_id
		 * @return bool		true			if multisite and blog_id is legit
		 * @return int		blog_id			if blog_id is not valid return the blog_id			            
		 */
		 
		/*
		* - - Change Log - - 
		*
		*/		
		static public function blog_id_pass($int_blog_id = NULL){
		
			if ( is_multisite() && is_int($int_blog_id) ) {
			
				$mix = get_blog_details( $int_blog_id );
				if ( $mix === false ) {
					return $int_blog_id;
				} else {
					return true;
				}
			} else {
				return false;
			}
		}
		
		/** 
		 * Starting from the left, replaces everything up to and including the needle with ''
		 *
		 * You can pass in the replace string but '' seemed like the most userful default
		 *
		 * @author Mark Simchock <mark.simchock@alchemyunited.com>
		 * 
		 * @param 	str		$str_needle		the string that anchors the up to and including replace
	     * @param 	str		$str_haystack	the string we're working on
		 * @param 	str		$str_replace	(optional)	the replacement string. default = ''
		 *
		 * $return 	str						returns the reworked string
		 */
		 
		/*
		* - - Change Log - - 
		*
		*/
		static public function lremove($str_needle = NULL, $str_haystack = NULL, $str_replace = ''){
		
			if ( $str_needle != NULL && $str_haystack != NULL && is_string($str_replace) ){
				$str_new_haystack = preg_replace("!^.*" . $str_needle . "!s", $str_replace, $str_haystack);
			
				return $str_new_haystack;
			}
			return;
		}
			
	
		/**
		 * Takes an associative array of value pairs key => bool and returns a normal array of just the keys that have a value of true
		 *		
		 * @author Mark Simchock <mark.simchock@alchemyunited.com>
		 * 
		 * @param 	array	$arr_args		an associative array (or so we'll presume for the moment)
		 *                     		
		 * @return	array	- regular array 
		 */
		 
		/*
		 * == CHANGE LOG == 
		 *
		 */
		static public function array_keys_for_value_true( $arr = array() ){
		
			// if we're not passed an array, we're going to return an empty array. 
			if ( ! is_array($arr) ){
				return array();
			}
			
			$arr_new = array();
			foreach ($arr as $key => $bool_value){
			
				if ( $bool_value === true ){
					$arr_new[] = $key;
				}
			}
			return $arr_new;
		}
		
		
		
		/**
		 * Tests to see if an array is isset() && is_array() && !empty(). Returns true, else false
		 *		
		 * @author Mark Simchock <mark.simchock@alchemyunited.com>
		 * 
		 * @param 	array	$arr_args		an array (or so we'll presume for the moment)
		 *                     		
		 * @return	bool   
		 */
		 
		/*
		 * == CHANGE LOG == 
		 *
		 */
		static public function array_pass($arr = ''){
			if ( isset($arr) && is_array($arr) && ! empty($arr) ){
				return true;
			}
			return false;
		}

		
		
		/**
		 * Tests to see if an array[key] is isset() && is_array() && !empty(). Returns true, else false. That is, is there a non-empty array within an array.
		 *		
		 * @author Mark Simchock <mark.simchock@alchemyunited.com>
		 * 
		 * @param 	array	$arr_args		an array (or so we'll presume for the moment)
		 *                     		
		 * @return	bool   
		 */
		 
		/*
		 * == CHANGE LOG == 
		 *
		 */
		static public function array_key_pass($arr = array(), $str_key = ''){
			if ( isset($arr[$str_key]) && is_array($arr[$str_key]) && !empty($arr[$str_key]) ){
				return true;
			}
			return false;
		}		
		
		
		/**
		 * Returns the *path* to there WP is installed
		 *
		 * TODO 
		 *
		 * @author WP Thumb plugin
		 *                     		
		 * @return string TODO      
		 */
		static public function home_path() {
			return str_replace( str_replace( home_url(), '', site_url() ), '', ABSPATH );
		}
		
	} // close class
} // close if class exists
			
?>