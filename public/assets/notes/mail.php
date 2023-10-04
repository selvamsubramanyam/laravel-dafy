<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $c_name = $_POST['name3'];
    $c_email = $_POST['email3'];
    $c_phone = $_POST['phone3'];
    $c_ride = $_POST['r-type3'];
    $c_pick = $_POST['pick3'];
    $c_drop = $_POST['drop3'];
    $c_date = $_POST['date3'];
    $cv_name = $_POST['v-name3'];
    $cv_type = $_POST['v-type3'];

    if (isset($c_email) && $c_email !== '') {
        $to = "abdullah.bca98@gmail.com";
        $subject = "Booking Service";
        $body = "From: $c_name\r\n";
        $body .= "Email: $c_email\r\n";
        $body .= "Phone: $c_phone\r\n";
        $body .= "Ride Type: $c_ride\r\n";
        $body .= "Pickup: $c_pick\r\n";
        $body .= "Dropoff: $c_drop\r\n";
        $body .= "Date: $c_date\r\n";
        $body .= "Vehicle Name: $cv_name\r\n";
        $body .= "Vehicle Type: $cv_type\r\n";

        $headers = "From: $c_name <$c_email>\r\n";
        $headers .= "Reply-To: $c_email\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

        $message_sent = mail($to, $subject, $body, $headers);

        if ($message_sent) {
            echo "Message sent successfully";
        } else {
            echo "Failed to send message";
        }
    } else {
        echo "Email is missing or invalid";
    }
}
?>