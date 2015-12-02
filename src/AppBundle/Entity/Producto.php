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
}