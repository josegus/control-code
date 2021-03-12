<?php

namespace Josegus\ControlCode;

final class ControlCode
{
    private $authorizationNumber;
    private $invoiceNumber;
    private $transactionDate;
    private $transactionMount;
    private $dosificationKey;
    private $customerDocumentNumber = 0;

    private $lastFiveVerhoeffDigits;
    private $allegedRC4;
    private $asciiTotalSum = 0;
    private $asciiPartialSum1 = 0;
    private $asciiPartialSum2 = 0;
    private $asciiPartialSum3 = 0;
    private $asciiPartialSum4 = 0;
    private $asciiPartialSum5 = 0;
    private $base64;
    private $controlCode;

    /**
     * Return a new instance of this class
     *
     * @return ControlCode
     */
    public static function make()
    {
        return new self;
    }

    public function getAuthorizationNumber(): string
    {
        return $this->authorizationNumber;
    }

    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    public function getTransactionDate(): string
    {
        return $this->transactionDate;
    }

    public function getDosageKey(): string
    {
        return $this->dosificationKey;
    }

    public function getCustomerDocumentNumber(): string
    {
        return $this->customerDocumentNumber;
    }

    public function getTransactionMount(): string
    {
        return $this->transactionMount;
    }

    public function getLastFiveVerhoeffDigits(): string
    {
        return $this->lastFiveVerhoeffDigits;
    }

    public function getAllegedRC4(): string
    {
        return $this->allegedRC4;
    }

    public function getAsciiTotalSum()
    {
        return $this->asciiTotalSum;
    }

    public function getAsciiPartialSum1()
    {
        return $this->asciiPartialSum1;
    }

    public function getAsciiPartialSum2()
    {
        return $this->asciiPartialSum2;
    }

    public function getAsciiPartialSum3()
    {
        return $this->asciiPartialSum3;
    }

    public function getAsciiPartialSum4()
    {
        return $this->asciiPartialSum4;
    }

    public function getAsciiPartialSum5()
    {
        return $this->asciiPartialSum5;
    }

    public function getBase64()
    {
        return $this->base64;
    }

    public function getControlCode()
    {
        return $this->controlCode;
    }

    /**
     * Genera el código de control
     *
     * @return string
     */
    public function generate()
    {
        $this->step1();
        $this->step2();
        $this->step3();
        $this->step4();
        $this->step5();
        $this->step6();

        return $this->controlCode;
    }

    /**
     * Generate and return the five Verhoeff digits for first step
     *
     * @return void
     */
    public function step1()
    {
        $this->invoiceNumber = Verhoeff::append($this->invoiceNumber, 2);
        $this->customerDocumentNumber = Verhoeff::append($this->customerDocumentNumber, 2);
        $this->transactionDate = Verhoeff::append($this->transactionDate, 2);
        $this->transactionMount = Verhoeff::append($this->transactionMount, 2);

        // Sum generated values
        $sumOfVariables =
            intval($this->invoiceNumber) +
            intval($this->customerDocumentNumber) +
            intval($this->transactionDate) +
            intval($this->transactionMount);

        // A la suma total se añade 5 digitos Verhoeff
        $sumOfVariablesWithFiveVerhoeffDigits = Verhoeff::append((string)$sumOfVariables, 5);

        // Obtener los ultimos 5 digitos (los generados) por Verhoeff
        $this->lastFiveVerhoeffDigits = substr($sumOfVariablesWithFiveVerhoeffDigits, -5);
    }

    public function step2()
    {
        // Almacenar los 5 digitos obtenidos en un array
        $lastFiveVerhoeffDigitsArray = str_split($this->lastFiveVerhoeffDigits);

        $lastDigitsArray = [];

        # Sumar cada item del array +1
        for ($i = 0; $i < count($lastFiveVerhoeffDigitsArray); $i++) {
            $newDigit = intval($lastFiveVerhoeffDigitsArray[$i]) + 1;
            $lastDigitsArray[] = $newDigit;
        }

        $string1 = substr($this->dosificationKey, 0, $lastDigitsArray[0]);
        $string2 = substr($this->dosificationKey, $lastDigitsArray[0], $lastDigitsArray[1]);
        $string3 = substr($this->dosificationKey, $lastDigitsArray[0] + $lastDigitsArray[1], $lastDigitsArray[2]);
        $string4 = substr($this->dosificationKey, $lastDigitsArray[0] + $lastDigitsArray[1] +
            $lastDigitsArray[2], $lastDigitsArray[3]);
        $string5 = substr($this->dosificationKey, $lastDigitsArray[0] + $lastDigitsArray[1] +
            $lastDigitsArray[2] + $lastDigitsArray[3], $lastDigitsArray[4]);

        // Concatenar las cadenas obtenidas con los atributos de factura
        $this->authorizationNumber .= $string1;
        $this->invoiceNumber .= $string2;
        $this->customerDocumentNumber .= $string3;
        $this->transactionDate .= $string4;
        $this->transactionMount .= $string5;
    }

    public function step3()
    {
        $cadenaConcatenada = $this->authorizationNumber .
            $this->invoiceNumber .
            $this->customerDocumentNumber .
            $this->transactionDate .
            $this->transactionMount;

        // Llave para cifrado
        $llaveParaCifrado = $this->dosificationKey . $this->lastFiveVerhoeffDigits;

        $this->allegedRC4 = AllegedRC4::generate($cadenaConcatenada, $llaveParaCifrado, 'normal');
    }

    public function step4()
    {
        // Cadena encriptada en paso 3 se convierte a un Array
        $chars = str_split($this->allegedRC4);

        $tmp = 1;
        for ($i = 0; $i < strlen($this->allegedRC4); $i++) {
            $this->asciiTotalSum += ord($chars[$i]);

            switch ($tmp) {
                case 1:
                    $this->asciiPartialSum1 += ord($chars[$i]);

                    break;
                case 2:
                    $this->asciiPartialSum2 += ord($chars[$i]);

                    break;
                case 3:
                    $this->asciiPartialSum3 += ord($chars[$i]);

                    break;
                case 4:
                    $this->asciiPartialSum4 += ord($chars[$i]);

                    break;
                case 5:
                    $this->asciiPartialSum5 += ord($chars[$i]);

                    break;
            }

            $tmp = $tmp < 5 ? $tmp + 1 : 1;
        }
    }

    public function step5()
    {
        // Almacenar los 5 digitos obtenidos en un array
        $lastFiveVerhoeffDigitsArray = str_split($this->lastFiveVerhoeffDigits);

        $lastDigitsArray = [];

        # Sumar cada item del array +1
        for ($i = 0; $i < count($lastFiveVerhoeffDigitsArray); $i++) {
            $newDigit = intval($lastFiveVerhoeffDigitsArray[$i]) + 1;
            $lastDigitsArray[] = $newDigit;
        }

        // Suma total * sumas parciales dividido entre resultados obtenidos
        // entre el dígito Verhoeff correthis->asciiPartialSumondiente más 1 (paso 2)
        $tmp1 = floor($this->asciiTotalSum * $this->asciiPartialSum1 / $lastDigitsArray[0]);
        $tmp2 = floor($this->asciiTotalSum * $this->asciiPartialSum2 / $lastDigitsArray[1]);
        $tmp3 = floor($this->asciiTotalSum * $this->asciiPartialSum3 / $lastDigitsArray[2]);
        $tmp4 = floor($this->asciiTotalSum * $this->asciiPartialSum4 / $lastDigitsArray[3]);
        $tmp5 = floor($this->asciiTotalSum * $this->asciiPartialSum5 / $lastDigitsArray[4]);

        // Se suman todos los resultados
        $sumProduct = $tmp1 + $tmp2 + $tmp3 + $tmp4 + $tmp5;

        // Se obtiene base64
        $this->base64 = Base64::convert((string)$sumProduct);
    }

    public function step6()
    {
        $this->controlCode = AllegedRC4::generate($this->base64, $this->dosificationKey . $this->lastFiveVerhoeffDigits);
    }

    /**
     * Número de autorización de dosificación
     *
     * @param $value
     * @return $this
     */
    public function authorizationNumber(string $value)
    {
        $this->authorizationNumber = $value;

        return $this;
    }

    /**
     * Número único y autoincrementable de factura
     *
     * @param $value
     * @return $this
     */
    public function invoiceNumber(string $value)
    {
        $this->invoiceNumber = $value;

        return $this;
    }

    /**
     * Fecha en formato Y-m-d o Y/m/d
     *
     * @param $value
     * @return $this
     */
    public function transactionDate(string $value)
    {
        // Convertir a yyyymmdd, reemplazando cualquier separador de fechas, ya sea "/" o "-"
        $value = str_replace('/', '', $value);
        $value = str_replace('-', '', $value);

        $this->transactionDate = intval($value);

        return $this;
    }

    /**
     * Monto de transacción, en numeral
     *
     * @param $value
     * @return $this
     */
    public function transactionMount($value)
    {
        // Reemplazar (,) por (.)
        $value = str_replace(',', '.', $value);

        // Redondear a 0 decimales
        $this->transactionMount = round((float)$value, 0, PHP_ROUND_HALF_UP);

        return $this;
    }

    /**
     * Cadena usada como llave de dosificación
     *
     * @param $value
     * @return $this
     */
    public function dosificationKey(string $value)
    {
        $this->dosificationKey = $value;

        return $this;
    }

    /**
     * Número de documento del cliente (NIT)
     *
     * @param int $value
     * @return $this
     */
    public function customerDocumentNumber($value = 0)
    {
        $this->customerDocumentNumber = $value;

        return $this;
    }
}
