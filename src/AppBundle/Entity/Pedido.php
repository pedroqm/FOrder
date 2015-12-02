<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Pedido
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
     * @ORM\Column(type="string")
     * @var string
     */
    protected $estado;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $incidencias  ;

    /**
     * @ORM\ManyToOne(targetEntity="Mesa",
     * inversedBy="pedidos")
     *
     * @var Mesa
     */
    protected  $mesaOcupada;
    /**
     * @ORM\ManyToMany(targetEntity="Producto",
     * inversedBy="comanda")
     *
     * @var Producto
     */
    protected $productos;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set estado
     *
     * @param string $estado
     * @return Pedido
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set incidencias
     *
     * @param string $incidencias
     * @return Pedido
     */
    public function setIncidencias($incidencias)
    {
        $this->incidencias = $incidencias;

        return $this;
    }

    /**
     * Get incidencias
     *
     * @return string 
     */
    public function getIncidencias()
    {
        return $this->incidencias;
    }

    /**
     * Set mesaOcupada
     *
     * @param \AppBundle\Entity\Mesa $mesaOcupada
     * @return Pedido
     */
    public function setMesaOcupada(\AppBundle\Entity\Mesa $mesaOcupada = null)
    {
        $this->mesaOcupada = $mesaOcupada;

        return $this;
    }

    /**
     * Get mesaOcupada
     *
     * @return \AppBundle\Entity\Mesa 
     */
    public function getMesaOcupada()
    {
        return $this->mesaOcupada;
    }

    /**
     * Add productos
     *
     * @param \AppBundle\Entity\Producto $productos
     * @return Pedido
     */
    public function addProducto(\AppBundle\Entity\Producto $productos)
    {
        $this->productos[] = $productos;

        return $this;
    }

    /**
     * Remove productos
     *
     * @param \AppBundle\Entity\Producto $productos
     */
    public function removeProducto(\AppBundle\Entity\Producto $productos)
    {
        $this->productos->removeElement($productos);
    }

    /**
     * Get productos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductos()
    {
        return $this->productos;
    }
}
