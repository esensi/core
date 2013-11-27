<?php

use Alba\User\Models\Name;


class NameTest extends TestCase {


    public function setUp() {
        parent::setUp();
        $this->name = new Name();
    }


    public function testFormatName() {
        
        $this->name->title = 'Mr';
        $this->name->first_name = 'First';
        $this->name->middle_name = 'Middle';
        $this->name->last_name = 'Last';
        $this->name->suffix = 'PhD';

        $expect = 'Mr First Middle Last PhD';
        $actual = $this->name->formatName('T F M L S');
        $this->assertEquals($expect, $actual, "'$expect' <> '$actual'");

        $expect = 'Mr First Last';
        $actual = $this->name->formatName('T F L');
        $this->assertEquals($expect, $actual, "'$expect' <> '$actual'");

        $expect = 'Mr Last PhD';
        $actual = $this->name->formatName('T L S');
        $this->assertEquals($expect, $actual, "'$expect' <> '$actual'");

        $expect = 'First Last';
        $actual = $this->name->formatName('F L');
        $this->assertEquals($expect, $actual, "'$expect' <> '$actual'");

        $expect = 'First Last';
        $actual = $this->name->formatName();
        $this->assertEquals($expect, $actual, "'$expect' <> '$actual'");

    }


    public function testValidateBasic() {

        $this->name->title = null;
        $this->name->first_name = null; //
        $this->name->middle_name = null;
        $this->name->last_name = null; //
        $this->name->suffix = null;        
        $this->assertFalse($this->name->validate($this->name->rulesForNameOnly));

        $this->name->title = null;
        $this->name->first_name = 'asdasd';
        $this->name->middle_name = null;
        $this->name->last_name = null; //
        $this->name->suffix = null;
        $this->assertFalse($this->name->validate($this->name->rulesForNameOnly));

        $this->name->title = null;
        $this->name->first_name = null; //
        $this->name->middle_name = null;
        $this->name->last_name = 'asdsad';
        $this->name->suffix = null;
        $this->assertFalse($this->name->validate($this->name->rulesForNameOnly));

        $this->name->title = '01234567891011'; //
        $this->name->first_name = 'asdsad';
        $this->name->middle_name = null;
        $this->name->last_name = 'asdasd';
        $this->name->suffix = null;
        $this->assertFalse($this->name->validate($this->name->rulesForNameOnly));

        $this->name->title = '012345678910';
        $this->name->first_name = 'asdsad';
        $this->name->middle_name = null;
        $this->name->last_name = 'asdasd';
        $this->name->suffix = '1234567891011'; //
        $this->assertFalse($this->name->validate($this->name->rulesForNameOnly));

        $this->name->title = '0123456789';
        $this->name->first_name = 'asdsad';
        $this->name->middle_name = null;
        $this->name->last_name = 'asdasd';
        $this->name->suffix = '0123456789';
        $this->assertTrue($this->name->validate($this->name->rulesForNameOnly));

    }


}