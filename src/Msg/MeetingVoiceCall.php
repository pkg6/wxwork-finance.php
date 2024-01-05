<?php

/*
 * This file is part of the pkg6/wework-finance.
 *
 * (c) pkg6 <https://github.com/pkg6>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Pkg6\WeWorkFinance\Msg;

class MeetingVoiceCall extends Msg
{

    /**
     * @var  string
     */
    protected $voiceid;

    /**
     * @var  array
     */
    protected $meeting_voice_call;

    /**
     * @return string
     */
    public function getVoiceid(): string
    {
        return $this->voiceid;
    }

    /**
     * @return array
     */
    public function getMeetingVoiceCall(): array
    {
        return $this->meeting_voice_call;
    }
}
