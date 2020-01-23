<?php 

    class DbOperations{

        private $con; 

        function __construct(){
            require_once dirname(__FILE__) . '/DbConnect.php';
            $db = new DbConnect; 
            $this->con = $db->connect(); 
        }
        
        public function cariUser($nama){
            $stmt = $this->con->prepare("SELECT * FROM mahasiswa where nama LIKE '%$nama%' ORDER BY nama ASC");
            $stmt->execute();
            $stmt->bind_result($nama,$jurusan,$alamat);
            $users = array();
            while($stmt->fetch()){
                $user = array();
                $user['nama'] = $nama; 
                $user['jurusan']=$jurusan; 
                $user['alamat'] = $alamat; 
                array_push($users, $user);
            }             
            return $users; 
        }

        public function createUser($nama , $jurusan, $alamat){
                $stmt = $this->con->prepare("INSERT INTO mahasiswa (nama, jurusan, alamat) VALUES (?, ?, ?) ");
                $stmt->bind_param("sss", $nama , $jurusan, $alamat);
                if($stmt->execute()){
                    return USER_CREATED;
                }else{
                    return USER_FAILURE;
                }
                return USER_EXISTS; 
        }
        
        public function getAllUsers(){
            $stmt = $this->con->prepare("SELECT nama, jurusan, alamat FROM mahasiswa;");
            $stmt->execute(); 
            $stmt->bind_result($nama, $jurusan, $alamat);
            $users = array(); 
            while($stmt->fetch()){ 
                $user = array(); 
                $user['nama'] = $nama; 
                $user['jurusan']=$jurusan; 
                $user['alamat'] = $alamat; 
                array_push($users, $user);
            }             
            return $users; 
        }
        
        public function updateUser($nama, $jurusan, $alamat){
            $stmt = $this->con->prepare("UPDATE mahasiswa SET jurusan = ?, alamat = ? WHERE nama = ?");
            $stmt->bind_param("sss", $jurusan, $alamat, $nama);
            
            if($stmt->execute())
                return true; 
            return false; 
        }
        
        private function isNamaExist($nama){
            $stmt = $this->con->prepare("SELECT id FROM mahasiswa WHERE nama = ?");
            $stmt->bind_param("s", $nama);
            $stmt->execute(); 
            $stmt->store_result(); 
            return $stmt->num_rows > 0;  
        }

    
    }