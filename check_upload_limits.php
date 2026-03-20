<?php

echo "--- LIMITI PHP ATTUALI ---\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size:       " . ini_get('post_max_size') . "\n";
echo "memory_limit:        " . ini_get('memory_limit') . "\n";
echo "max_execution_time:  " . ini_get('max_execution_time') . " secondi\n";
echo "max_input_time:      " . ini_get('max_input_time') . " secondi\n";
echo "--------------------------\n";

$file_ini = php_ini_loaded_file();
echo "File php.ini caricato: " . ($file_ini ? $file_ini : "Nessuno") . "\n";
