<?php   
   // generate random password
   function generateRandomPassword($length = 8)
   {
       $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
       $password = '';
       
       for ($i = 0; $i < $length; $i++) {
           $index = rand(0, strlen($characters) - 1);
           $password .= $characters[$index];
       }
   
       return $password;
   }

   $schoolMappings = [
    "PUPSRC" => "POLYTECHNIC UNIVERSITY OF THE PHILIPPINES",
    "PUP SANTA ROSA" => "POLYTECHNIC UNIVERSITY OF THE PHILIPPINES",
    "PUP STA ROSA" => "POLYTECHNIC UNIVERSITY OF THE PHILIPPINES",
    "POLYTECHNIC UNIVERSITY OF THE PHILIPPINES" => "POLYTECHNIC UNIVERSITY OF THE PHILIPPINES",
    "POLYTECHNIC UNIVERSITY OF THE PHILIPPINES STA ROSA" => "POLYTECHNIC UNIVERSITY OF THE PHILIPPINES",
    "POLYTECHNIC UNIVERSITY OF THE PHILIPPINES SANTA ROSA" => "POLYTECHNIC UNIVERSITY OF THE PHILIPPINES",
    "PNC" => "PAMANTASAN NG CABUYAO",
    "OLFU" => "OUR LADY OF FATIMA",
    "OLFU SANTA ROSA" => "OUR LADY OF FATIMA SANTA ROSA",
    "OLFU STA ROSA" => "OUR LADY OF FATIMA SANTA ROSA",
    "OUR LADY OF FATIMA SANTA ROSA" => "OUR LADY OF FATIMA SANTA ROSA",
    "OUR LADY OF FATIMA STA ROSA" => "OUR LADY OF FATIMA SANTA ROSA",
];

// Function to map school names
function mapSchoolName($schoolName, $schoolMappings) {
    // Check if the school name is in the mappings, and return the full name if found
    return isset($schoolMappings[$schoolName]) ? $schoolMappings[$schoolName] : $schoolName;
}


?>