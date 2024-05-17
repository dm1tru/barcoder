<?php

namespace Dm1tru\Barcoder\Infrastructure;

use Dm1tru\Barcoder\Domain\Entity\Barcode;
use Dm1tru\Barcoder\Domain\Entity\Device;
use Dm1tru\Barcoder\Domain\Repository\BarcodeRepositoryInterface;
use Dm1tru\Barcoder\Domain\Repository\DeviceRepositoryInterface;
use Dm1tru\Barcoder\Domain\ValueObject\Code;
use Dm1tru\Barcoder\Domain\ValueObject\Count;
use Dm1tru\Barcoder\Domain\ValueObject\Date;
use Dm1tru\Barcoder\Domain\ValueObject\Id;
use Dm1tru\Barcoder\Domain\ValueObject\Ip;
use Dm1tru\Barcoder\Domain\ValueObject\Name;
use Dm1tru\Barcoder\Domain\ValueObject\Order;
use PDO;
use PDOStatement;

class MysqlDeviceRepository implements DeviceRepositoryInterface
{
    private PDO $pdo;
    private PDOStatement $_qInsert;
    private PDOStatement $_qGetAll;
    private PDOStatement $_qGetById;
    private PDOStatement $_qDeleteById;
    private ?PDOStatement $_qUpdate;

    public function __construct()
    {
        $this->connect();

        $this->_qInsert = $this->pdo->prepare(
            "INSERT INTO devices 
            SET name= :name, host = :host, `order` = :order"
        );

        $this->_qGetAll = $this->pdo->prepare(
            "SELECT id, name, host, `order`
            FROM devices
            ORDER BY `order`, id"
        );

        $this->_qGetById = $this->pdo->prepare(
            "SELECT id, name, host, `order`
            FROM devices
            WHERE id = ?"
        );

        $this->_qDeleteById = $this->pdo->prepare(
            "DELETE FROM devices
            WHERE id = ?"
        );
    }

    private function connect(): void
    {
        $conf = parse_ini_file('config.ini', true);
        $dsn = "mysql:host=$conf[MYSQL_HOST];dbname=$conf[MYSQL_DATABASE]";
        $this->pdo = new \PDO($dsn, $conf['MYSQL_USER'], $conf['MYSQL_PASSWORD']);
    }

    public function add(Device $device): Id
    {
        $this->_qInsert->execute([
            'name' => $device->getName()->getName(),
            'host' => $device->getHost()->getIp(),
            'order' => $device->getOrder()->getOrder()
        ]);

        return new Id($this->pdo->lastInsertId());
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

    public function getById(Id $id): ?Device
    {
        $this->_qGetById->execute([$id->getId()]);
        $ret = $this->_qGetById->fetch(PDO::FETCH_ASSOC);
        if (!$ret) {
            return null;
        }
        return new Device(
            new Id($ret['id']),
            new Name($ret['name']),
            new Ip($ret['host']),
            new Order($ret['order'])
        );
    }

    public function update(id $id, ?Name $name, ?Ip $ip, ?Order $order): Device
    {
        $sql = "UPDATE devices
            SET {fields}
            WHERE id = :id";

        $update_fields = [];
        if ($name) {
            $update_fields[] = "name = :name";
        }

        if ($ip) {
            $update_fields[] = "host = :host";
        }

        if ($order) {
            $update_fields[] = "`order` = :order";
        }

        $sql = str_replace('{fields}', implode(', ', $update_fields), $sql);


        $this->_qUpdate = $this->pdo->prepare($sql);

        $params = [
            'id' => $id->getId()
        ];
        if ($name) {
            $params['name'] = $name->getName();
        }

        if ($ip) {
            $params['host'] = $ip->getIp();
        }

        if ($order) {
            $params['order'] = $order->getOrder();
        }

        $this->_qUpdate->execute($params);
        return $this->getById($id);
    }

    public function deleteById(Id $id): void
    {
        $this->_qDeleteById->execute([$id->getId()]);
    }
}
