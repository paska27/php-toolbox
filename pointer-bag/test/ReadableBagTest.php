<?php
namespace Paska\Toolbox\PointerBag;

class ReadableBagTest extends \PHPUnit_Framework_TestCase
{
    static private $fixture = [
        'find' => [
            'scalar' => 123,
            'array' => [1, 2, 3],
            'me' => [
                'very' => [
                    'dummy' => 1,
                    'very' => [
                        'stuff' => 'stuff',
                        'deep' => [
                            'along the way' => null,
                            'inside' => 'honey !'
                        ]
                    ]
                ]
            ]
        ],
        'find_me' => [
            'on_top' => [
                9
            ]
        ],
        'touch_me' => [
            3, 3, 3
        ],
        'touchMe' => [
            'soft' => [
                'hard' => 'device'
            ]
        ]
    ];

    public function testGetValue()
    {
        $bag = new ReadableBag(self::$fixture);

        $this->assertEquals(123, $bag->find->scalar->get());
        $this->assertEquals([1, 2, 3], $bag->find->array->get());
        $this->assertEquals('honey !', $bag->find->me->very->very->deep->inside->get());
        $this->assertNotEquals('honey ?', $bag->find->me->very->very->deep->inside->get());
        $this->assertEquals('honey !', $bag->find->me->very->very->deep->inside->get());
        $this->assertEquals([9], $bag->find_me->on_top->get());
        $this->assertEquals('default', $bag->ghost->get('default'));
        $this->assertNotEquals(null, $bag->ghost->get('default'));
        $this->assertEquals(null, $bag->ghost->get());
        $this->assertEquals([9], $bag->find_me->on_top->get(123));
    }

    public function testGetSubBag()
    {
        $bag = new ReadableBag(self::$fixture);

        $touchMe = $bag->touch_me->copy();
        $this->assertInstanceOf(ReadableBag::class, $touchMe);
        $this->assertEquals([3, 3, 3], $touchMe->get());

        $touchMe = $bag->touchMe->copy();
        $this->assertInstanceOf(ReadableBag::class, $touchMe);
        $this->assertEquals(['soft' => ['hard' => 'device']], $touchMe->get());
        $this->assertEquals(['hard' => 'device'], $touchMe->soft->get());
        $this->assertEquals('device', $touchMe->soft->hard->get());
        $this->assertEquals('device', $bag->touchMe->soft->hard->get()); // make sure original bag isn't mutated
        $this->assertNotEquals('super cool device', $touchMe->soft->hard->get());
        $this->assertEquals('super cool device', $touchMe->soft->hard->super->cool->device->get('super cool device'));
        $this->assertEquals('bag device', $bag->touchMe->soft->bag->device->get('bag device')); // make sure original bag isn't mutated
    }
}
