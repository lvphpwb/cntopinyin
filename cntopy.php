<?php
function transform($text){
    $start = 19968;
    //note 获取字典文件
    $shm_key = ftok(__FILE__, 'a');
    $shm_id = shmop_open($shm_key, "c", 0666, 125416);
    $data = shmop_read($shm_id, 0, 125416);
    if($data[0] != 'y'){
        $data = file_get_contents("py.txt");
        shmop_write($shm_id, $data, 0);
    }
    if (empty($text) || !is_string($text)){
        return false;
    }
    $str = json_encode($text);
    preg_match_all('/\\\\u([0-f]{4})/', $str, $match);
    foreach ($match[1] as $one){
        $tmp = hexdec($one);
        $offset = ($tmp - $start)*6;
        $res = substr($data, $offset, 6);
        $str = str_replace('\u' . $one, trim($res), $str);
    }
    return json_decode($str);
}

$data = transform("规划局就快了");
echo $data;