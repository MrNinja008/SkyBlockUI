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

namespace TheRealKizu\SkyBlockUI\commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

use TheRealKizu\SkyBlockUI\Loader;

class SkyBlockUICommand extends PluginCommand {

    /** @var Loader */
    private $main;

    public function __construct(Loader $main) {
        parent::__construct("skyblockui", $main);
        $this->main = $main;
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

        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "This command is available in-game only!");
        }

        $cfg = new Config($this->main->getDataFolder() . "config.yml", Config::YAML);
        if ($cfg->get("is-redskyblock") === "false") {
            $this->main->functions->sbUI($sender);
        } else if ($cfg->get("is-redskyblock") === "true"){
            $this->main->functions->rsbUI($sender);
            //$sender->sendMessage(TextFormat::RED . "RedSkyBlock feature coming soon!");
        }
        return true;
    }
}