<?php
/**
 * Sched API Integration plugin for Craft CMS 3.x
 *
 * Integration with Sched API and Craft CMS
 *
 * @link      http://julianmjones.com
 * @copyright Copyright (c) 2018 Julian Jones
 */

namespace julianmjones\schedapiintegration\variables;

use julianmjones\schedapiintegration\SchedApiIntegration;

use Craft;
use Yii;

/**
 * Sched API Integration Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.schedApiIntegration }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Julian Jones
 * @package   SchedApiIntegration
 * @since     1.0.0
 */
class SchedApiIntegrationVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Whatever you want to output to a Twig template can go into a Variable method.
     * You can have as many variable functions as you want.  From any Twig template,
     * call it like this:
     *
     *     {{ craft.schedApiIntegration.exampleVariable }}
     *
     * Or, if your variable requires parameters from Twig:
     *
     *     {{ craft.schedApiIntegration.exampleVariable(twigValue) }}
     *
     * @param null $optional
     * @return string
     */
    public function sponsors($optional = null)
    {
        $result = SchedApiIntegration::$plugin->schedApiIntegrationService->getSponsors();
        return $result;
    }

    public function schedule()
    {
        $result = SchedApiIntegration::$plugin->schedApiIntegrationService->getSchedule();
        return $result;
    }

    public function getUser($term, $by = 'username')
    {
        $speakers = $this->getSpeakers();
        //First, find the speaker in the speakers
        $speakerIndex = array_search($term, array_column($speakers, $by));
        if(false !== $speakerIndex) {
            return $speakers[$speakerIndex];
        }
        return false;
    }

    public function getSpeakers($fields = 'username,name,about,avatar,position,sessions,url')
    {
        $key = 'sched_speakers';
        $cache = Yii::$app->cache;
        $data = $cache->get($key);
        if($data) {
            return $data;
        } else {
            $result = SchedApiIntegration::$plugin->schedApiIntegrationService->getRoleExport('speaker', $fields);
            if($result) {
                $cache->set($key, $result, 120);
                return $result;
            }
            return [];
        }
    }

    public function getSponsors() {
        $key = 'sched_sponsors';
        $cache = Yii::$app->cache;
        $data = $cache->get($key);
        if($data) {
            return $data;
        } else {
            //Use the default fields and don't strip html
            $result = SchedApiIntegration::$plugin->schedApiIntegrationService->getRoleExport('sponsor', null, false);
            if($result) {
                $cache->set($key, $result, 180);
                return $result;
            }
            return [];
        }
    }

    public function getScheduleByUser($user) {
        $schedule = $this->schedule();
        $speakers = $this->getSpeakers();
        $returnSessions = [];
        //First, find the speaker in the speakers
        $speakerIndex = array_search($user, array_column($speakers, 'username'));
        if(false !== $speakerIndex) {
            $speaker = $speakers[$speakerIndex];
            $sessions = $speaker['sessions'];
            if(is_array($sessions)) {
                foreach($sessions as $sessionId) {
                    $sessionIndex = array_search($sessionId, array_column($schedule, 'id'));
                    //If we've found a session, add it to the array
                    if($sessionIndex) {
                        array_push($returnSessions, $schedule[$sessionIndex]);
                    }
                }
            }
        }
        //Sort the returning times
        uasort($returnSessions, function($a, $b) {
            return $a['start_time_ts'] - $b['start_time_ts'];
        });
        return $returnSessions;
    }
}
