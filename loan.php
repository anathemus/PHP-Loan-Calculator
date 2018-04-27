<?php
    require_once __DIR__.'/vendor/autoload.php';
    require __DIR__.'/calendar.php';
    require __DIR__.'/calculations.php';
    require_once __DIR__.'/googleHolidays.php';
    require_once __DIR__.'/header.php';


    $date = date($_POST['date-input']);
    $loan = floatval($_POST['loan-input']);
    $installment = floatval($_POST['installment-input']);
    $interest = floatval($_POST['interest-input']);
    $frequency = $_POST['installment-select'];

    switch ($frequency) {
        case 'Monthly':
            $frequency = 12;
            break;

        case 'Weekly':
            $frequency = 52;
            break;
            
        default:
            $frequency = 365;
            break;
    }

    if ($installment <= ($loan * (($interest / 100) / $frequency))) {
        $loanFail = true;
    } else {

        $logLMI = log_lmi($loan, $installment, $interest, $frequency);
        $logInterest = log_interest($interest, $frequency);

        $paymentsTotal = round(($logLMI / $logInterest), 0, PHP_ROUND_HALF_UP);
        $totalCost = round((($logLMI / $logInterest) * $installment), 2);
        $payRemainder = round(($totalCost - ($installment * ($paymentsTotal - 1))), 2);

        $beginDate = new DateTime($date);

        $endDate = new DateTime($date);

        $endDate = calculate_initial_end_date($date, $paymentsTotal, $frequency);

        $payPeriod = create_pay_period($date, $endDate);

        $endDate = adjust_end_date($payPeriod, $date, $endDate, $client);

        // recreate the payperiod to account for the new, final end-date ONLY if 
        // it's a daily payment.
        $payPeriod = create_pay_period($date, $endDate);

        // format for DateInterval, using switch statement
        switch ($frequency) {
            case 12:
                $payDates = new DatePeriod(
                    $beginDate,
                    new DateInterval('P1M'),
                    $endDate
                );
                break;
            
            case 52:
                $payDates = new DatePeriod(
                    $beginDate,
                    new DateInterval('P1W'),
                    $endDate
                );
                break;
            
            default:
                $payDates = new DatePeriod(
                    $beginDate,
                    new DateInterval('P1D'),
                    $endDate
                );
                break;
        }

        $beginFormatMDY = date_format($beginDate, 'm-d-Y');
        $endFormatMDY = date_format($endDate, 'm-d-Y');
        $events = getHolidayArray($date, $endDate);
        $payments = payment_dates_events_array($payDates, $installment, $endDate, $payRemainder);

        display_header();

        echo "<div class='row'></br></div>
        <div class='row'>
            <div class='col-8 offset-2'>";
                if ($loanFail != true) {
                    echo "<p>Number of payments: ".$paymentsTotal."</br> 
                    Total Cost: ".$totalCost."</br>
                    Final payment will be: ".$payRemainder."</br>
                    First payment on ".$beginFormatMDY."</br>
                    Final payment on ".$endFormatMDY."</p>
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
        </br>";

                create_calendar($date, $endDate, $payPeriod, $events, $payments);

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
                }
            }
                ?>
            