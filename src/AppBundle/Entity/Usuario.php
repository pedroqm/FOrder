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
     * Set dni
     *
     * @param string $dni
     * @return Usuario
     */
    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get dni
     *
     * @return string 
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set pass
     *
     * @param string $pass
     * @return Usuario
     */
    public function setPass($pass)
    {
        $this->pass = $pass;

        return $this;
    }

    /**
     * Get pass
     *
     * @return string 
     */
    public function getPass()
    {
        return $this->pass;
    }


    /**
     * Set email
     *
     * @param string $email
     * @return Usuario
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Usuario
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     * @return Usuario
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set nombreUsuario
     *
     * @param string $nombreUsuario
     * @return Usuario
     */
    public function setNombreUsuario($nombreUsuario)
    {
        $this->nombreUsuario = $nombreUsuario;

        return $this;
    }

    /**
     * Get nombreUsuario
     *
     * @return string 
     */
    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    /**
     * Set esAdmin
     *
     * @param boolean $esAdmin
     * @return Usuario
     */
    public function setEsAdmin($esAdmin)
    {
        $this->esAdmin = $esAdmin;

        return $this;
    }

    /**
     * Get esAdmin
     *
     * @return boolean 
     */
    public function getEsAdmin()
    {
        return $this->esAdmin;
    }

    /**
     * Set esCliente
     *
     * @param boolean $esCliente
     * @return Usuario
     */
    public function setEsCliente($esCliente)
    {
        $this->esCliente = $esCliente;

        return $this;
    }

    /**
     * Get esCliente
     *
     * @return boolean 
     */
    public function getEsCliente()
    {
        return $this->esCliente;
    }

    /**
     * Set esCamarero
     *
     * @param boolean $esCamarero
     * @return Usuario
     */
    public function setEsCamarero($esCamarero)
    {
        $this->esCamarero = $esCamarero;

        return $this;
    }

    /**
     * Get esCamarero
     *
     * @return boolean 
     */
    public function getEsCamarero()
    {
        return $this->esCamarero;
    }

    /**
     * Set mesa
     *
     * @param \AppBundle\Entity\Mesa $mesa
     * @return Usuario
     */
    public function setMesa(\AppBundle\Entity\Mesa $mesa = null)
    {
        $this->mesa = $mesa;

        return $this;
    }

    /**
     * Get mesa
     *
     * @return \AppBundle\Entity\Mesa 
     */
    public function getMesa()
    {
        return $this->mesa;
    }
}
