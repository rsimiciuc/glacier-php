<?php

/**
 * Copyright 2010-2013 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 * http://aws.amazon.com/apache2.0
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

namespace Application\Module;

use Aws\Glacier\Model\MultipartUpload\UploadPartGenerator;
use Guzzle\Common\Event;
use Guzzle\Http\Message\EntityEnclosingRequest;
use Guzzle\Service\Command\AbstractCommand;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Adds the content sha256 and tree hash to Glacier upload requests if not set
 */
class GlacierClientListener implements EventSubscriberInterface {

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents() {
        return array(
            "client.create_request" => array('logging'),
            "client.command.create" => array('logging'),
            "command.before_prepare" => array('logging'),
            "command.after_prepare" => array('logging'),
            "command.before_send" => array('logging'),
            "command.after_send" => array('logging'),
            "command.parse_response" => array('logging'),
            "client.region_changed" => array('logging'),
            "client.credentials_changed" => array('logging')
        );
    }

    public function logging(Event $event) {
        echo $event->getName() . PHP_EOL;
    }

    /**
     * Retrieve bodies passed in as UploadPartContext objects and set the real hash, length, etc. values on the command
     *
     * @param Event $event Event emitted
     */
    public function onCommandBeforeSend(Event $event) {
        echo 'asdadad ' . PHP_EOL;
        //var_dump($event['command']);
        /** @var $command AbstractCommand */
        //$command = $event['command'];
        /** @var $request EntityEnclosingRequest */
        //$request = $command->getRequest();
        //var_dump($request->getState());

        /*
          $contentHash = $command->get('ContentSHA256');
          if ($contentHash === true) {

          $request = $command->getRequest();
          $upload = UploadPartGenerator::createSingleUploadPart($request->getBody());
          $request->addHeader('x-amz-content-sha256', $upload->getContentHash());
          if (!$command->get('checksum')) {
          $request->addHeader('x-amz-sha256-tree-hash', $upload->getChecksum());
          }
          } elseif (is_string($contentHash)) {

          $request->addHeader('x-amz-content-sha256', $contentHash);
          }
         *
         */
    }

}

