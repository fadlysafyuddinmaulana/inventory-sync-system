<?php

namespace App\Database;

use Illuminate\Database\Connectors\SqlServerConnector as BaseSqlServerConnector;
use PDO;

class SqlServerConnector extends BaseSqlServerConnector
{
    /**
     * The PDO connection options.
     *
     * pdo_sqlsrv rejects PDO::ATTR_STRINGIFY_FETCHES in this environment.
     *
     * @var array
     */
    protected $options = [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
    ];

    /**
     * Filter out PDO attributes that are known to break pdo_sqlsrv here.
     */
    public function getOptions(array $config)
    {
        $options = parent::getOptions($config);

        unset($options[PDO::ATTR_STRINGIFY_FETCHES]);
        unset($options[PDO::ATTR_EMULATE_PREPARES]);

        return $options;
    }
}
