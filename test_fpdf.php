<?php
$fpdf_dir = 'C:/xampp/htdocs/Invoice/includes/fpdf/';
echo "fpdf.php exists: " . (file_exists($fpdf_dir . 'fpdf.php') ? 'YES' : 'NO') . "<br>";
echo "font/ dir exists: " . (is_dir($fpdf_dir . 'font') ? 'YES' : 'NO') . "<br>";
echo "Font files:<br>";
foreach(glob($fpdf_dir . 'font/*.php') as $f) {
    echo " - " . basename($f) . "<br>";
}
?>