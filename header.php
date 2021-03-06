<?php

require_once __DIR__.'/vendor/autoload.php';

function display_header() {

    echo "<html>
    <head>
        <meta author = 'Benjamin A Burgess' />
        <meta charset='utf-8' />
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <title>Benjamin A Burgess - Loan Calculator</title>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <!-- Latest compiled and minified Bootstrap CSS -->
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css'>
        <!-- jQuery library -->
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <!-- Popper JS -->
        <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js'></script>
        <!-- Latest compiled Bootstrap JavaScript -->
        <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js'></script>
        <link rel='stylesheet' type='text/css' href='site.css' />
    </head>
    <body>
        <header>
            <div class='content-wrapper'>
                <div class='text-center'>
                    <p class='site-title'>
                        <a href='/index.php'>Loan Calculator</a>
                    </p>
                </div>
            </div>
        </header>";
}
?>