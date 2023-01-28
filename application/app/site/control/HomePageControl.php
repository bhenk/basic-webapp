<?php

namespace app\site\control;

class HomePageControl extends DefaultPageControl {

    function __construct() {
        parent::__construct(title: "ggoh");
        //parent::__construct(body_contents: $body_contents);
    }

}