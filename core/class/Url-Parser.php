<?php
class URL_Parser {
    public $path;
    public $query;
    public $query_ar;
    public function __construct() {
        $current_url = $_SERVER['REQUEST_URI'];

        $url_parse = parse_url($current_url);

        $this->path = $url_parse['path'];
        $this->query = $url_parse['query'];
        parse_str($url_parse['query'], $this->query_ar);
    }
}