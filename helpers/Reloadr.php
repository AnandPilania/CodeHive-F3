<?php
class Reloadr extends Prefab {
    public function init($app) {
        $root_path = base_path();
        $config = $app->get('RELOADR');
        $filter = $config['FILTER'];

        if(!empty($dirs = $config['DIRS']?:array())){
            foreach($dirs as $dir){
                $dir = $root_path.$dir;
                if(file_exists($dir)){
                    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)) as $file){
                        $this->filterExt($file, $filter);
                    }
                }
            }
        }

        if(!empty($files = $config['FILES']?:array())){
            foreach($files as $file){
                $file = $root_path.$file;
                if($file_exists($file)){
                    $this->filterExt($file, $filter);
                }
            }
        }

        return $this->list;
    }

    protected function filterExt($file, array $filter = []) {
        $except = (null !== $filter['EXCEPT'] ? $filter['EXCEPT'] : []);
        $accept = (null !== $filter['ACCEPT'] ? $filter['ACCEPT'] : []);
        
        if(!empty($except)){
            foreach($except as $ext){
                if(pathinfo($file, PATHINFO_EXTENSION) !== strtolower($ext)){
                    $this->list[] = filemtime($file);
                }
            }
        }

        if(!empty($accept)){
            foreach($accept as $ext){
                if(pathinfo($file, PATHINFO_EXTENSION) === strtolower($ext)){
                    $this->list[] = filemtime($file);
                }
            }
        }
    }
}