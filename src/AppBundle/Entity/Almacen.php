<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Almacen
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    protected $stock;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    protected $stockMin;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $nombreIngrediente;


    /**
     * @ORM\OneToMany(targetEntity="Ingredientes",
     * mappedBy="almacenado")
     *
     * @var Ingredientes
     */
    protected $ingrediente;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ingrediente = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set stock
     *
     * @param integer $stock
     * @return Almacen
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return integer 
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set stockMin
     *
     * @param integer $stockMin
     * @return Almacen
     */
    public function setStockMin($stockMin)
    {
        $this->stockMin = $stockMin;

        return $this;
    }

    /**
     * Get stockMin
     *
     * @return integer 
     */
    public function getStockMin()
    {
        return $this->stockMin;
    }

    /**
     * Set nombreIngrediente
     *
     * @param string $nombreIngrediente
     * @return Almacen
     */
    public function setNombreIngrediente($nombreIngrediente)
    {
        $this->nombreIngrediente = $nombreIngrediente;

        return $this;
    }

    /**
     * Get nombreIngrediente
     *
     * @return string 
     */
    public function getNombreIngrediente()
    {
        return $this->nombreIngrediente;
    }

    /**
     * Add ingrediente
     *
     * @param \AppBundle\Entity\Ingredientes $ingrediente
     * @return Almacen
     */
    public function addIngrediente(\AppBundle\Entity\Ingredientes $ingrediente)
    {
        $this->ingrediente[] = $ingrediente;

        return $this;
    }

    /**
     * Remove ingrediente
     *
     * @param \AppBundle\Entity\Ingredientes $ingrediente
     */
    public function removeIngrediente(\AppBundle\Entity\Ingredientes $ingrediente)
    {
        $this->ingrediente->removeElement($ingrediente);
    }

    /**
     * Get ingrediente
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIngrediente()
    {
        return $this->ingrediente;
    }
}
