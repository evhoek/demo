<?php
namespace Tests\AppBundle\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Service\PrintModelService;
use AppBundle\Entity\LoginUser;

class PrintModelServiceTest extends KernelTestCase
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
     * Test owner in EditPrintModel
     *
     * @return void
     */
    public function test_EditPrintModel()
    {
    	// create test user
    	$user = new LoginUser();
    	
        $printModelService = $this->c->get('app.print_model');
        
        $result = $printModelService->EditPrintModel(1, $user, null);
        $this->assertEquals(false, $result);
    }
}