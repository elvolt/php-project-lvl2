<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\GenDiff\genDiff;
use function Differ\GenDiff\getAbsolutePath;

class TestGenDiff extends TestCase
{
    public function testGenDiff()
    {
        $result = file_get_contents(__DIR__ . '/fixtures/result');
        $beforePath = __DIR__ . '/fixtures/before.json';
        $afterPath = __DIR__ . '/fixtures/after.json';
        $this->assertEquals($result, genDiff($beforePath, $afterPath));
    }
}