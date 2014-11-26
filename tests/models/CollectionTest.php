<?php

use \PHPUnit_Framework_TestCase as PHPUnit;
use Esensi\Core\Models\Collection;

/**
 * Tests for the Collection class
 *
 * @package Esensi\Core
 * @author diego <diego@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class CollectionTest extends PHPUnit {

    /**
     * Setup adn prepare for the tests
     */
    public function setUp()
    {
        $this->multipleString = ',1,2,3,4,5,0,';
        $this->expectedMultipleStringArray = ['1', '2', '3', '4', '5', '0'];

        $this->array = ['1', '2', 3, true, false, 'blabla', 0, 0.0, '0'];

        $this->skippedInputs = [' ', '   ', null];
        $this->skippedInputs2 = ' ,    ,';
    }

    /**
     * @test
     */
    public function it_creates_a_collection_from_single_values()
    {
        foreach ($this->array as $elem)
        {
            $collection = Collection::parseMixed($elem);
            $this->assertInstanceOf('\Esensi\Core\Models\Collection', $collection);
            $this->assertEquals([$elem], $collection->all());
        }
    }

    /**
     * @test
     */
    public function it_creates_a_collection_from_multiple_value_string()
    {
        $collection = Collection::parseMixed($this->multipleString);
        //var_dump($collection->all());
        $this->assertInstanceOf('\Esensi\Core\Models\Collection', $collection);
        $this->assertEquals($this->expectedMultipleStringArray, $collection->all());
    }

    /**
     * @test
     */
    public function it_creates_a_collection_from_array()
    {
        $collection = Collection::parseMixed($this->array);
        $this->assertInstanceOf('\Esensi\Core\Models\Collection', $collection);
        $this->assertEquals($this->array, $collection->all());
    }

    /**
     * @test
     */
    public function it_creates_an_empty_collection_from_empty_string()
    {
        $collection = Collection::parseMixed('');
        $this->assertInstanceOf('\Esensi\Core\Models\Collection', $collection);
        $this->assertTrue($collection->isEmpty());
    }

    /**
     * @test
     */
    public function it_creates_an_empty_collection_from_empty_array()
    {
        $collection = Collection::parseMixed([]);
        $this->assertInstanceOf('\Esensi\Core\Models\Collection', $collection);
        $this->assertTrue($collection->isEmpty());
    }

    /**
     * @test
     */
    public function it_skips_blanks_and_null_elements()
    {
        $collection = Collection::parseMixed($this->skippedInputs);
        $this->assertInstanceOf('\Esensi\Core\Models\Collection', $collection);
        $this->assertTrue($collection->isEmpty());

        $collection = Collection::parseMixed($this->skippedInputs2);
        $this->assertInstanceOf('\Esensi\Core\Models\Collection', $collection);
        $this->assertTrue($collection->isEmpty());
    }

}

