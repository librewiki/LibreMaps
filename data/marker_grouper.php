<?php
class marker_grouper{
    static function counter($_db){
        $_db->query("SET names utf8mb4");
        $group_count = array_fill(0, 100, 0);
        $sql = 'SELECT Groups FROM tb_marker';
        $res = $_db->query($sql);
        while($row = $res->fetch_row()){
            if(substr($row[0],-1)==';'){
                $row[0] = substr($row[0],0,-1);
            }
            $parsed = explode(';',$_db->real_escape_string($row[0]));
            foreach ($parsed as $num) {
                if($group_count[$num] == null){
                    $group_count[$num] = 0;
                }
                ++$group_count[$num];
            }
        }
        return $group_count;
    }
    static function count_and_delete($_db){
        $_db->query("SET names utf8mb4");
        $counted = marker_grouper::counter($_db);
        $sql = 'SELECT idtb_Groups FROM tb_groups';
        $res = $_db->query($sql);
        while($row = $res->fetch_row()){
            if($counted[$row[0]]==0){
                $delsql = 'DELETE FROM tb_groups WHERE idtb_Groups='.$row[0];
                $_db->query($delsql);
            }
        }
    }
    static function input($input_data,$_db){
        $_db->query("SET names utf8mb4");
        //$input_data is unescaped sting, of which token is ";".
        //$_db is mysqli object.
        //return string which consists of int.
        $val = '';
        if(substr($input_data,-1)==';'){
            $input_data = substr($input_data,0,-1);
        }
        $parsed = explode(';',$_db->real_escape_string($input_data));
        foreach ($parsed as $name) {
            $sql = 'SELECT idtb_Groups FROM tb_groups WHERE Group_Name ="'.$name.'"';
            $res = $_db->query($sql);
            if($res->num_rows == 0) {
                $sql = 'INSERT INTO tb_groups (Group_Name) VALUES ("'.$name.'")';
                $_db->query($sql);
                $val.= $_db->insert_id.';';
            }
            else{
                $arr = $res->fetch_row();
                $val.= $arr[0].';';
            }
        }
        return $val;
    }
    static function output($output_data,$_db){
        $_db->query("SET names utf8mb4");
        //$output_data is query output.
        //$_db is mysqli object.
        //return string which consists of string.
        $val = '';
        if(substr($output_data,-1)==';'){
            $output_data = substr($output_data,0,-1);
        }
        $parsed = explode(';',$output_data);
        foreach ($parsed as $num) {
            $sql = 'SELECT Group_Name FROM tb_groups WHERE idtb_Groups ='.(integer)$num;
            $res = $_db->query($sql);
            $arr = $res->fetch_row();
            $val.= $arr[0].';';
        }
        return $val;
    }
}
