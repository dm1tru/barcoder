<?php

namespace Dm1tru\Barcoder\Infrastructure;


use Dm1tru\Barcoder\Domain\Entity\User;
use Dm1tru\Barcoder\Domain\Repository\UserRepositoryInterface;
use Dm1tru\Barcoder\Domain\ValueObject\Id;
use Dm1tru\Barcoder\Domain\ValueObject\Name;

use Dm1tru\Barcoder\Domain\ValueObject\Token;
use PDO;
use PDOStatement;

class MysqlUserRepository implements UserRepositoryInterface
{
    private PDO $pdo;
    private PDOStatement $_qInsert;
    private PDOStatement $_qGetAll;
    private PDOStatement $_qGetByToken;

    public function __construct()
    {
        $this->connect();

        $this->_qInsert = $this->pdo->prepare(
            "INSERT INTO devices 
            SET name= :name, host = :host, order = :order"
        );

        $this->_qGetAll = $this->pdo->prepare(
            "SELECT id, name, host, `order`
            FROM devices
            ORDER BY `order`, id"
        );

        $this->_qGetByToken = $this->pdo->prepare(
            "SELECT id, name, token
            FROM `users`
            WHERE token = ?
            ORDER BY id LIMIT 1"
        );
    }

    private function connect(): void
    {
        $conf = parse_ini_file('config.ini', true);
        $dsn = "mysql:host=$conf[MYSQL_HOST];dbname=$conf[MYSQL_DATABASE]";
        $this->pdo = new \PDO($dsn, $conf['MYSQL_USER'], $conf['MYSQL_PASSWORD']);
    }

    public function add(User $user): Id
    {
        /*
        $this->_qInsert->execute([
            'name' => $device->getName()->getName(),
            'count' => $device->getCount()->getCount(),
            'host' => $device->getHost()->getIp(),
            'order' => $device->getOrder()->getOrder()
        ]);

        return new Id($this->pdo->lastInsertId());
        */
    }

    public function getAll(): array
    {
        $devices = [];

        $this->_qGetAll->execute();
        while ($ret = $this->_qGetAll->fetch(PDO::FETCH_ASSOC)) {
            $devices[] = new Device(
                new Id($ret['id']),
                new Name($ret['name']),
                new Ip($ret['host']),
                new Order($ret['order'])
            );
        }

        return $devices;
    }

    public function getById(Id $id): ?User
    {
        return new User();
    }

    public function getByToken(Token $token): ?User
    {

        $this->_qGetByToken->execute([$token->getToken()]);
        $ret = $this->_qGetByToken->fetch(PDO::FETCH_ASSOC);
        if (!$ret) {
            return null;
        }

        return new User(new Id($ret['id']), new Name($ret['name']), new Token($ret['token']));
    }
}
