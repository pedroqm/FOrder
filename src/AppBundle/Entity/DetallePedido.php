<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class DetallePedido
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
    protected $idPedido;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $NombreProducto;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    protected $cantidad;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Pedido",
     * mappedBy="Detallepedido")
     *
     * @var Pedido
     */
    protected  $Dpedido;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->Dpedido = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set idPedido
     *
     * @param integer $idPedido
     * @return DetallePedido
     */
    public function setIdPedido($idPedido)
    {
        $this->idPedido = $idPedido;

        return $this;
    }

    /**
     * Get idPedido
     *
     * @return integer 
     */
    public function getIdPedido()
    {
        return $this->idPedido;
    }

    /**
     * Set NombreProducto
     *
     * @param string $nombreProducto
     * @return DetallePedido
     */
    public function setNombreProducto($nombreProducto)
    {
        $this->NombreProducto = $nombreProducto;

        return $this;
    }

    /**
     * Get NombreProducto
     *
     * @return string 
     */
    public function getNombreProducto()
    {
        return $this->NombreProducto;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     * @return DetallePedido
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Add Dpedido
     *
     * @param \AppBundle\Entity\Pedido $dpedido
     * @return DetallePedido
     */
    public function addDpedido(\AppBundle\Entity\Pedido $dpedido)
    {
        $this->Dpedido[] = $dpedido;

        return $this;
    }

    /**
     * Remove Dpedido
     *
     * @param \AppBundle\Entity\Pedido $dpedido
     */
    public function removeDpedido(\AppBundle\Entity\Pedido $dpedido)
    {
        $this->Dpedido->removeElement($dpedido);
    }

    /**
     * Get Dpedido
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDpedido()
    {
        return $this->Dpedido;
    }
}
