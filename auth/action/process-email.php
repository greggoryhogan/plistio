#!/usr/bin/env php
<?php 
/*
 * Read piped mail from STDIN and process for spotify
 */ 
     
// Set default limit of 1024k if no limit has been specified 
$iKlimit = 1024; 
// Error strings 
$sErrorSTDINFail = 'Error - failed to read mail from STDIN!'; 

// Attempt to connect to STDIN 
$fp = fopen("php://stdin", "r"); 

// Failed to connect to STDIN? (shouldn't really happen) 
if (!$fp) { 
    echo $sErrorSTDINFail; 
    exit(); 
} 

// Create empty string for storing message 
$email = ''; 

// Read message up until limit (if any) 
if ($iKlimit == -1) { 
    while (!feof($fp)) { 
        $email .= fread($fp, 1024); 
    }                     
} else { 
    while (!feof($fp) && $i_limit < $iKlimit) { 
        $email .= fread($fp, 1024); 
        $i_limit++; 
    }         
} 

// Close connection to STDIN 
fclose($fp); 

// handle email contents
$lines = explode("\n", $email);

// empty vars
$from = "";
$subject = "";
$headers = "";
$message = "";
$splittingheaders = true;
for ($i=0; $i < count($lines); $i++) {
    if ($splittingheaders) {
        // this is a header
        $headers .= $lines[$i]."\n";

        // look out for special headers
        if (preg_match("/^Subject: (.*)/", $lines[$i], $matches)) {
            $subject = $matches[1];
        }
        if (preg_match("/^From: (.*)/", $lines[$i], $matches)) {
            $from = $matches[1];
        }
        if (preg_match("/^To: (.*)/", $lines[$i], $matches)) {
            $to = $matches[1];
        }
    } else {
        // not a header, but message
        $message .= $lines[$i]; //."\n"
    }

    if (trim($lines[$i])=="") {
        // empty line, header section has ended
        $splittingheaders = false;
    }
}

//$headers = "From: share@plistio.com\r\n";
$headers = "From: ".$to."\r\n";
mail($from,'Plistio Autoreply',"Right back at ya!\n\n".$message,$headers);
?>