<?php
// Local dev config (default)
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "debate_news";

// Azure override using environment variables
if (getenv("IS_AZURE") === "true") {
    $db_host = getenv("DB_HOST");
    $db_user = getenv("DB_USER");
    $db_pass = getenv("DB_PASS");
    $db_name = getenv("DB_NAME");
}
?>