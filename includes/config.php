<?php
// Debugging
ini_set('error_reporting', E_ALL);

// DATABASE INFORMATION
define('DATABASE_HOST', getenv('IP'));
define('DATABASE_NAME', 'u792171406_sh_invoice'); //invoicemgsys
define('DATABASE_USER', 'root');
define('DATABASE_PASS', '');


// COMPANY INFORMATION
define('COMPANY_LOGO', 'images/SH.png');
define('COMPANY_LOGO_WIDTH', '1000');
define('COMPANY_LOGO_HEIGHT', '1000');
define('COMPANY_NAME','Shenal Holdings');
define('COMPANY_ADDRESS_1','No.61');
define('COMPANY_ADDRESS_2','Halgasthota awariwatta');
define('COMPANY_ADDRESS_3','Katunayake');
define('COMPANY_COUNTY','Sri Lanka');
define('COMPANY_POSTCODE','-----');

define('COMPANY_NUMBER','Company No: -------'); // Company registration number
define('COMPANY_VAT', 'Company VAT: -------'); // Company TAX/VAT number

// EMAIL DETAILS
define('EMAIL_FROM', 'sujithacostha4964@gmail.com'); // Email address invoice emails will be sent from
define('EMAIL_NAME', 'Invoice Mg System'); // Email from address
define('EMAIL_SUBJECT', 'Invoice default email subject'); // Invoice email subject
define('EMAIL_BODY_INVOICE', 'Invoice default body'); // Invoice email body
define('EMAIL_BODY_QUOTE', 'Quote default body'); // Invoice email body
define('EMAIL_BODY_RECEIPT', 'Receipt default body'); // Invoice email body

// OTHER SETTINFS
define('INVOICE_PREFIX', ''); // Prefix at start of invoice - leave empty '' for no prefix
define('INVOICE_INITIAL_VALUE', '1'); // Initial invoice order number (start of increment)
define('INVOICE_THEME', '#1a3c5e'); // Theme colour, this sets a colour theme for the PDF generate invoice
define('TIMEZONE', 'Asia/Colombo'); // Timezone - See for list of Timezone's http://php.net/manual/en/function.date-default-timezone-set.php
define('DATE_FORMAT', 'DD/MM/YYYY'); // DD/MM/YYYY or MM/DD/YYYY
define('CURRENCY', 'LKR'); // Currency symbol
define('ENABLE_VAT', true); // Enable TAX/VAT
define('VAT_INCLUDED', false); // Is VAT included or excluded?
define('VAT_RATE', '10'); // This is the percentage value

define('PAYMENT_DETAILS', ''); // Payment information
define('FOOTER_NOTE', 'Shenal Holdings-Invoice Management System');

// CONNECT TO THE DATABASE
$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

?>