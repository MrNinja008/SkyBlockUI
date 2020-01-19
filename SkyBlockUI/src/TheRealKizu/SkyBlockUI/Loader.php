<?php

/**
*                  SkyBlockUI
 * Copyright (C) 2020 TheRealKizu
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @author TheRealKizu
 */

declare(strict_types=1);

namespace TheRealKizu\SkyBlockUI;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;
use TheRealKizu\SkyBlockUI\commands\SkyBlockUICommand;
use TheRealKizu\SkyBlockUI\functions\Functions;

class Loader extends PluginBase {

    /**
     * @var Functions
     */
    public $functions;

    /**
     * @var Config
     */
    public $config;

    public function onLoad() {
        $this->getLogger()->notice("SkyBlockUI is initializing...");
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->saveResource("config.yml");
    }

    public function onEnable() {
        $this->functions = new Functions($this);
        $this->config = $this->getConfig()->getAll();
        $this->checkDepends();
        $this->registerCommands();
        $this->getLogger()->notice("SkyBlockUI has been initialized! Made with love by TheRealKizu.");

        //DO NOT EDIT!
        if ($this->getDescription()->getAuthors()[0] !== ["TheRealKizu"] || $this->getDescription()->getName() !== "SkyBlockUI"){
            $this->getLogger()->error("Fatal error! Illegal modification/use of SkyBlockUI by TheRealKizu (TheRealKizu#3267 or @TheRealKizu)!");
            $this->getServer()->shutdown();
        }

        //Just a logger.
        if ($this->config->get("is-redskyblock") === "true") {
            $this->getLogger()->notice("RedSkyBlock function is enabled! Disabling support for SkyBlock by GiantQuartz.");
        }

        if ($this->config->get("is-redskyblock") === "false") {
            $this->getLogger()->notice("SkyBlock function is enabled! Disabling support for RedSkyBlock by RedCraftGH.");
        }
    }

    public function onDisable() {
        $this->getLogger()->notice("SkyBlockUI disabled!");
    }

    public function registerCommands() {
        $this->getServer()->getCommandMap()->registerAll("SkyBlockUI", [
            new SkyBlockUICommand($this),
        ]);
    }

    public function checkDepends() {
        $sb = $this->getServer()->getPluginManager()->getPlugin("SkyBlock");
        $redsb = $this->getServer()->getPluginManager()->getPlugin("RedSkyBlock");
        if (is_null($sb)) {
            $this->getLogger()->error("Fatal error! SkyBlock plugin not found!");
            $this->getServer()->shutdown();
        }

        if ($this->config->get("is-redskyblock") === "true") {
            if (is_null($redsb)) {
                $this->getLogger()->error("Fatal error! RedSkyBlock plugin not found!");
                $this->getServer()->shutdown();
            }
        }
    }
}
