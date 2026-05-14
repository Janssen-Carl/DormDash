<?php
if (isset($_SESSION['error'])) {
      $errorMsg = getErrorMessage($_SESSION['error']);
      unset($_SESSION['error']);


      // display error message
      if ($errorMsg) {
            echo '<div class="error-box">⚠ ' . htmlspecialchars($errorMsg) . '</div>';
      }

      // DEBUG: Show detailed error if available
      if (isset($_SESSION['debug_error'])) {
            echo '<div class="error-box" style="background-color: #fee; border-color: #c00;">
                  <strong>DEBUG:</strong> ' . htmlspecialchars($_SESSION['debug_error']) . '
            </div>';
            unset($_SESSION['debug_error']);
      }
}