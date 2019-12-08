<?php

namespace SamLitowitz\Psalm\Plugin\Tests;

use SamLitowitz\Psalm\Plugin\NoDoubleQuotedStrings;
use SimpleXMLElement;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psalm\Plugin\RegistrationInterface;

class NoDoubleQuotedStringsTest extends TestCase
{
    /**
     * @var ObjectProphecy
     */
    private $registration;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->registration = $this->prophesize(RegistrationInterface::class);
    }

    /**
     * @test
     * @return void
     */
    public function hasEntryPoint()
    {
        $this->expectNotToPerformAssertions();
        $plugin = new NoDoubleQuotedStrings();
        $plugin($this->registration->reveal(), null);
    }

    /**
     * @test
     * @return void
     */
    public function acceptsConfig()
    {
        $this->expectNotToPerformAssertions();
        $plugin = new NoDoubleQuotedStrings();
        $plugin($this->registration->reveal(), new SimpleXMLElement('<myConfig></myConfig>'));
    }

    /**
     * @return void
     */
    public function detectsDoubleQuotedString()
    {
        $doubleQuotedString = "Hello world";
        $this->assertNotEmpty($doubleQuotedString);
    }

    /**
     * @return void
     */
    public function detectsHeredocString()
    {
        $hereDocString = <<<"SQL"
SELECT * FROM `table_name`;
SQL;
        $this->assertNotEmpty($hereDocString);
    }
}
