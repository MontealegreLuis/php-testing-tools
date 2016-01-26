<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Zf2\Mail;

use InvalidArgumentException;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\File;
use Zend\Mail\Transport\FileOptions;
use Zend\Mail\Transport\SmtpOptions;

class TransportFactory
{
    /**
     * @param array $configuration
     * @return File|Smtp
     * @throws InvalidArgumentException
     */
    public function buildTransport(array $configuration)
    {
        if ($configuration['type'] === 'file') {
            return new File(new FileOptions([
                'path' => $configuration['options']['path'],
                'callback'  => function () {
                    return 'message-' . microtime(true) . '-' . mt_rand() . '.html';
                }
            ]));
        } elseif ($configuration['type'] == 'smtp') {
            return new Smtp(new SmtpOptions([
                'host' => $configuration['options']['host'],
                'port' => $configuration['options']['port'],
            ]));
        }
        throw new InvalidArgumentException(
            "Mail transport type '{$configuration['type']}' is not supported"
        );
    }
}
