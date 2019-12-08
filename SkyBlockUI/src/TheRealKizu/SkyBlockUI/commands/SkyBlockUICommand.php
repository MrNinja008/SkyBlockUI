<?php

namespace TheRealKizu\SkyBlockUI\commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
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

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$this->testPermission($sender)) {
            return true;
        }

        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "This command is available in-game only!");
        }

        $this->main->functions->sbUI($sender);
        return true;
    }
}