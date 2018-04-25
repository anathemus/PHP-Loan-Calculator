<?php
    require_once __DIR__.'/header.php';
    require_once __DIR__.'/vendor/autoload.php';
    require __DIR__.'/calendar.php';
    require __DIR__.'/calculations.php';
    start_google_client();
    display_header();

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

        $endDate = calculate_initial_end_date($date, $paymentsTotal);

        $payPeriod = create_pay_period($beginDate, $endDate);

        // format for DateInterval, using switch statement
        switch ($frequency) {
            case 12:
                $payDates = new DatePeriod(
                    DateTime::createFromFormat('m-d-Y', ($beginDate->format('m-d-Y'))),
                    new DateInterval('P1M'),
                    DateTime::createFromFormat('m-d-Y', ($endDate->format('m-d-Y')))
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

        

?>
        <div class="row"></br></div>
        <div class="row">
            <div class="col-2"></div>
            <div class="col-8">
                <?  if ($loanFail != true) {
                    $beginNiceMonth = date('F', $beginDateAsDate);
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
        </br>
        <div class='table-responsive'>
            <table class='table table-bordered col-12'>
                <thead>
                    <tr>
                        <th colspan='7' class='col-10 offset-1 bg-success text-center'>".$beginYear."</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th colspan='7' class='col-10 offset-1 bg-primary text-center'>".$beginNiceMonth."</th>
                    </tr>
                    <tr>";

                        //setting up for the for loop for the first week
                        $monthNiceDay = DateTime::createFromFormat('D', 'Sun');

                        for ($i=0; $i < 7; $i++) {    
                            echo "<th scope='col' class='bg-light'>".$monthNiceDay->format('l')."</th>";
                            $monthNiceDay->add(new DateInterval('P1D'));
                        }
                        echo "</tr>";
                        $lastYear = $beginYear;
                        $lastMonth = $beginMonth;
                        $lastDay = $beginDay;

                        foreach ($payPeriod as $date) {
                            $year = $date->format('Y');
                            $month = $date->format('n');
                            $monthNice = $date->format('F');
                            $day = $date->format('d');
                            $lDay = $date->format('l');
                            
                            $dateFormatted = $date->format('m-d-Y');

                            if ($year > $lastYear) {
                                echo "<tr>
                                        <th colspan='7' class='col-10 offset-1 bg-success text-center'>".$year."</th>
                                    </tr>";
                                
                                $lastYear = $year;
                                $lastMonth = 0;
                            } else if ($month > $lastMonth) {
                                echo "<tr>
                                        <th colspan='7' class='col-10 offset-1 bg-primary text-center'>".$monthNice."</th>
                                    </tr>
                                    <tr>";
                                    for ($i=0; $i < 7; $i++) { 
                                        echo "<th scope='col' class='bg-light'>".$monthNiceDay->format('l')."</th>";
                                        $monthNiceDay->add(new DateInterval('P1D'));
                                    }
                                    echo "</tr>";
                                $lastMonth = $month;
                                $lastDay = 0;
                                
                            }
                            
                            if ($date->format('D') == 'Sun') {
                                echo "<tr>
                                        <td scope='row' class='bg-danger text-light' id='".$dateFormatted."'>".$day.$lDay."</td>";
                            } else if ($date->format('D') == 'Sat') {
                                echo "<td class='bg-danger text-light' id='".$dateFormatted."'>".$day.$lDay."</td>
                                </tr>";
                            } else if ($day > $lastDay) {
                                
                                echo "<td id='".$dateFormatted."'>".$day."</td>";
                                $lastDay++;
                            } else {
                                echo "<td> </td>";
                            }

                            // load the dom so as to label payment dates
                            /* $htmlDoc = ob_get_contents();
                            $dom = new DOMDocument('1.0');
                            $dom->loadHTML($htmlDoc);

                            foreach ($payDates as $date) {
                                $dateFormatted = $date->format('m-d-Y');
                                $ID = $dom->getElementById($dateFormatted);
                                $ID->setAttribute("class", "bg-warning");
                                $ID->appendChild($dom->createTextNode("Payment"));
                            }
                            */
                        }
            echo "</div>
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
            