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
class GlacierUploadListener implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'multipart_upload.before_part_upload' => array('onCommandBeforeSend'),
            'multipart_upload.after_part_upload' => array('onCommandBeforeSend'),
        );
    }

    public function logging(Event $event) {
        var_dump('adasdasd');
    }


    /**
     * Retrieve bodies passed in as UploadPartContext objects and set the real hash, length, etc. values on the command
     *
     * @param Event $event Event emitted
     */
    public function onCommandBeforeSend(Event $event)
    {
        file_put_contents('data/' . md5($event['source']->getUri()), serialize($event['state']));
    }
}

