<?php
function wefb_step_sql_callback()
{
    ini_set('max_execution_time', 900);
    ini_set('memory_limit','1024M');
    check_ajax_referer( 'HOUIAF&YHASD8asdhg8asfdtg', 'security' );
    
    if (!empty($_POST['start_sql']) && $_POST['start_sql'] == 'true')
    {
        $model = new wefb_rwBackupModel();
        $res = $model->wefb_make_sql_backup(WEFB_BACKUP_ZIP_DIR."/DATABASE_BACKUP.sql");
        if ($res == 'ok')
        {
            echo 'ok';
        } else {
            echo 0;
        }
    }
    wp_die();
}
add_action( 'wp_ajax_step_sql', 'wefb_step_sql_callback'); 

function wefb_step_arch_callback()
{
    ini_set('max_execution_time', 900);
    ini_set('memory_limit','1024M');
    check_ajax_referer( 'HOUIAF&YHASD8asdhg8asfdtg', 'security' );
    
    if (!empty($_POST['start_arch']) && $_POST['start_arch'] == 'true')
    {
        $otpt = WEFB_BACKUP_ZIP_DIR."/backup_".date("d_m_Y_H_i_s").".zip";   
        $inpt = get_home_path();
        
        $model = new wefb_rwBackupModel();
        $res = $model->wefb_zipData($inpt, $otpt);

        if ($res == 'ok')
        {
            echo 'ok';
        } else {
            echo 0;
        } 
    }
    
    wp_die();
}
add_action( 'wp_ajax_step_arch', 'wefb_step_arch_callback'); 

function wefb_step_deltmp_callback()
{
    check_ajax_referer( 'HOUIAF&YHASD8asdhg8asfdtg', 'security' );
    $inpt = get_home_path();
    
    if (!empty($_POST['start_del']) && $_POST['start_del'] == 'true')
    {
        if (unlink(WEFB_BACKUP_ZIP_DIR."/DATABASE_BACKUP.sql"))
        {
            echo 'ok';
        } else {
            echo 'no';
        } 
    }
    wp_die();
}
add_action( 'wp_ajax_step_deltmp', 'wefb_step_deltmp_callback');

function wefb_clear_folder_callback()
{
    check_ajax_referer( 'HOUIAF&YHASD8asdhg8asfdtg', 'security' );
    
    if (!empty($_POST['clear_folder']) && $_POST['clear_folder'] == 'true')
    {
        $fh = opendir(WEFB_BACKUP_ZIP_DIR);
        while ($file = readdir($fh)) {
            if ($file != '.' && $file != '..' && $file != 'index.html') {
                unlink(WEFB_BACKUP_ZIP_DIR.'/'.$file);
            }
        } 
        @unlink(WEFB_BACKUP_ZIP_DIR."/DATABASE_BACKUP.sql");
        echo 'ok';
    }
    wp_die();
}
add_action( 'wp_ajax_clear_folder', 'wefb_clear_folder_callback');