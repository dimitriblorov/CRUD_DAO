<?php

// Implementação do UsuarioDAO para MYSQL
require_once 'models/Usuario.php';

class UsuarioDaoMysql implements UsuarioDAO {
    private $pdo;
    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

    public function add(Usuario $u) {
        $sql = $this->pdo->prepare("INSERT INTO tabela (nome, email) VALUES (:nome, :email)");
        $sql->bindValue(':nome', $u->getNome());
        $sql->bindValue(':email', $u->getEmail());
        $sql->execute();

        $u->setId( $this->pdo->lastInsertId() );
        return $u;
    }
    
    public function findAll() {
        $array = [];
        // Pega todos os usuários do banco de dados
        $sql = $this->pdo->query("SELECT * FROM tabela");
        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll();

            // transforma em objetos do tipo Usuario e retorna em objetos
            foreach($data as $item) {
                $u = new Usuario();
                $u->setId($item['id']);
                $u->setNome($item['nome']);
                $u->setEmail($item['email']);

                $array[] = $u;
            }
        }
        return $array;

    }
    public function findByEmail($email) {
        $sql = $this->pdo->prepare("SELECT * FROM tabela WHERE email = :email");
        $sql->bindValue(':email', $email);
        $sql->execute();
        if($sql->rowCount() > 0) {
            $data = $sql->fetch();

            $u = new Usuario();
            $u->setId($data['id']);
            $u->setNome($data['nome']);
            $u->setEmail($data['email']);

            return $u;
        } else {
            return false;
        }
    }
    public function findById($id) {
        $sql = $this->pdo->prepare("SELECT * FROM tabela WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();
        if($sql->rowCount() > 0) {
            $data = $sql->fetch();

            $u = new Usuario();
            $u->setId($data['id']);
            $u->setNome($data['nome']);
            $u->setEmail($data['email']);

            return $u;
        } else {
            return false;
        }

    }
    public function update(Usuario $u) {
        $sql = $this->pdo->prepare("UPDATE tabela SET nome = :nome, email = :email WHERE id = :id");
        $sql->bindValue(':nome', $u->getNome());
        $sql->bindValue(':email', $u->getEmail());
        $sql->bindValue(':id', $u->getId());
        $sql->execute();

        return true;
    }
    public function delete($id) {
        $sql = $this->pdo->prepare("DELETE FROM tabela WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();

    }
}