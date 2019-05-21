<?php

namespace venveo\viewcount\variables;

use venveo\viewcount\ViewCount as Plugin;

class ViewCountVariable {

    public function increment($elementId) {
        $siteId = \Craft::$app->getSites()->getCurrentSite()->id;
        Plugin::$plugin->service->increment($elementId, $siteId);
    }

    public function recentEntries($interval = 'P2W') {
        return Plugin::$plugin->service->queryRecentEntries($interval);
    }
}
