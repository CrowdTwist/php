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
// | INCLUDES                                                   |
// +------------------------------------------------------------+

require_once '../../src/v1/CrowdTwist.php';

// +------------------------------------------------------------+
// | INSTRUCTIONS                                               |
// +------------------------------------------------------------+

if ($argc != 6)
{
    print 'usage: '
          . $argv[ 0 ] . ' '
          . '[API Key] '
          . '[API Secret] '
          . '[Delimiter] '
          . '[GZIP Compress 1|0] '
          . "[File Path]\n";
    exit(0);
}

list($command, $api_key, $api_secret, $delimiter, $gzip, $file_path) = $argv;

$response = crwd_Rpc_Api::make($api_key, $api_secret)
                ->purchase_return_batch($delimiter, intval($gzip), $file_path);
if (empty($response))
{
    // An empty response can indicate the cURL timeout values in the method
    // purchase_return_batch() are too low for the size of file being uploaded.
    // Consider increasing those temporarily to determine if that is the cause
    // of the exception.
    throw new crwd_Exception('a timeout or fatal server-side error has '
                             . 'occurred that may not have been logged; please '
                             . 'contact noc@crowdtwist.com');
}

if (is_string($response))
{
    print "$response\n";
    exit(0);
}
else
{
    print_r($response);
    exit(1);
}
?>
