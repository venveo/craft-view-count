<?php
namespace venveo\viewcount\events;

use yii\base\Event;

class ViewCountEvent extends Event {

    public $elementId;
    public $siteId;
    public $shouldSkip;
}
