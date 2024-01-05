<?php

/*
 * This file is part of the pkg6/wework-finance.
 *
 * (c) pkg6 <https://github.com/pkg6>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Pkg6\WeWorkFinance\Msg;

abstract class Msg
{
    /**
     * @var string
     */
    protected $msgid;
    /**
     * @var string
     */
    protected $action;
    /**
     * @var string
     */
    protected $from;
    /**
     * @var string[]
     */
    protected $tolist;
    /**
     * @var string|int
     */
    protected $msgtime;
    /**
     * @var string
     */
    protected $msgtype;

    /**
     * @param $msg
     */
    public function __construct($msg)
    {
        foreach ($msg as $k => $v) {
            $this->$k = $v;
        }
    }

    /**
     * @return string
     */
    public function getMsgid(): string
    {
        return $this->msgid;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @return string[]
     */
    public function getTolist(): array
    {
        return $this->tolist;
    }

    /**
     * @return int|string
     */
    public function getMsgtime()
    {
        return $this->msgtime;
    }

    /**
     * @return string
     */
    public function getMsgtype(): string
    {
        return $this->msgtype;
    }
}
