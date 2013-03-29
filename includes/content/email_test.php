<?php
 $to = "matthew.gluth@me.com, egeigle@gmail.com";
 $headers = 'From: Festival Time <festivaltime.us@gmail.com>' . "\r\n" . "Cc: jkennaly@gmail.com";
 $subject = "Email Test";
 $body = "This is a test. Please begin to panic now.";
mail($to, $subject, $body, $headers);
?>