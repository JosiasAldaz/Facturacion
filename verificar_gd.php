<?php
if (extension_loaded('gd') && function_exists('gd_info')) {
    echo "La extensión GD está instalada.";
} else {
    echo "La extensión GD no está instalada.";
}

?>