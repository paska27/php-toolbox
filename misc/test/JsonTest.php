<?php

namespace Paska\Toolbox;

class JsonTest extends \PHPUnit_Framework_TestCase
{
    public function testEncode()
    {
        $data = array(
            'marry' => 'Had',
            'a' => 'little',
            'lamb' => true,
            0 => 'its',
            'fleece' => 1,
            'was' => array(
                'white' => 2,
                array(
                    'as' => 'show'
                )
            )
        );
        $json = '{"marry":"Had","a":"little","lamb":true,"0":"its","fleece":1,"was":{"white":2,"0":{"as":"show"}}}';

        $this->assertEquals($json, (new Json($data))->encode());
        $this->assertNotEquals($data, (new Json($data))->encode());
        $this->assertEquals($data, Json::parseString($json));

        $this->expectException(\InvalidArgumentException::class);
        Json::parseString(substr($json, 0, -1));
    }
}
