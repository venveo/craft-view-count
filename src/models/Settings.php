<?php
/**
 * Popular Entries plugin for Craft CMS 3.x
 *
 * Keep track of view counts over a period of time to show popular entries.
 *
 * @link      https://venveo.com
 * @copyright Copyright (c) 2018 Venveo
 */

namespace venveo\viewcount\models;

use craft\base\Model;

/**
 * @author    Venveo
 * @package   ViewCount
 * @since     1.0.0
 */
class Settings extends Model
{

    public $ignoreLoggedInUsers;

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['ignoreLoggedInUsers', 'boolean'],
            ['ignoreLoggedInUsers', 'default', false]
        ];
    }
}
