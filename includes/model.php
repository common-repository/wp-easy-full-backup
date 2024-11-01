<?php
class wefb_rwBackupModel
{
    function wefb_make_sql_backup($backupFile = NULL)
    {
        global $wpdb;
        $tables = $wpdb->get_col('SHOW TABLES');
        $output = '';
        
        foreach ($tables as $table) {
            if (empty($wp_db_exclude_table) || (!(in_array($table, $wp_db_exclude_table)))) {
                $result = $wpdb->get_results("SELECT * FROM {$table}", ARRAY_N);
                $row2 = $wpdb->get_row('SHOW CREATE TABLE ' . $table, ARRAY_N);
                $output .= "\n\n" . $row2[1] . ";\n\n";
                for ($i = 0; $i < count($result); $i++) {
                    $row = $result[$i];
                    $output .= 'INSERT INTO ' . $table . ' VALUES(';
                    for ($j = 0; $j < count($result[0]); $j++) {
                        $row[$j] = $wpdb->_real_escape($row[$j]);
                        $output .= (isset($row[$j])) ? '"' . $row[$j] . '"' : '""';
                        if ($j < (count($result[0]) - 1)) {
                            $output .= ',';
                        }
                    }
                    $output .= ");\n";
                }
                $output .= "\n";
            }
        }
	$wpdb->flush();
        
	$file = fopen($backupFile,"w");
	$len = fwrite($file,$output);
	fclose($file);
	if($len != 0){
		return 'ok';
	}else{
		return 'no';
	}
    }
    
    function wefb_zipData($source, $destination) {
        if (substr(PHP_OS, 0, 3) == "WIN" || "Windows")
        { $DS = '\\'; } else { $DS = '/'; }
        
        if (extension_loaded('zip')) {
            if (file_exists($source)) {
                $zip = new ZipArchive();
                if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
                    $source = realpath($source);
                    if (is_dir($source)) {
                        $iterator = new RecursiveDirectoryIterator($source);

                        $iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
                        $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
                        foreach ($files as $file) {

                            if ($file != '.' && $file != '..')
                            {
                                $file = realpath($file);
                                if (is_dir($file)) {
                                    $zip->addEmptyDir(str_replace($source . $DS, '', $file));
                                } else if (is_file($file)) {
                                    $zip->addFromString(str_replace($source . $DS, '', $file), file_get_contents($file));  
                                }  
                            }
                            
                        }
                    } else if (is_file($source)) {
                        $zip->addFromString(basename($source), file_get_contents($source));
                    }
                }
                $zip->close();
                return 'ok';
            }
        }
        return false;
    }
   
}