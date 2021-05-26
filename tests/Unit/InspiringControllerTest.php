<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Service;
use Mockery;
use Mockery\MockInterface;
use App\Http\Controllers\InspiringController;
use App\Services\InspiringService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Foundation\Testing\WithFaker;

class InspiringControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    private $mock;
    public function testInspire()
    {

        /** @var \Mockery\MockInterface|\App\Services\InspiringService */
        $mock = \Mockery::mock(InspiringService::class);
        $mock->shouldReceive('inspire')->andReturn('名言'); 
        // mock is actually an object but vscode interpreter shows error string,
        // just ignore the red underline and test it like normal.(I make it ignore by the @var on top)

        $inspiringController = new InspiringController($mock);
        self::assertEquals(
            '名言',
            $inspiringController->inspire()
        );
    }
}
