<?php

$root = dirname(dirname(__FILE__));

return array(
    // Nếu hosting enable SAFE MODE và không ghi được file cache.
    // Sửa 'File' thành 'Session'
    'cache_adapter' => 'File', // 'File' or 'Session'
    
    // Nếu hosting không enable socket, change "false" to "true"
    // để dùng cURL thay thế cho việc upload
    'use_curl' => false,

    // CHMOD to 0777
    'temp_dir'    => $root . '/temp',
    // CHMOD to 0777
    'session_dir' => $root . '/sessions',

    'logo_dir' => $root . '/logos',

    'upload' => array(
        'allow_file_types' => array('jpg', 'jpeg', 'gif', 'png'),
        'max_file_size'    => 8*1024*1024, // 2 * 1024 * 1024 // 2mb
    ),
    /**
     * Hệ thống sẽ watermark vào file có kích cỡ tối thiểu widthxheight
     * VD: 300x200
     * => Chiều rộng của ảnh phải lớn hơn 300px, chiều cao của ảnh phải lớn hơn 200px
     * Nếu muốn ảnh nào cũng watermark thì để trống
     * 'watermark_minimum_size' => '',
     */
    'watermark_minimum_size' => '200x200',

    'options' => array(
        'watermark' => array(
            'label'   => 'Watermark',
            'default' => 1,
            'options' => array(
                1 => 'Yes',
                //0 => 'No', // xóa dòng này nếu bắt buộc user sử dụng watermark
            )
        ),
        'watermark_position' => array(
            'label'   => 'Watermark position',
            'default' => 'br',
            'type' => 'select',
            'options' => array(
                'tl' => 'top-left',
                'tr' => 'top-right',
                'bl' => 'bottom-left',
                'br' => 'bottom-right',
                'mc' => 'middle-center',
                'rd' => 'random'
            ),
        ),
        'watermark_logo' => array(
            'label'   => 'Logo',
            'default' => '1',
            'options' => array(
                '1' => 'Logo script',  // mean {logo_dir}/1.png
            )
        ),
        'resize' => array(
            'label'   => 'Resize',
            'default' => 0,
            'type'    => 'select',
            'options' => array(
                0    => 'Full size',
                100  => '100x',
                150  => '150x',
                320  => '320x',
                640  => '640x',
                800  => '800x',
                1024 => '1024x'
            )
        ),
        'server' => array(
            'label'   => 'Server',
            'default' => 'picasanew',
            'options' => array(
                //'imgur'      => 'Imgur',
                //'flickr'     => 'Flickr',
                //'imageshack' => 'Imageshack',
                //'picasa'     => 'Picasa',
                'postimage'  => 'Postimage',
'picasanew' => 'Picasanew',
            )
        ),
    ),

    'postimage' => array(
        // Không bắt buộc
        'accounts' => array(
            // array(
            //     'username' => 'user1',
            //     'password' => 'pass1',
            // ),
            // array(
            //     'username' => 'user1',
            //     'password' => 'pass1',
            // ),
        ),
    ),

    'imageshack' => array(
        /**
         * Bắt buộc - phải có ít nhất 1
         * Register: {@link https://imageshack.com/contact/api}.
         */
        'api_keys' => array(
            'your API key here',
            // 'other API',
            // 'other API',
        ),
        /**
         * Bắt buộc - phải có ít nhất 1
         */
        'accounts' => array(
            array(
                'username' => 'your username',
                'password' => 'your password',
            ),
            /**
             * Có thể thêm nhiều account khác theo mẫu tương tự bên dưới
             */
            // array(
            //     'username' => 'user2',
            //     'password' => 'pass2',
            // ),
        ),
    ),
    'imgur' => array(
        /**
         * Không bắt buộc nhưng nên có để tránh bị giới hạn upload.
         */
        'accounts' => array(
            array(
                'username' => 'your username',
                'password' => 'your password',
            ),
            /**
             * Có thể thêm nhiều account khác theo mẫu tương tự bên dưới
             */
            // array(
            //     'username' => 'user2',
            //     'password' => 'pass2',
            // ),
        ),
    ),

    'picasa' => array(
        /**
         * Bắt buộc - phải có ít nhất 1
         */
        'accounts' => array(
            array(
                'username' => 'phimletv20151@gmail.com',
                'password' => 'duynghia119494',
                /**
                 * Không bắt buộc, nhưng nếu không có thì hệ thống sẽ tự upload vào album "default".
                 * Có thể dùng nhiều album id, hệ thống sẽ tự lấy random mỗi lần upload
                 */
                'album_ids' => array(
                ),
            ),
            // Có thể thêm nhiều account khác theo mẫu tương tự bên dưới
            // array(
                // 'username' => 'user2',
                // 'password' => 'pass2',
                // 'album_ids' => array(
                // ),
            // ),
        ),
    ),

    'picasanew' => array(
        /**
         * Bắt buộc - phải có ít nhất 1
         */
        'accounts' => array(
            array(
                'username' => 'phimletv20151@gmail.com',
                'password' => 'phimletv123',
                /**
                 * Không bắt buộc, nhưng nếu không có thì hệ thống sẽ tự upload vào album "default".
                 * Có thể dùng nhiều album id, hệ thống sẽ tự lấy random mỗi lần upload
                 */
                'album_ids' => array('6237846539649216657'
                ),
            ),
            // Có thể thêm nhiều account khác theo mẫu tương tự bên dưới
            // array(
                // 'username' => 'user2',
                // 'password' => 'pass2',
                // 'album_ids' => array(
                // ),
            // ),
        ),
'API' => array('ID' => '108988370496-bo76qqd5vtp9gkdd6km8kg014ge7b1td.apps.googleusercontent.com',
'secret' => 'jxaq9apfgIHKnA2iHhpLWxmK'),
    ),

    'flickr' => array(
        /**
         * Có thể sử dụng TOKEN hoặc ACCOUNT để upload lên Flickr.
         * Khuyến cáo nên sử dụng TOKEN để đảm bảo tính ổn định.
         * Vì đôi khi sử dụng tự động đăng nhập sẽ bị yahoo yêu cầu nhập captcha -> không vượt qua được.
         *
         * Nếu sử dụng TOKEN thì khai báo ở file bên dưới hoặc chạy script "get_flickr_token.php".
         * Code sẽ tự động lấy token và lưu vào file này.
         * Khuyến cáo nên sử dụng script tự động.
         *
         * Mỗi TOKEN tương ứng với 1 ACCOUNT (hiện tại 1 account có 1T free cho việc upload)
         */

        // CHMOD to 0777.
        'token_file' => $root . '/includes/flickr_token.php',

        /**
         * Bắt buộc - phải có ít nhất 1
         * Có thể sử dụng nhiều API, nhưng 1 cái cũng có thể dùng cho nhiều TOKENs, nhiều ACCOUNTs
         * Đăng ký API tại đây {@link https://www.flickr.com/services/apps/create/noncommercial/}
         */
        'api_keys' => array(
            array(
                'key'    => 'your api key',
                'secret' => 'your secret key',
            ),
            /**
             * Có thể thêm nhiều account khác theo mẫu tương tự bên dưới
             */
            // array(
            //     'key'    => 'your value',
            //     'secret' => 'your value',
            // ),
        ),

        /**
         * Sử dụng tài khoản của yahoo để đăng nhập.
         * Hệ thống sẽ cố gắng tự động đăng nhập và save auth_token vào file bên trên.
         * Sử dụng tài khoản - tự động đăng nhập sẽ chậm ở lần đầu tiên, các lần sau sẽ bình thường.
         *
         * Không nên sử dụng account vì xử lý chậm hơn, và đôi khi bị yahoo chặn tự động login -> không thành công,
         * chỉ cần điền API Key ở bên trên, sau đó chạy script domain/get_fickr_token.php
         * là code sẽ tự tạo auth token
        */
        'accounts' => array(
            /**
             * Có thể thêm nhiều account khác theo mẫu tương tự bên dưới
             */
            array(
            ),
        ),
    ),
);