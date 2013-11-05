<?php

class Lib_Mein_Session_Auth
{
    protected static $instance = null;
    
    private $session = null;
    
    private $db = null;
    
    private $tableName = 'auth';
    
    private $identityField = 'email';
    
    private $passwordField = 'password';
    
    protected function __construct()
    {
        $this->session = new Lib_Mein_Session('auth');
        
        if(!isset($this->session->identity)) {
            $this->session->identity = false;
        }
    }
    
    /**
     *
     * @return Lib_Mein_Session_Auth
     */
    public static function getInstance()
    {
        if(self::$instance === null)
        {
            $className = __CLASS__;
            self::$instance = new $className();
        }
        
        return self::$instance;
    }
    
    /**
     *
     * @param Mein_Db_Adapter_Abstract $adapter
     * @return Lib_Mein_Session_Auth 
     */
    public function setDbAdapter(Lib_Mein_Db_Pdo $adapter)
    {
        $this->db = $adapter;
        return $this;
    }
    
    /**
     *
     * @return Mein_Db_Adapter
     */
    public function getDbAdapter()
    {
        return $this->db;
    }
    
    /**
     *
     * @param type $name
     * @return Lib_Mein_Session_Auth 
     */
    public function setTableName($name)
    {
        $this->tableName = $name;
        return $this;
    }
    
    public function getTableName()
    {
        return $this->tableName;
    }
    
    /**
     *
     * @param type $field
     * @return Lib_Mein_Session_Auth 
     */
    public function setIdentityField($field)
    {
        $this->identityField = $field;
        return $this;
    }
    
    public function getIdentityField()
    {
        return $this->identityField;
    }
    
    public function editIdentity($newIdentity)
    {
        $this->checkParams();
        
        // verifico se username esiste gia
        if(!$this->db->fetchOne("SELECT {$this->identityField} FROM {$this->tableName} WHERE {$this->identityField}=?", $newIdentity))
        {
            $this->db->update($this->tableName, array($this->identityField => $newIdentity), "{$this->identityField}=?", $this->session->identity);
            $this->refresh();
            return true;
        }
        return false;
    }
    
    /**
     *
     * @param type $field
     * @return Lib_Mein_Session_Auth 
     */
    public function setPasswordField($field)
    {
        $this->passwordField = $field;
        return $this;
    }
    
    public function getPasswordField()
    {
        return $this->passwordField;
    }

    public function editPassword($password)
    {
        $this->checkParams();
        
        $this->db->update($this->tableName, array($this->passwordField => sha1($password)), "{$this->identityField}=?", $this->session->identity);
        $this->refresh();
    }
    
    public function authenticate($username, $password = null)
    {
        if($this->hasIdentity()) {
            return true;
        }
        
        $this->checkParams();
        
        $username = addslashes($username);

        $res = ($password) ?
            $this->db->fetchRow("SELECT * FROM {$this->tableName} WHERE {$this->identityField}=? AND {$this->passwordField}=? LIMIT 1", $username, sha1($password)) :
            $this->db->fetchRow("SELECT * FROM {$this->tableName} WHERE {$this->identityField}=? LIMIT 1", $username);
        
        if(count($res) > 0)
        {
            $this->session->identity = $res[$this->identityField];
            $this->session->row = $res;
            return true;
        }
        
        return false;
    }
    
    public function hasIdentity()
    {
        return $this->session->identity;
    }
    
    public function __get($key)
    {
        if(!$this->hasIdentity()) {
            return false;
        }
        
        if(array_key_exists($key, $this->session->row)) {
            return $this->session->row[$key];
        }
        
        throw new Exception(__CLASS__ . '::' . __METHOD__ . " Key $key not found");
    }
    
    private function refresh()
    {
        $this->checkParams();
        
        $res = $this->db->fetchRow("SELECT * FROM {$this->tableName} WHERE {$this->identityField}=?", $this->session->identity);
        
        $this->session->identity = $res[$this->identityField];
        $this->session->row = $res;
    }
    
    private function checkParams()
    {
        if(!$this->db) {
            throw new Exception(__CLASS__ . " null db adapter");
        }
        
        if(!$this->tableName) {
            throw new Exception(__CLASS__ . " table name not setted");
        }
        
        if(!$this->identityField) {
            throw new Exception(__CLASS__ . " identity field not setted");
        }
        
        if(!$this->passwordField) {
            throw new Exception(__CLASS__ . " password field not setted");
        }
        
        return true;
    }
    
    /**
     *
     * @return Mein_Session_Auth 
     */
    public function destroy()
    {
        if($this->hasIdentity())
        {
            unset($this->session->identity);
            unset($this->session->row);
            $this->session->unsetAll();
        }
        
        return $this;
    }
}