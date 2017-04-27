<?php
// Grzegorz Mrózek, Rafał Schmidt 2014-2015
include_once 'static.php';

class Utility
{
	public static function RemoveUTF8($UTF8string) 
	{
		$ASCIIstring = str_replace( 
			array('ą','Ą','ć','Ć','ę','Ę','ł','Ł','ń','Ń','ó','Ó','ś','Ś','ź','Ź','ż','Ż') , 
			array('a','A','c','C','e','E','l','L','n','N','o','O','s','S','z','Z','z','Z'), $UTF8string);
		//Zmiana UTF8 na ASCII 
		return $ASCIIstring;
	}
	public static function HTMLDir($ASCIIstring)
	{
		$HTMLstring = date('dmyhis');
		$HTMLstring .= str_replace( array('@','/','\\','#','!','$','%','^','&','*','(',')','-','=','+','[',']','{','}','>','<','?','~','`',"'",'"',':',';','|',',','.',' ') , '', $ASCIIstring);
		return $HTMLstring;
	}
	public static function VarDecode($variable) 
	{
		//Usuwa zbędne / jeśli są (PHP < 4.4) i dekoduje z jsona do php
		if(get_magic_quotes_gpc())
		{
			$variable = stripslashes($variable);
		}
		return json_decode($variable, true);
	}
	public static function Delete($path)
	{
		if (is_dir($path) === true)
		{
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);
			foreach ($files as $file)
			{
				if (in_array($file->getBasename(), array('.', '..')) !== true)
				{
					if ($file->isDir() === true)
					{
						rmdir($file->getPathName());
					}
					else if (($file->isFile() === true) || ($file->isLink() === true))
					{
						unlink($file->getPathname());
					}
				}
			}
        return rmdir($path);
		}
		else if ((is_file($path) === true) || (is_link($path) === true))
		{
			return unlink($path);
		}
    return false;
	}
}

class FileInfo
{
	public static $PathToHtml;
	public static $ServerPath = "http://localhost/quizy/";
	public static $HtmlName;
}

if(isset($_POST) && sizeof($_POST) > 0) 
{
	$Quiz = $_POST;
	$Questions;
	for ( $i = 0; $i < count($Quiz["questions"]); $i++ ) {
		$Questions[$i] = json_decode($Quiz["questions"][$i], true);
	}
	$Quiz["questions"] = $Questions;
	FileInfo::$HtmlName = Utility::HTMLDir(Utility::RemoveUTF8($Quiz['quizTitle']));
	$HtmlDIR = FileInfo::$HtmlName;
	if (!mkdir("../quizy/".$HtmlDIR, 0777)) {
		echo 'Problem wewnęrzny serwera... Spróbuj później.';
		exit();
	} else { 
	//	echo "Zrobilem katalog!"; 
	}
	
	if(isset($_FILES) && (sizeof($_FILES) > 0)) {
		for($i = 0; $i < count($_FILES["images"]["name"]); $i++) {
			//var_dump( $_FILES["images"]["name"][$i] );
			$name = $_FILES["images"]["name"][$i];
			if (move_uploaded_file($_FILES["images"]["tmp_name"][$i],  "../quizy/".$HtmlDIR."/".$name)) {
				//echo "Stored in: " ."../quizy/$HtmlDIR".$_FILES["images"]["name"][$i];
			} else {
				//echo "Move upload seems to be struct !" ;
			}
		}
	}
	
	if(!file_exists("../quizy/$HtmlDIR/index.html")) 
	{
		$HtmlFile = fopen("../quizy/$HtmlDIR/index.html", "w+");
		$Html = $topHtml."<div id='topic'>".$Quiz['quizTitle']."</div>";
		foreach($Questions as $QuestionIndex=>$Question) 
		{
			$QuestionIndex++;
			$Html .= "<section><div class='question'>".$Question['title']."</div>";
			if($Question['type'] == "single") $Question['type'] = true; else $Question['type'] = false;
			foreach($Question['answers'] as $Index=>$Answer) 
			{
				$Index++;
				$Html .= "<label class='answer_name' for='answer_".$QuestionIndex."_".$Index."'><input class='answer_radio' type='".($Question['type'] ? "radio'" : "checkbox'")." id='answer_".$QuestionIndex."_".$Index."' name='answer_".$QuestionIndex."'>".$Answer['value']."</label>";
			}
			$Html .= "</section>";
		}
		$Html .= EndHtml($Quiz);
		fwrite($HtmlFile, $Html);
		fclose($HtmlFile);
		FileInfo::$PathToHtml = FileInfo::$ServerPath.$HtmlDIR.".zip";
		$path = '../quizy/'.$HtmlDIR;
		$rootPath = realpath($path);
		$zip = new ZipArchive();
		$zip->open('../quizy/'.$HtmlDIR.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
		$files = new RecursiveIteratorIterator(	new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);
		foreach ($files as $name => $file)
		{
			if (!$file->isDir())
			{
				$filePath = $file->getRealPath();
				$relativePath = substr($filePath, strlen($rootPath) + 1);
				$zip->addFile($filePath, $relativePath);
			}
		}
		$zip->close();
		Utility::Delete("$path");
		echo "<a href='".FileInfo::$PathToHtml."'>POBIERZ</a>";
	} else {
		echo "<div class='error'>To praktycznie niemożliwe... spróbuj jeszcze raz!</div>";
		exit();
	} 
} else {
	echo("<div class='error'>Error 696! Strona nie przekazała pytań!</div>");
	exit();
}

?>