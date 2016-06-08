<?php
namespace AppBundle\Service;

use AppBundle\Entity\PrintModel;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface; 

/**
 * This class is the service for PrintModel entity
 */
class PrintModelService
{
    /** @var string ENTITY_NAME should contains PrintModel entity name */
    const ENTITY_NAME = 'AppBundle:PrintModel';
    
    /** @var EntityManager $em should contains the Doctrine\ORM\EntityManager */
    private $em;
    
    /** @var string $uploadFolder should contains the absolute upload folder path */
    private $uploadFolder;


    public function __construct(EntityManager $em, ContainerInterface $c)
    {
        $this->em = $em;
        $this->uploadFolder = $c->get('kernel')->getRootDir() . $c->getParameter('upload_folder') . '/';
    }

    /**
     * Get PrintModel by ID
     *
     * @param integer $id the ID of an PrintModel record
     * @return null|PrintModel  null when PrintModel record cannot be found, otherwise the correct PrintModel
     */
    public function GetPrintModel($id)
    {
        return $this->em->getRepository(self::ENTITY_NAME)->findOneBy(array('id' => $id));
    }
    
    /**
     * Get all PrintModels
     *
     * @return PrintModels
     */
    public function GetPrintModels()
    {
        return $this->em->getRepository(self::ENTITY_NAME)->findAll();
    }
    
    /**
     * Get the absolute filename of PrintModel
     *
     * @return string
     */
    public function GetAbsoluteFileName(PrintModel $printModel)
    {
        return $this->uploadFolder . $printModel->getId();
    }
    
    /**
     * Insert new PrintModel to DB
     *
     * @param LoginUser $loginUser the owner of the PrintModel
     * @param UploadedFile $file
     * @param string $description null is allowed
     * @return false|integer false when PrintModel cannot be inserted, otherwise the new ID
     */
    public function AddPrintModel(\AppBundle\Entity\LoginUser $loginUser, $file, $description)
    {
        // check $file
        if (!($file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile))
            return false;
        
        $filename = $file->getClientOriginalName();
        $filesize = $file->getClientSize();
        
        // check description
        if (strlen($description) > $this->em->getClassMetadata(self::ENTITY_NAME)->fieldMappings['description']['length'])
            return false;
        
        // truncate filename if too long
        $filename = substr($filename, 0, $this->em->getClassMetadata(self::ENTITY_NAME)->fieldMappings['filename']['length']);
        
                
        // create new PrintModel
        $this->em->getConnection()->beginTransaction();
        try
        {
            $printModel = new PrintModel();
            $printModel->setLoginUser($loginUser);
            $printModel->setFilename($filename);
            $printModel->setFilesize($filesize);
            $printModel->setDescription(($description ? $description: ''));
        
            // insert entity
            $this->em->persist($printModel);
            $this->em->flush();
        
            // move uploaded file
            if (!$file->move($this->uploadFolder, $printModel->getId()))
                throw new Exception('Cannot move uploaded file');
            
            // commit
            $this->em->getConnection()->commit();
        }
        catch (Exception $e)
        {
            $this->em->getConnection()->rollBack();
            throw $e;
        }
        
        return $printModel->getId();
    }
    
    /**
     * Edit existing PrintModel
     *
     * @param integer $is the ID of the PrintModel
     * @param LoginUser $loginUser owner of the PrintModel
     * @param string $description null is allowed
     * @return false|true false when PrintModel cannot be updated, otherwise true
     */
    public function EditPrintModel($id, \AppBundle\Entity\LoginUser $loginUser, $description)
    {
        $printModel = $this->em->getRepository(self::ENTITY_NAME)->findOneBy(array('id' => $id));
        
        // PrintModel exists?
        if (!$printModel)
            return false;
        
        // PrintModel belongs to logged-in user?
        if ($printModel->getLoginUser()->getId() != $loginUser->getId())
            return false;
        
        // check description
        if (strlen($description) > $this->em->getClassMetadata(self::ENTITY_NAME)->fieldMappings['description']['length'])
            return false;
        
        // edit PrintModel
        $printModel->setDescription(($description ? $description: ''));
        $this->em->flush();
        
        return true;
    }
    
    /**
     * Deleted existing PrintModel
     *
     * @param integer $is the ID of the PrintModel
     * @param LoginUser $loginUser owner of the PrintModel
     * @return false|true false when PrintModel cannot be deleted, otherwise true
     */
    public function DeletePrintModel($id, \AppBundle\Entity\LoginUser $loginUser)
    {
        $printModel = $this->em->getRepository(self::ENTITY_NAME)->findOneBy(array('id' => $id));
        
        // PrintModel exists?
        if (!$printModel)
            return false;
        
        // PrintModel belongs to logged-in user?
        if ($printModel->getLoginUser()->getId() != $loginUser->getId())
            return false;
        
        // delete PrintModel
        $this->em->getConnection()->beginTransaction();
        try
        {
            // remove entity
            $this->em->remove($printModel);
            $this->em->flush();
            
            // remove file (if exists)
            @unlink($this->uploadFolder . $id);
            
            // commit
            $this->em->getConnection()->commit();
        }
        catch (Exception $e)
        {
            $this->em->getConnection()->rollBack();
            throw $e;
        }
        
        return true;
    }
    
}
