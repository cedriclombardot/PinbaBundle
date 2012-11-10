<?php

namespace Cedriclombardot\PinbaBundle\Propel;

class PinbaPropelPDO extends \DebugPDO
{
    const DRIVER_OCI = 'oci';
    const STATEMENT_CLASS = 'Cedriclombardot\\PinbaBundle\\Propel\\PinbaPropelPDOStatement';

    protected $dbname;

    /**
    * @see DebugPDO::__construct()
    */
    public function __construct($dsn, $username = null, $password = null, $driver_options = array())
    {
        parent::__construct($dsn, $username, $password, $driver_options);

        $this->configureStatementClass(self::STATEMENT_CLASS);

        if ($this->getAttribute(\PDO::ATTR_DRIVER_NAME) == self::DRIVER_OCI) {
            $this->dbname=$username;
        } else {
            $this->setDbNameFromDsn($dsn);
        }
    }

    /**
    * Extract the dbname from dsn
    * @param string $dsn
    */
    protected function setDbNameFromDsn($dsn)
    {
        preg_match('#dbname=([a-zA-Z0-9\_]+)#', $dsn, $matches);
        $this->dbname=$matches[1];
    }

    /**
    * Return the dbname
    */
    public function getDbName()
    {
        return $this->dbname;
    }
}
