<?php
class sql
{
    public static $sql;

    function __construct()
    {
        global $_config;
        self::数据库连接($_config['数据库']['地址'], $_config['数据库']['用户名'], $_config['数据库']['密码'], $_config['数据库']['名称']);
    }

    private static function 数据库连接($hostname, $username, $password, $db)
    {
        $sql = new mysqli($hostname, $username, $password, $db);;
        if ($sql->connect_error) {
            showmessage(-1, '数据库错误:' . $sql->connect_errno . "\n" . $sql->connect_error);
        }
        self::$sql = $sql;
    }

    public static function 插入_弹幕($data)
    {
        try {
            $stmt = self::$sql->prepare("INSERT IGNORE INTO danmaku_list (id, type, text, color, videotime, time) VALUES (?, ?, ?, ?, ?, ?)");
            @$stmt->bind_param('iisidi', $data['id'], $data['type'], $data['text'], $data['color'], $data['time'], time());
            if ($stmt->execute() == false) {
                throw new Exception($stmt->error_list);
            }
            $stmt->close();
        } catch (Exception $e) {
            showmessage(-1, $e->getMessage());
        }
    }

    public static function 插入_发送弹幕次数($ip)
    {
        try {
            $stmt = self::$sql->prepare("INSERT IGNORE INTO danmaku_ip (ip, time) VALUES (?, ?)");
            @$stmt->bind_param('si', $ip, time());
            if ($stmt->execute() == false) {
                throw new Exception($stmt->error_list);
            }
            $stmt->close();
        } catch (Exception $e) {
            showmessage(-1, $e->getMessage());
        }
    }

    public static function 查询_弹幕池($id)
    {
        try {
            $stmt = self::$sql->prepare("SELECT * FROM danmaku_list WHERE id=?");
            $stmt->bind_param('s', $id);
            if ($stmt->execute() == false) {
                throw new Exception($stmt->error_list);
            }
            $data = self::fetchAll($stmt->get_result());
            $stmt->close();
            return $data;
        } catch (Exception $e) {
            showmessage(-1, $e->getMessage());
        }
    }

    public static function 查询_发送弹幕次数($ip)
    {
        try {
            $stmt = self::$sql->prepare("SELECT * FROM danmaku_ip WHERE ip = ? LIMIT 1");
            $stmt->bind_param('s', $ip);
            if ($stmt->execute() == false) {
                throw new Exception($stmt->error_list);
            }
            $data = self::fetchAll($stmt->get_result());
            $stmt->close();
            return $data;
        } catch (Exception $e) {
            showmessage(-1, $e->getMessage());
        }
    }

    public static function 更新_发送弹幕次数($ip, $time = 'time')
    {
        try {
            $query = "UPDATE danmaku_ip SET c=c+1,time=$time WHERE ip = ?";
            if (is_int($time)) $query = "UPDATE danmaku_ip SET c=1,time=$time WHERE ip = ?";
            $stmt = self::$sql->prepare($query);
            $stmt->bind_param('s', $ip);
            if ($stmt->execute() == false) {
                throw new Exception($stmt->error_list);
            }
            $stmt->close();
        } catch (Exception $e) {
            showmessage(-1, $e->getMessage());
        }
    }

    private static function fetchAll($obj)
    {
        $data = [];
        if ($obj->num_rows > 0) {
            while ($arr = $obj->fetch_assoc()) {
                $data[] = $arr;
            }
        }
        $obj->free();
        return $data;
    }
}
