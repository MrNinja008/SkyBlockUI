<?php

namespace TheRealKizu\SkyBlockUI\commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;

use TheRealKizu\SkyBlockUI\Core;

class SkyBlockUICommand extends PluginCommand {

    /** @var Core */
    private $main;

    public function __construct(Core $main) {
        parent::__construct("skyblockui", $main);
        $this->main = $main;
        $this->setDescription("SkyBlockUI!");
        $this->setAliases(["sbui"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if(!$this->testPermission($sender)) {
            return true;
        }
        $this->main->functions->sbUI($sender);
        return true;
    }
}