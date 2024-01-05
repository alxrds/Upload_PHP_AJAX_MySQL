<?php 

include 'config/dbConfig.php';

$uploadDir = 'uploads/'; 
$response = [];

if($_POST){

    $uploadStatus = true; 
    $name = filter_input(INPUT_POST, $_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, $_POST['email'], FILTER_SANITIZE_EMAIL);
    $filesArr = $_FILES['files']; 
    $fileNames = array_filter($filesArr['name']); 
    $uploadedFile = ''; 

    if(!empty($fileNames)){  

        foreach($filesArr['name'] as $key=>$val){  

            $fileName = basename($filesArr['name'][$key]);  
            $ext = ltrim(substr($fileName, strrpos($fileName, '.' )), '.' );
            $targetFilePath = $uploadDir.md5($fileName.date('d-m-Y H:i:s')).'.'.$ext;  
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);  

            if(move_uploaded_file($filesArr["tmp_name"][$key], $targetFilePath)){  
                $uploadedFile .= $fileName.','; 
            }else{  
                $uploadStatus = false; 
                $response['message'] = 'Desculpe, ocorreu um erro!'; 
            }  

        }
    } 
        
    if($uploadStatus){ 

        $uploadedFileStr = trim($uploadedFile, ','); 
        $insert = $db->query("INSERT INTO archives (name, email, file_names) VALUES ('".$name."', '".$email."', '".$uploadedFileStr."')"); 
        
        if($insert){ 
            $response['status'] = 1; 
            $response['message'] = 'Arquivos enviados com sucesso!'; 
        } 

    } 

}

echo json_encode($response);