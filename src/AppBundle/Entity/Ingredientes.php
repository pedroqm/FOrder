<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Ingredientes
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
     * @ORM\Column(type="float")
     * @var float
     */
    protected $cantidad;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $nombreProducto;
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $nombreIngrediente;

    /**
     * @ORM\ManyToOne(targetEntity="Almacen",
     * inversedBy="ingrediente")
     *
     * @var Almacen
     */
    protected  $almacenado;
    /**
     * @ORM\ManyToMany(targetEntity="Producto",
     * inversedBy="ingredientes")
     *
     * @var Producto
     */
    protected $producto;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->producto = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set cantidad
     *
     * @param string $cantidad
     * @return Ingredientes
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return string 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set nombreProducto
     *
     * @param string $nombreProducto
     * @return Ingredientes
     */
    public function setNombreProducto($nombreProducto)
    {
        $this->nombreProducto = $nombreProducto;

        return $this;
    }

    /**
     * Get nombreProducto
     *
     * @return string 
     */
    public function getNombreProducto()
    {
        return $this->nombreProducto;
    }

    /**
     * Set nombreIngrediente
     *
     * @param string $nombreIngrediente
     * @return Ingredientes
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
     * Set almacenado
     *
     * @param \AppBundle\Entity\Almacen $almacenado
     * @return Ingredientes
     */
    public function setAlmacenado(\AppBundle\Entity\Almacen $almacenado = null)
    {
        $this->almacenado = $almacenado;

        return $this;
    }

    /**
     * Get almacenado
     *
     * @return \AppBundle\Entity\Almacen 
     */
    public function getAlmacenado()
    {
        return $this->almacenado;
    }

    /**
     * Add producto
     *
     * @param \AppBundle\Entity\Producto $producto
     * @return Ingredientes
     */
    public function addProducto(\AppBundle\Entity\Producto $producto)
    {
        $this->producto[] = $producto;

        return $this;
    }

    /**
     * Remove producto
     *
     * @param \AppBundle\Entity\Producto $producto
     */
    public function removeProducto(\AppBundle\Entity\Producto $producto)
    {
        $this->producto->removeElement($producto);
    }

    /**
     * Get producto
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducto()
    {
        return $this->producto;
    }
}
