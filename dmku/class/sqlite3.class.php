<?php
class sql
{
    public static $sql;

    function __construct()
    {
        global $_config;
        self::数据库连接($_config['数据库']['地址']);
    }

    private static function 数据库连接($path)
    {
        try {
            $sqlite = new SQLite3($path, SQLITE3_OPEN_READWRITE);
            $sqlite->enableExceptions(true);
            self::$sql = $sqlite;
        } catch (Exception $e) {
            showmessage(-1, '数据库错误:' . $e->getMessage());
        }
        //SQLite3::escapeString($string);
    }

    public static function 插入_弹幕($data)
    {
        try {
            $stmt = self::$sql->prepare("INSERT OR IGNORE INTO danmaku_list (id, type, text, color, videotime, time) VALUES (:id, :type, :text, :color, :videotime, :time)");
            $stmt->bindValue(':id', $data['id']);
            $stmt->bindValue(':type', $data['type']);
            $stmt->bindValue(':text', $data['text']);
            $stmt->bindValue(':color', $data['color']);
            $stmt->bindValue(':videotime', $data['time']);
            @$stmt->bindValue(':time', time());
            $stmt->execute();
        } catch (Exception $e) {
            showmessage(-1, '数据库错误:' . $e->getMessage());
        }
    }

    public static function 插入_发送弹幕次数($ip)
    {
        try {
            $stmt = self::$sql->prepare("INSERT OR IGNORE INTO danmaku_ip (ip, time) VALUES (:ip, :time)");
            $stmt->bindValue(':ip', $ip);
            @$stmt->bindValue(':time', time());
            $stmt->execute();
        } catch (Exception $e) {
            showmessage(-1, '数据库错误:' . $e->getMessage());
        }
    }

    public static function 查询_弹幕池($id)
    {
        try {
            $stmt = self::$sql->prepare("SELECT * FROM danmaku_list WHERE id=:id");
            $stmt->bindValue(':id', $id);
            $data = $stmt->execute();
            $data = self::fetchAll($data);
            return $data;
        } catch (Exception $e) {
            showmessage(-1, '数据库错误:' . $e->getMessage());
        }
    }

    public static function 查询_发送弹幕次数($ip)
    {
        try {
            $stmt = self::$sql->prepare("SELECT * FROM danmaku_ip WHERE ip=:ip LIMIT 1");
            $stmt->bindValue(':ip', $ip);
            $stmt->execute();
            $data = $stmt->execute();
            $data = self::fetchAll($data);
            return $data;
        } catch (Exception $e) {
            showmessage(-1, '数据库错误:' . $e->getMessage());
        }
    }

    public static function 更新_发送弹幕次数($ip, $time = 'time')
    {
        try {
            $query = "UPDATE danmaku_ip SET c=c+1,time=$time WHERE ip = :ip";
            if (is_int($time)) $query = "UPDATE danmaku_ip SET c=1,time=$time WHERE ip = :ip";
            $stmt = self::$sql->prepare($query);

            $stmt->bindValue(':ip', $ip);
            $stmt->execute();
        } catch (Exception $e) {
            showmessage(-1, '数据库错误:' . $e->getMessage());
        }
    }

    private static function fetchAll($obj)
    {
        $data = [];
        while ($arr = $obj->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $arr;
        }
        return $data;
    }
}
