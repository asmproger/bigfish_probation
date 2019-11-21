<?php
/**
 * @author asmproger <asmproger@gmail.com>
 * @copyright (c) 2019, asmproger
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class Material
 * @author asmproger <asmproger@gmail.com>
 * @copyright (c) 2019, asmproger
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\MaterialRepository")
 */
class Material
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $materialContent;

    /**
     * @var UploadedFile
     */
    private $imageFile;

    /**
     * @var Image
     * @ORM\OneToOne(targetEntity="Image", mappedBy="material", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $image;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="materials")
     */
    private $category;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Material
     */
    public function setTitle(string $title): Material
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Image|null
     */
    public function getImage(): ?Image
    {
        return $this->image;
    }

    /**
     * @param Image|null $image
     * @return Material
     */
    public function setImage(?Image $image): Material
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return Material
     */
    public function setCategory(?Category $category): Material
    {
        $this->category = $category;

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @return UploadedFile
     */
    public function getImageFile(): ?UploadedFile
    {
        return $this->imageFile;
    }

    /**
     * @param UploadedFile $imageFile
     * @return Material
     */
    public function setImageFile(UploadedFile $imageFile): Material
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->materialContent;
    }

    /**
     * @param string $content
     * @return Material
     */
    public function setContent(string $content): Material
    {
        $this->materialContent = $content;

        return $this;
    }
}