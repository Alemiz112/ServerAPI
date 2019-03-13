<?php

namespace alemiz\ServerStatus;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;

use alemiz\ServerStatus\provider\MySQL;


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Main extends PluginBase{

    public $cfg;

    public function onEnable(){
		$this->getLogger()->info("Plugin has been enabled!");
		@mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        $status = "Online";
        $set_status = new provider\MySQL($this);
        $set_status->connect($status);

    }

	public function onDisable(){
        $status = "Offline";
        $set_status = new provider\MySQL($this);
        $set_status->connect($status);

    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		switch($command->getName()){
            case "servers":
                if($this->cfg->get("Status") === "Online"){
                    $sender->sendMessage(TextFormat::GREEN. "Server is ONLINE!");
                }else {
                    $sender->sendMessage(TextFormat::DARK_RED. "Server is OFFLINE!");

                }
                return true;


            default:
				return false;
		}
	}
}