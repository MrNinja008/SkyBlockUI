<?php

/**
 *                  SkyBlockUI
 * Copyright (C) 2019-2021 TheRealKizu
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

use therealkizu\skyblockui\Loader;
use therealkizu\skyblockui\forms\SkyBlock;

class SkyBlockUICommand extends PluginCommand {

    /** @var Loader $plugin */
    protected $plugin;

    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin) {
        parent::__construct("skyblockui", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("Manage your skyblock island in UI!");
        $this->setAliases(["sbui", "islandui", "isui"]);
        $this->setPermission("sbui.command");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "This command is available in-game only!");
            return false;
        }

        if ($sender->hasPermission("sbui.command")) {
            if ($this->plugin->getForms() instanceof SkyBlock) {
                $session = \room17\SkyBlock\SkyBlock::getInstance()->getSessionManager()->getSession($sender);
                if (!$args[0]) {

                    $this->plugin->getForms()->mainUI($sender, $session);
                    return true;
                } else {
                    switch ($args[0]) {
                        case "create":
                            $this->plugin->getForms()->islandCreation($sender, $session);
                            break;
                        case "manage":
                            $this->plugin->getForms()->islandManagement($sender, $session);
                            break;
                    }
                }
            }
        } else {
            $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command!");
            return false;
        }
        return false;
    }

}