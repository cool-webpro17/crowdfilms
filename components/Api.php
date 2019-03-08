<?php

namespace app\components;
 
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
 
class Api extends Component
{
    public $publicRoutes = [
       'user/team' => ['GET'], 'user/reset_password' => ['POST'], 'user/index' => ['POST']

    ];

    public function handleRequest()
    {
        error_reporting(E_ALL ^ E_NOTICE);
        $requestData = file_get_contents('php://input');
        $json = json_decode($requestData, true);
        return $json;
    }

    public function processRequest( $requestData)
    {
       
        $request = Yii::$app->request;
      
       if($request->getIsPost())
       { 
           return $this->handlePost('create',['json' => $requestData]);
       } 
       elseif($request->getIsPut()) 
       { 
           return $this->handlePut('edit',['json' => $requestData]);
       } 
       elseif($request->getIsGet()) 
       { 
           return ['action'=> "viewRecord"];
       }
       elseif($request->getIsDelete()) 
       { 
           return $this->handleDelete('delete', ['json' => $requestData]);
       }
    }
    
    public function handlePost($action, $data = array()){
       
        $json = file_get_contents('php://input');
        
        if(empty($json)){
            $json = $data['json'];
        }
       
        $obj = json_decode($json, true);

        if(!$obj){
             $obj = Yii::$app->api->parseFormData($json);
        }

        return ['action'=> "createRecord", 'object' => $obj];
    }
    
    public function handlePut($action, $data = array()){
        $json = file_get_contents('php://input');
        if(empty($json)){
            $json = $data['json'];
        }
            
        $obj = json_decode($json, true);

        if(!$obj){
             $obj = Yii::$app->api->parseFormData($json);
        }
       
        return ['action'=> "updateRecord", 'object'=>$obj];
    }
    
    public function handleDelete( $action, $data = array()){
        $json = file_get_contents('php://input');
        if(empty($json)){
            $json = $data['json'];
        }
           
        $obj = json_decode($json, true);
       
        return ['action'=> "deleteRecord", 'object'=>$obj];
    }

    public function _sendCustomResponse($status = 200, $data = '', $success = true, $return = false)
    {
        $content_type = 'application/json';
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        header($status_header);
        header('Content-type: ' . $content_type);
        echo json_encode($data);
        die();
        Yii::$app->end();
    }
    
    public function _sendResponse($status = 200, $data = '', $success = true, $return = false)
    {
        $content_type = 'application/json';
                
        if($success)
        {
             $result = 'success';
             $message = 'ok';
        }
        else
        {
            $message = '';

            switch($status)
            {
                case 200:
                    $message = $data['error']? $data['error'] : "The server encountered an error processing your request.";
                    break;
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
                case 10000:
                    $message = 'Supplied data is not correct.';
                    break;
                case 10001:
                    $message = 'Data malformed.';
                    break;
                case 10002:
                    $message = 'Invalid role.';
                    break;
                case 10003:
                    $message = 'Invalid operation.';
                    break;
            }
                
            $result = 'error';
        }

        $arr = array('result' => $result, 'status' => $status, 'message' => $message);

        if(!empty($data)){
            $arr['data'] = $data;
        } 
        
        if($return){
            return $arr;
        } else {
            $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
            header($status_header);
            header('Content-type: ' . $content_type);
            echo json_encode($arr);
        }
        Yii::$app->end();
    }

    public  function processErrors($errors){
   
        $res = [];

        if(is_array($errors)){
             foreach($errors as $key=>$value){
                if(is_array($value))
                    $res[] = $value[0];
                else 
                    $res[] = $value;
            }
        }
        else
            $res[] = $errors;

        return $res;
    }

    private function _getStatusCodeMessage($status)
    {
        $codes = Array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }
    
    public function checkPublicActions(){
        $action = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        $pub = $this->publicRoutes;
        $publicRoutes = in_array($action, array_keys($pub));

        $method = $pub[$action] && in_array(Yii::$app->request-> method, $pub[$action]);

        return $publicRoutes && $method;
    }

    public static function parseFormData($raw_data){
        $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));

        if($boundary == null)
            $boundary = '---b---'; 
        $parts = array_slice(explode($boundary, $raw_data), 1);
        $data = array();

        foreach ($parts as $part) {
            // If this is the last part, break
            if ($part == "--\r\n") break; 

            // Separate content from headers
            $part = ltrim($part, "\r\n");
            list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);

            // Parse the headers list
            $raw_headers = explode("\r\n", $raw_headers);
            $headers = array();
            foreach ($raw_headers as $header) {
                list($name, $value) = explode(':', $header);
                $headers[strtolower($name)] = ltrim($value, ' '); 
            } 

            // Parse the Content-Disposition to get the field name, etc.
            if (isset($headers['content-disposition'])) {
                $filename = null;
                preg_match(
                    '/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/', 
                    $headers['content-disposition'], 
                    $matches
                );
                list(, $type, $name) = $matches;
                isset($matches[4]) and $filename = $matches[4]; 

                // handle your fields here
                switch ($name) {
                    // this is a file upload
                    case 'userfile':
                         file_put_contents($filename, $body);
                         break;

                    // default for all other files is to populate $data
                    default: 
                         $data[$name] = substr($body, 0, strlen($body) - 2);
                         break;
                } 
            }

        }

    return $data;
    }
    
    
}

