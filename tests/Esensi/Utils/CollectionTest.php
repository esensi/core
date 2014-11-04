<?php

use \PHPUnit_Framework_TestCase as PHPUnit;
use Esensi\Core\Utils\Collection;

/**
 * Tests for the Collection class
 *
 * @package Esensi\Model
 * @author diego <diego@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/model/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
class CollectionTest extends PHPUnit {

    /**
     * Setup adn prepare for the tests
     */
    public function setUp()
    {
        $this->singleString = '1';
        $this->expectedSingleStringArray = ['1'];

        $this->multipleString = '1,2,3,4,5';
        $this->expectedMultipleStringArray = ['1', '2', '3', '4', '5'];

        $this->array = ['1', '2', 3, true, false];

        $this->notAllowedInputs = [1, true, new Collection([]), 1.12];
    }

    /**
     * @test
     */
    public function it_creates_a_collection_from_single_value_string()
    {
        $collection = Collection::parseMixed($this->singleString);
        $this->assertInstanceOf('\Esensi\Core\Utils\Collection', $collection);
        $this->assertEquals($this->expectedSingleStringArray, $collection->all());
    }

    /**
     * @test
     */
    public function it_creates_a_collection_from_multiple_value_string()
    {
        $collection = Collection::parseMixed($this->multipleString);
        $this->assertInstanceOf('\Esensi\Core\Utils\Collection', $collection);
        $this->assertEquals($this->expectedMultipleStringArray, $collection->all());
    }

    /**
     * @test
     */
    public function it_creates_a_collection_from_array()
    {
        $collection = Collection::parseMixed($this->array);
        $this->assertInstanceOf('\Esensi\Core\Utils\Collection', $collection);
        $this->assertEquals($this->array, $collection->all());
    }

    /**
     * @test
     */
    public function it_creates_an_empty_collection_from_empty_string()
    {
        $collection = Collection::parseMixed('');
        $this->assertInstanceOf('\Esensi\Core\Utils\Collection', $collection);
        $this->assertTrue($collection->isEmpty());
    }

    /**
     * @test
     */
    public function it_creates_an_empty_collection_from_empty_array()
    {
        $collection = Collection::parseMixed([]);
        $this->assertInstanceOf('\Esensi\Core\Utils\Collection', $collection);
        $this->assertTrue($collection->isEmpty());
    }

    /**
     * @test
     */
    public function it_doesnt_accept_input_other_than_string_or_array()
    {
        foreach ($this->notAllowedInputs as $elem)
        {
            try
            {
                $collection = Collection::parseMixed($elem);
            }
            catch (\InvalidArgumentException $e)
            {
                continue;
            }
            $this->fail("It should've thrown an InvalidArgumentException!");
        }
    }

}

