<?php

    require_once('config.php');
    header("Content-type: application/json; charset=utf-8");
    class API{
        private $ramais;
        private $filas;
        private $conn;

        private function lerRamais($dados)
        {
            foreach($dados as $linhas){
                $linha = array_filter(explode(' ',$linhas));
                $arr = array_values($linha);
                if(isset($arr[1]) && trim($arr[1]) == '(Unspecified)' AND isset($arr[4]) && trim($arr[4]) == 'UNKNOWN')
                {        
                    list($name,$username) = explode('/',$arr[0]);        
                    $this->ramais[$name] = array('nome' => $name, 'ramal' => $username, 'ip' => 'Unspecified', 'online'=> 0, 'status' => 'UNKNOWN');
                }
                if (isset($arr[5]) AND trim( $arr[5] ) == "OK")
                {        
                    list($name,$username) = explode('/',$arr[0]);
                    $this->ramais[$name] = array('nome' => $name, 'ramal' => $username, 'ip' => trim($arr[1]), 'online' => 1, 'status' => 'OK'); 
                }
            }
        }

        private function lerFilas($dados)
        { 
            $status = '';
            foreach($dados as $linhas){
                if(strstr($linhas,'SIP/')){
                    if(strstr($linhas,'(Ring)')){
                        $linha = explode(' ', trim($linhas));
                        list($tech,$ramal) = explode('/',$linha[0]);
                        $status = 'chamando';
                    }
                    if(strstr($linhas,'(In use)')){            
                        $linha = explode(' ', trim($linhas));
                        list($tech,$ramal) = explode('/',$linha[0]);
                        $status = 'ocupado';    
                    }
                    if(strstr($linhas,'(Not in use)')){
                        $linha = explode(' ', trim($linhas));
                        list($tech,$ramal)  = explode('/',$linha[0]);
                        $status = 'disponivel';    
                    }
                    if(strstr($linhas,'(Unavailable)')){
                        $linha = explode(' ', trim($linhas));
                        list($tech,$ramal)  = explode('/',$linha[0]);
                        $status = 'indisponivel';    
                    }
                    if(strstr($linhas,'(paused)')){
                        $linha = explode(' ', trim($linhas));
                        list($tech,$ramal)  = explode('/',$linha[0]);
                        $status = 'pausado';    
                    }
                    $this->filas[$ramal] = array('nome' => $ramal, 'agente'=> $linha[count($linha)-1], 'status' => $status); 
                }
            }
        }

        private function consultarFilas()
        {
            $query = "";
            foreach($this->filas as $key => $value)
            {
                $query = "SELECT agente, status, nome FROM callcenter WHERE nome=?";
                $stmt = $this->conn->prepare($query);
            
                $stmt->bind_param("s", $value["nome"]);
                $stmt->execute();
                $resp = $stmt->get_result();
                if ($resp <> NULL AND $resp->num_rows > 0)
                {
        
                    $query = "UPDATE callcenter SET agente=?, status=? WHERE nome=?";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bind_param("sss", $value["agente"], $value["status"], $value["nome"]);
                }
                else
                {
                    $query = "INSERT INTO callcenter (agente, status, nome) VALUES (?, ?, ?)";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bind_param("sss", $value["agente"], $value["status"], $value["nome"]);
                }
                $stmt->execute();
                $stmt->close();
            }
        }

        private function consultarRamais()
        {
            $query = "";
            foreach($this->ramais as $key => $value)
            {
                $query = "SELECT nome, ramal, on_line FROM ramais WHERE nome=?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("s", $value["nome"]);
                $stmt->execute();
                $resp = $stmt->get_result();
                if ($resp <> NULL AND $resp->num_rows > 0)
                {
                    $query = "UPDATE ramais SET ramal=?, on_line=?, ip=?, status=? WHERE nome=?";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bind_param("sisss", $value["ramal"], $value["online"], $value["ip"], $value["status"], $value["nome"]);
                }
                else
                {
                    $query = "INSERT INTO ramais (ramal, on_line, nome, ip, status) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bind_param("sisss", $value["ramal"], $value["online"], $value["nome"], $value["ip"], $value["status"]);
                }
                $stmt->execute();
            }
            $stmt->close();
        }

        public function resultado()
        {
            $arr = array();
            $query = "SELECT ramais.nome, ramais.ramal, ramais.on_line, callcenter.agente, callcenter.status 
            FROM ramais INNER JOIN callcenter ON ramais.nome = callcenter.nome WHERE ramais.nome = ? ";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $nome);
            foreach($this->ramais as $key => $value)
            {
                $nome = $value["nome"];
                $stmt->execute();
                $resp = $stmt->get_result();
                if ($resp <> NULL AND $resp->num_rows > 0)
                {
                    while($linha = $resp->fetch_assoc())
                       $arr[$linha["nome"]] = $linha;
                }
            }
            $stmt->close();
            return $arr;
        }

        //htmlspecialchars()
        //filter_input()
        //mysqli_real_escape_string()

        function __construct()
        {
            $this->lerFilas(file('filas'));
            $this->lerRamais(file('ramais')); 
            $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
            if ($conn->connect_error)
            {
                die("Falha de conexão: " . $conn->connect_error);
            }
            $this->conn = $conn;
            $this->consultarRamais();
            $this->consultarFilas();
        }

        function __destruct()
        {
           $this->conn->close();
        }
    }

    echo json_encode((new API())->resultado());
?>