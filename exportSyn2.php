<?php

/*******EDIT LINES 3-8*******/
$DB_Server = "localhost"; //MySQL Server
$DB_Username = "root"; //MySQL Username
$DB_Password = "";             //MySQL Password
$DB_DBName = "name_and_synonym";         //MySQL Database Name
$DB_TBLName = "synonyms"; //MySQL Table Name
$filename = "synonyms";         //File Name
/*******YOU DO NOT NEED TO EDIT ANYTHING BELOW THIS LINE*******/
//create MySQL connection
$sql = "Select * from $DB_TBLName";
$Connect = @mysql_connect($DB_Server, $DB_Username, $DB_Password) or die("Couldn't connect to MySQL:<br>" . mysql_error() . "<br>" . mysql_errno());
//select database
$Db = @mysql_select_db($DB_DBName, $Connect) or die("Couldn't select database:<br>" . mysql_error(). "<br>" . mysql_errno());
//execute query
$result = @mysql_query($sql,$Connect) or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());
$file_ending = "xls";
//header info for browser
header("Content-Type: application/xls; charset=utf-8");
header("Content-Disposition: attachment; filename=$filename.xls");

header("Pragma: no-cache");
header("Expires: 0");
require_once 'IndicTextAnalyzer/word_processor.php';
$wp = new wordProcessor("", "Telugu");
/*******Start of Formatting for Excel*******/
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character
//start of printing column names as names of MySQL fields
for ($i = 0; $i < mysql_num_fields($result); $i++) {
echo mysql_field_name($result,$i) . "\t";
}
print("\n");
//end of printing column names
//start while loop to get data
    while($row = mysql_fetch_row($result))
    {
        $schema_insert = "";
        for($j=0; $j<mysql_num_fields($result);$j++)
        {
            if(!isset($row[$j]))
                $schema_insert .= "NULL".$sep;
            elseif ($row[$j] != "")
            {
            	$wp->setWord($row[$j], "Telugu");
            	
                $schema_insert .= $wp->getWord() .$sep;
            }
            else
            {
            	
                $schema_insert .= "".$sep;
            }
        }
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
        $schema_insert .= "\t";
        print(trim($schema_insert));
        print "\n";
    }
?>
