<?php

/*
 * This file is part of the pkg6/wework-finance.
 *
 * (c) pkg6 <https://github.com/pkg6>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Pkg6\WeWorkFinance\Msg;

class VoipDocShare extends Msg
{

    /**
     * @var  string
     */
    protected $voipid;

    /**
     * @var  array
     */
    protected $voip_doc_share;

    /**
     * @return string
     */
    public function getVoipid(): string
    {
        return $this->voipid;
    }

    /**
     * @return array
     */
    public function getVoipDocShare(): array
    {
        return $this->voip_doc_share;
    }
}
