<?php
require 'connect.php';

class crud {
    public function create($table, $data){
        global $conn;

        $placeholders = '';
        $collumns     = '';
        foreach ($data as $key => $value) {
            $placeholders .= ":$key,";
            $collumns     .= "$key,";
        }
        $placeholders = substr_replace($placeholders, '', -1);
        $collumns     = substr_replace($collumns, '', -1);
    
        $query = $conn->prepare(
            "INSERT INTO $table ($collumns) VALUES($placeholders)"
        );
        try {
            $query->execute($data);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
    public function read($table, $collumns='*', $where='', $where_value='', $sortvar='') {
        global $conn;

        if ($collumns == 'all') {
            $collumns = '*';
        }

        $filter = '';
        if (!empty($where) and !empty($where_value)) {
            if ($where > 1){
                $y = '';
                for ($x=0; $x<count($where); $x++) {
                    $y .= "$where[$x]=$where_value[$x] AND ";
                } 
                $y = substr_replace($y, '', -5);
                $filter = "WHERE $y";

            } else {
                $filter = "WHERE $where=$where_value";
            }
        }   

        $sort = '';
        if (!empty($sortvar)) {
            $sort = "ORDER BY $sortvar";
        }

        $query = $conn->prepare("SELECT $collumns FROM $table $filter $sort");
        // print_r($query);     
        $query->execute();

        $data = array();
        if ($query->rowCount()) {    
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[]=$row;
            }
            return $data;
        } else {
            return false;
        }

    }
    public function update($data, $table, $where, $where_value) {
        global $conn;

        $update_set = '';
        foreach ($data as $key => $value) {
            $update_set .= "$key=:$key,";
        }
        $update_set = substr_replace($update_set, '', -1); 
        
        $query = $conn->prepare(
            "UPDATE $table SET $update_set WHERE $where=$where_value"
        );
        // print_r($query);

        try {
            $query->execute($data);
            // print_r($query);
            return true;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            return false;
        }


    }
    public function delete($table, $where, $where_value) {
        global $conn;

        $query = $conn->prepare("DELETE FROM $table WHERE $where=$where_value");
        try {
            $query->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    public function get($table, $collumns, $where, $where_value) {
        global $conn;

        if ($collumns == 'all') {
            $collumns = '*';
        }

        $filter = '';
        if (!empty($where) and !empty($where_value)) {
            if ($where > 1){
                $y = '';
                for ($x=0; $x<count($where); $x++) {
                    $y .= "$where[$x]=$where_value[$x] AND ";
                } 
                $y = substr_replace($y, '', -5);
                $filter = "WHERE $y";

            } else {
                $filter = "WHERE $where=$where_value";
            }
        }

        // echo "SELECT $collumns FROM $table $filter"; 
        $query = $conn->prepare(
            "SELECT $collumns FROM $table $filter"
        );
        // print_r($query);
        $query->execute();
        
        if ($query->rowCount()) {
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result;
        } else {
            return false;
        }
    }

    /** 
     * @param collumns string
     * @param tables array
     * @param join_col array
     * @param join_col_val array
     * @param where string or array
     * @param where_value string or array
     */
    public function join(
        $collumns, 
        $tables, 
        $join_col, 
        $join_col_val, 
        $where='', 
        $where_value=''
    ) {
        global $conn; 

        $join_tables = array();
        $a           = 0;
        for ($x=1; $x < count($tables); $x++) {
            $join_tables[$a] = "JOIN $tables[$x]";
            $a++;
        }

        $joined_cols = array();
        for ($y=0; $y < count($join_col); $y++) {
            $joined_cols[$y] = "ON $join_col[$y]=$join_col_val[$y]";
        }
        
        $join_stmt = '';
        for ($z=0; $z < count($join_tables); $z++) {
            $join_stmt .= " $join_tables[$z] $joined_cols[$z]";
        }

        $where_stmt = '';
        if (!empty($where) and !empty($where_value)) {
            $where_cols = '';
            for ($x=0; $x<count($where); $x++) {
                $where_cols .= "$where[$x]=$where_value[$x] AND ";
            } 
            $where_cols = substr_replace($where_cols, '', -5);
            $where_stmt = "WHERE $where_cols";
        }

        $query = $conn->prepare(
            "SELECT $collumns FROM $tables[0] $join_stmt $where_stmt"
        );
        $query->execute();

        $data = array();
        if ($query->rowCount()) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }
}
?>