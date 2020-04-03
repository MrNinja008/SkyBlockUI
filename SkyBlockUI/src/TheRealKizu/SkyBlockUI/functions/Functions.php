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

namespace TheRealKizu\SkyBlockUI\functions;

use GiantQuartz\SkyBlock\island\IslandFactory;
use GiantQuartz\SkyBlock\session\SessionLocator;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use ReflectionException;
use TheRealKizu\SkyBlockUI\libs\jojoe77777\FormAPI\CustomForm;
use TheRealKizu\SkyBlockUI\libs\jojoe77777\FormAPI\SimpleForm;
use TheRealKizu\SkyBlockUI\Loader;

class Functions {

    /**
     * @var Loader
     */
    private $plugin;

    public function __construct(Loader $plugin){
        $this->plugin = $plugin;
    }

    // ---------- [SKYBLOCK] ----------

    /**
     * @param Player $player
     * @throws ReflectionException
     */
    public function sbUI(Player $player) {
        $session = SessionLocator::getSession($player);
        $form = new SimpleForm(function (Player $player, $data) use ($session) {
            $result = $data;
            if ($result !== null) {
                switch ($result) {
                    case 0:
                        //$this->SBIsland($sender);
                        if (!$session->hasIsland()) {
                            $this->SBIsland($player);
                        } else {
                            $player->sendMessage(TextFormat::RED . "You already have an island");
                        }
                        break;
                    case 1:
                        if ($session->hasIsland()) {
                            $this->SBManage($player);
                        } else {
                            $player->sendMessage(TextFormat::RED . "You don't have an island");
                        }
                        break;
                    case 2:
                        $this->memberManage($player);
                        break;
                    case 3:
                        $player->getServer()->dispatchCommand($player, "is help");
                        break;
                    case 4:
                        break;
                }
            }
        });
        $form->setTitle("§lSKYBLOCK UI");
        $form->setContent("§fSelect an option!");
        $form->addButton("§8Island Creation\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Island Management\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Member Management\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Help\n§d§l»§r §8Tap to select!", 0, "textures/items/written_book");
        $form->addButton("§cExit", 0, "textures/blocks/barrier");
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @throws ReflectionException
     */
    public function SBIsland(Player $player) {
        $session = SessionLocator::getSession($player);
        $form = new SimpleForm(function (Player $player, $data) use ($session) {
            $result = $data;
            if ($result !== null) {
                switch ($result) {
                    case 1:
                        //$sender->getServer()->dispatchCommand($sender, "is create Basic");
                        IslandFactory::createIslandFor($session, "Basic");
                        break;
                    case 2:
                        //$player->getServer()->dispatchCommand($player, "is create Palm");
                        IslandFactory::createIslandFor($session, "Palm");
                        break;
                    case 3:
                        //$player->getServer()->dispatchCommand($player, "is create");
                        IslandFactory::createIslandFor($session, "");
                        break;
                    case 4:
                        $this->sbUI($player);
                        break;
                }
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
     */
    public function SBManage(Player $player) {
        $form = new SimpleForm(function (Player $player, $data){
            $result = $data;
            if ($result !== null) {
                switch ($result) {
                    case 0:
                        $player->getServer()->dispatchCommand($player, "is join");
                        break;
                    case 1:
                        $player->getServer()->dispatchCommand($player, "is disband");
                        break;
                    case 2:
                        $player->getServer()->dispatchCommand($player, "is lock");
                        break;
                    case 3:
                        $this->sbUI($player);
                        break;
                }
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
     */
    public function memberManage(Player $player) {
        $form = new SimpleForm(function (Player $player, $data){
            $result = $data;
            if ($result !== null) {
                switch ($result) {
                    case 0:
                        $this->invitePlayer($player);
                        break;
                    case 1:
                        $this->memberBan($player);
                        break;
                    case 2:
                        $this->sbUI($player);
                        break;
                }
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
            if ($result !== null) {
                switch ($result) {
                    case 0:
                        $player->sendMessage(TextFormat::RED . "Feature coming soon!");
                        break;
                    case 1:
                        break;
                }
            }
        });
        $form->setTitle("§lSKYBLOCK UI");
        $form->setContent("§fSelect an option!");
        $form->addButton("§8Island Management\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§cExit", 0, "textures/blocks/barrier");
        $player->sendForm($form);
    }
}
