<?php

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["name"]));
                $name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $phone = trim($_POST["phone"]);
        $address1 = strip_tags(trim($_POST["address1"]));
        $address2 = strip_tags(trim($_POST["address2"]));      
        $town = strip_tags(trim($_POST["town"]));
        $county = strip_tags(trim($_POST["county"])); 
        $postcode = strip_tags(trim($_POST["post-code"]));
        $total = trim($_POST["total"]);
        $controlDevice1 = $_POST['controlDevice1'];
        $controlDeviceNumber1 = $_POST['controlDeviceNumber1'];
        $controlDevicePrice1 = $_POST['controlDevicePrice1'];
        $controlDevice2 = $_POST['controlDevice2'];
        $controlDeviceNumber2 = $_POST['controlDeviceNumber2'];
        $controlDevicePrice2 = $_POST['controlDevicePrice2'];
        $controlDevice3 = $_POST['controlDevice3'];
        $controlDevice4 = $_POST['controlDevice4'];
        $controlDevicePrice4 = $_POST['controlDevicePrice4'];

        if ($_POST['controlDeviceNumber1']) {
            $control_output1 = '<tr><td colspan="2">' . $controlDevice1 . '</td><td align="center">' . $controlDeviceNumber1 . '</td><td>' . $controlDevicePrice1 . '</td></tr>';
        }
        if ($_POST['controlDeviceNumber2']) {
            $control_output2 = '<tr><td colspan="2">' . $controlDevice2 . '</td><td align="center">' . $controlDeviceNumber2 . '</td><td>' . $controlDevicePrice2 . '</td></tr>';
        }
        if ($_POST['controlDevice3']) {
            $control_output3 = '<tr><td colspan="2">' . $controlDevice3 . '</td><td align="center"> 1 </td><td> Free </td></tr>';
        }
        if ($_POST['controlDevice4']) {
            $control_output4 = '<tr><td colspan="2">' . $controlDevice4 . '</td><td align="center"> 1 </td><td>' . $controlDevicePrice4 . '</td></tr>';
        }

        $control_output = '<tr><td colspan="4" height="15">&nbsp;</td></tr>';
        $control_output .= '<tr><th align="left" colspan="2">Control device</th><th>Quantity</th><th>Price</th></tr>';
        $control_output .= $control_output1;
        $control_output .= $control_output2;
        $control_output .= $control_output3;
        $control_output .= $control_output4;
        $control_output .= '<tr><td colspan="4" height="15">&nbsp;</td></tr>';


        while(list($key,$value) = each($_POST['room'])) {
                
            $room = $_POST['room'][$key];

            $roomDevice = $_POST['roomDevice'][$key];
            $roomDeviceNumber = $_POST['numberRoomDevice'][$key];
            $roomDevicePrice = $_POST['roomDevicePrice'][$key];            

            $doorDevice = $_POST['doorDevice'][$key];
            $doorDeviceNumber = $_POST['doorDeviceNumber'][$key];
            $doorDevicePrice = $_POST['doorDevicePrice'][$key];            

            $windowDevice = $_POST['windowDevice'][$key];
            $windowDeviceNumber = $_POST['windowDeviceNumber'][$key];
            $windowDevicePrice = $_POST['windowDevicePrice'][$key];

            $mdd = '<tr><th align="left">Movement detection device</th><td>' . $roomDevice . '</td><td align="center">' . $roomDeviceNumber . '</td><td>' . $roomDevicePrice . '</td></tr>';
            $dd  = '<tr><th align="left">Door detection device</th><td>' . $doorDevice . '</td><td align="center">' . $doorDeviceNumber . '</td><td>' . $doorDevicePrice . '</td></tr>';
            $wd  = '<tr><th align="left">Window detection device</th><td>' . $windowDevice . '</td><td align="center">' . $windowDeviceNumber . '</td><td>' . $windowDevicePrice . '</td></tr>';

            $room_output .= '<tr><td colspan="4" height="15">&nbsp;</td></tr><tr><th align="left" style="border-bottom:1px solid #808080;">Room</th><td colspan="3" style="border-bottom:1px solid #808080;">' . $room . '</td></tr>';
            $room_output .= '<tr><td colspan="2">&nbsp;</td><th>Quantity</th><th>Price</th></tr>';
            $room_output .= $mdd;
            $room_output .= $dd;
            $room_output .= $wd;
            $room_output .= '<tr><td colspan="4" height="1" style="border-bottom:1px dotted #333333;">&nbsp;</td></tr>';
        }

        // Check that data was sent to the mailer.
        if ( empty($name) OR empty($phone) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Oops! There was a problem with your submission. Please complete the form and try again.";
            exit;
        }

        // Set the recipient email address.
        // FIXME: Update this to your desired email address.
        $to = 'eglytep@gmail.com';
        $to .= $email;

        // Set the email subject.        
        $date = date('d-m-Y');
        $subject = "Estimate Builder - " . $date . " New quotation request from " . $name;

        // Build the email content.
        $email_content  = '<html><head><title>' . $subject . '</title></head>';
        $email_content .= '<body style="margin: 0 !important;padding: 0;background-color: #ffffff;">';
        $email_content .= '<table width="100%" align="center" style="max-width: 600px;margin: 0 auto;border-spacing: 0;font-family: sans-serif;width:100%;border-bottom:4px solid #0071bc;">
                            <tr><td colspan="2" valign="left"><img src="http://tapsecuritysystems.co.uk/images/home-security-uk-tri.png" alt="TAP Security Systems - Commercial &amp; Home Alarm Systems UK" /></td></tr>
                            <tr><td colspan="2" height="15">&nbsp;</td></tr>';
        $email_content .= '<tr><th colspan="2" style="border-bottom:2px solid #0071bc;"><span style="font-size:28px;color:#006fbf;text-transform:uppercase;">Wireless Enforcer Alarm Quotation</span></th></tr><td colspan="2" height="15">&nbsp;</td></tr>';
        $email_content .= '<tr><td width="50%" valigh="top"><strong>Name:</strong> ' . $name . '<br /> <strong>Email:</strong> ' . $email . '<br /> <strong>Phone number:</strong> <span style="text-decoration:none; color:#0071bc;">' . $phone . '</span></td>
                            <td width="50%" valigh="top"><strong>Address:</strong><br /> '. $address1 . ', '. $address2 . '<br /> '. $town . '<br /> '. $county . '<br /> '. $postcode . '</td>
                            </tr><tr><td colspan="2" height="15">&nbsp;</td></tr>';        
        $email_content .= '<tr><td colspan="2" height="15">&nbsp;</td></tr><tr><td colspan="2">';
        $email_content .= '<table style="border-top:1px solid #333333;border-bottom:1px solid #333333;border-collapse: collapse;width:100%;" width="100%">' . $room_output;
        $email_content .= $control_output . '</table></td></tr>';
        $email_content .= '<tr><td colspan="2" height="15">&nbsp;</td></tr>';
        $email_content .= '<tr><td colspan="2" align="left"><strong>System total price:</strong> ' . $total . ' + VAT</td></tr>';
        $email_content .= '<tr><td colspan="2" height="15">&nbsp;</td></tr>';
        $email_content .= '<tr><td colspan="2">If you wish to proceed with your installation please call <a href="tel:01937849798"><span style="text-decoration:none; color:#0071bc;">01937 849798</span></a> and choose option 1.</td></tr>';
        $email_content .= '<tr><td colspan="2" height="15">&nbsp;</td></tr>';
        $email_content .= '</table></body></html>';

        $mail_content = chunk_split(base64_encode($email_content));

        // Build the email headers.
        $email_headers  = "MIME-Version: 1.0\r\n";
        $email_headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $email_headers .= "X-UA-Compatible: IE=edge\r\n";
        $email_headers .= "Content-Transfer-Encoding: base64\r\n";        
        $email_headers .= "Reply-To: $name <$email> \n";
        $email_headers .= "From:$name <noreply@kariba.co.uk>";        

        // Send the email.
        if (mail($to, $subject, $mail_content, $email_headers)) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            echo "Thank You! Your message has been sent.";
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "Oops! Something went wrong and we couldn't send your message.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }

?>
