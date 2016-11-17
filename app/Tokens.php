<?php
    /*
        The MIT License (MIT)

        Copyright (c) 2014 Julian Xhokaxhiu

        Permission is hereby granted, free of charge, to any person obtaining a copy of
        this software and associated documentation files (the "Software"), to deal in
        the Software without restriction, including without limitation the rights to
        use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
        the Software, and to permit persons to whom the Software is furnished to do so,
        subject to the following conditions:

        The above copyright notice and this permission notice shall be included in all
        copies or substantial portions of the Software.

        THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
        IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
        FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
        COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
        IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
        CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
    */

    class Token {

        public $channel = '';
        public $filename = '';
        public $url = '';
        public $changes = '';
        public $api_level = 0;
        public $incremental = '';
        public $timestamp = 0;
        public $md5sum = '';

        public function __construct($fileName, $physicalPath, $device, $channel, $version) {
            $this->channel = $channel;
            $this->filename = $fileName;
            $filePath = $physicalPath.'/'.$fileName;
            $this->mcCacheProps($filePath, $device, $channel, $version);
        }

        private function mcCacheProps($filePath, $device, $channel, $version) {
            $mc = Flight::mc();
            $cache = $mc->get($filePath);
            if (true) {
                $buildpropArray = explode("\n", file_get_contents($filePath.'.build.prop'));
                if ($device == $this->getBuildPropValue($buildpropArray, 'ro.product.device') ||
                    $device == $this->getBuildPropValue($buildpropArray, 'ro.cm.device')) {
                    $api_level = intval($this->getBuildPropValue($buildpropArray, 'ro.build.version.sdk'));
                    $incremental = $this->getBuildPropValue($buildpropArray, 'ro.build.version.incremental');
                    $timestamp = intval($this->getBuildPropValue($buildpropArray, 'ro.build.date.utc'));
                    $arr = explode("/", $filePath);
                    $url = 'http://download.invisiblek.org/roms/' . $version . '/' . $device . '/' . end($arr);
                    $cache = array($device, $api_level, $incremental, $timestamp, Utils::getMD5($filePath), $url);
                    $mc->set($filePath, $cache);
                    $mc->set($incremental, array($device, $channel, $filePath));
                } else {
                    throw new Exception("$device: $filePath is in invalid path");
                }
            }
            assert($cache[0] == $device);
            $this->api_level = $cache[1];
            $this->incremental = $cache[2];
            $this->timestamp = $cache[3];
            $this->md5sum = $cache[4];
            $this->url = $cache[5];
        }

        private function getBuildPropValue($buildProp, $key) {
            foreach ($buildProp as $line) {
                if (!empty($line) && strncmp($line, '#', 1) != 0) {
                    if (strpos($line, $key) !== false) {
                        list($k, $v) = explode('=', $line, 2);
                        if ($k == $key)
                            return $v;
                    }
                }
            }
            return '';
        }
    };
