<?php 

namespace App\Helper;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;

class Exception{

    public function getException($host, $auth, $method = "GET", $data = [])
    {   
        try {
            
            $response = (new Client($auth))->request($method, $host, $data);
        
        } catch (ClientException | ServerException $e) {

            if ($e->hasResponse()) {
               $statusCode = $e->getResponse()->getStatusCode();
            }

            switch ($statusCode) {
                case 400:
                    return json_encode(['success' => 'false', 'result' => 'no such command']);
                break;

                case 401:
                    return json_encode(['success' => 'false', 'result' => 'unauthorized']);
                break;

                case 404:
                    return json_encode(['success' => 'false', 'result' => 'not found']);
                break;
                
                case 500:
                    return json_encode(['success' => 'false', 'result' => 'internal error']);
                break;

                default:
                    return json_encode(['success' => 'false', 'result' => "Error $statusCode - Undocumented"]);
                    //throw new \Exception($statusCode, 404);
                break;
            }
        
        } catch (ConnectException $e) {

            $handlerContext = $e->getHandlerContext();
            if ($handlerContext['errno'] ?? 0) {
                $errId = (int)($handlerContext['errno']);
            }

            switch ($errId) {
                case 7:
                    return json_encode(['success' => 'false', 'result' => 'failed to connect to router']);
                break;

                case 28:
                    return json_encode(['success' => 'false', 'result' => 'failed to connect to router']);
                break;
                
                default:
                    return json_encode(['success' => 'false', 'result' => "Error $errId - Undocumented"]);
                    //throw new \Exception($errId, 404);
                break;
            }
        
        } catch (\Exception $e) {
            return json_encode(['success' => 'false', 'result' => "Error"]);
            //throw new \Exception("erro", 404);
        }

        // RETORNA O RESPONSE
        return json_encode(
            [
                'success' => 'true', 
                'result' => json_decode($response->getBody())
            ]
        );

    }

}