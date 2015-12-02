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
}