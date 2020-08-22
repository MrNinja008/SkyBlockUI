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

use pocketmine\Player;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\TextFormat;

use room17\SkyBlock\SkyBlock;
use therealkizu\skyblockui\Loader;

class SkyBlockUICommand extends PluginCommand {

    /** @var Loader $plugin */
    private $plugin;

    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin) {
        parent::__construct("skyblockui", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("Manage your skyblock island in UI!");
        $this->setAliases(["sbui", "islandui", "isui"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender->hasPermission("sbui.command")) {
            if ($sender instanceof Player) {
                $session = SkyBlock::getInstance()->getSessionManager()->getSession($sender);

                $this->plugin->getForms()->sbUI($sender, $session);
            } else {
                $sender->sendMessage(TextFormat::RED . "This command is available in-game only!");
                return false;
            }
        } else {
            $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command!");
        }
        return false;
    }

}