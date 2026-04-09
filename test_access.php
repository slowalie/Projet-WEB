<?php
session_start();
echo "Session ID: " . session_id() . "\n";
echo "Session vars: " . json_encode($_SESSION) . "\n";
echo "user_id set? " . (isset($_SESSION['user_id']) ? 'YES' : 'NO') . "\n";
?>
