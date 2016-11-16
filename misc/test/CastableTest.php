<?php

use Paska\Toolbox\Castable;

class TestClass
{}

class TestClass2
{}

class CastableTest extends PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $obj = new TestClass();
        $arr = [
            0 => 'zero',
            'one' => 'one',
            2 => 2,
            'three' => 3
        ];
        $res = fopen(__FILE__, 'r');
        $null = null;
        $false = false;
        $true = true;

        $this->assertEquals('Object(TestClass)', Castable::toString($obj));
        $this->assertEquals('Array(0 => zero, one => one, 2 => 2, three => 3)', Castable::toString($arr));
        $this->assertNotEquals('Array(zero => zero, 1 => one, two => 2, 3 => three)', Castable::toString($arr));
        $this->assertNotEquals('Array(zero => zero, 1 => one, two => 2, 3 => three)', Castable::toString($arr));
        $this->assertEquals('Resource(stream)', Castable::toString($res));
        $this->assertEquals('null', (string) new Castable($null));
        $this->assertNotEquals(null, Castable::toString($null));
        $this->assertEquals('false', (string) new Castable($false));
        $this->assertNotEquals(false, Castable::toString($false));
        $this->assertEquals('true', (string) new Castable($true));
        $this->assertNotEquals(true, Castable::toString($true));
    }

    function testToMap() {
        $collection = array_fill(0, 5, new TestClass());
        $this->assertArrayHasKey('testclasss', Castable::toMap($collection));

        $mixedMap = array(
            0 => new TestClass(),
            'stdClass' => new \stdClass(),
            1 => new TestClass2()
        );
        $map = Castable::toMap($mixedMap);

        $this->assertCount(3, $map);
        $this->assertArrayHasKey('testclass', $map);
        $this->assertArrayHasKey('stdClass', $map);
        $this->assertArrayHasKey('testclass2', $map);
        $this->assertFalse(Castable::isMap($mixedMap));
        $this->assertTrue(Castable::isMap($map));
    }

    function testToList() {
        $list = array('one', 'two', 'three');
        $this->assertNotEquals($list, Castable::toList($list, ','));
        $this->assertEquals('one,two,three', Castable::toList($list, ','));
        $this->assertEquals('one;two;three', Castable::toList($list, ';'));
        $this->assertEquals('"one", "two", "three"', Castable::toList($list, ', ', '"'));
        $this->assertEquals('<one>, <two>, <three>', Castable::toList($list, ', ', array('<', '>')));
        $this->assertEquals('one, two, three', Castable::toCsv($list));
        $this->assertEquals("'one', 'two', 'three'", Castable::toCsv($list, "'"));
    }
}
