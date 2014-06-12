<?php
$fileName    = "urls.txt";
$placeholder = "#urls";
$restPlaceholder = "#rest";
$secretKey   = "pass";
$secretValue = "3JoeLHVpzmZ24PvHwBUzxr6wLWB1P8D5";
$urlsKey     = "urls";

// Main
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (array_key_exists($secretKey, $_GET) && $_GET[$secretKey] == $secretValue) {
        showForm();
    } else {
        redirectToSurvey();
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST[$secretKey] == $secretValue) {
        // Filter contents
        $contents = $_POST[$urlsKey];
        $contents = contentsToUrls($contents);
        $contents = urlsToContents($contents);
        updateFile($contents);
        $url = strtok($_SERVER["REQUEST_URI"], '?');
        $url = $url . "?" . $secretKey . "=" . $secretValue;
        header("Location: " . $url);
    }
}

function contentsToUrls($contents) {
    $urls = explode("\n", $contents);
    // Remove empty lines
    foreach ($urls as $n => $url) {
        if (trim($url) == "") {
            unset($urls[$n]);
        }
    }
    return $urls;
}

function urlsToContents($urls) {
    return implode("\n", $urls);
}

function redirectToSurvey() {
    global $fileName;
    
    // Avoid simultaneous access
    $lock = fopen($fileName, 'r');
    // Acquire an exclusive lock
    if (flock($lock, LOCK_EX)) {
        // Get and parse file contents
        $contents = file_get_contents($fileName);
        $urls = contentsToUrls($contents);
        // Check if empty
        if (empty($urls)) {
            // Release the lock
            flock($lock, LOCK_UN);
            // Close file
            fclose($lock);
            // Redirect to the Survey is Closed page
            header("Location: http://3d-massomics.eu/survey-closed");
            return;
        }
        $n = array_rand($urls);
        $url = trim($urls[$n]);
        unset($urls[$n]);
        $contents = urlsToContents($urls);
        file_put_contents($fileName, $contents);
        
        // Release the lock
        flock($lock, LOCK_UN);
    } else {
        // Something went terrebly wrong
        echo "Couldn't get the lock!";
    }
    // Close file
    fclose($lock);
    header("Location: " . $url);
}

function showForm() {
    global $placeholder;
    global $restPlaceholder;
    global $fileName;
    $form = file_get_contents("form.html");
    $contents = file_get_contents($fileName);
    $rest = count(contentsToUrls($contents));
    if ($rest > 1) {
        $rest = $rest . " surveys remaining";
    } elseif ($rest == 1)  {
        $rest = "Only one survey remaining";
    }  else {
        $rest = "You can see it";
    }
      
    // Replace the placeholder with the URLs
    $form = str_replace($placeholder,     $contents, $form);
    $form = str_replace($restPlaceholder, $rest,     $form);
    echo $form;
    
}

function updateFile($contents) {
    global $fileName;
    
    file_put_contents($fileName, $contents);
}
?>
