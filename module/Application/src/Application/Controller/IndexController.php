<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Aws\Glacier\Model\MultipartUpload\UploadBuilder;
use Aws\Glacier\Model\MultipartUpload\UploadPartGenerator;

class IndexController extends AbstractActionController {

    /**
     *
     * @var \Aws\Glacier\GlacierClient
     */
    protected $_client;

    public function __construct() {
        $config = new \Zend\Config\Reader\Ini();
        $awsconfig = $config->fromFile('config.ini');
        $this->_client = \Aws\Glacier\GlacierClient::factory($awsconfig);
        $this->_client->addSubscriber(new \Application\Module\GlacierClientListener());
        var_dump('constructor');
    }

    public function indexAction() {
        return new ViewModel();
    }

    public function uploadAction() {
        var_dump('upload action');

        $partSize = 8388608;

        $uploader = UploadBuilder::newInstance()
                ->setClient($this->_client)
                ->setSource($this->params('filename'))
                ->setVaultName($this->params('vault'));

        if ($state = @file_get_contents('data/' . md5($this->params('filename')))) {
            $parts = UploadPartGenerator::factory(fopen($this->params('filename'), 'r'), $partSize);
            $uploader->setPartGenerator($parts);
            $uploader->resumeFrom(unserialize($state));
        } else {
            $uploader->setPartSize($partSize);
        }

        $uploader = $uploader->build();

        $uploader->addSubscriber(new \Application\Module\GlacierUploadListener());

        try {
            $result = $uploader->upload();
            $archiveId = $result->get('archiveId');
            die(var_dump($archiveId));
        } catch (\Aws\Common\Exception\MultipartUploadException $e) {
            // If the upload fails, get the state of the upload
            $state = $e->getState();
            die(var_dump($state));
        }

        $archiveId = $result->get('archiveId');
        die(var_dump($archiveId));
    }

}
