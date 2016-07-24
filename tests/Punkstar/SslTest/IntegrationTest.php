<?php

namespace Punkstar\SslTest;

use PHPUnit\Framework\TestCase;
use Punkstar\Ssl\Reader;

class IntegrationTest extends TestCase
{
    /**
     * @test
     * @dataProvider exampleCertsDataProvider
     */
    public function testExampleCerts($certFileName)
    {
        $reader = new Reader();
        $cert = $reader->readFromFile($certFileName);

        $this->assertInternalType('string', $cert->certName());
        $this->assertInternalType('string', $cert->toString());
        $this->assertInstanceOf('DateTime', $cert->validTo());
        $this->assertInstanceOf('DateTime', $cert->validFrom());
    }

    /**
     * @test
     * @dataProvider exampleCertsDataProvider
     */
    public function testRawCertificate($certFileName)
    {
        $reader = new Reader();
        $cert = $reader->readFromFile($certFileName);

        $rawCertFromFile = file_get_contents($certFileName);

        $this->assertEquals($rawCertFromFile, $cert->toString());
    }

    /**
     * @test
     */
    public function testUrlReading()
    {
        $reader = new Reader();
        $cert = $reader->readFromUrl("https://google.com");
        $this->assertInstanceOf("Punkstar\\Ssl\\Certificate", $cert);
    }

    public function exampleCertsDataProvider()
    {
        $certs = glob(__DIR__ . "/../../../example_certs/*");
        $dataProvider = [];

        foreach ($certs as $cert) {
            $dataProvider[] = [$cert];
        }

        return $dataProvider;
    }
}
