<?php
/**
 * View Count plugin for Craft CMS 3.x
 *
 * Keep track of view counts over a period of time to show popular entries.
 *
 * @link      https://venveo.com
 * @copyright Copyright (c) 2018 Venveo
 */

namespace venveo\viewcount\migrations;

use Craft;
use craft\db\Migration;

/**
 * @author    Venveo
 * @package   viewcount
 * @since     1.0.0
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;

    // Public Methods
    // =========================================================================

    /**
     * This method contains the logic to be executed when applying this migration.
     * This method differs from [[up()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[up()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            $this->createIndexes();
            $this->addForeignKeys();
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
        }

        return true;
    }

    /**
     * This method contains the logic to be executed when removing this migration.
     * This method differs from [[down()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[down()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeDown()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates the tables needed for the Records used by the plugin
     *
     * @return bool
     */
    protected function createTables()
    {
        $tablesCreated = false;

        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%viewcount_elementcount}}');
        if ($tableSchema === null) {
            $tablesCreated = true;
            $this->createTable(
                '{{%viewcount_elementcount}}',
                [
                    'id' => $this->primaryKey(),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'uid' => $this->uid(),

                    'day' => $this->dateTime()->notNull(),

                    'siteId' => $this->integer()->notNull(),
                    'elementId' => $this->integer()->notNull(),
                    'count' => $this->integer()->notNull()->defaultValue(0),
                ]
            );
        }

        return $tablesCreated;
    }

    /**
     * Creates the indexes needed for the Records used by the plugin
     *
     * @return void
     */
    protected function createIndexes()
    {
        $this->createIndex(
            $this->db->getIndexName(
                '{{%viewcount_elementcount}}',
                ['elementId','day']
            ),
            '{{%viewcount_elementcount}}',
            ['elementId', 'day'],
            true
        );

        $this->createIndex(
            $this->db->getIndexName(
                '{{%viewcount_elementcount}}',
                'elementId'
            ),
            '{{%viewcount_elementcount}}',
            'elementId',
            false
        );

        $this->createIndex(
            $this->db->getIndexName(
                '{{%viewcount_elementcount}}',
                'count'
            ),
            '{{%viewcount_elementcount}}',
            'count',
            false
        );
    }

    /**
     * Creates the foreign keys needed for the Records used by the plugin
     *
     * @return void
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey(
            $this->db->getForeignKeyName('{{%viewcount_elementcount}}', 'siteId'),
            '{{%viewcount_elementcount}}',
            'siteId',
            '{{%sites}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName('{{%viewcount_elementcount}}', 'elementId'),
            '{{%viewcount_elementcount}}',
            'elementId',
            '{{%elements}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }
    /**
     * Removes the tables needed for the Records used by the plugin
     *
     * @return void
     */
    protected function removeTables()
    {
        $this->dropTableIfExists('{{%viewcount_elementcount}}');
    }
}
