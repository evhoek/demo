<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="print_models")
 * @ORM\Entity
 */
class PrintModel
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="LoginUser", inversedBy="print_models")
     * @ORM\JoinColumn(name="login_users_id", referencedColumnName="id")
     */
    private $loginUser;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $filename;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $filesize;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return PrintModel
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set loginUser
     *
     * @param \AppBundle\Entity\LoginUser $loginUser
     * @return PrintModel
     */
    public function setLoginUser(\AppBundle\Entity\LoginUser $loginUser = null)
    {
        $this->loginUser = $loginUser;

        return $this;
    }

    /**
     * Get loginUser
     *
     * @return \AppBundle\Entity\LoginUser 
     */
    public function getLoginUser()
    {
        return $this->loginUser;
    }

    /**
     * Set filename
     *
     * @param string $filename
     * @return PrintModel
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set filesize
     *
     * @param integer $filesize
     * @return PrintModel
     */
    public function setFilesize($filesize)
    {
        $this->filesize = $filesize;

        return $this;
    }

    /**
     * Get filesize
     *
     * @return integer 
     */
    public function getFilesize()
    {
        return $this->filesize;
    }
}
