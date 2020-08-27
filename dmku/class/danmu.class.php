<?php

class danmu
{
    public function 添加弹幕($data = [])
    {
        if (empty($data)) showmessage(-1, '参数错误');
        if (empty($data['id'])) {
            $data['id'] = $data['player'];
        } else {
            showmessage(-1, []);
        }
        //if (empty($data['author'])) showmessage(-1,'参数错误'); 暂时无用
        if (!is_float($data['time']) and !is_int($data['time'])) showmessage(-1, '参数错误');
        if (empty($data['text'])) showmessage(-1, '参数错误');
        // if (!is_int($data['color'])) showmessage(-1,'参数错误');
        // if ($data['type'] < 0 or $data['type'] > 3) showmessage(-1,'参数错误');

        sql::插入_弹幕($data);
    }

    public function 删除弹幕($id)
    {
        //sql::插入_弹幕($data);
        sql::删除_弹幕数据($id);
    }

    public function 弹幕池($id)
    {
        $data = sql::查询_弹幕池($id);
        //print_r($data);
        if (empty($data)) return null;

        $arr = [];
        foreach ($data as $k => $v) {
            // 请不要随意调换下列数组赋值顺序
            $arr[$k][] = (float) $v['videotime'];  //弹幕出现时间(s)
            $arr[$k][] = (string) $v['type'];   //弹幕样式  
            $arr[$k][] = (string) $v['color']; //字体的颜色
            $arr[$k][] = (string) $v['cid'];  //现在是弹幕id，以后可能是发送者id了
            $arr[$k][] = (string) $v['text'];  //弹幕文本
            $arr[$k][] = (string) $v['ip'];  //弹幕ip
            //$arr[$k][] = (string)$v['time'];  //弹幕系统时间
            $arr[$k][] = $date = date('m-d H:i', $v['time']);  //弹幕系统时间
            $arr[$k][] = (string) $v['size'];  //弹幕系统大小
        }

        return $arr;
    }
    public function 搜索弹幕($key)
    {
        $data = sql::搜索_弹幕池($key);
        //print_r($data);
        if (empty($data)) return null;

        $arr = [];
        foreach ($data as $k => $v) {
            // 请不要随意调换下列数组赋值顺序
            $arr[$k][] = (string) $v['id'];  //弹幕id
            $arr[$k][] = (float) $v['videotime'];  //弹幕出现时间(s)
            $arr[$k][] = (string) $v['type'];   //弹幕样式  
            $arr[$k][] = (string) $v['color']; //字体的颜色
            $arr[$k][] = (string) $v['cid'];  //现在是弹幕id，以后可能是发送者id了
            $arr[$k][] = (string) $v['text'];  //弹幕文本
            $arr[$k][] = (string) $v['ip'];  //弹幕ip
            //$arr[$k][] = (string)$v['time'];  //弹幕系统时间
            $arr[$k][] = $date = date('m-d H:i', $v['time']);  //弹幕系统时间
            $arr[$k][] = (string) $v['size'];  //弹幕系统大小
        }

        return $arr;
    }
    public function 弹幕列表()
    {
        $data = sql::显示_弹幕列表();
        //print_r($data);
        if (empty($data)) return null;

        $arr = [];
        foreach ($data as $k => $v) {
            // 请不要随意调换下列数组赋值顺序
            $arr[$k][] = (string) $v['id'];  //弹幕id
            $arr[$k][] = (float) $v['videotime'];  //弹幕出现时间(s)
            $arr[$k][] = (string) $v['type'];   //弹幕样式  
            $arr[$k][] = (string) $v['color']; //字体的颜色
            $arr[$k][] = (string) $v['cid'];  //现在是弹幕id，以后可能是发送者id了
            $arr[$k][] = (string) $v['text'];  //弹幕文本
            $arr[$k][] = (string) $v['ip'];  //弹幕ip
            //$arr[$k][] = (string)$v['time'];  //弹幕系统时间
            $arr[$k][] = $date = date('m-d H:i', $v['time']);  //弹幕系统时间
            $arr[$k][] = (string) $v['size'];  //弹幕系统大小
        }

        return $arr;
    }
    public function 举报列表()
    {
        $data = sql::显示_举报列表();
        //print_r($data);
        if (empty($data)) return null;

        $arr = [];
        foreach ($data as $k => $v) {
            // 请不要随意调换下列数组赋值顺序
            $arr[$k][] = (string) $v['id'];  //弹幕id
            $arr[$k][] = (string) $v['type'];   //弹幕样式  
            $arr[$k][] = (string) $v['cid'];  //现在是弹幕id，以后可能是发送者id了
            $arr[$k][] = (string) $v['text'];  //弹幕文本
            $arr[$k][] = (string) $v['ip'];  //弹幕ip
            $arr[$k][] = $date = date('m-d H:i', $v['time']);  //弹幕系统时间
        }

        return $arr;
    }
    //public function 删除_弹幕数据(){
    //sql::举报_弹幕();

    //}
    public function 举报弹幕($id)
    {
        sql::举报_弹幕($id);
    }
    public function 编辑弹幕($cid)
    {
        sql::编辑_弹幕($cid);
    }

    function __destruct()
    {
        global $_config;
        $type = $_config['数据库']['方式'];
        if ($type === 'pdo') sql::$sql = null;
        if ($type === 'sqlite3' or $type === 'mysqli') sql::$sql->close();
    }
}
