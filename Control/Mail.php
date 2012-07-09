<?php

/**
 * Send an email from vaultsofpandius@gmail.com
 * @param type $recipient: the recipient of the mail
 * @param type $subject: the subject of teh mail
 * @param type $message: the body of the mail
 * @return boolean:  true if successful, false otherwise
 */
function sendMail($recipient, $subject, $message) {

    $headers = "From: Vaults Of Pandius<vaultsofpandius@gmail.com>";
    if (mail($recipient, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}

?>