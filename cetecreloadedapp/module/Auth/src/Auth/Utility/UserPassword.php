<?php

namespace Auth\Utility;

use Zend\Crypt\Password\Bcrypt;

class UserPassword
{

    public $salt = 'aUJGgadjasdgdj';

    public $method = 'bcrypt';

    /**
     * El metodo debe ser bcrypt, otros mÃ©todos estan bloqueados
     * @param string $method
     */
    public function __construct($method = null)
    {
        if (! is_null($method) && ($method=='bcrypt')) {
            $this->method = $method;
        }
    }

    public function create($password)
    {
//         if ($this->method == 'md5') {
//             return md5($this->salt . $password);
//         } elseif ($this->method == 'sha1') {
//             return sha1($this->salt . $password);
            
//         } else
            
        if ($this->method == 'bcrypt') {
            $bcrypt = new Bcrypt();
            return $bcrypt->create($password);
        }
    }

    public function verify($password, $hash)
    {
//         if ($this->method == 'md5') {
//             return $hash == md5($this->salt . $password);
//         } elseif ($this->method == 'sha1') {
//             return $hash == sha1($this->salt . $password);
//         } else
        if ($this->method == 'bcrypt') {
            $bcrypt = new Bcrypt();
            return $bcrypt->verify($password, $hash);
        }
    }
    
    /*
     * Ciframos la contrase�a para la maxima seguridad le aplicamos un salt y hacemos el hash del hash 5 veces
     * (por defecto vienen mas de 10 pero es mas lento)
     */
    public function securePassword($password)
    {
    	$bcrypt     = new Bcrypt(array(
    			'salt' => '$2y$05$KkFmCjGPJiC1jdt.SFcJ5uDXkF1yYCQFgiQIjjT6p.z7QIHyU1elW',
    			'cost' => 5));
    	$securePass = $bcrypt->create($password);
    	//print_r($securePass); exit;
    	return $securePass;
    }
}
