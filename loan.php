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
            <div class="col-8">Number of payments:
        <?php
            $date = date($_POST['date-input']);
            $loan = round(floatval($_POST['loan-input']), 2);
            $installment = round(floatval($_POST['installment-input']), 2);
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

            $balance = $loan;
            $freqinterest = (($interest / 100) / $frequency);

            for ($totalpayments=0; $balance > 0 ; $totalpayments++) { 
                    $newBalance = ($balance + ($balance * $freqinterest));
                    $balance = $newBalance - $installment;
            }
            
            echo $totalpayments;

            // https://www.googleapis.com/calendar/v3/calendars/en.us%40holiday.calendar.google.com/events?key=AIzaSyD_WrkVGqA8L5SUyY1QSHrwSHs_rRScOgU
            $xml = simplexml_load_file("https://calendar.google.com/calendar/htmlembed?src=en.usa%23holiday%40group.v.calendar.google.com&amp;ctz=America%2FNew_York");
            $xml->asXML();
            $holidays = array();
            
            foreach ($xml->entry as $entry){
                $a = $entry->children('http://schemas.google.com/g/2005');
                $when = $a->when->attributes()->startTime;

                $holidays[(string)$when]["title"] = $entry->title;
            }

            if(is_array($holidays[date("Y-m-d")]))
                echo "holiday";
            else 
                echo "Not holiday";
        ?>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row">
            <div class="col-2"></div>
            <div class="col-8">
                <a href="/index.php" class="btn btn-primary">Back</a>
            </div>
            <div class="col-2"></div>
        </div>
    </body>
</html>