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
        <script>
            $( function() {
            $( "#datepicker" ).datepicker();
            } );
        </script>
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
        <div class="form-group row">
            <div class="col-2"></div>
            <label for="date-input" class="col-2 col-form-label">Start Date:</label>
            <div class="col-6">
                <input class="form-control" type="date" value="mm/dd/yyyy" id="date-input" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-2"></div>
            <label for="loan-input" class="col-2 col-form-label">Loan Amount:</label>
            <div class="col-6">
                <input class="form-control" type="number" value="XXXX.XX" id="loan-input" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-2"></div>
            <label for="installment-input" class="col-2 col-form-label">Installment Amount:</label>
            <div class="col-6">
                <input class="form-control" type="number" value="XXX.XX" id="installment-input" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-2"></div>
            <label for="interest-input" class="col-2 col-form-label">Interest Rate:</label>
            <div class="col-6">
                <input class="form-control" type="number" value="XX.XX" id="interest-input" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-2"></div>
            <label for="installment-select" class="col-2 col-form-label">Installment Frequency:</label>
            <div class="col-6">
                <select class="form-control" id="installement-select">
                    <option>Monthly</option>
                    <option>Weekly</option>
                    <option>Daily</option>
                </select>
            </div>
        </div>
    <?php

    ?>
    </body>
</html>