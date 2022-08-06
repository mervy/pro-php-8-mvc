<?php

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$requestPath = $_SERVER['REQUEST_URI'] ?? '/';

function redirectForeverTo($path)
{
    header("Location: {$path}", $replace = true, $code = 301);
    exit;
}

if ($requestMethod === 'GET' and $requestPath === '/') {
    print '
<!doctype html>
<html lang="en">
<head>
    <title>Title</title>   
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css"">
</head>
<body>  
    <div class="container mt-5">
        <h1>Hello World</h1>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js"></script>
</body>
</html>   
    
    ';
} else if ($requestPath === '/old-home') {
    redirectForeverTo('/');
} else {
    include(__DIR__ . '/includes/404.php');
}
