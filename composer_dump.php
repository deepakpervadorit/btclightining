<?php
// Change the path to your Composer installation if necessary
$output = shell_exec('composer dump-autoload 2>&1');
echo "<pre>$output</pre>";
?>