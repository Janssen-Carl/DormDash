<?php

function getErrorMessage($errorCode)
{
      $errorMessages = [
            'emptyinput' => "Please fill in all fields.",
            'stmtfailed' => "Something went wrong. Try again later.",
            'incorrectpassword' => "Incorrect password. Please try again.",
            'userdoesnotexist' => "No user found with that username or email.",
            'unauthorized' => "You must be an admin to access that page.",
      ];

      return $errorMessages[$errorCode] ?? '';
}

