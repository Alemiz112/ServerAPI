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
use pocketmine\scheduler\Task as PluginTask;


use alemiz\ServerStatus\provider\MySQL;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Main extends PluginBase{

    public $cfg;

    public function onEnable(){
		$this->getLogger()->info(TextFormat::GREEN."ServerAPI by Alemiz ENABLED!");
		@mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        if($this->cfg->get("MySql") == "true"){
            $status = "Online";
            $set_status = new provider\MySQL($this);
            $set_status->connect($status);
        }

        if($this->cfg->get("FakeSlots") == "true"){
            $fake_int = $this->cfg->get("FakeInterval");
            $this->getScheduler()->scheduleRepeatingTask(new SetOnlinePlayers($this), $fake_int * 20);
        }
    }

	public function onDisable(){
        $this->getLogger()->info(TextFormat::RED. "ServerAPI by Alemiz DISSABLED!");

        if($this->cfg->get("MySql") == "true"){
            $status = "Offline";
            $set_status = new provider\MySQL($this);
            $set_status->connect($status);
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		switch($command->getName()){
            case "servers":
                $sender->getSynapse();
                $sender =synapse\Player::getSynapse();
                return true;

            default:
				return false;
		}
	}
}



class SetOnlinePlayers extends PluginTask{
    private $plugin;

    public function __construct($plugin) {
        $this->plugin = $plugin;
    }

    public function onRun($tick){
         $query = $this->plugin->getServer()->getQueryInformation();
         $max = $query->getMaxPlayerCount();
         $fake_max = $this->plugin->cfg->get("FakeMax");

         if($max <= $fake_max){
             $fake_max= $max - 2;
             $this->plugin->getLogger()->info(TextFormat::RED. "Setting FakeMax to ". $fake_max." because of MaxPlayers".TextFormat::YELLOW." Please change it, RESTART server!");
         }

         $online = rand(1,$fake_max);
         $query->setPlayerCount($online);
    }
}