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
// | DOCUMENTATION                                              |
// +------------------------------------------------------------+

/**
 * PREREQUISITES
 * =============
 *
 *  o You must have PHP 5 or higher.
 *  o You must have cURL installed and loaded.
 *  o You must obtain your API key and secret.
 *
 * DOCUMENTATION
 * =============
 *
 *  http://www.crowdtwist.com/developers
 *  http://www.crowdtwist.com/docs/api
 *
 * CONTACT US
 * ==========
 *
 *  api@crowdtwist.com
 *
 * CrowdTwist, Inc. Michael Montero, May 17, 2011
 */

// +------------------------------------------------------------+
// | DEFINITIONS                                                |
// +------------------------------------------------------------+

define('_CRWD_MAX_LEADERBOARD', 100);

// +------------------------------------------------------------+
// | PUBLIC CLASSES                                             |
// +------------------------------------------------------------+

//
// +----------------+
// | crwd_Pixel_Api |
// +----------------+
//

/**
 * Performs API requests by drawing a 1x1 transparent image (pixel) into a
 * XHTML document.
 */
class crwd_Pixel_Api
extends _crwd_Api
{
    static function make($key, $secret)
    {
        return new self($key, $secret);
    }

    // +----------------+
    // | Public Methods |
    // +----------------+

    //
    // Auth
    //

    final public function get_auth_sign_in_url($user_id)
    {
        return $this->get_url(
                $this->get_auth_sign_in_end_point_name(),
                array('user_id' => $user_id));
    }

    final public function auth_sign_in($user_id)
    {
        $this->draw($this->get_auth_sign_in_end_point_name(),
                    array('user_id' => $user_id));
    }

    final public function get_auth_sign_out_url($user_id)
    {
        return $this->get_url(
                $this->get_auth_sign_out_end_point_name(),
                array('user_id' => $user_id));
    }

    final public function auth_sign_out($user_id)
    {
        $this->draw($this->get_auth_sign_out_end_point_name(),
                    array('user_id' => $user_id));
    }

    //
    // Lang
    //

    final public function get_lang_set_url($lang)
    {
        return $this->get_url(
                $this->get_lang_set_end_point_name(),
                array('lang' => $lang));
    }

    final public function lang_set($lang)
    {
        $this->draw($this->get_lang_set_end_point_name(),
                    array('lang' => $lang));
    }

    //
    // Purchase
    //

    final public function get_purchase_create_url(crwd_Purchase $purchase)
    {
        return $this->get_url(
                $this->get_purchase_create_end_point_name(),
                $purchase->get_query_table());
    }

    final public function purchase_create(crwd_Purchase $purchase)
    {
        $this->draw($this->get_purchase_create_end_point_name(),
                    $purchase->get_query_table());
    }

    //
    // User
    //

    final public function get_user_create_url(crwd_User $user)
    {
        return $this->get_url(
                $this->get_user_create_end_point_name(),
                $user->get_query_table());
    }

    final public function user_create(crwd_User $user)
    {
        $this->draw($this->get_user_create_end_point_name(),
                    $user->get_query_table());
    }

    final public function get_user_delete_url($user_id)
    {
        return $this->get_url(
                $this->get_user_delete_end_point_name(),
                array('user_id' => $user_id));
    }

    final public function user_delete($user_id)
    {
        $this->draw($this->get_user_delete_end_point_name(),
                    array('user_id' => $user_id));
    }

    final public function get_user_update_url(crwd_User $user)
    {
        $this->validate_update_user_object($user);

        return $this->get_url(
                $this->get_user_update_end_point_name(),
                $user->get_query_table());
    }

    final public function user_update(crwd_User $user)
    {
        $this->validate_update_user_object($user);

        $this->draw($this->get_user_update_end_point_name(),
                    $user->get_query_table());
    }

    //
    // User Activity
    //

    final public function get_user_activity_url($activity_name,
                                                $user_id,
                                                $params = array())
    {
        return $this->get_url(
                $this->get_user_activity_end_point_name(),
                array_merge(array('activity_name' => $activity_name,
                                  'user_id'       => $user_id),
                            $params));
    }

    final public function user_activity($activity_name,
                                        $user_id,
                                        $params = array())
    {
        $this->draw($this->get_user_activity_end_point_name(),
                    array_merge(
                        array('activity_name' => $activity_name,
                              'user_id'       => $user_id),
                    $params));
    }

    // +-----------------+
    // | Private Methods |
    // +-----------------+

    private function draw($end_point_name, $query_table)
    {
        $this->draw_js('pixel', $end_point_name, $query_table);
    }

    private function get_url($end_point_name, $query_table)
    {
        return $this->get_end_point_url($end_point_name, 'pixel', $query_table);
    }
}

//
// +--------------+
// | crwd_Rpc_Api |
// +--------------+
//

/**
 * Performs API requests by remote procedure call (RPC) using cURL.
 */
class crwd_Rpc_Api
extends _crwd_Api
{
    static function make($key, $secret)
    {
        return new self($key, $secret);
    }

    function __construct($key, $secret)
    {
        if (!function_exists('curl_init'))
        {
            throw new crwd_Exception('cannot instantiate ' . __CLASS__ . '; '
                                     . 'cURL is required for proper operation');
        }

        parent::__construct($key, $secret);
    }

    // +----------------+
    // | Public Methods |
    // +----------------+

    //
    // Activities
    //

    final public function get_activities_get_url()
    {
        return $this->get_url(
                $this->get_activities_get_end_point_name(),
                array());
    }

    final public function activities_get()
    {
        return $this->exec(
                'GET',
                $this->get_activities_get_end_point_name(),
                array());
    }

    final public function get_activities_get_user_url($user_id)
    {
        return $this->get_url(
                $this->get_activities_get_user_end_point_name(),
                array('user_id' => $user_id));
    }

    final public function activities_get_user($user_id)
    {
        return $this->exec(
                'GET',
                $this->get_activities_get_user_end_points_name(),
                array('user_id' => $user_id));
    }

    //
    // Leaderboard
    //

    final public function get_leaderboard_get_url($start,
                                                  $finish,
                                                  $period = 'all-time')
    {
        $this->validate_pagination($start, $finish, _CRWD_MAX_LEADERBOARD);

        return $this->get_url(
                $this->get_leaderboard_get_end_point_name(),
                array('finish' => $finish,
                      'period' => $period,
                      'start'  => $start));
    }

    final public function leaderboard_get($start,
                                          $finish,
                                          $period = 'all-time')
    {
        $this->validate_pagination($start, $finish, _CRWD_MAX_LEADERBOARD);

        return $this->exec(
                'GET',
                $this->get_leaderboard_get_end_point_name(),
                array('finish' => $finish,
                      'period' => $period,
                      'start'  => $start));
    }

    //
    // Location
    //

    final public function get_location_non_us_verify_url($city_name,
                                                         $country_name)
    {
        return $this->get_url(
                $this->get_location_non_us_verify_end_point_name(),
                array('city_name'    => $city_name,
                      'country_name' => $country_name));
    }

    final public function location_non_us_verify($city_name, $country_name)
    {
        return $this->exec(
                'GET',
                $this->get_location_non_us_verify_end_point_name(),
                array('city_name'    => $city_name,
                      'country_name' => $country_name));
    }

    final public function get_location_us_verify_url($us_postal_code)
    {
        return $this->get_url(
                $this->get_location_us_verify_end_point_name(),
                array('us_postal_code' => $us_postal_code));
    }

    final public function location_us_verify($us_postal_code)
    {
        return $this->exec(
                'GET',
                $this->get_location_us_verify_end_point_name(),
                array('us_postal_code' => $us_postal_code));
    }

    //
    // Purchase
    //

    final public function get_purchase_create_url(crwd_Purchase $purchase)
    {
        return $this->get_url(
                $this->get_purchase_create_end_point_name(),
                $purchase->get_query_table());
    }

    final public function purchase_create(crwd_Purchase $purchase)
    {
        return $this->exec(
                'POST',
                $this->get_purchase_create_end_point_name(),
                $purchase->get_query_table());
    }

    final public function purchase_create_batch($delimiter, $gzip, $file_path)
    {
        if (!is_file($file_path))
        {
            throw new crwd_Exception("could not find file at \"$file_path\"");
        }

        parse_str(
            $this->get_signed_query_string(
                'purchase-create-batch',
                array(
                    'delimiter' => $delimiter,
                    'file_hash' => sha1(file_get_contents($file_path)),
                    'gzip'      => intval($gzip),
                )),
            $params);

        $params[ 'file' ] = "@$file_path"
                            . ($gzip ? ';type=application/x-gzip' : '');

        $curl_handle = curl_init();
        curl_setopt($curl_handle,
                    CURLOPT_URL,
                    $this->get_end_point_base('json'));
        curl_setopt($curl_handle,
                    CURLOPT_RETURNTRANSFER,
                    true);
        curl_setopt($curl_handle,
                    CURLOPT_CONNECTTIMEOUT,
                    10800);
        curl_setopt($curl_handle,
                    CURLOPT_TIMEOUT,
                    10800);
        curl_setopt($curl_handle,
                    CURLOPT_POST,
                    true);
        curl_setopt($curl_handle,
                    CURLOPT_POSTFIELDS,
                    $params);

        $response  = curl_exec($curl_handle);
        $http_code = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
        $error     = curl_error($curl_handle);
        if (!empty($error))
        {
            throw new crwd_Exception(
                "cURL failed with error message \"$error\"");
        }

        if ($http_code != 200)
        {
            throw new crwd_Exception(
                'a timeout or fatal server-side error has occurred that may '
                . 'not have been logged; please contact noc@crowdtwist.com');
        }

        return empty($response) ? null : json_decode($response, true);
    }

    final public function purchase_return_batch($delimiter, $gzip, $file_path)
    {
        if (!is_file($file_path))
        {
            throw new crwd_Exception("could not find file at \"$file_path\"");
        }

        parse_str(
            $this->get_signed_query_string(
                'purchase-return-batch',
                array(
                    'delimiter' => $delimiter,
                    'file_hash' => sha1(file_get_contents($file_path)),
                    'gzip'      => intval($gzip),
                )),
            $params);

        $params[ 'file' ] = "@$file_path"
                            . ($gzip ? ';type=application/x-gzip' : '');

        $curl_handle = curl_init();
        curl_setopt($curl_handle,
                    CURLOPT_URL,
                    $this->get_end_point_base('json'));
        curl_setopt($curl_handle,
                    CURLOPT_RETURNTRANSFER,
                    true);
        curl_setopt($curl_handle,
                    CURLOPT_CONNECTTIMEOUT,
                    10800);
        curl_setopt($curl_handle,
                    CURLOPT_TIMEOUT,
                    10800);
        curl_setopt($curl_handle,
                    CURLOPT_POST,
                    true);
        curl_setopt($curl_handle,
                    CURLOPT_POSTFIELDS,
                    $params);

        $response  = curl_exec($curl_handle);
        $http_code = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
        $error     = curl_error($curl_handle);
        if (!empty($error))
        {
            throw new crwd_Exception(
                "cURL failed with error message \"$error\"");
        }

        if ($http_code != 200)
        {
            throw new crwd_Exception(
                'a timeout or fatal server-side error has occurred that may '
                . 'not have been logged; please contact noc@crowdtwist.com');
        }

        return empty($response) ? null : json_decode($response, true);
    }

    //
    // Redemption
    //

    final public function get_redemption_create_url($user_id, $num_points)
    {
        return $this->get_url(
                $this->get_redemption_create_end_point_name(),
                array(
                    'num_points' => $num_points,
                    'user_id'    => $user_id,
                ));
    }

    final public function redemption_create($user_id, $num_points)
    {
        return $this->exec(
                'POST',
                $this->get_redemption_create_end_point_name(),
                array(
                    'num_points' => $num_points,
                    'user_id'    => $user_id,
                ));
    }

    //
    // Share
    //

    final public function get_share_facebook_url($user_id, $title, $url, $fb_id)
    {
        return $this->get_url(
                $this->get_share_facebook_end_point_name(),
                array(
                    'user_id' => $user_id,
                    'title'   => $title,
                    'url'     => $url,
                    'fb_id'   => $fb_id,
                ));
    }

    final public function share_facebook($user_id, $title, $url, $fb_id)
    {
        $this->exec(
            'GET',
            $this->get_share_facebook_end_point_name(),
            array(
                'user_id' => $user_id,
                'title'   => $title,
                'url'     => $url,
                'fb_id'   => $fb_id,
            ));
    }

    final public function get_share_twitter_url($user_id,
                                                $title,
                                                $url,
                                                $twit_id)
    {
        return $this->get_url(
                $this->get_share_twitter_end_point_name(),
                array(
                    'user_id' => $user_id,
                    'title'   => $title,
                    'url'     => $url,
                    'twit_id' => $twit_id,
                ));
    }

    final public function share_twitter($user_id, $title, $url, $twit_id)
    {
        $this->exec(
            'GET',
            $this->get_share_twitter_end_point_name(),
            array(
                'user_id' => $user_id,
                'title'   => $title,
                'url'     => $url,
                'twit_id' => $twit_id,
            ));
    }

    //
    // User
    //

    final public function get_user_create_url(crwd_User $user)
    {
        return $this->get_url(
                $this->get_user_create_end_point_name(),
                $user->get_query_table());
    }

    final public function user_create(crwd_User $user)
    {
        return $this->exec(
                'POST',
                $this->get_user_create_end_point_name(),
                $user->get_query_table());
    }

    final public function get_user_delete_url($user_id)
    {
        return $this->get_url(
                $this->get_user_delete_end_point_name(),
                array('user_id' => $user_id));
    }

    final public function user_delete($user_id)
    {
        return $this->exec(
                'POST',
                $this->get_user_delete_end_point_name(),
                array('user_id' => $user_id));
    }

    final public function get_user_get_url($user_id)
    {
        return $this->get_url(
                $this->get_user_get_end_point_name(),
                array('user_id' => $user_id));
    }

    final public function user_get($user_id)
    {
        return $this->exec(
                'GET',
                $this->get_user_get_end_point_name(),
                array('user_id' => $user_id));
    }

    final public function get_user_points_summary_url($user_id)
    {
        return $this->get_url(
                $this->get_user_points_summary_end_point_name(),
                array('user_id' => $user_id));
    }

    final public function user_points_summary($user_id)
    {
        return $this->exec(
                'GET',
                $this->get_user_points_summary_end_point_name(),
                array('user_id' => $user_id));
    }

    final public function get_user_ranking_summary_url($user_id)
    {
        return $this->get_url(
                $this->get_user_ranking_summary_end_point_name(),
                array('user_id' => $user_id));
    }

    final public function user_ranking_summary($user_id)
    {
        return $this->exec(
                'GET',
                $this->get_user_ranking_summary_end_point_name(),
                array('user_id' => $user_id));
    }

    final public function get_user_update_url(crwd_User $user)
    {
        $this->validate_update_user_object($user);

        return $this->get_url(
                $this->get_user_update_end_point_name(),
                $user->get_query_table());
    }

    final public function user_update(crwd_User $user)
    {
        $this->validate_update_user_object($user);

        return $this->exec(
                'POST',
                $this->get_user_update_end_point_name(),
                $user->get_query_table());
    }

    //
    // User Activity
    //

    final public function get_user_activity_url($activity_name,
                                                $user_id,
                                                $params = array())
    {
        return $this->get_url(
                $this->get_user_activity_end_point_name(),
                array_merge(array('activity_name' => $activity_name,
                                  'user_id'       => $user_id),
                            $params));
    }

    final public function user_activity($activity_name,
                                        $user_id,
                                        $params = array())
    {
        return $this->exec(
                'POST',
                $this->get_user_activity_end_point_name(),
                array_merge(array('activity_name' => $activity_name,
                                  'user_id'       => $user_id),
                            $params));
    }

    // +-----------------+
    // | Private Methods |
    // +-----------------+

    private function exec($http_method, $end_point_name, $query_table)
    {
        return $this->exec_request($http_method,
                                   'json',
                                   $end_point_name,
                                   $query_table);
    }

    private function get_url($end_point_name, $query_table)
    {
        return $this->get_end_point_url($end_point_name, 'json', $query_table);
    }
}

//
// +-----------+
// | crwd_User |
// +-----------+
//

/**
 * Describes an user that is being added or updated but not retrieved.
 */
class crwd_User
extends _crwd_Transmittable_Entity
{
    static function make()
    {
        return new self();
    }

    function __construct()
    {
        parent::__construct();
    }

    // +----------------+
    // | Public Methods |
    // +----------------+

    final public function get_settable_vars()
    {
        return array(
            'city_name'                  => true,
            'country_name'               => true,
            'date_of_birth'              => true,
            'email_address'              => true,
            'facebook_access_token'      => true,
            'facebook_user_id'           => true,
            'first_name'                 => true,
            'fsq_access_token'           => true,
            'fsq_user_id'                => true,
            'gender_id'                  => true,
            'is_active'                  => true,
            'last_name'                  => true,
            'middle_name'                => true,
            'mobile_carrier_id'          => true,
            'mobile_phone_number'        => true,
            'password'                   => true,
            'receive_email_updates'      => true,
            'send_verify_email'          => true,
            'third_party_id'             => true,
            'twitter_oauth_token'        => true,
            'twitter_oauth_token_secret' => true,
            'twitter_user_id'            => true,
            'user_id'                    => true,
            'username'                   => true,
            'us_zip_code'                => true,
        );
    }

    // Validation will occur on the CrowdTwist server side.
    final public function validate() {}
}

//
// +---------------+
// | crwd_Purchase |
// +---------------+
//

/**
 * Describes a purchase which contains a receipt, items purchased on the
 * receipt and the, optional, location at which the purchase occurred.
 */
class crwd_Purchase
{
    private $receipt_id;
    private $date_purchase;
    private $total;
    private $shipping;
    private $tax;
    private $location;
    private $index;
    private $items;

    static function make($receipt_id, $date_purchase, $total, $shipping, $tax)
    {
        return new self($receipt_id, $date_purchase, $total, $shipping, $tax);
    }

    function __construct($receipt_id, $date_purchase, $total, $shipping, $tax)
    {
        $this->receipt_id    = $receipt_id;
        $this->date_purchase = $date_purchase;
        $this->total         = $total;
        $this->shipping      = $shipping;
        $this->tax           = $tax;
        $this->location      = array('loc_id'          => null,
                                     'loc_description' => null,
                                     'loc_address_1'   => null,
                                     'loc_address_2'   => null,
                                     'loc_address_3'   => null,
                                     'loc_lat'         => null,
                                     'loc_long'        => null);
        $this->index         = 1;
        $this->items         = array();
    }

    // +----------------+
    // | Public Methods |
    // +----------------+

    final public function add_item($id,
                                   $description,
                                   $color,
                                   $size,
                                   $quantity,
                                   $cost,
                                   $subtotal,
                                   $tax,
                                   $total,
                                   $coupon)
    {
        $this->items[ "item_id_" . $this->index ]          = $id;
        $this->items[ "item_description_" . $this->index ] = $description;
        $this->items[ "item_color_" . $this->index ]       = $color;
        $this->items[ "item_size_" . $this->index ]        = $size;
        $this->items[ "item_quantity_" . $this->index ]    = $quantity;
        $this->items[ "item_cost_" . $this->index ]        = $cost;
        $this->items[ "item_subtotal_" . $this->index ]    = $subtotal;
        $this->items[ "item_tax_" . $this->index ]         = $tax;
        $this->items[ "item_total_" . $this->index ]       = $total;
        $this->items[ "item_coupon_" . $this->index ]      = $coupon;

        $this->index++;

        return $this;
    }

    final public function add_location($id,
                                       $description,
                                       $address_1,
                                       $address_2,
                                       $address_3,
                                       $latitude,
                                       $longitude)
    {
        $this->location[ 'loc_id' ]          = $id;
        $this->location[ 'loc_description' ] = $description;
        $this->location[ 'loc_address_1' ]   = $address_1;
        $this->location[ 'loc_address_2' ]   = $address_2;
        $this->location[ 'loc_address_3' ]   = $address_3;
        $this->location[ 'loc_lat' ]         = $latitude;
        $this->location[ 'loc_long' ]        = $longitude;
        return $this;
    }

    final public function get_query_table()
    {
        $this->validate();

        return array_merge(array('receipt_id'    => $this->receipt_id,
                                 'date_purchase' => $this->date_purchase,
                                 'total'         => $this->total,
                                 'shipping'      => $this->shipping,
                                 'tax'           => $this->tax),
                           $this->location,
                           $this->items);
    }

    // +-----------------+
    // | Private Methods |
    // +-----------------+

    private function validate()
    {
        if (empty($this->receipt_id))
        {
            throw new crwd_Exception('the receipt ID was empty');
        }

        if (empty($this->date_purchase))
        {
            throw new crwd_Exception('the date of purchase was empty');
        }

        if (empty($this->items))
        {
            throw new crwd_Exception('the purchase does not contain any items');
        }
    }
}

//
// +----------------+
// | crwd_Exception |
// +----------------+
//

/**
 * Describes a named exception that will be thrown in all error cases.
 */
class crwd_Exception
extends Exception
{
    function __construct($message)
    {
        parent::__construct($message);
    }
}

// +------------------------------------------------------------+
// | PRIVATE CLASSES                                            |
// +------------------------------------------------------------+

//
// +-----------+
// | _crwd_Api |
// +-----------+
//

class _crwd_Api
{
    const API_DOMAIN = 'api.crowdtwist.com';
    const API_PATH   = '/v1';

    private $key;
    private $secret;
    private $request_scheme;
    private $jsonp_callback;

    function __construct($key, $secret)
    {
        $this->key            = $key;
        $this->secret         = $secret;
        $this->request_scheme = 'http';
    }

    // +----------------+
    // | Public Methods |
    // +----------------+

    final public function disable_jsonp()
    {
        $this->jsonp_callback = null;
        return $this;
    }

    final public function enable_jsonp($callback)
    {
        if (!empty($callback))
        {
            $this->jsonp_callback = $callback;
        }

        return $this;
    }

    final public function enable_ssl()
    {
        $this->request_scheme = 'https';
        _crwd_Js_Renderer::get_instance()->enable_ssl();

        return $this;
    }

    final public function get_end_point_url($end_point_name,
                                            $return_format,
                                            $query_table)
    {
        return $this->get_end_point_base($return_format)
               . '?'
               . $this->get_signed_query_string($end_point_name, $query_table);
    }

    final public function get_signed_query_string($end_point_name, $query_table)
    {
        $query_table[ 'api_ep' ]  = $end_point_name;
        $query_table[ 'api_key' ] = $this->key;

        if (!empty($this->jsonp_callback))
        {
            $query_table[ 'callback' ] = $this->jsonp_callback;
        }

        ksort($query_table);

        $query_string = '';
        foreach ($query_table as $key => $value)
        {
            $query_string .= $key . '=' . urlencode($value) . '&';
        }

        return $query_string . 'api_sig=' . $this->get_signature($query_table);
    }

    // +-------------------+
    // | Protected Methods |
    // +-------------------+

    final protected function draw_js($method, $end_point, $query_table)
    {
        $this->validate_method($method);

        _crwd_Js_Renderer::get_instance()->draw_js_include();

        if ($method == 'pixel')
        {
            _crwd_Js_Renderer::get_instance()
                ->draw_pixel_request($this->get_signed_query_string(
                                                $end_point,
                                                $query_table));
        }
        else
        {
            throw new crwd_Exception('pixel is the only method currently '
                                     . 'available for JS requests');
        }
    }

    final protected function exec_request($method,
                                          $return_format,
                                          $end_point,
                                          $query_table)
    {
        $this->validate_method($method);
        $this->validate_return_format($return_format);

        $url = $this->get_end_point_base($return_format);
        if ($method == 'GET')
        {
            $url .= "?" . $this->get_signed_query_string($end_point,
                                                         $query_table);
        }

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($handle, CURLOPT_TIMEOUT, 15);

        if ($method == 'GET')
        {
            curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'GET');
        }
        else if ($method == 'POST')
        {
            curl_setopt($handle, CURLOPT_POST, 1);
            curl_setopt($handle,
                        CURLOPT_POSTFIELDS,
                        $this->get_signed_query_string($end_point,
                                                       $query_table));
        }

        $response = curl_exec($handle);

        curl_close($handle);
        $handle = null;

        return $response;
    }

    final protected function get_activities_get_end_point_name()
    {
        return 'activities-get';
    }

    final protected function get_activities_get_user_end_points_name()
    {
        return 'activities-get-user';
    }

    final protected function get_auth_sign_in_end_point_name()
    {
        return 'auth-sign-in';
    }

    final protected function get_auth_sign_out_end_point_name()
    {
        return 'auth-sign-out';
    }

    final protected function get_end_point_base($return_format)
    {
        $this->validate_return_format($return_format);

        return $this->request_scheme
               . '://' . $this->get_api_domain()
               . self::API_PATH . "/$return_format";
    }

    final protected function get_lang_set_end_point_name()
    {
        return 'lang-set';
    }

    final protected function get_leaderboard_get_end_point_name()
    {
        return 'leaderboard-get';
    }

    final protected function get_location_non_us_verify_end_point_name()
    {
        return 'location-non-us-verify';
    }

    final protected function get_location_us_verify_end_point_name()
    {
        return 'location-us-verify';
    }

    final protected function get_purchase_create_end_point_name()
    {
        return 'purchase-create';
    }

    final protected function get_redemption_create_end_point_name()
    {
        return 'redemption-create';
    }

    final protected function get_share_facebook_end_point_name()
    {
        return 'share-facebook';
    }

    final protected function get_share_twitter_end_point_name()
    {
        return 'share-twitter';
    }

    final protected function get_user_activity_end_point_name()
    {
        return 'user-activity';
    }

    final protected function get_user_create_end_point_name()
    {
        return 'user-create';
    }

    final protected function get_user_delete_end_point_name()
    {
        return 'user-delete';
    }

    final protected function get_user_get_end_point_name()
    {
        return 'user-get';
    }

    final protected function get_user_points_summary_end_point_name()
    {
        return 'user-points-summary';
    }

    final protected function get_user_ranking_summary_end_point_name()
    {
        return 'user-ranking-summary';
    }

    final protected function get_user_update_end_point_name()
    {
        return 'user-update';
    }

    final protected function validate_pagination($start, $finish, $max)
    {
        $diff = abs($finish - $start);
        if ($diff > $max)
        {
            throw new crwd_Exception('failed to validate pagination; the '
                                     . 'number of items you are trying to '
                                     . 'retrieve is greater than maximum '
                                     . 'allowed');
        }
    }

    final protected function validate_update_user_object(crwd_User $user)
    {
        $user_id = $user->get_user_id();
        if (empty($user_id))
        {
            throw new crwd_Exception('cannot update user; no user ID was '
                                     . 'provided');
        }
    }

    // +-----------------+
    // | Private Methods |
    // +-----------------+

    private function get_api_domain()
    {
        if (function_exists('co_get_server_environment'))
        {
            return (co_get_server_environment() !=
                    co_get_production_env_name() ?
                        co_get_server_environment() . '-' : '')
                   . self::API_DOMAIN;
        }
        else
        {
            return self::API_DOMAIN;
        }
    }

    private function get_signature($query_table)
    {
        $signature = '';
        foreach ($query_table as $key => $value)
        {
            $signature .= $key . '=' . $value;
        }
        $signature .= $this->secret;

        return md5($signature);
    }

    private function validate_method($method)
    {
        if (!in_array($method, array('GET', 'pixel', 'POST')))
        {
            throw new crwd_Exception('failed to validate method; method must '
                                     . 'be either GET, pixel or POST');
        }
    }

    private function validate_return_format($return_format)
    {
        if ($return_format != 'json' && $return_format != 'pixel')
        {
            throw new crwd_Exception('failed to validate return format; return '
                                     . 'format must be either json or pixel');
        }
    }
}

//
// +----------------------------+
// | _crwd_Transmittable_Entity |
// +----------------------------+
//

/**
 * Describes an entity that can be sent to the API either by using a remote
 * procedure call (RPC) or Javascript (JS) asynchronous request.
 */
abstract class _crwd_Transmittable_Entity
{
    private $settable_vars;
    protected $data;

    function __construct()
    {
        $this->settable_vars = $this->get_settable_vars();
        $this->data          = array();
    }

    // +------------------+
    // | Abstract Methods |
    // +------------------+

    abstract public function get_settable_vars();

    abstract public function validate();

    // +----------------+
    // | Public Methods |
    // +----------------+

    final public function __call($name, $args)
    {
        if (!preg_match('/^(get|set)_(.*)$/', $name, $matches))
        {
            throw new crwd_Exception('cannot call method; unrecognized method "'
                                     . $name . '"');
        }

        $this->validate_var_name($matches[ 2 ]);

        if ($matches[ 1 ] == 'get')
        {
            return array_key_exists($matches[ 2 ], $this->data) ?
                    $this->data[ $matches[ 2 ] ] : null;
        }
        else
        {
            $this->data[ $matches[ 2 ] ] = $args[ 0 ];
            return $this;
        }
    }

    final public function get_query_table()
    {
        $this->validate();

        return $this->data;
    }

    // +-----------------+
    // | Private Methods |
    // +-----------------+

    private function validate_var_name($name)
    {
        if (!isset($this->settable_vars[ $name ]))
        {
            throw new crwd_Exception('failed to validate variable name; '
                                     . 'unrecognized variable "'. $name . '"');
        }
    }
}

//
// +-------------------+
// | _crwd_Js_Renderer |
// +-------------------+
//

class _crwd_Js_Renderer
{
    const JS_DOMAIN = 'resources.crowdtwist.com';
    const JS_PATH   = '/js/api/v1/api.js';

    private static $instance;
    private $js_include_rendered;
    private $request_scheme;

    static function get_instance()
    {
        if (!isset(self::$instance))
        {
            self::$instance = new _crwd_Js_Renderer();
        }

        return self::$instance;
    }

    function __construct()
    {
        $this->reset();
    }

    // +----------------+
    // | Public Methods |
    // +----------------+

    final public function draw_js_include()
    {
        if ($this->js_include_rendered)
        {
            return;
        }
?>
<script type="text/javascript" src="<?= $this->request_scheme ?>://<?= $this->get_js_domain() . self::JS_PATH ?>?z=<?= gmdate("Y-m-d") ?>">
</script>
<?
        $this->js_include_rendered = true;
    }

    final public function draw_pixel_request($query_string)
    {
?>
<script type="text/javascript">
    try
    {
        var a = new _crwd_api();

        a.pixel('<?= $query_string ?>');
    }
    catch (err) {}
</script>
<?
    }

    final public function enable_ssl()
    {
        $this->request_scheme = 'https';
        return $this;
    }

    final public function reset()
    {
        $this->js_include_rendered = false;
        $this->request_scheme      = 'http';
    }

    // +-----------------+
    // | Private Methods |
    // +-----------------+

    private function get_js_domain()
    {
        if (function_exists('co_get_server_environment'))
        {
            return (co_get_server_environment() !=
                    co_get_production_env_name() ?
                        co_get_server_environment() . '-' : '')
                   . self::JS_DOMAIN;
        }
        else
        {
            return self::JS_DOMAIN;
        }
    }
}
?>
