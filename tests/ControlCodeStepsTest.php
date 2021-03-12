<?php

namespace Josegus\ControlCode\Tests;

use Josegus\ControlCode\ControlCode;
use PHPUnit\Framework\TestCase;

class ControlCodeStepsTest extends TestCase
{
    protected function controlCode()
    {
        return ControlCode::make()
            ->authorizationNumber('29040011007')
            ->invoiceNumber('1503')
            ->customerDocumentNumber('4189179011')
            ->transactionDate('2007-07-02')
            ->transactionMount('2500')
            ->dosificationKey('9rCB7Sv4X29d)5k7N%3ab89p-3(5[A');
    }

    /** @test */
    public function first_step_generates_five_verhoeff_digits()
    {
        $controlCode = $this->controlCode();

        $controlCode->step1();

        $this->assertEquals('71621', $controlCode->getLastFiveVerhoeffDigits());
    }

    /** @test */
    public function second_step_takes_chars_from_dosage_key_and_append_it_to_attributes()
    {
        $controlCode = $this->controlCode();

        $controlCode->step1();
        $controlCode->step2();

        $this->assertEquals('290400110079rCB7Sv4', $controlCode->getAuthorizationNumber());
        $this->assertEquals('150312X2', $controlCode->getInvoiceNumber());
        $this->assertEquals('4189179011589d)5k7N', $controlCode->getCustomerDocumentNumber());
        $this->assertEquals('2007070201%3a', $controlCode->getTransactionDate());
        $this->assertEquals('250031b8', $controlCode->getTransactionMount());
    }

    /** @test */
    public function third_step_concats_invoice_properties_with_verhoeff_digits()
    {
        $controlCode = $this->controlCode();

        $controlCode->step1();
        $controlCode->step2();
        $controlCode->step3();

        $this->assertEquals('69DD0A42536C9900C4AE6484726C122ABDBF95D80A4BA403FB7834B3EC2A88595E2149A3D965923BA4547B42B9528AAE7B8CFB9996BA2B58516913057C9D791B6B748A', $controlCode->getAllegedRC4());
    }

    /** @test */
    public function fourth_step_calculates_ascii_sums()
    {
        $controlCode = $this->controlCode();

        $controlCode->step1();
        $controlCode->step2();
        $controlCode->step3();
        $controlCode->step4();

        $this->assertEquals('7720', $controlCode->getAsciiTotalSum());
    }

    /** @test */
    public function five_step_generates_base64_code()
    {
        $controlCode = $this->controlCode();

        $controlCode->step1();
        $controlCode->step2();
        $controlCode->step3();
        $controlCode->step4();
        $controlCode->step5();

        $this->assertEquals('18isw', $controlCode->getBase64());
    }

    /** @test */
    public function six_step_generates_control_code()
    {
        $controlCode = $this->controlCode();

        $controlCode->step1();
        $controlCode->step2();
        $controlCode->step3();
        $controlCode->step4();
        $controlCode->step5();
        $controlCode->step6();

        $this->assertEquals('6A-DC-53-05-14', $controlCode->getControlCode());
    }
}
