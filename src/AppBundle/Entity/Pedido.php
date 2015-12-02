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
}