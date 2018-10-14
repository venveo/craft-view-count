<?php
/**
 * Popular Entries plugin for Craft CMS 3.x
 *
 * Keep track of view counts over a period of time to show popular entries.
 *
 * @link      https://venveo.com
 * @copyright Copyright (c) 2018 Venveo
 */

namespace venveo\viewcount\records;

use venveo\viewcount\ViewCount;

use Craft;
use craft\db\ActiveRecord;

/**
 * ElementCount Record
 *
 *
 * @author    Venveo
 * @package   ViewCount
 * @since     1.0.0
 * @property int $id
 * @property int $siteId
 * @property int elementId
 * @property int count
 * @property \DateTime day
 */
class ElementCount extends ActiveRecord
{
    // Public Static Methods
    // =========================================================================

     /**
     * @return string the table name
     */
    public static function tableName()
    {
        return '{{%viewcount_elementcount}}';
    }
}
