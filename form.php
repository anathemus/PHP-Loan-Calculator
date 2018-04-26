<?php 
require_once __DIR__.'/header.php';

display_header();

 ?>
<div class="row"><div class="col-12"></br></div></div>
        <form action="/loan.php" method="POST">
            <div class="form-group-row align-items-center">
                <div class="col-8 col-md-6 offset-2 offset-md-3">
                    <label for="date-input" class="col-form-label">Start Date:</label>
                    <input class="form-control" type="date" placeholder="mm/dd/yyyy" id="date-input" name="date-input" required/>
                </div>
            </div>
            <div class="form-group-row align-items-center">
                <div class="col-8 col-md-6 offset-2 offset-md-3">
                    <label for="loan-input" class="col-form-label text-right">Loan Amount:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">$</div>
                        </div>
                        <input class="form-control" type="number" step=0.01 min=0 placeholder="ex. 1000.00" id="loan-input" name="loan-input" required />
                    </div>
                </div>
            </div>
            <div class="form-group row align-items-center">
                <div class="col-8 col-md-6 offset-2 offset-md-3">
                    <label for="installment-input" class="col-form-label text-right">Installment Amount:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">$</div>
                        </div>
                        <input class="form-control" type="number" step=0.01 min=0 placeholder="ex. 100.00" id="installment-input" name="installment-input" required />
                    </div>
                </div>
            </div>
            <div class="form-group row align-items-center">
                <div class="col-8 col-md-6 offset-2 offset-md-3">
                    <label for="interest-input" class="col-form-label text-right">Interest Rate:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">%</div>
                        </div>
                        <input class="form-control" type="number" step=0.01 min=0 placeholder="ex 10.00" id="interest-input" name="interest-input" required />
                    </div>
                </div>
            </div>
            <div class="form-group row align-items-center">
                <div class="col-8 col-md-6 offset-2 offset-md-3">
                    <label for="installment-select" class="col-form-label text-right">Installment Frequency:</label>
                    <select class="form-control" id="installement-select" name="installment-select" required>
                        <option>Monthly</option>
                        <option>Weekly</option>
                        <option>Daily</option>
                    </select>
                </div>
            </div>
            <div class="col-12"></br></div>
            <div class="form-group row align-items-center">
                <div class="col-4 col-md-4 offset-2 offset-md-3">
                    <input class="btn btn-primary" type="submit" value="Submit" />
                </div>
            </div>
        </form>
    </body>
</html>