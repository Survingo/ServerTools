<?php

/*
 ____                          _____           _     
/ ___|  ___ _ ____   _____ _ _|_   _|__   ___ | |___ 
\___ \ / _ \ '__\ \ / / _ \ '__|| |/ _ \ / _ \| / __|
 ___) |  __/ |   \ V /  __/ |   | | (_) | (_) | \__ \
|____/ \___|_|    \_/ \___|_|   |_|\___/ \___/|_|___/

Copyright 2016 Survingo

This Plugin was made for PocketMine-MP

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
*/

namespace Survingo\ServerTools;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class ServerTools extends PluginBase implements Listener{

    public function onEnable(){

                  $this->getLogger()->info(TF::GREEN . "ServerTools v" . $this->getDescription()->getVersion() . " by Survingo enabled!");
                  

    }

    public function onDisable(){

                  $this->getLogger()->info(TF::RED . "ServerTools v" . $this->getDescription()->getVersion() . " disabled!");

    }

    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
        switch (strtolower($command->getName())) {
            case "servertools":
                    if(!(isset($args[0]))) {
                        if($sender->hasPermission("servertools.command")) {
                            $sender->sendMessage("[§6Server§eTools§f] Use §2/tools help");
                            return true;
                        } else {
                            $sender->sendMessage("You don't have the permission to do that!");
                            return true;
                        }
                    }
                    $arg = array_shift($args);
                    switch ($arg) {
                        
               case "help":
               case "?":
                            if($sender->hasPermission("servertools.command.help")){
                            $sender->sendMessage(" ");
                            $sender->sendMessage("[§6Server§eTools§f] --- Showing help page 1 of 1 (/servertools help§7/§f? <page>) ---");
                            $sender->sendMessage("§2/tools help§f/§2?: §fShows the help");
                            $sender->sendMessage("§2/tools enable <PluginName>: §fEnables a disabled Plugin");
                            $sender->sendMessage("§2/tools disable <PluginName>: §fDisables a enabled Plugin");
                            $sender->sendMessage("§2/tools restart <PluginName>: §fRestarts a enabled Plugin");
                            $sender->sendMessage(" ");
		
                                return true;
                            } else {
                                $sender->sendMessage("§cYou don't have the permission to do that!");
                            }
                            return true;
                            break;
                case "disable":
                if($sender->hasPermission("servertools.command.disable")){
                		if(count($args) === 0){
			$sender->sendMessage("[§6Server§eTools§f] Usage: /tools disable <PluginName>");
			
			return true;
			
		}

		$pluginName = trim(implode(" ", $args));
		if($pluginName === "" or $pluginName === "ServerTools" or !(($plugin = Server::getInstance()->getPluginManager()->getPlugin($pluginName)) instanceof Plugin)){
		
			$sender->sendMessage("[§6Server§eTools§f] §cThe Plugin §7" . $pluginName . " §cdoesn't exists or can't be disabled! Remember that names are §6CaSe SeNsItIvE§c! (ServerTools can't be disabled)");
			return true;
			
		}else{
		  if($this->getServer()->getPluginManager()->isPluginEnabled($this->getServer()->getPluginManager()->getPlugin($pluginName))){
			$this->getServer()->getPluginManager()->disablePlugin($this->getServer()->getPluginManager()->getPlugin($pluginName));
			$sender->sendMessage("[§6Server§eTools§f] §aThe Plugin §2" . $plugin->getDescription()->getName() . " §adisabled!");
			return true;
			
		}else{
		  $sender->sendMessage("[§6Server§eTools§f] §cThe Plugin §7" . $plugin->getDescription()->getName() . " §cisn't enabled and can't be disabled!");
		  return true;
        }
      }
    }
    break;
    
                case "enable":
                if($sender->hasPermission("servertools.command.enable")){
		if(count($args) === 0){
			$sender->sendMessage("[§6Server§eTools§f] Usage: /tools enable <PluginName>");
			
			return true;
			
		}

		$pluginName = trim(implode(" ", $args));
		if($pluginName === "" or !(($plugin = Server::getInstance()->getPluginManager()->getPlugin($pluginName)) instanceof Plugin)){
		
			$sender->sendMessage("[§6Server§eTools§f] §cThe Plugin §7" . $pluginName . " §cdoesn't exists! Remember that names are §6CaSe SeNsItIvE§c!");
			return true;
			
		}

		if($plugin instanceof Plugin){
		  if(!$this->getServer()->getPluginManager()->isPluginEnabled($this->getServer()->getPluginManager()->getPlugin($pluginName))){
			$this->getServer()->getPluginManager()->enablePlugin($this->getServer()->getPluginManager()->getPlugin($pluginName));
			$sender->sendMessage("[§6Server§eTools§f] §aThe Plugin §2" . $plugin->getDescription()->getName() . " §aenabled!");
			return true;
			
		}else{
		  $sender->sendMessage("[§6Server§eTools§f] §cThe Plugin §7" . $plugin->getDescription()->getName() . " §cis already enabled!");
		  return true;
            }
          }
        }
        break;

                case "restart":
                if($sender->hasPermission("servertools.command.restart")){
		if(count($args) === 0){
			$sender->sendMessage("[§6Server§eTools§f] Usage: /tools restart <PluginName>");
			
			return true;
			
		}

		$pluginName = trim(implode(" ", $args));
		if($pluginName === "" or !(($plugin = Server::getInstance()->getPluginManager()->getPlugin($pluginName)) instanceof Plugin)){
		
			$sender->sendMessage("[§6Server§eTools§f] §cThe Plugin §7" . $pluginName . " §cdoesn't exists! Remember that names are §6CaSe SeNsItIvE§c!");
			return true;
			
		}

		if($plugin instanceof Plugin){
		  if($this->getServer()->getPluginManager()->isPluginEnabled($this->getServer()->getPluginManager()->getPlugin($pluginName))){
			$this->getServer()->getPluginManager()->disablePlugin($this->getServer()->getPluginManager()->getPlugin($pluginName));
			$this->getServer()->getPluginManager()->enablePlugin($this->getServer()->getPluginManager()->getPlugin($pluginName));
			$sender->sendMessage("[§6Server§eTools§f] §aThe Plugin §2" . $plugin->getDescription()->getName() . " §asuccessfully restarted!");
			return true;
			
		}else{
		  $sender->sendMessage("[§6Server§eTools§f] §cThe Plugin §7" . $plugin->getDescription()->getName() . " §cisn't enabled and can't be restarted!");
		  return true;
            }
          }
        }
        break;
                
      }
    }
  }
  
  
}