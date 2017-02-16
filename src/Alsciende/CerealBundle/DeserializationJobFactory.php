<?php

namespace Alsciende\CerealBundle;

/**
 * Description of DeserializationJobFactory
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class DeserializationJobFactory
{

    /** @var Alsciende\CerealBundle\JsonFileEncoder */
    private $encoder;
    
    function __construct ()
    {
        $this->encoder = new JsonFileEncoder();
    }
    
    function create($jsonDataPath, $classname)
    {
        $jobs = [];
        
        $files = $this->encoder->decode($jsonDataPath, $classname);
        
        foreach($files as $file) {
            $jobs[] = new \Alsciende\CerealBundle\DeserializationJob($file[0], $file[1], $classname);
        }
        
        return $jobs;
    }
    
    

}