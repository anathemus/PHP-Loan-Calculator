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
        <div class="row"><div class="col-12"></br></div></div>
        <form action="/loan.php" method="POST">
            <div class="form-group row align-items-center">
                <div class="col-0 col-md-2"></div>
                <label for="date-input" class="col-4 col-md-2 col-form-label text-right">Start Date:</label>
                <div class="col-6">
                    <input class="form-control" type="date" placeholder="mm/dd/yyyy" id="date-input" name="date-input" />
                </div>
            </div>
            <div class="form-group row align-items-center">
                <div class="col-0 col-md-2"></div>
                <label for="loan-input" class="col-4 col-md-2 col-form-label text-right">Loan Amount:</label>
                <div class="col-6">
                    <input class="form-control" type="number" placeholder="XXXX.XX" id="loan-input" name="loan-input" />
                </div>
            </div>
            <div class="form-group row align-items-center">
                <div class="col-0 col-md-2"></div>
                <label for="installment-input" class="col-4 col-md-2 col-form-label text-right">Installment Amount:</label>
                <div class="col-6">
                    <input class="form-control" type="number" placeholder="XXX.XX" id="installment-input" name="installment-input" />
                </div>
            </div>
            <div class="form-group row align-items-center">
                <div class="col-0 col-md-2"></div>
                <label for="interest-input" class="col-4 col-md-2 col-form-label text-right">Interest Rate:</label>
                <div class="col-6">
                    <input class="form-control" type="number" placeholder="XX.XX" id="interest-input" name="interest-input" />
                </div>
            </div>
            <div class="form-group row align-items-center">
                <div class="col-0 col-md-2"></div>
                <label for="installment-select" class="col-4 col-md-2 col-form-label text-right">Installment Frequency:</label>
                <div class="col-6">
                    <select class="form-control" id="installement-select" name="installment-select">
                        <option>Monthly</option>
                        <option>Weekly</option>
                        <option>Daily</option>
                    </select>
                </div>
            </div>
            <div class="form-group row align-items-center">
                <div class="col-2 col-md-4"></div>
                <div class="col-4 col-md-4">
                    <input class="btn btn-primary" type="submit" value="Submit" />
                </div>
                <div class="col-6 col-md-4"></div>
            </div>
        </form>
    <?php

    ?>
    </body>
</html>