<?php
    require_once __DIR__.'/vendor/autoload.php';

    session_start();

    $client = new Google_Client();
    $client->setAuthConfig('client_secret_953871450148-1fnnuor3qiecnuaorbkd8ljiu9kqnnlb.apps.googleusercontent.com.json');
    $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
    
    try {
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $client->setAccessToken($_SESSION['access_token']);
            $calendarService = new Google_Service_Calendar($client);
            $holidayCalendarId = "en.usa#holiday@group.v.calendar.google.com";
            $events = $calendarService->events;
            $holidayJson = $events->listEvents($holidayCalendarId);

        /*foreach ($holidayJson['items'] as $items => $property) {
            echo $property['summary'];
        }
        */
        
        } else {
            $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
            header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
        }
    }
    catch(Exception $e) {
        $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    }

    $date = date($_POST['date-input']);
    $loan = floatval($_POST['loan-input']);
    $installment = floatval($_POST['installment-input']);
    $interest = floatval($_POST['interest-input']);
    $frequency = $_POST['installment-select'];

    switch ($frequency) {
        case 'Monthly':
            $frequency = 12;
            $interval = 'months';
            break;

        case 'Weekly':
            $frequency = 52;
            $interval = 'weeks';
            break;
                
        default:
            $frequency = 365;
            $interval = 'days';
            break;
    }

    $loanDivInstallment = ($loan / $installment); 
    $freqinterest = (($interest / 100) / $frequency);
    $loanMultInterest = ($loanDivInstallment * $freqinterest);
    $LMIInverse = (1 - $loanMultInterest);
    $interestMult = (1 + $freqinterest);
    $logLMI = -log($LMIInverse);
    $logInterest = log($interestMult);

    if ($LMIInverse > 0) {
        $paymentsTotal = round(($logLMI / $logInterest), 0, PHP_ROUND_HALF_UP);
        $totalCost = round((($logLMI / $logInterest) * $installment), 2);
        $payRemainder = round(($totalCost - ($installment * ($paymentsTotal - 1))), 2);

        $endDate = date("m-d-Y", strtotime($date.' + '.$paymentsTotal.' '.$interval));

        $payPeriod = new DatePeriod(
        new DateTime($date),
        new DateInterval('P1D'),
        new DateTime($endDate->format('m-d-Y'))
    );
    } else {
            $loanFail = true;
    }
    
    

?>
<html>
    <head>
        <meta author = "Benjamin A Burgess" />
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Benjamin A Burgess - Loan Calculator</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Latest compiled and minified Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
        <!-- Latest compiled Bootstrap JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="site.css" />
    </head>
    <body>
        <header>
            <div class="content-wrapper">
                <div class="text-center">
                    <p class="site-title">
                        <a href="/index.php">Loan Calculator</a>
                    </p>
                </div>
            </div>
        </header>
        <div class="row"></br></div>
        <div class="row">
            <div class="col-2"></div>
            <div class="col-8">
                <?  if($loanFail == true) {
                    echo "<p>The amount of your installments is too low.</br>
                    This loan will never be paid off.</br>
                    Please try again.</p>
                    </div>
            <div class='col-2'></div>
        </div>
        <div class='row'>
            <div class='col-2'></div>
            <div class='col-8'>
                <a href='/index.php' class='btn btn-primary'>Back</a>
            </div>
            <div class='col-2'></div>
        </div>
    </body>
</html>";
                } else {
                    echo "<p>Number of payments: ".$paymentsTotal."</br> 
                    Total Cost: ".$totalCost."</br>
                    Final payment will be: ".$payRemainder."</br>
                    Final payment on ".$endDate."</p>
            </div>
            <div class='col-2'></div>
        </div>
        <div class='row'>
            <div class='col-2'></div>
            <div class='col-8'>
                <a href='/index.php' class='btn btn-primary'>Back</a>
            </div>
            <div class='col-2'></div>
        </div>
    </body>
</html>";
                }