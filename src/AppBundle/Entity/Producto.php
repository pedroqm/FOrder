<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Producto{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="decimal")
     * @var decimal
     */
    protected $precio;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $nombreProducto;

    //Hay suficientes ingredientes?
    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    protected $existencias;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $tipo;


    /**
     * @ORM\ManyToMany(targetEntity="Pedido",
     * mappedBy="productos")
     *
     * @var Pedido
     */
    protected $comanda;
    /**
     * @ORM\ManyToMany(targetEntity="Ingredientes",
     * mappedBy="producto")
     *
     * @var Ingredientes
     */
    protected $ingredientes;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comanda = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ingredientes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set precio
     *
     * @param string $precio
     * @return Producto
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return string 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set nombreProducto
     *
     * @param string $nombreProducto
     * @return Producto
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
     * Set existencias
     *
     * @param boolean $existencias
     * @return Producto
     */
    public function setExistencias($existencias)
    {
        $this->existencias = $existencias;

        return $this;
    }

    /**
     * Get existencias
     *
     * @return boolean 
     */
    public function getExistencias()
    {
        return $this->existencias;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Producto
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Add comanda
     *
     * @param \AppBundle\Entity\Pedido $comanda
     * @return Producto
     */
    public function addComanda(\AppBundle\Entity\Pedido $comanda)
    {
        $this->comanda[] = $comanda;

        return $this;
    }

    /**
     * Remove comanda
     *
     * @param \AppBundle\Entity\Pedido $comanda
     */
    public function removeComanda(\AppBundle\Entity\Pedido $comanda)
    {
        $this->comanda->removeElement($comanda);
    }

    /**
     * Get comanda
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComanda()
    {
        return $this->comanda;
    }

    /**
     * Add ingredientes
     *
     * @param \AppBundle\Entity\Ingredientes $ingredientes
     * @return Producto
     */
    public function addIngrediente(\AppBundle\Entity\Ingredientes $ingredientes)
    {
        $this->ingredientes[] = $ingredientes;

        return $this;
    }

    /**
     * Remove ingredientes
     *
     * @param \AppBundle\Entity\Ingredientes $ingredientes
     */
    public function removeIngrediente(\AppBundle\Entity\Ingredientes $ingredientes)
    {
        $this->ingredientes->removeElement($ingredientes);
    }

    /**
     * Get ingredientes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIngredientes()
    {
        return $this->ingredientes;
    }
}
