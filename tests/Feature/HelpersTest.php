<?php

namespace Tests\Unit;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_encodes_securely_in_base64()
    {
        $result = secureEncode64('hello');
        $this->assertStringContainsString('hello', base64_decode($result));
    }

    /** @test */
    public function it_formats_currency()
    {
        $result = format_currency(1234.56);
        $this->assertIsString($result);
        $this->assertStringContainsString('€', $result);
    }

    /** @test */
    public function it_formats_number()
    {
        $result = format_number(1234.56);
        $this->assertEquals('1 234,56', $result);
    }

    /** @test */
    public function it_formats_date()
    {
        $date = format_date('2023-05-01');
        $this->assertEquals('01/05/2023', $date);
    }

    /** @test */
    public function it_formats_hour()
    {
        $this->assertEquals('12:30', format_hour('12:30:45'));
        $this->assertEquals('09:15', format_hour('09:15'));
    }

    /** @test */
    public function it_formats_telephone()
    {
        $this->assertEquals('06 12 34 56 78', format_telephone('0612345678'));
    }

    /** @test */
    public function it_formats_siret()
    {
        $this->assertEquals('123 456 789 12345', format_siret('12345678912345'));
    }

    /** @test */
    public function it_converts_french_date_to_english()
    {
        $this->assertEquals('2023-01-31', format_date_FrToEng('31/01/2023'));
    }

    /** @test */
    public function it_calculates_days_between_dates()
    {
        $days = nbDaysBetween('2023-01-01', '2023-01-10');
        $this->assertEquals(9, $days);
    }

    /** @test */
    public function it_calculates_weekend_days_between_dates()
    {
        $days = nbDaysOffBetween('2023-01-01', '2023-01-10');
        $this->assertGreaterThanOrEqual(2, $days);
    }

    /** @test */
    public function it_returns_readable_file_size()
    {
        $this->assertEquals('1 kB', sizeFileReadable(1024));
    }

    /** @test */
    public function it_sanitizes_float_value()
    {
        $this->assertEquals(1234.56, sanitizeFloat('1 234,56 €'));
    }

    /** @test */
    public function it_removes_formatting_from_value()
    {
        $this->assertEquals(1234.56, supprimer_decoration('1.234,56 €'));
    }

    /** @test */
    public function it_returns_boolean_value()
    {
        $this->assertTrue(bool_val('true'));
        $this->assertFalse(bool_val('false'));
        $this->assertTrue(bool_val('yes'));
        $this->assertFalse(bool_val('no'));
    }

    /** @test */
    public function it_returns_empty_collection_if_not_admin()
    {
        $this->assertCount(0, salaries());
    }
}
