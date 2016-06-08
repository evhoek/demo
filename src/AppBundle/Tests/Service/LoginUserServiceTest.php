<?php
namespace Tests\AppBundle\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Service\LoginUserService;

class LoginUserServiceTest extends KernelTestCase
{
	/** @var Container $c */
    private $c;
    
    /**
     * Setup the container
     *
     * @param integer $is the ID of the PrintModel
     * @param LoginUser $loginUser owner of the PrintModel
     * @return false|true false when PrintModel cannot be deleted, otherwise true
     */
    protected function setUp()
    {
        self::bootKernel();
        $this->c = static::$kernel->getContainer();
    }
    
    /**
     * Test username length
     *
     * @return void
     */
    public function test_UsernameLength()
    {
        $loginUserService = $this->c->get('app.login_user');
        
        // too short
        $result = $loginUserService->addLoginUser("1234", "test");
        $this->assertEquals(false, $result);
        
        // too long
        $result = $loginUserService->addLoginUser("12345678012345678901234567890123456", "test");
        $this->assertEquals(false, $result);
    }
    
    /**
     * Test if a username cannot be inserted twice
     *
     * @return void
     */
    public function test_UsernameDuplicate()
    {
        $loginUserService = $this->c->get('app.login_user');
        
        // first create username 'test' if not exists yet
        $loginUserService->addLoginUser("test", "test");
        
        $result = $loginUserService->addLoginUser("test", "test");
        $this->assertEquals(false, $result);
    }
}