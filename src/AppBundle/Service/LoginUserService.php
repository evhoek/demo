<?php
namespace AppBundle\Service;

use AppBundle\Entity\LoginUser;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

/**
 * This class is the service for LoginUser entity
 */
class LoginUserService
{
    /** @var string ENTITY_NAME should contains LoginUser entity name */
    const ENTITY_NAME = 'AppBundle:LoginUser';
    
    /** @var EntityManager $em should contains the Doctrine\ORM\EntityManager */
    private $em;
    
    /** @var UserPasswordEncoder $pe should contains the Symfony\Component\Security\Core\Encoder\UserPasswordEncoder */
    private $pe;


    public function __construct(EntityManager $em, UserPasswordEncoder $pe)
    {
        $this->em = $em;
        $this->pe = $pe;
    }
    
    /**
     * Insert new LoginUser to DB
     *
     * @param string $username 
     * @param string $plainPassword 
     * @return false|integer  false when LoginUser cannot be inserted, otherwise the new ID
     */
    public function AddLoginUser($username, $plainPassword)
    {
        // check username exists
        if ($this->em->getRepository(self::ENTITY_NAME)->findOneBy(array('username' => $username)))
            return false;
        
        // check username length
        if (strlen($username) < 5 || strlen($username) > $this->em->getClassMetadata(self::ENTITY_NAME)->fieldMappings['username']['length'])
            return false;
        
        // check password length
        if (strlen($plainPassword) < 5)
            return false;
            
        // create new Entity
        $loginUser = new LoginUser();
        $loginUser->setUsername($username);
        
        // bcrypt password
        $password = $this->pe->encodePassword($loginUser, $plainPassword);
        $loginUser->setPassword($password);

        // save
        $this->em->persist($loginUser);
        $this->em->flush();
        
        return $loginUser->getId();
    }
}
