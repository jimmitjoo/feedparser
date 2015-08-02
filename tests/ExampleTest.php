<?php

namespace FeedParser\Tests;

class ExampleTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldAssertsTrue(){
        $this->assertEquals('0', '0');
    }


    public function testShouldAssertsFalse(){
        $this->assertEquals('0', 1);
    }
}
