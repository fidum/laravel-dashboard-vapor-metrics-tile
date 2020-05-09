<?php

namespace Fidum\VaporMetricsTile\Tests\Unit\Charts;

use Fidum\VaporMetricsTile\Charts\ChartType;
use Fidum\VaporMetricsTile\Tests\TestCase;

class ChartTypeTest extends TestCase
{
    public function testInvalidReturnsDefault()
    {
        $this->assertSame('averageFunctionDurationByInterval', ChartType::field(''));
        $this->assertSame('Average HTTP Request Duration (ms) ()', ChartType::label('', ''));

        $this->assertSame('averageFunctionDurationByInterval', ChartType::field('Invalid Type'));
        $this->assertSame(
            'Average HTTP Request Duration (ms) (Example Tile Name)',
            ChartType::label('Example Tile Name', '')
        );
    }

    public function testTypesReturnExpectedValues()
    {
        foreach (ChartType::TYPES as $type => $expected) {
            $this->assertSame($expected['field'], ChartType::field($type));
            $this->assertSame(
                $expected['label'] . ' (Example Tile Name)',
                ChartType::label('Example Tile Name', $type)
            );
        }
    }
}
