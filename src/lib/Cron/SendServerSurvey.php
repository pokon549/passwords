<?php
/**
 * This file is part of the Passwords App
 * created by Marius David Wieschollek
 * and licensed under the AGPL.
 */

namespace OCA\Passwords\Cron;

use OCA\Passwords\Helper\Http\RequestHelper;
use OCA\Passwords\Helper\Survey\ServerReportHelper;
use OCA\Passwords\Services\ConfigurationService;
use OCA\Passwords\Services\EnvironmentService;
use OCA\Passwords\Services\LoggingService;
use OCA\Passwords\Services\NotificationService;

/**
 * Class SendServerSurvey
 *
 * @package OCA\Passwords\Cron
 */
class SendServerSurvey extends AbstractCronJob {

    /**
     * @var ConfigurationService
     */
    protected $config;

    /**
     * @var ServerReportHelper
     */
    protected $serverReport;

    /**
     * @var NotificationService
     */
    protected $notifications;

    /**
     * @var float|int
     */
    protected $interval = 604800;

    /**
     * SendServerSurvey constructor.
     *
     * @param LoggingService       $logger
     * @param ConfigurationService $config
     * @param EnvironmentService   $environment
     * @param ServerReportHelper   $serverReport
     * @param NotificationService  $notifications
     */
    public function __construct(
        LoggingService $logger,
        ConfigurationService $config,
        EnvironmentService $environment,
        ServerReportHelper $serverReport,
        NotificationService $notifications
    ) {
        parent::__construct($logger, $environment);
        $this->serverReport  = $serverReport;
        $this->config        = $config;
        $this->notifications = $notifications;
    }

    /**
     * @param $argument
     *
     * @throws \Exception
     */
    protected function runJob($argument): void {
        $mode = $this->getReportMode();

        if($mode !== 0) $this->sendReport($mode > 1);
    }

    /**
     * @param bool $enhanced
     *
     * @return void
     */
    protected function sendReport(bool $enhanced): void {
        $this->serverReport->sendReport($enhanced);
    }

    /**
     * @return int
     */
    protected function getReportMode(): int {
        $mode = $this->config->getAppValue('survey/server/mode', -1);
        if($mode === -1) {
            if($this->serverReport->hasData()) $this->sendNotifications();

            return 0;
        }

        return intval($mode);
    }

    /**
     *
     */
    protected function sendNotifications(): void {
        $time = $this->config->getAppValue('survey/server/notification', 0);
        if($time > strtotime('-3 months')) return;

        $adminGroup = \OC::$server->getGroupManager()->get('admin');
        foreach($adminGroup->getUsers() as $admin) {
            $this->notifications->sendSurveyNotification($admin->getUID());
        }
        $this->config->setAppValue('survey/server/notification', time());
    }
}