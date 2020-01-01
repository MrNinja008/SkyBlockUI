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

namespace TheRealKizu\SkyBlockUI\functions;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\utils\TextFormat;
use TheRealKizu\SkyBlockUI\Loader;
use TheRealKizu\SkyBlockUI\libs\jojoe77777\FormAPI\CustomForm;
use TheRealKizu\SkyBlockUI\libs\jojoe77777\FormAPI\SimpleForm;

class Functions {

    /**
     * @var Loader
     */
	private $plugin;

	public function __construct(Loader $plugin){
        $this->plugin = $plugin;
	}

	// ---------- [SKYBLOCK] ----------
	public function sbUI(Player $player) {
        $form = new SimpleForm(function (Player $sender, $data){
            $result = $data;
            if ($result !== null) {
                switch ($result) {
                    case 0:
                        $this->SBIsland($sender);
                        break;
                    case 1:
                        $this->SBManage($sender);
                        break;
                    case 2:
                        //$this->memberManage($sender);
                        $sender->sendMessage(TextFormat::RED . "Feature is on rewritting...");
                        break;
                    case 3:
                        //$this->inviteMenu($sender);
                        $sender->sendMessage(TextFormat::RED . "Feature is on rewritting...");
                        break;
                    case 4:
                        $sender->getServer()->dispatchCommand($sender, "is help");
                        break;
                    case 5:
                        break;
                }
            }
        });
        $form->setTitle("§lSKYBLOCK UI");
        $form->setContent("§fSelect an option!");
        $form->addButton("§8Island Creation\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Island Management\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Member Management\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Invite Management\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $form->addButton("§8Help\n§d§l»§r §8Tap to select!", 0, "textures/items/written_book");
        $form->addButton("§cExit", 0, "textures/blocks/barrier");
        $player->sendForm($form);
    }

    public function SBIsland(Player $player) {
        $form = new SimpleForm(function (Player $sender, $data){
            $result = $data;
            if ($result !== null) {
                switch ($result) {
                    case 1:
                        $sender->getServer()->dispatchCommand($sender, "is create Basic");
                        break;
                    case 2:
                        $sender->getServer()->dispatchCommand($sender, "is create Palm");
                        break;
                    case 3:
                        $sender->getServer()->dispatchCommand($sender, "is create");
                        break;
                    case 4:
                        $this->sbUI($sender);
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

    public function SBManage(Player $sender) {
        $form = new SimpleForm(function (Player $sender, $data){
            $result = $data;
            if ($result !== null) {
                switch ($result) {
                    case 0:
                        $sender->getServer()->dispatchCommand($sender, "is join");
                        break;
                    case 1:
                        $sender->getServer()->dispatchCommand($sender, "is disband");
                        break;
                    case 2:
                        $sender->getServer()->dispatchCommand($sender, "is lock");
                        break;
                    case 3:
                        $this->sbUI($sender);
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
        $sender->sendForm($form);
    }

    public function memberManage(Player $sender) {
        $form = new SimpleForm(function (Player $sender, $data){
            $result = $data;
            if (is_null($result)) return;
            switch ($result) {
                case 0:
                    $this->sbUI($sender);
                    break;
                case 1:
                    $this->memberAdd($sender);
                    break;
                case 2:
                    $this->memberRem($sender);
                    break;
            }
        });
        $form->setTitle("§lMEMBER MANAGEMENT");
        $form->setContent("§fManage your island members!");
        $form->addButton("§cBack", 0);
        $form->addButton("§8Add Member\n§d§l»§r §8Tap to select!", 1);
        $form->addButton("§8Remove Member\n§d§l»§r §8Tap to select!", 2);
        $form->sendToPlayer($sender);
    }

	public function memberAdd(Player $player) {
        $form = new CustomForm(function (Player $p, $data){
            $result = $data[0];
            if (is_null($result)) return;
            Server::getInstance()->dispatchCommand($p, "is invite" . $data[0]);
        });
        $form->setTitle("§lADD MEMBER");
        $form->addLabel("Please write the IGN on the box.");
        $form->addInput("Player Name:", "Steve");
        $form->sendToPlayer($player);
	}

	public function memberRem(Player $player) {
        $form = new CustomForm(function (Player $p, $data){
            $result = $data[0];
            if (is_null($result)) return;
            Server::getInstance()->dispatchCommand($p, "is remove" . $data[0]);
        });
        $form->addLabel("Please write the IGN on the box.");
        $form->addInput("Player Name:", "Steve");
        $form->sendToPlayer($player);
	}

    public function inviteForm(Player $player) {
        $form = new CustomForm(function (Player $p, $data){
            $result = $data[0];
            if (is_null($result)) return;
            Server::getInstance()->dispatchCommand($p, "is invite" . $data[0]);
        });
        $form->addLabel("Please write the IGN on the box.");
        $form->addInput("Player Name:", "Steve");
        $form->sendToPlayer($player);
    }

    public function inviteMenu(Player $player) {
        $form = new SimpleForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) return;
            switch ($result) {
                case 0:
                    break;
                case 1:
                    $sender->getServer()->dispatchCommand($sender, "is accept");
                    break;
                case 2:
                    $sender->getServer()->dispatchCommand($sender, "is deny");
                    break;
                case 3:
                    $this->inviteForm($sender);
                    break;
            }
        });
        $form->setTitle("§lINVITE MENU");
        $form->setContent("§fSelect an option!");
        $form->addButton("§cBack", 0);
        $form->addButton("§8Accept Invite\n§d§l»§r §8Tap to select!", 1);
        $form->addButton("§8Deny Invite\n§d§l»§r §8Tap to select!", 2);
        $form->addButton("§8Invite Player\n§d§l»§r §8Tap to select!", 3);
        $form->sendToPlayer($player);
    }

    // ---------- [REDSKYBLOCK] ----------
    public function rsbUI(Player $player) {
        $form = new SimpleForm(function (Player $sender, $data){
            $result = $data;
            if (is_null($result)) return;
            switch ($result) {
                case 0:
                    break;
            }
        });
        $form->setTitle("§lSKYBLOCK UI");
        $form->setContent("§fSelect an option!");
        $form->addButton("§cExit", 0);
        $form->sendToPlayer($player);
    }
}
