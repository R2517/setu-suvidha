<?php
$url = 'https://rajbhavan-maharashtra.gov.in/wp-content/themes/rajbhavan/assets/img/maharashtra-govt-logo.png';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
$data = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200 && $data) {
    file_put_contents('c:/xampp/htdocs/setusuvidha.com/setu-suvidha/public/images/mh-logo.png', $data);
    echo "Success";
} else {
    echo "Failed with HTTP code: " . $http_code;
}
