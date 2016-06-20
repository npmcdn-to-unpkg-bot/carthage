<?php

namespace AymardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AymardBundle\Entity\Image;
use AymardBundle\Entity\Translation;
/**
 * Page
 *
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="AymardBundle\Repository\PageRepository")
 */
class Page
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;
    
    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Image", mappedBy="page")
     */
    private $photos;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Meta", mappedBy="page", cascade={"persist"})
     */
    private $metas;    

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="Translation", mappedBy="pages", cascade={"persist"})
     */
    private $translations;        
    
    private $translation; 

    public function __construct($locale){
        $this->translation = new Translation($locale);
        
        $this->photos = new ArrayCollection();
        $this->translations = new  ArrayCollection();
    }
    
    public function newTranslation($locale){
        $this->translation = new Translation($locale);
        return $this;
    }
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Page
     */
    public function setTitle($title)
    {
        $this->translation->setTitle($title);

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string slug
     *
     * @return Page
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Page
     */
    public function setDescription($description)
    {
        $this->translation->setDescription($description);

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
     * Set photos
     *
     * @param array $photos
     *
     * @return Page
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;
       
        return $this;
    }

    /**
     * Get photos
     *
     * @return array
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Add photo
     *
     * @param \AymardBundle\Entity\Image $photo
     *
     * @return Page
     */
    public function addPhoto(Image $photo)
    {
        $this->photos[] = $photo;
       
        return $this;
    }

    /**
     * Remove photo
     *
     * @param \AymardBundle\Entity\Image $photo
     */
    public function removePhoto(Image $photo)
    {
        $this->photos->removeElement($photo);
    }

    public function __toString(){
        return $this->slug . ' : ' . $this->title;
    }

    /**
     * Add metum
     *
     * @param \AymardBundle\Entity\Meta $metum
     *
     * @return Page
     */
    public function addMetum(\AymardBundle\Entity\Meta $metum)
    {
        $this->metas[] = $metum;

        return $this;
    }

    /**
     * Remove metum
     *
     * @param \AymardBundle\Entity\Meta $metum
     */
    public function removeMetum(\AymardBundle\Entity\Meta $metum)
    {
        $this->metas->removeElement($metum);
    }

    /**
     * Get metas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMetas()
    {
        return $this->metas;
    }
    
    
    
    public function addMeta(Meta $meta)
    {
        $meta->addPage($this);

        $this->metas->add($meta);
    }
    
    public function removeMeta(Meta $meta)
    {
        $this->metas->removeElement($meta);
    }

    /**
     * Add translation
     *
     * @param \AymardBundle\Entity\Translation $translation
     *
     * @return Page
     */
    public function addTranslation(Translation $translation = null)
    {
        if(is_null($translation)){
            $this->translations[] = $this->translation;
            $this->translation->addPage($this);
            return $this;
        }
        
        $this->translations[] = $translation;

        return $this;
    }

    /**
     * Remove translation
     *
     * @param \AymardBundle\Entity\Translation $translation
     */
    public function removeTranslation(\AymardBundle\Entity\Translation $translation)
    {
        $this->translations->removeElement($translation);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTranslations()
    {
        return $this->translations;
    }
}
