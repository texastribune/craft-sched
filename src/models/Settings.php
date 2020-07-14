<?php
/**
 * Sched API Integration plugin for Craft CMS 3.x
 *
 * Integration with Sched API and Craft CMS
 *
 * @link      http://julianmjones.com
 * @copyright Copyright (c) 2018 Julian Jones
 */

namespace julianmjones\schedapiintegration\models;

use julianmjones\schedapiintegration\SchedApiIntegration;

use Craft;
use craft\base\Model;

/**
 * SchedApiIntegration Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Julian Jones
 * @package   SchedApiIntegration
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some field model attribute
     *
     * @var string
     */
    public $schedApiKey = '';
    public $conferenceId = '';

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
            // the name, email, subject and body attributes are required
            [['schedApiKey', 'conferenceId'], 'required'],
            ['schedApiKey', 'string'],
            ['schedApiKey', 'default', 'value' => ''],
        ];
    }
}
