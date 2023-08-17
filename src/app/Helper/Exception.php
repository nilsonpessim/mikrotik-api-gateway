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

            /*print_r([
                $host,
                $auth,
                $method,
                $data
            ]);

            exit;
            */
            
            $response = (new Client($auth))->request($method, $host, $data);
        
        } catch (ClientException | ServerException $e) {

            if ($e->hasResponse()) {
               $statusCode = $e->getResponse()->getStatusCode();
            }

            switch ($statusCode) {
                case 400:
                    return json_encode(["error" => "no such command"]);
                break;

                case 404:
                    return json_encode(["error" => "not found"]);
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
                    //throw new \Exception("failed to connect to router", 404);
                    return json_encode(["error" => "failed to connect to router"]);
                break;
                
                default:
                    throw new \Exception($errId, 404);
                break;
            }
        
        } catch (\Exception $e) {
            throw new \Exception("erro", 404);
        }

        // RETORNA O RESPONSE
        return $response->getBody();

    }

}