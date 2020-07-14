<?php
/**
 * Sched API Integration plugin for Craft CMS 3.x
 *
 * Integration with Sched API and Craft CMS
 *
 * @link      http://julianmjones.com
 * @copyright Copyright (c) 2018 Julian Jones
 */

namespace julianmjones\schedapiintegration\utilities;

use julianmjones\schedapiintegration\SchedApiIntegration;
use julianmjones\schedapiintegration\assetbundles\schedapiintegrationutilityutility\SchedApiIntegrationUtilityUtilityAsset;

use Craft;
use craft\base\Utility;

/**
 * Sched API Integration Utility
 *
 * Utility is the base class for classes representing Control Panel utilities.
 *
 * https://craftcms.com/docs/plugins/utilities
 *
 * @author    Julian Jones
 * @package   SchedApiIntegration
 * @since     1.0.0
 */
class SchedApiIntegrationUtility extends Utility
{
    // Static
    // =========================================================================

    /**
     * Returns the display name of this utility.
     *
     * @return string The display name of this utility.
     */
    public static function displayName(): string
    {
        return Craft::t('sched-api-integration', 'SchedApiIntegrationUtility');
    }

    /**
     * Returns the utility’s unique identifier.
     *
     * The ID should be in `kebab-case`, as it will be visible in the URL (`admin/utilities/the-handle`).
     *
     * @return string
     */
    public static function id(): string
    {
        return 'schedapiintegration-sched-api-integration-utility';
    }

    /**
     * Returns the path to the utility's SVG icon.
     *
     * @return string|null The path to the utility SVG icon
     */
    public static function iconPath()
    {
        return Craft::getAlias("@julianmjones/schedapiintegration/assetbundles/schedapiintegrationutilityutility/dist/img/SchedApiIntegrationUtility-icon.svg");
    }

    /**
     * Returns the number that should be shown in the utility’s nav item badge.
     *
     * If `0` is returned, no badge will be shown
     *
     * @return int
     */
    public static function badgeCount(): int
    {
        return 0;
    }

    /**
     * Returns the utility's content HTML.
     *
     * @return string
     */
    public static function contentHtml(): string
    {
        Craft::$app->getView()->registerAssetBundle(SchedApiIntegrationUtilityUtilityAsset::class);

        $someVar = 'Have a nice day!';
        return Craft::$app->getView()->renderTemplate(
            'sched-api-integration/_components/utilities/SchedApiIntegrationUtility_content',
            [
                'someVar' => $someVar
            ]
        );
    }
}
