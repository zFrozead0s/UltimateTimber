<?php

/**
 *
 *  _____      __    _   ___ ___
 * |   \ \    / /__ /_\ | _ \_ _|
 * | |) \ \/\/ /___/ _ \|  _/| |
 * |___/ \_/\_/   /_/ \_\_| |___|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * Written by @CortexPE <https://CortexPE.xyz>
 * Intended for use on SynicadeNetwork <https://synicade.com>
 */

declare(strict_types = 1);

namespace CortexPE\DiscordWebhookAPI;

use DateTime;
use DateTimeZone;

final class Embed {
    
    protected array $data = [];

    public static function create(): Embed {
        return new Embed();
    }

    public function asArray(): array {
        return $this->data;
    }

    public function setAuthor(string $name, string $url = null, string $iconURL = null): Embed {
        if (!isset($this->data['author'])) {
            $this->data['author'] = [];
        }
        $this->data['author']['name'] = $name;

        if ($url !== null) {
            $this->data['author']['url'] = $url;
        }

        if ($iconURL !== null) {
            $this->data['author']['icon_url'] = $iconURL;
        }
        return $this;
    }

    public function setTitle(string $title): Embed {
        $this->data['title'] = $title;
        return $this;
    }

    public function setDescription(string $description): Embed {
        $this->data['description'] = $description;
        return $this;
    }

    public function setColor(int $color): Embed {
        $this->data['color'] = $color;
        return $this;
    }

    public function addField(string $name, string $value, bool $inline = false): Embed {
        if (!isset($this->data['fields'])) {
            $this->data['fields'] = [];
        }
        $this->data['fields'][] = [
            'name' => $name,
            'value' => $value,
            'inline' => $inline,
        ];
        return $this;
    }

    public function setThumbnail(string $url): Embed {
        if (!isset($this->data['thumbnail'])) {
            $this->data['thumbnail'] = [];
        }
        $this->data['thumbnail']['url'] = $url;
        return $this;
    }

    public function setImage(string $url): Embed {
        if (!isset($this->data['image'])) {
            $this->data['image'] = [];
        }
        $this->data['image']['url'] = $url;
        return $this;
    }

    public function setFooter(string $text, string $iconURL = null): Embed {
        if (!isset($this->data['footer'])) {
            $this->data['footer'] = [];
        }
        $this->data['footer']['text'] = $text;
        
        if ($iconURL !== null) {
            $this->data['footer']['icon_url'] = $iconURL;
        }
        return $this;
    }

    public function setTimestamp(DateTime $timestamp): Embed {
        $timestamp->setTimezone(new DateTimeZone('UTC'));
        $this->data['timestamp'] = $timestamp->format('Y-m-d\TH:i:s.v\Z');
        return $this;
    }
}