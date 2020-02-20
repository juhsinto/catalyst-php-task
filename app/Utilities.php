<?php
/**
 * Created by PhpStorm.
 * User: jacinto
 * Date: 19/2/20
 * Time: 8:32 PM
 */

namespace CatalystTask;


class Utilities
{

    public function __construct() {
    }

    /**
     * Code from https://www.linuxjournal.com/article/9585
     * Validate an email address.
     * Provide email address (raw input)
     * Returns true if the email address has the email
     * address format and the domain exists.
     **/

    public function validEmail($email)
    {
        $isValid = true;
        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex)
        {
            $isValid = false;
        }
        else
        {
            $domain = substr($email, $atIndex+1);
            $local = substr($email, 0, $atIndex);
            $localLen = strlen($local);
            $domainLen = strlen($domain);
            if ($localLen < 1 || $localLen > 64)
            {
                // local part length exceeded
                $isValid = false;
            }
            else if ($domainLen < 1 || $domainLen > 255)
            {
                // domain part length exceeded
                $isValid = false;
            }
            else if ($local[0] == '.' || $local[$localLen-1] == '.')
            {
                // local part starts or ends with '.'
                $isValid = false;
            }
            else if (preg_match('/\\.\\./', $local))
            {
                // local part has two consecutive dots
                $isValid = false;
            }
            else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
            {
                // character not valid in domain part
                $isValid = false;
            }
            else if (preg_match('/\\.\\./', $domain))
            {
                // domain part has two consecutive dots
                $isValid = false;
            }
            else if
            (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                str_replace("\\\\","",$local)))
            {
                // character not valid in local part unless
                // local part is quoted
                if (!preg_match('/^"(\\\\"|[^"])+"$/',
                    str_replace("\\\\","",$local)))
                {
                    $isValid = false;
                }
            }
            if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
            {
                // domain not found in DNS
                $isValid = false;
            }
        }
        return $isValid;
    }

    /**
     * @param $csvFile
     * @return array
     * @throws \Exception
     */
    public function readCSV($csvFile)
    {

        if (file_exists($csvFile)) {
//            echo "The file $csvFile exists";
        } else {
            $error = 'File does not exist!';
            throw new \Exception($error);
        }

        $file_handle = fopen($csvFile, 'r');
        while (!feof($file_handle)) {
            $line_of_text[] = fgetcsv($file_handle, 0);
        }
        fclose($file_handle);
        return $line_of_text;
    }

    public function validator($arrays_of_strings) {
        // assume all emails are true
        $allEmailsValid = true;
        $row = 1;
        foreach ( $arrays_of_strings as $columns ) {

            // [ASSUMPTION] skip first row, as it contains headers
            if($row == 1){ $row++; continue; }

            $name = $columns[0];
            $surname = $columns[1];
            $email = trim($columns[2]);

            // skip blank lines
            if ($name == "" && $surname == "" && $email == "") {
                continue;
            }



            if (!$this->validEmail($email)) {
                $allEmailsValid = false;
                echo "There was an issue - email " . $email . " is invalid! \n";
            }
        }

        // if there was no issue in the csv
        return $allEmailsValid;
    }




}