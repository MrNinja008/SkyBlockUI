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

namespace therealkizu\skyblockui\utils;

use therealkizu\skyblockui\forms\SkyBlock;
use therealkizu\skyblockui\Loader;

class Utils {

    /** @var Loader $plugin */
    protected $plugin;

    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * Checks whether the server is using PocketMine-MP or not.
     *
     * @return bool
     */
    public function isSpoon(): bool {
        if ($this->plugin->getServer()->getName() !== "PocketMine-MP") {
            $this->plugin->getLogger()->error("It seems you are not using PocketMine-MP. Disabling plugin...");
            $this->plugin->getServer()->getPluginManager()->disablePlugin($this->plugin);
            return true;
        }

        return false;
    }

    /**
     * Checks what SkyBlock plugin is the server using.
     *
     * @return void
     */
    public function checkSkyBlockPlugin(): void {
        $sbPlug = $this->plugin->getPluginConfig()->get("skyblock-plugin");
        if ($sbPlug === "giantquartz") {
            $this->plugin->forms = new SkyBlock($this->plugin);
        } else if ($sbPlug === "redcraftgh") {
            $this->plugin->getLogger()->error("RedSkyBlock support is currently on development! Disabling plugin...");
            $this->plugin->getServer()->getPluginManager()->disablePlugin($this->plugin);
        }
    }

}