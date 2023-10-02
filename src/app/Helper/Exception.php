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

            //print_r($e); exit;

            switch ($statusCode) {
                case 400:
                    return json_encode(['success' => 'false', 'result' => 'no such command']);
                break;

                case 404:
                    return json_encode(['success' => 'false', 'result' => 'not found']);
                break;
                
                case 500:
                    return json_encode(['success' => 'false', 'result' => 'internal error']);
                break;

                default:
                    throw new \Exception($statusCode, 404);
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
                    throw new \Exception($errId, 404);
                break;
            }
        
        } catch (\Exception $e) {
            throw new \Exception("erro", 404);
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