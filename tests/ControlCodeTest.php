<?php

namespace Josegus\ControlCode\Tests;

use Josegus\ControlCode\ControlCode;
use PHPUnit\Framework\TestCase;

class ControlCodeTest extends TestCase
{
    const AUTHORIZATION_NUMBER = 0;
    const INVOICE_NUMBER = 1;
    const CUSTOMER_DOCUMENT_NUMBER = 2;
    const TRANSACTION_DATE = 3;
    const TRANSACTION_MOUNT = 4;
    const DOSAGE_KEY = 5;
    const FIVE_VERHOEFF_DIGITS = 6;
    const CONCATENATED_STRING = 7;
    const SUMATORY = 8;
    const BASE64 = 9;
    const CONTROL_CODE = 10;

    /**
     * List of codes expected to be generated
     *
     * @var array
     */
    protected $expectedCodes = [];

    /**
     * List of codes generated from ControlCode class
     *
     * @var array
     */
    protected $generatedCodes = [];

    /**
     *
     * We will ignore first line, because it contains headings text
     *
     * @var bool
     */
    protected $isFirstIteration = true;

    /** @test */
    public function it_generate_control_codes()
    {
        // Read source test case
        $file = fopen(__DIR__ . '/stubs/test-case.txt', 'r');

        while (! feof($file)) {
            $line = fgets($file);

            $this->addGenerateControlCode($line);
            $this->addExpectedControlCode($line);

            $this->isFirstIteration = false;
        }

        fclose($file);

        $this->assertEquals($this->expectedCodes, $this->generatedCodes);
    }

    protected function addGenerateControlCode($line)
    {
        $line = trim($line);

        if (empty($line) || $this->isFirstIteration) {
            return;
        }

        // Explode the line string in an array containing all
        // attributes needed to generate control code
        $codes = explode('|', $line);

        $this->generatedCodes[] = ControlCode::make()
            ->authorizationNumber($codes[self::AUTHORIZATION_NUMBER])
            ->invoiceNumber($codes[self::INVOICE_NUMBER])
            ->customerDocumentNumber($codes[self::CUSTOMER_DOCUMENT_NUMBER])
            ->transactionDate($codes[self::TRANSACTION_DATE])
            ->transactionMount($codes[self::TRANSACTION_MOUNT])
            ->dosificationKey($codes[self::DOSAGE_KEY])
            ->generate();
    }

    protected function addExpectedControlCode($line)
    {
        $line = trim($line);

        if (empty($line) || $this->isFirstIteration) {
            return;
        }

        // Explode the line string in an array containing all
        // attributes needed to generate control code
        $codes = explode('|', $line);

        $this->expectedCodes[] = $codes[self::CONTROL_CODE];
    }
}
