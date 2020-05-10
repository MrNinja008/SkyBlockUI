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

namespace therealkizu\skyblockui\commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use therealkizu\skyblockui\Loader;

class SkyBlockUICommand extends PluginCommand {

    /** @var Loader */
    private $plugin;

    public function __construct(Loader $plugin) {
        parent::__construct("skyblockui", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("Command for SkyBlockUI");
        $this->setAliases(["sbui", "islandui", "isui"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$this->testPermission($sender)) {
            return true;
        }

        if ($sender instanceof Player) {
            $cfg = new Config($this->plugin->getDataFolder() . "config.yml", Config::YAML);
            if ($cfg->get("is-redskyblock") === "false") {
                $this->plugin->functions->sbUI($sender);
            } else if ($cfg->get("is-redskyblock") === "true"){
                $this->plugin->functions->rsbUI($sender);
            }
        } else {
            $sender->sendMessage(TextFormat::RED . "This command is available in-game only!");
        }
        return true;
    }
}