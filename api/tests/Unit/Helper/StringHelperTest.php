<?php declare(strict_types=1);

namespace App\Tests\Unit\Helper;

use PHPUnit\Framework\TestCase;
use App\Helper\StringHelper;

class StringHelperTest extends TestCase
{
    /**
     * @test
     * @group Unit
     * @group Helper
     * @dataProvider slugifyCases
     */
    public function slugify(string $case, string $expected)
    {
        $this->assertEquals($expected, StringHelper::Slugify($case));
    }

    public function slugifyCases(): array
    {
        return [
            [
                'case' => 'AAAAAA',
                'expected' => 'aaaaaa'
            ]
        ];
    }
}
