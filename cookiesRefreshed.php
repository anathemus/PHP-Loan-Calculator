<?php
require_once __DIR__.'/header.php';
start_google_client();
display_header();

echo "<div class='col-6 offset-3'>
        <img src='/Images/COOKIE.JPG' class='img-fluid rounded mx-auto d-block'>
</div>
<div class='col-4 offset-4 justify-content-center'>
    <p>You here because Cookie Monster and Google no like stale cookies.
    Me make fresh cookies for Google and you. Eat all the cookies!!!</p>
    <h1>OMNOMNOMNOMNOM!</h1>
    <a href='/form.php' class='btn btn-primary'>Go to the Form</a>
</div>";
?>