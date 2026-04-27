<?php
ini_set('display_errors', 0);
error_reporting(0);
ob_start();

include_once('includes/config.php');

// show PHP errors
ini_set('display_errors', 1);

// output any connection error
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

$action = isset($_POST['action']) ? $_POST['action'] : "";
$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

if ($action == 'email_invoice'){

	$fileId = $_POST['id'];
	$emailId = $_POST['email'];
	$invoice_type = $_POST['invoice_type'];
	$custom_email = $_POST['custom_email'];

	require_once('class.phpmailer.php');

	$mail = new PHPMailer(); // defaults to using php "mail()"

	$mail->AddReplyTo(EMAIL_FROM, EMAIL_NAME);
	$mail->SetFrom(EMAIL_FROM, EMAIL_NAME);
	$mail->AddAddress($emailId, "");

	$mail->Subject = EMAIL_SUBJECT;
	//$mail->AltBody = EMAIL_BODY; // optional, comment out and test
	if (empty($custom_email)){
		if($invoice_type == 'invoice'){
			$mail->MsgHTML(EMAIL_BODY_INVOICE);
		} else if($invoice_type == 'quote'){
			$mail->MsgHTML(EMAIL_BODY_QUOTE);
		} else if($invoice_type == 'receipt'){
			$mail->MsgHTML(EMAIL_BODY_RECEIPT);
		}
	} else {
		$mail->MsgHTML($custom_email);
	}

	$mail->AddAttachment("./invoices/".$fileId.".pdf"); // attachment

	if(!$mail->Send()) {
		 //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mail->ErrorInfo.'</pre>'
	    ));
	} else {
	   echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Invoice has been successfully send to the customer'
		));
	}

}

// download invoice csv sheet
if ($action == 'download_csv'){

    // output any connection error
    if ($mysqli->connect_error) {
        die('Error : ('.$mysqli->connect_errno .') '. $mysqli->connect_error);
    }

    $file_name = 'invoice-export-'.date('d-m-Y').'.csv';
    $file_path = 'downloads/'.$file_name;

    // Delete old CSV files
    $old_files = glob('downloads/invoice-export-*.csv');
    if ($old_files) {
        foreach ($old_files as $old_file) {
            if (is_file($old_file)) unlink($old_file);
        }
    }

    $file = fopen($file_path, "w");
    fwrite($file, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel
    chmod($file_path, 0777);

    // Manual CSV row formatter — works on all servers
    function format_csv_row($array) {
        $fields = array();
        foreach ($array as $field) {
            $field    = str_replace('"', '""', $field ?? '');
            $fields[] = '"' . $field . '"';
        }
        return implode(',', $fields) . "\r\n";
    }

    $query_table_columns_data = "SELECT i.invoice, i.invoice_date, i.invoice_due_date, 
                                    i.subtotal, i.shipping, i.discount, 
                                    i.vat, i.total, i.notes, i.invoice_type,
                                    i.status, c.name, c.email, c.address_1,
                                    c.address_2, c.town, c.county,
                                    c.postcode, c.phone, c.name_ship, c.address_1_ship,
                                    c.address_2_ship, c.town_ship, c.county_ship, c.postcode_ship
                                FROM invoices i
                                JOIN customers c ON c.invoice = i.invoice
                                ORDER BY i.invoice_date ASC";

    if ($result_column_data = mysqli_query($mysqli, $query_table_columns_data)) {

        $column_headers = array(
            'Invoice Number',
            'Invoice Date',
            'Invoice Due Date',
            'Subtotal',
            'Shipping',
            'Discount',
            'VAT',
            'Total',
            'Notes',
            'Invoice Type',
            'Status',
            'Customer Name',
            'Email Address',
            'Address Line 1',
            'Address Line 2',
            'Town',
            'County',
            'Postcode',
            'Phone Number',
            'Shipping Name',
            'Shipping Address Line 1',
            'Shipping Address Line 2',
            'Shipping Town',
            'Shipping County',
            'Shipping Postcode',
        );

        // Write headers
        fwrite($file, format_csv_row($column_headers));

        // Write data rows
        while ($column_data = $result_column_data->fetch_row()) {
            fwrite($file, format_csv_row($column_data));
        }

        // Close file before sending response
        fclose($file);

        echo json_encode(array(
            'status'  => 'Success',
            'message' => 'CSV has been generated. Download by <a href="download_file.php?file='.urlencode($file_name).'">clicking here</a>.'
        ));

    } else {
        fclose($file);
        echo json_encode(array(
            'status'  => 'Error',
            'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre>'
        ));
    }

    $mysqli->close();
}

// Create customer
if ($action == 'create_customer'){

	// invoice customer information
	// billing
	$customer_name = $_POST['customer_name']; // customer name
	$customer_email = $_POST['customer_email']; // customer email
	$customer_address_1 = $_POST['customer_address_1']; // customer address
	$customer_address_2 = $_POST['customer_address_2']; // customer address
	$customer_town = $_POST['customer_town']; // customer town
	$customer_county = $_POST['customer_county']; // customer county
	$customer_postcode = $_POST['customer_postcode']; // customer postcode
	$customer_phone = $_POST['customer_phone']; // customer phone number
	
	//shipping
	$customer_name_ship = $_POST['customer_name_ship']; // customer name (shipping)
	$customer_address_1_ship = $_POST['customer_address_1_ship']; // customer address (shipping)
	$customer_address_2_ship = $_POST['customer_address_2_ship']; // customer address (shipping)
	$customer_town_ship = $_POST['customer_town_ship']; // customer town (shipping)
	$customer_county_ship = $_POST['customer_county_ship']; // customer county (shipping)
	$customer_postcode_ship = $_POST['customer_postcode_ship']; // customer postcode (shipping)

	$query = "INSERT INTO store_customers (
					name,
					email,
					address_1,
					address_2,
					town,
					county,
					postcode,
					phone,
					name_ship,
					address_1_ship,
					address_2_ship,
					town_ship,
					county_ship,
					postcode_ship
				) VALUES (
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?
				);
			";

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param(
		'ssssssssssssss',
		$customer_name,$customer_email,$customer_address_1,$customer_address_2,$customer_town,$customer_county,$customer_postcode,
		$customer_phone,$customer_name_ship,$customer_address_1_ship,$customer_address_2_ship,$customer_town_ship,$customer_county_ship,$customer_postcode_ship);

	if($stmt->execute()){
		//if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message' => 'Customer has been created successfully!'
		));
	} else {
		// if unable to create invoice
		echo json_encode(array(
			'status' => 'Error',
			'message' => 'There has been an error, please try again.'
			// debug
			//'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
		));
	}

	//close database connection
	$mysqli->close();
}

// Create invoice
if ($action == 'create_invoice'){

	// invoice customer information
	// billing
	$customer_name = $_POST['customer_name']; // customer name
	$customer_email = $_POST['customer_email']; // customer email
	$customer_address_1 = $_POST['customer_address_1']; // customer address
	$customer_address_2 = $_POST['customer_address_2']; // customer address
	$customer_town = $_POST['customer_town']; // customer town
	$customer_county = $_POST['customer_county']; // customer county
	$customer_postcode = $_POST['customer_postcode']; // customer postcode
	$customer_phone = $_POST['customer_phone']; // customer phone number
	
	//shipping
	$customer_name_ship = $_POST['customer_name_ship']; // customer name (shipping)
	$customer_address_1_ship = $_POST['customer_address_1_ship']; // customer address (shipping)
	$customer_address_2_ship = $_POST['customer_address_2_ship']; // customer address (shipping)
	$customer_town_ship = $_POST['customer_town_ship']; // customer town (shipping)
	$customer_county_ship = $_POST['customer_county_ship']; // customer county (shipping)
	$customer_postcode_ship = $_POST['customer_postcode_ship']; // customer postcode (shipping)

	// invoice details
	$invoice_number = $_POST['invoice_id']; // invoice number
	// $custom_email = $_POST['custom_email']; // invoice custom email body
	$invoice_date = $_POST['invoice_date']; // invoice date
	$custom_email = $_POST['custom_email']; // custom invoice email
	$invoice_due_date = $_POST['invoice_due_date']; // invoice due date
	$invoice_subtotal = $_POST['invoice_subtotal']; // invoice sub-total
	$invoice_shipping = $_POST['invoice_shipping']; // invoice shipping amount
	$invoice_discount = $_POST['invoice_discount']; // invoice discount
	$invoice_vat = $_POST['invoice_vat']; // invoice vat
	$invoice_total = $_POST['invoice_total']; // invoice total
	$invoice_notes = $_POST['invoice_notes']; // Invoice notes
	$invoice_type = $_POST['invoice_type']; // Invoice type
	$invoice_status = $_POST['invoice_status']; // Invoice status

	// CHECK: Invoice exists and is NOT in 'Delete' status
    $check_query = "SELECT id FROM invoices WHERE invoice = '" . $invoice_number . "' AND status != 'Delete' LIMIT 1";
    $check_result = $mysqli->query($check_query);

    if ($check_result && $check_result->num_rows > 0) {
        echo json_encode(array(
            'status'  => 'Error',
            'message' => 'Invoice #' . $invoice_number . ' already exists and is active. Please use a different invoice number.'
        ));
        exit;
    }

	// insert invoice into database
	$query = "INSERT INTO invoices (
					invoice,
					custom_email,
					invoice_date, 
					invoice_due_date, 
					subtotal, 
					shipping, 
					discount, 
					vat, 
					total,
					notes,
					invoice_type,
					status
				) VALUES (
				  	'".$invoice_number."',
				  	'".$custom_email."',
				  	'".$invoice_date."',
				  	'".$invoice_due_date."',
				  	'".$invoice_subtotal."',
				  	'".$invoice_shipping."',
				  	'".$invoice_discount."',
				  	'".$invoice_vat."',
				  	'".$invoice_total."',
				  	'".$invoice_notes."',
				  	'".$invoice_type."',
				  	'".$invoice_status."'
			    );
			";
	// insert customer details into database
	$query .= "INSERT INTO customers (
					invoice,
					name,
					email,
					address_1,
					address_2,
					town,
					county,
					postcode,
					phone,
					name_ship,
					address_1_ship,
					address_2_ship,
					town_ship,
					county_ship,
					postcode_ship
				) VALUES (
					'".$invoice_number."',
					'".$customer_name."',
					'".$customer_email."',
					'".$customer_address_1."',
					'".$customer_address_2."',
					'".$customer_town."',
					'".$customer_county."',
					'".$customer_postcode."',
					'".$customer_phone."',
					'".$customer_name_ship."',
					'".$customer_address_1_ship."',
					'".$customer_address_2_ship."',
					'".$customer_town_ship."',
					'".$customer_county_ship."',
					'".$customer_postcode_ship."'
				);
			";

	// invoice product items
	foreach($_POST['invoice_product'] as $key => $value) {
	    $item_product = $value;
	    // $item_description = $_POST['invoice_product_desc'][$key];
	    $item_qty = $_POST['invoice_product_qty'][$key];
	    $item_price = $_POST['invoice_product_price'][$key];
	    $item_discount = $_POST['invoice_product_discount'][$key];
	    $item_subtotal = $_POST['invoice_product_sub'][$key];

	    // insert invoice items into database
		$query .= "INSERT INTO invoice_items (
				invoice,
				product,
				qty,
				price,
				discount,
				subtotal
			) VALUES (
				'".$invoice_number."',
				'".$item_product."',
				'".$item_qty."',
				'".$item_price."',
				'".$item_discount."',
				'".$item_subtotal."'
			);
		";

	}	

	// execute the query
	if($mysqli -> multi_query($query)){
		ob_clean();
		header('Content-Type: application/json');

		//if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message' => 'Invoice has been created successfully!'
		));
	
		exit;
	} else {
		// if unable to create invoice
		echo json_encode(array(
			'status' => 'Error',
			'message' => 'There has been an error, please try again.'
		));

		exit;
	}
	//close database connection
	$mysqli->close();

}

// Delete invoce
if($action == 'delete_invoice') {

	file_put_contents('debug.log', print_r($_POST, true));

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$id = $_POST["delete"];

	// the query
	// $id = $mysqli->real_escape_string($_POST['delete']);
	$query  = "DELETE FROM invoices      WHERE invoice = '" . $id . "';";
	$query .= "DELETE FROM customers     WHERE invoice = '" . $id . "';";
	$query .= "DELETE FROM invoice_items WHERE invoice = '" . $id . "';";

	if($mysqli -> multi_query($query)) {
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Invoice has been deleted successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	// close connection 
	$mysqli->close();

}

// Update Customer
if($action == 'update_customer') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$getID = $_POST['id']; // id

	// invoice customer information
	// billing
	$customer_name = $_POST['customer_name']; // customer name
	$customer_email = $_POST['customer_email']; // customer email
	$customer_address_1 = $_POST['customer_address_1']; // customer address
	$customer_address_2 = $_POST['customer_address_2']; // customer address
	$customer_town = $_POST['customer_town']; // customer town
	$customer_county = $_POST['customer_county']; // customer county
	$customer_postcode = $_POST['customer_postcode']; // customer postcode
	$customer_phone = $_POST['customer_phone']; // customer phone number
	
	//shipping
	$customer_name_ship = $_POST['customer_name_ship']; // customer name (shipping)
	$customer_address_1_ship = $_POST['customer_address_1_ship']; // customer address (shipping)
	$customer_address_2_ship = $_POST['customer_address_2_ship']; // customer address (shipping)
	$customer_town_ship = $_POST['customer_town_ship']; // customer town (shipping)
	$customer_county_ship = $_POST['customer_county_ship']; // customer county (shipping)
	$customer_postcode_ship = $_POST['customer_postcode_ship']; // customer postcode (shipping)

	// the query
	$query = "UPDATE store_customers SET
				name = ?,
				email = ?,
				address_1 = ?,
				address_2 = ?,
				town = ?,
				county = ?,
				postcode = ?,
				phone = ?,

				name_ship = ?,
				address_1_ship = ?,
				address_2_ship = ?,
				town_ship = ?,
				county_ship = ?,
				postcode_ship = ?

				WHERE id = ?

			";

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param(
		'sssssssssssssss',
		$customer_name,$customer_email,$customer_address_1,$customer_address_2,$customer_town,$customer_county,$customer_postcode,
		$customer_phone,$customer_name_ship,$customer_address_1_ship,$customer_address_2_ship,$customer_town_ship,$customer_county_ship,$customer_postcode_ship,$getID);

	//execute the query
	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Customer has been updated successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	//close database connection
	$mysqli->close();
	
}

// Update product
if($action == 'update_product') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// invoice product information
	$getID = $_POST['id']; // id
	$product_name = $_POST['product_name']; // product name
	$product_desc = $_POST['product_desc']; // product desc
	$product_price = $_POST['product_price']; // product price

	// the query
	$query = "UPDATE products SET
				product_name = ?,
				product_desc = ?,
				product_price = ?
			 WHERE product_id = ?
			";

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param(
		'ssss',
		$product_name,$product_desc,$product_price,$getID
	);

	//execute the query
	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Product has been updated successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	//close database connection
	$mysqli->close();
	
}


// Update Invoice
if ($action == 'update_invoice') {
 
    if ($mysqli->connect_error) {
        die('Error : (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }
 
    // ── Collect & escape all inputs ──────────────────────────────────────
    $id = $mysqli->real_escape_string($_POST['update_id']);
 
    // Billing
    $customer_name      = $mysqli->real_escape_string($_POST['customer_name']);
    $customer_email     = $mysqli->real_escape_string($_POST['customer_email']);
    $customer_address_1 = $mysqli->real_escape_string($_POST['customer_address_1']);
    $customer_address_2 = $mysqli->real_escape_string($_POST['customer_address_2']);
    $customer_town      = $mysqli->real_escape_string($_POST['customer_town']);
    $customer_county    = $mysqli->real_escape_string($_POST['customer_county']);
    $customer_postcode  = $mysqli->real_escape_string($_POST['customer_postcode']);
    $customer_phone     = $mysqli->real_escape_string($_POST['customer_phone']);
 
    // Shipping
    $customer_name_ship      = $mysqli->real_escape_string($_POST['customer_name_ship']);
    $customer_address_1_ship = $mysqli->real_escape_string($_POST['customer_address_1_ship']);
    $customer_address_2_ship = $mysqli->real_escape_string($_POST['customer_address_2_ship']);
    $customer_town_ship      = $mysqli->real_escape_string($_POST['customer_town_ship']);
    $customer_county_ship    = $mysqli->real_escape_string($_POST['customer_county_ship']);
    $customer_postcode_ship  = $mysqli->real_escape_string($_POST['customer_postcode_ship']);
 
    // Invoice meta
    $invoice_number   = $mysqli->real_escape_string($_POST['invoice_id']);
    $custom_email     = $mysqli->real_escape_string($_POST['custom_email']);
    $invoice_date     = $mysqli->real_escape_string($_POST['invoice_date']);
    $invoice_due_date = $mysqli->real_escape_string($_POST['invoice_due_date']);
    $invoice_subtotal = $mysqli->real_escape_string($_POST['invoice_subtotal']);
    $invoice_shipping = $mysqli->real_escape_string($_POST['invoice_shipping']);
    $invoice_discount = $mysqli->real_escape_string($_POST['invoice_discount']);
    $invoice_vat      = $mysqli->real_escape_string($_POST['invoice_vat']);
    $invoice_total    = $mysqli->real_escape_string($_POST['invoice_total']);
    $invoice_notes    = $mysqli->real_escape_string($_POST['invoice_notes']);
    $invoice_type     = $mysqli->real_escape_string($_POST['invoice_type']);
    $invoice_status   = $mysqli->real_escape_string($_POST['invoice_status']);
 
    // ── Delete old records, then re-insert ───────────────────────────────
    $query  = "DELETE FROM invoices      WHERE invoice = '" . $id . "';";
    $query .= "DELETE FROM customers     WHERE invoice = '" . $id . "';";
    $query .= "DELETE FROM invoice_items WHERE invoice = '" . $id . "';";
 
    // invoices
    $query .= "INSERT INTO invoices (
                    invoice,
                    custom_email,
                    invoice_date,
                    invoice_due_date,
                    subtotal,
                    shipping,
                    discount,
                    vat,
                    total,
                    notes,
                    invoice_type,
                    status
                ) VALUES (
                    '" . $invoice_number   . "',
                    '" . $custom_email     . "',
                    '" . $invoice_date     . "',
                    '" . $invoice_due_date . "',
                    '" . $invoice_subtotal . "',
                    '" . $invoice_shipping . "',
                    '" . $invoice_discount . "',
                    '" . $invoice_vat      . "',
                    '" . $invoice_total    . "',
                    '" . $invoice_notes    . "',
                    '" . $invoice_type     . "',
                    '" . $invoice_status   . "'
                );";
 
    // customers (no custom_email column in this table)
    $query .= "INSERT INTO customers (
                    invoice,
                    name,
                    email,
                    address_1,
                    address_2,
                    town,
                    county,
                    postcode,
                    phone,
                    name_ship,
                    address_1_ship,
                    address_2_ship,
                    town_ship,
                    county_ship,
                    postcode_ship
                ) VALUES (
                    '" . $invoice_number          . "',
                    '" . $customer_name           . "',
                    '" . $customer_email          . "',
                    '" . $customer_address_1      . "',
                    '" . $customer_address_2      . "',
                    '" . $customer_town           . "',
                    '" . $customer_county         . "',
                    '" . $customer_postcode       . "',
                    '" . $customer_phone          . "',
                    '" . $customer_name_ship      . "',
                    '" . $customer_address_1_ship . "',
                    '" . $customer_address_2_ship . "',
                    '" . $customer_town_ship      . "',
                    '" . $customer_county_ship    . "',
                    '" . $customer_postcode_ship  . "'
                );";
 
    // invoice line items — subtotal calculated server-side
    // because disabled inputs are not submitted by the browser
    foreach ($_POST['invoice_product'] as $key => $value) {
 
        $item_product  = $mysqli->real_escape_string($value);
        $item_qty      = (int)   $_POST['invoice_product_qty'][$key];
        $item_price    = (float) $_POST['invoice_product_price'][$key];
        $item_discount = $mysqli->real_escape_string($_POST['invoice_product_discount'][$key]);
 
        // calculate subtotal server-side
        $raw_subtotal = $item_qty * $item_price;
        if (!empty($item_discount)) {
            if (strpos($item_discount, '%') !== false) {
                $pct = (float) str_replace('%', '', $item_discount);
                $raw_subtotal -= ($raw_subtotal * $pct / 100);
            } else {
                $raw_subtotal -= (float) $item_discount;
            }
        }
        $item_subtotal = number_format(max(0, $raw_subtotal), 2, '.', '');
 
        $query .= "INSERT INTO invoice_items (
                    invoice,
                    product,
                    qty,
                    price,
                    discount,
                    subtotal
                ) VALUES (
                    '" . $invoice_number . "',
                    '" . $item_product   . "',
                    '" . $item_qty       . "',
                    '" . $item_price     . "',
                    '" . $item_discount  . "',
                    '" . $item_subtotal  . "'
                );";
    }
 
    header('Content-Type: application/json');
 
    if ($mysqli->multi_query($query)) {
        echo json_encode([
            'status'  => 'Success',
            'message' => 'Invoice has been updated successfully!'
        ]);
    } else {
        echo json_encode([
            'status'  => 'Error',
            'message' => 'There has been an error, please try again.<pre>' . $mysqli->error . '</pre>'
        ]);
    }
 
    $mysqli->close();
}
 

// Adding new product
if($action == 'delete_product') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$id = $_POST["delete"];

	// the query
	$query = "DELETE FROM products WHERE product_id = ?";

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param('s',$id);

	//execute the query
	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Product has been deleted successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	// close connection 
	$mysqli->close();

}

// Login to system
if($action == 'login') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	session_start();

    extract($_POST);

    $username = mysqli_real_escape_string($mysqli,$_POST['username']);
    $pass_encrypt = md5(mysqli_real_escape_string($mysqli,$_POST['password']));

    $query = "SELECT * FROM `users` WHERE username='$username' AND `password` = '$pass_encrypt'";

    $results = mysqli_query($mysqli,$query) or die (mysqli_error());
    $count = mysqli_num_rows($results);

    if($count!="") {
		$row = $results->fetch_assoc();

		$_SESSION['login_username'] = $row['username'];

		// processing remember me option and setting cookie with long expiry date
		if (isset($_POST['remember'])) {	
			session_set_cookie_params('604800'); //one week (value in seconds)
			session_regenerate_id(true);
		}  
		
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Login was a success! Transfering you to the system now, hold tight!'
		));
    } else {
    	echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'Login incorrect, does not exist or simply a problem! Try again!'
	    ));
    }
}

// Adding new product
if($action == 'add_product') {

	$product_name = $_POST['product_name'];
	$product_desc = $_POST['product_desc'];
	$product_price = $_POST['product_price'];

	//our insert query query
	$query  = "INSERT INTO products
				(
					product_name,
					product_desc,
					product_price
				)
				VALUES (
					?, 
                	?,
                	?
                );
              ";

    header('Content-Type: application/json');

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param('sss',$product_name,$product_desc,$product_price);

	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Product has been added successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	//close database connection
	$mysqli->close();
}

// Adding new user
if($action == 'add_user') {

	$user_name = $_POST['name'];
	$user_username = $_POST['username'];
	$user_email = $_POST['email'];
	$user_phone = $_POST['phone'];
	$user_password = $_POST['password'];

	//our insert query query
	$query  = "INSERT INTO users
				(
					name,
					username,
					email,
					phone,
					password
				)
				VALUES (
					?,
					?, 
                	?,
                	?,
                	?
                );
              ";

    header('Content-Type: application/json');

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	$user_password = md5($user_password);
	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param('sssss',$user_name,$user_username,$user_email,$user_phone,$user_password);

	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'User has been added successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	//close database connection
	$mysqli->close();
}

// Update User
if($action == 'update_user') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// user information
	$getID = $_POST['id']; // id
	$name = $_POST['name']; // name
	$username = $_POST['username']; // username
	$email = $_POST['email']; // email
	$phone = $_POST['phone']; // phone
	$password = $_POST['password']; // password

	if($password == ''){
		// the query
		$query = "UPDATE users SET
					name = ?,
					username = ?,
					email = ?,
					phone = ?
				 WHERE id = ?
				";
	} else {
		// the query
		$query = "UPDATE users SET
					name = ?,
					username = ?,
					email = ?,
					phone = ?,
					password =?
				 WHERE id = ?
				";
	}

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	if($password == ''){
		/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
		$stmt->bind_param(
			'sssss',
			$name,$username,$email,$phone,$getID
		);
	} else {
		$password = md5($password);
		/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
		$stmt->bind_param(
			'ssssss',
			$name,$username,$email,$phone,$password,$getID
		);
	}

	//execute the query
	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'User has been updated successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	//close database connection
	$mysqli->close();
	
}

// Delete User
if($action == 'delete_user') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$id = $_POST["delete"];

	// the query
	$query = "DELETE FROM users WHERE id = ?";

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param('s',$id);

	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'User has been deleted successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	// close connection 
	$mysqli->close();

}

// Delete Customer
if($action == 'delete_customer') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$id = $_POST["delete"];

	// the query
	$query = "DELETE FROM store_customers WHERE id = ?";

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param('s',$id);

	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Customer has been deleted successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	// close connection 
	$mysqli->close();

}

// Download invoice PDF
if ($action == 'download_invoice') {

    $invoice_number = intval($_GET['invoice_number']);

    if ($mysqli->connect_error) {
        die('Error : ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
    }

    // Get invoice data
    $query = "SELECT i.*, c.* 
              FROM invoices i 
              JOIN customers c ON c.invoice = i.invoice 
              WHERE i.invoice = $invoice_number 
              LIMIT 1";

    $result = $mysqli->query($query);

    if (!$result || $result->num_rows == 0) {
        echo json_encode(array('status' => 'Error', 'message' => 'Invoice not found.'));
        exit;
    }

    $data = $result->fetch_assoc();

    // Get invoice items
    $items_query = "SELECT * FROM invoice_items WHERE invoice = $invoice_number";
    $items_result = $mysqli->query($items_query);

    date_default_timezone_set(TIMEZONE);
    include('invoice.php');

    $invoice = new invoicr("A4", CURRENCY, "en");
    $invoice->setNumberFormat('.', ',');
    $invoice->setColor(INVOICE_THEME);
    $invoice->setType($data['invoice_type']);
    $invoice->setReference($data['invoice']);
    $invoice->setDate($data['invoice_date']);
    $invoice->setDue($data['invoice_due_date']);
    $invoice->setFrom(array(
        COMPANY_NAME,
        COMPANY_ADDRESS_1,
        COMPANY_ADDRESS_2,
        COMPANY_COUNTY,
        COMPANY_POSTCODE,
        COMPANY_NUMBER,
        COMPANY_VAT
    ));
    $invoice->setTo(array(
        $data['name'],
        $data['address_1'],
        $data['address_2'],
        $data['town'],
        $data['county'],
        $data['postcode'],
        "Phone: " . $data['phone']
    ));
    $invoice->shipTo(array(
        $data['name_ship'],
        $data['address_1_ship'],
        $data['address_2_ship'],
        $data['town_ship'],
        $data['county_ship'],
        $data['postcode_ship'],
        ''
    ));

    // Add items
    while ($item = $items_result->fetch_assoc()) {
        $item_vat = false;
        if (ENABLE_VAT == true) {
            $item_vat = (VAT_RATE / 100) * $item['subtotal'];
        }
        $invoice->addItem(
            $item['product'],
            '',
            $item['qty'],
            $item_vat,
            $item['price'],
            $item['subtotal'],
            $item['discount']
        );
    }

    // Add totals
    $invoice->addTotal("Total", $data['subtotal']);
    if (!empty($data['discount'])) {
        $invoice->addTotal("Discount", $data['discount']);
    }
    if (!empty($data['shipping'])) {
        $invoice->addTotal("Delivery", $data['shipping']);
    }
    if (ENABLE_VAT == true) {
        $invoice->addTotal("TAX/VAT " . VAT_RATE . "%", $data['vat']);
    }
    $invoice->addTotal("Total Due", $data['total'], true);
    $invoice->addBadge($data['status']);

    if (!empty($data['notes'])) {
        $invoice->addTitle("Customer Notes");
        $invoice->addParagraph($data['notes']);
    }

    $invoice->addTitle("Payment information");
    $invoice->addParagraph(PAYMENT_DETAILS);
    $invoice->setFooternote(FOOTER_NOTE);

    // Stream PDF directly to browser — no file saved
	while (ob_get_level() > 0) {
		ob_end_clean();
	}
    $invoice->render('invoice-' . $invoice_number . '.pdf', 'D'); // D = force download

    exit;
}

?>