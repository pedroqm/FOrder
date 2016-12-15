<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @UniqueEntity("nombreUsuario")
 */
class Usuario implements UserInterface
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
     *
     * @var string
     */
    protected $nombre;

    /**
     * @ORM\Column(type="decimal")
     * @var decimal
     */
    protected $factura;


    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $nombreUsuario;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     *
     */
    protected $mesaOcupada; //opcion 0 si no ocupa mesa

    /**
     * @ORM\Column(type="boolean", options={"default"=true})
     *
     * @var boolean
     */
    protected $esAdmin;

    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     *
     * @var boolean
     */
    protected $esCliente;

    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     *
     * @var boolean
     */
    protected $esCamarero;
    /**
     * @ORM\OneToOne(targetEntity="Mesa",
     * mappedBy="user")
     *
     * @var Mesa
     */
    protected $mesa;

    /**
     * @ORM\OneToMany(targetEntity="FacturaPagada",
     * mappedBy="usuario")
     * @var FacturaPagada
     */
    protected $FacturaP;
    /**
     * @ORM\OneToMany(targetEntity="FacturaNoPagada",
     * mappedBy="usuario")
     * @var FacturaNoPagada
     */
    protected $FacturaNoP;


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

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Usuario
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }




    public function __construct()
    {
        if ($this->getEsAdmin()) {
            $this->esAdmin=true;
            $this->esCamarero = false;
            $this->esCliente = false;
        }else {
            $this->esCamarero = false;
            $this->esAdmin = false;
            $this->esCliente = true;
        }
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        // TODO: Implement getRoles() method.
        $roles = array();

        if ($this->getEsAdmin()) {
            $roles[] = "ROLE_ADMIN";
        }
        if ($this->getEsCamarero()) {
            $roles[] = "ROLE_CAMARERO";
        }
        if ($this->getEsCliente()) {
            $roles[] = "ROLE_USER";
        }
        return $roles;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->getPass();
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        $this->getNombreUsuario();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * Set mesaOcupada
     *
     * @param string $mesaOcupada
     * @return Usuario
     */
    public function setMesaOcupada($mesaOcupada)
    {
        $this->mesaOcupada = $mesaOcupada;

        return $this;
    }

    /**
     * Get mesaOcupada
     *
     * @return string 
     */
    public function getMesaOcupada()
    {
        return $this->mesaOcupada;
    }

    /**
     * Add FacturaP
     *
     * @param \AppBundle\Entity\FacturaPagada $facturaP
     * @return Usuario
     */
    public function addFacturaP(\AppBundle\Entity\FacturaPagada $facturaP)
    {
        $this->FacturaP[] = $facturaP;

        return $this;
    }

    /**
     * Remove FacturaP
     *
     * @param \AppBundle\Entity\FacturaPagada $facturaP
     */
    public function removeFacturaP(\AppBundle\Entity\FacturaPagada $facturaP)
    {
        $this->FacturaP->removeElement($facturaP);
    }

    /**
     * Get FacturaP
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFacturaP()
    {
        return $this->FacturaP;
    }

    /**
     * Add FacturaNoP
     *
     * @param \AppBundle\Entity\FacturaNoPagada $facturaNoP
     * @return Usuario
     */
    public function addFacturaNoP(\AppBundle\Entity\FacturaNoPagada $facturaNoP)
    {
        $this->FacturaNoP[] = $facturaNoP;

        return $this;
    }

    /**
     * Remove FacturaNoP
     *
     * @param \AppBundle\Entity\FacturaNoPagada $facturaNoP
     */
    public function removeFacturaNoP(\AppBundle\Entity\FacturaNoPagada $facturaNoP)
    {
        $this->FacturaNoP->removeElement($facturaNoP);
    }

    /**
     * Get FacturaNoP
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFacturaNoP()
    {
        return $this->FacturaNoP;
    }

    public function isEqualTo(UserInterface $user){
        return $this->id===$user->getId();

    }


    /**
     * Set factura
     *
     * @param string $factura
     * @return Usuario
     */
    public function setFactura($factura)
    {
        $this->factura = $factura;

        return $this;
    }

    /**
     * Get factura
     *
     * @return string 
     */
    public function getFactura()
    {
        return $this->factura;
    }
}
