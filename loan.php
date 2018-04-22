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
    } catch(Exception $e) {
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
            $dateInterval = 'M';
            break;

        case 'Weekly':
            $frequency = 52;
            $interval = 'weeks';
            $dateInterval = 'D';
            break;
            
        default:
            $frequency = 365;
            $interval = 'days';
            $dateInterval = 'D';
            break;
    }

    if ($installment <= ($loan * (($interest / 100) / $frequency))) {
        $loanFail = true;
    } else {

        $loanDivInstallment = ($loan / $installment); 
        $freqinterest = (($interest / 100) / $frequency);
        $loanMultInterest = ($loanDivInstallment * $freqinterest);
        $LMIInverse = (1 - $loanMultInterest);
        $interestMult = (1 + $freqinterest);
        $logLMI = -log($LMIInverse);
        $logInterest = log($interestMult);

        $paymentsTotal = round(($logLMI / $logInterest), 0, PHP_ROUND_HALF_UP);
        $totalCost = round((($logLMI / $logInterest) * $installment), 2);
        $payRemainder = round(($totalCost - ($installment * ($paymentsTotal - 1))), 2);

        $dateFormat = 'm-d-Y';
        $beginDateAsDate = strtotime($date);
        $beginDate = new DateTime();
        $endDate = new DateTime();

        $beginDay = idate('d', $beginDateAsDate);
        $beginMonth = idate('m', $beginDateAsDate);
        $beginYear = idate('Y', $beginDateAsDate);
        $beginDate->setDate($beginYear, $beginMonth, $beginDay);

        $endDate->setDate($beginYear, $beginMonth, $beginDay);
        try {
            $endDate->add(new DateInterval('P'.intval($paymentsTotal).$dateInterval));
        } catch(Exception $e){
            echo $e->getMessage();
            echo gettype($paymentsTotal);
        }
        $payPeriod = new DatePeriod(
            $beginDate,
            new DateInterval('P1D'),
            $endDate
        );

        // Check for daily payments then weekends, adjust date of last payment accordingly.
        if ($frequency == 365) {
            $weekendDays = 0;
            foreach($payPeriod as $date){
                $days = $date->format('D');
                if ($days == 'Sat') {
                    $weekendDays++;
                } else if ($days == 'Sun') {
                    $weekendDays++;
                }

            $endDate->add(new DateInterval('P'.$weekendDays.'D'));
            // $endDate = $dailyEndDate->format($dateFormat);
            }
        }
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
                <?  if ($loanFail != true) {
                    echo "<p>Number of payments: ".$paymentsTotal."</br> 
                    Total Cost: ".$totalCost."</br>
                    Final payment will be: ".$payRemainder."</br>
                    Final payment on ".$beginDate->format($dateFormat)." ".$endDate->format($dateFormat)."</p>
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
                    echo "<p>The amount of your installments is too low.</br>
            Installments of $".$installment." are less than</br>
            the Interest added ".$frequency." which is ".round(($loan * (($interest / 100) / $frequency)), 2)."</br>
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
                }?>
            