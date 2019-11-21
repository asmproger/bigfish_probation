<?php
/**
 * @author asmproger <asmproger@gmail.com>
 * @copyright (c) 2019, asmproger
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Category
 * @author asmproger <asmproger@gmail.com>
 * @copyright (c) 2019, asmproger
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Material", mappedBy="category", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $materials;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->materials = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return Category
     */
    public function setTitle($title): Category
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getMaterials(): Collection
    {
        return $this->materials;
    }

    /**
     * @param ArrayCollection $materials
     * @return Category
     */
    public function setMaterials(ArrayCollection $materials): Category
    {
        $this->materials = $materials;

        return $this;
    }

    /**
     * @param Material $material
     * @return Category
     */
    public function addMaterial(Material $material): Category
    {
        if (!$this->materials->contains($material)) {
            $this->materials->add($material);
            $material->setCategory($this);
        }

        return $this;
    }

    /**
     * @param Material $material
     * @return Category
     */
    public function removeMaterial(Material $material): Category
    {
        if ($this->materials->contains($material)) {
            $this->materials->removeElement($material);
            $material->setCategory(null);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}