<?php
/**
 * @link      https://venveo.com
 * @copyright Copyright (c) 2018 Venveo
 */

namespace venveo\viewcount;

use Craft;
use craft\base\Plugin;
use craft\elements\db\ElementQuery;
use craft\web\twig\variables\CraftVariable;
use craft\web\View;
use venveo\viewcount\models\Settings;
use venveo\viewcount\records\ElementCount;
use venveo\viewcount\services\ViewCount as ViewCountService;
use venveo\viewcount\variables\ViewCountVariable;
use yii\base\Event;
use yii\base\InvalidConfigException;

/**
 * @author    Venveo
 * @package   ViewCount
 * @since     1.0.0
 *
 * @property  Settings $settings
 * @property ViewCountService service
 * @method    Settings getSettings()
 */
class ViewCount extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * ViewCount::$plugin
     *
     * @var ViewCount
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * ViewCount::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $this->setComponents([
            'service' => ViewCountService::class
        ]);


        Event::on(
            ElementQuery::class,
            ElementQuery::EVENT_BEFORE_PREPARE,
            function(Event $event) {
                $sender = $event->sender;
                if ($sender instanceof ElementQuery && !empty($sender->orderBy) && array_key_exists('views', $sender->orderBy)) {
                    $subQuery = $sender->subQuery;
                    $table = ElementCount::tableName();
                    $timestamp = (new \DateTime())->format('Y-m-d 00:00:00');
                    $on = ['and', "{$table}.elementId = [[entries.id]]", 'day = :timestamp'];
                    $subQuery->leftJoin($table, $on, [':timestamp' => $timestamp]);
                    $subQuery->addSelect("{$table}.count as views");
                }
            }
        );

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('viewcount', ViewCountVariable::class);
            }
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be insrted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'view-count/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
