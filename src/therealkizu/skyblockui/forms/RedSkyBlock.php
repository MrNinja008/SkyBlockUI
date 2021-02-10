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

namespace therealkizu\skyblockui\forms;

use pocketmine\Player;

use therealkizu\skyblockui\libs\jojoe77777\FormAPI\SimpleForm;
use therealkizu\skyblockui\Loader;

class RedSkyBlock {

    /** @var Loader $plugin */
    protected $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
    }

    // TODO: Finish this
    // Currently Experimantal

    public function mainUI(Player $player) {
        $form = new SimpleForm(function (Player $player, $data)  {
            $result = $data;
            if ($result === null)
                return;

            switch ($result) {
                case 0:
                    $this->islandManagement($player);
                    break;
            }
        });
        $form->setTitle("§lSKYBLOCK UI");
        $form->setContent("§fSelect an option!");
        $form->addButton("§8Island Management\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $player->sendForm($form);
    }

    public function islandManagement(Player $player) {
        $form = new SimpleForm(function ($data)  {
            $result = $data;
            if ($result === null)
                return;

            switch ($result) {
                case 0:
                    break;
            }
        });
        $form->setTitle("§lISLAND CREATION");
        $form->setContent("§fDo you want to create an island?");
        $form->addButton("§8Island Creation\n§d§l»§r §8Tap to select!", 0, "textures/items/paper");
        $player->sendForm($form);
    }

}