<?php
namespace alemiz\ServerStatus\provider;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\utils\Config;

use alemiz\ServerStatus\Main;

class MySQL {
    private $plugin;

    public function __construct($plugin)
    {
        $this->plugin = $plugin;
    }

    public function connect($status)
    {
        $host = $this->plugin->cfg->get("host");
        $dbname = $this->plugin->cfg->get("db");
        $username = $this->plugin->cfg->get("user");
        $password = $this->plugin->cfg->get("password");

        $id = $this->plugin->cfg->get("ID");

        $conn = new \mysqli($host, $username, $password, $dbname);
        if ($conn->connect_error) {
            $this->getLogger()->critical("Cant connect to MYSQL");
        }
        echo "ID: ".$id;
        $data = "UPDATE server_status SET Status='$status' WHERE id='$id'";

            if ($conn->query($data) === TRUE) {
                echo "Record updated successfully";
            } else {
                echo "Error updating record: " . $conn->error;
            }

    }
}