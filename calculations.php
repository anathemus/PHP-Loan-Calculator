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

function calculate_initial_end_date($date, $paymentsTotal) {
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
                
                return $endDate;
        }
     
        } catch(Exception $e){
            echo $e->getMessage();
        }
}
?>