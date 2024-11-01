<?php
/*
    Plugin Name: Wordpress easy full backup
    Description: Full backup site + database
    Version: 1.4
    Author: Yasnikov Andrey
    Author URI: http://realweb.su/
    Plugin URI: https://github.com/realweb-su/wp-easy-full-backup
*/
define('WEFB_BACKUP_DIR', plugin_dir_path(__FILE__));
define('WEFB_BACKUP_URL', plugin_dir_url(__FILE__));

/* check backup folder */
    $upload_dir = wp_upload_dir();
    define('WEFB_BACKUP_ZIP_DIR', $upload_dir['basedir']."/wp-easy-full-backup");
    define('WEFB_BACKUP_ZIP_URL', $upload_dir['baseurl']."/wp-easy-full-backup");
    
    if (!file_exists(WEFB_BACKUP_ZIP_DIR))
    {
        if (!is_dir(WEFB_BACKUP_ZIP_DIR))
        {
            mkdir(WEFB_BACKUP_ZIP_DIR);
            $fp = fopen(WEFB_BACKUP_ZIP_DIR."/index.html", "w");
            fwrite($fp, '');
            fclose($fp);
        }
    }
/* =================== */


function wefb_realweb_backup_loaddomain() {
    if (function_exists('load_plugin_textdomain')) {
        load_plugin_textdomain('wpbackup',false, dirname( plugin_basename( __FILE__ ) ).'/languages');
    }
}
add_action('init', 'wefb_realweb_backup_loaddomain');


include_once WEFB_BACKUP_DIR."includes/model.php";
include_once WEFB_BACKUP_DIR."includes/ajax.php";


if (is_admin())
{
    add_action( 'admin_menu', 'wefb_rwbackup_menu' );
        function wefb_rwbackup_menu() {
            add_menu_page( 
                __('Full backup of website','wpbackup'), 
                __('WP Backup','wpbackup'), 
                'manage_options', 
                'wefb-rw-backup', 
                'wefb_rw_backup', 
                'dashicons-album', 
                150 
            );
        }
        
    function wefb_rw_backup()
    {
        $ajax_nonce = wp_create_nonce("HOUIAF&YHASD8asdhg8asfdtg");
        $model = new wefb_rwBackupModel();
        
        include_once WEFB_BACKUP_DIR."tremplates/backend_home.php";
    }    
}