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
use therealkizu\skyblockui\commands\SkyBlockUICommand;
use therealkizu\skyblockui\functions\Functions;

class Loader extends PluginBase {

    /**
     * Legend:
     * 0 = In Development
     * 1 = Ready for production
     *
     * @var int $inDev
     */
    public $inDev = 1;

    /** @var Functions $functions */
    public $functions;

    public function onLoad() {
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->saveResource("config.yml");
    }

    public function onEnable() {
        $this->functions = new Functions($this);
        $this->registerCommands();

        //Just a config check.
        if ($this->inDev = 1) {
            if ($this->getConfig()->get("is-redskyblock") === "true") {
                $this->getLogger()->notice("RedSkyBlock function is enabled! Disabling support for SkyBlock by GiantQuartz.");
            } else if ($this->getConfig()->get("is-redskyblock") === "false") {
                $this->getLogger()->notice("SkyBlock function is enabled! Disabling support for RedSkyBlock by RedCraftGH.");
            }
        }
    }

    public function registerCommands() {
        $this->getServer()->getCommandMap()->registerAll("SkyBlockUI", [
            new SkyBlockUICommand($this),
        ]);
    }

}
