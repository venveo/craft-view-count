<?php
/**
 * View Count plugin for Craft CMS 3.x
 *
 * Keep track of view counts over a period of time to show popular entries.
 *
 * @link      https://venveo.com
 * @copyright Copyright (c) 2018 Venveo
 */

namespace venveo\viewcount\services;

use craft\base\Component;
use venveo\viewcount\events\ViewCountEvent;
use venveo\viewcount\records\ElementCount as ElementCountRecord;
use venveo\viewcount\ViewCount as Plugin;

/**
 *
 * @author    Venveo
 * @package   ViewCount
 * @since     1.0.0
 */
class ViewCount extends Component
{
    public const EVENT_REGISTER_VIEW = 'EVENT_REGISTER_VIEW';

    /**
     * Increments the number of views on an element
     *
     * @param int $elementId
     * @param int $siteId
     */
    public function increment($elementId, $siteId): void
    {
        if (!$this->shouldIncrement($elementId, $siteId)) {
            return;
        }

        /** @var ElementCountRecord $viewCountRecord */
        $viewCountRecord = ElementCountRecord::findOne([
            'elementId' => $elementId,
            'day' => date('Y-m-d'),
            'siteId' => $siteId
        ]);

        if ($viewCountRecord) {
            ++$viewCountRecord->count;
        } else {
            $viewCountRecord = new ElementCountRecord([
                'elementId' => $elementId,
                'day' => date('Y-m-d'),
                'count' => 1,
                'siteId' => $siteId
            ]);
        }

        $viewCountRecord->save();
    }

    /**
     * Determine if we should increment the view count. This gives third-party
     * plugins an opportunity to listen for our event.
     * @param $elementId
     * @param $siteId
     * @return bool
     */
    private function shouldIncrement($elementId, $siteId): bool
    {
        $settings = Plugin::$plugin->getSettings();
        if ($settings->ignoreLoggedInUsers && !\Craft::$app->getUser()->isGuest) {
            return false;
        }

        $event = new ViewCountEvent(['shouldSkip' => false, 'elementId' => $elementId, 'siteId' => $siteId]);
        $this->trigger(self::EVENT_REGISTER_VIEW, $event);
        if ($event->shouldSkip) {
            return false;
        }

        return true;
    }
}
