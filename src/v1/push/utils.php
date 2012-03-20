<?php

// +------------------------------------------------------------+
// | LICENSE                                                    |
// +------------------------------------------------------------+

/**
 * Copyright &copy; 2009-2011 CrowdTwist, Inc.  All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *   o Redistributions of source code must retain the above copyright notice,
 *     this list of conditions and the following disclaimer.
 *   o Redistributions in binary form must reproduce the above copyright notice,
 *     this list of conditions and the following disclaimer in the documentation
 *     and/or other materials provided with the distribution.
 *   o The names, trade names, trademarks, logos and other designations of
 *     CrowdTwist, Inc. and its contributors may not be used to endorse,
 *     publicize, advertise or otherwise promote any product derived from this
 *     software.
 *
 * THIS SOFTWARE IS PROVIDED BY CROWDTWIST, INC. "AS IS" AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE, ARE DISCLAIMED.  IN NO
 * EVENT SHALL CROWDTWIST, INC. OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, RELIANCE OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE TECHNOLOGIES,
 * GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
 * DAMAGE.
 */

// +------------------------------------------------------------+
// | PUBLIC FUNCTIONS                                           |
// +------------------------------------------------------------+

function crwd_push_signature_is_valid($key, $secret, $query_string)
{
    parse_str($query_string, $params);
    $api_sig = $params[ 'api_sig' ];

    unset($params[ 'api_key' ]);
    unset($params[ 'api_sig' ]);

    $params[ 'api_key' ] = $key;
    ksort($params);

    $sig = '';
    foreach ($params as $key => $value)
    {
        $sig .= $key . '=' . $value;
    }
    $sig .= $secret;

    return (bool)$api_sig == md5($sig);
}
?>
