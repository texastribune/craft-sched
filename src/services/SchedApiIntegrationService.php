<?php
/**
 * Sched API Integration plugin for Craft CMS 3.x
 *
 * Integration with Sched API and Craft CMS
 *
 * @link      http://julianmjones.com
 * @copyright Copyright (c) 2018 Julian Jones
 */

namespace julianmjones\schedapiintegration\services;

use julianmjones\schedapiintegration\SchedApiIntegration;

use Craft;
use craft\base\Component;
use GuzzleHttp\Client;
use Yii;

/**
 * SchedApiIntegrationService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Julian Jones
 * @package   SchedApiIntegration
 * @since     1.0.0
 */
class SchedApiIntegrationService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function gets the Sponsors from the Sched API
     *
     * From any other plugin file, call it like this:
     *
     *     SchedApiIntegration::$plugin->schedApiIntegrationService->getSponsors()
     *
     * @return mixed
     */

    public function getSponsors()
    {
        $cache = Yii::$app->cache;
        $guzzleClient = new \GuzzleHttp\Client();
        //Get the attributes for the plugin
        $apiKey = SchedApiIntegration::$plugin->getSettings()->schedApiKey;
        $conferenceId = SchedApiIntegration::$plugin->getSettings()->conferenceId;
        if ($apiKey && $conferenceId) {
            $url = 'https://'.$conferenceId
            .'.sched.com/api/role/export?api_key='.$apiKey
            .'&role=sponsor&format=json&strip_html=Y';
            $response = $guzzleClient->request('POST', $url);
            if($response->getStatusCode() == "200") {
                $json = json_decode($response->getBody(), true);
                return $json;
            } else {
                return false;
            }
        } 
        return false;
    }

    public function getSchedule()
    {
        $key = 'sched_schedule';
        $cache = Yii::$app->cache;
        $data = $cache->get($key);
        if ($data === false) {
            $guzzleClient = new \GuzzleHttp\Client();
            //Get the attributes for the plugin
            $apiKey = SchedApiIntegration::$plugin->getSettings()->schedApiKey;
            $conferenceId = SchedApiIntegration::$plugin->getSettings()->conferenceId;
            if ($apiKey && $conferenceId) {
                $url = 'https://'.$conferenceId
                .'.sched.com/api/session/export?api_key='.$apiKey
                .'&format=json&custom_data=Y';
                $response = $guzzleClient->request('POST', $url);
                if($response->getStatusCode() == "200") {
                    $json = json_decode($response->getBody(), true);
                    $cache->set($key, $json, 60);
                    return $json;
                } else {
                    return false;
                }
            }
            return false;
        } else {
            return $data;
        }
    }

    public function getUser($term, $by = 'username', $fields= "username,name,email,about,url,avatar,role,company,position,location")
    {
        $cache = Yii::$app->cache;
        $guzzleClient = new \GuzzleHttp\Client();
        //Get the attributes for the plugin
        $apiKey = SchedApiIntegration::$plugin->getSettings()->schedApiKey;
        $conferenceId = SchedApiIntegration::$plugin->getSettings()->conferenceId;
        if ($apiKey && $conferenceId) {
            $url = 'https://'.$conferenceId
            .'.sched.com/api/user/get?api_key='.$apiKey
            .'&format=json&by='.$by.'&term='.$term.'&fields='.$fields;
            $response = $guzzleClient->request('POST', $url);
            if($response->getStatusCode() == "200") {
                $json = json_decode($response->getBody(), true);
                return $json;
            } else {
                return false;
            }
        }
        return false;
    }

    public function getRoleExport($role = 'speaker', $fields = '', $stripHtml = true)
    {
        $cache = Yii::$app->cache;
        $guzzleClient = new \GuzzleHttp\Client();
        //Get the attributes for the plugin
        $apiKey = SchedApiIntegration::$plugin->getSettings()->schedApiKey;
        $conferenceId = SchedApiIntegration::$plugin->getSettings()->conferenceId;
        if ($apiKey && $conferenceId) {
            $url = 'https://'.$conferenceId
            .'.sched.com/api/role/export?api_key='.$apiKey
            .'&role='.$role.'&format=json';
            if($stripHtml) {
                $url = $url.'&fstrip_html=Y';
            }
            if($fields) {
                $url = $url.'&fields='.$fields;
            }
            $response = $guzzleClient->request('POST', $url);
            if($response->getStatusCode() == "200") {
                $json = json_decode($response->getBody(), true);
                return $json;
            } else {
                return false;
            }
        } 
        return false;
    }


}
