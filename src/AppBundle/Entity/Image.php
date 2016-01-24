<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Uploadable\Uploadable;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Image
 *
 * @ORM\Entity()
 * @ORM\Table(name="image")
 *
 * @Gedmo\Uploadable(
 *     pathMethod="getWebPath",
 *     filenameGenerator="ALPHANUMERIC", 
 *     appendNumber=true
 * )
 */
class Image
{
    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

    /**
     * $id
     *
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * Title
     *
     * @var string
     *
     * @ORM\Column(name="title", type="string", nullable=true)
     */
    public $title;

    /**
     * Description
     *
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    public $description;

    /**
     * Image path
     *
     * @var string
     *
     * @ORM\Column(name="path", type="string")
     *
     * @Gedmo\UploadableFilePath
     */
    public $path;

    /**
     * Image name
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     *
     * @Gedmo\UploadableFileName
     */
    public $name;

    /**
     * Image mime type
     *
     * @var string
     *
     * @ORM\Column(name="mime_type", type="string")
     *
     * @Gedmo\UploadableFileMimeType
     */
    public $mimeType;

    /**
     * Image size
     *
     * @var float
     *
     * @ORM\Column(name="size", type="decimal")
     *
     * @Gedmo\UploadableFileSize
     */
    public $size;

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
     * Get web path
     *
     * @return string
     */
    public function getWebPath()
    {
        return 'uploads/image/';
    }

    /**
     * Get file basename
     *
     * @return 
     */
    public function getFileBasename()
    {
        return pathinfo($this->path, PATHINFO_BASENAME);
    }
}
