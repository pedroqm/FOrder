<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class FacturaPagada
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
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    protected $hora;


    /**
     * @ORM\ManyToOne(targetEntity="Usuario",
     *  inversedBy="FacturaP")
     *
     * @var Usuario
     */
    protected $usuario;


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
     * Set idDetallePedido
     *
     * @param integer $idDetallePedido
     * @return FacturaPagada
     */
    public function setIdDetallePedido($idDetallePedido)
    {
        $this->idDetallePedido = $idDetallePedido;

        return $this;
    }

    /**
     * Get idDetallePedido
     *
     * @return integer 
     */
    public function getIdPedido()
    {
        return $this->idDetallePedido;
    }

    /**
     * Set hora
     *
     * @param \DateTime $hora
     * @return FacturaPagada
     */
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Get hora
     *
     * @return \DateTime 
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set usuario
     *
     * @param \AppBundle\Entity\Usuario $usuario
     * @return FacturaPagada
     */
    public function setUsuario(\AppBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \AppBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set idPedido
     *
     * @param integer $idPedido
     * @return FacturaPagada
     */
    public function setIdPedido($idPedido)
    {
        $this->idPedido = $idPedido;

        return $this;
    }
}
