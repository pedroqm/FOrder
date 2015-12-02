<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @UniqueEntity("nombreUsuario")
 */
class Usuario
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
     * @Assert\NotNull(message="El campo DNI no puede estar vacío")
     * @Assert\Length(
     *      max = 9,
     *      maxMessage = "El DNI no puede tener más de {{ limit }} caracteres"
     * )
     * @Assert\Regex(
     *      pattern="/\d{8}[A-Z]{1}/",
     *      match=true,
     *      message="Introduzca un DNI con formato válido"
     * )
     */
    protected $dni;

    /**
     * @ORM\Column(type="string")
     * @var string
     * @Assert\NotNull(message="El campo contraseña no puede estar vacío")
     * @Assert\Length(
     *
     *      min = 4,
     *
     *      minMessage = "La contraseña puede tener como mínimo {{ limit }} caracteres"
     * )
     */
    protected $pass;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     * @Assert\Regex(
     *     pattern     = "/^([a-z ñáéíóú]{2,60})$/i",
     *     htmlPattern = "/^([a-z ñáéíóú]{2,60})$/i",
     *     message="Introduzca un nombre completo válido, sin números ni caracteres especiales"
     * )
     */
    protected $nombreCompleto;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     * @Assert\Email(
     *     message = "El email '{{ value }}' no es un email válido."
     * )
     *
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     *
     */
    protected $telefono;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     * @Assert\Regex(
     *     pattern     = "/^([a-z ñáéíóú]{2,60})$/i",
     *     htmlPattern = "/^([a-z ñáéíóú]{2,60})$/i",
     *     message="Introduzca apellidos válidos, sin números ni caracteres especiales"
     * )
     */
    protected $apellidos;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $nombreUsuario;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    protected $esAdmin;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    protected $esCliente;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    protected $esCamarero;
    /**
     * @ORM\OneToOne(targetEntity="Mesa"
     * , inversedBy="user")
     *
     * @var Mesa
     */
    protected $mesa;

}
