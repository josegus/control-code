<?php

namespace Josegus\ControlCode\Tests;

use Josegus\ControlCode\Verhoeff;
use PHPUnit\Framework\TestCase;

class VerhoeffTest extends TestCase
{
    /** @test */
    public function it_add_digit_to_zero()
    {
        $expected = Verhoeff::append('0');

        $this->assertEquals('04', $expected);
    }

    /** @test */
    public function it_appends_two_digits_case_a()
    {
        $expected = Verhoeff::append('1503', 2);

        $this->assertEquals('150312', $expected);
    }

    /** @test */
    public function it_appends_two_digits_case_b()
    {
        $expected = Verhoeff::append('4189179011', 2);

        $this->assertEquals('418917901158', $expected);
    }

    /** @test */
    public function it_appends_two_digits_case_c()
    {
        $expected = Verhoeff::append('20070702', 2);

        $this->assertEquals('2007070201', $expected);
    }

    /** @test */
    public function it_appends_two_digits_case_d()
    {
        $expected = Verhoeff::append('2500', 2);

        $this->assertEquals('250031', $expected);
    }
}
