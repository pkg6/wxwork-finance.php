<?php

/*
 * This file is part of the pkg6/wework-finance.
 *
 * (c) pkg6 <https://github.com/pkg6>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Pkg6\WeWorkFinance\Msg;

class Card extends Msg
{

    /**
     * @var  string
     */
    protected $roomid;

    /**
     * @var  array
     */
    protected $card;

    /**
     * @return string
     */
    public function getRoomid(): string
    {
        return $this->roomid;
    }

    /**
     * @return array
     */
    public function getCard(): array
    {
        return $this->card;
    }
}
