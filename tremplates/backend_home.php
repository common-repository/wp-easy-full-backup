<div class="wrap">
    <h1>
        <?php _e('Backup WordPress site','wpbackup'); ?>
    </h1>
    <div>
        <p id="admin-email-description" class="description">
            <?php _e('Make sure to remove files created by the site to store files on the server is not safe !!!','wpbackup'); ?>           
        </p>
    </div>
</div>
<div id="dialog">
    <div style="margin-top: 20px; margin-bottom: 20px;">
        <table style=" text-align: center; width: 300px;">
            <tr>
                <td>
                    <a href="#" class="button button-primary start-backup">
                        <?php _e('Create a copy of the website','wpbackup'); ?>
                    </a>
                </td>
                <td>
                    <a href="#" class="button button-primary clear-folder">
                         <?php _e('Clear the directory and temporary files','wpbackup'); ?>
                    </a>
                </td>
            </tr>
        </table>
    </div>
    <div id="steps">
        <h3>
            <?php _e('Stages of site archive','wpbackup'); ?>
        </h3>
        <table style="width: 200px;">
            <tr>
                <td>
                    <b><?php _e('Create database copies','wpbackup'); ?>:</b>
                </td>
                <td>
                    <div id="step1">
                        <img class="step1_i_preloader" src="<?php echo WEFB_BACKUP_URL; ?>/assets/preloader.gif" />
                        <img class="step1_i_ok" src="<?php echo WEFB_BACKUP_URL; ?>/assets/ok.png" />
                        <img class="step1_i_no" src="<?php echo WEFB_BACKUP_URL; ?>/assets/no.png" />
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <b><?php _e('Archiving site','wpbackup'); ?>:</b>
                </td>
                <td>
                    <div id="step2">
                        <img class="step2_i_preloader" src="<?php echo WEFB_BACKUP_URL; ?>/assets/preloader.gif" />
                        <img class="step2_i_ok" src="<?php echo WEFB_BACKUP_URL; ?>/assets/ok.png" />
                        <img class="step2_i_no" src="<?php echo WEFB_BACKUP_URL; ?>/assets/no.png" />
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <b><?php _e('Delete temporary files','wpbackup'); ?>:</b>
                </td>
                <td>
                    <div id="step3">
                        <img class="step3_i_preloader" src="<?php echo WEFB_BACKUP_URL; ?>/assets/preloader.gif" />
                        <img class="step3_i_ok" src="<?php echo WEFB_BACKUP_URL; ?>/assets/ok.png" />
                        <img class="step3_i_no" src="<?php echo WEFB_BACKUP_URL; ?>/assets/no.png" />
                    </div>
                </td>
            </tr>
        </table>
    </div>

    
   <div>
        <h2><?php _e('Backups','wpbackup'); ?>:</h2>
    </div>
    <?php 
    $fh = opendir(WEFB_BACKUP_ZIP_DIR);
    while ($file = readdir($fh)) {
        if ($file != '.' && $file != '..' && $file != 'index.html') {
            echo '<a href="'.WEFB_BACKUP_ZIP_URL.'/'.$file.'">'.$file.'</a><br />';
        }
    } 
    ?>
    <hr />
</div>


<style>
    #steps img {
        width: 20px;
        display: none;
    }
    #steps td {
        padding: 8px;
    }
</style>
<script>
    var host = location.protocol+'//'+location.host+"/";
    jQuery(".start-backup").click(function() {
        jQuery(".step1_i_preloader").show();
        
        jQuery.ajax({
           type: "POST",
           url: host+"wp-admin/admin-ajax.php?action=step_sql",
           data: ({start_sql : 'true', security: '<?php echo $ajax_nonce; ?>'}),
           success: function(res)
           {
            if (res === 'ok')
            {
                jQuery(".step1_i_preloader").hide();
                jQuery(".step1_i_ok").show();
                jQuery(".step2_i_preloader").show();
                
                jQuery.ajax({
                   type: "POST",
                   url: host+"wp-admin/admin-ajax.php?action=step_arch",
                   data: ({start_arch : 'true', security: '<?php echo $ajax_nonce; ?>'}),
                   success: function(res)
                   {
                    if (res === 'ok')
                    {
                        jQuery(".step2_i_preloader").hide();
                        jQuery(".step2_i_ok").show();
                        jQuery(".step3_i_preloader").show();
                        
                        jQuery.ajax({
                            type: "POST",
                            url: host+"wp-admin/admin-ajax.php?action=step_deltmp",
                            data: ({start_del : 'true', security: '<?php echo $ajax_nonce; ?>'}),
                            success: function(res)
                            {
                                if (res === 'ok')
                                {
                                    jQuery(".step3_i_preloader").hide();
                                    jQuery(".step3_i_ok").show();
                                    setTimeout(function() { location.reload(); }, 1500);
                                } else {
                                    jQuery(".step3_i_preloader").hide();
                                    jQuery(".step3_i_no").show();
                                    alert("<?php _e('Could not delete the temporary database dump file','wpbackup'); ?>");
                                }
                            }
                        });

                    } else {
                        jQuery(".step2_i_preloader").hide();
                        jQuery(".step2_i_no").show();
                        alert("<?php _e('Could not archive site','wpbackup'); ?>");
                    }
                   }
                });
            } else {
                jQuery(".step1_i_preloader").hide();
                jQuery(".step1_i_no").show();
                alert("<?php _e('Сould not create a database dump','wpbackup'); ?>");
            }
           }
        });
        return false;
    });
    
    jQuery(".clear-folder").click(function() {
        jQuery.ajax({
           type: "POST",
           url: host+"wp-admin/admin-ajax.php?action=clear_folder",
           data: ({clear_folder : 'true', security: '<?php echo $ajax_nonce; ?>'}),
           success: function(res) {
               if (res === 'ok')
               {
                   jQuery(".clear-folder").html("<?php _e('Cleaning complete','wpbackup'); ?>");
                   setTimeout(function() { location.reload(); }, 1500);
               } else {
                 alert("<?php _e('Error cleaning directory','wpbackup'); ?>");  
               }
                setTimeout(function() { location.reload(); }, 1500);
           }
       });
        return false;
    });
</script>