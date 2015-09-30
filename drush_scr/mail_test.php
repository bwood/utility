$to = 'bwood@berkeley.edu';
$from = 'webplatform@berkeley.edu';
$subject = 'test from and reply-to';
$message = 'hello';
$headers = "From: $from" . "\r\n" .
  "Reply-To: $from" . "\r\n" .
  "X-Mailer: PHP/" . phpversion();


if (!mail($to, $subject, $message, $headers)) {
  drupal_set_message("can't send", "error");
}
else {
  drupal_set_message("sent");
}
