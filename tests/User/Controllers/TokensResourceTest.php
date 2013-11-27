<?php

use Alba\User\Controllers\TokensResource;
use Alba\User\Controllers\TokensResourceException;

class TokensResourceTest extends TestCase {
    

    public function setUp() {
        parent::setUp();
        
        //every test will have a mock Token inyected, available through $this->token
        $this->token = Mockery::mock('Alba\User\Models\Token')->shouldDeferMissing();
        $this->tokensResource = new TokensResource($this->token);

        //database already migrated from parent setUp()
        //Artisan::call('db:seed');

    }


    public function tearDown() {
        Mockery::close();

        parent::tearDown();
    }



    //Tests for store method
    //@todo: this cases seem easy enough to not test, but maybe there are other that 
    //might need testing following this example
    
    /**
     * @expectedException Alba\User\Controllers\TokensResourceException
     */
    public function testStoreWhenSaveReturnsFalse()
    {
        
        $this->token->shouldReceive('fill')->once();
        $this->token->shouldReceive('save')->once()->andReturn(false);
        
        $res = $this->tokensResource->store(array());

    }


    public function testStoreWhenSaveReturnsTrue()
    {
        
        $this->token->shouldReceive('fill')->once();
        $this->token->shouldReceive('save')->once()->andReturn(true);
        
        $res = $this->tokensResource->store(array());
        $this->assertEquals($res, $this->token);

    }




}