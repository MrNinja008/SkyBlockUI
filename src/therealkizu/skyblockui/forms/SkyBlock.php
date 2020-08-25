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

namespace therealkizu\skyblockui\forms;

use pocketmine\Player;

use room17\SkyBlock\command\IslandCommandMap;
use room17\SkyBlock\island\IslandFactory;
use room17\SkyBlock\session\Session;
use room17\SkyBlock\session\SessionLocator;
use room17\SkyBlock\utils\Invitation;
use room17\SkyBlock\utils\message\MessageContainer;
use therealkizu\skyblockui\Loader;
use therealkizu\skyblockui\libs\jojoe77777\FormAPI\CustomForm;
use therealkizu\skyblockui\libs\jojoe77777\FormAPI\SimpleForm;

class SkyBlock {

    /** @var Loader $plugin */
    protected $plugin;

    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @param Player $player
     * @param Session $session
     */
    public function mainUI(Player $player, Session $session): void {
        $form = new SimpleForm(function (Player $player, $data) use ($session) {
            $result = $data;
            if ($result === null) return;

            switch ($result) {
                case 0:
                    if (!$session->hasIsland()) {
                        $this->islandCreation($player, $session);
                    } else {
                        $session->sendTranslatedMessage(new MessageContainer("NEED_TO_BE_FREE"));
                    }
                    break;
                case 1:
                    if ($session->hasIsland()) {
                        $this->islandManagement($player, $session);
                    } else {
                        $session->sendTranslatedMessage(new MessageContainer("NEED_ISLAND"));
                    }
                    break;
                case 2:
                    $this->inviteManagement($player, $session);
                    break;
                case 3:
                    $this->memberManagement($player, $session);
                    break;
                case 4:
                    $player->getServer()->dispatchCommand($player, "is help");
                    /*
                    $skyBlock = $this->plugin->getServer()->getPluginManager()->getPlugin("SkyBlock");
                    if (!$skyBlock instanceof \room17\SkyBlock\SkyBlock)
                        return;

                    $map = new IslandCommandMap($skyBlock);
                    $session->sendTranslatedMessage(new MessageContainer("HELP_HEADER", [
                        "amount" => count($map->getCommands())
                    ]));

                    foreach ($map->getCommands() as $command) {
                        $session->sendTranslatedMessage(new MessageContainer("HELP_COMMAND_TEMPLATE", [
                            "name" => $command->getName(),
                            "description" => $session->getMessage($command->getDescriptionMessageContainer()),
                            "usage" => $session->getMessage($command->getUsageMessageContainer())
                        ]));
                    }
                    */
                    break;
            }
        });
        $form->setTitle("§lSKYBLOCK UI");
        $form->setContent("§fSelect an option!");
        $form->addButton("§8Island Creation\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Island Management\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Invite Management\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Member Management\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Help\n§d§l»§r §8Tap to select!", 0, "textures/ui/how_to_play_button_default_light");
        $form->addButton("§cExit", 0, "textures/blocks/barrier");
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @param Session $session
     */
    public function islandCreation(Player $player, Session $session): void {
        $form = new SimpleForm(function (Player $player, $data) use ($session) {
            $result = $data;
            if ($result === null) return;

            switch ($result) {
                case 0:
                    IslandFactory::createIslandFor($session, "Basic");
                    $session->sendTranslatedMessage(new MessageContainer("SUCCESSFULLY_CREATED_A_ISLAND"));
                    break;
                case 1:
                    IslandFactory::createIslandFor($session, "Palm");
                    $session->sendTranslatedMessage(new MessageContainer("SUCCESSFULLY_CREATED_A_ISLAND"));
                    break;
                case 2:
                    IslandFactory::createIslandFor($session, "");
                    $session->sendTranslatedMessage(new MessageContainer("SUCCESSFULLY_CREATED_A_ISLAND"));
                    break;
                case 3:
                    $this->mainUI($player, $session);
                    break;
            }
        });
        $form->setTitle("§lISLAND CREATION");
        $form->setContent("§fSelect an island to create!");
        $form->addButton("§8Basic Island\n§d§l»§r §8Tap to create!", 0, "textures/blocks/grass_side_carried");
        $form->addButton("§8Palm Island\n§d§l»§r §8Tap to create!", 0, "textures/blocks/sand");
        $form->addButton("§8Default Island\n§d§l»§r §8Tap to create!", 0, "textures/blocks/sapling_oak");
        $form->addButton("§cBack", 0, "textures/blocks/barrier");
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @param Session $session
     */
    public function islandManagement(Player $player, Session $session): void {
        $form = new SimpleForm(function (Player $player, $data) use ($session) {
            $result = $data;
            if ($result === null) return;

            switch ($result) {
                case 0:
                    $session->getPlayer()->teleport($session->getIsland()->getLevel()->getSpawnLocation());
                    $session->sendTranslatedMessage(new MessageContainer("TELEPORTED_TO_ISLAND"));
                    break;
                case 1:
                    IslandFactory::disbandIsland($session->getIsland());
                    break;
                case 2:
                    $island = $session->getIsland();
                    $island->setLocked(!$island->isLocked());
                    $island->save();
                    $session->sendTranslatedMessage(new MessageContainer($island->isLocked() ? "ISLAND_LOCKED" : "ISLAND_UNLOCKED"));
                    break;
                case 3:
                    $this->mainUI($player, $session);
                    break;
            }

        });
        $form->setTitle("§lISLAND MANAGEMENT");
        $form->setContent("§fManage your island!");
        $form->addButton("§8Join Island\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Disband Island\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        if ($session->getIsland()->isLocked()) {
            $form->addButton("§8Unlock Island\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        } else {
            $form->addButton("§8Lock Island\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        }
        $form->addButton("§cBack", 0, "textures/blocks/barrier");
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @param Session $session
     * @param Invitation $invitation
     */
    public function inviteManagement(Player $player, Session $session): void {
        $form = new SimpleForm(function (Player $player, $data) use ($session) {
            $result = $data;
            $invitation = $session->getLastInvitation();
            if ($result === null) return;

            switch ($result) {
                case 0:
                    if ($invitation !== null) {
                        $invitation->accept();
                    } else {
                        $session->sendTranslatedMessage(new MessageContainer("ACCEPT_USAGE"));
                    }
                    break;
                case 1:
                    if ($invitation !== null) {
                        $invitation->deny();
                    } else {
                        $session->sendTranslatedMessage(new MessageContainer("DENY_USAGE"));
                    }
                    break;
                case 2:
                    $this->mainUI($player, $session);
                    break;
            }
        });
        $form->setTitle("§lINVITE MANAGEMENT");
        $form->setContent("§fManage your invites!");
        $form->addButton("§8Accept Invite\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Deny Invite\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§cBack", 0, "textures/blocks/barrier");
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @param Session $session
     */
    public function memberManagement(Player $player, Session $session): void {
        $form = new SimpleForm(function (Player $player, $data) use ($session) {
            $result = $data;
            if ($result === null) return;

            switch ($result) {
                case 0:
                    $this->invitePlayer($player, $session);
                    break;
                case 1:
                    $this->removeMember($player, $session);
                    break;
                case 2:
                    $this->mainUI($player, $session);
                    break;
            }
        });
        $form->setTitle("§lMEMBER MANAGEMENT");
        $form->setContent("§fManage your island members!");
        $form->addButton("§8Invite Player\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Remove Player\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§cBack", 0, "textures/blocks/barrier");
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @param Session $session
     */
    public function invitePlayer(Player $player, Session $session): void {
        $form = new CustomForm(function ($data) use ($session) {
            $result = $data[0];
            if ($result === null) return;

            $p = $this->plugin->getServer()->getPlayer((string) $result);
            if ($p !== null) {
                if ($p instanceof Player) {
                    $invitedPlayerSession = \room17\SkyBlock\SkyBlock::getInstance()->getSessionManager()->getSession($p);
                    $session->sendInvitation(new Invitation($session, $invitedPlayerSession));
                }
            } else {
                $session->sendTranslatedMessage(new MessageContainer("NOT_ONLINE_PLAYER", [
                    "name" => $data[0]
                ]));
            }
        });
        $form->setTitle("§lINVITE PLAYER");
        $form->addLabel("Please write the IGN on the input box below");
        $form->addInput("Player Name:", "TheRealKizu");
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @param Session $session
     */
    public function removeMember(Player $player, Session $session): void {
        $form = new CustomForm(function ($data) use ($session) {
            $result = $data[0];
            if ($result === null) return;

            $p = $this->plugin->getServer()->getPlayer((string) $result);
            if ($p instanceof Player) {
                $playerSession = SessionLocator::getSession($p);
                if ($playerSession->getIsland() === $session->getIsland()) {
                    $session->sendTranslatedMessage(new MessageContainer("CANNOT_BANISH_A_MEMBER"));
                } elseif (in_array($p, $session->getIsland()->getPlayersOnline())) {
                    $p->teleport($this->plugin->getServer()->getDefaultLevel()->getSpawnLocation());
                    $playerSession->sendTranslatedMessage(new MessageContainer("BANISHED_FROM_THE_ISLAND"));
                    $session->sendTranslatedMessage(new MessageContainer("YOU_BANISHED_A_PLAYER", [
                        "name" => $playerSession->getName()
                    ]));
                } else {
                    $session->sendTranslatedMessage(new MessageContainer("NOT_A_VISITOR", [
                        "name" => $playerSession->getName()
                    ]));
                }
            } else {
                $session->sendTranslatedMessage(new MessageContainer("NOT_ONLINE_PLAYER", [
                    "name" => (string) $result
                ]));
            }
        });
        $form->setTitle("§lREMOVE PLAYER");
        $form->addLabel("Please write the IGN on the input box below");
        $form->addInput("Player Name:", "TheRealKizu");
        $player->sendForm($form);
    }

}