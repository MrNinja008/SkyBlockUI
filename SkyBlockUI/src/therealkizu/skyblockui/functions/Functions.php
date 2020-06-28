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

namespace therealkizu\skyblockui\functions;

use pocketmine\Player;
use pocketmine\utils\TextFormat;

use room17\SkyBlock\island\IslandFactory;
use room17\SkyBlock\session\Session;
use room17\SkyBlock\utils\message\MessageContainer;

use therealkizu\skyblockui\libs\jojoe77777\FormAPI\CustomForm;
use therealkizu\skyblockui\libs\jojoe77777\FormAPI\SimpleForm;
use therealkizu\skyblockui\Loader;

class Functions {

    /** @var Loader $plugin */
    private $plugin;

    public function __construct(Loader $plugin){
        $this->plugin = $plugin;
    }

    // ---------- [SKYBLOCK] ----------

    /**
     * @param Player $player
     * @param Session $session
     */
    public function sbUI(Player $player, Session $session) {
        $form = new SimpleForm(function (Player $player, $data) use ($session) {
            $result = $data;
            if (is_null($result)) return;

            switch ($result) {
                case 0:
                    if (!$session->hasIsland()) {
                        $this->SBIsland($player, $session);
                    } else {
                        $session->sendTranslatedMessage(new MessageContainer("NEED_TO_BE_FREE"));
                    }
                    break;
                case 1:
                    if ($session->hasIsland()) {
                        $this->SBManage($player, $session);
                    } else {
                        $session->sendTranslatedMessage(new MessageContainer("NEED_ISLAND"));
                    }
                    break;
                case 2:
                    $this->inviteManage($player, $session);
                    break;
                case 3:
                    $this->memberManage($player, $session);
                    break;
                case 4:
                    $player->getServer()->dispatchCommand($player, "is help");
                    break;
            }
        });
        $form->setTitle("§lSKYBLOCK UI");
        $form->setContent("§fSelect an option!");
        $form->addButton("§8Island Creation\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Island Management\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Invite Management\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Member Management\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Help\n§d§l»§r §8Tap to select!", 0, "textures/items/written_book");
        $form->addButton("§cExit", 0, "textures/blocks/barrier");
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @param Session $session
     */
    public function SBIsland(Player $player, Session $session) {
        $form = new SimpleForm(function (Player $player, $data) use ($session) {
            $result = $data;
            if ($result == null) return;

            switch ($result) {
                case 0:
                    IslandFactory::createIslandFor($session, "Basic");
                    break;
                case 1:
                    IslandFactory::createIslandFor($session, "Palm");
                    break;
                case 2:
                    IslandFactory::createIslandFor($session, "");
                    break;
                case 3:
                    $this->sbUI($player, $session);
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
    public function SBManage(Player $player, Session $session) {
        $form = new SimpleForm(function (Player $player, $data) use ($session) {
            $result = $data;
            if ($result == null) return;

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
                    $this->sbUI($player, $session);
                    break;
            }

        });
        $form->setTitle("§lISLAND MANAGEMENT");
        $form->setContent("§fManage your island!");
        $form->addButton("§8Join Island\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Disband Island\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Lock Island\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§cBack", 0, "textures/blocks/barrier");
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @param Session $session
     */
    public function inviteManage(Player $player, Session $session) {
        $form = new SimpleForm(function (Player $player, $data) use ($session) {
            $result = $data;
            if ($result == null) return;

            switch ($result) {
                case 0:
                    $inv = $session->getLastInvitation();
                    if ($inv != null) {
                        $inv->accept();
                    }
                    break;
                case 1:
                    $inv = $session->getLastInvitation();
                    if ($inv != null) {
                        $inv->deny();
                    }
                    break;
                case 2:
                    $this->sbUI($player, $session);
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
    public function memberManage(Player $player, Session $session) {
        $form = new SimpleForm(function (Player $player, $data) use ($session) {
            $result = $data;
            if ($result !== null) return;

            switch ($result) {
                case 0:
                    $this->invitePlayer($player);
                    break;
                case 1:
                    //$this->memberBan($player);
                    $player->sendMessage(TextFormat::RED . "This feature is currently on active development.");
                    break;
                case 2:
                    $this->sbUI($player, $session);
                    break;
            }
        });
        $form->setTitle("§lMEMBER MANAGEMENT");
        $form->setContent("§fManage your island members!");
        $form->addButton("§8Invite Player\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Remove Member\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§cBack", 0, "textures/blocks/barrier");
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     */
    public function invitePlayer(Player $player) {
        $form = new CustomForm(function (Player $player, $data){
            $result = $data[0];
            if ($result !== null) {
                $this->plugin->getServer()->dispatchCommand($player, "is invite" . $result);
            }
        });
        $form->setTitle("§lADD MEMBER");
        $form->addLabel("Please write the IGN on the box.");
        $form->addInput("Player Name:", "TheRealKizu");
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     */
    public function memberBan(Player $player) {
        $form = new CustomForm(function (Player $player, $data){
            $result = $data[0];
            if ($result !== null) {
                $this->plugin->getServer()->dispatchCommand($player, "is banish" . $result);
            }
        });
        $form->addLabel("Please write the IGN on the box.");
        $form->addInput("Player Name:", "TheRealKizu");
        $player->sendForm($form);
    }

    // ---------- [REDSKYBLOCK] ----------

    /**
     * @param Player $player
     */
    public function rsbUI(Player $player) {
        $form = new SimpleForm(function (Player $player, $data){
            $result = $data;
            if ($result !== null) return;

            switch ($result) {
                case 0:
                    $player->sendMessage(TextFormat::RED . "Feature coming soon!");
                    break;
                case 1:
                    break;
            }
        });
        $form->setTitle("§lSKYBLOCK UI");
        $form->setContent("§fSelect an option!");
        $form->addButton("§8Island Management\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§cExit", 0, "textures/blocks/barrier");
        $player->sendForm($form);
    }
}
