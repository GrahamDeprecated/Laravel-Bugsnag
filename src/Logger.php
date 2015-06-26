<?php

/*
 * This file is part of Alt Three Bugsnag.
 *
 * (c) Alt Three LTD <support@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AltThree\Bugsnag;

use Bugsnag_Client as Bugsnag;
use Exception;
use GrahamCampbell\LoggerCore\LoggerTrait;
use Psr\Log\LoggerInterface;

/**
 * This is the logger class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class Logger implements LoggerInterface
{
    use LoggerTrait;

    /**
     * The bugsnag client instance.
     *
     * @var \Bugsnag_Client
     */
    protected $bugsnag;

    /**
     * Create a new logger instance.
     *
     * @param \Bugsnag_Client $bugsnag
     *
     * @return void
     */
    public function __construct(Bugsnag $bugsnag)
    {
        $this->bugsnag = $bugsnag;
    }

    /**
     * Log a message to the logs.
     *
     * @param string $level
     * @param mixed  $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        $severity = $this->getSeverity($level);

        if ($message instanceof Exception) {
            $this->bugsnag->notifyException($message, array_except($context, ['title']), $severity);
        } else {
            $msg = $this->formatMessage($message);
            $title = array_get($context, 'title', str_limit((string) $msg));
            $this->bugsnag->notifyError($title, $msg, array_except($context, ['title']), $severity);
        }
    }

    /**
     * Get the severity for the logger.
     *
     * @param string $level
     *
     * @return string
     */
    protected function getSeverity($level)
    {
        switch ($level) {
            case 'warning':
            case 'notice':
                return 'warning';
            case 'info':
            case 'debug':
                return 'info';
            default:
                return 'error';
        }
    }
}
