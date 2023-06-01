<?php
/*
Plugin Name: DonasiAja
Description: DonasiAja adalah plugin donasi yang mudah & simple. Cocok digunakan untuk Penggalangan Dana, Lembaga Amal, Yayasan, Pesantren, Masjid, dan lain-lain.
Version: 1.6.0.1
Author: Sinkronus Co, modified by Josh
Author URI: https://juicepie.id
*/

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

define('ROOTDIR_DNA', plugin_dir_path(__FILE__));

define('DJA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * function list ====
 * dja_options_install
 * dja_options_install_data
 * deactivate_plugin_dna
 * load_custom_dja_admin_style
 * enqueue_donasiaja_admin_style
 * register_donasiaja_script
 * load_custom_dja_style_carousel
 * custom_style_donasiaja
 * custom_donasiaja_js
 * parent_theme_setup
 * donasiaja_shortcode_func
 * donasiaja_campaign_shortcode_func
 * 
 */
require_once ROOTDIR_DNA . 'plugins-data/data-1.php';

/*
function register_donasiaja_footer_script() {
    global $wpdb;
    donasiaja_global_vars();
    $plugin_version = $GLOBALS['donasiaja_vars']['plugin_version'];
    $table_name = $wpdb->prefix . "dja_settings";

    // Settings
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name.' where type="jquery_on" or type="jquery_custom" ORDER BY id ASC');
    $jquery_on = $query_settings[0]->data;
    $jquery_custom = $query_settings[1]->data;

    if($jquery_on=='0' || $jquery_on==null){
    	// no JQuery
    }elseif($jquery_on=='2'){
    	if($jquery_custom=='0' || $jquery_custom==null){
    		// no JQuery
    	}else{
    		echo '<script src="https://code.jquery.com/jquery-'.$jquery_custom.'.min.js"></script>';
    	}
    }else{
    	echo '<script src="'.plugin_dir_url( __FILE__ ) . 'assets/js/jquery.min.js?ver='.$plugin_version.'"></script>';
    }

    $query_settings = $wpdb->get_results('SELECT data from '.$table_name.' where type="theme_color" ORDER BY id ASC');
    $general_theme_color    = json_decode($query_settings[0]->data, true);
    $theme_color 			= $general_theme_color['color'][0];
	$progressbar_color  	= $general_theme_color['color'][1];
	$button_color 			= $general_theme_color['color'][2];
	$button_color2 			= $general_theme_color['color'][3];

    if($theme_color!=''){
    	echo '<style>.donasiaja_search_box .control .btn-material{background: '.$theme_color.';}</style>';
    }
	
}
// add_action('init', 'register_donasiaja_script');
add_action( 'wp_footer', 'register_donasiaja_footer_script');
*/


/**
 * Function List ====
 * donasiaja_readtime
 * donasiaja_load_campaign
 * donasiaja_load_campaign_search
 * action_get_the_user_attachments
 * filter_get_the_user_attachments
 * djafunction_publish_campaign
 * donasiaja_getIP
 * donasiaja_getOS
 * donasiaja_getBrowser
 * donasiaja_getMobDesktop
 * shortDisplayNumber
 * djafunction_set_love
 * getBaseUrl
 * paymentCode
 * 
 */
require_once ROOTDIR_DNA . 'plugins-data/data-2.php';


/**
 * Function List ====
 * djafunction_submit_donasi
 * joshfunction_waba_order_baru
 * joshfunction_manual_submitdonasi
 * joshfunction_input_leads
 * joshfunction_submit_leads
 * djafunction_regaff_fundraiser
 * donasiaja_send2tg
 * josh_send_email
 * djafunction_send_test_telegram
 * djafunction_send_test_email
 * djafunction_send_test_wanotif
 * djafunction_status_wanotif
 * 
 */
require_once ROOTDIR_DNA . 'plugins-data/data-3.php';


/**
 * Function List ====
 * djafunction_get_donasi
 * djafunction_get_donasi_confirmation
 * donasiaja_upload_confirmation
 * djafunction_update_confirmation
 * joshfunction_waba_slip
 * add_phone_number_field
 * djafunction_get_mydonasi
 * djafunction_update_donasi
 * djafunction_upload_ss
 * djafunction_update_fundraising_settings
 * djafunction_update_status_payment
 * djafunction_download_donasi
 * download_donasi
 * upload_donasi
 * download_template_excel
 * djafunction_del_donasi
 * djafunction_del_confirmation
 * 
 */
require_once ROOTDIR_DNA . 'plugins-data/data-4.php';


/**
 * Function List ===
 * djafunction_update_csrotator
 * djafunction_update_campaign
 * djafunction_update_info
 * djafunction_delete_campaign
 * djafunction_clone_campaign
 * djafunction_delete_info
 * djafunction_add_update_info
 * djafunction_update_text_followup
 * djafunction_send_wa
 * djafunction_set_payment
 * djafunction_clone_shortcode
 * djafunction_delete_shortcode
 * djafunction_update_shortcode
 * djafunction_add_shortcode
 * djafunction_login_user
 * djafunction_register_user
 * djafunction_send_link
 * 
 */
require_once ROOTDIR_DNA . 'plugins-data/data-5.php';


/**
 * Function List====
 * djafunction_reset_pass
 * djafunction_add_user
 * djafunction_delete_usernya
 * djafunction_update_user
 * djafunction_update_profile
 * djafunction_update_akun
 * djafunction_update_akun_bank
 * djafunction_update_pp_img
 * djafunction_update_pp_img_user
 * djafunction_upload_ktp
 * djafunction_upload_app_logo
 * djafunction_update_themes_settings
 * djafunction_update_form_settings
 * djafunction_del_category
 * djafunction_add_new_category
 * djafunction_save_category
 * djafunction_update_category_private
 * get_data_campaign
 * djafunction_update_socialproof
 * djafunction_update_general_settings
 * djafunction_update_payment_settings
 * djafunction_update_ipaymu_settings
 * djafunction_update_tripay_settings
 * djafunction_update_midtrans_settings
 * djafunction_update_moota_settings
 * djafunction_update_wanotif_settings
 * djafunction_update_telegram_settings
 * djafunction_update_email_settings
 * djafunction_upload_ktp_selfie
 * djafunction_upload_legalitas
 * djafunction_submit_verification
 * djafunction_update_cover_img
 * djafunction_load_data_donatur
 * djafunction_load_list_donatur
 * djafunction_load_fundraiser
 * djafunction_load_doa_donatur
 * 
 */
require_once ROOTDIR_DNA . 'plugins-data/data-6.php';


/**
 * Function List ===
 * djafunction_update_password
 * djafunction_update_password_user
 * josh_table_slip
 * joshfunction_table_change
 * joshfunction_table_delete
 * josh_imgupload
 * josh_crm_table
 * josh_crm_table_2
 * josh_crm_chart
 * dja_get_data_donasi
 * dja_handling_character
 * dja_get_provinsi
 * dja_get_kabkota
 * dja_get_kecamatan
 * djafunction_activate_apikey
 * djafunction_deactivate_apikey
 * check_verified_campaign
 * check_verified_dashboard
 * dja_auto_login_new_user
 * dja_whatsapp_format
 * d_randomString
 * d_randomBigString
 * d_formatUri
 * djaPhoneFormat
 * _check_is_curl_installed
 * 
 */
require_once ROOTDIR_DNA . 'plugins-data/data-7.php';

require 'library/f_additional_function.php';



/**
 * Function List ===
 * time_donation (donasiaja_readtime)
 * dja_human_time (humanReadtime)
 * jsh_timeInterval (TimeInterval)
 * redirect_logged_in_user
 * angka_terbilang
 * donasiaja_url_handler
 * donasiaja_remove_profile_menu
 * donasiaja_admin_default_page
 * check_license
 * donasiaja_global_vars
 * jh_settings_waba
 * jh_testing_waba
 */
require_once ROOTDIR_DNA . 'plugins-data/data-8.php';


add_action('init', function() {

	global $wpdb;
	global $pagenow;

	$table_name = $wpdb->prefix . "dja_settings";

    $query_settings = $wpdb->get_results('SELECT data from '.$table_name.' where type="login_setting" ORDER BY id ASC');
    $login_setting 		= $query_settings[0]->data;

    if($login_setting=='1'){
		if( $pagenow == 'wp-login.php' ){
			redirect_logged_in_user();
		}
	}

	if(isset($_GET['donasiaja'])){
	  	if($_GET['donasiaja']=='print_kuitansi'){
	  		require_once(ROOTDIR_DNA . 'admin/f_print_kuitansi.php');
	  		exit();
	  	}else{
	  		echo 'Please back on your WP Admin Dashboard!';
	  		exit();
	  	}
  	}

});



do_action( 'admin_page_access_denied', function() {
	global $wpdb;
		$table_name = $wpdb->prefix . "dja_settings";

    	$query_settings = $wpdb->get_results('SELECT data from '.$table_name.' where type="login_setting" or type="page_login" ORDER BY id ASC');
    	$login_setting 	= $query_settings[0]->data;
	    $page_login 	= $query_settings[1]->data;

	if($login_setting=='1'){
    	die(wp_redirect( home_url().'/'.$page_login.'/' ));
    }else{
    	die(wp_redirect( home_url().'/wp-login.php/' ));
    }	

} );


// require 'admin/plugin-update-checker/plugin-update-checker.php';
// $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
// 	'https://member.donasiaja.id/files/downloads/donasiaja/details.json',
// 	__FILE__, //Full path to the main plugin file or functions.php.
// 	'donasiaja'
// );

/**
 * Josh Cron Job
 */
add_filter( 'cron_schedules', function( $schedules ) {
    if( ! isset( $schedules['josh_one_minute'] ) ) {
        $schedules['josh_one_minute'] = array(
            'interval'  => 60,
            'display'   => esc_html__( 'Every one minute' )
        );
        return $schedules;
    }
} );

if( ! wp_next_scheduled( 'josh_capi_cron' ) ) {
    
    $josh_cron = wp_schedule_event( 1680529058, 'josh_one_minute', 'josh_capi_cron' );
    
}

add_action( 'josh_capi_cron', array('Send_API', 'begin') );


//menu items
add_action('admin_menu','donasiaja_modifymenu');
function donasiaja_modifymenu() {

	donasiaja_global_vars();
	global $wpdb;
	$cap = get_user_meta( wp_get_current_user()->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
    $roles = array_keys((array)$cap);
    $role = $roles[0];
    $plugin_license = strtoupper($GLOBALS['donasiaja_vars']['plugin_license']);

	$table_name = $wpdb->prefix . "dja_users";
	$table_name2 = $wpdb->prefix . "dja_settings";
	$table_name3 = $wpdb->prefix . "dja_campaign";

	$query_settings = $wpdb->get_results('SELECT data from '.$table_name2.' where type="campaign_setting" or type="main_menu_name" or type="fundraiser_on" ORDER BY id ASC');
    $campaign_setting = $query_settings[0]->data;
    $main_menu_name = $query_settings[1]->data;
    $fundraiser_on 	= $query_settings[2]->data;

	$rows_campaign = $wpdb->get_results("SELECT COUNT(id) as jumlah from $table_name3 where publish_status='2' ORDER BY id DESC")[0];
	$jumlah_pending = $rows_campaign->jumlah;

	$need_review = '';
	if($jumlah_pending >= 1){
		$need_review = ' ('.$jumlah_pending.')';
	}

	if($role=='donatur'){

		if($campaign_setting=='1'){

			//this is the main item for the menu
			add_menu_page('Data Donasi | DonasiAja ', //page title
			$main_menu_name, //menu title
			'read', //capabilities
			'donasiaja_dashboard', //menu slug
			'donasiaja_dashboard', //function
			WP_PLUGIN_URL.'/donasiaja/admin/icons/donasiaja3.ico',
		    '46'
			);

			// this submenu is for Dashboard
			add_submenu_page('donasiaja_dashboard', //parent slug
			'Dashboard | DonasiAja', //page title
			'Dashboard', //menu title
			'read', //capability
			'donasiaja_dashboard', //menu slug
			'donasiaja_dashboard'); //function
			
            // this submenu is for Campaign
			add_submenu_page('donasiaja_dashboard', //parent slug
			'Campaigns | DonasiAja', //page title
			'Campaigns', //menu title
			'read', //capability
			'donasiaja_data_campaign', //menu slug
			'donasiaja_data_campaign'); //function

            // this submenu is for Fundraiser (Fundraising)
			if($fundraiser_on=='1'){
				if($plugin_license=='ULTIMATE') {
					add_submenu_page('donasiaja_dashboard', //parent slug
					'Fundraising | DonasiAja', //page title
					'Fundraising', //menu title
					'read', //capability
					'donasiaja_data_fundraising', //menu slug
					'donasiaja_data_fundraising'); //function
				}
			}

            // this menu is for My Donate
			add_submenu_page('donasiaja_dashboard', //parent slug
			'My Donate | DonasiAja', //page title
			'My Donate', //menu title
			'read', //capability
			'donasiaja_mydonate', //menu slug
			'donasiaja_mydonate'); //function

            // this menu is for My Profile
			add_submenu_page('donasiaja_dashboard', //parent slug
			'My Profile | DonasiAja', //page title
			'My Profile', //menu title
			'read', //capability
			'donasiaja_myprofile', //menu slug
			'donasiaja_myprofile'); //function

		}else{

			//this is the main item for the menu
			add_menu_page('Data Donasi | DonasiAja ', //page title
			'My Donate', //menu title
			'read', //capabilities
			'donasiaja_dashboard', //menu slug
			'donasiaja_mydonate', //function
			WP_PLUGIN_URL.'/donasiaja/admin/icons/donasiaja3.ico',
		    '46'
			);

			if($fundraiser_on=='1'){
				if($plugin_license=='ULTIMATE') {
					add_submenu_page('donasiaja_dashboard', //parent slug
					'Fundraising | DonasiAja', //page title
					'Fundraising', //menu title
					'read', //capability
					'donasiaja_data_fundraising', //menu slug
					'donasiaja_data_fundraising'); //function
				}
			}

			//this submenu is HIDDEN
			add_submenu_page('donasiaja_dashboard', //parent slug
			'My Profile | DonasiAja', //page title
			'My Profile', //menu title
			'read', //capability
			'donasiaja_myprofile', //menu slug
			'donasiaja_myprofile'); //function

		}

	}else{

		//this is the main item for the menu
		add_menu_page('Data Donasi | DonasiAja ', //page title
		$main_menu_name, //menu title
		'read', //capabilities
		'donasiaja_dashboard', //menu slug
		'donasiaja_dashboard', //function
		WP_PLUGIN_URL.'/donasiaja/admin/icons/donasiaja3.ico',
	    '46'
		);

		add_submenu_page('donasiaja_dashboard', //parent slug
		'Dashboard | DonasiAja', //page title
		'Dashboard', //menu title
		'read', //capability
		'donasiaja_dashboard', //menu slug
		'donasiaja_dashboard'); //function

        add_submenu_page('donasiaja_dashboard', //parent slug
		'Dashboard Duta | YMPB', //page title
		'Dashboard Duta', //menu title
		'read', //capability
		'josh-dash-duta', //menu slug
		'josh_dashboard_duta'); //function

		$jh_role = wp_get_current_user();
		$allowed_roles = array('administrator', 'cs');
		// if(in_array($jh_role->roles, $allowed_roles) || $jh_role->ID === 12 ) {
		if($jh_role->roles[0] == 'administrator' || $jh_role->roles[0] === 'cs' || $jh_role->ID === 12 ) {
			
			add_submenu_page(
				'donasiaja_dashboard',
				'Customer Relationship Management (CRM)',
				'CRM',
				'read',
				'josh-crm',
				'josh_crm_2'
			);
		}

		if(wp_get_current_user()->ID == 1) {
			add_submenu_page(
				'donasiaja_dashboard',
				'Customer Relationship Management (CRM) - Develop',
				'CRM Dev',
				'read',
				'josh-crm-2',
				'josh_crm'
			);
		}
		
		if($role!='cs'){
			//this submenu is HIDDEN
			add_submenu_page('donasiaja_dashboard', //parent slug
			'Campaigns | DonasiAja', //page title
			'Campaigns'.$need_review, //menu title
			'read', //capability
			'donasiaja_data_campaign', //menu slug
			'donasiaja_data_campaign'); //function
		}
	}


    // jika bukan Donatur
    if($role!='donatur'){

    	$user_on_review = $wpdb->get_results('SELECT id from '.$table_name.' where user_verification="2"');

    	if($user_on_review!=null){
    		$jumlah_on_review = '('.count($user_on_review).')';
    	}else{
    		$jumlah_on_review = '';
    	}

    	if($role=='administrator' || $role=='cs'){
    		if($fundraiser_on=='1'){
    			if($plugin_license=='ULTIMATE') {
					add_submenu_page('donasiaja_dashboard', //parent slug
					'Fundraising | DonasiAja', //page title
					'Fundraising', //menu title
					'read', //capability
					'donasiaja_data_fundraising', //menu slug
					'donasiaja_data_fundraising'); //function
				}
			}
		}
        
   		if($role!='cs'){
		//this submenu is HIDDEN
		add_submenu_page('donasiaja_dashboard', //parent slug
		'Shortcodes | DonasiAja', //page title
		'Shortcodes', //menu title
		'read', //capability
		'donasiaja_data_shortcodes', //menu slug
		'donasiaja_data_shortcodes'); //function
		}

		if($role=='administrator'){
		//this submenu is HIDDEN
		add_submenu_page('donasiaja_dashboard', //parent slug
		'Members | DonasiAja', //page title
		'Members '.$jumlah_on_review.'', //menu title
		'read', //capability
		'donasiaja_data_members', //menu slug
		'donasiaja_data_members'); //function

		}

	}

	if($role!='donatur'){
		// //this submenu is HIDDEN
		add_submenu_page('donasiaja_dashboard', //parent slug
		'My Donate | DonasiAja', //page title
		'My Donate', //menu title
		'read', //capability
		'donasiaja_mydonate', //menu slug
		'donasiaja_mydonate'); //function

		//this submenu is HIDDEN
		add_submenu_page('donasiaja_dashboard', //parent slug
		'My Profile | DonasiAja', //page title
		'My Profile', //menu title
		'read', //capability
		'donasiaja_myprofile', //menu slug
		'donasiaja_myprofile'); //function
	}

	// jika bukan Donatur
    if($role=='administrator'){

		//this submenu is HIDDEN
		add_submenu_page('donasiaja_dashboard', //parent slug
		'Settings | DonasiAja', //page title
		'Settings', //menu title
		'read', //capability
		'donasiaja_settings', //menu slug
		'donasiaja_settings'); //function

        //this submenu is HIDDEN
		add_submenu_page('donasiaja_dashboard', //parent slug
		'CS Dashboard | YMPB', //page title
		'CS Dashboard', //menu title
		'read', //capability
		'josh_dash_cs', //menu slug
		'josh_dash_cs'); //function
		
		add_submenu_page('donasiaja_dashboard', //parent slug
		'Josh CS | DonasiAja', //page title
		'Josh CS', //menu title
		'read', //capability
		'josh_cs', //menu slug
		'josh_cs'); //function

		add_submenu_page('donasiaja_dashboard', //parent slug
		'Simulator CS | DonasiAja', //page title
		'Simulator', //menu title
		'read', //capability
		'josh_sim', //menu slug
		'josh_sim'); //function

		add_submenu_page('donasiaja_dashboard', //parent slug
		'Update Date SQL | DonasiAja', //page title
		'Update Date SQL', //menu title
		'read', //capability
		'josh_sql', //menu slug
		'josh_sql'); //function

		add_submenu_page('donasiaja_dashboard', //parent slug
		'Anti Duplicate Order | DonasiAja', //page title
		'Anti-duplicate Order', //menu title
		'read', //capability
		'josh_noduplicate', //menu slug
		'josh_noduplicate'); //function

		add_submenu_page('donasiaja_dashboard', //parent slug
		'JSON Practice | Josh', //page title
		'JSON', //menu title
		'read', //capability
		'josh-json', //menu slug
		'josh_json'); //function

		add_submenu_page('donasiaja_dashboard', //parent slug
		'PHP Parameters | Josh', //page title
		'PHP URL Param', //menu title
		'read', //capability
		'josh-param', //menu slug
		'josh_phpparam'); //function

		add_submenu_page('donasiaja_dashboard', //parent slug
		'Notification Telegram | Josh', //page title
		'Notif Telegram', //menu title
		'read', //capability
		'josh-notif', //menu slug
		'joshnotif'); //function

		add_submenu_page('donasiaja_dashboard', //parent slug
		'Read DB Error | Josh', //page title
		'DataBase Eror', //menu title
		'read', //capability
		'josh-db', //menu slug
		'joshdb'); //function

		add_submenu_page('donasiaja_dashboard', //parent slug
		'Manual Input | Josh', //page title
		'Manual Input', //menu title
		'read', //capability
		'manual-input', //menu slug
		'manualinput'); //function

		add_submenu_page('donasiaja_dashboard', //parent slug
		'New Plugins | Josh', //page title
		'New Plugins', //menu title
		'read', //capability
		'new-plugins', //menu slug
		'josh_plugins'); //function

		add_submenu_page('donasiaja_dashboard', //parent slug
		'Table | Josh', //page title
		'Slip Table', //menu title
		'read', //capability
		'table', //menu slug
		'josh_table', //function
		'2');	//position

	}

	if( wp_get_current_user()->ID === 12) {
		add_submenu_page('donasiaja_dashboard', //parent slug
		'Table | Josh', //page title
		'Slip Table', //menu title
		'read', //capability
		'table', //menu slug
		'josh_table', //function
		'2');	//position
	}
	
	//this submenu is HIDDEN, however, we need to add it anyways
	add_submenu_page(null, //parent slug
	'Update School', //page title
	'Update', //menu title
	'read', //capability
	'donasiaja_update', //menu slug
	'donasiaja_update'); //function

}

require_once(ROOTDIR_DNA . 'admin/f_donasiaja_dashboard2.php');
require_once(ROOTDIR_DNA . 'admin/f_donasiaja_dashboard_duta.php');
require_once(ROOTDIR_DNA . 'admin/f_donasiaja_data_campaign.php');
require_once(ROOTDIR_DNA . 'admin/f_donasiaja_data_fundraising.php');
require_once(ROOTDIR_DNA . 'admin/f_donasiaja_data_shortcodes.php');
require_once(ROOTDIR_DNA . 'admin/f_donasiaja_data_members.php');
require_once(ROOTDIR_DNA . 'admin/f_donasiaja_myprofile.php');
require_once(ROOTDIR_DNA . 'admin/f_donasiaja_mydonate.php');
require_once(ROOTDIR_DNA . 'admin/f_donasiaja_settings.php');
require_once(ROOTDIR_DNA . 'admin/f_josh_donatur.php');
require_once(ROOTDIR_DNA . 'admin/f_josh_simulator.php');
require_once(ROOTDIR_DNA . 'admin/f_josh_sql.php');
require_once(ROOTDIR_DNA . 'admin/f_josh_dash_cs.php');
require_once(ROOTDIR_DNA . 'admin/f_josh_noduplicate.php');
require_once(ROOTDIR_DNA . 'josh-json.php');
require_once(ROOTDIR_DNA . 'josh-utm.php');
require_once(ROOTDIR_DNA . 'josh-notif.php');
require_once(ROOTDIR_DNA . 'josh-db.php');
require_once(ROOTDIR_DNA . 'josh-manual.php');
require_once(ROOTDIR_DNA . 'josh-newplugins.php');
require_once(ROOTDIR_DNA . 'josh-table.php');
require_once(ROOTDIR_DNA . 'core/public-function/caller.php');
require_once(ROOTDIR_DNA . 'core/api/Send_API.php');
require_once ROOTDIR_DNA . 'josh-crm.php';
require_once ROOTDIR_DNA . 'josh-crm-2.php';