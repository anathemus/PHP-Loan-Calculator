<?php

function log_lmi($loan, $installment, $interest, $frequency) {
    $loanDivInstallment = ($loan / $installment); 
    $freqinterest = (($interest / 100) / $frequency);
    $loanMultInterest = ($loanDivInstallment * $freqinterest);
    $LMIInverse = (1 - $loanMultInterest);

    return -log($LMIInverse);
}

function log_interest($interest, $frequency) {
    $freqinterest = (($interest / 100) / $frequency);
    $interestMult = (1 + $freqinterest);

    return log($interestMult);
}

function calculate_initial_end_date($date, $paymentsTotal, $frequency) {
    
        $endDate = new DateTime($date);

        try {
            //switch to get DateInterval
            switch ($frequency) {
                case 12:
                    for ($i=0; $i < $paymentsTotal; $i++) { 
                        $endDate->add(new DateInterval('P1M'));
                    }
                    break;
            
                case 52:
                for ($i=0; $i < $paymentsTotal; $i++) { 
                    $endDate->add(new DateInterval('P1W'));
                }
                    break;

                default:
                for ($i=0; $i < $paymentsTotal; $i++) { 
                    $endDate->add(new DateInterval('P1D'));
                }
                    break;
                
                
        }

        } catch(Exception $e){
            echo $e->getMessage();
        }
        return $endDate;
        echo $endDate->format('m-d-Y');
}
?>