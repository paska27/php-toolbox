<?php

use Paska\Toolbox\PointerBag\WritableBag;

class WritableBagTest extends PHPUnit_Framework_TestCase
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

    public function testSetValue()
    {
        $bag = new WritableBag(self::$fixture);

        $bag->pocket->set('tissue');
        $this->assertEquals('tissue', $bag->pocket->get());
        $this->assertGetters($bag);

        $bag->pocket->wallet->set('hundred bucks');
        $this->assertEquals('hundred bucks', $bag->pocket->wallet->get());
        $this->assertGetters($bag);
    }

    private function assertGetters(WritableBag $bag)
    {
        $this->assertEquals([3, 3, 3], $bag->touch_me->get());
        $this->assertEquals('honey !', $bag->find->me->very->very->deep->inside->get());
        $this->assertNotEquals('honey !', $bag->find->me->very->very->deep->inside->me->get('dummy'));
    }
}
