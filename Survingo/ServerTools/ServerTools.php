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
use pocketmine\utils\Utils;
use pocketmine\Player;

class ServerTools extends PluginBase implements Listener{

    public function onEnable(){
    
                  $this->getLogger()->info(TF::GREEN . "Enabling ServerTools v" . $this->getDescription()->getVersion() . " by Survingo...");
                  $this->saveDefaultConfig();
                  $this->getLogger()->info(TF::GREEN . "Checking configuration...");
                  $this->checkConfiguration(); // Who knows what people may write there
                  
                  if($this->getConfig()->getNested("autoupdater.autoupdater") === true){
                    $this->checkInternetConnection();
                    }else{
                      $this->getLogger()->warning("AutoUpdater disabled!");
                      }   

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
                              if(count($args) === 0 or trim(implode(" ", $args)) === "1"){
                                $sender->sendMessage("[§6Server§eTools§f] --- Showing help page 1 of 2 (/servertools help <page>) ---");
                                $sender->sendMessage("§2/tools help: §fShows the help");
                                $sender->sendMessage("§2/tools ?: §7Alias for /tools help");
                                $sender->sendMessage("§2/tools enable <PluginName>: §fEnables a disabled Plugin");
                                $sender->sendMessage("§2/tools disable <PluginName>: §fDisables a enabled Plugin");
                                $sender->sendMessage("§2/tools restart <PluginName>: §fRestarts a enabled Plugin");
                                   
                                }elseif(trim(implode(" ", $args)) === "2"){  
                                $sender->sendMessage("§2/tools update: §fChecks for an Update");
                                $sender->sendMessage("§2/tools check: §7Alias for /tools update");
                                $sender->sendMessage("§e/tools config: §fChecks the config.yml §l§6BETA");
                                }
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

                case "update":
                case "check":
                if($sender->hasPermission("servertools.command.update")){
		if(count($args) === 0){
		  if(!$sender instanceof Player){
			$sender->sendMessage("[§6Server§eTools§f] Checking for an Update...");
			$this->checkInternetConnection();
			return true;
			   }else{
			     $sender->sendMessage("§cYou can't check for an Update In-Game!");
			     return true;
			     }
          }
        }
        break;
                
      }
    }
  }
  
    public function checkConfiguration(){
    
                   $config = $this->getConfig();
                       
                   if($config->get("version") !== $this->getDescription()->getVersion()){
                     $this->getLogger()->error("The current configuration was made for §6" . $config->get("version") . "§4, but you have installed version §6" . $this->getDescription()->getVersion() . "§4. Please delete the current '§6config.yml§4' and restart your server.");
                     $this->getServer()->getPluginManager()->disablePlugin($this->getServer()->getPluginManager()->getPlugin($this->getDescription()->getName()));
                   }elseif(!(($config->getNested("autoupdater.channel") == "stable") or ($config->getNested("autoupdater.channel") == "beta"))){
                     $this->getLogger()->error("Your preffered channel in the configuration '§c" . $config->getNested("autoupdater.channel") . "§4' is not available! Please choose between '§6stable§4' or '§6beta§4' and restart your Server.");
                     $this->getServer()->getPluginManager()->disablePlugin($this->getServer()->getPluginManager()->getPlugin($this->getDescription()->getName()));
                   }else{
                     $this->getLogger()->info(TF::GREEN . "Configuration is valid!");
                     }

  }  
  
    public function checkInternetConnection(){
    
                   $this->getLogger()->info(TF::GREEN . "Checking Internet Connection...");
                   //if(Utils::getURL("https://raw.githubusercontent.com/Survingo/AutoUpdater/master/InternetTest.txt") == "true"){
                     $this->getLogger()->info(TF::GREEN . "You are connected to the Internet! Now checking for an Update...");
                     $this->checkForUpdate();
                     //}else{
                      //$this->getLogger()->error("Can't check for an Update, because you are not connected to the Internet!");
                      //}

  }
  
    public function checkForUpdate(){
                  
                      $versionURL = "https://raw.githubusercontent.com/Survingo/AutoUpdater/master/ServerTools/version.txt";
                      $VersionTXT = Utils::getURL($versionURL);
                      $parsedVersion = yaml_parse($VersionTXT);
                    
                      $channelURL = "https://raw.githubusercontent.com/Survingo/AutoUpdater/master/ServerTools/channel.txt";
                      $channelTXT = Utils::getURL($channelURL);
                      $parsedChannel = yaml_parse($channelTXT);
                    
                      if($parsedVersion !== $this->getDescription()->getVersion() and $parsedChannel == $this->getConfig()->getNested("autoupdater.channel")){
                        $this->getLogger()->info("\n------------------------\n§l§6A new " . $this->getConfig()->getNested("autoupdater.channel") . " version of ServerTools is available!§r\n§eYour version:§6\n" . $this->getDescription()->getVersion() . "\n§eNew version:§6\n" . Utils::getURL("https://raw.githubusercontent.com/Survingo/AutoUpdater/master/ServerTools/version.txt") . "\n§eUpdate Info:§6 " . Utils::getURL("https://raw.githubusercontent.com/Survingo/AutoUpdater/master/ServerTools/info.txt") . "\n§eUpdate now at §a" . Utils::getURL("https://raw.githubusercontent.com/Survingo/AutoUpdater/master/ServerTools/download.txt") . "\n\n§f------------------------");
                        }elseif($parsedVersion !== $this->getDescription()->getVersion() and $parsedChannel !== $this->getConfig()->getNested("autoupdater.channel")){
                          $this->getLogger()->info("\n------------------------\n§l§6A new " . $this->getConfig()->getNested("autoupdater.channel") . " version of ServerTools is available!§r\n§eYour version:§6\n" . $this->getDescription()->getVersion() . "\n§eNew version:§6\n" . Utils::getURL("https://raw.githubusercontent.com/Survingo/AutoUpdater/master/ServerTools/version.txt") . "\n§eUpdate Info:§6 " . Utils::getURL("https://raw.githubusercontent.com/Survingo/AutoUpdater/master/ServerTools/info.txt") . "\n§eUpdate now at §a" . Utils::getURL("https://raw.githubusercontent.com/Survingo/AutoUpdater/master/ServerTools/download.txt") . "\n\n§f------------------------");
                          }else{
                            $this->getLogger()->info("§aYour version is up to date!");
                           }
  }
  
}