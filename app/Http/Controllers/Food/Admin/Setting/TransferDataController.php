<?php

namespace App\Http\Controllers\Food\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransferDataController extends Controller
{
     public function table_name(){

        // $db_config = Config::get('database.connections.'.Config::get('database.default'));
        // dd($db_config);

        

        // $host = "localhost";
        // $username = "root";
        // $password = "";

        $host = config('values.db_host_name');
        $username = config('values.db_user_name');
        $password = config('values.db_password');

        $source_db = Request('source_db');
        $destination_db = Request('destination_db');

        $source_db_table_name = "show tables from $source_db";
        $destination_db_table_name = "show tables from $destination_db";

        $connection_link = mysqli_connect($host, $username, $password);
        
        if ($connection_link === false) { 
            return response()->json('ERROR: Not connected' ,200);

        } 

        $source_table_result = $connection_link->query($source_db_table_name);
        $destination_table_result = $connection_link->query($destination_db_table_name);
            

        $source_db_table_name_list = [];
        $destination_db_table_name_list = [];

        while($table = mysqli_fetch_array($source_table_result)) { 
            array_push($source_db_table_name_list, $table[0]); 
        }

        while($table = mysqli_fetch_array($destination_table_result)) { 
            array_push($destination_db_table_name_list, $table[0]); 
        }


        // get  table name 
        if(count($source_db_table_name_list) > 0){

            return response()->json(['source_db_table_name_list'=>$source_db_table_name_list, 'destination_db_table_name_list'=>$destination_db_table_name_list ,200]);
            
        }else{
            return response()->json('Error' ,200);
        } 
        
        // Close the  connection 
        $connection_link->close(); 
    }




    public function table_transfer(){

        //dd(Request('source_table'), Request('destination_table'));
        $host = "localhost";
        $username = "root";
        $password = "";
        $source_db = Request('source_db');
        $destination_db = Request('destination_db');
        $destination_table = Request('destination_table');
        $source_table = Request('source_table');

        $sql_query = "INSERT INTO `$destination_db`.`$destination_table` SELECT * FROM `$source_db`.`$source_table`";
        $truncate_query = "TRUNCATE TABLE $destination_db.$destination_table";

        $connection_link = mysqli_connect($host, $username, $password);
        
        if ($connection_link === false) { 
            die("ERROR: Not connected. ".$connection_link->connect_error); 
        } 

        // truncate 
        if($connection_link->query($truncate_query) === true){
            if ($connection_link->query($sql_query) === true){
                return response()->json(['title'=>'Success', 'msg'=>'Data Transfered Successfully','icon'=>'success' ,200]);
            } 
            else{
                return response()->json(['title'=>'Warning', 'msg'=>'Error', 'icon'=>'warning' ,200]);
            } 
        }else{
            return response()->json(['title'=>'Warning', 'msg'=>'Error', 'icon'=>'warning' ,200]);
        } 
        
        // Close the  connection 
        $connection_link->close(); 
    }
}
