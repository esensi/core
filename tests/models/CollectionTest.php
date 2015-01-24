<?php

use PHPUnit_Framework_TestCase as PHPUnit;
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
     * Setup and prepare for the tests.
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
     * @test that parseMixed() returns a collection for a single value.
     */
    public function parseMixedReturnsCollectionForSingleValue()
    {
        foreach ($this->array as $elem)
        {
            $collection = Collection::parseMixed($elem);
            $this->assertInstanceOf('Esensi\Core\Models\Collection', $collection);
            $this->assertEquals([$elem], $collection->all());
        }
    }

    /**
     * @test that parseMixed() returns a collection from a delimited string.
     */
    public function parseMixedReturnsCollectionForDelimitedString()
    {
        $collection = Collection::parseMixed($this->multipleString);
        $this->assertInstanceOf('Esensi\Core\Models\Collection', $collection);
        $this->assertEquals($this->expectedMultipleStringArray, $collection->all());
    }

    /**
     * @test that parseMixed() returns a collection for an array.
     */
    public function parseMixedReturnsCollectionForArray()
    {
        $collection = Collection::parseMixed($this->array);
        $this->assertInstanceOf('Esensi\Core\Models\Collection', $collection);
        $this->assertEquals($this->array, $collection->all());
    }

    /**
     * @test that parseMixed() returns an empty collection for an empty value.
     */
    public function parseMixedReturnsEmptyCollectionForEmptyValue()
    {
        $collection = Collection::parseMixed('');
        $this->assertInstanceOf('Esensi\Core\Models\Collection', $collection);
        $this->assertTrue($collection->isEmpty());
    }

    /**
     * @test that parseMixed() returns an empty collection for an empty array.
     */
    public function parseMixedReturnsEmptyCollectionForEmptyArray()
    {
        $collection = Collection::parseMixed([]);
        $this->assertInstanceOf('Esensi\Core\Models\Collection', $collection);
        $this->assertTrue($collection->isEmpty());
    }

    /**
     * @test that parseMixed() return a collection while ignoring empty values.
     */
    public function parseMixedIgnoresEmptyValues()
    {
        $collection = Collection::parseMixed($this->skippedInputs);
        $this->assertInstanceOf('Esensi\Core\Models\Collection', $collection);
        $this->assertTrue($collection->isEmpty());

        $collection = Collection::parseMixed($this->skippedInputs2);
        $this->assertInstanceOf('Esensi\Core\Models\Collection', $collection);
        $this->assertTrue($collection->isEmpty());
    }

}
