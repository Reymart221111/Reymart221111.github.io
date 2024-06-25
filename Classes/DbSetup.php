<?php

namespace Classes;

use mysqli;
use Exception;
use Dotenv\Dotenv;

class DbSetup
{
    private static $instance = null;
    private static $envLoaded = false;
    private static $servername;
    private static $dbuser;
    private static $dbpass;
    private static $dbname;

    protected $conn;
    protected $tables; // Make protected to allow child classes to access it

    // Private constructor to prevent instantiation
    private function __construct()
    {
        $this->initialize();
    }

    // Static method to get the single instance of the class
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new DbSetup();
        }

        return self::$instance;
    }

    protected function initialize()
    {
        if (!self::$envLoaded) {
            $projectRoot = dirname(__DIR__, 1);

            $envPath = $projectRoot . '/.env';
            if (!file_exists($envPath)) {
                throw new Exception(".env file not found at path: $envPath");
            }

            $dotenv = Dotenv::createImmutable($projectRoot);
            $dotenv->load();

            self::$servername = $_ENV['DB_SERVERNAME'] ?? '';
            self::$dbuser = $_ENV['DB_USER'] ?? '';
            self::$dbpass = $_ENV['DB_PASS'] ?? '';
            self::$dbname = $_ENV['DB_NAME'] ?? '';

            // Validate database connection parameters
            $this->validateEnvironmentVariables();

            self::$envLoaded = true;
        }

        $this->createTables();
    }

    private function validateEnvironmentVariables()
    {
        if (empty(self::$servername) || empty(self::$dbuser) || empty(self::$dbname)) {
            throw new Exception("Database environment variables are not set correctly.");
        }
    }

    protected function connect()
    {
        if (!$this->conn) {
            $this->conn = new mysqli(self::$servername, self::$dbuser, self::$dbpass);

            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
        }

        return $this->conn;
    }

    protected function createTables()
    {
        try {
            $this->connect();
            $this->initializeTables();

            $createDatabaseSql = "CREATE DATABASE IF NOT EXISTS " . $this->conn->real_escape_string(self::$dbname);
            if ($this->conn->query($createDatabaseSql) !== TRUE) {
                throw new Exception("Error creating database: " . $this->conn->error);
            }

            $this->conn->select_db(self::$dbname);

            foreach ($this->tables as $table => $query) {
                if ($this->conn->query($query) !== TRUE) {
                    throw new Exception("Error creating table $table: " . $this->conn->error);
                }
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw $e;
        } finally {
            $this->conn->close();
        }
    }

    protected function initializeTables()
    {
        $this->tables = array(
            "Users" => "CREATE TABLE IF NOT EXISTS Users(
                           UserID INT AUTO_INCREMENT PRIMARY KEY,
                           UserName VARCHAR(200) NOT NULL,
                           Email VARCHAR(255) UNIQUE NOT NULL,
                           Password VARCHAR(255) NOT NULL,
                           Role ENUM('admin', 'chef') NOT NULL,
                           Profile_path VARCHAR(255),
                           RegisteredDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                        ) ENGINE=InnoDB;",

            "CuisineNationality" => "CREATE TABLE IF NOT EXISTS CuisineNationality(
                                        CuisineID INT AUTO_INCREMENT PRIMARY KEY,
                                        CuisineOrigin VARCHAR(100) UNIQUE NOT NULL
                                     ) ENGINE=InnoDB;",

            "MealType" => "CREATE TABLE IF NOT EXISTS MealType(
                              MealTypeID INT AUTO_INCREMENT PRIMARY KEY,
                              MealType VARCHAR(100) UNIQUE NOT NULL
                           ) ENGINE=InnoDB;",

            "DietRestriction" => "CREATE TABLE IF NOT EXISTS DietRestriction(
                                    RestrictionID INT AUTO_INCREMENT PRIMARY KEY,
                                    Restriction VARCHAR(100) UNIQUE NOT NULL
                                 ) ENGINE=InnoDB;",

            "RecipeDetails" => "CREATE TABLE IF NOT EXISTS RecipeDetails(
                                   RecipeID INT AUTO_INCREMENT PRIMARY KEY,
                                   RecipeName VARCHAR(200) NOT NULL,
                                   Ingredients TEXT NOT NULL,
                                   Procedures MEDIUMTEXT NOT NULL,
                                   CookingTime INT NOT NULL,
                                   ServingSize INT NOT NULL,
                                   ImagePath VARCHAR(500),
                                   AddedBy INT NOT NULL,
                                   CuisineID INT NULL,
                                   MealTypeID INT NULL,
                                   RestrictionID INT NULL,
                                   AddedDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                   FOREIGN KEY (CuisineID) REFERENCES CuisineNationality(CuisineID) ON DELETE SET NULL ON UPDATE CASCADE,
                                   FOREIGN KEY (MealTypeID) REFERENCES MealType(MealTypeID) ON DELETE SET NULL ON UPDATE CASCADE,
                                   FOREIGN KEY (RestrictionID) REFERENCES DietRestriction(RestrictionID) ON DELETE SET NULL ON UPDATE CASCADE,
                                   FOREIGN KEY (AddedBy) REFERENCES Users(UserID) ON DELETE SET NULL ON UPDATE CASCADE
                                ) ENGINE=InnoDB;"
        );
    }

    public function getConnection()
    {
        $this->conn = new mysqli(self::$servername, self::$dbuser, self::$dbpass, self::$dbname);

        if ($this->conn->connect_error) {
            throw new Exception("Connection failed: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
