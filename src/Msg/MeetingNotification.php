<?php

/*
 * This file is part of the pkg6/wework-finance.
 *
 * (c) pkg6 <https://github.com/pkg6>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Pkg6\WeWorkFinance\Msg;

class MeetingNotification extends Msg
{
    /**
     * @var  array
     */
    protected $info;

    /**
     * @var  string
     */
    protected $roomid;

    /**
     * @var  string
     */
    protected $time;
}