<?php

/**
 * Copyright 2019 TheRealKizu
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
    private $cfg;

    public function onLoad() {
        $this->cfg = new Config($this->getDataFolder() . "./config.yml", Config::YAML);
    }

    public function onEnable() {
        $this->functions = new Functions($this);
        $this->registerCommands();

        #DO NOT EDIT!
        if($this->getDescription()->getAuthors()[0] !== "TheRealKizu" || $this->getDescription()->getName() !== "SkyBlockUI"){
            $this->getLogger()->notice("Fatal error! Illegal modification/use of SkyBlockUI by TheRealKizu (@TheRealKizu)!");
            $this->getServer()->shutdown();
        }
    }

    public function registerCommands() : void {
        if ($this->cfg->get("is-redskyblock") === "false") {
            $this->getServer()->getCommandMap()->registerAll("SkyBlockUI", [
                new SkyBlockUICommand($this),
            ]);
        }
    }
}
