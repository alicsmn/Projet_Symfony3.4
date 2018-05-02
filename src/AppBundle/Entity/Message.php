<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
/**
* @ORM\Entity
* @ORM\Table(name="Message")
*/
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idSender;
    /**
     * @ORM\Column(type="integer")
     */
    private $idReceiver;

    /**
     * @var datetime $created
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="string")
     */
    private $msgContent;

    /**
     * @return mixed
     */
    public function getIdSender()
    {
        return $this->idSender;
    }
    /**
     * @param mixed $idSender
     */
    public function setIdSender($idSender)
    {
        $this->idSender = $idSender;
    }
    /**
     * @return mixed
     */
    public function getIdReceiver()
    {
        return $this->idReceiver;
    }

    /**
     * @param mixed $idReceiver
     */
    public function setIdReceiver($idReceiver)
    {
        $this->idReceiver = $idReceiver;
    }
    /**
     * @return datetime
     */
    public function getCreated()
    {
        return $this->created;
    }
    /**
     * @param datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }
    /**
     * @return mixed
     */
    public function getIdMsgContent()
    {
        return $this->msgContent;
    }
    /**
     * @param mixed $msgContent
     */
    public function setIdMsgContent($msgContent)
    {
        $this->msgContent = $msgContent;
    }


}