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
     * @ORM\Column(type="decimal")
     * @var decimal
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
}