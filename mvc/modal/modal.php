<?php
// Modal.php (base class)
abstract class Modal
{
    // protected string $id = '';
    protected static $table = '';
    protected static ?PDO $pdo = null;

    /**
     * Return a shared PDO instance (singleton).
     */
    protected static function database(): PDO
    {
        if (static::$pdo === null) {
            $servername   = "localhost";
            $username     = "root";
            $password     = "";
            $databasename = "aziz_cours";

            $dsn = "mysql:host=$servername;dbname=$databasename";


            static::$pdo = new PDO($dsn, $username, $password);
        }

        return static::$pdo;
    }


    abstract static function all();
    abstract static function create(array $data);
    abstract static function detail($id);
    abstract static function update($id);
    abstract static function delete($id, ?string $path);
}
