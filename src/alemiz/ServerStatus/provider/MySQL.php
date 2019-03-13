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
        $host = '192.168.2.84';
        $dbname = 'zadmin_mcpe';
        $username = 'mcpe';
        $password = 'y4u8ehe5e';

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