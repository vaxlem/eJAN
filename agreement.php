<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $signatureData = $_POST["signature"];

    $to = "vaxlem5@gmail.com"; // Change this to your email
    $subject = "New Signed Form Submission";
    $boundary = md5(time());

    // Extract base64 image data
    list($type, $signatureData) = explode(';', $signatureData);
    list(, $signatureData)      = explode(',', $signatureData);
    $signatureData = base64_decode($signatureData);
    $signatureFile = "signature.png";

    // Email Headers
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
    $headers .= "From: $email\r\n";

    // Email Body
    $message = "--$boundary\r\n";
    $message .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n\r\n";
    $message .= "Name: $name\nEmail: $email\n\nSignature attached below.\r\n\r\n";
    
    // Attach Signature
    $message .= "--$boundary\r\n";
    $message .= "Content-Type: image/png; name=\"$signatureFile\"\r\n";
    $message .= "Content-Transfer-Encoding: base64\r\n";
    $message .= "Content-Disposition: attachment; filename=\"$signatureFile\"\r\n\r\n";
    $message .= chunk_split(base64_encode($signatureData)) . "\r\n\r\n";
    $message .= "--$boundary--";

    // Send Email
    if (mail($to, $subject, $message, $headers)) {
        echo "Form submitted successfully!";
    } else {
        echo "Error submitting form.";
    }
} else {
    echo "Invalid request.";
}
?>