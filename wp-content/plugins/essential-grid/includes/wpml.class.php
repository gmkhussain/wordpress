<?php
	class Essential_Grid_Wpml{
		
		/**
		 * 
		 * true / false if the wpml plugin exists
		 */
		public static function is_wpml_exists(){
			
			if(class_exists("SitePress"))
				return(true);
			else
				return(false);
		}
		
		/**
		 * valdiate that wpml exists
		 */
		private static function validate_wpml_exists(){
			if(!self::is_wpml_exists())
				Essential_Grid_Base::throw_error(__("The wpml plugin don't exists", EG_TEXTDOMAIN));
		}
		
		/**
		 * get current language
		 */
		public static function get_current_lang(){
			self::validate_wpml_exists();
			$wpml = new SitePress();

			if(is_admin())
				$lang = $wpml->get_default_language();
			else
				$lang = self::get_current_lang_code();
			
			return($lang);
		}
		
		/**
		 * get current language code
		 */
		public static function get_current_lang_code(){
			$langTag = ICL_LANGUAGE_CODE;

			return($langTag);
		}
		
		/**
		 * disable the language filtering
		 */
		public static function disable_language_filtering(){
			self::validate_wpml_exists();
			global $sitepress;
			remove_filter('terms_clauses', array($sitepress, 'terms_clauses'));
		}
		
		/**
		 * enable the language filtering
		 */
		public static function enable_language_filtering(){
			self::validate_wpml_exists();
			global $sitepress;
			add_filter('terms_clauses', array($sitepress, 'terms_clauses'));
		}
		
		
		
		/**
		 * get default language id of tag / category
		 */
		public static function get_id_from_lang_id($id, $type = 'category'){
			if(self::is_wpml_exists()){
				
				$lang = self::get_current_lang_code();
				$real_id = icl_object_id($id, $type, true, $lang);
				
				return $real_id;
				
			}else{
			
				return $id;
			}
		}
		
		
		/**
		 * get current language id of tag / category
		 */
		public static function get_lang_id_from_id($id, $type = 'category'){
			if(self::is_wpml_exists()){
				
				$real_id = icl_object_id($id, $type, true);
				
				return $real_id;
				
			}else{
			
				return $id;
			}
		}
		
		
		/**
		 * change cat / tag ids in String to current language
		 */
		public static function change_cat_id_by_lang($catID, $type = 'category'){
			if(self::is_wpml_exists()){
				
				$real_id = icl_object_id($catID, $type, true);
				
				return $real_id;
				
			}else{
			
				return $catID;
			}
		}
		
		
	}