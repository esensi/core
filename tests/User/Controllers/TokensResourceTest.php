<?php

use Alba\User\Controllers\TokensResource;

class TokensResourceTest extends TestCase {
    

    public function setUp() {
        parent::setUp();

        $this->tokensResource = new TokensResource();

        //database already migrated from parent setUp()
        //Artisan::call('db:seed');

    }


    public function tearDown() {
        Mockery::close();

        parent::tearDown();
    }




    public function testGenerateTokenOk() {
        
        $token = $this->tokensResource->generateToken('activation');
        $this->assertNotNull($token);

    }


}