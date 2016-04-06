<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Uploadable\Uploadable;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
/**
 * Image
 *
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\ImageRepository")
 * @ORM\Table(name="image")
 *
 * @Gedmo\Uploadable(
 *     pathMethod="getWebPath",
 *     filenameGenerator="ALPHANUMERIC",
 *     appendNumber=true,
 * )
 * @Gedmo\SoftDeleteable(
 *     fieldName="deletedAt",
 *     timeAware=false
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
     * Hook SoftDeleteable behavior
     * updates deletedAt field
     */
    use SoftDeleteableEntity;

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
     *
     * @Assert\Image(
     *     minWidth = 300,
     *     maxWidth = 1560,
     *     minHeight = 400,
     *     maxHeight = 2080
     * )
     */
    protected $path;

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
     * Tags
     *
     * @var ArrayCollection
     *
     * @ORM\Column(name="tags", type="string", nullable=true)
     */
    public $tags;

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
     * @return string
     */
    public function getFileBasename()
    {
        return pathinfo($this->path, PATHINFO_BASENAME);
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return self
     */
    public function setPath($path)
    {
        if (null !== $path) {
            $this->path = $path;
        }

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Is the image have a "rare" tag
     *
     * @return boolean
     */
    public function isRare()
    {
        $tags = explode(';', $this->tags);

        $i = 0;

        while($i < count($tags) && 'rare' !== $tags[$i]) {
            $i++;
        }

        return ($i !== count($tags));
    }
}
