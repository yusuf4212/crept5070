<?php
function dja_options_install() {
    global $wpdb;

    $table_name = $wpdb->prefix . "dja_settings";
    $table_name2 = $wpdb->prefix . "dja_campaign";
    $table_name3 = $wpdb->prefix . "dja_campaign_update";

    $table_name4 = $wpdb->prefix . "dja_category";
    $table_name5 = $wpdb->prefix . "dja_donate";

    $table_name6 = $wpdb->prefix . "dja_aff_code";
    $table_name7 = $wpdb->prefix . "dja_love";

    $table_name8 = $wpdb->prefix . "dja_payment_list";
    $table_name9 = $wpdb->prefix . "dja_register";

    $table_name10 = $wpdb->prefix . "dja_shortcode";
    $table_name11 = $wpdb->prefix . "dja_users";

    $table_name12 = $wpdb->prefix . "dja_user_logs";
    $table_name13 = $wpdb->prefix . "dja_user_type";

    $table_name14 = $wpdb->prefix . "dja_verification_details";
    $table_name15 = $wpdb->prefix . "dja_verification_status";

    $table_name16 = $wpdb->prefix . "dja_payment_log";
    $table_name17 = $wpdb->prefix . "dja_password_reset";
    $table_name18 = $wpdb->prefix . "dja_password_reset_log";

    $table_name19 = $wpdb->prefix . "dja_aff_click";
    $table_name20 = $wpdb->prefix . "dja_aff_submit";
    $table_name21 = $wpdb->prefix . "dja_aff_payout";


    $charset_collate = $wpdb->get_charset_collate();
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $sql = "CREATE TABLE $table_name (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  type varchar(32) DEFAULT NULL,
		  data text DEFAULT NULL,
		  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);


	$sql = "CREATE TABLE $table_name2 (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  campaign_id varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  title text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  slug text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  target decimal(21,0) DEFAULT NULL,
		  image_url text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  information longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  location_name varchar(240) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  location_gmaps text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  publish_status int(1) DEFAULT NULL,
		  reached_status int(1) DEFAULT NULL,
		  end_date datetime DEFAULT NULL,
		  form_base int(1) DEFAULT NULL,
		  form_type varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  packaged int(12) DEFAULT NULL,
		  packaged_title varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  currency varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  category_id int(2) DEFAULT NULL,
		  user_id bigint(20) DEFAULT NULL,
		  fundraiser_id bigint(20) DEFAULT NULL,
		  payment_status int(1) DEFAULT NULL,
		  method_status text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  bank_account text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  form_status int(1) DEFAULT NULL,
		  form_text text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  unique_number_setting int(1) DEFAULT NULL,
		  unique_number_value text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  notification_status int(1) DEFAULT NULL,
		  wanotif_message text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  pixel_status int(1) DEFAULT NULL,
		  fb_pixel varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  fb_event text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  pengeluaran_setting int(1) DEFAULT NULL,
		  pengeluaran_title text,
		  gtm_status int(1) DEFAULT NULL,
		  gtm_id varchar(30) DEFAULT NULL,
		  tiktok_status int(1) DEFAULT NULL,
		  tiktok_pixel varchar(30) DEFAULT NULL,
		  socialproof int(1) DEFAULT NULL,
		  socialproof_text varchar(250) DEFAULT NULL,
		  socialproof_position varchar(20) DEFAULT NULL,
		  zakat_setting int(1) DEFAULT NULL,
  		  zakat_percent decimal(10,2) DEFAULT NULL,
  		  fundraiser_setting int(1) DEFAULT NULL,
		  fundraiser_on int(1) DEFAULT NULL,
		  fundraiser_text text,
		  fundraiser_button text,
		  fundraiser_commission_on int(1) DEFAULT NULL,
		  fundraiser_commission_type int(1) DEFAULT NULL,
		  fundraiser_commission_percent decimal(10,2) DEFAULT NULL,
		  fundraiser_commission_fixed int(10) DEFAULT NULL,
		  additional_info text,
		  additional_formula text,
		  additional_field text,
		  custom_field_setting text,
		  general_status int(1) DEFAULT NULL,
		  allocation_title varchar(60) DEFAULT NULL,
		  allocation_others_title varchar(60) DEFAULT NULL,
		  donatur_name int(1) DEFAULT NULL,
		  donatur_others_name varchar(60) DEFAULT NULL,
		  home_icon_url text,
		  back_icon_url text,
		  minimum_donate int(10) DEFAULT NULL,
		  opt_nominal text,
		  cs_rotator text,
		  wanotif_device int(1) DEFAULT NULL,
		  created_at datetime DEFAULT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);


    $sql = "CREATE TABLE $table_name3 (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  campaign_id varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  title text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  information longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  user_id bigint(20) DEFAULT NULL,
		  created_at datetime DEFAULT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);


    $sql = "CREATE TABLE $table_name4 (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  category varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  private_category int(1) DEFAULT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);

    $sql = "CREATE TABLE $table_name5 (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  campaign_id varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  invoice_id varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_id bigint(20) DEFAULT NULL,
		  name varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  whatsapp varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  email varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  comment text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  anonim int(1) DEFAULT NULL,
		  payment_method varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  payment_code varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  payment_number text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  payment_qrcode text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  payment_account varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  unique_number decimal(5,0) DEFAULT NULL,
		  nominal decimal(21,0) DEFAULT NULL,
		  main_donate decimal(21,0) DEFAULT NULL,
		  info_donate text,
		  status int(1) DEFAULT NULL,
		  cs_id int(14) DEFAULT NULL,
		  f0 int(1) DEFAULT NULL,
		  f1 int(1) DEFAULT NULL,
		  f2 int(1) DEFAULT NULL,
		  f3 int(1) DEFAULT NULL,
		  f4 int(1) DEFAULT NULL,
		  f5 int(1) DEFAULT NULL,
		  payment_trx_id varchar(64) DEFAULT NULL,
		  payment_at datetime DEFAULT NULL,
		  process_by varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  deeplink_url text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  payment_gateway varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  img_confirmation_url text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  img_confirmation_status int(1) DEFAULT NULL,
		  img_confirmation_date datetime DEFAULT NULL,
		  created_at datetime DEFAULT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);

	// Aff Fundraiser
    $sql = "CREATE TABLE $table_name6 (
		  id int(15) NOT NULL AUTO_INCREMENT,
		  campaign_id varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_id int(15) DEFAULT NULL,
		  aff_code varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  created_at datetime DEFAULT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);

    $sql = "CREATE TABLE $table_name7 (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  campaign_id varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  donate_id int(11) DEFAULT NULL,
		  user_id bigint(20) DEFAULT NULL,
		  love int(3) DEFAULT NULL,
		  ip varchar(21) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  os varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  browser varchar(21) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  mobdesktop varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  created_at datetime DEFAULT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);


    $sql = "CREATE TABLE $table_name8 (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  code varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  name varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);


    $sql = "CREATE TABLE $table_name9 (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  r_nama_lengkap varchar(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  r_email varchar(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  r_whatsapp varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  r_password varchar(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  r_code text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  r_status int(1) DEFAULT NULL,
		  created_at datetime DEFAULT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);

	

    $sql = "CREATE TABLE $table_name10 (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  s_id varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  s_name text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  s_category varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  s_style varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  s_show int(3) DEFAULT NULL,
		  s_loadmore int(3) DEFAULT NULL,
		  s_button_on int(1) DEFAULT NULL,
		  s_button_text varchar(30) DEFAULT NULL,
		  s_data_load int(2) DEFAULT NULL,
		  s_campaign text,
		  created_at datetime DEFAULT NULL,
		  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);


    $sql = "CREATE TABLE $table_name11 (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  user_id bigint(20) DEFAULT NULL,
		  user_randid varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_type varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'org,personal',
		  user_verification int(1) DEFAULT NULL,
		  user_bio text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  user_wa varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_provinsi varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_kabkota varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_kecamatan varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_provinsi_id int(4) DEFAULT NULL,
		  user_kabkota_id int(4) DEFAULT NULL,
		  user_kecamatan_id int(4) DEFAULT NULL,
		  user_alamat text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  user_bank_name varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_bank_no varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_bank_an varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_pp_img text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  user_cover_img text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  user_anonim_f int(1) DEFAULT NULL,
		  user_commission_f int(3) DEFAULT NULL,
		  created_at datetime DEFAULT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);


    $sql = "CREATE TABLE $table_name12 (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  del_by int(12) DEFAULT NULL,
		  user_login varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_email varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_id bigint(20) DEFAULT NULL,
		  user_fullname varchar(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_randid varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_type varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_verification int(1) DEFAULT NULL,
		  user_bio text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  user_wa varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_provinsi varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_kabkota varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_kecamatan varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_provinsi_id int(4) DEFAULT NULL,
		  user_kabkota_id int(4) DEFAULT NULL,
		  user_kecamatan_id int(4) DEFAULT NULL,
		  user_alamat text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  user_bank_name varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_bank_no varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_bank_an varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_pp_img text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  user_cover_img text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);


    $sql = "CREATE TABLE $table_name13 (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  type varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  name varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);


    $sql = "CREATE TABLE $table_name14 (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  u_id int(12) DEFAULT NULL,
		  u_nama_lengkap varchar(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  u_email varchar(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  u_whatsapp varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  u_ktp text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  u_ktp_selfie text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  u_jabatan text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  u_nama_ketua varchar(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  u_alamat_lengkap text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  u_program_unggulan text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  u_profile text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  u_legalitas text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);


    $sql = "CREATE TABLE $table_name15 (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  status int(1) DEFAULT NULL,
		  name varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);


	$sql = "CREATE TABLE $table_name16 (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  id_donate int(11) DEFAULT NULL,
		  hit text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  status varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  message text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  log text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  created_at datetime DEFAULT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);


	$sql = "CREATE TABLE $table_name17 (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  reset_email varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
	  	  reset_code varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
	  	  reset_status int(1) DEFAULT NULL,
	  	  ip varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
	  	  os varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
	  	  browser varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  created_at datetime DEFAULT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);


	$sql = "CREATE TABLE $table_name18 (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  reset_email varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
	  	  ip varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
	  	  os varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
	  	  browser varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  created_at datetime DEFAULT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);


	$sql = "CREATE TABLE $table_name19 (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  campaign_id varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  affcode_id varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  ip varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  os varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  browser varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  created_at datetime DEFAULT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);


	$sql = "CREATE TABLE $table_name20 (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  donate_id int(15) DEFAULT NULL,
		  campaign_id varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  affcode_id int(15) DEFAULT NULL,
		  payout_status int(1) DEFAULT NULL,
		  nominal_commission decimal(21,0) DEFAULT NULL,
		  created_at datetime DEFAULT NULL,
		  updated_at datetime DEFAULT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);


	$sql = "CREATE TABLE $table_name21 (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  payout_number varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
		  user_id bigint(20) DEFAULT NULL,
		  aff_submit_id text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  nominal_payout decimal(21,0) DEFAULT NULL,
		  status int(1) DEFAULT NULL,
		  image text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  bank_name text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  bank_no text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  bank_an text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
		  created_at datetime DEFAULT NULL,
		  updated_at timestamp NULL DEFAULT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate; ";
	dbDelta($sql);


    // ADD ROLE Donatur
    $check_donatur = wp_roles()->is_role( 'donatur' );
    if($check_donatur!=1){
    	$role = add_role( 'donatur', 'Donatur', array(
		    'read' => true,
		    'upload_files' => true, // True allows that capability
		));

    }

    // ADD ROLE Donatur
    $check_donatur2 = wp_roles()->is_role( 'cs' );
    if($check_donatur2!=1){
    	$role2 = add_role( 'cs', 'Customer Support', array(
		    'read' => true, // True allows that capability
		    'upload_files' => true, // True allows that capability
		));
    }

	$role = get_role( 'donatur' );
	$role->add_cap( 'upload_files' );
    $role->add_cap( 'read' );

	$role2 = get_role( 'cs' );
	$role2->add_cap( 'upload_files' );
    $role2->add_cap( 'read' );

}
// run the install scripts upon plugin activation
register_activation_hook(__FILE__, 'dja_options_install');

function dja_options_install_data() {
	global $wpdb;
	$table_name 	= $wpdb->prefix . 'dja_settings';
	$table_name2 	= $wpdb->prefix . 'dja_category';
	$table_name3 	= $wpdb->prefix . "dja_payment_list";
	$table_name4 	= $wpdb->prefix . "dja_user_type";
	$table_name5 	= $wpdb->prefix . "dja_verification_status";
	
	// table dja_settings
	$data_array = array(
				'label_tab',
				'max_love',
				'opt_nominal',
				'max_package',
				'form_setting',
				'del_campaign_setting',
				'btn_followup',
				'text_f1',
				'text_f2',
				'text_f3',
				'text_f4',
				'text_f5',
				'text_received',
				'text_received_status',
				'campaign_setting',
				'main_menu_name',
				'logo_url',
				'app_name',
				'login_setting',
				'login_text',
				'register_setting',
				'register_text',
				'page_login',
				'page_register',
				'anonim_text',
				'page_donate',
				'page_typ',
				'apikey_local',
				'apikey_server',
				'theme_color',
				'form_text',
				'unique_number_setting',
				'unique_number_value',
				'currency',
				'payment_setting',
				'bank_account',
				'ipaymu_mode',
				'ipaymu_va',
				'ipaymu_apikey',
				'moota_secret_token',
				'moota_range',
				'wanotif_url',
				'wanotif_apikey',
				'wanotif_message',
				'wanotif_message2',
				'wanotif_on_dashboard',
				'powered_by_setting',
				'form_email_setting',
				'form_comment_setting',
				'fb_pixel',
				'fb_event',
				'telegram_bot_token',
				'telegram_send_to',
				'telegram_on',
				'wanotif_on',
				'wanotif_followup1_on',
				'jquery_on',
				'gtm_id',
				'limitted_donation_button',
				'tripay_mode',
				'tripay_apikey',
				'tripay_privatekey',
				'tripay_merchant',
				'tripay_apikey_sandbox',
				'tripay_privatekey_sandbox',
				'tripay_merchant_sandbox',
				'midtrans_mode',
				'midtrans_serverkey',
				'midtrans_clientkey',
				'midtrans_merchant',
				'midtrans_serverkey_sandbox',
				'midtrans_clientkey_sandbox',
				'midtrans_merchant_sandbox',
				'socialproof_text',
				'socialproof_settings',
				'email_send_to',
				'email_on',
				'changepass_setting',
				'tiktok_pixel',
				'fundraiser_on',
				'fundraiser_text',
				'fundraiser_button',
				'fundraiser_commission_on',
				'fundraiser_commission_type',
				'fundraiser_commission_percent',
				'fundraiser_commission_fixed',
				'fundraiser_wa_on',
				'fundraiser_email_on',
				'fundraiser_wa_text',
				'fundraiser_email_text',
				'payment_error_handling',
				'email_success_message',
				'tripay_qris',
				'register_checkbox_setting',
				'register_checkbox_info',
				'form_confirmation_setting',
				'telegram_manual_confirmation',
				'jquery_custom',
				'minimum_donate',
				'wanotif_apikey_cs',
				'min_payout_setting',
				'min_payout',
				'donasiaja'
			);


	foreach ($data_array as $key => $value) {
		
		// cek apakah di table ada sesuai "type" ?
		$query = $wpdb->get_results('SELECT data from '.$table_name.' where type="'.$value.'"');
		if($query==null){

			// -> klo gak ada, insert
			if($value=='label_tab'){
				$isi = 'tab';
			}elseif($value=='max_love'){
				$isi = '0';
			}elseif($value=='opt_nominal'){
				$isi = '{"opt1":["10000","Rp 10rb","0"],"opt2":["25000","Rp 25rb","0"],"opt3":["50000","Rp 50rb","1"],"opt4":["100000","Rp 100rb","0"]}';
			}elseif($value=='max_package'){
				$isi = '10';
			}elseif($value=='form_setting'){
				$isi = '1';
			}elseif($value=='del_campaign_setting'){
				$isi = '0';
			}elseif($value=='btn_followup'){
				$isi = '3';
			}elseif($value=='text_f1'){
				$isi = 'Terimakasih *Bpk/Ibu {name}* atas Donasi yang akan Anda berikan. Semoga Rahmat dan Lindungan Allah selalu senantiasa bersama Anda.

Untuk Donasinya sejumlah *{total}* mohon ditransfer *{payment_account}* dengan No Rek *{payment_number}*. 
Terimakasih 😊🙏';
			}elseif($value=='text_f2'){
				$isi = 'Followup 2';
			}elseif($value=='text_f3'){
				$isi = 'Followup 3';
			}elseif($value=='text_f4'){
				$isi = 'Followup 4';
			}elseif($value=='text_f5'){
				$isi = 'Followup 5';
			}elseif($value=='text_received'){
				$isi = 'Terimakasih *Bpk/Ibu {name}* , donasi anda sudah kami terima. Semoga Rahmat dan Lindungan Allah selalu senantiasa bersama Anda.
Terimakasih 😊🙏';
			}elseif($value=='text_received_status'){
				$isi = '0';
			}elseif($value=='campaign_setting'){
				$isi = '1';
			}elseif($value=='main_menu_name'){
				$isi = 'DonasiAja';
			}elseif($value=='logo_url'){
				$isi = '';
			}elseif($value=='app_name'){
				$isi = '';
			}elseif($value=='login_setting'){
				$isi = '1';
			}elseif($value=='login_text'){
				$isi = 'Silahkan login jika sudah memiliki akun.';
			}elseif($value=='register_setting'){
				$isi = '0';
			}elseif($value=='register_text'){
				$isi = 'Mari bergabung bersama orang baik lainnya.';
			}elseif($value=='page_login'){
				$isi = 'login';
			}elseif($value=='page_register'){
				$isi = 'register';
			}elseif($value=='anonim_text'){
				$isi = 'Orang Baik';
			}elseif($value=='page_donate'){
				$isi = 'donate-now';
			}elseif($value=='page_typ'){
				$isi = 'invoice';
			}elseif($value=='apikey_local'){
				$isi = '{"donasiaja": [""]}';
			}elseif($value=='apikey_server'){
				$isi = '{"donasiaja": ["", "0", "", ""]}';
			}elseif($value=='theme_color'){
				$isi = '{"color":["","",""]}';
			}elseif($value=='form_text'){
				$isi = '{"text":["Donasi Sekarang!","Donasi","Anda akan berdonasi dalam program:","Donasi Terbaik Anda"]}';
			}elseif($value=='unique_number_setting'){
				$isi = '2';
			}elseif($value=='unique_number_value'){
				$isi = '{"unique_number":["1","1","999"]}';
			}elseif($value=='currency'){
				$isi = 'IDR';
			}elseif($value=='payment_setting'){
				$isi = '{"method1":["instant","Pembayaran Instan (Cepat & Mudah)","0"],"method2":["va","Virtual Account (Verifikasi Otomatis)","0"],"method3":["transfer","Transfer Bank (Verifikasi Manual 1x24jam)","1"]}';
			}elseif($value=='bank_account'){
				$isi = '{"bca":"__0"}';
			}elseif($value=='ipaymu_mode'){
				$isi = '0';
			}elseif($value=='moota_range'){
				$isi = '2';
			}elseif($value=='wanotif_url'){
				$isi = 'https://api.wanotif.id/v1';
			}elseif($value=='wanotif_message'){
				$isi = 'Terimakasih *Bpk/Ibu {name}* atas Donasi yang akan Anda berikan. Semoga Rahmat dan Lindungan Allah selalu senantiasa bersama Anda.

Untuk Donasinya sejumlah *{total}* mohon ditransfer ke *{payment_account}* dengan No Rek *{payment_number}*. 
Terimakasih 😊🙏';
			}elseif($value=='wanotif_message2'){
				$isi = 'Terimakasih *Bpk/Ibu {name}* , Donasi anda sudah kami terima. Semoga Rahmat dan Lindungan Allah selalu senantiasa bersama Anda.
Terimakasih 😊🙏';
			}elseif($value=='wanotif_on_dashboard'){
				$isi = '0';
			}elseif($value=='powered_by_setting'){
				$isi = '1';
			}elseif($value=='form_email_setting'){
				$isi = '1';
			}elseif($value=='form_comment_setting'){
				$isi = '1';
			}elseif($value=='fb_event'){
				$isi = '{"event":["","",""]}';
			}elseif($value=='telegram_send_to'){
				$isi = '[{"channel":"","message":""}]';
			}elseif($value=='telegram_on'){
				$isi = '1';
			}elseif($value=='wanotif_on'){
				$isi = '1';
			}elseif($value=='jquery_on'){
				$isi = '1';
			}elseif($value=='limitted_donation_button'){
				$isi = '0';
			}elseif($value=='socialproof_text'){
				$isi = 'Baru saja Donasi di {campaign_title}';
			}elseif($value=='telegram_send_to'){
				$isi = '{"settings":["flying_rounded","bottom_left","show","8","15"]}';
			}elseif($value=='email_send_to'){
				$isi = '[{"email":"","message":""}]';
			}elseif($value=='email_on'){
				$isi = '0';
			}elseif($value=='changepass_setting'){
				$isi = '1';
			}elseif($value=='fundraiser_on'){
				$isi = '0';
			}elseif($value=='fundraiser_text'){
				$isi = 'Mari jadi Fundraiser dan berikan manfaat bagi program ini.';
			}elseif($value=='fundraiser_button'){
				$isi = 'Jadi Fundraiser';
			}elseif($value=='fundraiser_commission_on'){
				$isi = '1';
			}elseif($value=='fundraiser_commission_type'){
				$isi = '0';
			}elseif($value=='fundraiser_commission_percent'){
				$isi = '10';
			}elseif($value=='fundraiser_commission_fixed'){
				$isi = '2000';
			}elseif($value=='fundraiser_wa_on'){
				$isi = '0';
			}elseif($value=='fundraiser_email_on'){
				$isi = '0';
			}elseif($value=='fundraiser_wa_text'){
				$isi = 'Selamat {name}

Komisi anda berhasil kami transfer ke rekening Anda.
Payout Number : {payout_number}
Total Komisi : {nominal}

Terimakasih atas kerjasamanya dan jangan berhenti untuk memasarkan produk dari kami untuk mendapatkan Komisi yang lebih Banyak Lagi.

Salam, DonasiAja Team 😊🙏';
			}elseif($value=='fundraiser_email_text'){
				$isi = '[{"email":"{email}","emailcc":"","emailbcc":"","subject":"Komisi {payout_number} - {nominal} sudah ditransfer","message":"<p>Selamat <strong>{name}</strong></p>linebreak<p>Komisi anda berhasil kami transfer ke rekening Anda.<br />Payout Number : <strong>{payout_number}</strong><br />Total Komisi : <strong>{nominal}</strong></p>linebreak<p>Terimakasih atas kerjasamanya dan jangan berhenti untuk memasarkan produk dari kami untuk mendapatkan Komisi yang lebih Banyak Lagi.</p>linebreak<p>Salam, <strong>DonasiAja Team</strong> 😊🙏</p>"}]';
			}elseif($value=='payment_error_handling'){
				$isi = '0';
			}elseif($value=='email_success_message'){
				$isi = '[{"email":"{email}","emailcc":"","emailbcc":"","subject":"Donasi sebesar {total} berhasil kami terima untuk Program {campaign_title}","message":"<p><strong>Terimakasih {name}</strong> atas Donasi yang sudah anda berikan pada program <strong>{campaign_title}</strong> sebesar <strong>{total}</strong>.</p>linebreak<p>Semoga Rahmat dan Lindungan Allah selalu senantiasa bersama Anda, dimudahkan segala urusannya dan dilancarkan rejekinya. Aaminnn</p>linebreak<p><br />Terimakasih, <strong>DonasiAja Team</strong> 😊🙏</p>"}]';
			}elseif($value=='tripay_qris'){
				$isi = 'QRISC';
			}elseif($value=='register_checkbox_setting'){
				$isi = '1';
			}elseif($value=='register_checkbox_info'){
				$isi = 'Dengan ini saya menyetujui dan mematuhi semua peraturan yang ada di website ini.';
			}elseif($value=='form_confirmation_setting'){
				$isi = '1';
			}elseif($value=='telegram_manual_confirmation'){
				$isi = '[{"channel":"","message":""}]';
			}elseif($value=='jquery_custom'){
				$isi = '3.6.1';
			}elseif($value=='minimum_donate'){
				$isi = '1000';
			}elseif($value=='wanotif_apikey_cs'){
				$isi = '{"jumlah":0,"data":{}}';
			}elseif($value=='min_payout_setting'){
				$isi = '1';
			}elseif($value=='min_payout'){
				$isi = '50000';
			}else{
				$isi = '';
			}

	    	$wpdb->insert( 
				$table_name, 
				array(
					'type' => $value,
					'data' => $isi
				) 
			);

		}
    }



    // table dja_category
	$data_array2 = array(
				'Pendidikan',
				'Kesehatan',
				'Kemanusiaan',
				'Bencana Alam',
				'Rumah Ibadah',
			);

	foreach ($data_array2 as $key => $value) {
    	$query = $wpdb->get_results('SELECT category from '.$table_name2.' where category="'.$value.'"');

    	if($query==null){

    		$wpdb->insert( 
				$table_name2, 
				array(
					'category' => $value
				) 
			);
    	}
    }

    // table dja_payment_list
	$data_array3 = array(
				"bca" => "Bank BCA",
				"mandiri" => "Bank Mandiri",
				"bni" => "Bank BNI",
				"bri" => "Bank BRI",
				"bank_btn" => "Bank BTN",
				"btpn" => "Bank BTPN",
				"bukopin" => "Bank Bukopin",
				"cimb_niaga" => "Bank CIMB Niaga",
				"mega" => "Bank Mega",
				"danamon" => "Bank Danamon",
				"bag" => "Bank Artha Graha Internasional",
				"permata" => "Permata Bank",
				"citi_bank" => "Citi Bank",
				"sinarmas" => "Bank Sinarmas",
				"sampoerna" => "Bank Sahabat Sampoerna",
				"maybank" => "Maybank",
				"hongleong" => "HongLeong Bank",
				"uob" => "United Overseas Bank",
				"bsi" => "Bank Syariah Indonesia",
				"bca_syariah" => "Bank BCA Syariah",
				"cimb_niaga_syariah" => "Bank CIMB Niaga Syariah",
				"mega_syariah" => "Bank Mega Syariah",
				"bank_bjb_syariah" => "Bank BJB Syariah",
				"bank_jateng_syariah" => "Bank Jateng Syariah",
				"bank_jatim_syariah" => "Bank Jatim Syariah",
				"bank_nagari_syariah" => "Bank Nagari Syariah",
				"bank_sulselbar_syariah" => "Bank Sulselbar Syariah",
				"bank_panin_dubai_syariah" => "Bank Panin Dubai Syariah",
				"muamalat" => "Bank Muamalat",
				"bank_bjb" => "Bank BJB",
				"bank_dki" => "Bank DKI",
				"bank_jateng" => "Bank Jateng",
				"bank_jatim" => "Bank Jatim",
				"bank_nagari" => "Bank Nagari",
				"bank_sulselbar" => "Bank Sulselbar",
				"qris" => "QRIS",
				"jenius" => "Jenius",
				"gopay" => "Gopay",
				"ovo" => "OVO",
				"dana" => "DANA",
				"shopeepay" => "ShopeePay",
				"linkaja" => "LinkAja",
				"jemput_donasi" => "Jemput Donasi",
				"alfamart" => "Alfamart",
				"alfamidi" => "Alfamidi",
				"indomaret" => "Indomaret"
			);

	foreach ($data_array3 as $key => $value) {
    	$query = $wpdb->get_results('SELECT code from '.$table_name3.' where code="'.$key.'"');

    	if($query==null){

    		$wpdb->insert( 
				$table_name3, 
				array(
					'code' => $key,
					'name' => $value,
				) 
			);
    	}
    }



    // table dja_user_type
	$data_array4 = array(
				"personal" => "Personal",
				"org" => "Organization",
			);

	foreach ($data_array4 as $key => $value) {
    	$query = $wpdb->get_results('SELECT type from '.$table_name4.' where type="'.$key.'"');

    	if($query==null){

    		$wpdb->insert( 
				$table_name4, 
				array(
					'type' => $key,
					'name' => $value,
				) 
			);
    	}
    }


    // table dja_verification_status
	$data_array5 = array(
				"0" => "No Status",
				"1" => "Verified",
				"2" => "On Review",
				"3" => "Rejected",
				"4" => "Banned",
			);

	foreach ($data_array5 as $key => $value) {
    	$query = $wpdb->get_results('SELECT status from '.$table_name5.' where status="'.$key.'"');

    	if($query==null){

    		$wpdb->insert( 
				$table_name5, 
				array(
					'status' => $key,
					'name' => $value,
				) 
			);
    	}
    }


}
register_activation_hook(__FILE__, 'dja_options_install_data');

function deactivate_plugin_dna() {
 	
    // REMOVE ROLE
   $role = get_role( 'donatur' );
   $role->remove_cap( 'upload_files' );
   $role->remove_cap( 'edit_posts' );
   $role->remove_cap( 'edit_published_posts' );
   $role->remove_cap( 'publish_posts' );
   $role->remove_cap( 'read' );
   $role->remove_cap( 'level_2' );
   $role->remove_cap( 'level_1' );
   $role->remove_cap( 'level_0' );
   $role->remove_cap( 'manage_options' );


    // REMOVE ROLE
   $role = get_role( 'cs' );
   $role->remove_cap( 'upload_files' );
   $role->remove_cap( 'edit_posts' );
   $role->remove_cap( 'edit_published_posts' );
   $role->remove_cap( 'publish_posts' );
   $role->remove_cap( 'read' );
   $role->remove_cap( 'level_2' );
   $role->remove_cap( 'level_1' );
   $role->remove_cap( 'level_0' );
   $role->remove_cap( 'manage_options' );

}
register_deactivation_hook( __FILE__, 'deactivate_plugin_dna' );


function load_custom_dja_admin_style() {

	global $wpdb;
    $table_name = $wpdb->prefix . "dja_users";
    $id_login = wp_get_current_user()->ID;
	$rows = $wpdb->get_results('SELECT * from '.$table_name.' where user_id="'.$id_login.'"');

	$cap = get_user_meta( wp_get_current_user()->ID, $wpdb->get_blog_prefix() . 'capabilities', true );
    $roles = array_keys((array)$cap);
    $role = $roles[0];

	if(isset($rows[0])){
	    if($rows[0]->user_pp_img=='') {
	        $pp_url = DJA_PLUGIN_URL . "assets/images/pp.jpg";
	    }else{
	        $pp_url = $rows[0]->user_pp_img;
	    }
	}else{
		$pp_url = DJA_PLUGIN_URL . "assets/images/pp.jpg";
	}

    donasiaja_global_vars();
    wp_register_style( 'dja_style_admin', DJA_PLUGIN_URL . 'admin/css/style-admin.css', false, null );
    wp_enqueue_style( 'dja_style_admin' );

    wp_register_script( 'donasiaja_script_admin', DJA_PLUGIN_URL . 'admin/js/donasiaja-admin.js', false, true );
    wp_enqueue_script('donasiaja_script_admin');

    $a = array( 'admin_url' => admin_url() );
    wp_localize_script( 'donasiaja_script_admin', 'donasiaja_admin', $a );

    $b = array( 'pp_url' => $pp_url );
    wp_localize_script( 'donasiaja_script_admin', 'donasiaja_admin2', $b );

    $c = array( 'logout_url' => wp_logout_url() );
    wp_localize_script( 'donasiaja_script_admin', 'donasiaja_admin3', $c );

    $d = array( 'role_user' => $role );
    wp_localize_script( 'donasiaja_script_admin', 'donasiaja_admin4', $d );


}
add_action( 'admin_enqueue_scripts', 'load_custom_dja_admin_style' );


function enqueue_donasiaja_admin_style(){
    wp_enqueue_script('donasiaja_script_admin');
 }
 add_action('admin_enqueue_scripts', 'enqueue_donasiaja_admin_style');
 
 
 // register jquery and style on initialization
function register_donasiaja_script() {
    global $wpdb;
    donasiaja_global_vars();
    $table_name = $wpdb->prefix . "dja_settings";
    $plugin_version = $GLOBALS['donasiaja_vars']['plugin_version'];
    $query_settings=$wpdb->get_results('SELECT data from '.$table_name.' where type="main_menu_name" or type="apikey_local" or type="donasiaja" ORDER BY id ASC');
    $x=$query_settings[0]->data;
    $a=$query_settings[1]->data;
    $b=json_decode($a,true);
    $c=$b['donasiaja'][0];
    $d=$query_settings[2]->data;

	// Register the script
	// wp_register_script( 'dja_script_1', DJA_PLUGIN_URL . 'assets/js/jquery.min.js', array(), $plugin_version, true );
	
	wp_register_script( 'dja_script_1', DJA_PLUGIN_URL . 'assets/js/hello.donasiaja.js', array(), $plugin_version, true );

	// Localize the script with new data
	$translation_array = array(
	    'varSiteUrl' => get_site_url(),
		'varPluginUrl' => DJA_PLUGIN_URL,
		'apiKey' => substr($c, 0, 11),
	    'm' => $x,
	    'n' => '.id',
	    'r' => 'https://',
	    'd' => $d
	);
	wp_localize_script( 'dja_script_1', 'donasiajaObjName', $translation_array );
	 
	// Enqueued script with localized data.
	wp_enqueue_script( 'dja_script_1' );
	
}
add_action('init', 'register_donasiaja_script');


function load_custom_dja_style_carousel() {
	
	wp_enqueue_script('dja_script_1');
	// wp_enqueue_script('dja_script_2');
}
add_action( 'wp_enqueue_scripts', 'load_custom_dja_style_carousel' );


function custom_style_donasiaja() {
	donasiaja_global_vars();
    $plugin_version = $GLOBALS['donasiaja_vars']['plugin_version'];
    wp_register_style( 'donasiaja-style', DJA_PLUGIN_URL . 'assets/css/donasiaja-style.css', false, $plugin_version );
    wp_enqueue_style( 'donasiaja-style' );
}
add_action( 'wp_enqueue_scripts', 'custom_style_donasiaja' );


function custom_donasiaja_js() {
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
    	echo '<script src="'. DJA_PLUGIN_URL . 'assets/js/jquery.min.js?ver='.$plugin_version.'"></script>';
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
// Add hook for front-end <head></head>
add_action( 'wp_head', 'custom_donasiaja_js' );


function parent_theme_setup() {
	add_theme_support( 'post-thumbnails' );
    add_image_size( 'campaign_cover', 650, 350, true );
    add_image_size( 'donasiaja_profile_picture', 150, 150, true );
}
add_action( 'after_setup_theme', 'parent_theme_setup' );



// GENERAL SHORTCODE FUNCTION TO LOAD MUCH CAMPAIGN
function donasiaja_shortcode_func( $atts, $content = "" ) {
	
	global $wpdb;
	$table_name = $wpdb->prefix . "dja_campaign";
	$table_name2 = $wpdb->prefix . "dja_donate";
	$table_name3 = $wpdb->prefix . "dja_users";
	$table_name4 = $wpdb->prefix . "dja_shortcode";
	$table_name5 = $wpdb->prefix . "dja_settings";

	$atts = shortcode_atts( array(
		'id' => null,
		'show' => null
	), $atts, 'donasiaja' );


	$show = '';
	if($atts['show']!==null){
  		$show = $atts['show'];

  		ob_start();

  		if($show=='total_terkumpul'){
  			$total_donasi = $wpdb->get_results("SELECT SUM(nominal) as total, COUNT(id) as jumlah FROM $table_name2 where status='1' ")[0];

	        if($total_donasi==null){
	            $total_terkumpul = 0;
	            $jumlah_donasi = 0;
	        }else{
	            $total_terkumpul = $total_donasi->total;
	            $jumlah_donasi = $total_donasi->jumlah;
	        }

  			echo 'Rp '.number_format($total_terkumpul,0,",",".");
  		}
  		if($show=='jumlah_donasi'){

  			$total_donasi = $wpdb->get_results("SELECT SUM(nominal) as total, COUNT(id) as jumlah FROM $table_name2 where status='1' ")[0];

	        if($total_donasi==null){
	            $total_terkumpul = 0;
	            $jumlah_donasi = 0;
	        }else{
	            $total_terkumpul = $total_donasi->total;
	            $jumlah_donasi = $total_donasi->jumlah;
	        }

  			echo $jumlah_donasi;
  		}
  		if($show=='jumlah_donatur'){
  			$jumlah_donatur = count( get_users( array( 'fields' => array( 'ID' ), 'role' => 'donatur' ) ) );
  			echo number_format($jumlah_donatur,0,",",".");
  		}
  		if($show=='jumlah_user'){
  			$jumlah_user = count( get_users( array( 'fields' => array( 'ID' ) ) ) );
  			echo number_format($jumlah_user,0,",",".");
  		}
  		if($show=='jumlah_all_campaign'){
  			$jumlah_all_campaign = count($wpdb->get_results("SELECT id from $table_name ORDER BY id DESC"));
  			echo number_format($jumlah_all_campaign,0,",",".");
  		}
  		if($show=='jumlah_active_campaign'){

  			$rows = $wpdb->get_results("SELECT id, end_date, publish_status from $table_name ORDER BY id DESC");

  			$active_campaign = 0;
            foreach ($rows as $row) {
                                                
                $date_now = date('Y-m-d');
                $datetime1 = new DateTime($date_now);
                $datetime2 = new DateTime($row->end_date);
                $hasil = $datetime1->diff($datetime2);

                if($hasil->invert==true && $row->publish_status==1){
                    
                }else{
                    if($row->publish_status==1){
                        // $act_status = '<span class="active-status">Active</span>';
                        $active_campaign++;
                    }
                }
            }   
  			
  			echo number_format($active_campaign,0,",",".");
  		}


		$output = ob_get_clean();
		return $output;


  	}else{

  		$id = '';
		if($atts['id']!==null){
	  		$id = $atts['id'];
	  	}

	  	$check_shortcode = $wpdb->get_results('SELECT * from '.$table_name4.' where s_id="'.$id.'"');
		if($check_shortcode==null){
			// wp_die();
			return false;
		}else{
			$style 			= $check_shortcode[0]->s_style;
			$show  			= $check_shortcode[0]->s_show;
			$category_id  	= $check_shortcode[0]->s_category;
			$loadmore  		= $check_shortcode[0]->s_loadmore;
			$button_on  	= $check_shortcode[0]->s_button_on;
			$button_text  	= $check_shortcode[0]->s_button_text;
			$s_data_load  	= $check_shortcode[0]->s_data_load;
			$s_campaign  	= $check_shortcode[0]->s_campaign;
			$j_utmsource	= $check_shortcode[0]->utm_source;
			$j_utmmedium	= $check_shortcode[0]->utm_medium;
			$j_utmcampaign	= $check_shortcode[0]->utm_campaign;
			$j_utmterm		= $check_shortcode[0]->utm_term;
			$j_utmcontent	= $check_shortcode[0]->utm_content;
		}

		//make a url params value
		if($j_utmsource!='') {
			$pass_param = 'utm_source='.$j_utmsource;
		}
		if($j_utmmedium!='') {
			if(isset($pass_param)) {
				$pass_param = $pass_param.'&utm_medium='.$j_utmmedium;
			} else {
				$pass_param = 'utm_medium='.$j_utmmedium;
			}
		}
		if($j_utmcampaign!='') {
			if(isset($pass_param)) {
				$pass_param = $pass_param.'&utm_campaign='.$j_utmcampaign;
			} else {
				$pass_param = 'utm_campaign='.$j_utmcampaign;
			}
		}
		if($j_utmterm!='') {
			if(isset($pass_param)) {
				$pass_param = $pass_param.'&utm_term='.$j_utmterm;
			} else {
				$pass_param = 'utm_term='.$j_utmterm;
			}
		}
		if($j_utmcontent!='') {
			if(isset($pass_param)) {
				$pass_param = $pass_param.'&utm_content='.$j_utmcontent;
			} else {
				$pass_param = 'utm_content='.$j_utmcontent;
			}
		}
		if(isset($pass_param)) {
			$pass_param = '?' . $pass_param;
		} else {
			$pass_param = '';
		}

		if($s_data_load == '1'){
			$s_campaign = json_decode($s_campaign, true);
			if($s_campaign!=null){
				$count = count($s_campaign['campaign']);
	            $i = 1;
	            $wherenya = '';
	            $campaign = [];
	            foreach ($s_campaign['campaign'] as $value) {

	                $idnya = $value;
	                $new_campaign = $wpdb->get_results("SELECT * FROM $table_name where id='$idnya' ");
	            	$campaign=array_merge($campaign,$new_campaign);

	            }

        	}

		}else{
			if($category_id==null || $category_id=='0'){
				// ALL Category show
				$campaign = $wpdb->get_results("SELECT * FROM $table_name where publish_status='1' ORDER BY id DESC LIMIT 0,$show ");
			}else{

				// example OR : WHERE (category_id='1' and publish_status='1') or (category_id='4' and publish_status='1')
				if( strpos($category_id, ',') !== false ) {

	                // ada koma, maka loop. berarti banyak category
	                $variable = $category_id;
	                $var=explode(',',$variable);
	                $count_categoty = count($var);
	                $no_category = 1;
	                $wherenya = '';
	                foreach($var as $value_idnya)
	                {
	                    $wherenya .= "(category_id='$value_idnya' and publish_status='1')";
	                    if($no_category<$count_categoty){
	                        $wherenya .= ' or ';
	                    }
	                    $no_category++;
	                }

	                $campaign = $wpdb->get_results("SELECT * FROM $table_name where $wherenya ORDER BY id DESC LIMIT 0,$show ");

	            }else{

	                // gak ada koma, berarti single
	                $campaign = $wpdb->get_results("SELECT * FROM $table_name where publish_status='1' and category_id='$category_id' ORDER BY id DESC LIMIT 0,$show ");
	            }

			}
		}

		// Settings
	    $query_settings = $wpdb->get_results('SELECT data from '.$table_name5.' where type="theme_color" ORDER BY id ASC');
	    $general_theme_color    = json_decode($query_settings[0]->data, true);
	    $theme_color 			= $general_theme_color['color'][0];
		$progressbar_color  	= $general_theme_color['color'][1];
		$button_color 			= $general_theme_color['color'][2];
		$button_color2 			= $general_theme_color['color'][3];

	    if($theme_color==''){
	    	$theme_color = '#009F61';
	    }
	    if($progressbar_color==''){
	    	$progressbar_color = '#009F61';
	    }
		
		// $campaign2 = $wpdb->get_results("SELECT * FROM $table_name where publish_status='1' ORDER BY id ASC LIMIT 0,6 ");

	  	$hasil_list = '';	
	  	$hasil_slider = '';
	  	$hasil_grid = '';


		if($style=='list' || $style=='slider' || $style=='grid'){

			foreach ($campaign as $value) {

				date_default_timezone_set('Asia/jakarta');
				// Waktu Berakhir
			    $date_now = date('Y-m-d');
			    $datetime1 = new DateTime($date_now);
			    $datetime2 = new DateTime($value->end_date);
			    $hasil = $datetime1->diff($datetime2);
			    
			    $year = $hasil->y;
			    $month = $hasil->m;
			    $day = $hasil->d;

			    // Date
			    $date_end = false;
			    if($year!=0){
			        if($day>7){
			    		$sisa_waktu = $year.'&nbsp;tahun,&nbsp;' .($month+1).'&nbsp;bulan&nbsp;lagi';
			    	}else{
			    		$sisa_waktu = $year.'&nbsp;tahun,&nbsp;' .$month.'&nbsp;bulan&nbsp;lagi';
			    	}
			    }else{
			        if($month!=0){
			            $sisa_waktu = $month.'&nbsp;bulan,&nbsp;' .$day.'&nbsp;hari&nbsp;lagi';
			        }else{
			            if($day==0 && $hasil->days==0){
			                $sisa_waktu = 'hari&nbsp;ini';
			            }else{
			                if($hasil->invert==true){
			                    $sisa_waktu = '<span style="color:#ff6b24;font-style:italic;">sudah&nbsp;berakhir</span>';
			                    $date_end = true;
			                }else{
			                    $sisa_waktu = $day.'&nbsp;hari&nbsp;lagi';
			                }
			                
			            }
			        }
			    }

			    if($value->end_date==null){
			    	$sisa_waktu = '∞';
			    }

			    if($hasil->invert==true){
			    	$sisa_waktu = '<span style="color:#ff6b24;font-style:italic;">sudah&nbsp;berakhir</span>';
			    }


				$total_donasi = $wpdb->get_results("SELECT SUM(nominal) as total, COUNT(id) as jumlah FROM $table_name2 where campaign_id='$value->campaign_id' and status='1' ")[0];

				if($value->target==0){
					// $persen = 100;
					$persen_width = 100;
				}else{
					// $persen = ($total_donasi->total/$row->target)*100;
					$persen_width = ($total_donasi->total/$value->target)*100;
					if($persen_width>100){
						$persen_width = 100;
					}
				}

				$data_donasinya = '';
	            if($total_donasi==null){
	                $total_terkumpul = 0;
	                $jumlah_donasi = 0;
	                $data_donasi = 0;
	            }else{
	                $total_terkumpul = $total_donasi->total;
	                $jumlah_donasi = $total_donasi->jumlah;

	                $data_donasi = $wpdb->get_results("SELECT user_id, name FROM $table_name2 where campaign_id='$value->campaign_id' and status='1' ORDER BY id ASC LIMIT 0,3 ");

	                // GET INISAL DONATUR / PP URL
	                foreach ($data_donasi as $data) {
	                	if($data->user_id==null || $data->user_id==0){
	                		$first_char = strtoupper(mb_substr($data->name, 0, 1));
	                		$data_donasinya .= '<span class="u_image u_inisial" style="background:'.$theme_color.'"> '.$first_char.' </span>';
	                	}else{
	                		$first_char = strtoupper(mb_substr($data->name, 0, 1));
	                		$user_profile = $wpdb->get_results('SELECT user_pp_img as photo from '.$table_name3.' where user_id="'.$data->user_id.'"');
							if(isset($user_profile[0])){
								if($user_profile==null){
									$first_char = strtoupper(mb_substr($data->name, 0, 1));
			                		$data_donasinya .= '<span class="u_image u_inisial" style="background:'.$theme_color.'"> '.$first_char.' </span>';
								}else{
									$url_photo = $user_profile[0]->photo;
									if($url_photo==''){
										$first_char = strtoupper(mb_substr($data->name, 0, 1));
				                		$data_donasinya .= '<span class="u_image u_inisial" style="background:'.$theme_color.'"> '.$first_char.' </span>';
									}else{
										$data_donasinya .= '<img src="'.$url_photo.'" alt="User Image" class="u_image">';
									}
								}
							}else{
								$first_char = strtoupper(mb_substr($data->name, 0, 1));
		                		$data_donasinya .= '<span class="u_image u_inisial" style="background:'.$theme_color.'"> '.$first_char.' </span>';
							}
	                	}
	                }
	            }

				if($value->image_url==null){
	                $image_url = DJA_PLUGIN_URL.'assets/images/cover_donasiaja.jpg';
	            }else{
	                $image_url = $value->image_url;
	                // $image_url = str_replace('localhost', '192.168.0.102', $image_url);
	            }

	            if($jumlah_donasi==0){
	            	$icon_donasi = '
	            	<div class="u_campaign u_icon">
					  <div class="campaign_days no_donation" style="padding-top:5px;">'.$sisa_waktu.'</div>
					</div>
					';
	            }else{
	            	$donasi_plusmore = '';
	            	if($jumlah_donasi>=4){
		            	
	            		$total_min_three = $jumlah_donasi-3;
	            		$donasi_totalnya = shortDisplayNumber($total_min_three).'+';

		            	if(strlen($donasi_totalnya)>=4){
		            		$donasi_totalnya = '<span style="font-size:7px;">'.$donasi_totalnya.'</span>';
		            	}elseif(strlen($donasi_totalnya)==3){
		            		$donasi_totalnya = '<span style="font-size:8px;">'.$donasi_totalnya.'</span>';
		            	}else{
		            		$donasi_totalnya = $donasi_totalnya;
		            	}

		            	// >= 4 char = 7px
		            	// 3 char = 8px
		            	// 2 char = 9px

		            	$donasi_plusmore = '<span class="u_image u_inisial" style="background:'.$theme_color.'">'.$donasi_totalnya.'</span>';
	            	}


					$icon_donasi = '
	            	<div class="u_campaign u_donasi">
					  <div class="u_photo">
					  	'.$data_donasinya.'
					  	'.$donasi_plusmore.'
					  </div>
					  <div class="campaign_days" style="margin-top:5px;font-size:11px;">'.$sisa_waktu.'</div>
					</div>
					';
	            }

	            if($value->publish_status=='1'){
	                $campaign_url = get_site_url().'/campaign/'.$value->slug;
	            }else{
	                $campaign_url = get_site_url().'/preview/'.$value->slug;
	            }


	            // GET PROFILE PICTURE CAMPAIGNER
				$profile = $wpdb->get_results('SELECT user_pp_img as photo, user_verification from '.$table_name3.' where user_id="'.$value->user_id.'"');
				if(isset($profile[0])){
					if($profile==null){
						$profile_photo = DJA_PLUGIN_URL . "assets/images/pp.jpg";
					}else{
						$profile_photo = $profile[0]->photo;
					}
				}else{
					$profile_photo = DJA_PLUGIN_URL . "assets/images/pp.jpg";
				}

				// GET USER NAME
				$args2 = array( 'blog_id' => 0, 'search' => $value->user_id, 'search_columns' => array( 'ID' ) );
	            $get_name = get_users( $args2 );

	            if($get_name==null){
	                $campaigner_name = '-';
	            }else{
	                $nama_user = str_replace("'", "", $get_name[0]->user_firstname.' '.$get_name[0]->user_lastname);
	                $campaigner_name = $nama_user; // nama asli
	            }
	            // user_firstname

	            if($profile[0]->user_verification=='1'){
	            	$verified_user = '<div class="verified_checklist"><img alt="Image" src="'.DJA_PLUGIN_URL.'assets/images/check.png"></div>';
	            }else{
	            	$verified_user = '';
	            }


	            $button_donasi = '';
	            if($button_on=='1'){

	            	$text_button = 'Donate Now';
	            	if($button_text!=''){
	            		$text_button = $button_text;
	            	}

	            	if($style=='list'){
	            		$button_donasi = '
	            		<div class="btn_donasi_box">
				        	<div class="btn_donasi">
					       	<button style="background-color:'.$button_color2.';">'.$text_button.'</button>
					       	</div>
					    </div>
					    ';
	            	}
	            	if($style=='slider'){
	            		$button_donasi = '
	            		<div class="btn_donasi_box btn_slider">
				        	<div class="btn_donasi">
					       	<button style="background-color:'.$button_color2.';">'.$text_button.'</button>
					       	</div>
					    </div>
					    ';
	            	}

	            	if($style=='grid'){
	            		$button_donasi = '
	            		<div class="btn_donasi_box btn_grid">
				        	<div class="btn_donasi">
					       	<button style="background-color:'.$button_color2.';">'.$text_button.'</button>
					       	</div>
					    </div>
					    ';
	            	}

	            }


	            if($style=='list'){
		            $hasil_list .= '
		            <li class="cards__item">
					  <a href="'.$campaign_url.$pass_param.'">
				      <div class="card__">
				        <div class="card__image"><img src="'.$image_url.'"></div>
				        <div class="card__content content_1">
				          <div class="card__title">'.$value->title.'</div>
				        </div>
				        <div class="card__content content_2">
				          <div class="card__text campaigner_name">'.$campaigner_name.$verified_user.'</div>
				          <div class="card__text donation_collected" style="color:'.$theme_color.'">Rp '.number_format($total_terkumpul,0,",",".").'<span class="donation_collected_text">terkumpul</span></div>
				          <div style="height:4px; width:100%;background:#eaeaea;border-radius:4px;"><div style="height:4px; width:'.$persen_width.'%;background:'.$progressbar_color.';border-radius:4px;" title="'.$persen_width.'%"></div></div>
				          '.$icon_donasi.'
				        </div>
				        '.$button_donasi.'
					    </div>
				        
				      </a>
				    </li>
				    ';
				}

				if($style=='slider'){
				    $hasil_slider .= '
					<div class="cards__item">
					  <a href="'.$campaign_url.'">
				      <div class="card__">
				        <div class="card__image"><img src="'.$image_url.'"></div>
				        <div class="card__content content_1">
				          <div class="card__title">'.$value->title.'</div>
				        </div>
				        <div class="card__content content_2">
				          <div class="card__text campaigner_name">'.$campaigner_name.$verified_user.'</div>
				          <div class="card__text donation_collected" style="color:'.$theme_color.'">Rp '.number_format($total_terkumpul,0,",",".").'<span class="donation_collected_text">terkumpul</span></div>
				          <div style="height:4px; width:100%;background:#eaeaea;border-radius:4px;"><div style="height:4px; width:'.$persen_width.'%;background:'.$progressbar_color.';border-radius:4px;" title="'.$persen_width.'%"></div></div>
				          '.$icon_donasi.'
				        </div>
				        '.$button_donasi.'
				      </div>
				      </a>
				    </div>
					';
				}

				if($style=='grid'){
					$hasil_grid .= 
					'<li class="cards__item josh">
					  <a href="'.$campaign_url.$pass_param.'" class="btn-shortcode" afia-params="link-target">
				      <div class="card__">
				        <div class="card__image"><img src="'.$image_url.'"></div>
				        <div class="card__content content_1">
				          <div class="card__title">'.$value->title.'</div>
				        </div>
				        <div class="card__content content_2" style="height: 40px;">
				          <div class="card__text campaigner_name">'.$campaigner_name.$verified_user.'</div>
				          
				          
				          
				        </div>
				        '.$button_donasi.'
				      </div>
				      </a>
				    </li>
					';
					// '<li class="cards__item">
					//   <a href="'.$campaign_url.'">
				    //   <div class="card__">
				    //     <div class="card__image"><img src="'.$image_url.'"></div>
				    //     <div class="card__content content_1">
				    //       <div class="card__title">'.$value->title.'</div>
				    //     </div>
				    //     <div class="card__content content_2">
				    //       <div class="card__text campaigner_name">'.$campaigner_name.$verified_user.'</div>
				    //       <div class="card__text donation_collected" style="color:'.$theme_color.'">Rp '.number_format($total_terkumpul,0,",",".").'<span class="donation_collected_text">terkumpul</span></div>
				    //       <div style="height:4px; width:100%;background:#eaeaea;border-radius:4px;"><div style="height:4px; width:'.$persen_width.'%;background:'.$progressbar_color.';border-radius:4px;" title="'.$persen_width.'%"></div></div>
				    //       '.$icon_donasi.'
				    //     </div>
				    //     '.$button_donasi.'
				    //   </div>
				    //   </a>
				    // </li>
					// ';
				}

			}

		}


		ob_start();

		if($style=='slider'){
			
			echo '<div id="section_'.$id.'" class="my-slider-'.$id.'">'.$hasil_slider.'</div>';
			echo '
				<script src="'.DJA_PLUGIN_URL . 'assets/js/tiny-slider.min.js"></script>
					
					<script type="module">
					var widthnya = $("#section_'.$id.'").width();
					var itemnya = 2;
					if(widthnya>=540){
						itemnya = 3;
					}
					/* if(widthnya<=350){ itemnya = 1; } */
					  var slider = tns({
					    container: ".my-slider-'.$id.'",
					    "items": itemnya,
						"slideBy": "page",
						"loop": true,
						"swipeAngle": false,
						"speed": 400,
						"controls": false,
						"controlsPosition" : "bottom",
						"navPosition" : "bottom",
						"touch" : true,
						"autoplay": true,
						"controlsText" : ["<span class='."'".'dashicons dashicons-arrow-left-alt2'."'".'></span>", "<span class='."'".'dashicons dashicons-arrow-right-alt2'."'".'></span>"]
					  });
				</script>
			';
		}

		if($style=='list'){
			echo '<ul id="section_'.$id.'" class="cards__campaign cards__list">'.$hasil_list.'</ul>';
			if($loadmore>=1){
				echo '<div id="box_button_'.$id.'" class="btn_loadmore_list"><div class="donasiaja_loadmore_info"></div><button class="load_campaign" data-id="'.$id.'" data-count="2" style="color:'.$button_color2.';border:1px solid '.$button_color2.';">Load more</button></div>';
			}
			
		}
		if($style=='grid'){
			echo '<ul id="section_'.$id.'" class="cards__campaign">'.$hasil_grid.'</ul>';
			if($loadmore>=1){
				echo '<div id="box_button_'.$id.'" class="btn_loadmore_grid"><div class="donasiaja_loadmore_info"></div><button class="load_campaign" data-id="'.$id.'" data-count="2" style="color:'.$button_color2.';border:1px solid '.$button_color2.';">Load more</button></div>';
			}
		}

		$output = ob_get_clean();
		return $output;


  	}

  	

}
add_shortcode( 'donasiaja', 'donasiaja_shortcode_func' );


// shortcode per campaign
function donasiaja_campaign_shortcode_func( $atts, $content = "" ) {
	
	global $wpdb;
	$table_name = $wpdb->prefix . "dja_campaign";
	$table_name2 = $wpdb->prefix . "dja_donate";
	$table_name3 = $wpdb->prefix . "dja_users";
	$table_name4 = $wpdb->prefix . "dja_shortcode";
	$table_name5 = $wpdb->prefix . "dja_campaign_update";
	$table_name6 = $wpdb->prefix . "dja_settings";

	$atts = shortcode_atts( array(
		'id' => null,
		'show' => null,
		'icon_donatur' => null
	), $atts, 'donasiaja_campaign' );


	$id = '';
	if($atts['id']!==null){
  		$id = $atts['id'];
  	}

	$show = '';
	if($atts['show']!==null){
  		$show = $atts['show'];
  	}

	$icon_donatur = '';
	if($atts['icon_donatur']!==null){
  		$icon_donatur = $atts['icon_donatur'];
  	}

  	$check_campaign = $wpdb->get_results('SELECT id from '.$table_name.' where campaign_id="'.$id.'"');
	if($check_campaign==null){
		return false;
	}

	// Settings
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name6.' where type="anonim_text" or type="theme_color" ORDER BY id ASC');
    $anonim_text 			= $query_settings[0]->data;
    $general_theme_color    = json_decode($query_settings[1]->data, true);
    $theme_color 			= $general_theme_color['color'][0];
	$progressbar_color  	= $general_theme_color['color'][1];
	$button_color 			= $general_theme_color['color'][2];

    if($theme_color==''){
    	$theme_color = '#009F61';
    }
    if($progressbar_color==''){
    	$progressbar_color = '#009F61';
    }


		$value = $wpdb->get_results("SELECT * FROM $table_name where campaign_id='$id' ")[0];

		$date_now = date('Y-m-d');
	    $datetime1 = new DateTime($date_now);
	    $datetime2 = new DateTime($value->end_date);
	    $hasil = $datetime1->diff($datetime2);
	    
	    $year = $hasil->y;
	    $month = $hasil->m;
	    $day = $hasil->d;

	    // Date
	    $date_end = false;
	    if($year!=0){
	        if($day>7){
	    		$sisa_waktu = $year.'&nbsp;tahun,&nbsp;' .($month+1).'&nbsp;bulan&nbsp;lagi';
	    	}else{
	    		$sisa_waktu = $year.'&nbsp;tahun,&nbsp;' .$month.'&nbsp;bulan&nbsp;lagi';
	    	}
	    }else{
	        if($month!=0){
	            $sisa_waktu = $month.'&nbsp;bulan,&nbsp;' .$day.'&nbsp;hari&nbsp;lagi';
	        }else{
	            if($day==0 && $hasil->days==0){
	                $sisa_waktu = 'hari&nbsp;ini';
	            }else{
	                if($hasil->invert==true){
	                    $sisa_waktu = '<span style="color:#ff6b24;font-style:italic;">sudah&nbsp;berakhir</span>';
	                    $date_end = true;
	                }else{
	                    $sisa_waktu = $day.'&nbsp;hari&nbsp;lagi';
	                }
	                
	            }
	        }
	    }

	    if($value->end_date==null){
	    	$sisa_waktu = '∞';
	    }
	    if($hasil->invert==true){
	    	$sisa_waktu = '<span style="color:#ff6b24;font-style:italic;">sudah&nbsp;berakhir</span>';
	    }


		$total_donasi = $wpdb->get_results("SELECT SUM(nominal) as total, COUNT(id) as jumlah FROM $table_name2 where campaign_id='$value->campaign_id' and status='1' ")[0];

		if($value->target==0){
			// $persen = 100;
			$persen_width = 100;
		}else{
			// $persen = ($total_donasi->total/$row->target)*100;
			$persen_width = ($total_donasi->total/$value->target)*100;
			if($persen_width>100){
				$persen_width = 100;
			}
		}

		$data_donasinya = '';
        if($total_donasi==null){
            $total_terkumpul = 0;
            $jumlah_donasi = 0;
            $data_donasi = 0;
        }else{
            $total_terkumpul = $total_donasi->total;
            $jumlah_donasi = $total_donasi->jumlah;

            $data_donasi = $wpdb->get_results("SELECT user_id, name FROM $table_name2 where campaign_id='$value->campaign_id' and status='1' ORDER BY id ASC LIMIT 0,3 ");

            // GET INISAL DONATUR / PP URL
            foreach ($data_donasi as $data) {
            	if($data->user_id==null || $data->user_id==0){
            		$first_char = strtoupper(mb_substr($data->name, 0, 1));
            		$data_donasinya .= '<span class="u_image u_inisial" style="background:'.$theme_color.'"> '.$first_char.' </span>';
            	}else{
            		$first_char = strtoupper(mb_substr($data->name, 0, 1));
            		$user_profile = $wpdb->get_results('SELECT user_pp_img as photo from '.$table_name3.' where user_id="'.$data->user_id.'"');
					if(isset($user_profile[0])){
						if($user_profile==null){
							$first_char = strtoupper(mb_substr($data->name, 0, 1));
	                		$data_donasinya .= '<span class="u_image u_inisial" style="background:'.$theme_color.'"> '.$first_char.' </span>';
						}else{
							$url_photo = $user_profile[0]->photo;
							if($url_photo==''){
								$first_char = strtoupper(mb_substr($data->name, 0, 1));
		                		$data_donasinya .= '<span class="u_image u_inisial" style="background:'.$theme_color.'"> '.$first_char.' </span>';
							}else{
								$data_donasinya .= '<img src="'.$url_photo.'" alt="User Image" class="u_image">';
							}
						}
					}else{
						$first_char = strtoupper(mb_substr($data->name, 0, 1));
                		$data_donasinya .= '<span class="u_image u_inisial" style="background:'.$theme_color.'"> '.$first_char.' </span>';
					}
            	}
            }
        }

		if($value->image_url==null){
            $image_url = DJA_PLUGIN_URL.'assets/images/cover_donasiaja.jpg';
        }else{
            $image_url = $value->image_url;
            // $image_url = str_replace('localhost', '192.168.0.102', $image_url);
        }

        if($jumlah_donasi==0){
        	$icon_donasi = '
        	<div class="u_campaign u_icon">
			  <div class="campaign_days no_donation" style="padding-top:5px;">'.$sisa_waktu.'</div>
			</div>
			';
        }else{
        	$donasi_plusmore = '';
        	if($jumlah_donasi>=4){
            	
        		$total_min_three = $jumlah_donasi-3;
        		$donasi_totalnya = shortDisplayNumber($total_min_three).'+';

            	if(strlen($donasi_totalnya)>=4){
            		$donasi_totalnya = '<span style="font-size:7px;">'.$donasi_totalnya.'</span>';
            	}elseif(strlen($donasi_totalnya)==3){
            		$donasi_totalnya = '<span style="font-size:8px;">'.$donasi_totalnya.'</span>';
            	}else{
            		$donasi_totalnya = $donasi_totalnya;
            	}

            	// >= 4 char = 7px
            	// 3 char = 8px
            	// 2 char = 9px

            	$donasi_plusmore = '<span class="u_image u_inisial" style="background:'.$theme_color.'">'.$donasi_totalnya.'</span>';
        	}

        	if($icon_donatur=='hide'){
        		$icon_donasi = '
	        	<div class="u_campaign u_donasi">
				  <div class="campaign_days" style="margin-top:5px;font-size:11px;">'.$sisa_waktu.'</div>
				</div>
				';
        	}else{
        		$icon_donasi = '
	        	<div class="u_campaign u_donasi">
				  <div class="u_photo">
				  	'.$data_donasinya.'
				  	'.$donasi_plusmore.'
				  </div>
				  <div class="campaign_days" style="margin-top:5px;font-size:11px;">'.$sisa_waktu.'</div>
				</div>
				';
        	}

			
        }

        if($value->publish_status=='1'){
            $campaign_url = get_site_url().'/campaign/'.$value->slug;
        }else{
            $campaign_url = get_site_url().'/preview/'.$value->slug;
        }


        // GET PROFILE PICTURE CAMPAIGNER
		$profile = $wpdb->get_results('SELECT user_pp_img as photo from '.$table_name3.' where user_id="'.$value->user_id.'"');
		if(isset($profile[0])){
			if($profile==null){
				$profile_photo = DJA_PLUGIN_URL . "assets/images/pp.jpg";
			}else{
				$profile_photo = $profile[0]->photo;
			}
		}else{
			$profile_photo = DJA_PLUGIN_URL . "assets/images/pp.jpg";
		}

		// GET USER NAME
		$args2 = array( 'blog_id' => 0, 'search' => $value->user_id, 'search_columns' => array( 'ID' ) );
        $get_name = get_users( $args2 );

        if($get_name==null){
            $campaigner_name = '-';
        }else{
            $nama_user = str_replace("'", "", $get_name[0]->user_firstname.' '.$get_name[0]->user_lastname);
            $campaigner_name = $nama_user; // nama asli
        }
	

	


	ob_start();

	if($show=='info'){
		echo html_entity_decode($value->information);
	}

	if($show=='info_update'){
		
		$campaign_update = $wpdb->get_results("SELECT * FROM $table_name5 where campaign_id='$id' ORDER BY id DESC");

		$dt = new DateTime($value->created_at);
	    $m = $dt->format('F');

	    if (strpos($m, 'January') !== false ) { $m = 'Januari'; }
	    elseif(strpos($m, 'February') !== false ) { $m = 'Februari'; }

	    $dt = $m.', '.$dt->format('j Y');
	    

		if($campaign_update==null){ 
			echo '
	    	<div id="kabar-terbaru-donasi">
				  <ul class="timeline" style="margin-top: 50px;">
					<li class="timeline-milestone is-start" style="height: 50px;">
				      <div class="timeline-action">
				      	<span class="date">'.$dt.'</span>
				        <h3 class="title">Campaign is published</h3>
				      </div>
				    </li>
				  </ul>
	    	</div>
	    	';

    	}else{

    		$data_info_update = '';
    		foreach ($campaign_update as $value) { 

			  	$readtime = new donasiaja_readtime();
				$time_update = $readtime->time_donation($value->created_at);

		  		$data_info_update .= '

				    <li class="timeline-milestone is-current">
				      <div class="timeline-action is-expandable expanded">
					        <span class="date">'.$dt.'</span>
					        <h3 class="title">'.$value->title.'</h3>
					        <div class="content">
					        	'.$value->information.'
					        </div>
				      </div>
				    </li>
			    ';

			}

    		$info_update = '
	    	<div id="kabar-terbaru-donasi">
				  <ul class="timeline" style="margin-top: 50px;">
				  	'.$data_info_update.'
				  	<li class="timeline-milestone is-start" style="height: 50px;">
				      <div class="timeline-action">
				      	<span class="date">'.$dt.'</span>
				        <h3 class="title">Campaign is published</h3>
				      </div>
				    </li>
				  </ul>
	    	</div>';

	    	echo $info_update;

    	}
	}

	if($show=='list_donatur'){

		$donasi = $wpdb->get_results("SELECT * FROM $table_name2 where campaign_id='$id' and status='1' ORDER BY id DESC limit 0,5 ");
		$rand_id = d_randomString(5);

		echo '
			<style>.donation_box {width:520px;}@media only screen and (max-width:480px) {.donation_box {width:100%;}}</style>
			<div class="donation_box black" style="background:#F4F8FD;">

			    <div id="box_'.$rand_id.'">';

				foreach ($donasi as $data) {

		        	$readtime = new donasiaja_readtime();
					$donation_time = $readtime->time_donation($data->created_at);

					$general_status = $value->general_status;
				    $allocation_title = $value->allocation_title;
				    $allocation_others_title = $value->allocation_others_title;

				    if($general_status=='1'){
				        if($allocation_title=='1'){
				            $allocation_title = 'Donasi';
				        }elseif($allocation_title=='2'){
				            $allocation_title = 'Zakat';
				        }else{
				            $allocation_title = $allocation_others_title;
				        }
				    }else{
				        $allocation_title = 'Donasi';
				    }

				    $donatur_name = $data->name;
					$anonim = 'Orang Baik';
					if($data->anonim=='1'){
						$donatur_name = $anonim_text;
					}

					echo '

					        <div class="donation_inner_box" style="background:#ffffff;">
					            <div class="donation_name">'.$donatur_name.'<span class="donation_time"><span class="dashicons dashicons-clock"></span>'.$donation_time.'</span>
					            </div>
					            <div class="donation_total" style="color: #23374d;font-weight:normal;">'.$allocation_title.' <b>Rp '.number_format($data->nominal,0,",",".").'</b></div>
					            <div class="donation_comment">'.str_replace('\\', '', $data->comment).'</div>
					        </div>
						        
						
					';

				}

			echo '
				</div>

			    <div id="box_btn_'.$rand_id.'" class="donation_button">
			        <div class="loadmore_info"></div>
			        <button id="'.$rand_id.'" class="load_list_donatur" data-id="'.$id.'" data-count="2" data-fullanonim="true" data-anonim="Hamba Allah" style="height: 45px !important;border-radius:45px;margin-top:10px;">Loadmore</button>
				</div>
			</div>';

	}

	if($show=='total_terkumpul'){
		echo 'Rp '.number_format($total_terkumpul,0,",",".");
	}

	if($show=='jumlah_donasi'){
		echo $jumlah_donasi .' Donasi';
	}

	if($show=='full_list'){

	    $view = '
		<ul id="section_'.$id.'" class="cards__campaign cards__list">
		  <li class="cards__item" style="margin:0;margin-top:10px;margin-bottom:10px;padding-bottom:5px;">
		  <a href="'.$campaign_url.'">
	      <div class="card__">
	        <div class="card__image"><img src="'.$image_url.'"></div>
	        <div class="card__content content_1">
	          <div class="card__title">'.$value->title.'</div>
	        </div>
	        <div class="card__content content_2">
	          <div class="card__text campaigner_name">'.$campaigner_name.'<div class="verified_checklist"><img alt="Image" src="'.DJA_PLUGIN_URL.'assets/images/check.png"></div></div>
	          <div class="card__text donation_collected" style="color:'.$theme_color.'">Rp '.number_format($total_terkumpul,0,",",".").'<span class="donation_collected_text">terkumpul</span></div>
	          <div style="height:4px; width:100%;background:#eaeaea;border-radius:4px;"><div style="height:4px; width:'.$persen_width.'%;background:'.$progressbar_color.';border-radius:4px;" title="'.$persen_width.'%"></div></div>
	          '.$icon_donasi.'
	        </div>
	      </div>
	      </a>
	      </li>
	    </ul>
		';

		echo $view;
			
	}

	if($show=='full_card'){

	    $view = '
		<div class="cards__item" style="padding:0;width:100%;margin-top:10px;margin-bottom:10px;">
		  <a href="'.$campaign_url.'">
	      <div class="card__">
	        <div class="card__image"><img src="'.$image_url.'"></div>
	        <div class="card__content content_1">
	          <div class="card__title">'.$value->title.'</div>
	        </div>
	        <div class="card__content content_2">
	          <div class="card__text campaigner_name">'.$campaigner_name.'<div class="verified_checklist"><img alt="Image" src="'.DJA_PLUGIN_URL.'assets/images/check.png"></div></div>
	          <div class="card__text donation_collected" style="color:'.$theme_color.'">Rp '.number_format($total_terkumpul,0,",",".").'<span class="donation_collected_text">terkumpul</span></div>
	          <div style="height:4px; width:100%;background:#eaeaea;border-radius:4px;"><div style="height:4px; width:'.$persen_width.'%;background:'.$progressbar_color.';border-radius:4px;" title="'.$persen_width.'%"></div></div>
	          '.$icon_donasi.'
	        </div>
	      </div>
	      </a>
	    </div>
		';

		echo $view;
			
	}

	if($show=='cta_form'){

		// print_r($value);
			
	}

	if($show=='progress'){

	    $progress = '
	        <div class="card__content content_2" style="padding:20px 10px 40px 10px;height:auto;border-radius: 4px;">
	          <div class="card__text donation_collected" style="color:'.$theme_color.'">Rp '.number_format($total_terkumpul,0,",",".").'<span class="donation_collected_text">terkumpul</span></div>
	          <div style="height:4px; width:100%;background:#eaeaea;border-radius:4px;"><div style="height:4px; width:'.$persen_width.'%;background:'.$progressbar_color.';border-radius:4px;" title="'.$persen_width.'%"></div></div>
	          '.$icon_donasi.'
	        </div>
		';

		echo $progress;
			
	}

	$output = ob_get_clean();
	return $output;

}
add_shortcode( 'donasiaja_campaign', 'donasiaja_campaign_shortcode_func' );



function donasiaja_socialproof_shortcode_func( $atts, $content = "" ) {
	
	global $wpdb;
	$table_name   = $wpdb->prefix . "dja_settings";
	$table_name2  = $wpdb->prefix . "dja_donate";
	$table_name3  = $wpdb->prefix . "dja_campaign";
	$table_name4  = $wpdb->prefix . "dja_users";

	$atts = shortcode_atts( array(
		'campaign' => null,
		'close' => null,
	), $atts, 'donasiaja_socialproof' );


	$campaign = '';
	if($atts['campaign']!==null){
  		$campaign = $atts['campaign'];
  	}

  	$close = 'hide';
	if($atts['close']!==null){
  		$close = $atts['close'];
  	}
  	if($close=='show'){
  		$close = 'true';
  	}else{
  		$close = 'false';
  	}


	// Settings
    $query_settings = $wpdb->get_results('SELECT data from '.$table_name.' where type="anonim_text" or type="socialproof_text" or type="socialproof_settings" ORDER BY id ASC');
    $anonim_text    	  = $query_settings[0]->data;
    $socialproof_text     = $query_settings[1]->data;
    $socialproof_settings = $query_settings[2]->data;

    $socialproof_setting  = json_decode($socialproof_settings, true);
    $popup_style    = $socialproof_setting['settings'][0];
    $position       = $socialproof_setting['settings'][1];
    $time_set       = $socialproof_setting['settings'][2];
    $delay          = $socialproof_setting['settings'][3];
    $data_load      = $socialproof_setting['settings'][4];

    // popup_style
    if($popup_style=='rounded'){
        $set_style = ' s-rounded';
    }elseif($popup_style=='flying_boxed'){
        $set_style = ' s-flying';
    }elseif($popup_style=='flying_rounded'){
        $set_style = ' s-rounded s-flying';
    }else{
        $set_style = '';
    }

    // delay
    $delay = $delay*1000;

    // data_load
    $total = $data_load;

    // time
    $time = $time_set;

    // title
    $title = $socialproof_text;

    // position
    $position_data = explode('_', $position);
	$p_gravity  = $position_data[0];
	$p_position = $position_data[1];

	// campaign
	if($campaign!=''){
    	$data_donasi = $wpdb->get_results("SELECT a.id, a.campaign_id, a.user_id, a.invoice_id, a.name, a.anonim, a.created_at, b.title, c.user_pp_img FROM $table_name2 a
		left JOIN $table_name3 b ON b.campaign_id = a.campaign_id
		left JOIN $table_name4 c ON c.user_id = a.user_id
		where a.status='1' and a.campaign_id='$campaign' ORDER BY id DESC LIMIT 0,$total ");
    }else{
    	$data_donasi = $wpdb->get_results("SELECT a.id, a.campaign_id, a.user_id, a.invoice_id, a.name, a.anonim, a.created_at, b.title, c.user_pp_img FROM $table_name2 a
		left JOIN $table_name3 b ON b.campaign_id = a.campaign_id
		left JOIN $table_name4 c ON c.user_id = a.user_id
		where a.status='1' ORDER BY id DESC LIMIT 0,$total ");
    }
	

	$data_donasinya = '';
	foreach ($data_donasi as $value) {
		
		$donatur_name = $value->name;
		if($value->anonim=='1'){
			$donatur_name = $anonim_text;
		}

		$data_field = array();
	    $data_field[ '{campaign_title}' ] = $value->title;
	   
		$titlenya = strtr($title, $data_field);
		
		$pp = '';
		if($value->user_pp_img!=''){
			$pp = $value->user_pp_img;
		}

		$the_time = donasiaja_readtime($value->created_at);

		$donatur_name = str_replace("'",'',$donatur_name);
		$donatur_name = str_replace('"','',$donatur_name);

		$title_campaign = str_replace("'",'',$titlenya);
		$title_campaign = str_replace('"','',$title_campaign);
		
		$data_donasinya .= '{"content": ["'.$donatur_name.'", "'.$the_time.'", "'.$title_campaign.'", "'.$pp.'", "'.$value->campaign_id.'"]},';
	}

	ob_start();


	echo '<script src="'.DJA_PLUGIN_URL.'assets/js/toastify.js"></script>';

	echo '
		<script>

		Array.prototype.getRandomColor= function(cut){
		    var i= Math.floor(Math.random()*this.length);
		    if(cut && i in this){
		        return this.splice(i, 1)[0];
		    }
		    return this[i];
		}
		var avatar_colors= ["#D94452", "#FA6C51", "#F5B945", "#9ED26A", "#35BA9B", "#4FC0E8", 
		"#9579DA", "#E3B692", "#E5C3C1", "#E7CDAC"];
		splittedContent = [
				'.$data_donasinya.'
	        ];
		function loopSplit(splittedContent) {
		    for (var i = 0; i < splittedContent.length; i++) {
		        (function (i) {
		        })(i);
		    };
		}
		loopSplit(splittedContent);
				
		var d = 0, howManyTimes = splittedContent.length;

		function f_socialproof() {

		  	var name 	= splittedContent[d].content[0];
		    var time 	= " - "+splittedContent[d].content[1];
		    var title 	= splittedContent[d].content[2];
		    var pp_url 	= splittedContent[d].content[3];
		    var c_id 	= splittedContent[d].content[4];
		    show_color_avatar = "";
		    if(pp_url!=""){
		    	show_color_avatar = "display:none;";
		    }
		    show_img_avatar = "";
		    if(pp_url==""){
		    	show_img_avatar = "display:none;";
		    }
		    var show_time = "'.$time.'";
		    if(show_time=="hide"){
		    	time = "";
		    }
		    setTimeout(function() {
		       Toastify({
				  text: "<div class=dsproof-container id="+c_id+"><div class=dsproof-avatar style=background:"+avatar_colors.getRandomColor()+";"+show_color_avatar+">"+name.substring(0, 1)+"</div><div class=dsproof-avatar style="+show_img_avatar+"><img src="+pp_url+"></div><div class=dsproof-content><div class=dsproof-name>"+name+"</div><div class=dsproof-title>"+title+"</div><div class=dsproof-verified><img src='.DJA_PLUGIN_URL.'assets/images/check.png'.'><span>Verified"+time+"</span></div><div></div>",
				  className: "donasiaja-socialproof'.$set_style.'",
				  escapeMarkup : false,
				  gravity: "'.$p_gravity.'",
				  position: "'.$p_position.'",
				  close: '.$close.',
				  duration: 5000,
				  style: {
				    background: "linear-gradient(to right, #ffffff, #ffffff)",
				  }
				}).showToast();
			}, 1000)
		  d++;
		  if (d < howManyTimes) {
		    setTimeout(f_socialproof, '.$delay.');
		  }
		}
		if(splittedContent.length>=1){
			f_socialproof();  
		} 
		</script>

	';

	echo '<style>
		.donasiaja-socialproof{line-height: 1.5;border-radius:6px;max-width:360px;height:auto;padding-right:30px!important;z-index:9999;background:#fff!important;box-shadow: 0 3px 6px -1px rgba(0, 0, 0, 0.06),0 10px 36px -4px rgba(77, 96, 232, 0.09) !important;}.donasiaja-socialproof .toast-close{position:absolute;right:0;color:#fff;margin-top:-16px!important;background:#0000004f;width:25px!important;height:25px!important;font-size:13px!important;text-align:center!important;padding:2px!important;opacity:1;top:10px}.dsproof-avatar{border-radius:4px;width:50px;height:50px;text-align:center;position:absolute;margin-left:-7px;margin-top:0px;font-size:32px;font-weight:700;color:#fffc;font-family:Lato,FontAwesome,lato,sans-serif!important}.dsproof-avatar img{width:50px;height:50px;border-radius:4px;}.dsproof-content{margin-left:54px;color:#888;font-size:11px;font-family:Lato,FontAwesome,lato,sans-serif!important}.dsproof-name{font-size:13px;font-weight:700;color:#35363c;position:absolute;margin-top:-3px}.dsproof-title{color:#656577;padding-top:16px;padding-bottom:2px}.dsproof-verified{font-size:10px;color:#b0b0c6;margin-bottom:2px;}.dsproof-verified span{padding-left:15px}.dsproof-verified img{width:12px;position:absolute;margin-top:2px}.toastify{padding:12px 20px;padding-top:12px!important;color:#fff;display:inline-block;box-shadow:0 3px 6px -1px rgba(0,0,0,.12),0 10px 36px -4px rgba(77,96,232,.3);background:-webkit-linear-gradient(315deg,#73a5ff,#5477f5);background:linear-gradient(135deg,#73a5ff,#5477f5);position:fixed;opacity:0;transition:all .4s cubic-bezier(.215,.61,.355,1);cursor:pointer;text-decoration:none;z-index:2147483647}.toastify.on{opacity:1}.toast-close{opacity:.4;padding:0 5px}.toastify-right{right:15px}.toastify-left{left:15px}.toastify-top{top:-150px}.toastify-bottom{bottom:-150px}.toastify-rounded{}.toastify-avatar{width:1.5em;height:1.5em;margin:-7px 5px;border-radius:2px}.toastify-center{margin-left:auto;margin-right:auto;left:0;right:0;max-width:fit-content;max-width:-moz-fit-content}@media only screen and (max-width:360px){.toastify-left,.toastify-right{margin-left:auto;margin-right:auto;left:0;right:0;max-width:fit-content}} .donasiaja-socialproof.s-rounded .dsproof-avatar{border-radius: 50px;} .donasiaja-socialproof.s-rounded .dsproof-avatar img{border-radius: 50px;}
		.donasiaja-socialproof.s-rounded {min-height:77px !important;height:auto !important;}
		.donasiaja-socialproof.s-rounded .dsproof-avatar {margin-top:0px;}
		.donasiaja-socialproof.s-flying { background: transparent !important;box-shadow:none !important;}
		.donasiaja-socialproof.s-flying .dsproof-avatar { box-shadow: 0 3px 6px -1px rgba(0, 0, 0, 0.06),0 10px 36px -4px rgba(77, 96, 232, 0.04) !important;}
		.donasiaja-socialproof.s-flying .dsproof-content { background: #fff;padding: 10px 20px 10px 16px;border-radius: 4px;box-shadow: 0 3px 6px -1px rgba(0, 0, 0, 0.06),0 10px 36px -4px rgba(77, 96, 232, 0.04)}
	</style>';
	$output = ob_get_clean();
	return $output;

}
add_shortcode( 'donasiaja_socialproof', 'donasiaja_socialproof_shortcode_func' );


