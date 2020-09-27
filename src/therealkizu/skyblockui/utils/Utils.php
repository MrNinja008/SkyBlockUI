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

use ReflectionException;

use therealkizu\skyblockui\Loader;
use therealkizu\skyblockui\libs\JackMD\ConfigUpdater\ConfigUpdater;

class Utils {

    /** @var Loader $plugin */
    protected $plugin;

    protected const CONFIG_VERSION = 1.0;

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
            $this->plugin->getLogger()->error("It seems you are not using PocketMine-MP! Disabling plugin...");
            $this->plugin->getServer()->getPluginManager()->disablePlugin($this->plugin);
            return true;
        }

        return false;
    }

    /**
     * Checks if the plugins config was created before the rewrite
     *
     * @return void
     */
    public function checkConfig(): void {
        try {
            ConfigUpdater::checkUpdate($this->plugin, $this->plugin->getConfig(), "config-version", (int)self::CONFIG_VERSION);
        } catch (ReflectionException $e) {
            $this->plugin->getLogger()->error("Error encountered while checking config: " . $e);
        }
    }

    /**
     * DO NOT EDIT!
     *
     * @return void
     */
    public function checkAuthor(): void {
        if ($this->plugin->getDescription()->getAuthors() !== ["TheRealKizu"]) {
            $this->plugin->getLogger()->error("You are not using the official version of this plugin (SkyBlockUI) by TheRealKizu! Download the official version here: https://github.com/TheRealKizu/SkyBlockUI/releases");
            $this->plugin->getServer()->getPluginManager()->disablePlugin($this->plugin);
        }
    }

}