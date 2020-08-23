<?php

/**
*                  SkyBlockUI
 * Copyright (C) 2019-2020 TheRealKizu
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

namespace therealkizu\skyblockui;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use therealkizu\skyblockui\commands\SkyBlockUICommand;
use therealkizu\skyblockui\forms\SkyBlock;
use therealkizu\skyblockui\utils\Utils;

class Loader extends PluginBase {

    /** @var Config $cfg */
    protected $cfg;

    /** @var SkyBlock $forms */
    protected $forms;

    /** @var Utils $utils */
    protected $utils;

    function onLoad(): void {
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->saveResource("config.yml");
    }

    function onEnable(): void {
        $this->initCommands();

        $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $sbPlug = $this->cfg->get("skyblock-plugin");
        switch ($sbPlug) {
            case "redcraftgh":
                $this->getLogger()->error("RedSkyBlock support is currently on development! Disabling plugin...");
                $this->getServer()->getPluginManager()->disablePlugin($this);
                break;
            case "giantquartz":
            default:
                $this->forms = new SkyBlock($this);
                break;
        }

        $this->utils = new Utils($this);
        $this->utils->isSpoon();
    }

    function initCommands(): void {
        $this->getServer()->getCommandMap()->registerAll("SkyBlockUI", [
            new SkyBlockUICommand($this),
        ]);
    }

    function getForms() {
        return $this->forms;
    }

}
