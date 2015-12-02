<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Mesa
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
     * @ORM\Column(type="decimal")
     * @var decimal
     */
    protected $cuenta;

    /**
     * @ORM\Column(type="decimal")
     * @var decimal
     */
    protected $factura;

    /**
     * @ORM\OneToMany(targetEntity="Pedido"
     * , mappedBy="mesaOcupada")
     *
     * @var Pedido
     */
    protected $pedidos;
    /**
     * @ORM\OneToOne(targetEntity="Usuario",
     *  mappedBy="mesa")
     *
     * @var Usuario
     */
    protected $user;
}