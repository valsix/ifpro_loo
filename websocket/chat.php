<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

    // Make sure composer dependencies have been installed
    require __DIR__ . '/vendor/autoload.php';

/**
 * chat.php
 * Send any incoming messages to all connected clients (except sender)
 */
class Chat implements MessageComponentInterface {
    
    protected $clients;

    public function __construct() {
        $this->clients = array();
    }

    public function onOpen(ConnectionInterface $conn) {
        parse_str($conn->httpRequest->getUri()->getQuery(), $queryParameters);
        $this->clients[$queryParameters['token']] = $conn;
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // $numRecv = count($this->clients) - 1;
        // echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
        //     , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        
        $arrParams = json_decode($msg);
        $pegawai_id_by    = $arrParams->PEGAWAI_ID_BY;
        $pegawai_id_to    = $arrParams->PEGAWAI_ID_TO;
        $pesan            = $arrParams->PESAN;
        // $TOKEN            = $arrParams->TOKEN;
        
        // if($TOKEN == md5("V4lsix1234"))
        // {}
        // else
        //  return;
        
        $db = pg_connect("host=localhost port=5433 dbname=asdp_office user=postgres password=root");
        
        list($usec, $sec) = explode(" ", microtime());
        $id = ((float)$usec + (float)$sec);

        $query = " INSERT INTO LIVE_CHAT(
                        LIVE_CHAT_ID, PEGAWAI_ID_BY, PEGAWAI_ID_TO, PESAN, TANGGAL
                    )
                    VALUES ('".$id."', '".$pegawai_id_by."', '".$pegawai_id_to."', '".$pesan."', CURRENT_TIMESTAMP)
                  ";
        $result = pg_query($query);

        $query = " SELECT LIVE_CHAT_ID, PEGAWAI_ID_BY, PEGAWAI_ID_TO, PESAN, 
                TO_CHAR(A.TANGGAL, 'Mon D') TANGGAL, TO_CHAR(A.TANGGAL, 'HH24:MI') JAM
                FROM LIVE_CHAT A WHERE LIVE_CHAT_ID = '".$id."'
                  ";
        $result = pg_query($query);
        $row = pg_fetch_row($result);

        $tanggal = $row[4];
        $jam = $row[5];
        
        if($this->clients[$pegawai_id_to]) {
            $data = '
                <div class="incoming_msg">
                    <div class="incoming_msg_img"> <i class="fa fa-user-circle" aria-hidden="true"></i> 
                    </div>
                    <div class="received_msg">
                        <div class="received_withd_msg">
                          <p>'.$pesan.'</p>
                          <span class="time_date"> '.$jam.'    |    '.$tanggal.'</span>
                        </div>
                    </div>
                </div>
            ';
            $this->clients[$pegawai_id_to]->send($data);
        }

        $data = '
            <div class="outgoing_msg">
                <div class="sent_msg">
                    <p>'.$pesan.'</p>
                    <span class="time_date"> '.$jam.'    |    '.$tanggal.'</span>
                </div>
            </div>
        ';
        $from->send($data);
    }

    public function onClose(ConnectionInterface $conn) {
        parse_str($conn->httpRequest->getUri()->getQuery(), $queryParameters);
        unset($this->clients[$queryParameters['token']]);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    public function sendAll(ConnectionInterface $from=null, $msg="", $all=true)
    {
        // foreach ($this->clients as $client) {
        //     if ($from !== $client) {
        //         $data = array(
        //             "class" => "alert-secondary",
        //             "msg" => $msg
        //         );
        //     } else {
        //         $data = array(
        //             "class" => "alert-success",
        //             "msg" => $msg
        //         );
        //     }
        //     if($all){
        //         $client->send((json_encode($data)));
        //     } else {
        //         if ($from !== $client) {
        //             // The sender is not the receiver, send to each client connected
        //             $client->send((json_encode($data)));
        //         }
        //     }
        // }
    }
}

    // Run the server application through the WebSocket protocol on port 8080

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Chat()
            )
        ),
        8089
    );
    $server->run();

